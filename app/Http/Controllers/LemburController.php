<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LemburController extends Controller
{
    public function index()
    {
        $nipUser = session('user')['nip'];

        $responseTim = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd2ViYXBwcy5icHMuZ28uaWRcL2tpcGFwcCIsInN1YiI6IjMzMDB8OTIwMDAiLCJhenAiOiJKWW9iMXA3MDNFZGVLRDl2IiwiYXVkIjoicHVibGljIiwiaWF0IjoxNzU5NzMxOTA5LCJ3aWxheWFoIjoiMzMwMF8xMCIsImZsYWctd2lsYXlhaCI6MTAsIm5hbWEtd2lsYXlhaCI6Ikphd2EgVGVuZ2FoIiwidW5pdC1rZXJqYSI6IjkyMDAwIiwibmFtYS11bml0IjoiQlBTIFByb3ZpbnNpIn0.e5Wb6R4fnIlmPX03ZY7PcU_wtbEcWRYb0N-cjHtgwog',
            'Origin'        => 'https://jateng.web.bps.go.id',
        ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
            'tahun' => '2025',
            'type' => '1',
        ]);

        $ketuaTim = [];

        if ($responseTim->successful()) {
            $semuaTim = $responseTim->json()['data'];

            foreach ($semuaTim as $tim) {
                foreach ($tim['anggota_tim'] as $anggota) {
                    if ($anggota['nipbaru'] == $nipUser) {
                        $ketuaTim[] = [
                            'nip'  => $tim['nipbaru_ketua'],
                            'nama' => $tim['nama_ketua'],
                            'tim'  => $tim['nama_tim'],
                        ];
                        break;
                    }
                }
            }
        }

        return view('lembur', compact('ketuaTim'));
    }
}
