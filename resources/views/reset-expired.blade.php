<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Kadaluarsa - Telava</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffff;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .expired-page {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: none;
            transition: all 0.3s ease;
        }

        .card-header {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            color: white;
            padding: 2.5rem 2rem;
            border-radius: 20px 20px 0 0 !important;
            text-align: center;
            border: none;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-top: 0.5rem;
            line-height: 1.6;
        }

        .card-content {
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .message-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: left;
        }

        .message-box i {
            color: #ffc107;
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .message-box p {
            margin: 0;
            color: #856404;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .btn-primary {
            background: linear-gradient(135deg, #b6bbc9, #667eea);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
            background: linear-gradient(135deg, #a0a5b9, #5568d3);
            color: white;
        }

        .back-to-login {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-to-login a:hover {
            text-decoration: underline;
            color: #5568d3;
        }

        /* Floating shapes animation */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            animation: float 8s ease-in-out infinite;
        }

        .shape-1 {
            width: 60px;
            height: 60px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 40px;
            height: 40px;
            top: 70%;
            right: 10%;
            animation-delay: 3s;
        }

        .shape-3 {
            width: 80px;
            height: 80px;
            bottom: 15%;
            left: 15%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .expired-page {
                margin: 10px;
            }

            .card-header {
                padding: 2rem 1.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .card-content {
                padding: 2rem 1.5rem;
            }

            .icon-wrapper {
                width: 80px;
                height: 80px;
            }
        }

        /* Steps info */
        .steps-info {
            text-align: left;
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .steps-info h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .steps-info ol {
            margin: 0;
            padding-left: 1.2rem;
        }

        .steps-info li {
            margin-bottom: 0.5rem;
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <!-- Floating background shapes -->
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>

    <div class="wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="expired-page">
                        <div class="card">
                            <div class="card-header">
                                <div class="icon-wrapper">
                                    <i class="fas fa-clock fa-3x"></i>
                                </div>
                                <h4 class="card-title">
                                    Link Kadaluarsa
                                </h4>
                                <p class="card-subtitle">
                                    Link reset password sudah tidak valid atau telah kadaluarsa
                                </p>
                            </div>

                            <div class="card-content">
                                <div class="message-box">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>
                                        <strong>Maaf,</strong> link reset password yang Anda gunakan sudah tidak berlaku. 
                                        Link reset password hanya valid selama 60 menit sejak permintaan dibuat.
                                    </p>
                                </div>

                                <a href="/forgot-password" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Kirim Ulang Link Reset
                                </a>

                                <div class="steps-info">
                                    <h6>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Langkah selanjutnya:
                                    </h6>
                                    <ol>
                                        <li>Klik tombol "Kirim Ulang Link Reset" di atas</li>
                                        <li>Masukkan alamat email Anda</li>
                                        <li>Periksa inbox email Anda</li>
                                        <li>Klik link baru yang dikirimkan dalam 60 menit</li>
                                    </ol>
                                </div>

                                <div class="back-to-login">
                                    <a href="/login">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Kembali ke Halaman Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Add entrance animation
        window.addEventListener('load', function() {
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>

</html>