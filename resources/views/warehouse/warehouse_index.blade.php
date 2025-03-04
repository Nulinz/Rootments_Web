@extends ('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overview</h4>
        </div>

        <!-- Tabs -->
        @include('generaldashboard.tabs')

        <div class="container px-0 mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Today Login</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Login</th>
                                        <th>Logout</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ware_emp as $ware)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">

                                                        <img src="{{ asset($ware->profile_image ?? 'assets/images/avatar.png') }}" alt="">

                                                    <div>
                                                        <h5 class="mb-0">{{ $ware->name }}</h5>
                                                        <h6 class="mb-0">{{ $ware->in_location ?? 'No location' }}</h6>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>@if(!is_null($ware->in_time))
                                                    {{ date("h:i", strtotime($ware->in_time)) }}
                                                @endif
                                            </td>
                                            <td>@if(!is_null($ware->out_time))
                                                    {{ date("h:i", strtotime($ware->out_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ware->status == 'approved')
                                                    <button class="" data-bs-toggle="tooltip"
                                                        data-id="{{ $ware->user_id }}" data-bs-title="Approved"><i
                                                            class="fas fa-circle-check text-success"></i></button>
                                                @else
                                                     @if(!empty($ware->in_time))

                                                    <button class="approve-attendance" data-bs-toggle="tooltip"
                                                        data-id="{{ $ware->user_id }}" data-bs-title="Not Approved"><i
                                                            class="fas fa-circle-check text-warning"></i></button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Task Status</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Team Performance</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Category Task</h6>
                        </div>
                        <div id="chart3"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Sub Category Task</h6>
                        </div>
                        <div id="chart4"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Leave Request</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sheik</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sabari</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Naveen</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sugan</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Venkat</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Hari</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Saravanan</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="./images/avatar.png" alt="">
                                                <div>
                                                    <h5 class="mb-0">Bala Krishnan</h5>
                                                    <h6 class="mb-0">Requesting for leave 12/12/2024 to
                                                        13/12/2024 for one day</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="text-decoration-underline">View</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>

    <!-- Chart 1 -->
    <script>
        var options = {
            series: [110, 100, 220],
            labels: ['To Do', 'In Progress', 'Completed'],
            colors: ['#003f5c', '#58508d', '#bc5090', '#0427B9'],
            chart: {
                type: 'donut',
                height: 315,
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 300,
                options: {
                    chart: {
                        height: 320,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

    <!-- Chart 2 -->
    <script>
        var options = {
            series: [{
                data: [20, 50, 80, 10, 100, 30, 90, 60, 100, 75, 85]
            }],
            chart: {
                height: 300,
                type: 'bar',
                events: {
                    click: function (chart, w, e) {
                    },
                },
            },
            colors: ['#0427B9'],
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                    borderRadius: 5,
                },
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: ['Staff 1', 'Staff 2', 'Staff 3', 'Staff 4', 'Staff 5', 'Staff 6', 'Staff 7', 'Staff 8', 'Staff 9', 'Staff 10', 'Staff 11'],
                labels: {
                    style: {
                        fontSize: '6px',
                        fontWeight: 500,
                    },
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>

    <!-- Chart 3 -->
    <script>
        var options = {
            series: [110, 100, 220, 350, 190],
            labels: ['Tailoring', 'Inventory Managemnet', 'Customer Assistant', 'Store Maintanace', 'Sales Management'],
            colors: ['#991f17', '#b04238', '#c86558', '#b3bfd1', '#d7e1ee'],
            chart: {
                type: 'donut',
                height: 315,
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 320,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();
    </script>

    <!-- Chart 4 -->
    <script>
        var options = {
            series: [110, 100, 220, 350, 190],
            labels: ['Tailoring', 'Inventory Managemnet', 'Customer Assistant', 'Store Maintanace', 'Sales Management'],
            colors: ['#0427B9', '#435DCA', '#8192DB', '#9AA8E2', '#C0C8ED'],
            chart: {
                type: 'donut',
                height: 315,
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 320,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    </script>

    <script>
        $('.approve-attendance').on("click",function () {
            let userId = $(this).data("id");

            // console.log(userId);
            $.ajax({
                url: "{{ route('attendance.approve') }}",
                type: "POST",
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        alert("Attendance Approved!");
                        location.reload();
                    } else {
                        alert("Something went wrong!");
                    }
                },
                error: function () {
                    alert("Error occurred!");
                }
            });
        });

</script>

@endsection
