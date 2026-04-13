<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TRekapitulasiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('t_rekapitulasi')->delete();
        
        \DB::table('t_rekapitulasi')->insert(array (
            0 => 
            array (
                'id_recap' => 181,
                'month' => '2026-02-01',
                'hb2' => 0,
                'hb3' => 0,
                'hb4' => 0,
                'hl2' => 0,
                'hl3' => 0,
                'hl4' => 0,
                'hl5' => 0,
                'hl6' => 0,
                'jumlah_hb' => 0,
                'jumlah_hl' => 0,
                'tanggal' => NULL,
                'pegawai_id_pegawai' => 1,
            ),
            1 => 
            array (
                'id_recap' => 208,
                'month' => '2026-03-01',
                'hb2' => 0,
                'hb3' => 0,
                'hb4' => 0,
                'hl2' => 0,
                'hl3' => 0,
                'hl4' => 0,
                'hl5' => 0,
                'hl6' => 0,
                'jumlah_hb' => 0,
                'jumlah_hl' => 0,
                'tanggal' => NULL,
                'pegawai_id_pegawai' => 1,
            ),
            2 => 
            array (
                'id_recap' => 233,
                'month' => '2026-04-01',
                'hb2' => 0,
                'hb3' => 0,
                'hb4' => 0,
                'hl2' => 0,
                'hl3' => 0,
                'hl4' => 0,
                'hl5' => 0,
                'hl6' => 0,
                'jumlah_hb' => 7,
                'jumlah_hl' => 0,
                'tanggal' => NULL,
                'pegawai_id_pegawai' => 121,
            ),
            3 => 
            array (
                'id_recap' => 234,
                'month' => '2026-04-01',
                'hb2' => 1,
                'hb3' => 1,
                'hb4' => 0,
                'hl2' => 0,
                'hl3' => 0,
                'hl4' => 1,
                'hl5' => 0,
                'hl6' => 1,
                'jumlah_hb' => 5,
                'jumlah_hl' => 10,
                'tanggal' => NULL,
                'pegawai_id_pegawai' => 1,
            ),
        ));
        
        
    }
}