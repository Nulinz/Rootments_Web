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
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sheik</h5>
                                                    <h6 class="mb-0">Salem</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>10: 00 AM</td>
                                        <td>18: 00 PM</td>
                                        <td>
                                            <button class="approve-attendance" data-bs-toggle="tooltip"
                                                data-bs-title="Not Approved"><i
                                                    class="text-warning fa-circle-check fas"></i></button>

                                        </td>
                                    </tr>

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
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-2">

                                                <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                <div>
                                                    <h5 class="mb-0">Sheik</h5>
                                                    <h6 class="mb-0">
                                                        Requesting for
                                                        <strong>Week Off</strong>


                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('approve.index') }}" class="text-decoration-underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
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
                                    <tr>
                                        <td>STR01</td>
                                        <td>Zorucci Edappally</td>
                                        <td>10</td>
                                        <td>5</td>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@endsection