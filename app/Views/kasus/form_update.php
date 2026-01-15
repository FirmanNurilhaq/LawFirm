<!DOCTYPE html>
<html lang="id">

<head>
    <title>Update Kasus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 p-8 font-sans">

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="md:col-span-1">

            <?php
            $isClosed = false;
            // Cek index 0 (data terbaru) dari riwayat
            if (!empty($riwayat_update) && $riwayat_update[0]['status_kasus'] == 'closed') {
                $isClosed = true;
            }
            ?>

            <?php if ($isClosed): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> KASUS DITUTUP
                    </p>
                    <p class="text-xs mt-1">Kasus ini telah selesai. Anda tidak dapat menambahkan laporan baru lagi.</p>
                </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-amber-500">
                <h2 class="font-bold text-lg mb-4">📝 Tulis Laporan Baru</h2>

                <div class="mb-4 p-3 bg-amber-50 rounded border border-amber-100">
                    <p class="text-xs text-gray-500 uppercase font-bold">Klien</p>
                    <p class="font-bold text-slate-800"><?= $k['nama_klien'] ?></p>
                    <p class="text-xs text-gray-500 mt-2 uppercase font-bold">Perkara</p>
                    <p class="text-sm text-slate-800"><?= $k['jenis_perkara'] ?></p>
                </div>

                <?php if (!$isClosed): ?>
                    <form action="<?= base_url('kasus/process') ?>" method="post">
                        <input type="hidden" name="id_konsultasi" value="<?= $k['id_konsultasi'] ?>">

                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Status Kasus</label>
                            <select name="status_kasus" class="w-full border rounded p-2 bg-white">
                                <option value="open">🟢 Kasus Berjalan (Open)</option>
                                <option value="closed">🔴 Kasus Selesai (Closed)</option>
                            </select>
                            <p class="text-[10px] text-red-500 mt-1">*Jika dipilih "Closed", kasus akan terkunci permanen.</p>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Progres Terkini</label>
                            <textarea name="progres" rows="3" class="w-full border rounded p-2 text-sm" placeholder="Contoh: Telah dilakukan mediasi tahap 1..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tindakan Hukum</label>
                            <textarea name="tindakan" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Apa yang sudah lawyer lakukan?" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Rencana Selanjutnya</label>
                            <textarea name="rencana" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Apa langkah berikutnya?" required></textarea>
                        </div>

                        <button type="submit" class="w-full bg-slate-800 text-white font-bold py-2 rounded hover:bg-slate-900 transition shadow-lg">
                            Simpan Laporan
                        </button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-8 border-t border-dashed border-gray-200 mt-4 opacity-50">
                        <div class="inline-block p-4 bg-gray-100 rounded-full mb-2">
                            <i class="fa-solid fa-file-circle-check text-4xl text-gray-400"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-500">Formulir Non-Aktif</p>
                    </div>
                <?php endif; ?>

                <a href="<?= base_url('kasus') ?>" class="block text-center text-sm text-gray-500 mt-4 hover:text-slate-800 font-bold">Kembali ke Daftar</a>
            </div>
        </div>

        <div class="md:col-span-2">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Riwayat Perkembangan</h2>

            <?php if (empty($riwayat_update)): ?>
                <div class="text-center py-10 bg-white rounded-xl shadow-sm">
                    <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada catatan perkembangan kasus.</p>
                </div>
            <?php else: ?>
                <div class="relative border-l-4 border-slate-200 ml-4 space-y-8">
                    <?php foreach ($riwayat_update as $r): ?>
                        <div class="relative pl-8 group">
                            <div class="absolute -left-3 top-0 bg-white border-4 <?= ($r['status_kasus'] == 'closed') ? 'border-red-500' : 'border-slate-800' ?> w-6 h-6 rounded-full group-hover:scale-110 transition"></div>

                            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded">
                                        <?= date('d M Y', strtotime($r['tanggal_laporan'])) ?>
                                    </span>
                                    <?php if ($r['status_kasus'] == 'closed'): ?>
                                        <span class="text-red-600 font-bold text-xs uppercase border border-red-200 px-2 py-1 rounded bg-red-50">
                                            Case Closed
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h3 class="font-bold text-lg text-slate-800 mb-2"><?= $r['progres'] ?></h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-4 pt-4 border-t border-dashed border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase">Tindakan</p>
                                        <p class="text-slate-600"><?= $r['tindakan'] ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase">Rencana</p>
                                        <p class="text-amber-600 font-medium"><?= $r['rencana'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>