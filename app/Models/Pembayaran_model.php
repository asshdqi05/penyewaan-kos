<?php

namespace App\Models;

use CodeIgniter\Model;

class Pembayaran_model extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_sewa_kamar', 'tanggal_pembayaran', 'jenis_pembayaran', 'metode_pembayaran', 'jumlah', 'bukti', 'created_at'];

    public function get_lap_pembayaran($tgl_awal, $tgl_akhir)
    {
        return $this->select('pembayaran.*, penyewa.nama_penyewa, kamar.nama_kamar, sewa_kamar.tanggal_masuk, sewa_kamar.tanggal_keluar, sewa_kamar.lama_sewa, sewa_kamar.total_harga, sewa_kamar.status_pembayaran, sewa_kamar.status')
            ->join('sewa_kamar', 'sewa_kamar.id = pembayaran.id_sewa_kamar')
            ->join('penyewa', 'penyewa.id = sewa_kamar.id_penyewa')
            ->join('kamar', 'kamar.id = sewa_kamar.id_kamar')
            ->where('tanggal_pembayaran >=', $tgl_awal)
            ->where('tanggal_pembayaran <=', $tgl_akhir)
            ->findAll();
    }
}
