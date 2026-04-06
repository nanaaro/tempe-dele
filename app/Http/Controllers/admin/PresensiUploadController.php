<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\RiwayatPresensi;

class PresensiUploadController extends Controller
{
    public function index()
    {
        return view('admin.presensi');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows  = $sheet->toArray();

            $tahun = (int) str_replace('Tahun : ', '', $rows[4][0] ?? date('Y'));
            $bulan = $this->parseBulan($rows[5][0] ?? '');

            $isValidJam = fn($j) => preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $j);
            $inserted = 0;

            for ($i = 9; $i < count($rows); $i++) {
                $row = $rows[$i];
                $nip = trim($row[0] ?? '');
                if (empty($nip)) continue;

                for ($col = 2; $col <= 32; $col++) {
                    $tanggal = $col - 1;
                    $cell    = trim($row[$col] ?? '');
                    if (empty($cell)) continue;

                    $parts      = explode("\n", $cell);
                    $jamMulai   = trim($parts[0] ?? '');
                    $jamSelesai = trim($parts[1] ?? '');
                    $status     = trim($parts[2] ?? '');

                    // Kalau jam_mulai bukan format jam, berarti isinya status (PD, CT, dll)
                    if (!$isValidJam($jamMulai)) {
                        $status     = $jamMulai;
                        $jamMulai   = null;
                        $jamSelesai = null;
                    }

                    if (empty($status)) continue;

                    $tanggalStr   = sprintf('%04d-%02d-%02d', $tahun, $bulan, $tanggal);
                    $jamMulaiDt   = ($jamMulai && $isValidJam($jamMulai))     ? $tanggalStr . ' ' . $jamMulai   : null;
                    $jamSelesaiDt = ($jamSelesai && $isValidJam($jamSelesai)) ? $tanggalStr . ' ' . $jamSelesai : null;

                    Presensi::updateOrCreate(
                        ['niplama' => $nip, 'tanggal' => $tanggalStr],
                        [
                            'jam_mulai'   => $jamMulaiDt,
                            'jam_selesai' => $jamSelesaiDt,
                            'status'      => $status,
                        ]
                    );

                    $inserted++;

                    \DB::statement("
                        UPDATE t_transaksi t
                        JOIN m_pegawai p ON t.submitted_by_NIP = p.nip
                        JOIN t_presensi pr ON p.nip_lama = pr.niplama AND DATE(pr.tanggal) = t.date
                        SET t.jam_selesai_disetujui = TIME(pr.jam_selesai)
                        WHERE pr.jam_selesai IS NOT NULL
                    ");

                    // Simpan riwayat upload
                    $adminNama = session('nama') ?? session('user.nama') ?? session('pegawai.nama') ?? 'Admin';
                }
            }

            // Simpan riwayat upload
            $adminNama = session('nama') ?? session('user.nama') ?? session('pegawai.nama') ?? 'Admin';
            RiwayatPresensi::create([
                'periode'     => sprintf('%04d-%02d', $tahun, $bulan),
                'nama_file'   => $file->getClientOriginalName(),
                'uploaded_by' => $adminNama,
                'uploaded_at' => now(),
            ]);

            return response()->json([
                'message' => "Upload berhasil! $inserted data presensi disimpan.",
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memproses file: ' . $e->getMessage()], 500);
        }
    }

    private function parseBulan(string $str): int
    {
        $bulanMap = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
        ];
        $str = strtolower(str_replace('Bulan : ', '', $str));
        return $bulanMap[$str] ?? (int) date('m');
    }

    public function getKalender(Request $request)
    {
        $niplama = $request->niplama;
        $periode = $request->periode; // format: 2025-09

        if (!$niplama || !$periode) {
            return response()->json([]);
        }

        [$tahun, $bulan] = explode('-', $periode);

        $data = Presensi::where('niplama', $niplama)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get(['tanggal', 'jam_mulai', 'jam_selesai', 'status'])
            ->keyBy(fn($p) => \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d'));

        return response()->json($data);
    }

    public function riwayat(Request $request)
    {
        $query = RiwayatPresensi::orderBy('uploaded_at', 'desc');

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        $riwayat = $query->paginate(10)->withPath(route('admin.riwayat_presensi'));

        if ($request->expectsJson()) {
            return response()->json($riwayat);
        }

        return view('admin.riwayat_presensi', compact('riwayat'));
    }
}
