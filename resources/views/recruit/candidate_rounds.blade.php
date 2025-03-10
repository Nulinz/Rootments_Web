<div class="empdetails">

    <!-- HR Table -->
    <div class="mb-3">
        <div class="sidebodyhead">
            <h4 class="m-0">HR Round</h4>
            <a data-bs-toggle="modal" data-bs-target="#addHRModal"><button class="listbtn">+ Interview</button></a>
        </div>
        <div class="mt-3 listtable">

            <div class="table-wrapper">
                <table class="table table-hover table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Processed By</th>
                            <th style="width: 400px">Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>15-03-2025</td>
                            <td>Sheik</td>
                            <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, incidunt?</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Technical Table -->
    <div class="mb-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Technical Round</h4>
            <a data-bs-toggle="modal" data-bs-target="#addTechModal"><button class="listbtn">+ Interview</button></a>
        </div>
        <div class="mt-3 listtable">

            <div class="table-wrapper">
                <table class="table table-hover table-striped" id="table2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Processed By</th>
                            <th style="width: 400px">Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>15-03-2025</td>
                            <td>Sheik</td>
                            <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, incidunt?</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Manager Table -->
    <div class="mb-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Manager Round</h4>
            <a data-bs-toggle="modal" data-bs-target="#addManagerModal"><button class="listbtn">+ Interview</button></a>
        </div>
        <div class="mt-3 listtable">

            <div class="table-wrapper">
                <table class="table table-hover table-striped" id="table3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Processed By</th>
                            <th style="width: 400px">Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>15-03-2025</td>
                            <td>Sheik</td>
                            <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, incidunt?</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add HR Modal -->
<div class="modal fade" id="addHRModal" tabindex="-1" aria-labelledby="addHRModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="addHRModalLabel">HR Interview</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="hr_itvdate">Interview Date <span>*</span></label>
                        <input type="date" class="form-control" name="hr_itvdate" id="hr_itvdate"
                            pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01" max="1999-12-31" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="hr_processedby">Processed By <span>*</span></label>
                        <select name="hr_processedby" id="hr_processedby" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="">HR Employees</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="hr_review">Review <span>*</span></label>
                        <textarea rows="3" class="form-control" name="hr_review" id="hr_review"
                            placeholder="Enter Review" required></textarea>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Tech Modal -->
<div class="modal fade" id="addTechModal" tabindex="-1" aria-labelledby="addTechModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="addTechModalLabel">Technical Interview</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="tech_itvdate">Interview Date <span>*</span></label>
                        <input type="date" class="form-control" name="tech_itvdate" id="tech_itvdate"
                            pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01" max="1999-12-31" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="tech_processedby">Processed By <span>*</span></label>
                        <select name="tech_processedby" id="tech_processedby" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="">HR Employees</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="tech_review">Review <span>*</span></label>
                        <textarea rows="3" class="form-control" name="tech_review" id="tech_review"
                            placeholder="Enter Review" required></textarea>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Manager Modal -->
<div class="modal fade" id="addManagerModal" tabindex="-1" aria-labelledby="addManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="addManagerModalLabel">Manager Interview</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="manager_itvdate">Interview Date <span>*</span></label>
                        <input type="date" class="form-control" name="manager_itvdate" id="manager_itvdate"
                            pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01" max="1999-12-31" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="manager_processedby">Processed By <span>*</span></label>
                        <select name="manager_processedby" id="manager_processedby" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="">HR Employees</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="manager_review">Review <span>*</span></label>
                        <textarea rows="3" class="form-control" name="manager_review" id="manager_review"
                            placeholder="Enter Review" required></textarea>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Save</button>
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
                "paging": false,
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
        initTable('#table1', '#headerDropdown1', '#filterInput1');
        initTable('#table2', '#headerDropdown2', '#filterInput2');
        initTable('#table3', '#headerDropdown3', '#filterInput3');
    });
</script>