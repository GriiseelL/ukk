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
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
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
        width: 120px;
        height: 120px;
        border-radius: 20px;
        overflow: hidden;
        background: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid white;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
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
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
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
        background: linear-gradient(135deg, var(--primary-color, #1da1f2), #0d8bd9);
        color: white;
    }

    .btn-profile.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 161, 242, 0.4);
        color: white;
    }

    .btn-profile.btn-secondary {
        background: rgba(29, 161, 242, 0.1);
        color: var(--primary-color, #1da1f2);
        border: 2px solid rgba(29, 161, 242, 0.2);
    }

    .btn-profile.btn-secondary:hover {
        background: rgba(29, 161, 242, 0.15);
        transform: translateY(-1px);
        color: var(--primary-color, #1da1f2);
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
        max-width: 100%;
    }

    .post-item {
        background: white;
        border: 1px solid #e1e8ed;
        border-radius: 12px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .post-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
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

    .hidden {
        display: none !important;
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
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
        }
    }

    /* Avatar fallback */
    .avatar-fallback {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-size: 48px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
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
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                 alt="Avatar {{ $user->name }}" 
                                 class="avatar-img">
                        @else
                            <div class="avatar-fallback">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
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
                        @if(auth()->check() && auth()->id() !== $user->id)
                            <button class="btn-profile btn-primary {{ $isFollowing ? 'following' : '' }}" 
                                    id="followBtn" onclick="toggleFollow({{ $user->id }})">
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
                        <div class="stat-number" id="postsCount">{{ $postsCount ?? 0 }}</div>
                        <div class="stat-label">Posts</div>
                    </div>
                    <div class="stat-item" onclick="showFollowers()">
                        <div class="stat-number" id="followersCount">{{ number_format($followersCount ?? 0) }}</div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-item" onclick="showFollowing()">
                        <div class="stat-number" id="followingCount">{{ number_format($followingCount ?? 0) }}</div>
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
                                </div>
                                
                                <div class="post-content mb-2">
                                    {!! nl2br(e($post->caption)) !!}
                                    @if($post->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $post->image) }}" 
                                                 alt="Post image"
                                                 class="img-fluid rounded" 
                                                 onclick="openImageModal('{{ asset('storage/' . $post->image) }}')">
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

<script>
    // Initialize variables
    const dbPosts = @json($posts ?? []);
    let isUserFollowing = {{ ($isFollowing ?? false) ? 'true' : 'false' }};
    
    // Toggle like functionality
    function toggleLike(postId, button) {
        @auth
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                const count = button.querySelector('span');
                
                if (data.liked) {
                    button.classList.add('liked');
                    showNotification('❤️ Liked!');
                } else {
                    button.classList.remove('liked');
                    showNotification('💔 Unliked');
                }
                
                count.textContent = data.likes_count || 0;
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
    function toggleFollow(userId) {
        @auth
            const btn = document.getElementById('followBtn');
            const textEl = btn.querySelector('.follow-text');
            const originalText = textEl.textContent;
            
            textEl.textContent = 'Loading...';
            btn.disabled = true;
            
            fetch(`/follow/store/${userId}`, {
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
                    btn.classList.add('following');
                    textEl.textContent = 'Following';
                    followersCount.textContent = data.followers_count || 0;
                    
                    btn.onmouseenter = () => textEl.textContent = 'Unfollow';
                    btn.onmouseleave = () => textEl.textContent = 'Following';
                    
                    showNotification('✅ Now following user!');
                } else {
                    btn.classList.remove('following');
                    textEl.textContent = 'Follow';
                    followersCount.textContent = data.followers_count || 0;
                    
                    btn.onmouseenter = null;
                    btn.onmouseleave = null;
                    
                    showNotification('❌ Unfollowed user');
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
        const targetContent = document.getElementById(tabName + '-content');
        const targetTab = event.target;
        
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }
        if (targetTab) {
            targetTab.classList.add('active');
        }
    }

    // Utility functions
    function openImageModal(imageSrc) {
        // Simple image modal implementation
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        `;
        
        const img = document.createElement('img');
        img.src = imageSrc;
        img.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        `;
        
        modal.appendChild(img);
        document.body.appendChild(modal);
        
        modal.onclick = () => {
            document.body.removeChild(modal);
        };
    }

    function openComments(postId) {
        showNotification('💬 Comments feature coming soon!');
    }

    function sharePost(postId) {
        const url = `${window.location.origin}/posts/${postId}`;
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
    }
</script>

@endsection