<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="container">
        <h2>LOGIN</h2>

        <form method="POST" action="#">
            @csrf

            <div class="input-group">
                <label>USERNAME</label>
                <input type="text" name="username" placeholder="Enter Username">
            </div>

            <div class="input-group">
                <label>PASSWORD</label>
                <input type="password" name="password" placeholder="Enter Password">
            </div>

            <button type="submit" class="submit-btn">LOGIN</button>
        </form>

        <div class="footer">
            <a href="#">FORGOT PASSWORD?</a>
        </div>
    </div>
</body>
</html>
