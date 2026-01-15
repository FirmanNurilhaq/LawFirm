<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard Klien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-[Inter]">

    <nav class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="font-bold text-xl text-slate-800">
            ⚖️ Area <span class="text-amber-600">Klien</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-600 text-sm">Halo, <b><?= session()->get('nama'); ?></b></span>
            <a href="<?= base_url('logout'); ?>" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="bg-slate-900 text-white rounded-xl p-8 mb-8 shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">Butuh Bantuan Hukum?</h1>
                <p class="text-slate-300">Ajukan konsultasi sekarang dan temukan solusi bersama ahli kami.</p>
            </div>
            <a href="<?= base_url('konsultasi/ajukan'); ?>" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-105 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Ajukan Jadwal Baru
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Menunggu Verifikasi</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">
                    <?= $stat_pending ?? 0 ?> <span class="text-sm font-normal text-gray-400">Pengajuan</span>
                </h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Jadwal Aktif</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">
                    <?= $stat_upcoming ?? 0 ?> <span class="text-sm font-normal text-gray-400">Kasus</span>
                </h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Selesai</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">
                    <?= $stat_completed ?? 0 ?> <span class="text-sm font-normal text-gray-400">Kasus</span>
                </h3>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> Riwayat Pengajuan Konsultasi
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider font-semibold">
                            <th class="p-4">Tanggal & Waktu</th>
                            <th class="p-4">Topik Masalah</th>
                            <th class="p-4">Lawyer & Lokasi</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php if (empty($riwayat)): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-regular fa-folder-open text-2xl mb-2"></i>
                                        Belum ada riwayat pengajuan konsultasi.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($riwayat as $r): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-700">
                                            <?= date('d M Y', strtotime($r['tanggal_fiksasi'] ?? $r['tanggal_usulan'])) ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?= date('H:i', strtotime($r['tanggal_fiksasi'] ?? $r['tanggal_usulan'])) ?> WIB
                                        </div>
                                    </td>

                                    <td class="p-4">
                                        <div class="font-medium text-slate-800"><?= esc($r['jenis_perkara']) ?></div>
                                        <div class="text-xs text-gray-500 truncate w-48"><?= esc($r['deskripsi_masalah']) ?></div>
                                    </td>

                                    <td class="p-4">
                                        <?php if ($r['no_bas']): ?>
                                            <div class="font-bold text-slate-700 text-xs">
                                                <i class="fa-solid fa-user-tie"></i> Lawyer: <?= $r['no_bas'] ?>
                                            </div>
                                            <div class="text-xs text-blue-600 mt-1 truncate w-40">
                                                <i class="fa-solid fa-location-dot"></i> <?= $r['meeting'] ?? 'Lokasi belum diatur' ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Sedang dicarikan lawyer...</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="p-4">
                                        <?php
                                        // Mapping Warna & Label Status
                                        $badges = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'waiting_lawyer' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                            'waiting_payment' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                            'approved' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                            'rejected' => 'bg-red-100 text-red-800 border border-red-200',
                                            'reschedule' => 'bg-pink-100 text-pink-800 border border-pink-200',
                                            'completed' => 'bg-green-100 text-green-800 border border-green-200'
                                        ];

                                        $labels = [
                                            'pending' => 'Verifikasi Admin',
                                            'waiting_lawyer' => 'Konfirmasi Lawyer',
                                            'waiting_payment' => 'Menunggu Pembayaran',
                                            'approved' => 'Siap / Terjadwal',
                                            'rejected' => 'Ditolak',
                                            'reschedule' => 'Reschedule',
                                            'completed' => 'Selesai'
                                        ];

                                        $statusKey = $r['status'];
                                        $badgeClass = $badges[$statusKey] ?? 'bg-gray-100 text-gray-800';
                                        $labelText = $labels[$statusKey] ?? $statusKey;
                                        ?>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold <?= $badgeClass ?>">
                                            <?= $labelText ?>
                                        </span>
                                    </td>

                                    <td class="p-4 text-center">
                                        <?php if ($r['status'] == 'waiting_payment'): ?>
                                            <a href="<?= base_url('konsultasi/pembayaran/' . $r['id_konsultasi']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-bold shadow transition flex items-center gap-1 justify-center">
                                                <i class="fa-solid fa-wallet"></i> Bayar
                                            </a>

                                        <?php elseif ($r['status'] == 'approved'): ?>
                                            <a href="<?= base_url('konsultasi/tiket/' . $r['id_konsultasi']) ?>" class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1 justify-center bg-blue-50 py-2 px-3 rounded-md border border-blue-100">
                                                <i class="fa-solid fa-ticket"></i> Lihat Tiket
                                            </a>

                                        <?php elseif ($r['status'] == 'completed'): ?>
                                            <a href="<?= base_url('kasus/timeline/' . $r['id_konsultasi']) ?>" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-md text-xs font-bold shadow transition flex items-center gap-1 justify-center">
                                                <i class="fa-solid fa-file-contract"></i> Pantau Kasus
                                            </a>

                                        <?php elseif ($r['status'] == 'reschedule'): ?>
                                            <span class="text-xs text-red-500 font-medium">Jadwal ditolak/ubah.</span>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>