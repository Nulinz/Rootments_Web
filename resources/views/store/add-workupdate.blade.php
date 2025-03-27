@extends ('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Add Work Update</h4>
        </div>


            <div class="container-fluid maindiv bg-white my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="" id="date" value="{{ date("Y-m-d") }}" readonly>
                    </div>
                </div>
            </div>

        <div class="container-fluid mt-4 listtable">
            <div class="table-wrapper">
                <form action="{{ route('store.work') }}" method="POST" id="c_form">
                    @csrf
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
                                    <input type="text" class="form-control" name="b_ftd" id="b_ftd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="b_mtd" id="b_mtd" value="{{ $sums->b_mtd_sum ?? 0 }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="b_lymtd" id="b_lymtd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="b_ltl" id="b_ltl" value="0" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Quantity</td>
                                <td>
                                    <input type="text" class="form-control" name="q_ftd" id="q_ftd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="q_mtd" id="q_mtd" value="{{ $sums->q_mtd_sum ?? 0 }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="q_lymtd" id="q_lymtd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="q_ltl" id="q_ltl" value="0" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Walk-In</td>
                                <td>
                                    <input type="text" class="form-control" name="w_ftd" id="w_ftd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="w_mtd" id="w_mtd" value="{{ $sums->w_mtd_sum ?? 0 }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="w_lymtd" id="w_lymtd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="w_ltl" id="w_ltl" value="0" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Loss Of Sales</td>
                                <td>
                                    <input type="text" class="form-control" name="los_ftd" id="los_ftd" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="los_mtd" id="los_mtd" value="{{ $sums->los_mtd_sum ?? 0 }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name=""  disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="los_abs" id="los_abs" value="0" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>ABS</td>
                                <td>
                                    <input type="text" class="form-control" name="abs_ftd" id="abs_ftd" value="0" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs_tgt" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs_ach" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="abs_per" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Conversion</td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="conversion" value="0">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                        <button type="submit" id="sub" class="formbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script>
        $(document).ready(function() {
    // Trigger calculation whenever any of the 'lymtd' fields change
    $('#b_lymtd, #q_lymtd, #w_lymtd').on('input', function() {
        calculateAndUpdate();
    });

    function calculateAndUpdate() {
        // Get the values of the fields
        var bLymtd = parseFloat($('#b_lymtd').val()) || 0;
        var qLymtd = parseFloat($('#q_lymtd').val()) || 0;
        var wLymtd = parseFloat($('#w_lymtd').val()) || 0;

        // Get the values of the MTD fields (assuming these are numeric)
        var bMtd = parseFloat($('#b_mtd').val()) || 1;  // Avoid division by 0
        var qMtd = parseFloat($('#q_mtd').val()) || 1;  // Avoid division by 0
        var wMtd = parseFloat($('#w_mtd').val()) || 1;  // Avoid division by 0

        // Calculate the results for b_ltl, q_ltl, w_ltl using the formula (value / mtd) - 1
        var bLtl = (bLymtd / bMtd) - 1;
        var qLtl = (qLymtd / qMtd) - 1;
        var wLtl = (wLymtd / wMtd) - 1;

        // Update the input fields with the calculated values
        $('#b_ltl').val(bLtl.toFixed(2)); // Limiting the decimal places to 2
        $('#q_ltl').val(qLtl.toFixed(2)); // Limiting the decimal places to 2
        $('#w_ltl').val(wLtl.toFixed(2)); // Limiting the decimal places to 2
    }

    $('#q_ftd').on('input', function() {

        var q_ftd = parseFloat($('#q_ftd').val()) || 0; // Ensure it's a number
        var b_ftd = parseFloat($('#b_ftd').val()) || 0; // Ensure it's a number

        $('#abs_ftd').val((q_ftd / b_ftd).toFixed(2));
        });

    $('#b_ftd, #q_ftd, #w_ftd, #los_ftd').on('input', function() {
        cal_mtd();
    });

    $('#los_ftd').on('input', function() {
         var q_mtd = $('#q_mtd').val();
         var b_mtd = $('#b_mtd').val();

         $('#los_abs').val((q_mtd/b_mtd).toFixed(2))
    });



    function cal_mtd() {

    $('#b_mtd').val((parseFloat($('#b_ftd').val()) || 0 + parseFloat($('#b_mtd').val()) || 0).toFixed(2));
    $('#q_mtd').val((parseFloat($('#q_ftd').val()) || 0 + parseFloat($('#q_mtd').val()) || 0).toFixed(2));
    $('#w_mtd').val((parseFloat($('#w_ftd').val()) || 0 + parseFloat($('#w_mtd').val()) || 0).toFixed(2));
    $('#los_mtd').val((parseFloat($('#los_ftd').val()) || 0 + parseFloat($('#los_mtd').val()) || 0).toFixed(2));
        // Get the values of the fields
        // var b_ftd = parseFloat($('#b_ftd').val()) || 0;
        // var q_ftd = parseFloat($('#q_ftd').val()) || 0;
        // var w_ftd = parseFloat($('#w_ftd').val()) || 0;

        // var b_mtd = parseFloat($('#b_mtd').val()) || 0;
        // var q_mtd = parseFloat($('#q_mtd').val()) || 0;
        // var w_mtd = parseFloat($('#w_mtd').val()) || 0;



        // // Update the input fields with the calculated values
        // $('#b_mtd').val((b_ftd+b_mtd).toFixed(2)); // Limiting the decimal places to 2
        // $('#q_mtd').val((q_ftd+q_mtd).toFixed(2)); // Limiting the decimal places to 2
        // $('#w_mtd').val((w_ftd+w_mtd).toFixed(2)); // Limiting the decimal places to 2
    }
});

    </script>

@endsection
