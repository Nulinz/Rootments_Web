@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Completed Task List</h4>
            <div class="d-flex justify-content-around">
            </div>
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
                        <!--<a href="" id="print" data-bs-toggle="tooltip" data-bs-title="Print"><img src="{{ asset('assets/images/printer.png') }}" id="print"-->
                        <!--        alt="" height="25px"></a>-->
                        <!--<a href="" id="excel" data-bs-toggle="tooltip" data-bs-title="Excel"><img src="{{ asset('assets/images/excel.png') }}" id="excel"-->
                        <!--        alt="" height="30px"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task Title</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Priority</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->task_title }}</td>
                                <td>{{ $data->category }}</td>
                                <td>{{ $data->subcategory }}</td>
                                <td>{{ $data->priority }}</td>
                                <td>{{ date("d-m-Y",strtotime($data->start_date)) }}</td>
                                <td>{{ date("d-m-Y",strtotime($data->end_date)) }}</td>

                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('task.view', ['id' => $data->id]) }}" data-bs-toggle="tooltip"
                                            data-bs-title="View Profile"><i class="fa-solid fa-eye"></i></a>
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
        // document.getElementById("print").addEventListener("click", function(e) {
        //     e.preventDefault();

        //     var table = document.querySelector(".example");
        //     var clonedTable = table.cloneNode(true);

        //     clonedTable.querySelectorAll("tr").forEach(row => {
        //         if (row.lastElementChild) {
        //             row.removeChild(row.lastElementChild);
        //         }
        //     });

        //     var printWindow = window.open('', '', 'height=600,width=800');
        //     printWindow.document.write(`
        //     <html>
        //         <head>
        //             <title>Task Lists</title>
        //             <style>
        //                 table { width: 100%; border-collapse: collapse; }
        //                 table, th, td { border: 1px solid black; }
        //                 th, td { padding: 8px; text-align: left; }
        //             </style>
        //         </head>
        //         <body>${clonedTable.outerHTML}</body>
        //     </html>
        // `);
        //     printWindow.document.close();
        //     printWindow.focus();
        //     printWindow.print();
        //     printWindow.close();
        // });

        // document.getElementById("excel").addEventListener("click", function(e) {
        //     e.preventDefault();

        //     var table = document.querySelector(".example");
        //     var csv = [];
        //     var rows = table.querySelectorAll("tr");

        //     rows.forEach(row => {
        //         var rowData = [];
        //         var cells = Array.from(row.children);
        //         cells.slice(0, -1).forEach(cell => {
        //             rowData.push('"' + cell.textContent.trim() + '"');
        //         });
        //         csv.push(rowData.join(","));
        //     });

        //     var csvBlob = new Blob([csv.join("\n")], {
        //         type: "text/csv"
        //     });
        //     var link = document.createElement("a");
        //     link.href = URL.createObjectURL(csvBlob);
        //     link.download = "Task-List.csv";
        //     document.body.appendChild(link);
        //     link.click();
        //     document.body.removeChild(link);
        // });
    </script>
    <script>
                messaging.requestPermission()
        .then(() => {
            return messaging.getToken({ vapidKey: 'YOUR_VAPID_PUBLIC_KEY' }); // Get token for the device
        })
        .then((token) => {
            console.log('FCM Token:', token);
            // Send this token to your backend for storing and sending notifications
            saveTokenToServer(token);
        })
        .catch((err) => {
            console.log('Permission denied or error occurred:', err);
        });
    </script>
@endsection
