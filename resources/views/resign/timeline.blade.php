<div class="sidebodyhead mb-3">
    <h4 class="m-0">Formalities</h4>
    <a data-bs-toggle="modal" data-bs-target="#updateModal"><button class="listbtn">+ Add Formalities</button></a>
</div>

<div class="container maindiv pt-3" style="height: 490px" id="timelinecards">
    <div class="timeline">

        <div class="entry completed">
            <div class="title">
                <h3>Handover</h3>
                <h6 class="text-success">Completed</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft">
                        <h6 class="mb-0">Team Coordinator</h6>
                    </div>
                    <div class="tasknamefile">
                        <a href="" data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                class="fa-solid fa-paperclip"></i></a>
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">Discuss staff responsibilities during the upcoming
                        holiday rush.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        12/12/2024</h6>
                    </h6>
                </div>
            </div>
        </div>

        <div class="entry pending">
            <div class="title">
                <h3>Exit Formalities</h3>
                <h6 class="text-success">Completed</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft">
                        <h6 class="mb-0">Team Coordinator</h6>
                    </div>
                    <div class="tasknamefile">
                        <a href="" data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                class="fa-solid fa-paperclip"></i></a>
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">Discuss staff responsibilities during the upcoming
                        holiday rush.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        12/12/2024</h6>
                    </h6>
                </div>
            </div>
        </div>

        <div class="entry">
            <div class="title">
                <h3>Final Settlements</h3>
                <h6 class="text-success">Completed</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft">
                        <h6 class="mb-0">Team Coordinator</h6>
                    </div>
                    <div class="tasknamefile">
                        <a href="" data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                class="fa-solid fa-paperclip"></i></a>
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">Discuss staff responsibilities during the upcoming
                        holiday rush.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        12/12/2024</h6>
                    </h6>
                </div>
            </div>
        </div>

        <div class="entry">
            <div class="title">
                <h3>Experience Certificate</h3>
                <h6 class="text-danger">Pending</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft">
                        <h6 class="mb-0">Update Now</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="entry">
            <div class="title">
                <h3>Termination</h3>
                <h6 class="text-danger">Pending</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft">
                        <h6 class="mb-0">Update Now</h6>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateModalLabel">Update Formalities</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="formalities">Formalities <span>*</span></label>
                        <select name="formalities" id="formalities" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Handover">Handover</option>
                            <option value="Exit Formalities">Exit Formalities</option>
                            <option value="Final Settlements">Final Settlements</option>
                            <option value="Experience Certificate">Experience Certificate</option>
                            <option value="Termination">Termination</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="review">Review <span>*</span></label>
                        <textarea rows="3" class="form-control" name="review" id="review" placeholder="Enter Review"
                            required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="attachment">Attachment</label>
                        <input type="file" class="form-control" name="attachment" id="attachment">
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>