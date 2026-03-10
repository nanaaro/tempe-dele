<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Tempe Dele')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@toast-ui/calendar@2.1.3/dist/toastui-calendar.min.css">

  <style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type="number"] { -moz-appearance: textfield; }
  </style>

  @stack('styles')
</head>

<body class="bg-white">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- MAIN AREA -->
        <div class="flex-1 min-w-0 flex flex-col">

            <!-- NAVBAR -->
            @include('partials.navbar')

            <!-- PAGE CONTENT -->
            <main class="flex-1 px-2 py-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')
</body>

</html>
