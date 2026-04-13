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
            'x-api-key'    => env('KIPAPP_API_KEY'),
            'Origin'       => 'https://jateng.web.bps.go.id',
        ])->post('https://jateng.web.bps.go.id/apiconnect/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];

            if ($data['kd_satker'] !== '3300') {
                return back()->withErrors([
                    'login' => 'Akses ditolak. Hanya pegawai BPS Jawa Tengah yang dapat login.'
                ]);
            }

            $existing = \DB::table('m_pegawai')->where('nip', $data['nip'])->first();

            if ($existing) {
                \DB::table('m_pegawai')->where('nip', $data['nip'])->update([
                    'nama'      => $data['nama'],
                    'email'     => $data['email'],
                    'nip_lama'  => $data['nip_lama'],
                    'foto_url'  => $data['foto_url'],
                    'satker'    => $data['satker'],
                    'kd_satker' => $data['kd_satker'],
                ]);
            } else {
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

            $pegawaiData = \DB::table('m_pegawai')->where('nip', $data['nip'])->first();

            // Hit API tim
            $responseTim = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . config('services.kipapp.token'),
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
                    if ($tim['nipbaru_ketua'] == $nipUser) {
                        $jenisUser = 'ketua_tim';
                        break;
                    }

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
            Session::put('jenis_user', 'ketua_tim');
            Session::put('id_pegawai', $pegawaiData->id_pegawai);

            $role = $pegawaiData->role;

            if ($role === 'superadmin' || $role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'user' && $jenisUser === 'ketua_tim') {
                return redirect()->route('ketua-tim.dashboard');
            } else {
                return redirect()->route('pegawai.dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login');
    }
}
