<?php

namespace Tests\Support\Models;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\ApprovalDokumenModel;

class ApprovalDokumenModelTest extends CIUnitTestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new ApprovalDokumenModel();
    }

    public function testInsertAndFind()
    {
        $mock = $this->getMockBuilder(ApprovalDokumenModel::class)
                     ->onlyMethods(['insert','find'])
                     ->getMock();

        $mock->method('insert')->willReturn(10);
        $mock->method('find')->willReturn(['id'=>10,'status'=>'pending']);

        $id = $mock->insert([
            'iddoc'=>1,
            'status'=>'pending',
            'approved_by'=>null
        ]);
        $this->assertEquals(10, $id);

        $record = $mock->find(10);
        $this->assertEquals('pending', $record['status']);
    }

    public function testUpdate()
    {
        $mock = $this->getMockBuilder(ApprovalDokumenModel::class)
                     ->onlyMethods(['update'])
                     ->getMock();

        $mock->expects($this->once())->method('update')->with(10, ['status'=>'approved']);
        $mock->update(10, ['status'=>'approved']);
    }
}
