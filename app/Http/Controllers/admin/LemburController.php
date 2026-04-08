<?php

namespace App\Http\Controllers\Admin;

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
            ->where('t.submitted_by_NIP', $nipUser)
            ->select('t.*', 'mt.nama_tim', 'kp.nama as nama_ketua')
            ->orderBy('t.date', 'desc')
            ->paginate(10);

        $responseTim = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd2ViYXBwcy5icHMuZ28uaWRcL2tpcGFwcCIsInN1YiI6IjMzMDB8OTIwMDAiLCJhenAiOiJKWW9iMXA3MDNFZGVLRDl2IiwiYXVkIjoicHVibGljIiwiaWF0IjoxNzU5NzMxOTA5LCJ3aWxheWFoIjoiMzMwMF8xMCIsImZsYWctd2lsYXlhaCI6MTAsIm5hbWEtd2lsYXlhaCI6Ikphd2EgVGVuZ2FoIiwidW5pdC1rZXJqYSI6IjkyMDAwIiwibmFtYS11bml0IjoiQlBTIFByb3ZpbnNpIn0.e5Wb6R4fnIlmPX03ZY7PcU_wtbEcWRYb0N-cjHtgwog',
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

        return view('admin.lembur.index', compact('ketuaTim', 'transaksi'));
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
}
