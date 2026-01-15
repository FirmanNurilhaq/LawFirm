<?php

namespace App\Models;

use CodeIgniter\Model;

class KasusModel extends Model
{
    protected $table            = 'kasus';
    protected $primaryKey       = 'id_kasus';
    protected $allowedFields    = [
        'id_konsultasi',
        'tanggal_laporan',
        'progres',
        'tindakan',
        'rencana',
        'status_kasus'
    ];
    protected $useTimestamps    = true; // created_at otomatis
}
