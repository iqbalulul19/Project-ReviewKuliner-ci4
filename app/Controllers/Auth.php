<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    public function login()
    {
        // Jika sudah login, langsung arahkan ke Home
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('login');
    }

    // Tambahkan fungsi register di Auth.php
    public function register() {
        return view('register');
    }

    public function saveRegister() {
        $userModel = new \App\Models\UserModel(); // Pastikan buat UserModel dulu
        $userModel->insert([
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user' // Default sebagai user biasa
        ]);
        return redirect()->to('/login')->with('error', 'Registrasi berhasil! Silakan login.');
    }

    // Update fungsi process() untuk menyimpan ROLE di session
    public function process() {
        $userModel = new \App\Models\UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'name'       => $user['name'],
                'role'       => $user['role'] 
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