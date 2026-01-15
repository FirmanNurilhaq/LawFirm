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

            // Ambil SEMUA data konsultasi, JOIN dengan tabel user untuk dapat nama klien
            // Urutkan dari yang terbaru
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
            // LOGIKA LAWYER
            // ==========================
            $model = new KonsultasiModel();
            $userModel = new UserModel();

            // Ambil Detail Lawyer (Untuk dapat No BAS)
            $lawyerDetail = $userModel->find($idUser);

            // Cek Safety: Jika data lawyer rusak/tidak punya BAS
            if (!$lawyerDetail || empty($lawyerDetail['no_bas'])) {
                $myBas = 'UNKNOWN';
            } else {
                $myBas = $lawyerDetail['no_bas'];
            }

            // A. TUGAS BARU (Permintaan Konfirmasi Jadwal)
            // Status: 'waiting_lawyer'
            $data['tugas_baru'] = $model->select('konsultasi.*, user.nama as nama_klien')
                ->join('user', 'user.id_user = konsultasi.id_user')
                ->where('konsultasi.no_bas', $myBas)
                ->where('konsultasi.status', 'waiting_lawyer')
                ->findAll();

            // B. JADWAL AKTIF (Siap Dieksekusi)
            // PERBAIKAN PENTING:
            // 1. Hanya ambil yang statusnya 'approved' (SUDAH BAYAR).
            // 2. Jangan tampilkan 'waiting_payment' agar lawyer tidak kerja duluan.
            $data['jadwal_aktif'] = $model->select('konsultasi.*, user.nama as nama_klien')
                ->join('user', 'user.id_user = konsultasi.id_user')
                ->where('konsultasi.no_bas', $myBas)
                // KITA KEMBALIKAN 'waiting_payment' AGAR LAWYER BISA PANTAU TAGIHAN
                ->whereIn('konsultasi.status', ['waiting_payment', 'approved'])
                ->orderBy('konsultasi.tanggal_fiksasi', 'ASC')
                ->findAll();

            return view('dashboard/lawyer_dashboard', $data);
        } elseif ($role == 'ketua firma') {
            // ==========================
            // LOGIKA KETUA FIRMA (STATISTIK)
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

            // 2. DATA UNTUK GRAFIK (PERBAIKAN)
            // Menggunakan COALESCE: Jika updated_at NULL, pakai created_at
            // Filter tahun DIHAPUS sementara agar semua data muncul di grafik
            $queryGrafik = $db->query("
                SELECT 
                    MONTH(COALESCE(k.updated_at, k.created_at)) as bulan, 
                    SUM(u.harga_konsultasi) as total
                FROM konsultasi k
                JOIN user u ON u.no_bas = k.no_bas
                WHERE k.status IN ('approved', 'completed')
                GROUP BY MONTH(COALESCE(k.updated_at, k.created_at))
            ");

            // Format data bulan 1-12
            $grafikData = array_fill(1, 12, 0);
            foreach ($queryGrafik->getResultArray() as $row) {
                // Pastikan bulan valid (1-12)
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
}
