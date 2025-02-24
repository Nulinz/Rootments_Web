@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overtime / Late List</h4>
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
                                    <button class="approve-ot listtdicon"  data-bs-toggle="modal" data-bs-target="#updateLeaveApproval"
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

    <!-- Update Approval Modal -->
<div class="modal fade" id="updateLeaveApproval" tabindex="-1" aria-labelledby="updateLeaveApprovalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title fs-5" id="updateLeaveApprovalLabel">Update OverTime/Late Approval</h4>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <form action="{{ route('ot.approve') }}" method="POST" id="">
                @csrf

                <input type="hidden" id="ot_id" name="ot_id">
                <div class="col-sm-12 col-md-12 mb-3">
                    <label for="sts" class="col-form-label">OverTime/Late amount</label>
                    <input type="number" class="form-control" name="ot_amount" >
                </div>


                <!-- Move the button inside the form -->
                <div class="d-flex justify-content-center align-items-center mx-auto">
                    <button type="submit" class="modalbtn btn btn-primary">Update</button>
                </div>
            </form>


        </div>
    </div>
</div>
</div>

    <script>

            $('.approve-ot').on("click", function () {

                let ot_Id = $(this).data("id");

                $('#ot_id').val(ot_Id)

                // console.log(userId);
                // $.ajax({
                //     url: "{{ route('ot.approve') }}",
                //     type: "POST",
                //     data: {
                //         attd_id: attd_Id,
                //         _token: $('meta[name="csrf-token"]').attr("content")
                //     },
                //     success: function (response) {
                //         if (response.success) {
                //             alert("Attendance Approved!");
                //             location.reload();
                //         } else {
                //             alert("Something went wrong!");
                //         }
                //     },
                //     error: function () {
                //         alert("Error occurred!");
                //     }
                // });
            });

    </script>

@endsection
