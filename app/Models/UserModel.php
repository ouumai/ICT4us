<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    
    // list of database follow as the column on table users
    protected $allowedFields    = ['fullname', 'email', 'password', 'profile_pic', 'created_at', 'reset_token', 'reset_expires_at'];
    
    // double hash
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