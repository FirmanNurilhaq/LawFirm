<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Konsultasi Hukum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-sm">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Silakan Masuk</h2>
            <p class="text-sm text-gray-500">Masukkan akun yang telah terdaftar.</p>
        </div>

        <?php if (session()->getFlashdata('msg')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('msg') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/process') ?>" method="post">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <button type="submit" class="w-full bg-slate-800 text-white font-bold py-2 rounded-md hover:bg-slate-900 transition">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Belum punya akun? <a href="<?= base_url('register') ?>" class="text-amber-600 font-bold hover:underline">Daftar disini</a>
        </p>
        <p class="mt-2 text-center text-sm text-gray-600">
            <a href="<?= base_url('/') ?>" class="hover:underline">← Kembali ke Beranda</a>
        </p>
    </div>

</body>

</html>