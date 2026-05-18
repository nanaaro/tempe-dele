<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'm_rates';
    protected $primaryKey = 'id_rate';
    public $timestamps = false;

    protected $fillable = [
        'golongan',
        'day_type',
        'uang_lembur',
        'uang_makan',
        'pajak',
        'terima',
    ];
}
