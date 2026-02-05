<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class DokumenControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        // Sini kau boleh masukkan Seeder kalau nak database ada data siap-siap
        // $this->seed('DokumenSeeder'); 
    }

    public function testIndexPaparHalaman()
    {
        // Ikut route: $routes->get('/', 'DokumenController::index'); dalam group 'dokumen'
        $result = $this->get('dokumen');

        $result->assertStatus(200);
        // assertSee selalunya check teks yang ada dalam HTML view kau
        $result->assertSee('Pengurusan Dokumen Modul'); 
    }

    public function testGetDokumenReturnsJson()
    {
        // Ikut route: dokumen/getDokumen/(:num)
        $result = $this->get('dokumen/getDokumen/1');

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
    }

    public function testTambahDokumenBerjaya()
    {
        // Pastikan fail ni wujud di: writable/tests/test_image.jpg
        // Kalau takde, buat satu fail gambar kosong kat situ
        $path = WRITEPATH . 'tests/test_image.jpg';
        if (!file_exists($path)) {
            mkdir(dirname($path), 0777, true);
            file_put_contents($path, 'fake image content');
        }

        $file = new \CodeIgniter\HTTP\Files\UploadedFile(
            $path, 'test_image.jpg', 'image/jpeg', filesize($path), 0
        );

        $result = $this->post('dokumen/tambah', [
            'idservis' => 1,
            'nama'     => 'Dokumen Test',
            'descdoc'  => 'Ini keterangan test',
            'file'     => $file
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
    }

    public function testEditDokumenWujud()
    {
        // Ikut route: dokumen/edit/(:num)
        $result = $this->get('dokumen/edit/1');

        $result->assertStatus(200);
        // Kita check struktur JSON yang dipulangkan
        $json = $result->getJSON();
        $this->assertArrayHasKey('status', json_decode($json, true));
    }

    public function testKemaskiniBerjaya()
    {
        // Ikut route: dokumen/kemaskini/(:num) (Method: POST)
        $result = $this->post('dokumen/kemaskini/1', [
            'nama'    => 'Nama Baru Update',
            'descdoc' => 'Keterangan baru'
        ]);

        $result->assertJSONFragment(['status' => true]);
    }

    public function testSoftDeleteBerjaya()
    {
        // Ikut route kau: $routes->post('softDelete/(:num)', ...)
        // Jadi kena guna post()
        $result = $this->post('dokumen/softDelete/1', []);
        $result->assertJSONFragment(['status' => true]);
    }

    public function testRestoreBerjaya()
    {
        // Ikut route kau: $routes->post('restore/(:num)', ...)
        $result = $this->post('dokumen/restore/1', []);
        $result->assertJSONFragment(['status' => true]);
    }

    public function testKemaskiniDokumenTakWujud()
    {
        $result = $this->post('dokumen/kemaskini/999', [
            'nama' => 'Hantu'
        ]);

        $result->assertJSONFragment([
            'status' => false, 
            'msg'    => 'Dokumen tidak dijumpai.'
        ]);
    }
}