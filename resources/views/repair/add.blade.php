@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Repair Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Repair Request Details</h4>
        </div>
        <form action="{{ route('repair.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storecode">Store Code <span>*</span></label>
                        <select class="form-select" name="store_code" id="storecode" autofocus required>
                            <option value="" selected disabled>Select Options</option>
                            
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storename">Store Name <span>*</span></label>
                        <input type="text" class="form-control" name="store_name" id="storename"
                            placeholder="Enter Store Name" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="repairdate">Repair Request Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="repair_date" id="repairdate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reason">Repair Description <span>*</span></label>
                        <textarea rows="1" class="form-control" name="repair_description" id="reason"
                            placeholder="Enter Repair Description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="upload">Upload File</label>
                        <input type="file" class="form-control" name="repair_file" id="upload">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Request</button>
            </div>
        </form>
    </div>
<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{ route('get_store_name') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data) {
                    $('#storecode').append(`<option value="${data.id}" selected>${data.store_code}</option>`);
                    $('#storename').val(data.store_name);
                }
            },
            error: function() {
                alert('Failed to fetch store details.');
            }
        });
    });
</script>
@endsection
