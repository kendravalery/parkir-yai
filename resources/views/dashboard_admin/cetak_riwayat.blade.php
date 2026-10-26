<!DOCTYPE html>
<html>

<head>
    <title>Cetak Riwayat</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white text-gray-800 p-6" onload="window.print()">

    <div class="max-w-4xl mx-auto">

        <!-- Judul -->
        <h2 class="text-2xl font-bold text-center mb-2">
            Riwayat Kendaraan Keluar
        </h2>

        <!-- Info tambahan -->
        <p class="text-center text-sm text-gray-500 mb-4">
            Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}
        </p>

        <p class="text-right text-sm text-gray-500 mb-4">
            Dicetak: {{ date('d M Y H:i') }}
        </p>

        <!-- Table -->
        <div class="overflow-hidden border border-gray-300 rounded-lg">
            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-4 py-2 text-left">Plat</th>
                        <th class="px-4 py-2 text-left">Jenis</th>
                        <th class="px-4 py-2 text-left">Waktu Masuk</th>
                        <th class="px-4 py-2 text-left">Biaya</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $d)
                        <tr class="border-t">
                            <td class="px-4 py-2 text-center font-semibold">
                                {{ $data->firstItem() + $loop->index }}
                            </td>
                            <td class="px-4 py-2 font-medium">
                                {{ $d->kendaraan->plat_nomor ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $d->kendaraan->jenis->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ date('d M Y H:i', strtotime($d->waktu_masuk)) }}
                            </td>
                            <td class="px-4 py-2 text-green-600 font-semibold">
                                Rp {{ number_format($d->biaya_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <!-- Tombol (tidak ikut ke-print) -->
        <div class="mt-6 print:hidden flex justify-between">
            <a href="{{ route('admin.riwayat') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Kembali
            </a>

            <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Cetak Ulang
            </button>
        </div>

    </div>

</body>

</html>
