<div class="card">
    <div class="card-body">

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
        </ul>

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

            <div class="d-flex align-items-center">
                <span class="mr-2">Search</span>
                <input type="text" class="form-control form-control-sm" style="width:220px" placeholder="Mix / Type"
                    wire:model.debounce.400ms="search">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; width:100%;">
                @includeIf('livewire.greensand._thead')
                <tbody>
                    @forelse ($currentRows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-warning btn-sm mr-2" wire:click="edit({{ $row->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" wire:click="delete({{ $row->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>

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

        <div class="d-flex justify-content-between align-items-center">
            @if ($activeTab === 'mm1')
                <small class="text-muted">
                    Showing {{ $rowsMm1->firstItem() ?? 0 }} to {{ $rowsMm1->lastItem() ?? 0 }} of {{ $rowsMm1->total() }}
                    entries
                </small>
                <div>{{ $rowsMm1->onEachSide(1)->links() }}</div>
            @else
                <small class="text-muted">
                    Showing {{ $rowsMm2->firstItem() ?? 0 }} to {{ $rowsMm2->lastItem() ?? 0 }} of {{ $rowsMm2->total() }}
                    entries
                </small>
                <div>{{ $rowsMm2->onEachSide(1)->links() }}</div>
            @endif
        </div>

    </div>
</div>