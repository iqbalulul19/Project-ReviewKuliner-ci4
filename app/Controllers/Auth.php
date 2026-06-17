<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // SINKRONISASI: Ubah 'isLoggedIn' menjadi 'logged_in'
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function saveRegister()
    {
        $userModel = new \App\Models\UserModel();
        $userModel->insert([
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user' // Default sebagai user biasa
        ]);
        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function process()
    {
        $userModel = new \App\Models\UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'logged_in' => true, // SINKRONISASI: Pakai 'logged_in' agar sinkron dengan navbar & routes
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'name'      => $user['name'],
                'role'      => $user['role']
            ]);
            return redirect()->to('/');
        }
        return redirect()->back()->with('error', 'Login Gagal!');
    }

    public function logout()
    {
        session()->destroy(); // Hapus semua session
        return redirect()->to('/login');
    }
}
