<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Klien</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Daftar Akun Baru</h2>
            <p class="text-sm text-gray-500">Silakan isi data diri Anda untuk memulai konsultasi.</p>
        </div>

        <form action="<?= base_url('register/process') ?>" method="post">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">NIK</label>
                <input type="number" name="nik" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">No. WhatsApp / Telp</label>
                <input type="text" name="no_telp" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Domisili</label>
                <textarea name="alamat" rows="2" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-amber-600 text-white font-bold py-2 rounded-md hover:bg-amber-700 transition">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="/login" class="text-amber-600 font-bold hover:underline">Masuk disini</a>
        </p>
        <p class="mt-2 text-center text-sm text-gray-600">
            <a href="/" class="hover:underline">← Kembali ke Beranda</a>
        </p>
    </div>

</body>

</html>