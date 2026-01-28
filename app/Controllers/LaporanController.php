<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KonsultasiModel;

class LaporanController extends BaseController
{
    // Helper function untuk cek akses
    private function cekAkses()
    {
        $role = session()->get('role');
        if (!in_array($role, ['sekretaris', 'ketua firma'])) {
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->cekAkses()) return redirect()->to('/dashboard');
        return view('laporan/index');
    }

    public function cetak()
    {
        if (!$this->cekAkses()) return redirect()->to('/dashboard');

        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $status = $this->request->getGet('status');

        $model = new KonsultasiModel();

        // Validasi Tanggal (Server Side)
        if ($tglAwal && $tglAkhir) {
            if ($tglAkhir < $tglAwal) {
                session()->setFlashdata('error', 'Tanggal "Hingga" tidak boleh kurang dari Tanggal "Dari".');
                return redirect()->back()->withInput();
            }
        }

        $builder = $model->select('konsultasi.*, user.nama as nama_klien, lawyer.nama as nama_lawyer')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->join('user as lawyer', 'lawyer.no_bas = konsultasi.no_bas', 'left');

        if ($tglAwal && $tglAkhir) {
            $builder->where('DATE(konsultasi.tanggal_usulan) >=', $tglAwal)
                ->where('DATE(konsultasi.tanggal_usulan) <=', $tglAkhir);
        }

        if ($status && $status != 'all') {
            $builder->where('konsultasi.status', $status);
        }

        $data = [
            'laporan' => $builder->orderBy('tanggal_usulan', 'DESC')->findAll(),
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'role_akses' => session()->get('role')
        ];

        return view('laporan/cetak', $data);
    }

    public function keuangan()
    {
        if (!$this->cekAkses()) return redirect()->to('/dashboard');

        // 1. Ambil Input Filter
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        // 2. Validasi Tanggal "Hingga" tidak boleh kurang dari "Dari"
        if ($tglAwal && $tglAkhir) {
            if (strtotime($tglAkhir) < strtotime($tglAwal)) {
                session()->setFlashdata('error', 'Rentang tanggal tidak valid! Tanggal akhir harus lebih besar dari tanggal awal.');
                // Jangan redirect, tapi tampilkan view dengan data kosong atau pesan error
                // Kita load view tapi tanpa data transaksi agar user memperbaiki input
                return view('laporan/keuangan', [
                    'transaksi' => [],
                    'total_pendapatan' => 0,
                    'tgl_awal' => $tglAwal,
                    'tgl_akhir' => $tglAkhir,
                    'role_akses' => session()->get('role')
                ]);
            }
        }

        $model = new KonsultasiModel();

        // 3. Query Builder
        $builder = $model->select('konsultasi.*, user.nama as nama_klien, lawyer.nama as nama_lawyer, lawyer.harga_konsultasi')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->join('user as lawyer', 'lawyer.no_bas = konsultasi.no_bas')
            ->whereIn('konsultasi.status', ['approved', 'completed']);

        // 4. Terapkan Filter Tanggal (Jika user mengisi)
        if ($tglAwal && $tglAkhir) {
            // Gunakan updated_at karena itu waktu pembayaran/penyelesaian
            $builder->where('DATE(konsultasi.updated_at) >=', $tglAwal)
                ->where('DATE(konsultasi.updated_at) <=', $tglAkhir);
        }

        $transaksi = $builder->orderBy('konsultasi.updated_at', 'DESC')->findAll();

        // 5. Hitung Total
        $totalPendapatan = 0;
        foreach ($transaksi as $t) {
            $totalPendapatan += $t['harga_konsultasi'];
        }

        $data = [
            'transaksi' => $transaksi,
            'total_pendapatan' => $totalPendapatan,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'role_akses' => session()->get('role')
        ];

        return view('laporan/keuangan', $data);
    }
}
