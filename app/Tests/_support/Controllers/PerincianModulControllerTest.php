<?php

namespace Tests\Support\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use App\Controllers\PerincianModulController;
use App\Models\ServisModel;
use App\Models\ModulDescModel;
use App\Models\ApprovalDokumenModel;

class PerincianModulControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected $controller;
    protected $servisModelMock;
    protected $descModelMock;
    protected $approvalModelMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock ServisModel
        $this->servisModelMock = $this->createMock(ServisModel::class);
        $this->servisModelMock->method('orderBy')->willReturnSelf();
        $this->servisModelMock->method('findAll')->willReturn([
            ['idservis'=>1,'namaservis'=>'Servis A'],
            ['idservis'=>2,'namaservis'=>'Servis B'],
        ]);
        $this->servisModelMock->method('find')->willReturnCallback(function($id){
            return $id==1 ? ['idservis'=>1,'namaservis'=>'Servis A'] : null;
        });

        // Mock ModulDescModel
        $this->descModelMock = $this->createMock(ModulDescModel::class);
        $this->descModelMock->method('where')->willReturnSelf();
        $this->descModelMock->method('first')->willReturn(['iddesc'=>1,'108idservis'=>1,'description'=>'Desc A']);
        $this->descModelMock->method('insert')->willReturn(1);
        $this->descModelMock->method('update')->willReturn(true);

        // Mock ApprovalDokumenModel
        $this->approvalModelMock = $this->createMock(ApprovalDokumenModel::class);
        $this->approvalModelMock->method('where')->willReturnSelf();
        $this->approvalModelMock->method('findAll')->willReturn([
            ['status'=>'pending'],
            ['status'=>'approved'],
            ['status'=>'approved'],
        ]);

        // Instantiate controller and inject mocks
        $this->controller = new PerincianModulController();
        
        // Use reflection to inject protected properties
        $reflection = new \ReflectionClass($this->controller);
        
        $servisProperty = $reflection->getProperty('servisModel');
        $servisProperty->setAccessible(true);
        $servisProperty->setValue($this->controller, $this->servisModelMock);
        
        $descProperty = $reflection->getProperty('descModel');
        $descProperty->setAccessible(true);
        $descProperty->setValue($this->controller, $this->descModelMock);

        // Override model() helper to return ApprovalDokumenModel mock
        if (!function_exists('model')) {
            function model($class)
            {
                global $approval_model_mock;
                return $approval_model_mock;
            }
        }
        $GLOBALS['approval_model_mock'] = $this->approvalModelMock;
    }

    // ===============================
    // index() - returns view with servis list
    // ===============================
    public function testIndexReturnsView()
    {
        ob_start();
        $this->controller->index();
        $rendered = ob_get_clean();

        $this->assertStringContainsString('Servis A', $rendered);
        $this->assertStringContainsString('Servis B', $rendered);
    }

    // ===============================
    // getServis() valid ID
    // ===============================
    public function testGetServisValidId()
    {
        $response = $this->controller->getServis(1);
        
        $this->assertNotNull($response);
        $json = json_decode($response->getBody(), true);

        $this->assertTrue($json['status']);
        $this->assertEquals('Servis A', $json['servis']['namaservis']);
        $this->assertEquals('Desc A', $json['desc']['description']);
        $this->assertEquals(1, $json['dokumen_status']['pending']);
        $this->assertEquals(2, $json['dokumen_status']['approved']);
        $this->assertEquals(0, $json['dokumen_status']['rejected']);
        $this->assertEquals(3, $json['dokumen_status']['total']);
    }

    // ===============================
    // getServis() invalid ID
    // ===============================
    public function testGetServisInvalidId()
    {
        $response = $this->controller->getServis(999);
        
        $this->assertNotNull($response);
        $json = json_decode($response->getBody(), true);

        $this->assertFalse($json['status']);
        $this->assertEquals('Servis tidak ditemui.', $json['message']);
    }

    // ===============================
    // save() with valid data
    // ===============================
    public function testSaveValid()
    {
        $reflection = new \ReflectionClass($this->controller);
        $requestProperty = $reflection->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($this->controller, \Config\Services::request());
        $_POST['idservis'] = 1;
        $_POST['namaservis'] = 'Servis Test';
        $_POST['infourl'] = 'https://example.com/info';
        $_POST['mohonurl'] = 'https://example.com/mohon';
        $_POST['description'] = 'Deskripsi test';

        $this->controller->save();

        $this->assertTrue(session()->getFlashdata('success') !== null);
    }

    // ===============================
    // save() with invalid data
    // ===============================
    public function testSaveInvalid()
    {
        $reflection = new \ReflectionClass($this->controller);
        $requestProperty = $reflection->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($this->controller, \Config\Services::request());
        $_POST['idservis'] = 999; // invalid servis
        $_POST['namaservis'] = '';
        $_POST['description'] = '';

        $this->controller->save();

        $this->assertTrue(session()->getFlashdata('error') !== null);
    }

    // ===============================
    // delete() valid ID
    // ===============================
    public function testDeleteValid()
    {
        $this->servisModelMock->expects($this->once())->method('delete')->with(1);
        $this->descModelMock->expects($this->once())->method('where')->willReturnSelf();
        $this->descModelMock->expects($this->once())->method('delete');

        $this->controller->delete(1);

        $this->assertTrue(session()->getFlashdata('success') !== null);
    }

    // ===============================
    // delete() invalid ID
    // ===============================
    public function testDeleteInvalid()
    {
        $this->controller->delete(999);
        $this->assertTrue(session()->getFlashdata('error') !== null);
    }
}
