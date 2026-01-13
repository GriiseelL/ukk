<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Flipside - Telava</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #16213e 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            overflow-x: hidden;
        }

        .flipside-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-container {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
            animation: slideUp 0.6s ease-out;
            border: 1px solid rgba(102, 126, 234, 0.2);
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

        /* Flipside Header */
        .flipside-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 30px;
            background: rgba(102, 126, 234, 0.15);
            border-bottom: 2px solid rgba(102, 126, 234, 0.3);
            backdrop-filter: blur(10px);
        }

        .back-to-normal-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .back-to-normal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .flipside-mode-badge {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: badgeGlow 2s ease-in-out infinite;
        }

        @keyframes badgeGlow {

            0%,
            100% {
                box-shadow: 0 4px 15px rgba(238, 90, 111, 0.4);
            }

            50% {
                box-shadow: 0 4px 25px rgba(238, 90, 111, 0.7);
            }
        }

        /* Cover Section */
        .cover-section {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            animation: gradientMove 8s ease infinite;
            position: relative;
        }

        @keyframes gradientMove {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        /* Avatar */
        .profile-header {
            padding: 0 30px 20px;
            position: relative;
            margin-top: -50px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            border: 4px solid #1a1a2e;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.05);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            margin-top: 15px;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 5px;
        }

        .profile-username {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 15px;
        }

        .profile-bio {
            font-size: 15px;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Stats */
        .stats-section {
            padding: 20px 30px;
            background: rgba(102, 126, 234, 0.05);
            border-top: 1px solid rgba(102, 126, 234, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            background: rgba(102, 126, 234, 0.2);
        }

        .stat-number {
            font-size: 20px;
            font-weight: 800;
            color: white;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Content Tabs */
        .content-tabs {
            padding: 0 30px;
            background: rgba(102, 126, 234, 0.05);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
        }

        .tab-list {
            display: flex;
            gap: 0;
            overflow-x: auto;
        }

        .tab-item {
            padding: 15px 20px;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.6);
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            font-weight: 600;
            white-space: nowrap;
        }

        .tab-item.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab-item:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        /* Content Area */
        .content-area {
            padding: 30px;
            min-height: 300px;
        }

        .post-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            margin-bottom: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
        }

        .post-item:hover {
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(102, 126, 234, 0.4);
        }

        .post-flipside-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-interactions {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(102, 126, 234, 0.2);
        }

        .interaction-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
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
            background: rgba(102, 126, 234, 0.2);
            transform: scale(1.05);
            color: white;
        }

        .interaction-btn.liked {
            color: #e74c3c;
        }

        .hidden {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .flipside-container {
                padding: 10px;
            }

            .flipside-header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 10px;
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
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            margin-bottom: 20px;
        }

        .empty-state strong {
            color: #667eea;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>

<body>
    @php
    $mappedFollowers = $followers->map(function($f) {
    return [
    'id' => $f->follower->id ?? null,
    'name' => $f->follower->name ?? '',
    'username' => $f->follower->username ?? '',
    'avatar' => $f->follower->avatar ?? null,
    ];
    });

    $mappedFollowing = $following->map(function($f) {
    return [
    'id' => $f->following->id ?? null,
    'name' => $f->following->name ?? '',
    'username' => $f->following->username ?? '',
    'avatar' => $f->following->avatar ?? null,
    ];
    });
    @endphp

    <script>
        window.appData = {
            followers: @json($mappedFollowers),
            following: @json($mappedFollowing),
            followingIds: @json($followingIds ?? []),
            posts: @json($flipsidePosts ?? []),
            user: @json($user ?? [])
        };
    </script>

    <div class="flipside-container">
        <div class="profile-container">
            <!-- Flipside Header -->
            <div class="flipside-header">
                <button class="back-to-normal-btn" onclick="window.location.href='/profile'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Profile</span>
                </button>
                <div class="flipside-mode-badge">
                    <span>🔥</span>
                    <span>FLIPSIDE MODE</span>
                </div>
            </div>

            <!-- Cover Section -->
            <div class="cover-section"></div>

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar">
                    @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                    @else
                    <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    @endif
                </div>

                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-username">{{ '@' . $user->username }}</p>
                    @if($user->bio)
                    <p class="profile-bio">{!! nl2br(e($user->bio)) !!}</p>
                    @endif
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ $flipsidePosts->count() ?? 0 }}</div>
                        <div class="stat-label">Flipside Posts</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $followersCount ?? 0 }}</div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $followingCount ?? 0 }}</div>
                        <div class="stat-label">Following</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $likesCount ?? 0 }}</div>
                        <div class="stat-label">Likes</div>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="content-tabs">
                <div class="tab-list">
                    <div class="tab-item active" onclick="showTab('posts')">
                        🔥 Flipside Posts
                    </div>
                    <div class="tab-item" onclick="showTab('media')">
                        🖼️ Media
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Posts Tab -->
                <div id="posts-content">
                    @forelse($flipsidePosts ?? [] as $post)
                    <div class="post-item">
                        <!-- Flipside Badge -->
                        <span class="post-flipside-badge">
                            🔒 Flipside
                        </span>

                        <div class="d-flex align-items-center mb-3">
                            @if($post->user->avatar)
                            <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                class="rounded-circle me-2"
                                style="width:40px; height:40px; object-fit:cover;">
                            @else
                            <div class="rounded-circle me-2 d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px; background: #667eea; color: white; font-weight: bold;">
                                {{ strtoupper(substr($post->user->name, 0, 2)) }}
                            </div>
                            @endif
                            <div>
                                <strong style="color: white;">{{ $post->user->name }}</strong>
                                <span style="color: rgba(255,255,255,0.6);">{{ '@' . $post->user->username }}</span>
                                <span style="color: rgba(255,255,255,0.5);">· {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div style="color: rgba(255,255,255,0.9);">
                            {!! nl2br(e($post->caption)) !!}
                            @if($post->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                    class="img-fluid rounded"
                                    style="max-width: 100%; border-radius: 12px;">
                            </div>
                            @endif
                        </div>

                        <!-- Post Interactions -->
                        <div class="post-interactions">
                            <button class="interaction-btn {{ ($post->likes && $post->likes->where('user_id', auth()->id())->count() > 0) ? 'liked' : '' }}"
                                onclick="toggleLike({{ $post->id }}, this)">
                                <i class="fas fa-heart"></i>
                                <span>{{ $post->likes ? $post->likes->count() : 0 }}</span>
                            </button>
                            <button class="interaction-btn">
                                <i class="fas fa-comment"></i>
                                <span>{{ $post->comments_count ?? 0 }}</span>
                            </button>
                            <button class="interaction-btn">
                                <i class="fas fa-share"></i>
                                Share
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-ghost"></i>
                        <p>
                            Belum ada konten Flipside.<br>
                            <strong>Post something secret!</strong> 🤫
                        </p>
                        <button class="btn btn-primary" onclick="window.location.href='/tweet'">
                            <i class="fas fa-plus"></i> Buat Post Flipside
                        </button>
                    </div>
                    @endforelse
                </div>

                <!-- Media Tab -->
                <div id="media-content" class="hidden">
                    @if(isset($flipsidePosts) && $flipsidePosts->where('image', '!=', null)->isNotEmpty())
                    <div class="row">
                        @foreach($flipsidePosts->where('image', '!=', null) as $post)
                        <div class="col-md-4 mb-3">
                            <div style="background: rgba(102, 126, 234, 0.1); border: 1px solid rgba(102, 126, 234, 0.2); border-radius: 12px; overflow: hidden;">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                    alt="Media"
                                    style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-image"></i>
                        <p>Belum ada media di Flipside.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TAMBAHKAN KE flipside.blade.php -->
    <!-- Sebelum penutup </body> -->
    <!-- ============================================ -->

    <!-- Modal Manage Close Friends -->
    <div class="modal fade" id="closeFriendsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background: #1a1a2e; border: 1px solid rgba(102, 126, 234, 0.3);">
                <div class="modal-header" style="border-bottom: 1px solid rgba(102, 126, 234, 0.2);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-user-friends me-2"></i>
                        Manage Close Friends
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                    <!-- Search Box -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text" style="background: rgba(102, 126, 234, 0.1); border: 1px solid rgba(102, 126, 234, 0.3); color: rgba(255,255,255,0.7);">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                id="searchCloseFriends"
                                class="form-control"
                                placeholder="Search followers..."
                                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(102, 126, 234, 0.3); color: white;"
                                onkeyup="filterCloseFriends()">
                        </div>
                    </div>

                    <!-- Info Banner -->
                    <div class="alert alert-info" style="background: rgba(102, 126, 234, 0.15); border: 1px solid rgba(102, 126, 234, 0.3); color: rgba(255,255,255,0.9);">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Select followers who can see your Flipside posts. Only they will have access to your private content.</small>
                    </div>

                    <!-- Selected Count -->
                    <div class="mb-3 p-3" style="background: rgba(102, 126, 234, 0.1); border-radius: 12px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: rgba(255,255,255,0.8);">
                                <i class="fas fa-check-circle me-2" style="color: #4ade80;"></i>
                                Selected Close Friends
                            </span>
                            <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); font-size: 14px; padding: 6px 12px;">
                                <span id="closeFriendsCount">0</span> / {{ $followersCount ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <!-- Followers List -->
                    <div id="closeFriendsList">
                        @forelse($followers ?? [] as $follower)
                        <div class="close-friend-item mb-2 p-3"
                            data-user-id="{{ $follower->follower->id }}"
                            data-username="{{ strtolower($follower->follower->username) }}"
                            data-name="{{ strtolower($follower->follower->name) }}"
                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(102, 126, 234, 0.2); border-radius: 12px; transition: all 0.3s ease; cursor: pointer;"
                            onmouseover="this.style.background='rgba(102, 126, 234, 0.15)'; this.style.borderColor='rgba(102, 126, 234, 0.4)';"
                            onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(102, 126, 234, 0.2)';">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Avatar -->
                                    @if($follower->follower->avatar)
                                    <img src="{{ asset('storage/' . $follower->follower->avatar) }}"
                                        class="rounded-circle"
                                        style="width: 50px; height: 50px; object-fit: cover; border: 2px solid rgba(102, 126, 234, 0.3);">
                                    @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; font-weight: bold; font-size: 18px; border: 2px solid rgba(102, 126, 234, 0.3);">
                                        {{ strtoupper(substr($follower->follower->name, 0, 2)) }}
                                    </div>
                                    @endif

                                    <!-- User Info -->
                                    <div>
                                        <h6 class="mb-0 text-white fw-bold">{{ $follower->follower->name }}</h6>
                                        <small style="color: rgba(255,255,255,0.6);">@{{ $follower->follower->username }}</small>
                                    </div>
                                </div>

                                <!-- Toggle Button -->
                                <div class="form-check form-switch" style="font-size: 1.5rem;">
                                    <input class="form-check-input close-friend-toggle"
                                        type="checkbox"
                                        id="closeFriend{{ $follower->follower->id }}"
                                        data-user-id="{{ $follower->follower->id }}"
                                        onchange="toggleCloseFriend({{ $follower->follower->id }}, this)"
                                        style="cursor: pointer; width: 50px; height: 25px;">
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x mb-3" style="color: rgba(255,255,255,0.3);"></i>
                            <p style="color: rgba(255,255,255,0.7);">
                                You don't have any followers yet.<br>
                                <small>Start building your audience!</small>
                            </p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(102, 126, 234, 0.2); background: rgba(102, 126, 234, 0.05);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveCloseFriends()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this button in Stats Section -->
    <style>
        .manage-close-friends-btn {
            width: 100%;
            margin-top: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .manage-close-friends-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .close-friend-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #4ade80, #22c55e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #1a1a2e;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(74, 222, 128, 0);
            }
        }
    </style>

    <!-- TAMBAHKAN DI DALAM .stats-section (setelah .stats-grid) -->
    <button class="manage-close-friends-btn" data-bs-toggle="modal" data-bs-target="#closeFriendsModal">
        <i class="fas fa-user-friends"></i>
        <span>Manage Close Friends</span>
        <span class="badge bg-success" id="closeFriendsBadge">0</span>
    </button>

    <script>
        // Global variable untuk menyimpan close friends
        let closeFriends = new Set();
        let originalCloseFriends = new Set();

        // Load existing close friends saat modal dibuka
        document.getElementById('closeFriendsModal').addEventListener('show.bs.modal', function() {
            loadExistingCloseFriends();
        });

        // Load close friends dari database
        async function loadExistingCloseFriends() {
            try {
                const response = await fetch('/flipside/close-friends', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    closeFriends = new Set(data.close_friends || []);
                    originalCloseFriends = new Set(data.close_friends || []);

                    // Update checkboxes
                    closeFriends.forEach(userId => {
                        const checkbox = document.getElementById(`closeFriend${userId}`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });

                    updateCloseFriendsCount();
                }
            } catch (error) {
                console.error('Error loading close friends:', error);
            }
        }

        // Toggle close friend
        function toggleCloseFriend(userId, checkbox) {
            if (checkbox.checked) {
                closeFriends.add(userId);
            } else {
                closeFriends.delete(userId);
            }
            updateCloseFriendsCount();
        }

        // Update count display
        function updateCloseFriendsCount() {
            const count = closeFriends.size;
            document.getElementById('closeFriendsCount').textContent = count;
            document.getElementById('closeFriendsBadge').textContent = count;

            // Visual feedback
            const badge = document.getElementById('closeFriendsBadge');
            badge.style.animation = 'none';
            setTimeout(() => {
                badge.style.animation = 'pulse 0.5s ease';
            }, 10);
        }

        // Filter/Search close friends
        function filterCloseFriends() {
            const searchTerm = document.getElementById('searchCloseFriends').value.toLowerCase();
            const items = document.querySelectorAll('.close-friend-item');

            items.forEach(item => {
                const username = item.getAttribute('data-username');
                const name = item.getAttribute('data-name');

                if (username.includes(searchTerm) || name.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Save close friends to database
        async function saveCloseFriends() {
            const saveBtn = event.target;
            const originalText = saveBtn.innerHTML;

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';

            try {
                const response = await fetch('/flipside/close-friends/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        close_friends: Array.from(closeFriends)
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    // Success notification
                    showNotification('✅ Close friends updated successfully!', 'success');

                    // Update original set
                    originalCloseFriends = new Set(closeFriends);

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('closeFriendsModal')).hide();

                    // Reload page to show updated posts visibility (optional)
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(result.message || 'Failed to update close friends');
                }
            } catch (error) {
                console.error('Error saving close friends:', error);
                showNotification('❌ Error: ' + error.message, 'danger');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        }

        // Show notification helper
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        `;
            notification.innerHTML = `
            <div class="d-flex align-items-center">
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Reset when modal is closed without saving
        document.getElementById('closeFriendsModal').addEventListener('hidden.bs.modal', function() {
            // Revert to original if not saved
            closeFriends = new Set(originalCloseFriends);

            // Reset checkboxes
            document.querySelectorAll('.close-friend-toggle').forEach(checkbox => {
                const userId = parseInt(checkbox.getAttribute('data-user-id'));
                checkbox.checked = closeFriends.has(userId);
            });

            updateCloseFriendsCount();

            // Clear search
            document.getElementById('searchCloseFriends').value = '';
            filterCloseFriends();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadExistingCloseFriends();
        });
    </script>

    <!-- Add animation CSS -->
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Custom Switch Styling */
        .form-check-input:checked {
            background-color: #4ade80;
            border-color: #22c55e;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(74, 222, 128, 0.25);
        }

        /* Scrollbar Styling */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.5);
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.7);
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        // Tab functionality
        function showTab(tabName) {
            document.querySelectorAll('[id$="-content"]').forEach(content => {
                content.classList.add('hidden');
            });
            document.querySelectorAll('.tab-item').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabName + '-content').classList.remove('hidden');
            event.target.classList.add('active');
        }

        // Like functionality
        async function toggleLike(postId, button) {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const liked = button.classList.contains("liked");

            try {
                const response = await fetch(
                    liked ? `/like/destroy/${postId}` : `/like/store/${postId}`, {
                        method: liked ? "DELETE" : "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrf,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                    }
                );

                if (response.ok) {
                    if (!liked) {
                        button.classList.add("liked");
                    } else {
                        button.classList.remove("liked");
                    }
                }
            } catch (error) {
                console.error('Like error:', error);
            }
        }
    </script>

</body>

</html>