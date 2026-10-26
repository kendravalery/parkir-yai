@extends('layout.layout_petugas')

@section('PetugasContent')
    <div class="space-y-6">


        {{-- <h2>Dashboard Kendaraan Keluar</h2> --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 text-white rounded-xl shadow ">
            <div class=" font-bold text-2xl tracking-wide">Sistem Parkir - Kendaraan Keluar</div>
            <div class="text-sm opacity-80">{{ date('d M Y - H:i') }}</div>
        </div>

        @if ($errors->any())
            <div style="color:red ">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div style="color:green">
                {{ session('success') }}
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4  rounded-xl shadow text-center">
                <div class="text-gray-500 text-sm ">Total Kendaraan Masuk Hari ini</div>
                <div class="text-2xl font-bold text-blue-600">{{ $totalHariIni ?? 0 }}</div>
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
                <div class="text-2xl font-bold  {{ ($sisa ?? 0) <= 0 ? 'text-red-600 ' : 'text-blue-600' }}">
                    {{ $sisa ?? 0 }}
                </div>
            </div>

        </div>
        <div class="bg-white p-6 rounded-xl shadow">
            <form action="{{ route('preview.keluar') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium  mb-1">Kode Tiket</label>
                    <input type="text" name="kode_tiket" placeholder="Masukkan kode tiket" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2  focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- seharus nya pas submit muncul pop up besar dulu terus nunujukin harga total waktu masuk waktu parkir jenis kendaraan plat nomor dll baru acc atau keluar mobil nya dan ada tombol ok atau tidak gitu, dan semua kendaraan sama semua tarif nya  --}}

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">Preview
                    Keluar</button>
            </form>
        </div>

        @if (isset($preview))
            <div class="fixed inset-0 bg-gray-500/40 flex items-center justify-center z-50 h-screen">

                <div class="bg-white p-6 rounded-xl w-[450px] shadow-2xl">
                    <h2 class="text-xl font-bold mb-4 text-center ">Konfirmasi keluar</h2>

                    <div class="space-y-2 text-sm">
                        <div>Plat : <b>{{ $preview->kendaraan->plat_nomor }}</b></div>
                        <div>Jenis : <b>{{ $preview->kendaraan->jenis->nama }}</b></div>
                        <div>Waktu masuk : <b>{{ $preview->waktu_masuk }}</b></div>
                        <div>Durasi : <b>
                                @if ($jamDisplay > 0)
                                    {{ $jamDisplay }} Jam
                                @endif
                                @if ($sisaMenit > 0)
                                    {{ $sisaMenit }}
                                @endif
                                Menit
                            </b></div>
                        <div>Biaya Total : <b> Rp {{ number_format($biaya) }}</b>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 ">
                        <a href="{{ route('keluar.from') }}" class="bg-gray-400 text-white px-4 py-2 rounded">
                            Batal</a>
                        <form action="{{ route('prosess.keluar') }}" method="POST">@csrf
                            <input type="hidden" name="kode_tiket" value="{{ $preview->kode_tiket }}">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                                Acc Keluar
                        </form>
                    </div>

                </div>
            </div>
        @endif

        <div id="tabel" class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="font-semibold text-lg">
                    Daftar kendaraan masuk
                </div>

                <form action="{{ route('keluar.from') }}" method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor"
                        class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded-lg  hover:bg-blue-700">Cari</button>
                    <a href="{{ route('keluar.from') }}"
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
                            <th class="px-6 py-3  text-left">jenis kendaraan</th>
                            <th class="px-6 py-3  text-left">waktu masuk</th>
                            <th class="px-6 py-3  text-left">code</th>
                        </tr>
                    </thead>
                    @forelse ($kendaraanMasuk as $transaksi)
                        <tbody>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-3 font-semibold">{{ $transaksi->kendaraan->plat_nomor }}</td>
                                <td class="px-6 py-3 ">{{ $transaksi->kendaraan->jenis->nama }}</td>
                                <td class="px-6 py-3 ">{{ $transaksi->waktu_masuk }}</td>
                                <td class="px-6 py-3 ">{{ $transaksi->kode_tiket }}</td>
                            </tr>

                        </tbody>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500"> Belum ada kendaraan masuk</td>
                        </tr>
                    @endforelse
                </table>
            </div>
            <div class="p-4">
                {{ $kendaraanMasuk->fragment('tabel')->links() }}
            </div>
        </div>
    </div>
@endsection
