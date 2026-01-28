<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-3xl font-bold mb-4">Selamat Datang!</h1>

        <p class="text-lg">Halo, <span class="font-bold text-amber-600"><?= session()->get('nama'); ?></span></p>
        <p>Anda login sebagai: <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm"><?= session()->get('role'); ?></span></p>

        <div class="mt-8 border-t pt-4">
            <a href="<?= base_url('logout'); ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </div>
</body>

</html>