<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LemburController extends Controller
{
    public function index()
    {
        $nipUser = session('user')['nip'];

        $transaksi = \DB::table('t_transaksi as t')
        ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
        ->leftJoin('m_pegawai as kp', 't.approver_employee_id', '=', 'kp.nip')
        ->leftJoin('m_dokumentasi as md', 't.dokumentasi_id_dokumentasi', '=', 'md.id_dokumentasi') // tambah ini
        ->where('t.submitted_by_NIP', $nipUser)
        ->select('t.*', 'mt.nama_tim', 'kp.nama as nama_ketua', 'md.file_path as file_dokumentasi') // tambah file_dokumentasi
        ->orderBy('t.date', 'desc')
        ->paginate(10);

        $responseTim = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . config('services.kipapp.token'),
            'Origin'        => 'https://jateng.web.bps.go.id',
        ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
            'tahun' => '2025',
            'type'  => '1',
        ]);

        $ketuaTim = [];

        if ($responseTim->successful()) {
            $semuaTim = $responseTim->json()['data'];
            foreach ($semuaTim as $tim) {
                foreach ($tim['anggota_tim'] as $anggota) {
                    if ($anggota['nipbaru'] == $nipUser) {
                        $ketuaTim[] = [
                            'nip'      => $tim['nipbaru_ketua'],
                            'nama'     => $tim['nama_ketua'],
                            'tim'      => $tim['nama_tim'],
                            'kode_tim' => $tim['kode_tim'],
                        ];
                        break;
                    }
                }
            }
        }

        return view('lembur', compact('ketuaTim', 'transaksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'approver_id' => 'required|string',
            'kode_tim'    => 'required|string',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'nullable',
            'uraian'      => 'required|string|max:255',
        ], [
            'uraian.required' => 'Uraian kegiatan wajib diisi.',
        ]);

        $nip     = session('user')['nip'];
        $tanggal = Carbon::parse($validated['tanggal']);
        $hari    = $tanggal->isWeekend() ? 1 : 0;

        $jamMulai   = Carbon::parse($validated['jam_mulai']);
        $jamSelesai = !empty($validated['jam_selesai'])
            ? Carbon::parse($validated['jam_selesai'])
            : null;

        $status = 'pending';
        $note   = null;

        if ($jamSelesai) {
            if ($jamSelesai->lessThan($jamMulai)) {
                $jamSelesai->addDay();
            }

            $durasi = $jamMulai->diffInHours($jamSelesai);

            if ($durasi < 2) {
                $status = 'rejected';
                $note   = 'Durasi lembur kurang dari 2 jam.';
            }
        }

        DB::table('t_transaksi')->insert([
            'submitted_by_NIP'     => $nip,
            'date'                 => $tanggal->toDateString(),
            'jam_mulai'            => $jamMulai->format('H:i:s'),
            'jam_selesai'          => $jamSelesai?->format('H:i:s'),
            'uraian'               => $validated['uraian'],
            'approver_employee_id' => $validated['approver_id'],
            'tim_kode_tim'         => $validated['kode_tim'],
            'status'               => $status,
            'note'                 => $note,
            'submitted_at'         => now(),
            'hari'                 => $hari,
        ]);

        $message = $status === 'rejected'
            ? 'Pengajuan tersimpan namun otomatis ditolak karena durasi lembur kurang dari 2 jam.'
            : 'Pengajuan lembur berhasil dikirim.';

        return back()->with($status === 'rejected' ? 'error' : 'success', $message);
    }

    public function timPegawai()
    {
        $idPegawai = session('id_pegawai');

        $tim = \DB::table('t_anggota_tim as at')
            ->join('m_tim as mt', 'at.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('at.pegawai_id_pegawai', $idPegawai)
            ->select('mt.kode_tim', 'mt.nama_tim')
            ->get();

        return response()->json($tim);
    }

    public function storeDoc(Request $request, $id_transaksi)
    {
        $request->validate([
            'file_path' => 'required|url|max:255',
        ]);

        // Pastikan transaksi milik user & statusnya approved
        $transaksi = DB::table('t_transaksi')
            ->where('id_transaksi', $id_transaksi)
            ->where('submitted_by_NIP', session('user')['nip'])
            ->where('status', 'approved')
            ->firstOrFail();

        // Insert ke m_dokumentasi
        $idDok = DB::table('m_dokumentasi')->insertGetId([
            'transaksi_id' => $id_transaksi,
            'date'         => $transaksi->date,
            'file_path'    => $request->file_path,
        ]);

        // Update kolom dokumentasi_id_dokumentasi di t_transaksi
        DB::table('t_transaksi')
            ->where('id_transaksi', $id_transaksi)
            ->update(['dokumentasi_id_dokumentasi' => $idDok]);

        return back()->with('success', 'Dokumentasi berhasil disimpan.');
    }

    public function destroyDoc($id_transaksi)
    {
        $transaksi = DB::table('t_transaksi')
            ->where('id_transaksi', $id_transaksi)
            ->where('submitted_by_NIP', session('user')['nip'])
            ->firstOrFail();

        if ($transaksi->dokumentasi_id_dokumentasi) {
            DB::table('m_dokumentasi')
                ->where('id_dokumentasi', $transaksi->dokumentasi_id_dokumentasi)
                ->delete();

            DB::table('t_transaksi')
                ->where('id_transaksi', $id_transaksi)
                ->update(['dokumentasi_id_dokumentasi' => null]);
        }

        return back()->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
