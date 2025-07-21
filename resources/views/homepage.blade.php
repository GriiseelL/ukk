@extends('layout.layarUtama')

@section('title', 'Beranda - SocialApp')

@section('content')
    <!-- Compose Box (Desktop) -->
    {{-- <div class="compose-box desktop-only">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="d-flex">
                <img src="https://via.placeholder.com/48" alt="Avatar" class="post-avatar me-3">
                <div class="flex-grow-1">
                    <textarea class="form-control border-0" name="content" rows="3" placeholder="Apa yang sedang terjadi?"
                        required></textarea>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-sm btn-light me-2"
                                onclick="document.getElementById('imageInput').click()">
                                <i class="far fa-image"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light me-2">
                                <i class="far fa-smile"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light">
                                <i class="fas fa-map-marker-alt"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Tweet</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Posts -->
    <div class="posts-container">
        <div class="text-center py-5">
            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada tweet</h5>
            <p class="text-muted">Mulai mengikuti orang untuk melihat tweet mereka disini</p>
        </div>
    </div> --}}

    <script>
        const token = localStorage.getItem("jwt_token");
        const user = JSON.parse(localStorage.getItem("user") || "{}");

        if (!token) {
            // Belum login
            window.location.href = "/";
        } else {
            // Token ada → coba validasi ke server
            fetch("/api/check", {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Accept": "application/json"
                }
            })
                .then(res => {
                    if (!res.ok) throw new Error("Token tidak valid");
                    return res.json();
                })
                .then(data => {
                    console.log("Token valid, user info:", data);
                    // tampilkan halaman
                })
                .catch(err => {
                    console.error(err);
                    localStorage.removeItem("jwt_token");
                    localStorage.removeItem("user");
                    window.location.href = "/";
                });
        }

    </script>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block"></div>
                <div class="col-lg-6 col-12">
                    <!-- Beranda Section -->
                    <div id="beranda-section">
                        <!-- Stories (Both Mobile & Desktop) -->
                        <div class="story-container">
                            <div class="d-flex">
                                <div class="story-item">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=1" alt="Story" />
                                    </div>
                                    <small>Kamu</small>
                                </div>
                                <div class="story-item">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=2" alt="Story" />
                                    </div>
                                    <small>Andi</small>
                                </div>
                                <div class="story-item">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=3" alt="Story" />
                                    </div>
                                    <small>Sari</small>
                                </div>
                                <div class="story-item">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=4" alt="Story" />
                                    </div>
                                    <small>Budi</small>
                                </div>
                                <div class="story-item d-none d-md-block">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=5" alt="Story" />
                                    </div>
                                    <small>Maria</small>
                                </div>
                                <div class="story-item d-none d-md-block">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=6" alt="Story" />
                                    </div>
                                    <small>Rudi</small>
                                </div>
                                <div class="story-item d-none d-lg-block">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=7" alt="Story" />
                                    </div>
                                    <small>Dewi</small>
                                </div>
                                <div class="story-item d-none d-lg-block">
                                    <div class="story-avatar">
                                        <img src="https://picsum.photos/60/60?random=8" alt="Story" />
                                    </div>
                                    <small>Agus</small>
                                </div>
                            </div>
                        </div>

                        <!-- Compose Box (Desktop) -->
                        <div class="compose-box desktop-only">
                            <div class="d-flex">
                                <img src="https://picsum.photos/48/48?random=0" alt="Avatar" class="post-avatar me-3" />
                                <div class="flex-grow-1">
                                    <textarea class="form-control border-0" rows="3"
                                        placeholder="Apa yang sedang terjadi?"></textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <button class="btn btn-sm btn-light me-2">
                                                <i class="far fa-image"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light me-2">
                                                <i class="far fa-smile"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                        </div>
                                        <button class="btn btn-primary btn-sm px-3">Tweet</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Posts -->
                        <div class="posts-container">
                            <!-- Post 1 -->
                            <div class="post-card card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <img src="https://picsum.photos/48/48?random=5" alt="Avatar"
                                            class="post-avatar me-3" />
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <strong class="me-2">Sarah Johnson</strong>
                                                <span class="text-muted">@sarah_j</span>
                                                <span class="text-muted ms-2">• 2j</span>
                                            </div>
                                            <p class="mb-3">
                                                Hari ini cuaca sangat cerah! Sempurna untuk
                                                jalan-jalan di taman. 🌞
                                            </p>
                                            <img src="https://picsum.photos/500/300?random=10" alt="Post"
                                                class="post-image mb-3" />
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-sm">
                                                    <i class="far fa-comment"></i> 12
                                                </button>
                                                <button class="btn btn-sm">
                                                    <i class="fas fa-retweet"></i> 5
                                                </button>
                                                <button class="btn btn-sm">
                                                    <i class="far fa-heart"></i> 48
                                                </button>
                                                <button class="btn btn-sm">
                                                    <i class="fas fa-share"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Post 2 -->
                            <div class="post-card card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <img src="https://picsum.photos/48/48?random=6" alt="Avatar"
                                            class="post-avatar me-3" />
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <strong class="me-2">Tech News</strong>
                                                <span class="text-muted">@technews</span>
                                                <span class="text-muted ms-2">• 4j</span>
                                            </div>
                                            <p class="mb-3">
                                                Update teknologi terbaru: AI semakin canggih dan
                                                membantu kehidupan sehari-hari. Bagaimana pendapat
                                                kalian? 🤖
                                            </p>
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-sm btn-light">
                                                    <i class="far fa-comment"></i> 28
                                                </button>
                                                <button class="btn btn-sm btn-light">
                                                    <i class="fas fa-retweet"></i> 15
                                                </button>
                                                <button class="btn btn-sm btn-light">
                                                    <i class="far fa-heart"></i> 92
                                                </button>
                                                <button class="btn btn-sm btn-light">
                                                    <i class="fas fa-share"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Post 3 -->
                            <div class="post-card card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <img src="https://picsum.photos/48/48?random=7" alt="Avatar"
                                            class="post-avatar me-3" />
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <strong class="me-2">Food Lover</strong>
                                                <span class="text-muted">@foodie</span>
                                                <span class="text-muted ms-2">• 6j</span>
                                            </div>
                                            <p class="mb-3">
                                                Makan siang hari ini: nasi gudeg yang lezat! 🍛 Ada
                                                yang punya rekomendasi tempat makan gudeg enak?
                                            </p>
                                            <img src="https://picsum.photos/500/300?random=11" alt="Post"
                                                class="post-image mb-3" />
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-sm btn-light">
                                                    <i class="far fa-comment"></i> 8
                                                </button>
                                                <button class="btn btn-sm btn-light">
                                                    <i class="fas fa-retweet"></i> 3
                                                </button>
                                                <button class="btn btn-sm btn-light">
                                                    <i class="far fa-heart"></i> 35
                                                </button>
                                                <button class="btn btn-sm btn-light">
                                                    <i class="far fa-share"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jelajah Section -->
                    <div id="jelajah-section" style="display: none">
                        <div class="explore-grid">
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=20" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=21" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=22" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=23" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=24" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=25" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=26" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=27" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=28" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=29" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=30" alt="Explore" />
                            </div>
                            <div class="explore-item">
                                <img src="https://picsum.photos/200/200?random=31" alt="Explore" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (Desktop) -->
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="trending-sidebar">
                        <h5>Trending untuk Anda</h5>
                        <div class="trending-item py-2">
                            <div class="text-muted small">Trending di Indonesia</div>
                            <div class="fw-bold">#TechUpdate</div>
                            <div class="text-muted small">25.5K Tweet</div>
                        </div>
                        <div class="trending-item py-2">
                            <div class="text-muted small">Trending</div>
                            <div class="fw-bold">#MakananNusantara</div>
                            <div class="text-muted small">18.2K Tweet</div>
                        </div>
                        <div class="trending-item py-2">
                            <div class="text-muted small">Trending di Jakarta</div>
                            <div class="fw-bold">#CuacaCerah</div>
                            <div class="text-muted small">12.8K Tweet</div>
                        </div>
                    </div>

                    <div class="trending-sidebar">
                        <h5>Siapa yang harus diikuti</h5>
                        <div class="d-flex align-items-center py-2">
                            <img src="https://picsum.photos/40/40?random=40" alt="Avatar" class="rounded-circle me-3"
                                style="width: 40px; height: 40px" />
                            <div class="flex-grow-1">
                                <div class="fw-bold">Developer Indonesia</div>
                                <div class="text-muted small">@devindonesia</div>
                            </div>
                            <button class="btn btn-primary btn-sm">Ikuti</button>
                        </div>
                        <div class="d-flex align-items-center py-2">
                            <img src="https://picsum.photos/40/40?random=41" alt="Avatar" class="rounded-circle me-3"
                                style="width: 40px; height: 40px" />
                            <div class="flex-grow-1">
                                <div class="fw-bold">Kuliner Jakarta</div>
                                <div class="text-muted small">@kulinerjakarta</div>
                            </div>
                            <button class="btn btn-primary btn-sm">Ikuti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load More -->
    <div class="text-center py-3">
        <button class="btn btn-outline-primary" onclick="loadMoreTweets()">
            <i class="fas fa-spinner fa-spin d-none" id="loadingSpinner"></i>
            Muat Lebih Banyak
        </button>
    </div>
@endsection

@push('scripts')
    <script>

        let currentPage = 1;

        document.getElementById('loadMoreBtn').addEventListener('click', async () => {
            try {
                const res = await fetch(`/tweets?page=${currentPage + 1}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (res.status === 401) {
                    alert('Session expired. Please login again.');
                    localStorage.removeItem('jwt_token');
                    window.location.href = '/ayow';
                    return;
                }

                const data = await res.text();
                if (data.trim() !== '') {
                    document.getElementById('tweetList').insertAdjacentHTML('beforeend', data);
                    currentPage++;
                } else {
                    document.getElementById('loadMoreBtn').style.display = 'none';
                }
            } catch (err) {
                console.error('Error loading tweets:', err);
            }
        });

        document.addEventListener('click', async (e) => {
            if (e.target.classList.contains('like-btn')) {
                const tweetId = e.target.dataset.tweetId;

                try {
                    const res = await fetch(`/tweets/${tweetId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });

                    if (res.status === 401) {
                        alert('Session expired. Please login again.');
                        localStorage.removeItem('jwt_token');
                        window.location.href = '/ayow';
                        return;
                    }

                    const data = await res.json();
                    if (data.success) {
                        const likeCountSpan = e.target.querySelector('.like-count');
                        likeCountSpan.textContent = data.likes;
                    }
                } catch (err) {
                    console.error('Error liking tweet:', err);
                }
            }
        });
                        });
    </script>

@endpush