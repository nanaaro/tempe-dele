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

        <form method="POST" action="{{route('login.proses')}}">
            @csrf

            <div class="input-group">
                <label>EMAIL</label>
                <input type="email" name="email" placeholder="Enter Email">
            </div>

            <div class="input-group">
                <label>PASSWORD</label>
                <input type="password" name="password" placeholder="Enter Password">
            </div>

            <button type="submit" class="submit-btn">LOGIN</button>
        </form>
    </div>
</body>
</html>
