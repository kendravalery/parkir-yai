@extends('layout.layout_owner')

@section('OwnerContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 rounded-xl shadow text-white">
        <div class="mt-2 font-bold  text-[20px] tracking-wide">Dashboard rekap</div>
        <div class="text-sm opacity-80">{{ date('d M Y - H:i') }}</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
        <div class="bg-green-100 p-4 rounded-xl">
            <div class="text-sm text-gray-600">Total Pendapatan</div>
            <div class="text-xl font-bold">Rp {{ number_format($totalSemua ,0 ,',','.') }}</div>
        </div>

        <div class="bg-blue-100 p-4 rounded-xl">
            <div class="text-sm text-gray-600">Jumlah Transaksi</div>
            <div class="text-xl font-bold">{{ count($transaksi) }}</div>
        </div>

        <div class="bg-yellow-100 p-4 rounded-xl">
            <div class="text-sm text-gray-600">Periode</div>
            <div class="text-sm font-bold">
                @if (request('tanggal_awal') && request('tanggal_akhir'))
                    {{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d M Y') }}
                    s/d
                    {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d M Y') }}
                @else
                    Semua Data
                @endif
            </div>
        </div>
    </div>
    <div id="tabel" class="bg-white p-6 rounded-xl shadow my-6">

        <h1 class="text-xl font-bold mb-1">Rekap Transaksi Parkir</h1>
        <p class="text-sm text-gray-500 mb-4">
            Data kendaraan yang sudah keluar dan total pendapatan
        </p>

        <form method="GET" class="flex flex-wrap items-end gap-3 mb-4">
            <div>
                <label class="text-sm text-gray-600">Dari</label>
                <input type="date" name="tanggal_awal" class="border rounded px-3 py-2"
                    value="{{ request('tanggal_awal') }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">Sampai</label>
                <input type="date" name="tanggal_akhir" class="border rounded px-3 py-2"
                    value="{{ request('tanggal_akhir') }}">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 cursor-pointer">
                Filter
            </button>
            <a href="{{ route('cetak.rekap') }}"
                class="bg-green-600 text-white px-3 py-1.5 text-sm rounded-lg hover:bg-green-700">
                <i class="bx bx-printer"></i> Cetak Semua
            </a>
        </form>

        <table class="w-full border rounded-xl overflow-hidden">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-2 text-left ">Kode Tiket</th>
                    <th class="p-2 text-left">Plat</th>
                    <th class="p-2 text-left">Masuk</th>
                    <th class="p-2 text-left">Keluar</th>
                    <th class="p-2 text-right">Total</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($transaksi as $t)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="p-2">{{ $t->kode_tiket }}</td>
                        <td class="p-2 font-mono">{{ $t->kendaraan->plat_nomor }}</td>
                        <td class="p-2 ">{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') }}</td>
                        <td class="p-2 ">{{ \Carbon\Carbon::parse($t->waktu_keluar)->format('d M Y H:i') }}</td>
                        <td class="p-2 text-right text-green-600">
                            Rp {{ number_format($t->biaya_total, 0, ',' , '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-400">Tidak ada data transaksi</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <div class="mt-4 font-bold">
            Total Pendapatan ( Halaman ini ) : Rp {{ number_format($totalPage , 0 ,',','.') }}
            {{ $transaksi->fragment('tabel')->links() }}
        </div>

    </div>
@endsection
