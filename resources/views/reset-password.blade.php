<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Telava</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffff;
            min-height: 100vh;
            position: relative;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .reset-page {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: linear-gradient(135deg, #b6bbc9, #667eea) !important;
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0 !important;
            text-align: center;
            border: none;
        }

        .card-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        .card-content {
            padding: 2rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group-text {
            background: linear-gradient(135deg, #b6bbc9, #667eea);
            color: white;
            border: none;
            border-radius: 10px 0 0 10px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-left: none;
            border-right: none;
            border-radius: 0;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }

        .input-group:focus-within .form-control {
            border-color: #667eea;
        }

        .input-group:focus-within .toggle-password {
            border-color: #667eea;
        }

        .toggle-password {
            background: white;
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 10px 10px 0;
            width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .toggle-password:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .toggle-password i {
            font-size: 1rem;
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
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
            background: linear-gradient(135deg, #b6bbc9, #667eea);
        }

        .back-to-login {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .back-to-login a:hover {
            text-decoration: underline;
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

            0%,
            100% {
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
            .reset-page {
                margin: 10px;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak {
            background: #dc3545;
            width: 33%;
        }

        .strength-medium {
            background: #ffc107;
            width: 66%;
        }

        .strength-strong {
            background: #28a745;
            width: 100%;
        }

        .password-hint {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .password-checklist {
            list-style: none;
            padding-left: 0;
            font-size: 0.85rem;
        }

        .password-checklist li {
            margin-bottom: 4px;
            color: #dc3545;
            transition: all 0.3s ease;
        }

        .password-checklist li.valid {
            color: #28a745;
        }

        .password-checklist i {
            margin-right: 6px;
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
                <div class="col-md-6 col-lg-5">
                    <div class="reset-page">
                        <div class="card">
                            <div class="card-header">
                                <div class="mb-3">
                                    <i class="fas fa-key fa-3x"></i>
                                </div>
                                <h4 class="card-title">
                                    Reset Password
                                </h4>
                                <p class="card-subtitle">
                                    Masukkan password baru untuk akun Anda
                                </p>
                            </div>

                            <div class="card-content">
                                <form method="POST" action="/reset-password">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control"
                                            placeholder="Password Baru"
                                            required
                                            minlength="8">
                                        <span class="toggle-password" onclick="togglePassword('password', this)">
                                            <i class="far fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="password-strength">
                                        <div class="password-strength-bar" id="strengthBar"></div>
                                    </div>
                                    <!-- <p class="password-hint">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol
                                    </p> -->
                                    <ul class="password-checklist mt-2" id="passwordChecklist">
                                        <li id="chk-length"><i class="fa-solid fa-circle-xmark"></i> Minimal 8 karakter</li>
                                        <li id="chk-lower"><i class="fa-solid fa-circle-xmark"></i> Huruf kecil (a-z)</li>
                                        <li id="chk-upper"><i class="fa-solid fa-circle-xmark"></i> Huruf besar (A-Z)</li>
                                        <li id="chk-number"><i class="fa-solid fa-circle-xmark"></i> Angka (0-9)</li>
                                        <li id="chk-symbol"><i class="fa-solid fa-circle-xmark"></i> Simbol (@$!%*#?&)</li>
                                    </ul>



                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            class="form-control"
                                            placeholder="Ulangi Password"
                                            required
                                            minlength="8">
                                        <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                                            <i class="far fa-eye"></i>
                                        </span>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Reset Password
                                    </button>

                                    <div class="back-to-login">
                                        <a href="/login">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Kembali ke Login
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, toggleBtn) {
            const input = document.getElementById(inputId);
            const icon = toggleBtn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');

        // Checklist elements
        const chkLength = document.getElementById('chk-length');
        const chkLower = document.getElementById('chk-lower');
        const chkUpper = document.getElementById('chk-upper');
        const chkNumber = document.getElementById('chk-number');
        const chkSymbol = document.getElementById('chk-symbol');

        passwordInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;

            toggleCheck(chkLength, val.length >= 8);
            toggleCheck(chkLower, /[a-z]/.test(val));
            toggleCheck(chkUpper, /[A-Z]/.test(val));
            toggleCheck(chkNumber, /[0-9]/.test(val));
            toggleCheck(chkSymbol, /[@$!%*#?&]/.test(val));

            if (val.length >= 8) strength++;
            if (/[a-z]/.test(val)) strength++;
            if (/[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[@$!%*#?&]/.test(val)) strength++;

            strengthBar.className = 'password-strength-bar';

            if (strength <= 2) strengthBar.classList.add('strength-weak');
            else if (strength <= 4) strengthBar.classList.add('strength-medium');
            else strengthBar.classList.add('strength-strong');
        });

        function toggleCheck(element, condition) {
            const icon = element.querySelector('i');
            if (condition) {
                element.classList.add('valid');
                icon.className = 'fa-solid fa-circle-check';
            } else {
                element.classList.remove('valid');
                icon.className = 'fa-solid fa-circle-xmark';
            }
        }


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

        // Handle validation errors
        @if($errors -> any())
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#667eea'
            });
        });
        @endif

        // Handle success message
        @if(session('success'))
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#667eea'
            }).then(() => {
                window.location.href = '/';
            });
        });
        @endif

        // Handle error message
        @if(session('error'))
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#667eea'
            });
        });
        @endif
    </script>
</body>

</html>