@php
$hasFlipsideAccess = isset($hasFlipsideAccess) && $hasFlipsideAccess;
$isFlipsideView = request()->is('*/flipside') || request()->get('view') === 'flipside';
$isFlipside = request()->is('flipside*') || (isset($flipsidePosts) && count($flipsidePosts) > 0);
$displayPosts = $isFlipsideView ? ($flipsidePosts ?? []) : ($posts ?? []);
$isOwnProfile = auth()->check() && auth()->id() === $user->id;
@endphp

@extends('layout.layarUtama')

@section('title', $user->name . ' - Telava')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    window.appData = {
        isFlipside: {{ $isFlipsideView ? 'true' : 'false' }},
        isOwnProfile: {{ $isOwnProfile ? 'true' : 'false' }},
        followers: @json($followers),
        following: @json($following),
        userId: {{ $user->id }},
        user: @json($user ?? [])
    };
</script>

<style>
    :root {
        --normal-bg: #ffffff;
        --normal-text: #1a1a1a;
        --normal-border: #e1e8ed;
        
        --flipside-bg: #0d0d0d;
        --flipside-text: #ffffff;
        --flipside-border: rgba(255, 0, 128, 0.2);
    }

    body {
        background: #f5f5f5;
        transition: background 0.5s ease;
    }

    .main-content {
        /* margin-top: 70px; */
        padding-bottom: 80px;
            padding-top: 60px;   /* sama dengan tinggi navbar */

    }

  .profile-container {
    max-width: 800px; /* 👈 Diperbesar */
    margin: 0 auto;
    background: {{ $isFlipsideView ? '#0d0d0d' : 'white' }};
    border-radius: 20px;
    overflow: hidden;
    box-shadow: {{ $isFlipsideView ? '0 8px 32px rgba(255, 0, 128, 0.3)' : '0 8px 32px rgba(0, 0, 0, 0.1)' }};
    border: {{ $isFlipsideView ? '1px solid rgba(255, 0, 128, 0.2)' : 'none' }};
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

    /* FLIPSIDE MODE HEADER */
    .flipside-mode-header {
        display: {{ $isFlipsideView ? 'flex' : 'none' }};
        align-items: center;
        justify-content: space-between;
        padding: 20px 30px;
        background: rgba(255, 0, 128, 0.1);
        border-bottom: 2px solid rgba(255, 0, 128, 0.3);
        backdrop-filter: blur(10px);
    }

    .back-to-normal-btn {
        background: linear-gradient(135deg, #FF0080, #7928CA);
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
        box-shadow: 0 4px 15px rgba(255, 0, 128, 0.4);
        text-decoration: none;
    }

    .back-to-normal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 0, 128, 0.6);
        color: white;
    }

    .flipside-mode-badge {
        background: linear-gradient(135deg, #FF0080, #FF4D00);
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
        0%, 100% { box-shadow: 0 4px 15px rgba(255, 0, 128, 0.4); }
        50% { box-shadow: 0 4px 25px rgba(255, 0, 128, 0.8); }
    }

    /* Cover Section */
    .cover-section {
        height: 250px;
        background: linear-gradient(45deg, #667eea, #764ba2, #45b7d1);
        background-size: 300% 300%;
        animation: gradientShift 6s ease infinite;
        position: relative;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .cover-view-btn {
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .cover-section:hover .cover-view-btn {
        opacity: 1;
    }

    .cover-view-btn:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.05);
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
        background: {{ $isFlipsideView ? 'linear-gradient(135deg, #FF0080, #7928CA)' : 'linear-gradient(135deg, #667eea, #764ba2)' }};
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid {{ $isFlipsideView ? '#0d0d0d' : 'white' }};
        box-shadow: {{ $isFlipsideView ? '0 8px 25px rgba(255, 0, 128, 0.5)' : '0 4px 16px rgba(0, 0, 0, 0.1)' }};
        transition: transform 0.3s ease;
        cursor: pointer;
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

    .avatar-fallback {
        background: linear-gradient(135deg, {{ $isFlipsideView ? '#FF0080, #7928CA' : '#667eea, #764ba2' }});
        color: white;
        font-size: 48px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .profile-info {
        margin-top: 15px;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 800;
        color: {{ $isFlipsideView ? 'var(--flipside-text)' : '#1a1a1a' }};
        margin-bottom: 5px;
    }

    .profile-username {
        font-size: 16px;
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.6)' : '#666' }};
        margin-bottom: 15px;
    }

    .profile-bio {
        font-size: 15px;
        line-height: 1.5;
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.8)' : '#444' }};
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
        background: linear-gradient(135deg, #1da1f2, #0d8bd9);
        color: white;
    }

    .btn-profile.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 161, 242, 0.4);
        color: white;
    }

    .btn-profile.btn-secondary {
        background: {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.1)' : 'rgba(29, 161, 242, 0.1)' }};
        color: {{ $isFlipsideView ? '#FF0080' : '#1da1f2' }};
        border: 2px solid {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.2)' : 'rgba(29, 161, 242, 0.2)' }};
    }

    .btn-profile.btn-secondary:hover {
        background: {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.2)' : 'rgba(29, 161, 242, 0.15)' }};
        transform: translateY(-1px);
    }

    .btn-profile.following {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .btn-profile.following:hover {
        background: linear-gradient(135deg, #dc3545, #e74c3c);
    }

    .btn-flipside {
        background: linear-gradient(135deg, #FF0080, #7928CA) !important;
        color: white !important;
        border: none !important;
    }

    .btn-flipside:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 0, 128, 0.6) !important;
        color: white !important;
    }

    .btn-flipside.locked {
        background: rgba(200, 200, 200, 0.3) !important;
        color: rgba(100, 100, 100, 0.7) !important;
        border: 2px solid rgba(200, 200, 200, 0.5) !important;
        cursor: not-allowed;
    }

    .btn-flipside.locked:hover {
        transform: none;
        box-shadow: none !important;
    }

    /* Stats Section */
    .stats-section {
        padding: 20px 30px;
        background: {{ $isFlipsideView ? '#1a1a1a' : 'rgba(0, 0, 0, 0.02)' }};
        border-top: 1px solid {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)' }};
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: {{ $isFlipsideView ? '#0d0d0d' : 'white' }};
        border: {{ $isFlipsideView ? '1px solid rgba(255, 0, 128, 0.2)' : 'none' }};
        border-radius: 12px;
        box-shadow: {{ $isFlipsideView ? 'none' : '0 2px 8px rgba(0, 0, 0, 0.05)' }};
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: {{ $isFlipsideView ? '0 6px 20px rgba(255, 0, 128, 0.4)' : '0 6px 20px rgba(0, 0, 0, 0.1)' }};
    }

    .stat-number {
        font-size: 20px;
        font-weight: 800;
        color: {{ $isFlipsideView ? 'var(--flipside-text)' : '#1a1a1a' }};
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.6)' : '#666' }};
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Content Tabs */
    .content-tabs {
        padding: 0 30px;
        background: {{ $isFlipsideView ? '#1a1a1a' : 'transparent' }};
        border-bottom: 1px solid {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)' }};
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
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.5)' : '#666' }};
        white-space: nowrap;
    }

    .tab-item.active {
        color: {{ $isFlipsideView ? '#FF0080' : '#1da1f2' }};
        border-bottom-color: {{ $isFlipsideView ? '#FF0080' : '#1da1f2' }};
    }

    .tab-item:hover {
        color: {{ $isFlipsideView ? '#FF0080' : '#1da1f2' }};
        background: {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.1)' : 'rgba(29, 161, 242, 0.05)' }};
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
        background: {{ $isFlipsideView ? '#1a1a1a' : 'white' }};
        border: 1px solid {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.2)' : '#e1e8ed' }};
        border-radius: 12px;
        margin-bottom: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
    }

    .post-item:hover {
        box-shadow: {{ $isFlipsideView ? '0 4px 12px rgba(255, 0, 128, 0.3)' : '0 4px 12px rgba(0, 0, 0, 0.1)' }};
        transform: translateY(-2px);
    }

    .post-flipside-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #FF0080, #7928CA);
        color: white;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    .post-content {
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.9)' : 'inherit' }};
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

    .post-interactions {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.1)' : '#e1e8ed' }};
    }

    .interaction-btn {
        background: none;
        border: none;
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.6)' : '#666' }};
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
        background: {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.2)' : 'rgba(0, 0, 0, 0.05)' }};
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

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.7)' : '#666' }};
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin-bottom: 10px;
        color: {{ $isFlipsideView ? 'white' : '#333' }};
    }

    .empty-state p {
        margin-bottom: 0;
    }

    .hidden {
        display: none !important;
    }

    /* Image Modal */
    #imageModal {
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

    #imageModal .image-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        max-height: 90%;
    }

    #imageModal img {
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

        .flipside-mode-header {
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

    .profile-menu-container {
        position: absolute;
        top: 20px;
        right: 30px;
        z-index: 100;
    }

    .profile-menu-btn {
        background: transparent;
        color: {{ $isFlipsideView ? '#FF0080' : '#666' }};
        border: none;
        padding: 10px 14px;
        border-radius: 50%;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        margin-top: 50px;
    }

    .profile-menu-btn:hover {
        background: {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.1)' : 'rgba(0, 0, 0, 0.05)' }};
        transform: scale(1.1);
    }

    .profile-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: {{ $isFlipsideView ? '#1a1a1a' : 'white' }};
        border: 1px solid {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.2)' : '#e1e8ed' }};
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        min-width: 220px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    .profile-dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-menu-item {
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        color: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.9)' : '#1a1a1a' }};
        border-bottom: 1px solid {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.1)' : '#f0f0f0' }};
    }

    .dropdown-menu-item:first-child {
        border-radius: 16px 16px 0 0;
    }

    .dropdown-menu-item:last-child {
        border-bottom: none;
        border-radius: 0 0 16px 16px;
    }

    .dropdown-menu-item:hover {
        background: {{ $isFlipsideView ? 'rgba(255, 0, 128, 0.1)' : 'rgba(29, 161, 242, 0.05)' }};
    }

    .dropdown-menu-item.danger {
        color: #dc3545;
    }

    .dropdown-menu-item.danger:hover {
        background: rgba(220, 53, 69, 0.1);
    }

    .dropdown-menu-item i {
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .dropdown-menu-divider {
        height: 1px;
        background: {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.1)' : '#e1e8ed' }};
        margin: 4px 0;
    }

    @media (max-width: 768px) {
        .profile-dropdown-menu {
            right: 0;
            left: auto;
        }
        
        .profile-menu-container {
            top: 15px;
            right: 20px;
        }
        
        .profile-menu-btn {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
    }
    /* === GRID MEDIA DI DALAM POST === */
.media-grid {
display: grid;
gap: 5px;
border-radius: 12px;
overflow: hidden;
width: 100%;
padding: 10px;
}
/* 1 GAMBAR */
.media-grid.media-count-1 {
grid-template-columns: 1fr;
}
.media-grid.media-count-1 .media-item {
max-height: 500px;
min-height: 300px;
}
/* 2 GAMBAR */
.media-grid.media-count-2 {
grid-template-columns: repeat(2, 1fr);
height: 300px;
}
.media-grid.media-count-2 .media-item {
height: 100%;
}
/* 3 GAMBAR - TWITTER LAYOUT */
.media-grid.media-count-3 {
grid-template-columns: 2fr 1fr;
grid-template-rows: repeat(2, 200px);
gap: 4px;
}
.media-grid.media-count-3 .media-item:nth-child(1) {
grid-row: 1 / 3;
height: 100%;
}
/* 4 GAMBAR - 2x2 GRID */
.media-grid.media-count-4 {
grid-template-columns: repeat(2, 1fr);
grid-template-rows: repeat(2, 200px);
gap: 4px;
}
.media-grid.media-count-4 .media-item {
height: 100%;
}
/* BASE STYLE MEDIA ITEM */
.media-item {
position: relative;
overflow: hidden;
cursor: pointer;
background: #000;
border-radius: 8px;
transition: transform 0.3s ease;
}
.media-item img,
.media-item video {
width: 100%;
height: 100%;
object-fit: cover;
display: block;
border-radius: inherit;
}
.media-item:hover {
transform: scale(1.015);
box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
border-radius: 10px;
}
.media-item:hover img,
.media-item:hover video {
transform: scale(1.03);
}
.post-video-container {
position: relative;
width: 100%;
height: 100%;
}
.post-video-container video {
width: 100%;
height: 100%;
object-fit: cover;
}
.mute-btn {
position: absolute;
bottom: 10px;
right: 10px;
background: rgba(0, 0, 0, 0.7) !important;
border: none;
color: white;
padding: 8px 10px;
border-radius: 50%;
cursor: pointer;
z-index: 3;
transition: all 0.3s ease;
backdrop-filter: blur(5px);
}
.mute-btn:hover {
background: rgba(0, 0, 0, 0.9) !important;
transform: scale(1.1);
}

.media-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
    padding: 10px 0;
}

@media (min-width: 768px) {
    .media-gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

.media-gallery-item {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: 12px;
    cursor: pointer;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.media-gallery-item:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    z-index: 10;
    border-color: #667eea;
}

.media-gallery-item img,
.media-gallery-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                filter 0.3s ease;
}

.media-gallery-item:hover img,
.media-gallery-item:hover video {
    transform: scale(1.05);
    filter: brightness(1.1);
}

/* Video Play Icon */
.video-play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 48px;
    color: white;
    opacity: 0.9;
    pointer-events: none;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
    transition: all 0.3s ease;
    z-index: 2;
}

.media-gallery-item:hover .video-play-icon {
    transform: translate(-50%, -50%) scale(1.2);
    opacity: 1;
}

/* Overlay effect saat hover */
.media-gallery-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        180deg, 
        rgba(0, 0, 0, 0) 0%, 
        rgba(0, 0, 0, 0.4) 100%
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
    pointer-events: none;
    border-radius: inherit;
}

.media-gallery-item:hover::before {
    opacity: 1;
}

/* Video specific styling */
.media-gallery-item.has-video video {
    background: #000;
}

.media-gallery-item.has-video::after {
    content: '🎬';
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.media-gallery-item.has-video:hover::after {
    opacity: 1;
}

/* Loading animation */
.media-gallery-item img,
.media-gallery-item video {
    animation: fadeInMedia 0.5s ease-in-out;
}

@keyframes fadeInMedia {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .media-gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 8px;
    }
    
    .video-play-icon {
        font-size: 36px;
    }
    
    .media-gallery-item:hover {
        transform: scale(1.02);
    }
}

@media (max-width: 480px) {
    .media-gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="row">
    <div class="col-lg-1 d-none d-lg-block"></div>
    <div class="col-lg-10 col-xl-9 col-12 px-3 px-lg-4">
        <div class="profile-container">
            @if($isFlipsideView)
            <div class="flipside-mode-header">
                <a href="{{ auth()->id() === $user->id ? '/profile' : route('profilePage', $user->username) }}"
                class="back-to-normal-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Profile</span>
                </a>
                <div class="flipside-mode-badge">
                    <span>🔥</span>
                    <span>FLIPSIDE MODE</span>
                </div>
            </div>
            @endif

            <!-- Cover Section --> 
            <div class="cover-section"
                 @if($user->cover)
                 style="background-image: url('/storage/{{ $user->cover }}'); background-size: cover; background-position: center; background-repeat: no-repeat; animation: none !important;"
                 @endif>
                @if($user->cover)
                <div class="cover-view-btn" onclick="viewCoverFullSize(event)">
                    <i class="fas fa-expand"></i>
                    <span>View Cover</span>
                </div>
                @endif
            </div>

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar-container">
                    <div class="avatar" onclick="viewAvatarFullSize()">
                        @if($isFlipsideView && $user->flipside_avatar)
                            <img src="{{ asset('storage/' . $user->flipside_avatar) }}" 
                                 alt="Flipside Avatar" 
                                 class="avatar-img">
                        @elseif($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                 alt="Avatar {{ $user->name }}" 
                                 class="avatar-img">
                        @else
                            <div class="avatar-fallback">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="profile-info">
                    <h1 class="profile-name">
                        @if($isFlipsideView && $user->flipside_name)
                            {{ $user->flipside_name }}
                        @else
                            {{ $user->name }}
                        @endif
                    </h1>
                    <p class="profile-username">{{ '@' . $user->username }}</p>
                    <p class="profile-bio">
                        {!! nl2br(e($user->bio ?? 'Belum ada bio.')) !!}
                    </p>

                    @if(!$isFlipsideView && auth()->check() && auth()->id() !== $user->id)
                    <div class="profile-menu-container">
                        <button class="profile-menu-btn" onclick="toggleProfileMenu(event)" title="More options">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        
                        <div class="profile-dropdown-menu" id="profileDropdownMenu">
                            <div class="dropdown-menu-item" onclick="shareProfile('{{ route('profilePage', $user->username) }}')">
                                <i class="fas fa-share"></i>
                                <span>Share Profile</span>
                            </div>
                            
                            <div class="dropdown-menu-item" onclick="copyProfileLink('{{ route('profilePage', $user->username) }}')">
                                <i class="fas fa-link"></i>
                                <span>Copy Link</span>
                            </div>
                            
                            <div class="dropdown-menu-divider"></div>
                            
                            <div class="dropdown-menu-item" onclick="reportUser({{ $user->id }})">
                                <i class="fas fa-flag"></i>
                                <span>Report User</span>
                            </div>
                            
                            <div class="dropdown-menu-item danger" onclick="toggleBlock({{ $user->id }})">
                                <i class="fas {{ $isUserBlocked ?? false ? 'fa-user-check' : 'fa-ban' }}"></i>
                                <span id="blockMenuText">{{ $isUserBlocked ?? false ? 'Unblock User' : 'Block User' }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                 <div class="profile-actions">
    @if(!$isFlipsideView)

        @if(auth()->check() && auth()->id() !== $user->id)
            <!-- Follow Button -->
            <button class="btn-profile btn-primary {{ $isFollowing ? 'following' : '' }}" 
                    id="followBtn" onclick="toggleFollow({{ $user->id }})">
                <span class="follow-text">
                    {{ $isFollowing ? 'Following' : 'Follow' }}
                </span>
            </button>

            <!-- Message Button -->
            <!-- <button class="btn-profile btn-secondary" onclick="sendMessage({{ $user->id }})">
                <i class="far fa-envelope"></i> Pesan
            </button> -->

            <!-- Flipside Access -->
            @if($hasFlipsideAccess)
                <a href="{{ route('profilePage', $user->username) }}?view=flipside" class="btn-profile btn-flipside">
                    <i class="fas fa-unlock"></i> View Flipside 🔥
                </a>
            @else
                <button class="btn-profile btn-flipside locked" title="You don't have access to Flipside" disabled>
                    <i class="fas fa-lock"></i> Flipside Locked 🔒
                </button>
            @endif
        @endif

    @endif
</div>

                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" id="postsCount">{{ count($displayPosts) }}</div>
                        <div class="stat-label">{{ $isFlipsideView ? 'Flipside Posts' : 'Posts' }}</div>
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
                        <div class="stat-number">{{ $likesCount ?? 0 }}</div>
                        <div class="stat-label">Likes</div>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="content-tabs">
                <div class="tab-list">
                    <div class="tab-item active" onclick="showTab('posts')">
                        {{ $isFlipsideView ? '🔥 Flipside Posts' : '📷 Posts' }}
                    </div>
                    @if(!$isFlipsideView)
                    <div class="tab-item" onclick="showTab('media')">🖼️ Media</div>
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Posts Content -->
                <div id="posts-content">
                    <div class="posts-stream">
                        @forelse($displayPosts as $post)
                            <div class="post-item" data-post-id="{{ $post->id }}">
                                @if($isFlipsideView)
                                <span class="post-flipside-badge">🔒 Flipside</span>
                                @endif

                                <div class="d-flex align-items-center mb-2">
                                    @php
                                        $postUserProfileLink = auth()->check() && auth()->id() === $post->user->id 
                                            ? '/profile' 
                                            : '/profilePage/' . $post->user->username;
                                    @endphp
                                    
                                    <a href="{{ $postUserProfileLink }}" style="text-decoration: none;">
                                        @if($post->user->avatar)
                                            <img src="{{ asset('storage/' . $post->user->avatar) }}" 
                                                 alt="{{ $post->user->name }}"
                                                 class="rounded-circle me-2" 
                                                 style="width:40px; height:40px; object-fit:cover; cursor: pointer;">
                                        @else
                                            <div class="rounded-circle me-2 d-flex justify-content-center align-items-center {{ $isFlipsideView ? 'text-white' : 'bg-primary text-white' }}"
                                                 style="width:40px; height:40px; {{ $isFlipsideView ? 'background: linear-gradient(135deg, #FF0080, #7928CA);' : '' }} font-weight: bold; cursor: pointer;">
                                                {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <div>
                                        <a href="{{ $postUserProfileLink }}" style="text-decoration: none;">
                                            <strong style="color: {{ $isFlipsideView ? 'white' : 'inherit' }}; cursor: pointer;">{{ $post->user->name }}</strong>
                                        </a>
                                        <a href="{{ $postUserProfileLink }}" style="text-decoration: none;">
                                            <span style="color: {{ $isFlipsideView ? 'rgba(255,255,255,0.6)' : '#666' }}; cursor: pointer;">{{ '@' . $post->user->username }}</span>
                                        </a>
                                        <span style="color: {{ $isFlipsideView ? 'rgba(255,255,255,0.5)' : '#666' }};">· {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                <div class="post-content mb-2">
                                    {!! nl2br(e($post->caption)) !!}
                                   {{-- Multi-media support --}}
@if($post->media && $post->media->count() > 0)
<div class="mt-3 media-grid media-count-{{ $post->media->count() }}">
@foreach($post->media as $medium)
@php
$ext = strtolower(pathinfo($medium->file_path, PATHINFO_EXTENSION));
@endphp
<div class="media-item">
{{-- IMAGE --}}
@if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
<img
src="{{ asset('storage/' . $medium->file_path) }}"
alt="Post image"
onclick="openImageModal('{{ asset('storage/' . $medium->file_path) }}')"
>
{{-- VIDEO --}}
@elseif(in_array($ext, ['mp4', 'mov', 'webm']))
<div class="post-video-container">
<video muted playsinline preload="metadata" controls>
<source src="{{ asset('storage/' . $medium->file_path) }}" type="{{ $medium->mime_type }}">
</video>
<button class="mute-btn">🔇</button>
</div>
@endif
</div>
@endforeach
</div>
@endif
                                </div>

                                <!-- Post Interactions -->
                                <div class="post-interactions">
                                    <button class="interaction-btn 
                                        {{ $isFlipside 
                                            ? ($post->flipsideLikes && $post->flipsideLikes->where('user_id', auth()->id())->count() > 0 ? 'liked' : '') 
                                            : ($post->likes && $post->likes->where('user_id', auth()->id())->count() > 0 ? 'liked' : '') 
                                        }}"
                                        onclick="toggleLike({{ $post->id }}, this)"
                                    >
                                        <i class="fas fa-heart"></i>
                                        <span>
                                            {{ $isFlipside 
                                                ? ($post->flipsideLikes ? $post->flipsideLikes->count() : 0) 
                                                : ($post->likes ? $post->likes->count() : 0) 
                                            }}
                                        </span>
                                    </button>

                                    <button class="interaction-btn" onclick="openComments({{ $post->id }})">
                                        <i class="fas fa-comment"></i>
                                        <span>
                                            {{ $isFlipside
                                                ? ($post->flipsideComments?->count() ?? 0)
                                                : ($post->comments?->count() ?? 0)
                                            }}
                                        </span>                                    
                                    </button>
                                    
                                    <button class="interaction-btn" onclick="sharePost({{ $post->id }})">
                                        <i class="fas fa-share"></i>
                                        Share
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas {{ $isFlipsideView ? 'fa-ghost' : 'fa-camera' }}"></i>
                                <h4>{{ $isFlipsideView ? 'Belum ada konten Flipside' : 'Belum ada postingan' }}</h4>
                                <p>{{ $isFlipsideView ? 'Konten secret belum ada.' : 'Postingan akan muncul di sini.' }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Media Content -->
   <!-- Media Content -->
@if(!$isFlipsideView)
<div id="media-content" class="hidden">
    @if($allMedia && $allMedia->count() > 0)
        <div class="media-gallery-grid">
            @foreach($allMedia as $media)
                @php
                    $ext = strtolower(pathinfo($media->file_path, PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'mov', 'webm']);
                    $src = $media->url;
                @endphp
                
                <div class="media-gallery-item {{ $isVideo ? 'has-video' : '' }}">
                    @if(!$isVideo)
                        <img src="{{ $src }}" 
                             alt="Media"
                             onclick="openImageModal('{{ $src }}')">
                    @else
                        <div class="post-video-container">
                            <video controls muted playsinline>
                                <source src="{{ $src }}" type="{{ $media->mime_type ?? 'video/mp4' }}">
                            </video>
                            <span class="video-play-icon">▶</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-images"></i>
            <h4>Belum ada media</h4>
            <p>Media dari postingan akan muncul di sini.</p>
        </div>
    @endif
</div>
@endif
            </div>
        </div>
    </div>
</div>

<!-- Followers / Following Modal -->
<div id="userListModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:3000;align-items:center;justify-content:center;backdrop-filter:blur(5px);">
    <div style="background:{{ $isFlipsideView ? '#1a1a1a' : 'white' }};border-radius:20px;width:90%;max-width:600px;max-height:80vh;display:flex;flex-direction:column;border:{{ $isFlipsideView ? '1px solid rgba(255, 0, 128, 0.2)' : 'none' }};box-shadow:0 20px 60px rgba(0, 0, 0, 0.3);">
        
        <!-- Modal Header -->
        <div style="padding:20px;border-bottom:1px solid {{ $isFlipsideView ? 'rgba(255, 255, 255, 0.1)' : '#e1e8ed' }};display:flex;justify-content:space-between;align-items:center;">
            <h3 id="userListTitle" style="margin:0;color:{{ $isFlipsideView ? 'white' : '#1a1a1a' }};font-weight:700;font-size:20px;">
                Users
            </h3>
            <button onclick="closeUserListModal()" 
                    style="background:none;border:none;font-size:24px;cursor:pointer;color:{{ $isFlipsideView ? 'rgba(255, 255, 255, 0.6)' : '#666' }};transition:all 0.3s ease;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;"
                    onmouseover="this.style.background='{{ $isFlipsideView ? 'rgba(255, 0, 128, 0.2)' : 'rgba(0, 0, 0, 0.05)' }}'"
                    onmouseout="this.style.background='none'">
                ×
            </button>
        </div>

        <!-- Modal Body -->
        <div id="userListContainer" style="flex:1;overflow-y:auto;padding:20px;">
            <!-- Users will be rendered here -->
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal">
    <button class="image-modal-close" onclick="closeImageModal()">&times;</button>
    <div class="image-modal-content">
        <img id="modalImage" src="" alt="Full size image">
    </div>
</div>

<script>
    // Initialize variables
    const isFlipsideView = {{ $isFlipsideView ? 'true' : 'false' }};
    const isOwnProfile = {{ $isOwnProfile ? 'true' : 'false' }};
    let isUserFollowing = {{ ($isFollowing ?? false) ? 'true' : 'false' }};
    
    // Toggle like functionality
    async function toggleLike(postId, button) {
        const icon = button.querySelector('i');
        const count = button.querySelector('span');
        let currentCount = parseInt(count.textContent);
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const liked = button.classList.contains("liked");
        
        const type = window.appData?.isFlipside ? 'flipside' : 'main';
        button.disabled = true;

        try {
            const endpoint = liked 
                ? `/like/destroy/${postId}/${type}`
                : `/like/store/${postId}/${type}`;

            const response = await fetch(endpoint, {
                method: liked ? "DELETE" : "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });

            const result = await response.json();

            if (response.ok) {
                if (!liked) {
                    button.classList.add("liked");
                    count.textContent = currentCount + 1;
                    showNotification("❤️ Liked!");
                } else {
                    button.classList.remove("liked");
                    count.textContent = Math.max(0, currentCount - 1);
                    showNotification("💔 Unliked");
                }
            } else {
                if (response.status === 409) {
                    showNotification(liked ? "⚠️ Already unliked" : "⚠️ Already liked");
                } else {
                    throw new Error(result.message || "Failed to toggle like");
                }
            }
        } catch (error) {
            console.error("Like error:", error);
            showNotification("⚠️ Network error!");
        } finally {
            button.disabled = false;
        }
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
        document.querySelectorAll('[id$="-content"]').forEach(content => {
            content.classList.add('hidden');
        });

        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.classList.remove('active');
        });

        const targetContent = document.getElementById(tabName + '-content');
        const targetTab = event.target;
        
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }
        if (targetTab) {
            targetTab.classList.add('active');
        }
    }

    // Image modal functions
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // View avatar full size
    function viewAvatarFullSize() {
        const isFlipside = window.appData.isFlipside;
        const user = window.appData.user;
        
        let avatarSrc = null;
        
        if (isFlipside && user.flipside_avatar) {
            avatarSrc = '/storage/' + user.flipside_avatar;
        } else if (user.avatar) {
            avatarSrc = '/storage/' + user.avatar;
        }
        
        if (avatarSrc) {
            openImageModal(avatarSrc);
        } else {
            showNotification('⚠️ No avatar to view');
        }
    }

    // View cover full size
    function viewCoverFullSize(event) {
        event.stopPropagation();
        const user = window.appData.user;
        
        if (user.cover) {
            openImageModal('/storage/' + user.cover);
        } else {
            showNotification('⚠️ No cover image to view');
        }
    }

    // Comments functionality
    async function openComments(postId) {
        try {
            const type = window.appData.isFlipside ? 'flipside' : 'main';
            
            console.log('Opening comments for post:', postId, 'type:', type);
            
            const response = await fetch(`/comment/index/${postId}/${type}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Comments result:', result);
            
            let comments = result.data || result.comments || [];

            showCommentsModal(postId, comments);
        } catch (error) {
            console.error('Error fetching comments:', error);
            showNotification('❌ Gagal memuat komentar');
        }
    }

    function showCommentsModal(postId, comments) {
        const modal = document.createElement('div');
        modal.id = 'commentsModal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 3000;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        `;

        const isFlipside = window.appData?.isFlipside || false;
        
        // Build comments HTML
        let commentsHtml = '';
        
        if (comments.length > 0) {
            comments.forEach(comment => {
                const avatarSrc = comment.user && comment.user.active_avatar 
                    ? '/storage/' + comment.user.active_avatar 
                    : null;
                    
                const avatar = avatarSrc
                    ? `<img src="${avatarSrc}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`
                    : `<div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg, #FF0080, #7928CA);color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;">${((comment.user && comment.user.name) ? comment.user.name.substring(0,2).toUpperCase() : '?')}</div>`;

                const deleteBtn = comment.user_id === {{ auth()->id() ?? 0 }} 
                    ? `<button onclick="deleteComment(${comment.id}, ${postId})" style="background:none;border:none;color:${isFlipside ? 'rgba(255,0,128,0.8)' : '#e74c3c'};cursor:pointer;padding:4px 8px;border-radius:4px;font-size:12px;margin-left:10px;transition:all 0.3s ease;" title="Delete comment" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fas fa-trash"></i></button>`
                    : '';
                
                commentsHtml += `
                    <div class="comment-item comment-item-${comment.id}" style="padding:15px 0;border-bottom:1px solid ${isFlipside ? 'rgba(255, 255, 255, 0.1)' : '#eee'};">
                        <div style="display:flex;align-items:start;gap:12px;">
                            ${avatar}
                            <div style="flex:1;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;">
                                    <div>
                                        <span style="font-weight:600;font-size:14px;color:${isFlipside ? 'white' : '#1a1a1a'};">${comment.user ? comment.user.name : 'Anonymous'}</span>
                                        <span style="color:${isFlipside ? 'rgba(255,255,255,0.5)' : '#666'};font-size:12px;margin-left:8px;">@${comment.user ? comment.user.username : 'unknown'}</span>
                                        <span style="color:${isFlipside ? 'rgba(255,255,255,0.4)' : '#999'};font-size:12px;margin-left:8px;">· ${formatTimeAgo(comment.created_at)}</span>
                                    </div>
                                    ${deleteBtn}
                                </div>
                                <p style="margin:0;font-size:14px;line-height:1.5;color:${isFlipside ? 'rgba(255,255,255,0.9)' : '#333'};">
                                    ${comment.content}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            commentsHtml = `
                <div style="text-align:center;padding:40px 20px;color:${isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};">
                    <i class="fas fa-comments" style="font-size:3rem;opacity:0.3;margin-bottom:15px;display:block;"></i>
                    <p style="margin:0;">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                </div>
            `;
        }
        
        modal.innerHTML = `
            <div style="background: ${isFlipside ? '#1a1a1a' : 'white'}; 
                        border-radius: 20px; 
                        width: 90%; 
                        max-width: 600px; 
                        max-height: 80vh; 
                        display: flex; 
                        flex-direction: column;
                        border: ${isFlipside ? '1px solid rgba(255, 0, 128, 0.2)' : 'none'};
                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
                
                <div style="padding: 20px; border-bottom: 1px solid ${isFlipside ? 'rgba(255, 255, 255, 0.1)' : '#e1e8ed'}; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; color: ${isFlipside ? 'white' : '#1a1a1a'}; font-weight: 700;">
                        💬 Comments (${comments.length})
                    </h3>
                    <button onclick="closeCommentsModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: ${isFlipside ? 'rgba(255, 255, 255, 0.6)' : '#666'}; transition: all 0.3s ease; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(0, 0, 0, 0.05)'}'" onmouseout="this.style.background='none'">
                        ×
                    </button>
                </div>

                <div id="commentsContainer" style="flex: 1; overflow-y: auto; padding: 20px;">
                    ${commentsHtml}
                </div>

                <div style="padding: 20px; border-top: 1px solid ${isFlipside ? 'rgba(255, 255, 255, 0.1)' : '#e1e8ed'};">
                    <form onsubmit="submitComment(event, ${postId})" style="display: flex; gap: 12px; align-items: flex-start;">
                        <textarea 
                            id="commentInput-${postId}" 
                            placeholder="Tulis komentar..." 
                            required
                            rows="2"
                            style="flex: 1; 
                                   padding: 12px 16px; 
                                   border: 2px solid ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : '#e1e8ed'}; 
                                   border-radius: 12px; 
                                   outline: none; 
                                   background: ${isFlipside ? '#0d0d0d' : 'white'};
                                   color: ${isFlipside ? 'white' : '#1a1a1a'};
                                   transition: all 0.3s ease;
                                   font-family: inherit;
                                   resize: vertical;
                                   min-height: 45px;"
                            onfocus="this.style.borderColor='${isFlipside ? '#FF0080' : '#1da1f2'}'"
                            onblur="this.style.borderColor='${isFlipside ? 'rgba(255, 0, 128, 0.2)' : '#e1e8ed'}'"></textarea>
                        <button type="submit" 
                                style="background: ${isFlipside ? 'linear-gradient(135deg, #FF0080, #7928CA)' : 'linear-gradient(135deg, #1da1f2, #0d8bd9)'}; 
                                       color: white; 
                                       border: none; 
                                       padding: 12px 24px; 
                                       border-radius: 12px; 
                                       font-weight: 600; 
                                       cursor: pointer; 
                                       transition: all 0.3s ease;
                                       white-space: nowrap;
                                       align-self: flex-start;"
                                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px ${isFlipside ? 'rgba(255, 0, 128, 0.4)' : 'rgba(29, 161, 242, 0.4)'}'"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                            <i class="fas fa-paper-plane"></i> Post
                        </button>
                    </form>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
    }

    function closeCommentsModal() {
        const modal = document.getElementById('commentsModal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    }

    async function submitComment(event, postId) {
        event.preventDefault();
        
        const commentInput = document.getElementById('commentInput-' + postId);
        const text = commentInput.value.trim();

        if (!text) {
            showNotification("⚠️ Komentar tidak boleh kosong!");
            return;
        }

        commentInput.disabled = true;

        try {
            const type = window.appData.isFlipside ? 'flipside' : 'main';
            
            console.log('Submitting comment:', { post_id: postId, content: text, type: type });
            
            const response = await fetch('/comment/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    post_id: postId,
                    content: text,
                    type: type
                })
            });

            const result = await response.json();
            
            console.log('Comment response:', result);

            if (response.ok && result.success) {
                commentInput.value = '';
                showNotification('✅ Komentar berhasil dikirim!');
                
                const postItem = document.querySelector(`[data-post-id="${postId}"]`);
                if (postItem) {
                    const commentBtn = postItem.querySelector('.interaction-btn:nth-child(2) span');
                    if (commentBtn) {
                        const currentCount = parseInt(commentBtn.textContent) || 0;
                        commentBtn.textContent = currentCount + 1;
                    }
                }
                
                setTimeout(() => {
                    closeCommentsModal();
                    openComments(postId);
                }, 1000);
            } else {
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join(', ');
                    showNotification('❌ ' + errorMessages);
                } else {
                    showNotification('❌ ' + (result.message || 'Failed to post comment'));
                }
            }
        } catch (error) {
            console.error('Error submitting comment:', error);
            showNotification("❌ Terjadi kesalahan jaringan");
        } finally {
            commentInput.disabled = false;
        }
    }

    async function deleteComment(commentId, postId) {
        if (!confirm('Hapus komentar ini?')) return;

        try {
            const type = window.appData.isFlipside ? 'flipside' : 'main';
            
            const response = await fetch(`/comment/destroy/${commentId}/${type}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showNotification('✅ Komentar berhasil dihapus!');
                
                const commentElement = document.querySelector(`.comment-item-${commentId}`);
                if (commentElement) {
                    commentElement.style.transition = 'all 0.3s ease';
                    commentElement.style.opacity = '0';
                    commentElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => commentElement.remove(), 300);
                }
                
                const postItem = document.querySelector(`[data-post-id="${postId}"]`);
                if (postItem) {
                    const commentBtn = postItem.querySelector('.interaction-btn:nth-child(2) span');
                    if (commentBtn) {
                        const currentCount = parseInt(commentBtn.textContent) || 0;
                        commentBtn.textContent = Math.max(0, currentCount - 1);
                    }
                }
                
                const modalTitle = document.querySelector('#commentsModal h3');
                if (modalTitle) {
                    const match = modalTitle.textContent.match(/\((\d+)\)/);
                    if (match) {
                        const newCount = Math.max(0, parseInt(match[1]) - 1);
                        modalTitle.textContent = `💬 Comments (${newCount})`;
                    }
                }
            } else {
                showNotification('❌ ' + (result.message || 'Failed to delete comment'));
            }
        } catch (error) {
            console.error('Error deleting comment:', error);
            showNotification('❌ Terjadi kesalahan jaringan');
        }
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h';
        if (seconds < 604800) return Math.floor(seconds / 86400) + 'd';
        return Math.floor(seconds / 604800) + 'w';
    }

    // Share post
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

    // Modal functions
    function openUserListModal() {
        const modal = document.getElementById("userListModal");
        if (modal) {
            modal.classList.remove("hidden");
            modal.style.display = "flex";
            document.body.style.overflow = 'hidden';
        }
    }

    function closeUserListModal() {
        const modal = document.getElementById("userListModal");
        if (modal) {
            modal.classList.add("hidden");
            modal.style.display = "none";
            document.body.style.overflow = 'auto';
        }
    }

function renderUserList(users, title, isFollowingList = false) {
    const container = document.getElementById("userListContainer");
    const modalTitle = document.getElementById("userListTitle");
    const isFlipside = window.appData.isFlipside;

    modalTitle.innerText = title;
    container.innerHTML = "";

    if (!users || users.length === 0) {
        container.innerHTML = `
            <div style="text-align:center;padding:40px;color:${isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};">
                <i class="fas fa-users" style="font-size:48px;margin-bottom:16px;display:block;"></i>
                <p style="font-size:16px;margin:0;">No ${title.toLowerCase()}</p>
            </div>
        `;
        openUserListModal();
        return;
    }

    users.forEach(user => {
        const userData = user.follower || user.following || user;
        const userId = userData.id;
        const userName = userData.name || 'Unknown';
        const userUsername = userData.username || 'unknown';
        
        const loggedInUsername = window.appData.user.username;

        let profileLink = `/profilePage/${userUsername}`;
        if (userUsername === loggedInUsername) {
            profileLink = `/profile`;
        }
        
        const avatar = userData.avatar
            ? `/storage/${userData.avatar}`
            : `https://ui-avatars.com/api/?name=${userName}&background=667eea&color=fff`;

        let actionButton = '';
        
        if (window.appData.isOwnProfile) {
            if (isFollowingList) {
                actionButton = `
                    <button onclick="unfollowUser(${userId}, this)" 
                            style="padding:8px 16px;background-color:#10b981;color:white;border:none;border-radius:9999px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#ef4444';this.textContent='Unfollow'"
                            onmouseout="this.style.backgroundColor='#10b981';this.textContent='Following'">
                        Following
                    </button>
                `;
            } else {
                actionButton = `
                    <button onclick="removeFollower(${userId}, this)" 
                            style="padding:8px 16px;background-color:#fee2e2;color:#dc2626;border:none;border-radius:9999px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#fecaca'"
                            onmouseout="this.style.backgroundColor='#fee2e2'">
                        Remove
                    </button>
                `;
            }
        } else if (userId !== {{ auth()->id() ?? 0 }}) {
            const isFollowing = window.appData.following && 
                               window.appData.following.some(f => {
                                   const followingUser = f.following || f;
                                   return followingUser.id === userId;
                               });
            
            if (isFollowing) {
                actionButton = `
                    <button onclick="unfollowUser(${userId}, this)" 
                            style="padding:8px 16px;background-color:#10b981;color:white;border:none;border-radius:9999px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#ef4444';this.textContent='Unfollow'"
                            onmouseout="this.style.backgroundColor='#10b981';this.textContent='Following'">
                        Following
                    </button>
                `;
            } else {
                actionButton = `
                    <button onclick="followUser(${userId}, this)" 
                            style="padding:8px 16px;background-color:#3b82f6;color:white;border:none;border-radius:9999px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#2563eb'"
                            onmouseout="this.style.backgroundColor='#3b82f6'">
                        Follow
                    </button>
                `;
            }
        }

        container.innerHTML += `
            <div class="user-list-item" data-user-id="${userId}" 
                 style="display:flex;align-items:center;justify-content:space-between;padding:12px;background:${isFlipside ? '#1a1a1a' : 'white'};border:1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : '#e5e7eb'};border-radius:12px;margin-bottom:10px;transition:all 0.3s ease;">
                <div style="display:flex;align-items:center;gap:12px;flex:1;">
                    <a href="${profileLink}" onclick="closeUserListModal()" style="display:inline-block;width:48px;height:48px;overflow:hidden;border-radius:50%;border:2px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : '#e5e7eb'};">
                        <img src="${avatar}" 
                             style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </a>
                    <div style="flex:1;">
                        <a href="${profileLink}" onclick="closeUserListModal()" style="text-decoration:none;">
                            <div style="font-weight:600;font-size:15px;color:${isFlipside ? 'white' : '#1a1a1a'};">
                                ${userName}
                            </div>
                        </a>
                        <div style="color:${isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};font-size:13px;">@${userUsername}</div>
                    </div>
                </div>
                ${actionButton}
            </div>
        `;
    });

    openUserListModal();
}

    function showFollowers() {
        const followers = window.appData.followers || [];
        console.log('Followers data:', followers);
        renderUserList(followers, "Followers", false);
    }

    function showFollowing() {
        const following = window.appData.following || [];
        console.log('Following data:', following);
        renderUserList(following, "Following", true);
    }

    async function followUser(userId, button) {
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const response = await fetch(`/follow/store/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success && result.following) {
                button.className = 'px-4 py-2 bg-green-500 hover:bg-red-500 text-white rounded-full text-sm font-semibold transition-all duration-300';
                button.innerHTML = 'Following';
                button.setAttribute('onclick', `unfollowUser(${userId}, this)`);
                button.onmouseover = function() { this.textContent = 'Unfollow'; };
                button.onmouseout = function() { this.textContent = 'Following'; };
                
                showNotification('✅ Now following!');
                
                const followingCount = document.getElementById('followingCount');
                if (followingCount) {
                    const currentCount = parseInt(followingCount.textContent.replace(/,/g, ''));
                    followingCount.textContent = (currentCount + 1).toLocaleString();
                }
            } else {
                throw new Error('Failed to follow');
            }
        } catch (error) {
            console.error('Follow error:', error);
            button.innerHTML = originalHTML;
            showNotification('❌ Failed to follow');
        } finally {
            button.disabled = false;
        }
    }

    async function unfollowUser(userId, button) {
        if (!confirm('Unfollow this user?')) return;
        
        const userItem = button.closest('.user-list-item');
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const response = await fetch(`/follow/destroy/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.message === 'unfoll success') {
                if (userItem) {
                    userItem.style.transition = 'all 0.3s ease';
                    userItem.style.opacity = '0';
                    userItem.style.transform = 'translateX(100%)';
                    
                    setTimeout(() => {
                        userItem.remove();
                        
                        const container = document.getElementById('userListContainer');
                        const remaining = container.querySelectorAll('.user-list-item').length;
                        
                        const modalTitle = document.getElementById('userListTitle');
                        if (modalTitle) {
                            const titleText = modalTitle.textContent.split('(')[0].trim();
                            modalTitle.textContent = `${titleText} (${remaining})`;
                        }
                        
                        if (remaining === 0) {
                            const isFlipside = window.appData.isFlipside;
                            const titleText = modalTitle.textContent.includes('Following') ? 'Following' : 'Followers';
                            container.innerHTML = `
                                <div style="text-align:center;padding:40px 20px;color:${isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};">
                                    <i class="fas fa-user-slash" style="font-size:3rem;opacity:0.3;margin-bottom:15px;display:block;"></i>
                                    <p style="margin:0;">${titleText === 'Following' ? 'Belum mengikuti siapa pun' : 'Belum ada followers'}</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
                
                showNotification('✅ Unfollowed successfully');
                
                const followingCount = document.getElementById('followingCount');
                if (followingCount) {
                    const currentCount = parseInt(followingCount.textContent.replace(/,/g, ''));
                    followingCount.textContent = Math.max(0, currentCount - 1).toLocaleString();
                }
            } else {
                throw new Error('Failed to unfollow');
            }
        } catch (error) {
            console.error('Unfollow error:', error);
            button.innerHTML = originalHTML;
            button.disabled = false;
            showNotification('❌ Failed to unfollow');
        }
    }

    async function removeFollower(userId, button) {
        if (!confirm('Remove this follower?')) return;
        
        const userItem = button.closest('.user-list-item');
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const response = await fetch(`/follow/remove/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                if (userItem) {
                    userItem.style.transition = 'all 0.3s ease';
                    userItem.style.opacity = '0';
                    userItem.style.transform = 'translateX(100%)';
                    
                    setTimeout(() => {
                        userItem.remove();
                        
                        const container = document.getElementById('userListContainer');
                        const remaining = container.querySelectorAll('.user-list-item').length;
                        
                        const modalTitle = document.getElementById('userListTitle');
                        if (modalTitle) {
                            modalTitle.textContent = `Followers (${remaining})`;
                        }
                        
                        const followersCount = document.getElementById('followersCount');
                        if (followersCount) {
                            const currentCount = parseInt(followersCount.textContent.replace(/,/g, ''));
                            followersCount.textContent = Math.max(0, currentCount - 1).toLocaleString();
                        }
                        
                        if (remaining === 0) {
                            const isFlipside = window.appData.isFlipside;
                            container.innerHTML = `
                                <div style="text-align:center;padding:40px 20px;color:${isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};">
                                    <i class="fas fa-user-slash" style="font-size:3rem;opacity:0.3;margin-bottom:15px;display:block;"></i>
                                    <p style="margin:0;">Belum ada followers</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
                
                showNotification('✅ ' + result.message);
            } else {
                throw new Error(result.message || 'Failed to remove follower');
            }
        } catch (error) {
            console.error('Remove follower error:', error);
            button.innerHTML = originalHTML;
            button.disabled = false;
            showNotification('❌ ' + (error.message || 'Failed to remove follower'));
        }
    }

    // Send message
    function sendMessage(userId) {
        showNotification('📩 Message feature coming soon!');
    }

    // Share profile
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

    // Profile menu functions
    function toggleProfileMenu(event) {
        event.stopPropagation();
        const menu = document.getElementById('profileDropdownMenu');
        menu.classList.toggle('show');
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('profileDropdownMenu');
        const menuContainer = document.querySelector('.profile-menu-container');
        
        if (menu && menuContainer && !menuContainer.contains(event.target)) {
            menu.classList.remove('show');
        }
    });

    function copyProfileLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('🔗 Profile link copied to clipboard!');
            document.getElementById('profileDropdownMenu').classList.remove('show');
        });
    }

    function reportUser(userId) {
        document.getElementById('profileDropdownMenu').classList.remove('show');
        showNotification('📝 Report feature coming soon!');
    }

    async function toggleBlock(userId) {
        const menu = document.getElementById('profileDropdownMenu');
        menu.classList.remove('show');
        
        const isBlocked = {{ $isUserBlocked ?? false ? 'true' : 'false' }};
        
        const confirmMessage = isBlocked 
            ? 'Are you sure you want to unblock this user?' 
            : 'Are you sure you want to block this user?\n\nBlocking will:\n• Remove follow relationships\n• Hide their posts from your feed\n• Prevent them from seeing your profile\n• Block all interactions';
        
        if (!confirm(confirmMessage)) {
            return;
        }
        
        const blockMenuText = document.getElementById('blockMenuText');
        const originalText = blockMenuText ? blockMenuText.textContent : '';
        
        if (blockMenuText) {
            blockMenuText.textContent = 'Processing...';
        }
        
        try {
            const response = await fetch(`/block/store/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (result.blocked) {
                    showNotification('🚫 User blocked successfully');
                    
                    if (blockMenuText) {
                        blockMenuText.textContent = 'Unblock User';
                        const icon = blockMenuText.previousElementSibling;
                        if (icon) {
                            icon.className = 'fas fa-user-check';
                        }
                    }
                    
                      setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification('✅ User unblocked successfully');
                    
                    if (blockMenuText) {
                        blockMenuText.textContent = 'Block User';
                        const icon = blockMenuText.previousElementSibling;
                        if (icon) {
                            icon.className = 'fas fa-ban';
                        }
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                throw new Error(result.message || 'Failed to toggle block');
            }
        } catch (error) {
            console.error('Block error:', error);
            if (blockMenuText) {
                blockMenuText.textContent = originalText;
            }
            showNotification('❌ Error: ' + error.message);
        }
    }

    // Notification function
    function showNotification(message) {
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
    }

    // Close image modal on click outside
    window.onclick = function(event) {
        const imageModal = document.getElementById('imageModal');
        if (event.target === imageModal) {
            closeImageModal();
        }
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeCommentsModal();
            closeUserListModal();
        }
    });

    // Animate posts on page load
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