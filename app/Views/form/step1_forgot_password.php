<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Semula Kata Laluan | ICT4U</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-color: #4f46e5;
            --brand-hover: #3730a3;
            --slate-500: #64748b;
            --slate-700: #334155;
            --slate-dark: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .main-container {
            display: flex;
            height: 100vh;
        }

        /* --- Left Side: Visuals --- */
        .image-section {
            flex: 1;
            background: url('<?= base_url('assets/image/roundaboutUKM.jpg') ?>') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .image-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.85), rgba(124, 58, 237, 0.5));
        }

        .overlay-content {
            position: relative;
            z-index: 2;
            color: white;
            max-width: 500px;
            animation: fadeIn 0.8s ease-out;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 45px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        }

        /* --- Right Side: Form --- */
        .form-section {
            width: 550px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 80px;
            background: #fff;
            overflow-y: auto;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--brand-color);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--slate-dark);
            margin-bottom: 8px;
        }

        .text-subtitle {
            color: var(--slate-500);
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--slate-700);
            margin-left: 4px;
        }

        .form-control {
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            padding: 12px 18px;
            border-radius: 16px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--slate-700);
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--brand-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .btn-action {
            background: var(--brand-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 10px;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
            transition: all 0.3s;
        }

        .btn-action:hover {
            background: var(--brand-hover);
            color: white;
        }

        .back-link 
        {
            color: var(--brand-color);
            font-weight: 700;
            text-decoration: none; 
            transition: all 0.3s;
        }

        .back-link:hover 
        {
            text-decoration: none; 
        }

        /* Progress Bar Container */
        .progress-stepper {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 35px;
        }

        /* Base Style for each step */
        .step-bar {
            flex: 1;
            height: 6px;
            border-radius: 20px;
            background: #f1f5f9; /* Warna kelabu (Inactive) */
            transition: all 0.5s ease;
            position: relative;
        }

        /* Active Style*/
        .step-bar.active {
            background: var(--brand-color);
            box-shadow: 0 0 12px rgba(79, 70, 229, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 992px) {
            .image-section { display: none; }
            .form-section { width: 100%; padding: 40px; }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="image-section">
        <div class="overlay-content">
            <div class="glass-card">
                <h2 class="fw-bold mb-3" style="font-size: 1.8rem;">Kemaskini Segera</h2>
                <p class="mb-0 opacity-90 leading-relaxed font-medium">
                    Jangan risau, ia berlaku kepada sesiapa sahaja. Masukkan butiran anda untuk mendapatkan semula akses ke sistem ICT4U.
                </p>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="brand-logo">
            <i class="bi bi-cpu-fill"></i> ICT4U
        </div>

        <div class="progress-stepper">
            <div class="step-bar active"></div> <div class="step-bar"></div>
            <div class="step-bar"></div>
        </div>

        <div>
            <h1 class="section-title">Tukar Kata Laluan</h1>
            <p class="text-subtitle">Sila isi maklumat di bawah untuk set semula.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 small p-3 mb-4">
                <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 rounded-4 small p-3 mb-4">
                <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('forgot/step1') ?>" method="post">
             <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Alamat Emel</label>
                <input type="email" name="email" class="form-control" placeholder="nama@syarikat.com" required>
            </div>

            <button type="submit" class="btn-action w-100 mb-4">Seterusnya</button>

            <p class="text-center small text-secondary font-semibold">
                <a href="<?= base_url('/login') ?>" class="back-link">← Kembali semula ke Log Masuk</a>
            </p>
        </form>

        <div class="mt-auto pt-4 text-center">
            <p class="small text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">
                &copy; 2026 ICT4U Management System
            </p>
        </div>
    </div>
</div>

</body>
</html>