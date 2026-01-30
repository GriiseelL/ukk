@extends('layout.layarUtama')

@section('title', 'Settings - Telava')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* ===== MAIN LAYOUT ===== */
    .main-content {
        margin-top: 70px;
        padding-bottom: 80px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: calc(100vh - 150px);
    }

    .settings-container {
        max-width: 800px;
        width: 100%;
        padding: 20px 30px;
    }

    /* ===== SETTINGS CARD ===== */
    .settings-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .settings-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .settings-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 5px;
    }

    .settings-subtitle {
        font-size: 14px;
        color: #666;
    }

    /* ===== AVATAR SECTION ===== */
    .avatar-section {
        text-align: center;
        margin-bottom: 40px;
        padding: 30px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .avatar-display {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #1da1f2, #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 56px;
        font-weight: 700;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .avatar-display:hover {
        transform: scale(1.05);
    }

    .avatar-display img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-overlay {
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
        border-radius: 50%;
    }

    .avatar-display:hover .avatar-overlay {
        opacity: 1;
    }

    .change-avatar-btn {
        background: #1da1f2;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 12px 30px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .change-avatar-btn:hover {
        background: #1a91da;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
    }

    /* ===== FORM SECTION ===== */
    .form-group {
        margin-bottom: 30px;
    }

    .form-label {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-input:focus {
        outline: none;
        border-color: #1da1f2;
        background: white;
        box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
    }

    .form-input:disabled {
        background: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }

    .input-group {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #657786;
        font-size: 16px;
    }

    .input-with-icon {
        padding-left: 45px;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: linear-gradient(135deg, #1da1f2, #0d8bd9);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px 32px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 161, 242, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 10px;
        padding: 14px 32px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    /* ===== DIVIDER ===== */
    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e1e8ed, transparent);
        margin: 30px 0;
    }

    /* ===== INFO BOX ===== */
    .info-box {
        background: #e3f2fd;
        border-left: 4px solid #1da1f2;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .info-box i {
        color: #1da1f2;
        margin-right: 10px;
    }

    .info-box p {
        margin: 0;
        font-size: 14px;
        color: #0d47a1;
    }

    /* ===== NOTIFICATION ===== */
    .notification {
        position: fixed;
        top: 90px;
        right: 20px;
        z-index: 9999;
        background: #1da1f2;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* ===== MODAL ===== */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        border-bottom: 2px solid #f0f0f0;
    }

    .modal-title {
        font-weight: 700;
        color: #1a1a1a;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .main-content {
            padding: 0;
        }

        .settings-container {
            padding: 15px 10px;
        }

        .settings-card {
            padding: 20px;
        }

        .settings-title {
            font-size: 20px;
        }

        .avatar-display {
            width: 100px;
            height: 100px;
            font-size: 40px;
        }
    }

    @media (min-width: 992px) {
        .main-content {
            margin-left: 250px;
            padding-right: 20px;
        }
    }
</style>

<div class="main-content">
    <div class="settings-container">
        <!-- Settings Card -->
        <div class="settings-card">
            <div class="settings-header">
                <h1 class="settings-title">
                    <i class="fas fa-cog"></i>
                    Account Settings
                </h1>
                <p class="settings-subtitle">Manage your account information and security</p>
            </div>

            <!-- Avatar Section -->
            <div class="avatar-section">
                <div class="avatar-display" onclick="openAvatarModal()">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                    <div class="avatar-overlay">
                        <i class="fas fa-camera" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
                <button class="change-avatar-btn" onclick="openAvatarModal()">
                    <i class="fas fa-upload"></i> Change Avatar
                </button>
            </div>

            <!-- Email Section -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input 
                        type="email" 
                        class="form-input input-with-icon" 
                        value="{{ auth()->user()->email }}" 
                        disabled
                    >
                </div>
                <small style="color: #666; font-size: 13px; margin-top: 5px; display: block;">
                    <i class="fas fa-info-circle"></i> Email cannot be changed for security reasons
                </small>
            </div>

            <div class="divider"></div>

            <!-- Password Section -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="info-box">
                    <i class="fas fa-shield-alt"></i>
                    <p>Keep your account secure by using a strong password</p>
                </div>
                <button class="btn-primary" onclick="openForgotPasswordModal()">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Change Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle" style="color: #1da1f2;"></i> Change Avatar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <div class="text-center mb-4">
                    <div id="avatarPreview" style="width: 150px; height: 150px; border-radius: 50%; margin: 0 auto; background: linear-gradient(135deg, #1da1f2, #0d8bd9); display: flex; align-items: center; justify-content: center; color: white; font-size: 60px; font-weight: 700; overflow: hidden; border: 4px solid #f0f0f0;">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>

                <form id="avatarForm" onsubmit="uploadAvatar(event)">
                    <div class="mb-3">
                        <label class="form-label">Select New Avatar</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="avatarInput" 
                            accept="image/*"
                            onchange="previewAvatar(event)"
                            style="border: 2px dashed #1da1f2; padding: 15px; border-radius: 10px;"
                        >
                        <small style="color: #666; display: block; margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Max size: 2MB • Formats: JPG, PNG, GIF
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary" id="uploadBtn">
                            <i class="fas fa-upload"></i> Upload Avatar
                        </button>
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key" style="color: #1da1f2;"></i> Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <div class="info-box mb-4">
                    <i class="fas fa-info-circle"></i>
                    <p>Enter your current password and choose a new one</p>
                </div>

                <form id="changePasswordForm" onsubmit="changePassword(event)">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input 
                            type="password" 
                            class="form-input" 
                            id="currentPassword" 
                            placeholder="Enter current password"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input 
                            type="password" 
                            class="form-input" 
                            id="newPassword" 
                            placeholder="Enter new password"
                            minlength="8"
                            required
                        >
                        <small style="color: #666; display: block; margin-top: 5px;">
                            Minimum 8 characters
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input 
                            type="password" 
                            class="form-input" 
                            id="confirmPassword" 
                            placeholder="Confirm new password"
                            minlength="8"
                            required
                        >
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary" id="changePasswordBtn">
                            <i class="fas fa-save"></i> Change Password
                        </button>
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Open Avatar Modal
    function openAvatarModal() {
        const modal = new bootstrap.Modal(document.getElementById('avatarModal'));
        modal.show();
    }

    // Preview Avatar
    function previewAvatar(event) {
        const file = event.target.files[0];
        
        if (!file) return;
        
        if (file.size > 2 * 1024 * 1024) {
            showNotification('File too large! Max 2MB', 'error');
            event.target.value = '';
            return;
        }
        
        if (!file.type.startsWith('image/')) {
            showNotification('File must be an image!', 'error');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
        };
        reader.readAsDataURL(file);
    }

    // Upload Avatar
    async function uploadAvatar(event) {
        event.preventDefault();
        
        const fileInput = document.getElementById('avatarInput');
        const file = fileInput.files[0];
        
        if (!file) {
            showNotification('Please select an image!', 'error');
            return;
        }
        
        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        
        const formData = new FormData();
        formData.append('avatar', file);
        
        try {
            const response = await fetch('/profile/update-avatar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                showNotification('Avatar updated successfully!', 'success');
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(result.message || 'Failed to upload avatar');
            }
        } catch (error) {
            console.error('Upload error:', error);
            showNotification('Failed to upload avatar. Try again!', 'error');
            
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Avatar';
        }
    }

    // Open Forgot Password Modal
    function openForgotPasswordModal() {
        const modal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
        modal.show();
    }

    // Change Password
    async function changePassword(event) {
        event.preventDefault();
        
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            showNotification('New passwords do not match!', 'error');
            return;
        }
        
        if (newPassword.length < 8) {
            showNotification('Password must be at least 8 characters!', 'error');
            return;
        }
        
        const changeBtn = document.getElementById('changePasswordBtn');
        changeBtn.disabled = true;
        changeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing...';
        
        try {
            const response = await fetch('/profile/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword,
                    new_password_confirmation: confirmPassword
                })
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                showNotification('Password changed successfully!', 'success');
                
                document.getElementById('changePasswordForm').reset();
                
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal')).hide();
                }, 1500);
            } else {
                throw new Error(result.message || 'Failed to change password');
            }
        } catch (error) {
            console.error('Change password error:', error);
            showNotification(error.message || 'Failed to change password!', 'error');
        } finally {
            changeBtn.disabled = false;
            changeBtn.innerHTML = '<i class="fas fa-save"></i> Change Password';
        }
    }

    // Show Notification
    function showNotification(message, type = 'info') {
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = 'notification';
        
        if (type === 'error') {
            notification.style.background = '#e74c3c';
        } else if (type === 'success') {
            notification.style.background = '#2ecc71';
        }
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection