<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6; padding:40px 0;">
<tr>
<td align="center">

    <table width="500" cellpadding="0" cellspacing="0" style="
        background:#ffffff;
        border-radius:12px;
        padding:30px;
        box-shadow:0 10px 25px rgba(0,0,0,0.05);
    ">

        <!-- HEADER -->
        <tr>
            <td align="center" style="padding-bottom:10px;">
                <h2 style="margin:0; color:#111827;">Reset Password</h2>
            </td>
        </tr>

        <!-- TEXT -->
        <tr>
            <td align="center" style="color:#6B7280; font-size:14px; padding-bottom:25px;">
                Klik tombol di bawah untuk mengatur ulang password akun kamu.
            </td>
        </tr>

        <!-- BUTTON (EMAIL SAFE) -->
        <tr>
            <td align="center">
                <a href="{{ $actionUrl ?? '#' }}" target="_blank"
                    style="
                        display:inline-block;
                        padding:14px 28px;
                        background:#4FACFE;
                        color:#ffffff;
                        text-decoration:none;
                        border-radius:8px;
                        font-weight:bold;
                        font-size:14px;
                ">
                    Reset Password
                </a>
            </td>
        </tr>

        <!-- LINK FALLBACK -->
        <tr>
            <td style="padding-top:25px; font-size:12px; color:#9CA3AF; text-align:center;">
                Jika tombol tidak bekerja, salin link berikut:
                <br><br>
                <a href="{{ $actionUrl }}" style="color:#4FACFE; word-break:break-all;">
                    {{ $actionUrl ?? 'URL ERROR' }}
                </a>
            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td style="padding-top:25px; font-size:12px; color:#9CA3AF; text-align:center;">
                Jika kamu tidak meminta reset password, abaikan email ini.
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>