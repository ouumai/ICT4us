<?php

namespace App\Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\ServisModel;
use App\Models\ModulDescModel;

class PerincianModulControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true; // run migrations before each test
    protected $seed = '';      // optional: you can seed data here

    protected $servisModel;
    protected $descModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize models
        $this->servisModel = new ServisModel();
        $this->descModel   = new ModulDescModel();
    }

    /** Test index page loads servis list */
    public function testIndexLoads()
    {
        // Insert dummy servis
        $this->servisModel->insert([
            'namaservis' => 'Servis Test',
            'infourl'    => 'https://info.test',
            'mohonurl'   => 'https://apply.test',
        ]);

        $result = $this->call('get', '/perincianmodul');
        $result->assertStatus(200);
        $result->assertSee('Servis Test');
    }

    /** Test fetching servis with description */
    public function testGetServis()
    {
        $id = $this->servisModel->insert([
            'namaservis' => 'Servis Get',
            'infourl'    => null,
            'mohonurl'   => null,
        ]);

        $this->descModel->insert([
            '108idservis' => $id,
            'description' => 'Test Description',
        ]);

        $result = $this->call('get', "/perincianmodul/getServis/$id");
        $result->assertStatus(200);

        $json = json_decode($result->getBody(), true);
        $this->assertTrue($json['status']);
        $this->assertEquals('Servis Get', $json['servis']['namaservis']);
        $this->assertEquals('Test Description', $json['desc']['description']);
    }

    /** Test saving new description */
    public function testSaveNewDescription()
    {
        $id = $this->servisModel->insert([
            'namaservis' => 'Servis Save',
            'infourl'    => null,
            'mohonurl'   => null,
        ]);

        $postData = [
            'idservis'   => $id,
            'namaservis' => 'Servis Save',
            'infourl'    => '',
            'mohonurl'   => '',
            'description'=> 'New Description',
        ];

        $result = $this->call('post', '/perincianmodul/save', $postData);
        $result->assertRedirect();

        $desc = $this->descModel->where('108idservis', $id)->first();
        $this->assertEquals('New Description', $desc['description']);
    }

    /** Test updating existing description */
    public function testUpdateDescription()
    {
        $id = $this->servisModel->insert([
            'namaservis' => 'Servis Update',
        ]);

        $descId = $this->descModel->insert([
            '108idservis' => $id,
            'description' => 'Old Description',
        ]);

        $postData = [
            'idservis'   => $id,
            'namaservis' => 'Servis Update',
            'infourl'    => '',
            'mohonurl'   => '',
            'description'=> 'Updated Description',
        ];

        $this->call('post', '/perincianmodul/save', $postData);

        $desc = $this->descModel->where('108idservis', $id)->first();
        $this->assertEquals('Updated Description', $desc['description']);
    }

    /** Test deleting servis and its description */
    public function testDeleteServis()
    {
        $id = $this->servisModel->insert([
            'namaservis' => 'Servis Delete',
        ]);

        $this->descModel->insert([
            '108idservis' => $id,
            'description' => 'Desc to Delete',
        ]);

        $this->call('get', "/perincianmodul/delete/$id");

        $this->assertNull($this->servisModel->find($id));
        $this->assertNull($this->descModel->where('108idservis', $id)->first());
    }
}
