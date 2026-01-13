<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialConnect - Login</title>

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

        .login-page {
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
            margin-bottom: 1rem;
        }

        .social-line {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 1.5rem;
        }

        .btn-social {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .btn-social:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .category {
            background: rgba(255, 255, 255, 0.95);
            margin: 0;
            padding: 1rem;
            color: #666;
            font-weight: 500;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-content {
            padding: 2rem;
        }

        .form-tabs {
            display: flex;
            margin-bottom: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 5px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px 20px;
            background: none;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            color: #666;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #b6bbc9, #667eea);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
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
            border-radius: 0 10px 10px 0;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
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

        .forgot-password {
            text-align: center;
            margin-top: 1rem;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            .login-page {
                margin: 10px;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .social-line {
                gap: 10px;
            }

            .btn-social {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .btn-loading {
            pointer-events: none;
            opacity: 0.8;
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
                    <div class="login-page">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-users"></i>
                                    Telava
                                </h4>
                                <div class="social-line">
                                    <a href="#" class="btn-social" onclick="socialLogin(event, 'facebook')">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="btn-social" onclick="socialLogin(event, 'google')">
                                        <i class="fab fa-google"></i>
                                    </a>
                                    <a href="#" class="btn-social" onclick="socialLogin(event, 'twitter')">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="#" class="btn-social" onclick="socialLogin(event, 'instagram')">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </div>

                            <p class="category text-center">
                                <i class="fas fa-magic me-2"></i>
                                Or continue with email
                            </p>

                            <div class="card-content">
                                <!-- Tab Navigation -->
                                <div class="form-tabs">
                                    <button class="tab-btn active" id="login-btn" onclick="switchTab('login')">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                    <button class="tab-btn" id="register-btn" onclick="switchTab('register')">
                                        <i class="fas fa-user-plus me-2"></i>Register
                                    </button>
                                </div>

                                <!-- Login Form -->
                                <div id="login-tab" class="tab-content active">
                                    <form action="{{ route('auth.login') }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input name="email" type="email" class="form-control" placeholder="Email Address" required>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember">
                                            <label class="form-check-label" for="remember">
                                                Remember me
                                            </label>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            Sign In
                                        </button>

                                        <div class="forgot-password">
                                            <a href="#" onclick="showForgotPassword(event)">
                                                <i class="fas fa-question-circle me-1"></i>
                                                Forgot your password?
                                            </a>
                                        </div>
                                    </form>
                                </div>

                                <!-- Forgot Password Box -->
                                <div id="forgotPasswordBox" class="mt-3 d-none">
                                    <form method="POST" action="/forgot-password">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="input-group mb-2">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input type="email"
                                                name="email"
                                                class="form-control"
                                                placeholder="Masukkan email kamu"
                                                required>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Kirim Link Reset
                                            </button>

                                            <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                onclick="hideForgotPassword()">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Register Form -->
                                <div id="register-tab" class="tab-content">
                                    <form action="{{ route('auth.regis') }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            <input name="username" type="text" class="form-control" placeholder="Username" required>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input name="name" type="text" class="form-control" placeholder="Full Name" required>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input name="email" type="email" class="form-control" placeholder="Email Address" required>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I agree to the <a href="#" style="color: #667eea;">Terms & Conditions</a>
                                            </label>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Create Account
                                        </button>
                                    </form>
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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Switch between tabs
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');

            // Add active class to correct button
            document.getElementById(tabName + '-btn').classList.add('active');
        }

        // Social login
        function socialLogin(e, provider) {
            e.preventDefault();
            const btn = e.currentTarget;
            const originalHTML = btn.innerHTML;

            btn.innerHTML = '<div class="loading"></div>';
            btn.style.pointerEvents = 'none';

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.pointerEvents = 'auto';

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: `${provider.charAt(0).toUpperCase() + provider.slice(1)} login successful!`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 2000);
        }

        // Show forgot password
        function showForgotPassword(e) {
            e.preventDefault();
            document.getElementById('forgotPasswordBox')
                .classList.remove('d-none');
        }

        function hideForgotPassword() {
            document.getElementById('forgotPasswordBox')
                .classList.add('d-none');
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
    </script>
</body>

</html>