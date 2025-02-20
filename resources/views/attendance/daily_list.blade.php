@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Daily Attendance List</h4>
        </div>

        <form action="" method="post" id="">
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" autofocus required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Store 1">Store 1</option>
                            <option value="Store 2">Store 2</option>
                            <option value="Store 3">Store 3</option>
                            <option value="Store 4">Store 4</option>
                            <option value="Store 5">Store 5</option>
                            <option value="Store 6">Store 6</option>
                            <option value="Store 7">Store 7</option>
                            <option value="Store 8">Store 8</option>
                            <option value="Store 9">Store 9</option>
                            <option value="Store 10">Store 10</option>
                            <option value="Store 11">Store 11</option>
                            <option value="Store 12">Store 12</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="date">Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="date" id="date" required>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 w-50 d-flex justify-content-center align-items-center mx-auto">
                <button type="button" class="formbtn">Save</button>
            </div>
        </form>

        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <a href="" id="pdfLink"><img src="./images/printer.png" id="print" alt=""
                                height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>
                        <a href="" id="excelLink"><img src="./images/excel.png" id="excel" alt=""
                                height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>EMP01</td>
                            <td>Sabari</td>
                            <td>Admin</td>
                            <td>09: 00: 59</td>
                            <td>06: 10: 30</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
