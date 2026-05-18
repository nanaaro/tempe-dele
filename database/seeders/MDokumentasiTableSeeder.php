<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MDokumentasiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('m_dokumentasi')->delete();
        
        \DB::table('m_dokumentasi')->insert(array (
            0 => 
            array (
                'id_dokumentasi' => 2,
                'transaksi_id' => 44,
                'date' => '2026-04-18',
                'file_path' => 'https://drive.google.com/file/d/128s3kp1jegPaav4fD-RyNwfaJYx3h-cf/view?usp=drive_link',
            ),
        ));
        
        
    }
}