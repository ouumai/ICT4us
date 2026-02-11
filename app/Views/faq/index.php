<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>document.title = "Pengurusan FAQ Modul";</script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. Global Font */
    body, .content-wrapper, .main-sidebar, h1, h2, h3, h4, h5, h6, p, span, div, table, input, textarea, button, select {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* 2. Hide Dashboard Header */
    .content-wrapper > .container-fluid > .d-md-flex.align-items-center.justify-content-between.mb-5 {
        display: none !important;
    }

    /* 3. Glassmorphism Card Style */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        border-radius: 1.5rem;
    }

    /* 4. Input Modern Style */
    .input-modern {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        outline: none;
        transition: all 0.2s;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .input-modern:focus { 
        border-color: #4f46e5; 
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); 
    }

    /* 5. Accordion Styling */
    .accordion-item {
        border: 1px solid #e2e8f0 !important;
        border-radius: 1.25rem !important;
        margin-bottom: 1rem;
        overflow: hidden;
        background: white;
    }
    .accordion-button {
        padding: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        background: white;
        width: 100%;
        text-align: left;
    }
    .accordion-button:not(.collapsed) {
        background: #f8fafc;
        color: #4f46e5;
        box-shadow: none;
    }

    /* 6. SweetAlert Custom Styling */
    .swal-rounded {
        border-radius: 2rem !important;
        padding: 1.5rem !important;
    }
    
    /* Styling for Close Button (X) */
    .swal2-close {
        border-radius: 50% !important;
        margin-top: 10px !important;
        margin-right: 10px !important;
        transition: all 0.2s ease !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .swal2-close:hover {
        background: transparent !important; 
        color: #ef4444 !important; 
    }

    .swal2-actions {
        width: 100% !important;
        display: flex !important;
        gap: 12px !important;
        margin-top: 1.5rem !important;
        padding: 0 1rem !important;
    }

    .btn-swal-hantar {
        flex: 1 !important;
        background: #4f46e5 !important; 
        color: white !important; 
        font-weight: 700 !important;
        padding: 14px !important; 
        border-radius: 16px !important;
        border: none !important; 
        order: 2;
    }

    .btn-swal-batal {
        flex: 1 !important;
        background: #fee2e2 !important; 
        color: #ef4444 !important; 
        font-weight: 700 !important;
        padding: 14px !important; 
        border-radius: 16px !important;
        order: 1;
    }

    .hidden { display: none !important; }
    .input-with-icon { padding-left: 3rem !important; }
</style>

<div class="container-fluid py-4">
    <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-50 p-3 rounded-2xl">
                <i class="bi bi-question-diamond-fill text-3xl text-indigo-500"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Pengurusan FAQ Modul</h1>
                <p class="text-gray-500 font-medium mb-0">Uruskan soalan lazim mengikut kategori servis sistem</p>
            </div>
        </div>
        <div id="actionArea" class="hidden mt-4 md:mt-0">
            <button id="btnCreateFaq" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl font-bold transition shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> Tambah FAQ Baru 
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
        <div class="md:col-span-4">
            <div class="glass-card p-6 h-full">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="bi bi-tag-fill"></i> Kategori Servis
                </label>
                <div class="relative">
                    <select id="dropdownServis" class="w-full appearance-none bg-white border border-slate-200 p-3 rounded-xl focus:outline-none font-semibold text-slate-600 cursor-pointer shadow-sm">
                        <option value="">-- Sila Pilih Servis --</option>
                        <?php foreach($servisList as $s): ?>
                            <option value="<?= esc($s['idservis']) ?>"><?= esc($s['namaservis']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <div class="md:col-span-8">
            <div id="searchContainer" class="glass-card p-6 h-full hidden">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="bi bi-question-lg"></i> Cari Soalan / Jawapan
                </label>
                <div class="relative">
                    <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 z-10"></i>
                    <input type="text" id="faqSearch" placeholder="Taip kata kunci carian..." class="input-modern input-with-icon">
                </div>
            </div>
        </div>
    </div>

    <div id="emptyState" class="glass-card py-20 bg-white text-center">
        <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-filter text-4xl text-slate-300"></i>
        </div>
        <h5 class="text-slate-900 font-bold mb-1">Sila Pilih Servis</h5>
        <p class="text-slate-500 font-medium">Pilih kategori servis di atas untuk memaparkan senarai FAQ.</p>
    </div>

    <div id="faqArea" class="hidden">
        <div id="faqList"></div>
    </div>
</div>

<script>
$(document).ready(function() {

    function loadFaq(id) {
        $('#faqList').html('<div class="text-center py-10 text-slate-400 font-bold">Memproses data...</div>');
        $.get(`<?= base_url('faq/ajax') ?>/${id}`, function(res) {
            if(res.success && res.faqs.length > 0) {
                let html = '<div class="accordion" id="faqAccordion">';
                res.faqs.forEach((faq, i) => {
                    let safeQuestion = faq.question.replace(/'/g, "\\'");
                    let safeAnswer = faq.answer.replace(/'/g, "\\'");
                    html += `
                    <div class="accordion-item shadow-sm border-0">
                        <div class="flex items-center justify-between bg-white pr-4">
                            <button class="accordion-button ${i>0?'collapsed':''}" data-bs-toggle="collapse" data-bs-target="#faq${faq.id}">
                                <span>${faq.question}</span>
                            </button>
                            <div class="flex gap-2 py-2">
                                <button onclick="openEditModal(${faq.id}, '${safeQuestion}', '${safeAnswer}')" class="w-10 h-10 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button onclick="deleteFaq(${faq.id})" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition" title="Padam">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div id="faq${faq.id}" class="accordion-collapse collapse ${i===0?'show':''}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-slate-600 leading-relaxed p-6 border-t border-slate-50">
                                ${faq.answer}
                            </div>
                        </div>
                    </div>`;
                });
                html += '</div>';
                $('#faqList').html(html);
            } else {
                $('#faqList').html('<div class="glass-card p-20 text-center">...</div>');
            }
        });
    }

    // --- TAMBAH FAQ (DENGAN X) ---
    $('#btnCreateFaq').click(function() {
        const idServis = $('#dropdownServis').val();
        Swal.fire({
            title: '<span class="text-2xl font-bold">Tambah FAQ Baru</span>',
            showCloseButton: true, // ICON X ADA KAT SINI
            html: `
                <div class="text-left mt-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Soalan</label>
                    <input id="swal-question" class="input-modern mb-4" placeholder="Taip soalan...">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jawapan</label>
                    <textarea id="swal-answer" class="input-modern" rows="5" placeholder="Taip jawapan..."></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan Rekod',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal-rounded',
                confirmButton: 'btn-swal-hantar',
                cancelButton: 'btn-swal-batal'
            },
            preConfirm: () => {
                const q = document.getElementById('swal-question').value;
                const a = document.getElementById('swal-answer').value;
                if (!q || !a) { Swal.showValidationMessage('Sila isi semua!'); }
                return { question: q, answer: a, idservis: idServis };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('faq/save') ?>', result.value, function() {
                    Swal.fire({ title: 'Berjaya!', icon: 'success', customClass: { popup: 'swal-rounded' } });
                    loadFaq(idServis);
                });
            }
        });
    });

    // --- EDIT FAQ (DENGAN X) ---
    window.openEditModal = function(id, question, answer) {
        Swal.fire({
            title: '<span class="text-2xl font-bold">Kemaskini FAQ</span>',
            showCloseButton: true, // ICON X ADA KAT SINI JUGA
            html: `
                <div class="text-left mt-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Soalan</label>
                    <input id="swal-question" class="input-modern mb-4" value="${question}">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jawapan</label>
                    <textarea id="swal-answer" class="input-modern" rows="5">${answer}</textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kemaskini',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal-rounded',
                confirmButton: 'btn-swal-hantar',
                cancelButton: 'btn-swal-batal'
            },
            preConfirm: () => {
                const q = document.getElementById('swal-question').value;
                const a = document.getElementById('swal-answer').value;
                if (!q || !a) { Swal.showValidationMessage('Isi semua!'); }
                return { id: id, question: q, answer: a };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`<?= base_url('faq/update') ?>/${result.value.id}`, result.value, function() {
                    Swal.fire({ title: 'Berjaya!', icon: 'success', customClass: { popup: 'swal-rounded' } });
                    loadFaq($('#dropdownServis').val());
                });
            }
        });
    };

    // ... Event listener lain (dropdown, delete, search) dikekalkan sama ...
    $('#dropdownServis').change(function() {
        const id = $(this).val();
        if (!id) {
            $('#faqArea, #actionArea, #searchContainer').addClass('hidden');
            $('#emptyState').removeClass('hidden');
            return;
        }
        $('#emptyState').addClass('hidden');
        $('#faqArea, #actionArea, #searchContainer').removeClass('hidden');
        loadFaq(id);
    });

    window.deleteFaq = function(id) {
        Swal.fire({
            title: 'Padam FAQ?',
            text: "Kekal!", icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal',
            customClass: { popup: 'swal-rounded', confirmButton: 'btn-swal-hantar', cancelButton: 'btn-swal-batal' }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('faq/delete') ?>/${id}`,
                    method: 'DELETE',
                    success: function() { 
                        loadFaq($('#dropdownServis').val());
                        Swal.fire({ title: 'Dipadam!', icon: 'success', customClass: { popup: 'swal-rounded' } });
                    }
                });
            }
        });
    };

    $('#faqSearch').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#faqAccordion .accordion-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1)
        });
    });
});
</script>

<?= $this->endSection() ?>