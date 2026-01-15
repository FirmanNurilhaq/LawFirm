<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasusModel;
use App\Models\KonsultasiModel;
use App\Models\UserModel;

class KasusController extends BaseController
{
    protected $kasusModel;
    protected $konsultasiModel;

    public function __construct()
    {
        $this->kasusModel = new KasusModel();
        $this->konsultasiModel = new KonsultasiModel();
    }

    // =================================================================
    // 1. MANAJEMEN KASUS (Untuk Lawyer & Sekretaris)
    // =================================================================

    // 1. Daftar Kasus (Lawyer lihat miliknya, Sekretaris lihat SEMUA)
    public function index()
    {
        $role = session()->get('role');

        // Validasi: Hanya Lawyer dan Sekretaris
        if (!in_array($role, ['lawyer', 'sekretaris'])) {
            return redirect()->to('/dashboard');
        }

        // --- MULAI PERBAIKAN QUERY ---
        $builder = $this->konsultasiModel
            ->select('konsultasi.*, user.nama as nama_klien')

            // 1. AMBIL STATUS TERAKHIR (Subquery)
            // Ini akan mencari update paling baru berdasarkan ID terbesar / tanggal terbaru
            ->select('(SELECT status_kasus FROM kasus WHERE kasus.id_konsultasi = konsultasi.id_konsultasi ORDER BY id_kasus DESC LIMIT 1) as status_kasus')

            // 2. AMBIL PROGRES TERAKHIR (Subquery)
            ->select('(SELECT progres FROM kasus WHERE kasus.id_konsultasi = konsultasi.id_konsultasi ORDER BY id_kasus DESC LIMIT 1) as last_progres')

            ->join('user', 'user.id_user = konsultasi.id_user')
            // HAPUS JOIN KE TABEL KASUS (JOIN 'kasus k') KARENA SUDAH DIGANTI SUBQUERY DI ATAS
            // HAPUS GROUP BY KARENA TIDAK ADA JOIN YANG MEMBUAT DUPLIKAT LAGI
            ->where('konsultasi.status', 'completed');

        // JIKA LAWYER: Filter berdasarkan No BAS miliknya
        if ($role == 'lawyer') {
            $userModel = new \App\Models\UserModel();
            $lawyer = $userModel->find(session()->get('id_user'));

            if ($lawyer && !empty($lawyer['no_bas'])) {
                $builder->where('konsultasi.no_bas', $lawyer['no_bas']);
            } else {
                $builder->where('konsultasi.id_konsultasi', 0);
            }
        }

        $data['kasus_list'] = $builder
            ->orderBy('konsultasi.updated_at', 'DESC')
            ->findAll();
        // --- SELESAI PERBAIKAN ---

        return view('kasus/index_lawyer', $data);
    }

    // 2. Form Update Perkembangan (Lawyer & Sekretaris)
    public function update($id_konsultasi)
    {
        $role = session()->get('role');

        // Validasi: Hanya Lawyer dan Sekretaris
        if (!in_array($role, ['lawyer', 'sekretaris'])) {
            return redirect()->to('/dashboard');
        }

        // Ambil detail klien
        $data['k'] = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_klien')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->find($id_konsultasi);

        if (!$data['k']) {
            return redirect()->to('/kasus')->with('error', 'Data kasus tidak ditemukan.');
        }

        // Ambil riwayat update kasus sebelumnya
        $data['riwayat_update'] = $this->kasusModel->where('id_konsultasi', $id_konsultasi)
            ->orderBy('tanggal_laporan', 'DESC')
            ->findAll();

        return view('kasus/form_update', $data);
    }

    // 3. Proses Simpan Update (Lawyer & Sekretaris)
    // 3. Proses Simpan Update (Lawyer & Sekretaris)
    public function processUpdate()
    {
        $role = session()->get('role');

        // Validasi Role
        if (!in_array($role, ['lawyer', 'sekretaris'])) {
            return redirect()->to('/dashboard');
        }

        $id_konsultasi = $this->request->getPost('id_konsultasi');

        // --- MULAI LOGIKA PENGAMANAN 'CASE CLOSED' ---
        // Cek riwayat terakhir
        $lastStatus = $this->kasusModel->where('id_konsultasi', $id_konsultasi)
            ->orderBy('id_kasus', 'DESC')
            ->first();

        // Jika riwayat terakhir ditemukan DAN statusnya sudah 'closed'
        if ($lastStatus && $lastStatus['status_kasus'] == 'closed') {
            return redirect()->back()->with('error', '⛔ PERINGATAN: Kasus ini sudah DITUTUP (Closed). Anda tidak dapat mengubah atau membuka kembali kasus yang sudah final.');
        }
        // --- AKHIR LOGIKA PENGAMANAN ---

        // Jika lolos (belum closed), simpan data baru
        $this->kasusModel->save([
            'id_konsultasi' => $id_konsultasi,
            'tanggal_laporan' => date('Y-m-d'),
            'progres' => $this->request->getPost('progres'),
            'tindakan' => $this->request->getPost('tindakan'),
            'rencana' => $this->request->getPost('rencana'),
            'status_kasus' => $this->request->getPost('status_kasus')
        ]);

        session()->setFlashdata('success', 'Laporan perkembangan berhasil ditambahkan.');
        return redirect()->to('/kasus/update/' . $id_konsultasi);
    }

    // =================================================================
    // 4. MONITORING KASUS (Khusus Klien - Read Only)
    // =================================================================

    public function timeline($id_konsultasi)
    {
        // Validasi: Pastikan user login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Ambil data konsultasi untuk validasi kepemilikan
        $k = $this->konsultasiModel->select('konsultasi.*, user.nama as nama_lawyer')
            ->join('user', 'user.no_bas = konsultasi.no_bas', 'left')
            ->find($id_konsultasi);

        // Security Check: Apakah yang akses adalah pemilik kasus?
        // Catatan: Klien hanya boleh lihat punya sendiri. 
        if (!$k || $k['id_user'] != session()->get('id_user')) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke kasus ini.');
        }

        // Ambil riwayat kasus
        $data = [
            'k' => $k,
            'riwayat' => $this->kasusModel->where('id_konsultasi', $id_konsultasi)
                ->orderBy('tanggal_laporan', 'DESC')
                ->findAll()
        ];

        return view('kasus/timeline_client', $data);
    }
}
