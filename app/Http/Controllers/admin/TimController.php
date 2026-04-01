<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tim;
use Illuminate\Http\Request;

class TimController extends Controller
{
    public function index(Request $request)
    {
        $query = Tim::query();

        if ($request->filled('search')) {
            $query->where('nama_tim', 'like', '%' . $request->search . '%');
        }

        $tim = $query->orderBy('nama_tim')->paginate(10)->withPath(route('admin.tim'));

        if ($request->expectsJson()) {
            return response()->json($tim);
        }

        return view('admin.tim', compact('tim'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_tim'            => 'required|string|max:50|unique:m_tim,kode_tim',
            'nama_tim'            => 'required|string|max:255',
            'nama_ketua'          => 'nullable|string|max:255',
            'niplama_ketua'       => 'nullable|string|max:30',
            'nipbaru_ketua'       => 'nullable|string|max:30',
            'is_penugasan_khusus' => 'nullable|integer',
            'status'              => 'nullable|string|max:45',
            'tanggal_non_aktif'   => 'nullable|date',
        ]);

        Tim::create($validated);

        return response()->json(['message' => 'Tim berhasil ditambahkan']);
    }

    public function update(Request $request, string $kode_tim)
    {
        $tim = Tim::findOrFail($kode_tim);

        $validated = $request->validate([
            'nama_tim'            => 'sometimes|string|max:255',
            'nama_ketua'          => 'nullable|string|max:255',
            'niplama_ketua'       => 'nullable|string|max:30',
            'nipbaru_ketua'       => 'nullable|string|max:30',
            'is_penugasan_khusus' => 'nullable|integer',
            'status'              => 'nullable|string|max:45',
            'tanggal_non_aktif'   => 'nullable|date',
        ]);

        $tim->update($validated);

        return response()->json(['message' => 'Tim berhasil diupdate']);
    }

    public function destroy(string $kode_tim)
    {
        $tim = Tim::findOrFail($kode_tim);
        $tim->delete();

        return response()->json(['message' => 'Tim berhasil dihapus']);
    }
}
