<div class="sidebodyhead mt-3">
    <h4 class="m-0">Repair Approval List</h4>
</div>

<div class="container-fluid mt-3 listtable">
    <div class="filter-container row mb-3">
        <div class="custom-search-container col-sm-12 col-md-8">
            <select class="form-select filter-option" id="headerDropdown2">
                <option value="All" selected>All</option>
            </select>
            <input type="text" id="filterInput2" class="form-control" placeholder=" Search">
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
        <table class="table table-hover table-striped" id="table2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store Code</th>
                    <th>Store Name</th>
                    <th>Repair Date</th>
                    <th>Repair Description</th>
                    <th>File</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repair as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->store_code }}</td>
                        <td>{{ $data->store_name }}</td>
                        <td>{{ $data->repair_date }}</td>
                        <td>{{ $data->repair_description }}</td>
                        <td>
                            <div class="d-flex gap-3">
                                @if (!empty($data->repair_file) && file_exists(public_path($data->repair_file)))
                                    <a href="{{ asset($data->repair_file) }}" download>
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span>Nil</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if ($data->status === 'Approved')
                                <span class="text-success">Approved</span>
                            @elseif ($data->status == 'Rejected')
                                <span class="text-danger">Rejected</span>
                            @else
                                <button class="listtdbtn" data-id="{{ $data->id }}" data-bs-toggle="modal"
                                    data-bs-target="#updateRepairApproval">Update</button>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

<!-- Update Approval Modal -->
<div class="modal fade" id="updateRepairApproval" tabindex="-1" aria-labelledby="updateRepairApprovalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateRepairApprovalLabel">Update Repair Approval</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="updateRepairForm">
                    @csrf
                    <input type="hidden" id="RepairId" name="id">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="sts" class="col-form-label">Status</label>
                        <select class="form-select sts" name="status" id="sts" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
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
        initTable('#table2', '#headerDropdown2', '#filterInput2');
    });
</script>

<script>
    $(document).ready(function () {
        $('.listtdbtn').on('click', function () {
            const id = $(this).data('id');
            $('#updateRepairApproval #RepairId').val(id);
        });

        $('#updateRepairForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            console.log(formData);

            $.ajax({
                url: '{{ route('approvelrepair.update') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function (error) {
                    alert('An error occurred. Please try again.');
                    console.error(error);
                }
            });
        });
    });
</script>