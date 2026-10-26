@extends('layout.layout_petugas')

@section('PetugasContent')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 rounded-xl shadow-lg text-white">
            <div class="text-2xl font-bold tracking-wide">
                Sistem Parkir - Kendaraan Masuk
            </div>
            <div class="text-sm opacity-80">
                {{ date('d M Y - H:i') }}
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg shadow">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif


        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

            {{-- Total Hari Ini --}}
            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-gray-500 text-sm">Total Kendaraan Masuk Hari Ini</div>
                <div class="text-2xl font-bold text-blue-600">
                    {{ $totalHariIni ?? 0 }}
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-gray-500 text-sm ">kapasitas</div>
                <div class="text-2xl font-bold text-blue-600"> {{ $kapasitas ?? 0 }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-gray-500 text-sm ">terisi</div>
                <div class="text-2xl font-bold text-blue-600">{{ $terisi ?? 0 }} </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-gray-500 text-sm ">sisa</div>
                <div class="text-2xl font-bold {{ ($sisa ?? 0) <= 0 ? 'text-red-600 ' : 'text-blue-600' }}">
                    {{ $sisa ?? 0 }}</div>
            </div>

        </div>

        {{-- FORM INPUT --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <form action="{{ route('masuk.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium mb-1">Plat Nomor</label>
                    <input type="text" name="plat_nomor" oninput="this.value = this.value.toUpperCase()"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Contoh: B 1234 ABC" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Jenis Kendaraan</label>
                    <select name="jenis_kendaraan_id"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                        <option value="">Pilih Jenis</option>
                        @foreach ($jenis as $j)
                            <option value="{{ $j->id }}">{{ $j->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- seharus nya data nya ngambil daris jenis kendaraan misal ada kendaraan lainnya bakal muncul atau di buar coursel  --}}
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Simpan Data
                </button>
                <div class="flex items-center gapo-2">
                    <input type="checkbox" name="auto_print" value="1" id="auto_print"
                        class="w-4 h-5 text-blue-600 border-gray-300 rounded "
                        {{ session('auto_print') ? 'checked' : '' }}>
                    <label for="auto_print" class="text-sm text-gray-700 p-1">
                        Print Tiket
                    </label>
                </div>
            </form>
        </div>

        {{-- TABEL --}}
        <div id="tabel" class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="font-semibold text-lg ">
                    Daftar Kendaraan Masuk
                </div>

                <form action="{{ route('masuk.from') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor"
                        class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">

                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded-lg hover:bg-blue-700">Cari</button>
                    <a href="{{ route('masuk.from') }}"
                        class="bg-gray-500 text-white px-3 py-1.5 text-sm rounded-lg
                         hover:bg-gray-600 flex items-center">
                        <i class="bx bx-refresh text-lg"></i>
                    </a>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3  text-left">plat nomor</th>
                            <th class="px-6 py-3  text-left">Jenis kendaraan</th>
                            <th class="px-6 py-3  text-left">Waktu Masuk</th>
                            <th class="px-6 py-3  text-left">Cetak</th>
                            <th class="px-6 py-3  text-left">code</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kendaraanMasuk as $transaksi)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-3  font-semibold">{{ $transaksi->kendaraan->plat_nomor }}</td>
                                <td class="px-6 py-3 ">{{ $transaksi->kendaraan->jenis->nama }}</td>
                                <td class="px-6 py-3 ">{{ $transaksi->waktu_masuk }}</td>
                                <td class="px-6 py-3"><button
                                        onclick="printManual(
        '{{ $transaksi->kode_tiket }}',
        '{{ $transaksi->kendaraan->plat_nomor }}',
        '{{ $transaksi->waktu_masuk }}'
    )"
                                        class="bg-green-600 text-white px-3 py-1.5 text-sm rounded-lg hover:bg-green-700">
                                        <i class="bx bx-printer"></i> print tiket
                                    </button></td>
                                <td class="px-6 py-3 ">{{ $transaksi->kode_tiket }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    Belum ada kendaraan masuk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $kendaraanMasuk->links() }}
            </div>
        </div>
        {{-- bagian print --}}
    </div>
    @if (session('print') && session('auto_print'))
        <div id="print-area" style="display:none;">
            <div style="width:250px; font-family:monospace; text-align:center;">
                <h3>TIKET PARKIR</h3>
                <hr>
                <p>Kode: <b>{{ session('print.kode') }}</b></p>
                <p>Plat: {{ session('print.plat') }}</p>
                <p>Masuk: {{ session('print.waktu') }}</p>
                <hr>
                <p>Simpan tiket ini</p>
            </div>
        </div>
    @endif
    <script>
        function printTiket() {
            let el = document.getElementById('print-area');
            if (!el) return;

            let win = window.open('', '', 'width=300,height=600');
            win.document.write(`
                <html>
                <head>
                    <title>Tiket</title>
                   <style>
    body {
        font-family: monospace;
        display: flex;
        justify-content: center; /* tengah horizontal */
        align-items: center;     /* tengah vertical */
        height: 100vh;
        margin: 0;
    }

    .ticket {
        text-align: center;
        width: 250px;
    }
</style>
                </head>
                <body>${el.innerHTML}</body>
                </html>
            `);

            win.document.close();
            win.print();
            win.close();
        }

        @if (session('print') && session('auto_print'))
            window.onload = function() {
                printTiket();
            }
        @endif
        function printManual(kode, plat, waktu) {
            let isi = `
        <div style="width:250px; font-family:monospace; text-align:center;">
            <h3>TIKET PARKIR</h3>
            <hr>
            <p>Kode: <b>${kode}</b></p>
            <p>Plat: ${plat}</p>
            <p>Masuk: ${waktu}</p>
            <hr>
            <p>Simpan tiket ini</p>
        </div>
    `;

            let win = window.open('', '', 'width=300,height=600');

            win.document.write(`
        <html>
        <head>
            <title>Tiket</title>
            <style>
                body {
                    font-family: monospace;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
            </style>
        </head>
        <body>${isi}</body>
        </html>
    `);

            win.document.close();
            win.print();
            win.close();
        }
    </script>
@endsection
