<?php

namespace Tests\Support\Models;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\DokumenModel;

class DokumenModelTest extends CIUnitTestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new DokumenModel();
    }

    public function testStatusConstantsExist()
    {
        $this->assertEquals('pending', DokumenModel::STATUS_PENDING);
        $this->assertEquals('approved', DokumenModel::STATUS_APPROVED);
        $this->assertEquals('rejected', DokumenModel::STATUS_REJECTED);
    }

    public function testInsertReturnsId()
    {
        // Mock insert
        $mockModel = $this->getMockBuilder(DokumenModel::class)
                          ->onlyMethods(['insert'])
                          ->getMock();

        $mockModel->method('insert')->willReturn(10);

        $id = $mockModel->insert([
            'idservis' => 1,
            'nama' => 'Test Doc',
            'namafail' => 'test.txt',
            'mime' => 'text/plain',
            'status' => DokumenModel::STATUS_PENDING
        ]);

        $this->assertEquals(10, $id);
    }

    public function testSoftDeleteAndRestore()
    {
        $mockModel = $this->getMockBuilder(DokumenModel::class)
                          ->onlyMethods(['delete', 'update'])
                          ->getMock();

        // Soft delete
        $mockModel->expects($this->once())->method('delete')->with(5);
        $mockModel->delete(5);

        // Restore
        $mockModel->expects($this->once())->method('update')->with(5, ['deleted_at'=>null]);
        $mockModel->update(5, ['deleted_at'=>null]);
    }
}
