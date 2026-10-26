@extends('layout.layout_admin')

@section('AdminContent')
    <div x-data="{
        openEdit: false,
        openDelete: false,
        editId: null,
        deleteId: null,
        editNama: '',
        deleteNama: ''
    }">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl text-white shadow p-6">
            <div class="font-bold text-2xl tracking-wide">Dashboard Jenis Kendaraan</div>
            <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
        </div>
        <div class="mb-4 my-6">
            <label class="block mb-2 font-medium text-gray-700">Jenis Kendaraan</label>
            <form action="{{ route('admin.jenis.store') }}" method="POST" class="flex gap-2">
                @csrf

                <input type="text" name="nama"
                    class="border rounded-lg p-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Tambahkan jenis kendaraan">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Simpan
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden my-5">

            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-700">
                    Data Jenis Kendaraan
                </h2>
            </div>

            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">
                            Jenis Kendaraan
                        </th>
                        <th class="px-6 py-3 text-center font-medium">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach ($data as $datas)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                {{ $datas->nama }}
                            </td>

                            <td class="px-6 py-4 text-center space-x-2">


                                <button
                                    @click="openEdit = true; editId = {{ $datas->id }}; editNama = '{{ $datas->nama }}' "
                                    class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100">
                                    Edit
                                </button>

                                <button
                                    @click="openDelete = true;
                                    deleteId = {{ $datas->id }};
                                    deleteNama='{{ $datas->nama }}';"
                                    class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100">
                                    Hapus
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- MODAL EDIT -->
        <div x-show="openEdit" x-transition class="fixed inset-0  bg-opacity-40 flex items-center justify-center z-50">

            <div class="bg-white rounded-xl shadow-lg w-96 p-6">

                <h2 class="text-lg font-semibold mb-4">
                    Edit Jenis Kendaraan
                </h2>

                <form :action="'/admin/jenis/' + editId" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="text" name="nama" x-model="editNama"
                        class="w-full border rounded-lg p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Batal
                        </button>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div x-show="openDelete" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">

            <!-- Click outside to close -->
            <div @click.away="openDelete = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-lg shadow-xl w-96 p-6">

                <h2 class="text-lg font-semibold mb-3">
                    Warning ⚠️
                </h2>

                <p class="mb-4 text-gray-600">
                    Apakah anda ingin mengahpus jenis kendaraan:
                    <span class="font-semibold text-red-600" x-text="deleteNama"></span>?
                    Tindakan ini dapat menghapus riwayat dengan jenis kendaraan  <span class="font-semibold text-red-600" x-text="deleteNama"></span>
                </p>

                <div class="flex justify-end gap-2">
                    <button @click="openDelete = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Batal
                    </button>

                    <form :action="'/admin/jenis/' + deleteId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
@endsection
