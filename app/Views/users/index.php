<!DOCTYPE html>
<html lang="id">

<head>
    <title>Kelola Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 p-8 font-sans">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">👥 Manajemen Pengguna</h1>
            <div class="flex gap-3">
                <a href="<?= base_url('dashboard') ?>" class="text-gray-500 hover:text-slate-800 font-bold py-2 px-4">Kembali</a>
                <a href="<?= base_url('users/form') ?>" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded shadow">
                    <i class="fa-solid fa-plus"></i> Tambah User Baru
                </a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden">
            <div class="bg-slate-800 text-white px-6 py-3 flex justify-between items-center">
                <h3 class="font-bold"><i class="fa-solid fa-user-tie mr-2"></i> Daftar Lawyer</h3>
                <span class="bg-slate-700 text-xs px-2 py-1 rounded"><?= count($lawyers) ?> Orang</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="p-4">Nama & No BAS</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Spesialisasi</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php foreach ($lawyers as $l): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <div class="font-bold text-slate-800"><?= $l['nama'] ?></div>
                                <div class="text-xs text-amber-600 font-mono"><?= $l['no_bas'] ?></div>
                            </td>
                            <td class="p-4">
                                <div><?= $l['email'] ?></div>
                                <div class="text-xs text-gray-500"><?= $l['no_telp'] ?></div>
                            </td>
                            <td class="p-4"><?= $l['spesialisasi'] ?></td>
                            <td class="p-4">
                                <?php if ($l['available']): ?>
                                    <span class="text-green-600 font-bold text-xs">● Aktif</span>
                                <?php else: ?>
                                    <span class="text-red-500 font-bold text-xs">● Cuti / Sibuk</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-center flex justify-center gap-2">
                                <a href="<?= base_url('users/form/' . $l['id_user']) ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= base_url('users/delete/' . $l['id_user']) ?>" onclick="return confirm('Hapus user ini?')" class="text-red-600 hover:bg-red-50 p-2 rounded"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-slate-100 text-slate-800 px-6 py-3 flex justify-between items-center border-b">
                <h3 class="font-bold"><i class="fa-solid fa-users mr-2"></i> Daftar Klien Terdaftar</h3>
                <span class="bg-white border text-xs px-2 py-1 rounded text-gray-600"><?= count($clients) ?> Orang</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">No. Telp</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php foreach ($clients as $c): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-bold text-slate-700"><?= $c['nama'] ?></td>
                            <td class="p-4"><?= $c['email'] ?></td>
                            <td class="p-4 text-gray-500"><?= $c['no_telp'] ?></td>
                            <td class="p-4 text-center flex justify-center gap-2">
                                <a href="<?= base_url('users/form/' . $c['id_user']) ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= base_url('users/delete/' . $c['id_user']) ?>" onclick="return confirm('Hapus klien ini?')" class="text-red-600 hover:bg-red-50 p-2 rounded"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>