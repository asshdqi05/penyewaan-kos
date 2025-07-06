<?php

namespace App\Controllers;

use App\Models\User_model;
use App\Models\Penyewa_model;

class Login_controller extends BaseController
{
    protected $userModel;
    protected $penyewaModel;

    public function __construct()
    {
        $this->userModel = new User_model();
        $this->penyewaModel = new Penyewa_model();
    }
    public function index()
    {
        return view('Login_view');
    }

    function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $get_data = $this->userModel->getById($user['id']);
                $user = $get_data->getRowArray();
                session()->set('logged_in', true);
                session()->set('user_id', $user['id']);
                session()->set('username', $user['username']);
                session()->set('role', $user['role']);
                session()->set('nama', $user['nama']);
                session()->set('nama_role', $user['nama_role']);
                return redirect()->to('/Dashboard');
            } else {
                session()->setFlashdata('msg', 'Password salah');
                return redirect()->to('/Login');
            }
        } else {
            session()->setFlashdata('msg', 'Username tidak ditemukan');
            return redirect()->to('/Login');
        }
    }

    function logout()
    {
        session()->remove(['logged_in', 'user_id', 'username', 'role', 'nama', 'nama_role']);
        return redirect()->to('/Login');
    }

    public function login_penyewa()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->penyewaModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user->password)) {
                session()->set('logged_in_penyewa', true);
                session()->set('id_penyewa', $user->id);
                session()->set('username_penyewa', $user->username);
                session()->set('nama_penyewa', $user->nama_penyewa);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Login Berhasil.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Password salah.'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Username tidak ditemukan.'
            ]);
        }
    }


    function logout_penyewa()
    {
        session()->remove(['logged_in_penyewa', 'id_penyewa', 'username_penyewa', 'nama_penyewa']);
        return redirect()->to('/Front-Dashboard');
    }
}
