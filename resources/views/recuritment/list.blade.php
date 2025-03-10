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
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recruit ID</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Vacant Count</th>
                            <th>Recruit Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>REC01</td>
                            <td>HR</td>
                            <td>Manager</td>
                            <td>2</td>
                            <td>14-02-2025</td>
                            <td>
                                <span class="text-success">Approved</span>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>REC02</td>
                            <td>Store</td>
                            <td>Assistant Store Manager</td>
                            <td>1</td>
                            <td>16-03-2025</td>
                            <td>
                                <span class="text-warning">Pending</span>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>REC03</td>
                            <td>Warehouse</td>
                            <td>Manager</td>
                            <td>1</td>
                            <td>20-02-2025</td>
                            <td>
                                <span class="text-danger">Rejected</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection