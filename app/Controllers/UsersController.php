<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // 1. Menampilkan Daftar User
    public function index()
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        // Ambil data lawyer dan klien
        $data['lawyers'] = $this->userModel->where('role', 'lawyer')->findAll();
        $data['clients'] = $this->userModel->where('role', 'client')->findAll();

        return view('users/index', $data);
    }

    // 2. Form Tambah/Edit
    public function form($id = null)
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'user' => $id ? $this->userModel->find($id) : null,
            'title' => $id ? 'Edit Pengguna' : 'Tambah Pengguna Baru'
        ];

        return view('users/form', $data);
    }

    // 3. Proses Simpan (Create/Update)
    public function save()
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $id = $this->request->getPost('id_user');
        $role = $this->request->getPost('role');

        // Data dasar
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role' => $role,
            'no_telp' => $this->request->getPost('no_telp'),
            'alamat' => $this->request->getPost('alamat'),
            'available' => $this->request->getPost('available') // Untuk lawyer (Cuti/Aktif)
        ];

        // Jika Lawyer, simpan data spesifik
        if ($role == 'lawyer') {
            $data['no_bas'] = $this->request->getPost('no_bas');
            $data['spesialisasi'] = $this->request->getPost('spesialisasi');
            $data['harga_konsultasi'] = $this->request->getPost('harga_konsultasi');
        }

        // Handle Password (Hanya update jika diisi)
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Simpan ke DB
        if ($id) {
            $this->userModel->update($id, $data);
            $msg = 'Data pengguna berhasil diperbarui.';
        } else {
            // Validasi email unik untuk user baru
            if ($this->userModel->where('email', $data['email'])->first()) {
                return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar!');
            }
            // Password wajib untuk user baru
            if (empty($password)) {
                $data['password'] = password_hash('123456', PASSWORD_DEFAULT); // Default password
            }
            $this->userModel->save($data);
            $msg = 'Pengguna baru berhasil ditambahkan.';
        }

        return redirect()->to('/users')->with('success', $msg);
    }

    // 4. Hapus User
    public function delete($id)
    {
        if (session()->get('role') != 'sekretaris') {
            return redirect()->to('/dashboard');
        }

        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
