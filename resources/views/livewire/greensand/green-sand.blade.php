<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Green Sand Check</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SandLab</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                
                {{-- FILTER --}}
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center cursor-pointer"
                        data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false"
                        aria-controls="filterCollapse" id="filterHeader">
                        <h5 class="font-size-14 mb-0">
                            <i class="ri-filter-2-line align-middle mr-1"></i> Filter Data
                        </h5>
                        <i class="ri-add-line" id="filterIcon"></i>
                    </div>

                    {{-- wire:ignore supaya Livewire tidak ganggu collapse --}}
                    <div id="filterCollapse" class="collapse" aria-labelledby="filterHeader" wire:ignore>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">Start Date</label>
                                    <input id="startDate" type="date" class="form-control" wire:model.live="start_date">
                                </div>

                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">End Date</label>
                                    <input id="endDate" type="date" class="form-control" wire:model.live="end_date">
                                </div>

                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">Shift</label>
                                    <select class="form-control" wire:model.defer="filter_shift">
                                        <option value="" {{ empty($filter_shift) ? 'selected' : '' }}>-- Select Shift --
                                        </option>
                                        <option value="Day">Day</option>
                                        <option value="Night">Night</option>
                                    </select>
                                </div>

                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">Search (mix/model)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" wire:model.defer="searchText"
                                            placeholder="keyword...">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" wire:click="applySearch">
                                                <i class="ri-search-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-12 mt-2">
                                    <div class="d-flex flex-wrap">
                                        <button type="button" class="btn btn-primary btn-sm mr-2 mb-2"
                                            wire:click="applySearch">
                                            <i class="ri-search-line mr-1"></i> Search
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2"
                                            wire:click="clearFilters">
                                            <i class="ri-refresh-line mr-1"></i> Refresh Filter
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm mb-2"
                                            wire:click="export">
                                            <i class="ri-file-excel-2-line mr-1"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- /FILTER --}}



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