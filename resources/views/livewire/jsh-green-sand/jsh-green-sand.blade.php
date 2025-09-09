<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- Title -->
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">JSH GFN GREEN SAND</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">SandLab</a></li>
                            <li class="breadcrumb-item active">JSH GFN GREEN SAND</li>
                        </ol>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" wire:click="create">
                                <i class="ri-add-line"></i> Add Data
                            </button>
                        </div>

                        {{-- ========== TABEL DETAIL (pakai datatable1 Nazox) ========== --}}
                        <table id="datatable1" class="table table-bordered table-striped nowrap w-100 mt-2">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Mesh</th>
                                    <th>Gram</th>
                                    <th>%</th>
                                    <th>Index</th>
                                    <th>%Index</th>
                                </tr>
                            </thead>

                            <tbody class="text-center">
                                @forelse($displayRows ?? collect() as $idx => $row)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $row->mesh }}</td>
                                    <td><b>{{ number_format($row->gram ?? 0, 2, ',', '.') }}</b></td>
                                    <td>{{ number_format($row->percentage ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ $row->index ?? 0 }}</td>
                                    <td>{{ number_format($row->percentage_index ?? 0, 1, ',', '.') }}</td>
                                </tr>
                                @empty
                                <!-- <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Belum ada data dalam 24 jam terakhir.
                                    </td>
                                </tr> -->
                                @endforelse

                                @if(!empty($displayRecap))
                                <tr>
                                    <th colspan="2" class="bg-dark text-white">TOTAL</th>
                                    <th class="bg-secondary text-white">
                                        <b>{{ number_format($displayRecap['total_gram'] ?? 0, 2, ',', '.') }}</b>
                                    </th>
                                    <th colspan="2">{{ $displayRecap['judge_overall'] ?? '' }}</th>
                                    <th class="bg-secondary text-white">
                                        {{ number_format($displayRecap['total_pi'] ?? 0, 1, ',', '.') }}
                                    </th>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        {{-- ========== TABEL REKAP ========== --}}
                        <table class="table table-bordered table-striped nowrap mt-4 w-auto">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <td>Nilai GFN (Σ %Index / 100)</td>
                                    <td>
                                        <b>{{ isset($displayRecap) ? number_format($displayRecap['nilai_gfn'], 2, ',', '.') : '-' }}</b>
                                    </td>
                                    <th>JUDGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>% MESH 140 (STD : 3.5 ~ 8.0 %)</td>
                                    <td>
                                        <b>{{ isset($displayRecap) ? number_format($displayRecap['mesh_total140'], 2, ',', '.') : '-' }}</b>
                                    </td>
                                    <td>{{ $displayRecap['judge_mesh_140'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Σ MESH 50, 70 & 100 (Min 64 %)</td>
                                    <td>
                                        <b>{{ isset($displayRecap) ? number_format($displayRecap['mesh_total70'], 2, ',', '.') : '-' }}</b>
                                    </td>
                                    <td>{{ $displayRecap['judge_mesh_70'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>% MESH 280 + PAN (STD : 0.00 ~ 1.40 %)</td>
                                    <td>
                                        <b>{{ isset($displayRecap) ? number_format($displayRecap['meshpan'], 2, ',', '.') : '-' }}</b>
                                    </td>
                                    <td>{{ $displayRecap['judge_meshpan'] ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Modal terpisah --}}
                        @include('livewire.jsh-green-sand.jsh-green-sand-form')


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('assets/js/greensand-jsh.js') }}"></script>  
@endpush