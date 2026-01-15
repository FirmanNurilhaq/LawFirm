<!DOCTYPE html>
<html lang="id">

<head>
    <title>Ajukan Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-[Inter]">

    <nav class="bg-white shadow-sm border-b px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="font-bold text-xl text-slate-800">Form Pengajuan</div>
            <a href="<?= base_url('dashboard') ?>" class="text-sm text-gray-500 hover:text-amber-600">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-10">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-slate-100">

            <h2 class="text-2xl font-bold text-slate-800 mb-6 border-b pb-4">Isi Detail Permasalahan</h2>

            <?php if (session()->has('errors')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li>• <?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('konsultasi/process') ?>" method="post" enctype="multipart/form-data">

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Jenis Perkara / Topik</label>
                    <input type="text" name="judul" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 focus:outline-none" placeholder="Contoh: Sengketa Tanah Warisan" value="<?= old('judul') ?>" required>
                </div>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Kronologi Singkat / Deskripsi Masalah</label>
                    <textarea name="deskripsi" rows="4" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 focus:outline-none" placeholder="Jelaskan secara ringkas permasalahan hukum Anda..." required><?= old('deskripsi') ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-slate-700 font-bold mb-2 text-sm">Metode Konsultasi</label>
                        <select name="tipe_konsultasi" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                            <option value="online">💻 Daring (Google Meet)</option>
                            <option value="offline">🤝 Tatap Muka (Kantor)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-2 text-sm">Usulan Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal" class="w-full border-slate-300 rounded-lg p-3 border focus:ring-2 focus:ring-amber-500 focus:outline-none" value="<?= old('tanggal') ?>" required>
                        <p class="text-xs text-gray-500 mt-1">*Jadwal final akan dikonfirmasi Sekretaris.</p>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Dokumen Pendukung (PDF)</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:bg-slate-50 transition">
                        <input type="file" name="dokumen" accept="application/pdf" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-amber-50 file:text-amber-700
                            hover:file:bg-amber-100
                        " required>
                        <p class="text-xs text-gray-400 mt-2">Maksimal 5MB. Format .pdf</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="<?= base_url('dashboard') ?>" class="px-6 py-3 rounded-lg text-slate-600 hover:bg-slate-100 font-bold transition">Batal</a>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-amber-600 text-white font-bold hover:bg-amber-700 shadow-lg transition transform hover:-translate-y-1">
                        Kirim Pengajuan 🚀
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>