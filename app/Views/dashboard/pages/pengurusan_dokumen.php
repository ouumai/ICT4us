<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>document.title = "Pengurusan Dokumen Modul";</script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

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

    /* 4. MODAL FULL SCREEN BLUR (Tutup Sidebar, Header & Footer) */
    .modal-backdrop { display: none !important; }
    .modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 100000 !important; 
        background: rgba(15, 23, 42, 0.5) !important; 
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        display: none; 
        overflow-x: hidden;
        overflow-y: auto;
    }

    .modal-dialog {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 100vh !important;
        margin: 0 auto !important;
        max-width: 500px !important;
        pointer-events: none;
    }

    .modal-content {
        pointer-events: auto;
        border: none !important;
        border-radius: 2rem !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        padding: 2.5rem !important;
        background: white !important;
        width: 100% !important;
    }

    /* 5. Input & Button Styles */
    .input-modern {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        outline: none;
        transition: all 0.2s;
        font-size: 0.95rem;
    }
    .input-modern:focus { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

    .btn-submit-blue {
        background-color: #4f46e5;
        color: white;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        margin-top: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    /* 6. Status UI & Action Buttons */
    .status-pill { padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-approved { background-color: #dcfce7; color: #166534; }
    .status-rejected { background-color: #fee2e2; color: #991b1b; }

    .btn-action {
        width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px; transition: 0.2s; border: none;
    }
</style>

<div class="container-fluid py-4">
    <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-100 p-3 rounded-2xl">
                <i class="bi bi-files text-3xl text-indigo-600"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Pengurusan Dokumen Modul</h1>
                <p class="text-slate-500 font-medium italic mb-0">Kemaskini dan urus fail mengikut servis</p>
            </div>
        </div>
        <div class="mt-4 md:mt-0">
            <button id="btnTambahModal" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg disabled:opacity-50" data-bs-toggle="modal" data-bs-target="#modalTambah" disabled>
                <i class="bi bi-cloud-plus-fill me-2"></i> Muat Naik Dokumen
            </button>
        </div>
    </div>

    <div class="glass-card p-6 mb-8 max-w-md">
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
            <i class="bi bi-tag-fill"></i> Pilih Kategori Servis
        </label>
        <div class="relative">
            <select id="dropdownServis" class="w-full appearance-none bg-white border border-slate-200 p-3 rounded-xl focus:outline-none font-semibold text-slate-600 cursor-pointer">
                <option value="">Sila Pilih Servis...</option>
                <?php foreach($servis as $s): ?>
                    <option value="<?= esc($s['idservis']) ?>"><?= esc($s['namaservis']) ?></option>
                <?php endforeach; ?>
            </select>
            <i class="bi bi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none"></i>
        </div>
    </div>

    <div id="dokumenArea" class="glass-card overflow-hidden bg-white">
        <div class="text-center py-20">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="bi bi-filter text-4xl text-black-500"></i>
            </div>
            <h5 class="text-slate-900 font-bold mb-1">Sila Pilih Servis</h5>
            <p class="text-slate-500 font-medium">Pilih kategori servis di atas untuk memaparkan senarai dokumen.</p>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-2xl font-bold text-slate-800 m-0">Muat Naik Dokumen Baru</h5>
                <button type="button" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-3xl leading-none outline-none" data-bs-dismiss="modal">&times;</button>
            </div>
            <form id="formTambah">
                <input type="hidden" name="idservis" id="inputServisTambah">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tajuk Dokumen</label>
                    <input type="text" name="nama" class="input-modern" required placeholder="Contoh: Sijil Kelayakan">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Penerangan / Nota</label>
                    <textarea name="descdoc" class="input-modern" rows="3" placeholder="Nota tambahan tentang dokumen ini..."></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Fail (PDF Sahaja - Maks 10MB)</label>
                    <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-700" accept="application/pdf" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-blue-500/30">
                    Hantar Dokumen
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-2xl font-bold text-slate-800 m-0">Kemaskini Maklumat</h5>
                <button type="button" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-3xl leading-none outline-none" data-bs-dismiss="modal">&times;</button>
            </div>
            <form id="formEdit">
                <input type="hidden" name="iddoc" id="edit_iddoc">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Dokumen</label>
                    <input type="text" name="nama" id="edit_nama" class="input-modern" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Catatan</label>
                    <textarea name="descdoc" id="edit_desc" class="input-modern" rows="3"></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tukar Fail (Opsional)</label>
                    <input type="file" name="file" id="edit_file" class="block w-full text-sm" accept="application/pdf">
                </div>
                <button type="submit" class="btn-submit-blue shadow-lg shadow-indigo-200">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function refreshTable(idservis){
        if(!idservis){
            $('#dokumenArea').html(`
                <div class="text-center py-20">
                    <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="bi bi-filter text-4xl text-black-500"></i>
                    </div>
                    <h5 class="text-slate-900 font-bold mb-1">Sila Pilih Servis</h5>
                    <p class="text-slate-500 font-medium">Pilih kategori servis di atas untuk memaparkan senarai dokumen.</p>
                </div>
            `);
            $('#btnTambahModal').prop('disabled', true);
            return;
        }
        $('#btnTambahModal').prop('disabled', false);
        $('#dokumenArea').html('<div class="text-center py-20 text-slate-400 italic">Memproses data...</div>');

        $.get('/dokumen/getDokumen/' + idservis, function(res){
            var items = res.items;
            if(!items || items.length === 0){
                $('#dokumenArea').html(`
                    <div class="p-20 text-center">
                        <div class="bg-red-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="bi bi-folder-x text-4xl text-red-500"></i>
                        </div>
                        <h5 class="text-slate-900 font-bold mb-1">Tiada Fail Dijumpai</h5>
                        <p class="text-slate-500 font-medium">Tiada dokumen yang dimuat naik untuk servis ini lagi.</p>
                    </div>
                `);
                return;
            }

            let html = '<div class="overflow-x-auto"><table class="min-w-full">';
            html += '<thead class="bg-slate-50 border-b text-slate-500 text-xs font-bold uppercase tracking-wider"><tr class="text-left"><th class="p-4 text-center w-24">Fail</th><th class="p-4">Maklumat Dokumen</th><th class="p-4 text-center">Status</th><th class="p-4 text-right">Tindakan</th></tr></thead>';
            html += '<tbody class="divide-y divide-slate-100 bg-white">';

            items.forEach(d => {
                const fileUrl = `/dokumen/viewFile/${d.idservis}/${d.namafail}`;
                const icon = `<div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto shadow-sm"><i class="bi bi-file-earmark-pdf-fill fs-4"></i></div>`;

                html += `<tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-4">${icon}</td>
                    <td class="p-4">
                        <div class="font-bold text-slate-800">${d.nama}</div>
                        <div class="text-xs text-slate-400 mt-1">${d.descdoc || 'Tiada nota'}</div>
                        <div class="flex items-center gap-2 mt-2 text-[10px] font-bold text-slate-400 uppercase"><i class="bi bi-clock"></i> ${formatDate(d.created_at)}</div>
                    </td>
                    <td class="p-4 text-center"><span class="status-pill status-${d.status}">${d.status}</span></td>
                    <td class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="${fileUrl}" target="_blank" class="btn-action bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white" title="Lihat"><i class="bi bi-eye-fill"></i></a>
                            <button onclick="editDokumen(${d.iddoc})" class="btn-action bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit"><i class="bi bi-pencil-square"></i></button>
                            <button onclick="hapusDokumen(${d.iddoc})" class="btn-action bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white" title="Padam"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>`;
            });
            html += '</tbody></table></div>';
            $('#dokumenArea').html(html);
        });
    }

    function formatDate(str){
        if(!str) return '-';
        const d = new Date(str);
        return d.toLocaleDateString('ms-MY', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    $('#dropdownServis').change(function(){
        var val = $(this).val();
        $('#inputServisTambah').val(val);
        refreshTable(val);
    });

    // AJAX CRUD Logic kekal sama mengikut kod asal anda
    $('#formTambah').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '/dokumen/tambah',
            type: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: function(res){
                if(res.status){
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: res.msg, timer: 1500, showConfirmButton: false });
                    $('#formTambah')[0].reset();
                    bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
                    refreshTable($('#dropdownServis').val());
                }
            }
        });
    });

    window.editDokumen = function(id){
        $.get('/dokumen/edit/' + id, function(res){
            if(res.status){
                $('#edit_iddoc').val(res.data.iddoc);
                $('#edit_nama').val(res.data.nama);
                $('#edit_desc').val(res.data.descdoc);
                new bootstrap.Modal(document.getElementById('modalEdit')).show();
            }
        });
    }

    $('#formEdit').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '/dokumen/kemaskini/' + $('#edit_iddoc').val(),
            type: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: function(res){
                if(res.status){
                    Swal.fire({ icon: 'success', title: 'Dikemaskini', timer: 1500, showConfirmButton: false });
                    bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                    refreshTable($('#dropdownServis').val());
                }
            }
        });
    });

    window.hapusDokumen = function(id){
        Swal.fire({ title: 'Nak Padam?', text: "Fail akan dibuang selamanya!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#4f46e5' }).then((result) => {
            if (result.isConfirmed) {
                $.post('/dokumen/hapus/' + id, function(){ refreshTable($('#dropdownServis').val()); });
            }
        });
    }
</script>

<?= $this->endSection() ?>