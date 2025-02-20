@extends ('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Salary Generation List</h4>
        </div>

        <form action="" method="post" id="">
            <div class="container-fluid maindiv bg-white my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="month">Month</label>
                        <input type="month" class="form-control" name="" id="month">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="store">Store Name</label>
                        <select class="form-select store" name="store" id="store" autofocus>
                            <option value="" selected disabled>Select Stores</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="twd">Total Working Days</label>
                        <input type="number" class="form-control" name="twd" id="twd" min="0"
                            placeholder="Enter Total Working Days">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" name="sal_form" class="formbtn">Save</button>
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
                        <a id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""
                                height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>
                        <a id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""
                                height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>
                    </div>
                </div>
            </div>
            <div class="table-wrapper">
                <form action="" method="POST" id="my_form">
                    <input type="text" class="form-control" name="post_form" id="post_form"
                        placeholder="Enter Total Working Days">
                    <input type="text" class="form-control" name="post_store" id="post_form"
                        placeholder="Enter Total Working Days">
                    <table class="example table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 100px">Employee</th>
                                <th style="width: 100px">Salary</th>
                                <th>TWD</th>
                                <th>TPD</th>
                                <th>LOP</th>
                                <th>Incentives</th>
                                <th>OT</th>
                                <th>Bonus</th>
                                <th>Advance</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>EMP02 <br> Revathi</td>
                                <td>28000</td>
                                <td><input type="number" class="form-control" id="twd" name="" value="0">
                                </td>
                                <td><input type="number" class="form-control" id="tpd" name="" value="0">
                                </td>
                                <td><input type="number" class="form-control" id="lop" name="" value="0">
                                </td>
                                <td><input type="number" class="form-control" id="incentives" name=""
                                        value="0"></td>
                                <td><input type="number" class="form-control" id="ot" name="" value="0">
                                </td>
                                <td><input type="number" class="form-control" id="bonus" name=""
                                        value="0">
                                </td>
                                <td><input type="number" class="form-control" id="advance" name=""
                                        value="0">
                                </td>
                                <td><input type="number" class="form-control" id="total" name=""
                                        value="0" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                        <button type="submit" class="formbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#month').on('change', function() {
            // Trigger an AJAX request when the page is ready
            var mon = $(this).val();
            $.ajax({
                url: '{{ route('payroll.drop') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    sal_mon: mon, // Send the selected store ID
                },

                success: function(response) {
                    // console.log(response);


                    $.each(response, function(index, value) {
                        // Dynamically create an option element for each store
                        var option = $('<option></option>').attr('value', value.id + '-Store')
                            .text(value
                                .store_name + '-' + value.store_code);

                        // Append the option to the select element
                        $('#store').append(option);

                    });
                    $('#store').append('<option value="4-office">Office</option>');

                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });

        $('.store').on('change', function() {
            // Trigger an AJAX request when the page is ready
            var store = $(this).val();
            var mon = $('#month').val();

            $.ajax({
                url: '{{ route('payroll.listPerson') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    mon: mon, // Send the selected store ID
                    store: store, // Send the selected store ID
                },

                success: function(response) {
                    // console.log(response);


                    $.each(response, function(index, value) {
                        // Dynamically create an option element for each store
                        var option = $('<option></option>').attr('value', value.id + '-Store')
                            .text(value
                                .store_name + '-' + value.store_code);

                        // Append the option to the select element
                        $('#store').append(option);

                    });
                    $('#store').append('<option value="4-office">Office</option>');

                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>
    {{--
    <script>
        document.getElementById("print").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".example");
            var clonedTable = table.cloneNode(true);

            clonedTable.querySelectorAll("tr").forEach(row => {
                row.querySelectorAll("td input").forEach(input => {
                    var value = input.value;
                    var textNode = document.createTextNode(value);
                    var parent = input.parentNode;
                    parent.innerHTML = '';
                    parent.appendChild(textNode);
                });
            });

            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Payroll Lists</title>
                        <style>
                            table { width: 100%; border-collapse: collapse; }
                            table, th, td { border: 1px solid black; }
                            th, td { padding: 8px; text-align: left; }
                        </style>
                    </head>
                    <body>${clonedTable.outerHTML}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });

        document.getElementById("excel").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".example");
            var csv = [];
            var rows = table.querySelectorAll("tr");

            rows.forEach(row => {
                var rowData = [];
                var cells = Array.from(row.children);
                cells.slice(0, -1).forEach(cell => {
                    rowData.push('"' + cell.textContent.trim() + '"');
                });
                csv.push(rowData.join(","));
            });

            var csvBlob = new Blob([csv.join("\n")], {
                type: "text/csv"
            });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(csvBlob);
            link.download = "Payroll-List.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script> --}}
@endsection
