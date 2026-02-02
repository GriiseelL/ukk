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
        /* ==================== ROOT VARIABLES ==================== */
        :root {
            --primary-color: #1da1f2;
            --secondary-color: #e91e63;
            --dark-bg: #000;
            --card-bg: #fff;
            --text-color: #333;
            --border-color: #e1e8ed;
            --navbar-height: 60px;
            --sidebar-width: 250px;
        }

        /* ==================== GLOBAL STYLES ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* ==================== NAVBAR ==================== */
        .navbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            height: var(--navbar-height);
            padding: 0 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* ==================== NAVBAR AVATAR & DROPDOWN ==================== */
        .navbar .nav-item.dropdown {
            position: relative;
        }

        .navbar .dropdown-toggle::after {
            display: none;
        }

        .navbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .navbar-avatar:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .navbar-avatar-fallback {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1da1f2, #0d8bd9);
            color: white;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            letter-spacing: 0.5px;
        }

        .navbar-avatar-fallback:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
        }

        /* ==================== DROPDOWN MENU ==================== */
        .dropdown-menu {
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            margin-top: 0.5rem;
            min-width: 280px;
            z-index: 1031;
        }

        .dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-header .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-header .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-header .user-avatar-fallback {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1da1f2, #0d8bd9);
            color: white;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown-header .user-details .user-name {
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
            font-size: 0.95rem;
        }

        .dropdown-header .user-details .user-username {
            color: #666;
            font-size: 0.85rem;
            margin: 0;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .dropdown-item:hover {
            background-color: rgba(29, 161, 242, 0.08);
            color: var(--primary-color);
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.08);
            color: #dc3545;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--border-color);
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            background-color: var(--card-bg);
            border-right: 1px solid var(--border-color);
            height: calc(100vh - var(--navbar-height));
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            padding: 1.5rem 1rem;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar .nav-link {
            color: var(--text-color);
            padding: 1rem 1.25rem;
            border-radius: 30px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1.05rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            position: relative;
        }

        .sidebar .nav-link i {
            width: 28px;
            font-size: 1.25rem;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(29, 161, 242, 0.1);
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d8bd9 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
        }

        .sidebar .nav-link.active:hover {
            transform: translateX(0);
        }

        .sidebar .badge {
            position: absolute;
            right: 15px;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* ==================== BUTTON TWEET ==================== */
        .btn-tweet {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d8bd9 100%);
            border: none;
            border-radius: 30px;
            padding: 1rem 1.5rem;
            font-weight: 700;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
            color: white;
            font-size: 1.1rem;
        }

        .btn-tweet:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 161, 242, 0.4);
            color: white;
        }

        .btn-tweet:active {
            transform: translateY(0);
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-top: var(--navbar-height);
            padding-bottom: 80px;
            background-color: #f5f5f5;
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: var(--sidebar-width);
            }
        }

        /* ==================== POST CARD ==================== */
        .post-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .post-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
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
            border-radius: 12px;
        }

        .post-card .btn-sm {
            background: none;
            border: none;
            color: #666;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .post-card .btn-sm:hover {
            color: var(--primary-color);
            background-color: rgba(29, 161, 242, 0.1);
            transform: scale(1.05);
        }

        .post-card .btn-sm.liked {
            color: var(--secondary-color);
        }

        .post-card .btn-sm.liked:hover {
            background-color: rgba(233, 30, 99, 0.1);
        }

        /* ==================== BOTTOM NAVIGATION (MOBILE) ==================== */
        .bottom-nav {
            background-color: var(--card-bg);
            border-top: 1px solid var(--border-color);
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 0.5rem 0;
            z-index: 1020;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
        }

        .bottom-nav .nav-link {
            color: var(--text-color);
            text-align: center;
            padding: 0.75rem;
            border-radius: 12px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }

        .bottom-nav .nav-link i {
            font-size: 1.4rem;
        }

        .bottom-nav .nav-link:hover {
            background-color: rgba(29, 161, 242, 0.1);
            color: var(--primary-color);
        }

        .bottom-nav .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(29, 161, 242, 0.1);
        }

        /* ==================== MODAL ==================== */
        .modal {
            z-index: 1050;
        }

        .modal-backdrop {
            z-index: 1040;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        /* ==================== MODAL MEDIA PREVIEW ==================== */
        #modalMediaPreview {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        #modalMediaPreview .media-item {
            position: relative;
            width: calc(50% - 0.375rem);
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: #f5f5f5;
        }

        #modalMediaPreview .media-item:only-child {
            width: 100%;
            height: 300px;
        }

        #modalMediaPreview:has(.media-item:nth-child(3):last-child) .media-item:first-child {
            width: 100%;
        }

        #modalMediaPreview .media-item img,
        #modalMediaPreview .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #modalMediaPreview .remove-media {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 10;
        }

        #modalMediaPreview .remove-media:hover {
            background: rgba(220, 53, 69, 0.95);
            transform: scale(1.1);
        }

        #modalMediaPreview .video-indicator {
            position: absolute;
            bottom: 0.5rem;
            right: 0.5rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        #modalMediaPreview .media-count-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: rgba(29, 161, 242, 0.9);
            color: white;
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* ==================== FORM STYLES ==================== */
        #tweetText {
            border: none;
            resize: none;
            outline: none !important;
            box-shadow: none !important;
        }

        #tweetText:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* ==================== UTILITIES ==================== */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ==================== FLIPSIDE MODE ==================== */
        body[data-flipside="true"] .dropdown-menu {
            background: #1a1a1a !important;
            border: 2px solid rgba(255, 0, 128, 0.3) !important;
        }

        body[data-flipside="true"] .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        body[data-flipside="true"] .dropdown-item:hover {
            background: rgba(255, 0, 128, 0.2) !important;
            color: #FF0080 !important;
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes pop {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        .post-card .btn-sm.liked {
            animation: pop 0.3s ease;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 575.98px) {
            .navbar-brand {
                font-size: 1.25rem;
            }

            .dropdown-menu {
                min-width: 260px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('homepage') }}">Telava</a>

            <div class="navbar-nav">
                <div class="nav-item dropdown">
                    <a class="nav-link p-0" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        @php
                        $user = auth()->user();
                        @endphp

                        @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}"
                            alt="{{ $user->name }}"
                            class="navbar-avatar">
                        @else
                        <div class="navbar-avatar-fallback">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- User Info Header -->
                        <li>
                            <div class="dropdown-header">
                                <div class="user-info">
                                    @if($user->avatar)
                                    <img src="{{ asset('storage/'.$user->avatar) }}"
                                        alt="{{ $user->name }}"
                                        class="user-avatar">
                                    @else
                                    <div class="user-avatar-fallback">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    @endif
                                    <div class="user-details">
                                        <p class="user-name">{{ $user->name }}</p>
                                        <p class="user-username">{{ '@'.$user->username }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user"></i> Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings') }}">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Desktop Sidebar -->
    <!-- Desktop Sidebar -->
    <div class="sidebar d-none d-lg-block">
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('homepage') }}">
                <i class="fas fa-home"></i> Beranda
            </a>
            <a class="nav-link" href="{{ route('jelajahi') }}">
                <i class="fas fa-search"></i> Jelajah
            </a>
            <!-- ✅ FIX: HAPUS <div class="col"> wrapper -->
            <a class="nav-link" href="{{ route('notifications.index') }}" id="notifications-link">
                <i class="far fa-bell"></i> Notifikasi
                @if($unreadNotificationsCount > 0)
                <span class="badge bg-danger" id="sidebar-badge">{{ $unreadNotificationsCount }}</span>
                @endif
            </a>
            <a class="nav-link" href="{{ route('profile') }}">
                <i class="far fa-user"></i> Profil
            </a>
        </nav>
        <button class="btn btn-tweet" data-bs-toggle="modal" data-bs-target="#tweetModal">
            Elav
        </button>
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
                    <a class="nav-link" href="{{ route('notifications.index') }}">
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
    <div class="modal fade" id="tweetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Elav</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex">
                        @php
                        $avatar = $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=1da1f2&color=fff';
                        @endphp

                        <img src="{{ $avatar }}" class="post-avatar me-3" alt="{{ $user->name }}">

                        <div class="flex-grow-1">
                            <textarea
                                id="tweetText"
                                class="form-control"
                                rows="4"
                                placeholder="Apa yang sedang terjadi?"
                                maxlength="280"></textarea>

                            <small class="text-muted float-end mt-2">
                                <span id="charCount">0</span>/280
                            </small>
                        </div>
                    </div>

                    <div id="modalMediaPreview"></div>
                </div>

                <div class="modal-footer justify-content-between">
                    <div class="d-flex gap-2 align-items-center">
                        <label for="modalMediaUpload" class="btn btn-sm text-primary" title="Upload media">
                            <i class="far fa-image fa-lg"></i>
                        </label>

                        <input type="file" id="modalMediaUpload" multiple accept="image/*,video/*" hidden>

                        <small class="text-muted" id="modalMediaCount"></small>
                    </div>

                    <button id="postTweetBtn" class="btn btn-primary px-4" disabled style="border-radius:30px">
                        Elav
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        console.log('🚀 Script loaded');

        // Flipside mode detection
        (function() {
            if (window.location.pathname.includes('/flipside')) {
                document.body.setAttribute('data-flipside', 'true');
            }
        })();


        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ DOM Ready');

            // Clean up unused backdrops
            const cleanupBackdrops = () => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                const hasActiveModal = document.querySelector('.modal.show');
                const hasActiveDropdown = document.querySelector('.dropdown-menu.show');

                if (!hasActiveModal && !hasActiveDropdown) {
                    backdrops.forEach(backdrop => backdrop.remove());
                }
            };

            cleanupBackdrops();

            // Reset body styles
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            // ========== GLOBALS ==========
            let selectedFiles = [];
            const MAX_FILES = 4;
            const MAX_FILE_SIZE = 50 * 1024 * 1024;

            // ========== GET ELEMENTS ==========
            const mediaUploadEl = document.getElementById('modalMediaUpload');
            const mediaPreviewEl = document.getElementById('modalMediaPreview');
            const mediaCountEl = document.getElementById('modalMediaCount');
            const tweetTextEl = document.getElementById('tweetText');
            const charCountEl = document.getElementById('charCount');
            const postBtnEl = document.getElementById('postTweetBtn');
            const tweetModalEl = document.getElementById('tweetModal');

            if (!mediaUploadEl || !mediaPreviewEl || !mediaCountEl) {
                console.error('❌ Required elements not found');
                return;
            }

            // ========== FILE UPLOAD ==========
            mediaUploadEl.addEventListener('change', function(event) {
                const files = Array.from(event.target.files);

                if (files.length === 0) return;

                if (selectedFiles.length + files.length > MAX_FILES) {
                    alert(`Maksimal ${MAX_FILES} file!`);
                    event.target.value = '';
                    return;
                }

                files.forEach(file => {
                    if (file.size > MAX_FILE_SIZE) {
                        alert(`File ${file.name} terlalu besar (maks 50MB)!`);
                        return;
                    }

                    if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
                        alert(`File ${file.name} harus berupa gambar atau video!`);
                        return;
                    }

                    selectedFiles.push(file);
                });

                updateMediaPreview();
                updatePostButton();
                event.target.value = '';
            });

            // ========== UPDATE PREVIEW ==========
            function updateMediaPreview() {
                mediaPreviewEl.innerHTML = '';

                if (selectedFiles.length === 0) {
                    mediaCountEl.textContent = '';
                    return;
                }

                mediaCountEl.textContent = `${selectedFiles.length}/${MAX_FILES} file`;

                selectedFiles.forEach((file, index) => {
                    const mediaItem = document.createElement('div');
                    mediaItem.className = 'media-item';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let mediaElement;

                        if (file.type.startsWith('image/')) {
                            mediaElement = document.createElement('img');
                            mediaElement.src = e.target.result;
                            mediaElement.alt = file.name;
                        } else if (file.type.startsWith('video/')) {
                            mediaElement = document.createElement('video');
                            mediaElement.src = e.target.result;
                            mediaElement.muted = true;
                            mediaElement.controls = false;

                            const videoIndicator = document.createElement('div');
                            videoIndicator.className = 'video-indicator';
                            videoIndicator.innerHTML = '<i class="fas fa-play"></i> Video';
                            mediaItem.appendChild(videoIndicator);
                        }

                        if (mediaElement) {
                            mediaItem.appendChild(mediaElement);
                        }
                    };

                    reader.readAsDataURL(file);

                    // Remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-media';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.type = 'button';
                    removeBtn.onclick = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        removeMedia(index);
                    };

                    mediaItem.appendChild(removeBtn);

                    // Badge
                    if (selectedFiles.length > 1) {
                        const badge = document.createElement('div');
                        badge.className = 'media-count-badge';
                        badge.textContent = index + 1;
                        mediaItem.appendChild(badge);
                    }

                    mediaPreviewEl.appendChild(mediaItem);
                });
            }

            function removeMedia(index) {
                selectedFiles.splice(index, 1);
                updateMediaPreview();
                updatePostButton();
            }

            function updatePostButton() {
                if (!tweetTextEl || !postBtnEl) return;
                const text = tweetTextEl.value.trim();
                postBtnEl.disabled = text.length === 0 && selectedFiles.length === 0;
            }

            // ========== CHARACTER COUNTER ==========
            if (tweetTextEl && charCountEl) {
                tweetTextEl.addEventListener('input', function() {
                    const count = this.value.length;
                    charCountEl.textContent = count;
                    updatePostButton();

                    if (count > 250) {
                        charCountEl.style.color = '#e91e63';
                    } else {
                        charCountEl.style.color = '#666';
                    }
                });
            }

            // ========== POST TWEET ==========
            if (postBtnEl) {
                postBtnEl.addEventListener('click', function() {
                    const text = tweetTextEl.value.trim();

                    if (!text && selectedFiles.length === 0) {
                        alert('Tweet tidak boleh kosong!');
                        return;
                    }

                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memposting...';

                    const formData = new FormData();
                    if (text) formData.append('caption', text);

                    selectedFiles.forEach(file => {
                        formData.append('media[]', file);
                    });

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
                                resetTweetModal();
                                const modal = bootstrap.Modal.getInstance(tweetModalEl);
                                modal.hide();
                                window.location.reload();
                            } else {
                                throw new Error(data.error || 'Gagal memposting');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal memposting tweet!');
                            this.disabled = false;
                            this.textContent = 'Elav';
                        });
                });
            }

            // ========== RESET MODAL ==========
            function resetTweetModal() {
                if (tweetTextEl) tweetTextEl.value = '';
                if (charCountEl) charCountEl.textContent = '0';
                selectedFiles = [];
                updateMediaPreview();
                if (postBtnEl) postBtnEl.disabled = true;
            }

            if (tweetModalEl) {
                tweetModalEl.addEventListener('hidden.bs.modal', resetTweetModal);
            }

            console.log('🎉 Setup complete');
        });
        // ============================================================
        // NOTIFICATION BADGE REAL-TIME UPDATE
        // ============================================================
        window.updateNotificationBadge = function(count = null) {
            if (count === null) {
                // Ambil count dari server
                fetch('/notifications/unread-count', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateBadgeElement(data.count);
                    })
                    .catch(error => console.error('Error fetching notification count:', error));
            } else {
                updateBadgeElement(count);
            }
        };

        function updateBadgeElement(count) {
            // ✅ Gunakan ID yang sudah kita tambahkan
            const sidebarBadge = document.getElementById('sidebar-badge');
            const mobileBadge = document.getElementById('mobile-badge');

            if (count > 0) {
                // Sidebar badge
                if (sidebarBadge) {
                    sidebarBadge.textContent = count;
                    sidebarBadge.style.display = 'inline-block';
                } else {
                    // Buat baru jika belum ada
                    const link = document.getElementById('notifications-link');
                    if (link) {
                        const newBadge = document.createElement('span');
                        newBadge.id = 'sidebar-badge';
                        newBadge.className = 'badge bg-danger';
                        newBadge.textContent = count;
                        newBadge.style.fontSize = '0.75rem';
                        newBadge.style.padding = '0.25rem 0.5rem';
                        newBadge.style.position = 'absolute';
                        newBadge.style.right = '15px';
                        link.appendChild(newBadge);
                    }
                }

                // Mobile badge
                if (mobileBadge) {
                    mobileBadge.textContent = count;
                    mobileBadge.style.display = 'inline-block';
                } else {
                    const link = document.getElementById('mobile-notifications-link');
                    if (link) {
                        const newBadge = document.createElement('span');
                        newBadge.id = 'mobile-badge';
                        newBadge.className = 'badge bg-danger position-absolute top-0 start-100 translate-middle';
                        newBadge.style.fontSize = '0.6rem';
                        newBadge.style.padding = '0.2rem 0.4rem';
                        newBadge.textContent = count;
                        link.appendChild(newBadge);
                    }
                }
            } else {
                // Sembunyikan badge
                if (sidebarBadge) sidebarBadge.style.display = 'none';
                if (mobileBadge) mobileBadge.style.display = 'none';
            }
        }

        // ============================================================
        // MARK ALL NOTIFICATIONS AS READ
        // ============================================================
        window.markNotificationsAsRead = function(callback = null) {
            fetch('/notifications/read', { // ✅ URL yang benar sesuai route kamu
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // ✅ Update badge jadi 0
                        window.updateNotificationBadge(0);

                        // ✅ Optional: Update UI notifikasi (hilangkan dot/read indicator)
                        const notificationItems = document.querySelectorAll('.notification-item');
                        notificationItems.forEach(item => {
                            item.classList.remove('unread');
                            const dot = item.querySelector('.notification-dot');
                            if (dot) dot.remove();
                        });

                        if (callback) callback();
                    }
                })
                .catch(error => {
                    console.error('Error marking notifications as read:', error);
                    if (callback) callback(false, error);
                });
        };

        // ============================================================
        // AUTO MARK AS READ SAAT BUKA HALAMAN NOTIFIKASI
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ini halaman notifikasi
            if (window.location.pathname.includes('/notifications')) {
                console.log('🔔 Notification page detected');

                // ✅ 300ms lebih cepat (user masih sempat lihat notifikasi baru)
                setTimeout(() => {
                    window.markNotificationsAsRead(() => {
                        console.log('✅ All notifications marked as read');
                    });
                }, 300);
            }
        });

        // ============================================================
        // MARK AS READ SAAT KLIK NOTIFICATION LINK
        // ============================================================
        document.addEventListener('click', function(e) {
            const target = e.target.closest('#notifications-link, #mobile-notifications-link');
            if (target) {
                // Delay 2 detik agar user sempat lihat
                setTimeout(() => {
                    window.markNotificationsAsRead(() => {
                        console.log('✅ Notifications marked as read after click');
                    });
                }, 2000);
            }
        });
    </script>
</body>

</html>