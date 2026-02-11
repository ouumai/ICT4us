<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    
    // Pastikan list ni selari dengan column DB kau
    protected $allowedFields    = ['fullname', 'email', 'password', 'profile_pic', 'created_at', 'reset_token', 'reset_expires_at'];
    
    // COMMENT-KAN ATAU BUANG BAHAGIAN NI SUPAYA TAK DOUBLE HASH
    /*
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    */
}