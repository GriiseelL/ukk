@extends('layout.layarUtama')

@section('title', 'Profil Saya - Telava')

@section('content')
<!-- Add CSRF token to head if not already present -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<!-- Cropper.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    window.appData = {
        followers: @json($followers ?? []),
        following: @json($following ?? []),
        followingIds: @json($followingIds ?? []),
        posts: @json($posts ?? []),
        user: @json($user ?? [])
    };
</script>

<style>
    .comment-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-avatar-fallback {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
    }

    .profile-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        animation: slideUp 0.6s ease-out;
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

    /* Cover Section */
    .cover-section {
        height: 200px;
        background: linear-gradient(45deg, #667eea, #764ba2, #45b7d1);
        background-size: 300% 300%;
        animation: gradientShift 6s ease infinite;
        position: relative;
        cursor: pointer;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .cover-edit-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        font-size: 18px;
        font-weight: 600;
    }

    .cover-section:hover .cover-edit-overlay {
        opacity: 1;
    }

    /* Profile Header */
    .profile-header {
        padding: 0 30px 20px;
        position: relative;
        margin-top: -50px;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        min-width: 200px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1000;
        animation: dropdownSlide 0.2s ease-out;
    }

    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item {
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background-color 0.2s ease;
        font-size: 14px;
        color: #333;
    }

    .dropdown-item:hover {
        background-color: rgba(29, 161, 242, 0.1);
        color: var(--primary-color, #1da1f2);
    }

    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        border: 4px solid white;
        background: linear-gradient(135deg, var(--primary-color, #1da1f2), #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: bold;
        color: white;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
    }

    .avatar:hover {
        transform: scale(1.05);
    }

    .avatar-edit-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        font-size: 14px;
        border-radius: 16px;
    }

    .avatar:hover .avatar-edit-overlay {
        opacity: 1;
    }

    .profile-info {
        margin-top: 15px;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .profile-username {
        font-size: 16px;
        color: #666;
        margin-bottom: 15px;
    }

    .profile-bio {
        font-size: 15px;
        line-height: 1.5;
        color: #444;
        margin-bottom: 20px;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .profile-bio:hover {
        background-color: rgba(29, 161, 242, 0.05);
    }

    /* Stats Section */
    .stats-section {
        padding: 20px 30px;
        background: rgba(0, 0, 0, 0.02);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Content Tabs */
    .content-tabs {
        padding: 0 30px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .tab-list {
        display: flex;
        gap: 0;
        overflow-x: auto;
    }

    .tab-item {
        padding: 15px 20px;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #666;
        white-space: nowrap;
    }

    .tab-item.active {
        color: var(--primary-color, #1da1f2);
        border-bottom-color: var(--primary-color, #1da1f2);
    }

    .tab-item:hover {
        color: var(--primary-color, #1da1f2);
        background: rgba(29, 161, 242, 0.05);
    }

    /* Content Area */
    .content-area {
        padding: 30px;
        min-height: 300px;
    }

    .posts-stream {
        margin-top: 20px;
    }

    .post-item {
        background: white;
        border: 1px solid var(--border-color, #e1e8ed);
        border-radius: 12px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        position: relative;
    }

    .post-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .post-content img:hover {
        transform: scale(1.02);
    }

    /* Post Interactions */
    .post-interactions {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color, #e1e8ed);
    }

    .interaction-btn {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 12px;
        border-radius: 20px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .interaction-btn:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: scale(1.05);
    }

    .interaction-btn.liked {
        color: #e74c3c;
    }

    .interaction-btn.liked:hover {
        background: rgba(231, 76, 60, 0.1);
    }

    /* Modal Styles */
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
        max-height: 80vh;
        overflow-y: auto;
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
        border-bottom: 1px solid var(--border-color, #e1e8ed);
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

    .hidden {
        display: none !important;
    }

    /* Comments Section */
    .comments-section {
        max-height: 400px;
        overflow-y: auto;
    }

    .comment-item {
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .comment-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }

    .comment-username {
        font-weight: 600;
        font-size: 14px;
    }

    .comment-time {
        color: #666;
        font-size: 12px;
    }

    .comment-text {
        font-size: 14px;
        line-height: 1.4;
        margin-left: 42px;
    }

    .comment-form {
        padding: 20px 0;
        border-top: 1px solid #eee;
    }

    .comment-input-group {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .comment-input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 10px 15px;
        resize: none;
        min-height: 40px;
        font-size: 14px;
    }

    .comment-submit {
        background: var(--primary-color, #1da1f2);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .comment-submit:hover {
        background: #0d8bd9;
        transform: translateY(-1px);
    }

    .comment-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
    }

    .image-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        max-height: 90%;
        text-align: center;
    }

    .image-modal-content img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .image-modal-close:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-container {
            margin: 10px;
            border-radius: 16px;
        }

        .profile-header {
            padding: 0 20px 20px;
        }

        .profile-name {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .content-area {
            padding: 20px;
        }

        .modal-content {
            margin: 10% auto;
            width: 95%;
        }
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
        }
    }
</style>

<div class="row">
    <div class="col-lg-3 d-none d-lg-block"></div>
    <div class="col-lg-6 col-12">
        <div class="profile-container">
            <!-- Cover Section -->
            <div class="cover-section" onclick="editCover()">
                <div class="cover-edit-overlay">
                    <i class="fas fa-camera me-2"></i> Edit Cover
                </div>
            </div>

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar-container">
                    <div class="avatar" onclick="toggleAvatarMenu(event)">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                        @else
                        <span id="avatarText">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        @endif
                        <div class="avatar-edit-overlay">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>

                    <div class="avatar-dropdown" id="avatarDropdown" style="display: none;">
                        <div class="dropdown-item" onclick="editAvatar()">
                            <i class="fas fa-camera"></i> Change Avatar
                        </div>
                        <div class="dropdown-item" onclick="editProfile()">
                            <i class="fas fa-edit"></i> Edit Profile
                        </div>
                        <div class="dropdown-item" onclick="viewAvatarFullSize()">
                            <i class="fas fa-expand"></i> View Full Size
                        </div>
                    </div>
                </div>

                <div class="profile-info">
                    <h1 class="profile-name" id="profileName">{{ $user->name }}</h1>
                    <p class="profile-username">{{ '@' . $user->username }}</p>
                    <p class="profile-bio" id="profileBio" onclick="editBio()">
                        {!! nl2br(e($user->bio ?? 'Belum ada bio.')) !!}
                        <small class="text-muted d-block mt-2"><i class="fas fa-edit"></i> Klik untuk edit bio</small>
                    </p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" id="postsCount">{{ $posts->count() ?? 0 }}</div>
                        <div class="stat-label">Posts</div>
                    </div>
                    <div class="stat-item" onclick="showFollowers()">
                        <div class="stat-number" id="followersCount">{{ $followersCount ?? 0 }}</div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-item" onclick="showFollowing()">
                        <div class="stat-number" id="followingCount">{{ $followingCount ?? 0 }}</div>
                        <div class="stat-label">Following</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="likesCount">{{ $likesCount ?? 0 }}</div>
                        <div class="stat-label">Likes</div>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="content-tabs">
                <div class="tab-list">
                    <div class="tab-item active" onclick="showTab('posts')">📷 Posts</div>
                    <div class="tab-item" onclick="showTab('media')">🖼️ Media</div>
                    <div class="tab-item" onclick="showTab('likes')">❤️ Likes</div>
                    <div class="tab-item" onclick="showTab('drafts')">📝 Drafts</div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Posts Tab -->
                <div id="posts-content">
                    <div class="posts-stream">
                        @forelse($posts ?? [] as $post)
                        <div class="post-item p-3" data-post-id="{{ $post->id }}">
                            <div class="d-flex align-items-center mb-2">
                                @if($post->user->avatar)
                                <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                    alt="{{ $post->user->name }}"
                                    class="rounded-circle me-2"
                                    style="width:40px; height:40px; object-fit:cover;">
                                @else
                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex justify-content-center align-items-center"
                                    style="width:40px; height:40px;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <strong>{{ $post->user->name }}</strong>
                                    <span class="text-muted">{{ '@' . $post->user->username }}</span>
                                    <span class="text-muted">· {{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                @if(auth()->id() === $post->user_id)
                                <div class="ms-auto position-relative">
                                    <span class="post-menu-btn" onclick="togglePostMenu({{ $post->id }})" style="cursor:pointer;">⋮</span>
                                    <div class="post-menu position-absolute bg-white border rounded shadow" id="postMenu-{{ $post->id }}" style="display:none; right:0; top:20px; z-index:100;">
                                        <div class="post-menu-item px-2 py-1" onclick="deletePost({{ $post->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="post-content mb-2">
                                {!! nl2br(e($post->caption)) !!}
                                @if($post->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $post->image) }}"
                                        alt="Post image"
                                        class="img-fluid rounded"
                                        onclick="openImageModal(this.src)">
                                </div>
                                @endif
                            </div>

                            <!-- Post Interactions -->
                            <div class="post-interactions">
                                <button class="interaction-btn {{ ($post->likes && $post->likes->where('user_id', auth()->id())->count() > 0) ? 'liked' : '' }}"
                                    onclick="toggleLike({{ $post->id }}, this)">
                                    <i class="fas fa-heart" id="like-icon-{{ $post->id }}"></i>
                                    <span id="like-count-{{ $post->id }}">{{ $post->likes ? $post->likes->count() : 0 }}</span>
                                </button>

                                <button class="interaction-btn" onclick="openComments({{ $post->id }})">
                                    <i class="fas fa-comment"></i>
                                    <span>{{ $post->comments_count ?? 0 }}</span>
                                </button>

                                <button class="interaction-btn" onclick="sharePost({{ $post->id }})">
                                    <i class="fas fa-share"></i>
                                    Share
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada postingan. Mulai berbagi sesuatu!</p>
                        </div>
                        @endforelse
                    </div>
                </div>


                <!-- Media Tab -->
                <div id="media-content" class="hidden">
                    @if(isset($posts) && $posts->where('image', '!=', null)->isNotEmpty())
                    <div class="row">
                        @foreach($posts->where('image', '!=', null) as $post)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                    class="card-img-top" alt="Media"
                                    onclick="openImageModal(this.src)"
                                    style="height: 200px; object-fit: cover; cursor: pointer;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada media. Upload foto pertama Anda!</p>
                    </div>
                    @endif
                </div>

                <!-- Likes Tab -->
                <div id="likes-content" class="hidden">
                    @forelse($likedPosts ?? [] as $post)
                    <div class="card shadow-sm rounded mb-3 openPostModal" data-post-id="{{ $post->id }}" style="cursor:pointer;">
                        <div class="d-flex align-items-center p-2">
                            <img src="{{ $post->user->avatar 
                                    ? asset('storage/' . $post->user->avatar) 
                                    : asset('images/default-avatar.png') }}"
                                class="rounded-circle me-2" width="40" height="40" alt="profile">
                            <a href="{{ route('profilePage', ['username' => $post->user->username]) }}"
                                class="fw-bold text-dark text-decoration-none">
                                {{ $post->user->username }}
                            </a>
                        </div>
                        @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Media">
                        @endif
                        <div class="card-body">
                            <p class="mb-1">{{ Str::limit($post->caption, 150) }}</p>
                            <small class="text-muted">{{ $post->likes_count }} Likes</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada postingan yang disukai.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Bootstrap Modal for Post Details -->
                <div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div id="postModalContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Drafts Tab -->
                <div id="drafts-content" class="hidden">
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Draft posts akan muncul di sini</p>
                    </div>
                </div>
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
    <button class="image-modal-close" onclick="closeImageModal()">&times;</button>
    <div class="image-modal-content">
        <img id="modalImage" src="" alt="Full size image">
    </div>
</div>

<script>
    // Initialize comments data
    let postComments = {};
    let cropper = null;
    let scaleX = 1;



    // Post composer functionality
    const postContent = document.getElementById('postContent');
    const charCount = document.getElementById('charCount');
    const postBtn = document.getElementById('postBtn');
    const imageUpload = document.getElementById('imageUpload');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewImage = document.getElementById('previewImage');


    // Fixed JavaScript for likes modal functionality

    document.addEventListener('DOMContentLoaded', function() {
        var postModal = new bootstrap.Modal(document.getElementById('postModal'));
        var postModalContent = document.getElementById('postModalContent');

        // Handle clicking on liked posts to show detail
        document.querySelectorAll('.openPostModal').forEach(function(el) {
            el.addEventListener('click', function() {
                var postId = this.getAttribute('data-post-id');

                // Show loading state
                postModalContent.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading post details...</p>
                </div>
            `;
                postModal.show();

                // Fetch post details
                fetch('/posts/show/' + postId, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Build modal content with post details
                        let modalContent = '';

                        // Post header with user info
                        modalContent += `
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

                        // Post image if exists
                        if (data.post.image) {
                            modalContent += `
                            <div class="mb-3">
                                <img src="/storage/${data.post.image}" class="img-fluid rounded shadow-sm" alt="Post image" style="max-height: 400px; width: 100%; object-fit: cover; cursor: pointer;" onclick="openImageModal('/storage/${data.post.image}')">
                            </div>
                        `;
                        }

                        // Post caption if exists
                        if (data.post.caption) {
                            modalContent += `
                            <div class="mb-3">
                                <p style="white-space: pre-line; font-size: 15px; line-height: 1.5;">${data.post.caption}</p>
                            </div>
                        `;
                        }

                        // Post stats
                        modalContent += `
                        <div class="d-flex justify-content-between align-items-center mb-3 py-2 border-top border-bottom">
                            <div class="d-flex gap-4">
                                <span id="likesCount-${data.post.id}"><i class="fas fa-heart text-danger"></i> ${data.post.likes_count || 0} likes</span>
                                <span id="commentsCount-${data.post.id}"><i class="fas fa-comment text-primary"></i> <span class="comment-count-number">${data.comments ? data.comments.length : 0}</span> comments</span>
                            </div>
                            <small class="text-muted">${new Date(data.post.created_at).toLocaleString()}</small>
                        </div>
                    `;

                        // Like button
                        modalContent += `
                        <div class="mb-3">
                            <button class="btn btn-outline-danger btn-sm ${data.isLiked ? 'active' : ''}" onclick="toggleLikeInModal(${data.post.id}, this)">
                                <i class="fas fa-heart"></i> ${data.isLiked ? 'Unlike' : 'Like'}
                            </button>
                        </div>
                    `;

                        // Comments section
                        modalContent += `
                        <hr>
                        <h6>Comments</h6>
                        <div class="comments-list" id="commentsList-${data.post.id}" style="max-height: 300px; overflow-y: auto;">
                    `;

                        if (data.comments && data.comments.length > 0) {
                            data.comments.forEach(comment => {
                                modalContent += `
                                <div class="d-flex mb-3 comment-item">
                                    ${comment.user.avatar ? 
                                        `<img src="/storage/${comment.user.avatar}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">` :
                                        `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                            ${comment.user.name.charAt(0).toUpperCase()}
                                        </div>`
                                    }
                                    <div class="flex-grow-1">
                                        <div class="bg-light rounded p-2">
                                            <strong class="small">${comment.user.name}</strong>
                                            <p class="mb-0 small">${comment.content}</p>
                                        </div>
                                        <small class="text-muted ms-2">${new Date(comment.created_at).toLocaleString()}</small>
                                    </div>
                                </div>
                            `;
                            });
                        } else {
                            modalContent += '<p class="text-muted text-center py-3" id="noCommentsMsg-${data.post.id}">No comments yet. Be the first to comment!</p>';
                        }

                        modalContent += '</div>';

                        // Comment form
                        modalContent += `
                        <div class="mt-3 border-top pt-3">
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" id="commentInput-${data.post.id}" placeholder="Write a comment..." onkeypress="handleCommentKeyPress(event, ${data.post.id})">
                                <button class="btn btn-primary btn-sm" id="commentBtn-${data.post.id}" onclick="submitCommentFromModal(${data.post.id})">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <div id="commentStatus-${data.post.id}" class="small mt-1" style="display: none;"></div>
                        </div>
                    `;

                        // Update modal content
                        postModalContent.innerHTML = modalContent;
                    })
                    .catch(error => {
                        console.error('Error fetching post details:', error);
                        postModalContent.innerHTML = `
                        <div class="text-center py-4">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p class="mb-0">Failed to load post details. Please try again.</p>
                            </div>
                            <button class="btn btn-secondary btn-sm" onclick="location.reload()">Refresh Page</button>
                        </div>
                    `;
                    });
            });
        });
    });

    // Handle like toggle in modal
    async function toggleLikeInModal(postId, button) {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const isLiked = button.classList.contains('active');

        button.disabled = true;

        try {
            const response = await fetch(
                isLiked ? `/like/destroy/${postId}` : `/like/store/${postId}`, {
                    method: isLiked ? "DELETE" : "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    }
                }
            );

            const result = await response.json();

            if (response.ok) {
                if (!isLiked) {
                    button.classList.add("active");
                    button.innerHTML = '<i class="fas fa-heart"></i> Unlike';
                } else {
                    button.classList.remove("active");
                    button.innerHTML = '<i class="fas fa-heart"></i> Like';
                }

                // Update likes count in modal
                const likesCountElement = document.getElementById(`likesCount-${postId}`);
                if (likesCountElement && result.likes_count !== undefined) {
                    likesCountElement.innerHTML = `<i class="fas fa-heart text-danger"></i> ${result.likes_count} likes`;
                }

                showNotification(isLiked ? "Post unliked" : "Post liked!");
            } else {
                showNotification("Error: " + (result.message || "Something went wrong"));
            }
        } catch (error) {
            console.error('Like error:', error);
            showNotification("Network error occurred");
        } finally {
            button.disabled = false;
        }
    }

    // Handle comment submission from modal - IMPROVED VERSION
    async function submitCommentFromModal(postId) {
        const commentInput = document.getElementById(`commentInput-${postId}`);
        const commentBtn = document.getElementById(`commentBtn-${postId}`);
        const commentStatus = document.getElementById(`commentStatus-${postId}`);
        const commentsList = document.getElementById(`commentsList-${postId}`);
        const noCommentsMsg = document.getElementById(`noCommentsMsg-${postId}`);

        const text = commentInput.value.trim();

        if (!text) {
            showNotification("Comment cannot be empty!");
            return;
        }

        // Show loading state
        commentBtn.disabled = true;
        commentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        commentStatus.className = 'small mt-1 text-info';
        commentStatus.textContent = 'Posting comment...';
        commentStatus.style.display = 'block';

        try {
            const response = await fetch('/comment/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    post_id: postId,
                    content: text
                })
            });

            const result = await response.json();

            console.log('Comment response:', result); // Debug log

            // Check for success - bisa response.ok atau result dengan message success
            if (response.ok && (result.message === "Komentar ditambahkan" || result.data)) {
                // Clear input
                commentInput.value = '';

                // Remove "no comments" message if exists
                if (noCommentsMsg) {
                    noCommentsMsg.remove();
                }

                // Get user data dari response atau gunakan data default
                const userData = result.data?.user || result.user || {
                    name: 'You',
                    avatar: null
                };

                // Add new comment to the DOM immediately
                const newCommentHTML = `
                <div class="d-flex mb-3 comment-item">
                    ${userData.avatar ? 
                        `<img src="/storage/${userData.avatar}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">` :
                        `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                            ${userData.name.charAt(0).toUpperCase()}
                        </div>`
                    }
                    <div class="flex-grow-1">
                        <div class="bg-light rounded p-2">
                            <strong class="small">${userData.name}</strong>
                            <p class="mb-0 small">${result.data?.content || text}</p>
                        </div>
                        <small class="text-muted ms-2">Just now</small>
                    </div>
                </div>
            `;

                commentsList.insertAdjacentHTML('beforeend', newCommentHTML);

                // Update comment count
                const commentCountElement = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
                if (commentCountElement) {
                    const currentCount = parseInt(commentCountElement.textContent) || 0;
                    commentCountElement.textContent = currentCount + 1;
                }

                // Scroll to bottom
                commentsList.scrollTop = commentsList.scrollHeight;

                // Show success
                commentStatus.className = 'small mt-1 text-success';
                commentStatus.textContent = 'Comment posted successfully!';

                // Hide success message after 2 seconds
                setTimeout(() => {
                    commentStatus.style.display = 'none';
                }, 2000);

                showNotification('Comment posted!');

            } else {
                throw new Error(result.message || "Failed to post comment!");
            }
        } catch (error) {
            console.error('Error submitting comment:', error);
            commentStatus.className = 'small mt-1 text-danger';
            commentStatus.textContent = 'Failed to post comment. Please try again.';
            showNotification("Error occurred while posting comment.");
        } finally {
            // Reset button
            commentBtn.disabled = false;
            commentBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }
    }

    // Handle Enter key press in comment input
    function handleCommentKeyPress(event, postId) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            submitCommentFromModal(postId);
        }
    }


    // Character count and post button state
    postContent.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/280`;

        if (length > 280) {
            charCount.classList.add('danger');
            postBtn.classList.remove('active');
        } else if (length > 250) {
            charCount.classList.add('warning');
            charCount.classList.remove('danger');
            postBtn.classList.add('active');
        } else {
            charCount.classList.remove('warning', 'danger');
            if (length > 0) {
                postBtn.classList.add('active');
            } else {
                postBtn.classList.remove('active');
            }
        }
    });



    // Like functionality
    async function toggleLike(postId, button) {
        const icon = document.getElementById(`like-icon-${postId}`);
        const count = document.getElementById(`like-count-${postId}`);
        let currentCount = parseInt(count.textContent);
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const liked = button.classList.contains("liked");

        button.disabled = true;

        try {
            let response = await fetch(
                liked ? `/like/destroy/${postId}` : `/like/store/${postId}`, {
                    method: liked ? "DELETE" : "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    }
                }
            );

            let result = await response.json();

            if (response.ok) {
                if (!liked) {
                    button.classList.add("liked");
                    icon.className = "fas fa-heart";
                    count.textContent = currentCount + 1;
                    showNotification("❤️ Post liked!");
                    createHeartAnimation(button);
                } else {
                    button.classList.remove("liked");
                    icon.className = "fas fa-heart";
                    count.textContent = Math.max(0, currentCount - 1);
                    showNotification("💔 Post unliked");
                }
            } else {
                showNotification(result.message || "⚠️ Error occurred!");
            }
        } catch (error) {
            console.error('Like error:', error);
            showNotification("⚠️ Network error!");
        } finally {
            button.disabled = false;
        }
    }

    function createHeartAnimation(button) {
        const heart = document.createElement("div");
        heart.innerHTML = "❤️";
        heart.style.cssText = `
            position: fixed;
            pointer-events: none;
            font-size: 20px;
            animation: heartFloat 1s ease-out forwards;
            z-index: 1000;
        `;

        if (!document.querySelector("#heart-animation-styles")) {
            const style = document.createElement("style");
            style.id = "heart-animation-styles";
            style.textContent = `
                @keyframes heartFloat {
                    0% { transform: translateY(0) scale(1); opacity: 1; }
                    100% { transform: translateY(-30px) scale(1.5); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        const rect = button.getBoundingClientRect();
        heart.style.left = (rect.left + rect.width / 2 - 10) + "px";
        heart.style.top = (rect.top - 10) + "px";

        document.body.appendChild(heart);
        setTimeout(() => heart.remove(), 1000);
    }

    // Comments functionality - FIXED VERSION
    async function openComments(postId) {
        try {
            const response = await fetch(`/comment/index/${postId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();
            console.log("Comments API result:", result);

            // Handle different response formats
            let comments = [];
            if (result.success && result.data) {
                comments = result.data;
            } else if (result.status === 200 && result.data) {
                comments = result.data;
            } else if (Array.isArray(result)) {
                comments = result;
            } else if (result.comments) {
                comments = result.comments;
            }

            let commentsHtml = '<div class="comments-section">';

            if (comments.length > 0) {
                comments.forEach(comment => {
                    let avatar;
                    if (comment.user?.avatar) {
                        avatar = `<img src="/storage/${comment.user.avatar}" alt="Avatar" class="comment-avatar-img">`;
                    } else {
                        const initial = comment.user?.name?.substring(0, 2).toUpperCase() || "??";
                        avatar = `<div class="comment-avatar-fallback">${initial}</div>`;
                    }

                    commentsHtml += `
                        <div class="comment-item">
                            <div class="comment-header">
                                ${avatar}
                                <span class="comment-username">${comment.user?.name || "Anonymous"}</span>
                                <span class="comment-time">${new Date(comment.created_at).toLocaleString()}</span>
                            </div>
                            <div class="comment-text">${comment.content}</div>
                        </div>
                    `;
                });
            } else {
                commentsHtml += `<p class="text-center text-muted py-3">Belum ada komentar. Jadilah yang pertama!</p>`;
            }

            // Add comment form
            let loginAvatar;
            if (window.appData?.user?.avatar) {
                loginAvatar = `<img src="/storage/${window.appData.user.avatar}" alt="Avatar" class="comment-avatar-img">`;
            } else {
                const initial = window.appData?.user?.name?.substring(0, 2).toUpperCase() || "??";
                loginAvatar = `<div class="comment-avatar-fallback">${initial}</div>`;
            }

            commentsHtml += `
                </div>
                <div class="comment-form">
                    <div class="comment-input-group">
                        ${loginAvatar}
                        <textarea class="comment-input" id="commentInput-${postId}" placeholder="Tulis komentar..." rows="1" onkeypress="handleCommentKeyPress(event, ${postId})"></textarea>
                        <button class="comment-submit" id="commentSubmit-${postId}" onclick="submitComment(${postId})">Post</button>
                    </div>
                    <div id="commentStatus-${postId}" class="mt-2 text-sm" style="display: none;"></div>
                </div>
            `;

            openModal(`Comments (${comments.length})`, commentsHtml);
        } catch (error) {
            console.error("❌ Error fetching comments:", error);
            showNotification("Error loading comments");
        }
    }

    // Handle Enter key in comment input
    function handleCommentKeyPress(event, postId) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            submitComment(postId);
        }
    }

    // Submit comment - IMPROVED VERSION
    async function submitComment(postId) {
        const commentInput = document.getElementById(`commentInput-${postId}`);
        const commentBtn = document.getElementById(`commentSubmit-${postId}`);
        const commentStatus = document.getElementById(`commentStatus-${postId}`);

        const text = commentInput.value.trim();

        if (!text) {
            showNotification("Komentar tidak boleh kosong!");
            return;
        }

        // Show loading state
        const originalBtnText = commentBtn.innerHTML;
        commentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        commentBtn.disabled = true;

        if (commentStatus) {
            commentStatus.className = 'mt-2 text-sm text-info';
            commentStatus.textContent = 'Mengirim komentar...';
            commentStatus.style.display = 'block';
        }

        try {
            const response = await fetch('/comment/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    post_id: postId,
                    content: text
                })
            });

            const result = await response.json();
            console.log('Comment response:', result);

            // Check for various success formats
            const isSuccess = response.ok && (
                result.message === "Komentar ditambahkan" ||
                result.status === 200 ||
                result.success === true ||
                result.data
            );

            if (isSuccess) {
                // Clear input
                commentInput.value = '';

                if (commentStatus) {
                    commentStatus.className = 'mt-2 text-sm text-success';
                    commentStatus.textContent = 'Komentar berhasil dikirim!';
                    setTimeout(() => {
                        commentStatus.style.display = 'none';
                    }, 2000);
                }

                showNotification('💬 Komentar berhasil dikirim!');

                // Refresh comments after 1 second
                setTimeout(() => {
                    openComments(postId);
                }, 1000);

            } else {
                throw new Error(result.message || "Gagal mengirim komentar");
            }

        } catch (error) {
            console.error('❌ Error submitting comment:', error);

            if (commentStatus) {
                commentStatus.className = 'mt-2 text-sm text-danger';
                commentStatus.textContent = 'Gagal mengirim komentar. Silakan coba lagi.';
            }

            showNotification("Terjadi kesalahan saat mengirim komentar");
        } finally {
            // Reset button
            commentBtn.innerHTML = originalBtnText;
            commentBtn.disabled = false;
        }
    }

    // Post management functions
    function togglePostMenu(postId) {
        const menu = document.getElementById('postMenu-' + postId);
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            document.querySelectorAll('.post-menu').forEach(m => m.style.display = 'none');
            menu.style.display = 'block';
        }
    }

    function deletePost(postId) {
        if (confirm('Yakin ingin menghapus post ini?')) {
            fetch(`/posts/destroy/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(res => {
                if (res.ok) {
                    document.querySelector(`[data-post-id="${postId}"]`).remove();
                    showNotification('Post berhasil dihapus');
                } else {
                    showNotification('Gagal menghapus post');
                }
            }).catch(error => {
                console.error('Delete error:', error);
                showNotification('Terjadi kesalahan');
            });
        }
    }

    // Tab functionality
    function showTab(tabName) {
        // Hide all content
        document.querySelectorAll('[id$="-content"]').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show selected content and activate tab
        document.getElementById(tabName + '-content').classList.remove('hidden');
        event.target.classList.add('active');
    }

    // Profile editing functions
    function editProfile() {
        const currentName = document.getElementById('profileName') ?
            document.getElementById('profileName').textContent : '{{ $user->name }}';
        const currentUsername = '{{ $user->username }}';
        const currentBio = document.getElementById('profileBio') ?
            document.getElementById('profileBio').innerHTML
            .replace(/<br>/g, '\n')
            .replace(/<small.*?<\/small>/g, '')
            .trim() : '{{ $user->bio ?? "" }}';

        const content = `
            <form onsubmit="updateProfile(event)">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama</label>
                    <input type="text" class="form-control" name="name" value="${currentName}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control" name="username" value="${currentUsername}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Bio</label>
                    <textarea class="form-control" rows="4" name="bio" maxlength="500">${currentBio}</textarea>
                    <small class="text-muted">Maximum 500 characters</small>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        `;
        openModal('Edit Profile', content);
    }

    function updateProfile(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        fetch("{{ route('profile.update') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const profileNameEl = document.getElementById('profileName');
                    if (profileNameEl) profileNameEl.textContent = formData.get('name');

                    const profileBioEl = document.getElementById('profileBio');
                    if (profileBioEl) profileBioEl.innerHTML =
                        formData.get('bio').replace(/\n/g, '<br>') +
                        '<small class="text-muted d-block mt-2"><i class="fas fa-edit"></i> Klik untuk edit bio</small>';

                    closeModal();
                    showNotification('✅ Profile berhasil diperbarui!');
                } else {
                    showNotification('❌ ' + (data.message || 'Gagal update profile!'));
                }
            })
            .catch(err => {
                console.error('Profile update error:', err);
                showNotification('⚠️ Terjadi error saat menghubungi server!');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    }

    function editBio() {
        const currentBio = document.getElementById('profileBio').innerHTML
            .replace(/<br>/g, '\n')
            .replace(/<small.*?<\/small>/g, '')
            .trim();

        const content = `
            <form onsubmit="updateBio(event)">
                <div class="mb-3">
                    <label class="form-label fw-bold">Edit Bio</label>
                    <textarea class="form-control" rows="4" name="bio" maxlength="500" required>${currentBio}</textarea>
                    <small class="text-muted">Maximum 500 characters</small>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save"></i> Save Bio
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        `;
        openModal('Edit Bio', content);
    }

    function updateBio(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        fetch("{{ route('profile.updateBio') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileBio').innerHTML =
                        formData.get('bio').replace(/\n/g, '<br>') +
                        '<small class="text-muted d-block mt-2"><i class="fas fa-edit"></i> Klik untuk edit bio</small>';
                    closeModal();
                    showNotification('✅ Bio berhasil diperbarui!');
                } else {
                    showNotification('❌ ' + (data.message || 'Gagal update bio!'));
                }
            })
            .catch(error => {
                console.error('Bio update error:', error);
                showNotification('⚠️ Terjadi error saat menghubungi server!');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    }

    // Avatar and Cover functions

    // Modified editAvatar function with crop functionality
    // let scaleX = 1;


    function editAvatar() {
        const content = `
        <form id="avatarUploadForm">
            <div class="mb-3">
                <label class="form-label fw-bold">Upload Avatar</label>
                <input type="file" class="form-control" id="avatarInput" accept="image/*" required>
                <small class="text-muted">Max size: 2MB. Supported formats: JPG, PNG, GIF</small>
            </div>
            
            <!-- Image preview and crop area -->
            <div id="cropContainer" style="display: none;">
                <div class="mb-3">
                    <label class="form-label fw-bold">Crop Avatar</label>
                    <div style="max-height: 400px; overflow: hidden;">
                        <img id="cropImage" style="max-width: 100%;">
                    </div>
                </div>
                
                <!-- Crop controls -->
                <div class="mb-3 text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperZoom(0.1)" title="Zoom In">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperZoom(-0.1)" title="Zoom Out">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperRotate(-45)" title="Rotate Left">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperRotate(45)" title="Rotate Right">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperFlipH()" title="Flip Horizontal">
                            <i class="fas fa-arrows-alt-h"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperReset()" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Preview -->
                <div class="mb-3 text-center">
                    <label class="form-label fw-bold">Preview</label>
                    <div id="cropPreview" style="width: 150px; height: 150px; margin: 0 auto; border-radius: 20px; overflow: hidden; border: 3px solid #ddd;"></div>
                </div>
            </div>
            
            <div class="text-center">
                <button type="button" id="cropAndUploadBtn" class="btn btn-primary me-2" onclick="cropAndUploadAvatar()" style="display: none;">
                    <i class="fas fa-check"></i> Crop & Upload
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeCropModal()">Cancel</button>
            </div>
        </form>
    `;

        openModal('Change Avatar', content);

        // Initialize file input listener
        document.getElementById('avatarInput').addEventListener('change', initCropper);
    }

    // Initialize cropper when image is selected
    function initCropper(e) {
        const file = e.target.files[0];

        if (!file) return;

        // Validate file size
        if (file.size > 2 * 1024 * 1024) {
            showNotification('File terlalu besar! Max 2MB.');
            e.target.value = '';
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            showNotification('Please select an image file.');
            e.target.value = '';
            return;
        }

        // Destroy existing cropper if any
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        // Read and display image
        const reader = new FileReader();
        reader.onload = function(event) {
            const cropContainer = document.getElementById('cropContainer');
            const cropImage = document.getElementById('cropImage');
            const uploadBtn = document.getElementById('cropAndUploadBtn');

            cropImage.src = event.target.result;
            cropContainer.style.display = 'block';
            uploadBtn.style.display = 'inline-block';

            // Initialize Cropper.js
            cropper = new Cropper(cropImage, {
                aspectRatio: 1, // Square crop for avatar
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                preview: '#cropPreview',
                responsive: true,
                ready: function() {
                    // Cropper is ready
                    showNotification('Adjust the crop area as needed');
                }
            });
        };
        reader.readAsDataURL(file);
    }

    // Cropper control functions
    function cropperZoom(ratio) {
        if (cropper) {
            cropper.zoom(ratio);
        }
    }

    function cropperRotate(degree) {
        if (cropper) {
            cropper.rotate(degree);
        }
    }


    function cropperFlipH() {
        if (cropper) {
            scaleX = -scaleX;
            cropper.scaleX(scaleX);
        }
    }

    function cropperReset() {
        if (cropper) {
            cropper.reset();
            scaleX = 1;
        }
    }

    // Crop and upload avatar
    function cropAndUploadAvatar() {
        if (!cropper) {
            showNotification('Please select an image first.');
            return;
        }

        const uploadBtn = document.getElementById('cropAndUploadBtn');
        const originalText = uploadBtn.innerHTML;

        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        uploadBtn.disabled = true;

        // Get cropped canvas
        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            // Create form data
            const formData = new FormData();
            formData.append('avatar', blob, 'avatar.jpg');

            // Upload to server
            fetch('/profile/update-avatar', {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update avatar in UI
                        const avatarImg = `<img src="${data.avatar_url}?t=${Date.now()}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">`;
                        document.querySelector('.avatar').innerHTML = avatarImg + '<div class="avatar-edit-overlay"><i class="fas fa-camera"></i></div>';

                        closeCropModal();
                        showNotification('Avatar berhasil diperbarui!');
                    } else {
                        showNotification(data.message || 'Gagal update avatar!');
                    }
                })
                .catch(error => {
                    console.error('Avatar update error:', error);
                    showNotification('Terjadi error saat upload avatar!');
                })
                .finally(() => {
                    uploadBtn.innerHTML = originalText;
                    uploadBtn.disabled = false;
                });
        }, 'image/jpeg', 0.9); // 90% quality
    }

    // Close crop modal and cleanup
    function closeCropModal() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        scaleX = 1;
        closeModal();
    }

    // Override the default closeModal to cleanup cropper
    const originalCloseModal = closeModal;
    closeModal = function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        scaleX = 1;
        originalCloseModal();
    };



    function previewAvatarImage(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                showNotification('❌ File terlalu besar! Max 2MB.');
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreviewImg').src = e.target.result;
                document.getElementById('avatarPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    // function updateAvatar(event) {
    //     event.preventDefault();
    //     const formData = new FormData(event.target);
    //     const submitBtn = event.target.querySelector('button[type="submit"]');
    //     const originalText = submitBtn.innerHTML;

    //     submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    //     submitBtn.disabled = true;

    //     fetch('/profile/update-avatar', {
    //             method: "POST",
    //             headers: {
    //                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             },
    //             body: formData
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 const avatarImg = `<img src="${data.avatar_url}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">`;
    //                 document.querySelector('.avatar').innerHTML = avatarImg + '<div class="avatar-edit-overlay"><i class="fas fa-camera"></i></div>';
    //                 closeModal();
    //                 showNotification('✅ Avatar berhasil diperbarui!');
    //             } else {
    //                 showNotification('❌ ' + (data.message || 'Gagal update avatar!'));
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Avatar update error:', error);
    //             showNotification('⚠️ Terjadi error saat upload avatar!');
    //         })
    //         .finally(() => {
    //             submitBtn.innerHTML = originalText;
    //             submitBtn.disabled = false;
    //         });
    // }

    function editCover() {
        const content = `
            <form onsubmit="updateCover(event)" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Upload Cover Image</label>
                    <input type="file" class="form-control" name="cover" accept="image/*" required onchange="previewCoverImage(event)">
                    <small class="text-muted">Max size: 5MB. Recommended size: 900x200px.</small>
                </div>
                <div class="mb-3">
                    <div id="coverPreview" class="text-center" style="display: none;">
                        <img id="coverPreviewImg" src="" alt="Cover Preview" style="width: 100%; max-width: 400px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-upload"></i> Upload Cover
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        `;
        openModal('Change Cover Image', content);
    }

    function previewCoverImage(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                showNotification('❌ File terlalu besar! Max 5MB.');
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreviewImg').src = e.target.result;
                document.getElementById('coverPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    function updateCover(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        submitBtn.disabled = true;

        fetch('/profile/update-cover', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const coverSection = document.querySelector('.cover-section');
                    coverSection.style.background = `url('${data.cover_url}') center/cover`;
                    closeModal();
                    showNotification('✅ Cover image berhasil diperbarui!');
                } else {
                    showNotification('❌ ' + (data.message || 'Gagal update cover!'));
                }
            })
            .catch(error => {
                console.error('Cover update error:', error);
                showNotification('⚠️ Terjadi error saat upload cover!');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    }

    // Other utility functions
    function sharePost(postId) {
        if (navigator.share) {
            navigator.share({
                title: 'Check out this post!',
                text: 'Amazing post on Telava',
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            showNotification('🔗 Link copied to clipboard!');
        }
    }

    function showFollowers() {
        const followers = window.appData.followers || [];
        if (!followers.length) {
            openModal('Followers', '<p class="text-center text-muted">Belum ada followers.</p>');
            return;
        }
        // Add followers display logic here
        openModal('Followers', `<p>Followers (${followers.length})</p>`);
    }

    function showFollowing() {
        const following = window.appData.following || [];
        if (!following.length) {
            openModal('Following', '<p class="text-center text-muted">Belum mengikuti siapa pun.</p>');
            return;
        }
        // Add following display logic here
        openModal('Following', `<p>Following (${following.length})</p>`);
    }

    function toggleAvatarMenu(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('avatarDropdown');
        if (dropdown.style.display === 'none' || !dropdown.style.display) {
            closeAllDropdowns();
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.avatar-dropdown').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    }

    function viewAvatarFullSize() {
        const avatar = document.querySelector('.avatar img');
        if (avatar) {
            openImageModal(avatar.src);
        } else {
            showNotification('Belum ada avatar untuk ditampilkan');
        }
        closeAllDropdowns();
    }

    // Image modal functions
    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Modal functions
    function openModal(title, content) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-body').innerHTML = content;
        document.getElementById('modal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'position-fixed bg-dark text-white px-3 py-2 rounded-pill';
        notification.style.cssText = `
            top: 90px;
            right: 20px;
            z-index: 3000;
            animation: slideInRight 0.3s ease;
            font-size: 14px;
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
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Event listeners
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        const imageModal = document.getElementById('imageModal');
        if (event.target === modal) closeModal();
        if (event.target === imageModal) closeImageModal();
        if (!event.target.closest('.avatar-container')) closeAllDropdowns();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeImageModal();
            closeAllDropdowns();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.querySelectorAll('.post-item').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }, 300);
    });
</script>

@endsection