<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncPegawai extends Command
{
    protected $signature = 'sync:pegawai';
    protected $description = 'Sinkronisasi data pegawai dan tim dari API';

    public function handle()
    {
        $this->info('Mulai sinkronisasi...');

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd2ViYXBwcy5icHMuZ28uaWRcL2tpcGFwcCIsInN1YiI6IjMzMDB8OTIwMDAiLCJhenAiOiJKWW9iMXA3MDNFZGVLRDl2IiwiYXVkIjoicHVibGljIiwiaWF0IjoxNzU5NzMxOTA5LCJ3aWxheWFoIjoiMzMwMF8xMCIsImZsYWctd2lsYXlhaCI6MTAsIm5hbWEtd2lsYXlhaCI6Ikphd2EgVGVuZ2FoIiwidW5pdC1rZXJqYSI6IjkyMDAwIiwibmFtYS11bml0IjoiQlBTIFByb3ZpbnNpIn0.e5Wb6R4fnIlmPX03ZY7PcU_wtbEcWRYb0N-cjHtgwog',
            'Origin'        => 'https://jateng.web.bps.go.id',
        ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
            'tahun' => '2025',
            'type' => '1'
        ]);

        if (!$response->successful()) {
            $this->error('Gagal hit API: ' . $response->status());
            return;
        }

        $semuaTim = $response->json()['data'];
        $this->info('Berhasil ambil ' . count($semuaTim) . ' tim.');

        foreach ($semuaTim as $tim) {
            try {
                // Simpan ke m_tim
                DB::table('m_tim')->updateOrInsert(
                    ['kode_tim' => $tim['kode_tim']],
                    [
                        'nama_tim'            => $tim['nama_tim'],
                        'nama_ketua'          => $tim['nama_ketua'],
                        'niplama_ketua'       => $tim['niplama_ketua'],
                        'nipbaru_ketua'       => $tim['nipbaru_ketua'],
                        'is_penugasan_khusus' => $tim['is_penugasan_khusus'],
                        'status'              => $tim['status'],
                        'tanggal_non_aktif'   => $tim['tanggal_non_aktif'],
                        'jumlah_anggota'      => $tim['jumlah_anggota'],
                    ]
                );

                // Simpan ketua ke m_pegawai
                DB::table('m_pegawai')->updateOrInsert(
                    ['nip' => $tim['nipbaru_ketua']],
                    [
                        'nama'     => $tim['nama_ketua'],
                        'nip_lama' => $tim['niplama_ketua'],
                        'nip'      => $tim['nipbaru_ketua'],
                        'role'     => 'user',
                    ]
                );

                foreach ($tim['anggota_tim'] as $anggota) {
                    DB::table('m_pegawai')->updateOrInsert(
                        ['nip' => $anggota['nipbaru']],
                        [
                            'nama'     => $anggota['nama'],
                            'nip_lama' => $anggota['niplama'],
                            'nip'      => $anggota['nipbaru'],
                            'role'     => 'user',
                        ]
                    );

                    $pegawai = DB::table('m_pegawai')->where('nip', $anggota['nipbaru'])->first();
                    $timData = DB::table('m_tim')->where('kode_tim', $tim['kode_tim'])->first();

                    DB::table('t_anggota_tim')->updateOrInsert(
                        [
                            'pegawai_id_pegawai' => $pegawai->id_pegawai,
                            'tim_kode_tim'       => $timData->kode_tim,
                        ],
                        [
                            'nip_lama' => $anggota['niplama'],
                            'nip'      => $anggota['nipbaru'],
                            'jenis'    => $anggota['jenis'],
                        ]
                    );
                }

                $this->info('✓ Tim ' . $tim['nama_tim'] . ' berhasil.');

            } catch (\Exception $e) {
                $this->error('✗ Tim ' . $tim['nama_tim'] . ' gagal: ' . $e->getMessage());
            }
        }

        $this->info('Sinkronisasi selesai!');

        $totalPegawai = \DB::table('m_pegawai')->count();
        $totalTim = \DB::table('m_tim')->count();
        $this->info("Total pegawai di database: {$totalPegawai}");
        $this->info("Total tim di database: {$totalTim}");
    }
}
