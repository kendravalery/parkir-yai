@extends('layout.layout_admin')
@section('AdminContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl shadow text-white p-6 mb-6">
        <div class="font-bold text-2xl tracking-wide">Dashboard Riwayat</div>
        <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
    </div>

    {{-- Card Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white border rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <div class="text-gray-500 text-sm">Total Kendaraan Keluar</div>
                <div class="text-2xl font-bold text-blue-600">
                    {{ $riwayatTransaksi->total() }}
                </div>
            </div>
            <i class="bx bx-car text-4xl text-blue-500"></i>
        </div>

        <div class="bg-white border rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <div class="text-gray-500 text-sm">Total Pendapatan</div>
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($riwayatTransaksi->sum('biaya_total'), 0, ',', '.') }}
                </div>
            </div>
            <i class="bx bx-money text-4xl text-green-500"></i>
        </div>

    </div>


    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

        <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="font-semibold text-lg">Daftar Kendaraan Keluar</div>
            <div class="flex flex-warp gap-2 items-center">

                <form action="{{ route('admin.riwayat') }}" method="GET" class="flex gap-2">

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor"
                    class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">

                    <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded-lg hover:bg-blue-700">
                        Cari
                    </button>

                    <a href="{{ route('admin.riwayat') }}"
                    class="bg-gray-500 text-white px-3 py-1.5 text-sm rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-refresh text-lg"></i>
                </a>

            </form>
            <a href="{{ route('admin.riwayat.cetak', [
                'page' => request('page'),
                'search' => request('search'),
            ]) }}"
                 class="bg-green-600 text-white px-3 py-1.5 text-sm rounded-lg hover:bg-green-700">
                <i class="bx bx-printer"></i> Cetak
            </a>
        </div>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-center">No</th>
                        <th class="px-6 py-3 text-left">Plat Nomor</th>
                        <th class="px-6 py-3 text-left">Jenis Kendaraan</th>
                        <th class="px-6 py-3 text-left">Waktu Masuk</th>
                        <th class="px-6 py-3 text-left">Waktu Keluar</th>
                        <th class="px-6 py-3 text-left">Kode Tiket</th>
                        <th class="px-6 py-3 text-left">Biaya Total</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($riwayatTransaksi as $transaksi)
                        <tr class="border-t hover:bg-gray-50 transition">

                            <td class="px-6 py-3 text-center font-semibold">
                                {{ $riwayatTransaksi->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-3 font-semibold">
                                {{ $transaksi->kendaraan->plat_nomor }}
                            </td>

                            <td class="px-6 py-3">
                                {{ $transaksi->kendaraan->jenis->nama }}
                            </td>

                            <td class="px-6 py-3">
                                {{ date('d M Y H:i', strtotime($transaksi->waktu_masuk)) }}
                            </td>
                            <td class="px-6 py-3">
                                {{ date('d M Y H:i', strtotime($transaksi->waktu_keluar)) }}
                            </td>

                            <td class="px-6 py-3 font-mono text-sm">
                                {{ $transaksi->kode_tiket }}
                            </td>

                            <td class="px-6 py-3 font-semibold text-green-600">
                                Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-3 text-center">
                                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                                    {{ $transaksi->status }}
                                </span>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                Belum ada kendaraan keluar
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $riwayatTransaksi->links() }}
        </div>

    </div>
@endsection
