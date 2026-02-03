@extends('layout.layarUtama')

@section('title', 'Homepage - Telava')

@section('content')
<style>
    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
        margin-left: 0;
        /* background-color: #fff; */
    }

    .like-btn.liked i {
        color: red;
    }

    .upload-preview video {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .media-type-indicator {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    /* NEW: Download Button Style */
    .media-download-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
        backdrop-filter: blur(5px);
    }

    .media-download-btn:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.1);
    }

    .media-download-btn i {
        font-size: 16px;
    }

    /* Dropdown Menu for Download */
    .media-dropdown {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }

    .media-dropdown .dropdown-toggle {
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .media-dropdown .dropdown-toggle:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .media-dropdown .dropdown-toggle::after {
        display: none;
    }

    .media-dropdown .dropdown-menu {
        min-width: 150px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Stories/Highlights Section */
    .highlights-section {
        background: white;
        border: 1px solid #dbdbdb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .highlights-container {
        gap: 16px;
        padding: 4px 0;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .highlights-container::-webkit-scrollbar {
        display: none;
    }

    .highlight-item {
        cursor: pointer;
        flex-shrink: 0;
        width: 80px;
    }

    .highlight-item:hover .highlight-ring {
        transform: scale(1.05);
    }

    .highlight-ring {
        position: relative;
        width: 66px;
        height: 66px;
        margin: 0 auto 8px;
        padding: 2px;
        border-radius: 50%;
        transition: transform 0.2s ease;
    }

    .highlight-ring.has-story {
        background: #0095f6;
    }

    .highlight-ring.no-story {
        background: transparent;
        border: 2px dashed #dbdbdb;
    }

    .highlight-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        padding: 2px;
    }

    .highlight-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #0095f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 20px;
    }

    .add-story-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        background: #0095f6;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-story-btn i {
        font-size: 10px;
        color: white;
    }

    .highlight-name {
        font-size: 12px;
        color: #262626;
        max-width: 74px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .highlights-section {
            border-radius: 0;
            border-left: none;
            border-right: none;
            padding: 12px 16px;
        }
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

    .post-composer {
        border-bottom: 1px solid #e6ecf0;
        padding: 12px;
    }

    .composer-textarea {
        width: 100%;
        border: none;
        resize: none;
        font-size: 16px;
        outline: none;
    }

    .media-preview {
        margin-top: 10px;
    }

    .media-preview.hidden {
        display: none;
    }

    /* GRID */
    .media-grid {
        display: grid;
        gap: 4px;
        border-radius: 12px;
        overflow: hidden;
    }

    .media-grid[data-count="1"] {
        grid-template-columns: 1fr;
    }

    .media-grid[data-count="2"] {
        grid-template-columns: repeat(2, 1fr);
    }

    .media-grid[data-count="3"] {
        grid-template-columns: 2fr 1fr;
        grid-template-rows: 1fr 1fr;
        height: 300px;
    }

    .media-grid[data-count="3"]>.media-item:first-child {
        grid-row: 1 / 3;
        grid-column: 1;
    }

    .media-grid[data-count="3"]>.media-item:nth-child(2) {
        grid-row: 1;
        grid-column: 2;
    }

    .media-grid[data-count="3"]>.media-item:nth-child(3) {
        grid-row: 2;
        grid-column: 2;
    }

    .media-grid[data-count="4"] {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        height: 300px;
    }

    .media-grid>.media-item {
        position: relative;
        aspect-ratio: auto;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
        height: 100%;
    }

    .media-item img,
    .media-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 8px;
        max-height: 100%;
        display: block;
        transition: transform 0.3s ease;
    }

    /* ✅ HOVER - OVERLAY DARK */
    .media-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
        pointer-events: none;
    }

    .media-item:hover::before {
        opacity: 1;
    }

    /* ✅ HOVER - ICON EXPAND */
    .media-item::after {
        /* content: '⤢'; */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        font-size: 32px;
        color: white;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 2;
        pointer-events: none;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    }

    .media-item:hover::after {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    /* ✅ HOVER - ZOOM IMAGE */
    .media-item:hover img,
    .media-item:hover video {
        transform: scale(1.05);
    }

    .post-video-container {
        position: relative;
        margin-bottom: 15px;
    }

    .media-remove {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(0, 0, 0, .6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        cursor: pointer;
    }

    /* TOOLBAR */
    .composer-toolbar {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .toolbar-left button {
        background: none;
        border: none;
        color: #1d9bf0;
        font-size: 18px;
        cursor: pointer;
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

    /* Feed Posts */
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
        min-height: 48px;
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

    /* POST MEDIA GRID - UPDATED FOR TWITTER/X STYLE */
    .post-media {
        display: grid;
        gap: 4px;
        border-radius: 16px;
        overflow: hidden;
    }

    .post-media[data-count="1"] {
        grid-template-columns: 1fr;
    }

    .post-media[data-count="1"]>* {
        max-height: 500px;
    }

    .post-media[data-count="2"] {
        grid-template-columns: repeat(2, 1fr);
        max-height: 400px;
    }

    /* 3 items: Large left, 2 stacked right (Twitter/X style) */
    .post-media[data-count="3"] {
        grid-template-columns: 2fr 1fr;
        grid-template-rows: 1fr 1fr;
        height: 400px;
    }

    .post-media[data-count="3"]>.media-item:first-child {
        grid-row: 1 / 3;
        grid-column: 1;
        height: 100%;
    }

    .post-media[data-count="3"]>.media-item:nth-child(2) {
        grid-row: 1;
        grid-column: 2;
        height: 100%;
    }

    .post-media[data-count="3"]>.media-item:nth-child(3) {
        grid-row: 2;
        grid-column: 2;
        height: 100%;
    }

    /* 4 items: 2x2 grid */
    .post-media[data-count="4"] {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        height: 400px;
    }

    .post-media>.media-item {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        width: 100%;
    }

    .post-media img,
    .post-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 0.3s ease;
    }

    /* ✅ HOVER - OVERLAY DARK FOR POST MEDIA */
    .post-media .media-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
        pointer-events: none;
    }

    .post-media .media-item:hover::before {
        opacity: 1;
    }

    /* ✅ HOVER - ICON EXPAND FOR POST MEDIA */
    .post-media .media-item::after {
        /* content: '⤢'; */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        font-size: 32px;
        color: white;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 2;
        pointer-events: none;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    }

    .post-media .media-item:hover::after {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    /* ✅ HOVER - ZOOM IMAGE FOR POST MEDIA */
    .post-media .media-item:hover img,
    .post-media .media-item:hover video {
        transform: scale(1.05);
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

    /* Sidebar Widgets */
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

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        animation: fadeIn 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .image-modal-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px 20px;
    }

    .image-modal-content img {
        max-width: 95%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 60px rgba(0, 0, 0, 0.8);
        animation: zoomIn 0.3s ease;
    }

    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s ease;
        background: rgba(0, 0, 0, 0.6);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
        line-height: 1;
    }

    .image-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
        border-color: rgba(255, 255, 255, 0.6);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Comment Dropdown Styles */
    .dropdown-menu {
        min-width: 120px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
    }

    .dropdown-item {
        padding: 8px 16px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
        color: #dc3545 !important;
    }

    .dropdown-toggle::after {
        display: none;
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

        .image-modal-close {
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            font-size: 30px;
        }

        .image-modal-content {
            padding: 50px 10px 10px;
        }

        .image-modal-content img {
            max-width: 100%;
            max-height: 85vh;
        }

        .post-media[data-count="3"],
        .post-media[data-count="4"] {
            height: 300px;
        }
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
            margin-left: -10%;
        }
    }

    .trending-sidebar {
        border: 1px solid #eee;
    }

    .suggest-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .suggest-item:hover {
        background: #f8f9fa;
        padding: 6px;
        border-radius: 10px;
        transition: .2s;
    }

    .suggest-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Follow Button in Post */
    .follow-btn-in-post {
        background: linear-gradient(135deg, #1da1f2, #0d8bd9);
        border: none;
        box-shadow: 0 2px 8px rgba(29, 161, 242, 0.3);
        font-size: 12px;
        font-weight: 600;
    }

    .follow-btn-in-post:hover {
        background: linear-gradient(135deg, #0d8bd9, #0a78c2);
        box-shadow: 0 4px 12px rgba(29, 161, 242, 0.5);
        transform: scale(1.05);
    }

    .follow-btn-in-post.btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .highlights-container {
        gap: 14px;
        overflow: visible !important;
    }

    /* ITEM */
    .highlight-item {
        width: 80px;
        position: relative;
        cursor: pointer;
    }

    /* RING */
    .highlight-ring {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        padding: 2px;
    }

    .has-story {
        background: linear-gradient(45deg, #ff00cc, #3333ff);
    }

    .no-story {
        background: #ddd;
    }

    /* AVATAR */
    .highlight-avatar {
        width: 100%;
        height: 100%;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .highlight-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* PLACEHOLDER */
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: #888;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* ADD BUTTON */
    .add-story-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 20px;
        height: 20px;
        background: #0095f6;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* NAME */
    .highlight-name {
        font-size: 12px;
        margin-top: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* MENU */
    .story-menu {
        position: absolute;
        top: 110%;
        left: 50%;
        transform: translateX(-50%);
        width: 160px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .2);
        display: none;
        z-index: 9999;
        overflow: hidden;
    }

    .story-menu div {
        padding: 10px;
        font-size: 14px;
    }

    .story-menu div:hover {
        background: #f1f1f1;
    }
    
</style>

<div class="row">
    <div class="col-lg-3 d-none d-lg-block"></div>
    <div class="col-lg-6 col-12">
        <div class="feed-container">
            <!-- Highlights Section -->
            <div class="highlights-section">

                <div class="highlights-header d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0" style="font-size:16px;font-weight:600;color:#262626;">
                        Stories
                    </h6>
                </div>

                <div class="highlights-container d-flex">

                    {{-- ================= YOUR STORY ================= --}}
                    @php
                    $authUser = $usersWithStories->firstWhere('id', Auth::id());
                    $hasUserStory = $authUser && $authUser->stories->isNotEmpty();
                    @endphp

                    <div class="highlight-item text-center"
                        onclick="handleYourStoryClick(event, {{ $hasUserStory ? 'true' : 'false' }})">

                        <div class="highlight-ring {{ $hasUserStory ? 'has-story' : 'no-story' }}">
                            <div class="highlight-avatar">
                                @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/'.Auth::user()->avatar) }}">
                                @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr(Auth::user()->name,0,2)) }}
                                </div>
                                @endif
                            </div>

                            @if(!$hasUserStory)
                            <div class="add-story-btn">
                                <i class="fas fa-plus"></i>
                            </div>
                            @endif
                        </div>

                        <div class="highlight-name">Your Story</div>

                        {{-- MENU --}}
                        @if($hasUserStory)
                        <div class="story-menu" id="yourStoryMenu">
                            <div onclick="openStory('{{ Auth::user()->username }}')">
                                👁 Lihat Story
                            </div>

                            <div onclick="openCreateStory()">
                                ➕ Buat Story Lagi
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- ================= OTHER USERS ================= --}}
                    @foreach($usersWithStories->where('id','!=',Auth::id()) as $user)

                    <div class="highlight-item text-center"
                        onclick="openStory('{{ $user->username }}')">

                        <div class="highlight-ring has-story">
                            <div class="highlight-avatar">
                                @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}">
                                @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($user->name,0,2)) }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="highlight-name">
                            {{ Str::limit($user->name,10) }}
                        </div>

                    </div>

                    @endforeach

                </div>
            </div>


            <!-- Post Composer -->
            <form id="postForm"
                action="{{ route('posts.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="post-composer">
                    <textarea
                        class="composer-textarea"
                        id="postContent"
                        name="caption"
                        placeholder="Apa yang sedang terjadi?"
                        rows="1"
                        oninput="updateCharCount()"></textarea>

                    <div class="text-end small text-muted">
                        <span id="charCount">0/280</span>
                    </div>

                    <div class="media-preview hidden" id="uploadPreview"></div>

                    <input
                        type="file"
                        id="mediaUpload"
                        name="media[]"
                        accept="image/*,video/*"
                        multiple
                        hidden
                        onchange="handleMediaUpload(event)">

                    <div class="composer-toolbar">
                        <div class="toolbar-left">
                            <button
                                type="button"
                                onclick="triggerMediaUpload()"
                                title="Media">
                                <i class="far fa-image"></i>
                            </button>
                        </div>

                        <button
                            class="post-btn"
                            id="postBtn"
                            type="submit"
                            disabled>
                            Post
                        </button>
                    </div>
                </div>
            </form>

            <!-- Feed Posts -->
            <div id="feedContainer">
                @forelse($posts as $post)
                <div class="post-card card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="post-avatar me-3">
                                <a href="{{ $post->user->id === auth()->id() ? route('profile') : url('/profilePage/' . $post->user->username) }}"
                                    class="text-decoration-none">
                                    @if($post->user->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar"
                                        class="rounded-circle" width="50" height="50">
                                    @else
                                    <div class="avatar-placeholder rounded-circle d-flex justify-content-center align-items-center"
                                        style="width: 50px; height: 50px; font-size: 18px; font-weight: 600; background: linear-gradient(135deg, #1e90ff, #007bff); color: white; text-decoration: none; line-height: 1;">
                                        {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                    </div>
                                    @endif
                                </a>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ $post->user->id === auth()->id() ? route('profile') : url('/profilePage/' . $post->user->username) }}"
                                            class="text-decoration-none text-dark">
                                            <strong class="me-2">{{ $post->user->name }}</strong>
                                        </a>
                                        <span class="text-muted">{{ '@' . $post->user->username }}</span>
                                        <span class="text-muted ms-2">• {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    {{-- Tombol Follow untuk user yang belum di-follow --}}
                                    @php
                                    $isFollowing = auth()->user()->following()->where('user_following', $post->user->id)->exists();
                                    @endphp
                                    @if(auth()->id() !== $post->user->id && !$isFollowing)
                                    <button
                                        class="btn btn-sm btn-primary follow-btn-in-post px-3 py-1"
                                        onclick="followUserFromPost({{ $post->user->id }}, this)"
                                        style="font-size: 12px; font-weight: 600; border-radius: 20px; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                        <i class="fas fa-user-plus me-1"></i> Follow
                                    </button>
                                    @endif
                                </div>

                                <p class="mb-3">{{ $post->caption }}</p>

                                @if($post->media && $post->media->count())
                                <div class="post-media media-grid" data-count="{{ $post->media->count() }}">
                                    @foreach($post->media as $media)
                                    @php
                                    $ext = pathinfo($media->file_path, PATHINFO_EXTENSION);
                                    $mediaUrl = asset('storage/' . $media->file_path);
                                    @endphp

                                    <div class="media-item">
                                        {{-- IMAGE --}}
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp','gif']))
                                        <img
                                            src="{{ $mediaUrl }}"
                                            onclick="openImageModal('{{ $mediaUrl }}')"
                                            style="width:100%; height:100%; object-fit:cover; cursor:pointer;">

                                        {{-- Download Button for Image --}}
                                        <div class="media-dropdown">
                                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation()">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); event.stopPropagation(); downloadMedia('{{ $mediaUrl }}', 'image_{{ $media->id }}.{{ $ext }}')">
                                                        <i class="fas fa-download me-2"></i>Download Image
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- VIDEO --}}
                                        @elseif(in_array(strtolower($ext), ['mp4','mov','webm']))
                                        <div class="post-video-container" style="position:relative; height:100%;">
                                            <video
                                                class="twitter-video"
                                                muted
                                                playsinline
                                                preload="metadata"
                                                controls
                                                style="width:100%; height:100%; object-fit:cover; cursor:pointer;">
                                                <source src="{{ $mediaUrl }}">
                                            </video>

                                            <button class="mute-btn"
                                                style="position:absolute; bottom:10px; right:10px;
                                                background:rgba(0,0,0,0.5); border:none;
                                                color:white; padding:5px 8px;
                                                border-radius:50%; cursor:pointer;">
                                                🔇
                                            </button>

                                            {{-- Download Button for Video --}}
                                            <div class="media-dropdown">
                                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation()">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); event.stopPropagation(); downloadMedia('{{ $mediaUrl }}', 'video_{{ $media->id }}.{{ $ext }}')">
                                                            <i class="fas fa-download me-2"></i>Download Video
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <div class="d-flex justify-content-between mt-2">
                                    <button class="btn btn-sm btn-light" onclick="showComments({{ $post->id }})">
                                        <i class="far fa-comment"></i> {{ $post->comments_count ?? 0 }}
                                    </button>

                                    <!-- <button class="btn btn-sm btn-light">
                                        <i class="fas fa-bookmark"></i>
                                    </button> -->

                                    <button class="btn btn-sm like-btn"
                                        onclick="toggleLike(this)"
                                        data-post-id="{{ $post->id }}"
                                        data-liked="{{ $post->is_liked_by_auth_user ? 'true' : 'false' }}">
                                        <i class="fa fa-heart {{ $post->is_liked_by_auth_user ? 'text-danger' : 'text-secondary' }}"></i>
                                        <span class="like-count">{{ $post->likes_count }}</span>
                                    </button>

                                    <button class="btn btn-sm btn-light">
                                        <i class="fas fa-share"></i> {{ $post->shares_count ?? 0 }}
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
        <div class="trending-sidebar p-3 rounded-4 shadow-sm bg-white">

            <h6 class="fw-semibold mb-3">
                <i class="fas fa-user-plus text-primary me-1"></i>
                Suggested for you
            </h6>

            @forelse($suggestedUsers as $user)
            <div class="suggest-item d-flex align-items-center justify-content-between mb-3">

                <div class="d-flex align-items-center gap-3">
                    <!-- Avatar -->
                    <a href="{{ route('profilePage', $user->username) }}" class="text-decoration-none">
                        <div class="suggest-avatar">
                            @if($user->avatar)
                            <img
                                src="{{ asset('storage/'.$user->avatar) }}"
                                alt="{{ $user->name }}">
                            @else
                            {{ strtoupper(substr($user->name,0,2)) }}
                            @endif
                        </div>
                    </a>

                    <!-- Info -->
                    <div>
                        <a href="{{ route('profilePage', $user->username) }}" class="text-decoration-none">
                            <div class="fw-semibold small text-dark hover-primary">{{ $user->name }}</div>
                        </a>
                        <div class="text-muted small">{{ '@' . ($user->username ?? 'user') }}</div>
                    </div>
                </div>

                <!-- Follow Button (jika diperlukan nanti) -->
                <!-- <button
                id="follow-btn-{{ $user->id }}"
                class="btn btn-sm btn-primary rounded-pill px-3"
                onclick="followUser({{ $user->id }})">
                Follow
            </button> -->

            </div>
            @empty
            <p class="text-muted small">No suggestions</p>
            @endforelse

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
    <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
    <div class="image-modal-content">
        <img id="modalImage" src="" alt="Full size image">
    </div>
</div>

<div class="story-dropdown" id="yourStoryMenu">
    @if($hasUserStory)
    <div class="story-option" onclick="openStory('{{ Auth::user()->username }}')">
        👁 Lihat Story
    </div>
    @endif

    <div class="story-option" onclick="openCreateStoryModal()">
        ➕ Buat Story
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ============================================================
    // FOLLOW USER DARI POST
    // ============================================================
    window.followUserFromPost = async function(userId, button) {
        const originalHTML = button.innerHTML;
        const originalClass = button.className;

        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.className = 'btn btn-sm btn-secondary follow-btn-in-post px-3 py-1';

        try {
            const response = await fetch(`/follow/store/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // ✅ Update button UI
                button.innerHTML = '<i class="fas fa-check me-1"></i> Following';
                button.className = 'btn btn-sm btn-success follow-btn-in-post px-3 py-1';
                button.disabled = true;

                // ✅ Success notification
                window.showNotification('✅ Now following ' + result.user_name + '!');

                // ✅ Optional: Fade out button setelah 2 detik
                setTimeout(() => {
                    button.style.opacity = '0.6';
                    button.style.transform = 'scale(0.95)';
                }, 2000);

            } else {
                throw new Error(result.message || 'Failed to follow user');
            }

        } catch (error) {
            console.error('Follow error:', error);
            button.innerHTML = originalHTML;
            button.className = originalClass;
            button.disabled = false;

            window.showNotification('❌ ' + (error.message || 'Failed to follow user'));
        }
    };
    // ============================================================
    // GLOBAL VARIABLES
    // ============================================================
    const currentUserId = Number("{{ auth()->id() }}");

    let uploadedFiles = [];
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
    const MAX_VIDEO_SIZE = 50 * 1024 * 1024; // 50MB
    const MAX_FILES = 4;

    // ============================================================
    // DOWNLOAD MEDIA FUNCTION
    // ============================================================
    window.downloadMedia = async function(mediaUrl, filename) {
        try {
            window.showNotification('📥 Downloading...');

            const response = await fetch(mediaUrl);
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = filename;

            document.body.appendChild(a);
            a.click();

            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            window.showNotification('✅ Download complete!');
        } catch (error) {
            console.error('Download error:', error);
            window.showNotification('⚠️ Download failed. Please try again.');
        }
    };

    // ============================================================
    // IMMEDIATELY DEFINE GLOBAL FUNCTIONS
    // ============================================================

    // Story Functions
    function handleYourStoryClick(e, hasStory, username) {

        // Kalau BELUM ADA STORY → langsung ke halaman create
        if (!hasStory) {
            window.location.href = "/stories/create";
            return;
        }

        // Kalau ADA STORY → toggle menu
        e.stopPropagation();
        const menu = document.getElementById('yourStoryMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    // klik di luar nutup menu
    document.addEventListener('click', function() {
        const menu = document.getElementById('yourStoryMenu');
        if (menu) menu.style.display = 'none';
    });

    function openCreateStory() {
        window.location.href = "/stories/create";
    }


    function openStory(username) {
        window.location.href = "/stories?user=" + username;
    }


    // window.openStory = function(username) {
    //     window.location.href = `/stories?user=${username}`;
    // };

    // window.openCreateStory = function() {
    //     window.location.href = "{{ route('stories.create') }}";
    // };

    // Character Count
    window.updateCharCount = function() {
        const textarea = document.getElementById('postContent');
        const postBtn = document.getElementById('postBtn');
        const charCount = document.getElementById('charCount');

        if (!textarea || !postBtn || !charCount) return;

        const textLength = textarea.value.trim().length;
        charCount.textContent = `${textLength}/280`;

        if (textLength > 0 || uploadedFiles.length > 0) {
            postBtn.disabled = false;
            postBtn.classList.add('active');
        } else {
            postBtn.disabled = true;
            postBtn.classList.remove('active');
        }
    };

    // Media Upload Functions
    window.triggerMediaUpload = function() {
        const input = document.getElementById('mediaUpload');
        if (!input) return;
        input.click();
    };

    window.handleMediaUpload = function(event) {
        const files = Array.from(event.target.files);
        if (!files.length) return;

        let imageCount = uploadedFiles.filter(f => f.type.startsWith('image/')).length;
        let videoCount = uploadedFiles.filter(f => f.type.startsWith('video/')).length;

        for (const file of files) {
            if (uploadedFiles.length >= MAX_FILES) {
                showNotification(`⚠️ Max ${MAX_FILES} media`);
                break;
            }

            if (file.type.startsWith('image/')) {
                if (imageCount >= 4) {
                    showNotification('⚠️ Max 4 images');
                    continue;
                }
                if (file.size > MAX_IMAGE_SIZE) {
                    showNotification(`⚠️ ${file.name} > 5MB`);
                    continue;
                }
                imageCount++;
            } else if (file.type.startsWith('video/')) {
                if (videoCount >= 1) {
                    showNotification('⚠️ Only 1 video allowed');
                    continue;
                }
                if (file.size > MAX_VIDEO_SIZE) {
                    showNotification(`⚠️ ${file.name} > 50MB`);
                    continue;
                }
                videoCount++;
            }

            uploadedFiles.push(file);
        }

        renderMediaPreviews();
        updateCharCount();
        event.target.value = '';
    };

    window.renderMediaPreviews = function() {
        const wrapper = document.getElementById('uploadPreview');
        wrapper.innerHTML = '';

        if (!uploadedFiles.length) {
            wrapper.classList.add('hidden');
            return;
        }

        wrapper.classList.remove('hidden');

        const grid = document.createElement('div');
        grid.className = 'media-grid';
        grid.dataset.count = uploadedFiles.length;

        uploadedFiles.forEach((file, index) => {
            const url = URL.createObjectURL(file);

            const item = document.createElement('div');
            item.className = 'media-item';

            item.innerHTML = `
            ${file.type.startsWith('image/')
                ? `<img src="${url}">`
                : `<video src="${url}" controls muted></video>`
            }
            <button class="media-remove" onclick="removeMediaAtIndex(${index})">✕</button>
        `;

            grid.appendChild(item);
        });

        wrapper.appendChild(grid);
    };

    window.removeMediaAtIndex = function(index) {
        if (index < 0 || index >= uploadedFiles.length) return;
        uploadedFiles.splice(index, 1);
        renderMediaPreviews();
        updateCharCount();
    };

    // Like Functionality
    window.toggleLike = async function(btn) {
        const postId = btn.dataset.postId;
        const isLiked = btn.dataset.liked === "true";
        const icon = btn.querySelector("i");
        const countSpan = btn.querySelector(".like-count");
        let count = parseInt(countSpan.textContent);
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        btn.disabled = true;

        try {
            const endpoint = isLiked ? `/like/destroy/${postId}` : `/like/store/${postId}`;

            const response = await fetch(endpoint, {
                method: isLiked ? "DELETE" : "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });

            const result = await response.json();

            if (response.ok) {
                if (!isLiked) {
                    btn.dataset.liked = "true";
                    icon.classList.remove("text-secondary");
                    icon.classList.add("text-danger");
                    countSpan.textContent = count + 1;
                    window.showNotification("❤️ Post liked!");
                } else {
                    btn.dataset.liked = "false";
                    icon.classList.remove("text-danger");
                    icon.classList.add("text-secondary");
                    countSpan.textContent = Math.max(0, count - 1);
                    window.showNotification("💔 Post unliked");
                }
            } else {
                if (response.status === 409) {
                    window.showNotification(isLiked ? "⚠️ Already unliked" : "⚠️ Already liked");
                } else {
                    throw new Error(result.message || 'Failed to toggle like');
                }
            }
        } catch (error) {
            console.error("Like error:", error);
            window.showNotification("⚠️ Network error!");
        } finally {
            btn.disabled = false;
        }
    };

    // Comments Functionality
    window.showComments = function(postId) {
        const modal = document.getElementById('modal');
        const modalBody = document.getElementById('modal-body');

        modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading comments...</p>
        </div>
    `;
        window.openModal('Comments', '');

        fetch(`/posts/show/${postId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                let modalContent = `
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

                if (data.post.image) {
                    modalContent += `
                <div class="mb-3">
                    <img src="/storage/${data.post.image}" class="img-fluid rounded shadow-sm" alt="Post image" style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
            `;
                }

                if (data.post.caption) {
                    modalContent += `
                <div class="mb-3">
                    <p style="white-space: pre-line; font-size: 15px; line-height: 1.5;">${data.post.caption}</p>
                </div>
            `;
                }

                modalContent += `
            <div class="d-flex justify-content-between align-items-center mb-3 py-2 border-top border-bottom">
                <div class="d-flex gap-4">
                    <span id="likesCount-${data.post.id}"><i class="fas fa-heart text-danger"></i> ${data.post.likes_count || 0} likes</span>
                    <span id="commentsCount-${data.post.id}"><i class="fas fa-comment text-primary"></i> <span class="comment-count-number">${data.comments ? data.comments.length : 0}</span> comments</span>
                </div>
                <small class="text-muted">${new Date(data.post.created_at).toLocaleString()}</small>
            </div>
            <hr>
            <h6>Comments</h6>
            <div class="comments-list" id="commentsList-${data.post.id}" style="max-height: 300px; overflow-y: auto;">
        `;

                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const isOwnComment = comment.user.id === currentUserId;
                        modalContent += `
                    <div class="d-flex mb-3 comment-item" data-comment-id="${comment.id}">
                        ${comment.user.avatar ? 
                            `<img src="/storage/${comment.user.avatar}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">` :
                            `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                ${comment.user.name.charAt(0).toUpperCase()}
                            </div>`
                        }
                        <div class="flex-grow-1">
                            <div class="bg-light rounded p-2 position-relative">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong class="small">${comment.user.name}</strong>
                                        <p class="mb-0 small">${comment.content}</p>
                                    </div>
                                    ${isOwnComment ? `
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted p-0 ms-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); window.deleteComment(${comment.id}, ${data.post.id})">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                            <small class="text-muted ms-2">${new Date(comment.created_at).toLocaleString()}</small>
                        </div>
                    </div>
                `;
                    });
                } else {
                    modalContent += `<p class="text-muted text-center py-3" id="noCommentsMsg-${data.post.id}">No comments yet. Be the first to comment!</p>`;
                }

                modalContent += `
            </div>
            <div class="mt-3 border-top pt-3">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="commentInput-${data.post.id}" placeholder="Write a comment..." onkeypress="window.handleCommentKeyPress(event, ${data.post.id})">
                    <button class="btn btn-primary btn-sm" id="commentBtn-${data.post.id}" onclick="window.submitCommentFromModal(${data.post.id})">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div id="commentStatus-${data.post.id}" class="small mt-1" style="display: none;"></div>
            </div>
        `;

                modalBody.innerHTML = modalContent;
            })
            .catch(error => {
                console.error('Error fetching post details:', error);
                modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="mb-0">Failed to load post details. Please try again.</p>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="location.reload()">Refresh Page</button>
            </div>
        `;
            });
    };

    window.handleCommentKeyPress = function(event, postId) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            window.submitCommentFromModal(postId);
        }
    };

    window.submitCommentFromModal = function(postId) {
        const commentInput = document.getElementById(`commentInput-${postId}`);
        const commentBtn = document.getElementById(`commentBtn-${postId}`);
        const commentStatus = document.getElementById(`commentStatus-${postId}`);
        const commentsList = document.getElementById(`commentsList-${postId}`);
        const commentCountSpan = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
        const noCommentsMsg = document.getElementById(`noCommentsMsg-${postId}`);

        const commentText = commentInput.value.trim();

        if (!commentText) {
            commentStatus.textContent = 'Please write a comment';
            commentStatus.className = 'small mt-1 text-danger';
            commentStatus.style.display = 'block';
            return;
        }

        commentBtn.disabled = true;
        commentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        commentStatus.textContent = 'Posting comment...';
        commentStatus.className = 'small mt-1 text-muted';
        commentStatus.style.display = 'block';

        fetch(`/comment/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    post_id: postId,
                    content: commentText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.data) {
                    if (noCommentsMsg) noCommentsMsg.remove();

                    const newComment = data.data;
                    const commentHtml = `
                <div class="d-flex mb-3 comment-item" data-comment-id="${newComment.id}">
                    ${newComment.user.active_avatar ? 
                        `<img src="/storage/${newComment.user.active_avatar}" class="rounded-circle me-2" width="32" height="32" alt="Avatar" style="object-fit: cover;">` :
                        `<div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                            ${newComment.user.name.charAt(0).toUpperCase()}
                        </div>`
                    }
                    <div class="flex-grow-1">
                        <div class="bg-light rounded p-2 position-relative">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <strong class="small">${newComment.user.name}</strong>
                                    <p class="mb-0 small">${newComment.content}</p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0 ms-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); window.deleteComment(${newComment.id}, ${postId})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted ms-2">Just now</small>
                    </div>
                </div>
            `;

                    commentsList.insertAdjacentHTML('beforeend', commentHtml);
                    commentsList.scrollTop = commentsList.scrollHeight;

                    if (commentCountSpan) {
                        commentCountSpan.textContent = parseInt(commentCountSpan.textContent) + 1;
                    }

                    const feedCommentBtn = document.querySelector(`button[onclick="showComments(${postId})"]`);
                    if (feedCommentBtn) {
                        const currentCount = feedCommentBtn.textContent.match(/\d+/);
                        const newCount = currentCount ? parseInt(currentCount[0]) + 1 : 1;
                        feedCommentBtn.innerHTML = `<i class="far fa-comment"></i> ${newCount}`;
                    }

                    commentInput.value = '';
                    commentStatus.textContent = 'Comment posted successfully!';
                    commentStatus.className = 'small mt-1 text-success';

                    setTimeout(() => {
                        commentStatus.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                commentStatus.textContent = 'Failed to post comment. Please try again.';
                commentStatus.className = 'small mt-1 text-danger';
            })
            .finally(() => {
                commentBtn.disabled = false;
                commentBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            });
    };

    window.deleteComment = async function(commentId, postId) {
        // ✅ SweetAlert konfirmasi
        const result = await Swal.fire({
            title: 'Hapus Comment?',
            text: "Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger px-4',
                cancelButton: 'btn btn-secondary px-4'
            }
        });

        // ✅ Jika user cancel, stop
        if (!result.isConfirmed) return;

        // ✅ Proses delete
        const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (commentItem) commentItem.style.opacity = '0.5';

        fetch(`/comment/destroy/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (commentItem) {
                        commentItem.style.animation = 'slideOutRight 0.3s ease';
                        setTimeout(() => {
                            commentItem.remove();

                            // Update counter di detail post
                            const commentCountSpan = document.querySelector(`#commentsCount-${postId} .comment-count-number`);
                            if (commentCountSpan) {
                                commentCountSpan.textContent = Math.max(0, parseInt(commentCountSpan.textContent) - 1);
                            }

                            // Update counter di feed
                            const feedCommentBtn = document.querySelector(`button[onclick="showComments(${postId})"]`);
                            if (feedCommentBtn) {
                                const currentCount = feedCommentBtn.textContent.match(/\d+/);
                                const newCount = currentCount ? Math.max(0, parseInt(currentCount[0]) - 1) : 0;
                                feedCommentBtn.innerHTML = `<i class="far fa-comment"></i> ${newCount}`;
                            }

                            // Show empty state jika tidak ada comment
                            const commentsList = document.getElementById(`commentsList-${postId}`);
                            if (commentsList && commentsList.children.length === 0) {
                                commentsList.innerHTML = `<p class="text-muted text-center py-3" id="noCommentsMsg-${postId}">No comments yet. Be the first to comment!</p>`;
                            }
                        }, 300);
                    }

                    // ✅ Success notification
                    window.showNotification('🗑️ Comment deleted successfully!');
                } else {
                    throw new Error(data.message || 'Failed to delete comment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (commentItem) commentItem.style.opacity = '1';

                // ✅ Error notification
                window.showNotification('⚠️ Failed to delete comment. Please try again.');
            });
    };

    // Modal Functions
    window.openModal = function(title, content) {
        document.getElementById('modal-title').textContent = title;
        if (content) {
            document.getElementById('modal-body').innerHTML = content;
        }
        document.getElementById('modal').style.display = 'block';
    };

    window.closeModal = function() {
        document.getElementById('modal').style.display = 'none';
    };

    window.openImageModal = function(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = 'block';
        modalImg.src = imageSrc;
        document.body.style.overflow = 'hidden';
    };

    window.closeImageModal = function() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    // Notification Function
    window.showNotification = function(message) {
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());

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
    };

    // ============================================================
    // DOM READY - FORM SUBMISSION & EVENT LISTENERS
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Functions available:', typeof window.triggerMediaUpload);

        const postForm = document.getElementById('postForm');
        const postContent = document.getElementById('postContent');
        const postBtn = document.getElementById('postBtn');

        if (postForm) {
            postForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const hasContent = postContent.value.trim().length > 0;
                const hasMedia = uploadedFiles.length > 0;

                if (!hasContent && !hasMedia) {
                    window.showNotification('⚠️ Please write something or add media');
                    return false;
                }

                const formData = new FormData(this);

                if (uploadedFiles.length > 0) {
                    uploadedFiles.forEach(file => {
                        formData.append('media[]', file);
                    });
                }

                if (postBtn) {
                    postBtn.disabled = true;
                    postBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
                }

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showNotification('✅ Post created successfully!');
                            postContent.value = '';
                            uploadedFiles = [];
                            window.renderMediaPreviews();
                            window.updateCharCount();

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Failed to create post');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showNotification('⚠️ Failed to create post. Please try again.');

                        if (postBtn) {
                            postBtn.disabled = false;
                            postBtn.innerHTML = 'Post';
                        }
                    });
            });
        }

        // Auto-resize textarea
        if (postContent) {
            postContent.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }

        // Initialize post button state
        if (postBtn) {
            postBtn.classList.remove('active');
        }
    });

    // ============================================================
    // WINDOW EVENT LISTENERS
    // ============================================================
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        const imageModal = document.getElementById('imageModal');
        if (event.target === modal) {
            window.closeModal();
        }
        if (event.target === imageModal) {
            window.closeImageModal();
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeModal();
            window.closeImageModal();
        }
    });

    console.log('✅ All functions loaded successfully');

    // ============================================================
    // VIDEO AUTO-PLAY & MUTE FUNCTIONALITY
    // ============================================================
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
                    video.play().catch(() => {});
                    currentlyPlaying = video;
                } else {
                    video.pause();
                    if (currentlyPlaying === video) currentlyPlaying = null;
                }
            });
        }, {
            threshold: [0, 0.6]
        });

        videos.forEach(video => observer.observe(video));

        // Click video to toggle fullscreen
        videos.forEach(video => {
            video.addEventListener("click", () => {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                } else {
                    video.requestFullscreen();
                }
            });
        });

        // Mute/unmute buttons
        const muteButtons = document.querySelectorAll(".mute-btn");
        muteButtons.forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                const video = btn.previousElementSibling;
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