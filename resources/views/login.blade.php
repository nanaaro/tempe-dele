<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
    <body>
        <div class="wrapper">

            {{-- Logo --}}
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" />
                <div class="logo-text">
                    <span class="logo-title">TEMPE DELE</span>
                    <span class="logo-subtitle">Sistem Pengelolaan Dokumen Lembur</span>
                </div>
            </div>

            {{-- Form Login --}}
            <div class="container">
                <h2>LOGIN</h2>
                @if ($errors->has('login'))
                    <div style="color: red; margin-bottom: 10px;">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login.proses') }}">
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
            </div>

        </div>
    </body>
</html>
