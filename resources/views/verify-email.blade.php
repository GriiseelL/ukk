<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
</head>
<body>
    <h2>📩 Cek Email Kamu</h2>
    <p>Kami sudah mengirim link verifikasi ke email kamu.</p>
    <p>Silakan klik link tersebut untuk mengaktifkan akun.</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Kirim Ulang Email</button>
    </form>
</body>
</html>
