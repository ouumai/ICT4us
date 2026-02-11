<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --soft-lilac: #f5f3ff;
        --deep-lilac: #8b5cf6;
        --soft-emerald: #ecfdf5;
        --emerald-green: #10b981;
    }

    /* 1. Global Setup */
    body, .content-wrapper, h1, h2, h3, h4, h5, h6, p, span, div, strong {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* Remove Default Headers */
    .content-header, .breadcrumb, .content-wrapper > section.content-header,
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

    .stat-card {
        border: none;
        border-radius: 28px;
        padding: 1.8rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card:hover {
        transform: translateY(-10px); /* Bergerak 10px ke atas */
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1) !important;
    }

    .icon-box {
        width: 55px; height: 55px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1.2rem;
        background: white;
    }

    .chart-card {
        border-radius: 30px;
        border: 1px solid #e2e8f0;
        background: white;
        padding: 2.5rem;
    }

    /* Date Stamp Style - Ikut style grey yang Mai nak */
    .date-stamp {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
        color: #64748b; /* Slate 500 */
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Custom Gray Text */
    .text-slate-500 { color: #64748b !important; }
    .fw-800 { font-weight: 800 !important; }
    .fw-700 { font-weight: 700 !important; }
</style>

<div class="container-fluid py-4">
    
    <div class="glass-card rounded-3xl p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-6">
             <div class="bg-indigo-100 p-3 rounded-2xl">
                <i class="bi bi-grid-fill text-3xl text-indigo-600"></i>
            </div>
            <div>
                <h1 class="text-3xl fw-800 text-slate-900 mb-1">Dashboard</h1>
                <p class="text-slate-500 font-medium mb-0">Ringkasan status permohonan dan statistik dokumen sistem.</p>
            </div>
        </div>
        
        <div class="mt-4 md:mt-0">
            <div class="date-stamp shadow-sm">
                <i class="bi bi-calendar-event text-slate-500"></i>
                <span id="currentDateTime" class="text-slate-500"><?= date('M d 2026, H:i:s') ?></span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-8">
        <div class="col-md-3">
            <div class="stat-card shadow-sm" style="background: var(--deep-lilac); color: white;">
                <div class="icon-box bg-white bg-opacity-20 text-white border-0">
                    <i class="bi bi-patch-check"></i>
                </div>
                <h6 class="fw-700 small text-uppercase opacity-75 mb-1">Servis Kelulusan</h6>
                <h2 class="fw-800 mb-0" style="font-size: 2.2rem;"><?= number_format($totalServisKelulusan) ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm" style="background: var(--emerald-green); color: white;">
                <div class="icon-box bg-white bg-opacity-20 text-white border-0">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <h6 class="fw-700 small text-uppercase opacity-75 mb-1 text-white">Dokumen Lulus</h6>
                <h2 class="fw-800 mb-0" style="font-size: 2.2rem;"><?= number_format($dokApproved) ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-emerald-premium shadow-sm border" style="background: var(--soft-emerald);">
                <div class="icon-box text-success shadow-sm">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h6 class="fw-700 small text-uppercase mb-1 opacity-75" style="color: var(--emerald-green);">Jumlah Dokumen</h6>
                <h2 class="fw-800 mb-0" style="font-size: 2.2rem; color: #10b981;"><?= number_format($totalDokumen) ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-lilac-premium shadow-sm border" style="background: var(--soft-lilac);">
                <div class="icon-box text-purple shadow-sm">
                    <i class="bi bi-ui-checks-grid"></i>
                </div>
                <h6 class="fw-700 small text-uppercase mb-1 opacity-75" style="color: var(--deep-lilac);">Perincian Modul</h6>
                <h2 class="fw-800 mb-0" style="font-size: 2.2rem; color: var(--deep-lilac);"><?= number_format($totalPerincianModul) ?></h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="chart-card shadow-sm">
                <h5 class="fw-800 mb-6 text-dark">Analisis Status Keseluruhan</h5>
                <div style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
                
                <div class="mt-8 space-y-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm fw-700 text-amber-600">Pending</span>
                            <span class="text-sm fw-800 text-slate-700"><?= $dokPending ?></span>
                        </div>
                        <div class="progress rounded-pill shadow-sm" style="height: 10px; background: #f1f5f9;">
                            <div class="progress-bar bg-warning" style="width: <?= ($totalDokumen > 0) ? ($dokPending/$totalDokumen)*100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm fw-700 text-success">Approved</span>
                            <span class="text-sm fw-800 text-slate-700"><?= $dokApproved ?></span>
                        </div>
                        <div class="progress rounded-pill shadow-sm" style="height: 10px; background: #f1f5f9;">
                            <div class="progress-bar bg-success" style="width: <?= ($totalDokumen > 0) ? ($dokApproved/$totalDokumen)*100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm fw-700 text-danger">Rejected</span>
                            <span class="text-sm fw-800 text-slate-700"><?= $dokRejected ?></span>
                        </div>
                        <div class="progress rounded-pill shadow-sm" style="height: 10px; background: #f1f5f9;">
                            <div class="progress-bar bg-danger" style="width: <?= ($totalDokumen > 0) ? ($dokRejected/$totalDokumen)*100 : 0 ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7"></div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Update Time
        setInterval(() => {
            const now = new Date();
            const options = { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            document.getElementById('currentDateTime').innerText = now.toLocaleString('en-US', options).replace(',', '');
        }, 1000);

        // Chart
        const ctx = document.getElementById('statusChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [<?= $dokPending ?>, <?= $dokApproved ?>, <?= $dokRejected ?>],
                    backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 12
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 25,
                            font: { size: 13, weight: '700', family: "'Plus Jakarta Sans', sans-serif" }
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });
    });
</script>
<?= $this->endSection() ?>