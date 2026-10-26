@extends('layout.layout_owner')
@section('OwnerContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl shadow text-white p-6 ">
        <div class="mt-2 font-bold text-[20px]">Dashboard Owner</div>
        <div class="text-sm opacity-80">{{ date('d M Y - H:i') }}</div>
    </div>
    <div class="my-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 my-6">
            <div class="bg-white shadow rounded-xl p-5 border">
                <div class="text-sm text-slate-500 ">Total Transaksi</div>
                <div class="text-2xl font-bold text-blue-600 ">{{ $totalTransaksi }}</div>
            </div>
            <div class="bg-white shadow rounded-xl p-5 border">
                <div class="text-sm text-slate-500 ">Transaksi Hari ini </div>
                <div class="text-2xl font-bold text-yellow-600 ">{{ $transaksiHariIni }}</div>
            </div>
            <div class="bg-white shadow rounded-xl p-5 border">
                <div class="text-sm text-slate-500 ">Total Pendapatan</div>
                <div class="text-2xl font-bold text-green-600 ">Rp :
                    {{ Number_format($totalPendapatan, 0, ',' . '.') }}
                </div>
            </div>
            <div class="bg-white shadow rounded-xl p-5 border">
                <div class="text-sm text-slate-500 ">Jumlah Petugas</div>
                <div class="text-2xl font-bold text-purple-600 ">{{ $petugas }}</div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="p-3  rounded-2xl shadow-lg relative overflow-hidden border">
                <div class="text-sm opacity-80">TOTAL MASUK HARI INI</div>
                <div class="text-3xl font-bold mt-3 tracking-wide">
                    {{ $totalHariIni ?? 0 }}
                </div>
            </div>

            <div class="bg-white p-3 rounded-2xl shadow-lg border">
                <div class="text-gray-400 text-sm">SEDANG PARKIR</div>
                <div class="text-3xl font-bold mt-3 text-gray-800">
                    {{ $terisi ?? 0 }}
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    kendaraan aktif saat ini
                </div>
            </div>

            <div class="bg-white p-3 rounded-2xl shadow-lg border">
                <div class="text-gray-400 text-sm">TOTAL JENIS KENDARAAN</div>
                <div class="text-3xl font-bold mt-3 text-gray-800">
                    {{ count($statistik) }}
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Detail jenis kendaraan yang aktif saat ini </h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($statistik as $item)
                    <div class="bg-gray-50 hover:bg-indigo-50 transition p-4 rounded-xl text-center border">
                        <div class="text-sm text-gray-500">
                            {{ $item->nama }}
                        </div>
                        <div class="text-3xl font-bold text-indigo-600 mt-2">
                            {{ $item->total_aktif ?? 0 }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
