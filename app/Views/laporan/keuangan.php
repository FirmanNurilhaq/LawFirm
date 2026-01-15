<!DOCTYPE html>
<html lang="id">

<head>
    <title>Riwayat Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 p-8 font-sans">

    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">💰 Laporan Keuangan</h1>
                <p class="text-gray-500">Rekapitulasi pendapatan dari konsultasi</p>
            </div>
            <div class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg text-right">
                <p class="text-xs font-bold uppercase opacity-80">Total Pendapatan</p>
                <h2 class="text-3xl font-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h2>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold border-b">
                    <tr>
                        <th class="p-4">Tanggal Bayar</th>
                        <th class="p-4">Klien</th>
                        <th class="p-4">Lawyer (Penerima)</th>
                        <th class="p-4 text-right">Nominal (IDR)</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php foreach ($transaksi as $t): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 text-gray-500">
                                <?= date('d M Y H:i', strtotime($t['updated_at'])) ?>
                            </td>
                            <td class="p-4 font-bold text-slate-700"><?= $t['nama_klien'] ?></td>
                            <td class="p-4 text-slate-600"><?= $t['nama_lawyer'] ?></td>
                            <td class="p-4 text-right font-mono font-bold text-green-600">
                                Rp <?= number_format($t['harga_konsultasi'], 0, ',', '.') ?>
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">LUNAS</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-center">
            <a href="<?= base_url('dashboard') ?>" class="text-gray-500 font-bold hover:text-slate-800">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>