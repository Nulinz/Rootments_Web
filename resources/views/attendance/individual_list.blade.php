@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Individual Attendance List</h4>
        </div>

        <form action="" method="post" id="">
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="month">Month <span>*</span></label>
                        <select class="form-select" name="month" id="month" autofocus required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="January">January</option>
                            <option value="Februray">Februray</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" required>
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
                        <label for="employee">Employee <span>*</span></label>
                        <select class="form-select" name="employee" id="employee" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="">Employee List</option>
                        </select>
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
                            <th>Date</th>
                            <th>Role</th>
                            <th>Present</th>
                            <th>Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>EMP01</td>
                            <td>12-02-2025</td>
                            <td>Admin</td>
                            <td>24</td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
