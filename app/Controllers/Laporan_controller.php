<?php

namespace App\Controllers;

use App\Models\Kamar_model;
use App\Models\Penyewa_model;
use App\Models\Sewa_kamar_model;
use App\Models\Pembayaran_model;


class Laporan_controller extends BaseController
{
    protected $kamarModel;
    protected $sewaKamarModel;
    protected $penyewaModel;
    protected $pembayaranModel;
    protected $db;

    public function __construct()
    {
        $this->kamarModel = new Kamar_model();
        $this->sewaKamarModel = new Sewa_kamar_model();
        $this->penyewaModel = new Penyewa_model();
        $this->pembayaranModel = new Pembayaran_model();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = "Laporan";
        $data['menu'] = "mn_laporan";
        return view('laporan/Laporan_view', $data);
    }

    public function laporan_penyewa()
    {
        $data['title'] = "Laporan Data Penyewa";
        $data['data'] = $this->penyewaModel->where('is_active', 'y')->findAll();
        return view('laporan/Laporan_penyewa', $data);
    }

    public function laporan_kamar()
    {
        $data['title'] = "Laporan Data Kamar";
        $data['data'] = $this->kamarModel->where('is_active', 'y')->findAll();
        return view('laporan/Laporan_kamar', $data);
    }

    public function laporan_penyewaan_kamar()
    {
        $data['title'] = "Laporan Data Penyewaan Kamar";
        $tgl_awal = $this->request->getPost('tgl_awal');
        $tgl_akhir = $this->request->getPost('tgl_akhir');
        $data['tgl_awal'] = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['data'] = $this->sewaKamarModel->get_lap_sewa_kamar($tgl_awal, $tgl_akhir);
        return view('laporan/Laporan_penyewaan_kamar', $data);
    }

    public function laporan_pembayaran()
    {
        $data['title'] = "Laporan Data Pembayaran";
        $tgl_awal = $this->request->getPost('tgl_awal');
        $tgl_akhir = $this->request->getPost('tgl_akhir');
        $data['tgl_awal'] = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['data'] = $this->pembayaranModel->get_lap_pembayaran($tgl_awal, $tgl_akhir);
        return view('laporan/Laporan_pembayaran', $data);
    }
}
