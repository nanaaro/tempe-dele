<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    protected $table = 'm_tim';
    protected $primaryKey = 'kode_tim';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_tim',
        'nama_tim',
        'nama_ketua',
        'niplama_ketua',
        'nipbaru_ketua',
        'is_penugasan_khusus',
        'status',
        'tanggal_non_aktif',
        'jumlah_anggota',
    ];
}
