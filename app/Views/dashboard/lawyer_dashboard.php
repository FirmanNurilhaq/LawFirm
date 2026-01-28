<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard Lawyer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-[Inter] text-slate-800">

    <nav class="bg-white border-b px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="bg-slate-800 text-white p-2 rounded-lg">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Area Lawyer</h1>
                <p class="text-xs text-gray-500">Kelola Jadwal & Kasus</p>
            </div>
        </div>

        <div class="hidden md:flex gap-6">
            <a href="<?= base_url('dashboard') ?>" class="text-slate-800 font-bold border-b-2 border-slate-800 pb-1">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="<?= base_url('kasus') ?>" class="text-gray-500 hover:text-amber-600 font-medium transition pb-1">
                <i class="fa-solid fa-folder-open"></i> Manajemen Kasus
            </a>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold"><?= session()->get('nama') ?></span>
            <a href="<?= base_url('logout') ?>" class="text-red-500 hover:text-red-700 text-sm font-bold">Keluar</a>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">

        <?php if (session()->getFlashdata('message')): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <div>
                    <p class="font-bold">Info</p>
                    <p><?= session()->getFlashdata('message') ?></p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700"><i class="fa-solid fa-times"></i></button>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">

            <div class="flex items-center gap-4">
                <?php if ($user['available'] == 1): ?>
                    <div class="bg-green-100 text-green-600 p-4 rounded-full animate-pulse">
                        <i class="fa-solid fa-wifi text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Status: AVAILABLE (Aktif)</h2>
                        <p class="text-sm text-gray-500">Anda sedang <span class="text-green-600 font-bold">Online</span>. Klien dapat memilih Anda untuk konsultasi baru.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-100 text-gray-500 p-4 rounded-full">
                        <i class="fa-solid fa-bed text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Status: CUTI / SIBUK</h2>
                        <p class="text-sm text-gray-500">Anda sedang <span class="text-red-500 font-bold">Offline</span>. Nama Anda disembunyikan dari daftar pencarian klien.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <form action="<?= base_url('dashboard/update_availability') ?>" method="post">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" onchange="this.form.submit()" <?= ($user['available'] == 1) ? 'checked' : '' ?>>

                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>

                        <span class="ml-3 text-sm font-medium text-gray-900">
                            <?= ($user['available'] == 1) ? 'Ubah ke Cuti' : 'Ubah ke Aktif' ?>
                        </span>
                    </label>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="<?= base_url('kasus') ?>" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4 hover:shadow-md transition hover:border-amber-400 group">
                <div class="bg-amber-100 text-amber-600 w-12 h-12 rounded-full flex items-center justify-center text-xl group-hover:bg-amber-600 group-hover:text-white transition">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Update Perkembangan</h3>
                    <p class="text-xs text-gray-500">Catat progres kasus klien.</p>
                </div>
            </a>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800"><?= count($jadwal_aktif) ?> Jadwal</h3>
                    <p class="text-xs text-gray-500">Aktif & Mendatang</p>
                </div>
            </div>
        </div>

        <?php if (!empty($tugas_baru)): ?>
            <div class="mb-10">
                <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bell text-amber-500 animate-bounce"></i> Permintaan Konfirmasi Jadwal
                </h2>

                <div class="grid gap-4">
                    <?php foreach ($tugas_baru as $tugas): ?>
                        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-amber-500 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-0.5 rounded uppercase">Perlu Konfirmasi</span>
                                    <span class="text-xs text-gray-500"><?= date('d M Y', strtotime($tugas['tanggal_fiksasi'])) ?></span>
                                </div>
                                <h3 class="font-bold text-lg text-slate-800"><?= $tugas['jenis_perkara'] ?></h3>
                                <p class="text-sm text-gray-600">Klien: <b><?= $tugas['nama_klien'] ?></b> | Via: <?= strtoupper($tugas['tipe_konsultasi']) ?></p>
                                <p class="text-xs text-gray-400 mt-1">Usulan Waktu: <b><?= date('H:i', strtotime($tugas['tanggal_fiksasi'])) ?> WIB</b></p>
                            </div>

                            <div class="flex items-center gap-3">
                                <form action="<?= base_url('konsultasi/lawyer-response') ?>" method="post" class="flex gap-2">
                                    <input type="hidden" name="id_konsultasi" value="<?= $tugas['id_konsultasi'] ?>">
                                    <button type="submit" name="keputusan" value="reschedule" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition">
                                        Minta Reschedule
                                    </button>
                                    <button type="submit" name="keputusan" value="confirm" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-900 shadow-lg transition">
                                        <i class="fa-solid fa-check"></i> Saya Bersedia
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <h2 class="text-lg font-bold text-slate-800 mb-4 border-t pt-8 flex items-center gap-2">
            <i class="fa-solid fa-briefcase text-slate-600"></i> Jadwal Aktif & Mendatang
        </h2>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Klien & Topik</th>
                        <th class="p-4">Akses / Lokasi</th>
                        <th class="p-4 text-center">Aksi / Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (empty($jadwal_aktif)): ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400 italic">
                                Belum ada jadwal aktif saat ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($jadwal_aktif as $ja): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 align-top">
                                    <div class="font-bold text-slate-800"><?= date('d M Y', strtotime($ja['tanggal_fiksasi'])) ?></div>
                                    <div class="text-blue-600 font-bold text-lg"><?= date('H:i', strtotime($ja['tanggal_fiksasi'])) ?></div>
                                    <div class="text-xs text-gray-400">WIB</div>
                                </td>

                                <td class="p-4 align-top">
                                    <div class="font-bold text-slate-700"><?= $ja['nama_klien'] ?? 'Klien' ?></div>
                                    <div class="text-xs text-gray-500 mb-1"><?= $ja['jenis_perkara'] ?></div>
                                    <?php if ($ja['dokumen_kelengkapan']): ?>
                                        <a href="<?= base_url('uploads/berkas/' . $ja['dokumen_kelengkapan']) ?>" target="_blank" class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded hover:bg-slate-200">
                                            <i class="fa-solid fa-paperclip"></i> Lihat Dokumen
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <td class="p-4 align-top">
                                    <?php if ($ja['tipe_konsultasi'] == 'online'): ?>
                                        <div class="mb-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 uppercase">Online</span>
                                        </div>
                                        <?php if ($ja['status'] == 'approved'): ?>
                                            <a href="<?= $ja['link_meeting'] ?? '#' ?>" target="_blank" class="text-blue-600 hover:underline font-bold text-xs flex items-center gap-1">
                                                <i class="fa-solid fa-video"></i> Link Meeting
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs italic">Link tersedia setelah bayar</span>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <div class="mb-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 uppercase">Tatap Muka</span>
                                        </div>
                                        <span class="text-slate-600 font-medium text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-map-pin"></i> Kantor / <?= $ja['link_meeting'] ?? 'Lokasi ditentukan' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="p-4 text-center align-middle">
                                    <?php if ($ja['status'] == 'waiting_payment'): ?>
                                        <div class="flex flex-col gap-2 items-center">
                                            <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded border border-orange-100 uppercase tracking-wide">
                                                <i class="fa-solid fa-hourglass-half animate-pulse"></i> Menunggu Bayar
                                            </span>
                                            <button disabled class="w-full bg-slate-100 text-slate-400 px-3 py-1.5 rounded text-xs font-bold cursor-not-allowed border border-slate-200 shadow-inner">
                                                <i class="fa-solid fa-lock"></i> Terkunci
                                            </button>
                                        </div>

                                    <?php elseif ($ja['status'] == 'approved'): ?>
                                        <div class="flex flex-col gap-2">
                                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded border border-green-100 uppercase tracking-wide">
                                                <i class="fa-solid fa-circle-check"></i> Siap Dilayani
                                            </span>
                                            <a href="<?= base_url('konsultasi/selesai/' . $ja['id_konsultasi']) ?>" onclick="return confirm('Apakah sesi konsultasi sudah selesai dilaksanakan? Kasus akan lanjut ke tahap monitoring.')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-xs font-bold shadow transition flex items-center justify-center gap-1 transform hover:scale-105">
                                                <i class="fa-solid fa-check-double"></i> Selesai Sesi
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>