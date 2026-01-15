<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KonsultasiModel;

class LaporanController extends BaseController
{
    public function index()
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }
        return view('laporan/index');
    }

    public function cetak()
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $status = $this->request->getGet('status');

        $model = new KonsultasiModel();

        // Query Laporan
        $builder = $model->select('konsultasi.*, user.nama as nama_klien, lawyer.nama as nama_lawyer')
            ->join('user', 'user.id_user = konsultasi.id_user') // Join Klien
            ->join('user as lawyer', 'lawyer.no_bas = konsultasi.no_bas', 'left'); // Join Lawyer

        // Filter Tanggal
        if ($tglAwal && $tglAkhir) {
            $builder->where('konsultasi.tanggal_usulan >=', $tglAwal)
                ->where('konsultasi.tanggal_usulan <=', $tglAkhir);
        }

        // Filter Status
        if ($status && $status != 'all') {
            $builder->where('konsultasi.status', $status);
        }

        $data = [
            'laporan' => $builder->orderBy('tanggal_usulan', 'DESC')->findAll(),
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir
        ];

        return view('laporan/cetak', $data);
    }
    // Tambahkan di dalam LaporanController

    public function keuangan()
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $model = new KonsultasiModel();

        // Ambil data transaksi sukses
        $transaksi = $model->select('konsultasi.*, user.nama as nama_klien, lawyer.nama as nama_lawyer, lawyer.harga_konsultasi')
            ->join('user', 'user.id_user = konsultasi.id_user')
            ->join('user as lawyer', 'lawyer.no_bas = konsultasi.no_bas')
            ->whereIn('konsultasi.status', ['approved', 'completed']) // Hanya yang sudah bayar
            ->orderBy('konsultasi.updated_at', 'DESC')
            ->findAll();

        // Hitung Total Pendapatan
        $totalPendapatan = 0;
        foreach ($transaksi as $t) {
            // Asumsi: harga saat transaksi adalah harga lawyer saat ini
            // (Idealnya harga disimpan di tabel konsultasi saat deal, tapi untuk skripsi ini cukup)
            $totalPendapatan += $t['harga_konsultasi'];
        }

        $data = [
            'transaksi' => $transaksi,
            'total_pendapatan' => $totalPendapatan
        ];

        return view('laporan/keuangan', $data);
    }
}
