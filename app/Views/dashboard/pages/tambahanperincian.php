<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>document.title = "Tambahan Perincian Modul";</script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="<?= base_url('ckeditor5-build-classic/build/ckeditor.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. Global Setup */
    body, .content-wrapper, .main-sidebar, h1, h2, h3, h4, h5, h6, p, span, div, table, input, textarea, button {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    .content-wrapper > .container-fluid > .d-md-flex.align-items-center.justify-content-between.mb-5 {
        display: none !important;
    }

    /* 2. Card Styling */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        border-radius: 1.5rem;
    }

    /* 3. Modern Input & Placeholder (Match Rujukan) */
    .modern-input-size {
        height: 56px !important;
        border-radius: 14px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
    }

    .input-with-icon { padding-left: 3.5rem !important; }

    #searchInput::placeholder {
        color: #94a3b8 !important;
        font-weight: 600;
        opacity: 1;
    }

    .icon-search-fix {
        position: absolute; left: 1.3rem; top: 50%; transform: translateY(-50%);
        color: #94a3b8 !important; font-size: 1.2rem; z-index: 10;
    }

    /* 4. Table Header Styling (Match Pengesahan Dokumen) */
    .compact-th {
        padding-top: 25px !important;
        padding-bottom: 25px !important;
        background-color: #f8fafc !important; /* bg-slate-50 */
        border-bottom: 1px solid #e2e8f0;
        
        /* Gaya Font Header Rujukan */
        font-size: 0.75rem !important;      /* text-xs */
        font-weight: 700 !important;        /* font-bold */
        text-transform: uppercase !important; /* uppercase */
        letter-spacing: 0.05em !important;   /* tracking-wider */
        color: #64748b !important;           /* text-slate-500 */
        white-space: nowrap;
    }

    /* 5. Table Action Buttons */
    .btn-action-table-large {
        width: 160px; height: 44px; display: inline-flex; align-items: center; justify-content: center;
        gap: 10px; font-size: 11px !important; font-weight: 800 !important; border-radius: 12px;
        text-transform: uppercase; transition: all 0.2s; border: 1px solid transparent;
    }
    .btn-view { background: #F1F5F9; color: #64748B; border-color: #E2E8F0; }
    .btn-view:not(:disabled):hover { background: #E2E8F0; color: #1E293B; }
    .btn-edit { background: #EEF2FF; color: #4F46E5; border-color: #E0E7FF; }
    .btn-edit:hover { background: #4F46E5; color: white; }

    /* SweetAlert Custom UI */
    .swal2-actions { width: 100% !important; display: flex !important; flex-direction: row !important; gap: 12px !important; margin-top: 1.5rem !important; padding: 0 1rem !important; }
    .btn-swal-hantar { flex: 1 !important; background: #3b82f6 !important; color: white !important; font-weight: 700 !important; padding: 14px !important; border-radius: 16px !important; border: none !important; font-size: 0.95rem !important; order: 2; }
    .btn-swal-padam { flex: 1 !important; background: #fee2e2 !important; color: #ef4444 !important; font-weight: 700 !important; padding: 14px !important; border-radius: 16px !important; border: none !important; font-size: 0.95rem !important; order: 1; }
    .swal2-popup { border-radius: 28px !important; padding: 2rem !important; }
    .swal-label-custom { display: block; font-size: 0.8rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
    .swal-input-custom { height: 52px; border-radius: 12px; border: 1px solid #e2e8f0; padding: 0 15px; width: 100%; background-color: #ffffff; font-weight: 500; font-size: 0.95rem; }
</style>

<div class="container-fluid py-4">
    <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-50 p-3 rounded-2xl"><i class="bi bi-folder-plus text-3xl text-indigo-600"></i></div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Tambahan Perincian Modul</h1>
                <p class="text-gray-500 font-medium mb-0">Urus pautan maklumat dan perincian servis tambahan</p>
            </div>
        </div>
        <button onclick="openEditor()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all">
            <i class="bi bi-plus-lg"></i> Tambah Perincian
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
        <div class="md:col-span-3 relative">
            <select id="sortOrder" onchange="sortData()" class="modern-input-size w-full appearance-none px-4 focus:outline-none transition text-slate-700 cursor-pointer">
                <option value="asc">Terdahulu (ID)</option>
                <option value="desc">Terkini (ID)</option>
            </select>
            <i class="bi bi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none"></i>
        </div>
        <div class="md:col-span-9 relative">
            <i class="bi bi-search icon-search-fix"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama servis..." class="modern-input-size input-with-icon w-full focus:outline-none transition focus:ring-4 focus:ring-indigo-50">
        </div>
    </div>

    <div class="glass-card overflow-hidden bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="dokumenTable">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-8 compact-th">Maklumat Servis</th>
                        <th class="px-8 compact-th text-center">Pautan Luar</th>
                        <th class="px-8 compact-th text-center">Tindakan</th> </tr>
                </thead>
                <tbody id="serviceTableBody" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
let allServis = [];
let editor;

async function fetchServis(){
    try {
        const res = await fetch('<?= base_url("dashboard/TambahanPerincian/getAll") ?>');
        const json = await res.json();
        if(json.status) { allServis = json.data; sortData(); }
    } catch (e) { console.error("Error:", e); }
}

function renderTable(){
    const body = document.getElementById('serviceTableBody');
    body.innerHTML = '';
    allServis.forEach(s => {
        const hasLinks = (s.infourl && s.infourl.trim() !== "") || (s.mohonurl && s.mohonurl.trim() !== "");
        const tr = document.createElement('tr');
        tr.className = "hover:bg-slate-50/50 transition-colors";
        tr.innerHTML = `
            <td class="px-8 py-6">
                <div class="text-[14px] font-bold text-slate-800 leading-tight">${s.namaservis}</div>
                <div class="text-[10px] text-slate-400 mt-1">ID: #${s.idservis}</div>
            </td>
            <td class="px-8 py-6 text-center">
                <button onclick="showLinks('${s.idservis}')" class="btn-action-table-large btn-view ${!hasLinks ? 'opacity-50' : ''}" ${!hasLinks ? 'disabled' : ''}>
                    <i class="bi bi-link-45deg text-lg"></i> ${hasLinks ? 'Lihat Pautan' : 'Tiada Pautan'}
                </button>
            </td>
            <td class="px-8 py-6 text-center"> <button onclick="openEditor('${s.idservis}')" class="btn-action-table-large btn-edit">
                    <i class="bi bi-pencil-square text-lg"></i> KEMASKINI
                </button>
            </td>
        `;
        body.appendChild(tr);
    });
}

function openEditor(id = null) {
    let s = id ? allServis.find(item => String(item.idservis) === String(id)) : null;
    Swal.fire({
        title: id ? 'Kemaskini Perincian' : 'Tambah Perincian',
        showCloseButton: true,
        html: `
            <div class="text-left space-y-4 p-2 mt-4">
                <div>
                    <label class="swal-label-custom">Nama Servis</label>
                    <input id="swal-namaservis" class="swal-input-custom" value="${s ? s.namaservis : ''}" placeholder="Contoh: Permohonan IP">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="swal-label-custom">URL Informasi</label><input id="swal-infourl" class="swal-input-custom" value="${s ? (s.infourl || '') : ''}" placeholder="https://..."></div>
                    <div><label class="swal-label-custom">URL Permohonan</label><input id="swal-mohonurl" class="swal-input-custom" value="${s ? (s.mohonurl || '') : ''}" placeholder="https://..."></div>
                </div>
                <div><label class="swal-label-custom">Penerangan / Nota</label><textarea id="swal-description"></textarea></div>
            </div>
        `,
        width: '640px',
        showConfirmButton: true,
        confirmButtonText: 'Hantar',
        showDenyButton: id ? true : false,
        denyButtonText: 'Padam Servis',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn-swal-hantar', denyButton: 'btn-swal-padam', closeButton: 'swal2-close' },
        backdrop: `rgba(15, 23, 42, 0.5) blur(8px)`,
        didOpen: () => {
            ClassicEditor.create(document.querySelector('#swal-description'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList']
            }).then(newEditor => { editor = newEditor; if(s) editor.setData(s.perincian?.description || ''); });
        },
        preConfirm: () => {
            const name = document.getElementById('swal-namaservis').value;
            if (!name) { Swal.showValidationMessage('Nama Servis wajib diisi!'); return false; }
            return { idservis: id, namaservis: name, infourl: document.getElementById('swal-infourl').value, mohonurl: document.getElementById('swal-mohonurl').value, description: editor.getData() }
        }
    }).then((result) => {
        if (result.isConfirmed) { saveServis(result.value); } 
        else if (result.isDenied) { deleteServis(id); }
    });
}

async function saveServis(data){
    const fd = new FormData();
    Object.keys(data).forEach(key => fd.append(key, data[key] || ''));
    try {
        const res = await fetch('<?= base_url("dashboard/TambahanPerincian/saveServis") ?>', { method:'POST', body:fd });
        const json = await res.json();
        if(json.status){ fetchServis(); Swal.fire({ icon: 'success', title: 'Berjaya', text: 'Data disimpan!', timer: 1500, showConfirmButton: false }); }
    } catch (e) { console.error(e); }
}

async function deleteServis(id){
    Swal.fire({ title: 'Padam rekod?', text: "Tindakan ini kekal!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Padam', cancelButtonText: 'Batal' }).then(async (result) => {
        if (result.isConfirmed) {
            const fd = new FormData(); fd.append('idservis', id);
            await fetch('<?= base_url("dashboard/TambahanPerincian/deleteServis") ?>', { method:'POST', body:fd });
            fetchServis(); Swal.fire('Dipadam!', 'Rekod dibuang.', 'success');
        }
    });
}

function showLinks(id) {
    const s = allServis.find(item => String(item.idservis) === String(id));
    Swal.fire({
        title: '<span class="text-xl font-bold">Pautan Luar</span>',
        showCloseButton: true,
        showConfirmButton: false,
        html: `<div class="text-left space-y-6 p-4 mt-2">
                <div><p class="swal-label-custom" style="font-size: 0.9rem;">URL Informasi</p>${s.infourl ? `<a href="${s.infourl}" target="_blank" class="text-blue-600 break-all text-lg underline font-semibold">${s.infourl}</a>` : '<span class="text-slate-400 text-base italic">Tiada pautan disediakan</span>'}</div>
                <div><p class="swal-label-custom" style="font-size: 0.9rem;">URL Permohonan</p>${s.mohonurl ? `<a href="${s.mohonurl}" target="_blank" class="text-blue-600 break-all text-lg underline font-semibold">${s.mohonurl}</a>` : '<span class="text-slate-400 text-base italic">Tiada pautan disediakan</span>'}</div>
            </div>`,
        backdrop: `rgba(15, 23, 42, 0.5) blur(8px)`
    });
}

function sortData() {
    const order = document.getElementById('sortOrder').value;
    allServis.sort((a, b) => order === 'asc' ? parseInt(a.idservis) - parseInt(b.idservis) : parseInt(b.idservis) - parseInt(a.idservis));
    renderTable();
}

function filterTable() {
    const q = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#serviceTableBody tr");
    rows.forEach(row => { row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none"; });
}

fetchServis();
</script>

<?= $this->endSection() ?>