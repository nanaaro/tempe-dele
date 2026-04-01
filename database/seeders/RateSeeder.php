<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        $golongan = ['II', 'III', 'IV'];

        $rates = [
            'II'  => ['kerja' => 24000, 'libur' => 48000, 'makan' => 35000, 'pajak' => 0],
            'III' => ['kerja' => 30000, 'libur' => 60000, 'makan' => 37000, 'pajak' => 0.05],
            'IV'  => ['kerja' => 36000, 'libur' => 72000, 'makan' => 41000, 'pajak' => 0.15],
        ];

        foreach ($golongan as $gol) {
            // Hari kerja
            DB::table('m_rates')->insert([
                'golongan'    => $gol,
                'day_type'    => 0,
                'uang_lembur' => $rates[$gol]['kerja'],
                'uang_makan'  => $rates[$gol]['makan'],
                'pajak'       => $rates[$gol]['pajak'],
                'terima'      => 0,
            ]);

            // Hari libur
            DB::table('m_rates')->insert([
                'golongan'    => $gol,
                'day_type'    => 1,
                'uang_lembur' => $rates[$gol]['libur'],
                'uang_makan'  => $rates[$gol]['makan'],
                'pajak'       => $rates[$gol]['pajak'],
                'terima'      => 0,
            ]);
        }
    }
}
