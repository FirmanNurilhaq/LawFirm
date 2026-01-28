<?php

namespace App\Models;

use CodeIgniter\Model;

class KonsultasiModel extends Model
{
    protected $table            = 'konsultasi';
    protected $primaryKey       = 'id_konsultasi';

    // Konfigurasi Timestamp
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'id_user',
        'no_bas',
        'jenis_perkara',
        'deskripsi_masalah',
        'dokumen_kelengkapan',
        'tipe_konsultasi',
        'tanggal_usulan',
        'tanggal_fiksasi',
        'status',
        'meeting',         // Lokasi offline
        'link_meeting',    // <--- WAJIB ADA (Untuk Link Zoom/Gmeet)
        'alasan_tolak',
        'created_at',      // Tambahkan biar aman
        'updated_at'       // Tambahkan biar aman
    ];
}
