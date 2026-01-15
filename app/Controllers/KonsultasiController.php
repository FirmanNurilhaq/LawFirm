<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KonsultasiModel;

class KonsultasiController extends BaseController
{
    protected $konsultasiModel;

    public function __construct()
    {
        $this->konsultasiModel = new KonsultasiModel();
    }

    public function ajukan()
    {
        if (session()->get('role') != 'client') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Ajukan Konsultasi Hukum'
        ];
        return view('konsultasi/form_pengajuan', $data);
    }

    public function processAjukan()
    {
        if (!$this->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tipe_konsultasi' => 'required',
            'tanggal' => 'required',
            'dokumen' => [
                'rules' => 'uploaded[dokumen]|mime_in[dokumen,application/pdf]|max_size[dokumen,5120]',
                'errors' => [
                    'uploaded' => 'Harap upload dokumen pendukung.',
                    'mime_in' => 'Format file harus PDF.',
                    'max_size' => 'Ukuran file maksimal 5MB.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fileDokumen = $this->request->getFile('dokumen');
        $namaFile = '';

        if ($fileDokumen->isValid() && !$fileDokumen->hasMoved()) {
            $namaFile = $fileDokumen->getRandomName();
            $fileDokumen->move('uploads/berkas', $namaFile);
        }

        $this->konsultasiModel->save([
            'id_user' => session()->get('id_user'),
            'jenis_perkara' => $this->request->getPost('judul'),
            'deskripsi_masalah' => $this->request->getPost('deskripsi'),
            'dokumen_kelengkapan' => $namaFile,
            'tipe_konsultasi' => $this->request->getPost('tipe_konsultasi'),
            'tanggal_usulan' => $this->request->getPost('tanggal'),
            'status' => 'pending'
        ]);

        session()->setFlashdata('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi sekretaris.');
        return redirect()->to('/dashboard');
    }

    public function verifikasi($id_konsultasi)
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $konsultasiModel = new \App\Models\KonsultasiModel();
        $userModel = new \App\Models\UserModel();

        $dataKonsultasi = $konsultasiModel->select('konsultasi.*, user.nama as nama_klien, user.email, user.no_telp')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->first();

        if (!$dataKonsultasi) {
            return redirect()->to('/dashboard')->with('error', 'Data tidak ditemukan.');
        }

        $lawyers = $userModel->where('role', 'lawyer')
            ->where('available', 1)
            ->findAll();

        $data = [
            'k' => $dataKonsultasi,
            'lawyers' => $lawyers
        ];

        return view('konsultasi/form_verifikasi', $data);
    }

    public function processVerifikasi()
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $id_konsultasi = $this->request->getPost('id_konsultasi');
        $action = $this->request->getPost('action');

        if ($action == 'reject') {
            $this->konsultasiModel->update($id_konsultasi, [
                'status' => 'rejected',
                'alasan_tolak' => $this->request->getPost('alasan_tolak')
            ]);
            session()->setFlashdata('msg', 'Pengajuan telah ditolak.');
        } else {
            $this->konsultasiModel->update($id_konsultasi, [
                'no_bas' => $this->request->getPost('no_bas'),
                'meeting' => $this->request->getPost('meeting'),
                'tanggal_fiksasi' => $this->request->getPost('tanggal_fiksasi'),
                'status' => 'waiting_lawyer'
            ]);
            session()->setFlashdata('success', 'Data diteruskan ke Lawyer untuk konfirmasi ketersediaan.');
        }

        return redirect()->to('/dashboard');
    }

    public function lawyerResponse()
    {
        if (session()->get('role') != 'lawyer') {
            return redirect()->to('/dashboard');
        }

        $id = $this->request->getPost('id_konsultasi');
        $keputusan = $this->request->getPost('keputusan');

        if ($keputusan == 'confirm') {
            $this->konsultasiModel->update($id, ['status' => 'waiting_payment']);
            session()->setFlashdata('success', 'Anda telah menyetujui jadwal. Menunggu pembayaran klien.');
        } else {
            $this->konsultasiModel->update($id, ['status' => 'reschedule']);
            session()->setFlashdata('msg', 'Permintaan reschedule dikirim ke Sekretaris.');
        }

        return redirect()->to('/dashboard');
    }

    // =================================================================
    // FASE 3: PEMBAYARAN MIDTRANS (Disesuaikan dengan Gambar User)
    // =================================================================

    public function pembayaran($id_konsultasi)
    {
        $model = new \App\Models\KonsultasiModel();

        // Join ke tabel User (sebagai Klien) DAN User (sebagai Lawyer) untuk ambil harga
        $data = $model->select('konsultasi.*, user.nama as nama_klien, user.email, user.no_telp, lawyer.harga_konsultasi as tarif_lawyer')
            ->join('user', 'user.id_user = konsultasi.id_user') // Join Klien
            ->join('user as lawyer', 'lawyer.no_bas = konsultasi.no_bas') // Join Lawyer via No BAS
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->where('konsultasi.id_user', session()->get('id_user'))
            ->first();

        if (!$data || $data['status'] != 'waiting_payment') {
            return redirect()->to('/dashboard')->with('error', 'Tagihan tidak valid.');
        }

        // --- CONFIG ---
        $serverKey = getenv('MIDTRANS_SERVER_KEY');
        $clientKey = getenv('MIDTRANS_CLIENT_KEY');
        $isProduction = false;

        // PERUBAHAN DI SINI: Ambil harga dari database Lawyer
        $biaya = $data['tarif_lawyer'];

        // --- PERBAIKAN DATA VALIDASI EMAIL ---
        // 1. Hapus spasi di depan/belakang
        $emailBersih = trim($data['email']);

        // 2. Cek apakah format valid? Jika tidak, pakai email dummy agar tidak error
        if (!filter_var($emailBersih, FILTER_VALIDATE_EMAIL)) {
            $emailBersih = "customer@demo.com"; // Fallback email
        }

        // 3. Pastikan No Telp tidak kosong
        $phoneBersih = empty($data['no_telp']) ? '08123456789' : trim($data['no_telp']);
        // -------------------------------------

        $params = [
            'transaction_details' => [
                'order_id' => 'KONSUL-' . $data['id_konsultasi'] . '-' . time(),
                'gross_amount' => $biaya,
            ],
            'customer_details' => [
                'first_name' => $data['nama_klien'],
                'email'      => $emailBersih, // Gunakan variabel yang sudah dibersihkan
                'phone'      => $phoneBersih,
            ],
            'item_details' => [
                [
                    'id' => 'SRV-01',
                    'price' => $biaya,
                    'quantity' => 1,
                    'name' => 'Konsultasi Hukum'
                ]
            ],
            'callbacks' => [
                'finish' => base_url('konsultasi/finish-payment?id=' . $data['id_konsultasi'])
            ]
        ];

        // Request Token
        $snapToken = $this->_getMidtransToken($params, $serverKey, $isProduction);

        if (!$snapToken) {
            return redirect()->back()->with('error', 'Gagal menghubungkan ke Payment Gateway.');
        }

        return view('konsultasi/invoice_pembayaran', [
            'k' => $data,
            'snapToken' => $snapToken,
            'biaya' => $biaya,
            'clientKey' => $clientKey
        ]);
    }

    private function _getMidtransToken($params, $serverKey, $isProduction)
    {
        $url = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':')
        ]);

        // --- BYPASS SSL UNTUK LOCALHOST ---
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            die('<h2>Error Koneksi cURL</h2>Pesan: ' . curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($result, true);

        // TAMPILKAN ERROR JIKA ADA
        if (isset($response['error_messages'])) {
            echo '<div style="background:#ffcccc; padding:20px; border:1px solid red;">';
            echo '<h2>Midtrans Error!</h2>';
            echo '<p>Midtrans menolak kunci Anda. Ini detailnya:</p>';
            echo '<pre>';
            print_r($response['error_messages']);
            echo '</pre>';
            echo '<hr>';
            echo '<p><b>Solusi:</b> Kunci Anda (Mid-server...) dianggap kunci Production oleh sistem, tapi kita mencoba akses URL Sandbox.</p>';
            echo '<p>Silakan kembali ke Dashboard Midtrans, lihat bagian bawah ada tombol biru <b>"Start generate credential"</b> (Payment BI SNAP). Klik tombol itu untuk generate kunci SNAP baru.</p>';
            echo '</div>';
            die();
        }

        return $response['token'] ?? null;
    }

    public function finishPayment()
    {
        $id = $this->request->getGet('id');
        $this->konsultasiModel->update($id, ['status' => 'approved']);
        session()->setFlashdata('success', 'Pembayaran Berhasil! Jadwal Konsultasi telah aktif.');
        return redirect()->to('/dashboard');
    }
    public function tiket($id_konsultasi)
    {
        $model = new \App\Models\KonsultasiModel();

        // Ambil data konsultasi yang sudah Approved
        $data = $model->select('konsultasi.*, user.nama as nama_lawyer, user.spesialisasi')
            ->join('user', 'user.no_bas = konsultasi.no_bas', 'left') // Join ke Lawyer
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->where('konsultasi.id_user', session()->get('id_user')) // Pastikan milik user yang login
            ->first();

        // Validasi: Hanya boleh lihat tiket jika status APPROVED atau COMPLETED
        if (!$data || !in_array($data['status'], ['approved', 'completed'])) {
            return redirect()->to('/dashboard')->with('error', 'Tiket belum tersedia atau belum dibayar.');
        }

        return view('konsultasi/tiket_akses', ['k' => $data]);
    }

    // 2. Lawyer Menandai Selesai (Finish Session)
    public function selesai($id_konsultasi)
    {
        // 1. Cek Login & Role
        if (session()->get('role') != 'lawyer') {
            return redirect()->to('/dashboard');
        }

        $model = new KonsultasiModel();

        // 2. Update Status Konsultasi Jadi 'completed'
        $model->update($id_konsultasi, ['status' => 'completed']);

        // 3. Inisialisasi Data di Tabel Kasus (Agar Timeline Klien tidak kosong)
        $kasusModel = new \App\Models\KasusModel();

        // Cek dulu biar gak duplikat
        $cek = $kasusModel->where('id_konsultasi', $id_konsultasi)->first();
        if (!$cek) {
            $kasusModel->save([
                'id_konsultasi' => $id_konsultasi,
                'tanggal_laporan' => date('Y-m-d'),
                'progres' => 'Sesi Konsultasi Selesai',
                'tindakan' => 'Analisis awal kasus',
                'rencana' => 'Menyusun strategi hukum',
                'status_kasus' => 'open'
            ]);
        }

        return redirect()->to('/dashboard')->with('success', 'Sesi selesai! Kasus kini masuk ke menu Manajemen Kasus.');
    }
}
