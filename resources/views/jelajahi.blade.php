@extends('layout.layarUtama')

@section('title', 'Jelajahi - Telava')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
    }

    .explore-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 15px;
        animation: fadeIn 0.6s ease-out;
        margin-left: 25px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Search Section */
    .search-section {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        width: 100%;
    }

    .search-container {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid rgba(29, 161, 242, 0.1);
        border-radius: 25px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: rgba(29, 161, 242, 0.05);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn:hover {
        transform: translateY(-50%) scale(1.1);
    }

    /* Users Section */
    .users-section {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        width: 100%;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .user-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 12px;
        background: rgba(29, 161, 242, 0.02);
        border: 1px solid rgba(29, 161, 242, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .user-item:hover {
        background: rgba(29, 161, 242, 0.05);
        border-color: var(--primary-color);
        transform: translateX(5px);
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        font-weight: bold;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 3px;
    }

    .user-username {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .user-stats {
        font-size: 13px;
        color: #888;
    }

    .follow-btn {
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .follow-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(29, 161, 242, 0.4);
    }

    .follow-btn.following {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .follow-btn.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #ddd;
    }

    .empty-state h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
    }

    .empty-state p {
        font-size: 14px;
        color: #888;
    }

    /* Notification */
    .notification {
        position: fixed;
        top: 90px;
        right: 20px;
        z-index: 10000;
        background: #333;
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 14px;
        animation: slideInRight 0.3s ease;
        max-width: 300px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

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

    /* Responsive */
    @media (max-width: 768px) {
        .explore-container {
            padding: 0 10px;
        }

        .search-section,
        .users-section {
            padding: 15px;
        }

        .user-item {
            padding: 12px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            font-size: 18px;
        }

        .user-name {
            font-size: 15px;
        }

        .user-username {
            font-size: 13px;
        }

        .follow-btn {
            padding: 6px 16px;
            font-size: 13px;
        }
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
        }
    }
</style>

<div class="main-content">
    <div class="explore-container">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <form action="{{ route('jelajahi') }}" method="GET">
                    <input type="text" 
                           class="search-input" 
                           name="keyword" 
                           value="{{ $keyword ?? '' }}"
                           placeholder="🔍 Cari pengguna..." 
                           autocomplete="off">
                    <button class="search-btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Users Section -->
        <div class="users-section">
            <h2 class="section-title">
                <i class="fas fa-users" style="color: var(--primary-color);"></i>
                @if(isset($keyword) && $keyword)
                    Hasil Pencarian "{{ $keyword }}"
                @else
                    Pengguna yang Disarankan
                @endif
            </h2>

            @if($search && count($search) > 0)
                <div class="user-list">
                    @foreach($search->shuffle() as $user)
                        <div class="user-item">
                            <a href="{{ route('profilePage', $user->username) }}" style="display: flex; align-items: center; gap: 15px; flex: 1; text-decoration: none; color: inherit;">
                                <div class="user-avatar">
                                    @if ($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar">
                                    @else
                                        {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-username">{{ '@' . $user->username }}</div>
                                    <div class="user-stats">
                                        {{ number_format($user->followers_count) }} pengikut • 
                                        {{ number_format($user->posts_count) }} postingan
                                    </div>
                                </div>
                            </a>
                            <button class="follow-btn {{ $user->isFollowing ? 'following' : '' }}"
                                    data-following="{{ $user->isFollowing ? 'true' : 'false' }}"
                                    onclick="event.stopPropagation(); toggleFollow(event, {{ $user->id }})">
                                <i class="fas fa-{{ $user->isFollowing ? 'check' : 'plus' }}"></i>
                                {{ $user->isFollowing ? 'Mengikuti' : 'Ikuti' }}
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada hasil</h3>
                    <p>Coba gunakan kata kunci lain untuk mencari pengguna</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Toggle Follow Function
    async function toggleFollow(event, userId) {
        event.stopPropagation();
        const btn = event.target.closest('.follow-btn');
        if (!btn) return;

        const isFollowing = btn.getAttribute("data-following") === "true";
        
        btn.classList.add('loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            let url = '';
            let method = '';

            if (isFollowing) {
                url = `/follow/destroy/${userId}`;
                method = 'DELETE';
            } else {
                url = `/follow/store/${userId}`;
                method = 'POST';
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                btn.classList.remove('loading');
                if (isFollowing) {
                    btn.setAttribute("data-following", "false");
                    btn.classList.remove("following");
                    btn.innerHTML = `<i class="fas fa-plus"></i> Ikuti`;
                    showNotification(data.message || "Berhenti mengikuti");
                } else {
                    btn.setAttribute("data-following", "true");
                    btn.classList.add("following");
                    btn.innerHTML = `<i class="fas fa-check"></i> Mengikuti`;
                    showNotification(data.message || "Berhasil mengikuti");
                }
            } else {
                btn.classList.remove('loading');
                btn.innerHTML = `<i class="fas fa-plus"></i> Ikuti`;
                showNotification(data.error || "Terjadi kesalahan", "error");
            }
        } catch (err) {
            console.error(err);
            btn.classList.remove('loading');
            btn.innerHTML = `<i class="fas fa-plus"></i> Ikuti`;
            showNotification("Gagal koneksi ke server", "error");
        }
    }

    // Show Notification
    function showNotification(message) {
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();

        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection