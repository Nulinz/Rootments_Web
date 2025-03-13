@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
    <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Recruit Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Recruit Request Details</h4>
        </div>
        <form action="{{ route('recruitment.store') }}" method="post">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="department">Department <span>*</span></label>
                        <select class="form-select " name="department" id="department" autofocus required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Admin">Admin</option>
                            <option value="HR">HR</option>
                            <option value="Operation">Operation</option>
                            <option value="Finance">Finance</option>
                            <option value="IT">IT</option>
                            <option value="Sales/Marketing">Sales/Marketing</option>
                            <option value="Area">Area</option>
                            <option value="Cluster">Cluster</option>
                            <option value="Store">Store</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="role">Role <span>*</span></label>
                        <select class="form-select " name="role" id="role" required>
                            <option value="" selected disabled>Select Options</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="location">Location <span>*</span></label>
                        <input class="form-control " name="location" id="location" placeholder="Enter Location">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="repairdate">Recruit Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="res_date" id="repairdate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="vacancy">Vacancy <span>*</span></label>
                        <input type="number" class="form-control" name="vacancy" id="vacancy" placeholder="Enter Vacancy"
                            required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="experience">Experience (In Years) <span>*</span></label>
                        <input type="text" class="form-control" name="experience" id="experience"
                            placeholder="Enter Experience" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="recruitdescp">Recruit Description</label>
                        <textarea rows="1" class="form-control" name="recruitdescp" id="recruitdescp"
                            placeholder="Enter Recruit Description"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Request</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#department').change(function () {
                var department = $(this).val();
                $('.store-section').hide().find('input, select').prop('required', false);
                if (department === 'Store') {
                    $('.store-section').show().find('input, select').prop('required', true);
                }
            });
        });
    </script>
    <script>
        $('#department').on('change',function(){
            var dept = $(this).val();
            // alert(dept);

            $.ajax({
                url : "{{ route('recruitment.role') }}",
                type:'POST',
                data:{
                    _token:'{{ csrf_token() }}',
                    dept:dept
                },
                success:function(res){
                    // console.log(res);

                    $('#role').empty();

                    $.each(res,function(index,value){

                        var opt = '<option value="'+value.id + '">'+ value.role +'</option>';
                        $('#role').append(opt);

                    });

                      // $.each(response.store, function (index, value) {
                    //     // Create a new table row
                    //     var row = '<tr>' +
                    //         '<td><div><input type="checkbox" name="store[]" value="' + value
                    //             .store_ref_id + '"></div></td>' +
                    //         '<td>' + value.store_name + '</td>' +
                    //         '<td>' + value.store_code + '</td>' +
                    //         '<td>' + value.user_name + '</td>' +
                    //         '<td>' + value.store_geo + '</td>' +
                    //         '<td>' + value.store_contact + '</td>' +
                    //         '</tr>';

                    //     // Append the new row to the tbody
                    //     $('tbody').append(row);
                    // });

                },
                error:function(error){
                    console.log(error)
                }
            });
        });
    </script>

@endsection
