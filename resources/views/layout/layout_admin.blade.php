<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        function ConfirmLogout() {
            return confirm("Apakah anda yakin ingin logout?");
        }
    </script>

</head>

<body class="bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <aside
            class="w-72 bg-white border-r border-slate-200 p-6 flex flex-col sticky top-0 h-screen transition-all shadow-sm">

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

                <h2 class="text-sm font-semibold text-slate-700 truncate">{{ Auth::user()->role }}</h2>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-4 px-3">Menu utama</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('admin') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class='bx bx-home text-lg'></i>
                    Dashboard
                </a>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Manajemen
                        Data</p>
                    <a href="{{ route('admin.user') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.user') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class='bx bx-user text-lg'></i>
                        Kelola user
                    </a>
                    <a href="{{ route('admin.area') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.area') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="bx bx-map text-lg"></i>
                        Kelola Area
                    </a>

                    <a href="{{ route('admin.tarif') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tarif') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="
                        bx bx-money text-lg"></i>
                        Kelola Tarif
                    </a>
                    <a href="{{ route('admin.jenis') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.jenis') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="bx bx-car text-lg"></i>
                        Jenis Kendaraan
                    </a>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-4 px-3">riwayat & log
                    </p>


                    <a href="{{ route('admin.riwayat') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.riwayat') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class='bx bx-history text-lg'></i>
                        Riwayat Transaksi
                    </a>
                    <a href="{{ route('admin.log') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.log') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="bx bx-time text-lg"></i>
                        Log aktivitas
                    </a>
                </div>
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-100">
                <form action="{{ route('logout') }}" method="POST"
                    onsubmit="return confirm('Apakah anda yakin ingin logout')">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all duration-200 group">
                        <i class='bx bx-log-out text-lg group-hover:-translate-x-1 transition'></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-h-screen">
            <div class="p-6">
                @yield('AdminContent')
            </div>
        </main>
    </div>
</body>

</html>
