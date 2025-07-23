<?php

namespace App\Controllers;

use App\Models\Kamar_model;
use App\Models\Penyewa_model;
use App\Models\Sewa_kamar_model;
use App\Models\Pembayaran_model;

class Sewa_kamar_controller extends BaseController
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
        $this->autoBatalSewa();
        $data['title'] = "Data Sewa Kamar";
        $data['menu'] = "mn_sewa_kamar";
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
        $data['penyewa'] = $this->penyewaModel->where('is_active', 'y')->findAll();
        $data['data'] = $this->sewaKamarModel->getListData();
        return view('Sewa_Kamar_view', $data);
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


    public function save()
    {
        $id_penyewa = $this->request->getPost('id_penyewa');
        $id_kamar = $this->request->getPost('id_kamar');
        $tanggal_masuk = $this->request->getPost('tanggal_masuk');
        $tanggal_keluar = $this->request->getPost('tanggal_keluar');
        $lama_sewa = $this->request->getPost('lama_sewa');
        $harga = $this->request->getPost('harga');
        $total_harga = $lama_sewa * $harga;
        $status_pembayaran = 'Lunas';
        $status = 'Check-in';

        $jenis_pembayaran = 'Pelunasan';
        $metode_pembayaran = $this->request->getPost('metode_pembayaran');

        $this->db->transStart();
        // Simpan data sewa kamar
        $dataSewa = [
            'id_penyewa' => $id_penyewa,
            'id_kamar' => $id_kamar,
            'tanggal_masuk' => $tanggal_masuk,
            'tanggal_keluar' => $tanggal_keluar,
            'lama_sewa' => $lama_sewa,
            'harga' => $harga,
            'total_harga' => $total_harga,
            'status' => $status,
            'status_pembayaran' => $status_pembayaran
        ];
        $this->sewaKamarModel->insert($dataSewa);
        if ($this->sewaKamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->sewaKamarModel->errors()]);
        }
        $id_sewa_kamar = $this->sewaKamarModel->insertID();

        // Update status kamar
        $this->kamarModel->updateStatus($id_kamar, 'Terisi');
        if ($this->kamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->kamarModel->errors()]);
        }
        // Simpan data pembayaran
        $dataPembayaran = [
            'id_sewa_kamar' => $id_sewa_kamar,
            'tanggal_pembayaran' => date('Y-m-d'),
            'jenis_pembayaran' => $jenis_pembayaran,
            'metode_pembayaran' => $metode_pembayaran,
            'jumlah' => $total_harga,
            'bukti' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'jumlah_bayar' => $total_harga
        ];
        $this->pembayaranModel->insert($dataPembayaran);
        if ($this->pembayaranModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->pembayaranModel->errors()]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi gagal.']);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
    }

    public function checkout()
    {
        $id = $this->request->getPost('id');
        $dataSewa = $this->sewaKamarModel->find($id);
        if (!$dataSewa) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data sewa tidak ditemukan.']);
        }

        $this->db->transStart();

        $status = [
            'status' => 'Check-out',
        ];
        // Update status sewa kamar
        $this->sewaKamarModel->update($id, $status);
        if ($this->sewaKamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->sewaKamarModel->errors()]);
        }

        $id_kamar = $dataSewa->id_kamar;
        $this->kamarModel->updateStatus($id_kamar, 'Kosong');
        if ($this->kamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->kamarModel->errors()]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi gagal.']);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Check-out berhasil.']);
    }

    public function cetak_struk($id = null)
    {
        $dataSewa = $this->sewaKamarModel->find($id);

        if (!$dataSewa) {
            return $this->response->setJSON([
                'error' => 'Data sewa tidak ditemukan.'
            ]);
        }

        $dataPenyewa = $this->penyewaModel->find($dataSewa->id_penyewa);
        $dataKamar = $this->kamarModel->find($dataSewa->id_kamar);
        $dataPembayaran = $this->pembayaranModel->where('id_sewa_kamar', $dataSewa->id)->first();

        $data = [
            'sewa' => $dataSewa,
            'penyewa' => $dataPenyewa,
            'kamar' => $dataKamar,
            'pembayaran' => $dataPembayaran,
        ];

        return view('Cetak_struk', $data);
    }

    public function save_pelunasan()
    {
        $id_sewa_kamar = $this->request->getPost('id');
        $jenis_pembayaran = 'Pelunasan';
        $metode_pembayaran = $this->request->getPost('metode_pembayaran');
        $jumlah = $this->request->getPost('jumlah_pembayaran');
        $id_kamar = $this->request->getPost('id_kamar');
        $sewaKamar = $this->sewaKamarModel->find($id_sewa_kamar);

        if (!$sewaKamar) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data sewa kamar tidak ditemukan.'
            ]);
        }
        $this->db->transStart();


        $dataPembayaran = [
            'id_sewa_kamar' => $id_sewa_kamar,
            'tanggal_pembayaran' => date('Y-m-d'),
            'jenis_pembayaran' => $jenis_pembayaran,
            'metode_pembayaran' => $metode_pembayaran,
            'jumlah' => $jumlah,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->pembayaranModel->insert($dataPembayaran);
        if ($this->pembayaranModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->pembayaranModel->errors()]);
        }
        // Update status sewa kamar
        $dataSewa = [
            'status_pembayaran' => 'Lunas',
            'status' => 'Check-in'
        ];
        $this->sewaKamarModel->update($id_sewa_kamar, $dataSewa);
        if ($this->sewaKamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->sewaKamarModel->errors()]);
        }

        // Update status kamar
        $dataKamar = [
            'status' => 'Terisi'
        ];
        $this->kamarModel->update($id_kamar, $dataKamar);
        if ($this->kamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->kamarModel->errors()]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi gagal.']);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
    }

    public function get_bukti_pembayaran($id)
    {
        $dataPembayaran = $this->pembayaranModel->where('id_sewa_kamar', $id)->where('jenis_pembayaran', 'DP')->first();
        if ($dataPembayaran && !empty($dataPembayaran->bukti)) {
            return $this->response->setJSON(['bukti' => $dataPembayaran->bukti]);
        } else {
            return $this->response->setJSON(['bukti' => null]);
        }
    }

    
}
