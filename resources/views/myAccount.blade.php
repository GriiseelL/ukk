@php
$isFlipside = request()->is('flipside*') || (isset($flipsidePosts) && count($flipsidePosts) > 0);

$mappedFollowers = $followers->map(function($f) {
    return [
        'id' => $f->follower->id ?? null,
        'name' => $f->follower->name ?? '',
        'username' => $f->follower->username ?? '',
        'avatar' => $f->follower->avatar ?? null,
    ];
});


    // Tentukan cover image berdasarkan mode
    $activeCover = $isFlipside 
        ? ($user->flipside_cover ?? null) 
        : ($user->cover ?? null);

$mappedFollowing = $following->map(function($f) {
    return [
        'id' => $f->following->id ?? null,
        'name' => $f->following->name ?? '',
        'username' => $f->following->username ?? '',
        'avatar' => $f->following->avatar ?? null,
    ];
});

$displayPosts = $isFlipside ? ($flipsidePosts ?? []) : ($posts ?? []);
@endphp

@extends('layout.layarUtama')

@section('title', ($isFlipside ? 'Flipside' : 'Profil Saya') . ' - Telava')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


<script>
    window.appData = {
        followers: @json($mappedFollowers),
        following: @json($mappedFollowing),
        followingIds: @json($followingIds ?? []),
        posts: @json($displayPosts),
        user: @json($user ?? []),
        isFlipside: {{ $isFlipside ? 'true' : 'false' }}
    };
</script>

<style>
    :root {
        --normal-bg: #ffffff;
        --normal-bg-secondary: rgba(0, 0, 0, 0.02);
        --normal-text: #1a1a1a;
        --normal-text-muted: #666;
        --normal-border: rgba(0, 0, 0, 0.05);
        --normal-gradient: linear-gradient(45deg, #667eea, #764ba2, #45b7d1);

        --flipside-bg: #0d0d0d;
        --flipside-bg-secondary: #1a1a1a;
        --flipside-text: #ffffff;
        --flipside-text-muted: rgba(255, 255, 255, 0.5);
        --flipside-border: rgba(255, 255, 255, 0.1);
        --flipside-gradient: linear-gradient(135deg, #FF0080 0%, #7928CA 50%, #FF0080 100%);
    }

    body {
        background: {{ $isFlipside ? '#f5f5f5' : '#f5f5f5' }};
        transition: background 0.5s ease;
    }

    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
    }

    .profile-container {
        max-width: 900px;
        margin: 0 auto;
        background: {{ $isFlipside ? '#0d0d0d' : 'white' }};
        border-radius: 20px;
        overflow: hidden;
        box-shadow: {{ $isFlipside ? '0 8px 32px rgba(255, 0, 128, 0.3)' : '0 8px 32px rgba(0, 0, 0, 0.1)' }};
        border: {{ $isFlipside ? '1px solid rgba(255, 0, 128, 0.2)' : 'none' }};
        animation: slideUp 0.6s ease-out;
    }

    /* FLIPSIDE MODE HEADER */
    .mode-header {
        display: {{ $isFlipside ? 'flex' : 'none' }};
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
    }

    .back-to-normal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 0, 128, 0.6);
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
        background: {{ $isFlipside ? 'var(--flipside-gradient)' : 'var(--normal-gradient)' }};
        background-size: {{ $isFlipside ? '200% 200%' : '300% 300%' }};
        animation: {{ $isFlipside ? 'gradientMove 8s ease infinite' : 'gradientShift 6s ease infinite' }};
        position: relative;
        cursor: pointer; /* ✅ ALWAYS CLICKABLE */
    }

    .cover-edit-overlay {
        /* ✅ REMOVE display: none untuk flipside */
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex; /* ✅ ALWAYS FLEX */
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        font-size: 18px;
        font-weight: 600;
    }

    .cover-section:hover .cover-edit-overlay {
        opacity: 1; /* ✅ SHOW ON HOVER */
    }

    /* Cover View Button */
    .cover-view-btn {
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease, background 0.3s ease;
    }

    .cover-section:hover .cover-view-btn {
        opacity: 1;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes gradientMove {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
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
        border: 4px solid {{ $isFlipside ? '#0d0d0d' : 'white' }};
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: bold;
        color: white;
        box-shadow: {{ $isFlipside ? '0 8px 25px rgba(255, 0, 128, 0.5)' : '0 8px 25px rgba(0, 0, 0, 0.15)' }};
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
        display: {{ $isFlipside ? 'none' : 'flex' }};
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
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

    .avatar-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: {{ $isFlipside ? '#0d0d0d' : 'white' }};
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid {{ $isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(0, 0, 0, 0.1)' }};
        min-width: 200px;
        z-index: 1000;
        animation: dropdownSlide 0.2s ease-out;
    }

    @keyframes dropdownSlide {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item {
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background-color 0.2s ease;
        font-size: 14px;
        color: {{ $isFlipside ? 'rgba(255,255,255,0.9)' : '#333' }};
    }

    .dropdown-item:hover {
        background-color: {{ $isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)' }};
        color: {{ $isFlipside ? '#FF0080' : '#667eea' }};
    }

    .profile-info {
        margin-top: 15px;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 800;
        color: {{ $isFlipside ? 'var(--flipside-text)' : 'var(--normal-text)' }};
        margin-bottom: 5px;
    }

    .profile-username {
        font-size: 16px;
        color: {{ $isFlipside ? 'var(--flipside-text-muted)' : 'var(--normal-text-muted)' }};
        margin-bottom: 15px;
    }

    .profile-bio {
        font-size: 15px;
        line-height: 1.5;
        color: {{ $isFlipside ? 'rgba(255, 255, 255, 0.8)' : '#444' }};
        margin-bottom: 20px;
        cursor: {{ $isFlipside ? 'default' : 'pointer' }};
        padding: 8px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .profile-bio:hover {
        background-color: {{ $isFlipside ? 'transparent' : 'rgba(29, 161, 242, 0.05)' }};
    }

    /* Stats Section */
    .stats-section {
        padding: 20px 30px;
        background: {{ $isFlipside ? '#1a1a1a' : 'var(--normal-bg-secondary)' }};
        border-top: 1px solid {{ $isFlipside ? 'rgba(255, 255, 255, 0.1)' : 'var(--normal-border)' }};
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: {{ $isFlipside ? '#0d0d0d' : 'white' }};
        border: {{ $isFlipside ? '1px solid rgba(255, 0, 128, 0.2)' : 'none' }};
        border-radius: 12px;
        box-shadow: {{ $isFlipside ? 'none' : '0 2px 8px rgba(0, 0, 0, 0.05)' }};
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: {{ $isFlipside ? '0 6px 20px rgba(255, 0, 128, 0.4)' : '0 6px 20px rgba(0, 0, 0, 0.1)' }};
        background: {{ $isFlipside ? 'rgba(255, 0, 128, 0.1)' : 'white' }};
    }

    .stat-number {
        font-size: 20px;
        font-weight: 800;
        color: {{ $isFlipside ? 'var(--flipside-text)' : 'var(--normal-text)' }};
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: {{ $isFlipside ? 'var(--flipside-text-muted)' : 'var(--normal-text-muted)' }};
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* FLIPSIDE TOGGLE BUTTON */
    .flipside-toggle-container {
        display: {{ $isFlipside ? 'none' : 'block' }};
        position: fixed;
        bottom: 90px;
        right: 20px;
        z-index: 1500;
        animation: floatIn 0.6s ease-out;
    }

    @keyframes floatIn {
        from { opacity: 0; transform: translateY(50px) scale(0.8); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .flipside-toggle-btn {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF0080 0%, #7928CA 100%);
        border: 4px solid white;
        box-shadow: 0 8px 25px rgba(255, 0, 128, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .flipside-toggle-btn:hover {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 12px 35px rgba(255, 0, 128, 0.6);
    }

    .flipside-icon {
        font-size: 28px;
        color: white;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .flipside-tooltip {
        position: absolute;
        right: 80px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .flipside-toggle-container:hover .flipside-tooltip {
        opacity: 1;
        right: 75px;
    }

    /* FLIPSIDE POST FAB */
    .flipside-post-fab {
        display: {{ $isFlipside ? 'flex' : 'none' }};
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF0080 0%, #7928CA 100%);
        border: 4px solid #0d0d0d;
        box-shadow: 0 8px 25px rgba(255, 0, 128, 0.6);
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1500;
    }

    .flipside-post-fab:hover {
        transform: scale(1.15) rotate(-10deg);
        box-shadow: 0 12px 35px rgba(255, 0, 128, 0.8);
    }

    .flipside-post-fab i {
        font-size: 28px;
        color: white;
    }

    /* MANAGE FLIPSIDE FOLLOWERS BUTTON */
    .manage-flipside-btn {
        display: {{ $isFlipside ? 'flex' : 'none' }};
        position: fixed;
        bottom: 170px;
        right: 20px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7928CA 0%, #FF0080 100%);
        border: 4px solid #0d0d0d;
        box-shadow: 0 8px 25px rgba(121, 40, 202, 0.6);
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1500;
        animation: floatIn 0.6s ease-out 0.2s both;
    }

    .manage-flipside-btn:hover {
        transform: scale(1.15) rotate(10deg);
        box-shadow: 0 12px 35px rgba(121, 40, 202, 0.8);
    }

    .manage-flipside-btn i {
        font-size: 26px;
        color: white;
    }

    /* Content Tabs */
    .content-tabs {
        padding: 0 30px;
        background: {{ $isFlipside ? '#1a1a1a' : 'transparent' }};
        border-bottom: 1px solid {{ $isFlipside ? 'rgba(255, 255, 255, 0.1)' : 'var(--normal-border)' }};
    }

    .tab-list {
        display: flex;
        gap: 0;
        overflow-x: auto;
    }

    .tab-item {
        padding: 15px 20px;
        cursor: pointer;
        color: {{ $isFlipside ? 'rgba(255, 255, 255, 0.5)' : 'var(--normal-text-muted)' }};
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-weight: 600;
        white-space: nowrap;
    }

    .tab-item.active {
        color: {{ $isFlipside ? '#FF0080' : '#667eea' }};
        border-bottom-color: {{ $isFlipside ? '#FF0080' : '#667eea' }};
    }

    .tab-item:hover {
        color: {{ $isFlipside ? '#FF0080' : '#667eea' }};
        background: {{ $isFlipside ? 'rgba(255, 0, 128, 0.1)' : 'rgba(102, 126, 234, 0.1)' }};
    }

    /* Content Area */
    .content-area {
        padding: 30px;
        min-height: 300px;
    }

    .post-item {
        background: {{ $isFlipside ? '#1a1a1a' : 'white' }};
        border: 1px solid {{ $isFlipside ? 'rgba(255, 0, 128, 0.2)' : '#e1e8ed' }};
        border-radius: 12px;
        margin-bottom: 15px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
    }

    .post-item:hover {
        box-shadow: {{ $isFlipside ? '0 4px 12px rgba(255, 0, 128, 0.3)' : '0 4px 12px rgba(0, 0, 0, 0.08)' }};
        background: {{ $isFlipside ? '#222222' : 'white' }};
    }

    .post-menu-btn {
        transition: all 0.3s ease;
    }

    .post-menu-btn:hover {
        background: {{ $isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(0, 0, 0, 0.05)' }} !important;
        color: {{ $isFlipside ? '#FF0080' : '#333' }} !important;
    }

    .post-menu {
        animation: menuSlideDown 0.2s ease-out;
    }

    @keyframes menuSlideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .post-menu-item:hover {
        background: {{ $isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(231, 76, 60, 0.1)' }} !important;
        color: #e74c3c !important;
    }

    .post-flipside-badge {
        position: absolute;
        top: 15px;
        right: 50px;
        background: linear-gradient(135deg, #FF0080, #7928CA);
        color: white;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    .post-content {
        color: {{ $isFlipside ? 'rgba(255, 255, 255, 0.9)' : 'inherit' }};
    }

    .post-interactions {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid {{ $isFlipside ? 'rgba(255, 255, 255, 0.1)' : '#e1e8ed' }};
    }

    .interaction-btn {
        background: none;
        border: none;
        color: {{ $isFlipside ? 'rgba(255, 255, 255, 0.6)' : '#666' }};
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
        background: {{ $isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.2)' }};
        transform: scale(1.05);
        color: {{ $isFlipside ? 'white' : 'inherit' }};
    }

    .interaction-btn.liked {
        color: #e74c3c;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: {{ $isFlipside ? 'rgba(255, 255, 255, 0.3)' : 'rgba(0, 0, 0, 0.3)' }};
        margin-bottom: 20px;
    }

    .empty-state p {
        color: {{ $isFlipside ? 'rgba(255, 255, 255, 0.7)' : '#666' }};
        font-size: 16px;
    }

    .hidden {
        display: none !important;
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
        background: {{ $isFlipside ? '#1a1a1a' : 'white' }};
        border: {{ $isFlipside ? '2px solid rgba(255, 0, 128, 0.3)' : 'none' }};
        border-radius: 16px;
        overflow: hidden;
        animation: slideDown 0.3s ease;
        max-height: 80vh;
        overflow-y: auto;
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid {{ $isFlipside ? 'rgba(255, 0, 128, 0.2)' : '#e1e8ed' }};
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: {{ $isFlipside ? 'white' : 'inherit' }};
    }

    .close {
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        border: none;
        background: none;
        color: {{ $isFlipside ? 'rgba(255,255,255,0.7)' : '#aaa' }};
    }

    .close:hover {
        color: {{ $isFlipside ? 'white' : '#000' }};
    }

    .modal-body {
        padding: 20px;
        color: {{ $isFlipside ? 'rgba(255,255,255,0.9)' : 'inherit' }};
    }

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

    @media (max-width: 768px) {
        .profile-container {
            margin: 10px;
            border-radius: 16px;
        }

        .mode-header {
            padding: 15px 20px;
            flex-direction: column;
            gap: 10px;
        }

        .profile-header {
            padding: 0 20px 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .content-area {
            padding: 20px;
        }

        .flipside-toggle-container,
        .flipside-post-fab,
        .manage-flipside-btn {
            bottom: 75px;
            right: 15px;
            width: 55px;
            height: 55px;
        }
    }

    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .user-list-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    }

    .user-list-item .modal-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e0e0e0;
    }

    .user-list-item .modal-avatar.flipside {
        border-color: rgba(255, 0, 128, 0.3);
    }

    .user-list-item .name {
        display: block;
        font-size: 15px;
        font-weight: 700;
    }

    .user-list-item .username {
        font-size: 13px;
    }

    .modal-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e0e0e0;
    }

    .modal-avatar.flipside {
        border-color: rgba(255, 0, 128, 0.3);
    }

    .media-grid {
        display: grid;
        gap: 6px;
        border-radius: 12px;
        overflow: hidden;
    }

    .media-grid.media-count-1 {
        grid-template-columns: 1fr;
    }

    .media-grid.media-count-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .media-grid.media-count-3,
    .media-grid.media-count-4 {
        grid-template-columns: repeat(2, 1fr);
    }

    .media-item img,
    .media-item video {
        width: 100%;
        height: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
    }

    .media-grid-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-lg-3 d-none d-lg-block"></div>
    <div class="col-lg-6 col-12">
        <div class="profile-container">
            @if($isFlipside)
            <div class="mode-header">
                <button class="back-to-normal-btn" onclick="window.location.href='/profile'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Profile</span>
                </button>
                <div class="flipside-mode-badge">
                    <span>🔥</span>
                    <span>FLIPSIDE MODE</span>
                </div>
            </div>
            @endif

            <div class="cover-section {{ $activeCover ? 'has-cover-image' : '' }}" 
                @if($activeCover)
                style="background-image: url('{{ asset('storage/' . $activeCover) }}'); 
                        background-size: cover; 
                        background-position: center; 
                        background-repeat: no-repeat;
                        animation: none !important;"
                @endif
                onclick="{{ !$isFlipside ? 'editCover()' : 'editFlipsideCover()' }}">
                
                {{-- Edit Overlay --}}
                <div class="cover-edit-overlay" onclick="event.stopPropagation(); {{ $isFlipside ? 'editFlipsideCover()' : 'editCover()' }}">
                    <i class="fas fa-camera me-2"></i> 
                    {{ $activeCover ? 'Change' : 'Add' }} 
                    {{ $isFlipside ? 'Flipside' : '' }} Cover
                 </div>
    
    
                @if($user->cover)
                <!-- View Cover Button -->
                    <div class="cover-view-btn" onclick="viewCoverFullSize(event)" 
                        style="position: absolute; top: 15px; right: 15px; background: rgba(0, 0, 0, 0.6); color: white; padding: 8px 16px; border-radius: 20px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s ease; z-index: 10; backdrop-filter: blur(10px);"
                        onmouseover="this.style.background='rgba(0, 0, 0, 0.8)'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.background='rgba(0, 0, 0, 0.6)'; this.style.transform='scale(1)';">
                        <i class="fas fa-expand"></i>
                        <span>View Cover</span>
                    </div>
                @endif
            </div>
            <div class="profile-header">
            <div class="avatar-container">
                <div class="avatar" onclick="{{ $isFlipside ? 'editFlipsideAvatar()' : 'toggleAvatarMenu(event)' }}">
                    @if($isFlipside)
                        @if($user->flipside_avatar)
                            <img src="{{ asset('storage/' . $user->flipside_avatar) }}" alt="Flipside Avatar">
                        @else
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                        <div class="avatar-edit-overlay">
                            <i class="fas fa-camera"></i>
                            <small style="font-size: 10px; display: block; margin-top: 2px;">Flipside</small>
                        </div>
                    @else
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                        @else
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                        @if(!$isFlipside)
                        <div class="avatar-edit-overlay">
                            <i class="fas fa-camera"></i>
                        </div>
                        @endif
                    @endif
                </div>

                @if(!$isFlipside)
                <div class="avatar-dropdown" id="avatarDropdown">
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
                @endif
            </div>

                <div class="profile-info">
                    <h1 class="profile-name">
                        @if($isFlipside && $user->flipside_name)
                            {{ $user->flipside_name }}
                            <span style="font-size: 14px; color: rgba(255, 0, 128, 0.8); margin-left: 10px; cursor: pointer;" onclick="editFlipsideName()">
                                <i class="fas fa-edit"></i>
                            </span>
                        @else
                            {{ $user->name }}
                        @endif
                    </h1>
                    <p class="profile-username">{{ '@' . $user->username }}</p>
                    {{-- bio section --}}
                </div>
            </div>

            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ count($displayPosts) }}</div>
                        <div class="stat-label">{{ $isFlipside ? 'Flipside Posts' : 'Posts' }}</div>
                    </div>
                    <div class="stat-item" onclick="showFollowers()">
                        <div class="stat-number">{{ $followersCount ?? 0 }}</div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-item" onclick="showFollowing()">
                        <div class="stat-number">{{ $followingCount ?? 0 }}</div>
                        <div class="stat-label">Following</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $likesCount ?? 0 }}</div>
                        <div class="stat-label">Likes</div>
                    </div>
                </div>
            </div>

            <div class="content-tabs">
                <div class="tab-list">
                    <div class="tab-item active" onclick="showTab('posts')">
                        {{ $isFlipside ? '🔥 Flipside Posts' : '📷 Posts' }}
                    </div>
                    <div class="tab-item" onclick="showTab('media')">🖼️ Media</div>
                    @if(!$isFlipside)
                    <div class="tab-item" onclick="showTab('likes')">❤️ Likes</div>
                    @endif
                </div>
            </div>

            <div class="content-area">
                <div id="posts-content">
                    @forelse($displayPosts as $post)
                    <div class="post-item">
                        @if($isFlipside)
                        <span class="post-flipside-badge">🔒 Flipside</span>
                        @endif

                        <div class="d-flex align-items-center mb-3">
                           @if($post->user->active_avatar)
                                <img src="{{ asset('storage/' . $post->user->active_avatar) }}"
                                    class="rounded-circle me-2"
                                    style="width:40px; height:40px; object-fit:cover;">
                            @else
                                <div class="rounded-circle me-2 d-flex justify-content-center align-items-center 
                                    {{ $isFlipside ? 'text-white' : 'text-white bg-primary' }}"
                                    style="width: 40px; height: 40px; 
                                    {{ $isFlipside ? 'background: linear-gradient(135deg, #FF0080, #7928CA);' : '' }} font-weight: bold;">
                                    {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <strong style="color: {{ $isFlipside ? 'white' : 'inherit' }};">{{ $post->user->name }}</strong>
                                <span style="color: {{ $isFlipside ? 'rgba(255,255,255,0.6)' : '#666' }};">{{ '@' . $post->user->username }}</span>
                                <span style="color: {{ $isFlipside ? 'rgba(255,255,255,0.5)' : '#666' }};">· {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            
                            @if(auth()->id() === $post->user_id)
                            <div class="post-menu-container" style="position: relative;">
                                <button class="post-menu-btn" onclick="togglePostMenu({{ $post->id }})" style="background: none; border: none; color: {{ $isFlipside ? 'rgba(255,255,255,0.6)' : '#666' }}; font-size: 20px; cursor: pointer; padding: 5px 10px; border-radius: 50%; transition: all 0.3s ease;">
                                    ⋮
                                </button>
                                <div class="post-menu" id="postMenu-{{ $post->id }}" style="display: none; position: absolute; right: 0; top: 100%; background: {{ $isFlipside ? '#1a1a1a' : 'white' }}; border: 1px solid {{ $isFlipside ? 'rgba(255, 0, 128, 0.3)' : '#ddd' }}; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 100; min-width: 150px; margin-top: 5px;">
                                    <div class="post-menu-item" onclick="deletePost({{ $post->id }})" style="padding: 12px 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; color: {{ $isFlipside ? 'rgba(255,255,255,0.9)' : '#333' }}; transition: all 0.2s ease; border-radius: 8px;">
                                        <i class="fas fa-trash" style="color: #e74c3c;"></i>
                                        <span>Delete Post</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="post-content">
                            {!! nl2br(e($post->caption)) !!}
                            @if($post->media && $post->media->count())
                            <div class="mt-3 media-grid media-count-{{ $post->media->count() }}">
                                @foreach($post->media as $media)
                                    <div class="media-item">
                                        @if($media->type === 'image')
                                            <img
                                                src="{{ asset('storage/' . $media->file_path) }}"
                                                onclick="openImageModal(this.src)"
                                            >
                                       @elseif($media->type === 'video') 
                                    <div class="post-video-container" style="position:relative; margin-bottom:15px;">
                                        <video
                                            class="twitter-video"
                                            muted
                                            playsinline
                                            preload="metadata"
                                            style="width:100%; max-height:400px; object-fit:cover; border-radius:12px; cursor:pointer;">
                                            <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                        </video>
                                        <!-- tombol mute/unmute -->
                                        <button class="mute-btn"
                                            style="position:absolute; bottom:10px; right:10px; background:rgba(0,0,0,0.5); border:none; color:white; padding:5px 8px; border-radius:50%; cursor:pointer;">
                                            🔇
                                        </button>
                                    </div>
                                    @endif
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

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
                       <button class="interaction-btn"
                            onclick="openComments({{ $post->id }}, '{{ $isFlipside ? 'flipside' : 'main' }}')"
                        >
                            <i class="fas fa-comment"></i>

                            <span>
                                {{ $isFlipside
                                    ? ($post->flipsideComments?->count() ?? 0)
                                    : ($post->comments?->count() ?? 0)
                                }}
                            </span>
                        </button>

                            <button class="interaction-btn">
                                <i class="fas fa-share"></i>
                                Share
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas {{ $isFlipside ? 'fa-ghost' : 'fa-camera' }}"></i>
                        <p>
                            @if($isFlipside)
                            Belum ada konten Flipside.<br>
                            <strong>Post something secret!</strong> 🤫
                            @else
                            Belum ada postingan. Mulai berbagi sesuatu!
                            @endif
                        </p>
                    </div>
                    @endforelse
                </div>

                <div id="media-content" class="hidden">
                    @php
                        $allMedia = collect($displayPosts)->pluck('media')->flatten();
                    @endphp

                    @if($allMedia->count())
                    <div class="row">
                        @foreach($allMedia as $media)
                            @if($media->type === 'image')
                            <div class="col-md-4 mb-3">
                                <div class="media-grid-item">
                                    <img
                                        src="{{ asset('storage/' . $media->file_path) }}"
                                        onclick="openImageModal(this.src)"
                                    >
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-image"></i>
                        <p>Belum ada media.</p>
                    </div>
                    @endif
                </div>


                @if(!$isFlipside)
                <div id="likes-content" class="hidden">
                    @forelse($likedPosts ?? [] as $post)
                    <div class="card shadow-sm rounded mb-3 openPostModal" data-post-id="{{ $post->id }}" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="d-flex align-items-center p-2">
                           @if($post->user->avatar)
                            <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                class="rounded-circle me-2"
                                style="width:40px; height:40px; object-fit:cover;">
                            @else
                            <div class="rounded-circle me-2 d-flex justify-content-center align-items-center {{ $isFlipside ? 'text-white' : 'text-white bg-primary' }}"
                                style="width: 40px; height: 40px; {{ $isFlipside ? 'background: linear-gradient(135deg, #FF0080, #7928CA);' : '' }} font-weight: bold;">
                                {{ strtoupper(substr($post->user->name, 0, 2)) }}
                            </div>
                            @endif
                            <div class="flex-grow-1">
                              <a href="{{ route('profile', $post->user->username) }}"
                                    onclick="event.stopPropagation();"
                                    class="fw-bold"
                                    style="
                                        text-decoration: none !important; color: {{ $isFlipside ? 'white' : 'inherit' }}; ">
                                    {{ $post->user->name }}
                                    </a>

                                    <a href="{{ route('profile', $post->user->username) }}"
                                    onclick="event.stopPropagation();"
                                    style=" text-decoration: none !important; color: {{ $isFlipside ? 'rgba(255,255,255,0.6)' : '#666' }};">
                                    {{ '@' . $post->user->username }}
                            </a>

                                <span style="color: {{ $isFlipside ? 'rgba(255,255,255,0.5)' : '#666' }};">· {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <!-- <a href="{{ route('profilePage', ['username' => $post->user->username]) }}"
                                class="fw-bold text-dark text-decoration-none"
                                onclick="event.stopPropagation();">
                                {{ $post->user->username }}
                            </a> -->
                        </div>
                        @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Media" style="max-height: 400px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <p class="mb-1">{{ Str::limit($post->caption, 150) }}</p>
                            <small class="text-muted">{{ $post->likes_count }} Likes</small>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-heart"></i>
                        <p>Belum ada postingan yang disukai.</p>
                    </div>
                    @endforelse
                </div>

                <div id="drafts-content" class="hidden">
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <p>Draft posts akan muncul di sini</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if(!$isFlipside)
        <div class="flipside-toggle-container">
            <div class="flipside-toggle-btn" onclick="window.location.href='/flipside'">
                <span class="flipside-icon">🔄</span>
            </div>
            <div class="flipside-tooltip">
                Switch to Flipside 🔥
            </div>
        </div>
        @endif

        @if($isFlipside)
        <div class="flipside-post-fab" onclick="openFlipsidePostModal()">
            <i class="fas fa-pen"></i>
        </div>

        <div class="manage-flipside-btn" onclick="openFlipsideFollowersModal()">
            <i class="fas fa-user-friends"></i>
        </div>
        @endif
    </div>
</div>

<!-- Main Modal -->
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


<!-- Flipside Post Modal -->
<div id="flipsidePostModal" class="modal" style="display: none;">
    <div class="modal-content" style="background: #1a1a1a !important; border: 2px solid rgba(255, 0, 128, 0.3) !important;">
        <div class="modal-header" style="background: linear-gradient(135deg, rgba(255, 0, 128, 0.2), rgba(121, 40, 202, 0.2)); border-bottom: 2px solid rgba(255, 0, 128, 0.3) !important;">
            <h3 style="margin: 0; font-size: 20px; font-weight: 800; background: linear-gradient(135deg, #FF0080, #7928CA); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: flex; align-items: center; gap: 10px;">
                <span>🔒</span>
                Create Flipside Post
            </h3>
            <button class="close" onclick="closeFlipsidePostModal()" style="color: rgba(255,255,255,0.7);">&times;</button>
        </div>
        <div class="modal-body" style="padding: 25px;">
            <form id="flipsidePostForm" onsubmit="submitFlipsidePost(event)">
                <div class="mb-3">
                    <textarea 
                        id="flipsidePostCaption" 
                        class="form-control" 
                        placeholder="What's on your mind? Share your secret thoughts... 🤫"
                        maxlength="1000"
                        required
                        style="width: 100%; min-height: 150px; background: #0d0d0d; border: 2px solid rgba(255, 0, 128, 0.3); border-radius: 12px; padding: 15px; color: white; font-size: 16px; resize: vertical;"
                    ></textarea>
                    <div class="char-counter" id="charCounter" style="color: rgba(255, 255, 255, 0.5); font-size: 13px; margin-top: 8px;">0 / 1000</div>
                </div>

                <div class="image-preview-container" id="imagePreviewContainer" style="margin-top: 15px; position: relative; display: none;">
                    <div class="image-preview-wrapper" style="position: relative; border-radius: 12px; overflow: hidden; border: 2px solid rgba(255, 0, 128, 0.3);">
                        <img id="imagePreview" src="" alt="Preview" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px;">
                        <button type="button" class="remove-image-btn" onclick="removeFlipsideImage()" style="position: absolute; top: 10px; right: 10px; width: 35px; height: 35px; border-radius: 50%; background: rgba(0, 0, 0, 0.8); border: 2px solid #FF0080; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="post-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255, 0, 128, 0.2);">
                    <div class="post-options" style="display: flex; gap: 10px;">
                        <button type="button" class="post-option-btn" onclick="triggerFlipsideImageUpload()" title="Add Image" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255, 0, 128, 0.1); border: 1px solid rgba(255, 0, 128, 0.3); color: rgba(255, 255, 255, 0.7); display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <i class="fas fa-image"></i>
                        </button>
                        <input 
                            type="file" 
                            id="flipsideImageInput" 
                            accept="image/*" 
                            style="display: none;"
                            onchange="previewFlipsideImage(event)"
                        >
                    </div>
                    <button type="submit" class="btn" id="flipsideSubmitBtn" style="background: linear-gradient(135deg, #FF0080, #7928CA); color: white; border: none; padding: 12px 30px; border-radius: 25px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 0, 128, 0.4);">
                        <i class="fas fa-paper-plane me-2"></i>
                        Post to Flipside
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flipside Followers Modal -->
<div id="flipsideFollowersModal" class="modal" style="display: none;">
    <div class="modal-content" style="background: #1a1a1a !important; border: 2px solid rgba(121, 40, 202, 0.3) !important;">
        <div class="modal-header" style="background: linear-gradient(135deg, rgba(121, 40, 202, 0.2), rgba(255, 0, 128, 0.2)); border-bottom: 2px solid rgba(121, 40, 202, 0.3) !important; padding: 20px;">
            <h3 style="margin: 0; font-size: 20px; font-weight: 800; background: linear-gradient(135deg, #7928CA, #FF0080); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: flex; align-items: center; gap: 10px;">
                <span>👥</span>
                Manage Flipside Followers
            </h3>
            <button class="close" onclick="closeFlipsideFollowersModal()" style="color: rgba(255,255,255,0.7);">&times;</button>
        </div>
        <div class="modal-body" style="padding: 25px; color: white;">
            <div class="flipside-stats" style="display: flex; gap: 20px; padding: 15px; background: rgba(121, 40, 202, 0.1); border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(121, 40, 202, 0.2);">
                <div class="flipside-stat-box" style="flex: 1; text-align: center;">
                    <div class="flipside-stat-number" id="totalFollowersCount" style="font-size: 24px; font-weight: 800; background: linear-gradient(135deg, #7928CA, #FF0080); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">0</div>
                    <div class="flipside-stat-label" style="font-size: 12px; color: rgba(255, 255, 255, 0.6); text-transform: uppercase; letter-spacing: 0.5px;">Total Followers</div>
                </div>
                <div class="flipside-stat-box" style="flex: 1; text-align: center;">
                    <div class="flipside-stat-number" id="flipsideFollowersCount" style="font-size: 24px; font-weight: 800; background: linear-gradient(135deg, #7928CA, #FF0080); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">0</div>
                    <div class="flipside-stat-label" style="font-size: 12px; color: rgba(255, 255, 255, 0.6); text-transform: uppercase; letter-spacing: 0.5px;">Flipside Access</div>
                </div>
            </div>

            <input 
                type="text" 
                class="form-control" 
                id="flipsideSearchBox"
                placeholder="🔍 Search followers..."
                oninput="filterFlipsideFollowers()"
                style="background: #0d0d0d; border: 2px solid rgba(121, 40, 202, 0.3); border-radius: 12px; padding: 12px 15px; color: white; width: 100%; margin-bottom: 20px;"
            >

            <div id="flipsideFollowersList" style="max-height: 400px; overflow-y: auto;">
                <!-- Followers will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Post Detail Modal (Bootstrap) -->
<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            <div id="postModalContent"></div>
        </div>
    </div>
</div>

<!-- Flipside Name Setup Modal -->
<div id="flipsideNameModal" class="modal" style="display: none;">
    <div class="modal-content" style="background: #1a1a1a !important; border: 2px solid rgba(255, 0, 128, 0.3) !important; max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, rgba(255, 0, 128, 0.2), rgba(121, 40, 202, 0.2)); border-bottom: 2px solid rgba(255, 0, 128, 0.3) !important;">
            <h3 style="margin: 0; font-size: 20px; font-weight: 800; background: linear-gradient(135deg, #FF0080, #7928CA); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: flex; align-items: center; gap: 10px;">
                <span>🎭</span>
                Set Your Flipside Identity
            </h3>
        </div>
        <div class="modal-body" style="padding: 30px;">
            <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 20px; text-align: center; line-height: 1.6;">
                Welcome to <strong style="background: linear-gradient(135deg, #FF0080, #7928CA); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Flipside Mode</strong>! 🔥<br>
                Choose a different name for your secret identity.
            </p>
            
            <form id="flipsideNameForm" onsubmit="submitFlipsideName(event)">
                <div class="mb-4">
                    <label style="color: rgba(255, 255, 255, 0.9); font-weight: 600; display: block; margin-bottom: 10px;">
                        Flipside Name
                    </label>
                    <input 
                        type="text" 
                        id="flipsideNameInput" 
                        class="form-control" 
                        placeholder="Enter your secret identity..."
                        maxlength="100"
                        required
                        style="
                            background: #0d0d0d; 
                            border: 2px solid rgba(255, 0, 128, 0.3); 
                            border-radius: 12px; 
                            padding: 15px; 
                            color: white; 
                            font-size: 16px;
                            width: 100%;
                        "
                    >
                    <small style="color: rgba(255, 255, 255, 0.5); display: block; margin-top: 8px;">
                        This name will only appear in Flipside Mode
                    </small>
                </div>
                
                <div style="text-align: center; margin-top: 25px;">
                    <button 
                        type="submit" 
                        class="btn" 
                        id="flipsideNameSubmitBtn"
                        style="
                            background: linear-gradient(135deg, #FF0080, #7928CA); 
                            color: white; 
                            border: none; 
                            padding: 14px 40px; 
                            border-radius: 25px; 
                            font-weight: 700;
                            font-size: 16px;
                            cursor: pointer;
                            box-shadow: 0 4px 15px rgba(255, 0, 128, 0.4);
                            transition: all 0.3s ease;
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(255, 0, 128, 0.6)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255, 0, 128, 0.4)'"
                    >
                        <i class="fas fa-check-circle me-2"></i>
                        Set Flipside Name
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let cropper = null;
    let scaleX = 1;

    // Post menu functions
    function togglePostMenu(postId) {
        document.querySelectorAll('.post-menu').forEach(menu => {
            if (menu.id !== 'postMenu-' + postId) {
                menu.style.display = 'none';
            }
        });

        const menu = document.getElementById('postMenu-' + postId);
        if (menu) {
            menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' : 'none';
        }
    }

    async function deletePost(postId) {
        if (!confirm('Yakin ingin menghapus post ini? Tindakan ini tidak dapat dibatalkan.')) {
            return;
        }

        const menu = document.getElementById('postMenu-' + postId);
        if (menu) menu.style.display = 'none';

        try {
            const response = await fetch('/posts/destroy/' + postId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                const postElements = document.querySelectorAll('.post-item');
                let postElement = null;
                
                postElements.forEach(el => {
                    if (el.querySelector('#postMenu-' + postId)) {
                        postElement = el;
                    }
                });
                
                if (postElement) {
                    postElement.style.transition = 'all 0.3s ease';
                    postElement.style.opacity = '0';
                    postElement.style.transform = 'translateX(-100%)';
                    
                    setTimeout(() => {
                        postElement.remove();
                        showNotification('🗑️ Post berhasil dihapus!');
                        
                        const remainingPosts = document.querySelectorAll('.post-item');
                        if (remainingPosts.length === 0) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }, 300);
                } else {
                    showNotification('✅ Post berhasil dihapus!');
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                throw new Error(result.message || 'Failed to delete post');
            }
        } catch (error) {
            console.error('Delete error:', error);
            showNotification('❌ Gagal menghapus post. Coba lagi!');
        }
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.post-menu-container')) {
            document.querySelectorAll('.post-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

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
        const icon = button.querySelector('i');
        const count = button.querySelector('span');
        let currentCount = parseInt(count.textContent);
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const liked = button.classList.contains("liked");
        
        // ✅ TAMBAHAN: Deteksi type berdasarkan mode
        const type = window.appData.isFlipside ? 'flipside' : 'main';

        button.disabled = true;

        try {
            // ✅ UPDATE: Tambahkan type parameter di endpoint
            const response = await fetch(
                liked 
                    ? `/like/destroy/${postId}/${type}` 
                    : `/like/store/${postId}/${type}`, 
                {
                    method: liked ? "DELETE" : "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    }
                }
            );

            const result = await response.json();

            if (response.ok) {
                if (!liked) {
                    button.classList.add("liked");
                    count.textContent = currentCount + 1;
                    showNotification("❤️ Post liked!");
                } else {
                    button.classList.remove("liked");
                    count.textContent = Math.max(0, currentCount - 1);
                    showNotification("💔 Post unliked");
                }
            } else {
                // Handle specific error codes
                if (response.status === 409) {
                    showNotification(liked ? "⚠️ Already unliked" : "⚠️ Already liked");
                } else {
                    throw new Error(result.message || 'Failed to toggle like');
                }
            }
        } catch (error) {
            console.error('Like error:', error);
            showNotification("⚠️ Network error!");
        } finally {
            button.disabled = false;
        }
    }


    
    // Comments functionality
// UPDATE: openComments function - TAMBAHKAN TYPE PARAMETER
async function openComments(postId) {
    try {
        // ✅ Deteksi type berdasarkan mode
        const type = window.appData.isFlipside ? 'flipside' : 'main';
        
        // ✅ Tambahkan type parameter di URL
        const response = await fetch(`/comment/index/${postId}/${type}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();
        let comments = result.data || result.comments || [];

        let commentsHtml = '<div class="comments-section">';

        if (comments.length > 0) {
            comments.forEach(comment => {
                // ✅ Gunakan active_avatar yang sudah di-set dari backend
                const avatarSrc = comment.user && comment.user.active_avatar 
                    ? '/storage/' + comment.user.active_avatar 
                    : null;
                    
                const avatar = avatarSrc
                    ? `<img src="${avatarSrc}" alt="Avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`
                    : `<div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg, #FF0080, #7928CA);color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;">${((comment.user && comment.user.name) ? comment.user.name.substring(0,2).toUpperCase() : '?')}</div>`;

                const isFlipside = window.appData.isFlipside;
                
                // ✅ Tambahkan tombol delete
                const deleteBtn = comment.user_id === {{ auth()->id() }} 
                    ? `<button onclick="deleteComment(${comment.id}, ${postId})" style="background:none;border:none;color:${isFlipside ? 'rgba(255,0,128,0.8)' : '#e74c3c'};cursor:pointer;padding:4px 8px;border-radius:4px;font-size:12px;margin-left:10px;" title="Delete comment"><i class="fas fa-trash"></i></button>`
                    : '';
                
                commentsHtml += `<div class="comment-item-${comment.id}" style="padding:12px 0;border-bottom:1px solid ${isFlipside ? 'rgba(255, 255, 255, 0.1)' : '#eee'};"><div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">${avatar}<div style="flex:1;"><span style="font-weight:600;font-size:14px;color:${isFlipside ? 'white' : 'inherit'};">${comment.user ? comment.user.name : 'Anonymous'}</span><span style="color:${isFlipside ? 'rgba(255,255,255,0.5)' : '#666'};font-size:12px;margin-left:10px;">${new Date(comment.created_at).toLocaleString()}</span></div>${deleteBtn}</div><div style="font-size:14px;line-height:1.4;margin-left:50px;color:${isFlipside ? 'rgba(255,255,255,0.9)' : 'inherit'};">${comment.content}</div></div>`;
            });
        } else {
            commentsHtml += `<p style="text-align:center;color:${window.appData.isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};padding:20px;">Belum ada komentar.</p>`;
        }

        const isFlipside = window.appData.isFlipside;
        commentsHtml += `</div><div style="padding:20px 0;border-top:1px solid ${isFlipside ? 'rgba(255, 255, 255, 0.1)' : '#eee'};"><div style="display:flex;gap:10px;align-items:flex-start;"><textarea class="form-control" id="commentInput-${postId}" placeholder="Tulis komentar..." rows="2" style="flex:1;border-radius:12px;background:${isFlipside ? '#1a1a1a' : 'white'};color:${isFlipside ? 'white' : 'inherit'};border:1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : '#ddd'};"></textarea><button class="btn btn-primary" onclick="submitComment(${postId})" style="background:linear-gradient(135deg,#FF0080,#7928CA);border:none;border-radius:12px;padding:10px 20px;">Post</button></div></div>`;

        openModal(`Comments (${comments.length})`, commentsHtml);
    } catch (error) {
        console.error("Error fetching comments:", error);
        showNotification("Error loading comments");
    }
}

// UPDATE: submitComment function - TAMBAHKAN TYPE
async function submitComment(postId) {
    const commentInput = document.getElementById('commentInput-' + postId);
    const text = commentInput.value.trim();

    if (!text) {
        showNotification("Komentar tidak boleh kosong!");
        return;
    }

    // Disable input sementara
    commentInput.disabled = true;

    try {
        // ✅ Deteksi type berdasarkan mode
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
                type: type // ✅ Tambahkan type
            })
        });

        const result = await response.json();
        
        console.log('Comment response:', result);

        if (response.ok && result.success) {
            commentInput.value = '';
            showNotification('💬 Komentar berhasil dikirim!');
            setTimeout(() => openComments(postId), 1000);
        } else {
            // Handle specific errors
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

// ✅ TAMBAHAN: Function untuk delete comment
async function deleteComment(commentId, postId) {
    if (!confirm('Yakin ingin menghapus komentar ini?')) {
        return;
    }

    try {
        const response = await fetch(`/comment/destroy/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // Remove comment element with animation
            const commentElement = document.querySelector(`.comment-item-${commentId}`);
            if (commentElement) {
                commentElement.style.transition = 'all 0.3s ease';
                commentElement.style.opacity = '0';
                commentElement.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    commentElement.remove();
                    showNotification('🗑️ Komentar berhasil dihapus!');
                    
                    // Reload comments to update count
                    setTimeout(() => openComments(postId), 500);
                }, 300);
            }
        } else {
            throw new Error(result.message || 'Failed to delete comment');
        }
    } catch (error) {
        console.error('Delete comment error:', error);
        showNotification('❌ Gagal menghapus komentar!');
    }
}
    @if(!$isFlipside)
    // Profile editing functions
    function editProfile() {
        const content = '<form onsubmit="updateProfile(event)"><div class="mb-3"><label class="form-label fw-bold">Nama</label><input type="text" class="form-control" name="name" value="{{ $user->name }}" required></div><div class="mb-3"><label class="form-label fw-bold">Username</label><input type="text" class="form-control" name="username" value="{{ $user->username }}" required></div><div class="mb-3"><label class="form-label fw-bold">Bio</label><textarea class="form-control" rows="4" name="bio" maxlength="500">{{ $user->bio }}</textarea></div><div class="text-center"><button type="submit" class="btn btn-primary me-2"><i class="fas fa-save"></i> Save Changes</button><button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button></div></form>';
        openModal('Edit Profile', content);
    }

    function updateProfile(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

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
                    location.reload();
                } else {
                    showNotification('❌ Gagal update profile!');
                }
            });
    }

    function editBio() {
        const content = '<form onsubmit="updateBio(event)"><div class="mb-3"><label class="form-label fw-bold">Edit Bio</label><textarea class="form-control" rows="4" name="bio" maxlength="500" required>{{ $user->bio }}</textarea></div><div class="text-center"><button type="submit" class="btn btn-primary me-2"><i class="fas fa-save"></i> Save Bio</button><button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button></div></form>';
        openModal('Edit Bio', content);
    }

    function updateBio(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

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
                    location.reload();
                }
            });
    }

    function editAvatar() {
        const content = '<form id="avatarUploadForm"><div class="mb-3"><label class="form-label fw-bold">Upload Avatar</label><input type="file" class="form-control" id="avatarInput" accept="image/*" required><small class="text-muted">Max size: 2MB</small></div><div id="cropContainer" style="display: none;"><div class="mb-3"><div style="max-height: 400px; overflow: hidden;"><img id="cropImage" style="max-width: 100%;"></div></div><div class="mb-3 text-center"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperZoom(0.1)"><i class="fas fa-search-plus"></i></button><button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperZoom(-0.1)"><i class="fas fa-search-minus"></i></button><button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperRotate(-45)"><i class="fas fa-undo"></i></button><button type="button" class="btn btn-sm btn-outline-secondary" onclick="cropperRotate(45)"><i class="fas fa-redo"></i></button></div></div></div><div class="text-center"><button type="button" id="cropAndUploadBtn" class="btn btn-primary me-2" onclick="cropAndUploadAvatar()" style="display: none;"><i class="fas fa-check"></i> Crop & Upload</button><button type="button" class="btn btn-secondary" onclick="closeCropModal()">Cancel</button></div></form>';

        openModal('Change Avatar', content);
        document.getElementById('avatarInput').addEventListener('change', initCropper);
    }

    function initCropper(e) {
        const file = e.target.files[0];
        if (!file || file.size > 2 * 1024 * 1024) {
            showNotification('File terlalu besar! Max 2MB.');
            return;
        }

        if (cropper) cropper.destroy();

        const reader = new FileReader();
        reader.onload = function(event) {
            const cropImage = document.getElementById('cropImage');
            cropImage.src = event.target.result;
            document.getElementById('cropContainer').style.display = 'block';
            document.getElementById('cropAndUploadBtn').style.display = 'inline-block';

            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                responsive: true
            });
        };
        reader.readAsDataURL(file);
    }

    function cropperZoom(ratio) {
        if (cropper) cropper.zoom(ratio);
    }

    function cropperRotate(degree) {
        if (cropper) cropper.rotate(degree);
    }

    function cropAndUploadAvatar() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('avatar', blob, 'avatar.jpg');

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
                        location.reload();
                    }
                });
        }, 'image/jpeg', 0.9);
    }

    function closeCropModal() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        closeModal();
    }

    function editCover() {
        const content = '<form onsubmit="updateCover(event)"><div class="mb-3"><label class="form-label fw-bold">Upload Cover Image</label><input type="file" class="form-control" name="cover" accept="image/*" required></div><div class="text-center"><button type="submit" class="btn btn-primary me-2"><i class="fas fa-upload"></i> Upload Cover</button><button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button></div></form>';
        openModal('Change Cover Image', content);
    }

    function updateCover(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

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
                    location.reload();
                }
            });
    }

    function toggleAvatarMenu(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('avatarDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    function viewAvatarFullSize() {
        const avatar = document.querySelector('.avatar img');
        if (avatar) {
            openImageModal(avatar.src);
        }
    }
    @endif

    // Followers/Following
    function showFollowers() {
        const followers = window.appData && window.appData.followers ? window.appData.followers : [];
        if (!followers.length) {
            openModal('Followers', '<p class="text-center text-muted">Belum ada followers.</p>');
            return;
        }
        renderUserList('Followers', followers);
    }

    function showFollowing() {
        const following = window.appData && window.appData.following ? window.appData.following : [];
        if (!following.length) {
            openModal('Following', '<p class="text-center text-muted">Belum mengikuti siapa pun.</p>');
            return;
        }
        renderUserList('Following', following);
    }

   function renderUserList(title, users) {
    const isFollowersModal = title === 'Followers';
    const isFollowingModal = title === 'Following';
    const isFlipside = window.appData.isFlipside;

    const listHTML = users.map(user => {

        // === DEFAULT AVATAR (HURUF NAMA) ===
       const avatar = user.avatar 
    ? '/storage/' + user.avatar 
    : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=0D6EFD&color=fff&size=128`;


        // === PASTIKAN SELALU TERDEFINISI ===
        let actionButton = '';

        if (isFollowingModal) {
            actionButton = `
                <button class="btn btn-sm"
                    onclick="unfollowUser(${user.id}, this)"
                    style="
                        background: ${isFlipside ? 'rgba(255,0,128,0.2)' : '#e0e0e0'};
                        color: ${isFlipside ? '#0D6EFD' : '#333'};
                        border: none;
                        padding: 6px 16px;
                        border-radius: 20px;
                        font-weight: 600;
                        font-size: 13px;">
                    Following
                </button>
            `;
        } else if (isFollowersModal) {
            actionButton = `
                <button class="btn btn-sm"
                    onclick="removeFollower(${user.id}, this)"
                    style="
                        background: ${isFlipside ? 'rgba(255,0,128,0.1)' : '#f5f5f5'};
                        color: ${isFlipside ? '#0D6EFD' : '#666'};
                        border: 1px solid ${isFlipside ? 'rgba(255,0,128,0.3)' : '#ddd'};
                        padding: 6px 16px;
                        border-radius: 20px;
                        font-weight: 600;
                        font-size: 13px;">
                    Remove
                </button>
            `;
        }

        return `
        <div class="user-list-item" data-user-id="${user.id}"
            style="
                border: 1px solid ${isFlipside ? 'rgba(255,0,128,0.2)' : '#e0e0e0'};
                background: ${isFlipside ? '#1a1a1a' : 'white'};
                border-radius: 12px;
                padding: 12px;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            ">

            <div class="d-flex align-items-center gap-2">
                <a href="/profilePage/${user.username}" onclick="closeModal()" style="text-decoration: none;">
                    <img src="${avatar}" 
                        alt="${user.name}"
                        style="
                            width: 45px;
                            height: 45px;
                            border-radius: 50%;
                            object-fit: cover;
                            border: 2px solid ${isFlipside ? 'rgba(255,0,128,0.3)' : '#e0e0e0'};
                        ">
                </a>

                <div>
                    <a href="/profilePage/${user.username}" onclick="closeModal()" style="text-decoration: none;">
                        <strong style="color:${isFlipside ? 'white' : '#1a1a1a'};">${user.name}</strong>
                    </a>
                    <small style="color:${isFlipside ? 'rgba(255,255,255,0.6)' : '#666'};">@${user.username}</small>
                </div>
            </div>

            ${actionButton}
        </div>`;
    }).join('');

    openModal(
        title, 
        `
        <h6 class="mb-3" style="color:${isFlipside ? 'white' : '#1a1a1a'}; font-weight: 700;">
            ${title} (<span id="userListCount">${users.length}</span>)
        </h6>
        <div id="userListContainer">${listHTML}</div>
        `
    );
}


    // UNFOLLOW USER
    // Function untuk UNFOLLOW user (menghapus dari following list)
async function unfollowUser(userId, button) {
    if (!confirm('Yakin ingin berhenti mengikuti user ini?')) {
        return;
    }

    const userItem = button.closest('.user-list-item');
    const originalButtonHTML = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        // Endpoint untuk UNFOLLOW
        const response = await fetch('/follow/destroy/' + userId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        // Perbaikan: Cek berdasarkan HTTP status DAN message
        if (response.ok && (result.message === 'unfoll success' || result.status === 200)) {
            // Animasi remove
            userItem.style.transition = 'all 0.3s ease';
            userItem.style.opacity = '0';
            userItem.style.transform = 'translateX(100%)';
            
            setTimeout(() => {
                userItem.remove();
                
                // Update counter
                const countElement = document.getElementById('userListCount');
                if (countElement) {
                    const currentCount = parseInt(countElement.textContent);
                    countElement.textContent = currentCount - 1;
                }
                
                // Update window data jika ada
                if (window.appData && window.appData.following) {
                    window.appData.following = window.appData.following.filter(f => f.id !== userId);
                }
                
                // Tampilkan pesan kosong jika sudah tidak ada
                const container = document.getElementById('userListContainer');
                if (container && container.children.length === 0) {
                    container.innerHTML = '<p class="text-center text-muted py-3">Belum ada following.</p>';
                }
            }, 300);

            showNotification('✅ Berhasil unfollow!');
        } else {
            throw new Error(result.message || 'Failed to unfollow');
        }
    } catch (error) {
        console.error('Unfollow error:', error);
        showNotification('❌ Gagal unfollow. Coba lagi!');
        button.disabled = false;
        button.innerHTML = originalButtonHTML;
    }
}

// Function untuk REMOVE FOLLOWER (menghapus dari followers list)
async function removeFollower(userId, button) {
    if (!confirm('Yakin ingin menghapus follower ini?')) {
        return;
    }

    const userItem = button.closest('.user-list-item');
    const originalButtonHTML = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const response = await fetch('/follow/remove/' + userId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        // ✅ PERBAIKAN: Cek success dari backend
        if (response.ok && result.success) {
            userItem.style.transition = 'all 0.3s ease';
            userItem.style.opacity = '0';
            userItem.style.transform = 'translateX(100%)';
            
            setTimeout(() => {
                userItem.remove();
                
                const countElement = document.getElementById('userListCount');
                if (countElement) {
                    const currentCount = parseInt(countElement.textContent);
                    countElement.textContent = currentCount - 1;
                }
                
                if (window.appData && window.appData.followers) {
                    window.appData.followers = window.appData.followers.filter(f => f.id !== userId);
                }
                
                const container = document.getElementById('userListContainer');
                if (container && container.children.length === 0) {
                    container.innerHTML = '<p class="text-center text-muted py-3">Belum ada followers.</p>';
                }
            }, 300);

            // Gunakan message dari backend
            showNotification('✅ ' + result.message);
        } else {
            // Tampilkan error message dari backend
            throw new Error(result.message || 'Gagal menghapus follower');
        }
    } catch (error) {
        console.error('Remove follower error:', error);
        showNotification('❌ ' + (error.message || 'Gagal menghapus follower. Coba lagi!'));
        button.disabled = false;
        button.innerHTML = originalButtonHTML;
    }
}

// Helper function untuk notification
    function showNotification(message) {
        // Implementasi sesuai dengan sistem notifikasi Anda
        alert(message);
    }

    // Modal functions
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

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
        notification.style.cssText = 'top: 90px; right: 20px; z-index: 3000; animation: slideInRight 0.3s ease; font-size: 14px;';
        notification.innerHTML = message;

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // FLIPSIDE POST MODAL
    function openFlipsidePostModal() {
        document.getElementById('flipsidePostModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        const caption = document.getElementById('flipsidePostCaption');
        if (caption) caption.focus();
    }

    function closeFlipsidePostModal() {
        document.getElementById('flipsidePostModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        
        document.getElementById('flipsidePostForm').reset();
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('charCounter').textContent = '0 / 1000';
    }

    const captionEl = document.getElementById('flipsidePostCaption');
    if (captionEl) {
        captionEl.addEventListener('input', function() {
            const counter = document.getElementById('charCounter');
            const length = this.value.length;
            counter.textContent = length + ' / 1000';
            
            if (length > 900) {
                counter.style.color = '#FF0080';
            } else if (length > 700) {
                counter.style.color = '#FFD700';
            } else {
                counter.style.color = 'rgba(255, 255, 255, 0.5)';
            }
        });
    }

    function triggerFlipsideImageUpload() {
        document.getElementById('flipsideImageInput').click();
    }

    function previewFlipsideImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            showNotification('❌ File terlalu besar! Maksimal 5MB');
            event.target.value = '';
            return;
        }

        if (!file.type.startsWith('image/')) {
            showNotification('❌ File harus berupa gambar!');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    function removeFlipsideImage() {
        document.getElementById('flipsideImageInput').value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }

    async function submitFlipsidePost(event) {
        event.preventDefault();
        
        const submitBtn = document.getElementById('flipsideSubmitBtn');
        const caption = document.getElementById('flipsidePostCaption').value.trim();
        const imageInput = document.getElementById('flipsideImageInput');
        
        if (!caption) {
            showNotification('⚠️ Caption tidak boleh kosong!');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Posting...';

        try {
            const formData = new FormData();
            formData.append('caption', caption);
            formData.append('is_flipside', '1');
            
            // Append CSRF token to FormData instead of header
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);
            
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }

            const response = await fetch('/post/store', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
                credentials: 'same-origin' // Important for session cookies
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showNotification('🔥 Flipside post berhasil dibuat!');
                closeFlipsidePostModal();
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(result.message || 'Failed to create post');
            }
        } catch (error) {
            console.error('Error creating flipside post:', error);
            showNotification('❌ Gagal membuat post. Coba lagi!');
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Post to Flipside';
        }
    }

    // FLIPSIDE FOLLOWERS MODAL
    let flipsideFollowersData = [];
    let flipsideAccessList = [];

    async function openFlipsideFollowersModal() {
        document.getElementById('flipsideFollowersModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        await loadFlipsideFollowers();
    }

    function closeFlipsideFollowersModal() {
        document.getElementById('flipsideFollowersModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    async function loadFlipsideFollowers() {
        try {
            const followersRes = await fetch('/followers', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const flipsideRes = await fetch('/flipside-followers', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const followersData = await followersRes.json();
            const flipsideData = await flipsideRes.json();

            flipsideFollowersData = followersData.data || (window.appData ? window.appData.followers : []) || [];
            flipsideAccessList = flipsideData.data || [];

            renderFlipsideFollowers(flipsideFollowersData);
            updateFlipsideStats();
        } catch (error) {
            console.error('Error loading flipside followers:', error);
            flipsideFollowersData = window.appData ? window.appData.followers || [] : [];
            renderFlipsideFollowers(flipsideFollowersData);
            updateFlipsideStats();
        }
    }

    function renderFlipsideFollowers(followers) {
        const container = document.getElementById('flipsideFollowersList');
        
        if (!followers || followers.length === 0) {
            container.innerHTML = '<div style="text-align: center; padding: 40px 20px; color: rgba(255, 255, 255, 0.5);"><i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 15px; display: block;"></i><p>Belum ada followers.<br>Share profile kamu untuk mendapatkan followers!</p></div>';
            return;
        }

        container.innerHTML = followers.map(follower => {
            const hasAccess = flipsideAccessList.includes(follower.id);
            const avatar = follower.avatar 
                ? '<img src="/storage/' + follower.avatar + '" alt="' + follower.name + '" style="width: 50px; height: 50px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(121, 40, 202, 0.3);">'
                : '<div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #7928CA, #FF0080); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">' + ((follower.name || '?').substring(0, 2).toUpperCase()) + '</div>';

            return '<div class="flipside-follower-item" data-user-id="' + follower.id + '" style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #0d0d0d; border: 1px solid rgba(121, 40, 202, 0.2); border-radius: 12px; margin-bottom: 10px; transition: all 0.3s ease;"><div class="flipside-follower-info" style="display: flex; align-items: center; gap: 12px; flex: 1;">' + avatar + '<div class="flipside-follower-details"><h4 style="margin: 0; font-size: 16px; font-weight: 700; color: white;">' + (follower.name || 'Unknown') + '</h4><p style="margin: 0; font-size: 13px; color: rgba(255, 255, 255, 0.6);">@' + (follower.username || 'unknown') + '</p></div></div><div class="flipside-toggle-switch ' + (hasAccess ? 'active' : '') + '" onclick="toggleFlipsideAccess(' + follower.id + ', this)" style="position: relative; width: 50px; height: 26px; background: ' + (hasAccess ? 'linear-gradient(135deg, #7928CA, #FF0080)' : 'rgba(255, 255, 255, 0.1)') + '; border-radius: 13px; cursor: pointer; transition: all 0.3s ease; border: 2px solid ' + (hasAccess ? 'transparent' : 'rgba(255, 255, 255, 0.2)') + ';"><div class="flipside-toggle-slider" style="position: absolute; top: 2px; left: ' + (hasAccess ? '26px' : '2px') + '; width: 18px; height: 18px; background: white; border-radius: 50%; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"></div></div></div>';
        }).join('');
    }

    function filterFlipsideFollowers() {
        const searchTerm = document.getElementById('flipsideSearchBox').value.toLowerCase();
        const filtered = flipsideFollowersData.filter(follower => {
            const name = (follower.name || '').toLowerCase();
            const username = (follower.username || '').toLowerCase();
            return name.includes(searchTerm) || username.includes(searchTerm);
        });
        renderFlipsideFollowers(filtered);
    }

    async function toggleFlipsideAccess(userId, toggleElement) {
        const isActive = toggleElement.classList.contains('active');
        
        try {
            const response = await fetch('/flipside-access/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    grant_access: !isActive
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                toggleElement.classList.toggle('active');
                
                const slider = toggleElement.querySelector('.flipside-toggle-slider');
                if (isActive) {
                    toggleElement.style.background = 'rgba(255, 255, 255, 0.1)';
                    toggleElement.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                    slider.style.left = '2px';
                    flipsideAccessList = flipsideAccessList.filter(id => id !== userId);
                    showNotification('❌ Flipside access removed');
                } else {
                    toggleElement.style.background = 'linear-gradient(135deg, #7928CA, #FF0080)';
                    toggleElement.style.borderColor = 'transparent';
                    slider.style.left = '26px';
                    flipsideAccessList.push(userId);
                    showNotification('✅ Flipside access granted!');
                }
                
                updateFlipsideStats();
            } else {
                throw new Error(result.message || 'Failed to update access');
            }
        } catch (error) {
            console.error('Error toggling flipside access:', error);
            showNotification('⚠️ Failed to update access. Try again!');
        }
    }

    function updateFlipsideStats() {
        document.getElementById('totalFollowersCount').textContent = flipsideFollowersData.length;
        document.getElementById('flipsideFollowersCount').textContent = flipsideAccessList.length;
    }

    // POST DETAIL MODAL FOR LIKED POSTS
    document.addEventListener('DOMContentLoaded', function() {
        var postModal = new bootstrap.Modal(document.getElementById('postModal'));
        var postModalContent = document.getElementById('postModalContent');

        document.querySelectorAll('.openPostModal').forEach(function(el) {
            el.addEventListener('click', function() {
                var postId = this.getAttribute('data-post-id');

                postModalContent.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading post details...</p></div>';
                postModal.show();

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
                        let modalContent = '';

                        modalContent += '<div class="d-flex align-items-center mb-3">' + (data.post.user.avatar ? '<img src="/storage/' + data.post.user.avatar + '" class="rounded-circle me-3" width="50" height="50" alt="Avatar" style="object-fit: cover;">' : '<div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px; font-weight: bold;">' + data.post.user.name.charAt(0).toUpperCase() + '</div>') + '<div><h6 class="mb-1"><a href="/profilePage/' + data.post.user.username + '" class="text-decoration-none text-dark">' + data.post.user.name + '</a></h6><small class="text-muted">@' + data.post.user.username + ' • ' + new Date(data.post.created_at).toLocaleDateString() + '</small></div></div>';

                        if (data.post.image) {
                            modalContent += '<div class="mb-3"><img src="/storage/' + data.post.image + '" class="img-fluid rounded shadow-sm" alt="Post image" style="max-height: 400px; width: 100%; object-fit: cover; cursor: pointer;" onclick="openImageModal(\'/storage/' + data.post.image + '\')"></div>';
                        }

                        if (data.post.caption) {
                            modalContent += '<div class="mb-3"><p style="white-space: pre-line; font-size: 15px; line-height: 1.5;">' + data.post.caption + '</p></div>';
                        }

                        modalContent += '<div class="d-flex justify-content-between align-items-center mb-3 py-2 border-top border-bottom"><div class="d-flex gap-4"><span id="likesCount-' + data.post.id + '"><i class="fas fa-heart text-danger"></i> ' + (data.post.likes_count || 0) + ' likes</span><span id="commentsCount-' + data.post.id + '"><i class="fas fa-comment text-primary"></i> <span class="comment-count-number">' + (data.comments ? data.comments.length : 0) + '</span> comments</span></div><small class="text-muted">' + new Date(data.post.created_at).toLocaleString() + '</small></div>';

                        modalContent += '<div class="mb-3"><button class="btn btn-outline-danger btn-sm ' + (data.isLiked ? 'active' : '') + '" onclick="toggleLikeInModal(' + data.post.id + ', this)"><i class="fas fa-heart"></i> ' + (data.isLiked ? 'Unlike' : 'Like') + '</button></div>';

                        modalContent += '<hr><h6>Comments</h6><div class="comments-list" id="commentsList-' + data.post.id + '" style="max-height: 300px; overflow-y: auto;">';

                        if (data.comments && data.comments.length > 0) {
                            data.comments.forEach(comment => {
                                modalContent += '<div class="d-flex mb-3 comment-item">' + (comment.user.avatar ? '<img src="/storage/' + comment.user.avatar + '" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">' : '<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">' + comment.user.name.charAt(0).toUpperCase() + '</div>') + '<div class="flex-grow-1"><div class="bg-light rounded p-2"><strong class="small">' + comment.user.name + '</strong><p class="mb-0 small">' + comment.content + '</p></div><small class="text-muted ms-2">' + new Date(comment.created_at).toLocaleString() + '</small></div></div>';
                            });
                        } else {
                            modalContent += '<p class="text-muted text-center py-3" id="noCommentsMsg-' + data.post.id + '">No comments yet. Be the first to comment!</p>';
                        }

                        modalContent += '</div><div class="mt-3 border-top pt-3"><div class="d-flex gap-2"><input type="text" class="form-control" id="commentInput-' + data.post.id + '" placeholder="Write a comment..." onkeypress="handleCommentKeyPress(event, ' + data.post.id + ')"><button class="btn btn-primary btn-sm" id="commentBtn-' + data.post.id + '" onclick="submitCommentFromModal(' + data.post.id + ')"><i class="fas fa-paper-plane"></i></button></div><div id="commentStatus-' + data.post.id + '" class="small mt-1" style="display: none;"></div></div>';

                        postModalContent.innerHTML = modalContent;
                    })
                    .catch(error => {
                        console.error('Error fetching post details:', error);
                        postModalContent.innerHTML = '<div class="text-center py-4"><div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i><p class="mb-0">Failed to load post details. Please try again.</p></div><button class="btn btn-secondary btn-sm" onclick="location.reload()">Refresh Page</button></div>';
                    });
            });
        });
    });

    async function toggleLikeInModal(postId, button) {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const isLiked = button.classList.contains('active');
    
    // ✅ DETEKSI TYPE BERDASARKAN MODE
    const type = window.appData.isFlipside ? 'flipside' : 'main';

    button.disabled = true;

    try {
        // ✅ ENDPOINT DENGAN TYPE PARAMETER
        const response = await fetch(
            isLiked 
                ? `/like/destroy/${postId}/${type}` 
                : `/like/store/${postId}/${type}`, 
            {
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
            // Toggle button state
            if (!isLiked) {
                button.classList.add("active");
                button.innerHTML = '<i class="fas fa-heart"></i> Unlike';
            } else {
                button.classList.remove("active");
                button.innerHTML = '<i class="fas fa-heart"></i> Like';
            }

            // Update likes count if available
            const likesCountElement = document.getElementById('likesCount-' + postId);
            if (likesCountElement && result.likes_count !== undefined) {
                likesCountElement.innerHTML = '<i class="fas fa-heart text-danger"></i> ' + result.likes_count + ' likes';
            }

            showNotification(isLiked ? "💔 Post unliked" : "❤️ Post liked!");
        } else {
            if (response.status === 409) {
                showNotification("⚠️ " + result.message);
            } else {
                throw new Error(result.message || "Something went wrong");
            }
        }
    } catch (error) {
        console.error('Like error:', error);
        showNotification("⚠️ Network error occurred");
    } finally {
        button.disabled = false;
    }
}
    
 // UPDATE: submitCommentFromModal dengan type
async function submitCommentFromModal(postId) {
    const commentInput = document.getElementById('commentInput-' + postId);
    const commentBtn = document.getElementById('commentBtn-' + postId);
    const commentStatus = document.getElementById('commentStatus-' + postId);
    const commentsList = document.getElementById('commentsList-' + postId);
    const noCommentsMsg = document.getElementById('noCommentsMsg-' + postId);

    const text = commentInput.value.trim();

    if (!text) {
        showNotification("⚠️ Comment cannot be empty!");
        return;
    }

    commentBtn.disabled = true;
    commentInput.disabled = true;
    commentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    commentStatus.className = 'small mt-1 text-info';
    commentStatus.textContent = 'Posting comment...';
    commentStatus.style.display = 'block';

    try {
        // ✅ Deteksi type berdasarkan mode
        const type = window.appData.isFlipside ? 'flipside' : 'main';
        
        console.log('Submitting comment from modal:', { post_id: postId, content: text, type: type });
        
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
                type: type // ✅ Tambahkan type
            })
        });

        const result = await response.json();
        
        console.log('Comment modal response:', result);

        if (response.ok && result.success) {
            commentInput.value = '';

            if (noCommentsMsg) {
                noCommentsMsg.remove();
            }

            const userData = result.data && result.data.user ? result.data.user : {
                name: 'You',
                active_avatar: null
            };

            const avatarSrc = userData.active_avatar ? '/storage/' + userData.active_avatar : null;
            const avatar = avatarSrc
                ? `<img src="${avatarSrc}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">`
                : `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">${userData.name.charAt(0).toUpperCase()}</div>`;

            const deleteBtn = `<button onclick="deleteCommentFromModal(${result.data.id}, ${postId})" style="background:none;border:none;color:#e74c3c;cursor:pointer;padding:4px;margin-left:8px;" title="Delete"><i class="fas fa-trash"></i></button>`;

            const newCommentHTML = `<div class="d-flex mb-3 comment-item comment-item-${result.data.id}">${avatar}<div class="flex-grow-1"><div class="bg-light rounded p-2"><strong class="small">${userData.name}</strong>${deleteBtn}<p class="mb-0 small">${result.data.content}</p></div><small class="text-muted ms-2">Just now</small></div></div>`;

            commentsList.insertAdjacentHTML('beforeend', newCommentHTML);

            const commentCountElement = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
            if (commentCountElement) {
                const currentCount = parseInt(commentCountElement.textContent) || 0;
                commentCountElement.textContent = currentCount + 1;
            }

            commentsList.scrollTop = commentsList.scrollHeight;

            commentStatus.className = 'small mt-1 text-success';
            commentStatus.textContent = '✅ Comment posted successfully!';

            setTimeout(() => {
                commentStatus.style.display = 'none';
            }, 2000);

            showNotification('💬 Comment posted!');

        } else {
            throw new Error(result.message || "Failed to post comment!");
        }
    } catch (error) {
        console.error('Error submitting comment:', error);
        commentStatus.className = 'small mt-1 text-danger';
        commentStatus.textContent = '❌ ' + error.message;
        showNotification("⚠️ " + error.message);
    } finally {
        commentBtn.disabled = false;
        commentInput.disabled = false;
        commentBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    }
}

// ✅ Function untuk delete comment dari modal
async function deleteCommentFromModal(commentId, postId) {
    if (!confirm('Yakin ingin menghapus komentar ini?')) {
        return;
    }

    try {
        const response = await fetch(`/comment/destroy/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            const commentElement = document.querySelector(`.comment-item-${commentId}`);
            if (commentElement) {
                commentElement.style.transition = 'all 0.3s ease';
                commentElement.style.opacity = '0';
                commentElement.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    commentElement.remove();
                    
                    // Update counter
                    const commentCountElement = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
                    if (commentCountElement) {
                        const currentCount = parseInt(commentCountElement.textContent) || 0;
                        commentCountElement.textContent = Math.max(0, currentCount - 1);
                    }
                    
                    showNotification('🗑️ Comment deleted!');
                }, 300);
            }
        } else {
            throw new Error(result.message || 'Failed to delete');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showNotification('❌ Failed to delete comment!');
    }
}

    function handleCommentKeyPress(event, postId) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            submitCommentFromModal(postId);
        }
    }

    // Event listeners
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        const imageModal = document.getElementById('imageModal');
        const flipsidePostModal = document.getElementById('flipsidePostModal');
        const flipsideFollowersModal = document.getElementById('flipsideFollowersModal');
        
        if (event.target === modal) closeModal();
        if (event.target === imageModal) closeImageModal();
        if (event.target === flipsidePostModal) closeFlipsidePostModal();
        if (event.target === flipsideFollowersModal) closeFlipsideFollowersModal();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeImageModal();
            closeFlipsidePostModal();
            closeFlipsideFollowersModal();
        }
    });

    // Animate posts on load
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

// ===== FLIPSIDE AVATAR FUNCTIONS =====
// Function untuk edit Flipside Avatar
function editFlipsideAvatar() {
    const content = `
        <div style="text-align: center; padding: 20px;">
            <div style="margin-bottom: 20px;">
                <div style="
                    width: 150px; 
                    height: 150px; 
                    border-radius: 20px; 
                    background: linear-gradient(135deg, #FF0080, #7928CA); 
                    margin: 0 auto 20px; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                    border: 4px solid rgba(255, 0, 128, 0.3);
                    overflow: hidden;
                ">
                    <img id="flipsideAvatarPreview" 
                         src="${window.appData.user.flipside_avatar ? '/storage/' + window.appData.user.flipside_avatar : ''}" 
                         style="width: 100%; height: 100%; object-fit: cover; ${window.appData.user.flipside_avatar ? '' : 'display:none;'}"
                         alt="Flipside Avatar">
                    <span id="flipsideAvatarInitial" style="font-size: 48px; color: white; font-weight: bold; ${window.appData.user.flipside_avatar ? 'display:none;' : ''}">
                        ${window.appData.user.name.charAt(0).toUpperCase()}
                    </span>
                </div>
                
                <h5 style="color: white; margin-bottom: 10px;">🔥 Flipside Avatar</h5>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; margin-bottom: 20px;">
                    Avatar ini hanya terlihat di mode Flipside
                </p>
            </div>
            
            <form id="flipsideAvatarUploadForm" style="max-width: 400px; margin: 0 auto;">
                <div class="mb-3">
                    <input 
                        type="file" 
                        class="form-control" 
                        id="flipsideAvatarInput" 
                        accept="image/*" 
                        onchange="previewFlipsideAvatarImage(event)"
                        style="
                            background: #0d0d0d; 
                            border: 2px solid rgba(255, 0, 128, 0.3); 
                            border-radius: 12px; 
                            color: white;
                            padding: 12px;
                        "
                    >
                    <small style="color: rgba(255, 255, 255, 0.5); display: block; margin-top: 8px;">
                        Max size: 2MB • JPG, PNG, GIF
                    </small>
                </div>
                
                <div id="flipsideAvatarCropContainer" style="display: none; margin: 20px 0;">
                    <div style="max-height: 400px; overflow: hidden; border-radius: 12px; border: 2px solid rgba(255, 0, 128, 0.3);">
                        <img id="flipsideAvatarCropImage" style="max-width: 100%;">
                    </div>
                    
                    <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                        <button type="button" class="btn btn-sm" onclick="flipsideCropperZoom(0.1)" style="background: rgba(255, 0, 128, 0.2); color: white; border: 1px solid rgba(255, 0, 128, 0.3);">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCropperZoom(-0.1)" style="background: rgba(255, 0, 128, 0.2); color: white; border: 1px solid rgba(255, 0, 128, 0.3);">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCropperRotate(-45)" style="background: rgba(255, 0, 128, 0.2); color: white; border: 1px solid rgba(255, 0, 128, 0.3);">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCropperRotate(45)" style="background: rgba(255, 0, 128, 0.2); color: white; border: 1px solid rgba(255, 0, 128, 0.3);">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button 
                        type="button" 
                        id="flipsideAvatarUploadBtn" 
                        class="btn me-2" 
                        onclick="uploadFlipsideAvatar()"
                        style="
                            background: linear-gradient(135deg, #FF0080, #7928CA); 
                            color: white; 
                            border: none; 
                            padding: 12px 30px; 
                            border-radius: 25px; 
                            font-weight: 700;
                            display: none;
                        "
                    >
                        <i class="fas fa-upload"></i> Upload Flipside Avatar
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-secondary" 
                        onclick="closeModal()"
                        style="padding: 12px 30px; border-radius: 25px;"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    openModal('🔥 Change Flipside Avatar', content);
}

let flipsideAvatarCropper = null;

function previewFlipsideAvatarImage(e) {
    const file = e.target.files[0];
    
    if (!file) return;
    
    if (file.size > 2 * 1024 * 1024) {
        showNotification('❌ File terlalu besar! Max 2MB');
        e.target.value = '';
        return;
    }
    
    if (!file.type.startsWith('image/')) {
        showNotification('❌ File harus berupa gambar!');
        e.target.value = '';
        return;
    }
    
    // Destroy existing cropper
    if (flipsideAvatarCropper) {
        flipsideAvatarCropper.destroy();
    }
    
    const reader = new FileReader();
    reader.onload = function(event) {
        const cropImage = document.getElementById('flipsideAvatarCropImage');
        cropImage.src = event.target.result;
        
        document.getElementById('flipsideAvatarCropContainer').style.display = 'block';
        document.getElementById('flipsideAvatarUploadBtn').style.display = 'inline-block';
        
        // Initialize Cropper.js
        flipsideAvatarCropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            responsive: true,
            background: false,
            autoCropArea: 1,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false
        });
    };
    reader.readAsDataURL(file);
}

function flipsideCropperZoom(ratio) {
    if (flipsideAvatarCropper) {
        flipsideAvatarCropper.zoom(ratio);
    }
}

function flipsideCropperRotate(degree) {
    if (flipsideAvatarCropper) {
        flipsideAvatarCropper.rotate(degree);
    }
}

async function uploadFlipsideAvatar() {
    if (!flipsideAvatarCropper) {
        showNotification('⚠️ Pilih gambar terlebih dahulu!');
        return;
    }
    
    const uploadBtn = document.getElementById('flipsideAvatarUploadBtn');
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    
    try {
        const canvas = flipsideAvatarCropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        const blob = await new Promise(resolve => {
            canvas.toBlob(resolve, 'image/jpeg', 0.9);
        });
        
        const formData = new FormData();
        formData.append('flipside_avatar', blob, 'flipside_avatar.jpg');
        
        const response = await fetch('/profile/update-flipside-avatar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('🔥 Flipside avatar berhasil diupdate!');
            
            // Destroy cropper
            if (flipsideAvatarCropper) {
                flipsideAvatarCropper.destroy();
                flipsideAvatarCropper = null;
            }
            
            closeModal();
            
            // Reload page untuk update avatar
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to upload flipside avatar');
        }
    } catch (error) {
        console.error('Upload error:', error);
        showNotification('❌ Gagal upload avatar. Coba lagi!');
        
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Flipside Avatar';
    }
}    

// ===== FLIPSIDE NAME SETUP =====

// Cek saat halaman load di flipside mode
document.addEventListener('DOMContentLoaded', function() {
    const isFlipside = window.appData.isFlipside;
    const hasFlipsideName = window.appData.user.flipside_name;
    
    // Jika di flipside mode DAN belum punya flipside name
    if (isFlipside && !hasFlipsideName) {
        setTimeout(() => {
            openFlipsideNameModal();
        }, 500);
    }
});

function openFlipsideNameModal() {
    const modal = document.getElementById('flipsideNameModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    const input = document.getElementById('flipsideNameInput');
    if (input) {
        input.focus();
        
        // Suggest default name
        if (window.appData.user.name) {
            input.placeholder = 'e.g., Dark ' + window.appData.user.name;
        }
    }
}

function closeFlipsideNameModal() {
    const modal = document.getElementById('flipsideNameModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

async function submitFlipsideName(event) {
    event.preventDefault();
    
    const submitBtn = document.getElementById('flipsideNameSubmitBtn');
    const input = document.getElementById('flipsideNameInput');
    const flipsideName = input.value.trim();
    
    if (!flipsideName) {
        showNotification('⚠️ Flipside name tidak boleh kosong!');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
    
    try {
        const response = await fetch('/profile/update-flipside-name', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                flipside_name: flipsideName
            })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('🎭 Flipside identity set!');
            
            // Update window data
            window.appData.user.flipside_name = result.flipside_name;
            
            closeFlipsideNameModal();
            
            // Reload untuk update UI
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to set flipside name');
        }
    } catch (error) {
        console.error('Error setting flipside name:', error);
        showNotification('❌ Gagal menyimpan. Coba lagi!');
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Set Flipside Name';
    }
}

// Function untuk edit flipside name (bisa dipanggil dari menu)
function editFlipsideName() {
    const currentName = window.appData.user.flipside_name || '';
    
    const content = `
        <div style="padding: 20px;">
            <h5 style="color: white; margin-bottom: 20px; text-align: center;">
                🎭 Edit Flipside Name
            </h5>
            
            <form onsubmit="updateFlipsideNameFromEdit(event)">
                <div class="mb-3">
                    <input 
                        type="text" 
                        id="editFlipsideNameInput" 
                        class="form-control" 
                        value="${currentName}"
                        placeholder="Enter your secret identity..."
                        maxlength="100"
                        required
                        style="
                            background: #0d0d0d; 
                            border: 2px solid rgba(255, 0, 128, 0.3); 
                            border-radius: 12px; 
                            padding: 12px; 
                            color: white; 
                            font-size: 16px;
                        "
                    >
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button 
                        type="submit" 
                        class="btn me-2" 
                        style="
                            background: linear-gradient(135deg, #FF0080, #7928CA); 
                            color: white; 
                            border: none; 
                            padding: 10px 25px; 
                            border-radius: 20px; 
                            font-weight: 600;
                        "
                    >
                        <i class="fas fa-save"></i> Save
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-secondary" 
                        onclick="closeModal()"
                        style="padding: 10px 25px; border-radius: 20px;"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    openModal('Edit Flipside Name', content);
}

async function updateFlipsideNameFromEdit(event) {
    event.preventDefault();
    
    const input = document.getElementById('editFlipsideNameInput');
    const flipsideName = input.value.trim();
    
    if (!flipsideName) {
        showNotification('⚠️ Name cannot be empty!');
        return;
    }
    
    try {
        const response = await fetch('/profile/update-flipside-name', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                flipside_name: flipsideName
            })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('✅ Flipside name updated!');
            closeModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to update');
        }
    } catch (error) {
        console.error('Update error:', error);
        showNotification('❌ Failed to update. Try again!');
    }
}

let coverCropper = null;

function editCover() {
    const hasCover = {{ $user->cover ? 'true' : 'false' }};
    const isFlipside = {{ $isFlipside ? 'true' : 'false' }};
    
    const content = `
        <div style="padding: 20px;">
            ${hasCover ? `
                <div class="current-cover mb-3" style="text-align: center;">
                    <p style="color: ${isFlipside ? 'rgba(255,255,255,0.8)' : 'rgba(0,0,0,0.7)'}; margin-bottom: 10px; font-weight: 600;">Current Cover:</p>
                    <img src="{{ asset('storage/' . ($user->cover ?? '')) }}" 
                         style="max-width: 100%; max-height: 180px; border-radius: 12px; object-fit: cover; border: 2px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'};">
                </div>
            ` : ''}
            
            <form id="coverUploadForm">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: ${isFlipside ? 'white' : 'inherit'};">
                        <i class="fas fa-image me-2"></i>Upload New Cover Image
                    </label>
                    <input 
                        type="file" 
                        class="form-control" 
                        id="coverInput"
                        accept="image/*" 
                        required
                        onchange="initCoverCropper(event)"
                        style="background: ${isFlipside ? '#0d0d0d' : 'white'}; border: 2px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; color: ${isFlipside ? 'white' : 'inherit'}; padding: 12px; border-radius: 8px;"
                    >
                    <small style="color: ${isFlipside ? 'rgba(255,255,255,0.6)' : 'rgba(0,0,0,0.6)'}; display: block; margin-top: 8px;">
                        <i class="fas fa-info-circle"></i> Aspect Ratio: 3.6:1 (900x250px) • Max 5MB
                    </small>
                </div>
                
                <!-- CROPPER CONTAINER -->
                <div id="coverCropContainer" style="display: none; margin: 20px 0;">
                    <div style="max-height: 400px; overflow: hidden; border-radius: 12px; border: 2px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; background: ${isFlipside ? '#0d0d0d' : '#f5f5f5'};">
                        <img id="coverCropImage" style="max-width: 100%; display: block;">
                    </div>
                    
                    <!-- CROPPER CONTROLS -->
                    <div style="margin-top: 15px; text-align: center; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm" onclick="coverCropperZoom(0.1)" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-search-plus"></i> Zoom In
                        </button>
                        <button type="button" class="btn btn-sm" onclick="coverCropperZoom(-0.1)" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-search-minus"></i> Zoom Out
                        </button>
                        <button type="button" class="btn btn-sm" onclick="coverCropperMove('left')" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="coverCropperMove('right')" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="coverCropperMove('up')" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="coverCropperMove('down')" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="coverCropperReset()" 
                                style="background: ${isFlipside ? 'rgba(255, 0, 128, 0.2)' : 'rgba(102, 126, 234, 0.1)'}; 
                                       color: ${isFlipside ? '#FF0080' : '#667eea'}; 
                                       border: 1px solid ${isFlipside ? 'rgba(255, 0, 128, 0.3)' : 'rgba(102, 126, 234, 0.3)'}; 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
                
                <div class="text-center mt-4" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary" id="cropAndUploadBtn" onclick="cropAndUploadCover()" 
                            style="display: none; background: ${isFlipside ? 'linear-gradient(135deg, #FF0080, #7928CA)' : 'linear-gradient(135deg, #667eea, #764ba2)'}; border: none; padding: 12px 30px; border-radius: 25px; font-weight: 700; box-shadow: 0 4px 15px ${isFlipside ? 'rgba(255, 0, 128, 0.4)' : 'rgba(102, 126, 234, 0.4)'};">
                        <i class="fas fa-check-circle me-2"></i>Crop & Upload
                    </button>
                    ${hasCover ? `
                        <button type="button" class="btn btn-danger" onclick="removeCover()" 
                                style="padding: 12px 30px; border-radius: 25px; font-weight: 700;">
                            <i class="fas fa-trash me-2"></i>Remove Cover
                        </button>
                    ` : ''}
                    <button type="button" class="btn btn-secondary" onclick="closeCoverModal()" 
                            style="padding: 12px 30px; border-radius: 25px; font-weight: 700;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    openModal('🎨 Change Cover Image', content);
}

function initCoverCropper(event) {
    const file = event.target.files[0];
    
    if (!file) return;
    
    if (file.size > 5 * 1024 * 1024) {
        showNotification('❌ File terlalu besar! Max 5MB');
        event.target.value = '';
        return;
    }
    
    if (!file.type.startsWith('image/')) {
        showNotification('❌ File harus berupa gambar!');
        event.target.value = '';
        return;
    }
    
    // Destroy existing cropper
    if (coverCropper) {
        coverCropper.destroy();
        coverCropper = null;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('coverCropImage');
        cropImage.src = e.target.result;
        
        document.getElementById('coverCropContainer').style.display = 'block';
        document.getElementById('cropAndUploadBtn').style.display = 'inline-block';
        
        // Initialize Cropper.js - aspect ratio sesuai cover section (900px:250px = 3.6:1)
        coverCropper = new Cropper(cropImage, {
            aspectRatio: 3.6, // 900px width : 250px height (dari CSS .cover-section)
            viewMode: 2,
            responsive: true,
            background: false,
            autoCropArea: 1,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            minContainerWidth: 450,
            minContainerHeight: 125,
            minCropBoxWidth: 450,
            minCropBoxHeight: 125
        });
        
        showNotification('✅ Foto berhasil dimuat! Sesuaikan posisi cover.');
    };
    reader.readAsDataURL(file);
}

function coverCropperZoom(ratio) {
    if (coverCropper) {
        coverCropper.zoom(ratio);
    }
}

function coverCropperMove(direction) {
    if (!coverCropper) return;
    
    const moveDistance = 10;
    switch(direction) {
        case 'left':
            coverCropper.move(-moveDistance, 0);
            break;
        case 'right':
            coverCropper.move(moveDistance, 0);
            break;
        case 'up':
            coverCropper.move(0, -moveDistance);
            break;
        case 'down':
            coverCropper.move(0, moveDistance);
            break;
    }
}

function coverCropperReset() {
    if (coverCropper) {
        coverCropper.reset();
        showNotification('🔄 Cover direset ke posisi awal');
    }
}

async function cropAndUploadCover() {
    if (!coverCropper) {
        showNotification('⚠️ Pilih gambar terlebih dahulu!');
        return;
    }
    
    const uploadBtn = document.getElementById('cropAndUploadBtn');
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    
    try {
        // Crop dengan ukuran yang sesuai dengan cover section display
        // 900px (max-width container) x 250px (height cover)
        const canvas = coverCropper.getCroppedCanvas({
            width: 1800,  // 2x resolution untuk quality
            height: 500,  // 2x dari 250px
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        const blob = await new Promise(resolve => {
            canvas.toBlob(resolve, 'image/jpeg', 0.92);
        });
        
        const formData = new FormData();
        formData.append('cover', blob, 'cover.jpg');
        
        const response = await fetch('/profile/update-cover', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('✅ Cover berhasil diupdate!');
            
            if (coverCropper) {
                coverCropper.destroy();
                coverCropper = null;
            }
            
            closeModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to update cover');
        }
    } catch (error) {
        console.error('Update cover error:', error);
        showNotification('❌ Gagal update cover. Coba lagi!');
        
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Crop & Upload';
    }
}

function closeCoverModal() {
    if (coverCropper) {
        coverCropper.destroy();
        coverCropper = null;
    }
    closeModal();
}

async function removeCover() {
    if (!confirm('Yakin ingin menghapus cover image?')) {
        return;
    }
    
    try {
        const response = await fetch('/profile/remove-cover', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('✅ Cover berhasil dihapus!');
            closeModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to remove cover');
        }
    } catch (error) {
        console.error('Remove cover error:', error);
        showNotification('❌ Gagal hapus cover. Coba lagi!');
    }
}

// Function untuk view cover full size
function viewCoverFullSize(event) {
    event.stopPropagation(); // Prevent editCover from triggering
    
    const coverImage = '{{ asset("storage/" . ($user->cover ?? "")) }}';
    
    if (!coverImage) {
        showNotification('⚠️ Tidak ada cover image');
        return;
    }
    
    openImageModal(coverImage);
}

// ===== FLIPSIDE COVER FUNCTIONS =====

let flipsideCoverCropper = null;

/**
 * Edit Flipside Cover
 */
function editFlipsideCover() {
    const hasCover = {{ $user->flipside_cover ? 'true' : 'false' }};
    
    const content = `
        <div style="padding: 20px;">
            ${hasCover ? `
                <div class="current-cover mb-3" style="text-align: center;">
                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 10px; font-weight: 600;">
                        🔥 Current Flipside Cover:
                    </p>
                    <img src="{{ asset('storage/' . ($user->flipside_cover ?? '')) }}" 
                         style="max-width: 100%; max-height: 180px; border-radius: 12px; 
                                object-fit: cover; border: 2px solid rgba(255, 0, 128, 0.3);">
                </div>
            ` : ''}
            
            <form id="flipsideCoverUploadForm">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: white;">
                        <i class="fas fa-image me-2"></i>Upload Flipside Cover Image
                    </label>
                    <input 
                        type="file" 
                        class="form-control" 
                        id="flipsideCoverInput"
                        accept="image/*" 
                        required
                        onchange="initFlipsideCoverCropper(event)"
                        style="background: #0d0d0d; border: 2px solid rgba(255, 0, 128, 0.3); 
                               color: white; padding: 12px; border-radius: 8px;"
                    >
                    <small style="color: rgba(255,255,255,0.6); display: block; margin-top: 8px;">
                        <i class="fas fa-info-circle"></i> Aspect Ratio: 3.6:1 (900x250px) • Max 5MB
                    </small>
                </div>
                
                <!-- CROPPER CONTAINER -->
                <div id="flipsideCoverCropContainer" style="display: none; margin: 20px 0;">
                    <div style="max-height: 400px; overflow: hidden; border-radius: 12px; 
                                border: 2px solid rgba(255, 0, 128, 0.3); background: #0d0d0d;">
                        <img id="flipsideCoverCropImage" style="max-width: 100%; display: block;">
                    </div>
                    
                    <!-- CROPPER CONTROLS -->
                    <div style="margin-top: 15px; text-align: center; display: flex; gap: 8px; 
                                justify-content: center; flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperZoom(0.1)" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-search-plus"></i> Zoom In
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperZoom(-0.1)" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-search-minus"></i> Zoom Out
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperMove('left')" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperMove('right')" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperMove('up')" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperMove('down')" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="flipsideCoverCropperReset()" 
                                style="background: rgba(255, 0, 128, 0.2); color: #FF0080; 
                                       border: 1px solid rgba(255, 0, 128, 0.3); 
                                       padding: 8px 16px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
                
                <div class="text-center mt-4" style="display: flex; gap: 10px; 
                                                     justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary" id="flipsideCropAndUploadBtn" 
                            onclick="cropAndUploadFlipsideCover()" 
                            style="display: none; background: linear-gradient(135deg, #FF0080, #7928CA); 
                                   border: none; padding: 12px 30px; border-radius: 25px; 
                                   font-weight: 700; box-shadow: 0 4px 15px rgba(255, 0, 128, 0.4);">
                        <i class="fas fa-check-circle me-2"></i>Crop & Upload
                    </button>
                    ${hasCover ? `
                        <button type="button" class="btn btn-danger" onclick="removeFlipsideCover()" 
                                style="padding: 12px 30px; border-radius: 25px; font-weight: 700;">
                            <i class="fas fa-trash me-2"></i>Remove Cover
                        </button>
                    ` : ''}
                    <button type="button" class="btn btn-secondary" onclick="closeFlipsideCoverModal()" 
                            style="padding: 12px 30px; border-radius: 25px; font-weight: 700;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    openModal('🔥 Change Flipside Cover', content);
}

/**
 * Initialize Flipside Cover Cropper
 */
function initFlipsideCoverCropper(event) {
    const file = event.target.files[0];
    
    if (!file) return;
    
    if (file.size > 5 * 1024 * 1024) {
        showNotification('❌ File terlalu besar! Max 5MB');
        event.target.value = '';
        return;
    }
    
    if (!file.type.startsWith('image/')) {
        showNotification('❌ File harus berupa gambar!');
        event.target.value = '';
        return;
    }
    
    // Destroy existing cropper
    if (flipsideCoverCropper) {
        flipsideCoverCropper.destroy();
        flipsideCoverCropper = null;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('flipsideCoverCropImage');
        cropImage.src = e.target.result;
        
        document.getElementById('flipsideCoverCropContainer').style.display = 'block';
        document.getElementById('flipsideCropAndUploadBtn').style.display = 'inline-block';
        
        // Initialize Cropper.js
        flipsideCoverCropper = new Cropper(cropImage, {
            aspectRatio: 3.6,
            viewMode: 2,
            responsive: true,
            background: false,
            autoCropArea: 1,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            minContainerWidth: 450,
            minContainerHeight: 125,
            minCropBoxWidth: 450,
            minCropBoxHeight: 125
        });
        
        showNotification('✅ Foto berhasil dimuat! Sesuaikan posisi cover.');
    };
    reader.readAsDataURL(file);
}

/**
 * Flipside Cover Cropper Controls
 */
function flipsideCoverCropperZoom(ratio) {
    if (flipsideCoverCropper) {
        flipsideCoverCropper.zoom(ratio);
    }
}

function flipsideCoverCropperMove(direction) {
    if (!flipsideCoverCropper) return;
    
    const moveDistance = 10;
    switch(direction) {
        case 'left':
            flipsideCoverCropper.move(-moveDistance, 0);
            break;
        case 'right':
            flipsideCoverCropper.move(moveDistance, 0);
            break;
        case 'up':
            flipsideCoverCropper.move(0, -moveDistance);
            break;
        case 'down':
            flipsideCoverCropper.move(0, moveDistance);
            break;
    }
}

function flipsideCoverCropperReset() {
    if (flipsideCoverCropper) {
        flipsideCoverCropper.reset();
        showNotification('🔄 Cover direset ke posisi awal');
    }
}

/**
 * Crop and Upload Flipside Cover
 */
async function cropAndUploadFlipsideCover() {
    if (!flipsideCoverCropper) {
        showNotification('⚠️ Pilih gambar terlebih dahulu!');
        return;
    }
    
    const uploadBtn = document.getElementById('flipsideCropAndUploadBtn');
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    
    try {
        const canvas = flipsideCoverCropper.getCroppedCanvas({
            width: 1800,
            height: 500,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        const blob = await new Promise(resolve => {
            canvas.toBlob(resolve, 'image/jpeg', 0.92);
        });
        
        const formData = new FormData();
        formData.append('flipside_cover', blob, 'flipside_cover.jpg');
        
        const response = await fetch('/profile/update-flipside-cover', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('🔥 Flipside cover berhasil diupdate!');
            
            if (flipsideCoverCropper) {
                flipsideCoverCropper.destroy();
                flipsideCoverCropper = null;
            }
            
            closeModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to update flipside cover');
        }
    } catch (error) {
        console.error('Update flipside cover error:', error);
        showNotification('❌ Gagal update cover. Coba lagi!');
        
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Crop & Upload';
    }
}

/**
 * Remove Flipside Cover
 */
async function removeFlipsideCover() {
    if (!confirm('Yakin ingin menghapus Flipside cover?')) {
        return;
    }
    
    try {
        const response = await fetch('/profile/remove-flipside-cover', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification('✅ Flipside cover berhasil dihapus!');
            closeModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Failed to remove flipside cover');
        }
    } catch (error) {
        console.error('Remove flipside cover error:', error);
        showNotification('❌ Gagal hapus cover. Coba lagi!');
    }
}

/**
 * Close Flipside Cover Modal
 */
function closeFlipsideCoverModal() {
    if (flipsideCoverCropper) {
        flipsideCoverCropper.destroy();
        flipsideCoverCropper = null;
    }
    closeModal();
}

/**
 * View Cover Full Size (Updated untuk support main dan flipside)
 */
function viewCoverFullSize(event, mode = 'main') {
    event.stopPropagation();
    
    const coverImage = mode === 'flipside'
        ? '{{ asset("storage/" . ($user->flipside_cover ?? "")) }}'
        : '{{ asset("storage/" . ($user->cover ?? "")) }}';
    
    if (!coverImage) {
        showNotification('⚠️ Tidak ada cover image');
        return;
    }
    
    openImageModal(coverImage);
}

document.addEventListener("DOMContentLoaded", () => {
    const videos = document.querySelectorAll(".twitter-video");
    let currentlyPlaying = null;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const video = entry.target;

            if (entry.isIntersecting && entry.intersectionRatio >= 0.6) {
                if (currentlyPlaying && currentlyPlaying !== video) {
                    currentlyPlaying.pause();
                }
                video.play().catch(()=>{});
                currentlyPlaying = video;
            } else {
                video.pause();
                if (currentlyPlaying === video) currentlyPlaying = null;
            }
        });
    }, { threshold: [0, 0.6] });

    videos.forEach(video => observer.observe(video));

    // klik video → buka fullscreen
    videos.forEach(video => {
        video.addEventListener("click", () => {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                video.requestFullscreen();
            }
        });
    });

    // tombol mute/unmute
    const muteButtons = document.querySelectorAll(".mute-btn");
    muteButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation(); // jangan trigger fullscreen
            const video = btn.previousElementSibling; // video sebelah tombol
            if (video.muted) {
                video.muted = false;
                btn.textContent = "🔊";
            } else {
                video.muted = true;
                btn.textContent = "🔇";
            }
        });
    });
});
</script>

@endsection