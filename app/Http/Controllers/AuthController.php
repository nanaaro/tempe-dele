<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key'    => env('KIPAPP_API_KEY'),
                'Origin'       => 'https://jateng.web.bps.go.id',
            ])->post('https://jateng.web.bps.go.id/apiconnect/login', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            $body = $response->json();

            if ($response->successful() && isset($body['data'])) {
                $data = $body['data'];

                // Validasi satker
                if (($data['kd_satker'] ?? null) !== '3300') {
                    return back()->withErrors([
                        'login' => 'Akses ditolak. Hanya pegawai BPS Jawa Tengah yang dapat login.'
                    ]);
                }

                // Cek pegawai di database
                $existing = DB::table('m_pegawai')->where('nip', $data['nip'])->first();

                if ($existing) {
                    DB::table('m_pegawai')->where('nip', $data['nip'])->update([
                        'nama'      => $data['nama'] ?? null,
                        'email'     => $data['email'] ?? null,
                        'nip_lama'  => $data['nip_lama'] ?? null,
                        'foto_url'  => $data['foto_url'] ?? null,
                        'satker'    => $data['satker'] ?? null,
                        'kd_satker' => $data['kd_satker'] ?? null,
                    ]);
                } else {
                    DB::table('m_pegawai')->insert([
                        'nama'      => $data['nama'] ?? null,
                        'email'     => $data['email'] ?? null,
                        'nip'       => $data['nip'],
                        'nip_lama'  => $data['nip_lama'] ?? null,
                        'foto_url'  => $data['foto_url'] ?? null,
                        'satker'    => $data['satker'] ?? null,
                        'kd_satker' => $data['kd_satker'] ?? null,
                        'role'      => $data['role'],
                    ]);
                }

                $pegawaiData = DB::table('m_pegawai')->where('nip', $data['nip'])->first();

                $tahunSekarang = date('Y');
                $nipUser = $data['nip'];
                $jenisUser = null;

                // fungsi ambil timkerja
                $getTimKerja = function ($tahun) {
                    return Http::withHeaders([
                        'Content-Type'  => 'application/json',
                        'Authorization' => 'Bearer ' . config('services.kipapp.token'),
                        'Origin'        => 'https://jateng.web.bps.go.id',
                    ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
                        'tahun' => (string) $tahun,
                        'type'  => '1',
                    ]);
                };

                // fungsi cari role user + validasi ketua aktif
                $cekJenisUser = function ($timData, $nipUser) {
                    foreach ($timData as $tim) {

                        // cek ketua tim (harus aktif kalau ada field status)
                        if (
                            ($tim['nipbaru_ketua'] ?? null) == $nipUser &&
                            (($tim['status_ketua'] ?? 'aktif') === 'aktif')
                        ) {
                            return 'ketua_tim';
                        }

                        // cek anggota
                        if (!empty($tim['anggota_tim'])) {
                            foreach ($tim['anggota_tim'] as $anggota) {
                                if (($anggota['nipbaru'] ?? null) == $nipUser) {
                                    return 'anggota';
                                }
                            }
                        }
                    }

                    return null;
                };

                // ambil tahun sekarang
                $responseTim = $getTimKerja($tahunSekarang);

                if ($responseTim->successful()) {
                    $timBody = $responseTim->json();

                    if (isset($timBody['data'])) {
                        $jenisUser = $cekJenisUser($timBody['data'], $nipUser);
                    }
                }

                // fallback kalau belum ketemu
                if ($jenisUser === null) {
                    $responseTim = $getTimKerja($tahunSekarang - 1);

                    if ($responseTim->successful()) {
                        $timBody = $responseTim->json();

                        if (isset($timBody['data'])) {
                            $jenisUser = $cekJenisUser($timBody['data'], $nipUser);
                        }
                    }
                }

                Session::put('user', $data);
                Session::put('logged_in', true);
                Session::put('jenis_user', $jenisUser);
                Session::put('id_pegawai', $pegawaiData->id_pegawai ?? null);
                Session::put('jenis_user', 'ketua_tim');
                Session::put('role', $pegawaiData->role ?? 'user');

                $role = $pegawaiData->role ?? 'user';

                if ($role === 'superadmin' || $role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'user' && $jenisUser === 'ketua_tim') {
                    return redirect()->route('ketua-tim.dashboard');
                } else {
                    return redirect()->route('pegawai.dashboard');
                }
            }

            Log::error('Login gagal', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if (isset($body['detail']) && str_contains(strtolower($body['detail']), 'incorrect username or password')) {
                return back()->withErrors(['login' => 'Username atau password salah.']);
            }

            if ($response->serverError()) {
                return back()->withErrors(['login' => 'Server API sedang bermasalah, coba lagi nanti.']);
            }

            if ($response->clientError()) {
                return back()->withErrors(['login' => 'Username atau password salah.']);
            }

            return back()->withErrors([
                'login' => '[Error ' . $response->status() . '] ' . ($body['detail'] ?? $body['message'] ?? $response->body())
            ]);

        } catch (ConnectionException $e) {
            Log::error('Connection error saat login', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'login' => 'Tidak dapat terhubung ke server.'
            ]);

        } catch (\Exception $e) {
            Log::error('Exception login', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'login' => '[Error tidak terduga] ' . $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login');
    }
}
