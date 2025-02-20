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
                        <label for="storecode">Store Code <span>*</span></label>
                        <select class="form-select " name="store_id" id="storecode" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storename">Store Name <span>*</span></label>
                        <input type="text" class="form-control" name="store_name" id="storename"
                            placeholder="Enter Store Name" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="repairdate">Recruit Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="res_date" id="repairdate" required>
                    </div>
                </div>
            </div>
            <div class="sidebodyhead my-3">
                <h4 class="m-0">Store </h4>
            </div>


            <div class="container-fluid maindiv my-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Vacant Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="role-list">

                    </tbody>
                </table>

            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Request</button>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            const newRow = () => {
                return `
            <tr>
                <td>
                    <div>
                        <select class="form-select role-select" name="role_id[]" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($role_data as $data)
                                <option value="{{ $data->id }}">{{ $data->role }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="number" name="vat_count[]" class="form-control" min="0" placeholder="Enter Required Count" required>
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-3">
                        <a><i class="fas fa-circle-plus text-success addRow"></i></a>
                        <a><i class="fas fa-circle-minus text-danger removeRow"></i></a>
                    </div>
                </td>
            </tr>`;
            };

            if ($(".role-list tr").length === 0) {
                $(".role-list").append(newRow());
            }

            $(document).on("click", ".addRow", function() {
                $(".role-list").append(newRow());
                updateDisabledOptions();
            });

            $(document).on("click", ".removeRow", function() {
                if ($(".role-list tr").length > 1) {
                    $(this).closest("tr").remove();
                    updateDisabledOptions();
                }
            });

            const updateDisabledOptions = () => {
                const selectedValues = $(".role-select").map(function() {
                    return $(this).val();
                }).get();

                $(".role-select").each(function() {
                    const currentSelect = $(this);
                    currentSelect.find("option").each(function() {
                        const optionValue = $(this).val();
                        if (selectedValues.includes(optionValue) && optionValue !==
                            currentSelect.val()) {
                            $(this).prop("disabled", true);
                        } else {
                            $(this).prop("disabled", false);
                        }
                    });
                });
            };

            $(document).on("change", ".role-select", function() {
                updateDisabledOptions();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('get_emp_name') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#storename').val(data.store_name);
                        $('#storecode').append(
                            `<option value="${data.store_id}" selected>${data.store_code}</option>`
                        );

                    }
                },
                error: function() {
                    alert('Failed to fetch store details.');
                }
            });
        });
    </script>
@endsection
