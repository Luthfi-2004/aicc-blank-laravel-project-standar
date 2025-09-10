<?php

namespace App\Http\Livewire\GreenSand;

use App\Models\Process; // model
use Illuminate\Validation\Rule; // val
use Livewire\Component; // lw
use Illuminate\Support\Facades\DB; // db
use Illuminate\Database\Eloquent\Builder; // qb
use Carbon\Carbon; // time
use Maatwebsite\Excel\Facades\Excel; // xls
use App\Exports\GreensandExportFull; // exp

class Greensand extends Component
{
    // state
    public string $activeTab = 'mm1'; // tab
    public string $modalTab = 'mm'; // tab
    public string $formMode = 'create'; // mode
    public ?int $editingId = null; // id

    // search
    public string $search = ''; // srch
    public string $searchText = ''; // srch
    public ?string $start_date = null; // filt
    public ?string $end_date = null; // filt
    public ?string $filter_shift = null; // filt
    public bool $filterOpen = true; // ui

    public ?int $pendingDeleteId = null; // del

    protected array $queryString = [
        'activeTab' => ['except' => 'mm1'],
        'search' => ['except' => ''],
        'start_date' => ['except' => null],
        'end_date' => ['except' => null],
        'filter_shift' => ['except' => null],
    ]; // qs

    // form
    public array $form = [
        'process_date' => null,
        'shift' => '',
        'plant' => 0,
        'mm_no' => 1,
        'mix_no' => null,
        'mix_start_t' => null,
        'mix_finish_t' => null,
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
        'return_time_t' => null,
        'model_type' => null,
        'moisture_bc9' => null,
        'moisture_bc10' => null,
        'moisture_bc11' => null,
        'temp_bc9' => null,
        'temp_bc10' => null,
        'temp_bc11' => null,
    ]; // form

    // const
    private const MM_TABS = ['mm1', 'mm2', 'all']; // const
    private const MODAL_TABS = ['mm', 'additive', 'bc', 'return']; // const
    private const NUM_FIELDS = [ // const
        'sample_p',
        'sample_c',
        'sample_gt',
        'cb_mm',
        'cb_lab',
        'sample_m',
        'bakunetsu',
        'sample_ac',
        'sample_tc',
        'vsd_mm',
        'sample_ig',
        'cb_weight',
        'tp50_weight',
        'ssi',
        'additive_m3',
        'additive_vsd',
        'additive_sc',
        'bc12_cb',
        'bc12_m',
        'bc11_ac',
        'bc11_vsd',
        'bc16_cb',
        'bc16_m',
        'moisture_bc9',
        'moisture_bc10',
        'moisture_bc11',
        'temp_bc9',
        'temp_bc10',
        'temp_bc11',
    ];

    // rules
    protected function rules(): array
    {
        $r = [
            'form.process_date' => ['required', 'date'],
            'form.shift' => ['required', Rule::in(['D', 'S', 'N'])],
            'form.plant' => ['required', 'integer', 'in:0,1'],
            'form.mm_no' => ['required', 'integer', 'in:1,2'],
            'form.mix_no' => ['required', 'integer', 'min:1'],
            'form.mix_start_t' => ['nullable', 'date_format:H:i'],
            'form.mix_finish_t' => ['nullable', 'date_format:H:i'],
            'form.return_time_t' => ['nullable', 'date_format:H:i'],
            'form.model_type' => ['nullable', 'string', 'max:20'],
        ];
        foreach (self::NUM_FIELDS as $f)
            $r["form.$f"] = ['nullable', 'numeric'];
        return $r; // ret
    } // rules

    protected $listeners = ['gs:modal-open' => 'onModalOpen']; // lst

    // init
    private function initialForm(): array
    {
        return array_replace($this->form, [
            'process_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'shift' => '',
            'plant' => 0,
            'mm_no' => 1,
            'mix_no' => null,
            'mix_start_t' => null,
            'mix_finish_t' => null,
            'return_time_t' => null,
            'model_type' => null,
        ]); // ret
    } // init

    // create
    public function create(): void
    {
        $this->formMode = 'create';
        $this->editingId = null;
        $this->resetValidation();
        $this->resetErrorBag();
        $this->form = $this->initialForm();
        $this->dispatch('gs:open');
    } // add

    public function onModalOpen(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
    } // open

    // edit
    public function edit(int $id): void
    {
        $row = Process::findOrFail($id);

        $this->resetValidation();
        $this->resetErrorBag();

        $this->activeTab = ((int) $row->mm_no === 1) ? 'mm1' : 'mm2';
        $this->modalTab = 'mm';
        $this->editingId = $row->id;
        $this->formMode = 'edit';

        $this->form = array_replace($this->form, [
            'process_date' => optional($row->process_date)->format('Y-m-d H:i:s'),
            'shift' => $row->shift,
            'plant' => $row->plant,
            'mm_no' => (int) $row->mm_no,
            'mix_no' => $row->mix_no,
            'mix_start_t' => $row->mix_start?->format('H:i'),
            'mix_finish_t' => $row->mix_finish?->format('H:i'),
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
            'return_time_t' => $row->return_time?->format('H:i'),
            'model_type' => $row->model_type,
            'moisture_bc9' => $row->moisture_bc9,
            'moisture_bc10' => $row->moisture_bc10,
            'moisture_bc11' => $row->moisture_bc11,
            'temp_bc9' => $row->temp_bc9,
            'temp_bc10' => $row->temp_bc10,
            'temp_bc11' => $row->temp_bc11,
        ]);

        $this->dispatch('gs:open');
    } // edit

    // delete
    public function delete(int $id): void
    {
        try {
            Process::whereKey($id)->delete();
            $this->toast('success', 'Data berhasil dihapus', 'Berhasil');
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error', 'Terjadi kesalahan saat menghapus data', 'Gagal');
        }
    } // del

    // submit
    public function submit(): void
    {
        if ($this->formMode === 'create') {
            $this->form['process_date'] = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        }

        $this->validate();

        $dt = Carbon::parse($this->form['process_date'])->format('Y-m-d H:i:s');
        $mixStart = $this->combineDateTime($dt, $this->form['mix_start_t']);
        $mixFinish = $this->combineDateTime($dt, $this->form['mix_finish_t']);
        $returnAt = $this->combineDateTime($dt, $this->form['return_time_t']);

        $exists = Process::query()
            ->when($this->formMode === 'edit', fn(Builder $q) => $q->where('id', '!=', $this->editingId))
            ->whereDate('process_date', Carbon::parse($dt)->toDateString())
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
            'process_date' => $dt,
            'shift' => $this->form['shift'],
            'plant' => $this->form['plant'],
            'mm_no' => $this->form['mm_no'],
            'mix_no' => $this->form['mix_no'],
            'mix_start' => $mixStart,
            'mix_finish' => $mixFinish,
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
            'additive_m3' => $this->form['additive_m3'],
            'additive_vsd' => $this->form['additive_vsd'],
            'additive_sc' => $this->form['additive_sc'],
            'bc12_cb' => $this->form['bc12_cb'],
            'bc12_m' => $this->form['bc12_m'],
            'bc11_ac' => $this->form['bc11_ac'],
            'bc11_vsd' => $this->form['bc11_vsd'],
            'bc16_cb' => $this->form['bc16_cb'],
            'bc16_m' => $this->form['bc16_m'],
            'return_time' => $returnAt,
            'model_type' => $this->form['model_type'],
            'moisture_bc9' => $this->form['moisture_bc9'],
            'moisture_bc10' => $this->form['moisture_bc10'],
            'moisture_bc11' => $this->form['moisture_bc11'],
            'temp_bc9' => $this->form['temp_bc9'],
            'temp_bc10' => $this->form['temp_bc10'],
            'temp_bc11' => $this->form['temp_bc11'],
        ];

        try {
            DB::transaction(function () use ($payload) {
                if ($this->formMode === 'edit' && $this->editingId) {
                    Process::whereKey($this->editingId)->update($payload);
                } else {
                    Process::create($payload);
                }
            });

            $this->activeTab = ((int) $this->form['mm_no'] === 2) ? 'mm2' : 'mm1';
            $this->dispatch('gs:close');

            $msg = $this->formMode === 'edit' ? 'Data berhasil diperbarui' : 'Data berhasil ditambahkan';
            $this->toast('success', $msg, 'Berhasil');
            $this->resetForm();
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error', 'Terjadi kesalahan saat menyimpan data', 'Gagal');
        }
    } // save

    // search
    public function applySearch(): void
    {
        $this->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'filter_shift' => ['nullable', 'in:D,S,N'],
            'searchText' => ['nullable', 'string', 'max:100'],
        ]);
        $this->search = trim($this->searchText);
    } // filt

    public function clearSearch(): void
    {
        $this->searchText = '';
        $this->search = '';
    } // clr
    public function setDateRange(?string $s, ?string $e): void
    {
        $this->start_date = $s ?: null;
        $this->end_date = $e ?: null;
    } // date
    public function setFilterShift(?string $v): void
    {
        $this->filter_shift = $v ?: null;
    } // shift

    public function clearFilters(): void
    {
        $this->start_date = $this->end_date = $this->filter_shift = null;
        $this->toast('info', 'Filter dibersihkan', 'Info');
    } // clr

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, self::MM_TABS, true))
            $this->activeTab = $tab;
    } // tab

    // modal
    public function setMm(int|string $mm): void
    {
        $this->form['mm_no'] = ((int) $mm === 2) ? 2 : 1;
    } // mm

    public function setModalTab(string $tab): void
    {
        if (in_array($tab, self::MODAL_TABS, true))
            $this->modalTab = $tab;
    } // mtab

    public function updatedSearch(): void
    {
        $this->search = trim($this->search);
    } // sync

    // query
    protected function baseQuery(): Builder
    {
        return Process::query()
            ->when($this->start_date, fn($q) => $q->whereDate('process_date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('process_date', '<=', $this->end_date))
            ->when($this->filter_shift, fn($q) => $q->where('shift', $this->filter_shift));
    } // base

    protected function applyMmFilter(Builder $q, string $mm): void
    {
        $num = $mm === 'mm1' ? 1 : 2;
        $q->where(fn($w) => $w->where('mm_no', $num)
            ->orWhereRaw("LOWER(REPLACE(CAST(mm_no AS CHAR), ' ', '')) = ?", [$mm]));
    } // mm

    // util
    private function combineDateTime(?string $dateOrDatetime, ?string $time): ?string
    {
        if (!$dateOrDatetime || !$time)
            return null;
        $d = Carbon::parse($dateOrDatetime);
        if (strlen($time) === 5)
            $time .= ':00';
        $d->setTimeFromTimeString($time);
        return $d->format('Y-m-d H:i:s');
    } // join

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->formMode = 'create';
        $this->form = $this->initialForm();
    } // rst

    private function normalizeDateRange(): void
    {
        if ($this->start_date && (!$this->end_date || $this->end_date < $this->start_date)) {
            $this->end_date = $this->start_date;
        }
    } // norm

    // export
    public function export(string $scope = 'all')
    {
        $mm = $scope === 'mm1' ? 1 : ($scope === 'mm2' ? 2 : null);
        $filename = sprintf('greensand_%s_%s.xlsx', $scope, now('Asia/Jakarta')->format('Ymd_His'));

        return Excel::download(new GreensandExportFull(
            start: $this->start_date,
            end: $this->end_date,
            shift: $this->filter_shift,
            q: $this->search !== '' ? $this->search : null,
            mm: $mm
        ), $filename);
    } // xls

    // view
    public function render()
    {
        $this->normalizeDateRange();

        $q = $this->baseQuery()
            ->orderByDesc('process_date')
            ->orderByDesc('mix_no');

        if ($this->activeTab === 'mm1' || $this->activeTab === 'mm2') {
            $this->applyMmFilter($q, $this->activeTab);
        }

        $rows = $q->get();
        return view('livewire.greensand.green-sand', ['currentRows' => $rows]);
    } // rend

    // toast
    private function toast(string $type, string $text, string $title = ''): void
    {
        $this->dispatch('gs:toast', compact('type', 'text', 'title'));
    } // toast
}
