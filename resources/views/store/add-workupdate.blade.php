@extends ('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Add Work Update</h4>
        </div>

        <form action="" method="">
            <div class="container-fluid maindiv bg-white my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="date" id="date" readonly>
                    </div>
                </div>
            </div>
        </form>

        <div class="container-fluid mt-4 listtable">
            <div class="table-wrapper">
                <form action="" method="">
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
                                <td>
                                    <input type="text" class="form-control" name="ftd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="mtd" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="lymtd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="l2l" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_1" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ach" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_2" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="con" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Quantity</td>
                                <td>
                                    <input type="text" class="form-control" name="ftd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="mtd" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="lymtd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="l2l" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_1" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ach" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_2" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="con" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Walk-In</td>
                                <td>
                                    <input type="text" class="form-control" name="ftd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="mtd" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="lymtd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="l2l" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_1" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ach" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_2" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="con" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Loss Of Sales</td>
                                <td>
                                    <input type="text" class="form-control" name="ftd">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="mtd" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="lymtd" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="l2l" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_1" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ach" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_2" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="con" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>ABS</td>
                                <td>
                                    <input type="text" class="form-control" name="ftd" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="mtd" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="lymtd" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="l2l" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_1">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ach">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_2">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="con" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Conversion</td>
                                <td>
                                    <input type="text" class="form-control" name="ftd" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="mtd" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="lymtd" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="l2l" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_1" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ach" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="tgt_2" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="con">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                        <button type="submit" class="formbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection