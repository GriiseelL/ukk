<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Telava</title>
    <link rel="icon" href="{{ asset('logo/telava-logo.png') }}" type="image/png" />

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
            background-color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .navbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
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
            z-index: 100;
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
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d8bd9 100%);
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
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
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
            z-index: 999;
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
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d8bd9 100%);
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

        #tweetText:focus {
            outline: none;
            box-shadow: none;
        }

        .media-options .btn {
            background: none;
            border: none;
            padding: 8px;
            margin-right: 5px;
            transition: all 0.2s ease;
        }

        .media-options .btn:hover {
            background-color: rgba(29, 161, 242, 0.1);
            border-radius: 50%;
        }

        #postTweetBtn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        #postTweetBtn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 161, 242, 0.4);
        }

        /* FIXED: Modal Z-Index */
        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .modal-dialog {
            z-index: 1056 !important;
        }

        /* Pastikan main content tidak tertutup */
        .main-content {
            position: relative;
            z-index: 1;
            background-color: #ffffff;
        }

        .btn-tweet {
            /* background: linear-gradient(135deg, #4f46e5, #6366f1); */
            color: #fff;
            /* ⬅️ ini yang bikin teks putih */
            font-weight: 600;
        }

        .btn-tweet:hover {
            color: #fff;
            /* biar hover nggak balik hitam */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" id="mainNavbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Telava</a>


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
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Desktop Sidebar -->
    <div class="sidebar d-none d-lg-block" style="width: 250px">
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('homepage') }}">
                <i class="fas fa-home me-3"></i>Beranda
            </a>
            <a class="nav-link" href="{{ route('jelajahi') }}">
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
            <a class="nav-link" href="{{ route('profile') }}">
                <i class="far fa-user me-3"></i>Profil
            </a>
        </nav>
        <button class="btn btn-tweet" data-bs-toggle="modal" data-bs-target="#tweetModal">Elav</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <!-- Bottom Navigation (Mobile) -->
    <div class="bottom-nav d-lg-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a class="nav-link active" href="{{ route('homepage') }}">
                        <i class="fas fa-home"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="{{ route('jelajahi') }}">
                        <i class="fas fa-search"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#tweetModal">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="#">
                        <i class="far fa-bell"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="nav-link" href="{{ route('profile') }}">
                        <i class="far fa-user-circle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tweet -->
    <div class="modal fade" id="tweetModal" tabindex="-1" aria-labelledby="tweetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 1px solid #e1e8ed;">
                    <h5 class="modal-title" id="tweetModalLabel" style="font-weight: bold;">Buat Tweet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        @php
                        $user = auth()->user();
                        $avatar = $user->avatar
                        ? asset('storage/' . $user->avatar)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1da1f2&color=fff';
                        @endphp

                        <img src="{{ $avatar }}" class="post-avatar me-3" alt="Avatar">

                        <div class="flex-grow-1">
                            <textarea class="form-control" id="tweetText" rows="4" placeholder="Apa yang sedang terjadi?" style="border: none; resize: none; font-size: 16px;" maxlength="280"></textarea>

                            <div class="text-end mt-2">
                                <small class="text-muted">
                                    <span id="charCount">0</span>/280
                                </small>
                            </div>

                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="position-relative">
                                    <img id="previewImg" src="" class="img-fluid rounded" style="max-height: 300px;">
                                    <button type="button" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-2" onclick="removeImage()" style="border-radius: 50%; width: 32px; height: 32px; padding: 0;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e1e8ed; justify-content: space-between;">
                    <div class="media-options">
                        <label for="imageUpload" class="btn btn-sm" style="color: var(--primary-color); cursor: pointer;">
                            <i class="far fa-image fa-lg"></i>
                        </label>
                        <input type="file" id="imageUpload" accept="image/*" style="display: none;">

                        <button type="button" class="btn btn-sm" style="color: var(--primary-color);">
                            <i class="far fa-smile fa-lg"></i>
                        </button>

                        <button type="button" class="btn btn-sm" style="color: var(--primary-color);">
                            <i class="far fa-calendar fa-lg"></i>
                        </button>
                    </div>

                    <button type="button" class="btn btn-primary" id="postTweetBtn" disabled style="background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%); border: none; border-radius: 20px; padding: 8px 20px; font-weight: bold;">
                        Elav
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // FIXED: Pastikan tidak ada modal yang terbuka saat page load
        document.addEventListener('DOMContentLoaded', function() {
            // Tutup semua modal backdrop yang mungkin masih ada
            const modalBackdrops = document.querySelectorAll('.modal-backdrop');
            modalBackdrops.forEach(backdrop => backdrop.remove());

            // Reset body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });

        // Like button functionality
        document.querySelectorAll(".post-card .fa-heart").forEach((icon) => {
            icon.addEventListener("click", function() {
                const btn = this.closest("button");
                const span = btn.querySelector("span");
                let count = parseInt(span?.textContent || "0");

                if (this.classList.contains("fas")) {
                    this.classList.remove("fas", "liked");
                    this.classList.add("far");
                    btn.classList.remove("liked");
                    if (span) span.textContent = count - 1;
                } else {
                    this.classList.remove("far");
                    this.classList.add("fas", "liked");
                    btn.classList.add("liked");
                    if (span) span.textContent = count + 1;
                }
            });
        });

        // Counter karakter
        document.getElementById('tweetText').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('charCount').textContent = count;
            const btn = document.getElementById('postTweetBtn');
            const hasImage = document.getElementById('imagePreview').style.display === 'block';
            btn.disabled = count === 0 && !hasImage;
            const charCountElement = document.getElementById('charCount');
            if (count > 250) {
                charCountElement.style.color = '#e91e63';
            } else {
                charCountElement.style.color = '#666';
            }
        });

        // Preview gambar
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                event.target.value = '';
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('postTweetBtn').disabled = false;
            };
            reader.readAsDataURL(file);
        });

        // Hapus gambar
        function removeImage() {
            document.getElementById('imageUpload').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            const tweetText = document.getElementById('tweetText');
            document.getElementById('postTweetBtn').disabled = tweetText.value.length === 0;
        }

        // Post tweet
        document.getElementById('postTweetBtn').addEventListener('click', function() {
            const text = document.getElementById('tweetText').value.trim();
            const imageFile = document.getElementById('imageUpload').files[0];
            const btn = this;

            if (!text && !imageFile) {
                alert('Tweet tidak boleh kosong!');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memposting...';

            const formData = new FormData();
            if (text) formData.append('caption', text);
            if (imageFile) formData.append('image', imageFile);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/posts/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.message) {
                        document.getElementById('tweetText').value = '';
                        document.getElementById('charCount').textContent = '0';
                        removeImage();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('tweetModal'));
                        modal.hide();
                        window.location.reload();
                    } else {
                        throw new Error(data.error || 'Gagal memposting tweet');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memposting tweet. Silakan coba lagi.');
                    btn.disabled = false;
                    btn.textContent = 'Elav';
                });
        });

        // Reset modal
        document.getElementById('tweetModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('tweetText').value = '';
            document.getElementById('charCount').textContent = '0';
            removeImage();
            document.getElementById('postTweetBtn').disabled = true;
        });
    </script>
</body>

</html>