<?php

namespace App\Tests;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\UserModel;

class UserModelTest extends CIUnitTestCase
{
    protected $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();

        // Clear table before each test
        $this->userModel->db->table($this->userModel->table)->truncate();
    }

    public function testInsertUser()
    {
        $data = [
            'username' => 'shafiq',
            'email'    => 'shafiq@example.com',
            'password' => '123456',
            'role'     => 'admin',
            'status'   => 'active',
        ];

        $id = $this->userModel->insert($data);

        $this->assertIsInt($id);

        $user = $this->userModel->find($id);

        $this->assertNotNull($user);
        $this->assertEquals('shafiq', $user['username']);
        $this->assertEquals('shafiq@example.com', $user['email']);
        $this->assertTrue(password_verify('123456', $user['password']));
    }

    public function testCountUsers()
    {
        $this->userModel->insert([
            'username' => 'user1',
            'email'    => 'user1@example.com',
            'password' => 'pass1',
            'role'     => 'admin',
            'status'   => 'active',
        ]);

        $this->userModel->insert([
            'username' => 'user2',
            'email'    => 'user2@example.com',
            'password' => 'pass2',
            'role'     => 'user',
            'status'   => 'inactive',
        ]);

        $this->assertEquals(2, $this->userModel->countAllUsers());
        $this->assertEquals(1, $this->userModel->countByRole('admin'));
        $this->assertEquals(1, $this->userModel->countByStatus('inactive'));
    }
}
