<div class="tunas-payment-page">
    <style>
        :root {
            --pay-primary: #5b4ee8;
            --pay-primary-dark: #4437cd;
            --pay-primary-soft: #efedff;
            --pay-green: #13b883;
            --pay-green-soft: #eaf9f3;
            --pay-yellow: #eaa31a;
            --pay-yellow-soft: #fff7e6;
            --pay-red: #dc4c5a;
            --pay-red-soft: #fff0f2;
            --pay-dark: #182033;
            --pay-text: #5f6679;
            --pay-muted: #9298a8;
            --pay-border: #e7e9f1;
            --pay-bg: #f7f8fc;
            --pay-white: #fff;
            --pay-shadow: 0 20px 60px rgba(35, 39, 72, .09);
        }

        .tunas-payment-page {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 8% 8%, rgba(91, 78, 232, .06), transparent 22%),
                radial-gradient(circle at 94% 38%, rgba(19, 184, 131, .05), transparent 18%),
                var(--pay-bg);
            color: var(--pay-dark);
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .tunas-payment-page *,
        .tunas-payment-page *::before,
        .tunas-payment-page *::after {
            box-sizing: border-box;
        }

        .payment-topbar {
            position: relative;
            z-index: 10;
            border-bottom: 1px solid var(--pay-border);
            background: rgba(255, 255, 255, .90);
            backdrop-filter: blur(18px);
        }

        .payment-topbar-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .payment-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--pay-dark) !important;
            text-decoration: none !important;
        }

        .payment-brand-mark {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--pay-primary), #7863ec);
            color: #fff;
            box-shadow: 0 9px 24px rgba(91, 78, 232, .20);
        }

        .payment-brand-copy strong,
        .payment-brand-copy small {
            display: block;
        }

        .payment-brand-copy strong {
            font-size: 18px;
            font-weight: 850;
        }

        .payment-brand-copy small {
            margin-top: 4px;
            color: var(--pay-muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .payment-back-link {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--pay-border);
            border-radius: 11px;
            background: #fff;
            color: var(--pay-text) !important;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none !important;
        }

        .payment-main {
            padding: 52px 0 80px;
        }

        .payment-heading {
            max-width: 760px;
            margin-bottom: 28px;
        }

        .payment-heading-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 12px;
            color: var(--pay-primary);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .payment-heading h1 {
            margin: 0;
            color: var(--pay-dark);
            font-size: clamp(30px, 4vw, 43px);
            font-weight: 850;
            line-height: 1.16;
            letter-spacing: -.04em;
        }

        .payment-heading p {
            max-width: 660px;
            margin: 13px 0 0;
            color: var(--pay-text);
            font-size: 14px;
            line-height: 1.75;
        }

        .payment-card {
            margin-bottom: 20px;
            padding: 26px;
            border: 1px solid var(--pay-border);
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 10px 32px rgba(35, 39, 72, .055);
        }

        .invoice-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            padding: 26px;
            border-radius: 22px;
            background: linear-gradient(135deg, #5044d5, #7660eb);
            color: #fff;
            box-shadow: 0 20px 45px rgba(91, 78, 232, .18);
        }

        .invoice-hero::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            right: -70px;
            top: -80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .invoice-hero-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
        }

        .invoice-label {
            display: block;
            margin-bottom: 7px;
            color: rgba(255, 255, 255, .70);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .invoice-number {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 9px;
        }

        .invoice-number strong {
            font-size: 19px;
            font-weight: 900;
            letter-spacing: -.02em;
        }

        .copy-button {
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 9px;
            background: rgba(255, 255, 255, .11);
            color: #fff;
            padding: 7px 9px;
            font-size: 9px;
            font-weight: 800;
            cursor: pointer;
        }

        .payment-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex: 0 0 auto;
            padding: 8px 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .13);
            color: #fff;
            font-size: 9px;
            font-weight: 850;
        }

        .invoice-total {
            position: relative;
            z-index: 2;
            margin-top: 25px;
        }

        .invoice-total span,
        .invoice-total strong {
            display: block;
        }

        .invoice-total span {
            color: rgba(255, 255, 255, .70);
            font-size: 10px;
        }

        .invoice-total strong {
            margin-top: 6px;
            font-size: clamp(28px, 4vw, 38px);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .invoice-meta {
            position: relative;
            z-index: 2;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 18px;
            color: rgba(255, 255, 255, .72);
            font-size: 9px;
        }

        .invoice-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .countdown-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 20px;
            padding: 17px 19px;
            border: 1px solid #f1dfb3;
            border-radius: 17px;
            background: var(--pay-yellow-soft);
        }

        .countdown-copy strong,
        .countdown-copy span {
            display: block;
        }

        .countdown-copy strong {
            color: #72500d;
            font-size: 11px;
            font-weight: 850;
        }

        .countdown-copy span {
            margin-top: 3px;
            color: #9a762d;
            font-size: 9px;
        }

        .countdown-value {
            flex: 0 0 auto;
            color: #8a6113;
            font-size: 17px;
            font-weight: 900;
            letter-spacing: .04em;
        }

        .payment-card-heading {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .payment-card-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            flex: 0 0 42px;
            border-radius: 13px;
            background: var(--pay-primary-soft);
            color: var(--pay-primary);
        }

        .payment-card-heading h2 {
            margin: 0;
            color: var(--pay-dark);
            font-size: 17px;
            font-weight: 850;
        }

        .payment-card-heading p {
            margin: 4px 0 0;
            color: var(--pay-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        .account-box {
            padding: 18px;
            border: 1px solid #e5e2ff;
            border-radius: 16px;
            background: #faf9ff;
        }

        .account-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 11px 0;
            border-bottom: 1px solid #ebe9f6;
        }

        .account-row:last-child {
            border-bottom: 0;
        }

        .account-row span,
        .account-row strong {
            display: block;
        }

        .account-row span {
            color: var(--pay-muted);
            font-size: 9px;
        }

        .account-row strong {
            margin-top: 3px;
            color: var(--pay-dark);
            font-size: 12px;
            font-weight: 850;
            word-break: break-word;
        }

        .copy-light {
            flex: 0 0 auto;
            border: 1px solid #ddd9ff;
            border-radius: 9px;
            background: #fff;
            color: var(--pay-primary);
            padding: 7px 9px;
            font-size: 8px;
            font-weight: 850;
            cursor: pointer;
        }

        .qris-wrap {
            text-align: center;
        }

        .qris-image {
            width: min(300px, 100%);
            display: block;
            margin: 0 auto 16px;
            padding: 12px;
            border: 1px solid var(--pay-border);
            border-radius: 18px;
            background: #fff;
            object-fit: contain;
        }

        .qris-note {
            max-width: 420px;
            margin: 0 auto;
            color: var(--pay-text);
            font-size: 10px;
            line-height: 1.65;
        }

        .instruction-box {
            margin-top: 17px;
            padding: 15px;
            border: 1px solid var(--pay-border);
            border-radius: 13px;
            background: #fbfbfd;
        }

        .instruction-box strong {
            display: block;
            margin-bottom: 6px;
            color: var(--pay-dark);
            font-size: 10px;
            font-weight: 850;
        }

        .instruction-box p {
            margin: 0;
            white-space: pre-line;
            color: var(--pay-text);
            font-size: 9px;
            line-height: 1.7;
        }

        .upload-zone {
            position: relative;
            display: block;
            padding: 28px 20px;
            border: 1.5px dashed #d6d3ef;
            border-radius: 17px;
            background: #faf9ff;
            text-align: center;
            cursor: pointer;
        }

        .upload-zone:hover {
            border-color: var(--pay-primary);
        }

        .upload-zone input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            margin: 0 auto 12px;
            border-radius: 15px;
            background: var(--pay-primary-soft);
            color: var(--pay-primary);
            font-size: 18px;
        }

        .upload-zone strong,
        .upload-zone small {
            display: block;
        }

        .upload-zone strong {
            color: var(--pay-dark);
            font-size: 11px;
            font-weight: 850;
        }

        .upload-zone small {
            margin-top: 5px;
            color: var(--pay-muted);
            font-size: 9px;
        }

        .selected-proof {
            margin-top: 12px;
            padding: 11px 13px;
            border: 1px solid #dfeee8;
            border-radius: 11px;
            background: #f4fbf8;
            color: #376d5a;
            font-size: 9px;
            word-break: break-word;
        }

        .payment-error {
            margin-top: 9px;
            color: var(--pay-red);
            font-size: 9px;
            font-weight: 700;
        }

        .btn-payment-primary,
        .btn-payment-secondary {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 18px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 10px;
            font-weight: 850;
            text-decoration: none !important;
            transition: .2s ease;
        }

        .btn-payment-primary {
            width: 100%;
            margin-top: 16px;
            border: 0;
            background: var(--pay-primary);
            color: #fff !important;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(91, 78, 232, .17);
        }

        .btn-payment-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            background: var(--pay-primary-dark);
        }

        .btn-payment-primary:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .btn-payment-secondary {
            border: 1px solid var(--pay-border);
            background: #fff;
            color: var(--pay-text) !important;
        }

        .status-panel {
            padding: 28px;
            border-radius: 20px;
            text-align: center;
        }

        .status-panel-icon {
            width: 62px;
            height: 62px;
            display: grid;
            place-items: center;
            margin: 0 auto 16px;
            border-radius: 19px;
            font-size: 22px;
        }

        .status-panel h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 850;
        }

        .status-panel p {
            max-width: 520px;
            margin: 9px auto 0;
            font-size: 10px;
            line-height: 1.7;
        }

        .status-waiting {
            border: 1px solid #f0dfb8;
            background: var(--pay-yellow-soft);
        }

        .status-waiting .status-panel-icon {
            background: #ffedc0;
            color: #a36f0d;
        }

        .status-waiting h2 {
            color: #6e4d10;
        }

        .status-waiting p {
            color: #916d27;
        }

        .status-paid {
            border: 1px solid #cfeee3;
            background: var(--pay-green-soft);
        }

        .status-paid .status-panel-icon {
            background: #d8f5eb;
            color: var(--pay-green);
        }

        .status-paid h2 {
            color: #17684f;
        }

        .status-paid p {
            color: #397b68;
        }

        .status-rejected {
            border: 1px solid #f1cfd4;
            background: var(--pay-red-soft);
        }

        .status-rejected .status-panel-icon {
            background: #ffe0e4;
            color: var(--pay-red);
        }

        .status-rejected h2 {
            color: #8b2834;
        }

        .status-rejected p {
            color: #a6535d;
        }

        .status-neutral {
            border: 1px solid var(--pay-border);
            background: #f7f8fb;
        }

        .status-neutral .status-panel-icon {
            background: #eceef4;
            color: #777f92;
        }

        .status-neutral h2 {
            color: var(--pay-dark);
        }

        .status-neutral p {
            color: var(--pay-text);
        }

        .rejection-reason {
            max-width: 560px;
            margin: 16px auto 0;
            padding: 12px 14px;
            border: 1px solid #efc8ce;
            border-radius: 12px;
            background: #fff;
            color: #88323d;
            font-size: 9px;
            text-align: left;
            line-height: 1.6;
        }

        .summary-card {
            position: sticky;
            top: 24px;
        }

        .summary-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .summary-title span {
            display: block;
            color: var(--pay-primary);
            font-size: 8px;
            font-weight: 900;
            letter-spacing: .1em;
        }

        .summary-title h3 {
            margin: 4px 0 0;
            font-size: 17px;
            font-weight: 850;
        }

        .summary-title i {
            color: #c9c6e9;
            font-size: 20px;
        }

        .summary-package {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px;
            border-radius: 14px;
            background: #f8f8fc;
        }

        .summary-package-icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            flex: 0 0 48px;
            border-radius: 12px;
            background: var(--pay-primary-soft);
            color: var(--pay-primary);
        }

        .summary-package strong,
        .summary-package span {
            display: block;
        }

        .summary-package strong {
            font-size: 10px;
            font-weight: 850;
        }

        .summary-package span {
            margin-top: 3px;
            color: var(--pay-muted);
            font-size: 8px;
        }

        .summary-divider {
            height: 1px;
            margin: 17px 0;
            background: var(--pay-border);
        }

        .summary-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin: 10px 0;
            font-size: 9px;
        }

        .summary-row>span {
            color: var(--pay-muted);
        }

        .summary-row>strong {
            max-width: 60%;
            color: var(--pay-dark);
            font-weight: 850;
            text-align: right;
            word-break: break-word;
        }

        .summary-final {
            margin-top: 15px;
            padding-top: 16px;
            border-top: 1px solid var(--pay-border);
        }

        .summary-final span,
        .summary-final strong {
            display: block;
        }

        .summary-final span {
            color: var(--pay-muted);
            font-size: 9px;
        }

        .summary-final strong {
            margin-top: 5px;
            color: var(--pay-primary);
            font-size: 23px;
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .privacy-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 17px;
            padding: 11px;
            border-radius: 11px;
            background: #f7f8fb;
            color: var(--pay-muted);
            font-size: 8px;
            line-height: 1.55;
        }

        .privacy-note i {
            color: var(--pay-primary);
            margin-top: 2px;
        }

        .flash-success {
            margin-bottom: 18px;
            padding: 13px 15px;
            border: 1px solid #cfeee3;
            border-radius: 13px;
            background: var(--pay-green-soft);
            color: #236f59;
            font-size: 10px;
            font-weight: 700;
        }

        .payment-spinner {
            width: 13px;
            height: 13px;
            border: 2px solid rgba(255, 255, 255, .35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: payment-spin .7s linear infinite;
        }

        @keyframes payment-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .payment-footer {
            border-top: 1px solid var(--pay-border);
            background: #fff;
        }

        .payment-footer-inner {
            min-height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            color: var(--pay-muted);
            font-size: 9px;
        }

        @media (max-width: 991.98px) {
            .summary-card {
                position: static;
            }
        }

        @media (max-width: 767.98px) {
            .payment-main {
                padding: 36px 0 60px;
            }

            .invoice-hero-top,
            .countdown-card,
            .account-row {
                align-items: flex-start;
            }

            .invoice-hero-top {
                flex-direction: column;
            }

            .payment-status-badge {
                align-self: flex-start;
            }
        }

        @media (max-width: 575.98px) {

            .payment-brand-copy small,
            .payment-back-link span {
                display: none;
            }

            .payment-back-link {
                width: 40px;
                padding: 0;
            }

            .payment-card,
            .invoice-hero {
                padding: 20px;
                border-radius: 18px;
            }

            .account-row {
                flex-direction: column;
            }

            .payment-footer-inner {
                flex-direction: column;
                justify-content: center;
                text-align: center;
            }
        }
    </style>

    <header class="payment-topbar">
        <div class="container">
            <div class="payment-topbar-inner">
                <a class="payment-brand" href="{{ route('home') }}">
                    <span class="payment-brand-mark">
                        <i class="ti-book"></i>
                    </span>

                    <span class="payment-brand-copy">
                        <strong>Tunas Bimbel</strong>
                        <small>Pembayaran Paket Belajar</small>
                    </span>
                </a>

                <a class="payment-back-link" href="{{ route('home') }}#paket">
                    <i class="ti-angle-left"></i>
                    <span>Kembali ke Paket</span>
                </a>
            </div>
        </div>
    </header>

    <main class="payment-main">
        <div class="container">
            <div class="payment-heading">
                <div class="payment-heading-badge">
                    <i class="ti-receipt"></i>
                    Invoice Pembayaran
                </div>

                <h1>Selesaikan pembayaran paket Tunas Bimbel.</h1>

                <p>
                    Gunakan informasi pembayaran pada invoice ini. Setelah pembayaran dilakukan,
                    kirim bukti pembayaran agar administrator dapat melakukan verifikasi.
                </p>
            </div>

            @if (session()->has('success'))
                <div class="flash-success">
                    <i class="ti-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row align-items-start">
                <div class="col-lg-8">
                    {{-- INVOICE HERO --}}
                    <section class="invoice-hero">
                        <div class="invoice-hero-top">
                            <div>
                                <span class="invoice-label">Nomor Invoice</span>

                                <div class="invoice-number">
                                    <strong>{{ $transaction->invoice_no }}</strong>

                                    <button class="copy-button js-payment-copy"
                                        data-copy="{{ $transaction->invoice_no }}" type="button">
                                        <i class="ti-files"></i>
                                        Salin
                                    </button>
                                </div>
                            </div>

                            <span class="payment-status-badge">
                                <i class="ti-info-alt"></i>
                                {{ $transaction->status_label }}
                            </span>
                        </div>

                        <div class="invoice-total">
                            <span>Total yang harus dibayar</span>

                            <strong>
                                Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="invoice-meta">
                            <span>
                                <i class="ti-package"></i>
                                {{ $transaction->package_name }}
                            </span>

                            <span>
                                <i class="ti-calendar"></i>
                                {{ $transaction->billing_label }}
                            </span>

                            <span>
                                <i class="ti-timer"></i>
                                {{ $transaction->duration_months }} bulan akses
                            </span>
                        </div>
                    </section>

                    {{-- COUNTDOWN --}}
                    @if (in_array(
                            $transaction->status,
                            [\App\Models\Transaction::STATUS_PENDING_PAYMENT, \App\Models\Transaction::STATUS_REJECTED],
                            true) && $transaction->expires_at)
                        <section class="countdown-card">
                            <div class="countdown-copy">
                                <strong>Batas waktu pembayaran</strong>
                                <span>
                                    Invoice berakhir pada
                                    {{ $transaction->expires_at->format('d M Y, H:i') }}
                                </span>
                            </div>

                            <div class="countdown-value" data-expiry="{{ $transaction->expires_at->timestamp }}"
                                id="payment-countdown" wire:ignore>
                                --:--:--
                            </div>
                        </section>
                    @endif

                    {{-- STATUS PAID --}}
                    @if ($transaction->status === \App\Models\Transaction::STATUS_PAID)
                        <section class="payment-card">
                            <div class="status-panel status-paid">
                                <div class="status-panel-icon">
                                    <i class="ti-check"></i>
                                </div>

                                <h2>Pembayaran berhasil diverifikasi.</h2>

                                <p>
                                    Paket <strong>{{ $transaction->package_name }}</strong>
                                    sudah diaktifkan pada akun student
                                    <strong>{{ $this->maskedEmail() }}</strong>.
                                    Silakan masuk ke aplikasi Tunas Bimbel untuk mulai belajar.
                                </p>
                            </div>
                        </section>

                        {{-- WAITING --}}
                    @elseif($transaction->status === \App\Models\Transaction::STATUS_WAITING_VERIFICATION)
                        <section class="payment-card">
                            <div class="status-panel status-waiting">
                                <div class="status-panel-icon">
                                    <i class="ti-time"></i>
                                </div>

                                <h2>Pembayaran sedang diverifikasi.</h2>

                                <p>
                                    Bukti pembayaran sudah kami terima.
                                    Administrator/admin akan memeriksa nominal dan informasi pembayaran.
                                    Paket belum aktif sampai pembayaran dinyatakan valid.
                                </p>
                            </div>

                            @if ($transaction->proof_original_name)
                                <div class="instruction-box">
                                    <strong>Bukti yang dikirim</strong>
                                    <p>
                                        {{ $transaction->proof_original_name }}
                                        @if ($transaction->proof_uploaded_at)
                                            — dikirim {{ $transaction->proof_uploaded_at->format('d M Y, H:i') }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </section>

                        {{-- EXPIRED --}}
                    @elseif($transaction->status === \App\Models\Transaction::STATUS_EXPIRED)
                        <section class="payment-card">
                            <div class="status-panel status-neutral">
                                <div class="status-panel-icon">
                                    <i class="ti-timer"></i>
                                </div>

                                <h2>Invoice sudah kedaluwarsa.</h2>

                                <p>
                                    Batas pembayaran invoice ini telah berakhir.
                                    Silakan kembali ke halaman paket dan buat invoice baru.
                                </p>

                                <a class="btn-payment-secondary" href="{{ route('home') }}#paket"
                                    style="margin-top:16px;">
                                    Pilih Paket Kembali
                                </a>
                            </div>
                        </section>

                        {{-- CANCELLED --}}
                    @elseif($transaction->status === \App\Models\Transaction::STATUS_CANCELLED)
                        <section class="payment-card">
                            <div class="status-panel status-neutral">
                                <div class="status-panel-icon">
                                    <i class="ti-close"></i>
                                </div>

                                <h2>Invoice sudah dibatalkan.</h2>

                                <p>
                                    Invoice ini tidak lagi aktif.
                                    Silakan kembali ke halaman paket untuk membuat pesanan baru.
                                </p>

                                <a class="btn-payment-secondary" href="{{ route('home') }}#paket"
                                    style="margin-top:16px;">
                                    Kembali ke Paket
                                </a>
                            </div>
                        </section>
                    @else
                        {{-- REJECTED WARNING --}}
                        @if ($transaction->status === \App\Models\Transaction::STATUS_REJECTED)
                            <section class="payment-card">
                                <div class="status-panel status-rejected">
                                    <div class="status-panel-icon">
                                        <i class="ti-alert"></i>
                                    </div>

                                    <h2>Bukti pembayaran ditolak.</h2>

                                    <p>
                                        Periksa alasan dari administrator, lalu kirim kembali
                                        bukti pembayaran yang benar selama invoice masih aktif.
                                    </p>

                                    @if ($transaction->rejection_reason)
                                        <div class="rejection-reason">
                                            <strong>Alasan penolakan:</strong><br>
                                            {{ $transaction->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                            </section>
                        @endif

                        {{-- PAYMENT INFORMATION --}}
                        <section class="payment-card">
                            <div class="payment-card-heading">
                                <div class="payment-card-icon">
                                    @if ($this->isQris())
                                        <i class="ti-layout-grid2"></i>
                                    @else
                                        <i class="ti-credit-card"></i>
                                    @endif
                                </div>

                                <div>
                                    <h2>{{ $transaction->payment_method_name ?: 'Metode Pembayaran' }}</h2>

                                    <p>
                                        Gunakan informasi berikut untuk melakukan pembayaran.
                                    </p>
                                </div>
                            </div>

                            @if ($this->isQris() && $transaction->paymentMethod?->qr_image)
                                <div class="qris-wrap">
                                    <img alt="QRIS {{ $transaction->payment_method_name }}" class="qris-image"
                                        src="{{ asset('storage/' . ltrim($transaction->paymentMethod->qr_image, '/')) }}">

                                    <div class="qris-note">
                                        Scan QRIS menggunakan aplikasi mobile banking atau e-wallet.
                                        Pastikan nominal yang dibayarkan sesuai total invoice.
                                    </div>
                                </div>
                            @else
                                <div class="account-box">
                                    @if ($transaction->payment_provider)
                                        <div class="account-row">
                                            <div>
                                                <span>Bank / Provider</span>
                                                <strong>{{ $transaction->payment_provider }}</strong>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($transaction->payment_account_number)
                                        <div class="account-row">
                                            <div>
                                                <span>
                                                    {{ $this->isEwallet() ? 'Nomor E-Wallet' : 'Nomor Rekening / Akun' }}
                                                </span>

                                                <strong>
                                                    {{ $transaction->payment_account_number }}
                                                </strong>
                                            </div>

                                            <button class="copy-light js-payment-copy"
                                                data-copy="{{ $transaction->payment_account_number }}" type="button">
                                                Salin
                                            </button>
                                        </div>
                                    @endif

                                    @if ($transaction->payment_account_name)
                                        <div class="account-row">
                                            <div>
                                                <span>Atas Nama</span>
                                                <strong>{{ $transaction->payment_account_name }}</strong>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="account-row">
                                        <div>
                                            <span>Nominal Transfer</span>
                                            <strong>
                                                Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}
                                            </strong>
                                        </div>

                                        <button class="copy-light js-payment-copy"
                                            data-copy="{{ (int) $transaction->total }}" type="button">
                                            Salin
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if ($transaction->paymentMethod?->instructions)
                                <div class="instruction-box">
                                    <strong>Petunjuk pembayaran</strong>

                                    <p>{{ $transaction->paymentMethod->instructions }}</p>
                                </div>
                            @endif
                        </section>

                        {{-- UPLOAD / CONFIRM --}}
                        <section class="payment-card">
                            <div class="payment-card-heading">
                                <div class="payment-card-icon">
                                    <i class="ti-upload"></i>
                                </div>

                                <div>
                                    <h2>Konfirmasi pembayaran</h2>

                                    <p>
                                        Setelah pembayaran dilakukan, kirim bukti agar admin dapat memverifikasinya.
                                    </p>
                                </div>
                            </div>

                            @if ($this->requiresProof())
                                <form wire:submit.prevent="uploadProof">
                                    <label class="upload-zone">
                                        <input accept=".jpg,.jpeg,.png,.pdf" type="file" wire:model="proof">

                                        <span class="upload-icon">
                                            <i class="ti-cloud-up"></i>
                                        </span>

                                        <strong>Pilih bukti pembayaran</strong>

                                        <small>
                                            JPG, JPEG, PNG, atau PDF • Maksimal 5 MB
                                        </small>
                                    </label>

                                    <div class="selected-proof" wire:loading wire:target="proof">
                                        Mengunggah file sementara...
                                    </div>

                                    @if ($proof)
                                        <div class="selected-proof">
                                            <strong>File dipilih:</strong>
                                            {{ $proof->getClientOriginalName() }}
                                        </div>
                                    @endif

                                    @error('proof')
                                        <div class="payment-error">
                                            <i class="ti-alert"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <button class="btn-payment-primary" type="submit" wire:loading.attr="disabled"
                                        wire:target="proof,uploadProof">
                                        <span wire:loading.remove wire:target="uploadProof">
                                            Kirim Bukti Pembayaran
                                            <i class="ti-arrow-right"></i>
                                        </span>

                                        <span style="display:inline-flex;align-items:center;gap:8px;" wire:loading
                                            wire:target="uploadProof">
                                            <span class="payment-spinner"></span>
                                            Mengirim bukti...
                                        </span>
                                    </button>
                                </form>
                            @else
                                <div class="instruction-box">
                                    <strong>Tidak diperlukan upload bukti</strong>

                                    <p>
                                        Metode pembayaran ini dikonfigurasi tanpa kewajiban upload file.
                                        Tekan tombol di bawah setelah Anda menyelesaikan pembayaran.
                                    </p>
                                </div>

                                @error('proof')
                                    <div class="payment-error">
                                        <i class="ti-alert"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <button class="btn-payment-primary" type="button" wire:click="confirmWithoutProof"
                                    wire:loading.attr="disabled" wire:target="confirmWithoutProof">
                                    <span wire:loading.remove wire:target="confirmWithoutProof">
                                        Konfirmasi Sudah Membayar
                                        <i class="ti-arrow-right"></i>
                                    </span>

                                    <span style="display:inline-flex;align-items:center;gap:8px;" wire:loading
                                        wire:target="confirmWithoutProof">
                                        <span class="payment-spinner"></span>
                                        Mengirim konfirmasi...
                                    </span>
                                </button>
                            @endif

                            <div class="privacy-note">
                                <i class="ti-lock"></i>

                                <span>
                                    Bukti pembayaran disimpan pada storage private dan tidak tersedia
                                    melalui URL publik. Hanya administrator/admin yang berwenang
                                    yang akan dapat membukanya pada tahap verifikasi.
                                </span>
                            </div>
                        </section>
                    @endif
                </div>

                {{-- SUMMARY --}}
                <div class="col-lg-4">
                    <aside class="payment-card summary-card">
                        <div class="summary-title">
                            <div>
                                <span>RINGKASAN INVOICE</span>
                                <h3>Detail pesanan</h3>
                            </div>

                            <i class="ti-receipt"></i>
                        </div>

                        <div class="summary-package">
                            <div class="summary-package-icon">
                                <i class="ti-book"></i>
                            </div>

                            <div>
                                <strong>{{ $transaction->package_name }}</strong>
                                <span>
                                    Paket belajar Tunas Bimbel
                                </span>
                            </div>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row">
                            <span>Invoice</span>
                            <strong>{{ $transaction->invoice_no }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Periode</span>
                            <strong>{{ $transaction->billing_label }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Durasi akses</span>
                            <strong>{{ $transaction->duration_months }} bulan</strong>
                        </div>

                        <div class="summary-row">
                            <span>Metode</span>
                            <strong>
                                {{ $transaction->payment_method_name ?: '-' }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Nama</span>
                            <strong>{{ $transaction->customer_name }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Email student</span>
                            <strong>{{ $this->maskedEmail() }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>WhatsApp</span>
                            <strong>{{ $this->maskedPhone() }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Status</span>
                            <strong>{{ $transaction->status_label }}</strong>
                        </div>

                        @if ((float) $transaction->discount > 0)
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <strong>
                                    Rp {{ number_format((float) $transaction->subtotal, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="summary-row">
                                <span>Diskon</span>
                                <strong style="color:#13b883;">
                                    - Rp {{ number_format((float) $transaction->discount, 0, ',', '.') }}
                                </strong>
                            </div>
                        @endif

                        <div class="summary-final">
                            <span>Total pembayaran</span>

                            <strong>
                                Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="privacy-note">
                            <i class="ti-info-alt"></i>

                            <span>
                                Paket baru aktif setelah pembayaran diverifikasi oleh administrator/admin.
                            </span>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </main>

    <footer class="payment-footer">
        <div class="container">
            <div class="payment-footer-inner">
                <span>&copy; {{ date('Y') }} Tunas Bimbel.</span>
                <span>Belajar • Tumbuh • Berprestasi</span>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const initPaymentPage = () => {
                const countdown = document.getElementById('payment-countdown');

                if (countdown && !countdown.dataset.initialized) {
                    countdown.dataset.initialized = '1';

                    const expirySeconds = Number(countdown.dataset.expiry || 0);
                    const expiryTime = expirySeconds * 1000;

                    const renderCountdown = () => {
                        const remaining = expiryTime - Date.now();

                        if (remaining <= 0) {
                            countdown.textContent = '00:00:00';

                            if (!countdown.dataset.reloading) {
                                countdown.dataset.reloading = '1';

                                window.setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }

                            return false;
                        }

                        const totalSeconds = Math.floor(remaining / 1000);
                        const hours = Math.floor(totalSeconds / 3600);
                        const minutes = Math.floor((totalSeconds % 3600) / 60);
                        const seconds = totalSeconds % 60;

                        countdown.textContent =
                            String(hours).padStart(2, '0') +
                            ':' +
                            String(minutes).padStart(2, '0') +
                            ':' +
                            String(seconds).padStart(2, '0');

                        return true;
                    };

                    renderCountdown();

                    const timer = window.setInterval(() => {
                        if (!renderCountdown()) {
                            window.clearInterval(timer);
                        }
                    }, 1000);
                }
            };

            if (!window.__tunasPaymentCopyBound) {
                window.__tunasPaymentCopyBound = true;

                document.addEventListener('click', async (event) => {
                    const button = event.target.closest('.js-payment-copy');

                    if (!button) {
                        return;
                    }

                    const text = button.dataset.copy ?? '';
                    const original = button.innerHTML;

                    try {
                        await navigator.clipboard.writeText(text);
                        button.textContent = 'Tersalin';
                    } catch (error) {
                        const textarea = document.createElement('textarea');
                        textarea.value = text;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';

                        document.body.appendChild(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        textarea.remove();

                        button.textContent = 'Tersalin';
                    }

                    window.setTimeout(() => {
                        button.innerHTML = original;
                    }, 1400);
                });
            }

            initPaymentPage();

            document.addEventListener(
                'livewire:navigated',
                initPaymentPage
            );
        })();
    </script>
</div>
