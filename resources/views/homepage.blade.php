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


        /* Stories/Highlights Section */
        .highlights-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .highlights-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .highlights-header h6 {
            margin: 0;
            font-weight: 700;
            color: var(--text-color);
        }

        .highlights-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .highlights-container::-webkit-scrollbar {
            height: 4px;
        }

        .highlights-container::-webkit-scrollbar-track {
            background: #f7f9fa;
            border-radius: 4px;
        }

        .highlights-container::-webkit-scrollbar-thumb {
            background: #657786;
            border-radius: 4px;
        }

        .highlight-item {
            min-width: 80px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .highlight-item:hover {
            transform: translateY(-3px);
        }

        .highlight-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            padding: 3px;
            margin-bottom: 8px;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .highlight-avatar.viewed {
            border-color: #657786;
        }

        .highlight-avatar img,
        .highlight-avatar .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-placeholder {
            background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .highlight-label {
            font-size: 12px;
            color: var(--text-color);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 80px;
        }

        .add-highlight {
            border: 2px dashed var(--border-color);
            background: #f7f9fa;
            color: #657786;
        }

        .add-highlight:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
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
            /* background: linear-gradient(135deg, var(--primary-color), #0d8bd9); */
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

        /* Feed Posts - Menggunakan style yang sudah ada dari layout */
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
            /* cegah flex grow */
            min-height: 48px;
            /* cegah ketarik */
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

        /* Sidebar Widgets - Menggunakan class yang sudah ada */
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
                    <div class="highlights-header">
                        <h6><i class="fas fa-star text-primary"></i> Stories</h6>
                        <small class="text-muted">Tap to view</small>
                    </div>
                    <div class="highlights-container">
                        <div class="highlight-item">
                            <div class="highlight-avatar add-highlight">
                                <div class="avatar-placeholder">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="highlight-label">Your Story</div>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-avatar">
                                <div class="avatar-placeholder"
                                    style="background: linear-gradient(135deg, #ff6b6b, #4ecdc4);">SC</div>
                            </div>
                            <div class="highlight-label">Sarah Chen</div>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-avatar viewed">
                                <div class="avatar-placeholder"
                                    style="background: linear-gradient(135deg, #667eea, #764ba2);">AS</div>
                            </div>
                            <div class="highlight-label">Alex Smith</div>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-avatar">
                                <div class="avatar-placeholder"
                                    style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">MJ</div>
                            </div>
                            <div class="highlight-label">Maria Jose</div>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-avatar viewed">
                                <div class="avatar-placeholder"
                                    style="background: linear-gradient(135deg, #ffa726, #ff5722);">DK</div>
                            </div>
                            <div class="highlight-label">David Kim</div>
                        </div>
                    </div>
                </div>

                <div class="post-composer">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
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
                            <img id="previewImage" src="" alt="Preview">
                            <button type="button" class="remove-image" onclick="removeImage()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <input type="file" class="file-upload" id="imageUpload" name="image" accept="image/*"
                            onchange="handleImageUpload(event)">

                        <div class="composer-tools">
                            <div class="composer-options">
                                <button type="button" class="composer-option" onclick="triggerImageUpload()"
                                    title="Tambah foto">
                                    <i class="fas fa-image"></i>
                                </button>
                                <button type="button" class="composer-option" title="Tambah GIF">
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
                    <!-- Posts will be rendered here -->
                    @forelse($posts as $post)
                        <div class="post-card card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="post-avatar me-3">
                                        @if($post->user->avatar)
                                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar">
                                        @else
                                            <img src="https://picsum.photos/48/48?random=" alt="Avatar">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <strong class="me-2">{{ $post->user->name }}</strong>
                                            <span class="text-muted">{{ '@' . $post->user->username }}</span>
                                            <span class="text-muted ms-2">• {{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mb-3">
                                            {{ $post->caption }}
                                        </p>
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post" class="post-image mb-3" />
                                        @endif
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-light">
                                                <i class="far fa-comment"></i> {{ $post->comments_count ?? 0 }}
                                            </button>
                                            <button class="btn btn-sm btn-light">
                                                <i class="fas fa-retweet"></i> {{ $post->shares_count ?? 0 }}
                                            </button>
                                            {{-- <button
                                                class="btn btn-sm like-btn {{ $post->is_liked_by_auth_user ? 'liked' : '' }}"
                                                onclick="toggleLike({{ $post->id }})" data-post-id="{{ $post->id }}">
                                                <i class="{{ $post->is_liked_by_auth_user ? 'fas' : 'far' }} fa-heart"></i>
                                                <span id="like-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                                            </button> --}}

                                            <button class="btn btn-sm like-btn" onclick="toggleLike(this)"
                                                data-post-id="{{ $post->id }}"
                                                data-liked="{{ $post->is_liked_by_auth_user ? 'true' : 'false' }}">
                                                <i
                                                    class="fa fa-heart {{ $post->is_liked_by_auth_user ? 'text-danger' : 'text-secondary' }}"></i>
                                                <span class="like-count">{{ $post->likes_count }}</span>
                                            </button>


                                            <button class="btn btn-sm btn-light">
                                                <i class="fas fa-share"></i>
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
            <!-- Right Sidebar menggunakan class yang sudah ada -->
            <div class="trending-sidebar">
                <h6><i class="fas fa-fire text-primary"></i> Trending</h6>
                <div class="trending-item">
                    <div class="trending-topic">#TechIndonesia</div>
                    <div class="trending-count">15.2K Tweets</div>
                </div>
                <div class="trending-item">
                    <div class="trending-topic">#CodeLife</div>
                    <div class="trending-count">8.7K Tweets</div>
                </div>
                <div class="trending-item">
                    <div class="trending-topic">#WebDeveloper</div>
                    <div class="trending-count">5.3K Tweets</div>
                </div>
                <div class="trending-item">
                    <div class="trending-topic">#StartupLife</div>
                    <div class="trending-count">3.8K Tweets</div>
                </div>
            </div>

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

            <div class="trending-sidebar">
                <h6><i class="fas fa-chart-line text-primary"></i> What's happening</h6>
                <div class="trending-item">
                    <small class="text-muted">Trending in Technology</small>
                    <div class="trending-topic">ChatGPT Updates</div>
                    <div class="trending-count">25.8K Tweets</div>
                </div>
                <div class="trending-item">
                    <small class="text-muted">Trending in Sports</small>
                    <div class="trending-topic">World Cup 2024</div>
                    <div class="trending-count">18.3K Tweets</div>
                </div>
                <div class="trending-item">
                    <small class="text-muted">Entertainment</small>
                    <div class="trending-topic">New Movie Release</div>
                    <div class="trending-count">12.1K Tweets</div>
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

    <script>
        // Feed data
        // let feedPosts = [
        //     {
        //         id: 1,
        //         user: { name: "Sarah Chen", username: "sarahchen", avatar: "SC", gradient: "linear-gradient(135deg, #ff6b6b, #4ecdc4)" },
        //         content: "Just finished building my first React Native app! 🚀 The learning curve was steep but so rewarding. Mobile development is definitely the future.",
        //         timestamp: new Date(Date.now() - 1 * 60 * 60 * 1000),
        //         likes: 45,
        //         comments: 8,
        //         shares: 3,
        //         liked: false,
        //         hasImage: false
        //     },
        //     {
        //         id: 2,
        //         user: { name: "Alex Smith", username: "alexsmith", avatar: "AS", gradient: "linear-gradient(135deg, #667eea, #764ba2)" },
        //         content: "Beautiful sunset from my home office today 🌅 Sometimes the best inspiration comes from taking a moment to appreciate nature.",
        //         timestamp: new Date(Date.now() - 3 * 60 * 60 * 1000),
        //         likes: 128,
        //         comments: 24,
        //         shares: 12,
        //         liked: true,
        //         hasImage: true,
        //         imageUrl: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop"
        //     },
        //     {
        //         id: 3,
        //         user: { name: "Maria Jose", username: "mariajose", avatar: "MJ", gradient: "linear-gradient(135deg, #4ecdc4, #44a08d)" },
        //         content: "Coffee and code - the perfect combination for a productive morning ☕️💻 What does your workspace look like? #DevLife #MorningMotivation",
        //         timestamp: new Date(Date.now() - 5 * 60 * 60 * 1000),
        //         likes: 67,
        //         comments: 15,
        //         shares: 8,
        //         liked: false,
        //         hasImage: true,
        //         imageUrl: "https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=600&h=400&fit=crop"
        //     },
        //     {
        //         id: 4,
        //         user: { name: "David Kim", username: "davidkim", avatar: "DK", gradient: "linear-gradient(135deg, #ffa726, #ff5722)" },
        //         content: "Excited to announce that our startup just secured Series A funding! 🎉 Thank you to everyone who believed in our vision. This is just the beginning!",
        //         timestamp: new Date(Date.now() - 8 * 60 * 60 * 1000),
        //         likes: 234,
        //         comments: 56,
        //         shares: 89,
        //         liked: true,
        //         hasImage: false
        //     },
        //     {
        //         id: 5,
        //         user: { name: "Lisa Wang", username: "lisawang", avatar: "LW", gradient: "linear-gradient(135deg, #a8edea, #fed6e3)" },
        //         content: "Working on some UI designs for a new project. Really loving these color combinations! 🎨 What do you think about the trend towards more vibrant interfaces?",
        //         timestamp: new Date(Date.now() - 12 * 60 * 60 * 1000),
        //         likes: 91,
        //         comments: 23,
        //         shares: 14,
        //         liked: false,
        //         hasImage: true,
        //         imageUrl: "https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=600&h=400&fit=crop"
        //     }
        // ];

        let postIdCounter = 6;

        // Post composer functionality
        const postContent = document.getElementById('postContent');
        const charCount = document.getElementById('charCount');
        const postBtn = document.getElementById('postBtn');
        const imageUpload = document.getElementById('imageUpload');
        const uploadPreview = document.getElementById('uploadPreview');
        const previewImage = document.getElementById('previewImage');

        // Character count and post button state
        postContent.addEventListener('input', function () {
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

        // Auto-resize textarea
        postContent.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Image upload functionality
        function triggerImageUpload() {
            imageUpload.click();
        }

        function handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    uploadPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            uploadPreview.classList.add('hidden');
            imageUpload.value = '';
            previewImage.src = '';
        }

        // Create new post
        function createPost() {
            const content = postContent.value.trim();
            if (content.length === 0 || content.length > 280) return;

            const hasImage = !uploadPreview.classList.contains('hidden');
            const newPost = {
                id: postIdCounter++,
                user: { name: "John Doe", username: "johndoe", avatar: "JD", gradient: "linear-gradient(135deg, #1da1f2, #0d8bd9)" },
                content: content,
                timestamp: new Date(),
                likes: 0,
                comments: 0,
                shares: 0,
                liked: false,
                hasImage: hasImage,
                imageUrl: hasImage ? previewImage.src : null
            };

            feedPosts.unshift(newPost);

            // Clear composer
            postContent.value = '';
            postContent.style.height = 'auto';
            removeImage();
            charCount.textContent = '0/280';
            charCount.classList.remove('warning', 'danger');
            postBtn.classList.remove('active');

            // Re-render feed
            renderFeed();
            showNotification('📝 Post berhasil dipublikasikan!');
        }

        // Render feed posts
        // function renderFeed() {
        //     const container = document.getElementById('feedContainer');
        //     container.innerHTML = '';

        //     feedPosts.forEach((post, index) => {
        //         const postElement = document.createElement('div');
        //         postElement.className = 'post-card';
        //         postElement.style.animationDelay = `${index * 0.1}s`;

        //         const timeAgo = getTimeAgo(post.timestamp);

        //         postElement.innerHTML = `
        //                 <div class="post-header">
        //                     <div class="post-avatar" style="background: ${post.user.gradient};" onclick="viewProfile('${post.user.username}')">
        //                         ${post.user.avatar}
        //                     </div>
        //                     <div class="post-user-info flex-grow-1">
        //                         <h6>${post.user.name} <small class="fw-normal">@${post.user.username} · ${timeAgo}</small></h6>
        //                     </div>
        //                     <div class="dropdown">
        //                         <button class="btn btn-sm" data-bs-toggle="dropdown">
        //                             <i class="fas fa-ellipsis-h text-muted"></i>
        //                         </button>
        //                         <ul class="dropdown-menu">
        //                             <li><a class="dropdown-item" href="#" onclick="editPost(${post.id})"><i class="fas fa-edit me-2"></i>Edit</a></li>
        //                             <li><a class="dropdown-item" href="#" onclick="deletePost(${post.id})"><i class="fas fa-trash me-2"></i>Delete</a></li>
        //                             <li><hr class="dropdown-divider"></li>
        //                             <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Report</a></li>
        //                         </ul>
        //                     </div>
        //                 </div>

        //                 <div class="post-content">
        //                     ${post.content}
        //                 </div>

        //                 ${post.hasImage ? `
        //                     <div class="post-media">
        //                         <img src="${post.imageUrl}" alt="Post image" onclick="openImageModal('${post.imageUrl}')">
        //                     </div>
        //                 ` : ''}

        //                 <div class="post-actions">
        //                     <button class="action-btn ${post.liked ? 'liked' : ''}" onclick="toggleLike(${post.id})">
        //                         <i class="fas fa-heart"></i>
        //                         <span>${post.likes}</span>
        //                     </button>
        //                     <button class="action-btn" onclick="showComments(${post.id})">
        //                         <i class="fas fa-comment"></i>
        //                         <span>${post.comments}</span>
        //                     </button>
        //                     <button class="action-btn" onclick="sharePost(${post.id})">
        //                         <i class="fas fa-retweet"></i>
        //                         <span>${post.shares}</span>
        //                     </button>
        //                     <button class="action-btn" onclick="bookmarkPost(${post.id})">
        //                         <i class="fas fa-bookmark"></i>
        //                     </button>
        //                 </div>
        //             `;

        //         container.appendChild(postElement);
        //     });
        // }

        // Post interactions
        async function toggleLike(btn) {
            const postId = btn.dataset.postId;
            const isLiked = btn.dataset.liked === "true";
            const icon = btn.querySelector("i");
            const countSpan = btn.querySelector(".like-count");
            let count = parseInt(countSpan.textContent);

            try {
                if (!isLiked) {
                    await fetch(`/like/store/${postId}`, {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                    });
                    btn.dataset.liked = "true";
                    icon.classList.remove("text-secondary");
                    icon.classList.add("text-danger");
                    countSpan.textContent = count + 1;
                } else {
                    await fetch(`/like/destroy/${postId}`, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                    });
                    btn.dataset.liked = "false";
                    icon.classList.remove("text-danger");
                    icon.classList.add("text-secondary");
                    countSpan.textContent = count - 1;
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }



        function showComments(postId) {
            const post = feedPosts.find(p => p.id === postId);
            if (!post) return;

            const content = `
                    <div class="text-center">
                        <h5>Komentar</h5>
                        <div class="mt-4">
                            <div class="d-flex gap-3 mb-3">
                                <div class="composer-avatar" style="width: 40px; height: 40px; font-size: 14px;">SC</div>
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-3">
                                        <strong>Sarah Chen</strong>
                                        <p class="mb-0">Post yang bagus! Sangat menginspirasi melihat progress kamu.</p>
                                    </div>
                                    <small class="text-muted">2 jam yang lalu</small>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mb-3">
                                <div class="composer-avatar" style="width: 40px; height: 40px; font-size: 14px; background: linear-gradient(135deg, #667eea, #764ba2);">AS</div>
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-3">
                                        <strong>Alex Smith</strong>
                                        <p class="mb-0">Terima kasih sudah share ini! Insight yang sangat membantu.</p>
                                    </div>
                                    <small class="text-muted">1 jam yang lalu</small>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="composer-avatar" style="width: 40px; height: 40px; font-size: 14px;">JD</div>
                                <div class="flex-grow-1">
                                    <textarea class="form-control" placeholder="Tulis komentar..." rows="2"></textarea>
                                    <button class="btn btn-primary btn-sm mt-2">Kirim Komentar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            openModal('Komentar', content);
        }

        function sharePost(postId) {
            const post = feedPosts.find(p => p.id === postId);
            if (!post) return;

            post.shares++;
            renderFeed();
            showNotification('🔄 Post dibagikan!');
        }

        function bookmarkPost(postId) {
            showNotification('🔖 Post disimpan!');
        }

        function editPost(postId) {
            const post = feedPosts.find(p => p.id === postId);
            if (!post) return;

            const content = `
                    <form onsubmit="updatePost(event, ${postId})">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Edit Post</label>
                            <textarea class="form-control" rows="4" name="content" required>${post.content}</textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save"></i> Update Post
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                        </div>
                    </form>
                `;
            openModal('Edit Post', content);
        }

        function updatePost(event, postId) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const content = formData.get('content');

            const post = feedPosts.find(p => p.id === postId);
            if (post) {
                post.content = content;
                renderFeed();
                closeModal();
                showNotification('✅ Post berhasil diperbarui!');
            }
        }

        function deletePost(postId) {
            if (confirm('Yakin ingin menghapus post ini?')) {
                feedPosts = feedPosts.filter(p => p.id !== postId);
                renderFeed();
                showNotification('🗑️ Post berhasil dihapus!');
            }
        }

        function viewProfile(username) {
            showNotification(`👤 Melihat profil @${username}`);
        }

        function openImageModal(imageUrl) {
            const content = `
                        <div class="text-center">
                            <img src="${imageUrl}" alt="Gambar ukuran penuh" style="width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px;">
                        </div>
                    `;
            openModal('Gambar', content);
        }

        // Time ago helper function
        function getTimeAgo(timestamp) {
            const now = new Date();
            const diff = now - timestamp;
            const seconds = Math.floor(diff / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (days > 0) return `${days}h`;
            if (hours > 0) return `${hours}j`;
            if (minutes > 0) return `${minutes}m`;
            return 'sekarang';
        }

        // Modal functionality
        function openModal(title, content) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-body').innerHTML = content;
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
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

            // Add animation keyframes if not exists
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

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('modal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            renderFeed();

            // Add staggered entrance animations
            setTimeout(() => {
                document.querySelectorAll('.post-card').forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 150);
                });
            }, 300);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                if (postBtn.classList.contains('active')) {
                    createPost();
                }
            }
        });

        // Infinite scroll simulation
        let isLoading = false;

        window.addEventListener('scroll', function () {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000 && !isLoading) {
                loadMorePosts();
            }
        });

        function loadMorePosts() {
            isLoading = true;

            // Simulate loading delay
            setTimeout(() => {
                const morePosts = [
                    {
                        id: postIdCounter++,
                        user: { name: "Emma Wilson", username: "emmawilson", avatar: "EW", gradient: "linear-gradient(135deg, #f093fb, #f5576c)" },
                        content: "Baru saja deploy project terbaru ke production! 🚀 Tidak ada yang mengalahkan perasaan ketika semuanya berjalan dengan sempurna. Saatnya rayakan dengan pizza! 🍕",
                        timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000),
                        likes: 34,
                        comments: 7,
                        shares: 2,
                        liked: false,
                        hasImage: false
                    },
                    {
                        id: postIdCounter++,
                        user: { name: "James Brown", username: "jamesbrown", avatar: "JB", gradient: "linear-gradient(135deg, #43e97b, #38f9d7)" },
                        content: "Sesi coding weekend dengan pemandangan yang luar biasa ini! 🏔️ Kadang kantor terbaik adalah di manapun kamu bisa setup laptop.",
                        timestamp: new Date(Date.now() - 26 * 60 * 60 * 1000),
                        likes: 89,
                        comments: 18,
                        shares: 12,
                        liked: true,
                        hasImage: true,
                        imageUrl: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop"
                    }
                ];

                feedPosts.push(...morePosts);
                renderFeed();
                isLoading = false;

                showNotification('📱 Post baru dimuat!');
            }, 1000);
        }

    </script>

@endsection