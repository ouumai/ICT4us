<?php

namespace Tests\Support\Models;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\ServisModel;

class ServisModelTest extends CIUnitTestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new ServisModel();
    }

    public function testInsertReturnsId()
    {
        $mock = $this->getMockBuilder(ServisModel::class)
                     ->onlyMethods(['insert'])
                     ->getMock();

        $mock->method('insert')->willReturn(5);

        $id = $mock->insert([
            'namaservis' => 'Servis Test',
            'infourl'    => 'https://example.com',
            'mohonurl'   => 'https://example.com/mohon',
            'status'     => 'active',
        ]);

        $this->assertEquals(5, $id);
    }

    public function testUpdate()
    {
        $mock = $this->getMockBuilder(ServisModel::class)
                     ->onlyMethods(['update'])
                     ->getMock();

        $mock->expects($this->once())->method('update')->with(1, ['namaservis'=>'Updated Servis']);
        $mock->update(1, ['namaservis'=>'Updated Servis']);
    }

    public function testDeleteSoft()
    {
        $mock = $this->getMockBuilder(ServisModel::class)
                     ->onlyMethods(['delete'])
                     ->getMock();

        $mock->expects($this->once())->method('delete')->with(1);
        $mock->delete(1);
    }
}
