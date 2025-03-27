@extends ('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Salary Generation List</h4>
        </div>

        @if(request()->isMethod('get'))
            <form action="{{route('payroll.listPerson')}}" method="post" id="">
                @csrf
                <div class="container-fluid maindiv bg-white my-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                            <label for="dept">Departments</label>
                            <select class="form-select" name="dept" id="dept" autofocus required>
                                @foreach ($dept as $item)
                                    <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs" id="store_div" style="display:none">
                            <label for="store">Store Name</label>
                            <select class="form-select store" name="store" id="store" autofocus>
                                <option value="" selected disabled>Select Stores</option>
                                @foreach ($stores as $store)
                                    <option value="{{$store->id}}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                        {{$store->store_name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" name="month" id="month">
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                            <label for="twd">Total Working Days</label>
                            <input type="number" class="form-control" name="twd" id="twd" min="0"
                                placeholder="Enter Total Working Days">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                    <button type="submit" name="sal_form" class="formbtn">Save</button>
                </div>
            </form>
        @endif
        @if(request()->isMethod('post'))
            <div class="container-fluid mt-4 listtable">
                <!--<div class="filter-container row mb-3">-->
                <!--    <div class="custom-search-container col-sm-12 col-md-8">-->
                <!--        <select class="headerDropdown form-select filter-option">-->
                <!--            <option value="All" selected>All</option>-->
                <!--        </select>-->
                <!--        <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">-->
                <!--    </div>-->

                <!--    <div class="select1 col-sm-12 col-md-4 mx-auto">-->
                <!--        <div class="d-flex gap-3">-->
                <!--            <a id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                <!--                    height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                <!--            <a id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                <!--                    height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="table-wrapper">
                    <form action="{{route('payroll.insert')}}" method="POST" id="c_form">
                        @csrf
                        <input type="hidden" class="form-control" name="month" id="" value="{{$post_mon}}">
                        <input type="hidden" class="form-control" name="store" id="" value="{{$post_store}}">
                        <table class=" table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 100px">Employee</th>
                                    <th style="width: 100px">Salary</th>
                                    <th>TWD</th>
                                    <th>TPD</th>
                                    <th>LOP</th>
                                    <th>Incentives</th>
                                    <th>OT</th>
                                    <th>Bonus</th>
                                    <th>Deduction</th>
                                    <th>Advance</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($u_list as $ul)
                                                    @php
                                                        // dd($u_list);
                                                    @endphp
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$ul->emp_code}} <br> {{$ul->name}}</td>
                                                        <td>
                                                            <input hidden type="text" class="form-control" name="empId[]" value="{{$ul->emp_id}}">
                                                            <input type="number" class="form-control" id="base" name="salary[]"
                                                                value="{{$ul->base_salary}}" readonly>
                                                        </td>
                                                        <td><input type="number" class="form-control" id="twd" name="totalWork[]" value="{{$twd}}">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="tpd" name="present[]"
                                                                value="{{$ul->attendance_count ?? 0}}">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="lop" name="lop[]"
                                                                value="{{$twd - $ul->attendance_count}}">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="incentives" name="incentive[]" value="0">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="over_time" name="ot[]"
                                                                value="{{$ul->total_ot ?? 0}}">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="bonus" name="bonus[]" value="0">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="deduct" name="deduct[]"
                                                                value="{{$ul->total_late ?? 0}}">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="advance" name="advance[]" value="0">
                                                        </td>
                                                        <td><input type="number" class="form-control" id="total" name="total[]" value="0" readonly>
                                                        </td>
                                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                            <button type="submit" id="sub" class="formbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    <script>
        $('#dept').on('change', function () {
            var dept = $(this).find('option:selected').val();
            if (dept === 'Store') {
                $('#store_div').show();
            } else {
                $('#store_div').hide();
            }
        });
    </script>

    <script>
        $(document).on('change', '#incentives, #bonus, #advance, #deduct, #over_time, #lop, #tpd, #twd', function () {
            // Get the current row
            let $currentRow = $(this).closest('tr');

            // Get the values of inputs within the current row
            let twd = parseFloat($currentRow.find('#twd').val()) || 0;
            let inc = parseFloat($currentRow.find('#incentives').val()) || 0;
            let bonus = parseFloat($currentRow.find('#bonus').val()) || 0;
            let adv = parseFloat($currentRow.find('#advance').val()) || 0;
            let ded = parseFloat($currentRow.find('#deduct').val()) || 0;
            let ot = parseFloat($currentRow.find('#over_time').val()) || 0;
            let lop = parseFloat($currentRow.find('#lop').val()) || 0;
            let tpd = parseFloat($currentRow.find('#tpd').val()) || 0;
            let base = parseFloat($currentRow.find('#base').val()) || 0;

            let per_day = parseInt(base / twd);


            let plus = parseFloat(per_day * tpd) + parseFloat(inc) + parseFloat(ot) + parseFloat(bonus);
            let minus = parseFloat(ded) + parseFloat(adv);

            // Ensure that the calculation doesn't result in NaN
            let total = plus - minus;

            console.log(plus);

            $currentRow.find('#lop').val(twd - tpd)


            // Set the total in the current row's `.total` input
            $currentRow.find('#total').val(total)



            // let add = parseInt(base / 26);
            // let lop_amt = one * lop;
            // let per_amt = 50;
            // let per_amt1 = per * per_amt;
            // let total = (((base) - (lop_amt + per_amt1)))
            // let total1 = parseFloat(total) + parseInt(inc) + parseInt(add);

            //   $(".total").val(total1);

            // $currentRow.find('.total').val(total1);


            // console.log("Inc Value: " + inc);
            // console.log("Per Value: " + per);
            // console.log("Lop Value: " + base);

            // You can perform additional actions here with the retrieved values
        });
    </script>

    <script>
        $('#month1').on('change', function () {
            // Trigger an AJAX request when the page is ready
            var mon = $(this).val();
            $.ajax({
                url: '{{ route('payroll.drop') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    sal_mon: mon, // Send the selected store ID
                },

                success: function (response) {
                    console.log(response);


                    $('#store').empty(); // Clears all existing options in the select dropdown

                    $.each(response, function (index, value) {
                        var option = $('<option></option>').attr('value', value.id).text(value.store_name + ' - ' + value.store_code);
                        $('#store').append(option);
                    });
                    // $('#store').append('<option value="4-office">Office</option>');

                },
                error: function (xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });

        // $('.store').on('change', function() {
        //     // Trigger an AJAX request when the page is ready
        //     var store = $(this).val();
        //     var mon = $('#month').val();

        //     $.ajax({
        //         url: '{{ route('payroll.listPerson') }}', // Laravel route for the POST request
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}', // CSRF token for security
        //             mon: mon, // Send the selected store ID
        //             store: store, // Send the selected store ID
        //         },

        //         success: function(response) {
        //             // console.log(response);


        //             $.each(response, function(index, value) {
        //                 // Dynamically create an option element for each store
        //                 var option = $('<option></option>').attr('value', value.id + '-Store')
        //                     .text(value
        //                         .store_name + '-' + value.store_code);

        //                 // Append the option to the select element
        //                 $('#store').append(option);

        //             });
        //             $('#store').append('<option value="4-office">Office</option>');

        //         },
        //         error: function(xhr, status, error) {

        //             alert('An error occurred: ' + error);
        //         }
        //     });
        // });
    </script>
@endsection
