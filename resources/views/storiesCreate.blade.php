<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buat Story Baru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #1da1f2;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f8fa;
            padding: 20px;
        }

        .main-content {
            max-width: 900px;
            margin: 0 auto;
            animation: fadeIn 0.6s ease-out;
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

        .story-header {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .story-type-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .type-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .type-option {
            border: 2px solid rgba(29, 161, 242, 0.1);
            border-radius: 16px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            background: rgba(29, 161, 242, 0.02);
        }

        .type-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(29, 161, 242, 0.15);
        }

        .type-option.active {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(29, 161, 242, 0.1), rgba(29, 161, 242, 0.05));
        }

        .type-option.active::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        .type-icon {
            font-size: 32px;
            margin-bottom: 12px;
            color: var(--primary-color);
        }

        .type-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .type-description {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }

        .story-editor {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 25px;
            margin-bottom: 25px;
        }

        .editor-main {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .story-preview {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
        }

        .preview-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .story-simulator {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* FRAME STORY FIXED */
        .story-preview-frame {
            width: 100%;
            max-width: 260px;
            aspect-ratio: 9 / 16;
            background: #000;
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            transition: aspect-ratio 0.3s ease;
        }

        /* MEDIA DI DALAM FRAME */
        .story-preview-frame img,
        .story-preview-frame video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        /* Ketika aspect ratio dinamis */
        .story-preview-frame.dynamic {
            aspect-ratio: auto;
            max-height: 500px;
        }
        
        .story-preview-frame.dynamic img,
        .story-preview-frame.dynamic video {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* TEXT STORY */
        .story-preview-frame.text-story {
            background: var(--selected-bg, linear-gradient(135deg, #667eea, #764ba2));
        }

        .story-preview-frame .story-text-content {
            padding: 20px;
            color: white;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
            max-width: 100%;
            word-wrap: break-word;
        }

        .placeholder-content {
            opacity: 0.7;
            font-style: italic;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(29, 161, 242, 0.1);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(29, 161, 242, 0.02);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
            background: white;
        }

        .form-control.large {
            padding: 16px;
            font-size: 16px;
            min-height: 120px;
            resize: vertical;
        }

        .file-upload-area {
            border: 2px dashed rgba(29, 161, 242, 0.3);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            background: rgba(29, 161, 242, 0.02);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(29, 161, 242, 0.05);
        }

        .upload-icon {
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .upload-text {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .upload-hint {
            font-size: 14px;
            color: #666;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            top: 0;
            left: 0;
        }

        .background-picker {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .bg-option {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .bg-option:hover {
            transform: scale(1.1);
        }

        .bg-option.active {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(29, 161, 242, 0.3);
        }

        .privacy-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .privacy-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .privacy-option {
            background: rgba(29, 161, 242, 0.02);
            border: 2px solid rgba(29, 161, 242, 0.1);
            border-radius: 16px;
            padding: 18px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .privacy-option:hover {
            background: rgba(29, 161, 242, 0.05);
            border-color: rgba(29, 161, 242, 0.3);
            transform: translateX(3px);
        }

        .privacy-option.active {
            background: linear-gradient(135deg, rgba(29, 161, 242, 0.15), rgba(29, 161, 242, 0.08));
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(29, 161, 242, 0.2);
        }

        .privacy-info {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }

        .privacy-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: rgba(29, 161, 242, 0.1);
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .privacy-option.active .privacy-icon {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }

        .privacy-text h4 {
            color: #1a1a1a;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .privacy-text p {
            color: #666;
            font-size: 13px;
            line-height: 1.4;
        }

        .radio-check {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid rgba(29, 161, 242, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .privacy-option.active .radio-check {
            border-color: var(--primary-color);
            background: var(--primary-color);
        }

        .radio-check i {
            font-size: 12px;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .privacy-option.active .radio-check i {
            opacity: 1;
        }

        .close-friends-section {
            display: none;
            background: rgba(29, 161, 242, 0.03);
            border: 2px solid rgba(29, 161, 242, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-top: 15px;
            transition: all 0.4s ease;
        }

        .close-friends-section.show {
            display: block;
        }

        .friends-header {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .friends-header i {
            color: var(--primary-color);
        }

        .friend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 10px;
            background: white;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .friend-item:hover {
            background: rgba(29, 161, 242, 0.05);
            border-color: rgba(29, 161, 242, 0.2);
            transform: translateX(3px);
        }

        .friend-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .friend-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .friend-name {
            color: #1a1a1a;
            font-size: 15px;
            font-weight: 600;
        }

        .checkbox-wrapper {
            position: relative;
        }

        .checkbox-custom {
            width: 24px;
            height: 24px;
            border: 2px solid rgba(29, 161, 242, 0.3);
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .checkbox-custom.checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .checkbox-custom i {
            font-size: 12px;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .checkbox-custom.checked i {
            opacity: 1;
        }

        .close-friends-section::-webkit-scrollbar {
            width: 6px;
        }

        .close-friends-section::-webkit-scrollbar-track {
            background: rgba(29, 161, 242, 0.05);
            border-radius: 10px;
        }

        .close-friends-section::-webkit-scrollbar-thumb {
            background: rgba(29, 161, 242, 0.3);
            border-radius: 10px;
        }

        .close-friends-section::-webkit-scrollbar-thumb:hover {
            background: rgba(29, 161, 242, 0.5);
        }

        .story-actions {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #0d8bd9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(29, 161, 242, 0.4);
        }

        .btn-secondary {
            background: rgba(0, 0, 0, 0.05);
            color: #666;
            border: 2px solid rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .crop-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .crop-modal.active {
            display: flex;
        }

        .crop-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            max-width: 90%;
            max-height: 90vh;
            overflow: auto;
            position: relative;
        }

        .crop-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .crop-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .close-crop {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-crop:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(90deg);
        }

        .crop-image-container {
            max-width: 700px;
            max-height: 500px;
            margin: 0 auto 20px;
        }

        #cropImage {
            max-width: 100%;
            display: block;
        }

        .crop-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .crop-btn {
            padding: 10px 16px;
            border: 2px solid rgba(29, 161, 242, 0.2);
            background: rgba(29, 161, 242, 0.05);
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .crop-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .crop-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10001;
            background: #2ed573;
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 14px;
            animation: slideInRight 0.3s ease;
            max-width: 300px;
        }

        .toast-notification.error {
            background: #ff4757;
        }

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

        @media (max-width: 768px) {
            .story-editor {
                grid-template-columns: 1fr;
            }

            .story-preview {
                position: static;
            }

            .type-options {
                grid-template-columns: 1fr;
            }

            .story-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .crop-container {
                max-width: 95%;
                padding: 20px;
            }

            .crop-controls {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="story-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle" style="color: #ff6b6b;"></i>
                Buat Story Baru
            </h1>
        </div>

        <form id="storyForm">
            <div class="story-type-section">
                <h2 class="section-title">
                    <i class="fas fa-palette"></i>
                    Pilih Tipe Story
                </h2>
                <div class="type-options">
                    <div class="type-option active" data-type="text">
                        <div class="type-icon">
                            <i class="fas fa-font"></i>
                        </div>
                        <div class="type-title">Text Story</div>
                        <div class="type-description">Buat story dengan teks dan background menarik</div>
                    </div>
                    <div class="type-option" data-type="image">
                        <div class="type-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="type-title">Image Story</div>
                        <div class="type-description">Upload foto dengan caption (bisa di-crop)</div>
                    </div>
                    <div class="type-option" data-type="video">
                        <div class="type-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="type-title">Video Story</div>
                        <div class="type-description">Share video pendek yang menarik</div>
                    </div>
                </div>
                <input type="hidden" id="storyType" value="text">
            </div>

            <div class="story-editor">
                <div class="editor-main">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-pen"></i>
                            Caption Story
                        </label>
                        <input type="text" id="captionInput" class="form-control"
                            placeholder="Tulis caption untuk story Anda..." maxlength="255">
                    </div>

                    <div id="textStoryFields">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-quote-right"></i>
                                Text Content
                            </label>
                            <textarea id="textInput" class="form-control large"
                                placeholder="Tulis pesan Anda di sini..." maxlength="255"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-palette"></i>
                                Background
                            </label>
                            <input type="hidden" id="backgroundInput" value="linear-gradient(135deg, #667eea, #764ba2)">
                            <div class="background-picker">
                                <div class="bg-option active" data-bg="linear-gradient(135deg, #667eea, #764ba2)"
                                    style="background: linear-gradient(135deg, #667eea, #764ba2);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #ff6b6b, #feca57)"
                                    style="background: linear-gradient(135deg, #ff6b6b, #feca57);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #4ecdc4, #44a08d)"
                                    style="background: linear-gradient(135deg, #4ecdc4, #44a08d);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #a8edea, #fed6e3)"
                                    style="background: linear-gradient(135deg, #a8edea, #fed6e3);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #ff9a9e, #fecfef)"
                                    style="background: linear-gradient(135deg, #ff9a9e, #fecfef);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #a1c4fd, #c2e9fb)"
                                    style="background: linear-gradient(135deg, #a1c4fd, #c2e9fb);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #ffecd2, #fcb69f)"
                                    style="background: linear-gradient(135deg, #ffecd2, #fcb69f);"></div>
                                <div class="bg-option" data-bg="linear-gradient(135deg, #89f7fe, #66a6ff)"
                                    style="background: linear-gradient(135deg, #89f7fe, #66a6ff);"></div>
                            </div>
                        </div>
                    </div>

                    <div id="mediaStoryFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-upload"></i>
                                Upload Media
                            </label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <input type="file" id="mediaInput" class="file-input" accept="image/*,video/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Drag & drop file atau klik untuk upload</div>
                                <div class="upload-hint">Maksimal 10MB • JPG, PNG, MP4</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="story-preview">
                    <div class="preview-title">
                        <i class="fas fa-eye"></i>
                        Preview Story
                    </div>
                    <div class="story-simulator">
                        <div class="story-preview-frame text-story" id="previewFrame" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <div class="story-text-content">
                                <div class="placeholder-content">
                                    Tulis sesuatu untuk melihat preview...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="privacy-section">
                <h2 class="section-title">
                    <i class="fas fa-shield-alt"></i>
                    Pengaturan Privasi
                </h2>
                <div class="privacy-options">
                    <div class="privacy-option active" onclick="selectPrivacy('everyone')">
                        <div class="privacy-info">
                            <div class="privacy-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="privacy-text">
                                <h4>Publik</h4>
                                <p>Semua orang dapat melihat story ini</p>
                            </div>
                        </div>
                        <div class="radio-check">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>

                    <div class="privacy-option" onclick="selectPrivacy('close-friends')">
                        <div class="privacy-info">
                            <div class="privacy-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <div class="privacy-text">
                                <h4>Teman Dekat</h4>
                                <p>Hanya teman terpilih yang dapat melihat</p>
                            </div>
                        </div>
                        <div class="radio-check">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>

                    <div class="privacy-option" onclick="selectPrivacy('private')">
                        <div class="privacy-info">
                            <div class="privacy-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="privacy-text">
                                <h4>Hanya Saya</h4>
                                <p>Hanya Anda yang dapat melihat story ini</p>
                            </div>
                        </div>
                        <div class="radio-check">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>

                <div class="close-friends-section" id="closeFriendsSection">
                    <div class="friends-header">
                        <i class="fas fa-users"></i>
                        Pilih Teman Dekat
                    </div>
                    <div id="friendsList"></div>
                </div>
            </div>
            <input type="hidden" id="privacyInput" value="everyone">

            <div class="story-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" id="publishBtn">
                    <i class="fas fa-paper-plane"></i>
                    Publikasikan Story
                </button>
            </div>
        </form>
    </div>

    <div class="crop-modal" id="cropModal">
        <div class="crop-container">
            <div class="crop-header">
                <h3 class="crop-title">
                    <i class="fas fa-crop"></i>
                    Crop Gambar
                </h3>
                <button class="close-crop" id="closeCrop">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="crop-image-container">
                <img id="cropImage" src="" alt="Crop Image">
            </div>

            <div class="crop-controls">
                <button type="button" class="crop-btn" onclick="setRatio(NaN)">Free</button>
                <button type="button" class="crop-btn" onclick="setRatio(1)">1:1</button>
                <button type="button" class="crop-btn" onclick="setRatio(4/5)">4:5</button>
                <button type="button" class="crop-btn" onclick="setRatio(9/16)">9:16</button>
                <button type="button" class="crop-btn" onclick="setRatio(16/9)">16:9</button>
                <button type="button" class="crop-btn" id="zoomIn">+</button>
                <button type="button" class="crop-btn" id="zoomOut">-</button>
                <button type="button" class="crop-btn" id="rotateLeft">⟲</button>
                <button type="button" class="crop-btn" id="rotateRight">⟳</button>
            </div>

            <div class="crop-actions">
                <button type="button" class="btn btn-secondary" id="cancelCrop">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-secondary" id="skipCrop">
                    <i class="fas fa-forward"></i>
                    Skip Crop
                </button>
                <button type="button" class="btn btn-primary" id="applyCrop">
                    <i class="fas fa-check"></i>
                    Terapkan Crop
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        let currentType = 'text';
        let selectedBackground = 'linear-gradient(135deg, #667eea, #764ba2)';
        let cropper = null;
        let originalImageFile = null;
        let croppedImageBlob = null;
        let selectedPrivacy = 'everyone';
        let selectedFriends = new Set();

        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            loadFriendsList();
        });

        function loadFriendsList() {
            let followers = [];

            try {
                followers = @json($followers ?? []);
            } catch (e) {
                console.log('No followers data available');
                followers = [];
            }

            const list = document.getElementById('friendsList');

            if (followers.length === 0) {
                list.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">Belum ada followers yang dapat dipilih</div>';
                return;
            }

            list.innerHTML = followers.map(f => `
                <div class="friend-item" onclick="toggleFriend(${f.id})">
                    <div class="friend-info">
                        <div class="friend-avatar">${(f.name || f.username || 'U').charAt(0).toUpperCase()}</div>
                        <div class="friend-name">${f.name || f.username || 'Unknown'}</div>
                    </div>
                    <div class="checkbox-wrapper">
                        <div class="checkbox-custom" id="checkbox-${f.id}">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function setupEventListeners() {
            document.getElementById('storyForm').addEventListener('submit', handleFormSubmit);
            document.getElementById('mediaInput').addEventListener('change', handleFileSelect);

            document.querySelectorAll('.type-option').forEach(option => {
                option.addEventListener('click', function() {
                    selectStoryType(this.dataset.type);
                });
            });

            document.getElementById('textInput').addEventListener('input', updateTextPreview);

            document.querySelectorAll('.bg-option').forEach(option => {
                option.addEventListener('click', function() {
                    selectBackground(this.dataset.bg);
                });
            });

            document.getElementById('closeCrop').addEventListener('click', closeCropModal);
            document.getElementById('cancelCrop').addEventListener('click', closeCropModal);
            document.getElementById('skipCrop').addEventListener('click', skipCrop);
            document.getElementById('applyCrop').addEventListener('click', applyCrop);
            document.getElementById('zoomIn').addEventListener('click', () => cropper && cropper.zoom(0.1));
            document.getElementById('zoomOut').addEventListener('click', () => cropper && cropper.zoom(-0.1));
            document.getElementById('rotateLeft').addEventListener('click', () => cropper && cropper.rotate(-45));
            document.getElementById('rotateRight').addEventListener('click', () => cropper && cropper.rotate(45));
        }

        function selectStoryType(type) {
            currentType = type;
            document.getElementById('storyType').value = type;

            document.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('active'));
            event.currentTarget.classList.add('active');

            const previewFrame = document.getElementById('previewFrame');

            if (type === 'text') {
                document.getElementById('textStoryFields').style.display = 'block';
                document.getElementById('mediaStoryFields').style.display = 'none';
                
                // Reset ke text story
                previewFrame.className = 'story-preview-frame text-story';
                previewFrame.style.background = selectedBackground;
                updateTextPreview();
            } else {
                document.getElementById('textStoryFields').style.display = 'none';
                document.getElementById('mediaStoryFields').style.display = 'block';
                
                // Reset ke media story
                previewFrame.className = 'story-preview-frame';
                previewFrame.style.background = '#000';
                previewFrame.innerHTML = '<div class="placeholder-content" style="color: #999;">Upload gambar atau video...</div>';
            }
        }

        function updateTextPreview() {
            const text = document.getElementById('textInput').value;
            const frame = document.getElementById('previewFrame');

            if (text.trim()) {
                frame.innerHTML = `<div class="story-text-content">${text}</div>`;
            } else {
                frame.innerHTML = '<div class="story-text-content"><div class="placeholder-content">Tulis sesuatu untuk melihat preview...</div></div>';
            }
        }

        function selectBackground(bg) {
            selectedBackground = bg;
            document.getElementById('backgroundInput').value = bg;

            document.querySelectorAll('.bg-option').forEach(opt => opt.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Update background preview
            const frame = document.getElementById('previewFrame');
            if (currentType === 'text') {
                frame.style.background = bg;
            }
        }

        function selectPrivacy(type) {
            selectedPrivacy = type;
            document.getElementById('privacyInput').value = type;

            document.querySelectorAll('.privacy-option').forEach(op => {
                op.classList.remove('active');
            });
            const clicked = document.querySelector(`.privacy-option[onclick*="${type}"]`);
            if (clicked) clicked.classList.add('active');

            const closeFriendsSection = document.getElementById('closeFriendsSection');

            if (type === 'close-friends') {
                closeFriendsSection.classList.add('show');
                loadFriendsList();
            } else {
                closeFriendsSection.classList.remove('show');
                selectedFriends.clear();
                document.querySelectorAll('.checkbox-custom').forEach(cb => {
                    cb.classList.remove('checked');
                });
            }
        }

        function toggleFriend(id) {
            const checkbox = document.getElementById('checkbox-' + id);

            if (selectedFriends.has(id)) {
                selectedFriends.delete(id);
                checkbox.classList.remove('checked');
            } else {
                selectedFriends.add(id);
                checkbox.classList.add('checked');
            }
        }

        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) return;

            originalImageFile = file;

            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    document.getElementById('cropImage').src = evt.target.result;
                    document.getElementById('cropModal').classList.add('active');
                    initCropper();
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith("video/")) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const frame = document.getElementById('previewFrame');
                    const video = document.createElement('video');
                    
                    video.onloadedmetadata = function() {
                        const aspectRatio = video.videoWidth / video.videoHeight;
                        
                        // Jika landscape atau square, gunakan dynamic frame
                        if (aspectRatio >= 1) {
                            frame.classList.add('dynamic');
                            frame.style.aspectRatio = 'auto';
                        } else {
                            // Portrait - gunakan 9:16
                            frame.classList.remove('dynamic');
                            frame.style.aspectRatio = '9 / 16';
                        }
                        
                        frame.innerHTML = `<video src="${evt.target.result}" class="uploaded-media" controls muted autoplay loop></video>`;
                    };
                    
                    video.src = evt.target.result;
                };
                reader.readAsDataURL(file);
                croppedImageBlob = file;
            }
        }

        function initCropper() {
            const image = document.getElementById('cropImage');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(image, {
                aspectRatio: 9 / 16,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }

        function closeCropModal() {
            document.getElementById('cropModal').classList.remove('active');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            document.getElementById('mediaInput').value = '';
        }

        function skipCrop() {
            croppedImageBlob = originalImageFile;

            const reader = new FileReader();
            reader.onload = function(evt) {
                const frame = document.getElementById('previewFrame');
                const img = new Image();
                
                img.onload = function() {
                    const aspectRatio = img.width / img.height;
                    
                    // Jika landscape atau square, gunakan dynamic frame
                    if (aspectRatio >= 1) {
                        frame.classList.add('dynamic');
                        frame.style.aspectRatio = 'auto';
                    } else {
                        // Portrait - gunakan 9:16
                        frame.classList.remove('dynamic');
                        frame.style.aspectRatio = '9 / 16';
                    }
                    
                    frame.innerHTML = `<img src="${evt.target.result}" alt="Preview">`;
                };
                
                img.src = evt.target.result;
            };
            reader.readAsDataURL(originalImageFile);

            closeCropModal();
        }

        function applyCrop() {
            if (!cropper) return;

            const cropData = cropper.getData();
            const aspectRatio = cropData.width / cropData.height;

            cropper.getCroppedCanvas({
                maxWidth: 1080,
                maxHeight: 1920,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                croppedImageBlob = blob;

                const reader = new FileReader();
                reader.onload = function(evt) {
                    const frame = document.getElementById('previewFrame');
                    
                    // Jika landscape atau square, gunakan dynamic frame
                    if (aspectRatio >= 1) {
                        frame.classList.add('dynamic');
                        frame.style.aspectRatio = 'auto';
                    } else if (aspectRatio > 0.5 && aspectRatio < 0.6) {
                        // 9:16 portrait
                        frame.classList.remove('dynamic');
                        frame.style.aspectRatio = '9 / 16';
                    } else {
                        // Portrait lainnya
                        frame.classList.remove('dynamic');
                        frame.style.aspectRatio = aspectRatio;
                    }
                    
                    frame.innerHTML = `<img src="${evt.target.result}" alt="Cropped Preview">`;
                };
                reader.readAsDataURL(blob);

                closeCropModal();
                showNotification('Gambar berhasil di-crop!');
            }, 'image/jpeg', 0.95);
        }

        function setRatio(ratio) {
            if (!cropper) return;

            cropper.setAspectRatio(ratio);

            const frame = document.getElementById("previewFrame");

            // Update preview frame aspect ratio
            if (!ratio || isNaN(ratio)) {
                // Free crop - akan di-set saat apply
                frame.classList.add('dynamic');
                frame.style.aspectRatio = 'auto';
            } else if (ratio >= 1) {
                // Landscape atau square
                frame.classList.add('dynamic');
                frame.style.aspectRatio = 'auto';
            } else {
                // Portrait
                frame.classList.remove('dynamic');
                frame.style.aspectRatio = ratio;
            }
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const publishBtn = document.getElementById('publishBtn');
            const originalText = publishBtn.innerHTML;

            publishBtn.disabled = true;
            publishBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
            publishBtn.style.pointerEvents = 'none';

            const caption = document.getElementById('captionInput').value;
            const type = currentType;

            let formData = new FormData();
            formData.append('type', type);
            formData.append('privacy', selectedPrivacy);

            if (type === 'text') {
                const textContent = document.getElementById('textInput').value;
                const background = selectedBackground;

                if (!textContent.trim()) {
                    showNotification("Tulis teks untuk story!", 'error');
                    resetButton();
                    return;
                }

                formData.append('text_content', textContent);
                formData.append('background', background);

                if (caption && caption.trim()) {
                    formData.append('caption', caption);
                }
            } else {
                if (!croppedImageBlob && !originalImageFile) {
                    showNotification("Pilih gambar atau video terlebih dahulu!", 'error');
                    resetButton();
                    return;
                }

                formData.append('caption', caption);
                formData.append('media', croppedImageBlob || originalImageFile);
            }

            if (selectedPrivacy === "close-friends") {
                if (selectedFriends.size === 0) {
                    showNotification("Pilih minimal 1 teman untuk close friends!", 'error');
                    resetButton();
                    return;
                }

                selectedFriends.forEach(id => {
                    formData.append('close_friends[]', id);
                });
            }

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]');

                if (!csrf) {
                    showNotification("CSRF token tidak ditemukan!", "error");
                    resetButton();
                    return;
                }

                const response = await fetch("{{ route('stories.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf.content,
                        "Accept": "application/json",
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errData = await response.json();
                    throw new Error(errData.message || "Upload gagal");
                }

                const result = await response.json();

                if (result.success) {
                    showNotification("Story berhasil diupload!");
                    setTimeout(() => {
                        window.location.href = "{{ route('stories') }}";
                    }, 1000);
                } else {
                    showNotification(result.message || "Gagal upload story!", "error");
                    resetButton();
                }

            } catch (err) {
                console.error(err);
                showNotification("Error: " + err.message, "error");
                resetButton();
            }

            function resetButton() {
                publishBtn.disabled = false;
                publishBtn.innerHTML = originalText;
                publishBtn.style.pointerEvents = 'auto';
            }
        }

        function showNotification(msg, type = "success") {
            const notif = document.createElement("div");
            notif.className = `toast-notification ${type}`;
            notif.innerText = msg;

            document.body.appendChild(notif);

            setTimeout(() => notif.remove(), 3000);
        }
    </script>

</body>

</html>