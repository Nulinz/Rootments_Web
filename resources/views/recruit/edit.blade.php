@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Edit Job Posting Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Job Posting Details</h4>
        </div>
        <form action="" method="post" id="">
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobid">Job ID <span>*</span></label>
                        <input type="number" class="form-control" name="jobid" id="jobid" placeholder="Enter Job ID"
                            readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtitle">Job Title <span>*</span></label>
                        <input type="text" class="form-control" name="jobtitle" id="jobtitle" placeholder="Enter Job Title"
                            autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="department">Department <span>*</span></label>
                        <select name="department" id="department" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Sales">Sales</option>
                            <option value="Finance">Finance</option>
                            <option value="Etc">Etc</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="joblocation">Job Location <span>*</span></label>
                        <input type="text" class="form-control" name="joblocation" id="joblocation"
                            placeholder="Enter Job Location">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtype">Job Type <span>*</span></label>
                        <select name="jobtype" id="jobtype" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobdesp">Job Description <span>*</span></label>
                        <textarea class="form-control" rows="1" name="jobdesp" id="jobdesp"
                            placeholder="Enter Job Description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="expreq">Experience Required (In Years) <span>*</span></label>
                        <input type="number" class="form-control" name="expreq" id="expreq" min="0"
                            placeholder="Enter Experience Required" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="slryrange">Salary Range</label>
                        <input type="number" class="form-control" name="slryrange" id="slryrange" min="0"
                            placeholder="Enter Salary Range">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="postdate">Posting Date <span>*</span></label>
                        <input type="date" class="form-control" name="postdate" id="postdate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="appdeadline">Application Deadline</label>
                        <input type="date" class="form-control" name="appdeadline" id="appdeadline">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="sts">Status <span>*</span></label>
                        <select name="sts" id="sts" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Opened">Opened</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" id="submitBtn" class="formbtn">Update</button>
            </div>
        </form>
    </div>

@endsection