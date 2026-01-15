<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user'; // Nama tabel di database
    protected $primaryKey       = 'id_user';

    // Kolom mana saja yang boleh diisi lewat kodingan
    protected $allowedFields = [
        'nama',
        'email',
        'password',
        'role',
        'no_telp',
        'alamat',
        'foto',
        // Data Lawyer
        'no_bas',
        'spesialisasi',
        'available',
        'harga_konsultasi' // <--- WAJIB DITAMBAHKAN DI SINI
    ];

    protected $useTimestamps = true; // Agar created_at otomatis terisi
}
