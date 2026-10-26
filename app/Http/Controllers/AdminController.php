<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\JenisKendaraan;
use App\Models\LogAktifitas;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard_admin.index', [
            'totalTransaksi' => Transaksi::whereNotNull('waktu_keluar')->count(),
            'transaksiHariIni' => Transaksi::whereDate('waktu_keluar', today())->count(),
            'totalPendapatan' => Transaksi::sum('biaya_total'),
            'petugas' => User::where('role', 'petugas')->count(),
            'user' => $user,
        ]);
    }
    public function jenis()
    {
        $user = Auth::user();
        return view('dashboard_admin.jenis_kendaraan', [
            'data' => JenisKendaraan::all(),
            'user' => $user,
        ]);
    }

    public function jenisStore(Request $request)
    {
        $request->validate(['nama' => 'required']);
        JenisKendaraan::create(['nama' => $request->nama]);

        return back()->with('success', 'Jenis kendaraan berhasil ditambahkan');
    }

    // area parkir

    public function area()
    {
        $area = AreaParkir::first();
        $user = Auth::user();

        if (!$area) {
            $area = AreaParkir::create(['nama_area' => '----', 'kapasitas' => 0]);
        }

        return view('dashboard_admin.areaParkir', compact('area', 'user'));
    }

    public function areaUpdate(Request $request)
    {
        $request->validate([
            'nama_area' => 'required',
            'kapasitas' => 'required|integer',
        ]);

        $area = AreaParkir::first();

        $area->update([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
        ]);
        return back()->with('succsess', 'Area parkir berhasil di ubah');
    }

    // halaman tarif
    public function tarif()
    {
        $user = Auth::user();
        $jenisKendaraan = JenisKendaraan::with('tarif')->get();

        return view('dashboard_admin.tarif', compact('jenisKendaraan', 'user'));
    }

    public function tarifUpdate(request $request)
    {
        foreach ($request->tarif as $id => $tarif) {
            tarif::UpdateOrCreate(['jenis_kendaraan_id' => $id], ['tarif_per_jam' => $tarif]);
        }

        return back()->with('success', 'Tarif berhasil diubah');
    }

    public function hapusJenis($id)
    {
        $jenisKendaraan = JenisKendaraan::findOrFail($id);

        $jenisKendaraan->delete();

        return back();
    }

    public function editJenis($id, Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);
        $jenisKendaraan = JenisKendaraan::findOrFail($id);

        $jenisKendaraan->update(['nama' => $request->nama]);

        return back()->with('success', 'Jenis kendaraan telah diubah');
    }
    public function adminUser()
    {
        $user = Auth::user();
        $users = User::all();
        return view('dashboard_admin.kelolaUser', compact('users', 'user'));
    }

    // kelola user
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'petugas',
        ]);

        return back()->with('success', 'Petugas berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // cuman boleh edit petugas aja
        if ($user->role !== 'petugas') {
            return back()->with('error', 'Tidak bisa mengedit user ini');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return back()->with('success', 'Petugas berhasil diupdate');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    public function Log()
    {
        $user = Auth::user();
        $logs = LogAktifitas::with('user')->latest('waktu_aktivitas')->paginate(10);
        return view('dashboard_admin.log', compact('logs', 'user'));
    }

    public function riwayat(Request $request)
    {
        $akun = Auth::user();
        $query = Transaksi::with('kendaraan.jenis')->where('status', 'keluar');
        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $search = preg_replace('/[^A-Z0-9]/', '', $search);

            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->whereRaw("REPLACE(REPLACE(REPLACE(UPPER(plat_nomor), ' ' , ''), '-' , ''), '.' , '') LIKE ?", ["%{$search}%"]);
            });
        }
        $riwayatTransaksi = $query->latest()->paginate(7);
        return view('dashboard_admin.riwayat', ['riwayatTransaksi' => $riwayatTransaksi, 'user' => $akun]);
    }
    public function cetak(Request $request)
    {
        $query = Transaksi::with('kendaraan.jenis')->where('status', 'keluar');

        if ($request->search) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('plat_nomor', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate(7)->withQueryString();

        return view('dashboard_admin.cetak_riwayat', compact('data'));
    }
}
