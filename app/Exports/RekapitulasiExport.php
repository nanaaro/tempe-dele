<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapitulasiExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function collection()
    {
        $bulan = $this->params['bulan'] ?? now()->format('Y-m');
        [$tahun, $bln] = explode('-', $bulan);

        $transaksi = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select('p.nama', 'p.nip_lama', 't.date', 't.hari', 't.jam_mulai_disetujui', 't.jam_selesai_disetujui')
            ->orderBy('p.nama')
            ->orderBy('t.date')
            ->get();

        return $transaksi->groupBy('nip_lama')->map(function ($rows) {
            $nama    = $rows->first()->nama;
            $nip     = $rows->first()->nip_lama;
            $hb      = array_fill(1, 12, 0);
            $hl      = array_fill(1, 16, 0);
            $tanggal = [];

            foreach ($rows as $row) {
                if (!$row->jam_mulai_disetujui || !$row->jam_selesai_disetujui) continue;
                $durasi = (int) floor((strtotime($row->jam_selesai_disetujui) - strtotime($row->jam_mulai_disetujui)) / 3600);
                $tanggal[] = (int) date('j', strtotime($row->date));

                if ($row->hari == 0) {
                    if (isset($hb[$durasi])) $hb[$durasi]++;
                } else {
                    if (isset($hl[$durasi])) $hl[$durasi]++;
                }
            }

            $jumlahHb = 0;
            for ($i = 1; $i <= 12; $i++) $jumlahHb += $hb[$i] * $i;
            $jumlahHl = 0;
            for ($i = 1; $i <= 16; $i++) $jumlahHl += $hl[$i] * $i;

            $row = ['Nama' => $nama, 'NIP' => "\t" . $nip];
            for ($i = 1; $i <= 12; $i++) $row['HB '.$i] = $hb[$i] ?: '';
            for ($i = 1; $i <= 16; $i++) $row['HL '.$i] = $hl[$i] ?: '';
            $row['Jumlah HB'] = $jumlahHb ?: '';
            $row['Jumlah HL'] = $jumlahHl ?: '';
            $row['Tanggal']   = implode(', ', array_unique($tanggal));
            return $row;
        })->values();
    }

    public function headings(): array
    {
        $headings = ['Nama', 'NIP'];
        for ($i = 1; $i <= 12; $i++) $headings[] = 'HB ' . $i;
        for ($i = 1; $i <= 16; $i++) $headings[] = 'HL ' . $i;
        $headings[] = 'Jumlah HB';
        $headings[] = 'Jumlah HL';
        $headings[] = 'Tanggal';
        return $headings;
    }

    public function title(): string
    {
        return 'Rekapitulasi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
