<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = ['tarif_per_jam' ,'jenis_kendaraan_id'];

    public function jenis()
    {
        return $this->belongsTo(JenisKendaraan::class, 'jenis_kendaraan_id');
    }
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
