<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPresensi extends Model
{
    protected $table = 't_riwayat_presensi';
    public $timestamps = false;

    protected $fillable = [
        'periode',
        'nama_file',
        'uploaded_by',
        'uploaded_at',
    ];
}
