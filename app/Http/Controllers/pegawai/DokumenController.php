<?php
namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    public function index()
    {
        $nip = session('user')['nip'];
        $nip_lama = DB::table('m_pegawai')->where('nip', $nip)->value('nip_lama');
        $isPns = strlen($nip_lama) == 9;

        // Ambil dokumen yang relevan — SPKL selalu, laporan sesuai jenis
        $dokumenList = DB::table('t_dokumen')
            ->whereIn('type', ['spkl', $isPns ? 'laporan_pns' : 'laporan_pppk'])
            ->orderBy('periode', 'desc')
            ->get();

        $periodeList = $dokumenList->pluck('periode')->unique()->sortDesc()->values();

        return view('pegawai.dokumen', compact('dokumenList', 'periodeList', 'isPns'));
    }

    public function view($id)
    {
        $dokumen = DB::table('t_dokumen')->where('id_dokumen', $id)->first();
        if (!$dokumen || !$dokumen->file_blob) abort(404);
        return response($dokumen->file_blob, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="dokumen.pdf"',
        ]);
    }

    public function view($id)
    {
        $dokumen = DB::table('t_dokumen')->where('id_dokumen', $id)->first();
        if (!$dokumen || !$dokumen->file_blob) {
            abort(404);
        }
        return response($dokumen->file_blob, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="dokumen.pdf"',
        ]);
    }
}
