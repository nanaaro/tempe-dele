<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaTim extends Model
{
    protected $table = 't_anggota_tim';
    protected $primaryKey = ['pegawai_id_pegawai', 'tim_kode_tim'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'pegawai_id_pegawai',
        'tim_kode_tim',
        'nip_lama',
        'nip',
        'jenis',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id_pegawai', 'id_pegawai');
    }
}
