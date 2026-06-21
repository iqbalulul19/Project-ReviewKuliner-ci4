<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
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
                'rules'  => 'required|is_unique[users.username]',
                'errors' => [
                    'required'  => 'Username wajib diisi!',
                    'is_unique' => 'Username ini sudah dipakai, pilih yang lain!'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[5]',
                'errors' => [
                    'required'   => 'Password wajib diisi!',
                    'min_length' => 'Password terlalu pendek, minimal 5 karakter!'
                ]
            ]
        ];

        // 2. Jika validasi gagal
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Jika berhasil, simpan ke database
        $userModel = new UserModel();
        $userModel->insert([
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user'
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

        // 2. Jika form kosong
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        // 3. Pengecekan data di database
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

        // 4. Jika password salah atau user tidak ditemukan
        return redirect()->back()->withInput()->with('error', 'Login Gagal! Username atau Password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
