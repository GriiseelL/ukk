@extends('layout.layarUtama')

@section('title', 'Notifikasi - Telava')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
    }

    .notifications-container {
        max-width: 800px;
        margin: 0 auto;
        animation: fadeIn 0.6s ease-out;
        margin-right: 300px;
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

    /* Header Section */
    .notifications-header {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .notification-badge {
        background: linear-gradient(135deg, #ff6b6b, #ff5252);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(255, 107, 107, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 107, 107, 0);
        }
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .filter-dropdown {
        position: relative;
    }

    .filter-btn {
        background: rgba(29, 161, 242, 0.1);
        border: 2px solid rgba(29, 161, 242, 0.2);
        border-radius: 20px;
        padding: 10px 18px;
        color: var(--primary-color);
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .mark-read-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        border-radius: 20px;
        padding: 10px 18px;
        color: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mark-read-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-top: 20px;
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
        font-weight: 500;
    }

    .filter-tab.active,
    .filter-tab:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    /* Notifications List */
    .notifications-list {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: rgba(29, 161, 242, 0.02);
        transform: translateX(5px);
    }

    .notification-item.unread {
        background: linear-gradient(90deg, rgba(29, 161, 242, 0.05), rgba(255, 255, 255, 0.95));
        border-left: 4px solid var(--primary-color);
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        top: 20px;
        right: 20px;
        width: 10px;
        height: 10px;
        background: #ff6b6b;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .notification-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
        margin-right: 15px;
        position: relative;
        flex-shrink: 0;
    }

    .notification-type-icon {
        position: absolute;
        bottom: -3px;
        right: -3px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid white;
        color: white;
    }

    .type-like {
        background: #ff6b6b;
    }

    .type-comment {
        background: #4ecdc4;
    }

    .type-follow {
        background: #45b7d1;
    }

    .type-share {
        background: #feca57;
    }

    .type-mention {
        background: #5f27cd;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-text {
        font-size: 15px;
        color: #1a1a1a;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .notification-text .username {
        font-weight: 700;
        color: var(--primary-color);
    }

    .notification-text .action {
        color: #666;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .notification-time {
        font-size: 13px;
        color: #888;
    }

    .notification-post-preview {
        font-size: 13px;
        color: #666;
        background: rgba(0, 0, 0, 0.05);
        padding: 8px 12px;
        border-radius: 12px;
        margin-top: 8px;
        border-left: 3px solid var(--primary-color);
    }

    .notification-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .notification-btn {
        padding: 6px 12px;
        border: 1px solid rgba(29, 161, 242, 0.3);
        border-radius: 15px;
        background: transparent;
        color: var(--primary-color);
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .notification-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    .notification-btn.primary {
        background: var(--primary-color);
        color: white;
    }

    .notification-btn.primary:hover {
        background: #0d8bd9;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state .icon {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 16px;
        line-height: 1.5;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Loading States */
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

    .notification-skeleton {
        display: flex;
        align-items: flex-start;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .skeleton-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .skeleton-content {
        flex: 1;
    }

    .skeleton-text {
        height: 16px;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .skeleton-text.short {
        width: 70%;
    }

    .skeleton-text.long {
        width: 90%;
    }

    .skeleton-time {
        height: 12px;
        width: 80px;
        border-radius: 4px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-container {
            margin: 10px;
        }

        .page-title {
            font-size: 24px;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            justify-content: space-between;
            width: 100%;
        }

        .notification-item {
            padding: 15px;
        }

        .notification-avatar {
            width: 45px;
            height: 45px;
            font-size: 14px;
        }
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
        }
    }

    /* Custom scrollbar */
    .notifications-list {
        max-height: 70vh;
        overflow-y: auto;
    }

    .notifications-list::-webkit-scrollbar {
        width: 6px;
    }

    .notifications-list::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
    }

    .notifications-list::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 3px;
    }

    .notifications-list::-webkit-scrollbar-thumb:hover {
        background: #0d8bd9;
    }
</style>

<div class="main-content">
    <div class="notifications-container">
        <!-- Header Section -->
        <div class="notifications-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-bell"></i>
                    Notifikasi
                    <div class="notification-badge" id="notificationCount">
                        {{ $notifications->where('is_read',0)->count() }}
                    </div>
                </h1>
                <div class="header-actions">
                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleFilterDropdown()">
                            <i class="fas fa-filter"></i>
                            Filter
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <button class="mark-read-btn" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i>
                        Tandai Semua Dibaca
                    </button>
                </div>
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">Semua</button>
                <button class="filter-tab" data-filter="likes">Suka</button>
                <button class="filter-tab" data-filter="comments">Komentar</button>
                <button class="filter-tab" data-filter="follows">Mengikuti</button>
                <button class="filter-tab" data-filter="mentions">Sebutan</button>
                <button class="filter-tab" data-filter="shares">Berbagi</button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="notifications-list" id="notificationsList">
            <!-- Loading skeleton initially -->
            <div class="notification-skeleton">
                <div class="skeleton skeleton-avatar"></div>
                <div class="skeleton-content">
                    <div class="skeleton skeleton-text long"></div>
                    <div class="skeleton skeleton-text short"></div>
                    <div class="skeleton skeleton-time"></div>
                </div>
            </div>
            <div class="notification-skeleton">
                <div class="skeleton skeleton-avatar"></div>
                <div class="skeleton-content">
                    <div class="skeleton skeleton-text long"></div>
                    <div class="skeleton skeleton-text short"></div>
                    <div class="skeleton skeleton-time"></div>
                </div>
            </div>
            <div class="notification-skeleton">
                <div class="skeleton skeleton-avatar"></div>
                <div class="skeleton-content">
                    <div class="skeleton skeleton-text long"></div>
                    <div class="skeleton skeleton-text short"></div>
                    <div class="skeleton skeleton-time"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sample notification data - replace with actual data from your backend
    const notificationsFromServer = @json($notifications);
    let notifications = notificationsFromServer.map(n => ({
        id: n.id,
        type: n.type,
        user: {
            id: n.sender.id,
            username: n.sender.username,
            initial: n.sender.username.charAt(0).toUpperCase()
        },
        action: n.type === 'like' ? 'menyukai' : n.type === 'comment' ? 'mengomentari' : n.type === 'follow' ? 'mulai mengikuti' : '',
        target: 'postingan Anda',
        time: new Date(n.created_at).toLocaleString(),
        read: n.is_read == 1,

        is_followed: n.is_followed ?? false
    }));

    let currentFilter = 'all';

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        setupEventListeners();
        setTimeout(loadNotifications, 800); // Simulate loading
    });

    function setupEventListeners() {
        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                setActiveFilter(this.dataset.filter);
            });
        });

        // Mark individual notifications as read on click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.notification-item')) {
                const item = e.target.closest('.notification-item');
                markAsRead(parseInt(item.dataset.id));
            }
        });
    }

    function loadNotifications() {
        const container = document.getElementById('notificationsList');
        const filteredNotifications = filterNotifications();

        if (filteredNotifications.length === 0) {
            container.innerHTML = `
                    <div class="empty-state">
                        <div class="icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h3>Tidak Ada Notifikasi</h3>
                        <p>Belum ada notifikasi untuk kategori ini. Notifikasi baru akan muncul di sini.</p>
                    </div>
                `;
            return;
        }

        container.innerHTML = filteredNotifications.map(notification =>
            createNotificationHTML(notification)
        ).join('');

        updateNotificationCount();
    }

    function createNotificationHTML(notification) {
        const typeIcons = {
            like: 'fas fa-heart',
            comment: 'fas fa-comment',
            follow: 'fas fa-user-plus',
            mention: 'fas fa-at',
            share: 'fas fa-share'
        };

        const actionButtons = notification.type === 'follow' ? `
    <div class="notification-actions">
        ${
            notification.is_followed
                ? `<button class="notification-btn primary" disabled>
                        <i class="fas fa-check"></i> Diikuti
                   </button>`
                : `<button class="notification-btn primary"
                        onclick="followBack(${notification.id}, ${notification.user.id})">
                        <i class="fas fa-user-plus"></i> Ikuti Balik
                   </button>`
        }
        <button class="notification-btn" onclick="viewProfile('${notification.user.username}')">
            <i class="fas fa-eye"></i> Lihat Profil
        </button>
    </div>
` : '';


        const commentPreview = notification.comment ? `
                <div class="notification-post-preview">
                    <i class="fas fa-quote-left" style="margin-right: 5px; opacity: 0.7;"></i>
                    ${notification.comment}
                </div>
            ` : '';

        const postPreview = notification.postPreview && notification.type !== 'follow' ? `
                <div class="notification-post-preview">
                    ${notification.postPreview}
                </div>
            ` : '';

        return `
                <div class="notification-item ${!notification.read ? 'unread' : ''}" data-id="${notification.id}">
                    <div class="notification-avatar">
                        ${notification.user.initial}
                        <div class="notification-type-icon type-${notification.type}">
                            <i class="${typeIcons[notification.type]}"></i>
                        </div>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">
                        <span class="username clickable"
                            onclick="viewProfile('${notification.user.username}')">
                            @${notification.user.username}
                        </span>
                            ${notification.target}
                        </div>
                        <div class="notification-meta">
                            <span class="notification-time">
                                <i class="fas fa-clock" style="margin-right: 4px; opacity: 0.7;"></i>
                                ${notification.time}
                            </span>
                        </div>
                        ${commentPreview}
                        ${postPreview}
                        ${actionButtons}
                    </div>
                </div>
            `;
    }

    function setActiveFilter(filter) {
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
        currentFilter = filter;
        loadNotifications();
    }

    function filterNotifications() {
        if (currentFilter === 'all') {
            return notifications;
        }

        const filterMap = {
            likes: 'like',
            comments: 'comment',
            follows: 'follow',
            mentions: 'mention',
            shares: 'share'
        };

        return notifications.filter(n => n.type === filterMap[currentFilter]);
    }

    function markAsRead(notificationId) {
        const notification = notifications.find(n => n.id === notificationId);
        if (notification && !notification.read) {
            notification.read = true;
            const element = document.querySelector(`[data-id="${notificationId}"]`);
            if (element) {
                element.classList.remove('unread');
            }
            updateNotificationCount();
            showNotification('Notifikasi ditandai sebagai dibaca');
        }
    }

    function markAllAsRead() {
        const unreadCount = notifications.filter(n => !n.read).length;
        if (unreadCount === 0) {
            showNotification('Semua notifikasi sudah dibaca');
            return;
        }

        notifications.forEach(n => n.read = true);
        document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.classList.remove('unread');
        });
        updateNotificationCount();
        showNotification(`${unreadCount} notifikasi ditandai sebagai dibaca`);
    }

    function updateNotificationCount() {
        const unreadCount = notifications.filter(n => !n.read).length;
        const badge = document.getElementById('notificationCount');
        if (unreadCount > 0) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    function followBack(userId) {
        fetch(`/follow/store/${userId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.following) {
                    showNotification("Berhasil mengikuti balik");
                } else {
                    showNotification("Berhenti mengikuti");
                }
            })
            .catch(() => {
                showNotification("Gagal follow user");
            });
    }

    function viewProfile(username) {
        window.location.href = `/profilePage/${username}`;
    }


    function toggleFilterDropdown() {
        showNotification('Filter dropdown - fitur akan segera hadir');
    }

    function showNotification(message) {
        // Remove existing notification
        const existing = document.querySelector('.toast-notification');
        if (existing) existing.remove();

        // Create new notification
        const notification = document.createElement('div');
        notification.className = 'toast-notification';
        notification.style.cssText = `
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
            `;
        notification.textContent = message;
        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Add CSS animation for toast notification
    const style = document.createElement('style');
    style.textContent = `
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
        `;
    document.head.appendChild(style);

    // Simulate real-time notifications
</script>
@endsection