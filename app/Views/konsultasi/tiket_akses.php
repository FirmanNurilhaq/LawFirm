<!DOCTYPE html>
<html lang="id">

<head>
    <title>E-Ticket Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-900 font-[Inter] flex items-center justify-center min-h-screen py-10 px-4">

    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

        <div class="w-full md:w-2/3 p-8 relative">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-xs font-bold text-slate-400 tracking-widest uppercase">Tiket Konsultasi</h1>
                    <h2 class="text-2xl font-bold text-slate-800 mt-1">Hukum & Keadilan</h2>
                </div>
                <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                    <?= $k['status'] ?>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="bg-amber-100 p-3 rounded-xl text-amber-600">
                        <i class="fa-regular fa-calendar-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Jadwal Pelaksanaan</p>
                        <p class="font-bold text-slate-800 text-lg"><?= date('d F Y', strtotime($k['tanggal_fiksasi'])) ?></p>
                        <p class="text-slate-600"><?= date('H:i', strtotime($k['tanggal_fiksasi'])) ?> WIB</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                        <i class="fa-solid fa-user-tie text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Konsultan Hukum</p>
                        <p class="font-bold text-slate-800 text-lg"><?= $k['nama_lawyer'] ?></p>
                        <p class="text-slate-600 text-sm"><?= $k['spesialisasi'] ?? 'Advokat Umum' ?></p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-2">Akses Masuk / Lokasi</p>
                    <?php if ($k['tipe_konsultasi'] == 'online'): ?>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-video text-purple-600"></i>
                            <span class="font-bold text-slate-700">Google Meet / Zoom</span>
                        </div>
                        <a href="<?= $k['meeting'] ?>" target="_blank" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow transition">
                            Gabung Rapat Sekarang 🚀
                        </a>
                        <p class="text-xs text-gray-400 mt-2 text-center">Klik tombol di atas saat jadwal dimulai.</p>
                    <?php else: ?>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-building text-orange-600"></i>
                            <span class="font-bold text-slate-700">Kantor Firma Hukum</span>
                        </div>
                        <p class="text-slate-800 font-medium"><?= $k['meeting'] ?></p>
                        <p class="text-xs text-gray-400 mt-2">Harap datang 15 menit sebelum jadwal dimulai.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/3 bg-slate-100 border-l border-dashed border-slate-300 p-8 flex flex-col items-center justify-center relative">
            <div class="w-6 h-6 bg-slate-900 rounded-full absolute -top-3 -left-3"></div>
            <div class="w-6 h-6 bg-slate-900 rounded-full absolute -bottom-3 -left-3"></div>

            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=KONSUL-<?= $k['id_konsultasi'] ?>" alt="QR Code" class="w-32 h-32 mix-blend-multiply opacity-80">
            <p class="text-xs text-gray-400 mt-4 text-center">Tunjukkan QR ini jika konsultasi Tatap Muka.</p>

            <a href="<?= base_url('dashboard') ?>" class="mt-8 text-sm font-bold text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>
    </div>

</body>

</html>