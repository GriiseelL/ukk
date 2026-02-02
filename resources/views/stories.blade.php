<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modern Stories - Telava</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .story-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .story-viewer {
            position: relative;
            width: min(390px, 100vw);
            height: min(720px, 100vh);
            background: linear-gradient(145deg, #1e1e2e, #2a2a40);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .story-viewer {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
                border: none;
            }
        }

        .story-progress-bar {
            display: flex;
            position: absolute;
            top: 16px;
            left: 16px;
            right: 16px;
            z-index: 20;
            gap: 6px;
        }

        .progress-segment {
            flex: 1;
            height: 4px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #ff8e53);
            background-size: 200% 100%;
            width: 0%;
            border-radius: 6px;
            transition: width 0.1s linear;
        }

        .progress-segment.active .progress-fill {
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .progress-segment.viewed .progress-fill {
            width: 100%;
            background: linear-gradient(90deg, #4ecdc4, #44a08d);
            animation: none;
        }

        .story-header {
            position: absolute;
            top: 36px;
            left: 16px;
            right: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 15;
            color: white;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            padding: 12px 16px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .story-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .story-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            background: linear-gradient(145deg, #ff6b6b, #4ecdc4);
            overflow: hidden;
        }

        .story-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .story-username {
            font-weight: 600;
            font-size: 15px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .story-time {
            font-size: 13px;
            opacity: 0.8;
        }

        .story-controls {
            display: flex;
            gap: 12px;
            position: relative;
        }

        .story-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 16px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .story-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .story-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        .story-media {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .story-media img,
        .story-media video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: black;
        }


        /* Orientation Helper */
        .story-media.landscape img,
        .story-media.landscape video {
            object-fit: contain;
        }

        .story-media.portrait img,
        .story-media.portrait video {
            object-fit: cover;
        }

        .story-media.square img,
        .story-media.square video {
            object-fit: cover;
        }


        .story-text-overlay {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 24px 180px;
            z-index: 10;
        }

        .story-text {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.5;
            color: white;
            text-align: center;
            max-width: 100%;
            word-wrap: break-word;
        }

        .story-caption {
            font-size: 16px;
            font-weight: 500;
            color: white;
            text-align: center;
            position: absolute;
            bottom: 140px;
            left: 24px;
            right: 24px;
        }

        .story-navigation {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 5;
        }

        .story-navigation:active {
            background: rgba(255, 255, 255, 0.1);
        }

        .prev-story {
            left: 0;
        }

        .next-story {
            right: 0;
        }

        .close-story {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 25;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .close-story:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        .story-actions {
            position: absolute;
            bottom: 24px;
            left: 24px;
            right: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            z-index: 15;
        }

        .story-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 12px 20px;
            color: white;
            font-size: 15px;
        }

        .story-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .story-action-btn {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .story-action-btn:hover {
            transform: scale(1.1);
        }

        .story-menu-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px;
            min-width: 180px;
            display: none;
            z-index: 30;
        }

        .story-menu-dropdown.active {
            display: block;
        }

        .story-menu-item {
            padding: 12px 16px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .story-menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .story-menu-item.delete {
            color: #ff4757;
        }

        .story-menu-item.mute-story {
            color: #ffa502;
        }

        /* POPUP NOTIFICATION STYLE */
        .story-post-action {
            position: fixed;
            bottom: 50%;
            left: 50%;
            transform: translate(-50%, 50%);
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            z-index: 300;
            display: flex;
            flex-direction: column;
            gap: 16px;
            animation: slideUp .4s ease;
            pointer-events: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
            min-width: 320px;
            max-width: 90vw;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes slideUp {
            from {
                transform: translate(-50%, 50%) translateY(40px);
                opacity: 0;
            }

            to {
                transform: translate(-50%, 50%) translateY(0);
                opacity: 1;
            }
        }

        .post-action-text {
            color: #fff;
            font-weight: 600;
            text-align: center;
            font-size: 18px;
            padding: 8px 0;
        }

        .post-action-buttons {
            display: flex;
            gap: 12px;
        }

        .post-action-buttons button {
            flex: 1;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .post-action-buttons button:active {
            transform: scale(0.95);
        }

        .view-btn {
            background: #444;
            color: #fff;
        }

        .view-btn:hover {
            background: #555;
        }

        .add-btn {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: #fff;
        }

        .add-btn:hover {
            opacity: 0.9;
        }

        /* Overlay gelap di belakang popup */
        .story-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 250;
            display: none;
        }

        .story-overlay.active {
            display: block;
        }

        /* Nonaktifkan navigasi saat popup muncul */
        .story-viewer.popup-active .story-navigation {
            pointer-events: none;
        }

        .story-viewer.popup-active {
            filter: blur(4px);
        }
    </style>
</head>

<body>
    <div class="story-container">
        <!-- Overlay gelap -->
        @if(session('story_created'))
        <div class="story-overlay active" id="storyOverlay"></div>
        @endif

        <!-- Popup notifikasi -->
        @if(session('story_created'))
        <div class="story-post-action" id="storyPostAction">
            <div class="post-action-text">
                🎉 Story berhasil dibuat!
            </div>

            <div class="post-action-buttons">
                <button class="view-btn" onclick="hidePostAction()">
                    👁️ Lihat Story
                </button>

                <button class="add-btn" onclick="goAddStory()">
                    ➕ Tambah Story Lagi
                </button>
            </div>
        </div>
        @endif

        <div class="story-viewer" id="storyViewer">
            <button class="close-story" onclick="closeStory()">
                <i class="fas fa-times"></i>
            </button>

            <div class="story-progress-bar" id="progressBar"></div>

            <div class="story-header">
                <div class="story-user-info">
                    <div class="story-avatar" id="currentAvatar"></div>
                    <div>
                        <div class="story-username" id="currentUsername"></div>
                        <div class="story-time" id="currentTime"></div>
                    </div>
                </div>
                <div class="story-controls">
                    <button class="story-btn" onclick="togglePlay()">
                        <i class="fas fa-pause" id="playBtn"></i>
                    </button>
                    <button class="story-btn" onclick="toggleMute()">
                        <i class="fas fa-volume-up" id="muteBtn"></i>
                    </button>
                    <button class="story-btn" id="storyMenuBtn" onclick="toggleStoryMenu()">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <div class="story-menu-dropdown" id="storyMenu">
                        <div class="story-menu-item delete" onclick="deleteStory()" style="display: none;">
                            <i class="fas fa-trash"></i>
                            <span>Delete Story</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="story-content" id="storyContent">
                <div class="story-media" id="storyMedia" style="display: none;"></div>
                <div class="story-text-overlay" id="storyTextOverlay">
                    <div class="story-text" id="storyText" style="display: none;"></div>
                </div>
                <div class="story-caption" id="storyCaption" style="display: none;"></div>
            </div>

            <button class="story-navigation prev-story" onclick="prevStory()"></button>
            <button class="story-navigation next-story" onclick="nextStory()"></button>
        </div>
    </div>

    @php
    $storiesData = $stories->sortBy('created_at')->values()->map(function($story) {
    return [
    'id' => $story->id,
    'type' => $story->type,
    'media' => $story->media ? asset('storage/'.$story->media) : null,
    'caption' => $story->caption,
    'text_content' => $story->text_content ?? null,
    'background' => $story->background ?? 'linear-gradient(135deg, #667eea, #764ba2)',
    'created_at' => $story->created_at->diffForHumans(),
    'user' => [
    'id' => $story->user->id,
    'name' => $story->user->name,
    'avatar_url' => $story->user->avatar ? asset('storage/'.$story->user->avatar) : null,
    ],
    ];
    });
    @endphp

    <script>
        const stories = @json($storiesData);
      const currentUserId = Number({{ Auth::id() }});

        function initPopupState() {
            const popup = document.getElementById('storyPostAction');
            const viewer = document.getElementById('storyViewer');
            const overlay = document.getElementById('storyOverlay');

            if (popup) {
                viewer.classList.add('popup-active');
                if (overlay) overlay.classList.add('active');
                // Pause story saat popup muncul
                isPlaying = false;
                cancelAnimationFrame(progressRAF);
            }
        }

        function hidePostAction() {
            const popup = document.getElementById('storyPostAction');
            const viewer = document.getElementById('storyViewer');
            const overlay = document.getElementById('storyOverlay');

            if (popup) {
                popup.style.animation = 'slideUp 0.3s ease reverse';
                setTimeout(() => {
                    popup.style.display = 'none';
                    viewer.classList.remove('popup-active');
                    if (overlay) overlay.classList.remove('active');

                    // Resume story
                    isPlaying = true;
                    document.getElementById("playBtn").className = 'fas fa-pause';
                    if (activeVideo) activeVideo.play();
                    startProgress();
                }, 300);
            }
        }

        function goAddStory() {
            // Clean up before redirect
            stopAndClearActiveVideo();
            cancelAnimationFrame(progressRAF);
            document.getElementById('storyMenu').classList.remove('active');

            const popup = document.getElementById('storyPostAction');
            const overlay = document.getElementById('storyOverlay');
            const viewer = document.getElementById('storyViewer');

            if (popup) {
                popup.classList.add('hiding');
                if (overlay) overlay.classList.remove('active');
                viewer.classList.remove('popup-active');

                setTimeout(() => {
                    window.location.href = "{{ route('stories.create') }}";
                }, 300);
            } else {
                window.location.href = "{{ route('stories.create') }}";
            }
        }

        function toggleStoryMenu() {
            const menu = document.getElementById('storyMenu');
            if (!menu) return;
            menu.classList.toggle('active');
        }

        console.log('Stories loaded:', stories);
        console.log('Current user:', currentUserId);

        if (!stories || stories.length === 0) {
            alert('No stories available');
            window.location.href = "{{ route('homepage') }}";
        }

        let currentIndex = 0;
        let isPlaying = true;
        let isMuted = false;
        let progressRAF = null;
        let totalDurationDefault = 5000;
        let totalDuration = totalDurationDefault;
        let progressStartTime = null;
        let pausedProgress = 0;
        let activeVideo = null;

        const storyMedia = document.getElementById("storyMedia");
        const currentAvatar = document.getElementById("currentAvatar");
        const currentUsername = document.getElementById("currentUsername");
        const currentTime = document.getElementById("currentTime");
        const storyCaption = document.getElementById("storyCaption");
        const storyText = document.getElementById("storyText");
        const storyTextOverlay = document.getElementById("storyTextOverlay");
        const progressBar = document.getElementById("progressBar");
        const storyContent = document.getElementById("storyContent");

        function updateStoryMenu(story) {
            const menuBtn = document.getElementById('storyMenuBtn');
            const deleteMenuItem = document.querySelector('.story-menu-item.delete');
            const menu = document.getElementById('storyMenu');

            if (!menuBtn || !menu) return;

            const isOwnStory = story.user && story.user.id === currentUserId;

            menuBtn.style.display = isOwnStory ? 'flex' : 'none';

            if (deleteMenuItem) {
                deleteMenuItem.style.display = isOwnStory ? 'flex' : 'none';
            }

            menu.classList.remove('active');
        }

        function loadStory(index) {
            if (index < 0 || index >= stories.length) return;

            stopAndClearActiveVideo();
            cancelAnimationFrame(progressRAF);
            progressStartTime = null;
            pausedProgress = 0;

            currentIndex = index;
            const story = stories[index];

            console.log('Loading story:', story);

            updateStoryMenu(story);

            if (story.user && story.user.avatar_url) {
                currentAvatar.innerHTML = `<img src="${story.user.avatar_url}" alt="${story.user.name}">`;
            } else if (story.user) {
                currentAvatar.innerHTML = story.user.name.substring(0, 2).toUpperCase();
            }

            currentUsername.textContent = story.user?.name || '';
            currentTime.textContent = story.created_at || '';

            storyText.textContent = '';
            storyText.style.display = 'none';
            storyCaption.textContent = '';
            storyCaption.style.display = 'none';
            storyTextOverlay.style.display = 'flex';

            storyMedia.innerHTML = "";
            storyMedia.style.display = 'none';
            storyContent.classList.remove('has-media');
            storyContent.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';

            if (story.type === "text") {
                const bgGradient = story.background || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                storyContent.style.background = bgGradient;

                if (story.text_content && story.text_content.trim()) {
                    storyText.textContent = story.text_content;
                    storyText.style.display = 'block';
                    storyTextOverlay.style.display = 'flex';
                }

                if (story.caption && story.caption.trim()) {
                    storyCaption.textContent = story.caption;
                    storyCaption.style.display = 'block';
                }

                totalDuration = totalDurationDefault;
                renderProgressSegments();
                if (isPlaying) startProgress(totalDuration);
            } else if (story.type === "image" && story.media) {
                storyContent.classList.add('has-media');
                storyTextOverlay.style.display = 'none';

                const img = document.createElement('img');
                img.src = story.media;
                img.onload = () => {
                    totalDuration = totalDurationDefault;
                    renderProgressSegments();
                    if (isPlaying) startProgress(totalDuration);
                };
                storyMedia.appendChild(img);
                storyMedia.style.display = "flex";

                if (story.caption) {
                    storyCaption.textContent = story.caption;
                    storyCaption.style.display = 'block';
                }
            } else if (story.type === "video" && story.media) {
                storyContent.classList.add('has-media');
                storyTextOverlay.style.display = 'none';

                const video = document.createElement('video');
                video.src = story.media;
                video.muted = isMuted;
                video.playsInline = true;
                activeVideo = video;

                video.onloadedmetadata = () => {
                    totalDuration = Math.min(video.duration, 30) * 1000;
                    renderProgressSegments();
                    if (isPlaying) {
                        video.play().catch(() => {});
                        startProgress(totalDuration);
                    }
                };

                video.onended = () => nextStory();

                storyMedia.appendChild(video);
                storyMedia.style.display = "flex";

                if (story.caption) {
                    storyCaption.textContent = story.caption;
                    storyCaption.style.display = 'block';
                }
            }
        }

        function stopAndClearActiveVideo() {
            if (activeVideo) {
                try {
                    activeVideo.pause();
                    activeVideo.removeAttribute('src');
                    activeVideo.load();
                } catch (e) {}
                activeVideo = null;
            }
        }

        function renderProgressSegments() {
            progressBar.innerHTML = "";
            for (let i = 0; i < stories.length; i++) {
                const seg = document.createElement('div');
                seg.className = 'progress-segment';
                const fill = document.createElement('div');
                fill.className = 'progress-fill';

                if (i < currentIndex) {
                    seg.classList.add('viewed');
                    fill.style.width = '100%';
                } else if (i === currentIndex) {
                    seg.classList.add('active');
                }

                seg.appendChild(fill);
                progressBar.appendChild(seg);
            }
        }

        function startProgress(time = totalDuration) {
            const activeFill = document.querySelector('.progress-segment.active .progress-fill');
            if (!activeFill) return;

            let start = null;

            function step(timestamp) {
                if (!start) start = timestamp;
                if (!progressStartTime) progressStartTime = start;

                const elapsed = timestamp - progressStartTime;
                const percent = Math.min((elapsed / time) * 100, 100);
                activeFill.style.width = percent + '%';

                if (percent < 100 && isPlaying) {
                    progressRAF = requestAnimationFrame(step);
                } else if (percent >= 100) {
                    nextStory();
                }
            }

            progressRAF = requestAnimationFrame(step);
        }

        function nextStory() {
            if (currentIndex < stories.length - 1) {
                currentIndex++;
                loadStory(currentIndex);
            } else {
                closeStory();
            }
        }

        function prevStory() {
            if (currentIndex > 0) {
                currentIndex--;
                loadStory(currentIndex);
            }
        }

        function closeStory() {
            stopAndClearActiveVideo();
            window.location.href = "{{ route('homepage') }}";
        }

        function togglePlay() {
            isPlaying = !isPlaying;
            document.getElementById("playBtn").className = isPlaying ? 'fas fa-pause' : 'fas fa-play';

            if (activeVideo) {
                isPlaying ? activeVideo.play() : activeVideo.pause();
            }

            if (isPlaying) {
                startProgress();
            } else {
                cancelAnimationFrame(progressRAF);
            }
        }

        function toggleMute() {
            isMuted = !isMuted;
            if (activeVideo) activeVideo.muted = isMuted;
            document.getElementById('muteBtn').className = isMuted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
        }

        function toggleMenu() {
            document.getElementById('storyMenu').classList.toggle('active');
        }

        function likeStory() {
            console.log('Story liked');
        }

        function muteStoryUser() {
            const story = stories[currentIndex];
            if (confirm(`Mute stories from ${story.user.name}?`)) {
                document.getElementById('storyMenu').classList.remove('active');
                fetch('/stories/mute-user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            user_id: story.user.id
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            alert('Story muted!');
                            closeStory();
                        }
                    });
            }
        }

        function deleteStory() {
            const story = stories[currentIndex];
            if (confirm('Delete this story?')) {
                document.getElementById('storyMenu').classList.remove('active');
                fetch('/stories/destroy', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            id: story.id
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            stories.splice(currentIndex, 1);
                            if (stories.length === 0) {
                                closeStory();
                            } else {
                                if (currentIndex >= stories.length) currentIndex--;
                                loadStory(currentIndex);
                            }
                        }
                    });
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.story-btn') && !e.target.closest('.story-menu-dropdown')) {
                document.getElementById('storyMenu').classList.remove('active');
            }
        });

        initPopupState();
        loadStory(currentIndex);
    </script>
</body>

</html>