<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key'    => 'fa6e2d9ee8d5d376660b4da209761dd183292313406877ef67cd8632197c469b',
            'Origin'       => 'https://jateng.web.bps.go.id',
        ])->post('https://jateng.web.bps.go.id/apiconnect/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];

            $existing = \DB::table('m_pegawai')->where('nip', $data['nip'])->first();

            if ($existing) {
                // Update data tapi JANGAN timpa role yang sudah ada
                \DB::table('m_pegawai')->where('nip', $data['nip'])->update([
                    'nama'      => $data['nama'],
                    'email'     => $data['email'],
                    'nip_lama'  => $data['nip_lama'],
                    'foto_url'  => $data['foto_url'],
                    'satker'    => $data['satker'],
                    'kd_satker' => $data['kd_satker'],
                ]);
            } else {
                // Pegawai baru, insert dengan default role user
                \DB::table('m_pegawai')->insert([
                    'nama'      => $data['nama'],
                    'email'     => $data['email'],
                    'nip'       => $data['nip'],
                    'nip_lama'  => $data['nip_lama'],
                    'foto_url'  => $data['foto_url'],
                    'satker'    => $data['satker'],
                    'kd_satker' => $data['kd_satker'],
                    'role'      => 'user',
                ]);
            }

            // Ambil id_pegawai yang baru disimpan
            $pegawaiData = \DB::table('m_pegawai')->where('nip', $data['nip'])->first();

            // Hit API tim
            $responseTim = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd2ViYXBwcy5icHMuZ28uaWRcL2tpcGFwcCIsInN1YiI6IjMzMDB8OTIwMDAiLCJhenAiOiJKWW9iMXA3MDNFZGVLRDl2IiwiYXVkIjoicHVibGljIiwiaWF0IjoxNzU5NzMxOTA5LCJ3aWxheWFoIjoiMzMwMF8xMCIsImZsYWctd2lsYXlhaCI6MTAsIm5hbWEtd2lsYXlhaCI6Ikphd2EgVGVuZ2FoIiwidW5pdC1rZXJqYSI6IjkyMDAwIiwibmFtYS11bml0IjoiQlBTIFByb3ZpbnNpIn0.e5Wb6R4fnIlmPX03ZY7PcU_wtbEcWRYb0N-cjHtgwog',
                'Origin'        => 'https://jateng.web.bps.go.id',
            ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
                'tahun' => '2025',
                'type' => '1',
            ]);

            $jenisUser = null;

            if ($responseTim->successful()) {
                $semuaTim = $responseTim->json()['data'];
                $nipUser = $data['nip'];

                foreach ($semuaTim as $tim) {
                    // Cek apakah dia ketua tim
                    if ($tim['nipbaru_ketua'] == $nipUser) {
                        $jenisUser = 'ketua_tim';
                        break;
                    }

                    // Cek apakah dia anggota tim
                    foreach ($tim['anggota_tim'] as $anggota) {
                        if ($anggota['nipbaru'] == $nipUser) {
                            $jenisUser = 'anggota';
                            break 2;
                        }
                    }
                }
            }

            // Simpan ke session
            Session::put('jenis_user', $jenisUser);

            // Simpan ke session
            Session::put('user', $data);
            Session::put('logged_in', true);
            Session::put('id_pegawai', $pegawaiData->id_pegawai);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.'
        ]);
    }
}
