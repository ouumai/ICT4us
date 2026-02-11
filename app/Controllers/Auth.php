<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // --- 1. AUTHENTICATION METHODS ---

    public function register()
    {
        return view('form/register');
    }

    public function attemptRegister()
    {
        // 1. Rules diupdate: username dibuang sebab takde dalam form
        // Kita akan auto-generate username guna emel (sebelum @) supaya database tak error
        $rules = [
            'fullname' => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Sila semak maklumat pendaftaran anda.');
        }

        $model = new UserModel();
        
        // 2. Generate username secara automatik dari emel
        // Contoh: amirul@gmail.com -> username jadi amirul
        $email = $this->request->getPost('email');
        $username = explode('@', $email)[0];

        // 3. Simpan data dengan Password Hash
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email'    => $email,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Wajib hash!
        ];

        if ($model->save($data)) {
            return redirect()->to('/login')->with('success', 'Akaun berjaya dicipta. Sila log masuk.');
        } else {
            return redirect()->back()->withInput()->with('error_pw', 'Gagal menyimpan data ke database.');
        }
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('form/login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        // Pastikan password_verify digunakan
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id'     => $user['id'],
                'fullname'    => $user['fullname'],
                'email'       => $user['email'],
                'profile_pic' => $user['profile_pic'], 
                'isLoggedIn'  => true
            ]);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Emel atau kata laluan salah.');
    }

    // ... (Logout dan Profile methods dikekalkan) ...

    /**
     * Handle the direct password update via Email input
     */
    public function attemptDirectReset()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $rules = [
            'email'           => 'required|valid_email',
            'password'        => 'required|min_length[6]',
            'confirmpassword' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Pastikan emel sah, kata laluan minima 6 aksara dan sepadan.');
        }

        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error_pw', 'Emel tidak dijumpai dalam sistem.');
        }

        // Update dengan password_hash
        $model->update($user['id'], [
            'password'         => password_hash($password, PASSWORD_DEFAULT), 
            'reset_token'      => null,
            'reset_expires_at' => null
        ]);

        return redirect()->to('/login')->with('success', 'Kata laluan berjaya ditukar. Sila log masuk.');
    }
}