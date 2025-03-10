@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Job Posting List</h4>
            <a href="{{ route('recruit.add') }}"><button class="listbtn">+ Add Job Posting</button></a>
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
                            <th>Recruit ID</th>
                            <th>Job ID</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Experience</th>
                            <th>Location</th>
                            <th>Salary Range</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>REC01</td>
                            <td>JOB01</td>
                            <td>1</td>
                            <td>HR</td>
                            <td>Manager</td>
                            <td>2 years</td>
                            <td>Salem</td>
                            <td>15,000</td>
                            <td>Opened</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="listtdbtn" data-bs-toggle="modal"
                                        data-bs-target="#updateRecruitApproval">
                                        Update
                                    </button>
                                    <a href="{{ route('recruit.profile') }}" data-bs-toggle="tooltip"
                                        data-bs-title="View Profile">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('recruit.edit') }}" data-bs-toggle="tooltip" data-bs-title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Update Approval Modal -->
    <div class="modal fade" id="updateRecruitApproval" tabindex="-1" aria-labelledby="updateRecruitApprovalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateRecruitApprovalLabel">Update Job Posting</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRecruitForm">
                        <input type="hidden" id="RecruitId" name="id">
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

@endsection