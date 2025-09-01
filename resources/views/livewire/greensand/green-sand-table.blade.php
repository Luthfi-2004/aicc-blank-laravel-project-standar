<!-- resources/views/livewire/greensand/green-sand-table.blade.php -->
<div class="card">
  <div class="card-body">

    {{-- TAB NAVIGATION (tetap) --}}
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item">
        <a href="#" class="nav-link {{ $activeTab === 'mm1' ? 'active' : '' }}" wire:click.prevent="setActiveTab('mm1')">
          <i class="ri-layout-grid-line mr-1"></i> MM 1
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ $activeTab === 'mm2' ? 'active' : '' }}" wire:click.prevent="setActiveTab('mm2')">
          <i class="ri-layout-grid-fill mr-1"></i> MM 2
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}" wire:click.prevent="setActiveTab('all')">
          <i class="ri-stack-line mr-1"></i> All
        </a>
      </li>
    </ul>

    {{-- HAPUS blok PERPAGE & SEARCH Livewire karena akan ditangani DataTables --}}

    {{-- DATA TABLE (full DataTables) --}}
    <div class="table-responsive">
      <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse:collapse; width:100%;">
        @includeIf('livewire.greensand._thead')
        <tbody>
          {{-- PENTING: $currentRows harus berisi SEMUA baris hasil filter (bukan paginate) --}}
          @forelse ($currentRows as $row)
            <tr wire:key="row-{{ $row->id }}">
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-outline-warning btn-sm mr-2" wire:click="edit({{ $row->id }})">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-sm" wire:click="confirmDelete({{ $row->id }})">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>

              {{-- KOLOM DATA --}}
              <td class="text-center">{{ optional($row->process_date)->format('Y-m-d') }}</td>
              <td class="text-center">{{ $row->shift }}</td>
              <td class="text-center">{{ $row->mm_no }}</td>
              <td class="text-center">{{ $row->mix_no }}</td>
              <td class="text-center">{{ optional($row->mix_start)->format('H:i') }}</td>
              <td class="text-center">{{ optional($row->mix_finish)->format('H:i') }}</td>
              <td class="text-center">{{ $row->sample_p }}</td>
              <td class="text-center">{{ $row->sample_c }}</td>
              <td class="text-center">{{ $row->sample_gt }}</td>
              <td class="text-center">{{ $row->cb_mm }}</td>
              <td class="text-center">{{ $row->cb_lab }}</td>
              <td class="text-center">{{ $row->sample_m }}</td>
              <td class="text-center">{{ $row->bakunetsu }}</td>
              <td class="text-center">{{ $row->sample_ac }}</td>
              <td class="text-center">{{ $row->sample_tc }}</td>
              <td class="text-center">{{ $row->vsd_mm }}</td>
              <td class="text-center">{{ $row->sample_ig }}</td>
              <td class="text-center">{{ $row->cb_weight }}</td>
              <td class="text-center">{{ $row->tp50_weight }}</td>
              <td class="text-center">{{ $row->ssi }}</td>
              <td class="text-center">{{ $row->additive_m3 }}</td>
              <td class="text-center">{{ $row->additive_vsd }}</td>
              <td class="text-center">{{ $row->additive_sc }}</td>
              <td class="text-center">{{ $row->bc12_cb }}</td>
              <td class="text-center">{{ $row->bc12_m }}</td>
              <td class="text-center">{{ $row->bc11_ac }}</td>
              <td class="text-center">{{ $row->bc11_vsd }}</td>
              <td class="text-center">{{ $row->bc16_cb }}</td>
              <td class="text-center">{{ $row->bc16_m }}</td>
              <td class="text-center">{{ optional($row->return_time)->format('H:i') }}</td>
              <td class="text-center">{{ $row->model_type }}</td>
              <td class="text-center">{{ $row->moisture_bc9 }}</td>
              <td class="text-center">{{ $row->moisture_bc10 }}</td>
              <td class="text-center">{{ $row->moisture_bc11 }}</td>
              <td class="text-center">{{ $row->temp_bc9 }}</td>
              <td class="text-center">{{ $row->temp_bc10 }}</td>
              <td class="text-center">{{ $row->temp_bc11 }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="36" class="text-center text-muted">Belum ada data.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- HAPUS blok PAGINATION Livewire karena paging ditangani DataTables --}}
  </div>
</div>
