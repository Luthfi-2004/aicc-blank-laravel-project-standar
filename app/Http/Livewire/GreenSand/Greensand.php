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
    public string $activeTab = 'mm1'; // tab
    public string $modalTab = 'mm'; // tab
    public string $formMode = 'create'; // mode
    public ?int $editingId = null; // id

    public string $search = ''; // srch
    public string $searchText = ''; // srch

    public ?string $start_date = null; // filt
    public ?string $end_date = null; // filt
    public ?string $filter_shift = null; // filt
    public bool $filterOpen = true; // ui

    public ?int $pendingDeleteId = null; // del

    protected array $queryString = [
        'activeTab'    => ['except' => 'mm1'],
        'search'       => ['except' => ''],
        'start_date'   => ['except' => null],
        'end_date'     => ['except' => null],
        'filter_shift' => ['except' => null],
    ]; // qs

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

    protected function rules(): array
    {
        return [
            'form.process_date' => ['required', 'date'], // req
            'form.shift' => ['required', Rule::in(['D', 'S', 'N'])], // req
            'form.plant' => ['required', 'integer', 'in:0,1'], // req
            'form.mm_no' => ['required', 'integer', 'in:1,2'], // req
            'form.mix_no' => ['required', 'integer', 'min:1'], // req
            'form.mix_start_t' => ['nullable', 'date_format:H:i'], // fmt
            'form.mix_finish_t' => ['nullable', 'date_format:H:i'], // fmt
            'form.sample_p' => ['nullable', 'numeric'], // num
            'form.sample_c' => ['nullable', 'numeric'], // num
            'form.sample_gt' => ['nullable', 'numeric'], // num
            'form.cb_mm' => ['nullable', 'numeric'], // num
            'form.cb_lab' => ['nullable', 'numeric'], // num
            'form.sample_m' => ['nullable', 'numeric'], // num
            'form.bakunetsu' => ['nullable', 'numeric'], // num
            'form.sample_ac' => ['nullable', 'numeric'], // num
            'form.sample_tc' => ['nullable', 'numeric'], // num
            'form.vsd_mm' => ['nullable', 'numeric'], // num
            'form.sample_ig' => ['nullable', 'numeric'], // num
            'form.cb_weight' => ['nullable', 'numeric'], // num
            'form.tp50_weight' => ['nullable', 'numeric'], // num
            'form.ssi' => ['nullable', 'numeric'], // num
            'form.additive_m3' => ['nullable', 'numeric'], // num
            'form.additive_vsd' => ['nullable', 'numeric'], // num
            'form.additive_sc' => ['nullable', 'numeric'], // num
            'form.bc12_cb' => ['nullable', 'numeric'], // num
            'form.bc12_m' => ['nullable', 'numeric'], // num
            'form.bc11_ac' => ['nullable', 'numeric'], // num
            'form.bc11_vsd' => ['nullable', 'numeric'], // num
            'form.bc16_cb' => ['nullable', 'numeric'], // num
            'form.bc16_m' => ['nullable', 'numeric'], // num
            'form.return_time_t' => ['nullable', 'date_format:H:i'], // fmt
            'form.model_type' => ['nullable', 'string', 'max:20'], // str
            'form.moisture_bc9' => ['nullable', 'numeric'], // num
            'form.moisture_bc10' => ['nullable', 'numeric'], // num
            'form.moisture_bc11' => ['nullable', 'numeric'], // num
            'form.temp_bc9' => ['nullable', 'numeric'], // num
            'form.temp_bc10' => ['nullable', 'numeric'], // num
            'form.temp_bc11' => ['nullable', 'numeric'], // num
        ]; // ret
    } // rules

    protected $listeners = ['gs:modal-open' => 'onModalOpen']; // lst

    public function create(): void
    {
        $this->formMode = 'create'; // mode
        $this->editingId = null; // clr
        $this->resetValidation(); // rst
        $this->resetErrorBag(); // rst
        $this->reset('form'); // rst
        $this->form['process_date'] = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'); // now
        $this->dispatch('gs:open'); // evt
    } // add

    public function onModalOpen(): void
    {
        $this->resetValidation(); // rst
        $this->resetErrorBag(); // rst
    } // open

    public function edit(int $id): void
    {
        $row = Process::findOrFail($id); // get

        $this->resetValidation(); // rst
        $this->resetErrorBag(); // rst

        $this->activeTab = ((int) $row->mm_no === 1) ? 'mm1' : 'mm2'; // ui
        $this->modalTab = 'mm'; // ui
        $this->editingId = $row->id; // id
        $this->formMode = 'edit'; // mode

        $this->form = array_merge($this->form, [
            'process_date' => optional($row->process_date)->format('Y-m-d H:i:s'), // map
            'shift' => $row->shift, // map
            'plant' => $row->plant, // map
            'mm_no' => (int) $row->mm_no, // map
            'mix_no' => $row->mix_no, // map
            'mix_start_t' => $row->mix_start ? $row->mix_start->format('H:i') : null, // map
            'mix_finish_t' => $row->mix_finish ? $row->mix_finish->format('H:i') : null, // map
            'sample_p' => $row->sample_p, // map
            'sample_c' => $row->sample_c, // map
            'sample_gt' => $row->sample_gt, // map
            'cb_mm' => $row->cb_mm, // map
            'cb_lab' => $row->cb_lab, // map
            'sample_m' => $row->sample_m, // map
            'bakunetsu' => $row->bakunetsu, // map
            'sample_ac' => $row->sample_ac, // map
            'sample_tc' => $row->sample_tc, // map
            'vsd_mm' => $row->vsd_mm, // map
            'sample_ig' => $row->sample_ig, // map
            'cb_weight' => $row->cb_weight, // map
            'tp50_weight' => $row->tp50_weight, // map
            'ssi' => $row->ssi, // map
            'additive_m3' => $row->additive_m3, // map
            'additive_vsd' => $row->additive_vsd, // map
            'additive_sc' => $row->additive_sc, // map
            'bc12_cb' => $row->bc12_cb, // map
            'bc12_m' => $row->bc12_m, // map
            'bc11_ac' => $row->bc11_ac, // map
            'bc11_vsd' => $row->bc11_vsd, // map
            'bc16_cb' => $row->bc16_cb, // map
            'bc16_m' => $row->bc16_m, // map
            'return_time_t' => $row->return_time ? $row->return_time->format('H:i') : null, // map
            'model_type' => $row->model_type, // map
            'moisture_bc9' => $row->moisture_bc9, // map
            'moisture_bc10' => $row->moisture_bc10, // map
            'moisture_bc11' => $row->moisture_bc11, // map
            'temp_bc9' => $row->temp_bc9, // map
            'temp_bc10' => $row->temp_bc10, // map
            'temp_bc11' => $row->temp_bc11, // map
        ]); // form

        $this->dispatch('gs:open'); // evt
    } // edit

    public function delete(int $id): void
    {
        try {
            Process::whereKey($id)->delete(); // del
            $this->toast('success', 'Data berhasil dihapus', 'Berhasil'); // ok
        } catch (\Throwable $e) {
            report($e); // log
            $this->toast('error', 'Terjadi kesalahan saat menghapus data', 'Gagal'); // err
        }
    } // del

    public function submit(): void
    {
        if ($this->formMode === 'create') {
            $this->form['process_date'] = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'); // now
        }

        $this->validate(); // val

        $processDateDT = Carbon::parse($this->form['process_date'])->format('Y-m-d H:i:s'); // dt
        $mixStart  = $this->combineDateTime($processDateDT, $this->form['mix_start_t']); // dt
        $mixFinish = $this->combineDateTime($processDateDT, $this->form['mix_finish_t']); // dt
        $returnAt  = $this->combineDateTime($processDateDT, $this->form['return_time_t']); // dt

        $exists = Process::query()
            ->when($this->formMode === 'edit', fn (Builder $q) => $q->where('id', '!=', $this->editingId)) // neq
            ->whereDate('process_date', Carbon::parse($processDateDT)->toDateString()) // date
            ->where('shift', $this->form['shift']) // filt
            ->where('plant', $this->form['plant']) // filt
            ->where('mm_no', $this->form['mm_no']) // filt
            ->where('mix_no', $this->form['mix_no']) // filt
            ->exists(); // dup

        if ($exists) {
            $this->addError('form.mix_no', 'Data mix sudah ada.'); // err
            return; // stop (tanpa toast warning lagi)
        }

        $payload = [
            'process_date' => $processDateDT,
            'shift'        => $this->form['shift'],
            'plant'        => $this->form['plant'],
            'mm_no'        => $this->form['mm_no'],
            'mix_no'       => $this->form['mix_no'],
            'mix_start'    => $mixStart,
            'mix_finish'   => $mixFinish,
            'sample_p'     => $this->form['sample_p'],
            'sample_c'     => $this->form['sample_c'],
            'sample_gt'    => $this->form['sample_gt'],
            'cb_mm'        => $this->form['cb_mm'],
            'cb_lab'       => $this->form['cb_lab'],
            'sample_m'     => $this->form['sample_m'],
            'bakunetsu'    => $this->form['bakunetsu'],
            'sample_ac'    => $this->form['sample_ac'],
            'sample_tc'    => $this->form['sample_tc'],
            'vsd_mm'       => $this->form['vsd_mm'],
            'sample_ig'    => $this->form['sample_ig'],
            'cb_weight'    => $this->form['cb_weight'],
            'tp50_weight'  => $this->form['tp50_weight'],
            'ssi'          => $this->form['ssi'],
            'additive_m3'  => $this->form['additive_m3'],
            'additive_vsd' => $this->form['additive_vsd'],
            'additive_sc'  => $this->form['additive_sc'],
            'bc12_cb'      => $this->form['bc12_cb'],
            'bc12_m'       => $this->form['bc12_m'],
            'bc11_ac'      => $this->form['bc11_ac'],
            'bc11_vsd'     => $this->form['bc11_vsd'],
            'bc16_cb'      => $this->form['bc16_cb'],
            'bc16_m'       => $this->form['bc16_m'],
            'return_time'  => $returnAt,
            'model_type'   => $this->form['model_type'],
            'moisture_bc9'  => $this->form['moisture_bc9'],
            'moisture_bc10' => $this->form['moisture_bc10'],
            'moisture_bc11' => $this->form['moisture_bc11'],
            'temp_bc9'      => $this->form['temp_bc9'],
            'temp_bc10'     => $this->form['temp_bc10'],
            'temp_bc11'     => $this->form['temp_bc11'],
        ]; // data

        try {
            DB::transaction(function () use ($payload) {
                if ($this->formMode === 'edit' && $this->editingId) {
                    Process::whereKey($this->editingId)->update($payload); // upd
                } else {
                    Process::create($payload); // ins
                }
            }); // tx

            $this->dispatch('gs:close'); // evt

            $msg = ($this->formMode === 'edit')
                ? 'Data berhasil diperbarui'
                : 'Data berhasil ditambahkan'; // msg

            $this->toast('success', $msg, 'Berhasil'); // hanya sukses
            $this->resetForm(); // rst

        } catch (\Throwable $e) {
            report($e); // log
            $this->toast('error', 'Terjadi kesalahan saat menyimpan data', 'Gagal'); // err
        }
    } // save

    public function applySearch(): void
    {
        $this->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'filter_shift' => ['nullable', 'in:D,S,N'],
            'searchText' => ['nullable', 'string', 'max:100'],
        ]); // val

        $this->search = trim($this->searchText); // set
    } // filt

    public function clearSearch(): void
    {
        $this->searchText = ''; // clr
        $this->search = ''; // clr
    } // clr

    public function setDateRange(?string $start, ?string $end): void
    {
        $this->start_date = $start ?: null; // set
        $this->end_date = $end ?: null; // set
    } // date

    public function setFilterShift(?string $shift): void
    {
        $this->filter_shift = $shift ?: null; // set
    } // shift

    public function clearFilters(): void
    {
        $this->start_date = null; // clr
        $this->end_date = null; // clr
        $this->filter_shift = null; // clr
        $this->toast('info', 'Filter dibersihkan', 'Info'); // info
    } // clr

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['mm1', 'mm2', 'all'], true)) {
            $this->activeTab = $tab; // set
        }
    } // tab

    public function updatedSearch(): void
    {
        $this->search = trim($this->search); // sync
    } // sync

    protected function baseQuery(): Builder
    {
        $q = Process::query(); // base

        if ($this->start_date) {
            $q->whereDate('process_date', '>=', $this->start_date); // filt
        }
        if ($this->end_date) {
            $q->whereDate('process_date', '<=', $this->end_date); // filt
        }
        if ($this->filter_shift) {
            $q->where('shift', $this->filter_shift); // filt
        }

        return $q; // ret
    } // base

    protected function applyMmFilter(Builder $query, string $mm): void
    {
        $num = ($mm === 'mm1') ? 1 : 2; // map

        $query->where(function ($w) use ($num, $mm) {
            $w->where('mm_no', $num)
              ->orWhereRaw("LOWER(REPLACE(CAST(mm_no AS CHAR), ' ', '')) = ?", [$mm]);
        }); // or
    } // mm

    private function combineDateTime(?string $dateOrDatetime, ?string $time): ?string
    {
        if (!$dateOrDatetime || !$time) return null; // guard
        $d = Carbon::parse($dateOrDatetime); // parse
        if (strlen($time) === 5) {
            $time .= ':00'; // sec
        }
        $d->setTimeFromTimeString($time); // set
        return $d->format('Y-m-d H:i:s'); // out
    } // join

    private function resetForm(): void
    {
        $this->editingId = null; // clr
        $this->formMode = 'create'; // mode
        $this->form = array_merge($this->form, [
            'process_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
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
        ]); // set
    } // rst

    private function normalizeDateRange(): void
    {
        if ($this->start_date && (!$this->end_date || $this->end_date < $this->start_date)) {
            $this->end_date = $this->start_date; // fix
        }
    } // norm

    public function export(string $scope = 'all')
    {
        $mm = $scope === 'mm1' ? 1 : ($scope === 'mm2' ? 2 : null); // mm

        $filename = sprintf(
            'greensand_%s_%s.xlsx',
            $scope,
            now('Asia/Jakarta')->format('Ymd_His')
        ); // name

        return Excel::download(
            new GreensandExportFull(
                start: $this->start_date,
                end: $this->end_date,
                shift: $this->filter_shift,
                q: $this->search !== '' ? $this->search : null,
                mm: $mm
            ),
            $filename
        ); // dl
    } // xls

    public function render()
    {
        $this->normalizeDateRange(); // norm

        $base = $this->baseQuery()
            ->orderByDesc('process_date')
            ->orderByDesc('mix_no'); // sort

        if ($this->activeTab === 'mm1') {
            $q1 = (clone $base); // dup
            $this->applyMmFilter($q1, 'mm1'); // filt
            $rows = $q1->get(); // get
            return view('livewire.greensand.green-sand', ['currentRows' => $rows]); // view
        }

        if ($this->activeTab === 'mm2') {
            $q2 = (clone $base); // dup
            $this->applyMmFilter($q2, 'mm2'); // filt
            $rows = $q2->get(); // get
            return view('livewire.greensand.green-sand', ['currentRows' => $rows]); // view
        }

        $rowsAll = $base->get(); // all
        return view('livewire.greensand.green-sand', ['currentRows' => $rowsAll]); // view
    } // rend

    private function toast(string $type, string $text, string $title = ''): void
    {
        $this->dispatch('gs:toast', [
            'type' => $type,
            'text' => $text,
            'title' => $title,
        ]); // evt
    } // toast
}
