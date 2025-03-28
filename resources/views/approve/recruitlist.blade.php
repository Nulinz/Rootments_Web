<div class="sidebodyhead mt-3">
    <h4 class="m-0">Recruitment Approval List</h4>
</div>

<div class="container-fluid mt-3 listtable">
    <div class="filter-container row mb-3">
        <div class="custom-search-container col-sm-12 col-md-8">
            <select class="form-select filter-option" id="headerDropdown5">
                <option value="All" selected>All</option>
            </select>
            <input type="text" id="filterInput5" class="form-control" placeholder=" Search">
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
        <table class="table table-hover table-striped" id="table5">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Recruit ID</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Vacant Count</th>
                    <th>Recruit Date</th>
                    <th>Request By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rec as $rc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>REC{{ $rc->id}}</td>
                    <td>{{ $rc->dept }}</td>
                    <td>{{ $rc->role }}</td>
                    <td>{{ $rc->vacancy }}</td>
                    <td>{{ date("d-m-Y",strtotime($rc->res_date))}}</td>
                    <td>{{ $rc->name }}</td>
                    <td>
                        <button class="listtdbtn" data-id="{{ $rc->id }}" data-bs-toggle="modal" data-bs-target="#updateRecruitApproval">
                            Update
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>

            </tbody>
        </table>
    </div>
</div>

<!-- Update Approval Modal -->
<div class="modal fade" id="updateRecruitApproval" tabindex="-1" aria-labelledby="updateRecruitApprovalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateRecruitApprovalLabel">Update Recruit Approval</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateRecruitForm">
                    <input type="hidden" id="RecruitId" name="RecruitId">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="sts" class="col-form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="" selected disabled>Select Options</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
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
            $(tableId + ' thead th').each(function (index) {
                var headerText = $(this).text();
                if (headerText != "" && headerText.toLowerCase() != "action") {
                    $(dropdownId).append('<option value="' + index + '">' + headerText + '</option>');
                }
            });
            $(filterInputId).on('keyup', function () {
                var selectedColumn = $(dropdownId).val();
                if (selectedColumn !== 'All') {
                    table.column(selectedColumn).search($(this).val()).draw();
                } else {
                    table.search($(this).val()).draw();
                }
            });
            $(dropdownId).on('change', function () {
                $(filterInputId).val('');
                table.search('').columns().search('').draw();
            });
            $(filterInputId).on('keyup', function () {
                table.search($(this).val()).draw();
            });
        }
        // Initialize each table
        initTable('#table5', '#headerDropdown5', '#filterInput5');
    });
</script>

<script>
    $(document).ready(function () {
        // Set RecruitId value on button click
        $('.listtdbtn').on('click', function () {
            const id = $(this).data('id');
            $('#RecruitId').val(id);
        });



        // Handle form submission
        $('#updateRecruitForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            // console.log(formData);

            $.ajax({
                url: '{{ route('approvelrecurit.update') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    location.reload(); // Refresh the page after successful update
                },
                error: function (error) {
                    alert('An error occurred. Please try again.');
                    console.error(error.responseText); // Display detailed error
                }
            });
        });
    });
</script>
