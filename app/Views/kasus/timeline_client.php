<!DOCTYPE html>
<html lang="id">

<head>
    <title>Timeline Kasus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-50 font-[Inter] min-h-screen">

    <div class="bg-white border-b sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="font-bold text-lg text-slate-800">📋 Monitoring Kasus</h1>
            <a href="<?= base_url('dashboard') ?>" class="text-sm font-bold text-slate-500 hover:text-amber-600">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="container mx-auto px-6 py-10 max-w-4xl">

        <div class="bg-slate-900 text-white p-8 rounded-2xl shadow-xl mb-10 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Topik Perkara</p>
                <h2 class="text-2xl font-bold mb-4"><?= $k['jenis_perkara'] ?></h2>

                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-amber-500"></i>
                        <span>Lawyer: <b><?= $k['nama_lawyer'] ?></b></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-amber-500"></i>
                        <span>Mulai: <?= date('d M Y', strtotime($k['tanggal_fiksasi'])) ?></span>
                    </div>
                </div>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-slate-800 to-transparent opacity-50"></div>
        </div>

        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-amber-600"></i> Riwayat Perkembangan
        </h3>

        <?php if (empty($riwayat)): ?>
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-slate-300">
                <div class="inline-block p-4 bg-slate-50 rounded-full mb-3 text-slate-300">
                    <i class="fa-solid fa-hourglass-half text-4xl"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada update laporan dari Lawyer.</p>
                <p class="text-xs text-gray-400">Mohon cek kembali secara berkala.</p>
            </div>
        <?php else: ?>
            <div class="relative border-l-4 border-slate-200 ml-4 space-y-10 pb-10">
                <?php foreach ($riwayat as $r): ?>
                    <div class="relative pl-8 group">
                        <div class="absolute -left-3 top-0 bg-white border-4 <?= ($r['status_kasus'] == 'closed') ? 'border-red-500' : 'border-amber-500' ?> w-6 h-6 rounded-full group-hover:scale-125 transition"></div>

                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-lg transition transform hover:-translate-y-1">
                            <div class="flex justify-between items-start mb-3 border-b border-slate-50 pb-3">
                                <div>
                                    <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded">
                                        <?= date('l, d F Y', strtotime($r['tanggal_laporan'])) ?>
                                    </span>
                                </div>
                                <?php if ($r['status_kasus'] == 'closed'): ?>
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                        <i class="fa-solid fa-lock"></i> Kasus Ditutup
                                    </span>
                                <?php else: ?>
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                        <i class="fa-solid fa-rotate"></i> Sedang Berjalan
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h4 class="font-bold text-lg text-slate-800 mb-4 leading-relaxed">
                                "<?= $r['progres'] ?>"
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg">
                                <div>
                                    <p class="text-xs text-slate-400 font-bold uppercase mb-1">Tindakan Hukum Diambil</p>
                                    <p class="text-sm text-slate-700 leading-snug"><?= $r['tindakan'] ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-bold uppercase mb-1">Rencana Selanjutnya</p>
                                    <p class="text-sm text-amber-700 font-semibold leading-snug">
                                        <i class="fa-solid fa-arrow-right"></i> <?= $r['rencana'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>