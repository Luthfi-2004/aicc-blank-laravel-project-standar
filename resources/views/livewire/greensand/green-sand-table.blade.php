<div class="card">
    <div class="card-body">

        {{-- ====== TAB NAVIGATION ====== --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a href="#" class="nav-link {{ $activeTab === 'mm1' ? 'active' : '' }}"
                   wire:click.prevent="setActiveTab('mm1')">
                    <i class="ri-layout-grid-line mr-1"></i> MM 1
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ $activeTab === 'mm2' ? 'active' : '' }}"
                   wire:click.prevent="setActiveTab('mm2')">
                    <i class="ri-layout-grid-fill mr-1"></i> MM 2
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}"
                   wire:click.prevent="setActiveTab('all')">
                    <i class="ri-stack-line mr-1"></i> All
                </a>
            </li>
        </ul>

        {{-- ====== PERPAGE & SEARCH ====== --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center">
                <span class="mr-2">Show</span>
                <select class="custom-select custom-select-sm w-auto" wire:model.live="perPage">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
                <span class="ml-2">entries</span>
            </div>

            <div class="position-relative" style="width:260px">
                <input type="text"
                       class="form-control form-control-sm pr-5 rounded-pill"
                       placeholder="Mix / Type"
                       wire:model.defer="searchText"
                       wire:keydown.enter="applySearch">

                <button type="button" class="btn btn-sm position-absolute"
                        style="right:34px; top:50%; transform:translateY(-50%); border:none; background:transparent"
                        title="Apply search" wire:click="applySearch">
                    <i class="ri-search-line"></i>
                </button>

                @if(($searchText ?? '') !== '' || ($search ?? '') !== '')
                    <button type="button" class="btn btn-sm position-absolute"
                            style="right:6px; top:50%; transform:translateY(-50%); border:none; background:transparent"
                            title="Clear" wire:click="clearSearch">
                        <i class="ri-close-line"></i>
                    </button>
                @endif
            </div>
        </div>

        {{-- ====== DATA TABLE ====== --}}
        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; width:100%;">
                @includeIf('livewire.greensand._thead')
                <tbody>
                @forelse ($currentRows as $row)
                    <tr wire:key="row-{{ $row->id }}">
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-warning btn-sm mr-2" wire:click="edit({{ $row->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $row->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>

                        {{-- ====== KOLOM DATA ====== --}}
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
{{-- ==== Modal Konfirmasi Hapus (GLOBAL, cuma satu) ==== --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true" wire:ignore>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="confirmDeleteLabel">
          <i class="ri-error-warning-line mr-1"></i> Konfirmasi Hapus
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cancelDelete">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" wire:click="cancelDelete">
          Batal
        </button>
        <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" wire:loading.attr="disabled">
          <span wire:loading.remove>Ya, Hapus</span>
          <span wire:loading>Memproses...</span>
        </button>
      </div>
    </div>
  </div>
</div>

        {{-- ====== PAGINATION ====== --}}
        <div class="d-flex justify-content-between align-items-center">
            @if ($activeTab === 'mm1')
                <small class="text-muted">
                    Showing {{ $rowsMm1->firstItem() ?? 0 }} to {{ $rowsMm1->lastItem() ?? 0 }} of {{ $rowsMm1->total() }} entries
                </small>
                <div>{{ $rowsMm1->onEachSide(1)->links() }}</div>
            @elseif ($activeTab === 'mm2')
                <small class="text-muted">
                    Showing {{ $rowsMm2->firstItem() ?? 0 }} to {{ $rowsMm2->lastItem() ?? 0 }} of {{ $rowsMm2->total() }} entries
                </small>
                <div>{{ $rowsMm2->onEachSide(1)->links() }}</div>
            @else
                <small class="text-muted">
                    Showing {{ $rowsAll->firstItem() ?? 0 }} to {{ $rowsAll->lastItem() ?? 0 }} of {{ $rowsAll->total() }} entries
                </small>
                <div>{{ $rowsAll->onEachSide(1)->links() }}</div>
            @endif
        </div>

    </div>
</div>
