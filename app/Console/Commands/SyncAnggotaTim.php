<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAnggotaTim extends Command
{
    protected $signature   = 'sync:anggota-tim {tahun? : Tahun data timkerja, default tahun sekarang}';
    protected $description = 'Sinkronisasi anggota tim dari API KipApp ke database lokal';

    public function handle()
    {
        $tahun = $this->argument('tahun') ?? date('Y');
        $this->info("Mengambil data timkerja tahun {$tahun}...");

        // 1. Ambil data dari API
        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . config('services.kipapp.token'),
                'Origin'        => 'https://jateng.web.bps.go.id',
            ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
                'tahun' => (string) $tahun,
                'type'  => '1',
            ]);
        } catch (\Exception $e) {
            $this->error('Gagal konek ke API: ' . $e->getMessage());
            return 1;
        }

        if (!$response->successful()) {
            $this->error('API gagal: ' . $response->status() . ' ' . $response->body());
            return 1;
        }

        $timList = $response->json()['data'] ?? [];

        if (empty($timList)) {
            $this->warn('Tidak ada data timkerja dari API.');
            return 0;
        }

        $this->info('Ditemukan ' . count($timList) . ' tim. Mulai sinkronisasi...');

        $totalInsertPegawai = 0;
        $totalInsertAnggota = 0;
        $totalSkip          = 0;

        foreach ($timList as $tim) {
            $kodeTim = $tim['kode_tim'] ?? null;
            if (!$kodeTim) continue;

            // 2. Pastikan tim ada di m_tim
            $timExist = DB::table('m_tim')->where('kode_tim', $kodeTim)->first();
            if (!$timExist) {
                $this->warn("  Tim {$kodeTim} tidak ditemukan di m_tim, skip.");
                continue;
            }

            $this->line("  Proses tim: {$timExist->nama_tim} ({$kodeTim})");

            if (empty($tim['anggota_tim'])) {
                $this->line("    → Tidak ada anggota.");
                continue;
            }

            foreach ($tim['anggota_tim'] as $anggota) {
                $nipbaru = $anggota['nipbaru'] ?? null;
                $niplama = $anggota['niplama'] ?? null;
                $nama    = $anggota['nama'] ?? '(tanpa nama)';

                // 3. Cari pegawai di m_pegawai
                $pegawai = DB::table('m_pegawai')
                    ->when($nipbaru, fn($q) => $q->where('nip', $nipbaru))
                    ->when(!$nipbaru && $niplama, fn($q) => $q->where('nip_lama', $niplama))
                    ->first();

                // 4. Kalau belum ada, insert dulu ke m_pegawai
                if (!$pegawai) {
                    if (!$nipbaru && !$niplama) {
                        $this->warn("    → Skip {$nama}: tidak ada NIP.");
                        $totalSkip++;
                        continue;
                    }

                    DB::table('m_pegawai')->insert([
                        'nama'     => $nama,
                        'nip'      => $nipbaru,
                        'nip_lama' => $niplama,
                        'role'     => 'user',
                    ]);

                    $pegawai = DB::table('m_pegawai')
                        ->when($nipbaru, fn($q) => $q->where('nip', $nipbaru))
                        ->when(!$nipbaru && $niplama, fn($q) => $q->where('nip_lama', $niplama))
                        ->first();

                    $this->line("    → Insert pegawai baru: {$nama}");
                    $totalInsertPegawai++;
                }

                // 5. Insert ke t_anggota_tim kalau belum ada
                $sudahAda = DB::table('t_anggota_tim')
                    ->where('tim_kode_tim', $kodeTim)
                    ->where('pegawai_id_pegawai', $pegawai->id_pegawai)
                    ->exists();

                if ($sudahAda) {
                    $totalSkip++;
                    continue;
                }

                DB::table('t_anggota_tim')->insert([
                    'tim_kode_tim'       => $kodeTim,
                    'pegawai_id_pegawai' => $pegawai->id_pegawai,
                    'nip'                => $nipbaru,
                    'nip_lama'           => $niplama,
                    'jenis'              => 1,
                ]);

                $this->line("    → Tambah anggota: {$nama}");
                $totalInsertAnggota++;
            }

            // 6. Update jumlah_anggota di m_tim
            $jumlah = DB::table('t_anggota_tim')->where('tim_kode_tim', $kodeTim)->count();
            DB::table('m_tim')->where('kode_tim', $kodeTim)->update(['jumlah_anggota' => $jumlah]);
        }

        $this->newLine();
        $this->info("Selesai!");
        $this->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Pegawai baru diinsert', $totalInsertPegawai],
                ['Anggota tim diinsert',  $totalInsertAnggota],
                ['Dilewati (sudah ada)',  $totalSkip],
            ]
        );

        return 0;
    }
}
