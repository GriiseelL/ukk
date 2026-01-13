@extends('layout.layarUtama')

@section('title', 'Homepage - Telava')

@section('content')
<style>
    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
        margin-left: 0;
    }

    .like-btn.liked i {
        color: red;
    }


    .upload-preview video {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .media-type-indicator {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    /* Stories/Highlights Section */
    .highlights-section {
        background: white;
        border: 1px solid #dbdbdb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .highlights-container {
        gap: 16px;
        padding: 4px 0;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .highlights-container::-webkit-scrollbar {
        display: none;
    }

    .highlight-item {
        cursor: pointer;
        flex-shrink: 0;
        width: 80px;
    }

    .highlight-item:hover .highlight-ring {
        transform: scale(1.05);
    }

    .highlight-ring {
        position: relative;
        width: 66px;
        height: 66px;
        margin: 0 auto 8px;
        padding: 2px;
        border-radius: 50%;
        transition: transform 0.2s ease;
    }

    .highlight-ring.has-story {
        background: #0095f6;
    }

    .highlight-ring.no-story {
        background: transparent;
        border: 2px dashed #dbdbdb;
    }

    .highlight-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        padding: 2px;
    }

    .highlight-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #0095f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 20px;
    }

    .add-story-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        background: #0095f6;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-story-btn i {
        font-size: 10px;
        color: white;
    }

    .highlight-name {
        font-size: 12px;
        color: #262626;
        max-width: 74px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .highlights-section {
            border-radius: 0;
            border-left: none;
            border-right: none;
            padding: 12px 16px;
        }
    }

    /* Post Composer */
    .post-composer {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        padding: 20px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .post-composer:focus-within {
        box-shadow: 0 4px 20px rgba(29, 161, 242, 0.15);
        border-color: var(--primary-color);
    }

    .composer-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
    }

    .composer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        flex-shrink: 0;
    }

    .composer-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 18px;
        resize: none;
        min-height: 60px;
        font-family: inherit;
        color: var(--text-color);
    }

    .composer-input::placeholder {
        color: #657786;
    }

    .composer-tools {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
    }

    .composer-options {
        display: flex;
        gap: 15px;
    }

    .composer-option {
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
        font-size: 18px;
    }

    .composer-option:hover {
        background-color: rgba(29, 161, 242, 0.1);
        transform: scale(1.1);
    }

    .post-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 10px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.5;
        pointer-events: none;
    }

    .post-btn.active {
        opacity: 1;
        pointer-events: all;
    }

    .post-btn.active:hover {
        background: #0d8bd9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
    }

    .char-count {
        font-size: 14px;
        color: #657786;
        margin-right: 15px;
    }

    .char-count.warning {
        color: #ff6b35;
    }

    .char-count.danger {
        color: #e74c3c;
    }

    /* Feed Posts */
    .feed-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .post-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        animation: slideUp 0.6s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .post-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .post-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .post-avatar {
        width: 48px;
        height: 48px;
        min-width: 48px;
        min-height: 48px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
    }

    .post-avatar img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }


    .post-avatar:hover {
        transform: scale(1.05);
    }

    .post-user-info h6 {
        margin: 0;
        font-weight: 700;
        color: var(--text-color);
    }

    .post-user-info small {
        color: #657786;
    }

    .post-content {
        margin-bottom: 15px;
        line-height: 1.6;
        font-size: 15px;
    }

    .post-media {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 15px;
        max-height: 400px;
    }

    .post-media img {
        width: 100%;
        height: auto;
        object-fit: cover;
        cursor: pointer;
    }

    .post-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid var(--border-color);
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        color: #657786;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 20px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .action-btn:hover {
        background-color: #f7f9fa;
        color: var(--text-color);
    }

    .action-btn.liked {
        color: var(--secondary-color);
    }

    .action-btn.liked:hover {
        background-color: rgba(233, 30, 99, 0.1);
    }

    /* File Upload */
    .file-upload {
        display: none;
    }

    .upload-preview {
        margin-top: 15px;
        max-height: 200px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
    }

    .upload-preview img {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hidden {
        display: none;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content {
        position: relative;
        margin: 5% auto;
        width: 90%;
        max-width: 500px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .close {
        color: #aaa;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        border: none;
        background: none;
    }

    .close:hover {
        color: #000;
    }

    .modal-body {
        padding: 20px;
    }

    /* Sidebar Widgets */
    .trending-sidebar h6 {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-weight: 700;
        color: var(--text-color);
    }

    .trending-item {
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .trending-item:last-child {
        border-bottom: none;
    }

    .trending-item:hover {
        background-color: #f7f9fa;
        margin: 0 -20px;
        padding-left: 20px;
        padding-right: 20px;
    }

    .trending-topic {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 2px;
    }

    .trending-count {
        font-size: 12px;
        color: #657786;
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        animation: fadeIn 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .image-modal-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px 20px;
    }

    .image-modal-content img {
        max-width: 95%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 60px rgba(0, 0, 0, 0.8);
        animation: zoomIn 0.3s ease;
    }

    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s ease;
        background: rgba(0, 0, 0, 0.6);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
        line-height: 1;
    }

    .image-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
        border-color: rgba(255, 255, 255, 0.6);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Comment Dropdown Styles */
    .dropdown-menu {
        min-width: 120px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
    }

    .dropdown-item {
        padding: 8px 16px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
        color: #dc3545 !important;
    }

    .dropdown-toggle::after {
        display: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            margin-top: 60px;
        }

        .highlights-container {
            gap: 10px;
        }

        .highlight-item {
            min-width: 70px;
        }

        .highlight-avatar {
            width: 60px;
            height: 60px;
        }

        .composer-input {
            font-size: 16px;
        }

        .post-card {
            margin: 10px;
            border-radius: 12px;
        }

        .image-modal-close {
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            font-size: 30px;
        }

        .image-modal-content {
            padding: 50px 10px 10px;
        }

        .image-modal-content img {
            max-width: 100%;
            max-height: 85vh;
        }
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
            margin-left: -10%;
        }
    }
</style>

<div class="row">
    <div class="col-lg-3 d-none d-lg-block"></div>
    <div class="col-lg-6 col-12">
        <div class="feed-container">
            <!-- Highlights Section -->
            <div class="highlights-section">
                <div class="highlights-header d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0" style="font-size: 16px; font-weight: 600; color: #262626;">Stories</h6>
                </div>

                <div class="highlights-container d-flex overflow-auto">
                    {{-- Your Story --}}
                    @php
                    $authUser = $usersWithStories->firstWhere('id', Auth::id());
                    $hasUserStory = $authUser && $authUser->stories->isNotEmpty();
                    @endphp

                    <div class="highlight-item text-center"
                        onclick="handleYourStory({{ $hasUserStory ? 'true' : 'false' }}, '{{ Auth::user()->username }}')">

                        <div class="highlight-ring {{ $hasUserStory ? 'has-story' : 'no-story' }}">
                            <div class="highlight-avatar">
                                @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Your Story">
                                @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>

                            @if(!$hasUserStory)
                            <div class="add-story-btn">
                                <i class="fas fa-plus"></i>
                            </div>
                            @endif
                        </div>

                        <div class="highlight-name">Your Story</div>
                    </div>

                    {{-- Stories dari user lain --}}
                    @foreach($usersWithStories->where('id', '!=', Auth::id()) as $user)
                    <div class="highlight-item text-center"
                        onclick="openStory('{{ $user->username }}')">

                        <div class="highlight-ring has-story">
                            <div class="highlight-avatar">
                                @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                <div class="avatar-placeholder"
                                    style="background: linear-gradient(135deg, #{{ substr(md5($user->id), 0, 6) }}, #{{ substr(md5($user->id + 1), 0, 6) }});">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="highlight-name">
                            {{ Str::limit($user->name, 10) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Post Composer -->
            <div class="post-composer">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                    @csrf

                    <div class="composer-header">
                        <div class="composer-avatar">
                            @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar"
                                class="rounded-circle" width="40" height="40">
                            @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <textarea class="composer-input" id="postContent" name="caption"
                            placeholder="Apa yang sedang terjadi?" maxlength="280" oninput="updateCharCount()"
                            required></textarea>
                    </div>

                    <div class="upload-preview hidden" id="uploadPreview">
                        <span class="media-type-indicator" id="mediaTypeIndicator"></span>
                        <img id="previewImage" src="" alt="Preview" style="display: none;">
                        <video id="previewVideo" controls style="display: none;"></video>
                        <button type="button" class="remove-image" onclick="removeMedia()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <input type="file"
                        class="file-upload"
                        id="mediaUpload"
                        name="media"
                        accept="image/*,video/*"
                        hidden
                        onchange="handleMediaUpload(event)">

                    <div class="composer-tools">
                        <div class="composer-options">
                            <button type="button" class="composer-option" onclick="triggerMediaUpload('image')"
                                title="Tambah foto">
                                <i class="fas fa-image"></i>
                            </button>
                            <button type="button" class="composer-option"
                                onclick="triggerMediaUpload('video')"
                                title="Tambah video">
                                <i class="fas fa-video"></i>
                            </button>
                            <button type="button" class="composer-option" title="Tambah poll">
                                <i class="fas fa-poll"></i>
                            </button>
                            <button type="button" class="composer-option" title="Tambah emoji">
                                <i class="fas fa-smile"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="char-count" id="charCount">0/280</span>
                            <button type="submit" class="post-btn" id="postBtn">Post</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Feed Posts -->
            <div id="feedContainer">
                @forelse($posts as $post)
                <div class="post-card card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="post-avatar me-3">
                                <a href="{{ $post->user->id === auth()->id() ? route('profile') : url('/profilePage/' . $post->user->username) }}"
                                    class="text-decoration-none">
                                    @if($post->user->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar"
                                        class="rounded-circle" width="50" height="50">
                                    @else
                                    <div class="avatar-placeholder rounded-circle d-flex justify-content-center align-items-center"
                                        style="width: 50px; height: 50px; font-size: 18px; font-weight: 600; background: linear-gradient(135deg, #1e90ff, #007bff); color: white; text-decoration: none; line-height: 1;">
                                        {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                    </div>
                                    @endif
                                </a>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="{{ $post->user->id === auth()->id() ? route('profile') : url('/profilePage/' . $post->user->username) }}"
                                        class="text-decoration-none text-dark">
                                        <strong class="me-2">{{ $post->user->name }}</strong>
                                    </a>
                                    <span class="text-muted">{{ '@' . $post->user->username }}</span>
                                    <span class="text-muted ms-2">• {{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mb-3">{{ $post->caption }}</p>
                                @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}"
                                    alt="Post"
                                    class="post-image mb-3"
                                    onclick="openImageModal('{{ asset('storage/' . $post->image) }}')"
                                    style="cursor: pointer;" />
                                @endif
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-light" onclick="showComments({{ $post->id }})">
                                        <i class="far fa-comment"></i> {{ $post->comments_count ?? 0 }}
                                    </button>
                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-bookmark"></i>
                                    </button>

                                    <button class="btn btn-sm like-btn" onclick="toggleLike(this)"
                                        data-post-id="{{ $post->id }}"
                                        data-liked="{{ $post->is_liked_by_auth_user ? 'true' : 'false' }}">
                                        <i class="fa fa-heart {{ $post->is_liked_by_auth_user ? 'text-danger' : 'text-secondary' }}"></i>
                                        <span class="like-count">{{ $post->likes_count }}</span>
                                    </button>

                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-share"></i> {{ $post->shares_count ?? 0 }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada post</h5>
                    <p class="text-muted">Mulai mengikuti orang untuk melihat post mereka disini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-3 d-none d-lg-block">
        <div class="trending-sidebar">
            <h6><i class="fas fa-users text-primary"></i> Suggested for you</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="composer-avatar" style="width: 40px; height: 40px; font-size: 14px;">SC</div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Sarah Chen</div>
                    <small class="text-muted">@sarahchen</small>
                </div>
                <button class="btn btn-outline-primary btn-sm">Follow</button>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="composer-avatar"
                    style="width: 40px; height: 40px; font-size: 14px; background: linear-gradient(135deg, #ff6b6b, #4ecdc4);">
                    AS</div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Alex Smith</div>
                    <small class="text-muted">@alexsmith</small>
                </div>
                <button class="btn btn-outline-primary btn-sm">Follow</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Title</h3>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
            Content goes here
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
    <div class="image-modal-content">
        <img id="modalImage" src="" alt="Full size image">
    </div>
</div>

<script>
    // ============================================================
    // GLOBAL VARIABLES
    // ============================================================
    const currentUserId = Number("{{ auth()->id() }}");

    let uploadedFiles = []; // Array untuk menyimpan multiple files
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
    const MAX_VIDEO_SIZE = 50 * 1024 * 1024; // 50MB
    const MAX_FILES = 4; // Maximum 4 files

    // ============================================================
    // IMMEDIATELY DEFINE GLOBAL FUNCTIONS (BEFORE DOMContentLoaded)
    // ============================================================

    // Story Functions
    window.handleYourStory = function(hasStory, username) {
        if (hasStory) {
            window.location.href = `/stories?user=${username}`;
        } else {
            window.location.href = '/stories/create';
        }
    };

    window.openStory = function(username) {
        window.location.href = `/stories?user=${username}`;
    };

    window.openCreateStory = function() {
        window.location.href = "{{ route('stories.create') }}";
    };

    // Character Count
    window.updateCharCount = function() {
        const postContent = document.getElementById('postContent');
        const charCount = document.getElementById('charCount');
        const postBtn = document.getElementById('postBtn');

        if (!postContent) return;

        const length = postContent.value.length;
        charCount.textContent = `${length}/280`;

        if (length > 280) {
            charCount.classList.add('danger');
            charCount.classList.remove('warning');
            postBtn.classList.remove('active');
        } else if (length > 250) {
            charCount.classList.add('warning');
            charCount.classList.remove('danger');
            postBtn.classList.add('active');
        } else {
            charCount.classList.remove('warning', 'danger');
            if (length > 0 || uploadedFiles.length > 0) {
                postBtn.classList.add('active');
            } else {
                postBtn.classList.remove('active');
            }
        }
    };

    // Media Upload Functions
    window.triggerMediaUpload = function(type) {
        console.log('triggerMediaUpload called with type:', type);
        const mediaUpload = document.getElementById('mediaUpload');

        if (!mediaUpload) {
            console.error('Media upload input not found');
            window.showNotification('⚠️ Upload input not found');
            return;
        }

        // Set accept attribute based on type
        if (type === 'image') {
            mediaUpload.setAttribute('accept', 'image/*');
            mediaUpload.setAttribute('multiple', 'multiple');
        } else if (type === 'video') {
            mediaUpload.setAttribute('accept', 'video/*');
            mediaUpload.removeAttribute('multiple');
        }

        mediaUpload.click();
    };

    window.handleMediaUpload = function(event) {
        const files = Array.from(event.target.files);

        if (!files || files.length === 0) return;

        const uploadPreview = document.getElementById('uploadPreview');
        const mediaTypeIndicator = document.getElementById('mediaTypeIndicator');

        if (!uploadPreview) {
            console.error('Preview elements not found');
            return;
        }

        // Check if adding video when images exist or vice versa
        const hasImages = uploadedFiles.some(f => f.type.startsWith('image/'));
        const hasVideos = uploadedFiles.some(f => f.type.startsWith('video/'));
        const newHasImages = files.some(f => f.type.startsWith('image/'));
        const newHasVideos = files.some(f => f.type.startsWith('video/'));

        if ((hasImages && newHasVideos) || (hasVideos && newHasImages)) {
            window.showNotification('⚠️ Cannot mix images and videos');
            event.target.value = '';
            return;
        }

        // Validate each file
        for (const file of files) {
            if (uploadedFiles.length >= MAX_FILES) {
                window.showNotification(`⚠️ Maximum ${MAX_FILES} files allowed`);
                break;
            }

            if (file.type.startsWith('image/') && file.size > MAX_IMAGE_SIZE) {
                window.showNotification(`⚠️ ${file.name} exceeds 5MB limit`);
                continue;
            }

            if (file.type.startsWith('video/') && file.size > MAX_VIDEO_SIZE) {
                window.showNotification(`⚠️ ${file.name} exceeds 50MB limit`);
                continue;
            }

            const isDuplicate = uploadedFiles.some(f =>
                f.name === file.name && f.size === file.size
            );

            if (isDuplicate) {
                window.showNotification(`⚠️ ${file.name} already added`);
                continue;
            }

            uploadedFiles.push(file);
        }

        window.renderMediaPreviews();
        window.updateCharCount();
        event.target.value = '';

        const fileType = uploadedFiles[0]?.type.startsWith('image/') ? 'Image' : 'Video';
        const count = uploadedFiles.length;
        window.showNotification(`✅ ${count} ${fileType}${count > 1 ? 's' : ''} loaded!`);
    };

    window.renderMediaPreviews = function() {
        const uploadPreview = document.getElementById('uploadPreview');
        const mediaTypeIndicator = document.getElementById('mediaTypeIndicator');

        if (uploadedFiles.length === 0) {
            uploadPreview.classList.add('hidden');
            return;
        }

        uploadPreview.classList.remove('hidden');

        const existingPreviews = uploadPreview.querySelectorAll('.preview-item');
        existingPreviews.forEach(p => p.remove());

        const isImage = uploadedFiles[0].type.startsWith('image/');
        const count = uploadedFiles.length;
        mediaTypeIndicator.innerHTML = isImage ?
            `<i class="fas fa-image"></i> ${count} Image${count > 1 ? 's' : ''}` :
            `<i class="fas fa-video"></i> Video`;

        uploadedFiles.forEach((file, index) => {
            const fileURL = URL.createObjectURL(file);
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.style.cssText = 'position: relative; margin-top: 10px;';

            if (file.type.startsWith('image/')) {
                previewItem.innerHTML = `
                <img src="${fileURL}" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;">
                <button type="button" class="remove-media-btn" onclick="window.removeMediaAtIndex(${index})" 
                    style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            } else if (file.type.startsWith('video/')) {
                previewItem.innerHTML = `
                <video src="${fileURL}" controls style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;"></video>
                <button type="button" class="remove-media-btn" onclick="window.removeMediaAtIndex(${index})"
                    style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            }

            uploadPreview.appendChild(previewItem);
        });
    };

    window.removeMediaAtIndex = function(index) {
        if (index >= 0 && index < uploadedFiles.length) {
            uploadedFiles.splice(index, 1);
            window.renderMediaPreviews();
            window.updateCharCount();
            window.showNotification('🗑️ Media removed');
        }
    };

    window.removeMedia = function() {
        uploadedFiles = [];
        const uploadPreview = document.getElementById('uploadPreview');

        if (uploadPreview) {
            const previews = uploadPreview.querySelectorAll('.preview-item');
            previews.forEach(p => {
                const img = p.querySelector('img');
                const video = p.querySelector('video');
                if (img && img.src) URL.revokeObjectURL(img.src);
                if (video && video.src) URL.revokeObjectURL(video.src);
            });

            uploadPreview.classList.add('hidden');
        }

        window.updateCharCount();
        window.showNotification('🗑️ All media removed');
    };

    // Like Functionality
    window.toggleLike = async function(btn) {
        const postId = btn.dataset.postId;
        const isLiked = btn.dataset.liked === "true";
        const icon = btn.querySelector("i");
        const countSpan = btn.querySelector(".like-count");
        let count = parseInt(countSpan.textContent);
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        btn.disabled = true;

        try {
            const endpoint = isLiked ? `/like/destroy/${postId}/main` : `/like/store/${postId}/main`;

            const response = await fetch(endpoint, {
                method: isLiked ? "DELETE" : "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });

            const result = await response.json();

            if (response.ok) {
                if (!isLiked) {
                    btn.dataset.liked = "true";
                    icon.classList.remove("text-secondary");
                    icon.classList.add("text-danger");
                    countSpan.textContent = count + 1;
                    window.showNotification("❤️ Post liked!");
                } else {
                    btn.dataset.liked = "false";
                    icon.classList.remove("text-danger");
                    icon.classList.add("text-secondary");
                    countSpan.textContent = Math.max(0, count - 1);
                    window.showNotification("💔 Post unliked");
                }
            } else {
                if (response.status === 409) {
                    window.showNotification(isLiked ? "⚠️ Already unliked" : "⚠️ Already liked");
                } else {
                    throw new Error(result.message || 'Failed to toggle like');
                }
            }
        } catch (error) {
            console.error("Like error:", error);
            window.showNotification("⚠️ Network error!");
        } finally {
            btn.disabled = false;
        }
    };

    // Comments Functionality
    window.showComments = function(postId) {
        const modal = document.getElementById('modal');
        const modalBody = document.getElementById('modal-body');

        modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading comments...</p>
        </div>
    `;
        window.openModal('Comments', '');

        fetch(`/posts/show/${postId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                let modalContent = `
            <div class="d-flex align-items-center mb-3">
                ${data.post.user.avatar ? 
                    `<img src="/storage/${data.post.user.avatar}" class="rounded-circle me-3" width="50" height="50" alt="Avatar" style="object-fit: cover;">` :
                    `<div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px; font-weight: bold;">
                        ${data.post.user.name.charAt(0).toUpperCase()}
                    </div>`
                }
                <div>
                    <h6 class="mb-1">
                        <a href="/profilePage/${data.post.user.username}" class="text-decoration-none text-dark">
                            ${data.post.user.name}
                        </a>
                    </h6>
                    <small class="text-muted">@${data.post.user.username} • ${new Date(data.post.created_at).toLocaleDateString()}</small>
                </div>
            </div>
        `;

                if (data.post.image) {
                    modalContent += `
                <div class="mb-3">
                    <img src="/storage/${data.post.image}" class="img-fluid rounded shadow-sm" alt="Post image" style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
            `;
                }

                if (data.post.caption) {
                    modalContent += `
                <div class="mb-3">
                    <p style="white-space: pre-line; font-size: 15px; line-height: 1.5;">${data.post.caption}</p>
                </div>
            `;
                }

                modalContent += `
            <div class="d-flex justify-content-between align-items-center mb-3 py-2 border-top border-bottom">
                <div class="d-flex gap-4">
                    <span id="likesCount-${data.post.id}"><i class="fas fa-heart text-danger"></i> ${data.post.likes_count || 0} likes</span>
                    <span id="commentsCount-${data.post.id}"><i class="fas fa-comment text-primary"></i> <span class="comment-count-number">${data.comments ? data.comments.length : 0}</span> comments</span>
                </div>
                <small class="text-muted">${new Date(data.post.created_at).toLocaleString()}</small>
            </div>
            <hr>
            <h6>Comments</h6>
            <div class="comments-list" id="commentsList-${data.post.id}" style="max-height: 300px; overflow-y: auto;">
        `;

                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const isOwnComment = comment.user.id === currentUserId;
                        modalContent += `
                    <div class="d-flex mb-3 comment-item" data-comment-id="${comment.id}">
                        ${comment.user.avatar ? 
                            `<img src="/storage/${comment.user.avatar}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">` :
                            `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                ${comment.user.name.charAt(0).toUpperCase()}
                            </div>`
                        }
                        <div class="flex-grow-1">
                            <div class="bg-light rounded p-2 position-relative">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong class="small">${comment.user.name}</strong>
                                        <p class="mb-0 small">${comment.content}</p>
                                    </div>
                                    ${isOwnComment ? `
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted p-0 ms-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); window.deleteComment(${comment.id}, ${data.post.id})">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                            <small class="text-muted ms-2">${new Date(comment.created_at).toLocaleString()}</small>
                        </div>
                    </div>
                `;
                    });
                } else {
                    modalContent += `<p class="text-muted text-center py-3" id="noCommentsMsg-${data.post.id}">No comments yet. Be the first to comment!</p>`;
                }

                modalContent += `
            </div>
            <div class="mt-3 border-top pt-3">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="commentInput-${data.post.id}" placeholder="Write a comment..." onkeypress="window.handleCommentKeyPress(event, ${data.post.id})">
                    <button class="btn btn-primary btn-sm" id="commentBtn-${data.post.id}" onclick="window.submitCommentFromModal(${data.post.id})">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div id="commentStatus-${data.post.id}" class="small mt-1" style="display: none;"></div>
            </div>
        `;

                modalBody.innerHTML = modalContent;
            })
            .catch(error => {
                console.error('Error fetching post details:', error);
                modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="mb-0">Failed to load post details. Please try again.</p>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="location.reload()">Refresh Page</button>
            </div>
        `;
            });
    };

    window.handleCommentKeyPress = function(event, postId) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            window.submitCommentFromModal(postId);
        }
    };

    window.submitCommentFromModal = function(postId) {
        const commentInput = document.getElementById(`commentInput-${postId}`);
        const commentBtn = document.getElementById(`commentBtn-${postId}`);
        const commentStatus = document.getElementById(`commentStatus-${postId}`);
        const commentsList = document.getElementById(`commentsList-${postId}`);
        const commentCountSpan = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
        const noCommentsMsg = document.getElementById(`noCommentsMsg-${postId}`);

        const commentText = commentInput.value.trim();

        if (!commentText) {
            commentStatus.textContent = 'Please write a comment';
            commentStatus.className = 'small mt-1 text-danger';
            commentStatus.style.display = 'block';
            return;
        }

        commentBtn.disabled = true;
        commentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        commentStatus.textContent = 'Posting comment...';
        commentStatus.className = 'small mt-1 text-muted';
        commentStatus.style.display = 'block';

        fetch(`/comment/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    post_id: postId,
                    content: commentText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.data) {
                    if (noCommentsMsg) noCommentsMsg.remove();

                    const newComment = data.data;
                    const commentHtml = `
                <div class="d-flex mb-3 comment-item" data-comment-id="${newComment.id}">
                    ${newComment.user.active_avatar ? 
                        `<img src="/storage/${newComment.user.active_avatar}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">` :
                        `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                            ${newComment.user.name.charAt(0).toUpperCase()}
                        </div>`
                    }
                    <div class="flex-grow-1">
                        <div class="bg-light rounded p-2 position-relative">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <strong class="small">${newComment.user.name}</strong>
                                    <p class="mb-0 small">${newComment.content}</p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0 ms-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); window.deleteComment(${newComment.id}, ${postId})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted ms-2">Just now</small>
                    </div>
                </div>
            `;

                    commentsList.insertAdjacentHTML('beforeend', commentHtml);
                    commentsList.scrollTop = commentsList.scrollHeight;

                    if (commentCountSpan) {
                        commentCountSpan.textContent = parseInt(commentCountSpan.textContent) + 1;
                    }

                    const feedCommentBtn = document.querySelector(`button[onclick="showComments(${postId})"]`);
                    if (feedCommentBtn) {
                        const currentCount = feedCommentBtn.textContent.match(/\d+/);
                        const newCount = currentCount ? parseInt(currentCount[0]) + 1 : 1;
                        feedCommentBtn.innerHTML = `<i class="far fa-comment"></i> ${newCount}`;
                    }

                    commentInput.value = '';
                    commentStatus.textContent = 'Comment posted successfully!';
                    commentStatus.className = 'small mt-1 text-success';

                    setTimeout(() => {
                        commentStatus.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                commentStatus.textContent = 'Failed to post comment. Please try again.';
                commentStatus.className = 'small mt-1 text-danger';
            })
            .finally(() => {
                commentBtn.disabled = false;
                commentBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            });
    };

    window.deleteComment = function(commentId, postId) {
        if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) return;

        const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (commentItem) commentItem.style.opacity = '0.5';

        fetch(`/comment/destroy/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (commentItem) {
                        commentItem.style.animation = 'slideOutRight 0.3s ease';
                        setTimeout(() => {
                            commentItem.remove();

                            const commentCountSpan = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
                            if (commentCountSpan) {
                                commentCountSpan.textContent = Math.max(0, parseInt(commentCountSpan.textContent) - 1);
                            }

                            const feedCommentBtn = document.querySelector(`button[onclick="showComments(${postId})"]`);
                            if (feedCommentBtn) {
                                const currentCount = feedCommentBtn.textContent.match(/\d+/);
                                const newCount = currentCount ? Math.max(0, parseInt(currentCount[0]) - 1) : 0;
                                feedCommentBtn.innerHTML = `<i class="far fa-comment"></i> ${newCount}`;
                            }

                            const commentsList = document.getElementById(`commentsList-${postId}`);
                            if (commentsList && commentsList.children.length === 0) {
                                commentsList.innerHTML = `<p class="text-muted text-center py-3" id="noCommentsMsg-${postId}">No comments yet. Be the first to comment!</p>`;
                            }
                        }, 300);
                    }
                    window.showNotification('🗑️ Comment deleted successfully!');
                } else {
                    throw new Error(data.message || 'Failed to delete comment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (commentItem) commentItem.style.opacity = '1';
                window.showNotification('⚠️ Failed to delete comment. Please try again.');
            });
    };

    // Modal Functions
    window.openModal = function(title, content) {
        document.getElementById('modal-title').textContent = title;
        if (content) {
            document.getElementById('modal-body').innerHTML = content;
        }
        document.getElementById('modal').style.display = 'block';
    };

    window.closeModal = function() {
        document.getElementById('modal').style.display = 'none';
    };

    window.openImageModal = function(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = 'block';
        modalImg.src = imageSrc;
        document.body.style.overflow = 'hidden';
    };

    window.closeImageModal = function() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    // Notification Function
    window.showNotification = function(message) {
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());

        const notification = document.createElement('div');
        notification.className = 'custom-notification position-fixed bg-dark text-white px-3 py-2 rounded-pill';
        notification.style.cssText = `
        top: 90px;
        right: 20px;
        z-index: 3000;
        animation: slideInRight 0.3s ease;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
        notification.innerHTML = message;

        if (!document.querySelector('#notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
            document.head.appendChild(style);
        }

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    };

    // ============================================================
    // DOM READY - FORM SUBMISSION & EVENT LISTENERS
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Functions available:', typeof window.triggerMediaUpload);

        const postForm = document.getElementById('postForm');
        const postContent = document.getElementById('postContent');
        const postBtn = document.getElementById('postBtn');

        if (postForm) {
            postForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const hasContent = postContent.value.trim().length > 0;
                const hasMedia = uploadedFiles.length > 0;

                if (!hasContent && !hasMedia) {
                    window.showNotification('⚠️ Please write something or add media');
                    return false;
                }

                const formData = new FormData(this);
                formData.delete('media');

                uploadedFiles.forEach((file, index) => {
                    formData.append(`media[${index}]`, file);
                });

                if (postBtn) {
                    postBtn.disabled = true;
                    postBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
                }

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showNotification('✅ Post created successfully!');
                            postContent.value = '';
                            uploadedFiles = [];
                            window.renderMediaPreviews();
                            window.updateCharCount();

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Failed to create post');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showNotification('⚠️ Failed to create post. Please try again.');

                        if (postBtn) {
                            postBtn.disabled = false;
                            postBtn.innerHTML = 'Post';
                        }
                    });
            });
        }

        // Auto-resize textarea
        if (postContent) {
            postContent.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }

        // Initialize post button state
        if (postBtn) {
            postBtn.classList.remove('active');
        }
    });

    // ============================================================
    // WINDOW EVENT LISTENERS
    // ============================================================
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        const imageModal = document.getElementById('imageModal');
        if (event.target === modal) {
            window.closeModal();
        }
        if (event.target === imageModal) {
            window.closeImageModal();
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeModal();
            window.closeImageModal();
        }
    });

    console.log('✅ All functions loaded successfully');
</script>
@endsection