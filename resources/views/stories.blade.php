<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .main-content {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
        }

        .story-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
        }

        .story-viewer {
            position: relative;
            width: min(390px, 100vw);
            height: min(720px, 100vh);
            background: linear-gradient(145deg, #1e1e2e, #2a2a40);
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.7),
                0 10px 20px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
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
            backdrop-filter: blur(10px);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #ff8e53, #ff6b6b);
            background-size: 200% 100%;
            width: 0%;
            border-radius: 6px;
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

        .progress-segment.active .progress-fill {
            width: 100%;
        }

        .progress-segment.viewed .progress-fill {
            width: 100%;
            transition: none;
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
            border: 3px solid transparent;
            background: linear-gradient(145deg, #ff6b6b, #4ecdc4) padding-box,
                linear-gradient(145deg, #ff6b6b, #4ecdc4) border-box;
            overflow: hidden;
            position: relative;
        }

        .story-avatar::before {
            content: '';
            position: absolute;
            inset: 3px;
            border-radius: 50%;
            background: linear-gradient(145deg, #2a2a40, #1e1e2e);
            z-index: -1;
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
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .story-controls {
            display: flex;
            gap: 12px;
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
            backdrop-filter: blur(10px);
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

        .story-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(255, 107, 107, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(78, 205, 196, 0.3) 0%, transparent 50%);
            pointer-events: none;
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
            /* Black background instead of gradient */
        }

        .story-media img,
        .story-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* This fills the entire area */
            object-position: center;
        }

        /* Remove the gradient overlay when there's media */
        .story-content.has-media::before {
            display: none;
        }

        .story-text-overlay {
            position: absolute;
            bottom: 140px;
            left: 24px;
            right: 24px;
            color: white;
            text-align: center;
            z-index: 10;
        }

        .story-text {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.8);
            line-height: 1.2;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .story-caption {
            font-size: 18px;
            opacity: 0.9;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
            font-weight: 500;
        }

        .story-navigation {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 10;
            transition: background 0.2s;
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
            backdrop-filter: blur(20px);
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
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
        }

        .story-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.3);
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
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }

        .story-action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
        }

        .story-thumbnails {
            position: absolute;
            bottom: 90px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 30;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .story-thumbnail {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .story-thumbnail::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.1));
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .story-thumbnail:hover::before {
            opacity: 1;
        }

        .story-thumbnail.active {
            border-color: #ff6b6b;
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }

        .story-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Loading animation */
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #ff6b6b;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Enhanced animations */
        .story-viewer {
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Pulse effect for like button */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        .story-action-btn.liked {
            animation: pulse 0.3s ease-in-out;
            background: linear-gradient(135deg, #ff4757, #ff3742);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .story-text {
                font-size: 28px;
            }

            .story-caption {
                font-size: 16px;
            }

            .story-header {
                padding: 10px 14px;
            }

            .story-actions {
                bottom: 20px;
                left: 20px;
                right: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="story-container">
        <div class="story-viewer">
            <button class="close-story" onclick="closeStory()">
                <i class="fas fa-times"></i>
            </button>

            <div class="story-progress-bar" id="progressBar">
            </div>

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
                </div>
            </div>

            <div class="story-content" id="storyContent">
                <div class="loading-spinner" id="loadingSpinner" style="display: none;">
                    <div class="spinner"></div>
                </div>
                <div class="story-media" id="storyMedia" style="display: none;"></div>
                <div class="story-text-overlay">
                    <!-- <div class="story-text" id="storyText">Welcome to Stories</div> -->
                    <div class="story-caption" id="storyCaption">Tap to explore amazing content</div>
                </div>
            </div>

            <button class="story-navigation prev-story" onclick="prevStory()"></button>
            <button class="story-navigation next-story" onclick="nextStory()"></button>

            <div class="story-actions">
                <input type="text" class="story-input" placeholder="Send message" />
                <button class="story-action-btn" onclick="likeStory()">
                    <i class="fas fa-heart" id="likeIcon"></i>
                </button>
                <button class="story-action-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    @php
    $storiesData = $stories
    ->sortBy('created_at')
    ->values()
    ->map(function($story) {
    return [
    'id' => $story->id_story,
    'type' => $story->type,
    'media' => $story->media ? asset('storage/'.$story->media) : null,
    'caption' => $story->caption,
    'created_at' => $story->created_at->diffForHumans(), // 👈 ini
    'user' => [
    'id' => $story->user->id,
    'name' => $story->user->name,
    'avatar_url' => $story->user->profile_photo
    ? asset('storage/'.$story->user->profile_photo)
    : null,
    ],
    ];
    });
    @endphp



    <script>
        const stories = @json($storiesData);
        let currentIndex = 0;
        let currentSegment = 0;
        let isPlaying = true;
        let progressTimer;
        const duration = 5000; // 5 detik default untuk image

        const storyMedia = document.getElementById("storyMedia");
        const currentAvatar = document.getElementById("currentAvatar");
        const currentUsername = document.getElementById("currentUsername");
        const currentTime = document.getElementById("currentTime");
        const storyCaption = document.getElementById("storyCaption");
        const progressBar = document.getElementById("progressBar");

        function loadStory(index) {
            if (index < 0 || index >= stories.length) return;

            const story = stories[index];
            const storyContent = document.getElementById("storyContent");

            // Reset progress
            cancelAnimationFrame(progressTimer);
            startTime = null;
            pausedTime = 0;

            // Avatar
            if (story.user.avatar_url) {
                currentAvatar.innerHTML = `<img src="${story.user.avatar_url}" alt="${story.user.name}">`;
            } else {
                currentAvatar.innerHTML = story.user.name.substring(0, 2).toUpperCase();
            }

            currentUsername.textContent = story.user.name;
            currentTime.textContent = story.created_at;
            storyCaption.textContent = story.caption ?? "";

            // Display media
            storyMedia.innerHTML = "";
            if (story.type === "image" && story.media) {
                // Add class to remove gradient background
                storyContent.classList.add('has-media');

                const img = document.createElement('img');
                img.src = story.media;
                img.alt = "Story image";
                img.style.maxWidth = "100%";
                img.style.maxHeight = "100%";
                img.style.width = "auto";
                img.style.height = "auto";
                img.style.objectFit = "contain"; // biar proporsinya asli
                img.style.objectPosition = "center";

                storyMedia.appendChild(img);
                storyMedia.style.display = "flex"; // Changed from block to flex

                img.onload = () => {
                    totalDuration = duration;
                    if (isPlaying) startProgress();
                };
            } else if (story.type === "video" && story.media) {
                // Add class to remove gradient background
                storyContent.classList.add('has-media');

                const video = document.createElement("video");
                video.src = story.media;
                video.autoplay = isPlaying;
                video.muted = isMuted;
                video.playsInline = true;
                video.style.width = "100%";
                video.style.height = "100%";
                video.style.objectFit = "cover";
                video.style.objectPosition = "center";

                video.onloadedmetadata = () => {
                    totalDuration = video.duration * 1000;
                    if (isPlaying) {
                        video.play();
                        startProgress();
                    }
                };

                storyMedia.appendChild(video);
                storyMedia.style.display = "flex"; // Changed from block to flex
            } else {
                // Remove class if no media
                storyContent.classList.remove('has-media');
                storyMedia.style.display = "none";
                totalDuration = duration;
                if (isPlaying) startProgress();
            }

            updateProgressBar(stories.length);
        }

        function updateProgressBar(totalSegments) {
            progressBar.innerHTML = "";

            for (let i = 0; i < totalSegments; i++) {
                const segment = document.createElement("div");
                segment.className = "progress-segment";

                if (i < currentIndex) {
                    segment.classList.add("viewed");
                } else if (i === currentIndex) {
                    segment.classList.add("active");
                }

                const fill = document.createElement("div");
                fill.className = "progress-fill";
                segment.appendChild(fill);

                progressBar.appendChild(segment);
            }
        }

        function startProgress(time = duration) {
            cancelAnimationFrame(progressTimer);

            const activeSegment = document.querySelector(
                ".progress-segment.active .progress-fill"
            );
            if (!activeSegment) return;

            let start = null;

            function step(timestamp) {
                if (!start) start = timestamp;
                const progress = timestamp - start;
                const percent = Math.min((progress / time) * 100, 100);
                activeSegment.style.width = percent + "%";

                if (percent < 100 && isPlaying) {
                    progressTimer = requestAnimationFrame(step);
                } else if (percent >= 100) {
                    nextStory();
                }
            }
            progressTimer = requestAnimationFrame(step);
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
            window.location.href = "{{ route('homepage') }}";
        }

        // klik kanan / kiri buat navigasi
        document.getElementById("storyContent").addEventListener("click", (e) => {
            const x = e.clientX;
            if (x < window.innerWidth / 2) {
                prevStory();
            } else {
                nextStory();
            }
        });

        // mulai pertama kali
        loadStory(currentIndex);

        let isMuted = false; // tambahin variabel global
        let totalDuration = duration;
        let activeVideo = null; // biar tau kalau sedang ada video

        function togglePlay() {
            isPlaying = !isPlaying;

            const playBtn = document.getElementById("playBtn");

            if (isPlaying) {
                playBtn.classList.remove("fa-play");
                playBtn.classList.add("fa-pause");

                // resume progress
                startProgress(totalDuration);

                // resume video kalau ada
                if (activeVideo) activeVideo.play();
            } else {
                playBtn.classList.remove("fa-pause");
                playBtn.classList.add("fa-play");

                // stop progress
                cancelAnimationFrame(progressTimer);

                // pause video kalau ada
                if (activeVideo) activeVideo.pause();
            }
        }
    </script>

</body>

</html>