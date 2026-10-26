@extends('layout.layout_admin')
@section('AdminContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl shadow text-white p-6 mb-5">
        <div class="font-bold text-2xl tracking-wide ">Dashboard log Aktivitas</div>
        <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="font-semibold text-lg">Daftar Log Aktivitas</div>
        </div>
        <div class="overflow-x-auto">

            <table class="w-full text-sm text-gray-700 ">
                <thead class="text-gray-600 bg-gray-50 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Nama</th>
                        <th class="px-6 py-3 text-left font-medium">Aktivitas</th>
                        <th class="px-6 py-3 text-left font-medium">Waktu Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr class="hover:bg-gray-50 border-t transition">

                            <td class="px-6 py-3 font-semibold">
                                {{ $log->nama_user }}
                                @if (!$log->user)
                                    <span class="ml-2 text-xs  bg-red-100 text-red-500 px-2 py-1 rounded ">petugas
                                        terhapus</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-mono">{{ $log->aktivitas }}</td>
                            <td class="px-6 py-3">{{ $log->waktu_aktivitas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $logs->links() }}</div>
    </div>
@endsection
