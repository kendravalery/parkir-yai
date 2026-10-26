<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\JenisKendaraan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Nette\Utils\Paginator;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function index()
    {
        $area = AreaParkir::first();

        $kendaraanMasuk = Transaksi::with('kendaraan.jenis')->where('status', 'masuk')->latest()->get();
        $totalHariIni = Transaksi::whereDate('waktu_masuk', today())->count();

        $statistik = JenisKendaraan::withCount([
            'kendaraans as total_aktif' => function ($query) {
                $query->whereHas('transaksis', function ($q) {
                    $q->where('status', 'masuk');
                });
            },
        ])->get();

        $terisi = Transaksi::where('status', 'masuk')->count();
        return view('dashboard_owner.index', [
            'jenis' => \App\Models\JenisKendaraan::all(),
            'kendaraanMasuk' => $kendaraanMasuk,
            'totalHariIni' => $totalHariIni,
            'statistik' => $statistik,
            'terisi' => $terisi,

            'totalTransaksi' => Transaksi::count(),
            'transaksiHariIni' => Transaksi::whereDate('waktu_keluar', today())->count(),
            'totalPendapatan' => Transaksi::sum('biaya_total'),
            'petugas' => User::where('role', 'petugas')->count(),
        ]);
    }

    public function rekap(Request $request)
    {
        $query = Transaksi::with('kendaraan')->where('status', 'keluar');

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('waktu_keluar', [$request->tanggal_awal, $request->tanggal_akhir]);
        }
        $totalSemua = $query->sum('biaya_total');

        $transaksi = $query->latest()->paginate(7);

        $totalPage = $transaksi->sum('biaya_total');

        return view('dashboard_owner.rekap', compact('transaksi', 'totalSemua', 'totalPage'));
    }
    public function cetakRekap()
    {
        $transaksi = Transaksi::with('kendaraan')->where('status', 'keluar')->orderBy('waktu_keluar', 'desc')->get();

        $totalSemua = $transaksi->sum('biaya_total');

        return view('dashboard_owner.cetak_rekap', compact('transaksi', 'totalSemua'));
    }
}
