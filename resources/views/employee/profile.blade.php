@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

@section('content')

<div class="sidebodydiv px-5 mb-4">
    <div class="sidebodyback my-3" onclick="goBack()">
        <div class="backhead">
            <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
            <h6 class="m-0">Employee Profile</h6>
        </div>
    </div>

    <div class="mainbdy">

        <!-- Left Content -->
        <div class="contentleft">
            <div class="cards mt-2">

                <div class="basicdetails mb-2">
                    <div class="maincard row">
                        <div class="cardshead">
                            <div class="col-12 cardsh5">
                                <h5 class="mb-0">Basic Details</h5>
                            </div>
                        </div>
                        <div
                            class="col-sm-12 col-md-12 col-xl-12 mb-3 d-flex justify-content-center align-items-center">
                            @if($users->profile_image)
                                <img src="{{ asset($users->profile_image) }}" width="125px" height="125px" alt="" class="profileimg">
                            @else
                                <img src="{{ asset('assets/images/avatar.png') }}" width="125px" height="125px" alt="" class="profileimg">
                            @endif
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Employee Code</h6>
                                <h5 class="mb-0">{{ $users->emp_code }}</h5>
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Full Name</h6>
                                <h5 class="mb-0">{{ $users->name }}</h5>
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Email ID</h6>
                                <h5 class="mb-0">{{ $users->email }}</h5>
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Contact Number</h6>
                                <h5 class="mb-0">{{ $users->contact_no }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="assigndetails mb-2">
                    <div class="maincard row">
                        <div class="cardshead">
                            <div class="col-12 cardsh5">
                                <h5 class="mb-0">Assigned Details</h5>
                            </div>
                            <a class="editicon" href="{{ route('employee.jobedit', ['id' => $users->id]) }}"
                                data-bs-toggle="tooltip" data-bs-title="Edit Assign">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Assigned Store</h6>
                                @if($users->store_name)
                                <h5 class="mb-0">{{ $users->store_name }}</h5>
                                @else
                                <h5 class="mb-0">-</h5>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Department</h6>
                                <h5 class="mb-0">{{ $users->role_dept }}</h5>
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <h6 class="mb-1">Assigned Role</h6>
                                <h5 class="mb-0">{{ $users->role }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- Right Content -->
        <div class="contentright">
            <div class="proftabs">
                <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 gap-xl-3 border-0"
                    id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="profiletabs active"
                            data-url="{{ route('employee.details', ['id' => $users->id]) }}" id="details-tab" role="tab"
                            data-bs-toggle="tab" type="button" data-bs-target="#details" aria-controls="details"
                            aria-selected="true">Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="profiletabs" data-url="{{ route('employee.salary', ['id' => $users->id]) }}"
                            id="salary-tab" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#salary"
                            aria-controls="salary" aria-selected="false">Salary</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="profiletabs" data-url="{{ route('employee.remark', ['id' => $users->id]) }}"
                            id="remarks-tab" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#remarks"
                            aria-controls="remarks" aria-selected="false">Remarks</button>
                    </li>
                </ul>
            </div>

            <div class="ps-3 tab-content" id="tabContentWrapper">
                <p>Loading...</p>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {

        const loadContent = (url) => {
            $("#tabContentWrapper").html('<p>Loading...</p>');
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $("#tabContentWrapper").html(data);
                },
                error: function () {
                    $("#tabContentWrapper").html("<p>Error loading content</p>");
                }
            });
        };

        $(".profiletabs").on("click", function () {
            $(".profiletabs").removeClass("active");
            $(this).addClass("active");

            const url = $(this).data("url");
            loadContent(url);
        });

        $(".profiletabs.active").trigger("click");
    });
</script>

@endsection