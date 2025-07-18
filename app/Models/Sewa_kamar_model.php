<?php

namespace App\Models;

use CodeIgniter\Model;

class Sewa_kamar_model extends Model
{
    protected $table            = 'sewa_kamar';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_penyewa', 'id_kamar', 'tanggal_masuk', 'tanggal_keluar', 'lama_sewa', 'harga', 'total_harga', 'status_pembayaran', 'status'];

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
            ->findAll();
    }
}
