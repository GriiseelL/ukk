@extends('layout.layarUtama')

@section('title', 'Profile Not Available - Telava')

@section('content')
<style>
    body {
        background: #f5f5f5;
    }

    .blocked-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .blocked-card {
        background: white;
        border-radius: 24px;
        padding: 60px 40px;
        margin-top: 50px;
        text-align: center;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        animation: slideUp 0.5s ease-out;
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

    .blocked-icon {
        font-size: 5rem;
        margin-bottom: 30px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .blocked-icon.you-blocked {
        color: #6c757d;
    }

    .blocked-icon.blocked-by {
        color: #dc3545;
    }

    .blocked-title {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .blocked-description {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .blocked-username {
        display: inline-block;
        background: rgba(0, 0, 0, 0.05);
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 30px;
    }

    .blocked-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-action {
        padding: 14px 24px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #1da1f2, #0d8bd9);
        color: white;
    }

    .btn-primary-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 161, 242, 0.4);
        color: white;
    }

    .btn-secondary-action {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 2px solid rgba(220, 53, 69, 0.2);
    }

    .btn-secondary-action:hover {
        background: rgba(220, 53, 69, 0.15);
        transform: translateY(-1px);
        color: #dc3545;
    }

    .btn-outline-action {
        background: transparent;
        color: #666;
        border: 2px solid #e1e8ed;
    }

    .btn-outline-action:hover {
        background: rgba(0, 0, 0, 0.02);
        border-color: #1da1f2;
        color: #1da1f2;
    }

    .info-box {
        background: rgba(29, 161, 242, 0.05);
        border: 1px solid rgba(29, 161, 242, 0.2);
        border-radius: 16px;
        padding: 20px;
        margin-top: 30px;
        text-align: left;
    }

    .info-box-title {
        font-weight: 700;
        color: #1da1f2;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-box-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-box-list li {
        padding: 8px 0;
        color: #666;
        font-size: 14px;
        display: flex;
        align-items: start;
        gap: 10px;
    }

    .info-box-list li i {
        color: #1da1f2;
        margin-top: 3px;
    }

    @media (max-width: 576px) {
        .blocked-card {
            padding: 40px 30px;
        }

        .blocked-icon {
            font-size: 4rem;
        }

        .blocked-title {
            font-size: 24px;
        }

        .blocked-description {
            font-size: 15px;
        }
    }
</style>

<div class="blocked-container">
    <div class="blocked-card">
        @if($isBlocked)
        <!-- You blocked this user -->
        <div class="blocked-icon you-blocked">
            <i class="fas fa-user-slash"></i>
        </div>

        <h1 class="blocked-title">You've Blocked This User</h1>

        <div class="blocked-username">
            <i class="fas fa-at"></i> {{ $username }}
        </div>

        <p class="blocked-description">
            You won't see content from this user in your feed, and they won't be able to follow you or see your profile.
        </p>

        <div class="blocked-actions">
            <button onclick="unblockUser({{ $user->id }})" class="btn-action btn-secondary-action">
                <i class="fas fa-unlock"></i>
                Unblock {{ $username }}
            </button>

            <a href="/" class="btn-action btn-primary-action">
                <i class="fas fa-home"></i>
                Go to Home
            </a>

            <button onclick="window.history.back()" class="btn-action btn-outline-action">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </button>
        </div>

        <div class="info-box">
            <div class="info-box-title">
                <i class="fas fa-info-circle"></i>
                What happens when you block someone?
            </div>
            <ul class="info-box-list">
                <li>
                    <i class="fas fa-check"></i>
                    <span>They can't follow you or see your posts</span>
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    <span>You won't see their posts in your feed</span>
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    <span>All previous interactions are hidden</span>
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    <span>Flipside access is automatically removed</span>
                </li>
            </ul>
        </div>

        @elseif($isBlockedBy)
        <!-- You are blocked by this user -->
        <div class="blocked-icon blocked-by">
            <i class="fas fa-ban"></i>
        </div>

        <h1 class="blocked-title">Content Unavailable</h1>

        <div class="blocked-username">
            <i class="fas fa-at"></i>{{ $username }}

        </div>

        <p class="blocked-description">
            This profile is not accessible at the moment. You may have been blocked by this user.
        </p>

        <div class="blocked-actions">
            <a href="/" class="btn-action btn-primary-action">
                <i class="fas fa-home"></i>
                Go to Home
            </a>

            <button onclick="window.history.back()" class="btn-action btn-outline-action">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </button>
        </div>

        <div class="info-box">
            <div class="info-box-title">
                <i class="fas fa-info-circle"></i>
                Why can't I see this profile?
            </div>
            <ul class="info-box-list">
                <li>
                    <i class="fas fa-check"></i>
                    <span>The user may have blocked you</span>
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    <span>Their profile might be private</span>
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    <span>The account might be restricted</span>
                </li>
            </ul>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function unblockUser(userId) {
        // ✅ GANTI DENGAN SWEETALERT2
        const result = await Swal.fire({
            title: 'Unblock User?',
            html: 'Are you sure you want to <strong>unblock</strong> this user?<br><small style="color: #666;">They will be able to see your profile again.</small>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-unlock"></i> Yes, Unblock',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-notification',
                title: 'swal2-notification-title',
                confirmButton: 'swal2-confirm-btn',
                cancelButton: 'swal2-cancel-btn'
            }
        });

        if (!result.isConfirmed) {
            return;
        }
        try {
            const response = await fetch(`/block/destroy/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification('✅ User unblocked successfully');

                // Redirect to profile after 1 second
                setTimeout(() => {
                    window.location.href = '/profilePage/{{ $username }}';
                }, 1000);
            } else {
                showNotification('❌ ' + result.message);
            }
        } catch (error) {
            console.error('Unblock error:', error);
            showNotification('❌ Failed to unblock user');
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>

@endsection