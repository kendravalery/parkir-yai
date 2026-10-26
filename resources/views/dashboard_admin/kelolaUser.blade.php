@extends('layout.layout_admin')
@section('AdminContent')
    <div x-data="{
        openEdit: false,
        editId: null,
        editName: '',
        editEmail: ''
    }">

        {{-- Alert --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-xl text-white shadow p-6">
            <div class="font-bold text-2xl tracking-wide">Dashboard kelola user</div>
            <div class="text-sm opacity-80 mt-1">{{ date('d M Y - H:i') }}</div>
        </div>
        {{-- Form Tambah Petugas --}}
        <div class="bg-white p-4 rounded shadow mb-4 my-6">
            <form action="{{ route('admin.user.store') }}" method="POST" class="flex gap-2 flex-wrap">
                @csrf
                <input type="text" name="name" placeholder="Nama" class="border p-2 rounded w-48">
                <input type="email" name="email" placeholder="Email" class="border p-2 rounded w-56">
                <input type="password" name="password" placeholder="Password" class="border p-2 rounded w-40">
                <button class="bg-blue-600 text-white px-4 rounded">Simpan</button>
            </form>
        </div>

        {{-- Tabel Petugas --}}
        <div class="bg-white shadow-sm rounded-xl border  border-gray-200 overflow-hidden my-5">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-700"></h2>
            </div>
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class=" p-2">Nama</th>
                        <th class=" p-2">Email</th>
                        <th class=" p-2">Role</th>
                        <th class=" p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @if ($user->role == 'petugas')
                            <tr class="hover:bg-gray-50 border-t">
                                <td class="text-center p-2">{{ $user->name }}</td>
                                <td class="text-center p-2">{{ $user->email }}</td>
                                <td class="text-center p-2">{{ $user->role }}</td>
                                <td class="text-center flex items-center justify-center p-2 flex gap-2">
                                    <button
                                        @click="openEdit = true; editId={{ $user->id }}; editName='{{ $user->name }}'; editEmail='{{ $user->email }}'; editPassword={{ $user->password }}"
                                        class="bg-yellow-400 text-white px-2 py-1 rounded text-sm">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 text-white px-2 py-1 rounded text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modal Edit --}}
        <div x-show="openEdit" x-transition class="fixed inset-0  flex items-center justify-center z-50">
            <div @click.away="openEdit = false" class="bg-white rounded-lg shadow-xl w-96 p-6">
                <h2 class="text-lg font-semibold mb-4">Edit Petugas</h2>
                <form :action="'/admin/user/' + editId" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="text" name="name" x-model="editName" class="w-full border rounded p-2 mb-4">
                    <input type="email" name="email" x-model="editEmail" class="w-full border rounded p-2 mb-4">
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                        class="w-full border rounded p-2 mb-4">

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
