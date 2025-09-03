<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Title --}}
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Green Sand Check</h4>
                    <div class="page-title-right">
                        {{-- Breadcrumb --}}
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">SandLab</a></li>
                            <li class="breadcrumb-item active">Green Sand Check</li>
                        </ol>
                    </div>
                </div>
                @php $isOpen = $filterOpen ?? true; @endphp
                {{-- Filter --}}
                <div class="card mb-3">
                    <div id="filterHeader"
                        class="card-header bg-light d-flex justify-content-between align-items-center cursor-pointer"
                        data-toggle="collapse" data-target="#filterCollapse"
                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="filterCollapse">
                        <h5 class="font-size-14 mb-0">
                            <i class="ri-filter-2-line align-middle mr-1"></i> Filter Data
                        </h5>
                        <i id="filterIcon" class="{{ $isOpen ? 'ri-subtract-line' : 'ri-add-line' }}"></i>
                    </div>
                    <div id="filterCollapse" class="collapse {{ $isOpen ? 'show' : '' }}"
                        data-open="{{ $isOpen ? 1 : 0 }}" data-gs-state="{{ $isOpen ? 1 : 0 }}" data-lock="0">
                        <div class="card-body">
                            <div class="row align-items-end">
                                {{-- Start --}}
                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">Start Date</label>
                                    <input id="startDate" type="date" class="form-control gs-input"
                                        wire:model.defer="start_date" placeholder="Enter Charging Date"
                                        autocomplete="off">
                                </div>

                                {{-- End --}}
                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">End Date</label>
                                    <input id="endDate" type="date" class="form-control gs-input"
                                        wire:model.defer="end_date" placeholder="Enter Charging Date" autocomplete="off"
                                        min="{{ $start_date ?? '' }}">
                                </div>
                                {{-- Shift --}}
                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">Shift</label>
                                    <select class="form-control" wire:model.defer="filter_shift" autocomplete="off">
                                        <option value="" {{ empty($filter_shift) ? 'selected' : '' }}>-- Select
                                            Shift --</option>
                                        <option value="D">Day</option>
                                        <option value="S">Subuh</option>
                                        <option value="N">Night</option>
                                    </select>
                                </div>
                                {{-- Search --}}
                                <div class="col-xl-3 col-lg-3 mb-2">
                                    <label class="form-label mb-1">Search (mix/model)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="keyword..."
                                            wire:model.defer="searchText" autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm" wire:click="applySearch">
                                                <i class="ri-search-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Actions --}}
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
                {{-- Table --}}
                <div class="card mb-4">
                    <div class="card-body shadow-lg">
                        {{-- Add --}}
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" wire:click="create"
                                data-toggle="modal" data-target="#modal-greensand">
                                <i class="ri-add-line"></i> Add Data
                            </button>
                        </div>
                        {{-- Partial --}}
                        @include('livewire.greensand.green-sand-table')
                    </div>
                </div>
                {{-- Modal --}}
                @include('livewire.greensand.green-sand-modal')
            </div>
        </div>
    </div>
</div>
