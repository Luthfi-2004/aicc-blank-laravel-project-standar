<?php

namespace App\Exports;

use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Support\GreensandQuery; // <— TAMBAH INI

class GreensandExportFull implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function __construct(
        protected ?string $start = null,
        protected ?string $end   = null,
        protected ?string $shift = null,
        protected ?string $q     = null,
        protected ?int    $mm    = null
    ) {}

    public function query()
    {
        // gunakan builder yang sama agar hasil export = hasil yang tampil di UI
        $filters = [
            'start' => $this->start,
            'end'   => $this->end,
            'shift' => $this->shift,
            'q'     => $this->q,
            'mm'    => $this->mm,
        ];

        $q = GreensandQuery::build($filters);

        // (opsional) debug:
        // logger()->info('[EXPORT greensand] count', ['count' => (clone $q)->count(), 'filters' => $filters]);

        return $q;
    }

    public function headings(): array
    {
        return [
            [
                'Process Date','Shift','MM No','Mix No','Mix Start','Mix Finish',
                'MM Sample', '', '', '', '', '', '', '', '', '', '', '', '', '',
                'Additive',  '', '',
                'BC Sample', '', '', '', '', '',
                'Return Sand', '', '', '', '', '', '', '',
            ],
            [
                '','','','','','',
                'P','C','GT','CB (MM)','CB (Lab)','M','Bakunetsu','AC','TC','VSD (MM)','IG','CB Weight','TP50 Weight','SSI',
                'M3','VSD','SC',
                'BC12 CB','BC12 M','BC11 AC','BC11 VSD','BC16 CB','BC16 M',
                'RS Time','Type','Moist BC9','Moist BC10','Moist BC11','Temp BC9','Temp BC10','Temp BC11',
            ],
        ];
    }

    public function map($row): array
    {
        $fmtDate = fn($v) => $v
            ? ($v instanceof \DateTimeInterface ? $v->format('Y-m-d') : \Carbon\Carbon::parse($v)->format('Y-m-d'))
            : '';
        $fmtTime = fn($v) => $v
            ? ($v instanceof \DateTimeInterface ? $v->format('H:i') : \Carbon\Carbon::parse($v)->format('H:i'))
            : '';
        $val = fn($v) => $v ?? '';

        return [
            $fmtDate($row->process_date),
            $val($row->shift),
            $val($row->mm_no),
            $val($row->mix_no),
            $fmtTime($row->mix_start),
            $fmtTime($row->mix_finish),

            $val($row->sample_p),
            $val($row->sample_c),
            $val($row->sample_gt),
            $val($row->cb_mm),
            $val($row->cb_lab),
            $val($row->sample_m),
            $val($row->bakunetsu),
            $val($row->sample_ac),
            $val($row->sample_tc),
            $val($row->vsd_mm),
            $val($row->sample_ig),
            $val($row->cb_weight),
            $val($row->tp50_weight),
            $val($row->ssi),

            $val($row->additive_m3),
            $val($row->additive_vsd),
            $val($row->additive_sc),

            $val($row->bc12_cb),
            $val($row->bc12_m),
            $val($row->bc11_ac),
            $val($row->bc11_vsd),
            $val($row->bc16_cb),
            $val($row->bc16_m),

            $fmtTime($row->return_time),
            $val($row->model_type),
            $val($row->moisture_bc9),
            $val($row->moisture_bc10),
            $val($row->moisture_bc11),
            $val($row->temp_bc9),
            $val($row->temp_bc10),
            $val($row->temp_bc11),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('G1:T1');
        $sheet->mergeCells('U1:W1');
        $sheet->mergeCells('X1:AC1');
        $sheet->mergeCells('AD1:AK1');

        $lastCol    = 'AK';
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F81BD']]],
        );

        $sheet->getRowDimension(1)->setRowHeight(22);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getStyle("A1:{$lastCol}{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A'=>18,'B'=>18,'C'=>18,'D'=>18,'E'=>18,'F'=>18,
            'G'=>18,'H'=>18,'I'=>18,'J'=>18,'K'=>18,'L'=>18,'M'=>18,'N'=>18,'O'=>18,'P'=>18,'Q'=>18,'R'=>18,'S'=>18,'T'=>18,
            'U'=>18,'V'=>18,'W'=>18,
            'X'=>18,'Y'=>18,'Z'=>18,
            'AA'=>18,'AB'=>18,'AC'=>18,
            'AD'=>18,'AE'=>18,'AF'=>18,'AG'=>18,'AH'=>18,'AI'=>18,'AJ'=>18,'AK'=>18,
        ];
    }
}
