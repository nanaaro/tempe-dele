<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            // =============================
            // HANDLE JIKA LOGIN BERHASIL
            // =============================
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
                        'role'      => 'user',
                    ]);
                }

                $pegawaiData = DB::table('m_pegawai')->where('nip', $data['nip'])->first();

                // =============================
                // CEK TIM KERJA
                // =============================
                $responseTim = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . config('services.kipapp.token'),
                    'Origin'        => 'https://jateng.web.bps.go.id',
                ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
                    'tahun' => '2025',
                    'type'  => '1',
                ]);

                $jenisUser = null;

                if ($responseTim->successful()) {
                    $timBody = $responseTim->json();

                    if (isset($timBody['data'])) {
                        $semuaTim = $timBody['data'];
                        $nipUser  = $data['nip'];

                        foreach ($semuaTim as $tim) {
                            if (($tim['nipbaru_ketua'] ?? null) == $nipUser) {
                                $jenisUser = 'ketua_tim';
                                break;
                            }

                            if (isset($tim['anggota_tim'])) {
                                foreach ($tim['anggota_tim'] as $anggota) {
                                    if (($anggota['nipbaru'] ?? null) == $nipUser) {
                                        $jenisUser = 'anggota';
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }

                // =============================
                // SET SESSION
                // =============================
                Session::put('user', $data);
                Session::put('logged_in', true);
                Session::put('jenis_user', $jenisUser);
                Session::put('id_pegawai', $pegawaiData->id_pegawai ?? null);

                $role = $pegawaiData->role ?? 'user';

                // =============================
                // REDIRECT BERDASARKAN ROLE
                // =============================
                if ($role === 'superadmin' || $role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'user' && $jenisUser === 'ketua_tim') {
                    return redirect()->route('ketua-tim.dashboard');
                } else {
                    return redirect()->route('pegawai.dashboard');
                }
            }

            // =============================
            // HANDLE ERROR LOGIN
            // =============================
            Log::error('Login gagal', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            // API kadang kirim 500 tapi isi pesan tetap "incorrect username or password"
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
                'login' => 'Gagal login. Status: ' . $response->status() .
                    ' | Pesan: ' . ($body['detail'] ?? $body['message'] ?? $response->body())
            ]);

        } catch (\Exception $e) {
            Log::error('Exception login', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'login' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login');
    }
}
