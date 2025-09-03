<div class="modal fade" id="modal-greensand" tabindex="-1" role="dialog" aria-labelledby="gsModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="gsModalLabel">{{ $formMode === 'edit' ? 'Edit' : 'Add' }} Green Sand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    @if (isset($forceBs5) && $forceBs5) data-bs-dismiss="modal" @endif>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">

                {{-- Header --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">

                            {{-- MM --}}
                            <div class="col-md-2 mb-2">
                                <label class="form-label mb-1">M</label>
                                <div class="btn-group btn-group-sm d-flex">
                                    <button type="button"
                                        class="btn btn-outline-secondary {{ $form['mm_no'] == 1 ? 'active' : '' }}"
                                        wire:click="setMm(1)">1</button>
                                    <button type="button"
                                        class="btn btn-outline-secondary {{ $form['mm_no'] == 2 ? 'active' : '' }}"
                                        wire:click="setMm(2)">2</button>
                                </div>
                            </div>

                            {{-- Shift --}}
                            <div class="col-md-2 mb-2">
                                <label class="form-label mb-1">Shift</label>
                                <select class="form-control @error('form.shift') is-invalid @enderror"
                                    wire:model.defer="form.shift" autocomplete="off">
                                    <option value="" disabled {{ $form['shift'] === '' ? 'selected' : '' }}>--
                                        Select Shift --</option>
                                    <option value="D">Day</option>
                                    <option value="N">Night</option>
                                </select>
                                @error('form.shift')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Process Date (auto today, hidden) --}}
                            <input type="hidden" wire:model.defer="form.process_date">


                            {{-- Mix --}}
                            <div class="col-md-2 mb-2">
                                <label class="form-label mb-1">Mix Ke</label>
                                <input type="number" min="1"
                                    class="form-control @error('form.mix_no') is-invalid @enderror"
                                    wire:model.defer="form.mix_no" placeholder="Enter Sample Mix Ke" inputmode="numeric"
                                    autocomplete="off">
                                @error('form.mix_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Start --}}
                            <div class="col-md-3 mb-2">
                                <label class="form-label mb-1">Mix Start</label>
                                <input type="time"
                                    class="form-control @error('form.mix_start_t') is-invalid @enderror"
                                    wire:model.defer="form.mix_start_t" autocomplete="off">
                                @error('form.mix_start_t')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Finish --}}
                            <div class="col-md-3 mb-2">
                                <label class="form-label mb-1">Mix Finish</label>
                                <input type="time"
                                    class="form-control @error('form.mix_finish_t') is-invalid @enderror"
                                    wire:model.defer="form.mix_finish_t" autocomplete="off">
                                @error('form.mix_finish_t')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $modalTab === 'mm' ? 'active' : '' }}" data-toggle="tab" href="#tab-mm"
                            role="tab" wire:click.prevent="setModalTab('mm')">
                            <i class="ri-flask-line mr-1"></i> MM Sample
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $modalTab === 'additive' ? 'active' : '' }}" data-toggle="tab"
                            href="#tab-additive" role="tab" wire:click.prevent="setModalTab('additive')">
                            <i class="ri-pie-chart-2-line mr-1"></i> Additive
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $modalTab === 'bc' ? 'active' : '' }}" data-toggle="tab" href="#tab-bc"
                            role="tab" wire:click.prevent="setModalTab('bc')">
                            <i class="ri-alert-line mr-1"></i> BC Sample
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $modalTab === 'return' ? 'active' : '' }}" data-toggle="tab" href="#tab-return"
                            role="tab" wire:click.prevent="setModalTab('return')">
                            <i class="ri-recycle-line mr-1"></i> Return Sand
                        </a>
                    </li>
                </ul>

                {{-- Konten --}}
                <div class="tab-content">

                    {{-- MM --}}
                    <div class="tab-pane fade {{ $modalTab === 'mm' ? 'show active' : '' }}" id="tab-mm" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $mmFields = [
                                            ['sample_p', 'P'],
                                            ['sample_c', 'C'],
                                            ['sample_gt', 'G.T'],
                                            ['cb_mm', 'CB MM'],
                                            ['cb_lab', 'CB Lab'],
                                            ['sample_m', 'Moisture'],
                                            ['bakunetsu', 'Bakunetsu'],
                                            ['sample_ac', 'AC'],
                                            ['sample_tc', 'TC'],
                                            ['vsd_mm', 'Vsd'],
                                            ['sample_ig', 'IG'],
                                            ['cb_weight', 'CB Weight'],
                                            ['tp50_weight', 'TP 50 Weight'],
                                            ['ssi', 'SSI'],
                                        ];
                                    @endphp

                                    @foreach ($mmFields as [$name, $label])
                                        <div class="col-md-3 mb-3">
                                            <label>{{ $label }}</label>
                                            <input type="number"
                                                class="form-control @error('form.' . $name) is-invalid @enderror"
                                                wire:model.defer="form.{{ $name }}"
                                                placeholder="Enter Sample {{ $label }}" inputmode="decimal"
                                                autocomplete="off">
                                            @error('form.' . $name)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additive --}}
                    <div class="tab-pane fade {{ $modalTab === 'additive' ? 'show active' : '' }}" id="tab-additive"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>M3</label>
                                        <input type="number"
                                            class="form-control @error('form.additive_m3') is-invalid @enderror"
                                            wire:model.defer="form.additive_m3" placeholder="Enter Sample M3"
                                            inputmode="decimal" autocomplete="off">
                                        @error('form.additive_m3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>VSD</label>
                                        <input type="number"
                                            class="form-control @error('form.additive_vsd') is-invalid @enderror"
                                            wire:model.defer="form.additive_vsd" placeholder="Enter Sample VSD"
                                            inputmode="decimal" autocomplete="off">
                                        @error('form.additive_vsd')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>SC</label>
                                        <input type="number"
                                            class="form-control @error('form.additive_sc') is-invalid @enderror"
                                            wire:model.defer="form.additive_sc" placeholder="Enter Sample SC"
                                            inputmode="decimal" autocomplete="off">
                                        @error('form.additive_sc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BC --}}
                    <div class="tab-pane fade {{ $modalTab === 'bc' ? 'show active' : '' }}" id="tab-bc"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>BC 12 Sample (CB)</label>
                                            <input type="number"
                                                class="form-control @error('form.bc12_cb') is-invalid @enderror"
                                                wire:model.defer="form.bc12_cb" placeholder="Enter Sample BC 12 (CB)"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.bc12_cb')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label>BC 11 Sample (AC)</label>
                                            <input type="number"
                                                class="form-control @error('form.bc11_ac') is-invalid @enderror"
                                                wire:model.defer="form.bc11_ac" placeholder="Enter Sample BC 11 (AC)"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.bc11_ac')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label>BC 16 Sample (CB)</label>
                                            <input type="number"
                                                class="form-control @error('form.bc16_cb') is-invalid @enderror"
                                                wire:model.defer="form.bc16_cb" placeholder="Enter Sample BC 16 (CB)"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.bc16_cb')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>BC 12 Sample (M)</label>
                                            <input type="number"
                                                class="form-control @error('form.bc12_m') is-invalid @enderror"
                                                wire:model.defer="form.bc12_m" placeholder="Enter Sample BC 12 (M)"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.bc12_m')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label>BC 11 Sample (VSD)</label>
                                            <input type="number"
                                                class="form-control @error('form.bc11_vsd') is-invalid @enderror"
                                                wire:model.defer="form.bc11_vsd"
                                                placeholder="Enter Sample BC 11 (VSD)" inputmode="decimal"
                                                autocomplete="off">
                                            @error('form.bc11_vsd')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label>BC 16 Sample (M)</label>
                                            <input type="number"
                                                class="form-control @error('form.bc16_m') is-invalid @enderror"
                                                wire:model.defer="form.bc16_m" placeholder="Enter Sample BC 16 (M)"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.bc16_m')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Return --}}
                    <div class="tab-pane fade {{ $modalTab === 'return' ? 'show active' : '' }}" id="tab-return"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Time</label>
                                        <input class="form-control @error('form.return_time_t') is-invalid @enderror"
                                            type="time" wire:model.defer="form.return_time_t" autocomplete="off">
                                        @error('form.return_time_t')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>Type</label>
                                        <input class="form-control @error('form.model_type') is-invalid @enderror"
                                            wire:model.defer="form.model_type"
                                            placeholder="Enter Type (WIP / ES01 / ...)" autocomplete="off">
                                        @error('form.model_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Moisture</label>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <input type="number"
                                                class="form-control @error('form.moisture_bc9') is-invalid @enderror"
                                                wire:model.defer="form.moisture_bc9" placeholder="Enter Moisture BC 9"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.moisture_bc9')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="number"
                                                class="form-control @error('form.moisture_bc10') is-invalid @enderror"
                                                wire:model.defer="form.moisture_bc10"
                                                placeholder="Enter Moisture BC 10" inputmode="decimal"
                                                autocomplete="off">
                                            @error('form.moisture_bc10')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="number"
                                                class="form-control @error('form.moisture_bc11') is-invalid @enderror"
                                                wire:model.defer="form.moisture_bc11"
                                                placeholder="Enter Moisture BC 11" inputmode="decimal"
                                                autocomplete="off">
                                            @error('form.moisture_bc11')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label>Temperature</label>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <input type="number"
                                                class="form-control @error('form.temp_bc9') is-invalid @enderror"
                                                wire:model.defer="form.temp_bc9" placeholder="Enter Temp BC 9"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.temp_bc9')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="number"
                                                class="form-control @error('form.temp_bc10') is-invalid @enderror"
                                                wire:model.defer="form.temp_bc10" placeholder="Enter Temp BC 10"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.temp_bc10')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="number"
                                                class="form-control @error('form.temp_bc11') is-invalid @enderror"
                                                wire:model.defer="form.temp_bc11" placeholder="Enter Temp BC 11"
                                                inputmode="decimal" autocomplete="off">
                                            @error('form.temp_bc11')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-outline-secondary btn-cancel-row mr-2" data-dismiss="modal"
                    @if (isset($forceBs5) && $forceBs5) data-bs-dismiss="modal" @endif>
                    <i class="ri-close-line"></i> Cancel
                </button>

                <button type="button" class="btn btn-success" wire:click="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove><i class="ri-checkbox-circle-line"></i> Submit</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>

        </div>
    </div>
</div>
