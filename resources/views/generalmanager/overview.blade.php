@extends('layouts.app')
@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">

    <div class="px-5 py-3 sidebodydiv">
        <div class="sidebodyhead">
            <h4 class="m-0">Overview</h4>
        </div>

        <!-- Tabs -->
        @include('generaldashboard.tabs')

        <div class="container mt-2 px-0">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
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
                                                    {{-- @if ($hr->profile_image) --}}
                                                        <img src="{{ asset($hr->profile_image ?? 'assets/images/avatar.png') }}" alt="">
                                                    {{-- @endif --}}
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
                                                            class="text-success fa-circle-check fas"></i></button>
                                                @else
                                                     @if(!empty($hr->in_time))

                                                    <button class="approve-attendance" data-bs-toggle="tooltip"
                                                        data-id="{{ $hr->user_id }}" data-bs-title="Not Approved"><i
                                                            class="text-warning fa-circle-check fas"></i></button>
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

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Team Counts</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Request</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <tbody>
                                    @foreach ($pendingRequests as $data)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                @if ($data->profile_image)
                                                    <img src="{{ asset($data->profile_image) }}" alt="" width="40" height="40">
                                                @else
                                                    <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                @endif
                                                <div>
                                                    <h5 class="mb-0">{{ $data->name }}</h5>
                                                    <h6 class="mb-0">
                                                        Requesting for
                                                        <strong>{{ $data->request_type }}</strong>

                                                        @if($data->request_type == 'Leave' && isset($data->start_date, $data->end_date))
                                                            ({{ date('m-d-Y', strtotime($data->start_date)) }} to
                                                            {{ date('m-d-Y', strtotime($data->end_date)) }})
                                                            - {{ $data->reason }}
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('approve.index', ['id' => $data->id, 'type' => $data->request_type]) }}"
                                                class="text-decoration-underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Today Store Status</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Total</th>
                                        <th>Present</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($store_per as $sp)

                                    <tr>
                                        <td>{{ $sp->store_code }}</td>
                                        <td>{{ $sp->store_name }}</td>
                                        <td>{{ $sp->members_count }}</td>
                                        <td>{{ $sp->present_today_count }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).on("click", ".approve-attendance", function () {

               let userId = $(this).data("id");

               console.log(userId);
               $.ajax({
                   url: "{{ route('attendance.approve') }}",
                   type: "POST",
                   data: {
                       user_id: userId,
                       _token: $('meta[name="csrf-token"]').attr("content")
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var roleNames = @json($roleNames);
            var userCounts = @json($userCounts);

            var options = {
                series: [{
                    name: "Users Count",
                    data: userCounts
                }],
                chart: {
                    type: "bar",
                    height: 320,
                },
                plotOptions: {
                    bar: {
                        borderRadius: 0,
                        horizontal: true,
                        barHeight: '80%',
                    },
                },
                dataLabels: {
                    enabled: false,
                    formatter: function (val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex] + ': ' + val;
                    },
                },
                xaxis: {
                    categories: roleNames,
                },
                legend: {
                    show: false
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart2"), options);
            chart.render();
        });
    </script>


@endsection
