<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard Sekretaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-[Inter] text-slate-800">

    <nav class="bg-white border-b px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="bg-slate-800 text-white p-2 rounded-lg">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Panel Sekretaris</h1>
                <p class="text-xs text-gray-500">Administrator Firma</p>
            </div>
        </div>

        <div class="hidden md:flex gap-8">
            <a href="<?= base_url('dashboard') ?>" class="text-gray-500 hover:text-slate-800 font-medium pb-1">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="<?= base_url('users') ?>" class="text-gray-500 hover:text-amber-600 font-medium pb-1">
                <i class="fa-solid fa-users-gear"></i> User
            </a>
            <a href="<?= base_url('kasus') ?>" class="text-gray-500 hover:text-amber-600 font-medium pb-1">
                <i class="fa-solid fa-folder-tree"></i> Kasus
            </a>
            <a href="<?= base_url('laporan') ?>" class="text-gray-500 hover:text-amber-600 font-medium pb-1">
                <i class="fa-solid fa-print"></i> Laporan
            </a>
            <a href="<?= base_url('laporan/keuangan') ?>" class="text-gray-500 hover:text-green-600 font-medium pb-1">
                <i class="fa-solid fa-sack-dollar"></i> Keuangan
            </a>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold hidden md:block"><?= session()->get('nama') ?></span>
            <a href="<?= base_url('logout') ?>" class="text-red-500 hover:text-red-700 text-sm font-bold">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Perlu Verifikasi</p>
                    <h3 class="text-3xl font-bold text-amber-500 mt-1"><?= $count_pending ?></h3>
                </div>
                <div class="bg-amber-100 p-3 rounded-full text-amber-600">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Jadwal Aktif</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-1"><?= $count_process ?></h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h2 class="font-bold text-lg text-slate-800">Daftar Pengajuan Konsultasi</h2>
                <div class="text-xs text-gray-500">Menampilkan data terbaru</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider font-semibold">
                            <th class="p-4">Tanggal Usulan</th>
                            <th class="p-4">Klien</th>
                            <th class="p-4">Topik & Dokumen</th>
                            <th class="p-4">Metode</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($pengajuan)): ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400 italic">
                                    Belum ada pengajuan konsultasi masuk.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pengajuan as $row): ?>
                                <tr class="hover:bg-sl`ate-50 transition">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-700">
                                            <?= date('d M Y', strtotime($row['tanggal_usulan'])) ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?= date('H:i', strtotime($row['tanggal_usulan'])) ?> WIB
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-800"><?= $row['nama_klien'] ?></div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fa-brands fa-whatsapp text-green-500"></i> <?= $row['no_telp'] ?>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-medium text-slate-800 mb-1"><?= $row['jenis_perkara'] ?></div>
                                        <?php if ($row['dokumen_kelengkapan']): ?>
                                            <a href="<?= base_url('uploads/berkas/' . $row['dokumen_kelengkapan']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-600 px-2 py-1 rounded border border-red-100 hover:bg-red-100 transition">
                                                <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">Tidak ada dokumen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($row['tipe_konsultasi'] == 'online'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fa-solid fa-video"></i> Daring
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fa-solid fa-handshake"></i> Tatap Muka
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <?php
                                        $statusClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'waiting_lawyer' => 'bg-purple-100 text-purple-800', // Tambahan status baru
                                            'waiting_payment' => 'bg-orange-100 text-orange-800', // Tambahan status baru
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'completed' => 'bg-green-100 text-green-800'
                                        ];
                                        $badges = $statusClass[$row['status']] ?? 'bg-gray-100 text-gray-800';

                                        // Label agar lebih ramah dibaca
                                        $labels = [
                                            'pending' => 'Verifikasi',
                                            'waiting_lawyer' => 'Tunggu Lawyer',
                                            'waiting_payment' => 'Tunggu Bayar',
                                            'approved' => 'Terjadwal',
                                            'rejected' => 'Ditolak',
                                            'completed' => 'Selesai'
                                        ];
                                        $textLabel = $labels[$row['status']] ?? $row['status'];
                                        ?>
                                        <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wide <?= $badges ?>">
                                            <?= $textLabel ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if ($row['status'] == 'pending'): ?>
                                            <button onclick="openModalVerification(<?= $row['id_konsultasi'] ?>, '<?= $row['nama_klien'] ?>')" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-2 rounded-md text-xs font-bold shadow-md transition transform hover:scale-105">
                                                <i class="fa-solid fa-gavel"></i> Proses
                                            </button>
                                        <?php elseif ($row['status'] == 'completed'): ?>
                                            <button class="text-green-600 font-bold text-xs" disabled>
                                                <i class="fa-solid fa-check-double"></i> Tuntas
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs italic">Dalam Proses</span>
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

    <script>
        function openModalVerification(id, nama) {
            window.location.href = "<?= base_url('konsultasi/verifikasi/') ?>" + id;
        }
    </script>
</body>

</html>