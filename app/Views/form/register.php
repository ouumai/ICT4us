<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertai Kami - ICT4U</title>
    
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

        .image-section {
            flex: 1;
            background: url('<?= base_url('assets/image/airterjunUKM.jpg') ?>') center/cover no-repeat;
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

        .form-section {
            width: 550px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 100px 80px 60px 80px;
            background: #fff;
            overflow-y: auto;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--brand-color);
            margin-top: 20px;
            margin-bottom: 50px;
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

        .btn-register {
            background: var(--brand-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 10px;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .btn-register:hover {
            background: var(--brand-hover);
            color: white;
        }

        .requirement-text {
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .req-met { color: #10b981 !important; font-weight: 700; } /* Warna Hijau */
        
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
                <h2 class="fw-bold mb-3" style="font-size: 1.8rem;">Mulakan Langkah Anda</h2>
                <p class="mb-0 opacity-90 leading-relaxed font-medium">Daftar sekarang untuk akses penuh ke sistem pengurusan ICT4U. Pantas, selamat dan bersepadu.</p>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="brand-logo">
            <i class="bi bi-cpu-fill"></i> ICT4U
        </div>

        <div>
            <h1 class="section-title">Cipta Akaun</h1>
            <p class="text-subtitle">Sertai komuniti teknologi kami hari ini.</p>
        </div>

        <?php if (session()->getFlashdata('error_pw')): ?>
            <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm d-flex align-items-center" role="alert" style="background-color: #fef2f2; color: #991b1b;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div style="font-size: 0.85rem; font-weight: 600;">
                    <?= session()->getFlashdata('error_pw') ?>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/register') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Penuh</label>
                <input type="text" name="fullname" class="form-control" placeholder="Ahmad Zulkarnain" value="<?= old('fullname') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Emel Rasmi</label>
                <input type="email" name="email" class="form-control" placeholder="nama@syarikat.com" value="<?= old('email') ?>" required>
            </div>

            <div class="row g-3 mb-2">
                <div class="col-6">
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" minlength="8" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Sahkan</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" minlength="8" required>
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center gap-2 px-1">
                <i id="req-icon" class="bi bi-info-circle-fill text-primary" style="font-size: 0.85rem;"></i>
                <p id="password-hint" class="requirement-text mb-0 text-muted fw-medium">
                    Kata laluan mestilah sekurang-kurangnya <strong>8 aksara</strong>.
                </p>
            </div>

            <button type="submit" class="btn-register w-100 mb-4">Daftar Sekarang</button>

            <p class="text-center small text-secondary font-semibold" style="font-weight: 600;">
                Dah ada akaun? <a href="<?= base_url('/login') ?>" class="login-link text-decoration-none" style="color: var(--brand-color); font-weight: 700;">Log masuk</a>
            </p>
        </form>

        <div class="mt-auto pt-4 text-center">
            <p class="small text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">
                &copy; 2026 ICT4U Management System
            </p>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const hintText = document.getElementById('password-hint');
    const hintIcon = document.getElementById('req-icon');

    passwordInput.addEventListener('input', function() {
        if (this.value.length >= 8) {
            hintText.classList.add('req-met');
            hintText.innerHTML = 'Syarat kata laluan dipenuhi <i class="bi bi-check-lg"></i>';
            hintIcon.classList.replace('text-primary', 'text-success');
            hintIcon.classList.replace('bi-info-circle-fill', 'bi-check-circle-fill');
        } else {
            hintText.classList.remove('req-met');
            hintText.innerHTML = 'Kata laluan mestilah sekurang-kurangnya <strong>8 aksara</strong>.';
            hintIcon.classList.add('text-primary');
            hintIcon.classList.replace('text-success', 'text-primary');
            hintIcon.classList.replace('bi-check-circle-fill', 'bi-info-circle-fill');
        }
    });
</script>

</body>
</html>