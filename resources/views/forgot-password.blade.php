<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>reset password</title>
</head>

<body>
   <form method="POST" action="/forgot-password">
      @csrf
      <input type="email" name="email" placeholder="Email" required>
      <button type="submit">Kirim Link Reset</button>
   </form>
</body>

</html>