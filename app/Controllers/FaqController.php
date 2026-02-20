<?php

namespace App\Controllers;

use App\Models\FaqModel;
use App\Models\ServisModel;
use CodeIgniter\API\ResponseTrait; // Guna trait untuk response JSON lebih standard

class FaqController extends BaseController
{
    use ResponseTrait; // Memudahkan return JSON

    protected $faqModel;
    protected $servisModel;
    protected $db;

    public function __construct()
    {
        // Load Models dan Database
        $this->faqModel    = new FaqModel();
        $this->servisModel = new ServisModel();
        $this->db          = \Config\Database::connect(); // Load DB instance untuk Transaction
        helper(['url', 'form', 'text']); 
    }

    /**
     * Rules Validation dengan Custom Error Messages
     */
    private function getValidationRules()
    {
        return [
            'question' => [
                'rules'  => 'required|min_length[5]|max_length[255]',
                'errors' => [
                    'required'   => 'Sila tulis soalan.',
                    'min_length' => 'Soalan terlalu pendek (min 5 huruf).',
                ]
            ],
            'answer' => [
                'rules'  => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Jawapan wajib diisi.',
                ]
            ],
            'idservis' => [
                // TELAH DIBETULKAN: Menggunakan nama table aict4u103dservis
                'rules'  => 'required|is_not_unique[aict4u103dservis.idservis]', 
                'errors' => [
                    'is_not_unique' => 'Kategori servis tidak sah.',
                ]
            ]
        ];
    }

    /**
     * Membersihkan HTML dari CKEditor (Security: Anti-XSS)
     * Kita buang tag bahaya seperti <script>, <iframe>, <object>
     */
    private function cleanInput($html)
    {
        // 1. Benarkan tag formatting sahaja
        $allowed_tags = '<p><br><b><i><u><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><div><span>';
        
        // 2. Strip tags yang tak dibenarkan
        $clean = strip_tags($html, $allowed_tags);

        // 3. Extra layer: Buang attribute javascript (cth: onload, onclick)
        $clean = preg_replace('/on[a-z]+="[^"]*"/i', '', $clean);
        
        return $clean;
    }

    // -------------------------------------------------------------------------
    // PAGE VIEWS
    // -------------------------------------------------------------------------

    public function index()
    {
        // Optimization: Ambil ID dan Nama sahaja, tak perlu column lain yang berat
        $servisList = $this->servisModel->select('idservis, namaservis')
                                        ->orderBy('namaservis', 'ASC')
                                        ->findAll();

        return view('faq/index', [
            'servisList' => $servisList,
            'title'      => 'Pengurusan FAQ'
        ]);
    }

    public function create($idservis)
    {
        // Dapatkan maklumat servis berdasarkan ID
        $servis = $this->servisModel->find($idservis);
        
        // Jika servis tak jumpa, redirect dengan error
        if (!$servis) {
            return redirect()->to('/faq')->with('error', 'Servis tidak ditemui.');
        }

        // Papar form create dengan maklumat servis
        return view('faq/create', ['servis' => $servis]);
    }

    public function edit($id)
    {
        // Semak kewujudan rekod FAQ
        $faq = $this->faqModel->find($id);
        
        // Jika tak jumpa, redirect dengan error
        if (!$faq) {
            return redirect()->to('/faq')->with('error', 'Rekod FAQ tidak ditemui.');
        }

        // Dapatkan maklumat servis untuk paparan
        $servis = $this->servisModel->find($faq['idservis']);
        return view('faq/edit', ['faq' => $faq, 'servis' => $servis]);
    }

    // -------------------------------------------------------------------------
    // CRUD OPERATIONS (LOGIC)
    // -------------------------------------------------------------------------

    public function ajax($idservis)
    {
        // Pastikan request adalah AJAX
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('Akses tidak dibenarkan.');
        }

        // Error Handling jika DB problem
        try {
            $faqs = $this->faqModel->where('idservis', $idservis)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll();

            return $this->respond([
                'success' => true,
                'faqs'    => $faqs
            ]);
        } catch (\Exception $e) {
            // Log error untuk developer check nanti (writepath/logs)
            log_message('error', '[FAQ AJAX] ' . $e->getMessage());
            return $this->failServerError('Ralat sistem semasa memuat turun data.');
        }
    }

    public function store()
    {
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = [
            'idservis'   => $this->request->getPost('idservis'),
            'question'   => strip_tags($this->request->getPost('question')),
            'answer'     => $this->cleanInput($this->request->getPost('answer')),
            'is_pinned'  => $this->request->getPost('is_pinned') ?? 0, // TAMBAH NI
            'sort_order' => $this->request->getPost('sort_order') ?? 0, // TAMBAH NI
        ];

        if ($this->faqModel->insert($data)) {
            return redirect()->to('/faq')->with('success', 'FAQ baru berjaya ditambah.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal simpan data.');
        }
    }

    public function update($id = null)
    {
        // 1. Check rekod wujud tak
        $faq = $this->faqModel->find($id);
        if (!$faq) {
            return $this->failNotFound('Rekod tidak dijumpai.');
        }

        // 2. Ambil data dari request
        // Tips: Kita guna data asal kalau input baru kosong
        $data = [
            'question' => strip_tags($this->request->getVar('question')),
            'answer'   => $this->cleanInput($this->request->getVar('answer')),
            'idservis' => $this->request->getVar('idservis') ?? $faq['idservis'], // Guna ID sedia ada kalau tak hantar
        ];

        // 3. Update database
        if ($this->faqModel->update($id, $data)) {
            return $this->respond(['success' => true, 'message' => 'FAQ berjaya dikemaskini.']);
        } else {
            return $this->fail('Gagal mengemaskini data.');
        }
    }

    // Delete FAQ Record
    public function delete($id)
    {
        // Pastikan request adalah AJAX
        if (!$this->request->isAJAX()) {
            return $this->failForbidden();
        }

        try {
            $this->db->transStart();
            
            // Check kewujudan sebelum delete
            if (!$this->faqModel->find($id)) {
                return $this->failNotFound('Rekod tidak ditemui.');
            }
            
            $this->faqModel->delete($id);
            
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return $this->respondDeleted(['success' => true, 'message' => 'FAQ berjaya dipadam.']);

        } catch (\Exception $e) {
            log_message('error', '[FAQ DELETE] ' . $e->getMessage());
            return $this->failServerError('Ralat semasa memadam rekod.');
        }
    }
}