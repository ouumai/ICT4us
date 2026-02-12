<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    //1. REGISTRATION

    public function register()
    {
        return view('form/register');
    }

    public function attemptRegister()
    {
        // Rules for field that only exist in Table users
        $rules = [
            'fullname'         => 'required|min_length[3]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Sila semak maklumat pendaftaran anda.');
        }

        $model = new UserModel();
        
        // Simpan data tanpa username
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email'    => $this->request->getPost('email'),
            // Password dihash guna input 'password' yang betul
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        if ($model->save($data)) {
            return redirect()->to('/login')->with('success', 'Akaun berjaya dicipta. Sila log masuk.');
        } else {
            return redirect()->back()->withInput()->with('error_pw', 'Gagal menyimpan data ke database.');
        }
    }

    // 2. LOG IN 

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

        // Bandingkan password guna password_verify
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id'    => $user['id'],
                'fullname'   => $user['fullname'],
                'email'      => $user['email'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Emel atau kata laluan salah.');
    }

    //3. PROFILE & LOG OUT

    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new UserModel();
        $data['user'] = $model->find(session()->get('user_id'));

        return view('form/profile', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function updateProfile()
    {
        $model = new UserModel();
        $id = session()->get('user_id');

        $rules = [
            'fullname' => 'required|min_length[3]',
            'email'    => "required|valid_email|is_unique[users.email,id,$id]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Input tidak sah.');
        }

        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email'    => $this->request->getPost('email'),
        ];

        $model->update($id, $data);
        session()->set('fullname', $data['fullname']);

        return redirect()->back()->with('success', 'Profil berjaya dikemaskini.');
    }

    //4. RESET PASSWORD (DIRECT)

    public function forgotPassword()
    {
        return view('form/forgot_password');
    }

    public function attemptDirectReset()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $rules = [
            'email'           => 'required|valid_email',
            'password'        => 'required|min_length[8]',
            'confirmpassword' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_pw', 'Pastikan emel sah dan kata laluan sepadan.');
        }

        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error_pw', 'Emel tidak dijumpai.');
        }

        $model->update($user['id'], [
            'password'         => password_hash($password, PASSWORD_DEFAULT), 
            'reset_token'      => null,
            'reset_expires_at' => null
        ]);

        return redirect()->to('/login')->with('success', 'Kata laluan berjaya ditukar.');
    }

    //5. UPDATE PASSWORD
    public function updatePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new UserModel();
        // 1. Tarik latest user data guna ID dalam session
        $user = $model->find(session()->get('user_id'));

        // 2. Ambil password lama yang user taip kat form
        $currentPassInput = $this->request->getPost('current_password');

        // 3. Verification: Tukar kepada 'error_pw' supaya keluar SweetAlert kat profile.php
        if (!password_verify($currentPassInput, $user['password'])) {
            return redirect()->back()->with('error_pw', 'Kata laluan semasa anda salah! Sila cuba lagi.');
        }

        // 4. Validation: Check password baru
        $rules = [
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error_pw', 'Pastikan password baru minima 8 aksara & sepadan.');
        }

        // 5. UPDATE: Simpan hash baru
        $model->update($user['id'], [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->back()->with('success', 'Kata laluan berjaya dikemaskini.');
    }
}