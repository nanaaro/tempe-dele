<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    protected $table = 'pegawai';

    protected $primaryKey = 'id_pegawai';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'email',
        'password',
        'nama',
        'niplama',
        'nipbaru',
        'golongan',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
