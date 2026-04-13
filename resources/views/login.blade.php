<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempe Dele</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
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
                        <div style="position: relative;">
                            <input type="password" name="password" id="passwordInput" placeholder="Enter Password"">
                            <button type="button" onclick="togglePassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0;">
                                <svg id="iconShow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="gray">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="iconHide" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="gray" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">LOGIN</button>
                </form>
            </div>

        </div>

        <script>
            function togglePassword() {
                const input    = document.getElementById('passwordInput');
                const iconShow = document.getElementById('iconShow');
                const iconHide = document.getElementById('iconHide');

                if (input.type === 'password') {
                    input.type       = 'text';
                    iconShow.style.display = 'none';
                    iconHide.style.display = 'block';
                } else {
                    input.type       = 'password';
                    iconShow.style.display = 'block';
                    iconHide.style.display = 'none';
                }
            }
        </script>
    </body>
</html>
