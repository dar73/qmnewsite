<?php
$QUESTIONS_ARR = array();
$que_q = "SELECT t1.iQuesID,t1.vQuestion,t1.cQtype,t2.vAnswer,t2.iAnsID FROM leads_question t1 JOIN leads_answer t2 ON t1.iQuesID=t2.iQuesID ORDER by t1.iRank;";
$que_q_r = sql_query($que_q, "");
if (sql_num_rows($que_q_r)) {
    while (list($iQuesID, $vQuestion, $cQtype, $vAnswer, $iAnsID) = sql_fetch_row($que_q_r)) {
        if (!isset($QUESTIONS_ARR[$iQuesID])) $QUESTIONS_ARR[$iQuesID] = array('vQuestion' => $vQuestion, 'iQuesID' => $iQuesID, 'cQtype' => $cQtype, 'OPTIONS' => array());
        array_push($QUESTIONS_ARR[$iQuesID]['OPTIONS'], array('iAnsID' => $iAnsID, 'vAnswer' => $vAnswer, 'iQuesID' => $iQuesID));
    }
}
DFA($QUESTIONS_ARR);
?>
<!-- The Modal -->
<div class="modal" id="GetQ_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Get Quotes</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->

            <div class="modal-body">
                <div class="col-md-12">
                    <form id="regForm" action="send_quote.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="schedule" id="" value="">
                        <input type="hidden" name="areaid" id="areaid" value="">

                        <!-- <h1 class="mb-3">Register With Quote Master:</h1> -->

                        <!-- One "tab" for each step in the form: -->


                        <div class="tab">
                            <h1 class="mb-3 text-center">Service Details</h1>

                            <div class="form-group mb-4">
                                <label for="">How Often Do You Want Service Per Week ?</label>
                                <select name="how_often" id="how_often" class="form-control form-control-sm">
                                    <option value="">---Please select---</option>
                                    <option value="1x month">1x month</option>
                                    <option value="2x month">2x month</option>
                                    <option value="1x">1x</option>
                                    <option value="2x">2x</option>
                                    <option value="3x">3x</option>
                                    <option value="4x">4x</option>
                                    <option value="5x">5x</option>
                                    <option value="6x">6x</option>
                                    <option value="7x">7x</option>
                                </select>
                                <small class="errormsg"></small>
                            </div>

                            <div class="form-group mb-4">
                                <label style="font-weight: 700 !important" for="selectbox">What describes your current cleaning situation?</label>

                                <select name="cleaning_situation" id="cleaning_situation" class="form-control form-control-sm">
                                    <option value="">---Please select---</option>
                                    <option value="1">In house - we clean our own office, but looking to hire a cleaner</option>
                                    <option value="2">Outsourced - we pay a cleaning company</option>
                                    <option value="3">We are new company - we are looking for a cleaner</option>
                                </select>
                                <small class="errormsg"></small>
                            </div>

                            <div class="div_hidden">



                                <div class="form-group">
                                    <label style="font-weight: 700 !important" for="selectbox">Current cleaners are?</label><br>
                                </div>


                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" class="form-check-input CC" name="cleaning_cleaners[]" value="1">Not dusting well
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">

                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="2"> Not cleaning restrooms well
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="3">
                                        Missing some obvious things
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="4">
                                        Not following their cleaning schedule
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="5">
                                        I am doing some comparison shopping
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="6">
                                        The entryway could look better
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="7">
                                        They are missing trash in some offices
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="8">
                                        They may be or are retiring soon / moving away
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="9">
                                        Not showing up when scheduled
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="check2">
                                        <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input CC" value="10">
                                        Looking for a back-up in case I need one
                                    </label>
                                </div>



                                <small class="errormsg"></small>


                                <div class="form-group mt-4 mb-4">

                                    <label style="font-weight: 700 !important" for="selectbox">Current rating 1-5?</label>
                                    <select name="rating" id="rating" class="form-control form-control-sm">
                                        <option value="">---Please select---</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <small class="errormsg"></small>
                                </div>

                            </div>

                        </div>

                        <div class="tab">
                            <h1 class="mb-3 text-center">Schedule Your Meeting</h1>
                            <div class="form-group mb-4">
                                <label style="font-weight: 700 !important" for="selectbox">How many
                                    quotes do you need?</label>
                                <select name="No_of_quotes" id="No_of_quotes" class="form-control">
                                    <option value="">---Please select---</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>

                                </select>
                                <small class="errormsg"></small>
                            </div>

                            <div class="form-group mb-4">
                                <label style="font-weight: 700 !important" for="selectbox">Do you
                                    want to self schedule?</label>
                                <select name="self_schedule" id="self_schedule" class="form-control">

                                    <option value="">Please select Your choice</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>

                                </select>
                                <small class="errormsg"></small>
                            </div>
                            <div class="schedule_date">

                            </div>

                            <div class="form-group changeincompany mb-4">
                                <label for="">Are you looking to change your current company?</label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input " value="yes" name="change_in_company">Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input " value="no" name="change_in_company">No
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="tab">
                            <h1 class="mb-3 text-center">User Details</h1>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <input type="text" class="form-control" required id="name_of_company" name="name_of_company" value="" placeholder="Enter Company Name">
                                        <small class="errormsg"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter First Name" value="" required>
                                        <small class="errormsg"></small>
                                    </div>

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <input type="text" id="last_name" name="last_name" value="" class="form-control" placeholder="Enter Last Name" required>
                                        <small class="errormsg"></small>
                                    </div>

                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <input type="text" id="position" value="" name="position" class="form-control" placeholder="Position in the Company" required>
                                        <small class="errormsg"></small>
                                    </div>

                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <input type="text" id="phone" name="phone" value="" class="form-control" placeholder="Phone" required>
                                        <small class="errormsg"></small>
                                    </div>

                                </div>
                            </div>

                            <div class="form-row">

                                <div class="col-md-12 ">
                                    <div class="form-group">

                                        <input type="text" class="form-control" id="email" placeholder="Enter email" name="email" value="">
                                        <small class="errormsg"></small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab" id="last_tab">
                            <h2 class="mb-3 text-center"><strong>Below is the summary of your selection</strong></h2>

                            <table class="table" id="confirmation_page">

                                <tbody>
                                    <!-- <tr>
                                                <td>John</td>
                                                <td>Doe</td>
                                                <td>john@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>Mary</td>
                                                <td>Moe</td>
                                                <td>mary@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>July</td>
                                                <td>Dooley</td>
                                                <td>july@example.com</td>
                                            </tr> -->
                                </tbody>
                            </table>
                            <p><strong>Please review and confirm your Details</strong></p>
                            <div class="text-left">

                                <button type="button" id="confirm_booking" class="btn btn-success">Yes</button>
                                <button type="button" id="btnclose" class="btn btn-danger">No</button>
                            </div>
                        </div>


                        <div class="div_onea mt-5" style="overflow:auto;">
                            <div class="div_twoa" style="float:right;">
                                <button type="button" id="prevBtn" class="btn btn-info" onclick="nextPrev(-1)">Back</button>
                                <button type="button" class="btn" id="nextBtn" onclick="nextPrev(1)">Next</button>
                            </div>
                        </div>

                        <!-- Circles which indicates the steps of the form: -->
                        <div style="text-align:center;margin-top:40px;">
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                        </div>

                    </form>

                </div>
            </div>



            <!-- Modal footer -->
            <div class="modal-footer">
                <!-- <button type="button" onclick="customerSignUp()" class="btn btn-success">Register</button> -->
                <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
            </div>
        </div>


    </div>
</div>
<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        //... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        //... and run a function that will display the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form...
        if (currentTab >= x.length) {
            // ... the form gets submitted:
            document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class on the current step:
        x[n].className += " active";
    }

    $(document).ready(function() {
        $(document).on('change', '#cleaning_situation', function() {
            var clstatus = $('#cleaning_situation').val();
            if (clstatus == '1' || clstatus == '3') {
                $('.div_hidden').empty();
                $('.changeincompany').css("display", "none");
            } else {
                $('.div_hidden').html(`

                                        <div class="form-group">
                                            <label style="font-weight: 700 !important" for="selectbox">Current cleaners are?</label><br>
                                        </div>


                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" class="form-check-input" name="cleaning_cleaners[]" value="1">Not dusting well
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">

                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="2"> Not cleaning restrooms well
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="3">
                                                Missing some obvious things
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="4">
                                                Not following their cleaning schedule
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cl-*eaners[]" class="form-check-input" value="5">
                                                I am doing some comparison shopping
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="6">
                                                The entryway could look better
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="7">
                                                They are missing trash in some offices
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="8">
                                                They may be or are retiring soon / moving away
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="9">
                                                Not showing up when scheduled
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="check2">
                                                <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="10">
                                                Looking for a back-up in case I need one
                                            </label>
                                        </div>



                                        <small class="errormsg"></small>


                                        <div class="form-group">

                                            <label style="font-weight: 700 !important" for="selectbox">Current rating 1-5?</label>
                                            <select name="rating" id="rating" class="form-control form-control-sm">
                                                <option value="">---Please select---</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                            <small class="errormsg"></small>
                                        </div>`);
                $('.changeincompany').css("display", "block");
            }

        });
    });
</script>