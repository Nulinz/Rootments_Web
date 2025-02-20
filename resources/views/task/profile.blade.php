@extends('layouts.app')


<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

<style>
    .mainbdy {
        margin-top: 10px;
        display: block !important;
    }
</style>

@section('content')
    <div class="sidebodydiv px-5 mb-4">

        <div class="sidebodyhead my-3">
            <h4 class="m-0">Task View</h4>
        </div>
        <div class="mainbdy">
            <div class="p-3 rounded-2 maindiv">
                <div class="row">
                    <!-- Left Content -->
                    <div class="col-12 col-sm-12 col-md-12 col-xl-12 left-content">
                        <div class="container ps-0 pe-2" id="timelinecards">
                            <div class="timeline">

                                @foreach ($task as $t)
    <div class="entry completed">
        <div class="title">
            <h3>{{ $t->assigned_by_name }}</h3>
            <h6 class="mb-2">{{ $t->assigned_by_role }}</h6>
            <h6>{{ \Carbon\Carbon::parse($t->created_at)->format('h:i A') }}</h6>
        </div>
        <div class="entrybody">
            <div class="taskname mb-1">
                <div class="tasknameleft">
                    @if($t->priority == 'High')
                        <i class="fa-solid fa-circle text-danger"></i>
                    @elseif($t->priority == 'Low')
                        <i class="fa-solid fa-circle text-primary"></i>
                    @else
                        <i class="fa-solid fa-circle text-warning"></i>
                    @endif
                    <h6 class="mb-0">{{ $t->task_title }}</h6>
                </div>
                <div class="tasknamefile">
                    @if ($t->task_file)
                        <a href="{{ asset($t->task_file) }}" data-bs-toggle="tooltip" data-bs-title="Attachment" download>
                            <i class="fa-solid fa-paperclip"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="taskcategory mb-1">
                <h6 class="mb-0">
                    <span class="category">{{ $t->category }}</span> / 
                    <span class="subcat">{{ $t->subcategory }}</span>
                </h6>
            </div>
            <div class="taskdescp mb-1">
                <h6 class="mb-0">{{ $t->task_description }}</h6>
                <h5 class="mb-0">{{ $t->assigned_to_name }} - {{ $t->assigned_to_role }}</h5>
            </div>
            <div class="taskdate mb-2">
                <h6 class="m-0 startdate">
                    <i class="fa-regular fa-calendar"></i>&nbsp;
                    {{ date('d/m/Y', strtotime($t->start_date)) }}
                </h6>
                <h6 class="m-0 enddate">
                    <i class="fas fa-flag"></i>&nbsp;
                    {{ date('d/m/Y', strtotime($t->end_date)) }}
                </h6>
            </div>
            <div class="taskdate">
                <h6 class="m-0 startdate">
                    <i class="fas fa-hourglass-start"></i>&nbsp;
                    {{ \Carbon\Carbon::parse($t->start_time)->format('h:i A') }}
                </h6>
                <h6 class="m-0 enddate">
                    <i class="fas fa-hourglass-end"></i>&nbsp;
                    {{ \Carbon\Carbon::parse($t->end_time)->format('h:i A') }}
                </h6>
            </div>
        </div>
    </div>
@endforeach



                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
