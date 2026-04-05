<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>
<body style="margin:0; padding:0; background:#f2f4f7; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

    <table width="500" cellpadding="0" cellspacing="0" style="background:#ffffff; margin:40px auto; border-radius:12px; padding:30px;">

        <tr>
            <td align="center">
                <h2 style="margin:0;">🎓 TunasBimbel</h2>
                <p style="color:#888;">Platform belajar modern</p>
            </td>
        </tr>

        <tr>
            <td style="padding-top:20px;">
                <h3>Halo {{ $user->name }} 👋</h3>
                <p>Terima kasih telah mendaftar di <b>TunasBimbel</b>.</p>
                <p>Silakan klik tombol di bawah untuk verifikasi email kamu:</p>
            </td>
        </tr>

        <tr>
            <td align="center" style="padding:30px 0;">
                <a href="{{ $url }}" 
                   style="background:#2F80ED; color:#fff; padding:14px 24px; text-decoration:none; border-radius:8px; font-weight:bold;">
                    Verifikasi Email
                </a>
            </td>
        </tr>

        <tr>
            <td>
                <p style="color:#666;">
                    Jika kamu tidak merasa mendaftar, abaikan email ini.
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding-top:20px;">
                <p>Salam hangat,<br><b>TunasBimbel 🚀</b></p>
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>