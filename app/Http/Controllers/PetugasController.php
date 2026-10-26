<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\AreaParkir;
use App\Models\JenisKendaraan;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\LogAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class PetugasController extends Controller
{
    // Dashboard masuk

    public function index()
    {
        $user = Auth::User();

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

        return view('dashboard_petugas.index', [
            'jenis' => \App\Models\JenisKendaraan::all(),
            'kendaraanMasuk' => $kendaraanMasuk,
            'totalHariIni' => $totalHariIni,
            'statistik' => $statistik,
            'terisi' => $terisi,
            'user' => $user,
        ]);
    }

    // kendaraan masuk

    public function createMasuk(Request $request)
    {
        // $today = Carbon::today();
        $user = Auth::User();
        $area = AreaParkir::first();
        $terisi = $area->terisi ?? 0;
        $kapasitas = $area->kapasitas ?? 0;
        $sisa = $kapasitas - $terisi;
        $query = Transaksi::with('kendaraan.jenis')->where('status', 'masuk');

        // bagian search plat nomor

        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $search = preg_replace('/[^A-Z0-9]/', '', $search);

            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->whereRaw("REPLACE(REPLACE(REPLACE(UPPER(plat_nomor), ' ' , ''), '-' , ''), '.' , '') LIKE ?", ["%{$search}%"]);
            });
        }

        $kendaraanMasuk = $query->latest()->paginate(7)->withQueryString(); // <- kalo di bagian withQueryString supaya  search tetap saat pindah halaman
        $totalHariIni = Transaksi::whereDate('waktu_masuk', today())->count();

        return view('dashboard_petugas.kendaraan_masuk', [
            'jenis' => \App\Models\JenisKendaraan::all(),
            'totalHariIni' => $totalHariIni,
            'kapasitas' => $kapasitas,
            'terisi' => $terisi,
            'sisa' => $sisa,
            'kendaraanMasuk' => $kendaraanMasuk,
            'user' => $user,
        ]);
    }

    public function storeMasuk(Request $request)
    {
        $user = Auth::User();
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis_kendaraan_id' => 'required|exists:jenis_kendaraans,id',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $kendaraan = Kendaraan::firstOrCreate(['plat_nomor' => strtoupper($request->plat_nomor)], ['jenis_kendaraan_id' => $request->jenis_kendaraan_id]);

            $chek = Transaksi::where('kendaraan_id', $kendaraan->id)->where('status', 'masuk')->exists();

            if ($chek) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Kendaraan masih berada di dalam parkiran']);
            }
            $area = AreaParkir::first();

            if (!$area) {
                return back()->withErrors(['error' => 'Area parkir belum di buat oleh admin']);
            } // sebelenr nya rollback yang ini gak terlelu penting karena default nya ---

            if ($area->terisi >= $area->kapasitas) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Parkiran penuh']);
            }

            $tarif = Tarif::where('jenis_kendaraan_id', $kendaraan->jenis_kendaraan_id)->first();

            if (!$tarif) {
                return back()->withErrors(['error' => 'Tarif belum di buat admin']);
            }

            // kode tiket (barcode)
            $kodeTiket = strtoupper(Str::random(10));

            // transaksi
            Transaksi::create([
                'kendaraan_id' => $kendaraan->id,
                'kode_tiket' => $kodeTiket,
                'waktu_masuk' => now(),
                'tarif_id' => $tarif->id,
                'status' => 'masuk',
                'user_id' => $user->id,
                'area_parkir_id' => $area->id,
                'user' => $user,
            ]);

            // update area
            $area->increment('terisi');
            // untuk log aktifitas
            LogAktifitas::create([
                'user_id' => $user->id,
                'nama_user' => $user->name,
                'aktivitas' => "Masuk | Plat {$kendaraan->plat_nomor} | Tiket {$kodeTiket} ",
                'waktu_aktivitas' => now(),
            ]);
            DB::commit();

            session(['auto_print' => $request->has('auto_print')]);

            return back()->with([
                'success' => 'Kendaraan berhasil masuk',
                'print' => [
                    'kode' => $kodeTiket,
                    'plat' => $kendaraan->plat_nomor,
                    'waktu' => now()->format('d-m-Y H:i'),
                    'area_parkir_id' => $area->nama_area,
                ],
                'auto_print' => $request->has('auto_print'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* ================= kendaraan keluar ================= */

    public function createKeluar(Request $request)
    {
        $user = Auth::User();
        $area = AreaParkir::first();

        $terisi = $area->terisi;

        $kapasitas = $area->kapasitas;

        $sisa = $kapasitas - $terisi;

        $today = Carbon::today();

        $query = Transaksi::with('kendaraan.jenis')->where('status', 'masuk');

        // bagian search plat nomor

        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $search = preg_replace('/[^A-Z0-9]/', '', $search);

            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->whereRaw("REPLACE(REPLACE(REPLACE(UPPER(plat_nomor), ' ' , ''), '-' , ''), '.' , '') LIKE ?", ["%{$search}%"]);
            });
        }

        $kendaraanMasuk = $query->latest()->paginate(7);
        $totalhariIni = Transaksi::whereDate('waktu_masuk', today())->count();

        $statistik = JenisKendaraan::withCount([
            'kendaraans as total_hari_ini' => function ($query) use ($today) {
                $query->whereHas('transaksis', function ($q) use ($today) {
                    $q->whereDate('waktu_masuk', $today);
                });
            },
        ])->get();

        return view('dashboard_petugas.kendaraan_keluar', [
            'jenis' => \App\Models\JenisKendaraan::all(),
            'kendaraanMasuk' => $kendaraanMasuk,
            'totalHariIni' => $totalhariIni,
            'statistik' => $statistik,
            'terisi' => $terisi,
            'kapasitas' => $kapasitas,
            'sisa' => $sisa,
            'user' => $user,
        ]);
    }

    public function previewKeluar(Request $request)
    {
        $user = Auth::User();
        $request->validate(['kode_tiket' => 'required']);

        $transaksi = Transaksi::where('kode_tiket', $request->kode_tiket)->where('status', 'masuk')->with('kendaraan.jenis')->first();

        if (!$transaksi) {
            return back()->with(['error' => 'Tiket tidak ditemukan']);
        }

        $masuk = \Carbon\Carbon::parse($transaksi->waktu_masuk);
        $durasiMenit = floor($masuk->diffInSeconds(now()) / 60);

        $jamDisplay = floor($durasiMenit / 60);
        $sisaMenit = $durasiMenit % 60;

        // bulatin ke jam
        $jam = ceil($durasiMenit / 60);

        // ngambil dari relasi jenis nya
        $tarif = $transaksi->kendaraan->jenis->tarif;
        // di hitung
        $biaya = $jam * $tarif->tarif_per_jam;

        $kendaraanMasuk = Transaksi::where('status', 'masuk')->with('kendaraan.jenis')->latest()->paginate(7);

        return view('dashboard_petugas.kendaraan_keluar', [
            'preview' => $transaksi,
            'jamDisplay' => $jamDisplay,
            'sisaMenit' => $sisaMenit,
            'durasi' => $durasiMenit,
            'biaya' => $biaya,
            'kendaraanMasuk' => $kendaraanMasuk,
            'user' => $user,
        ]);
    }

    public function processKeluar(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::where('kode_tiket', $request->kode_tiket)->where('status', 'masuk')->lockForUpdate()->with('tarif', 'kendaraan')->first();

            if (!$transaksi) {
                return back()->withErrors(['kode_tiket' => 'Kode tiket tidak valid']);
            }

            $now = now();

            // gunakan cara yang sama seperti preview: diffInSeconds / 60 -> floor -> integer menit
            $masuk = $transaksi->waktu_masuk; // sudah di-cast ke Carbon di model
            $durasiMenit = (int) floor($masuk->diffInSeconds($now) / 60);

            // hitung jam yang dibulatkan ke atas dan minimal 1 jam
            $jam = max(1, (int) ceil($durasiMenit / 60));

            $tarif = $transaksi->kendaraan->jenis->tarif;
            if (!$tarif) {
                throw new \Exception('Tarif tidak ditemukan untuk transaksi ini');
            }

            $total = $jam * $tarif->tarif_per_jam;

            // update transaksi
            $transaksi->update([
                'waktu_keluar' => $now,
                'durasi_menit' => $durasiMenit,
                'biaya_total' => $total,
                'status' => 'keluar',
            ]);

            // update area
            $area = AreaParkir::lockForUpdate()->find($transaksi->area_parkir_id);
            if ($area) {
                $area->decrement('terisi');
            }
            $user = $request->user();
            // log
            LogAktifitas::create([
                'user_id' => $request->user()->id,
                'nama_user' => $user->name,
                'aktivitas' => "Keluar | Plat {$transaksi->kendaraan->plat_nomor} | Tiket {$transaksi->kode_tiket} | Rp " . number_format($total, 0, ',', '.'),
                'waktu_aktivitas' => $now,
            ]);

            DB::commit();

            return redirect()
                ->route('keluar.from')
                ->with('success', 'Kendaraan keluar. Total: Rp' . number_format($total, 0, ',', '.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function riwayat(request $request)
    {
        $user = Auth::User();
        $query = Transaksi::with('kendaraan.jenis')->where('status', 'keluar');
        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $search = preg_replace('/[^A-Z0-9]/', '', $search);

            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->whereRaw("REPLACE(REPLACE(REPLACE(UPPER(plat_nomor), ' ' , ''), '-' , ''), '.' , '') LIKE ?", ["%{$search}%"]);
            });
        }
        $riwayatTransaksi = $query->latest()->paginate(7);

        return view('dashboard_petugas.riwayat', ['riwayatTransaksi' => $riwayatTransaksi, 'user' => $user]);
    }
}
