<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-color: #4f46e5;
            --brand-hover: #3730a3;
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
            background: url('<?= base_url('assets/image/dectarUKM.jpg') ?>') center/cover no-repeat;
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

        /* --- Bahagian Kanan (Form) --- */
        .form-section {
            width: 550px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px;
            background: #fff;
            overflow-y: auto;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--brand-color);
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        .form-control {
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            padding: 14px 18px;
            border-radius: 16px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--brand-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-login {
            background: var(--brand-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 15px;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .btn-login:hover {
            background: var(--brand-hover);
            color: white;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
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
                <h2 class="fw-bolder mb-3 text-3xl" style="font-weight: 800;">Revolusi Digital ICT4U</h2>
                <p class="mb-0 opacity-90 leading-relaxed font-medium">Satu platform untuk semua keperluan pengurusan sistem anda. Pantas, selamat dan efisien untuk masa hadapan digital.</p>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="brand-logo">
            <i class="bi bi-cpu-fill"></i> ICT4U
        </div>

        <div class="mb-5">
            <h1 class="section-title">Selamat Kembali</h1>
            <p class="text-secondary fw-semibold">Masukkan emel dan kata laluan anda untuk akses.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 small p-3 mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-2"></i> 
                <strong><?= session()->getFlashdata('error') ?></strong>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-dark ml-1">Alamat Emel</label>
                <input type="email" name="email" class="form-control" placeholder="nama@syarikat.com" required>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-bold text-dark m-0">Kata Laluan</label>
                    <a href="<?= base_url('/forgot-password') ?>" class="small text-decoration-none fw-bold" style="color: var(--brand-color);">Lupa Kata Laluan?</a>
                </div>
                <input type="password" name="password" class="form-control" placeholder="minimum 8 aksara" required>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-4">Log Masuk Sekarang</button>

            <p class="text-center small text-secondary fw-semibold">
                Belum mempunyai akaun? <a href="<?= base_url('/register') ?>" class="fw-bold text-decoration-none hover:underline" style="color: var(--brand-color);">Daftar Sekarang</a>
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