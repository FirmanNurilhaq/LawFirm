<!DOCTYPE html>
<html lang="id">

<head>
    <title>Manajemen Kasus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 p-8">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">📂 Kasus & Klien Saya</h1>
            <a href="<?= base_url('dashboard') ?>" class="text-blue-600 hover:underline">Kembali ke Dashboard</a>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 text-slate-600 uppercase text-sm font-bold">
                    <th class="p-4">Klien</th>
                    <th class="p-4">Perkara</th>
                    <th class="p-4">Status Terakhir</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kasus_list as $row): ?>
                    <tr class="border-b hover:bg-slate-50">
                        <td class="p-4 font-bold"><?= $row['nama_klien'] ?></td>
                        <td class="p-4"><?= $row['jenis_perkara'] ?></td>
                        <td class="p-4">
                            <?php if ($row['status_kasus'] == 'closed'): ?>
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">DITUTUP</span>
                            <?php elseif ($row['status_kasus'] == 'open'): ?>
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">BERJALAN</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs">Belum Ada Update</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <a href="<?= base_url('kasus/update/' . $row['id_konsultasi']) ?>" class="bg-slate-800 text-white px-3 py-2 rounded text-xs font-bold hover:bg-slate-900">
                                <i class="fa-solid fa-pen-to-square"></i> Update Progres
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>