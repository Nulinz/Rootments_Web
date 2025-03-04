<div class="sidebodyhead mt-3">
    <h4 class="m-0">Leave Approval List</h4>
</div>

<div class="container-fluid mt-3 listtable">
    <div class="filter-container row mb-3">
        <div class="custom-search-container col-sm-12 col-md-8">
            <select class="form-select filter-option" id="headerDropdown1">
                <option value="All" selected>All</option>
            </select>
            <input type="text" id="filterInput1" class="form-control" placeholder=" Search">
        </div>

        <div class="select1 col-sm-12 col-md-4 mx-auto">
            <!--<div class="d-flex gap-3">-->
            <!--    <a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
            <!--            height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
            <!--    <a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
            <!--            height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
            <!--</div>-->
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table table-hover table-striped" id="table1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Code</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Store</th>
                    <th>Date</th>
                    <th>Request</th>
                    <th>Status</th>
                    @php
                        $user_id = Auth::user()->id;

                        $user = DB::table('users')
                            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                            ->where('users.id', $user_id)
                            ->select('users.name', 'users.emp_code', 'roles.role', 'roles.role_dept', 'users.role_id')
                            ->first();
                    @endphp

                    {{-- @if (optional($user)->role_id == 3)
                        <th>OverAll Status</th>
                    @endif --}}

                </tr>
            </thead>

            <tbody>
                @foreach ($leave as $data)
                    @php

                        // $user = DB::table('users')
                        //     ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
                        //     ->where('users.id', $data->created_by)
                        //     ->select('users.name', 'users.emp_code', 'roles.role', 'roles.role_dept')
                        //     ->first();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->emp_code }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->role_dept }}</td>
                        <td>{{ $data->role }}</td>
                        <td>{{ $data->store_name }}</td>
                        <td>{{ date("d-m-Y",strtotime($data->start_date))}} <br> {{ date("d-m-Y",strtotime($data->end_date))}}</td>
                        <td>{{ $data->request_type }} <br>  @if($data->request_type=='Permission'){{date("h:i",strtotime($data->start_time))}} - {{date("h:i",strtotime($data->end_time))}}@endif</td>
                        <td>
                            <button class="listtdbtn" data-id="{{ $data->id }}" data-role='12'
                                data-bs-toggle="modal" data-bs-target="#updateLeaveApproval">
                                Update
                            </button>
                        </td>
                        <?php
                        /*

                        <td>
                            @php
                                $user = auth()->user();
                            @endphp

                            @if ($user->role_id == 12)
                                @if ($data->request_status == 'Approved')
                                    <span class="text-success">Approved</span>
                                    @if ($data->esculate_to == null)
                                        <button class="esulate_button" data-id="{{ $data->l_id }}">Escalate</button>
                                    @endif
                                @elseif ($data->request_status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                @elseif ($data->request_status == 'Pending')
                                    <button class="listtdbtn" data-id="{{ $data->l_id }}" data-role='12'
                                        data-bs-toggle="modal" data-bs-target="#updateLeaveApproval">
                                        Update
                                    </button>
                                @endif
                            @elseif ($user->role_id == 3)
                                @if ($data->esculate_status == 'Approved')
                                    <span class="text-success">Approved</span>
                                @elseif ($data->esculate_status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                @else
                                    <button class="listtdbtn" data-id="{{ $data->l_id }}" data-role='3'
                                        data-bs-toggle="modal" data-bs-target="#updateLeaveApproval">
                                        Update
                                    </button>
                                @endif
                            @endif
                        </td>

                        @if (optional($user)->role_id == 3)
                            <td>

                                @if ($data->request_status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                @elseif($data->status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                @else
                                    <span class="text-success"> {{ $data->status }}</span>
                                @endif
                            </td>
                        @endif
                        */
                        ?>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<!-- Update Approval Modal -->
<div class="modal fade" id="updateLeaveApproval" tabindex="-1" aria-labelledby="updateLeaveApprovalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateLeaveApprovalLabel">Update Leave Approval</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="updateLeaveForm">
                    @csrf
                    <input type="hidden" id="leaveId" name="id">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="sts" class="col-form-label">Status</label>
                        <select class="form-select sts" name="status" id="sts" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            @if (hasAccess($user->role_id,'leave'))
                            <option>Escalate</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-12 mb-3" id="hr" style="display:none">
                        <label for="sts" class="col-form-label">HR List</label>
                        <select class="form-select sts" name="hr"  required>
                            @foreach ($hr_list as $hr)
                                <option value="{{$hr->id}}">{{$hr->name}}</option>
                            @endforeach
                        </select>
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
    $(document).ready(function() {
        function initTable(tableId, dropdownId, filterInputId) {
            var table = $(tableId).DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "order": [0, "asc"],
                "bDestroy": true,
                "info": false,
                "responsive": true,
                "pageLength": 30,
                "dom": '<"top"f>rt<"bottom"ilp><"clear">',
            });
            $(tableId + ' thead th').each(function(index) {
                var headerText = $(this).text();
                if (headerText != "" && headerText.toLowerCase() != "action") {
                    $(dropdownId).append('<option value="' + index + '">' + headerText + '</option>');
                }
            });
            $(filterInputId).on('keyup', function() {
                var selectedColumn = $(dropdownId).val();
                if (selectedColumn !== 'All') {
                    table.column(selectedColumn).search($(this).val()).draw();
                } else {
                    table.search($(this).val()).draw();
                }
            });
            $(dropdownId).on('change', function() {
                $(filterInputId).val('');
                table.search('').columns().search('').draw();
            });
            $(filterInputId).on('keyup', function() {
                table.search($(this).val()).draw();
            });
        }
        // Initialize each table
        initTable('#table1', '#headerDropdown1', '#filterInput1');
    });
</script>

<script>
    $(document).ready(function() {
        $('.listtdbtn').on('click', function() {
            let id = $(this).data("id");
            console.log(id);
            $('#updateLeaveApproval #leaveId').val(id);
        });

        $('#updateLeaveForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this); // Create FormData object from the form

        $.ajax({
            url: '{{ route('approveleave.update') }}', // Laravel route for the POST request
            type: 'POST',
            data: formData, // Send FormData directly
            processData: false, // Don't process the data (because it's FormData)
            contentType: false, // Don't set content-type (FormData will automatically set it)
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            },
            success: function(response) {
                // alert(response.message);
                location.reload(); // Reload the page on success
            },
            error: function(xhr, status, error) {
                location.reload(); // Reload the page on success
                // alert('An error occurred: ' + error); // Show an error message
            }
        });
         });
    });

</script>

<script>
    // $(document).ready(function() {
    //     $(".esulate_button").on("click", function() {
    //         let id = $(this).data("id");
    //         console.log(id);

    //         $.ajax({
    //             url: "{{ route('update.leaveescalate') }}",
    //             type: "POST",
    //             data: {
    //                 id: id,
    //                 _token: "{{ csrf_token() }}"
    //             },
    //             success: function(response) {
    //                 alert(response.message);
    //                 location.reload();
    //             }
    //         });
    //     });
    // });

    $(document).ready(function() {
        $("#sts").on("change", function() {
            let sts = $(this).find("option:selected").val();

            if(sts=='Escalate'){
                    $('#hr').show();
            }else{
                $('#hr').hide();
            }

        });
    });
</script>
