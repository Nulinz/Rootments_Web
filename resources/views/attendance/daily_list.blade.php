@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Daily Attendance List</h4>
        </div>

        <form action="{{ route('attendance.list')}}" method="POST" id="attendanceForm">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" autofocus required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($stores as $store)
                                <option value="{{$store->id}}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                    {{$store->store_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="date">Date <span>*</span></label>
                        <input type="date" class="form-control" name="date" id="date" required value="{{ old('date') }}">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 w-50 d-flex justify-content-center align-items-center mx-auto">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>

        <!-- Table for displaying attendance data -->
        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder="Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                        <!--        height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                        <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                        <!--        height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Role</th>
                            <th>In-Time</th>
                            <th>Out-Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $list)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$list->emp_code}}</td>
                            <td>{{$list->name}}</td>
                            <td>{{$list->role}}</td>
                            <td>{{$list->in_time}}</td>
                            <td>{{$list->out_time}}</td>
                            <td>{{$list->status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
