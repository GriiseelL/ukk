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

        /* ✅ PERBAIKAN NAVBAR - Z-INDEX STANDAR BOOTSTRAP */
        .navbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030 !important;
            height: 55px;
            padding: 0 16px;
        }


        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 18px;
        }


        /* ✅ PERBAIKAN DROPDOWN - PASTIKAN BISA MUNCUL */
        .navbar .dropdown-menu {
            z-index: 1031 !important;
            /* Di atas navbar */
            position: absolute !important;
        }

        .navbar .nav-item.dropdown {
            position: relative;
        }

        /* ✅ PASTIKAN TIDAK ADA OVERFLOW YANG MEMOTONG */
        .navbar-collapse {
            overflow: visible !important;
        }

        .navbar .container-fluid {
            overflow: visible !important;
        }

        .sidebar {
            background-color: var(--card-bg);
            border-right: 1px solid var(--border-color);
            height: calc(100vh - 70px);
            position: fixed;
            top: 50px;
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

        /* ✅ PERBAIKAN MODAL Z-INDEX */
        .modal {
            z-index: 1050 !important;
            /* Bootstrap standard */
        }

        .modal-backdrop {
            z-index: 1040 !important;
            /* Bootstrap standard */
        }

        .modal-dialog {
            z-index: 1055 !important;
        }

        .main-content {
            position: relative;
            background-color: #ffffff;
        }

        .btn-tweet {
            color: #fff;
            font-weight: 600;
        }

        .btn-tweet:hover {
            color: #fff;
        }

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

        /* ===== MODAL MEDIA PREVIEW STYLES (UNIQUE TO MODAL!) ===== */
        #modalMediaPreview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            width: 100%;
            min-height: 0;
        }

        #modalMediaPreview .media-item {
            position: relative;
            width: calc(50% - 5px);
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
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.75);
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
            font-size: 14px;
        }

        #modalMediaPreview .remove-media:hover {
            background: rgba(220, 53, 69, 0.95);
            transform: scale(1.1);
        }

        #modalMediaPreview .media-item video {
            background: #000;
        }

        #modalMediaPreview .video-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        #modalMediaPreview .media-count-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: rgba(29, 161, 242, 0.9);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" id="mainNavbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Telava</a>

            <div class="navbar-nav flex-row">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        @php
                        $user = auth()->user();
                        $navbarAvatar = $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=1da1f2&color=fff';
                        @endphp

                        <img
                            src="{{ $navbarAvatar }}"
                            class="rounded-circle"
                            style="width:32px;height:32px;object-fit:cover;">


                    </a> <!-- ✅ TUTUP A DI SINI -->

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings') }}">Pengaturan</a></li>
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
            <a class="nav-link d-flex justify-content-between align-items-center"
                href="{{ route('notifications.index') }}">
                <span>
                    <i class="far fa-bell me-3"></i>Notifikasi
                </span>
                @if($unreadNotificationsCount > 0)
                <span class="badge bg-danger rounded-pill">
                    {{ $unreadNotificationsCount }}
                </span>
                @endif
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
    <div class="modal fade" id="tweetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px">

                <div class="modal-header">
                    <h5 class="modal-title">Buat Elav</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex">
                        @php
                        $user = auth()->user();
                        $avatar = $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=1da1f2&color=fff';
                        @endphp

                        <img src="{{ $avatar }}" class="post-avatar me-3">

                        <div class="flex-grow-1">
                            <textarea
                                id="tweetText"
                                class="form-control"
                                rows="4"
                                placeholder="Apa yang sedang terjadi?"
                                maxlength="280"
                                style="border:none;resize:none"></textarea>

                            <small class="text-muted float-end">
                                <span id="charCount">0</span>/280
                            </small>
                        </div>
                    </div>

                    <!-- MODAL MEDIA PREVIEW - ID UNIK! -->
                    <div id="modalMediaPreview"></div>
                </div>

                <div class="modal-footer justify-content-between">
                    <div class="d-flex gap-2">
                        <!-- Upload Image/Video -->
                        <label for="modalMediaUpload" class="btn btn-sm text-primary" title="Upload gambar atau video">
                            <i class="far fa-image fa-lg"></i>
                        </label>

                        <input
                            type="file"
                            id="modalMediaUpload"
                            multiple
                            accept="image/*,video/*"
                            hidden>

                        <small class="text-muted align-self-center" id="modalMediaCount"></small>
                    </div>

                    <button id="postTweetBtn"
                        class="btn btn-primary"
                        disabled
                        style="border-radius:20px">
                        Elav
                    </button>
                </div>

            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        console.log('🚀 Script started loading...');

        (function() {
            if (window.location.pathname.includes('/flipside')) {
                document.body.setAttribute('data-flipside', 'true');
            }
        })();

        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ DOM Ready!');

            // ✅ PERBAIKAN: Hanya bersihkan backdrop yang tidak terpakai
            // JANGAN hapus backdrop saat dropdown aktif
            const cleanupUnusedBackdrops = () => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                const hasActiveModal = document.querySelector('.modal.show');
                const hasActiveDropdown = document.querySelector('.dropdown-menu.show');

                // Hanya hapus jika tidak ada modal atau dropdown aktif
                if (!hasActiveModal && !hasActiveDropdown) {
                    backdrops.forEach(backdrop => backdrop.remove());
                }
            };

            cleanupUnusedBackdrops();

            // Reset body styles yang mungkin tersisa
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            // ========== GLOBALS ==========
            let selectedFiles = [];
            const MAX_FILES = 4;
            const MAX_FILE_SIZE = 50 * 1024 * 1024;

            // ========== GET ELEMENTS WITH UNIQUE IDs ==========
            const mediaUploadEl = document.getElementById('modalMediaUpload');
            const mediaPreviewEl = document.getElementById('modalMediaPreview');
            const mediaCountEl = document.getElementById('modalMediaCount');
            const tweetTextEl = document.getElementById('tweetText');
            const charCountEl = document.getElementById('charCount');
            const postBtnEl = document.getElementById('postTweetBtn');
            const tweetModalEl = document.getElementById('tweetModal');

            console.log('Elements:', {
                mediaUpload: !!mediaUploadEl,
                mediaPreview: !!mediaPreviewEl,
                mediaCount: !!mediaCountEl
            });

            if (!mediaUploadEl) {
                console.error('❌ mediaUpload not found!');
                return;
            }

            // ========== FILE UPLOAD HANDLER ==========
            mediaUploadEl.addEventListener('change', function(event) {
                console.log('🔥 FILE INPUT CHANGED!');

                const files = Array.from(event.target.files);
                console.log('Files:', files.length, files.map(f => f.name));

                if (files.length === 0) return;

                if (selectedFiles.length + files.length > MAX_FILES) {
                    alert(`Maksimal ${MAX_FILES} file!`);
                    event.target.value = '';
                    return;
                }

                files.forEach(file => {
                    if (file.size > MAX_FILE_SIZE) {
                        alert(`File ${file.name} terlalu besar!`);
                        return;
                    }

                    if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
                        alert(`File ${file.name} harus berupa gambar atau video!`);
                        return;
                    }

                    selectedFiles.push(file);
                    console.log('✅ Added:', file.name);
                });

                console.log('Total files:', selectedFiles.length);
                updateMediaPreview();
                updatePostButton();
                event.target.value = '';
            });

            // ========== UPDATE PREVIEW ==========
            function updateMediaPreview() {
                console.log('🔄 Updating preview...');

                if (!mediaPreviewEl || !mediaCountEl) {
                    console.error('❌ Preview elements not found!');
                    return;
                }

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
                        console.log('✅ Loaded:', file.name);
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

                    reader.onerror = function(error) {
                        console.error('❌ Error:', error);
                    };

                    reader.readAsDataURL(file);

                    // Remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-media';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.type = 'button';
                    removeBtn.onclick = function(e) {
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

                console.log('✅ Preview updated!');
            }

            function removeMedia(index) {
                console.log('🗑️ Removing:', index);
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
                                throw new Error(data.error || 'Gagal');
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

            // ========== LIKE BUTTON ==========
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

            console.log('🎉 ALL SETUP COMPLETE!');
        });
    </script>
</body>

</html>