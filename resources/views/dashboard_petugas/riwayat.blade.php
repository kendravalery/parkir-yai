@extends('layout.layout_petugas')
@section('PetugasContent')
    <div class="space-y-6">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 rounded-xl sahdow-lg text-white">
            <div class="text-2xl font-bold ">Riwayat Transaksi</div>
            <div class="text-sm opacity-80">{{ date('d M Y H:i') }}</div>
        </div>
        {{-- TABLE  --}}

        <div class="bg-white shadow-sm border-xl border-gray-200 overflow-hidden my-5">
            <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="font-semibold text-lg">
                        Daftar Kendaraan
                    </div>
                    <div class="text-sm text-gray-500">
                        Total: {{ $riwayatTransaksi->total() }} data
                    </div>
                </div>

                <form action="{{ route('Riwayat') }}" method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor"
                        class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded-lg  hover:bg-blue-700">Cari</button>
                    <a href="{{ route('Riwayat') }}"
                        class="bg-gray-500 text-white px-3 py-1.5 text-sm rounded-lg
                         hover:bg-gray-600 flex items-center">
                        <i class="bx bx-refresh text-lg"></i>
                    </a>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-6 py-3   font-medium text-left">plat nomor</th>
                            <th class="px-6 py-3   font-medium text-left">Jenis kendaraan</th>
                            <th class="px-6 py-3   font-medium text-left">Waktu masuk</th>
                            <th class="px-6 py-3   font-medium text-left">Waktu keluar</th>
                            <th class="px-6 py-3   font-medium text-left">Kode tiket</th>
                            <th class="px-6 py-3   font-medium text-left">Biaya total</th>
                            <th class="px-6 py-3   font-medium text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatTransaksi as $transaksi)
                            <tr class="border-t hover:bg-gray-50 ">
                                <td class="px-6 py-3 font-semibold ">{{ $transaksi->kendaraan->plat_nomor }}</td>
                                <td class="px-6 py-3">{{ $transaksi->kendaraan->jenis->nama }}</td>
                                <td class="px-6 py-3">{{ $transaksi->waktu_masuk }}</td>
                                <td class="px-6 py-3">{{ $transaksi->waktu_keluar }}</td>
                                <td class="px-6 py-3">{{ $transaksi->kode_tiket }}</td>
                                <td class="px-6 py-3 text-green-600">
                                    Rp{{ number_format($transaksi->biaya_total, 0, ',', '.' )}}</td>
                                <td class="px-6 py-3 ">{{ $transaksi->status }}</td>
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
            <div class="p-4">{{ $riwayatTransaksi->links() }}</div>
        </div>
    </div>
@endsection
