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

        // LOAD HELPER BREVO (Penting!)
        helper(['brevo', 'text']);
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

        $builder = $this->konsultasiModel
            ->select('konsultasi.*, user.nama as nama_klien')
            // Subquery Status Terakhir
            ->select('(SELECT status_kasus FROM kasus WHERE kasus.id_konsultasi = konsultasi.id_konsultasi ORDER BY id_kasus DESC LIMIT 1) as status_kasus')
            // Subquery Progres Terakhir
            ->select('(SELECT progres FROM kasus WHERE kasus.id_konsultasi = konsultasi.id_konsultasi ORDER BY id_kasus DESC LIMIT 1) as last_progres')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->where('konsultasi.status', 'completed');

        // JIKA LAWYER: Filter berdasarkan No BAS miliknya
        if ($role == 'lawyer') {
            $userModel = new \App\Models\UserModel();
            $lawyer = $userModel->find(session()->get('id_user'));

            if ($lawyer && !empty($lawyer['no_bas'])) {
                $builder->where('konsultasi.no_bas', $lawyer['no_bas']);
            } else {
                $builder->where('konsultasi.id_konsultasi', 0); // Safety jika no_bas kosong
            }
        }

        $data['kasus_list'] = $builder
            ->orderBy('konsultasi.updated_at', 'DESC')
            ->findAll();

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
    public function processUpdate()
    {
        $role = session()->get('role');

        // Validasi Role
        if (!in_array($role, ['lawyer', 'sekretaris'])) {
            return redirect()->to('/dashboard');
        }

        $id_konsultasi = $this->request->getPost('id_konsultasi');

        // --- LOGIKA PENGAMANAN 'CASE CLOSED' ---
        $lastStatus = $this->kasusModel->where('id_konsultasi', $id_konsultasi)
            ->orderBy('id_kasus', 'DESC')
            ->first();

        if ($lastStatus && $lastStatus['status_kasus'] == 'closed') {
            return redirect()->back()->with('error', '⛔ PERINGATAN: Kasus ini sudah DITUTUP (Closed). Anda tidak dapat mengubahnya lagi.');
        }

        // Ambil Inputan Form
        $progres = $this->request->getPost('progres');
        $tindakan = $this->request->getPost('tindakan');
        $rencana = $this->request->getPost('rencana');
        $status_kasus = $this->request->getPost('status_kasus');

        // Simpan ke Database
        $this->kasusModel->save([
            'id_konsultasi' => $id_konsultasi,
            'tanggal_laporan' => date('Y-m-d'),
            'progres' => $progres,
            'tindakan' => $tindakan,
            'rencana' => $rencana,
            'status_kasus' => $status_kasus
        ]);

        // ========================================================
        // [EMAIL 6] NOTIFIKASI UPDATE PROGRES KE KLIEN (CRITICAL!)
        // ========================================================

        // 1. Ambil Email Klien
        $klienData = $this->konsultasiModel->select('user.email, user.nama as nama_klien, konsultasi.jenis_perkara')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->where('konsultasi.id_konsultasi', $id_konsultasi)
            ->first();

        if ($klienData) {
            $statusLabel = strtoupper($status_kasus);
            $warnaStatus = ($status_kasus == 'closed') ? '#ef4444' : '#3b82f6'; // Merah jika closed, Biru jika open

            $subject = "[UPDATE KASUS] Perkembangan Terbaru: {$klienData['jenis_perkara']}";

            $msg = "
                <h3>Halo, {$klienData['nama_klien']}</h3>
                <p>Tim Lawyer kami baru saja memperbarui status perkembangan kasus Anda.</p>
                
                <div style='background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin: 10px 0;'>
                    <p><strong>Status Kasus:</strong> <span style='color: $warnaStatus; font-weight: bold;'>$statusLabel</span></p>
                    <p><strong>Progres Terbaru:</strong><br> $progres</p>
                    <p><strong>Tindakan yang Dilakukan:</strong><br> $tindakan</p>
                    <p><strong>Rencana Selanjutnya:</strong><br> $rencana</p>
                </div>

                <p>Anda dapat melihat riwayat lengkapnya melalui Dashboard Klien.</p>
                <a href='" . base_url('login') . "' style='background-color: #1e293b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Lihat Dashboard</a>
            ";

            // Kirim Email via Helper
            send_email_brevo($klienData['email'], $klienData['nama_klien'], $subject, $msg);
        }
        // ========================================================

        session()->setFlashdata('success', 'Laporan perkembangan berhasil ditambahkan & Notifikasi email dikirim ke Klien.');
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
