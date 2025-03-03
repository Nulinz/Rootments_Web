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
                                    @foreach ($pur_emp as $pur)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">

                                                        <img src="{{ asset($pur->profile_image ?? 'assets/images/avatar.png') }}" alt="">

                                                    <div>
                                                        <h5 class="mb-0">{{ $pur->name }}</h5>
                                                        <h6 class="mb-0">{{ $pur->in_location ?? 'No location' }}</h6>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>@if(!is_null($pur->in_time))
                                                    {{ date("h:i", strtotime($pur->in_time)) }}
                                                @endif
                                            </td>
                                            <td>@if(!is_null($pur->out_time))
                                                    {{ date("h:i", strtotime($pur->out_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($pur->status == 'approved')
                                                    <button class="" data-bs-toggle="tooltip"
                                                        data-id="{{ $pur->user_id }}" data-bs-title="Approved"><i
                                                            class="fas fa-circle-check text-success"></i></button>
                                                @else
                                                     @if(!empty($pur->in_time))

                                                    <button class="approve-attendance" data-bs-toggle="tooltip"
                                                        data-id="{{ $pur->user_id }}" data-bs-title="Not Approved"><i
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

            </div>
        </div>
    </div>

@endsection
