@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_stages.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_strength.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_team.css') }}">

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyhead">
            <h4 class="m-0">My Dashboard</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="container-fluid px-0 mt-2 stages">
            <div class="row">

                <!-- Todo -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="todo">
                            <div class="todoct">
                                <h6 class="m-0">To Do</h6>
                            </div>
                            <div class="todono totalno" id="todo-column">
                                <h6 class="m-0 text-end"><span id="todo-count">{{ $tasks_todo_count }}</span></h6>
                            </div>
                        </div>

                        <div class="cardmain column">
                            <div class="row drag todo-list sortable-column" id="todo">

                                @foreach ($tasks_todo as $task)
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard task"
                                        data-id="{{ $task->id }}" draggable="true">
                                        <div class="taskname mb-1">
                                            <div class="tasknameleft">
                                                @if ($task->priority == 'High')
                                                    <i class="fa-solid fa-circle text-danger"></i>
                                                @elseif($task->priority == 'Low')
                                                    <i class="fa-solid fa-circle text-primary"></i>
                                                @else
                                                    <i class="fa-solid fa-circle text-warning"></i>
                                                @endif
                                                <h6 class="mb-0">{{ $task->task_title }}
                                                </h6>
                                            </div>
                                            <div class="tasknamefile">
                                                @if ($task->task_file)
                                                    <a href="{{ asset($task->task_file) }}" data-bs-toggle="tooltip"
                                                        data-bs-title="Attachment" download>
                                                        <i class="fa-solid fa-paperclip"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="taskcategory mb-1">
                                            <h6 class="mb-0"><span class="category">{{ $task->category }}</span> /
                                                <span class="subcat">{{ $task->subcategory }}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-1">
                                            <h6 class="mb-0">{{ $task->task_description }}</h6>
                                            <div class="taskdescpdiv">
                                                <h5 class="mb-0">{{ $task->assigned_by }}</h5>
                                            </div>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate">
                                                <i class="fa-regular fa-calendar"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->start_date)) }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-flag"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->end_date)) ?? 'no' }}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate">
                                                <i class="fas fa-hourglass-start"></i>&nbsp;
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $task->start_time)->format('h:i A') }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $task->end_time)->format('h:i A') }}
                                            </h6>
                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inprogress -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="inprogress">
                            <div class="inprgct">
                                <h6 class="m-0">In Progress</h6>
                            </div>
                            <div class="inprgno totalno" id="inprogress-column">
                                <h6 class="m-0 text-end"><span id="inprogress-count">{{ $tasks_inprogress_count }}</span>
                                </h6>
                            </div>
                        </div>

                        <div class="cardmain column">
                            <div class="row drag inprogress-list sortable-column" id="inprogress">

                                @foreach ($tasks_inprogress as $task)
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard task"
                                        data-id="{{ $task->id }}" draggable="true">
                                        <div class="taskname mb-1">
                                            <div class="tasknameleft">

                                                @if ($task->priority == 'High')
                                                    <i class="fa-solid fa-circle text-danger"></i>
                                                @elseif($task->priority == 'Low')
                                                    <i class="fa-solid fa-circle text-primary"></i>
                                                @else
                                                    <i class="fa-solid fa-circle text-warning"></i>
                                                @endif
                                                <h6 class="mb-0">{{ $task->task_title }}</h6>
                                            </div>
                                            <div class="tasknamefile">
                                                @if ($task->task_file)
                                                    <a href="{{ asset($task->task_file) }}" data-bs-toggle="tooltip"
                                                        data-bs-title="Attachment" download>
                                                        <i class="fa-solid fa-paperclip"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="taskcategory mb-1">
                                            <h6 class="mb-0"><span class="category">{{ $task->category }}</span>
                                                /
                                                <span class="subcat">{{ $task->subcategory }}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-1">
                                            <h6 class="mb-0">{{ $task->task_description }}</h6>
                                            <div class="taskdescpdiv">
                                                <h5 class="mb-0">{{ $task->assigned_by }}</h5>
                                            </div>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate">
                                                <i class="fa-regular fa-calendar"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->start_date)) }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-flag"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->end_date)) }}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 starttime">
                                                <i class="fas fa-hourglass-start"></i>&nbsp;
                                                {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }}
                                            </h6>
                                            <h6 class="m-0 endtime">
                                                <i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- On Hold -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="onhold">
                            <div class="onholdct">
                                <h6 class="m-0">On Hold</h6>
                            </div>
                            <div class="onholdno totalno" id="onhold-column">
                                <h6 class="m-0 text-end"><span id="onhold-count">{{ $tasks_onhold_count }}</span></h6>
                            </div>
                        </div>

                        <div class="cardmain column">
                            <div class="row drag onhold-list sortable-column" id="onhold">
                                @foreach ($tasks_onhold as $task)
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard task"
                                        data-id="{{ $task->id }}" draggable="true">
                                        <div class="taskname mb-1">
                                            <div class="tasknameleft">
                                                @if ($task->priority == 'High')
                                                    <i class="fa-solid fa-circle text-danger"></i>
                                                @elseif($task->priority == 'Low')
                                                    <i class="fa-solid fa-circle text-primary"></i>
                                                @else
                                                    <i class="fa-solid fa-circle text-warning"></i>
                                                @endif
                                                <h6 class="mb-0">{{ $task->task_title }}</h6>
                                            </div>
                                            <div class="tasknamefile">
                                                @if ($task->task_file)
                                                    <a href="{{ asset($task->task_file) }}" data-bs-toggle="tooltip"
                                                        data-bs-title="Attachment" download>
                                                        <i class="fa-solid fa-paperclip"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="taskcategory mb-1">
                                            <h6 class="mb-0"><span class="category">{{ $task->category }}</span> /
                                                <span class="subcat">{{ $task->subcategory }}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-1">
                                            <h6 class="mb-0">{{ $task->task_description }}</h6>
                                            <div class="taskdescpdiv">
                                                <h5 class="mb-0">{{ $task->assigned_by }}</h5>
                                            </div>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate">
                                                <i class="fa-regular fa-calendar"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->start_date)) }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-flag"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->end_date)) }}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate">
                                                <i class="fas fa-hourglass-start"></i>&nbsp;
                                                {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="completed">
                            <div class="completedct">
                                <h6 class="m-0">Completed</h6>
                            </div>
                            <div class="completedno totalno" id="complete-column">
                                <h6 class="m-0 text-end"><span id="complete-count">{{ $tasks_complete_count }}</span>
                                </h6>
                            </div>
                        </div>

                        <div class="cardmain column">
                            <div class="row drag complete-list sortable-column" id="complete" >

                                @foreach ($tasks_complete as $index => $task)

                                @if(($task->task_status=='Assigned')||($task->task_status=='close'))

                                <?php
                                // Add 15 days to the task's end date using Carbon
                                $end_find = date("Y-m-d",strtotime($task->end_date."+15 days"));

                                // Get the current date
                                 $current_date = date("Y-m-d");

                                // Compare the end_find date with the current date
                                // if ($end_find > $current_date) {
                                //     continue;
                                // }
                                 ?>

                                @endif
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard completedtask"
                                        data-id="{{ $task->id }}" data-patent_id="{{ $task->f_id }}"
                                        data-cat="{{ $task->category_id }}" data-status="{{ $task->task_status }}" data-subcat="{{ $task->subcategory_id }}"
                                        draggable="true">
                                        <div class="taskname mb-1">
                                            <div class="tasknameleft">
                                                @if ($task->priority == 'High')
                                                    <i class="fa-solid fa-circle text-danger"></i>
                                                @elseif($task->priority == 'Low')
                                                    <i class="fa-solid fa-circle text-primary"></i>
                                                @else
                                                    <i class="fa-solid fa-circle text-warning"></i>
                                                @endif
                                                <h6 class="mb-0">{{ $task->task_title }}</h6>
                                            </div>
                                            <div class="tasknamefile">
                                                @if ($task->task_file)
                                                    <a href="{{ asset($task->task_file) }}" data-bs-toggle="tooltip"
                                                        data-bs-title="Attachment" download>
                                                        <i class="fa-solid fa-paperclip"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="taskcategory mb-1">
                                            <h6 class="mb-0"><span class="category">{{ $task->category }}</span> /
                                                <span class="subcat">{{ $task->subcategory }}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-1">
                                            <h6 class="mb-0">{{ $task->task_description }}</h6>
                                            <div class="taskdescpdiv">
                                                <h5 class="mb-0">{{ $task->assigned_by }}</h5>
                                                <div>
                                                    {{-- @dd($task->task_status); --}}
                                                    @if($task->task_status=='Completed')
                                                    <a class="mb-0" data-bs-toggle="modal" data-bs-target="#completedModal"
                                                        id="assign"><button class="taskassignbtn">Assign</button></a>

                                                    <a class="mb-0" id="close"><button data-close="{{ $task->id }}" class="taskclosebtn">Close</button></a>
                                                    @else
                                                        @if($task->task_status=='Assigned')
                                                        <h5 class="mb-0 text-success">Assigned</h5>
                                                        @else
                                                        <h5 class="mb-0 text-danger ">Closed</h5>
                                                        @endif
                                                    @endif




                                                </div>
                                            </div>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate">
                                                <i class="fa-regular fa-calendar"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->start_date)) }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-flag"></i>&nbsp;
                                                {{ date("d-m-Y",strtotime($task->end_date)) }}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate">
                                                <i class="fas fa-hourglass-start"></i>&nbsp;
                                                {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }}
                                            </h6>
                                            <h6 class="m-0 enddate">
                                                <i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Update Assign Modal -->
    <div class="modal fade" id="completedModal" tabindex="-1" aria-labelledby="completedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="completedModalLabel">Assign Task</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row" id="taskForm" enctype="multipart/form-data">
                        <input type="hidden" name="f_id" id="f_id" value="">
                        <input type="hidden" name="task_id" id="task_id" value="">
                        <input type="hidden" name="category_id" id="cat_id" value="">
                        <input type="hidden" name="subcategory_id" id="subcat_id" value="">

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="taskname" class="col-form-label">Task Title</label>
                            <input type="text" class="form-control" name="task_title" id="taskname"
                                placeholder="Enter Task Title" required>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="assignto" class="col-form-label">Assign To</label>
                            <select class="form-select" name="assign_to" id="assignto" required>
                                <option value="" selected disabled>Select Employee</option>
                                @foreach ($employees as $us)
                                    <option value="{{ $us->id }}">{{ $us->name }} - {{ $us->role }} - {{ $us->role_dept }}
                                        @if(!empty($us->store_name))
                                        - {{ $us->store_name }}
                                        @endif</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="startdate" class="col-form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="startdate" required>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="starttime" class="col-form-label">Start Time</label>
                            <input type="time" class="form-control" name="start_time" id="starttime" required>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="enddate" class="col-form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="enddate" required>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="endtime" class="col-form-label">End Time</label>
                            <input type="time" class="form-control" name="end_time" id="endtime" required>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="priority" class="col-form-label">Priority</label>
                            <select class="form-select" name="priority" id="priority" required>
                                <option value="" selected disabled>Select Priority</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="file" class="col-form-label">File</label>
                            <input type="file" class="form-control" name="task_file" id="file">
                        </div>

                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="taskdescp" class="col-form-label">Task Description</label>
                            <textarea class="form-control" name="task_description" id="taskdescp"
                                placeholder="Enter Task Description"></textarea>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mx-auto">
                            <button type="submit" class="modalbtn">Assign</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.taskclosebtn').on('click', function(){

                var close = confirm("Are you sure you want to close the task flow ?");

                 if(close){
                    var close_id = $(this).data('close');
                    // alert(close_id);

                    $.ajax({
                        url: "{{ route('update.task') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            id: close_id,
                            status: 'Close',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                location.reload();
                            }
                        }
                    });
                 } // if closing

            })

            $('.sortable-column').each(function () {
                new Sortable(this, {
                    group: {
                        name: 'tasks',
                        pull: this.id === 'complete' ? false : true,
                        put: true
                    },
                    animation: 150,
                    ghostClass: 'blue-background-class',
                    forceFallback: true,

                    onStart: function (evt) {


                       // Create a new label to display the column name dynamically near the item being dragged
            // var columnName = evt.from.id; // The ID of the column being hovered over
            // var $columnNameLabel = $('<div class="drag-column-name">Hovering over: ' + columnName + '</div>');
            // $columnNameLabel.appendTo('body'); // Append it to the body (or to a specific container)
            // $columnNameLabel.css({
            //     position: 'absolute',
            //     top: evt.clientY + 10 + 'px',
            //     left: evt.clientX + 10 + 'px',
            //     backgroundColor: 'rgba(0, 0, 0, 0.7)',
            //     color: 'white',
            //     padding: '5px',
            //     borderRadius: '5px',
            //     fontSize: '12px',
            //     zIndex: 9999
            // });

                        // var columnId = evt.from.id;
                        // var taskStatus = $(evt.item).data('status').trim();
                        // var st_originColumn = evt.from;

                        //  console.log(taskStatus);

                        // // if (taskStatus === 'Completed' && columnId !== 'complete') {
                        // //             originColumn.appendChild(evt.item); // Move it back
                        // //             evt.preventDefault();
                        // //             alert("hello");
                        // //             return;
                        // //     }

                        // console.log(taskStatus,columnId);

                        // if(columnId === 'complete'){
                        //     evt.preventDefault(); // Prevent the drag action entirely
                        //     return;
                        // }

                        console.log('Dragging Task ID:', $(evt.item).data('id'), 'from:', evt
                            .from.id);
                    },

                    onEnd: function (evt) {
                        var taskId = $(evt.item).data('id');
                        var taskStatus = $(evt.item).data('status');
                        var originColumn = evt.from;
                        var targetColumn = evt.to;
                        var columnId = targetColumn.id;

                        if (!columnId || originColumn.id === columnId) {
                            return;
                        }



                        var newStatusMap = {
                            'todo': 'To Do',
                            'inprogress': 'In Progress',
                            'onhold': 'On Hold',
                            'complete': 'Completed'
                        };

                        var newStatus = newStatusMap[columnId] || '';
                        if (!newStatus) {
                            return;
                        }

                        if (columnId === 'complete') {
                            var confirmation = confirm(
                                "Are you sure you want to mark this task as completed?");
                            if (!confirmation) {
                                originColumn.appendChild(evt.item); // Move it back
                                return;
                            }
                        }

                         // If the task is in "Completed" status, prevent dropping it back into any other column


                        // if (columnId === 'complete') {
                        //     alert("You cannot move a completed task back.");
                        //     originColumn.appendChild(evt.item); // Move it back
                        //     return;
                        // }

                        $(evt.item).attr('data-status', newStatus);
                        $(evt.item).data('status', newStatus);

                        $.ajax({
                            url: "{{ route('update.task') }}",
                            type: "POST",
                            dataType: "json",
                            data: {
                                id: taskId,
                                status: newStatus,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    location.reload();
                                    $('#todo-count').text(response.taskCounts.todo);
                                    $('#inprogress-count').text(response.taskCounts
                                        .inprogress);
                                    $('#onhold-count').text(response.taskCounts
                                        .onhold);
                                    $('#complete-count').text(response.taskCounts
                                        .complete);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".completedtask").forEach(task => {
                task.addEventListener("click", function () {
                    let taskId = this.getAttribute("data-patent_id");

                    let catId = this.getAttribute("data-cat");

                    let subcatId = this.getAttribute("data-subcat");

                    document.getElementById("f_id").value = taskId;

                    document.getElementById("cat_id").value = catId;

                    document.getElementById("subcat_id").value = subcatId;

                    document.getElementById("task_id").value = this.getAttribute("data-id");;
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#taskForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('completedtaskstore') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        alert(response.message);
                        window.location.href = "{{ route('mydash.dashboard') }}";
                    },
                    error: function (xhr) {
                        alert('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endsection
