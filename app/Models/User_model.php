<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id', 'nama', 'username', 'password', 'role'];

    public function getAll()
    {
        $builder = $this->db->table('user');
        $builder->select('*,user.nama,user.id as id_user,role.role_name as nama_role,user.role as role');
        $builder->join('role', 'role.id=user.role', 'left');
        return $builder->get();
    }

    public function getById($id)
    {
        $builder = $this->db->table('user');
        $builder->select('*,user.id as id_user,role.role_name as nama_role,user.role as role');
        $builder->join('role', 'role.id=user.role', 'left');
        $builder->where('user.id', $id);
        return $builder->get();
    }

    public function getRole()
    {
        // $bulder = $this->db->table('role');
        // return $bulder->get();
        $query = $this->db->query("SELECT * FROM role");
        return $query;
    }
}
