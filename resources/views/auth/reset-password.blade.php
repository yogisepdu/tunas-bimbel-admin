<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #22C1DC, #4FACFE);
        }

        .card {
            width: 100%;
            max-width: 400px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 14px;
        }

        .input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .input:focus {
            border-color: #22C1DC;
            box-shadow: 0 0 0 3px rgba(34,193,220,0.2);
        }

        .readonly {
            background: #f3f4f6;
            color: #6B7280;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .error-text {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg,#22C1DC,#4FACFE);
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(34,193,220,0.4);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 13px;
            color: #6B7280;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="title">Reset Password 🔐</div>
    <div class="subtitle">Masukkan password baru untuk akun kamu</div>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- EMAIL -->
        <div class="input-group">
            <input type="email" name="email" value="{{ $email }}" readonly class="input readonly">
        </div>

        <!-- PASSWORD -->
        <div class="input-group password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password baru" class="input">
            <span class="toggle" onclick="toggle('password', this)">👁️</span>

            @error('password')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- CONFIRM -->
        <div class="input-group password-wrapper">
            <input type="password" name="password_confirmation" id="confirm" placeholder="Konfirmasi password" class="input">
            <span class="toggle" onclick="toggle('confirm', this)">👁️</span>

            @error('password_confirmation')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn">
            Reset Password
        </button>
    </form>

</div>

<script>
function toggle(id, el) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        el.innerText = "🙈";
    } else {
        input.type = "password";
        el.innerText = "👁️";
    }
}
</script>

</body>
</html>