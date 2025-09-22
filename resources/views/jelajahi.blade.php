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
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.6s ease-out;
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
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
        }

        .search-btn:hover {
            transform: translateY(-50%) scale(1.1);
        }

        .search-btn.loading {
            pointer-events: none;
        }

        /* Auto-complete dropdown */
        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .search-dropdown-item {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background 0.2s ease;
        }

        .search-dropdown-item:hover {
            background: rgba(29, 161, 242, 0.05);
        }

        .search-dropdown-item:first-child {
            border-radius: 15px 15px 0 0;
        }

        .search-dropdown-item:last-child {
            border-radius: 0 0 15px 15px;
        }

        /* Filter tabs */
        .search-filters {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 8px 16px;
            border: 2px solid rgba(29, 161, 242, 0.2);
            border-radius: 20px;
            background: transparent;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .filter-tab.active,
        .filter-tab:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Trending Section */
        .section-title {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .section-title::after {
            content: '';
            height: 2px;
            background: linear-gradient(45deg, var(--primary-color), #0d8bd9);
            flex: 1;
            margin-left: 15px;
        }

        .trending-section,
        .suggested-section,
        .photos-section,
        .categories-section,
        .stories-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .trending-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .trending-item {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4, #45b7d1);
            background-size: 300% 300%;
            animation: gradientShift 8s ease infinite;
            border-radius: 16px;
            padding: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .trending-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .trending-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .trending-item:hover:before {
            opacity: 1;
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

        .trending-content {
            position: relative;
            z-index: 2;
        }

        .trending-hashtag {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .trending-posts {
            font-size: 14px;
            opacity: 0.9;
        }

        .trending-growth {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 5px;
        }

        /* Suggested Users */
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .user-card {
            background: rgba(29, 161, 242, 0.02);
            border: 1px solid rgba(29, 161, 242, 0.1);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            margin: 0 auto 15px;
            transition: transform 0.3s ease;
            position: relative;
        }

        .user-card:hover .user-avatar {
            transform: scale(1.1);
        }

        .user-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .user-username {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .user-stats {
            font-size: 12px;
            color: #888;
            margin-bottom: 15px;
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
            position: relative;
            overflow: hidden;
        }

        .follow-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(29, 161, 242, 0.4);
        }

        .follow-btn.following {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .follow-btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Photo Grid */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .photo-item {
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .photo-item:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .photo-bg {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            position: relative;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .photo-item:hover .photo-overlay {
            opacity: 1;
        }

        .photo-stat {
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        /* Categories */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .category-item {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .category-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .category-item:hover::before {
            left: 100%;
        }

        .category-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .category-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .category-name {
            font-size: 14px;
            font-weight: 600;
        }

        /* Stories Section */
        .stories-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
            scroll-behavior: smooth;
        }

        .stories-container::-webkit-scrollbar {
            height: 6px;
        }

        .stories-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .stories-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .story-item {
            flex-shrink: 0;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .story-item:hover {
            transform: scale(1.1);
        }

        .story-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-bottom: 8px;
            border: 3px solid transparent;
            background-clip: padding-box;
            transition: all 0.3s ease;
            position: relative;
        }

        .story-avatar:before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
            border-radius: 50%;
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .story-username {
            font-size: 12px;
            color: #666;
            max-width: 70px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Loading states */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .explore-container {
                margin: 10px;
            }

            .section-title {
                font-size: 20px;
            }

            .trending-grid,
            .user-grid,
            .photo-grid {
                grid-template-columns: 1fr;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stories-container {
                gap: 12px;
            }

            .story-avatar {
                width: 60px;
                height: 60px;
            }
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 250px;
            }
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.7) translateY(50px);
            transition: all 0.3s ease;
            position: relative;
        }

        .modal-overlay.active .modal {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            padding: 25px 25px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            background: #f5f5f5;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: #e0e0e0;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 15px 25px 25px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Story Modal */
        .story-modal {
            background: black;
            border-radius: 15px;
            max-width: 400px;
            height: 600px;
            position: relative;
            overflow: hidden;
        }

        .story-content {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            color: white;
        }

        .story-header {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
        }

        .story-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .story-progress {
            position: absolute;
            top: 10px;
            left: 20px;
            right: 20px;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            overflow: hidden;
        }

        .story-progress-bar {
            height: 100%;
            background: white;
            width: 0%;
            animation: storyProgress 5s linear forwards;
        }

        @keyframes storyProgress {
            to {
                width: 100%;
            }
        }

        /* Notification styles */
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

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #ddd;
        }
    </style>

    <div class="main-content">
        <div class="explore-container">
            <!-- Search Section -->
            <div class="search-section">
                <div class="search-container">
                    <form action="{{ route('jelajahi') }}" method="GET">
                        <input type="text" class="search-input" name="keyword" value="{{ $keyword }}"
                            placeholder="🔍 Cari pengguna, hashtag, atau topik..." id="searchInput" autocomplete="off">
                        <button class="search-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    {{-- <div class="search-dropdown" id="searchDropdown">
                        <!-- Auto-complete results will appear here -->
                    </div> --}}
                </div>
                <div class="search-filters">
                    <button class="filter-tab active" data-filter="all">Semua</button>
                    <button class="filter-tab" data-filter="users">Pengguna</button>
                    <button class="filter-tab" data-filter="hashtags">Hashtag</button>
                    <button class="filter-tab" data-filter="photos">Foto</button>
                </div>
            </div>

            <div class="suggested-section">
                <h2 class="section-title">
                    <i class="fas fa-user-plus" style="color: #45b7d1;"></i>
                    Pengguna yang Disarankan
                </h2>
                <div class="user-grid">
                    {{-- @php
                    // Sample suggested users data - replace with actual data from your controller
                    $suggestedUsers = [
                    [
                    'id' => 1,
                    'name' => 'Alice Johnson',
                    'username' => 'alice_photo',
                    'followers' => 12500,
                    'posts' => 245,
                    'bio' => 'Professional photographer based in Jakarta',
                    'initial' => 'A',
                    'isFollowing' => false
                    ],
                    [
                    'id' => 2,
                    'name' => 'Bob Wilson',
                    'username' => 'bob_travel',
                    'followers' => 8700,
                    'posts' => 189,
                    'bio' => 'Travel enthusiast exploring Indonesia',
                    'initial' => 'B',
                    'isFollowing' => true
                    ],
                    [
                    'id' => 3,
                    'name' => 'Charlie Brown',
                    'username' => 'charlie_art',
                    'followers' => 6300,
                    'posts' => 156,
                    'bio' => 'Digital artist creating modern Indonesian art',
                    'initial' => 'C',
                    'isFollowing' => false
                    ],
                    [
                    'id' => 4,
                    'name' => 'Diana Chen',
                    'username' => 'diana_food',
                    'followers' => 15200,
                    'posts' => 312,
                    'bio' => 'Food blogger | Recipe creator',
                    'initial' => 'D',
                    'isFollowing' => false
                    ],
                    [
                    'id' => 5,
                    'name' => 'Evan Rodriguez',
                    'username' => 'evan_tech',
                    'followers' => 9800,
                    'posts' => 98,
                    'bio' => 'Tech entrepreneur & startup mentor',
                    'initial' => 'E',
                    'isFollowing' => true
                    ],
                    [
                    'id' => 6,
                    'name' => 'Fiona Lee',
                    'username' => 'fiona_music',
                    'followers' => 18600,
                    'posts' => 267,
                    'bio' => 'Musician & songwriter',
                    'initial' => 'F',
                    'isFollowing' => false
                    ]
                    ];
                    @endphp --}}
                    @foreach($search as $user)
                        <div class="user-card">
                            <a href="{{ route('profilePage', $user->username) }}" class="user-card-link">
                                <div class="user-avatar">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-username">{{ '@' . $user->username }}</div>
                                <div class="user-stats">
                                    {{ number_format($user->followers_count) }} pengikut •
                                    {{ number_format($user->posts_count) }} postingan
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
            </div>

            <!-- Stories Section -->
            <div class="stories-section">
                <h2 class="section-title">
                    <i class="fas fa-play-circle" style="color: #ff6b6b;"></i>
                    Stories
                </h2>
                <div class="stories-container" id="storiesContainer">
                    <div class="skeleton" style="width: 70px; height: 70px; border-radius: 50%; margin-bottom: 8px;"></div>
                </div>
            </div>

            <!-- Trending Hashtags -->
            <div class="trending-section">
                <h2 class="section-title">
                    <i class="fas fa-fire" style="color: #ff6b6b;"></i>
                    Trending
                </h2>
                <div class="trending-grid" id="trendingGrid">
                    <div class="skeleton" style="height: 120px; border-radius: 16px;"></div>
                </div>
            </div>

            <!-- Categories -->
            <div class="categories-section">
                <h2 class="section-title">
                    <i class="fas fa-th-large" style="color: #4ecdc4;"></i>
                    Kategori
                </h2>
                <div class="category-grid" id="categoryGrid">
                    <div class="skeleton" style="height: 100px; border-radius: 12px;"></div>
                </div>
            </div>

            <!-- Suggested Users - Now integrated directly in template -->


            <!-- Popular Photos -->
            <div class="photos-section">
                <h2 class="section-title">
                    <i class="fas fa-images" style="color: #667eea;"></i>
                    Foto Populer
                </h2>
                <div class="photo-grid" id="photoGrid">
                    <div class="skeleton" style="aspect-ratio: 1; border-radius: 12px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Templates -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal" id="modalContent">
            <!-- Modal content will be inserted here -->
        </div>
    </div>

    <!-- Story Modal Template -->
    <div class="modal-overlay" id="storyModalOverlay">
        <div class="story-modal" id="storyModalContent">
            <!-- Story content will be inserted here-->
        </div>
    </div>

    <script>
        // Global variables
        let currentFilter = 'all';
        let searchTimeout = null;
        let isLoading = false;

        // Sample data (removed users from here since it's now in template)
        const sampleData = {
            stories: [
                { id: 1, username: 'alice_photo', initial: 'A', color: '#ff6b6b' },
                { id: 2, username: 'bob_travel', initial: 'B', color: '#4ecdc4' },
                { id: 3, username: 'charlie_art', initial: 'C', color: '#45b7d1' },
                { id: 4, username: 'diana_food', initial: 'D', color: '#96ceb4' },
                { id: 5, username: 'evan_tech', initial: 'E', color: '#feca57' },
                { id: 6, username: 'fiona_music', initial: 'F', color: '#ff9ff3' },
                { id: 7, username: 'george_sport', initial: 'G', color: '#54a0ff' },
                { id: 8, username: 'hannah_books', initial: 'H', color: '#5f27cd' }
            ],
            trending: [
                { hashtag: '#IndonesiaIndah', posts: '12.5K', growth: '+15%', color: '#ff6b6b' },
                { hashtag: '#KulinerNusantara', posts: '8.3K', growth: '+23%', color: '#4ecdc4' },
                { hashtag: '#WisataLokal', posts: '15.7K', growth: '+8%', color: '#45b7d1' },
                { hashtag: '#BudayaNusantara', posts: '6.9K', growth: '+31%', color: '#96ceb4' },
                { hashtag: '#TechInnovation', posts: '11.2K', growth: '+12%', color: '#feca57' },
                { hashtag: '#ArtLocal', posts: '4.8K', growth: '+45%', color: '#ff9ff3' }
            ],
            categories: [
                { name: 'Fotografi', icon: 'fas fa-camera', color: '#667eea' },
                { name: 'Travel', icon: 'fas fa-map-marked-alt', color: '#764ba2' },
                { name: 'Kuliner', icon: 'fas fa-utensils', color: '#f093fb' },
                { name: 'Musik', icon: 'fas fa-music', color: '#f5576c' },
                { name: 'Olahraga', icon: 'fas fa-running', color: '#4facfe' },
                { name: 'Teknologi', icon: 'fas fa-laptop-code', color: '#43e97b' },
                { name: 'Fashion', icon: 'fas fa-tshirt', color: '#fa709a' },
                { name: 'Seni', icon: 'fas fa-palette', color: '#fee140' }
            ],
            photos: [
                { id: 1, likes: 234, comments: 45, shares: 12, author: 'alice_photo' },
                { id: 2, likes: 567, comments: 89, shares: 23, author: 'bob_travel' },
                { id: 3, likes: 123, comments: 34, shares: 8, author: 'charlie_art' },
                { id: 4, likes: 789, comments: 156, shares: 45, author: 'diana_food' },
                { id: 5, likes: 345, comments: 67, shares: 15, author: 'evan_tech' },
                { id: 6, likes: 456, comments: 78, shares: 19, author: 'fiona_music' },
                { id: 7, likes: 678, comments: 123, shares: 34, author: 'alice_photo' },
                { id: 8, likes: 234, comments: 45, shares: 11, author: 'bob_travel' },
                { id: 9, likes: 567, comments: 89, shares: 28, author: 'charlie_art' }
            ]
        };

        // User data for JavaScript interactions (matching template data)
        const userData = @json($suggestedUsers ?? []);

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            setupEventListeners();
            loadInitialData();
        });

        function setupEventListeners() {
            // Search input
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', handleSearchInput);
            searchInput.addEventListener('focus', showSearchDropdown);
            searchInput.addEventListener('blur', hideSearchDropdown);

            // Filter tabs
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    setActiveFilter(this.dataset.filter);
                });
            });

            // Modal close
            document.getElementById('modalOverlay').addEventListener('click', function (e) {
                if (e.target === this) closeModal();
            });

            document.getElementById('storyModalOverlay').addEventListener('click', function (e) {
                if (e.target === this) closeStoryModal();
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeModal();
                    closeStoryModal();
                }
                if (e.key === '/' && !e.target.matches('input, textarea')) {
                    e.preventDefault();
                    document.getElementById('searchInput').focus();
                }
            });
        }

        function loadInitialData() {
            setTimeout(() => {
                loadStories();
                loadTrending();
                loadCategories();
                loadPopularPhotos();
                // Note: Suggested users are now loaded directly in the template
            }, 300);
        }

        // Search functionality
        function handleSearchInput(e) {
            const query = e.target.value.trim();

            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            searchTimeout = setTimeout(() => {
                if (query.length > 0) {
                    performAutoComplete(query);
                } else {
                    hideSearchDropdown();
                }
            }, 300);
        }

        function performAutoComplete(query) {
            const dropdown = document.getElementById('searchDropdown');
            const suggestions = generateSuggestions(query);

            if (suggestions.length > 0) {
                dropdown.innerHTML = suggestions.map(item => `
                                                                <div class="search-dropdown-item" onclick="selectSuggestion('${item.text}', '${item.type}')">
                                                                    <i class="${item.icon}" style="color: ${item.color}; width: 20px;"></i>
                                                                    <span>${item.text}</span>
                                                                    <small style="margin-left: auto; color: #666;">${item.type}</small>
                                                                </div>
                                                            `).join('');
                dropdown.style.display = 'block';
            } else {
                hideSearchDropdown();
            }
        }

        function generateSuggestions(query) {
            const suggestions = [];
            const lowerQuery = query.toLowerCase();

            // Search users from template data
            userData.forEach(user => {
                if (user.name.toLowerCase().includes(lowerQuery) || user.username.toLowerCase().includes(lowerQuery)) {
                    suggestions.push({
                        text: user.name,
                        type: 'Pengguna',
                        icon: 'fas fa-user',
                        color: '#45b7d1'
                    });
                }
            });

            // Search hashtags
            sampleData.trending.forEach(trend => {
                if (trend.hashtag.toLowerCase().includes(lowerQuery)) {
                    suggestions.push({
                        text: trend.hashtag,
                        type: 'Hashtag',
                        icon: 'fas fa-hashtag',
                        color: '#ff6b6b'
                    });
                }
            });

            // Search categories
            sampleData.categories.forEach(category => {
                if (category.name.toLowerCase().includes(lowerQuery)) {
                    suggestions.push({
                        text: category.name,
                        type: 'Kategori',
                        icon: category.icon,
                        color: category.color
                    });
                }
            });

            return suggestions.slice(0, 8);
        }

        function selectSuggestion(text, type) {
            document.getElementById('searchInput').value = text;
            hideSearchDropdown();
            performSearch();
        }

        function showSearchDropdown() {
            const input = document.getElementById('searchInput');
            if (input.value.trim().length > 0) {
                performAutoComplete(input.value.trim());
            }
        }

        function hideSearchDropdown() {
            setTimeout(() => {
                document.getElementById('searchDropdown').style.display = 'none';
            }, 150);
        }

        function performSearch() {
            const query = document.getElementById('searchInput').value.trim();
            const btn = document.querySelector('.search-btn');

            if (!query) return;

            btn.classList.add('loading');

            setTimeout(() => {
                btn.classList.remove('loading');
                showNotification(`Pencarian untuk "${query}" sedang diproses...`);
                hideSearchDropdown();
            }, 1000);
        }

        // Filter functionality
        function setActiveFilter(filter) {
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
            currentFilter = filter;

            // Apply filter logic here
            filterContent(filter);
        }

        function filterContent(filter) {
            // This would filter the displayed content based on the selected filter
            showNotification(`Filter "${filter}" diterapkan`);
        }

        // Load content functions
        function loadStories() {
            const container = document.getElementById('storiesContainer');
            container.innerHTML = sampleData.stories.map(story => `
                                                            <div class="story-item" onclick="openStoryModal('${story.username}')">
                                                                <div class="story-avatar" style="background: ${story.color}">
                                                                    ${story.initial}
                                                                </div>
                                                                <div class="story-username">${story.username}</div>
                                                            </div>
                                                        `).join('');
        }

        function loadTrending() {
            const container = document.getElementById('trendingGrid');
            container.innerHTML = sampleData.trending.map(trend => `
                                                            <div class="trending-item" onclick="searchHashtag('${trend.hashtag}')">
                                                                <div class="trending-content">
                                                                    <div class="trending-hashtag">${trend.hashtag}</div>
                                                                    <div class="trending-posts">${trend.posts} postingan</div>
                                                                    <div class="trending-growth">Tren ${trend.growth}</div>
                                                                </div>
                                                            </div>
                                                        `).join('');
        }

        function loadCategories() {
            const container = document.getElementById('categoryGrid');
            container.innerHTML = sampleData.categories.map(category => `
                                                            <a href="#" class="category-item" style="background: linear-gradient(135deg, ${category.color}, ${adjustBrightness(category.color, -20)});" onclick="exploreCategory('${category.name}')">
                                                                <div class="category-icon">
                                                                    <i class="${category.icon}"></i>
                                                                </div>
                                                                <div class="category-name">${category.name}</div>
                                                            </a>
                                                        `).join('');
        }

        function loadPopularPhotos() {
            const container = document.getElementById('photoGrid');
            container.innerHTML = sampleData.photos.map(photo => `
                                                            <div class="photo-item" onclick="openPhotoModal(${photo.id})">
                                                                <div class="photo-bg">
                                                                    <i class="fas fa-image" style="font-size: 24px; opacity: 0.7;"></i>
                                                                </div>
                                                                <div class="photo-overlay">
                                                                    <div class="photo-stat">
                                                                        <i class="fas fa-heart"></i>
                                                                        ${formatNumber(photo.likes)}
                                                                    </div>
                                                                    <div class="photo-stat">
                                                                        <i class="fas fa-comment"></i>
                                                                        ${formatNumber(photo.comments)}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `).join('');
        }

        // Modal functions
        function openStoryModal(username) {
            const story = sampleData.stories.find(s => s.username === username);
            if (!story) return;

            const modal = document.getElementById('storyModalContent');
            modal.innerHTML = `
                                                            <div class="story-progress">
                                                                <div class="story-progress-bar"></div>
                                                            </div>
                                                            <div class="story-content">
                                                                <div class="story-header">
                                                                    <div class="story-avatar-small" style="background: ${story.color}">
                                                                        ${story.initial}
                                                                    </div>
                                                                    <div>
                                                                        <div style="font-weight: bold; margin-bottom: 2px;">${story.username}</div>
                                                                        <div style="font-size: 12px; opacity: 0.8;">2 jam yang lalu</div>
                                                                    </div>
                                                                    <button class="modal-close" onclick="closeStoryModal()" style="margin-left: auto; background: rgba(255,255,255,0.2); color: white;">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div style="flex: 1; display: flex; align-items: center; justify-content: center; font-size: 24px; text-align: center;">
                                                                    Story dari ${story.username}<br>
                                                                    <small style="font-size: 14px; opacity: 0.8; margin-top: 10px;">
                                                                        Ini adalah contoh story yang menampilkan konten menarik!
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        `;

            document.getElementById('storyModalOverlay').classList.add('active');

            // Auto close after 5 seconds
            setTimeout(() => {
                closeStoryModal();
            }, 5000);
        }

        function closeStoryModal() {
            document.getElementById('storyModalOverlay').classList.remove('active');
        }

        function openUserModal(userId) {
            const user = userData.find(u => u.id === userId);
            if (!user) return;

            const modal = document.getElementById('modalContent');
            modal.innerHTML = `
                                                            <div class="modal-header">
                                                                <div class="modal-title">
                                                                    <i class="fas fa-user"></i>
                                                                    Profil Pengguna
                                                                </div>
                                                                <button class="modal-close" onclick="closeModal()">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="profile-header">
                                                                    <div class="profile-avatar-large">
                                                                        ${user.name.charAt(0)}
                                                                    </div>
                                                                    <h3 style="margin: 0; font-size: 20px; color: #1a1a1a;">${user.name}</h3>
                                                                    <p style="margin: 5px 0 0; color: #666;">@${user.username}</p>
                                                                </div>

                                                                <div class="profile-stats">
                                                                    <div class="stat-item">
                                                                        <div class="stat-number">${formatNumber(user.posts)}</div>
                                                                        <div class="stat-label">Postingan</div>
                                                                    </div>
                                                                    <div class="stat-item">
                                                                        <div class="stat-number">${formatNumber(user.followers)}</div>
                                                                        <div class="stat-label">Pengikut</div>
                                                                    </div>
                                                                    <div class="stat-item">
                                                                        <div class="stat-number">${formatNumber(user.following || 0)}</div>
                                                                        <div class="stat-label">Mengikuti</div>
                                                                    </div>
                                                                </div>

                                                                <div class="profile-bio">
                                                                    <i class="fas fa-quote-left" style="color: var(--primary-color); margin-right: 8px;"></i>
                                                                    ${user.bio}
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" onclick="closeModal()">
                                                                    <i class="fas fa-times"></i>
                                                                    Tutup
                                                                </button>
                                                                <button class="btn btn-primary" onclick="toggleFollowModal(${user.id})">
                                                                    <i class="fas fa-${user.isFollowing ? 'user-minus' : 'user-plus'}"></i>
                                                                    ${user.isFollowing ? 'Berhenti Mengikuti' : 'Ikuti'}
                                                                </button>
                                                            </div>
                                                        `;

            document.getElementById('modalOverlay').classList.add('active');
        }

        function openPhotoModal(photoId) {
            const photo = sampleData.photos.find(p => p.id === photoId);
            if (!photo) return;

            const modal = document.getElementById('modalContent');
            modal.innerHTML = `
                                                            <div class="modal-header">
                                                                <div class="modal-title">
                                                                    <i class="fas fa-image"></i>
                                                                    Foto oleh @${photo.author}
                                                                </div>
                                                                <button class="modal-close" onclick="closeModal()">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="photo-modal-content">
                                                                    <div class="photo-display">
                                                                        <i class="fas fa-image" style="font-size: 48px; opacity: 0.7;"></i>
                                                                    </div>
                                                                    <div class="photo-actions">
                                                                        <button class="action-btn" onclick="toggleLike(${photoId})">
                                                                            <i class="fas fa-heart"></i>
                                                                            <span>${formatNumber(photo.likes)}</span>
                                                                        </button>
                                                                        <button class="action-btn" onclick="showComments(${photoId})">
                                                                            <i class="fas fa-comment"></i>
                                                                            <span>${formatNumber(photo.comments)}</span>
                                                                        </button>
                                                                        <button class="action-btn" onclick="sharePhoto(${photoId})">
                                                                            <i class="fas fa-share"></i>
                                                                            <span>${formatNumber(photo.shares)}</span>
                                                                        </button>
                                                                        <button class="action-btn" onclick="savePhoto(${photoId})">
                                                                            <i class="fas fa-bookmark"></i>
                                                                            Simpan
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;

            document.getElementById('modalOverlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
        }

        // Action functions
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
                    // unfollow
                    url = `/follow/destroy/${userId}`;
                    method = 'DELETE';
                } else {
                    // follow
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
                        // unfollow sukses
                        btn.setAttribute("data-following", "false");
                        btn.classList.remove("following");
                        btn.innerHTML = `<i class="fas fa-plus"></i> Ikuti`;
                        showNotification(data.message || "Berhenti mengikuti");
                    } else {
                        // follow sukses
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
        function searchHashtag(hashtag) {
            document.getElementById('searchInput').value = hashtag;
            showNotification(`Mencari postingan dengan ${hashtag}`);
        }

        function exploreCategory(categoryName) {
            showNotification(`Menjelajahi kategori ${categoryName}`);
        }

        function toggleLike(photoId) {
            const photo = sampleData.photos.find(p => p.id === photoId);
            if (photo) {
                photo.likes += Math.random() > 0.5 ? 1 : -1;
                showNotification('Foto disukai!');
            }
        }

        function showComments(photoId) {
            showNotification('Menampilkan komentar...');
        }

        function sharePhoto(photoId) {
            showNotification('Foto dibagikan!');
        }

        function savePhoto(photoId) {
            showNotification('Foto disimpan ke koleksi!');
        }

        // Utility functions
        function formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        function adjustBrightness(hex, percent) {
            // Simple brightness adjustment
            const num = parseInt(hex.replace('#', ''), 16);
            const amt = Math.round(2.55 * percent);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255))
                .toString(16).slice(1);
        }

        function showNotification(message) {
            // Remove existing notification
            const existing = document.querySelector('.notification');
            if (existing) existing.remove();

            // Create new notification
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Performance optimization - Lazy loading for images
        function setupLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.remove('skeleton');
                                observer.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        // Initialize lazy loading after content loads
        setTimeout(setupLazyLoading, 1000);
    </script>
@endsection