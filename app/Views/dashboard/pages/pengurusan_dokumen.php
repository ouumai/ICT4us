<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>document.title = "Pengurusan Dokumen Modul";</script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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

    /* 4. SweetAlert Custom Styling */
    .swal2-actions {
        width: 100% !important;
        display: flex !important;
        flex-direction: row !important; 
        gap: 12px !important;
        margin-top: 1.5rem !important;
        padding: 0 1rem !important;
    }

    .btn-swal-hantar {
        flex: 1 !important;
        background: #3b82f6 !important; 
        color: white !important; 
        font-weight: 700 !important;
        padding: 14px !important; 
        border-radius: 16px !important;
        border: none !important; 
        font-size: 0.95rem !important;
        order: 2;
    }

    .btn-swal-padam {
        flex: 1 !important;
        background: #fee2e2 !important; 
        color: #ef4444 !important; 
        font-weight: 700 !important;
        padding: 14px !important; 
        border-radius: 16px !important;
        border: none !important;
        font-size: 0.95rem !important;
        order: 1;
    }

    /* Kursor not-allowed untuk butang muat naik */
    #btnTambahModal:disabled {
        cursor: not-allowed !important;
        pointer-events: auto !important;
        opacity: 0.6;
    }

    .swal2-popup { border-radius: 28px !important; padding: 2rem !important; }
    .swal-label-custom { display: block; font-size: 0.8rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; text-align: left; }
    .swal-input-custom { height: 52px; border-radius: 12px; border: 1px solid #e2e8f0; padding: 0 15px; width: 100%; background-color: #ffffff; font-weight: 500; font-size: 0.95rem; }

    /* 5. Status UI & Action Buttons */
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
            <button id="btnTambahModal" onclick="openDokumenEditor()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl font-bold transition flex items-center shadow-lg disabled:opacity-50" disabled>
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
        $('#dokumenArea').html('<div class="text-center py-20 text-slate-400">Memproses data...</div>');

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
                            <button onclick="openDokumenEditor(${d.iddoc})" class="btn-action bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit"><i class="bi bi-pencil-square"></i></button>
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
        refreshTable($(this).val());
    });

    // --- SweetAlert Editor Logic ---
    function openDokumenEditor(iddoc = null) {
        const idservis = $('#dropdownServis').val();
        if (iddoc) {
            $.get('/dokumen/edit/' + iddoc, function(res) {
                if (res.status) showSwalEditor(res.data, idservis);
            });
        } else {
            showSwalEditor(null, idservis);
        }
    }

    function showSwalEditor(data = null, idservis) {
        Swal.fire({
            title: data ? 'Kemaskini Dokumen' : 'Muat Naik Dokumen',
            showCloseButton: true,
            html: `
                <div class="text-left space-y-4 p-2 mt-4">
                    <div>
                        <label class="swal-label-custom">Nama Dokumen</label>
                        <input id="swal-nama" class="swal-input-custom" value="${data ? data.nama : ''}" placeholder="Contoh: Sijil Kelayakan">
                    </div>
                    <div>
                        <label class="swal-label-custom">Penerangan / Nota</label>
                        <textarea id="swal-descdoc" class="swal-input-custom" style="height: 100px; padding: 10px; resize: none;">${data ? (data.descdoc || '') : ''}</textarea>
                    </div>
                    <div>
                        <label class="swal-label-custom">Pilih Fail (PDF Sahaja)</label>
                        <input type="file" id="swal-file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-700" accept="application/pdf">
                        ${data ? '<p class="text-[11px] text-amber-600 mt-2 font-bold italic">* Biarkan kosong jika tidak mahu tukar fail</p>' : ''}
                    </div>
                </div>
            `,
            width: '550px',
            showConfirmButton: true,
            confirmButtonText: 'Hantar Dokumen',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn-swal-hantar',
                closeButton: 'swal2-close'
            },
            backdrop: `rgba(15, 23, 42, 0.5) blur(8px)`,
            preConfirm: () => {
                const nama = document.getElementById('swal-nama').value;
                const fileInput = document.getElementById('swal-file');
                
                if (!nama) { Swal.showValidationMessage('Nama dokumen wajib diisi!'); return false; }
                if (!data && fileInput.files.length === 0) { Swal.showValidationMessage('Sila pilih fail PDF!'); return false; }

                const fd = new FormData();
                fd.append('idservis', idservis);
                fd.append('nama', nama);
                fd.append('descdoc', document.getElementById('swal-descdoc').value);
                if (fileInput.files[0]) fd.append('file', fileInput.files[0]);
                
                return fd;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const url = data ? '/dokumen/kemaskini/' + data.iddoc : '/dokumen/tambah';
                saveDokumen(url, result.value);
            }
        });
    }

    async function saveDokumen(url, formData) {
        $.ajax({
            url: url, type: 'POST', data: formData, processData: false, contentType: false,
            success: function(res) {
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: 'Data telah disimpan!', timer: 1500, showConfirmButton: false });
                    refreshTable($('#dropdownServis').val());
                } else {
                    Swal.fire('Ralat', res.msg || 'Gagal menyimpan fail', 'error');
                }
            }
        });
    }

    window.hapusDokumen = function(id){
        Swal.fire({ 
            title: 'Padam Dokumen?', 
            text: "Fail ini akan dibuang secara kekal!", 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn-swal-padam',
                cancelButton: 'btn-swal-hantar'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/dokumen/hapus/' + id, function(){ 
                    Swal.fire('Dipadam!', 'Dokumen telah dibuang.', 'success');
                    refreshTable($('#dropdownServis').val()); 
                });
            }
        });
    }
</script>

<?= $this->endSection() ?>