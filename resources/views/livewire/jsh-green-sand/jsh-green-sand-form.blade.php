<div class="modal fade" id="modal-greensand" tabindex="-1" role="dialog" aria-labelledby="modalGreensandLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="modalGreensandLabel">Add Data GFN Green Sand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-xl-6 col-lg-6 mb-2">
                        <label class="form-label mb-1">Tanggal</label>
                        <input id="startDate" type="date" class="form-control" wire:model.defer="gfn_date" placeholder="YYYY-MM-DD" autocomplete="off">
                        @error('gfn_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-xl-6 col-lg-6 mb-2">
                        <label class="form-label mb-1">Shift</label>
                        <select class="form-control" wire:model.defer="shift">
                            <option value="" hidden>Pilih Shift</option>
                            <option value="D">D (Day)</option>
                            <option value="S">S (Swing)</option>
                            <option value="N">N (Night)</option>
                        </select>
                        @error('shift') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Tabel input gram --}}
                <div class="table-responsive">
                    <table class="table table-sm table-bordered text-center align-middle mb-2">
                        <thead class="thead-light">
                            <tr>
                                <th width="60">NO</th>
                                <th width="100">MESH</th>
                                <th width="120">GRAM</th>
                                <th width="100">%</th>
                                <th width="100">INDEX</th>
                                <th width="120">% INDEX</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meshes as $i => $mesh)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mesh }}</td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm text-right" wire:model.lazy="grams.{{ $i }}">
                                </td>
                                <td>{{ number_format($percentages[$i] ?? 0, 2, ',', '.') }}</td>
                                <td>{{ $indices[$i] }}</td>
                                <td>{{ number_format($percentageIndices[$i] ?? 0, 1, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('grams') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" wire:click="save">
                    <i class="fas fa-plus"></i> Add Data
                </button>
            </div>
        </div>
    </div>
</div>