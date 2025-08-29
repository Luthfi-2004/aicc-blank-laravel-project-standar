<?php

namespace App\Http\Livewire\GreenSand;

use App\Models\Process;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class Greensand extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ===== UI State =====
    public string $activeTab = 'mm1';   // 'mm1' | 'mm2' | 'all'
    public string $modalTab = 'mm';
    public string $formMode = 'create';
    public ?int $editingId = null;

    // Tabel controls
    public string $search = '';
    public int $perPage = 10;
    public string $searchText = '';

    // ===== Filter tanggal & shift =====
    public ?string $start_date = null;  // Y-m-d
    public ?string $end_date = null;  // Y-m-d
    public ?string $filter_shift = null;  // 'Day' | 'Night' | null
    public bool $filterOpen = true;
    // ===== Konfirmasi delete =====
    public ?int $pendingDeleteId = null;

    // ===== Query String =====
    protected array $queryString = [
        'activeTab' => ['except' => 'mm1'],
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'start_date' => ['except' => null],
        'end_date' => ['except' => null],
        'filter_shift' => ['except' => null],
    ];

    // ===== Form State =====
    public array $form = [
        // Header
        'process_date' => null,
        'shift' => '',
        'plant' => 0,
        'mm_no' => 1,   // integer 1/2
        'mix_no' => null,
        'mix_start_t' => null,
        'mix_finish_t' => null,

        // MM sample
        'sample_p' => null,
        'sample_c' => null,
        'sample_gt' => null,
        'cb_mm' => null,
        'cb_lab' => null,
        'sample_m' => null,
        'bakunetsu' => null,
        'sample_ac' => null,
        'sample_tc' => null,
        'vsd_mm' => null,
        'sample_ig' => null,
        'cb_weight' => null,
        'tp50_weight' => null,
        'ssi' => null,

        // Additive
        'additive_m3' => null,
        'additive_vsd' => null,
        'additive_sc' => null,

        // BC
        'bc12_cb' => null,
        'bc12_m' => null,
        'bc11_ac' => null,
        'bc11_vsd' => null,
        'bc16_cb' => null,
        'bc16_m' => null,

        // Return
        'return_time_t' => null,
        'model_type' => null,
        'moisture_bc9' => null,
        'moisture_bc10' => null,
        'moisture_bc11' => null,
        'temp_bc9' => null,
        'temp_bc10' => null,
        'temp_bc11' => null,
    ];

    // ===== Rules =====
    protected function rules(): array
    {
        return [
            // Header
            'form.process_date' => ['required', 'date'],
            'form.shift' => ['required', Rule::in(['Day', 'Night'])],
            'form.plant' => ['required', 'integer', 'in:0,1'],
            'form.mm_no' => ['required', 'integer', 'in:1,2'],
            'form.mix_no' => ['required', 'integer', 'min:1'],
            'form.mix_start_t' => ['nullable', 'date_format:H:i'],
            'form.mix_finish_t' => ['nullable', 'date_format:H:i'],

            // MM sample
            'form.sample_p' => ['nullable', 'numeric'],
            'form.sample_c' => ['nullable', 'numeric'],
            'form.sample_gt' => ['nullable', 'numeric'],
            'form.cb_mm' => ['nullable', 'numeric'],
            'form.cb_lab' => ['nullable', 'numeric'],
            'form.sample_m' => ['nullable', 'numeric'],
            'form.bakunetsu' => ['nullable', 'numeric'],
            'form.sample_ac' => ['nullable', 'numeric'],
            'form.sample_tc' => ['nullable', 'numeric'],
            'form.vsd_mm' => ['nullable', 'numeric'],
            'form.sample_ig' => ['nullable', 'numeric'],
            'form.cb_weight' => ['nullable', 'numeric'],
            'form.tp50_weight' => ['nullable', 'numeric'],
            'form.ssi' => ['nullable', 'numeric'],

            // Additive
            'form.additive_m3' => ['nullable', 'numeric'],
            'form.additive_vsd' => ['nullable', 'numeric'],
            'form.additive_sc' => ['nullable', 'numeric'],

            // BC
            'form.bc12_cb' => ['nullable', 'numeric'],
            'form.bc12_m' => ['nullable', 'numeric'],
            'form.bc11_ac' => ['nullable', 'numeric'],
            'form.bc11_vsd' => ['nullable', 'numeric'],
            'form.bc16_cb' => ['nullable', 'numeric'],
            'form.bc16_m' => ['nullable', 'numeric'],

            // Return
            'form.return_time_t' => ['nullable', 'date_format:H:i'],
            'form.model_type' => ['nullable', 'string', 'max:20'],
            'form.moisture_bc9' => ['nullable', 'numeric'],
            'form.moisture_bc10' => ['nullable', 'numeric'],
            'form.moisture_bc11' => ['nullable', 'numeric'],
            'form.temp_bc9' => ['nullable', 'numeric'],
            'form.temp_bc10' => ['nullable', 'numeric'],
            'form.temp_bc11' => ['nullable', 'numeric'],
        ];
    }

    // ====== Modal Confirm Delete ======
    public function confirmDelete(int $id): void
    {
        $this->pendingDeleteId = $id;
        $this->dispatch('gs:confirm-open');
    }

    public function deleteConfirmed(): void
    {
        if ($this->pendingDeleteId) {
            $this->delete($this->pendingDeleteId);
            $this->pendingDeleteId = null;
        }
        $this->dispatch('gs:confirm-close');
    }

    public function cancelDelete(): void
    {
        $this->pendingDeleteId = null;
        $this->dispatch('gs:confirm-close');
    }

    // ====== Actions (Modal/Form) ======
    public function setMm(int $mm): void
    {
        $this->form['mm_no'] = in_array($mm, [1, 2]) ? $mm : 1;
    }

    public function setModalTab(string $tab): void
    {
        $this->modalTab = $tab;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->formMode = 'create';
        $this->dispatch('gs:open');
    }

    public function edit(int $id): void
    {
        $row = Process::findOrFail($id);

        $this->activeTab = ((int) $row->mm_no === 1) ? 'mm1' : 'mm2';
        $this->modalTab = 'mm';
        $this->editingId = $row->id;
        $this->formMode = 'edit';

        $this->form = array_merge($this->form, [
            'process_date' => optional($row->process_date)->format('Y-m-d'),
            'shift' => $row->shift,
            'plant' => $row->plant,
            'mm_no' => (int) $row->mm_no,
            'mix_no' => $row->mix_no,
            'mix_start_t' => $row->mix_start ? $row->mix_start->format('H:i') : null,
            'mix_finish_t' => $row->mix_finish ? $row->mix_finish->format('H:i') : null,

            'sample_p' => $row->sample_p,
            'sample_c' => $row->sample_c,
            'sample_gt' => $row->sample_gt,
            'cb_mm' => $row->cb_mm,
            'cb_lab' => $row->cb_lab,
            'sample_m' => $row->sample_m,
            'bakunetsu' => $row->bakunetsu,
            'sample_ac' => $row->sample_ac,
            'sample_tc' => $row->sample_tc,
            'vsd_mm' => $row->vsd_mm,
            'sample_ig' => $row->sample_ig,
            'cb_weight' => $row->cb_weight,
            'tp50_weight' => $row->tp50_weight,
            'ssi' => $row->ssi,

            'additive_m3' => $row->additive_m3,
            'additive_vsd' => $row->additive_vsd,
            'additive_sc' => $row->additive_sc,

            'bc12_cb' => $row->bc12_cb,
            'bc12_m' => $row->bc12_m,
            'bc11_ac' => $row->bc11_ac,
            'bc11_vsd' => $row->bc11_vsd,
            'bc16_cb' => $row->bc16_cb,
            'bc16_m' => $row->bc16_m,

            'return_time_t' => $row->return_time ? $row->return_time->format('H:i') : null,
            'model_type' => $row->model_type,
            'moisture_bc9' => $row->moisture_bc9,
            'moisture_bc10' => $row->moisture_bc10,
            'moisture_bc11' => $row->moisture_bc11,
            'temp_bc9' => $row->temp_bc9,
            'temp_bc10' => $row->temp_bc10,
            'temp_bc11' => $row->temp_bc11,
        ]);

        $this->dispatch('gs:open');
    }

    public function delete(int $id): void
    {
        Process::whereKey($id)->delete();
        $this->dispatch('gs:toast', ['type' => 'success', 'text' => 'Data dihapus']);
    }

    public function submit(): void
    {
        $this->validate();

        $mixStart = $this->combineDateTime($this->form['process_date'], $this->form['mix_start_t']);
        $mixFinish = $this->combineDateTime($this->form['process_date'], $this->form['mix_finish_t']);
        $returnAt = $this->combineDateTime($this->form['process_date'], $this->form['return_time_t']);

        $exists = Process::query()
            ->when($this->formMode === 'edit', fn($q) => $q->where('id', '!=', $this->editingId))
            ->where('process_date', $this->form['process_date'])
            ->where('shift', $this->form['shift'])
            ->where('plant', $this->form['plant'])
            ->where('mm_no', $this->form['mm_no'])
            ->where('mix_no', $this->form['mix_no'])
            ->exists();

        if ($exists) {
            $this->addError('form.mix_no', 'Data mix sudah ada.');
            return;
        }

        $payload = [
            // Header
            'process_date' => $this->form['process_date'],
            'shift' => $this->form['shift'],
            'plant' => $this->form['plant'],
            'mm_no' => $this->form['mm_no'],
            'mix_no' => $this->form['mix_no'],
            'mix_start' => $mixStart,
            'mix_finish' => $mixFinish,

            // MM sample
            'sample_p' => $this->form['sample_p'],
            'sample_c' => $this->form['sample_c'],
            'sample_gt' => $this->form['sample_gt'],
            'cb_mm' => $this->form['cb_mm'],
            'cb_lab' => $this->form['cb_lab'],
            'sample_m' => $this->form['sample_m'],
            'bakunetsu' => $this->form['bakunetsu'],
            'sample_ac' => $this->form['sample_ac'],
            'sample_tc' => $this->form['sample_tc'],
            'vsd_mm' => $this->form['vsd_mm'],
            'sample_ig' => $this->form['sample_ig'],
            'cb_weight' => $this->form['cb_weight'],
            'tp50_weight' => $this->form['tp50_weight'],
            'ssi' => $this->form['ssi'],

            // Additive
            'additive_m3' => $this->form['additive_m3'],
            'additive_vsd' => $this->form['additive_vsd'],
            'additive_sc' => $this->form['additive_sc'],

            // BC
            'bc12_cb' => $this->form['bc12_cb'],
            'bc12_m' => $this->form['bc12_m'],
            'bc11_ac' => $this->form['bc11_ac'],
            'bc11_vsd' => $this->form['bc11_vsd'],
            'bc16_cb' => $this->form['bc16_cb'],
            'bc16_m' => $this->form['bc16_m'],

            // Return
            'return_time' => $returnAt,
            'model_type' => $this->form['model_type'],
            'moisture_bc9' => $this->form['moisture_bc9'],
            'moisture_bc10' => $this->form['moisture_bc10'],
            'moisture_bc11' => $this->form['moisture_bc11'],
            'temp_bc9' => $this->form['temp_bc9'],
            'temp_bc10' => $this->form['temp_bc10'],
            'temp_bc11' => $this->form['temp_bc11'],
        ];

        DB::transaction(function () use ($payload) {
            if ($this->formMode === 'edit' && $this->editingId) {
                Process::whereKey($this->editingId)->update($payload);
            } else {
                Process::create($payload);
            }
        });

        $this->dispatch('gs:close');
        $this->dispatch('gs:toast', ['type' => 'success', 'text' => 'Data berhasil disimpan']);
        $this->resetForm();
    }

    // ===== Search (berdasar UX Anda) =====
    public function applySearch(): void
    {
        $this->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'filter_shift' => ['nullable', 'in:Day,Night'],
            'searchText' => ['nullable', 'string', 'max:100'],
        ]);
        $this->search = trim($this->searchText);
        $this->resetAllPages();
    }

    public function clearSearch(): void
    {
        $this->searchText = '';
        $this->search = '';
        $this->resetAllPages();
    }

    // ===== Filter =====
    public function setDateRange(?string $start, ?string $end): void
    {
        $this->start_date = $start ?: null;
        $this->end_date = $end ?: null;
        $this->resetAllPages();
    }

    public function setFilterShift(?string $shift): void
    {
        $this->filter_shift = $shift ?: null;
        $this->resetAllPages();
    }

    public function clearFilters(): void
    {
        $this->start_date = null;
        $this->end_date = null;
        $this->filter_shift = null;
        $this->resetAllPages();
        $this->dispatch('gs:toast', ['type' => 'info', 'text' => 'Filter cleared']);
    }

    public function export(): void
    {
        $params = array_filter([
            'start' => $this->start_date ?: null,
            'end' => $this->end_date ?: null,
            'shift' => $this->filter_shift ?: null,
            'q' => $this->search !== '' ? $this->search : null,
            'mm' => ($this->activeTab === 'mm1') ? 1 : (($this->activeTab === 'mm2') ? 2 : null),
        ], fn($v) => filled($v));

        $url = route('greensand.export', $params);
        $this->dispatch('gs:export', url: $url);
    }

    // ===== Pagination & Tab =====
    public function setActiveTab(string $tab): void
    {
        $this->activeTab = in_array($tab, ['mm1', 'mm2', 'all']) ? $tab : 'mm1';
        $this->resetAllPages();
    }

    protected function resetAllPages(): void
    {
        // pastikan tiap paginator balik ke halaman 1
        $this->gotoPage(1, 'rowsMm1');
        $this->gotoPage(1, 'rowsMm2');
        $this->gotoPage(1, 'rowsAll');
    }

    public function updatedSearch(): void
    {
        $this->search = trim($this->search);
        $this->resetAllPages();
    }

    public function updatedPerPage(): void
    {
        $this->resetAllPages();
    }

    // ===== Helpers: Query builder =====
    protected function baseQuery()
    {
        $q = Process::query();

        // Filter tanggal
        if ($this->start_date) {
            $q->whereDate('process_date', '>=', $this->start_date);
        }
        if ($this->end_date) {
            $q->whereDate('process_date', '<=', $this->end_date);
        }

        // Filter shift
        if ($this->filter_shift) {
            $q->where('shift', $this->filter_shift);
        }

        // Search (mix_no / model_type / shift)
        if ($this->search !== '') {
            $keyword = '%' . $this->search . '%';
            $q->where(function ($x) use ($keyword) {
                $x->where('mix_no', 'like', $keyword)
                    ->orWhere('model_type', 'like', $keyword)
                    ->orWhere('shift', 'like', $keyword);
            });
        }

        return $q;
    }

    protected function applyMmFilter($query, string $mm): void
    {
        // mm = 'mm1' atau 'mm2'
        $num = ($mm === 'mm1') ? 1 : 2;

        // Kolom mm_no di DB Anda bertipe integer (berdasar form), jadi langsung where int.
        // Tetap tambahkan toleransi jika ternyata string/format lain.
        $query->where(function ($w) use ($num, $mm) {
            $w->where('mm_no', $num)
                ->orWhereRaw("LOWER(REPLACE(CAST(mm_no AS CHAR), ' ', '')) = ?", [$mm]); // jaga-jaga jika string "MM 1"
        });
    }

    private function combineDateTime(?string $date, ?string $time): ?string
    {
        if (!$date || !$time) {
            return null;
        }
        return $date . ' ' . $time . ':00';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->formMode = 'create';
        $this->form = array_merge($this->form, [
            'process_date' => now()->format('Y-m-d'),
            'shift' => '',
            'plant' => 0,
            'mm_no' => 1,
            'mix_no' => null,
            'mix_start_t' => null,
            'mix_finish_t' => null,
            'return_time_t' => null,
            'model_type' => null,

            'sample_p' => null,
            'sample_c' => null,
            'sample_gt' => null,
            'cb_mm' => null,
            'cb_lab' => null,
            'sample_m' => null,
            'bakunetsu' => null,
            'sample_ac' => null,
            'sample_tc' => null,
            'vsd_mm' => null,
            'sample_ig' => null,
            'cb_weight' => null,
            'tp50_weight' => null,
            'ssi' => null,

            'additive_m3' => null,
            'additive_vsd' => null,
            'additive_sc' => null,
            'bc12_cb' => null,
            'bc12_m' => null,
            'bc11_ac' => null,
            'bc11_vsd' => null,
            'bc16_cb' => null,
            'bc16_m' => null,

            'moisture_bc9' => null,
            'moisture_bc10' => null,
            'moisture_bc11' => null,
            'temp_bc9' => null,
            'temp_bc10' => null,
            'temp_bc11' => null,
        ]);
    }

    public function updatedStartDate($value): void
    {
        if (!$value) {
            $this->end_date = null;
            return;
        }
        if (!$this->end_date || $this->end_date < $value) {
            $this->end_date = $value;
        }
    }

    public function updatedEndDate($value): void
    {
        if ($this->start_date && $value < $this->start_date) {
            $this->end_date = $this->start_date;
        }
    }

    private function normalizeDateRange(): void
    {
        if ($this->start_date && (!$this->end_date || $this->end_date < $this->start_date)) {
            $this->end_date = $this->start_date;
        }
    }

    // ===== RENDER (3 tab: mm1, mm2, all) =====
    public function render()
    {
        $this->normalizeDateRange();

        $base = $this->baseQuery()->orderByDesc('process_date')->orderByDesc('mix_no');

        if ($this->activeTab === 'mm1') {
            $q1 = (clone $base);
            $this->applyMmFilter($q1, 'mm1');

            $rowsMm1 = $q1->paginate($this->perPage, ['*'], 'rowsMm1');

            return view('livewire.greensand.green-sand', [
                'currentRows' => $rowsMm1,
                'rowsMm1' => $rowsMm1,
            ]);
        }

        if ($this->activeTab === 'mm2') {
            $q2 = (clone $base);
            $this->applyMmFilter($q2, 'mm2');

            $rowsMm2 = $q2->paginate($this->perPage, ['*'], 'rowsMm2');

            return view('livewire.greensand.green-sand', [
                'currentRows' => $rowsMm2,
                'rowsMm2' => $rowsMm2,
            ]);
        }

        // Tab ALL (tanpa filter mm)
        $rowsAll = $base->paginate($this->perPage, ['*'], 'rowsAll');

        return view('livewire.greensand.green-sand', [
            'currentRows' => $rowsAll,
            'rowsAll' => $rowsAll,
        ]);
    }
}
