<?php

namespace App\Models;

use CodeIgniter\Model;

class Sewa_kamar_model extends Model
{
    protected $table            = 'sewa_kamar';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_penyewa', 'id_kamar', 'tanggal_masuk', 'tanggal_keluar', 'lama_sewa', 'harga', 'total_harga', 'status_pembayaran', 'status', 'created_at'];

    public function getListData()
    {
        return $this->select('sewa_kamar.*, penyewa.nama_penyewa,penyewa.nik, kamar.nama_kamar')
            ->join('penyewa', 'penyewa.id = sewa_kamar.id_penyewa')
            ->join('kamar', 'kamar.id = sewa_kamar.id_kamar')
            ->findAll();
    }

    public function getListDatabyPenyewa($id_penyewa)
    {
        return $this->select('sewa_kamar.*, penyewa.nama_penyewa, penyewa.nik, kamar.nama_kamar')
            ->join('penyewa', 'penyewa.id = sewa_kamar.id_penyewa')
            ->join('kamar', 'kamar.id = sewa_kamar.id_kamar')
            ->where('sewa_kamar.id_penyewa', $id_penyewa)
            ->orderBy('sewa_kamar.id', 'desc')
            ->findAll();
    }

    public function get_lap_sewa_kamar($tgl_awal, $tgl_akhir)
    {
        return $this->select('sewa_kamar.*, penyewa.nama_penyewa, penyewa.nik, kamar.nama_kamar')
            ->join('penyewa', 'penyewa.id = sewa_kamar.id_penyewa')
            ->join('kamar', 'kamar.id = sewa_kamar.id_kamar')
            ->where('sewa_kamar.tanggal_masuk >=', $tgl_awal)
            ->where('sewa_kamar.tanggal_masuk <=', $tgl_akhir)
            ->where('sewa_kamar.status', 'Check-in')
            ->orWhere('sewa_kamar.status', 'Check-out')
            ->findAll();
    }

    public function getDatatables($search = '', $order = null, $start = 0, $length = 10, $tanggal_awal = null, $tanggal_akhir = null)
    {
        $builder = $this->db->table('sewa_kamar');
        $builder->select('sewa_kamar.*, penyewa.nama_penyewa, penyewa.nik, kamar.nama_kamar');
        $builder->join('penyewa', 'penyewa.id = sewa_kamar.id_penyewa');
        $builder->join('kamar', 'kamar.id = sewa_kamar.id_kamar');
        $builder->orderBy('sewa_kamar.created_at', 'DESC');

        if ($tanggal_awal && $tanggal_akhir) {
            $builder->where('sewa_kamar.created_at >=', $tanggal_awal . ' 00:00:00');
            $builder->where('sewa_kamar.created_at <=', $tanggal_akhir . ' 23:59:59');
        }

        if ($search) {
            $builder->groupStart()
                ->like('penyewa.nama_penyewa', $search)
                ->orLike('penyewa.nik', $search)
                ->orLike('kamar.nama_kamar', $search)
                ->orLike('sewa_kamar.status_pembayaran', $search)
                ->orLike('sewa_kamar.status', $search)
                ->orLike('sewa_kamar.total_harga', $search)
                ->groupEnd();
        }

        if ($order) {
            // Kolom berdasarkan urutan yang ditampilkan di front-end (sesuaikan dengan tampilan datatable)
            $columns = ['penyewa.nama_penyewa', 'kamar.nama_kamar', 'sewa_kamar.tanggal_masuk', 'sewa_kamar.tanggal_keluar', 'sewa_kamar.total_harga'];
            $builder->orderBy($columns[$order['column']], $order['dir']);
        }

        $builder->limit($length, $start);
        return $builder->get()->getResultArray();
    }


    public function countFiltered($search = '', $tanggal_awal = null, $tanggal_akhir = null)
{
    $builder = $this->db->table('sewa_kamar');
    $builder->select('sewa_kamar.id');
    $builder->join('penyewa', 'penyewa.id = sewa_kamar.id_penyewa');
    $builder->join('kamar', 'kamar.id = sewa_kamar.id_kamar');

    if ($tanggal_awal && $tanggal_akhir) {
        $builder->where('sewa_kamar.created_at >=', $tanggal_awal . ' 00:00:00');
        $builder->where('sewa_kamar.created_at <=', $tanggal_akhir . ' 23:59:59');
    }

    if ($search) {
        $builder->groupStart()
            ->like('penyewa.nama_penyewa', $search)
            ->orLike('penyewa.nik', $search)
            ->orLike('kamar.nama_kamar', $search)
            ->orLike('sewa_kamar.status_pembayaran', $search)
            ->orLike('sewa_kamar.status', $search)
            ->orLike('sewa_kamar.total_harga', $search)
            ->groupEnd();
    }

    return $builder->countAllResults();
}


    public function countAllData()
    {
        return $this->db->table('sewa_kamar')->countAll();
    }
}
