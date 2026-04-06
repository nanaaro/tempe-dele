<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AkumulasiExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
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

        $query = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->leftJoin('m_rates as r', function ($join) {
                $join->on('p.golongan', '=', 'r.golongan')
                     ->on('t.hari', '=', 'r.day_type');
            })
            ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select(
                't.date', 't.hari',
                't.jam_mulai_disetujui', 't.jam_selesai_disetujui',
                't.jam_mulai', 't.jam_selesai',
                'p.nama', 'p.nip', 'p.golongan',
                'r.uang_lembur', 'r.uang_makan', 'r.pajak'
            )
            ->orderBy('t.date')
            ->orderBy('p.nama');

        if (!empty($this->params['tim']))     $query->where('t.tim_kode_tim', $this->params['tim']);
        if (!empty($this->params['pegawai'])) $query->where('t.submitted_by_NIP', $this->params['pegawai']);

        return $query->get()->map(function ($item, $i) {
            $mulai   = strtotime($item->jam_mulai_disetujui);
            $selesai = strtotime($item->jam_selesai_disetujui);
            $jam     = $mulai && $selesai ? (int) floor(($selesai - $mulai) / 3600) : 0;

            $jamDiajukan = $item->jam_mulai && $item->jam_selesai
                ? (int) floor((strtotime($item->jam_selesai) - strtotime($item->jam_mulai)) / 3600) : 0;

            $uangLembur = ($item->uang_lembur ?? 0) * $jam;
            $uangMakan  = $item->uang_makan ?? 0;
            $jumlah     = $uangLembur + $uangMakan;
            $pajak      = round($jumlah * (($item->pajak ?? 0) / 100));
            $terima     = $jumlah - $pajak;

            return [
                'no'            => $i + 1,
                'nama'          => $item->nama,
                'nip'           => "\t" . $item->nip,
                'tanggal'       => \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                'hari'          => $item->hari == 0 ? 'Bekerja' : 'Libur',
                'jam_diajukan'  => $jamDiajukan,
                'jam_disetujui' => $jam,
                'golongan'      => $item->golongan ?? '-',
                'uang_lembur'   => $uangLembur,
                'uang_makan'    => $uangMakan,
                'jumlah'        => $jumlah,
                'pajak'         => $pajak,
                'terima'        => $terima,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'Nama', 'NIP', 'Tanggal', 'Hari',
            'Jam Diajukan', 'Jam Disetujui', 'Golongan',
            'Uang Lembur', 'Uang Makan', 'Jumlah', 'PPh 21', 'Terima'
        ];
    }

    public function title(): string
    {
        return 'Akumulasi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
