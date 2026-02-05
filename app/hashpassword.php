<?php
// Load CodeIgniter framework
require 'app/Config/Paths.php';
require 'vendor/autoload.php';

use App\Models\UserModel;

$model = new UserModel();

// Ambil semua user
$users = $model->findAll();

foreach($users as $user) {
    // Cek kalau password belum hashed (asumsi plain text < 60 char)
    if(strlen($user['password']) < 60) {
        $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
        $model->update($user['id'], ['password' => $hashed]);
        echo "User {$user['email']} updated\n";
    } else {
        echo "User {$user['email']} already hashed\n";
    }
}

echo "Migration complete!\n";
