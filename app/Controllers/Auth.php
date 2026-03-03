<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

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
        $user = $model->find($id);

        // 1. Tangkap fail dari form
        $file = $this->request->getFile('profile_pic');
        $picName = $user['profile_pic']; // Ambil nama sedia ada

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $picName = $file->getRandomName(); // unique name

            $file->move(FCPATH . 'uploads/profile/', $picName);
            session()->set('profile_pic', $picName); // Update session
        }

        $data = [
            'fullname'    => $this->request->getPost('fullname'),
            'email'       => $this->request->getPost('email'),
            'profile_pic' => $picName,
        ];

        $model->update($id, $data);
        session()->set('fullname', $data['fullname']);

        return redirect()->back()->with('success', 'Profil berjaya dikemaskini.');
    }

    public function getProfilePic($filename)
    {
        $path = WRITEPATH . 'uploads/profile/' . $filename;
        if (!file_exists($path)) return null;

        $file = new \CodeIgniter\Files\File($path);
        $binary = readfile($path);
        return $this->response->setHeader('Content-Type', $file->getMimeType())->setBody($binary);
    }

    //4. RESET PASSWORD (DIRECT)

    public function forgotPassword()
    {
        return view('form/forgot_password');
    }

    public function forgotStep1()
    {
        return view('form/step1_forgot_password'); // file buat kemudian
    }

    public function processStep1()
    {
        $email = $this->request->getPost('email');
        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Emel tidak dijumpai.');
        }

        // 1. Generate Token & Timestamp
        $token = rand(100000, 999999);
        session()->set([
            'reset_token'      => $token,
            'reset_email'      => $email,
            'token_created_at' => time() // Masa mula untuk kiraan 5 minit
        ]);

        // 2. Setup Email Service
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Kod Keselamatan ICT4U');

        // 3. Template HTML dengan Amaran 5 Minit
        $message = "
        <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
            <div style='max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; border: 1px solid #ddd;'>
                <h2 style='color: #333; text-align: center;'>Sistem ICT4U</h2>
                <hr style='border: 0; border-top: 1px solid #eee;'>
                <p style='font-size: 16px; color: #555;'>Hai <strong>{$user['fullname']}</strong>,</p>
                <p style='font-size: 16px; color: #555;'>Sila gunakan kod pengesahan di bawah untuk set semula kata laluan anda:</p>
                
                <div style='background-color: #e7f3ff; border: 1px dashed #007bff; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px;'>
                    <h1 style='color: #4f46e5; font-size: 38px; letter-spacing: 8px; margin: 0;'>$token</h1>
                </div>

                <p style='font-size: 14px; color: #ed213a; text-align: center; font-size: 14px; margin-top: 10px;'><strong>Kod ini hanya sah untuk 5 minit sahaja.</strong></p>
                <p style='font-size: 13px; color: #888; text-align: center;'>Jika anda tidak meminta kod ini, sila abaikan emel ini.</p>
            </div>
        </div>
        ";

        $emailService->setMessage($message);

        // 4. Hantar & Redirect (Tanpa Debugger Luaran)
        if ($emailService->send()) {
            return redirect()->to('forgot/step2')->with('success', 'Kod telah dihantar! Sila semak emel anda segera.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghantar emel. Sila cuba sebentar lagi.');
        }
    }

    public function forgotStep2()
    {
        // Pastikan user dah lalu Step 1. Kalau terus ke Step 2, kita tendang balik ke step 1.
        if (!session()->get('reset_email')) {
            return redirect()->to('forgot/step1');
        }
        return view('form/step2_forgot_password');
    }

  public function processStep2()
    {
        $tokenInput = $this->request->getPost('token');
        $tokenSession = session()->get('reset_token');
        $createdAt = session()->get('token_created_at');
        $currentTime = time();

        // 1. Check kalau session dah hilang atau tak wujud
        if (!$tokenSession || !$createdAt) {
            return redirect()->to('forgot/step1')->with('error', 'Sesi telah tamat. Sila minta kod baru.');
        }

        // 2. Kira beza masa: 5 minit = 300 saat
        if (($currentTime - $createdAt) > 300) {
            // Padam session lama sebab dah expired
            session()->remove(['reset_token', 'token_created_at']);
            return redirect()->to('forgot/step1')->with('error', 'Kod pengesahan telah tamat tempoh (lebih 5 minit).');
        }

        // 3. Check token betul atau salah
        if ($tokenInput == $tokenSession) {
            // Jangan buang reset_email lagi, cuma buang token & masa
            session()->remove(['reset_token', 'token_created_at']); 
            return redirect()->to('forgot/step3');
        }

        return redirect()->back()->with('error', 'Kod pengesahan salah! Sila semak emel anda.');
    }

   public function forgotStep3()
    {
        if (!session()->get('reset_email')) {
            return redirect()->to('forgot/step1');
        }
        return view('form/step3_forgot_password');
    }

    public function processStep3()
    {

        // 1. Set Rules Validation (Min 8 characters)
        $rules = [
            'password'         => 'required|min_length[8]',
            'confirmpassword'  => 'required|matches[password]'
        ];

        // 2. Run Validation
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Kata laluan mestilah sekurang-kurangnya 8 aksara dan sepadan.');
        }

        $email = session()->get('reset_email');
        $password = $this->request->getPost('password');
        $confirm = $this->request->getPost('confirmpassword');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Kata laluan tidak sepadan.');
        }

        // Update password baru dalam database
        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        $model->update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        // Clear session supaya link reset tak boleh guna lagi
        session()->remove('reset_email');

        return redirect()->to('/login')->with('success', 'Kata laluan berjaya ditukar. Sila log masuk.');
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