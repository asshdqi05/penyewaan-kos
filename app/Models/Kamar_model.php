<?php

namespace App\Models;

use CodeIgniter\Model;

class Kamar_model extends Model
{
    protected $table            = 'kamar';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'nama_kamar', 'fasilitas', 'harga', 'status', 'foto', 'is_active'];

    function updateStatus($id_kamar, $status)
    {
        $this->db->table('kamar')->where('id', $id_kamar)->set('status', $status)->update();
    }
}
