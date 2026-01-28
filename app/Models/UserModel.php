<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // PERBAIKAN DISINI: Menambahkan verification_token dan is_active
    protected $allowedFields    = [
        'nama', 'email', 'password', 'nik', 'no_telp', 
        'alamat', 'role', 'no_bas', 'spesialisasi', 
        'harga_konsultasi', 'available', 'verification_token', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}