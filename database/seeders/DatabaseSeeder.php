<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            MPegawaiTableSeeder::class,
        ]);
        $this->call(MDokumentasiTableSeeder::class);
        $this->call(MPejabatTableSeeder::class);
        $this->call(MTimTableSeeder::class);
        $this->call(TAkumulasiTableSeeder::class);
        $this->call(TAnggotaTimTableSeeder::class);
        $this->call(TDokumenTableSeeder::class);
        $this->call(TDokumenPejabatTableSeeder::class);
        $this->call(TLaporanTableSeeder::class);
        $this->call(TPresensiTableSeeder::class);
        $this->call(TRekapitulasiTableSeeder::class);
        $this->call(TRiwayatPresensiTableSeeder::class);
        $this->call(TTransaksiTableSeeder::class);
    }
}
