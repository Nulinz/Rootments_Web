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
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <button class="" data-bs-toggle="tooltip" data-bs-title="Approved"><i
                                                    class="fas fa-circle-check text-success"></i></button>
                                        </td>
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