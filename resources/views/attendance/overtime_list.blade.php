@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overtime List</h4>
        </div>

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
                            <th>Emp Code</th>
                            <th>Emp Name</th>
                            <th>Role</th>
                            <th>Store</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($ot_lists as $ot)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$ot->name}}</td>
                            <td>{{$ot->emp_code}}</td>
                            <td>{{$ot->role}}</td>
                            <td>{{$ot->store_name}}</td>
                            <td>{{$ot->cat}}</td>
                            <td>{{date("d-m-Y",strtotime($ot->c_on))}}</td>
                            <td>{{$ot->time}}</td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- <a><i class="fas fa-circle-check text-success"></i></a> --}}
                                    <button class="approve-ot" data-bs-toggle="tooltip"
                                    data-id="{{ $ot->id }}" data-bs-title="Approved"><i
                                        class="fas fa-circle-check text-success"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
         $(document).ready(function () {
            $(document).on("click", ".approve-ot", function () {
                let attd_Id = $(this).data("id");

                // console.log(userId);
                $.ajax({
                    url: "{{ route('ot.approve') }}",
                    type: "POST",
                    data: {
                        attd_id: attd_Id,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("Attendance Approved!");
                            location.reload();
                        } else {
                            alert("Something went wrong!");
                        }
                    },
                    error: function () {
                        alert("Error occurred!");
                    }
                });
            });
        });
    </script>

@endsection
