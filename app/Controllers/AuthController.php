<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        // Load Helper Brevo agar bisa kirim email
        helper(['brevo', 'text']);
    }

    // 1. Menampilkan Form Register
    public function register()
    {
        return view('auth/register');
    }

    // 2. Memproses Data Register (DENGAN VERIFIKASI EMAIL)
    public function processRegister()
    {
        $userModel = new UserModel();

        // Cek Email Unik
        $email = $this->request->getPost('email');
        if ($userModel->where('email', $email)->first()) {
            session()->setFlashdata('error', 'Email sudah terdaftar. Silakan login.');
            return redirect()->to('/register');
        }

        // Generate Token Unik
        $token = bin2hex(random_bytes(32)); // Contoh: a3f982...

        // Data User Baru
        $data = [
            'nama'      => $this->request->getPost('nama'),
            'email'     => $email,
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nik'       => $this->request->getPost('nik'),
            'no_telp'   => $this->request->getPost('no_telp'),
            'alamat'    => $this->request->getPost('alamat'),
            'role'      => 'client',
            'is_active' => 0, // DEFAULT BELUM AKTIF
            'verification_token' => $token
        ];

        // Simpan ke Database
        $userModel->save($data);

        // --- KIRIM EMAIL VERIFIKASI VIA BREVO ---
        $linkVerifikasi = base_url('auth/verify/' . $token);
        $namaUser = $data['nama'];

        $subject = "Aktivasi Akun - Hukum Law Firm";
        $message = "
            <h3>Halo, $namaUser!</h3>
            <p>Terima kasih telah mendaftar. Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>
            <p style='text-align: center;'>
                <a href='$linkVerifikasi' style='background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verifikasi Email Saya</a>
            </p>
            <p>Atau klik link berikut: <br> <a href='$linkVerifikasi'>$linkVerifikasi</a></p>
            <p>Link ini berlaku selama akun belum diaktifkan.</p>
        ";

        if (send_email_brevo($email, $namaUser, $subject, $message)) {
            session()->setFlashdata('success', 'Registrasi berhasil! Silakan cek Inbox/Spam email Anda untuk verifikasi akun.');
        } else {
            session()->setFlashdata('error', 'Registrasi berhasil, tapi gagal mengirim email. Hubungi Admin.');
        }
        // ----------------------------------------

        return redirect()->to('/login');
    }

    // 3. Fungsi Verifikasi (Dipanggil saat user klik link di email)
    public function verify($token)
    {
        $userModel = new UserModel();

        // Cari user berdasarkan token
        $user = $userModel->where('verification_token', $token)->first();

        if ($user) {
            // Aktifkan User & Hapus Token
            $userModel->update($user['id_user'], [
                'is_active' => 1,
                'verification_token' => null // Kosongkan agar token tidak bisa dipakai ulang
            ]);

            session()->setFlashdata('success', 'Selamat! Akun Anda telah aktif. Silakan login.');
        } else {
            session()->setFlashdata('error', 'Link verifikasi tidak valid atau kedaluwarsa.');
        }

        return redirect()->to('/login');
    }

    // 4. Menampilkan Halaman Login
    public function login()
    {
        return view('auth/login');
    }

    // 5. Proses Login (DENGAN CEK STATUS AKTIF)
    public function processLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Cek Password
            if (password_verify($password, $user['password'])) {
                
                // [BARU] Cek Apakah Akun Sudah Aktif?
                if ($user['is_active'] == 0) {
                    $session->setFlashdata('msg', 'Akun Anda belum diverifikasi. Silakan cek email Anda.');
                    return redirect()->to('/login');
                }

                // Jika Lolos Semua Cek, Set Session
                $ses_data = [
                    'id_user'   => $user['id_user'],
                    'nama'      => $user['nama'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    // Untuk Lawyer: Simpan No BAS di sesi biar gampang query
                    'no_bas'    => $user['no_bas'] ?? null, 
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);

                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    // 6. Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}