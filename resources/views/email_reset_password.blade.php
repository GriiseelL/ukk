<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>Klik tombol di bawah untuk reset password:</p>

    <a href="{{ url('/reset-password?token=' . $token) }}"
        style="padding:12px 20px;background:#4f46e5;color:#fff;border-radius:6px;text-decoration:none">
        Reset Password
    </a>

    <p>Link berlaku 15 menit.</p>
</body>

</html>