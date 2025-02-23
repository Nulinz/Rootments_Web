@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overview</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="container px-0 mt-3">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Stores</h6>
                            <select class="form-select mb-2" name="store" id="store">
                                <option value="" selected disabled>Select Options</option>
                                @foreach ($list as $ls)
                                <option value="{{$ls->id}}">{{$ls->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Store Code</th>
                                        <th>Store Name</th>
                                        <th>Location</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($store as $st)
                                    <tr>

                                        <td>{{$st->store_code}}</td>
                                        <td>{{$st->store_name}}</td>
                                        <td>{{$st->store_geo}}</td>

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
                            <h6 class="card1h6 mb-2">Cluster Target Achievement</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#store').on('change', function () {
            // Trigger an AJAX request when the page is ready
            var cluster = $(this).find('option:selected').val();
            $.ajax({
                url: '{{ route('get_cluster_store') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    cluster: cluster, // Send the selected store ID
                },

                success: function (response) {
                    // console.log(response);

                    $('tbody').empty();

                    $.each(response, function (index, value) {
                        // Create a new table row
                        var row = '<tr>' +

                            '<td>' + value.store_code + '</td>' +
                            '<td>' + value.store_name + '</td>' +
                            '<td>' + value.store_geo + '</td>' +
                            '</tr>';

                        // Append the new row to the tbody
                        $('tbody').append(row);
                    });

                },
                error: function (xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

    <!-- Chart 1 -->
    <script>
        var options = {
            series: [{
                name: 'Sales Achieved',
                data: [44, 55]
            }, {
                name: 'Sales Target Pending',
                data: [13, 23]
            }],
            colors: ['#0427B9', '#8192DB'],
            chart: {
                type: 'bar',
                height: 320,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            xaxis: {
                categories: ['Cluster Manager 1', 'Cluster Manager 2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

@endsection
