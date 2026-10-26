@extends('layout.layout_admin')
@section('AdminContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl shadow text-white p-6 mb-6">
        <div class="font-bold text-2xl tracking-wide">Dashboard Kelola Tarif Kendaraan</div>
        <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

        <div class="p-4 border-b">
            <div class="font-semibold text-lg">Pengaturan Tarif Parkir</div>
            <div class="text-sm text-gray-500">Atur tarif parkir per jam untuk setiap jenis kendaraan</div>
        </div>

        <form action="{{ route('admin.tarif.Update') }}" method="POST" class="p-4">
            @csrf
            @method('PUT')

            <div class="space-y-4">

                @foreach ($jenisKendaraan as $jenis)
                    <div class="flex items-center justify-between border rounded-lg p-3 hover:bg-gray-50">

                        <div class="font-semibold text-gray-700 w-40">
                            {{ $jenis->nama }}
                        </div>

                        <div class="flex items-center gap-2">

                            <span class="text-gray-500">Rp</span>

                            <input type="number" name="tarif[{{ $jenis->id }}]"
                                value="{{ $jenis->tarif->tarif_per_jam ?? 0 }}"
                                class="border rounded-lg px-3 py-1.5 w-40 focus:ring-2 focus:ring-blue-400 focus:outline-none">

                            <span class="text-gray-400 text-sm">
                                / jam
                            </span>

                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Tarif
                </button>
            </div>

        </form>

    </div>
@endsection
