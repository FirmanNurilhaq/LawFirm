<!DOCTYPE html>
<html lang="id">

<head>
    <title>Verifikasi Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-[Inter] text-slate-800 pb-10">

    <nav class="bg-white border-b px-6 py-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="font-bold text-lg">🔍 Verifikasi Pengajuan</h1>
            <a href="<?= base_url('dashboard') ?>" class="text-sm text-gray-500 hover:text-red-600">
                <i class="fa-solid fa-xmark"></i> Batal & Kembali
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-500 text-xs uppercase tracking-wider mb-4 border-b pb-2">Data Klien</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 block">Nama Lengkap</label>
                        <div class="font-semibold text-slate-800"><?= $k['nama_klien'] ?></div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block">Kontak</label>
                        <div class="text-sm text-slate-800"><?= $k['email'] ?></div>
                        <div class="text-sm text-slate-800"><?= $k['no_telp'] ?></div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-500 text-xs uppercase tracking-wider mb-4 border-b pb-2">Detail Masalah</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-400 block">Topik Perkara</label>
                        <div class="font-bold text-amber-600"><?= $k['jenis_perkara'] ?></div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block">Deskripsi / Kronologi</label>
                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-3 rounded border border-slate-100">
                            "<?= $k['deskripsi_masalah'] ?>"
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Dokumen Pendukung</label>
                        <?php if ($k['dokumen_kelengkapan']): ?>
                            <a href="<?= base_url('uploads/berkas/' . $k['dokumen_kelengkapan']) ?>" target="_blank" class="block w-full text-center bg-red-50 text-red-600 py-2 rounded text-sm font-bold border border-red-100 hover:bg-red-100 transition">
                                <i class="fa-solid fa-file-pdf"></i> Download PDF
                            </a>
                        <?php else: ?>
                            <span class="text-xs text-gray-400 italic">Tidak ada dokumen dilampirkan.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="bg-slate-800 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                    Penetapan Jadwal & Lawyer
                </h2>

                <form action="<?= base_url('konsultasi/process-verifikasi') ?>" method="post">
                    <input type="hidden" name="id_konsultasi" value="<?= $k['id_konsultasi'] ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <label class="block text-blue-800 font-bold mb-1 text-xs uppercase">Usulan Klien</label>
                            <div class="font-mono text-lg font-bold text-blue-900">
                                <?= date('d M Y, H:i', strtotime($k['tanggal_usulan'])) ?>
                            </div>
                            <div class="mt-1 text-xs text-blue-600">
                                Metode: <b><?= strtoupper($k['tipe_konsultasi']) ?></b>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 font-bold mb-2 text-sm">Tetapkan Tanggal (Fiksasi)</label>
                            <input type="datetime-local" name="tanggal_fiksasi" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 outline-none" value="<?= $k['tanggal_usulan'] ?>" required>
                            <p class="text-xs text-gray-500 mt-1">Ubah jam/tanggal jika ingin reschedule.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-slate-700 font-bold mb-2 text-sm">Pilih Lawyer Penanggung Jawab</label>
                        <select name="no_bas" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 outline-none bg-white" required>
                            <option value="" disabled selected>-- Pilih Lawyer Tersedia --</option>
                            <?php foreach ($lawyers as $lawyer): ?>
                                <option value="<?= $lawyer['no_bas'] ?>">
                                    <?= $lawyer['nama'] ?> (Spesialis: <?= $lawyer['spesialisasi'] ?? 'Umum' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="block text-slate-700 font-bold mb-2 text-sm">
                            <?php echo ($k['tipe_konsultasi'] == 'online') ? 'Link Google Meet / Zoom' : 'Nama Ruangan / Lokasi Kantor'; ?>
                        </label>
                        <input type="text" name="meeting" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 outline-none"
                            placeholder="<?php echo ($k['tipe_konsultasi'] == 'online') ? 'https://meet.google.com/...' : 'Ruang Meeting Lt. 2'; ?>" required>
                    </div>

                    <div class="border-t pt-6 flex justify-between items-center">
                        <div class="relative group">
                            <button type="submit" name="action" value="reject" class="text-red-500 font-bold hover:text-red-700 text-sm px-4 py-2 hover:bg-red-50 rounded transition" formnovalidate onclick="return confirm('Yakin tolak pengajuan ini?')">
                                ✘ Tolak Pengajuan
                            </button>
                        </div>

                        <button type="submit" name="action" value="approve" class="bg-slate-800 text-white px-8 py-3 rounded-lg font-bold hover:bg-slate-900 shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                            <i class="fa-solid fa-check"></i> Setujui & Terbitkan Invoice
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script>
        // Update dashboard/secretary_dashboard.php dibagian script
        // window.location.href = "<?= base_url('konsultasi/verifikasi/') ?>" + id;
    </script>
</body>

</html>