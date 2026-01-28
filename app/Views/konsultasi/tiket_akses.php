<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tiket Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-900 font-[Inter] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

        <div class="bg-slate-800 text-white p-10 md:w-2/5 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="bg-white/10 w-12 h-12 rounded-lg flex items-center justify-center mb-6">
                    <i class="fa-solid fa-scale-balanced text-2xl text-amber-500"></i>
                </div>
                <h2 class="text-2xl font-bold leading-tight mb-2">Hukum & Keadilan</h2>
                <p class="text-slate-400 text-sm">Konsultasi Hukum Profesional</p>
            </div>

            <div class="relative z-10 mt-10">
                <p class="text-xs text-slate-500 uppercase tracking-widest mb-2">ID Tiket</p>
                <p class="font-mono text-xl text-amber-500 tracking-wider">#<?= str_pad($k['id_konsultasi'], 6, '0', STR_PAD_LEFT) ?></p>
            </div>

            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-amber-500 rounded-full opacity-10 blur-2xl"></div>
            <div class="absolute top-10 right-10 w-20 h-20 bg-blue-500 rounded-full opacity-10 blur-xl"></div>
        </div>

        <div class="p-10 md:w-3/5 bg-white relative">
            <div class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-900 rounded-full hidden md:block"></div>

            <div class="flex justify-between items-start mb-8">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Tiket Konsultasi</p>
                    <h1 class="text-2xl font-bold text-slate-800"><?= $k['jenis_perkara'] ?></h1>
                </div>
                <?php if ($k['status'] == 'approved'): ?>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">APPROVED</span>
                <?php elseif ($k['status'] == 'completed'): ?>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold border border-gray-200">SELESAI</span>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="bg-amber-50 text-amber-600 w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-calendar-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Jadwal Pelaksanaan</p>
                        <p class="font-bold text-slate-800 text-lg"><?= date('d F Y', strtotime($k['tanggal_fiksasi'])) ?></p>
                        <p class="text-sm text-gray-500"><?= date('H:i', strtotime($k['tanggal_fiksasi'])) ?> WIB</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="bg-blue-50 text-blue-600 w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user-tie text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Konsultan Hukum</p>
                        <p class="font-bold text-slate-800 text-lg"><?= $k['nama_lawyer'] ?></p>
                        <p class="text-sm text-gray-500"><?= $k['spesialisasi'] ?? 'Advokat Umum' ?></p>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200 my-6"></div>

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-center">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-3">Akses Masuk / Lokasi</p>

                    <?php if ($k['tipe_konsultasi'] == 'online'): ?>
                        <div class="flex items-center justify-center gap-2 mb-4 text-slate-700 font-semibold">
                            <i class="fa-solid fa-video text-purple-500"></i> Google Meet / Zoom
                        </div>

                        <?php
                        // LOGIKA LINK: Cek apakah inputan lawyer ada 'http' nya
                        $link = $k['meeting'];
                        if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
                            // Jika tidak ada https, kita tambahkan manual
                            $link = "https://" . $link;
                        }
                        ?>

                        <a href="<?= $link ?>" target="_blank" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-purple-200 transition transform hover:scale-105">
                            Gabung Rapat Sekarang <i class="fa-solid fa-rocket ml-2"></i>
                        </a>
                        <p class="text-[10px] text-gray-400 mt-2">Klik tombol di atas saat jadwal dimulai.</p>

                    <?php else: ?>
                        <div class="flex items-center justify-center gap-2 mb-4 text-slate-700 font-semibold">
                            <i class="fa-solid fa-building text-orange-500"></i> Tatap Muka (Offline)
                        </div>
                        <p class="text-sm text-slate-800 font-medium bg-white p-3 rounded border border-gray-200">
                            <?= $k['meeting'] ?>
                        </p>
                        <p class="text-[10px] text-gray-400 mt-2">Harap datang 15 menit sebelum jadwal.</p>
                    <?php endif; ?>

                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="<?= base_url('dashboard') ?>" class="text-gray-400 hover:text-slate-800 text-sm font-semibold transition">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

</body>

</html>