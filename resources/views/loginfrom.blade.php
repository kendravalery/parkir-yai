<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Parkir Yai</title>
    @vite('resources/css/app.css')
</head>

<body class="h-screen bg-white">

    <div class="flex h-full">

        <!-- KIRI (VISUAL SERIUS) -->
        <div class="hidden md:flex w-1/1 relative bg-gray-900">
            <div class="absolute inset-0 bg-black/40"></div>

            <!-- Background image -->
            <img src="{{ asset('image/parkiran.jpg') }}" alt="">

            <!-- Overlay text -->
            <div class="absolute z-10 p-12 text-white bottom-0">
                <h1 class="text-3xl font-semibold mb-2">Parkir Yai</h1>
                <p class="text-sm opacity-80">Sistem parkir</p>
            </div>
        </div>

        <!-- KANAN (FORM) -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-6">

            <div class="w-full max-w-sm">

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- LOGO -->
                <div class="mb-8 ">
                    <div class="flex items-center justify-center mb-10">
                        {{-- <svg class="w-20 h-20 text-blue-600 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18M5 7v10M19 7v10" />
                        </svg> --}}
                        <img src="{{ asset('image/logo-smk-al-intisab (1).webp') }}" height="90" width="90"
                            alt="">
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800">Masuk ke akun</h2>
                    <p class="text-sm text-gray-500">Silakan login untuk melanjutkan</p>
                </div>

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="mb-4">
                        <input
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    </div>

                    <div class="mb-6">
                        <input
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            type="password" name="password" placeholder="Password">
                    </div>

                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                        Login
                    </button>
                </form>

            </div>
        </div>

    </div>

</body>

</html>
