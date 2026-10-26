<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['kendaraan_id', 'tarif_id', 'durasi_menit', 'biaya_total', 'user_id', 'area_parkir_id', 'waktu_masuk', 'waktu_keluar', 'kode_tiket', 'status'];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function area()
    {
        return $this->belongsTo(AreaParkir::class, 'area_parkir_id');
    }
}
