<?php

namespace App\Controllers;

use App\Models\FaqModel;
use App\Models\ServisModel;
use CodeIgniter\API\ResponseTrait;

class FaqController extends BaseController
{
    use ResponseTrait;

    protected $faqModel;
    protected $servisModel;
    protected $db;

    public function __construct()
    {
        $this->faqModel    = new FaqModel();
        $this->servisModel = new ServisModel();
        $this->db          = \Config\Database::connect();
        helper(['url', 'form', 'text']); 
    }

    private function getValidationRules()
    {
        return [
            'question' => 'required|min_length[5]|max_length[255]',
            'answer'   => 'required|min_length[5]',
            'idservis' => 'required'
        ];
    }

    private function cleanInput($html)
    {
        $allowed_tags = '<p><br><b><i><u><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><div><span>';
        $clean = strip_tags($html, $allowed_tags);
        $clean = preg_replace('/on[a-z]+="[^"]*"/i', '', $clean);
        return $clean;
    }

    public function index($id = null)
    {
        $servisList = $this->servisModel->select('idservis, namaservis')->orderBy('namaservis', 'ASC')->findAll();

        return view('faq/index', [
            'servisList' => $servisList,
            'title'      => 'Pengurusan FAQ',
            'selectedId' => $id 
        ]);
    }

    public function ajax($idservis)
    {
        if (!$this->request->isAJAX()) return $this->failForbidden();

        try {
            $faqs = $this->faqModel->where('idservis', $idservis)->orderBy('created_at', 'DESC')->findAll();
            return $this->respond(['success' => true, 'faqs' => $faqs]);
        } catch (\Exception $e) {
            return $this->failServerError('Ralat sistem.');
        }
    }

    public function save()
    {
        if (!$this->validate($this->getValidationRules())) {
            return $this->respond(['status' => 'error', 'message' => 'Sila isi borang dengan lebih jelas.'], 400);
        }

        $idservis = $this->request->getPost('idservis');
        $question = strip_tags($this->request->getPost('question'));

        // Check Duplicate
        $exists = $this->faqModel->where(['idservis' => $idservis, 'LOWER(question)' => strtolower(trim($question))])->first();
        if ($exists) {
            return $this->respond(['status' => 'error', 'message' => 'Soalan ini sudah wujud dalam kategori ini!'], 400);
        }

        $data = [
            'idservis' => $idservis,
            'question' => $question,
            'answer'   => $this->cleanInput($this->request->getPost('answer'))
        ];

        if ($this->faqModel->insert($data)) {
            return $this->respond(['status' => 'success', 'message' => 'FAQ berjaya disimpan!']);
        }
        return $this->fail('Gagal simpan.');
    }

    public function update($id = null)
    {
        $data = [
            'question' => strip_tags($this->request->getVar('question')),
            'answer'   => $this->cleanInput($this->request->getVar('answer')),
        ];

        if ($this->faqModel->update($id, $data)) {
            return $this->respond(['success' => true, 'message' => 'Berjaya dikemaskini.']);
        }
        return $this->fail('Gagal update.');
    }

    public function delete($id = null)
    {
        if ($this->faqModel->delete($id)) {
            return $this->respondDeleted(['success' => true]);
        }
        return $this->failServerError('Gagal padam.');
    }
}