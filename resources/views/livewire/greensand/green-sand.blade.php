{{-- resources/views/livewire/greensand/green-sand.blade.php --}}
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Page Title --}}
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Green Sand Check</h4>
                    <small class="text-muted">quality-control ></small>
                </div>

                {{-- =================== FILTER =================== --}}
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center cursor-pointer"
                        data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false"
                        aria-controls="filterCollapse" id="filterHeader">
                        <h5 class="font-size-14 mb-0">
                            <i class="ri-filter-2-line align-middle mr-1"></i> Filter Data
                        </h5>
                        <i class="ri-add-line" id="filterIcon"></i>
                    </div>

                    <div id="filterCollapse" class="collapse" aria-labelledby="filterHeader">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-xl-3 col-lg-3">
                                    <label class="form-label mb-1">Start Date</label>
                                    <input type="date" class="form-control">
                                </div>

                                <div class="col-xl-3 col-lg-3">
                                    <label class="form-label mb-1">End Date</label>
                                    <input type="date" class="form-control">
                                </div>

                                <div class="col-xl-3 col-lg-3">
                                    <label class="form-label mb-1">Model</label>
                                    <select class="form-control">
                                        <option value="">-- Select Model --</option>
                                    </select>
                                </div>

                                <div class="col-xl-3 col-lg-3">
                                    <label class="form-label mb-1">Shift</label>
                                    <select class="form-control">
                                        <option value="">-- Select Shift --</option>
                                        <option value="1">Shift 1</option>
                                        <option value="2">Shift 2</option>
                                        <option value="3">Shift 3</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-12 mt-3">
                                    <div class="d-flex flex-wrap">
                                        <button type="button" class="btn btn-primary btn-sm mr-2 mb-2">
                                            <i class="ri-search-line mr-1"></i> Search
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2">
                                            <i class="ri-refresh-line mr-1"></i> Refresh
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm mb-2">
                                            <i class="ri-file-excel-2-line mr-1"></i> Export Excel
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- ================ /FILTER ================== --}}

                {{-- =================== ACTIONS + TABLE =================== --}}
                <div class="card mb-4">
                    <div class="card-body p-3 p-md-4 bg-light rounded">

                        {{-- Add Data --}}
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" wire:click="create" data-toggle="modal"
                                data-target="#modal-greensand">
                                <i class="ri-add-line"></i> Add Data
                            </button>
                        </div>
                        {{-- TABLE (partial terpisah) --}}
                        @include('livewire.greensand.green-sand-table')

                    </div>
                </div>
                {{-- ================ /ACTIONS + TABLE ================== --}}

                {{-- =================== MODAL (partial terpisah) =================== --}}
                @include('livewire.greensand.green-sand-modal')
                {{-- ================ /MODAL ================== --}}
            </div>
        </div>
    </div>
</div>