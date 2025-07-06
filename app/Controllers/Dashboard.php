<?php

namespace App\Controllers;

use App\Models\Kamar_model;

class Dashboard extends BaseController
{
    protected $kamarModel;
    public function __construct()
    {
        $this->kamarModel = new Kamar_model();
    }
    public function index()
    {
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
}
