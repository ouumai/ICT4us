<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>document.title = "Sistem Approval Dokumen";</script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<style>
    /* 1. Global Setup */
    body, .content-wrapper, .main-sidebar, h1, h2, h3, h4, h5, h6, p, span, div, table {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .content-wrapper > .container-fluid > .d-md-flex.align-items-center.justify-content-between.mb-5 {
        display: none !important;
    }

    /* 2. UI Styles */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .input-with-icon { padding-left: 3rem !important; }
    #searchDokumen::placeholder { color: #94a3b8 !important; font-weight: 600; opacity: 1; }
    #searchDokumen { color: #475569; font-weight: 600; }

    /* 3. Table Header Style */
    #dokumenTable thead th {
        padding-top: 25px !important;
        padding-bottom: 25px !important;
        background-color: #f8fafc !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #64748b !important;
        white-space: nowrap;
    }

    /* Status Pills */
    .status-pill { padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .status-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .status-approved { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-rejected { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* 4. MODAL X BUTTON (THE FIX) */
    #closeViewModal {
        color: #94a3b8;
        transition: all 0.2s ease;
        background: transparent;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Tambah stroke untuk nampak BOLD */
        -webkit-text-stroke: 1.2px #94a3b8;
    }
    #closeViewModal:hover, #closeViewModal:active {
        color: #ef4444 !important; 
        -webkit-text-stroke: 1.2px #ef4444;
    }

    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container-fluid py-1">
    <div class="glass-card rounded-3xl p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-6">
             <div class="bg-indigo-100 p-3 rounded-2xl">
                <i class="bi bi-check-circle text-3xl text-indigo-600"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1 text-dark">Pengesahan Dokumen</h1>
                <p class="text-gray-500 font-medium mb-0">Jalan Kerja Dokumen Servis</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
        <div class="md:col-span-4 relative">
            <select id="filterStatus" class="w-full appearance-none bg-white border border-slate-200 p-3 rounded-xl focus:outline-none transition font-semibold text-slate-600 cursor-pointer h-[56px]">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu (Pending)</option>
                <option value="approved">Diterima (Approved)</option>
                <option value="rejected">Ditolak (Rejected)</option>
            </select>
            <i class="bi bi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none"></i>
        </div>
        <div class="md:col-span-8 relative">
            <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 z-10"></i>
            <input type="text" id="searchDokumen" placeholder="Cari tajuk dokumen..." 
                   class="input-with-icon w-full bg-white border border-slate-200 p-3 rounded-xl focus:outline-none h-[56px]">
        </div>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto" id="dokumenTable">
                <thead>
                    <tr class="bg-slate-50 border-b">
                        <th class="p-4 text-center w-20">No</th>
                        <th class="p-4 text-left">Maklumat Dokumen</th>
                        <th class="p-4 text-left">Format</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Tarikh Hantar</th>
                        <th class="p-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white"></tbody>
            </table>
        </div>
        <div class="p-6 border-t bg-slate-50/50 flex justify-between items-center">
            <p id="totalInfo" class="text-sm text-slate-500 font-medium mb-0"></p>
            <div class="flex space-x-2 pagination"></div>
        </div>
    </div>
</div>

<div id="viewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4" style="z-index: 9999;">
    <div class="modal-container bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl animate-[slideUp_0.3s_ease-out]">
        <div class="bg-slate-50 p-5 flex justify-between items-center border-b">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2 m-0">Perincian Dokumen</h2>
            <button id="closeViewModal" title="Tutup"><i class="bi bi-x-lg" style="font-size: 1.3rem;"></i></button>
        </div>
        <div id="dokumenDetails" class="p-8 max-h-96 overflow-y-auto"></div>
    </div>
</div>

<div id="lottieContainer" style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000; display:none;">
    <lottie-player id="successAnimation" src="https://assets10.lottiefiles.com/packages/lf20_jbrw3hcz.json" background="transparent" speed="1" style="width:250px;height:250px;" autoplay></lottie-player>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('#dokumenTable tbody');
    const searchInput = document.getElementById('searchDokumen');
    const filterStatus = document.getElementById('filterStatus');
    const viewModal = document.getElementById('viewModal');
    const dokumenDetails = document.getElementById('dokumenDetails');
    const paginationContainer = document.querySelector('.pagination');
    const lottieContainer = document.getElementById('lottieContainer');
    const successAnimation = document.getElementById('successAnimation');

    let currentPage = 1, limit = 10;

    async function loadData(page = 1) {
        const status = filterStatus.value;
        tbody.innerHTML = '<tr><td colspan="6" class="p-10 text-center text-slate-400">Memuatkan data...</td></tr>';
        try {
            const res = await fetch(`<?= base_url('approvaldokumen/getAll') ?>?status=${status}&page=${page}`);
            const result = await res.json();
            if (result.data && result.data.length > 0) {
                populateTable(result.data, result.pagination);
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="p-12 text-center text-slate-400 italic">Tiada rekod dijumpai.</td></tr>';
                document.getElementById('totalInfo').innerText = 'Menunjukkan 0 rekod';
                paginationContainer.innerHTML = '';
            }
        } catch (err) { console.error(err); }
    }

    function populateTable(data, pagination) {
        tbody.innerHTML = '';
        currentPage = pagination.page;
        const totalPages = Math.ceil(pagination.total / pagination.limit);
        const start = (currentPage - 1) * pagination.limit + 1;
        const end = start + data.length - 1;
        document.getElementById('totalInfo').innerText = `Menunjukkan ${start}-${end} daripada ${pagination.total} rekod`;

        data.forEach((d, index) => {
            const statusLabel = d.status ?? 'pending';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="p-4 text-center text-slate-400 font-semibold">${index + 1 + (currentPage - 1) * limit}</td>
                <td class="p-4">
                    <div class="flex items-center justify-between cursor-pointer group" onclick="showDokumenModal('${d.iddoc}')">
                        <div>
                            <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">${d.nama}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">ID: #${d.iddoc}</div>
                        </div>
                        <i class="bi bi-chevron-down text-slate-300 group-hover:text-indigo-600 transition-colors"></i>
                    </div>
                </td>
                <td class="p-4"><span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded font-bold uppercase tracking-wide">${d.mime ? d.mime.split('/')[1] : 'FILE'}</span></td>
                <td class="p-4 text-center"><span class="status-pill status-${statusLabel}">${statusLabel}</span></td>
                <td class="p-4 text-slate-500"><div class="flex items-center gap-2 text-sm"><i class="bi bi-clock-history"></i> ${formatDate(d.created_at)}</div></td>
                <td class="p-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button class="viewBtn btn-action bg-indigo-50 text-indigo-600 p-2 rounded-xl hover:bg-indigo-600 hover:text-white transition" data-id="${d.iddoc}"><i class="bi bi-eye-fill pointer-events-none"></i></button>
                        <button class="approveBtn btn-action bg-green-50 text-green-600 p-2 rounded-xl hover:bg-green-600 hover:text-white transition" data-id="${d.iddoc}"><i class="bi bi-check-lg pointer-events-none"></i></button>
                        <button class="rejectBtn btn-action bg-red-50 text-red-600 p-2 rounded-xl hover:bg-red-600 hover:text-white transition" data-id="${d.iddoc}"><i class="bi bi-x-lg pointer-events-none"></i></button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `w-10 h-10 rounded-xl font-bold transition ${i === currentPage ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100'}`;
            btn.addEventListener('click', () => { loadData(i); });
            paginationContainer.appendChild(btn);
        }
    }

    function formatDate(str) { if (!str) return '-'; const d = new Date(str); return d.toLocaleDateString('ms-MY', { day: '2-digit', month: 'short', year: 'numeric' }); }

    window.showDokumenModal = async function(id) {
        viewModal.classList.remove('hidden');
        dokumenDetails.innerHTML = '<div class="text-center p-10">Memuatkan...</div>';
        try {
            const res = await fetch(`<?= base_url('approvaldokumen/getDokumen') ?>/${id}`);
            const json = await res.json();
            if (json.status) {
                const d = json.data;
                const fileUrl = `<?= base_url('dokumen/viewFile') ?>/${d.idservis}/${d.namafail}`;
                let fileHTML = d.mime.includes('image') ? `<img src="${fileUrl}" class="w-full rounded-2xl shadow-lg border" />` : (d.mime === 'application/pdf' ? `<iframe src="${fileUrl}" width="100%" height="450px" class="rounded-2xl border"></iframe>` : `<div class="p-8 border-2 border-dashed rounded-2xl text-center"><a href="${fileUrl}" target="_blank" class="text-indigo-600 font-bold underline">Muat Turun Fail</a></div>`);
                dokumenDetails.innerHTML = `<div class="grid grid-cols-2 gap-4 mb-6"><div class="bg-slate-50 p-4 rounded-2xl"><span class="text-xs text-slate-400 font-bold uppercase">Nama Dokumen</span><p class="font-bold text-slate-700 mt-1">${d.nama}</p></div><div class="bg-slate-50 p-4 rounded-2xl"><span class="text-xs text-slate-400 font-bold uppercase">Status Semasa</span><div class="mt-2"><span class="status-pill status-${d.status}">${d.status}</span></div></div></div><div class="mb-6"><span class="text-xs text-slate-400 font-bold uppercase">Catatan</span><p class="text-slate-600 mt-1">${d.descdoc || 'Tiada catatan.'}</p></div>${fileHTML}`;
            }
        } catch (err) { console.error(err); }
    }

    window.changeStatus = async function(id, status) {
        const confirmText = status.charAt(0).toUpperCase() + status.slice(1);
        const result = await Swal.fire({ title: `Pengesahan ${confirmText}`, text: `Pasti mahu tukar status kepada ${status}?`, icon: status === 'approved' ? 'question' : 'warning', showCancelButton: true, confirmButtonColor: status === 'approved' ? '#10b981' : '#ef4444', confirmButtonText: `Ya, ${confirmText}!` });
        if (!result.isConfirmed) return;
        try {
            const res = await fetch(`<?= base_url('approvaldokumen/changeStatus') ?>/${id}/${status}`, { method: 'POST' });
            const data = await res.json();
            if (data.status) {
                if (status === 'approved') { lottieContainer.style.display = 'block'; successAnimation.play(); setTimeout(() => { lottieContainer.style.display = 'none'; }, 1500); }
                Swal.fire('Berjaya!', data.message, 'success');
                loadData(currentPage);
            }
        } catch (err) { console.error(err); }
    }

    tbody.addEventListener('click', e => {
        const btn = e.target.closest('.btn-action');
        if (!btn) return;
        const id = btn.getAttribute('data-id');
        if (btn.classList.contains('viewBtn')) showDokumenModal(id);
        else if (btn.classList.contains('approveBtn')) changeStatus(id, 'approved');
        else if (btn.classList.contains('rejectBtn')) changeStatus(id, 'rejected');
    });

    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase();
        Array.from(tbody.rows).forEach(row => { row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none'; });
    });

    filterStatus.addEventListener('change', () => loadData(1));
    document.getElementById('closeViewModal').onclick = () => viewModal.classList.add('hidden');

    loadData(1);
});
</script>

<?= $this->endSection() ?>