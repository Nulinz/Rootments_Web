<div class="sidebodyhead mb-3">
    <h4 class="m-0">Formalities</h4>
    <a data-bs-toggle="modal" data-bs-target="#updateModal"><button class="listbtn">+ Add Formalities</button></a>
</div>

<div class="container maindiv pt-3" style="height: 490px" id="timelinecards">
    <div class="timeline">

        @foreach ($for_list as $fl)


        <div class="entry completed">
            <div class="title">
                <h3>{{ $fl->formality }}</h3>
                <h6 class="text-success">{{ $fl->status }}</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft">
                        <h6 class="mb-0">{{ $fl->name }}</h6>
                    </div>
                    <div class="tasknamefile">
                        @if(!is_null($fl->file))
                        <a href="" data-bs-toggle="tooltip" data-bs-title="Attachment" download="{{ basename($fl->file)}}"><i
                                class="fa-solid fa-paperclip"></i></a>
                        @endif
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">{{ $fl->review }}.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        {{ date("d-m-Y",strtotime($fl->created_at)) }}</h6>
                    </h6>
                </div>
            </div>
        </div>

        @endforeach


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
                <form action="{{ route('resign.formality') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input hidden type="text" name="res_id" value="{{ $pro->res_id }}">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="formalities">Formalities <span>*</span></label>
                        <select name="formal_type" id="formalities" class="form-select" required>
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
