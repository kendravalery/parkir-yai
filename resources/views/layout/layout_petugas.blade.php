<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Petugas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <aside
            class="w-72 bg-white border-r border-slate-200 p-6 flex flex-col sticky top-0 h-screen transition-all shadow-sm">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
                <img src="{{ asset('image/logo-smk-al-intisab (1).webp') }}" class="w-10 h-10 object-contain"
                    alt="Logo">
                <div>
                    <h1 class="text-base font-semibold   text-slate-800 leading-none">Parkir Yai</h1>
                    <p class="text-xs text-slate-400">Management System</p>
                </div>
            </div>

            <!-- User -->
            <div class="px-6 py-4 border-b border-slate-100">
                <p class="text-xs text-slate-400">Login sebagai</p>

                <h2 class="text-sm font-semibold text-slate-700 truncate">{{ $user->name }}</h2>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-4 px-3">Menu utama</p>
                <a href="{{ route('petugas.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('petugas')
                        ? 'bg-blue-50 text-blue-700 shadow-sm '
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">

                    <i class='bx bx-home-alt text-lg'></i>
                    Dashboard
                </a>
                <a href="{{ route('masuk.from') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('masuk')
                        ? 'bg-blue-50 text-blue-700 shadow-sm '
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class='bx bx-log-in-circle text-lg '></i>
                    Kendaraan Masuk
                </a>
                <a href="{{ route('keluar.from') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('keluar')
                        ? 'bg-blue-50 text-blue-700 shadow-sm '
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class='bx bx-log-out-circle text-lg '></i>
                    Kendaraan Keluar
                </a>
                <a href="{{ route('Riwayat') }}"
                    class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('riwayat') ? 'bg-blue-50 text-blue-700 shadow-sm ' : 'text-slate-600 hover:bg-blue-50 hover:text-slate-900' }} ">
                    <i class='bx bx-history text-lg '></i>
                    Riwayat Transaksi
                </a>
            </nav>
            <div class="mt-auto pt-6 border-t border-slate-100">
                <form action="{{ route('logout') }}" method="POST"
                    onsubmit="return confirm('Apakah anda yakin ingin logout')">
                    @csrf
                    <button
                        class="flex w-full items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 bg-red-50  hover:bg-red-100 rounded-xl transition-all duration-200 group">

                        <i class='bx bx-log-out text-lg group-hover:-translate-x-1 transition '></i>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        <main class="flex-1 flex flex-col  min-h-screen">
            <div class="p-6">
                @yield('PetugasContent')
            </div>

        </main>
    </div>
</body>

</html>
