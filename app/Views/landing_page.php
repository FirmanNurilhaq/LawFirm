<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Konsultasi Hukum & Lawyer</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <nav class="bg-slate-900 text-white shadow-lg fixed w-full z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold tracking-wider flex items-center gap-2">
                <span class="text-amber-500">⚖️</span> FIRMA HUKUM
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="<?= base_url('/') ?>" class="hover:text-amber-500 transition">Beranda</a>
                <a href="#layanan" class="hover:text-amber-500 transition">Layanan</a>
                <a href="#tentang" class="hover:text-amber-500 transition">Tentang Kami</a>
            </div>

            <div class="flex gap-4">
                <a href="<?= base_url('login'); ?>" class="hidden md:block px-4 py-2 text-sm font-semibold text-slate-200 hover:text-white transition">
                    Masuk
                </a>
                <a href="<?= base_url('register'); ?>" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-md transition shadow-md">
                    Daftar Klien
                </a>
            </div>
        </div>
    </nav>

    <section class="relative bg-slate-900 h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="<?= base_url('assets/justice.jpg'); ?>"
                alt="Law Background"
                class="w-full h-full object-cover opacity-30">
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Keadilan yang <span class="text-amber-500">Transparan</span> & <span class="text-amber-500">Terpercaya</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                Konsultasikan permasalahan hukum Anda dengan lawyer profesional kami.
                Jadwal fleksibel, transparan, dan terintegrasi sistem.
            </p>

            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="<?= base_url('register'); ?>" class="px-8 py-4 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg shadow-lg transition transform hover:-translate-y-1">
                    Mulai Konsultasi
                </a>
                <a href="#layanan" class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-slate-900 transition">
                    Pelajari Alur
                </a>
            </div>
        </div>
    </section>

    <section id="layanan" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Kenapa Memilih Kami?</h2>
                <p class="text-gray-500 mt-2">Sistem manajemen konsultasi modern untuk efisiensi kasus Anda.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <div class="p-8 bg-slate-50 rounded-xl hover:shadow-xl transition border border-gray-100">
                    <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mb-6">
                        📅
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Penjadwalan Mudah</h3>
                    <p class="text-gray-600">
                        Atur jadwal konsultasi secara online tanpa ribet. Pilih waktu yang cocok untuk tatap muka atau daring.
                    </p>
                </div>

                <div class="p-8 bg-slate-50 rounded-xl hover:shadow-xl transition border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-6">
                        👨‍⚖️
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Lawyer Profesional</h3>
                    <p class="text-gray-600">
                        Didukung oleh lawyer yang memiliki spesialisasi jelas dan terverifikasi Berita Acara Sumpah (BAS).
                    </p>
                </div>

                <div class="p-8 bg-slate-50 rounded-xl hover:shadow-xl transition border border-gray-100">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-6">
                        📈
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Monitoring Kasus</h3>
                    <p class="text-gray-600">
                        Pantau progres penanganan kasus Anda melalui laporan kemajuan yang transparan dan *real-time*.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-10">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <span class="text-white font-bold text-lg">⚖️ Firma Hukum Digital</span>
                <p class="text-sm mt-1">Sistem Informasi Manajemen Konsultasi Hukum.</p>
            </div>
            <div class="text-sm">
                &copy; 2024 Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>

</html>