<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Khusus Print: Sembunyikan Form Filter & Tombol saat dicetak */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white;
                padding: 0;
            }

            .container {
                max-width: 100%;
                box-shadow: none;
                border: none;
            }

            /* Memaksa background warna header tabel tercetak */
            th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="bg-gray-50 p-8 font-sans">

    <div class="max-w-5xl mx-auto container">

        <div class="no-print mb-6">
            <div class="flex justify-between items-center mb-4">
                <a href="<?= base_url('dashboard') ?>" class="text-gray-500 hover:text-blue-600 font-bold flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Perhatian</p>
                    <p><?= session()->getFlashdata('error') ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <form action="" method="get" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" value="<?= $tgl_awal ?? '' ?>" required
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Hingga Tanggal</label>
                        <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?? '' ?>" required
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-bold text-sm w-full transition">
                            <i class="fa-solid fa-filter"></i> Tampilkan
                        </button>

                        <a href="<?= base_url('laporan/keuangan') ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-600 px-4 py-2 rounded font-bold text-sm transition text-center flex items-center justify-center">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">💰 Laporan Keuangan</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Periode:
                    <?php if ($tgl_awal && $tgl_akhir): ?>
                        <span class="font-bold text-slate-800"><?= date('d M Y', strtotime($tgl_awal)) ?></span> s/d
                        <span class="font-bold text-slate-800"><?= date('d M Y', strtotime($tgl_akhir)) ?></span>
                    <?php else: ?>
                        <span class="font-bold text-slate-800">Semua Waktu</span>
                    <?php endif; ?>
                </p>
            </div>

            <button onclick="window.print()" class="no-print bg-red-600 text-white px-6 py-3 rounded-xl shadow-lg font-bold hover:bg-red-700 transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </button>
        </div>

        <div class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg mb-6 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold uppercase opacity-80">Total Pendapatan Terpilih</p>
                <p class="text-xs opacity-60">Status: Approved & Completed</p>
            </div>
            <h2 class="text-3xl font-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold border-b">
                    <tr>
                        <th class="p-4 w-10">No</th>
                        <th class="p-4">Tanggal Transaksi</th>
                        <th class="p-4">Klien</th>
                        <th class="p-4">Lawyer (Penerima)</th>
                        <th class="p-4 text-right">Nominal (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (empty($transaksi)): ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 italic">
                                Tidak ada data transaksi pada periode ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($transaksi as $t): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 text-gray-500"><?= $no++ ?></td>
                                <td class="p-4 text-gray-500">
                                    <?= date('d M Y', strtotime($t['updated_at'])) ?>
                                    <br>
                                    <span class="text-xs text-gray-400"><?= date('H:i', strtotime($t['updated_at'])) ?> WIB</span>
                                </td>
                                <td class="p-4 font-bold text-slate-700"><?= $t['nama_klien'] ?></td>
                                <td class="p-4 text-slate-600"><?= $t['nama_lawyer'] ?></td>
                                <td class="p-4 text-right font-mono font-bold text-green-600">
                                    Rp <?= number_format($t['harga_konsultasi'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-right hidden print:block">
            <p class="text-xs text-gray-500">Dicetak oleh: <?= session()->get('nama') ?> | Tanggal: <?= date('d M Y H:i') ?></p>
        </div>

    </div>

    <script>
        const tglAwal = document.querySelector('input[name="tgl_awal"]');
        const tglAkhir = document.querySelector('input[name="tgl_akhir"]');

        tglAwal.addEventListener('change', function() {
            // Set min tanggal akhir harus sama dengan tanggal awal
            tglAkhir.min = this.value;
        });
    </script>
</body>

</html>