<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    // 1. Menampilkan Form Register
    public function register()
    {
        return view('auth/register');
    }

    // 2. Memproses Data Register
    public function processRegister()
    {
        $userModel = new UserModel();

        // Ambil data dari form HTML
        $data = [
            'nama'      => $this->request->getPost('nama'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Enkripsi password
            'nik'       => $this->request->getPost('nik'),
            'no_telp'   => $this->request->getPost('no_telp'),
            'alamat'    => $this->request->getPost('alamat'),
            'role'      => 'client' // Default role jika daftar sendiri pasti Client
        ];

        // Simpan ke Database
        $userModel->save($data);

        session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
        return redirect()->to('/login');
    }
    // 3. Menampilkan Halaman Login
    public function login()
    {
        return view('auth/login');
    }

    // 4. Proses Login
    public function processLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Cek Password
            if (password_verify($password, $user['password'])) {
                // Jika password benar, SIMPAN DATA SESI
                $ses_data = [
                    'id_user'   => $user['id_user'],
                    'nama'      => $user['nama'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);

                // Redirect berdasarkan Role (Arahkan ke Dashboard yang sesuai)
                // Untuk sementara kita arahkan ke Dashboard umum dulu
                return redirect()->to('/dashboard');
            } else {
                // Password salah
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            // Email tidak ditemukan
            $session->setFlashdata('msg', 'Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    // 5. Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
