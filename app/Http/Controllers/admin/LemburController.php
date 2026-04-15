<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\LemburExport;
use Maatwebsite\Excel\Facades\Excel;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->format('Y-m'));
        $tim   = $request->query('tim');
        $nip   = $request->query('nip');
        $nipUser = session('user')['nip'];

        $periode      = Carbon::parse($bulan . '-01');
        $startOfMonth = $periode->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $periode->copy()->endOfMonth()->toDateString();

        $query = \DB::table('t_transaksi as t')
            ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
            ->leftJoin('m_pegawai as kp', 't.approver_employee_id', '=', 'kp.nip')
            ->leftJoin('m_pegawai as pg', 't.submitted_by_NIP', '=', 'pg.nip')
            ->leftJoin('m_dokumentasi as md', 't.dokumentasi_id_dokumentasi', '=', 'md.id_dokumentasi')
            ->select('t.*', 'mt.nama_tim', 'kp.nama as nama_ketua', 'pg.nama as nama_pegawai', 'md.file_path as file_dokumentasi')
            ->whereBetween('t.date', [$startOfMonth, $endOfMonth])
            ->orderBy('t.date', 'desc');

        if ($tim) $query->where('t.tim_kode_tim', $tim);
        if ($nip) $query->where('t.submitted_by_NIP', $nip);

        $transaksi = $query->paginate(10)->withQueryString();

        // Ambil ketuaTim untuk form submit
        $ketuaTim = [];
        $responseTim = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . config('services.kipapp.token'),
            'Origin'        => 'https://jateng.web.bps.go.id',
        ])->post('https://kipapp.bps.go.id/api/v3/timkerja', [
            'tahun' => '2025',
            'type'  => '1',
        ]);

        if ($responseTim->successful()) {
            $semuaTim = $responseTim->json()['data'];
            foreach ($semuaTim as $tim_item) {
                foreach ($tim_item['anggota_tim'] as $anggota) {
                    if ($anggota['nipbaru'] == $nipUser) {
                        $ketuaTim[] = [
                            'nip'      => $tim_item['nipbaru_ketua'],
                            'nama'     => $tim_item['nama_ketua'],
                            'tim'      => $tim_item['nama_tim'],
                            'kode_tim' => $tim_item['kode_tim'],
                        ];
                        break;
                    }
                }
            }
        }

        return view('admin.lembur', compact('transaksi', 'ketuaTim', 'bulan'));
    }

    public function store(Request $request)
    {
        $role = session('role');
        if ($role === 'admin') {
            return back()->with('error', 'Admin tidak dapat mengajukan lembur.');
        }

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

    public function allPegawai()
    {
        $pegawai = \DB::table('m_pegawai')
            ->select('nip', 'nama')
            ->orderBy('nama')
            ->get();

        return response()->json($pegawai);
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->query('bulan');
        $tim   = $request->query('tim') ?: null;
        $nip   = $request->query('nip') ?: null;

        $namaBulan = Carbon::parse($bulan . '-01')->translatedFormat('F_Y');
        $filename  = "Lembur_{$namaBulan}.xlsx";

        return Excel::download(new LemburExport($bulan, $tim, $nip), $filename);
    }

    public function storeDoc(Request $request, $id_transaksi)
    {
        $request->validate([
            'file_path' => 'required|url|max:255',
        ]);

        $transaksi = DB::table('t_transaksi')
            ->where('id_transaksi', $id_transaksi)
            ->where('submitted_by_NIP', session('user')['nip'])
            ->where('status', 'approved')
            ->firstOrFail();

        $idDok = DB::table('m_dokumentasi')->insertGetId([
            'transaksi_id' => $id_transaksi,
            'date'         => $transaksi->date,
            'file_path'    => $request->file_path,
        ]);

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
