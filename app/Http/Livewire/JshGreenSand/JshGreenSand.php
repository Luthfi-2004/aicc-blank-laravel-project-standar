<?php

namespace App\Http\Livewire\JshGreenSand;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\JshGfn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class JshGreenSand extends Component
{
    /** 
     * Array mesh & index yang dipakai sebagai acuan tetap 
     */
    public array $meshes  = ['18,5', '26', '36', '50', '70', '100', '140', '200', '280', 'PAN'];
    public array $indices = [10,     20,    30,    40,    50,    70,     100,   140,   200,   300];

    /** Input nilai gram dari user */
    public array $grams = [];
    public ?string $gfn_date = null;
    public ?string $shift = null;


    /** Hasil perhitungan dari gram */
    public array $percentages = [];           // hasil % tiap baris
    public array $percentageIndices = [];     // hasil %Index tiap baris
    public float $totalGram = 0.0;            // total gram dari semua input
    public float $totalPercentageIndex = 0.0; // total %Index dari semua baris

    /** State untuk tampilan (show 24 jam terakhir, hanya batch terbaru) */
    public Collection $displayRows;           // 10 baris detail batch yang ditampilkan
    public ?array $displayRecap = null;       // ringkasan (nilai_gfn, mesh_total140, dst)
    public ?string $displayBatch = null;      // batch_code yang sedang ditampilkan

    protected $rules = [
        'grams.*' => 'nullable|numeric|min:0',
        'gfn_date' => 'required|date',
        'shift'    => 'required|in:D,S,N',
    ];

    /**
     * Pertama kali component dipanggil
     * - Inisialisasi array grams (kosong)
     * - Hitung awal (semua nol)
     * - Muat data batch terbaru dalam 24 jam terakhir untuk ditampilkan
     */
    public function mount(): void
    {
        $this->grams = array_fill(0, 10, null);
        $this->recalc();
        $this->loadLatest24h();
    }

    /**
     * Fungsi untuk membuka modal Add Data
     * dipanggil dari tombol wire:click="create"
     */
    public function create(): void
    {
        $this->dispatch('showModalGreensand');
    }

    /**
     * Alternatif untuk buka modal
     */
    public function openModal(): void
    {
        $this->dispatch('showModalGreensand');
    }

    /**
     * Tutup modal input data
     */
    public function closeModal(): void
    {
        $this->dispatch('hideModalGreensand');
    }

    /**
     * Reset form ke kondisi awal
     */
    public function resetForm(): void
    {
        $this->gfn_date = null;
        $this->shift    = null;
        $this->grams = array_fill(0, 10, null);
        $this->percentages = $this->percentageIndices = [];
        $this->totalGram = 0.0;
        $this->totalPercentageIndex = 0.0;
        $this->resetValidation();
    }

    /**
     * Trigger otomatis setiap ada perubahan pada input grams
     * -> Panggil ulang hitungan recalc()
     */
    public function updatedGrams(): void
    {
        $this->recalc();
    }

    /**
     * Hitung ulang:
     * - totalGram
     * - % (gram/total*100)
     * - %Index (% * Index)
     * - Σ %Index
     */
    private function recalc(): void
    {
        $this->totalGram = (float) array_sum(array_map(fn ($v) => (float) $v, $this->grams));
        $this->percentages = [];
        $this->percentageIndices = [];

        for ($i = 0; $i < 10; $i++) {
            $g = (float) ($this->grams[$i] ?? 0);

            // % = (gram / totalGram) * 100
            $pct = $this->totalGram > 0 ? ($g / $this->totalGram) * 100 : 0;

            // %INDEX = % * INDEX
            $pctIndex = $pct * $this->indices[$i];

            $this->percentages[$i]       = round($pct, 2);
            $this->percentageIndices[$i] = round($pctIndex, 1);
        }

        // Σ %INDEX (hasil dari semua baris)
        $this->totalPercentageIndex = array_sum($this->percentageIndices);
    }

    /**
     * Simpan data:
     * - Validasi input
     * - Hitung ulang
     * - Simpan 10 baris ke tb_jsh_gfns
     * - Buat rekap ke tb_total_gfn
     * - Tampilkan HANYA batch baru (batch lama otomatis "hilang" dari show)
     */
    public function save(): void
    {
        $this->validate();
        $this->recalc();

        if ($this->totalGram <= 0) {
            $this->addError('grams', 'Isikan minimal satu nilai GRAM > 0.');
            return;
        }

        $batch = now()->format('YmdHis') . '-' . Str::random(4);

        DB::transaction(function () use ($batch) {
            // Simpan detail tiap mesh ke tb_jsh_gfns
            for ($i = 0; $i < 10; $i++) {
                JshGfn::create([
                    'batch_code'             => $batch,
                    'gfn_date'               => $this->gfn_date,
                    'shift'                  => $this->shift,
                    'mesh'                   => $this->meshes[$i],
                    'gram'                   => (float) ($this->grams[$i] ?? 0),
                    'percentage'             => $this->percentages[$i],
                    'index'                  => $this->indices[$i],
                    'percentage_index'       => $this->percentageIndices[$i],
                    'total_gram'             => $this->totalGram,
                    'total_percentage_index' => $this->totalPercentageIndex,
                    'nilai_gfn'              => round($this->totalPercentageIndex / 100, 2),
                ]);
            }

            // Rekap batch ke tb_total_gfn
            $this->createTotalForBatch($batch);
        });

        // TAMPILKAN batch baru → batch sebelumnya otomatis tidak ditampilkan lagi
        $this->setDisplayForBatch($batch);

        // Reset & tutup modal
        $this->dispatch('hideModalGreensand');
        $this->resetForm();
        session()->flash('status', 'Data GFN berhasil disimpan.');
        $this->dispatch('gfnSaved');
    }

    /**
     * Render view Livewire
     */
    public function render()
    {
        return view('livewire.jsh-green-sand.jsh-green-sand');
    }

    /**
     * Membuat rekap total ke tb_total_gfn berdasarkan batch
     * - nilai_gfn      = Σ%INDEX / 100
     * - mesh_total140  = % mesh 140
     * - mesh_total70   = Σ% mesh 50+70+100
     * - meshpan        = Σ% mesh 280+PAN
     * - judge_*        = OK/NG berdasarkan standar
     */
    private function createTotalForBatch(string $batch): void
    {
        $rows = JshGfn::query()->where('batch_code', $batch)->get();

        $totalPI   = (float) $rows->sum('percentage_index'); // Σ %INDEX
        $totalGram = (float) $rows->sum('gram');

        // Ambil nilai % sesuai kelompok
        $mesh_total140 = (float) optional($rows->firstWhere('mesh', '140'))->percentage ?? 0;
        $mesh_total70  = (float) $rows->whereIn('mesh', ['50', '70', '100'])->sum('percentage');
        $meshpan       = (float) $rows->whereIn('mesh', ['280', 'PAN'])->sum('percentage');

        // Nilai GFN
        $nilaiGfn = round($totalPI / 100, 2);

        // Standar
        $std140min = 3.50;
        $std140max = 8.00;
        $stdMin5070100 = 64.00;
        $std280min = 0.00;
        $std280max = 1.40;
        $stdGfnMin = null;   // isi jika ada standar nilai GFN
        $stdGfnMax = null;

        // Penilaian
        $judge140 = ($mesh_total140 >= $std140min && $mesh_total140 <= $std140max) ? 'OK' : 'NG';
        $judge70  = ($mesh_total70  >= $stdMin5070100) ? 'OK' : 'NG';
        $judgepan = ($meshpan >= $std280min && $meshpan <= $std280max) ? 'OK' : 'NG';
        $judgeGfn = (!is_null($stdGfnMin) && !is_null($stdGfnMax))
            ? (($nilaiGfn >= $stdGfnMin && $nilaiGfn <= $stdGfnMax) ? 'OK' : 'NG')
            : 'OK';
        // Simpan/update ke tb_total_gfn
        DB::table('tb_total_gfn')->updateOrInsert(
            ['batch_code' => $batch],
            [
                // Jika kamu menyimpan total_gram & total_percentage_index di tabel rekap, bisa tambahkan juga di sini.
                'nilai_gfn'       => $nilaiGfn,
                'mesh_total140'   => round($mesh_total140, 2),
                'mesh_total70'    => round($mesh_total70, 2),
                'meshpan'         => round($meshpan, 2),
                'judge_mesh_140'  => $judge140,
                'judge_mesh_70'   => $judge70,
                'judge_meshpan'   => $judgepan,
                'updated_at'      => now(),
                'created_at'      => now(),
            ]
        );
    }

    /**
     * Muat data tampilan:
     * - Cari batch_code terbaru yang dibuat dalam 24 jam terakhir
     * - Ambil 10 baris detail & rekap untuk batch tersebut
     * - Jika tidak ada, kosongkan tampilan
     * Catatan: hanya menampilkan SATU batch terbaru (batch lama otomatis "hilang")
     */
    public function loadLatest24h(): void
    {
        $since = now()->subDay(); // 24 jam terakhir

        // Ambil batch terbaru (by created_at) dalam 24 jam
        $latestBatch = JshGfn::query()
            ->where('created_at', '>=', $since)
            ->orderByDesc('created_at')
            ->value('batch_code');

        if (!$latestBatch) {
            // Tidak ada data dalam 24 jam terakhir
            $this->displayBatch = null;
            $this->displayRows = collect();
            $this->displayRecap = null;
            return;
        }

        // Set tampilan ke batch terbaru → batch sebelumnya tidak ditampilkan
        $this->setDisplayForBatch($latestBatch);
    }

    /**
     * Set state tampilan (displayRows & displayRecap) untuk 1 batch tertentu.
     * Dipakai setelah save (batch baru) dan saat loadLatest24h().
     */
    private function setDisplayForBatch(string $batch): void
    {
        $this->displayBatch = $batch;

        // Ambil 10 baris detail & urutkan mengikuti urutan $meshes
        $rows = JshGfn::query()
            ->where('batch_code', $batch)
            ->get()
            ->keyBy('mesh');

        $ordered = collect();
        foreach ($this->meshes as $i => $mesh) {
            $r = $rows->get($mesh);
            if ($r) {
                $ordered->push($r);
            } else {
                // fallback kalau ada baris kosong (harusnya tidak terjadi)
                $ordered->push((object) [
                    'mesh' => $mesh,
                    'gram' => 0,
                    'percentage' => 0,
                    'index' => $this->indices[$i],
                    'percentage_index' => 0,
                ]);
            }
        }
        $this->displayRows = $ordered;

        // Ambil rekap dari tb_total_gfn
        $recap = DB::table('tb_total_gfn')
            ->where('batch_code', $batch)
            ->first();

        $this->displayRecap = $recap ? [
            'nilai_gfn'      => isset($recap->nilai_gfn) ? (float) $recap->nilai_gfn : 0.0,
            'mesh_total140'  => isset($recap->mesh_total140) ? (float) $recap->mesh_total140 : 0.0,
            'mesh_total70'   => isset($recap->mesh_total70) ? (float) $recap->mesh_total70 : 0.0,
            'meshpan'        => isset($recap->meshpan) ? (float) $recap->meshpan : 0.0,
            'judge_mesh_140' => $recap->judge_mesh_140 ?? '-',
            'judge_mesh_70'  => $recap->judge_mesh_70 ?? '-',
            'judge_meshpan'  => $recap->judge_meshpan ?? '-',

            // jika tabel rekap tidak simpan total, hitung dari detail
            'total_gram'     => isset($recap->total_gram) ? (float) $recap->total_gram : (float) $ordered->sum('gram'),
            'total_pi'       => isset($recap->total_percentage_index) ? (float) $recap->total_percentage_index : (float) $ordered->sum('percentage_index'),
        ] : null;
    }
}
