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
        // 1. Aturan Validasi Registrasi
        $rules = [
            'name'     => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama lengkap wajib diisi!']
            ],
            'username' => [
                'rules'  => 'required|is_unique[users.username]', // Sekaligus mengecek agar username tidak kembar
                'errors' => [
                    'required' => 'Username wajib diisi!',
                    'is_unique'=> 'Username ini sudah dipakai, pilih yang lain!'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[5]',
                'errors' => [
                    'required'  => 'Password wajib diisi!',
                    'min_length'=> 'Password terlalu pendek, minimal 5 karakter!'
                ]
            ]
        ];

        // 2. Jika validasi gagal, kembalikan ke form register beserta error
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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
        // 1. Aturan Validasi Login
        $rules = [
            'username' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Username tidak boleh kosong!']
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Password tidak boleh kosong!']
            ]
        ];

        // 2. Jika validasi gagal, kembalikan ke form login beserta error
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new \App\Models\UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'name'      => $user['name'],
                'role'      => $user['role']
            ]);
            return redirect()->to('/');
        }
        return redirect()->back()->with('error', 'Login Gagal! Username atau Password salah.');
    }

    public function logout()
    {
        session()->destroy(); // Hapus semua session
        return redirect()->to('/login');
    }
}
