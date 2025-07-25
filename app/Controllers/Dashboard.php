<?php

namespace App\Controllers;

use App\Models\Kamar_model;
use App\Models\Sewa_kamar_model;

class Dashboard extends BaseController
{
    protected $kamarModel;
    protected $sewaKamarModel;
    public function __construct()
    {
        $this->kamarModel = new Kamar_model();
    }
    public function index()
    {
        $this->autoBatalSewa();
        $session = session();
        $data['title'] = 'Dashboard';
        $data['menu'] = 'mn_dashboard';
        $data['kamar'] = $this->kamarModel->where('is_active', 'y')->findAll();
        $data['total_kamar'] = count($data['kamar']);
        $data['total_kamar_kosong'] = count(array_filter($data['kamar'], function ($kamar) {
            return $kamar->status == 'Kosong';
        }));
        $data['total_kamar_terisi'] = count(array_filter($data['kamar'], function ($kamar) {
            return $kamar->status == 'Terisi';
        }));
        $data['total_kamar_dipesan'] = count(array_filter($data['kamar'], function ($kamar) {
            return $kamar->status == 'Dipesan';
        }));
        return view('Dashboard', $data);
    }

    private function autoBatalSewa()
    {
        $now = date('Y-m-d');
        $currentTime = date('H:i:s');

        // Hanya dijalankan jika sudah jam 12 siang
        if ($currentTime >= '12:00:00') {
            // Ambil semua data sewa yang perlu dibatalkan
            $listBatal = $this->sewaKamarModel
                ->where('tanggal_masuk <=', $now)
                ->where('status_pembayaran !=', 'Lunas')
                ->where('status', 'Booked')
                ->findAll();

            if ($listBatal) {
                // Ubah status sewa jadi Batal
                $this->sewaKamarModel
                    ->where('tanggal_masuk <=', $now)
                    ->where('status_pembayaran !=', 'Lunas')
                    ->where('status', 'Booked')
                    ->set(['status' => 'Batal'])
                    ->update();

                // Loop untuk ubah status kamarnya jadi "Kosong"
                foreach ($listBatal as $item) {
                    $this->kamarModel
                        ->where('id', $item->id_kamar)
                        ->set(['status' => 'Kosong'])
                        ->update();
                }
            }
        }
    }
}
