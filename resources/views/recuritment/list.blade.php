@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Recruitment Request List</h4>
            <a href="{{ route('recruitment.add') }}"><button class="listbtn">+ Add Recruit Request</button></a>
        </div>

        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <!--<div class="d-flex gap-3">-->
                    <!--    <a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print"-->
                    <!--            alt="" height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                    <!--    <a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel"-->
                    <!--            alt="" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    <!--</div>-->
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Store Code</th>
                            <th>Store Name</th>
                            <th>Role </th>
                            <th>Vacant Count</th>
                            <th>Recruit Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rec as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->store_code }}</td>
                                <td>{{ $data->store_name }}</td>
                                <td>{!! nl2br(str_replace(',', '<br>', $data->roles)) !!}</td>
                                <td>{!! nl2br(str_replace(',', '<br>', $data->vat_counts)) !!}</td>
                                <td>{{ $data->res_date }}</td>
                                <td>
                                    @if($data->status == 'Approved')
                                    <span class="text-success">Approved</span>
                                    @elseif($data->status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                    @else
                                    <span class="text-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
