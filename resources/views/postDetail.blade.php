@extends('layout.layarUtama')

@section('title', 'Postingan - Telava')

@section('content')
<style>
    .post-show-container {
        max-width: 700px;
        margin: 30px auto;
        padding: 0 15px;
        margin-top: 70px;
        margin-left: 50px;
    }

    .post-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .post-header {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .post-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
    }

    .post-user-info {
        flex: 1;
    }

    .post-username {
        font-weight: 700;
        font-size: 16px;
        color: #1a1a1a;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .post-username:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .post-time {
        font-size: 13px;
        color: #888;
        margin-top: 3px;
    }

    .post-content {
        padding: 20px;
        font-size: 16px;
        line-height: 1.6;
        color: #1a1a1a;
    }

    .post-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        display: block;
    }

    .post-actions {
        padding: 15px 20px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: none;
        color: #666;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 8px 15px;
        border-radius: 15px;
    }

    .action-btn:hover {
        background: rgba(29, 161, 242, 0.1);
        color: var(--primary-color);
    }

    .action-btn.active {
        color: var(--primary-color);
        font-weight: 600;
    }

    .action-btn.like-btn.active {
        color: #ff6b6b;
    }

    .comments-section {
        border-top: 1px solid #f0f0f0;
        padding: 20px;
    }

    .comments-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #1a1a1a;
    }

    .comment-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        flex-shrink: 0;
    }

    .comment-content {
        flex: 1;
    }

    .comment-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }

    .comment-username {
        font-weight: 600;
        font-size: 14px;
        color: var(--primary-color);
        cursor: pointer;
    }

    .comment-text {
        font-size: 14px;
        color: #333;
        line-height: 1.5;
    }

    .comment-time {
        font-size: 12px;
        color: #888;
        margin-left: 8px;
    }

    .comment-form {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #f0f0f0;
    }

    .comment-input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .comment-input:focus {
        border-color: var(--primary-color);
    }

    .comment-submit {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 12px 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .comment-submit:hover {
        background: #0d8bd9;
        transform: translateY(-2px);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(29, 161, 242, 0.1);
        color: var(--primary-color);
        border: 2px solid rgba(29, 161, 242, 0.2);
        border-radius: 15px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateX(-3px);
    }

    @media (max-width: 768px) {
        .post-show-container {
            margin: 15px;
        }

        .post-card {
            border-radius: 15px;
        }
    }
</style>

<div class="main-content">
    <div class="post-show-container">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

        <div class="post-card">
            <!-- Post Header -->
            <div class="post-header">
                <div class="post-avatar">
                    @if($post->user->avatar)
                    <img
                        src="{{ asset('storage/' . $post->user->avatar) }}"
                        style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                    @else
                    {{ strtoupper($post->user->username[0]) }}
                    @endif
                </div>

                <div class="post-user-info">
                    <div class="post-username" onclick="window.location.href='/profilePage/{{ $post->user->username }}'">
                        {{ $post->user->username }}
                    </div>
                    <div class="post-time">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Post Content -->
            <div class="post-content">
                {{ $post->caption }}
            </div>

            <!-- Post Image -->
            @if($post->image)
            @foreach($post->media as $media)
            <img src="{{ asset('storage/' . $media->file_path) }}" class="post-image">
            @endforeach

            @endif

            <!-- Post Actions -->
            <div class="post-actions">
                <button class="action-btn like-btn {{ $isLiked ? 'active' : '' }}" onclick="toggleLike({{ $post->id }})">
                    <i class="fas fa-heart"></i>
                    <span id="likeCount">{{ $post->likes()->count() }}</span> Suka
                </button>
                <button class="action-btn" onclick="scrollToComments()">
                    <i class="fas fa-comment"></i>
                    <span>{{ $post->comments()->count() }}</span> Komentar
                </button>
                <button class="action-btn">
                    <i class="fas fa-share"></i>
                    Bagikan
                </button>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="post-card">
            <div class="comments-section">
                <div class="comments-title">
                    Komentar ({{ $post->comments()->count() }})
                </div>

                <!-- Comments List -->
                <div id="commentsList">
                    @forelse($comments as $comment)
                    <div class="comment-item">
                        <div class="comment-avatar">
                            @if($comment->user->avatar)
                            <img
                                src="{{ asset('storage/' . $comment->user->avatar) }}"
                                style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                            @else
                            {{ strtoupper($comment->user->username[0]) }}
                            @endif
                        </div>

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-username"
                                    onclick="window.location.href='/profilePage/{{ $comment->user->username }}'">
                                    {{ $comment->user->username }}
                                </span>
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="comment-text">
                                {{ $comment->content }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 30px; color: #888;">
                        Belum ada komentar. Jadilah yang pertama!
                    </div>
                    @endforelse
                </div>

                <!-- Comment Form -->
                <form id="commentForm" class="comment-form">
                    @csrf
                    <input type="text"
                        id="commentInput"
                        class="comment-input"
                        placeholder="Tulis komentar..."
                        autocomplete="off">
                    <button type="submit" class="comment-submit">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const postId = {
        {
            $post - > id
        }
    };
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Toggle Like
    function toggleLike(postId) {
        fetch(`/post/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const likeBtn = document.querySelector('.like-btn');
                const likeCount = document.getElementById('likeCount');

                if (data.liked) {
                    likeBtn.classList.add('active');
                    likeBtn.innerHTML = `<i class="fas fa-heart"></i> <span>${data.count}</span> Suka`;
                } else {
                    likeBtn.classList.remove('active');
                    likeBtn.innerHTML = `<i class="fas fa-heart"></i> <span>${data.count}</span> Suka`;
                }

                likeCount.textContent = data.count;
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Gagal menyukai postingan');
            });
    }

    // Add Comment
    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const content = document.getElementById('commentInput').value.trim();

        if (!content) {
            showNotification('Komentar tidak boleh kosong');
            return;
        }

        fetch(`/post/${postId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear input
                    document.getElementById('commentInput').value = '';

                    // Add new comment to list
                    addCommentToDOM(data.comment);

                    // Update comment count
                    const commentBtn = document.querySelector('.post-actions .action-btn:nth-child(2)');
                    const currentCount = parseInt(commentBtn.querySelector('span').textContent);
                    commentBtn.innerHTML = `<i class="fas fa-comment"></i> <span>${currentCount + 1}</span> Komentar`;

                    showNotification('Komentar berhasil ditambahkan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Gagal menambahkan komentar');
            });
    });

    function addCommentToDOM(comment) {
        const commentsList = document.getElementById('commentsList');

        // Remove empty state if exists
        const emptyState = commentsList.querySelector('div[style*="text-align: center"]');
        if (emptyState) {
            emptyState.remove();
        }

        const commentElement = document.createElement('div');
        commentElement.className = 'comment-item';
        commentElement.innerHTML = `
            <div class="comment-avatar">
                ${comment.user.username[0]}
            </div>
            <div class="comment-content">
                <div class="comment-header">
                    <span class="comment-username">
                        ${comment.user.username}
                    </span>
                    <span class="comment-time">${comment.created_at}</span>
                </div>
                <div class="comment-text">
                    ${comment.content}
                </div>
            </div>
        `;

        commentsList.prepend(commentElement);
    }

    function scrollToComments() {
        document.querySelector('.comments-section').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 10000;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease, fadeOut 0.5s 2.5s ease forwards;
            max-width: 300px;
            text-align: center;
        `;
        notification.innerHTML = `<i class="fas fa-check-circle" style="margin-right: 8px;"></i> ${message}`;
        document.body.appendChild(notification);

        setTimeout(() => notification.remove(), 3000);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(100%); }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection