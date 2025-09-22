@extends('layout.layarUtama')

@section('title', 'Profil Saya - Telava')

@section('content')
        <!-- Add CSRF token to head if not already present -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            window.appData = {
                followers: @json($followers),
                following: @json($following),
                followingIds: @json($followingIds),
                posts: @json($posts),
                user: @json($user)
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
                /* default hidden */
                position: absolute;
                top: 100%;
                /* tepat di bawah avatar */
                left: 0;
                background: white;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(0, 0, 0, 0.1);
                min-width: 200px;
                max-height: 250px;
                /* tinggi maksimal sebelum scroll */
                overflow-y: auto;
                /* scroll vertikal jika lebih */
                z-index: 1000;
                animation: dropdownSlide 0.2s ease-out;
            }

            /* optional: scrollbar lebih stylish */
            .avatar-dropdown::-webkit-scrollbar {
                width: 6px;
            }

            .avatar-dropdown::-webkit-scrollbar-thumb {
                background-color: rgba(0, 0, 0, 0.2);
                border-radius: 3px;
            }

            .avatar-dropdown::-webkit-scrollbar-track {
                background: transparent;
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
                color: var(--primary-color);
            }

            .dropdown-item:first-child {
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
            }

            .dropdown-item:last-child {
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            .dropdown-divider {
                height: 1px;
                background-color: rgba(0, 0, 0, 0.1);
                margin: 5px 0;
            }

            .dropdown-item i {
                width: 16px;
                text-align: center;
                font-size: 14px;
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

            .profile-bio.editing {
                background-color: #f8f9fa;
                border: 1px solid var(--border-color);
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

            /* Post Composer */
            .post-composer {
                background: white;
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                margin-bottom: 20px;
                padding: 20px;
                border: 1px solid var(--border-color);
                transition: all 0.3s ease;
            }

            .post-composer:focus-within {
                box-shadow: 0 8px 25px rgba(29, 161, 242, 0.15);
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
                background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
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
                color: #1a1a1a;
            }

            .composer-input::placeholder {
                color: #666;
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
                color: #666;
                margin-right: 15px;
            }

            .char-count.warning {
                color: #ff6b35;
            }

            .char-count.danger {
                color: #e74c3c;
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

            .post-card-own {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
                cursor: pointer;
                border: 1px solid var(--border-color);
                position: relative;
            }

            .post-card-own:hover {
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
            }

            .post-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                cursor: pointer;
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

            .post-card-own:hover .post-overlay {
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
            }

            .post-meta {
                font-size: 12px;
                color: #666;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .post-actions {
                position: absolute;
                top: 10px;
                right: 10px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .post-card-own:hover .post-actions {
                opacity: 1;
            }

            .post-action-btn {
                background: rgba(0, 0, 0, 0.7);
                color: white;
                border: none;
                border-radius: 50%;
                width: 32px;
                height: 32px;
                cursor: pointer;
                margin-left: 5px;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .post-action-btn:hover {
                background: var(--primary-color);
                transform: scale(1.1);
            }

            .post-action-btn.delete:hover {
                background: #e74c3c;
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

            .modal-content.large {
                max-width: 800px;
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

            .hidden {
                display: none;
            }

            /* Recent Posts Stream */
            .posts-stream {
                margin-top: 20px;
            }

            .stream-post {
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 15px;
                transition: all 0.3s ease;
                position: relative;
            }

            .stream-post:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            }

            .stream-post-header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 12px;
            }

            .stream-post-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 14px;
            }

            .stream-post-info h4 {
                margin: 0;
                font-size: 16px;
                font-weight: 700;
            }

            .stream-post-info small {
                color: #666;
            }

            .stream-post-content p {
                margin: 0;
                line-height: 1.5;
                font-size: 15px;
                margin-bottom: 12px;
            }

            .stream-post-content img {
                width: 100%;
                border-radius: 8px;
                margin-top: 10px;
                max-height: 300px;
                object-fit: cover;
                cursor: pointer;
            }

            /* Post Interactions */
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

            .stream-post-actions {
                position: absolute;
                top: 15px;
                right: 15px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .stream-post:hover .stream-post-actions {
                opacity: 1;
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
                background: var(--primary-color);
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

                .post-composer {
                    margin: 10px;
                }

                .composer-input {
                    font-size: 16px;
                }

                .post-interactions {
                    gap: 10px;
                }

                .interaction-btn {
                    font-size: 12px;
                    padding: 6px 8px;
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
                <!-- Post Composer -->


                <div class="profile-container">
                    <!-- Cover Section -->
                    <div class="cover-section" onclick="editCover()">
                        <div class="cover-edit-overlay">
                            <i class="fas fa-camera me-2"></i> Edit Cover
                        </div>
                    </div>

                    <!-- Profile Header -->
                    <!-- Updated Avatar Section with Dropdown -->
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
                                <div class="dropdown-item" onclick="viewAvatarFullSize()"> <i class="fas fa-expand"></i> View
                                    Full Size </div>
                            </div>
                        </div>

                        <div class="profile-info">
                            <h1 class="profile-name">{{ $user->name }}</h1>
                            <p class="profile-username">{{ '@' . $user->username }}</p>
                            <p class="profile-bio">
                                {!! nl2br(e($user->bio ?? 'Belum ada bio.')) !!}
                            </p>
                        </div>
                    </div>



                    <!-- Stats Section -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number" id="postsCount">{{ $posts->count() }}</div>
                            <div class="stat-label">Posts</div>
                        </div>
                        <div class="stat-item" onclick="showFollowers()">
                            <div class="stat-number" id="followersCount">{{ $followersCount }}</div>
                            <div class="stat-label">Followers</div>
                        </div>
                        <div class="stat-item" onclick="showFollowing()">
                            <div class="stat-number" id="followingCount">{{ $followingCount }}</div>
                            <div class="stat-label">Following</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="likesCount">{{ $likesCount }}</div>
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
                    {{-- Posts Tab --}}
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

                    {{-- Media Tab --}}
                    <div id="media-content" class="hidden">
                        @if($posts->where('image', '!=', null)->isNotEmpty())
                            <div class="post-grid" id="mediaGrid">
                                @foreach($posts->where('image', '!=', null) as $post)
                                    <div class="card shadow-sm rounded mb-3">
                                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Media"
                                            onclick="openImageModal(this.src)">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 400px;">
                                <div class="text-center">
                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada media. Upload foto pertama Anda!</p>
                                </div>
                            </div>
                        @endif
                    </div>


                    {{-- Likes Tab --}}
                    {{-- <div id="likes-content" class="hidden">
                        <div class="text-center py-5">
                            <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Posts yang Anda sukai akan muncul di sini</p>
                        </div>
                    </div> --}}
                    <div id="likes-content" class="hidden">
                        @forelse($likedPosts as $post)
                            <div class="card shadow-sm rounded mb-3">

                                {{-- kalau ada gambar --}}
                                @if($post->image)
                                    <a href="{{ route('profile', ['post_id' => $post->id]) }}">
                                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Media">
                                    </a>
                                @endif

                                <div class="card-body">
                                    <a href="{{ route('profile', ['user_id' => $post->user->id]) }}" class="fw-bold">
                                        {{ $post->user->name }}
                                    </a>
                                    <p>{{ Str::limit($post->caption, 150) }}</p>
                                    <small>{{ $post->likes_count }} Likes</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada postingan yang disukai.</p>
                        @endforelse
                    </div>



                    {{-- Detail Postingan (muncul kalau ada $activePost) --}}
                    @if($activePost)
                        <div class="modal show" style="display:block; background:rgba(0,0,0,0.5)">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content p-3">
                                    <button type="button" onclick="window.location='{{ route('profile') }}'"
                                        class="btn-close ms-auto"></button>

                                    {{-- tampilkan gambar hanya kalau ada --}}
                                    @if($activePost->image)
                                        <img src="{{ asset('storage/' . $activePost->image) }}" class="img-fluid mb-3">
                                    @endif

                                    <h5>
                                        <a href="{{ route('profile', ['user_id' => $activePost->user->id]) }}">
                                            {{ $activePost->user->name }}
                                        </a>
                                    </h5>

                                    {{-- konten teks selalu tampil --}}
                                    <p>{{ $activePost->content }}</p>

                                    <hr>
                                    <h6>Komentar</h6>
                                    @forelse($activePost->comments as $comment)
                                        <div>
                                            <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                                        </div>
                                    @empty
                                        <p class="text-muted">Belum ada komentar.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif




                    {{-- Drafts Tab --}}
                    <div id="drafts-content" class="hidden">
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Draft posts akan muncul di sini</p>
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

                showNotification('📝 Post berhasil dipublikasikan!');

                // Clear composer
                postContent.value = '';
                postContent.style.height = 'auto';
                removeImage();
                charCount.textContent = '0/280';
                charCount.classList.remove('warning', 'danger');
                postBtn.classList.remove('active');
            }

            // Like functionality - FIXED VERSION
            async function toggleLike(postId, button) {
                const icon = document.getElementById(`like-icon-${postId}`);
                const count = document.getElementById(`like-count-${postId}`);
                let currentCount = parseInt(count.textContent);

                // Get CSRF token
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const liked = button.classList.contains("liked");

                // Disable button temporarily to prevent double clicks
                button.disabled = true;

                try {
                    let response = await fetch(
                        liked ? `/like/destroy/${postId}` : `/like/store/${postId}`,
                        {
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
                    // Re-enable button
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

                // Add keyframes if not exists
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

                // Position relative to button
                const rect = button.getBoundingClientRect();
                heart.style.left = (rect.left + rect.width / 2 - 10) + "px";
                heart.style.top = (rect.top - 10) + "px";

                document.body.appendChild(heart);
                setTimeout(() => heart.remove(), 1000);
            }

            // Share functionality
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

            // Image modal functionality
            function openImageModal(imageSrc) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');

                modalImage.src = imageSrc;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';

                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.style.opacity = '0';
                document.body.style.overflow = 'auto';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }

            // Comments functionality


            // 🔹 buka modal komentar
          async function openComments(postId) {
                try {
                    const response = await fetch(`/comment/index/${postId}`);
                    const result = await response.json();
                    console.log("Comments API result:", result);

                    const comments = result.data ?? []; // fallback array kosong
                    let commentsHtml = '<div class="comments-section">';

                    if (comments.length > 0) {
                        comments.forEach(comment => {
                            // cek apakah user punya avatar
                            let avatar;
                            if (comment.user?.avatar) {
                                // kalau avatar di DB simpan path (misal: avatars/budi.jpg)
                                avatar = `<img src="/storage/${comment.user.avatar}" alt="Avatar" class="comment-avatar-img">`;
                            } else {
                                // fallback inisial nama
                                const inisial = comment.user?.name?.substring(0, 2).toUpperCase() || "??";
                                avatar = `<div class="comment-avatar-fallback">${inisial}</div>`;
                            }

                            commentsHtml += `
                        <div class="comment-item">
                            <div class="comment-header">
                                ${avatar}
                                <span class="comment-username">${comment.user?.name || "Anon"}</span>
                                <span class="comment-time">${new Date(comment.created_at).toLocaleString()}</span>
                            </div>
                            <div class="comment-text">${comment.content}</div>
                        </div>
                    `;
                        });
                    } else {
                        commentsHtml += `<p>Belum ada komentar. Jadilah yang pertama!</p>`;
                    }

                    // avatar user login (ambil dari window.appData)
                    let loginAvatar;
                    if (window.appData?.user?.avatar) {
                        loginAvatar = `<img src="/storage/${window.appData.user.avatar}" alt="Avatar" class="comment-avatar-img">`;
                    } else {
                        const inisial = window.appData?.user?.name?.substring(0, 2).toUpperCase() || "??";
                        loginAvatar = `<div class="comment-avatar-fallback">${inisial}</div>`;
                    }

                    commentsHtml += `
                </div>
                <div class="comment-form">
                    <div class="comment-input-group">
                        ${loginAvatar}
                        <textarea class="comment-input" id="commentInput-${postId}" placeholder="Tulis komentar..." rows="1"></textarea>
                        <button class="comment-submit" onclick="submitComment(${postId})">Post</button>
                    </div>
                </div>
            `;

                    openModal(`Comments (${comments.length})`, commentsHtml);
                } catch (error) {
                    console.error("❌ Error fetching comments:", error);
                }
            }


            // 🔹 kirim komentar baru
            async function submitComment(postId) {
                const commentInput = document.getElementById(`commentInput-${postId}`);
                const text = commentInput.value.trim();

                if (!text) {
                    alert("Komentar tidak boleh kosong!");
                    return;
                }

                try {
                    const response = await fetch('/comment/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            content: text
                        })
                    });

                    const result = await response.json();

                    if (result.status === 200) {
                        showNotification('💬 Comment posted!');
                        openComments(postId); // refresh modal
                    } else {
                        alert("Gagal mengirim komentar!");
                    }

                    commentInput.value = '';

                } catch (error) {
                    console.error('❌ Error submitting comment:', error);
                    alert("Terjadi kesalahan saat mengirim komentar.");
                }
            }   // << ini juga harus ada




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
            // Profile editing functions - UPDATED VERSION
            function editProfile() {
                // fallback pakai Blade variabel kalau elemen tidak ada
                const currentName = document.getElementById('profileName')
                    ? document.getElementById('profileName').textContent
                    : '{{ $user->name }}';

                const currentUsername = '{{ $user->username }}';

                const currentBio = document.getElementById('profileBio')
                    ? document.getElementById('profileBio').innerHTML
                        .replace(/<br>/g, '\n')
                        .replace(/<small.*?<\/small>/g, '')
                        .trim()
                    : '{{ $user->bio ?? "" }}';

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
                            // update nama
                            const profileNameEl = document.getElementById('profileName');
                            if (profileNameEl) profileNameEl.textContent = formData.get('name');

                            // update bio
                            const profileBioEl = document.getElementById('profileBio');
                            if (profileBioEl) profileBioEl.innerHTML =
                                formData.get('bio').replace(/\n/g, '<br>') +
                                '<small class="text-muted d-block mt-2"><i class="fas fa-edit"></i> Klik untuk edit bio</small>';

                            // update avatar text (2 huruf)
                            const avatarElements = document.querySelectorAll('#avatarText, .composer-avatar');
                            avatarElements.forEach(el => {
                                if (el.textContent.length <= 2) {
                                    el.textContent = formData.get('name').substring(0, 2).toUpperCase();
                                }
                            });

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

                // Show loading state
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
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            }


            // Avatar Upload Functions
            function editAvatar() {
                const content = `
                <form onsubmit="updateAvatar(event)" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Avatar</label>
                        <input type="file" class="form-control" name="avatar" accept="image/*" required onchange="previewAvatarImage(event)">
                        <small class="text-muted">Max size: 2MB. Supported formats: JPG, PNG, GIF</small>
                    </div>
                    <div class="mb-3">
                        <div id="avatarPreview" class="text-center" style="display: none;">
                            <img id="avatarPreviewImg" src="" alt="Avatar Preview" style="width: 150px; height: 150px; object-fit: cover; border-radius: 20px; border: 3px solid #ddd;">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-upload"></i> Upload Avatar
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    </div>
                </form>
            `;
                openModal('Change Avatar', content);
            }

            function previewAvatarImage(event) {
                const file = event.target.files[0];
                if (file) {
                    // Check file size (2MB = 2 * 1024 * 1024 bytes)
                    if (file.size > 2 * 1024 * 1024) {
                        showNotification('❌ File terlalu besar! Max 2MB.');
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('avatarPreviewImg').src = e.target.result;
                        document.getElementById('avatarPreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }

            function updateAvatar(event) {
                event.preventDefault();
                const formData = new FormData(event.target);

                // Show loading state
                const submitBtn = event.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                submitBtn.disabled = true;

                // Get CSRF token
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/profile/update-avatar', {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update avatar display
                            const avatarImg = `<img src="${data.avatar_url}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">`;
                            document.querySelector('.avatar').innerHTML = avatarImg + '<div class="avatar-edit-overlay"><i class="fas fa-camera"></i></div>';

                            // Update composer avatar if exists
                            const composerAvatar = document.getElementById('composerAvatar');
                            if (composerAvatar) {
                                composerAvatar.innerHTML = `<img src="${data.avatar_url}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                            }

                            // Update all stream post avatars for current user
                            document.querySelectorAll('.stream-post-avatar').forEach(avatar => {
                                if (avatar.textContent.length <= 2) { // If showing initials
                                    avatar.innerHTML = `<img src="${data.avatar_url}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                                }
                            });

                            closeModal();
                            showNotification('✅ Avatar berhasil diperbarui!');
                        } else {
                            showNotification('❌ ' + (data.message || 'Gagal update avatar!'));
                        }
                    })
                    .catch(error => {
                        console.error('Avatar update error:', error);
                        showNotification('⚠️ Terjadi error saat upload avatar!');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            }

            // Cover Upload Functions
            function editCover() {
                const content = `
                <form onsubmit="updateCover(event)" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Cover Image</label>
                        <input type="file" class="form-control" name="cover" accept="image/*" required onchange="previewCoverImage(event)">
                        <small class="text-muted">Max size: 5MB. Recommended size: 900x200px. Supported formats: JPG, PNG, GIF</small>
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
                    // Check file size (5MB = 5 * 1024 * 1024 bytes)
                    if (file.size > 5 * 1024 * 1024) {
                        showNotification('❌ File terlalu besar! Max 5MB.');
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('coverPreviewImg').src = e.target.result;
                        document.getElementById('coverPreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }

            function updateCover(event) {
                event.preventDefault();
                const formData = new FormData(event.target);

                // Show loading state
                const submitBtn = event.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                submitBtn.disabled = true;

                // Get CSRF token
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/profile/update-cover', {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update cover display
                            const coverSection = document.querySelector('.cover-section');
                            coverSection.style.background = `url('${data.cover_url}') center/cover`;
                            coverSection.style.backgroundSize = 'cover';
                            coverSection.style.backgroundPosition = 'center';

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


            function showFollowers() {
                const followers = window.appData.followers;
                const followingIds = window.appData.followingIds;

                if (!followers.length) {
                    openModal('Followers', '<p class="text-center text-muted">Belum ada followers.</p>');
                    return;
                }

                let content = `<div class="text-center">
                <h5>Followers (${followers.length})</h5>
                <div class="mt-3">`;

                followers.forEach(f => {
                    const user = f.follower;
                    const avatar = user.avatar
                        ? `<img src="/storage/${user.avatar}" class="rounded-circle" style="width:50px; height:50px; object-fit:cover;">`
                        : `<div style="width:50px; height:50px; background:linear-gradient(135deg,#ff6b6b,#4ecdc4); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold;">
                        ${user.name.charAt(0).toUpperCase()}
                        </div>`;

                    // cek apakah user login follow balik si follower
                    const isFollowing = followingIds.includes(user.id);

                    content += `
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                        ${avatar}
                        <div class="flex-grow-1 text-start">
                            <strong>${user.name}</strong><br>
                            <span class="text-muted">@${user.username}</span>
                        </div>
                        <button class="btn btn-sm ${isFollowing ? 'btn-outline-primary' : 'btn-primary'}">
                            ${isFollowing ? 'Following' : 'Follow'}
                        </button>
                    </div>
                `;
                });

                content += `</div></div>`;

                openModal('My Followers', content);
            }


            // Other functions
            // function showFollowing() {
            //     openModal('Following (345)', '<p class="text-center text-muted">People you follow will be displayed here</p>');
            // }

            function showFollowing() {
                const following = window.appData.following;

                if (!following.length) {
                    openModal('Following', '<p class="text-center text-muted">Belum mengikuti siapa pun.</p>');
                    return;
                }

                let content = `<div class="text-center">
                <h5>Following (${following.length})</h5>
                <div class="mt-3">`;

                following.forEach(f => {
                    const user = f.following; // ambil relasi "following"
                    if (!user) return; // jaga-jaga kalau null

                    const avatar = user.avatar
                        ? `<img src="/storage/${user.avatar}" class="rounded-circle" style="width:50px; height:50px; object-fit:cover;">`
                        : `<div style="width:50px; height:50px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold;">
                        ${user.name.charAt(0).toUpperCase()}
                        </div>`;

                    content += `
                        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                            ${avatar}
                            <div class="flex-grow-1 text-start">
                                <strong>${user.name}</strong><br>
                                <span class="text-muted">@${user.username}</span>
                            </div>
                            <button class="btn btn-sm btn-outline-danger">Unfollow</button>
                        </div>
                    `;
                });

                content += `</div></div>`;

                openModal('Following', content);
            }

            function shareProfile() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $user->name }} - Telava Profile',
                        text: 'Check out {{ $user->name }} on Telava!',
                        url: window.location.href
                    });
                } else {
                    navigator.clipboard.writeText(window.location.href);
                    showNotification('🔗 Profile link copied to clipboard!');
                }
            }

            function viewAnalytics() {
                const content = `
                <div class="text-center">
                    <h5>Profile Analytics</h5>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary">Profile Views</h6>
                                    <h4>234</h4>
                                    <small class="text-success">+12% from last week</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary">Post Impressions</h6>
                                    <h4>1.2K</h4>
                                    <small class="text-success">+8% from last week</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary">Engagement Rate</h6>
                                    <h4>4.2%</h4>
                                    <small class="text-success">+2% from last week</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary">New Followers</h6>
                                    <h4>45</h4>
                                    <small class="text-success">+18 this week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                openModal('Analytics Dashboard', content);
            }

            // Modal functionality
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
                const imageModal = document.getElementById('imageModal');

                if (event.target === modal) {
                    closeModal();
                }
                if (event.target === imageModal) {
                    closeImageModal();
                }
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeModal();
                    closeImageModal();
                }
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    if (postBtn.classList.contains('active')) {
                        createPost();
                    }
                }
            });

            // Initialize page
            document.addEventListener('DOMContentLoaded', function () {
                // Add entrance animations
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

            function toggleAvatarMenu(event) {
                event.stopPropagation();
                const dropdown = document.getElementById('avatarDropdown');

                if (dropdown.style.display === 'none' || !dropdown.style.display) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            }

            function closeAllDropdowns() {
                const dropdowns = document.querySelectorAll('.avatar-dropdown');
                dropdowns.forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            }

            function viewProfile() {
                closeAllDropdowns();
                // Scroll to top to show full profile
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                showNotification('📋 Viewing your profile');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.avatar-container')) {
                    closeAllDropdowns();
                }
            });

            // Close dropdown on ESC key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAllDropdowns();
                }
            });


            // Photo Viewer Functions
            function viewAvatarFullSize() {
                const avatar = document.querySelector('.avatar img');
                if (avatar) {
                    openImageModal(avatar.src);
                } else {
                    showNotification('Belum ada avatar untuk ditampilkan');
                }
            }

            function viewCoverFullSize() {
                const coverSection = document.querySelector('.cover-section');
                const backgroundImage = coverSection.style.backgroundImage;

                if (backgroundImage && backgroundImage !== 'none') {
                    // Extract URL from background-image CSS
                    const imageUrl = backgroundImage.slice(5, -2); // Remove 'url("' and '")'
                    openImageModal(imageUrl);
                } else {
                    showNotification('Belum ada cover image untuk ditampilkan');
                }
            }

            // Enhanced openImageModal function with better controls
            function openImageModal(imageSrc) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');

                // Create enhanced modal if doesn't exist
                if (!modal) {
                    createEnhancedImageModal();
                }

                modalImage.src = imageSrc;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';

                // Add loading state
                modalImage.style.opacity = '0';
                modalImage.onload = function () {
                    modalImage.style.transition = 'opacity 0.3s ease';
                    modalImage.style.opacity = '1';
                };

                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            }

            function createEnhancedImageModal() {
                const modalHTML = `
                <div id="imageModal" class="image-modal">
                    <button class="image-modal-close" onclick="closeImageModal()">&times;</button>
                    <div class="image-modal-content">
                        <img id="modalImage" src="" alt="Full size image">
                        <div class="image-modal-controls">
                            <button class="modal-control-btn" onclick="downloadImage()" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="modal-control-btn" onclick="shareImage()" title="Share">
                                <i class="fas fa-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }

            function downloadImage() {
                const modalImage = document.getElementById('modalImage');
                const link = document.createElement('a');
                link.href = modalImage.src;
                link.download = 'profile-image.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                showNotification('Image downloaded!');
            }

            function shareImage() {
                const modalImage = document.getElementById('modalImage');

                if (navigator.share) {
                    fetch(modalImage.src)
                        .then(res => res.blob())
                        .then(blob => {
                            const file = new File([blob], 'profile-image.jpg', { type: blob.type });
                            navigator.share({
                                files: [file],
                                title: 'Profile Image',
                                text: 'Check out this profile image!'
                            });
                        });
                } else {
                    navigator.clipboard.writeText(modalImage.src);
                    showNotification('Image URL copied to clipboard!');
                }
            }

            // Update avatar dropdown with view option
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

            // Add these styles for enhanced modal
            const enhancedModalStyles = `
            <style>
            .image-modal {
                display: none;
                position: fixed;
                z-index: 3000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.95);
                backdrop-filter: blur(5px);
                opacity: 0;
                transition: opacity 0.3s ease;
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
                max-height: 80vh;
                border-radius: 8px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                object-fit: contain;
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

            .image-modal-controls {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
            }

            .modal-control-btn {
                background: rgba(0, 0, 0, 0.7);
                color: white;
                border: none;
                border-radius: 50%;
                width: 45px;
                height: 45px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                font-size: 16px;
            }

            .modal-control-btn:hover {
                background: rgba(29, 161, 242, 0.8);
                transform: scale(1.1);
            }

            @media (max-width: 768px) {
                .image-modal-content img {
                    max-height: 70vh;
                }

                .image-modal-close {
                    width: 40px;
                    height: 40px;
                    font-size: 20px;
                    top: 15px;
                    right: 15px;
                }

                .modal-control-btn {
                    width: 40px;
                    height: 40px;
                    font-size: 14px;
                }
            }
            </style>
            `;

            // Inject styles
            document.head.insertAdjacentHTML('beforeend', enhancedModalStyles);

        </script>

@endsection