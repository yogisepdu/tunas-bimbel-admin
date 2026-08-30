<div class="tunas-landing">
    {{-- =========================================================
        TUNAS BIMBEL - PUBLIC LANDING PAGE
        File: resources/views/livewire/home-dashboard.blade.php
    ========================================================== --}}


    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg tunas-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-mark">
                    <i class="ti-book"></i>
                </span>
                <span class="brand-copy">
                    <strong>Tunas Bimbel</strong>
                    <small>Belajar • Tumbuh • Berprestasi</small>
                </span>
            </a>

            <button aria-controls="tunasNavbar" aria-expanded="false" aria-label="Buka navigasi" class="navbar-toggler"
                data-target="#tunasNavbar" data-toggle="collapse" type="button">
                <i class="ti-menu"></i>
            </button>

            <div class="navbar-collapse collapse" id="tunasNavbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keunggulan">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#program">Program</a></li>
                    <li class="nav-item"><a class="nav-link" href="#cara-belajar">Cara Belajar</a></li>
                    <li class="nav-item"><a class="nav-link" href="#paket">Paket</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                </ul>

                @auth
                    <a class="navbar-login-btn" href="{{ route('dashboard') }}">
                        <i class="ti-dashboard"></i>
                        Dashboard
                    </a>
                @else
                    <a class="navbar-login-btn" href="{{ route('login') }}">
                        <i class="ti-user"></i>
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="tunas-hero" id="beranda">
        <div class="hero-grid-bg"></div>
        <div class="hero-orb one"></div>
        <div class="hero-orb two"></div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-copy" data-aos="fade-up">
                        <div class="hero-badge">
                            <span class="dot"></span>
                            Platform Belajar Online Tunas Bimbel
                        </div>

                        <h1>
                            Belajar lebih terarah,
                            <span>raih target lebih cepat.</span>
                        </h1>

                        <p>
                            Satu platform untuk belajar melalui materi terstruktur, video pembelajaran,
                            PDF, quiz, tryout, dan evaluasi progres yang membantu siswa belajar lebih
                            fokus dan konsisten.
                        </p>

                        <div class="hero-actions">
                            <a class="btn-tunas btn-tunas-light" href="#paket">
                                Lihat Paket Belajar
                                <i class="ti-arrow-right"></i>
                            </a>

                            <a class="btn-tunas btn-tunas-outline" href="#keunggulan">
                                <i class="ti-control-play"></i>
                                Jelajahi Fitur
                            </a>
                        </div>

                        <div class="hero-mini-points">
                            <div class="hero-mini-point">
                                <i class="ti-check"></i>
                                Materi terstruktur
                            </div>
                            <div class="hero-mini-point">
                                <i class="ti-check"></i>
                                Quiz & tryout
                            </div>
                            <div class="hero-mini-point">
                                <i class="ti-check"></i>
                                Belajar fleksibel
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-visual-wrap" data-aos-delay="120" data-aos="fade-left">
                        <div class="learning-window">
                            <div class="learning-topbar">
                                <div class="window-dots">
                                    <span></span><span></span><span></span>
                                </div>
                                <div class="window-title">
                                    <span><i class="ti-book"></i></span>
                                    Ruang Belajar
                                </div>
                            </div>

                            <div class="learning-body">
                                <aside class="learning-sidebar">
                                    <div class="mini-logo"><i class="ti-layout-grid2"></i></div>
                                    <div class="mini-nav active"></div>
                                    <div class="mini-nav"></div>
                                    <div class="mini-nav"></div>
                                    <div class="mini-nav"></div>
                                </aside>

                                <div class="learning-content">
                                    <div class="learning-greeting">
                                        <div>
                                            <h5>Selamat datang 👋</h5>
                                            <p>Lanjutkan target belajar hari ini.</p>
                                        </div>
                                        <div class="mini-avatar">TB</div>
                                    </div>

                                    <div class="progress-banner">
                                        <div>
                                            <small>PROGRES BELAJAR</small>
                                            <strong>Teruskan materi berikutnya</strong>
                                        </div>
                                        <div class="progress-ring">72%</div>
                                    </div>

                                    <div class="course-mini-grid">
                                        <div class="course-mini">
                                            <div class="icon"><i class="ti-ruler-pencil"></i></div>
                                            <strong>Matematika</strong>
                                            <small>8 materi</small>
                                        </div>
                                        <div class="course-mini">
                                            <div class="icon"><i class="ti-bolt"></i></div>
                                            <strong>Fisika</strong>
                                            <small>6 materi</small>
                                        </div>
                                        <div class="course-mini">
                                            <div class="icon"><i class="ti-pencil-alt"></i></div>
                                            <strong>Bahasa Indonesia</strong>
                                            <small>7 materi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="floating-card quiz">
                            <div class="float-icon"><i class="ti-write"></i></div>
                            <div>
                                <strong>Quiz Interaktif</strong>
                                <small>Uji pemahaman materi</small>
                            </div>
                        </div>

                        <div class="floating-card video">
                            <div class="float-icon"><i class="ti-video-camera"></i></div>
                            <div>
                                <strong>Video Pembelajaran</strong>
                                <small>Belajar kapan saja</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- QUICK FEATURE STRIP --}}
    <div class="feature-strip-wrap">
        <div class="container">
            <div class="feature-strip">
                <div class="row">
                    <div class="col-md-4 quick-feature">
                        <div class="quick-feature-icon"><i class="ti-layers-alt"></i></div>
                        <div>
                            <h5>Materi Terorganisir</h5>
                            <p>Kelas, sub materi, video, dan PDF dalam alur yang jelas.</p>
                        </div>
                    </div>

                    <div class="col-md-4 quick-feature">
                        <div class="quick-feature-icon"><i class="ti-pencil-alt"></i></div>
                        <div>
                            <h5>Latihan & Tryout</h5>
                            <p>Evaluasi pemahaman melalui quiz dan simulasi soal.</p>
                        </div>
                    </div>

                    <div class="col-md-4 quick-feature">
                        <div class="quick-feature-icon"><i class="ti-stats-up"></i></div>
                        <div>
                            <h5>Progres Belajar</h5>
                            <p>Belajar konsisten dengan aktivitas yang lebih mudah dipantau.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KEUNGGULAN --}}
    <section class="tunas-section" id="keunggulan">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <div class="section-kicker">
                    <i class="ti-shine"></i>
                    Belajar Lebih Efektif
                </div>
                <h2>
                    Semua kebutuhan belajar dalam
                    <span class="gradient-text">satu platform.</span>
                </h2>
                <p>
                    Tunas Bimbel menyatukan materi, video, dokumen, latihan, dan tryout
                    agar proses belajar tidak terpecah-pecah dan lebih mudah diikuti.
                </p>
            </div>

            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="ti-book"></i></div>
                        <h4>Materi Bertahap</h4>
                        <p>Konten disusun per kelas dan sub materi sehingga siswa mengetahui apa yang harus dipelajari
                            berikutnya.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4" data-aos-delay="70" data-aos="fade-up">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="ti-video-camera"></i></div>
                        <h4>Video Pembelajaran</h4>
                        <p>Pelajari topik melalui video yang dapat diakses kembali saat siswa membutuhkan pengulangan
                            materi.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4" data-aos-delay="140" data-aos="fade-up">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="ti-write"></i></div>
                        <h4>Quiz & Evaluasi</h4>
                        <p>Uji pemahaman setelah belajar agar siswa dapat mengetahui bagian yang masih perlu
                            ditingkatkan.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4" data-aos-delay="210" data-aos="fade-up">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="ti-cup"></i></div>
                        <h4>Tryout Terstruktur</h4>
                        <p>Berlatih menghadapi soal dengan format evaluasi yang membantu membangun kesiapan dan
                            kebiasaan mengerjakan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SHOWCASE --}}
    <section class="tunas-section tunas-section-soft">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="showcase-panel">
                        <h3>Belajar tidak hanya menonton, tetapi juga memahami.</h3>
                        <p>
                            Setiap materi dapat dilengkapi video, dokumen, dan latihan agar proses
                            belajar lebih aktif dan tidak berhenti pada satu jenis konten.
                        </p>

                        <div class="study-preview">
                            <div class="study-preview-card">
                                <div class="label">
                                    <i class="ti-video-camera"></i>
                                    Materi sedang dipelajari
                                </div>
                                <strong>Persamaan & Fungsi Dasar</strong>
                                <div class="fake-progress"><span></span></div>
                            </div>

                            <div class="study-preview-card">
                                <div class="label">
                                    <i class="ti-pencil-alt"></i>
                                    Latihan
                                </div>
                                <strong>Quiz Materi</strong>
                                <div class="fake-progress"><span style="width: 88%"></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="showcase-list">
                        <div class="section-kicker">
                            <i class="ti-layout-media-center-alt"></i>
                            Ekosistem Pembelajaran
                        </div>

                        <h3>Materi saling terhubung dari awal sampai evaluasi.</h3>

                        <p>
                            Struktur Tunas Bimbel dirancang agar siswa dapat berpindah dari membaca materi,
                            menonton penjelasan, mengerjakan latihan, hingga mengikuti tryout dalam satu alur.
                        </p>

                        <div class="benefit-item">
                            <div class="benefit-check"><i class="ti-check"></i></div>
                            <div>
                                <strong>Kelas dan sub materi yang jelas</strong>
                                <span>Konten dikelompokkan berdasarkan kelas dan bab agar mudah ditemukan.</span>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-check"><i class="ti-check"></i></div>
                            <div>
                                <strong>Video dan PDF pendamping</strong>
                                <span>Gunakan jenis materi berbeda untuk memperkuat pemahaman topik.</span>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-check"><i class="ti-check"></i></div>
                            <div>
                                <strong>Quiz dan soal tryout</strong>
                                <span>Latihan tersedia untuk mengukur kesiapan setelah proses belajar.</span>
                            </div>
                        </div>

                        <a class="btn-tunas" href="#paket"
                            style="background: var(--tunas-primary); color:#fff !important; margin-top: 8px;">
                            Mulai Pilih Program
                            <i class="ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PROGRAM --}}
    <section class="tunas-section" id="program">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <div class="section-kicker">
                    <i class="ti-blackboard"></i>
                    Fitur Pembelajaran
                </div>
                <h2>Ruang belajar yang mendukung <span class="gradient-text">berbagai aktivitas.</span></h2>
                <p>
                    Tidak hanya melihat materi. Siswa dapat mengikuti rangkaian belajar dari konten hingga evaluasi.
                </p>
            </div>

            <div class="row mt-5">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="program-card">
                        <div class="program-head">
                            <div class="program-icon"><i class="ti-book"></i></div>
                            <div class="program-badge">MATERI</div>
                        </div>
                        <div class="program-body">
                            <h4>Kelas & Sub Materi</h4>
                            <p>Pelajaran disusun berdasarkan kelas serta bab agar siswa dapat mengikuti urutan belajar
                                dengan mudah.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="program-card">
                        <div class="program-head">
                            <div class="program-icon"><i class="ti-video-camera"></i></div>
                            <div class="program-badge">VIDEO</div>
                        </div>
                        <div class="program-body">
                            <h4>Video Pembelajaran</h4>
                            <p>Materi video membantu siswa mempelajari penjelasan secara visual dan mengulang bagian
                                yang belum dipahami.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="program-card">
                        <div class="program-head">
                            <div class="program-icon"><i class="ti-file"></i></div>
                            <div class="program-badge">PDF</div>
                        </div>
                        <div class="program-body">
                            <h4>Modul & Dokumen</h4>
                            <p>PDF pendamping dapat digunakan untuk membaca ulang ringkasan, contoh, dan bahan belajar
                                terkait materi.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="program-card">
                        <div class="program-head">
                            <div class="program-icon"><i class="ti-pencil-alt"></i></div>
                            <div class="program-badge">QUIZ</div>
                        </div>
                        <div class="program-body">
                            <h4>Quiz Materi</h4>
                            <p>Latihan singkat setelah belajar membantu siswa mengecek apakah konsep utama sudah
                                benar-benar dipahami.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="program-card">
                        <div class="program-head">
                            <div class="program-icon"><i class="ti-medall"></i></div>
                            <div class="program-badge">TRYOUT</div>
                        </div>
                        <div class="program-body">
                            <h4>Tryout & Bank Soal</h4>
                            <p>Berlatih mengerjakan kumpulan soal untuk membangun kesiapan, ketelitian, dan strategi
                                mengerjakan.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="program-card">
                        <div class="program-head">
                            <div class="program-icon"><i class="ti-calendar"></i></div>
                            <div class="program-badge">AKADEMIK</div>
                        </div>
                        <div class="program-body">
                            <h4>Informasi Akademik</h4>
                            <p>Kalender dan pengumuman membantu pengguna melihat informasi penting yang berkaitan dengan
                                aktivitas belajar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CARA BELAJAR --}}
    <section class="tunas-section tunas-section-soft" id="cara-belajar">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <div class="section-kicker">
                    <i class="ti-direction-alt"></i>
                    Alur Belajar
                </div>
                <h2>Sederhana untuk diikuti, <span class="gradient-text">jelas untuk dipelajari.</span></h2>
                <p>
                    Siswa dapat menjalankan proses belajar secara bertahap tanpa harus mencari materi dari banyak
                    tempat.
                </p>
            </div>

            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="learning-step">
                        <div class="step-number">01</div>
                        <h4>Pilih kelas</h4>
                        <p>Masuk ke kelas atau program belajar yang tersedia sesuai kebutuhan siswa.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="learning-step">
                        <div class="step-number">02</div>
                        <h4>Pelajari materi</h4>
                        <p>Ikuti sub materi menggunakan video dan PDF yang telah disediakan.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="learning-step">
                        <div class="step-number">03</div>
                        <h4>Kerjakan quiz</h4>
                        <p>Gunakan latihan untuk mengecek pemahaman sebelum melanjutkan ke materi berikutnya.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="learning-step">
                        <div class="step-number">04</div>
                        <h4>Ikuti tryout</h4>
                        <p>Latih kesiapan melalui soal terstruktur dan evaluasi hasil belajar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TRYOUT PROMO --}}
    <section class="tunas-section">
        <div class="container">
            <div class="tryout-wrap">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="tryout-copy">
                            <div class="section-kicker">
                                <i class="ti-timer"></i>
                                Quiz & Tryout
                            </div>

                            <h2>Uji pemahaman sebelum menghadapi ujian sebenarnya.</h2>
                            <p>
                                Gunakan latihan dan tryout sebagai bagian dari proses belajar, bukan hanya
                                sebagai tes akhir. Dengan latihan yang konsisten, siswa dapat mengetahui
                                materi mana yang masih perlu dipelajari kembali.
                            </p>

                            <div class="tryout-points">
                                <div class="tryout-point"><i class="ti-check"></i> Soal pilihan ganda</div>
                                <div class="tryout-point"><i class="ti-check"></i> Evaluasi pemahaman</div>
                                <div class="tryout-point"><i class="ti-check"></i> Terhubung dengan kelas</div>
                                <div class="tryout-point"><i class="ti-check"></i> Latihan lebih terarah</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="quiz-card-ui">
                            <div class="quiz-ui-top">
                                <span>Soal 08 dari 20</span>
                                <b>12:45</b>
                            </div>

                            <h5>
                                Jika nilai x = 4, berapakah hasil dari 3x + 2?
                            </h5>

                            <div class="answer-ui">A. 10</div>
                            <div class="answer-ui">B. 12</div>
                            <div class="answer-ui active">C. 14</div>
                            <div class="answer-ui">D. 16</div>

                            <div class="fake-progress" style="background:#ececf3; margin-top:18px;">
                                <span style="width:40%; background:var(--tunas-primary);"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PAKET DINAMIS --}}
    <section class="tunas-section tunas-section-soft" id="paket">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <div class="section-kicker">
                    <i class="ti-package"></i>
                    Paket Belajar
                </div>
                <h2>Pilih program sesuai <span class="gradient-text">target belajarmu.</span></h2>
                <p>
                    Paket di bawah ini diambil langsung dari data yang dikelola melalui panel Tunas Bimbel.
                </p>

                <div class="billing-switch">
                    <button class="billing-btn {{ $billing === 'monthly' ? 'active' : '' }}" type="button"
                        wire:click="$set('billing', 'monthly')">
                        Bulanan
                    </button>
                    <button class="billing-btn {{ $billing === 'yearly' ? 'active' : '' }}" type="button"
                        wire:click="$set('billing', 'yearly')">
                        Tahunan
                    </button>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                @forelse($packages as $package)
                    <div class="col-lg-4 col-md-6 d-flex mb-4">
                        <div class="pricing-card-tunas {{ $package['highlight'] ? 'highlight' : '' }} w-100">
                            @if ($package['highlight'])
                                <div class="premium-badge">
                                    <i class="ti-star"></i>
                                    Paket Premium
                                </div>
                            @endif

                            @if (!empty($package['image']))
                                <div class="package-image">
                                    <img alt="{{ $package['name'] }}"
                                        src="{{ asset('storage/' . ltrim($package['image'], '/')) }}">
                                </div>
                            @else
                                <div class="package-placeholder">
                                    <i class="ti-book"></i>
                                </div>
                            @endif

                            <h4>{{ $package['name'] }}</h4>

                            <p class="package-desc">
                                {{ $package['description'] ?: 'Paket belajar Tunas Bimbel dengan kelas dan materi yang telah disiapkan.' }}
                            </p>

                            <div class="package-price">
                                <strong>
                                    Rp
                                    {{ number_format($billing === 'monthly' ? $package['price_monthly'] : $package['price_yearly'], 0, ',', '.') }}
                                </strong>
                                <span>/ {{ $billing === 'monthly' ? 'bulan' : 'tahun' }}</span>
                            </div>

                            <div class="package-divider"></div>

                            <div class="package-features">
                                <div class="package-features-title">Kelas dalam paket:</div>

                                @forelse($package['features'] as $feature)
                                    <div class="package-feature">
                                        <i class="ti-check"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @empty
                                    <div class="package-empty">
                                        Detail kelas untuk paket ini belum ditambahkan.
                                    </div>
                                @endforelse
                            </div>

                            <button class="btn-tunas btn-package" type="button"
                                wire:click="buy('{{ $package['id'] }}')" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="buy('{{ $package['id'] }}')">
                                    {{ $package['button'] }}
                                    <i class="ti-arrow-right"></i>
                                </span>
                                <span wire:loading wire:target="buy('{{ $package['id'] }}')">
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-8">
                        <div class="pricing-empty-state">
                            <i class="ti-package" style="font-size:28px; display:block; margin-bottom:12px;"></i>
                            Paket belajar belum tersedia saat ini.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="tunas-section" id="faq">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <div class="section-kicker">
                    <i class="ti-help-alt"></i>
                    Pertanyaan Umum
                </div>
                <h2>Hal yang perlu diketahui tentang <span class="gradient-text">Tunas Bimbel.</span></h2>
            </div>

            <div class="row mt-5">
                <div class="col-lg-6 mb-4">
                    <div class="faq-card">
                        <div class="faq-icon"><i class="ti-book"></i></div>
                        <h5>Apa saja yang dapat dipelajari di Tunas Bimbel?</h5>
                        <p>
                            Materi belajar disusun berdasarkan kelas yang tersedia pada paket. Di dalamnya
                            dapat terdapat sub materi, video, PDF, quiz, dan latihan terkait.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-card">
                        <div class="faq-icon"><i class="ti-mobile"></i></div>
                        <h5>Apakah materi dapat dipelajari kembali?</h5>
                        <p>
                            Selama akses terhadap program masih aktif, siswa dapat menggunakan materi
                            pembelajaran yang tersedia sesuai ketentuan paket yang dipilih.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-card">
                        <div class="faq-icon"><i class="ti-pencil-alt"></i></div>
                        <h5>Apakah ada quiz dan tryout?</h5>
                        <p>
                            Sistem Tunas Bimbel mendukung quiz per kelas serta modul tryout untuk
                            membantu siswa melakukan latihan dan evaluasi hasil belajar.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-card">
                        <div class="faq-icon"><i class="ti-user"></i></div>
                        <h5>Siapa yang mengelola materi pembelajaran?</h5>
                        <p>
                            Materi dikelola melalui panel oleh administrator, admin, dan teacher sesuai
                            hak akses serta kelas yang menjadi tanggung jawab masing-masing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="tunas-section pt-0">
        <div class="container">
            <div class="cta-box" data-aos="fade-up">
                <div class="cta-content">
                    <h2>Mulai bangun kebiasaan belajar yang lebih terarah bersama Tunas Bimbel.</h2>
                    <p>
                        Pilih paket yang sesuai dengan kebutuhan, pelajari materi secara bertahap,
                        dan gunakan quiz serta tryout untuk mengevaluasi perkembangan belajar.
                    </p>

                    <a class="btn-tunas btn-tunas-light" href="#paket">
                        Pilih Paket Belajar
                        <i class="ti-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="tunas-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-lg-0 mb-4">
                    <div class="footer-brand">
                        <span class="brand-mark"><i class="ti-book"></i></span>
                        <span class="brand-copy">
                            <strong>Tunas Bimbel</strong>
                            <small>Belajar • Tumbuh • Berprestasi</small>
                        </span>
                    </div>

                    <p class="footer-about">
                        Platform pembelajaran yang menghubungkan kelas, materi, video, PDF,
                        quiz, tryout, serta informasi akademik dalam satu sistem yang terstruktur.
                    </p>
                </div>

                <div class="col-6 col-lg-2 mb-4">
                    <div class="footer-title">Navigasi</div>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#keunggulan">Keunggulan</a></li>
                        <li><a href="#program">Program</a></li>
                        <li><a href="#paket">Paket</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2 mb-4">
                    <div class="footer-title">Pembelajaran</div>
                    <ul class="footer-links">
                        <li><a href="#program">Video</a></li>
                        <li><a href="#program">PDF</a></li>
                        <li><a href="#program">Quiz</a></li>
                        <li><a href="#program">Tryout</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <div class="footer-title">Akses Sistem</div>
                    <div class="footer-contact">
                        <i class="ti-lock"></i>
                        <span>Panel website digunakan oleh administrator, admin, dan teacher.</span>
                    </div>
                    <div class="footer-contact">
                        <i class="ti-mobile"></i>
                        <span>Siswa menggunakan layanan pembelajaran melalui aplikasi Tunas Bimbel.</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <span>
                    &copy; {{ date('Y') }} Tunas Bimbel. Seluruh hak dilindungi.
                </span>
                <span class="mt-md-0 mt-2">
                    Belajar • Tumbuh • Berprestasi
                </span>
            </div>
        </div>
    </footer>
</div>
