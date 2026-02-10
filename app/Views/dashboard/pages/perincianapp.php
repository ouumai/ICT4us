<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>document.title = "Sistem Perincian Modul";</script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="<?= base_url('ckeditor5-build-classic/build/ckeditor.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. Global Font Setup */
    body, .content-wrapper, .main-sidebar, h1, h2, h3, h4, h5, h6, p, span, div, table, input, textarea, button {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* 2. Hide Default Dashboard Header */
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
        border-color: #3b82f6; 
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); 
    }

    /* 5. Reset Button Style */
    .btn-reset {
        color: #64748b;
        font-weight: 700;
        padding: 12px 20px;
        border-radius: 12px;
        transition: 0.2s;
    }
    .btn-reset:hover { background: #FEEBE7; color: #1e293b; }

    /* CKEditor Customization */
    .ck-editor__main>.ck-editor__editable { min-height: 250px; border-radius: 0 0 12px 12px !important; padding: 10px 20px !important; }
    .ck.ck-editor__top { border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    
    .hidden { display: none; }
</style>

<div class="container-fluid py-4">
    <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-50 p-3 rounded-2xl">
                <i class="bi bi-collection-fill text-3xl text-indigo-500"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Sistem Perincian Modul</h1>
                <p class="text-slate-500 font-medium italic mb-0">Kemaskini maklumat dan penerangan servis rasmi</p>
            </div>
        </div>
    </div>

    <div class="glass-card p-6 mb-8 max-w-md">
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
            <i class="bi bi-tag-fill text-black-400"></i> Pilih Servis Utama
        </label>
        <div class="relative">
            <select id="dropdownServis" class="w-full appearance-none bg-white border border-slate-200 p-3 rounded-xl focus:outline-none font-semibold text-slate-600 cursor-pointer shadow-sm">
                <option value="">-- Sila Pilih Servis --</option>
                <?php foreach($servisList as $s): ?>
                    <option value="<?= esc($s['idservis']) ?>" 
                            data-name="<?= esc($s['namaservis']) ?>"
                            data-infourl="<?= esc($s['infourl']) ?>"
                            data-mohonurl="<?= esc($s['mohonurl']) ?>">
                        <?= esc($s['namaservis']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <i class="bi bi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none"></i>
        </div>
    </div>

    <div id="emptyState" class="glass-card py-20 bg-white">
        <div class="text-center">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-filter text-4xl text-slate-300"></i>
            </div>
            <h5 class="text-slate-900 font-bold mb-1">Sila Pilih Servis</h5>
            <p class="text-slate-500 font-medium">Pilih kategori servis di atas untuk memaparkan borang perincian.</p>
        </div>
    </div>

    <div id="formArea" class="hidden">
        <div class="glass-card p-8 bg-white">
            <form id="servisForm" action="<?= site_url('perincianmodul/save') ?>" method="POST" class="space-y-8">
                <?= csrf_field() ?>
                <input type="hidden" name="idservis" id="idservis">

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Servis Rasmi (Max 145 Aksara)</label>
                    <input type="text" id="namaservis" name="namaservis" class="input-modern" maxlength="145" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Info URL (HTTP/HTTPS/FTP)</label>
                        <input type="url" id="infourl" name="infourl" class="input-modern" placeholder="https://...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Mohon URL (HTTP/HTTPS/FTP)</label>
                        <input type="url" id="mohonurl" name="mohonurl" class="input-modern" placeholder="https://...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description / Perincian</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="flex justify-end items-center gap-4 pt-6 border-t border-slate-100">
                    <button type="button" id="btnReset" class="btn-reset text-red">Reset</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-blue-500/30">
                        <i class="bi bi-check2-circle me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let editor;
    let originalData = {};

    // Initialize CKEditor
    ClassicEditor.create(document.querySelector('#description'), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
    }).then(newEditor => {
        editor = newEditor;
    }).catch(error => console.error(error));

    // Handle Dropdown Change
    $('#dropdownServis').change(function() {
        const id = $(this).val();
        const selectedOption = $(this).find('option:selected');

        if (!id) {
            $('#formArea').addClass('hidden');
            $('#emptyState').removeClass('hidden');
            return;
        }

        $('#emptyState').addClass('hidden');
        $('#formArea').removeClass('hidden');

        const name = selectedOption.data('name');
        const info = selectedOption.data('infourl');
        const mohon = selectedOption.data('mohonurl');

        $('#idservis').val(id);
        $('#namaservis').val(name);
        $('#infourl').val(info);
        $('#mohonurl').val(mohon);

        editor.setData('<p><i>Memuatkan data...</i></p>');
        $.get(`<?= base_url('perincianmodul/getServis') ?>/${id}`, function(res) {
            const descContent = (res.desc && res.desc.description) ? res.desc.description : '';
            editor.setData(descContent);
            
            originalData = {
                name: name,
                info: info,
                mohon: mohon,
                desc: descContent
            };
        });
    });

    // Reset Logic
    $('#btnReset').click(function() {
        if (!originalData.name) return;
        Swal.fire({
            title: 'Reset Semula?',
            text: "Data akan dikembalikan kepada maklumat asal.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#namaservis').val(originalData.name);
                $('#infourl').val(originalData.info);
                $('#mohonurl').val(originalData.mohon);
                editor.setData(originalData.desc);
            }
        });
    });

    // Alert Handling
    <?php if(session()->getFlashdata('success')): ?>
        Swal.fire({ icon: 'success', title: 'Berjaya!', text: '<?= session()->getFlashdata('success') ?>', confirmButtonColor: '#3b82f6' });
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>