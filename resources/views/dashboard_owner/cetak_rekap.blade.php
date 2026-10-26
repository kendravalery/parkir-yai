<!DOCTYPE html>
<html>

<head>
    <title>Cetak Rekap</title>
    @vite('resources/css/app.css')

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800 p-6" onload="window.print()">

    <div class="max-w-4xl mx-auto">


        <h2 class="text-2xl font-bold text-center mb-2">
            Rekap Transaksi Parkir
        </h2>

        <p class="text-center text-sm text-gray-500 mb-4">
            Laporan kendaraan keluar
        </p>

        <p class="text-right text-sm text-gray-500 mb-4">
            Dicetak: {{ date('d M Y H:i') }}
        </p>

        {{-- TOTAL --}}
        <div class="bg-green-100 p-4 rounded-lg mb-6 text-center">
            <div class="text-sm text-gray-600">Total Pendapatan</div>
            <div class="text-2xl font-bold text-green-700">
                Rp {{ number_format($totalSemua, 0, ',', '.') }}
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden border border-gray-300 rounded-lg">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 text-left">Kode</th>
                        <th class="px-4 py-2 text-left">Plat</th>
                        <th class="px-4 py-2 text-left">Masuk</th>
                        <th class="px-4 py-2 text-left">Keluar</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksi as $t)
                        <tr class="border-t">

                            <td class="px-4 py-2 font-semibold">
                                {{ $t->kode_tiket }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $t->kendaraan->plat_nomor ?? '-' }}
                            </td>

                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $t->waktu_keluar ? \Carbon\Carbon::parse($t->waktu_keluar)->format('d M Y H:i') : '-' }}
                            </td>

                            <td class="px-4 py-2 text-right text-green-600 font-semibold">
                                Rp {{ number_format($t->biaya_total, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-6 text-gray-400">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>


        <div class="no-print mt-6 flex justify-between">

            <a href="{{ route('owner.rekap') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                ← Kembali
            </a>

            <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Cetak Ulang
            </button>

        </div>

    </div>

</body>

</html>
