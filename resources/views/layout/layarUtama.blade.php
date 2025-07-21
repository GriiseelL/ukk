<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telava</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/png" />


    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1da1f2;
            --secondary-color: #e91e63;
            --dark-bg: #000;
            --card-bg: #fff;
            --text-color: #333;
            --border-color: #e1e8ed;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                sans-serif;
        }

        .navbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }

        .main-content {
            margin-top: 70px;
            padding-bottom: 80px;
        }

        .sidebar {
            background-color: var(--card-bg);
            border-right: 1px solid var(--border-color);
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: var(--text-color);
            padding: 15px 20px;
            border-radius: 25px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(29, 161, 242, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg,
                    var(--primary-color) 0%,
                    #0d8bd9 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
        }

        .post-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .post-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .post-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .story-container {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .story-item {
            min-width: 75px;
            text-align: center;
            margin-right: 15px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .story-item:hover {
            transform: scale(1.05);
        }

        .story-avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(45deg,
                    #f09433 0%,
                    #e6683c 25%,
                    #dc2743 50%,
                    #cc2366 75%,
                    #bc1888 100%);
            padding: 3px;
            margin-bottom: 8px;
        }

        .story-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .bottom-nav {
            background-color: var(--card-bg);
            border-top: 1px solid var(--border-color);
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
            z-index: 1000;
        }

        .bottom-nav .nav-link {
            color: var(--text-color);
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .bottom-nav .nav-link:hover {
            background-color: rgba(29, 161, 242, 0.1);
            color: var(--primary-color);
        }

        .bottom-nav .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(29, 161, 242, 0.1);
        }

        .trending-sidebar {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .btn-tweet {
            background: linear-gradient(135deg,
                    var(--primary-color) 0%,
                    #0d8bd9 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 24px;
            font-weight: bold;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
        }

        .btn-tweet:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 161, 242, 0.4);
        }

        .compose-box {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 20px;
            margin-bottom: 0;
        }

        .mobile-only {
            display: none;
        }

        .desktop-only {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-top: 56px;
                padding-bottom: 80px;
            }

            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }

            .post-card {
                border-radius: 12px;
                margin-bottom: 12px;
            }

            .story-container {
                border-radius: 12px;
                margin-bottom: 12px;
            }

            .explore-grid {
                gap: 3px;
            }

            .explore-item {
                border-radius: 6px;
            }
        }

        .explore-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
            margin-top: 20px;
        }

        .explore-item {
            aspect-ratio: 1;
            overflow: hidden;
            position: relative;
        }

        .explore-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .explore-item:hover::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fab fa-twitter"></i> SocialApp
            </a>

            <div class="d-flex mx-auto" style="width: 300px">
                <input class="form-control" type="search" placeholder="Cari..." aria-label="Search">
            </div>

            <div class="navbar-nav flex-row">
                <a class="nav-link me-3" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </a>
                <a class="nav-link me-3" href="#">
                    <i class="far fa-envelope"></i>
                </a>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown">
                        <i class="far fa-user-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Desktop Sidebar -->
    <div class="sidebar d-none d-lg-block" style="width: 250px">
        <nav class="nav flex-column">
            <a class="nav-link active" href="#">
                <i class="fas fa-home me-3"></i>Beranda
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-search me-3"></i>Jelajah
            </a>
            <a class="nav-link" href="#">
                <i class="far fa-bell me-3"></i>Notifikasi
            </a>
            <a class="nav-link" href="#">
                <i class="far fa-envelope me-3"></i>Pesan
            </a>
            <a class="nav-link" href="#">
                <i class="far fa-bookmark me-3"></i>Tersimpan
            </a>
            <a class="nav-link" href="#">
                <i class="far fa-user me-3"></i>Profil
            </a>
        </nav>
        <button class="btn btn-tweet">Tweet</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            {{-- <div class="row">
                <div class="col-lg-3 d-none d-lg-block"></div>
                <div class="col-lg-6 col-12">
                    <!-- Content Area - Tempat untuk konten dinamis -->
                    <div class="content-area">
                        <h5>Konten Utama</h5>
                        <p>Area ini untuk konten dinamis yang akan diisi sesuai dengan halaman yang aktif.</p>
                    </div>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <!-- Right Sidebar -->
                    <div class="right-sidebar">
                        <h6>Trending</h6>
                        <p>Sidebar kanan untuk trending topics, suggestions, dll.</p>
                    </div>
                </div>
            </div>
        </div> --}}
        @yield('content')

    </div>

    <!-- Bottom Navigation (Mobile) -->
    <div class="bottom-nav d-lg-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-home"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="#">
                        <i class="fas fa-search"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="#">
                        <i class="far fa-bell"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="#">
                        <i class="far fa-user-circle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll(".post-card .fa-heart").forEach((icon) => {
            icon.addEventListener("click", function () {
                const btn = this.closest("button");
                const span = btn.querySelector("span");
                let count = parseInt(span?.textContent || "0");

                if (this.classList.contains("fas")) {
                    // unlike
                    this.classList.remove("fas", "liked");
                    this.classList.add("far");
                    btn.classList.remove("liked");
                    if (span) span.textContent = count - 1;
                } else {
                    // like
                    this.classList.remove("far");
                    this.classList.add("fas", "liked");
                    btn.classList.add("liked");
                    if (span) span.textContent = count + 1;
                }
            });
        });
    </script>
</body>

</body>
<style>
    .post-card .btn-sm {
        background: none;
        border: none;
        color: #555;
        font-size: 15px;
        transition: all 0.2s ease;
    }

    .post-card .btn-sm:hover {
        color: var(--primary-color);
        transform: scale(1.2);
    }

    .post-card .btn-sm.liked {
        color: var(--secondary-color);
        animation: pop 0.3s ease;
    }

    @keyframes pop {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.5);
        }

        100% {
            transform: scale(1);
        }
    }
</style>

</html>