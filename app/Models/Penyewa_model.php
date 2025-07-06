<?php

namespace App\Models;

use CodeIgniter\Model;

class Penyewa_model extends Model
{
    protected $table            = 'penyewa';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'nama_penyewa', 'alamat', 'nohp', 'nik', 'jenis_kelamin', 'username', 'password', 'jenis_registrasi', 'is_active'];
}
