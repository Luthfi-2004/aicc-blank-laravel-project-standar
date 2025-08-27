<?php

namespace App\Http\Livewire\GreenSand;

use App\Models\Process;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Greensand extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ===== UI State =====
    public string $activeTab = 'mm1';
    public string $modalTab = 'mm';
    public string $formMode = 'create';
    public ?int $editingId = null;

    // Tabel controls
    public string $search = '';
    public int $perPage = 10;
    public string $searchText = '';

    public function applySearch(): void
{
    $this->search = trim($this->searchText);
    $this->resetPage('page_mm1');
    $this->resetPage('page_mm2');
}

public function clearSearch(): void
{
    $this->searchText = '';
    $this->search = '';
    $this->resetPage('page_mm1');
    $this->resetPage('page_mm2');
}

    // Tambahan: bind query string agar state konsisten
    protected array $queryString = [
        'activeTab' => ['except' => 'mm1'],
        'search'    => ['except' => ''],
        'perPage'   => ['except' => 10],
    ];

    // ===== Form State =====
    public array $form = [
        // Header
        'process_date' => null,
        'shift' => '',
        'plant' => 0,
        'mm_no' => 1,
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

    // ===== Table Data Providers (dua paginator, satu tabel) =====
    public function getRowsMm1Property()
    {
        return $this->queryRows(1);
    }

    public function getRowsMm2Property()
    {
        return $this->queryRows(2);
    }

    public function getCurrentRowsProperty()
    {
        return $this->activeTab === 'mm1' ? $this->rowsMm1 : $this->rowsMm2;
    }

    // ===== Actions (Tab, Modal, CRUD) =====
    public function setActiveTab(string $tab): void
    {
        $this->activeTab = in_array($tab, ['mm1', 'mm2']) ? $tab : 'mm1';
        // penting: reset paginator sesuai tab agar hasil sinkron
        if ($this->activeTab === 'mm1') {
            $this->resetPage('page_mm1');
        } else {
            $this->resetPage('page_mm2');
        }
    }

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

        $this->activeTab = $row->mm_no === 1 ? 'mm1' : 'mm2';
        $this->modalTab = 'mm';
        $this->editingId = $row->id;
        $this->formMode = 'edit';

        $this->form = array_merge($this->form, [
            'process_date' => optional($row->process_date)->format('Y-m-d'),
            'shift' => $row->shift,
            'plant' => $row->plant,
            'mm_no' => $row->mm_no,
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

        $mixStart  = $this->combineDateTime($this->form['process_date'], $this->form['mix_start_t']);
        $mixFinish = $this->combineDateTime($this->form['process_date'], $this->form['mix_finish_t']);
        $returnAt  = $this->combineDateTime($this->form['process_date'], $this->form['return_time_t']);

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

    // ===== Helpers =====
    private function queryRows(int $mm)
    {
        return Process::query()
            ->where('mm_no', $mm)
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($qq) use ($s) {
                    $qq->where('mix_no', 'like', $s)
                       ->orWhere('model_type', 'like', $s);
                });
            })
            ->orderByDesc('process_date')
            ->orderByDesc('mix_no')
            ->paginate($this->perPage, ['*'], $mm === 1 ? 'page_mm1' : 'page_mm2');
    }

    private function combineDateTime(?string $date, ?string $time): ?string
    {
        if (!$date || !$time) return null;
        return "$date $time:00";
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

    // Reaktif: reset halaman saat state kontrol berubah
    public function updatedSearch()
    {
        $this->search = trim($this->search);
        $this->resetPage('page_mm1');
        $this->resetPage('page_mm2');
    }

    public function updatedPerPage()
    {
        $this->resetPage('page_mm1');
        $this->resetPage('page_mm2');
    }

    public function render()
    {
        return view('livewire.greensand.green-sand', [
            'rowsMm1' => $this->rowsMm1,
            'rowsMm2' => $this->rowsMm2,
            'currentRows' => $this->currentRows,
        ]);
    }
}
