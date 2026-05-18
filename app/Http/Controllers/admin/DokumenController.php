<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen; 
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index()
    {
        $pejabat = Dokumen::orderBy('jabatan')
            ->orderByRaw("status = 'aktif' DESC")
            ->get();
        return view('admin.dokumen', compact('pejabat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'jabatan'  => 'required|string|max:100',
            'nip'      => 'nullable|string|max:30',
            'nip_lama' => 'nullable|string|max:30',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        if ($validated['status'] === 'aktif') {
            Dokumen::where('jabatan', $validated['jabatan'])
                ->update(['status' => 'nonaktif']);
        }

        Dokumen::create($validated);

        return response()->json(['message' => 'Pejabat berhasil ditambahkan']);
    }

    public function update(Request $request, int $id_pejabat)
    {
        $pejabat = Dokumen::findOrFail($id_pejabat);

        $validated = $request->validate([
            'nama'     => 'sometimes|string|max:255',
            'jabatan'  => 'sometimes|string|max:100',
            'nip'      => 'nullable|string|max:30',
            'nip_lama' => 'nullable|string|max:30',
            'status'   => 'sometimes|in:aktif,nonaktif',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'aktif') {
            Dokumen::where('jabatan', $pejabat->jabatan)
                ->where('id_pejabat', '!=', $id_pejabat)
                ->update(['status' => 'nonaktif']);
        }

        $pejabat->update($validated);

        return response()->json(['message' => 'Pejabat berhasil diupdate']);
    }

    public function destroy(int $id_pejabat)
    {
        Dokumen::findOrFail($id_pejabat)->delete();
        return response()->json(['message' => 'Pejabat berhasil dihapus']);
    }
}
