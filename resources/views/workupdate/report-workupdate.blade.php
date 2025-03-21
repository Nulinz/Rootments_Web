@extends ('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Work Update Report</h4>
        </div>

        <form action="">
            <div class="container-fluid maindiv bg-white my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="" id="date" value="{{ date("Y-m-d") }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="store">Store</label>
                        <select class="form-select" name="" id="store">
                            <option value="" selected disabled>Select Store</option>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>

        <div class="container-fluid mt-4 listtable">
            <div class="table-wrapper">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 100px">Type</th>
                            <th>FTD</th>
                            <th>MTD</th>
                            <th>LY MTD</th>
                            <th>L2L</th>
                            <th>TGT</th>
                            <th>ACH %</th>
                            <th>TGT %</th>
                            <th>ABS</th>
                            <th>CON %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bills</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Quantity</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Walk-In</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Loss Of Sales</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>ABS</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Conversion</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection