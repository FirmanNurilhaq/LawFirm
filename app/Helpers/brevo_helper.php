<?php

if (!function_exists('send_email_brevo')) {
    /**
     * Kirim Email menggunakan API Brevo (Sendinblue)
     * * @param string $toEmail Email penerima
     * @param string $toName Nama penerima
     * @param string $subject Judul Email
     * @param string $htmlContent Isi email (HTML)
     * @return boolean True jika sukses (atau terkirim ke API), False jika gagal curl
     */
    function send_email_brevo($toEmail, $toName, $subject, $htmlContent)
    {
        // Ambil konfigurasi dari .env
        $apiKey = getenv('BREVO_API_KEY');
        $senderEmail = getenv('BREVO_SENDER_EMAIL');
        $senderName = getenv('BREVO_SENDER_NAME');

        // URL Endpoint API Brevo
        $url = 'https://api.brevo.com/v3/smtp/email';

        // Payload Data JSON
        $data = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName
                ]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ];

        // Inisialisasi cURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Header wajib Brevo
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);

        // BYPASS SSL (PENTING UNTUK LOCALHOST AGAR TIDAK ERROR CERTIFICATE)
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Eksekusi
        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        // --- DEBUG MODE: AKTIFKAN INI ---
        if ($err) {
            echo "<h1>cURL Error</h1>";
            var_dump($err);
            die(); // Matikan proses biar kelihatan errornya
        } else {
            $json = json_decode($response, true);

            // Jika Brevo menolak (misal: Sender tidak valid, Key salah, Saldo habis)
            if (isset($json['code']) || isset($json['message'])) {
                echo "<h1>Brevo API Error</h1>";
                echo "<pre>";
                print_r($json);
                echo "</pre>";
                die(); // Matikan proses biar kelihatan response dari Brevo
            }
        }

        return true;
    }
}
