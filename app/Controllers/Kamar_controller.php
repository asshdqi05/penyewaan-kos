<?php

namespace App\Controllers;

use App\Models\Kamar_model;

class Kamar_controller extends BaseController
{
    protected $kamarModel;
    public function __construct()
    {
        $this->kamarModel = new Kamar_model();
    }

    public function index()
    {
        $data['title'] = "Data kamar";
        $data['menu'] = "mn_kamar";
        $data['data'] = $this->kamarModel->where('is_active', 'y')->findAll();
        return view('Kamar_view', $data);
    }

    public function save()
    {
        $file = $this->request->getFile('foto');
        $namaFoto = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFoto = $file->getRandomName();
            $file->move('uploads/kamar', $namaFoto);
        }

        $data = [
            'nama_kamar' => $this->request->getPost('nama_kamar'),
            'fasilitas'  => $this->request->getPost('fasilitas'),
            'harga'      => $this->request->getPost('harga'),
            'foto'       => $namaFoto
        ];

        $this->kamarModel->save($data);
        session()->setFlashdata('success', 'Data Berhasil Disimpan.');
        return redirect()->to(base_url('Kamar'));
    }


    public function edit()
    {
        $id = $this->request->getPost("id_kamar");
        $kamarLama = $this->kamarModel->find($id);

        $file = $this->request->getFile('foto');
        $namaFoto = $kamarLama->foto;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($namaFoto && file_exists('uploads/kamar/' . $namaFoto)) {
                unlink('uploads/kamar/' . $namaFoto);
            }
            $namaFoto = $file->getRandomName();
            $file->move('uploads/kamar', $namaFoto);
        }

        $data = [
            'nama_kamar' => $this->request->getPost('nama_kamar'),
            'fasilitas'  => $this->request->getPost('fasilitas'),
            'harga'      => $this->request->getPost('harga'),
            'foto'       => $namaFoto
        ];

        $this->kamarModel->update($id, $data);
        session()->setFlashdata('success', 'Data Berhasil Diubah.');
        return redirect()->to(base_url('Kamar'));
    }


    public function delete()
    {
        $id = $this->request->getPost("id");
        $kamar = $this->kamarModel->find($id);

        if ($kamar && $kamar->foto && file_exists('uploads/kamar/' . $kamar->foto)) {
            unlink('uploads/kamar/' . $kamar->foto);
        }

        // Soft delete dengan is_active = no
        $this->kamarModel->update($id, ['is_active' => 'no']);
        session()->setFlashdata('success', 'Data Berhasil Dihapus.');
        return redirect()->to(base_url('Kamar'));
    }
}
