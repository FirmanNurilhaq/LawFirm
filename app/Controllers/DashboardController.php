<?php

namespace App\Controllers;

use App\Models\KonsultasiModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // 1. Cek Login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');
        $idUser = session()->get('id_user');
        $data = []; // Variabel untuk menampung data ke view

        // 2. Logika per Role
        if ($role == 'client') {
            // ==========================
            // LOGIKA KLIEN
            // ==========================
            $model = new KonsultasiModel();

            // Ambil data riwayat
            $data['riwayat'] = $model->where('id_user', $idUser)
                ->orderBy('created_at', 'DESC')
                ->findAll();

            // Hitung Statistik untuk Card Dashboard
            $data['stat_pending'] = $model->where('id_user', $idUser)->where('status', 'pending')->countAllResults();
            $data['stat_upcoming'] = $model->where('id_user', $idUser)->whereIn('status', ['waiting_payment', 'approved'])->countAllResults();
            $data['stat_completed'] = $model->where('id_user', $idUser)->where('status', 'completed')->countAllResults();

            return view('dashboard/client_dashboard', $data);
        } elseif ($role == 'sekretaris') {
            // ==========================
            // LOGIKA SEKRETARIS
            // ==========================
            $model = new KonsultasiModel();

            // Ambil SEMUA data konsultasi
            $data['pengajuan'] = $model->select('konsultasi.*, user.nama as nama_klien, user.no_telp')
                ->join('user', 'user.id_user = konsultasi.id_user')
                ->orderBy('konsultasi.created_at', 'DESC')
                ->findAll();

            // Hitung statistik sederhana
            $data['count_pending'] = $model->where('status', 'pending')->countAllResults();
            $data['count_process'] = $model->whereIn('status', ['waiting_lawyer', 'waiting_payment', 'approved'])->countAllResults();

            return view('dashboard/secretary_dashboard', $data);
        } elseif ($role == 'lawyer') {
            // ==========================
            // LOGIKA LAWYER (SUDAH DIPERBAIKI)
            // ==========================
            $model = new KonsultasiModel();
            $userModel = new UserModel();

            // Ambil Detail Lawyer (Untuk dapat No BAS & Status Available)
            $lawyerDetail = $userModel->find($idUser);

            // [PERBAIKAN UTAMA DISINI]
            // Mengirim data lawyer ke view agar variabel $user dikenali
            $data['user'] = $lawyerDetail;

            // Cek Safety: Jika data lawyer rusak/tidak punya BAS
            if (!$lawyerDetail || empty($lawyerDetail['no_bas'])) {
                $myBas = 'UNKNOWN';
            } else {
                $myBas = $lawyerDetail['no_bas'];
            }

            // A. TUGAS BARU (Permintaan Konfirmasi Jadwal)
            $data['tugas_baru'] = $model->select('konsultasi.*, user.nama as nama_klien')
                ->join('user', 'user.id_user = konsultasi.id_user')
                ->where('konsultasi.no_bas', $myBas)
                ->where('konsultasi.status', 'waiting_lawyer')
                ->findAll();

            // B. JADWAL AKTIF (Siap Dieksekusi)
            $data['jadwal_aktif'] = $model->select('konsultasi.*, user.nama as nama_klien')
                ->join('user', 'user.id_user = konsultasi.id_user')
                ->where('konsultasi.no_bas', $myBas)
                ->whereIn('konsultasi.status', ['waiting_payment', 'approved'])
                ->orderBy('konsultasi.tanggal_fiksasi', 'ASC')
                ->findAll();

            return view('dashboard/lawyer_dashboard', $data);
        } elseif ($role == 'ketua firma') {
            // ==========================
            // LOGIKA KETUA FIRMA
            // ==========================
            $konsultasiModel = new KonsultasiModel();
            $userModel = new UserModel();
            $db = \Config\Database::connect();

            // 1. KARTU ATAS
            $queryIncome = $db->table('user')
                ->selectSum('harga_konsultasi')
                ->join('konsultasi', 'konsultasi.no_bas = user.no_bas')
                ->whereIn('konsultasi.status', ['approved', 'completed'])
                ->get();

            $data['total_income'] = $queryIncome->getRow()->harga_konsultasi;
            $data['total_kasus'] = $konsultasiModel->where('status', 'completed')->countAllResults();
            $data['total_klien'] = $userModel->where('role', 'client')->countAllResults();
            $data['total_lawyer'] = $userModel->where('role', 'lawyer')->countAllResults();

            // 2. DATA UNTUK GRAFIK
            $queryGrafik = $db->query("
                SELECT 
                    MONTH(COALESCE(k.updated_at, k.created_at)) as bulan, 
                    SUM(u.harga_konsultasi) as total
                FROM konsultasi k
                JOIN user u ON u.no_bas = k.no_bas
                WHERE k.status IN ('approved', 'completed')
                GROUP BY MONTH(COALESCE(k.updated_at, k.created_at))
            ");

            $grafikData = array_fill(1, 12, 0);
            foreach ($queryGrafik->getResultArray() as $row) {
                $bulan = (int)$row['bulan'];
                if ($bulan >= 1 && $bulan <= 12) {
                    $grafikData[$bulan] = (int)$row['total'];
                }
            }
            $data['grafik_pendapatan'] = json_encode(array_values($grafikData));

            return view('dashboard/head_dashboard', $data);
        } else {
            return view('dashboard/index');
        }
    }

    // ==========================================
    // FITUR TAMBAHAN: UPDATE STATUS LAWYER
    // ==========================================
    public function update_availability()
    {
        // 1. Pastikan yang akses adalah Lawyer
        if (session()->get('role') != 'lawyer') {
            return redirect()->to('/dashboard');
        }

        $userModel = new UserModel();
        $idUser = session()->get('id_user');

        // 2. Ambil status saat ini
        $currentUser = $userModel->find($idUser);
        $currentStatus = $currentUser['available'];

        // 3. Balik Statusnya (Toggle)
        // Jika 1 (Available) ubah jadi 0 (Cuti), dan sebaliknya
        $newStatus = ($currentStatus == 1) ? 0 : 1;

        // 4. Update ke Database
        $userModel->update($idUser, ['available' => $newStatus]);

        // 5. Beri Notifikasi
        $statusText = ($newStatus == 1) ? 'Sekarang Anda ONLINE (Siap terima kasus)' : 'Sekarang Anda CUTI (Tidak menerima kasus baru)';
        session()->setFlashdata('message', $statusText);

        return redirect()->to('/dashboard');
    }
}
