<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktifitas extends Model
{
    protected $table = 'log_aktifitas';

    protected $fillable = ['user_id', 'aktivitas', 'waktu_aktivitas','nama_user'];

    protected $casts = ['waktu_aktivitas' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
