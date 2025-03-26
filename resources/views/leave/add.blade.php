@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Leave Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Leave Request Details</h4>
        </div>
        <form action="{{ route('leave.store') }}" method="post">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="startdate">Start Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="start_date" id="startdate" required autofocus>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="enddate">End Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="end_date" id="enddatedate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reqtype">Request Type <span>*</span></label>
                        <select class="form-select" name="request_type" id="reqtype" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Permission">Permission</option>
                            <option value="Week Off">Week Off</option>
                            <option value="Casual Leave">Casual Leave</option>
                            <option value="Sick Leave">Sick Leave</option>
                        </select>
                    </div>
                    {{-- @php
                        $user = auth()->user();
                        $role = $user->role_id;

                        // echo $role;

                    @endphp
                     @if(hasAccess($role,'employee')) --}}
                     {{-- ($role < 13 || $role > 19) --}}
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reqtype">Request To <span>*</span></label>
                        <select class="form-select" name="request_to" id="" required>
                            @foreach ($list as $li)
                            <option value="{{$li->id}}">{{$li->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    {{-- @endif --}}
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs time-section" style="display: none;">
                        <label for="starttime">Start Time</label>
                        <input type="time" class="form-control" name="start_time" id="starttime">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs time-section" style="display: none;">
                        <label for="endtime">End Time</label>
                        <input type="time" class="form-control" name="end_time" id="endtime">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reason">Reason <span>*</span></label>
                        <textarea rows="1" class="form-control" name="reason" id="reason" placeholder="Enter Reason" required></textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Request</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $("#reqtype").change(function () {
                var request = $(this).val();
                $(".time-section").hide();
                $(".time-section").prop("required", false);
                if (request === "Permission") {
                    $(".time-section").show();
                    $(".time-section").prop("required", true);
                }
            })
        })
    </script>
@endsection
