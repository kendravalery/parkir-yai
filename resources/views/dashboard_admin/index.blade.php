@extends('layout.layout_admin')
@section('AdminContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl shadow text-white p-6">
        <div class="font-bold text-2xl tracking-wide ">Dashboard Admin</div>
        <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
    </div>

    {{-- <div class="flex items-center gap-4 bg-yellow-50 my-4 rounded p-4 shadow border-yellow-400 border-1">
        <div class="bg-yelow-400 px-3 bg-yellow-400 rounded text-white border">Notif</div>
        <div class="text-yellow-900">Belum jadi
        </div>
    </div> --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 my-6">
        <div class="bg-white shadow rounded-xl p-5 border">
            <div class="text-sm text-slate-500">Total Transaksi</div>
            <div class="text-2xl font-bold text-blue-600">{{ $totalTransaksi }}</div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 border">
            <div class="text-sm text-slate-500">Total Transaksi Hari Ini</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $transaksiHariIni }}</div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 border">
            <div class="text-sm text-slate-500">Total Pendapatan</div>
            <div class="text-2xl font-bold text-green-600">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 border">
            <div class="text-sm text-slate-500">Jumlah Petugas</div>
            <div class="text-2xl font-bold text-purple-600">{{ $petugas }}</div>
        </div>
    </div>
@endsection
