<?php

namespace App\Controllers;

use App\Models\Penyewa_model;

class Penyewa_controller extends BaseController
{
    protected $penyewaModel;
    public function __construct()
    {
        $this->penyewaModel = new Penyewa_model();
    }

    public function index()
    {
        $data['title'] = "Data Penyewa";
        $data['menu'] = "mn_penyewa";
        $data['data'] = $this->penyewaModel->where('is_active', 'y')->findAll();
        return view('Penyewa_view', $data);
    }

    public function save()
    {
        $data = [
            'nama_penyewa' => $this->request->getPost('nama_penyewa'),
            'alamat'       => $this->request->getPost('alamat'),
            'nohp'         => $this->request->getPost('nohp'),
            'nik'          => $this->request->getPost('nik'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'jenis_registrasi' => 'Langsung'
        ];

        $this->penyewaModel->save($data);
        session()->setFlashdata('success', 'Data Berhasil Disimpan.');
        return redirect()->to(base_url('Penyewa'));
    }

    public function edit()
    {
        $id = $this->request->getPost("id");
        $data = [
            'nama_penyewa' => $this->request->getPost('nama_penyewa'),
            'alamat'       => $this->request->getPost('alamat'),
            'nohp'         => $this->request->getPost('nohp'),
            'nik'          => $this->request->getPost('nik'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        ];

        $this->penyewaModel->update($id, $data);
        session()->setFlashdata('success', 'Data Berhasil Diupdate.');
        return redirect()->to(base_url('Penyewa'));
    }

    public function delete()
    {
        $id = $this->request->getPost("id");
        $status = 'n';
        $this->penyewaModel->update($id, ['is_active' => $status]);
        session()->setFlashdata('success', 'Data Berhasil Dihapus.');
        return redirect()->to(base_url('Penyewa'));
    }
}
