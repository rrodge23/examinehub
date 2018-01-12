<div class="row">
    <span><b>ADD QUESTIONAIRE</b></span>
</div>

<div class="row" style="height:100%; width:100%;">
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Title</span>
            <input type="text" class="form-control use" placeholder="Enter Title" aria-describedby="basic-addon1" required="required" id="" name="questionaire_title">
        </div>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Description</span>
            <input type="text" class="form-control use" placeholder="Enter Description" aria-describedby="basic-addon1" required="required" id="" name="questionaire_description">
        </div>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Day</span>
            <input type="text" class="form-control use datepicker-date" placeholder="Select Date" aria-describedby="basic-addon1" required="required" id="" name="questionaire_date">
        </div>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Time</span>
            <input type="text" class="form-control use datepicker-time" placeholder="Select Time" aria-describedby="basic-addon1" required="required" id="" name="questionaire_time">
        </div>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Terms and Condition</span>
            <input type="text" class="form-control use mytextarea" placeholder="" aria-describedby="basic-addon1" required="required" id="" name="terms_and_condition">
        </div>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Duration</span>
            <input type="text" class="form-control use time-hours-minute-duration" placeholder="Select Duration" aria-describedby="basic-addon1" required="required" id="" name="questionaire_duration">
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs tab-nav-right tab-header" id="tab-header" role="tablist" style="margin-bottom:50px;">
                <li role="presentation" class="active" style="width:20%;" id="tab-header-add-question">
                    <a href="#tab-add-question" data-toggle="tab">
                        <span class="material-icons">add</span>
                    </a>
                </li>
            </ul>

                <!-- Tab panes -->
            <div class="tab-content" id="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab-add-question">
                    <div class="row" id="">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><div style="width:100px;float:left;text-align:right;">Question Type</div></span>
                            <select name="quesstion_type" data-placeholder="Select Category" class="chzn-select" required="required" id="">
                                <option value="1">Multiple Choice<option>
                                <option value="2">Identification<option>
                                <option value="3">Essay<option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Category Title</span>
                        <input type="text" class="form-control use" placeholder="Enter Title" aria-describedby="basic-addon1" required="required" id="" name="questionaire_type_title">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Points per Item</span>
                        <input type="text" class="form-control use number-duration" placeholder="Enter Number of Points" aria-describedby="basic-addon1" required="required" id="number-of-points" name="questionaire_points" pattern="[0-9]{10}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Number Of Items</span>
                        <input type="text" class="form-control use" placeholder="Enter Number of Items" aria-describedby="basic-addon1" required="required" id="number-of-items" name="number_of_items" pattern="[0-9]{10}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Total Items</span>
                        <input readonly="readonly" type="text" class="form-control use" aria-describedby="basic-addon1" required="required" id="" name="total_points">
                    </div>
                    <button rel='tooltip' data-original-title='Add' class='pull-right btn-add-question-type btn btn-success' type='button' name='create' onclick='return false;'>
                        <i class='material-icons'>add</i>
                    </button>
                 </div><!-- end tab panel div -->
               
             </div> <!-- end tab content div -->
        </div>
    </div>
</div>