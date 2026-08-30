<div class="checkout-page">
    <style>
        .checkout-payment-title {
            margin-bottom: 14px;
        }

        .checkout-payment-title strong {
            display: block;
            color: #182033;
            font-size: 13px;
            font-weight: 800;
        }

        .checkout-payment-title span {
            display: block;
            margin-top: 4px;
            color: #8d94a7;
            font-size: 10px;
        }

        .payment-method-option {
            position: relative;
            display: block;
            height: 100%;
            cursor: pointer;
        }

        .payment-method-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-method-card {
            height: 100%;
            padding: 16px;
            border: 1px solid #e7e9f1;
            border-radius: 14px;
            background: #fff;
            transition: .2s ease;
        }

        .payment-method-card:hover {
            border-color: #ccc6ff;
            transform: translateY(-1px);
        }

        .payment-method-option.is-selected .payment-method-card {
            border: 2px solid #5b4ee8;
            background: #faf9ff;
            box-shadow: 0 8px 24px rgba(91, 78, 232, .07);
        }

        .payment-method-card-inner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .payment-radio {
            width: 20px;
            height: 20px;
            display: grid;
            place-items: center;
            flex: 0 0 20px;
            margin-top: 1px;
            border: 1.5px solid #d7d9e2;
            border-radius: 50%;
        }

        .payment-method-option.is-selected .payment-radio {
            border-color: #5b4ee8;
        }

        .payment-method-option.is-selected .payment-radio::after {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #5b4ee8;
        }

        .payment-method-icon {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            flex: 0 0 40px;
            border-radius: 12px;
            background: #efedff;
            color: #5b4ee8;
        }

        .payment-method-content {
            min-width: 0;
            flex: 1;
        }

        .payment-method-content strong {
            display: block;
            color: #182033;
            font-size: 12px;
            font-weight: 800;
        }

        .payment-method-content small {
            display: block;
            margin-top: 3px;
            color: #8d94a7;
            font-size: 9px;
            line-height: 1.55;
        }

        .payment-method-content .account-preview {
            display: block;
            margin-top: 7px;
            color: #596176;
            font-size: 9px;
            font-weight: 700;
        }

        .review-payment-box {
            padding: 13px;
            border: 1px solid #e9eaf0;
            border-radius: 11px;
            background: #fff;
        }

        .review-payment-box span,
        .review-payment-box strong,
        .review-payment-box small {
            display: block;
        }

        .review-payment-box span {
            color: #8d94a7;
            font-size: 8px;
        }

        .review-payment-box strong {
            margin-top: 5px;
            color: #182033;
            font-size: 11px;
            font-weight: 800;
        }

        .review-payment-box small {
            margin-top: 4px;
            color: #8d94a7;
            font-size: 9px;
        }
    </style>

    <div class="checkout-bg-shape checkout-bg-shape-one"></div>
    <div class="checkout-bg-shape checkout-bg-shape-two"></div>

    <header class="checkout-topbar">
        <div class="container">
            <div class="checkout-topbar-inner">
                <a class="checkout-brand" href="{{ route('home') }}">
                    <span class="checkout-brand-mark">
                        <i class="ti-book"></i>
                    </span>

                    <span class="checkout-brand-copy">
                        <strong>Tunas Bimbel</strong>
                        <small>Checkout Paket Belajar</small>
                    </span>
                </a>

                <a class="checkout-back-link" href="{{ route('home') }}#paket">
                    <i class="ti-angle-left"></i>
                    <span>Kembali ke Paket</span>
                </a>
            </div>
        </div>
    </header>

    <main class="checkout-main">
        <div class="container">
            <div class="checkout-heading">
                <div>
                    <div class="checkout-eyebrow">
                        <i class="ti-shopping-cart"></i>
                        Checkout
                    </div>

                    <h1>Selesaikan pilihan paket belajarmu.</h1>

                    <p>
                        Periksa paket, pilih periode dan metode pembayaran,
                        lalu lengkapi data akun student yang akan menerima akses.
                    </p>
                </div>

                <div class="checkout-secure-badge">
                    <div class="secure-icon">
                        <i class="ti-lock"></i>
                    </div>

                    <div>
                        <strong>Checkout aman</strong>
                        <span>Invoice dibuat berdasarkan data paket Tunas Bimbel.</span>
                    </div>
                </div>
            </div>

            <div class="checkout-progress">
                <div class="checkout-progress-item is-complete">
                    <span class="progress-circle">
                        <i class="ti-check"></i>
                    </span>

                    <div>
                        <strong>1. Pilih Paket</strong>
                        <small>Paket telah dipilih</small>
                    </div>
                </div>

                <span class="progress-line"></span>

                <div class="checkout-progress-item {{ $reviewing ? 'is-complete' : 'is-active' }}">
                    <span class="progress-circle">
                        @if ($reviewing)
                            <i class="ti-check"></i>
                        @else
                            2
                        @endif
                    </span>

                    <div>
                        <strong>2. Checkout</strong>
                        <small>{{ $reviewing ? 'Data telah diperiksa' : 'Lengkapi data' }}</small>
                    </div>
                </div>

                <span class="progress-line"></span>

                <div class="checkout-progress-item {{ $reviewing ? 'is-active' : '' }}">
                    <span class="progress-circle">3</span>

                    <div>
                        <strong>3. Pembayaran</strong>
                        <small>Invoice & bukti pembayaran</small>
                    </div>
                </div>
            </div>

            <div class="row checkout-layout">
                <div class="col-lg-8">

                    @if (!$reviewing)
                        <section class="checkout-card">
                            <div class="checkout-card-heading">
                                <div class="checkout-card-icon">
                                    <i class="ti-package"></i>
                                </div>

                                <div>
                                    <span class="checkout-card-number">01</span>
                                    <h2>Paket yang dipilih</h2>
                                    <p>Pastikan paket dan periode belajar sudah sesuai.</p>
                                </div>
                            </div>

                            <div class="selected-package">
                                <div class="selected-package-media">
                                    @if ($package->image)
                                        <img alt="{{ $package->name }}"
                                            src="{{ asset('storage/' . ltrim($package->image, '/')) }}">
                                    @else
                                        <div class="selected-package-placeholder">
                                            <i class="ti-book"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="selected-package-content">
                                    <div class="selected-package-top">
                                        <div>
                                            <span class="selected-label">PAKET TERPILIH</span>
                                            <h3>{{ $package->name }}</h3>
                                        </div>

                                        <span class="package-status">
                                            <i class="ti-check"></i>
                                            Dipilih
                                        </span>
                                    </div>

                                    <p>
                                        {{ $package->description ?: 'Paket belajar Tunas Bimbel dengan kelas dan materi pembelajaran yang telah disiapkan.' }}
                                    </p>

                                    <div class="package-meta-row">
                                        <div class="package-meta-item">
                                            <i class="ti-layers-alt"></i>
                                            <span>
                                                <strong>{{ $package->classes->count() }}</strong>
                                                kelas
                                            </span>
                                        </div>

                                        <div class="package-meta-item">
                                            <i class="ti-timer"></i>
                                            <span>
                                                <strong>{{ $this->periodLabel() }}</strong>
                                                akses
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($package->classes->isNotEmpty())
                                <div class="included-classes">
                                    <div class="included-classes-title">
                                        <div>
                                            <strong>Kelas dalam paket</strong>
                                            <span>
                                                Student memperoleh akses setelah pembayaran diverifikasi.
                                            </span>
                                        </div>

                                        <span class="classes-count">
                                            {{ $package->classes->count() }} kelas
                                        </span>
                                    </div>

                                    <div class="class-chip-list">
                                        @foreach ($package->classes as $class)
                                            <span class="class-chip">
                                                <i class="ti-check"></i>
                                                {{ $class->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="billing-choice-section">
                                <div class="field-heading">
                                    <div>
                                        <strong>Pilih periode belajar</strong>
                                        <span>Harga invoice mengikuti periode yang dipilih.</span>
                                    </div>
                                </div>

                                <div class="billing-choice-grid">
                                    <button class="billing-choice {{ $billing === 'monthly' ? 'is-selected' : '' }}"
                                        type="button" wire:click="setBilling('monthly')">
                                        <span class="billing-radio">
                                            <span></span>
                                        </span>

                                        <span class="billing-choice-copy">
                                            <strong>Bulanan</strong>
                                            <small>Akses selama 1 bulan</small>
                                        </span>

                                        <span class="billing-choice-price">
                                            <strong>
                                                Rp {{ number_format($this->monthlyPrice(), 0, ',', '.') }}
                                            </strong>
                                            <small>/ bulan</small>
                                        </span>
                                    </button>

                                    <button class="billing-choice {{ $billing === 'yearly' ? 'is-selected' : '' }}"
                                        type="button" wire:click="setBilling('yearly')">
                                        <span class="billing-radio">
                                            <span></span>
                                        </span>

                                        <span class="billing-choice-copy">
                                            <span class="billing-save-badge">
                                                Hemat 2 bulan
                                            </span>

                                            <strong>Tahunan</strong>
                                            <small>Akses selama 12 bulan</small>
                                        </span>

                                        <span class="billing-choice-price">
                                            <strong>
                                                Rp {{ number_format($this->yearlyPrice(), 0, ',', '.') }}
                                            </strong>
                                            <small>/ tahun</small>
                                        </span>
                                    </button>
                                </div>

                                @if ($billing === 'yearly' && $this->yearlySaving() > 0)
                                    <div class="saving-note">
                                        <i class="ti-gift"></i>

                                        <span>
                                            Paket tahunan menghemat
                                            <strong>
                                                Rp {{ number_format($this->yearlySaving(), 0, ',', '.') }}
                                            </strong>
                                            dibanding 12 pembayaran bulanan.
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </section>

                        <form wire:submit.prevent="reviewOrder">
                            <section class="checkout-card">
                                <div class="checkout-card-heading">
                                    <div class="checkout-card-icon checkout-card-icon-green">
                                        <i class="ti-user"></i>
                                    </div>

                                    <div>
                                        <span class="checkout-card-number">02</span>
                                        <h2>Data student & pembayaran</h2>
                                        <p>Email harus merupakan akun student Tunas Bimbel yang sudah terdaftar.</p>
                                    </div>
                                </div>

                                <div class="checkout-form-grid">
                                    <div class="checkout-field checkout-field-full">
                                        <label for="customer_name">
                                            Nama lengkap
                                            <span>*</span>
                                        </label>

                                        <div class="checkout-input-wrap @error('customer_name') has-error @enderror">
                                            <i class="ti-user"></i>

                                            <input autocomplete="name" id="customer_name"
                                                placeholder="Masukkan nama lengkap" type="text"
                                                wire:model.blur="customer_name">
                                        </div>

                                        @error('customer_name')
                                            <div class="checkout-error">
                                                <i class="ti-alert"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="checkout-field">
                                        <label for="customer_email">
                                            Email akun student
                                            <span>*</span>
                                        </label>

                                        <div class="checkout-input-wrap @error('customer_email') has-error @enderror">
                                            <i class="ti-email"></i>

                                            <input autocomplete="email" id="customer_email"
                                                placeholder="student@email.com" type="email"
                                                wire:model.blur="customer_email">
                                        </div>

                                        @error('customer_email')
                                            <div class="checkout-error">
                                                <i class="ti-alert"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="checkout-field">
                                        <label for="customer_phone">
                                            Nomor WhatsApp
                                            <span>*</span>
                                        </label>

                                        <div class="checkout-input-wrap @error('customer_phone') has-error @enderror">
                                            <i class="ti-mobile"></i>

                                            <input autocomplete="tel" id="customer_phone"
                                                placeholder="0812 3456 7890" type="tel"
                                                wire:model.blur="customer_phone">
                                        </div>

                                        @error('customer_phone')
                                            <div class="checkout-error">
                                                <i class="ti-alert"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div
                                    style="
                                        margin-top:24px;
                                        padding-top:22px;
                                        border-top:1px solid #e7e9f1;
                                    ">
                                    <div class="checkout-payment-title">
                                        <strong>
                                            Pilih metode pembayaran
                                            <span style="color:#dd4b59">*</span>
                                        </strong>

                                        <span>
                                            Rekening atau QR akan ditampilkan lengkap setelah invoice dibuat.
                                        </span>
                                    </div>

                                    @if ($paymentMethods->isEmpty())
                                        <div class="alert alert-warning mb-0">
                                            Belum ada metode pembayaran aktif.
                                            Silakan hubungi administrator Tunas Bimbel.
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach ($paymentMethods as $method)
                                                <div class="col-md-6 mb-3"
                                                    wire:key="payment-method-{{ $method->id }}">
                                                    <label
                                                        class="payment-method-option {{ (int) $payment_method_id === (int) $method->id ? 'is-selected' : '' }}">
                                                        <input type="radio" value="{{ $method->id }}"
                                                            wire:model.live="payment_method_id">

                                                        <div class="payment-method-card">
                                                            <div class="payment-method-card-inner">
                                                                <span class="payment-radio"></span>

                                                                <div class="payment-method-icon">
                                                                    @if ($method->type === 'bank_transfer')
                                                                        <i class="ti-credit-card"></i>
                                                                    @elseif($method->type === 'qris')
                                                                        <i class="ti-layout-grid2"></i>
                                                                    @else
                                                                        <i class="ti-money"></i>
                                                                    @endif
                                                                </div>

                                                                <div class="payment-method-content">
                                                                    <strong>
                                                                        {{ $method->name }}
                                                                    </strong>

                                                                    <small>
                                                                        {{ match ($method->type) {
                                                                            'bank_transfer' => 'Transfer Bank',
                                                                            'ewallet' => 'E-Wallet',
                                                                            'qris' => 'QRIS',
                                                                            default => 'Pembayaran Manual',
                                                                        } }}

                                                                        @if ($method->provider)
                                                                            • {{ $method->provider }}
                                                                        @endif
                                                                    </small>

                                                                    @if ($method->type !== 'qris' && $method->account_number)
                                                                        <span class="account-preview">
                                                                            {{ $method->account_number }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @error('payment_method_id')
                                        <div class="checkout-error">
                                            <i class="ti-alert"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="checkout-info-box">
                                    <div class="checkout-info-icon">
                                        <i class="ti-info-alt"></i>
                                    </div>

                                    <div>
                                        <strong>Pastikan email student benar.</strong>

                                        <p>
                                            Setelah pembayaran diverifikasi, paket akan diaktifkan
                                            ke akun student yang menggunakan email tersebut.
                                        </p>
                                    </div>
                                </div>

                                <div class="checkout-form-actions">
                                    <a class="btn-checkout-secondary" href="{{ route('home') }}#paket">
                                        <i class="ti-angle-left"></i>
                                        Ganti Paket
                                    </a>

                                    <button @disabled($paymentMethods->isEmpty()) class="btn-checkout-primary" type="submit"
                                        wire:loading.attr="disabled" wire:target="reviewOrder">
                                        <span wire:loading.remove wire:target="reviewOrder">
                                            Tinjau Pesanan
                                            <i class="ti-arrow-right"></i>
                                        </span>

                                        <span wire:loading wire:target="reviewOrder">
                                            <span class="checkout-spinner"></span>
                                            Memeriksa data...
                                        </span>
                                    </button>
                                </div>
                            </section>
                        </form>
                    @else
                        <section class="checkout-card checkout-review-card">
                            <div class="review-success-icon">
                                <i class="ti-check"></i>
                            </div>

                            <div class="review-heading">
                                <span>DATA CHECKOUT SIAP</span>
                                <h2>Periksa sekali lagi sebelum invoice dibuat.</h2>

                                <p>
                                    Setelah invoice dibuat, Anda diarahkan ke halaman pembayaran
                                    untuk melihat rekening/QR dan mengirim bukti pembayaran.
                                </p>
                            </div>

                            <div class="review-block">
                                <div class="review-block-heading">
                                    <div>
                                        <i class="ti-package"></i>
                                        <strong>Ringkasan paket</strong>
                                    </div>

                                    <button class="review-edit-btn" type="button" wire:click="editCustomerData">
                                        Ubah
                                    </button>
                                </div>

                                <div class="review-package-row">
                                    <div>
                                        <span>Paket</span>
                                        <strong>{{ $package->name }}</strong>
                                    </div>

                                    <div>
                                        <span>Periode</span>
                                        <strong>{{ $this->billingLabel() }}</strong>
                                    </div>

                                    <div>
                                        <span>Akses</span>
                                        <strong>{{ $this->periodLabel() }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="review-block">
                                <div class="review-block-heading">
                                    <div>
                                        <i class="ti-user"></i>
                                        <strong>Data student</strong>
                                    </div>

                                    <button class="review-edit-btn" type="button" wire:click="editCustomerData">
                                        Edit
                                    </button>
                                </div>

                                <div class="review-customer-grid">
                                    <div class="review-customer-item">
                                        <span>Nama lengkap</span>
                                        <strong>{{ $customer_name }}</strong>
                                    </div>

                                    <div class="review-customer-item">
                                        <span>Email student</span>
                                        <strong>{{ $customer_email }}</strong>
                                    </div>

                                    <div class="review-customer-item">
                                        <span>Nomor WhatsApp</span>
                                        <strong>{{ $customer_phone }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if ($selectedPaymentMethod)
                                <div class="review-block">
                                    <div class="review-block-heading">
                                        <div>
                                            <i class="ti-credit-card"></i>
                                            <strong>Metode pembayaran</strong>
                                        </div>

                                        <button class="review-edit-btn" type="button" wire:click="editCustomerData">
                                            Ubah
                                        </button>
                                    </div>

                                    <div class="review-payment-box">
                                        <span>Metode yang dipilih</span>

                                        <strong>
                                            {{ $selectedPaymentMethod->name }}
                                        </strong>

                                        <small>
                                            {{ match ($selectedPaymentMethod->type) {
                                                'bank_transfer' => 'Transfer Bank',
                                                'ewallet' => 'E-Wallet',
                                                'qris' => 'QRIS',
                                                default => 'Pembayaran Manual',
                                            } }}

                                            @if ($selectedPaymentMethod->provider)
                                                • {{ $selectedPaymentMethod->provider }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endif

                            <div class="payment-next-card">
                                <div class="payment-next-icon">
                                    <i class="ti-receipt"></i>
                                </div>

                                <div class="payment-next-copy">
                                    <span>LANGKAH BERIKUTNYA</span>
                                    <h3>Buat invoice pembayaran</h3>

                                    <p>
                                        Invoice disimpan dengan status menunggu pembayaran.
                                        Paket belum aktif sampai pembayaran diverifikasi administrator/admin.
                                    </p>
                                </div>
                            </div>

                            <div class="checkout-form-actions">
                                <button class="btn-checkout-secondary" type="button" wire:click="editCustomerData">
                                    <i class="ti-angle-left"></i>
                                    Kembali Edit
                                </button>

                                <button class="btn-checkout-primary" type="button" wire:click="createInvoice"
                                    wire:loading.attr="disabled" wire:target="createInvoice">
                                    <span wire:loading.remove wire:target="createInvoice">
                                        Buat Invoice & Lanjutkan
                                        <i class="ti-arrow-right"></i>
                                    </span>

                                    <span wire:loading wire:target="createInvoice">
                                        <span class="checkout-spinner"></span>
                                        Membuat invoice...
                                    </span>
                                </button>
                            </div>
                        </section>
                    @endif
                </div>

                <div class="col-lg-4">
                    <aside class="order-summary-card">
                        <div class="order-summary-heading">
                            <div>
                                <span>RINGKASAN PESANAN</span>
                                <h2>Total pembelian</h2>
                            </div>

                            <i class="ti-receipt"></i>
                        </div>

                        <div class="summary-package-mini">
                            <div class="summary-package-image">
                                @if ($package->image)
                                    <img alt="{{ $package->name }}"
                                        src="{{ asset('storage/' . ltrim($package->image, '/')) }}">
                                @else
                                    <i class="ti-book"></i>
                                @endif
                            </div>

                            <div>
                                <small>Tunas Bimbel</small>
                                <strong>{{ $package->name }}</strong>
                                <span>{{ $package->classes->count() }} kelas</span>
                            </div>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row">
                            <span>Periode</span>
                            <strong>{{ $this->billingLabel() }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Durasi akses</span>
                            <strong>{{ $this->periodLabel() }}</strong>
                        </div>

                        @if ($selectedPaymentMethod)
                            <div class="summary-row">
                                <span>Pembayaran</span>
                                <strong>{{ $selectedPaymentMethod->name }}</strong>
                            </div>
                        @endif

                        @if ($billing === 'yearly')
                            <div class="summary-row summary-row-muted">
                                <span>Harga normal 12 bulan</span>

                                <strong>
                                    Rp {{ number_format($this->regularYearlyPrice(), 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="summary-row summary-row-saving">
                                <span>Diskon tahunan</span>

                                <strong>
                                    - Rp {{ number_format($this->yearlySaving(), 0, ',', '.') }}
                                </strong>
                            </div>
                        @else
                            <div class="summary-row">
                                <span>Harga paket</span>

                                <strong>
                                    Rp {{ number_format($this->monthlyPrice(), 0, ',', '.') }}
                                </strong>
                            </div>
                        @endif

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <div>
                                <span>Total pembayaran</span>
                                <small>Nominal ini akan disimpan pada invoice.</small>
                            </div>

                            <strong>
                                Rp {{ number_format($this->totalPrice(), 0, ',', '.') }}
                            </strong>
                        </div>

                        @if ($billing === 'yearly')
                            <div class="monthly-equivalent">
                                <i class="ti-tag"></i>

                                <span>
                                    Setara sekitar
                                    <strong>
                                        Rp {{ number_format($this->yearlyPrice() / 12, 0, ',', '.') }}
                                    </strong>
                                    / bulan.
                                </span>
                            </div>
                        @endif

                        <div class="summary-benefits">
                            <div class="summary-benefit">
                                <span><i class="ti-check"></i></span>

                                <div>
                                    <strong>{{ $package->classes->count() }} kelas dalam paket</strong>
                                    <small>Aktif setelah pembayaran diverifikasi.</small>
                                </div>
                            </div>

                            <div class="summary-benefit">
                                <span><i class="ti-check"></i></span>

                                <div>
                                    <strong>Invoice unik</strong>
                                    <small>Setiap transaksi mempunyai nomor invoice dan token publik.</small>
                                </div>
                            </div>

                            <div class="summary-benefit">
                                <span><i class="ti-check"></i></span>

                                <div>
                                    <strong>Aktivasi terkontrol</strong>
                                    <small>Paket tidak langsung aktif hanya karena invoice dibuat.</small>
                                </div>
                            </div>
                        </div>

                        <div class="summary-security">
                            <i class="ti-lock"></i>

                            <span>
                                Harga dihitung kembali oleh backend berdasarkan data package.
                            </span>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </main>

    <footer class="checkout-footer">
        <div class="container">
            <div class="checkout-footer-inner">
                <span>&copy; {{ date('Y') }} Tunas Bimbel.</span>
                <span>Belajar • Tumbuh • Berprestasi</span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('checkout-review-ready', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</div>
