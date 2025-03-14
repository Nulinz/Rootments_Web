@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Individual Attendance List</h4>
        </div>

        <form  method="post" id="form">
            @csrf

            <div class="container-fluid maindiv my-3">
                <div class="row">

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="dept">Departments <span>*</span></label>
                        <select class="form-select" name="dept" id="dept" autofocus required>
                            @foreach ($dept as $item)
                               <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs" id="store_div" style="display:none">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" >
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($stores as $store)
                                <option value="{{$store->id}}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                    {{$store->store_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="employee">Employee <span>*</span></label>
                        <select class="form-select" name="employee" id="employee" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="month">Month <span>*</span></label>
                        <input type="month" class="form-control" name="month" id="month">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 w-50 d-flex justify-content-center align-items-center mx-auto">
                <button type="submit" class="formbtn">Save</button>
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
                            <th>Date</th>
                            <th>In Location</th>
                            <th>In Time</th>
                            <th>Out Location</th>
                            <th>Out Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td>1</td>
                            <td>EMP01</td>
                            <td>12-02-2025</td>
                            <td>Admin</td>
                            <td>24</td>
                            <td>1</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#dept').on('change',function(){
            var dept = $(this).find('option:selected').val();
            if(dept==='Store'){
                $('#store_div').show();
            }else{
                $('#store_div').hide();
            }
        });
    </script>



<script>
     $('#stores, #dept').on('change', function() {



        $('#employee').empty();
            // Trigger an AJAX request when the page is ready

            var dept = $('#dept').find('option:selected').val();

            if(dept=='Store'){

                var store_id = $('#stores').find('option:selected').val();
            }



            $.ajax({
                url: '{{ route('get_store_per') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    store_id: store_id, // Send the selected store ID,
                    dept : dept
                },

                success: function(response) {
                     console.log(response);

                        // $('#emploee').append('<option value="" selected disabled>Select Options</option>');
                    $.each(response, function(index, value) {
                        // Create a new table row
                        var row = '<option value="' + value.id + '">' + value.name + '</option>';
                        // Append the new row to the tbody
                        $('#employee').append(row);
                    });

                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });

        // $('#form').on('submit', function() {

        //     e.preventDefault();  // Prevent the form from submitting the usual way

        //         let formData = new FormData(this); // Gather form data

        //         // console.log(...formData);


        //     $.ajax({
        //         url: '{{ route('get_ind_attd') }}', // Laravel route for the POST request
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}', // CSRF token for security
        //             formdata : formData, // Send the selected store ID
        //         },

        //         success: function(response) {
        //             console.log(response);

        //             $('table tbody').empty();
        //                 // $('#emploee').append('<option value="" selected disabled>Select Options</option>');
        //             // $.each(response, function(index, value) {
        //             //     // Create a new table row
        //             //     var row = '<tr>' +
        //             //         '<td>' + value.date + '</td>' +
        //             //         '<td>' + value.in_location + '</td>' +
        //             //         '<td>' + value.in_time + '</td>' +
        //             //         '<td>' + value.out_location + '</td>' +
        //             //         '<td>' + value.in_time + '</td>' +
        //             //         '</tr>';

        //             //     // Append the new row to the tbody
        //             //     $('tbody').append(row);
        //             // });

        //         },
        //         error: function(xhr, status, error) {

        //             alert('An error occurred: ' + error);
        //         }
        //     });
        // });
</script>

<script>
    $('#form').on('submit', function(e) {
    e.preventDefault();  // Prevent the form from submitting the usual way

    let formData = new FormData(this); // Gather form data

    $.ajax({
        url: '{{ route('get_ind_attd') }}', // Laravel route for the POST request
        type: 'POST',
        data: formData, // Directly send the form data (without wrapping it)
        processData: false, // Don't process the data
        contentType: false, // Don't set the content type (since it's FormData)

        success: function(response) {
            console.log(response); // For debugging, check the response

            // Clear previous table data
            $('table tbody').empty();

            // Populate the table with new data (example of how you can use response)
            $.each(response, function(index, value) {
                var row = '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + value.c_on + '</td>' +
                    '<td>' + value.in_location + '</td>' +
                    '<td>' + value.in_time + '</td>' +
                    '<td>' + value.out_location + '</td>' +
                    '<td>' + value.out_time + '</td>' +
                    '</tr>';

                $('table tbody').append(row); // Append the row to the table
            });
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error);  // Handle error gracefully
        }
    });
});

</script>

@endsection
