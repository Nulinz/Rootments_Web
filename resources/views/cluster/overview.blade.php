@extends ('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Dashboard - Cluster Manager</h4>
        </div>

        <!-- Cluster Tabs -->
        @include('cluster.tabs')

        <div class="container px-0 mt-3">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Stores</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Store Code</th>
                                        <th>Store Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cl_data as $cl)
                                    <tr>
                                        <td>{{$cl->store_code}}</td>
                                        <td>{{$cl->store_name}}</td>
                                        <td>{{$cl->store_geo}}</td>
                                        <td>{{$cl->st_name ?? 'no name'}}</td>
                                    </tr>
                                   @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Base Branch Target Achievement</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 cards">
                    <div class="cardsdiv">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-xl-8 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Sales Growth Rate (Previous Month Difference)</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Request</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sheik</h5>
                                                    <h6 class="mb-0">Employee</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Sick Leave</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                    data-bs-title="Approve"></i>
                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                    data-bs-title="Reject"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sabari</h5>
                                                    <h6 class="mb-0">Admin</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Transfer</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                    data-bs-title="Approve"></i>
                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                    data-bs-title="Reject"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Naveen</h5>
                                                    <h6 class="mb-0">Store Maintanence</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Work From Home</td>
                                        <td class="text-danger">Rejected</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sugan</h5>
                                                    <h6 class="mb-0">Employee</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Transfer</td>
                                        <td class="text-success">Approved</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Venkat</h5>
                                                    <h6 class="mb-0">Employee</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Transfer</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                    data-bs-title="Approve"></i>
                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                    data-bs-title="Reject"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Hari</h5>
                                                    <h6 class="mb-0">Admin</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Casual Leave</td>
                                        <td class="text-success">Approved</t>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Saravanan</h5>
                                                    <h6 class="mb-0">Admin</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Week Off</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                    data-bs-title="Approve"></i>
                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                    data-bs-title="Reject"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Bala Krishnan</h5>
                                                    <h6 class="mb-0">Store Maintanence</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Sick Leave</td>
                                        <td class="text-danger">Rejected</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
