<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KonsultasiModel;
use App\Models\UserModel; // Kita butuh UserModel untuk ambil email Sekretaris/Lawyer

class KonsultasiController extends BaseController
{
    protected $konsultasiModel;

    public function __construct()
    {
        $this->konsultasiModel = new KonsultasiModel();
        // Load Helper Brevo yang baru dibuat
        helper(['brevo', 'text']);
    }

    // =================================================================
    // FASE 1: PENGAJUAN (Klien)
    // =================================================================

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

        // Simpan Data
        $this->konsultasiModel->save([
            'id_user' => session()->get('id_user'),
            'jenis_perkara' => $this->request->getPost('judul'),
            'deskripsi_masalah' => $this->request->getPost('deskripsi'),
            'dokumen_kelengkapan' => $namaFile,
            'tipe_konsultasi' => $this->request->getPost('tipe_konsultasi'),
            'tanggal_usulan' => $this->request->getPost('tanggal'),
            'status' => 'pending'
        ]);

        // [EMAIL 1] NOTIFIKASI KE SEKRETARIS (Ada Pengajuan Baru)
        $userModel = new UserModel();
        // Ambil salah satu sekretaris aktif
        $sekretaris = $userModel->where('role', 'sekretaris')->first();

        if ($sekretaris) {
            $namaKlien = session()->get('nama');
            $judul = $this->request->getPost('judul');

            $subject = "[INFO] Pengajuan Konsultasi Baru: $namaKlien";
            $msg = "
                <h3>Halo Sekretaris,</h3>
                <p>Ada pengajuan konsultasi baru masuk ke sistem.</p>
                <ul>
                    <li><strong>Klien:</strong> $namaKlien</li>
                    <li><strong>Perkara:</strong> $judul</li>
                    <li><strong>Tanggal Usulan:</strong> " . $this->request->getPost('tanggal') . "</li>
                </ul>
                <p>Mohon segera login dan lakukan verifikasi.</p>
            ";
            send_email_brevo($sekretaris['email'], $sekretaris['nama'], $subject, $msg);
        }

        session()->setFlashdata('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi sekretaris.');
        return redirect()->to('/dashboard');
    }

    // =================================================================
    // FASE 2: VERIFIKASI (Sekretaris & Lawyer)
    // =================================================================

    public function verifikasi($id_konsultasi)
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $userModel = new UserModel();

        $dataKonsultasi = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_klien, user.email, user.no_telp')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->first();

        if (!$dataKonsultasi) {
            return redirect()->to('/dashboard')->with('error', 'Data tidak ditemukan.');
        }

        // Ambil Lawyer yang Available saja
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
            // Tolak
            $this->konsultasiModel->update($id_konsultasi, [
                'status' => 'rejected',
                'alasan_tolak' => $this->request->getPost('alasan_tolak')
            ]);

            // Opsional: Email ke Klien kalau ditolak (Bisa ditambahkan sendiri)

            session()->setFlashdata('msg', 'Pengajuan telah ditolak.');
        } else {
            // Approve & Teruskan ke Lawyer
            $no_bas = $this->request->getPost('no_bas');

            $this->konsultasiModel->update($id_konsultasi, [
                'no_bas' => $no_bas,
                'meeting' => $this->request->getPost('meeting'),
                'tanggal_fiksasi' => $this->request->getPost('tanggal_fiksasi'),
                'status' => 'waiting_lawyer'
            ]);

            // [EMAIL 2] NOTIFIKASI KE LAWYER (Ada Tugas Baru)
            $userModel = new UserModel();
            $lawyer = $userModel->where('no_bas', $no_bas)->first();

            if ($lawyer) {
                $tgl = date('d M Y H:i', strtotime($this->request->getPost('tanggal_fiksasi')));
                $subject = "[TUGAS] Konfirmasi Jadwal Konsultasi";
                $msg = "
                    <h3>Halo, {$lawyer['nama']}</h3>
                    <p>Sekretaris telah meneruskan pengajuan konsultasi kepada Anda.</p>
                    <p><strong>Jadwal Usulan:</strong> $tgl WIB</p>
                    <p>Silakan login ke Dashboard untuk <strong>Menerima</strong> atau <strong>Meminta Reschedule</strong>.</p>
                ";
                send_email_brevo($lawyer['email'], $lawyer['nama'], $subject, $msg);
            }

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

        // Ambil Data Konsultasi & Klien
        $konsultasi = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_klien, user.email as email_klien')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->find($id);

        if ($keputusan == 'confirm') {
            // A. JIKA CONFIRM
            $this->konsultasiModel->update($id, ['status' => 'waiting_payment']);

            // [EMAIL 3] NOTIFIKASI KE KLIEN (Suruh Bayar)
            if ($konsultasi) {
                $subject = "[PENTING] Jadwal Disetujui - Menunggu Pembayaran";
                $msg = "
                    <h3>Halo {$konsultasi['nama_klien']},</h3>
                    <p>Kabar baik! Lawyer kami telah menyetujui jadwal konsultasi Anda.</p>
                    <p>Langkah selanjutnya: <strong>Lakukan Pembayaran</strong> agar Anda mendapatkan link meeting/tiket akses.</p>
                    <p>Silakan login ke aplikasi untuk melihat invoice.</p>
                ";
                send_email_brevo($konsultasi['email_klien'], $konsultasi['nama_klien'], $subject, $msg);
            }

            session()->setFlashdata('success', 'Anda menyetujui jadwal. Klien telah dinotifikasi untuk membayar.');
        } else {
            // B. JIKA MINTA RESCHEDULE
            $this->konsultasiModel->update($id, ['status' => 'reschedule']);

            // [EMAIL 4] NOTIFIKASI KE SEKRETARIS (Lawyer Minta Ganti Waktu)
            $userModel = new UserModel();
            $sekretaris = $userModel->where('role', 'sekretaris')->first();
            $namaLawyer = session()->get('nama'); // Nama Lawyer yg login

            if ($sekretaris) {
                $subject = "[ALERT] Lawyer Minta Reschedule: $namaLawyer";
                $msg = "
                    <h3>Halo Sekretaris,</h3>
                    <p>Lawyer <strong>$namaLawyer</strong> tidak bisa menghadiri jadwal yang diajukan untuk klien <strong>{$konsultasi['nama_klien']}</strong>.</p>
                    <p>Status konsultasi sekarang: <strong>RESCHEDULE</strong>.</p>
                    <p>Mohon hubungi Lawyer/Klien untuk menentukan waktu baru, lalu update data di Dashboard Sekretaris.</p>
                ";
                send_email_brevo($sekretaris['email'], $sekretaris['nama'], $subject, $msg);
            }

            session()->setFlashdata('msg', 'Permintaan reschedule dikirim. Sekretaris akan dinotifikasi via Email.');
        }

        return redirect()->to('/dashboard');
    }

    // =================================================================
    // FASE 3: PEMBAYARAN MIDTRANS
    // =================================================================

    public function pembayaran($id_konsultasi)
    {
        // Join User (Klien) & Lawyer
        $data = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_klien, user.email, user.no_telp, lawyer.harga_konsultasi as tarif_lawyer')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->join('user as lawyer', 'lawyer.no_bas = konsultasi.no_bas')
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->where('konsultasi.id_user', session()->get('id_user'))
            ->first();

        if (!$data || $data['status'] != 'waiting_payment') {
            return redirect()->to('/dashboard')->with('error', 'Tagihan tidak valid.');
        }

        // Config Midtrans
        $serverKey = getenv('MIDTRANS_SERVER_KEY');
        $clientKey = getenv('MIDTRANS_CLIENT_KEY');
        $isProduction = false;
        $biaya = $data['tarif_lawyer'];

        // Validasi Data Midtrans
        $emailBersih = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL) ? trim($data['email']) : "customer@demo.com";
        $phoneBersih = empty($data['no_telp']) ? '08123456789' : trim($data['no_telp']);

        $params = [
            'transaction_details' => [
                'order_id' => 'KONSUL-' . $data['id_konsultasi'] . '-' . time(),
                'gross_amount' => $biaya,
            ],
            'customer_details' => [
                'first_name' => $data['nama_klien'],
                'email'      => $emailBersih,
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

        // Get Token
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

        // Bypass SSL Localhost
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        // Error Handling Sederhana
        if (isset($response['error_messages'])) {
            // Bisa return null dan handle di fungsi utama
            return null;
        }

        return $response['token'] ?? null;
    }

    public function finishPayment()
    {
        $id = $this->request->getGet('id');
        $this->konsultasiModel->update($id, ['status' => 'approved']);

        // [EMAIL 5] NOTIFIKASI TIKET KE KLIEN (Lunas)
        $data = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_klien, user.email as email_klien')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->find($id);

        if ($data) {
            $linkTiket = base_url('konsultasi/tiket/' . $id);
            $subject = "[LUNAS] Pembayaran Berhasil - Konsultasi Aktif";
            $msg = "
                <h3>Pembayaran Diterima!</h3>
                <p>Halo {$data['nama_klien']}, pembayaran Anda telah kami terima.</p>
                <p>Silakan klik link di bawah ini untuk melihat <strong>TIKET AKSES</strong> Anda:</p>
                <p><a href='$linkTiket' style='background-color:#4CAF50; color:white; padding:10px 20px; text-decoration:none;'>LIHAT TIKET</a></p>
                <p>Mohon hadir tepat waktu.</p>
            ";
            send_email_brevo($data['email_klien'], $data['nama_klien'], $subject, $msg);
        }

        session()->setFlashdata('success', 'Pembayaran Berhasil! Tiket akses telah dikirim via Email.');
        return redirect()->to('/dashboard');
    }

    // =================================================================
    // FASE 4: TIKET & SELESAI
    // =================================================================

    public function tiket($id_konsultasi)
    {
        $data = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_lawyer, user.spesialisasi')
            ->join('user', 'user.no_bas = konsultasi.no_bas', 'left')
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->where('konsultasi.id_user', session()->get('id_user'))
            ->first();

        if (!$data || !in_array($data['status'], ['approved', 'completed'])) {
            return redirect()->to('/dashboard')->with('error', 'Tiket belum tersedia.');
        }

        return view('konsultasi/tiket_akses', ['k' => $data]);
    }

    public function selesai($id_konsultasi)
    {
        if (session()->get('role') != 'lawyer') {
            return redirect()->to('/dashboard');
        }

        // Update status konsultasi
        $this->konsultasiModel->update($id_konsultasi, ['status' => 'completed']);

        // Generate data awal di tabel Kasus
        $kasusModel = new \App\Models\KasusModel();
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

        return redirect()->to('/dashboard')->with('success', 'Sesi selesai! Kasus masuk ke monitoring.');
    }
}
