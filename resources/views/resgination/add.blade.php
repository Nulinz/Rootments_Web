@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Resign Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Resign Request Details</h4>
        </div>
        <form action="{{ route('resignation.store') }}" method="post">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="empid">Employee Code <span>*</span></label>
                        <select class="form-select" name="emp_id" id="empcode" autofocus required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="empname">Employee Name <span>*</span></label>
                        <input type="text" class="form-control" name="emp_name" id="empname"
                            placeholder="Enter Employee Name" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="store">Store Name <span>*</span></label>
                        <select class="form-select" name="store_id" id="store" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="resigndate">Resign Request Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="res_date" id="resigndate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reason">Reason For Leaving <span>*</span></label>
                        <textarea rows="1" class="form-control" name="res_reason" id="reason" placeholder="Enter Reason For Leaving"
                            required></textarea>
                    </div>
                    @php
                    $user = auth()->user();
                    $role = $user->role_id;

                    @endphp
                    @if($role < 13 || $role > 19)
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reqtype">Request To <span>*</span></label>
                        <select class="form-select" name="request_to" id="" required>
                            @foreach ($hr_list as $hr)
                            <option value="{{$hr->id}}">{{$hr->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Request</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('get_emp_name') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#empname').val(data.name);
                        $('#empcode').append(
                            `<option value="${data.id}" selected>${data.emp_code}</option>`);
                        $('#empname').val(data.name);
                        $('#store').append(
                            `<option value="${data.store_id}" selected>${data.store_code} - ${data.store_name}</option>`
                        );

                    }
                },
                error: function() {
                    alert('Failed to fetch store details.');
                }
            });
        });
    </script>
@endsection
