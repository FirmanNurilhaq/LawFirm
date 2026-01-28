<!DOCTYPE html>
<html lang="id">

<head>
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 p-10 flex justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-slate-800 mb-6"><?= $title ?></h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('users/save') ?>" method="post">
            <input type="hidden" name="id_user" value="<?= $user['id_user'] ?? '' ?>">

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Peran Pengguna (Role)</label>
                <select name="role" id="roleSelect" class="w-full border rounded p-2 bg-slate-50" onchange="toggleLawyerFields()">
                    <option value="client" <?= ($user && $user['role'] == 'client') ? 'selected' : '' ?>>Klien</option>
                    <option value="lawyer" <?= ($user && $user['role'] == 'lawyer') ? 'selected' : '' ?>>Lawyer</option>
                    <option value="sekretaris" <?= ($user && $user['role'] == 'sekretaris') ? 'selected' : '' ?>>Sekretaris</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $user['nama'] ?? '' ?>" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" class="w-full border rounded p-2" required>
                </div>
            </div>

            <div class="mb-4 bg-yellow-50 p-3 rounded border border-yellow-200">
                <label class="block text-sm font-bold text-yellow-800 mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" placeholder="<?= $user ? 'Kosongkan jika tidak ingin mengubah password' : 'Wajib diisi untuk user baru' ?>">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="no_telp" value="<?= $user['no_telp'] ?? '' ?>" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                    <input type="text" name="alamat" value="<?= $user['alamat'] ?? '' ?>" class="w-full border rounded p-2">
                </div>
            </div>

            <div id="lawyerFields" class="hidden border-t pt-4 mt-4 bg-slate-50 p-4 rounded border-slate-200">
                <h3 class="font-bold text-slate-800 mb-3">Detail Advokat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor BAS</label>
                        <input type="text" name="no_bas" value="<?= $user['no_bas'] ?? '' ?>" class="w-full border rounded p-2" placeholder="Contoh: B-12345">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Spesialisasi</label>
                        <input type="text" name="spesialisasi" value="<?= $user['spesialisasi'] ?? '' ?>" class="w-full border rounded p-2" placeholder="Contoh: Pidana, Perdata">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tarif Konsultasi (Rp)</label>
                    <input type="number" name="harga_konsultasi" value="<?= $user['harga_konsultasi'] ?? '150000' ?>" class="w-full border rounded p-2" placeholder="Contoh: 250000">
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Ketersediaan</label>
                    <select name="available" class="w-full border rounded p-2">
                        <option value="1" <?= ($user && $user['available'] == 1) ? 'selected' : '' ?>>✅ Aktif / Tersedia</option>
                        <option value="0" <?= ($user && $user['available'] == 0) ? 'selected' : '' ?>>❌ Cuti / Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 mt-6">
                <a href="<?= base_url('users') ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        function toggleLawyerFields() {
            var role = document.getElementById('roleSelect').value;
            var fields = document.getElementById('lawyerFields');
            if (role === 'lawyer') {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
            }
        }
        // Jalankan saat load halaman (untuk mode Edit)
        toggleLawyerFields();
    </script>
</body>

</html>