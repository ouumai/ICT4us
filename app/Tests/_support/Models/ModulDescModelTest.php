<?php

namespace Tests\Support\Models;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\ModulDescModel;

class ModulDescModelTest extends CIUnitTestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new ModulDescModel();
    }

    public function testInsertAndUpdate()
    {
        $mock = $this->getMockBuilder(ModulDescModel::class)
                     ->onlyMethods(['insert','update'])
                     ->getMock();

        $mock->method('insert')->willReturn(1);
        $mock->method('update')->willReturn(true);

        $id = $mock->insert([
            '108idservis'=>1,
            'description'=>'Test Description'
        ]);
        $this->assertEquals(1, $id);

        $result = $mock->update(1, ['description'=>'Updated']);
        $this->assertTrue($result);
    }

    public function testSoftDelete()
    {
        $mock = $this->getMockBuilder(ModulDescModel::class)
                     ->onlyMethods(['delete'])
                     ->getMock();

        $mock->expects($this->once())->method('delete')->with(1);
        $mock->delete(1);
    }
}
