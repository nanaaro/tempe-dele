<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MTimTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('m_tim')->delete();
        
        \DB::table('m_tim')->insert(array (
            0 => 
            array (
                'kode_tim' => '9R8wxMWrwWnOVKQ4',
                'nama_tim' => 'Tim Kerja Kolaborasi Internal',
                'nama_ketua' => 'Ir. Endang Tri Wahyuningsih MM',
                'niplama_ketua' => '340012530',
                'nipbaru_ketua' => '196509231990032002',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 11,
            ),
            1 => 
            array (
                'kode_tim' => 'aVqQ5nApx07e3gPR',
                'nama_tim' => 'Tim Kerja Reformasi Birokrasi dan Zona Integritas',
                'nama_ketua' => 'Panular Dinu Satomo S.ST, M.Si',
                'niplama_ketua' => '340017382',
                'nipbaru_ketua' => '198206102004121001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 70,
            ),
            2 => 
            array (
                'kode_tim' => 'bjZdGnl0XRnARaJ5',
                'nama_tim' => 'Tim Kerja Statistik Sektoral',
                'nama_ketua' => 'Iman Teguh Raharto S.Si, M.Si',
                'niplama_ketua' => '340013350',
                'nipbaru_ketua' => '197004101992111001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 51,
            ),
            3 => 
            array (
                'kode_tim' => 'g2YxkEobQKMqwrm6',
                'nama_tim' => 'Tim Kerja Humas dan Protokol',
                'nama_ketua' => 'Subuh Sukmono Putro SST., M.Ec.Dev',
                'niplama_ketua' => '340015332',
                'nipbaru_ketua' => '197503151996121001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 10,
            ),
            4 => 
            array (
                'kode_tim' => 'jNexXnQa3wnKYOoy',
                'nama_tim' => 'Tim Kerja Harga dan Distribusi',
                'nama_ketua' => 'Wisnu Nurdiyanto SST, MT',
                'niplama_ketua' => '340017911',
                'nipbaru_ketua' => '198306252006021003',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 14,
            ),
            5 => 
            array (
                'kode_tim' => 'jr4L2M800WMm3vpx',
                'nama_tim' => 'Tim Kerja Industri dan Pertanian',
                'nama_ketua' => 'Ir. Sri Diastuti M.M',
                'niplama_ketua' => '340013610',
                'nipbaru_ketua' => '196809291993022001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 15,
            ),
            6 => 
            array (
                'kode_tim' => 'LRPQ8nJ3XPMDb4vY',
                'nama_tim' => 'Tim Kerja Harga dan Distribusi',
                'nama_ketua' => 'Arjuliwondo S.Si.',
                'niplama_ketua' => '340011843',
                'nipbaru_ketua' => '196507221988021001',
                'is_penugasan_khusus' => 0,
                'status' => 'nonaktif',
                'tanggal_non_aktif' => '2025-08-01',
                'jumlah_anggota' => 15,
            ),
            7 => 
            array (
                'kode_tim' => 'QrBzgE3O3lEqVPjy',
                'nama_tim' => 'Tim Kerja Bagian Umum, SAKIP, SPIP, Kerjasama dan PPID',
                'nama_ketua' => 'Joko Suwarjo S.Si, M.Si',
                'niplama_ketua' => '340013741',
                'nipbaru_ketua' => '197106131993121001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 106,
            ),
            8 => 
            array (
                'kode_tim' => 'XaQd5EY1m0EKY3pA',
            'nama_tim' => 'Tim Kerja Sistem Informasi dan Diseminasi (SID)',
                'nama_ketua' => 'Sumbodo Aji Cahyono S.Si., M.A',
                'niplama_ketua' => '340015750',
                'nipbaru_ketua' => '197703081999011001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 66,
            ),
            9 => 
            array (
                'kode_tim' => 'xBKwVM55DPMpR4Yv',
                'nama_tim' => 'Tim Kerja Ekonomi dan Analisis',
                'nama_ketua' => 'Didik Nursetyohadi M.Agb.',
                'niplama_ketua' => '340015979',
                'nipbaru_ketua' => '197509161999121001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 33,
            ),
            10 => 
            array (
                'kode_tim' => 'z6qd17yB63nmgwQ4',
                'nama_tim' => 'Tim Kerja Unit Kerja Kepala',
                'nama_ketua' => 'Wisnu Nurdiyanto SST, MT',
                'niplama_ketua' => '340017911',
                'nipbaru_ketua' => '198306252006021003',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 11,
            ),
            11 => 
            array (
                'kode_tim' => 'zR1aWMDoRvn3bDj4',
                'nama_tim' => 'Tim Kerja Indikator Statistik Sosial',
                'nama_ketua' => 'Harjo Teguh Ilmiana S.Si, M.M.',
                'niplama_ketua' => '340015769',
                'nipbaru_ketua' => '197603041999011001',
                'is_penugasan_khusus' => 0,
                'status' => 'aktif',
                'tanggal_non_aktif' => NULL,
                'jumlah_anggota' => 21,
            ),
        ));
        
        
    }
}