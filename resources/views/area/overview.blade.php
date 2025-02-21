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
                                <option value="">Cluster 1</option>
                                <option value="">Cluster 2</option>
                            </select>
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
                                    <tr>
                                        <td>STR01</td>
                                        <td>Rootments</td>
                                        <td>Salem</td>
                                        <td><span class="active">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR02</td>
                                        <td>Rootments</td>
                                        <td>Kerala</td>
                                        <td><span class="active">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR03</td>
                                        <td>Rootments</td>
                                        <td>Coimbatore</td>
                                        <td><span class="active">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR04</td>
                                        <td>Rootments</td>
                                        <td>Palakkad</td>
                                        <td><span class="inactive">Inactive</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR05</td>
                                        <td>Rootments</td>
                                        <td>Wayanad</td>
                                        <td><span class="active">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR06</td>
                                        <td>Rootments</td>
                                        <td>Varkhala</td>
                                        <td><span class="inactive">Inactive</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR07</td>
                                        <td>Rootments</td>
                                        <td>Kochin</td>
                                        <td><span class="inactive">Inactive</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR08</td>
                                        <td>Rootments</td>
                                        <td>Trivandram</td>
                                        <td><span class="active">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>STR09</td>
                                        <td>Rootments</td>
                                        <td>Kottayam</td>
                                        <td><span class="inactive">Inactive</span></td>
                                    </tr>
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