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
            <li class="nav-item">
                <a href="#" class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}"
                   wire:click.prevent="setActiveTab('all')">
                    <i class="ri-stack-line mr-1"></i> All
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table id="datatable1" class="table table-bordered" data-tab="{{ $activeTab }}"
                   wire:key="gs-table-{{ $activeTab }}">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">Action</th>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">Date</th>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">Shift</th>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">MM</th>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">MIX KE</th>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">MIX START</th>
                        <th class="text-center align-middle" rowspan="2" style="min-width:120px;">MIX FINISH</th>
                        <th colspan="14" class="text-center">MM Sample</th>
                        <th colspan="3" class="text-center">Additive</th>
                        <th colspan="6" class="text-center">BC Sample</th>
                        <th colspan="8" class="text-center">Return Sand</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="min-width:120px;">P</th>
                        <th class="text-center" style="min-width:120px;">C</th>
                        <th class="text-center" style="min-width:120px;">G.T</th>
                        <th class="text-center" style="min-width:120px;">CB MM</th>
                        <th class="text-center" style="min-width:120px;">CB Lab</th>
                        <th class="text-center" style="min-width:120px;">M</th>
                        <th class="text-center" style="min-width:120px;">Bakunetsu</th>
                        <th class="text-center" style="min-width:120px;">AC</th>
                        <th class="text-center" style="min-width:120px;">TC</th>
                        <th class="text-center" style="min-width:120px;">Vsd</th>
                        <th class="text-center" style="min-width:120px;">IG</th>
                        <th class="text-center" style="min-width:120px;">CB weight</th>
                        <th class="text-center" style="min-width:120px;">TP 50 weight</th>
                        <th class="text-center" style="min-width:120px;">SSI</th>
                        <th class="text-center" style="min-width:120px;">M3</th>
                        <th class="text-center" style="min-width:120px;">VSD</th>
                        <th class="text-center" style="min-width:120px;">SC</th>
                        <th class="text-center" style="min-width:120px;">BC12 CB</th>
                        <th class="text-center" style="min-width:120px;">BC12 M</th>
                        <th class="text-center" style="min-width:120px;">BC11 AC</th>
                        <th class="text-center" style="min-width:120px;">BC11 VSD</th>
                        <th class="text-center" style="min-width:120px;">BC16 CB</th>
                        <th class="text-center" style="min-width:120px;">BC16 M</th>
                        <th class="text-center" style="min-width:120px;">RS Time</th>
                        <th class="text-center" style="min-width:120px;">Type</th>
                        <th class="text-center" style="min-width:120px;">Moist BC9</th>
                        <th class="text-center" style="min-width:120px;">Moist BC10</th>
                        <th class="text-center" style="min-width:120px;">Moist BC11</th>
                        <th class="text-center" style="min-width:120px;">Temp BC9</th>
                        <th class="text-center" style="min-width:120px;">Temp BC10</th>
                        <th class="text-center" style="min-width:120px;">Temp BC11</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($currentRows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-warning btn-sm mr-2"
                                            wire:click="edit({{ $row->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm js-delete"
                                            data-id="{{ $row->id }}"
                                            data-label="(ID: {{ $row->id }}, MM: {{ $row->mm_no }})"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">{{ $row->process_date?->format('d-m-Y H:i:s') }}</td>
                            <td class="text-center">{{ $row->shift }}</td>
                            <td class="text-center">{{ $row->mm_no }}</td>
                            <td class="text-center">{{ $row->mix_no }}</td>
                            <td class="text-center">{{ $row->mix_start?->format('H:i:s') ?? '-' }}</td>
                            <td class="text-center">{{ $row->mix_finish?->format('H:i:s') ?? '-' }}</td>
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
                            <td class="text-center">{{ $row->return_time?->format('H:i:s') ?? '-' }}</td>
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
                            <td class="text-center" colspan="38">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
         aria-labelledby="confirmDeleteTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteTitle">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="confirmDeleteText" class="mb-0">Yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteYes">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

</div>
