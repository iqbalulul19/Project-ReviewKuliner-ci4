<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    // 1. TAMPILAN PROFIL (Hanya Nama & Username)
    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user
        ];

        return view('profile_view', $data); // Kita buat file view ini
    }

    // 2. TAMPILAN FORM EDIT
    public function edit()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        $data = [
            'title' => 'Edit Profil',
            'user'  => $user
        ];

        return view('profile_edit', $data); // Kita buat file view ini
    }

    // 3. PROSES UPDATE (Sama seperti sebelumnya)
    public function update()
    {
        $userModel = new UserModel();
        $id = session()->get('user_id');
        $name = $this->request->getPost('name');
        $password = $this->request->getPost('password');

        $dataUpdate = ['name' => $name];

        if (!empty($password)) {
            $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $dataUpdate);
        session()->set('name', $name);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui!');
    }
}