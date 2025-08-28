<?php

namespace App\Exports;

use App\Models\Process;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GreensandExportFull implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function __construct(
        protected ?string $start = null,
        protected ?string $end   = null,
        protected ?string $shift = null,   // Day | Night | null
        protected ?string $q     = null,   // keyword
        protected ?int    $mm    = null,   // 1 | 2 | null (null = semua mm)
    ) {}

    public function query()
    {
        $q = Process::query();

        if ($this->mm)    $q->where('mm_no', $this->mm);
        if ($this->start) $q->whereDate('process_date', '>=', $this->start);
        if ($this->end)   $q->whereDate('process_date', '<=', $this->end);
        if ($this->shift) $q->where('shift', $this->shift);
        if ($this->q) {
            $kw = '%'.trim($this->q).'%';
            $q->where(function (Builder $w) use ($kw) {
                $w->where('mix_no', 'like', $kw)
                  ->orWhere('model_type', 'like', $kw);
            });
        }

        // urutan sama seperti tampilan tabel kamu
        return $q->orderByDesc('process_date')->orderByDesc('mix_no');
    }

    public function headings(): array
    {
        return [
            // Action sengaja tidak di-export
            'Process Date','Shift','MM No','Mix No','Mix Start','Mix Finish',
            'P','C','GT','CB (MM)','CB (Lab)','M','Bakunetsu','AC','TC','VSD (MM)','IG','CB Weight','TP50 Weight','SSI',
            'Additive M3','Additive VSD','Additive SC',
            'BC12 CB','BC12 M','BC11 AC','BC11 VSD','BC16 CB','BC16 M',
            'Return Time','Model Type','Moisture BC9','Moisture BC10','Moisture BC11','Temp BC9','Temp BC10','Temp BC11',
        ];
    }

    public function map($row): array
    {
        // Helper format (biar null → ''), waktu → H:i, tanggal → Y-m-d
        $d = fn($v) => $v ? $v->format('Y-m-d') : '';
        $t = fn($v) => $v ? $v->format('H:i')   : '';
        $x = fn($v) => $v ?? '';

        return [
            $d($row->process_date),
            $x($row->shift),
            $x($row->mm_no),
            $x($row->mix_no),
            $t($row->mix_start),
            $t($row->mix_finish),

            $x($row->sample_p),
            $x($row->sample_c),
            $x($row->sample_gt),
            $x($row->cb_mm),
            $x($row->cb_lab),
            $x($row->sample_m),
            $x($row->bakunetsu),
            $x($row->sample_ac),
            $x($row->sample_tc),
            $x($row->vsd_mm),
            $x($row->sample_ig),
            $x($row->cb_weight),
            $x($row->tp50_weight),
            $x($row->ssi),

            $x($row->additive_m3),
            $x($row->additive_vsd),
            $x($row->additive_sc),

            $x($row->bc12_cb),
            $x($row->bc12_m),
            $x($row->bc11_ac),
            $x($row->bc11_vsd),
            $x($row->bc16_cb),
            $x($row->bc16_m),

            $t($row->return_time),
            $x($row->model_type),
            $x($row->moisture_bc9),
            $x($row->moisture_bc10),
            $x($row->moisture_bc11),
            $x($row->temp_bc9),
            $x($row->temp_bc10),
            $x($row->temp_bc11),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header warna + bold
        $lastCol = 'AN'; // 40 kolom (A..AN) → update jika kamu ubah heading
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Freeze header
        $sheet->freezePane('A2');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A'=>14,'B'=>10,'C'=>8,'D'=>8,'E'=>10,'F'=>10,
            'G'=>8,'H'=>8,'I'=>8,'J'=>10,'K'=>10,'L'=>8,'M'=>12,'N'=>8,'O'=>8,'P'=>10,'Q'=>8,'R'=>12,'S'=>12,'T'=>10,
            'U'=>12,'V'=>12,'W'=>12,
            'X'=>10,'Y'=>10,'Z'=>10,'AA'=>12,'AB'=>10,'AC'=>10,
            'AD'=>10,'AE'=>16,'AF'=>12,'AG'=>12,'AH'=>12,'AI'=>10,'AJ'=>10,'AK'=>10,
            // AL..AN biarkan auto
        ];
    }
}
