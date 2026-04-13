<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
        }

        $pegawai = $query->orderBy('nama')->paginate(10)->withPath(route('admin.pengguna'));

        if ($request->expectsJson()) {
            return response()->json($pegawai);
        }

        return view('admin.pengguna', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'nullable|string|max:30',
            'nip_lama' => 'nullable|string|max:30',
            'email'    => 'required|email|unique:m_pegawai,email',
            'golongan' => 'nullable|string|max:10',
            'role'     => 'required|in:user,admin,ketua-tim',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Pegawai::create($validated);

        return response()->json(['message' => 'Pegawai berhasil ditambahkan']);
    }

    public function update(Request $request, int $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $nip = session('user')['nip'];
        $roleSaya = \DB::table('m_pegawai')->where('nip', $nip)->value('role');

        $validated = $request->validate([
            'nama'     => 'sometimes|string|max:255',
            'nip'      => 'nullable|string|max:30',
            'nip_lama' => 'nullable|string|max:30',
            'email'    => 'sometimes|email|unique:m_pegawai,email,' . $id . ',id_pegawai',
            'golongan' => 'nullable|string|max:10',
            'role'     => 'sometimes|in:user,admin,ketua-tim',
        ]);

        if ($roleSaya !== 'superadmin') {
            unset($validated['role']);
        }

        $pegawai->update($validated);

        return response()->json(['message' => 'Pegawai berhasil diupdate']);
    }

    public function updatePassword(Request $request, int $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $pegawai->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function destroy(int $id)
    {
        Pegawai::findOrFail($id)->delete();
        return response()->json(['message' => 'Pegawai berhasil dihapus']);
    }

    public function getAll()
    {
        $pegawai = Pegawai::orderBy('nama')->get(['id_pegawai', 'nama', 'nip']);
        return response()->json($pegawai);
    }
}
