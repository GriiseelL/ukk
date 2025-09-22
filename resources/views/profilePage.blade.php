@extends('layout.layarUtama')

@section('title', 'Profil - Telava')

@section('content')
    <style>
        .post-interactions {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
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

        .interaction-btn i {
            font-size: 16px;
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
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
            background-size: 300% 300%;
            animation: gradientShift 6s ease infinite;
            position: relative;
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

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            border: 4px solid white;
            background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.05);
        }

        .online-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 16px;
            height: 16px;
            background: #00ff88;
            border: 3px solid white;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
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
        }

        /* Profile Actions */
        .profile-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }


        .btn-profile {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-profile.btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
            color: white;
        }

        .btn-profile.btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(29, 161, 242, 0.4);
            color: white;
        }

        .btn-profile.btn-secondary {
            background: rgba(29, 161, 242, 0.1);
            color: var(--primary-color);
            border: 2px solid rgba(29, 161, 242, 0.2);
        }

        .btn-profile.btn-secondary:hover {
            background: rgba(29, 161, 242, 0.15);
            transform: translateY(-1px);
            color: var(--primary-color);
        }

        .btn-profile.following {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .btn-profile.following:hover {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
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
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .tab-item:hover {
            color: var(--primary-color);
            background: rgba(29, 161, 242, 0.05);
        }

        /* Content Area */
        .content-area {
            padding: 30px;
            min-height: 300px;
        }

        .post-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .post-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid var(--border-color);
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .post-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .post-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .post-card:hover .post-overlay {
            opacity: 1;
        }

        .post-stat {
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .post-content {
            padding: 15px;
        }

        .post-title {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .post-meta {
            font-size: 12px;
            color: #666;
        }

        .post-actions {
            padding: 10px 15px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border-radius: 20px;
            transition: all 0.3s ease;
            font-size: 14px;
            color: #666;
        }

        .action-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: scale(1.05);
        }

        .action-btn.liked {
            color: #ff3040;
        }

        .action-btn.liked i {
            color: #ff3040;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h4 {
            margin-bottom: 10px;
            color: #333;
        }

        .empty-state p {
            margin-bottom: 0;
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

            .profile-actions {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-area {
                padding: 20px;
            }

            .post-grid {
                grid-template-columns: 1fr;
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
                <div class="cover-section"></div>

                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="avatar-container">
                        <div class="avatar">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="online-indicator"></div>
                    </div>

                    <div class="profile-info">
                        <h1 class="profile-name">{{ $user->name }}</h1>
                        <p class="profile-username">{{ '@' . $user->username }}</p>
                        <p class="profile-bio">
                            {!! nl2br(e($user->bio ?? 'Belum ada bio.')) !!}
                        </p>

                        <div class="profile-actions">
                            @if(auth()->id() !== $user->id)
                                <button class="btn-profile btn-primary {{ $isFollowing ? 'following' : '' }}" id="followBtn"
                                    onclick="toggleFollow({{ $user->id }})">
                                    <span class="follow-text">
                                        {{ $isFollowing ? 'Following' : 'Follow' }}
                                    </span>
                                </button>

                                <button class="btn-profile btn-secondary" onclick="sendMessage({{ $user->id }})">
                                    <i class="far fa-envelope"></i> Pesan
                                </button>
                            @endif
                            <button class="btn-profile btn-secondary"
                                onclick="shareProfile('{{ route('profilePage', $user->username) }}')">
                                <i class="fas fa-share"></i> Bagikan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number" id="postsCount">{{ $postsCount }}</div>
                            <div class="stat-label">Posts</div>
                        </div>
                        <div class="stat-item" onclick="showFollowers()">
                            <div class="stat-number" id="followersCount">{{ number_format($followersCount) }}</div>
                            <div class="stat-label">Followers</div>
                        </div>
                        <div class="stat-item" onclick="showFollowing()">
                            <div class="stat-number" id="followingCount">{{ number_format($followingCount) }}</div>
                            <div class="stat-label">Following</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4.8</div>
                            <div class="stat-label">Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Content Tabs -->
                <div class="content-tabs">
                    <div class="tab-list">
                        <div class="tab-item active" onclick="showTab('posts')">📷 Posts</div>
                        <div class="tab-item" onclick="showTab('reels')">🎬 Reels</div>
                        <div class="tab-item" onclick="showTab('tagged')">🏷️ Tagged</div>
                        <div class="tab-item" onclick="showTab('saved')">💾 Saved</div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    <!-- Posts Content -->
                    <div id="posts-content">
                        {{-- Stream posts --}}
                        <div class="posts-stream">
                            @forelse($posts as $post)
                                <div class="post-item p-3 mb-3 border rounded" data-post-id="{{ $post->id }}">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($post->user->avatar)
                                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}"
                                                class="rounded-circle me-2" style="width:40px; height:40px; object-fit:cover;">
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
                                    </div>
                                    <div class="post-content mb-2">
                                        {!! nl2br(e($post->caption)) !!}
                                        @if($post->image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post image"
                                                    class="img-fluid rounded" onclick="openImageModal(this.src)">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Post Interactions -->
                                    <div class="post-interactions">
                                        <button
                                            class="interaction-btn {{ $post->likes->where('user_id', auth()->id())->count() > 0 ? 'liked' : '' }}"
                                            onclick="toggleLike({{ $post->id }}, this)">
                                            <i class="fas fa-heart" id="like-icon-{{ $post->id }}"></i>
                                            <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
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

                    <!-- Reels Content -->
                    <div id="reels-content" class="hidden">
                        <div class="empty-state">
                            <i class="fas fa-video"></i>
                            <h4>Reels akan segera hadir!</h4>
                            <p>Fitur reels masih dalam pengembangan.</p>
                        </div>
                    </div>

                    <!-- Tagged Content -->
                    <div id="tagged-content" class="hidden">
                        <div class="empty-state">
                            <i class="fas fa-tag"></i>
                            <h4>Belum ada foto yang di-tag</h4>
                            <p>Foto yang menandai {{ $user->name }} akan muncul di sini.</p>
                        </div>
                    </div>

                    <!-- Saved Content -->
                    <div id="saved-content" class="hidden">
                        <div class="empty-state">
                            <i class="fas fa-bookmark"></i>
                            <h4>Koleksi tersimpan kosong</h4>
                            <p>Postingan yang disimpan akan muncul di sini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    @auth
        @if(auth()->id() === $user->id)
            <button class="fab" onclick="createPost()" title="Create new post">
                <i class="fas fa-plus"></i>
            </button>
        @endif
    @endauth

    <script>
        // Get database posts data
        const dbPosts = @json($posts);
        let isUserFollowing = {{ $isFollowing ? 'true' : 'false' }};

        // Convert database posts to JavaScript format
        const posts = dbPosts.map(post => ({
            id: post.id,
            title: post.caption ? post.caption.substring(0, 50) + (post.caption.length > 50 ? '...' : '') : 'Untitled Post',
            caption: post.caption || '',
            image: post.image,
            likes: post.likes ? post.likes.length : 0,
            comments: 0, // You can add comments count when you implement comments
            shares: 0,
            liked: {{ auth()->check() ? 'true' : 'false' }} && post.likes && post.likes.some(like => like.user_id === {{ auth()->id() ?? 'null' }}),
            gradient: `linear-gradient(135deg, ${getRandomColor()}, ${getRandomColor()})`,
            description: post.caption || 'No description available.',
            created_at: post.created_at
        }));

        function getRandomColor() {
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#667eea', '#764ba2', '#f093fb', '#f5576c'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        // Toggle like functionality
        function toggleLike(postId, button) {
            @auth
                // Make AJAX request to toggle like
                fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        const icon = button.querySelector('i');
                        const count = button.querySelector('.like-count');

                        if (data.liked) {
                            button.classList.add('liked');
                            icon.className = 'fas fa-heart';
                            showNotification('❤️ Liked!');
                        } else {
                            button.classList.remove('liked');
                            icon.className = 'far fa-heart';
                            showNotification('💔 Unliked');
                        }

                        count.textContent = data.likes_count;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error: Unable to like post');
                    });
            @else
                showNotification('Please login to like posts');
            @endauth
                                        }

        // Follow functionality
        // Updated JavaScript functions for follow functionality

        // Follow functionality
        function toggleFollow(id) {
            @auth
                const btn = document.getElementById('followBtn');
                const textEl = btn.querySelector('.follow-text');
                const originalText = textEl.textContent;

                // loading state
                textEl.textContent = 'Loading...';
                btn.disabled = true;

                fetch(`/follow/store/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        const followersCount = document.getElementById('followersCount');

                        if (data.following) {
                            // ✅ Follow success
                            btn.classList.add('following');
                            textEl.textContent = 'Following';
                            followersCount.textContent = data.followers_count;

                            // hover jadi Unfollow
                            btn.onmouseenter = () => textEl.textContent = 'Unfollow';
                            btn.onmouseleave = () => textEl.textContent = 'Following';

                            showNotification(`✅ Now following user!`);
                        } else {
                            // ❌ Unfollow success
                            btn.classList.remove('following');
                            textEl.textContent = 'Follow';
                            followersCount.textContent = data.followers_count;

                            btn.onmouseenter = null;
                            btn.onmouseleave = null;

                            showNotification(`❌ Unfollowed user`);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        textEl.textContent = originalText;
                        showNotification('❌ Error: Unable to follow user');
                    })
                    .finally(() => {
                        btn.disabled = false;
                    });
            @else
                showNotification('Please login to follow users');
                window.location.href = '/login';
            @endauth
        }

        // notifikasi sederhana
        function showNotification(message) {
            alert(message);
        }
        // Enhanced notification function
        function showNotification(message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.custom-notification');
            existingNotifications.forEach(notification => {
                notification.remove();
            });

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

            // Add CSS animation if not exists
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

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }        // Tab functionality
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

        // Modal functionality
        function openImageModal(postId) {
            const post = posts.find(p => p.id === postId) || dbPosts.find(p => p.id === postId);
            if (!post) return;

            // You can implement image modal here
            console.log('Opening image modal for post:', post);
        }

        function openCommentModal(postId) {
            // You can implement comment modal here
            console.log('Opening comment modal for post:', postId);
        }

        function sharePost(postId) {
            const url = `{{ url('/') }}/posts/${postId}`;
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this post!',
                    url: url
                });
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    showNotification('🔗 Link copied to clipboard!');
                });
            }
        }

        // Other functions
        function showFollowers() {
            showNotification('👥 Followers list coming soon!');
        }

        function showFollowing() {
            showNotification('👥 Following list coming soon!');
        }

        function sendMessage(userId) {
            showNotification('📩 Message feature coming soon!');
        }

        function shareProfile(url) {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $user->name }} - Telava Profile',
                    url: url
                });
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    showNotification('🔗 Profile link copied to clipboard!');
                });
            }
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

        // Add CSS for hidden elements
        document.addEventListener('DOMContentLoaded', function () {
            const style = document.createElement('style');
            style.textContent = '.hidden { display: none !important; }';
            document.head.appendChild(style);
        });
    </script>

@endsection