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
                                    @foreach ($hr_emp as $hr)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">

                                                    <img src="{{ asset($hr->profile_image ?? 'assets/images/avatar.png') }}" alt="">

                                                    <div>
                                                        <h5 class="mb-0">{{ $hr->name }}</h5>
                                                        <h6 class="mb-0">{{ $hr->in_location ?? 'No location' }}</h6>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>@if(!is_null($hr->in_time))
                                                    {{ date("h:i", strtotime($hr->in_time)) }}
                                                @endif
                                            </td>
                                            <td>@if(!is_null($hr->out_time))
                                                    {{ date("h:i", strtotime($hr->out_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($hr->status == 'approved')
                                                    <button class="" data-bs-toggle="tooltip"
                                                        data-id="{{ $hr->user_id }}" data-bs-title="Approved"><i
                                                            class="fas fa-circle-check text-success"></i></button>
                                                @else
                                                     @if(!empty($hr->in_time))

                                                    <button class="approve-attendance" data-bs-toggle="tooltip"
                                                        data-id="{{ $hr->user_id }}" data-bs-title="Not Approved"><i
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
                                    @foreach ($pendingLeaves as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    @if ($data->profile_image)
                                                        <img src="{{ asset($data->profile_image) }}" alt="">
                                                    @else
                                                        <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                    @endif
                                                    <div>
                                                        <h5 class="mb-0">{{ $data->name }}</h5>
                                                        <h6 class="mb-0">
                                                            Requesting for leave
                                                            {{ date('m-d-Y', strtotime($data->start_date)) }} to
                                                            {{ date('m-d-Y', strtotime($data->end_date)) }} -
                                                            {{ $data->reason }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><a href="{{ route('approve.index') }}"
                                                    class="text-decoration-underline">View</a></td>
                                        </tr>
                                    @endforeach
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
         var taskCounts = @json($task);

            var chartElement = document.querySelector("#chart1");

            var seriesData = [
                Number(taskCounts.todo) || 0,
                Number(taskCounts.in_progress) || 0,
                Number(taskCounts.on_hold) || 0,
                Number(taskCounts.completed) || 0
            ];

        var options = {
            series: seriesData,
            labels: ['To Do', 'In Progress', 'On Hold', 'Completed'],
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
        var taskCounts = @json($taskCounts);
            var staffNames = @json($staffNames);

            // Ensure the chart container exists
            var chartElement = document.querySelector("#chart2");

        var options = {
            series: [{
                data: taskCounts
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
                categories: staffNames,
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

            var categoryNames = @json($categoryNames);
            var taskCounts = @json($categorytaskCounts);

            var chartElement = document.querySelector("#chart3");


        var options = {
            series: taskCounts,
            labels: categoryNames,
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
         var subcategoryNames = {!! json_encode($subcategoryNames ?? []) !!};
            var subtaskCounts = {!! json_encode($subcategorytaskCounts ?? []) !!};

            var chartElement = document.querySelector("#chart4");
        var options = {
            series: subtaskCounts,
            labels: subcategoryNames,
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

                console.log(userId);
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
