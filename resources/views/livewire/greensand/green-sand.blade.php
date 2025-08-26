<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Green Sand Check</h4>
                    <small class="text-muted">quality-control ></small>
                </div>

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
                <!-- end card -->
                <!-- start card modal -->
                <div class="card mb-4">
                    <div class="card-body p-3 p-md-4 bg-light rounded">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                data-target="#modal-greensand">
                                <i class="ri-add-line"></i> Add Data
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#mm1" role="tab">
                                            <i class="ri-layout-grid-line mr-1"></i> MM 1
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#mm2" role="tab">
                                            <i class="ri-layout-grid-fill mr-1"></i> MM 2
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    <!--  TAB MM 1   -->
                                    <div class="tab-pane fade show active" id="mm1" role="tabpanel">

                                        <!--  Header kontrol (dummy) -->
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">Show</span>
                                                <select class="custom-select custom-select-sm w-auto">
                                                    <option>5</option>
                                                    <option>10</option>
                                                    <option>25</option>
                                                </select>
                                                <span class="ml-2">entries</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">Search</span>
                                                <input type="text" class="form-control form-control-sm"
                                                    style="width: 220px">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="datatable1" class="table table-bordered dt-responsive nowrap"
                                                style="border-collapse: collapse; width: 100%;">

                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            Action</th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">MM
                                                        </th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            MIX KE</th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            MIX START</th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            MIX FINISH</th>
                                                        <th colspan="14" class="text-center">MM Sample</th>
                                                        <th colspan="3" class="text-center">Additive</th>
                                                        <th colspan="6" class="text-center">BC Sample</th>
                                                        <th colspan="8" class="text-center">Return Sand</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="min-width: 120px;">P</th>
                                                        <th class="text-center" style="min-width: 120px;">C</th>
                                                        <th class="text-center" style="min-width: 120px;">G.T</th>
                                                        <th class="text-center" style="min-width: 120px;">CB MM</th>
                                                        <th class="text-center" style="min-width: 120px;">CB Lab</th>
                                                        <th class="text-center" style="min-width: 120px;">M</th>
                                                        <th class="text-center" style="min-width: 120px;">Bakunetsu</th>
                                                        <th class="text-center" style="min-width: 120px;">AC</th>
                                                        <th class="text-center" style="min-width: 120px;">TC</th>
                                                        <th class="text-center" style="min-width: 120px;">Vsd</th>
                                                        <th class="text-center" style="min-width: 120px;">IG</th>
                                                        <th class="text-center" style="min-width: 120px;">CB weight</th>
                                                        <th class="text-center" style="min-width: 120px;">TP 50 weight
                                                        </th>
                                                        <th class="text-center" style="min-width: 120px;">SSI</th>
                                                        <th class="text-center" style="min-width: 120px;">M3</th>
                                                        <th class="text-center" style="min-width: 120px;">VSD</th>
                                                        <th class="text-center" style="min-width: 120px;">SC</th>
                                                        <th class="text-center" style="min-width: 120px;">BC12 CB</th>
                                                        <th class="text-center" style="min-width: 120px;">BC12 M</th>
                                                        <th class="text-center" style="min-width: 120px;">BC11 AC</th>
                                                        <th class="text-center" style="min-width: 120px;">BC11 VSD</th>
                                                        <th class="text-center" style="min-width: 120px;">BC16 CB</th>
                                                        <th class="text-center" style="min-width: 120px;">BC16 M</th>
                                                        <th class="text-center" style="min-width: 120px;">RS Time</th>
                                                        <th class="text-center" style="min-width: 120px;">Type</th>
                                                        <th class="text-center" style="min-width: 120px;">Moist BC9</th>
                                                        <th class="text-center" style="min-width: 120px;">Moist BC10
                                                        </th>
                                                        <th class="text-center" style="min-width: 120px;">Moist BC11
                                                        </th>
                                                        <th class="text-center" style="min-width: 120px;">Temp BC9</th>
                                                        <th class="text-center" style="min-width: 120px;">Temp BC10</th>
                                                        <th class="text-center" style="min-width: 120px;">Temp BC11</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @for ($i = 1; $i <= 3; $i++)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center jus">
                                                                    <button
                                                                        class="btn btn-outline-warning btn-sm btn-edit-row mr-2"><i
                                                                            class="fas fa-edit"></i></button>
                                                                    <button
                                                                        class="btn btn-outline-danger btn-sm btn-delete-row"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ $i }}</td>
                                                            <td class="text-center">{{ $i }}</td>
                                                            <td class="text-center">08:00</td>
                                                            <td class="text-center">09:00</td>
                                                            <td class="text-center">255</td>
                                                            <td class="text-center">255</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">41,02</td>
                                                            <td class="text-center">41,02</td>
                                                            <td class="text-center">38,80</td>
                                                            <td class="text-center">38,80</td>
                                                            <td class="text-center">39,90</td>
                                                            <td class="text-center">39,90</td>
                                                            <td class="text-center">2,64</td>
                                                            <td class="text-center">2,64</td>
                                                            <td class="text-center">1,2</td>
                                                            <td class="text-center">3,4</td>
                                                            <td class="text-center">5,6</td>
                                                            <td class="text-center">11,22</td>
                                                            <td class="text-center">33,44</td>
                                                            <td class="text-center">55,66</td>
                                                            <td class="text-center">77,88</td>
                                                            <td class="text-center">99,00</td>
                                                            <td class="text-center">11,22</td>
                                                            <td class="text-center">2025-08-26 08:00:00</td>
                                                            <td class="text-center">Type A</td>
                                                            <td class="text-center">1.1</td>
                                                            <td class="text-center">1.2</td>
                                                            <td class="text-center">1.3</td>
                                                            <td class="text-center">30</td>
                                                            <td class="text-center">31</td>
                                                            <td class="text-center">32</td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="pt-2">
                                            <small class="text-muted">Showing 1 to 10 of 57 entries</small>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="mm2" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">Show</span>
                                                <select class="custom-select custom-select-sm w-auto">
                                                    <option>5</option>
                                                    <option>10</option>
                                                    <option>25</option>
                                                </select>
                                                <span class="ml-2">entries</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">Search</span>
                                                <input type="text" class="form-control form-control-sm"
                                                    style="width: 220px">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="datatable1" class="table table-bordered dt-responsive nowrap"
                                                style="border-collapse: collapse; width: 100%;">

                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            Action</th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">MM
                                                        </th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            MIX KE</th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            MIX START</th>
                                                        <th class="text-center align-middle" rowspan="2"
                                                            style="min-width: 120px;">
                                                            MIX FINISH</th>
                                                        <th colspan="14" class="text-center">MM Sample</th>
                                                        <th colspan="3" class="text-center">Additive</th>
                                                        <th colspan="6" class="text-center">BC Sample</th>
                                                        <th colspan="8" class="text-center">Return Sand</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="min-width: 120px;">P</th>
                                                        <th class="text-center" style="min-width: 120px;">C</th>
                                                        <th class="text-center" style="min-width: 120px;">G.T</th>
                                                        <th class="text-center" style="min-width: 120px;">CB MM</th>
                                                        <th class="text-center" style="min-width: 120px;">CB Lab</th>
                                                        <th class="text-center" style="min-width: 120px;">M</th>
                                                        <th class="text-center" style="min-width: 120px;">Bakunetsu</th>
                                                        <th class="text-center" style="min-width: 120px;">AC</th>
                                                        <th class="text-center" style="min-width: 120px;">TC</th>
                                                        <th class="text-center" style="min-width: 120px;">Vsd</th>
                                                        <th class="text-center" style="min-width: 120px;">IG</th>
                                                        <th class="text-center" style="min-width: 120px;">CB weight</th>
                                                        <th class="text-center" style="min-width: 120px;">TP 50 weight
                                                        </th>
                                                        <th class="text-center" style="min-width: 120px;">SSI</th>
                                                        <th class="text-center" style="min-width: 120px;">M3</th>
                                                        <th class="text-center" style="min-width: 120px;">VSD</th>
                                                        <th class="text-center" style="min-width: 120px;">SC</th>
                                                        <th class="text-center" style="min-width: 120px;">BC12 CB</th>
                                                        <th class="text-center" style="min-width: 120px;">BC12 M</th>
                                                        <th class="text-center" style="min-width: 120px;">BC11 AC</th>
                                                        <th class="text-center" style="min-width: 120px;">BC11 VSD</th>
                                                        <th class="text-center" style="min-width: 120px;">BC16 CB</th>
                                                        <th class="text-center" style="min-width: 120px;">BC16 M</th>
                                                        <th class="text-center" style="min-width: 120px;">RS Time</th>
                                                        <th class="text-center" style="min-width: 120px;">Type</th>
                                                        <th class="text-center" style="min-width: 120px;">Moist BC9</th>
                                                        <th class="text-center" style="min-width: 120px;">Moist BC10
                                                        </th>
                                                        <th class="text-center" style="min-width: 120px;">Moist BC11
                                                        </th>
                                                        <th class="text-center" style="min-width: 120px;">Temp BC9</th>
                                                        <th class="text-center" style="min-width: 120px;">Temp BC10</th>
                                                        <th class="text-center" style="min-width: 120px;">Temp BC11</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @for ($i = 1; $i <= 3; $i++)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center jus">
                                                                    <button
                                                                        class="btn btn-outline-warning btn-sm btn-edit-row mr-2"><i
                                                                            class="fas fa-edit"></i></button>
                                                                    <button
                                                                        class="btn btn-outline-danger btn-sm btn-delete-row"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ $i }}</td>
                                                            <td class="text-center">{{ $i }}</td>
                                                            <td class="text-center">08:00</td>
                                                            <td class="text-center">09:00</td>
                                                            <td class="text-center">255</td>
                                                            <td class="text-center">255</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">14,90</td>
                                                            <td class="text-center">41,02</td>
                                                            <td class="text-center">41,02</td>
                                                            <td class="text-center">38,80</td>
                                                            <td class="text-center">38,80</td>
                                                            <td class="text-center">39,90</td>
                                                            <td class="text-center">39,90</td>
                                                            <td class="text-center">2,64</td>
                                                            <td class="text-center">2,64</td>
                                                            <td class="text-center">1,2</td>
                                                            <td class="text-center">3,4</td>
                                                            <td class="text-center">5,6</td>
                                                            <td class="text-center">11,22</td>
                                                            <td class="text-center">33,44</td>
                                                            <td class="text-center">55,66</td>
                                                            <td class="text-center">77,88</td>
                                                            <td class="text-center">99,00</td>
                                                            <td class="text-center">11,22</td>
                                                            <td class="text-center">2025-08-26 08:00:00</td>
                                                            <td class="text-center">Type A</td>
                                                            <td class="text-center">1.1</td>
                                                            <td class="text-center">1.2</td>
                                                            <td class="text-center">1.3</td>
                                                            <td class="text-center">30</td>
                                                            <td class="text-center">31</td>
                                                            <td class="text-center">32</td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="pt-2">
                                            <small class="text-muted">Showing 1 to 10 of 57 entries</small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal fade" id="modal-greensand" tabindex="-1" role="dialog" aria-labelledby="gsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="gsModalLabel">Add Green Sand</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">M</label>
                                                <div class="btn-group btn-group-sm d-flex">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary active">1</button>
                                                    <button type="button" class="btn btn-outline-secondary">2</button>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Mix Ke</label>
                                                <input type="number" min="1" class="form-control"
                                                    placeholder="1 / 2 / 3">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Mix Start</label>
                                                <input type="time" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label mb-1">Mix Finish</label>
                                                <input type="time" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-mm"
                                            role="tab"><i class="ri-flask-line mr-1"></i> MM Sample</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-additive"
                                            role="tab"><i class="ri-pie-chart-2-line mr-1"></i> Additive</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-bc"
                                            role="tab"><i class="ri-alert-line mr-1"></i> BC Sample</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-return"
                                            role="tab"><i class="ri-recycle-line mr-1"></i> Return Sand</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-mm" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 mb-3"><label>P</label><input
                                                            class="form-control" placeholder="Sample P"></div>
                                                    <div class="col-md-3 mb-3"><label>C</label><input
                                                            class="form-control" placeholder="Sample C"></div>
                                                    <div class="col-md-3 mb-3"><label>G.T</label><input
                                                            class="form-control" placeholder="Sample G.T"></div>
                                                    <div class="col-md-3 mb-3"><label>CB MM</label><input
                                                            class="form-control" placeholder="Sample CB MM"></div>
                                                    <div class="col-md-3 mb-3"><label>CB Lab</label><input
                                                            class="form-control" placeholder="Sample CB Lab"></div>
                                                    <div class="col-md-3 mb-3"><label>Moisture</label><input
                                                            class="form-control" placeholder="Sample Moisture"></div>
                                                    <div class="col-md-3 mb-3"><label>Bakunetsu</label><input
                                                            class="form-control" placeholder="Sample Bakunetsu"></div>
                                                    <div class="col-md-3 mb-3"><label>AC</label><input
                                                            class="form-control" placeholder="Sample AC"></div>
                                                    <div class="col-md-3 mb-3"><label>TC</label><input
                                                            class="form-control" placeholder="Sample TC"></div>
                                                    <div class="col-md-3 mb-3"><label>Vsd</label><input
                                                            class="form-control" placeholder="Sample Vsd"></div>
                                                    <div class="col-md-3 mb-3"><label>IG</label><input
                                                            class="form-control" placeholder="Sample IG"></div>
                                                    <div class="col-md-3 mb-3"><label>CB Weight</label><input
                                                            class="form-control" placeholder="Sample CB Weight"></div>
                                                    <div class="col-md-3 mb-3"><label>TP 50 Weight</label><input
                                                            class="form-control" placeholder="Sample TP 50 Weight">
                                                    </div>
                                                    <div class="col-md-3 mb-3"><label>SSI</label><input
                                                            class="form-control" placeholder="Sample SSI"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-additive" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3"><label>M3</label><input
                                                            class="form-control" placeholder="M3"></div>
                                                    <div class="col-md-4 mb-3"><label>VSD</label><input
                                                            class="form-control" placeholder="VSD"></div>
                                                    <div class="col-md-4 mb-3"><label>SC</label><input
                                                            class="form-control" placeholder="SC"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-bc" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3"><label>BC 12 Sample</label><input
                                                                class="form-control" placeholder="CB Left"></div>
                                                        <div class="mb-3"><label>BC 11 Sample</label><input
                                                                class="form-control" placeholder="AC Left"></div>
                                                        <div class="mb-3"><label>BC 16 Sample</label><input
                                                                class="form-control" placeholder="CB Left"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3"><label>BC 12 Sample</label><input
                                                                class="form-control" placeholder="CB Right"></div>
                                                        <div class="mb-3"><label>BC 11 Sample</label><input
                                                                class="form-control" placeholder="VSD Right"></div>
                                                        <div class="mb-3"><label>BC 16 Sample</label><input
                                                                class="form-control" placeholder="CB Right"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-return" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><label>Time</label><input class="form-control"
                                                            placeholder="Time"></div>
                                                    <div class="col-md-6">
                                                        <label>Type</label>
                                                        <select class="form-control">
                                                            <option value="">-- Select Type --</option>
                                                            <option>BC 9</option>
                                                            <option>BC 10</option>
                                                            <option>BC 11</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Moisture</label>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2"><input class="form-control"
                                                                placeholder="BC 9"></div>
                                                        <div class="col-md-4 mb-2"><input class="form-control"
                                                                placeholder="BC 10"></div>
                                                        <div class="col-md-4 mb-2"><input class="form-control"
                                                                placeholder="BC 11"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label>Temperature</label>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2"><input class="form-control"
                                                                placeholder="BC 9"></div>
                                                        <div class="col-md-4 mb-2"><input class="form-control"
                                                                placeholder="BC 10"></div>
                                                        <div class="col-md-4 mb-2"><input class="form-control"
                                                                placeholder="BC 11"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary mr-2" data-dismiss="modal">
                                    <i class="ri-close-line"></i> Cancel
                                </button>
                                <button type="button" class="btn btn-success">
                                    <i class="ri-checkbox-circle-line"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>