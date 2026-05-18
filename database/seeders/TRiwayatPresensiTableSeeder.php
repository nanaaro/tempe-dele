<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TRiwayatPresensiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('t_riwayat_presensi')->delete();
        
        \DB::table('t_riwayat_presensi')->insert(array (
            0 => 
            array (
                'id' => 1,
                'periode' => '2025-09',
                'nama_file' => '9 September.xlsx',
                'uploaded_by' => 'Saniman SH',
                'uploaded_at' => '2026-03-28 06:55:37',
            ),
            1 => 
            array (
                'id' => 2,
                'periode' => '2026-02',
                'nama_file' => '02 Feb 2026.xlsx',
                'uploaded_by' => 'Saniman SH',
                'uploaded_at' => '2026-04-09 01:58:19',
            ),
        ));
        
        
    }
}