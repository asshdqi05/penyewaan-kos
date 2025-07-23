<?php

namespace App\Controllers;

use App\Models\Kamar_model;
use App\Models\Penyewa_model;
use App\Models\Sewa_kamar_model;
use App\Models\Pembayaran_model;

class Front_controller extends BaseController
{
    protected $kamarModel;
    protected $penyewaModel;
    protected $sewaKamarModel;
    protected $pembayaranModel;
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->kamarModel = new Kamar_model();
        $this->penyewaModel = new Penyewa_model();
        $this->sewaKamarModel = new Sewa_kamar_model();
        $this->pembayaranModel = new Pembayaran_model();
        $this->session = session();
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        $session = session();
        $this->autoBatalSewa();
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
        return view('Front_dashboard', $data);
    }

    private function autoBatalSewa()
    {
        $now = date('Y-m-d');
        $currentTime = date('H:i:s');

        if ($currentTime >= '12:00:00') {
            $listBatal = $this->sewaKamarModel
                ->where('tanggal_masuk <=', $now)
                ->where('status_pembayaran !=', 'Lunas')
                ->where('status', 'Booked')
                ->findAll();

            if ($listBatal) {
                $this->sewaKamarModel
                    ->where('tanggal_masuk <=', $now)
                    ->where('status_pembayaran !=', 'Lunas')
                    ->where('status', 'Booked')
                    ->set(['status' => 'Batal'])
                    ->update();

                foreach ($listBatal as $item) {
                    $this->kamarModel
                        ->where('id', $item->id_kamar)
                        ->set(['status' => 'Kosong'])
                        ->update();
                }
            }
        }
    }


    public function register()
    {
        $data = [
            'nama_penyewa'      => $this->request->getPost('nama_penyewa'),
            'alamat'            => $this->request->getPost('alamat'),
            'nohp'              => $this->request->getPost('nohp'),
            'nik'               => $this->request->getPost('nik'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'username'          => $this->request->getPost('username'),
            'password'          => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'jenis_registrasi'  => 'Online'
        ];

        if ($this->penyewaModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Registrasi berhasil. Silakan login.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Registrasi gagal. Silakan coba lagi.'
            ]);
        }
    }


    public function sewa_kamar()
    {
        $this->autoBatalSewa();
        if (!$this->session->has('id_penyewa')) {
            return redirect()->to('/Front-Dashboard');
        }
        $data['title'] = 'Sewa Kamar';
        $data['menu'] = 'mn_sewa_kamar';
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
        $data['data'] = $this->sewaKamarModel->getListDatabyPenyewa($this->session->get('id_penyewa'));
        return view('Front_sewa_kamar', $data);
    }

    public function save_sewa_kamar()
    {
        $id_penyewa = $this->request->getPost('id_penyewa');
        $id_kamar = $this->request->getPost('id_kamar');
        $tanggal_masuk = $this->request->getPost('tanggal_masuk');
        $tanggal_keluar = $this->request->getPost('tanggal_keluar');
        $lama_sewa = $this->request->getPost('lama_sewa');
        $harga = $this->request->getPost('harga');
        $total_harga = $lama_sewa * $harga;
        $status_pembayaran = 'Menunggu pembayaran';
        $status = '-';
        $data = [
            'id_penyewa'      => $id_penyewa,
            'id_kamar'        => $id_kamar,
            'tanggal_masuk'   => $tanggal_masuk,
            'tanggal_keluar'  => $tanggal_keluar,
            'lama_sewa'       => $lama_sewa,
            'total_harga'     => $total_harga,
            'status'          => $status,
            'status_pembayaran' => $status_pembayaran
        ];

        if ($this->sewaKamarModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data sewa kamar berhasil disimpan.Silahkan melakukan pembayaran.',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data sewa kamar gagal disimpan.'
            ]);
        }
    }

    public function save_pembayaran()
    {
        $id_sewa_kamar = $this->request->getPost('id_sewa');
        $jenis_pembayaran = 'DP';
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

        $cek_status_kamar = $this->kamarModel->find($id_kamar);
        if ($cek_status_kamar->status != 'Kosong') {
            $dataSewa = [
                'status' => 'Batal',
                'status_pembayaran' => 'Dibatalkan'
            ];
            $this->sewaKamarModel->update($id_sewa_kamar, $dataSewa);
            return $this->response->setJSON([
                'status' => 'pembatalan',
                'message' => 'Kamar tidak tersedia untuk sewa. Booking telah dibatalkan.'
            ]);
        }

        $this->validation->setRules([
            'bukti_pembayaran' => [
                'label' => 'Bukti Pembayaran',
                'rules' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|is_image[bukti_pembayaran]',
                'errors' => [
                    'uploaded' => 'Bukti pembayaran harus diupload.',
                    'max_size' => 'Ukuran bukti pembayaran tidak boleh lebih dari 2MB.',
                    'is_image' => 'Bukti pembayaran harus berupa gambar.'
                ]
            ],
            'metode_pembayaran' => [
                'label' => 'Metode Pembayaran',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Metode pembayaran harus dipilih.'
                ]
            ]
        ]);



        if (!$this->validation->withRequest($this->request)->run()) {
            $errors = $this->validation->getErrors();
            $errorMessage = implode('<br>', $errors);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $errorMessage
            ]);
        }

        $this->db->transStart();

        $file = $this->request->getFile('bukti_pembayaran');
        $namaFoto = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFoto = $file->getRandomName();
            $file->move('uploads/bukti_pembayaran', $namaFoto);
        }
        $dataPembayaran = [
            'id_sewa_kamar' => $id_sewa_kamar,
            'tanggal_pembayaran' => date('Y-m-d'),
            'jenis_pembayaran' => $jenis_pembayaran,
            'metode_pembayaran' => $metode_pembayaran,
            'jumlah' => $jumlah,
            'bukti' => $namaFoto,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->pembayaranModel->insert($dataPembayaran);
        if ($this->pembayaranModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->pembayaranModel->errors()]);
        }
        // Update status sewa kamar
        $dataSewa = [
            'status_pembayaran' => 'Sudah bayar DP',
            'status' => 'Booked'
        ];
        $this->sewaKamarModel->update($id_sewa_kamar, $dataSewa);
        if ($this->sewaKamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->sewaKamarModel->errors()]);
        }

        // Update status kamar
        $dataKamar = [
            'status' => 'Dipesan'
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

    public function cetak_struk_dp($id)
    {
        $dataSewa = $this->sewaKamarModel->where(['id' => $id])->first();
        $dataPenyewa = $this->penyewaModel->where(['id' => $dataSewa->id_penyewa])->first();
        $dataKamar = $this->kamarModel->find($dataSewa->id_kamar);
        $dataPembayaran = $this->pembayaranModel->where([
            'id_sewa_kamar' => $id
        ])->findAll();

        $data = [
            'sewa' => $dataSewa,
            'penyewa' => $dataPenyewa,
            'kamar' => $dataKamar,
            'pembayaran' => $dataPembayaran,
        ];
        return view('Cetak_struk_dp', $data);
    }

    public function delete_sewa_kamar()
    {
        $id = $this->request->getPost('id');
        $dataSewa = $this->sewaKamarModel->find($id);
        if (!$dataSewa) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data sewa kamar tidak ditemukan.']);
        }

        $this->db->transStart();

        // Update status kamar
        $id_kamar = $dataSewa->id_kamar;
        $this->kamarModel->updateStatus($id_kamar, 'Kosong');
        if ($this->kamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->kamarModel->errors()]);
        }

        // Hapus data sewa kamar
        $this->sewaKamarModel->delete($id);
        if ($this->sewaKamarModel->errors()) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $this->sewaKamarModel->errors()]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi gagal.']);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
    }

    public function cekKamarKosong()
    {
        $id_kamar = $this->request->getPost('id_kamar');
        $kamar = $this->kamarModel->find($id_kamar);

        if (!$kamar) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kamar tidak ditemukan.']);
        }

        // Cek apakah kamar kosong
        if ($kamar->status === 'Kosong') {
            return $this->response->setJSON(['kamar_kosong' => true]);
        } else {
            return $this->response->setJSON(['kamar_kosong' => false]);
        }
    }
}
