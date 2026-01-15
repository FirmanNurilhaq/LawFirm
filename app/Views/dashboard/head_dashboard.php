<!DOCTYPE html>
<html lang="id">

<head>
    <title>Executive Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 font-sans text-slate-800">

    <nav class="bg-slate-900 text-white px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="bg-amber-500 text-slate-900 p-2 rounded-lg font-bold">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Executive Panel</h1>
                <p class="text-xs text-gray-400">Monitoring & Statistik</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <span class="text-sm font-semibold">Selamat Datang, <?= session()->get('nama') ?></span>
            <a href="<?= base_url('logout') ?>" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-xs font-bold transition">Keluar</a>
        </div>
    </nav>

    <div class="container mx-auto px-8 py-10">

        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fa-solid fa-bullseye text-amber-500"></i> Ringkasan Performa
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Total Pendapatan</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">
                            Rp <?= number_format($total_income ?? 0, 0, ',', '.') ?>
                        </h3>
                    </div>
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Total Kasus Selesai</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">
                            <?= $total_kasus ?> <span class="text-sm text-gray-400 font-normal">Kasus</span>
                        </h3>
                    </div>
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <i class="fa-solid fa-gavel"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Total Klien</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">
                            <?= $total_klien ?> <span class="text-sm text-gray-400 font-normal">Orang</span>
                        </h3>
                    </div>
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Total Advokat</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">
                            <?= $total_lawyer ?> <span class="text-sm text-gray-400 font-normal">Orang</span>
                        </h3>
                    </div>
                    <div class="bg-orange-100 text-orange-600 p-3 rounded-full">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md lg:col-span-2">
                <h3 class="font-bold text-lg mb-4 text-slate-700">Grafik Pendapatan Tahun Ini</h3>
                <canvas id="incomeChart" height="150"></canvas>
            </div>

            <div class="bg-slate-800 text-white p-6 rounded-xl shadow-md">
                <h3 class="font-bold text-lg mb-4 text-amber-400">Informasi Sistem</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between border-b border-slate-700 pb-2">
                        <span>Server Status</span>
                        <span class="text-green-400 font-bold">Online 🟢</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-700 pb-2">
                        <span>Database</span>
                        <span class="text-blue-400 font-bold">Connected</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-700 pb-2">
                        <span>Versi Aplikasi</span>
                        <span class="text-gray-400">v1.0.0 (Stable)</span>
                    </li>
                    <li class="flex justify-between pt-2">
                        <span>Tanggal Server</span>
                        <span><?= date('d M Y') ?></span>
                    </li>
                </ul>

                <div class="mt-8 p-4 bg-slate-700 rounded-lg text-center">
                    <p class="text-xs text-gray-400 mb-2">Butuh laporan detail?</p>
                    <button disabled class="bg-gray-600 text-gray-400 px-4 py-2 rounded text-xs font-bold cursor-not-allowed">
                        Hubungi Sekretaris
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',

                    // PERBAIKAN DISINI: Gunakan variabel $grafik_pendapatan
                    data: <?= $grafik_pendapatan ?>,

                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>