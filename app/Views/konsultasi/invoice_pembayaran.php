<!DOCTYPE html>
<html lang="id">

<head>
    <title>Invoice Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $clientKey ?>"></script>
</head>

<body class="bg-gray-100 font-[Inter] flex items-center justify-center min-h-screen py-10">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-slate-200">

        <div class="bg-slate-900 text-white p-8 text-center relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold tracking-widest uppercase mb-2">Invoice Tagihan</p>
                <h1 class="text-4xl font-bold">Rp <?= number_format($biaya, 0, ',', '.') ?></h1>
                <div class="mt-4 inline-block bg-amber-500 text-white text-xs px-3 py-1 rounded-full font-bold">
                    Menunggu Pembayaran
                </div>
            </div>
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>

        <div class="p-8 space-y-6">

            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <div>
                    <p class="text-xs text-gray-400">Kepada Klien</p>
                    <p class="font-bold text-slate-800"><?= $k['nama_klien'] ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">ID Konsultasi</p>
                    <p class="font-mono font-bold text-slate-800">#REQ-<?= $k['id_konsultasi'] ?></p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Layanan</span>
                    <span class="font-medium text-slate-800 text-right w-1/2"><?= $k['jenis_perkara'] ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Jadwal</span>
                    <span class="font-medium text-slate-800">
                        <?= date('d M Y, H:i', strtotime($k['tanggal_fiksasi'])) ?> WIB
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Lawyer</span>
                    <span class="font-medium text-slate-800">
                        <?= $k['no_bas'] ?>
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-bold uppercase text-amber-600">
                        <?= $k['tipe_konsultasi'] ?>
                    </span>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 text-xs text-gray-500 leading-relaxed text-center">
                <i class="fa-solid fa-lock mb-1"></i><br>
                Pembayaran Anda diamankan oleh Midtrans Gateway. Silakan selesaikan pembayaran untuk mendapatkan akses Link Meeting / Lokasi Kantor.
            </div>

            <div class="space-y-3 pt-2">
                <button id="pay-button" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                </button>

                <a href="<?= base_url('dashboard') ?>" class="block w-full text-center text-slate-500 hover:text-slate-800 text-sm font-semibold py-2">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('<?= $snapToken ?>', {
                onSuccess: function(result) {
                    // Redirect otomatis ditangani callback 'finish' di controller
                    // Tapi kita bisa paksa redirect via JS juga biar cepat
                    window.location.href = "<?= base_url('konsultasi/finish-payment?id=' . $k['id_konsultasi']) ?>";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda!");
                    location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    location.reload();
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            })
        });
    </script>

</body>

</html>