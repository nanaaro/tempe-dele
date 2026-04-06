<?php
namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    public function index()
    {
        $nip     = session('user')['nip'];
        $nip_lama = DB::table('m_pegawai')->where('nip', $nip)->value('nip_lama');
        $isPns   = strlen($nip_lama) == 9;

        $dokumenList = DB::table('t_dokumen')
            ->whereIn('type', ['spkl', $isPns ? 'laporan_pns' : 'laporan_pppk'])
            ->orderBy('periode', 'desc')
            ->get();

        // Kelompokkan per periode: ['2026-03' => ['spkl' => obj, 'laporan' => obj]]
        $periodeMap = [];
        foreach ($dokumenList as $dok) {
            $laporanKey = $isPns ? 'laporan_pns' : 'laporan_pppk';
            if ($dok->type === 'spkl') {
                $periodeMap[$dok->periode]['spkl'] = $dok;
            } elseif ($dok->type === $laporanKey) {
                $periodeMap[$dok->periode]['laporan'] = $dok;
            }
        }
        krsort($periodeMap);

        return view('pegawai.dokumen', compact('periodeMap', 'isPns'));
    }

    public function download($id)
    {
        $nip      = session('user')['nip'];
        $nip_lama = DB::table('m_pegawai')->where('nip', $nip)->value('nip_lama');
        $isPns    = strlen($nip_lama) == 9;

        $dokumen = DB::table('t_dokumen')->where('id_dokumen', $id)->first();

        if (!$dokumen || !$dokumen->file_blob) abort(404);

        // Pastikan pegawai hanya bisa download dokumen yang relevan
        $allowed = ['spkl', $isPns ? 'laporan_pns' : 'laporan_pppk'];
        if (!in_array($dokumen->type, $allowed)) abort(403);

        $filename = $dokumen->type . '_' . $dokumen->periode . '.pdf';

        return response($dokumen->file_blob, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
