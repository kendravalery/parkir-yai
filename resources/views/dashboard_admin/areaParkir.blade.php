@extends('layout.layout_admin')

@section('AdminContent')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl shadow text-white p-6">
        <div class="font-bold text-2xl tracking-wide ">Dashboard Pengaturan Area Parkir</div>
        <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
    </div>

    {{-- info card --}}
    <div class="grid grid-cols-3 gap-4 mb-6 my-6">

        <div class="bg-white p-4 rounded-xl shadow border">
            <div class="text-sm text-gray-500">Nama Parkiran</div>
            <div class="text-lg font-semibold">
                {{ $area->nama_area }}
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border">
            <div class="text-sm text-gray-500">Kapasitas</div>
            <div class="text-lg font-semibold">
                {{ $area->kapasitas }}
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border">
            <div class="text-sm text-gray-500">Terisi</div>
            <div class="text-lg font-semibold text-blue-600">
                {{ $area->kapasitas - ($area->terisi ?? 0) }}
            </div>
        </div>

    </div>

    {{-- FORM EDIT --}}
    <div class="bg-white shadow-lg rounded-xl p-6 border max-w-2xl">

        <h2 class="text-lg font-semibold mb-4 text-gray-700">
            Kelola Area
        </h2>


        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.area.Update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Nama Parkiran</label>
                <input type="text" name="nama_area" value="{{ $area->nama_area }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- KAPASITAS --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Kapasitas</label>
                <input type="number" name="kapasitas" value="{{ $area->kapasitas }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- BUTTON --}}
            <div class="text-right">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
@endsection
