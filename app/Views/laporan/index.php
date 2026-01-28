<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 p-10 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg">
        <div class="text-center mb-6">
            <div class="inline-block p-3 rounded-full bg-slate-100 text-slate-800 mb-2">
                <i class="fa-solid fa-print text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Cetak Laporan</h1>
            <p class="text-gray-500 text-sm">Pilih periode laporan konsultasi hukum</p>
        </div>

        <form action="<?= base_url('laporan/cetak') ?>" method="get" target="_blank">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="tgl_awal" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" class="w-full border rounded p-2" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Konsultasi</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="all">Semua Status</option>
                    <option value="completed">Selesai (Completed)</option>
                    <option value="approved">Akan Datang (Approved)</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 rounded shadow transition flex justify-center items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Generate Laporan
            </button>

            <a href="<?= base_url('dashboard') ?>" class="block text-center text-sm text-gray-500 mt-4 hover:text-slate-800">Kembali ke Dashboard</a>
        </form>
    </div>

</body>

</html>