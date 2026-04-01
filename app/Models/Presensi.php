<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 't_presensi';
    protected $primaryKey = 'id_presensi';
    public $timestamps = false;

    protected $fillable = [
        'niplama',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'status',
        'transaksi_id_transaksi',
    ];
}
