<?php
$QUESTIONS_ARR = array();
$que_q = "SELECT t1.iQuesID,t1.vQuestion,t1.cQtype,t2.vAnswer,t2.iAnsID FROM leads_question t1 JOIN leads_answer t2 ON t1.iQuesID=t2.iQuesID where t1.cStatus='A' and t2.cStatus='A' ORDER by t1.iRank,t2.iRank";
$que_q_r = sql_query($que_q, "");
if (sql_num_rows($que_q_r)) {
    while (list($iQuesID, $vQuestion, $cQtype, $vAnswer, $iAnsID) = sql_fetch_row($que_q_r)) {
        if (!isset($QUESTIONS_ARR[$iQuesID])) $QUESTIONS_ARR[$iQuesID] = array('vQuestion' => $vQuestion, 'iQuesID' => $iQuesID, 'cQtype' => $cQtype, 'OPTIONS' => array());
        $QUESTIONS_ARR[$iQuesID]['OPTIONS'][$iAnsID] = $vAnswer;
    }
}

$SCHEDULES_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
//DFA($QUESTIONS_ARR);
?>
<!-- The Modal -->
<div class="modal fade" id="GetQ_modal">
    <div class="modal-dialog modal-lg modal-md">
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
                        <input type="hidden" name="areaid" id="areaid" value="">
                        <input type="hidden" name="num_of_Q" id="num_of_Q" value="">

                        <!-- <h3 class="mb-3">Register With Quote Master:</h3> -->

                        <!-- One "tab" for each step in the form: -->

                        <?php
                        foreach ($QUESTIONS_ARR as $key => $value) {
                            if ($value['cQtype'] == 'C') {
                                echo '<div class=q' . $value['iQuesID'] . '>';
                                echo '<div class="tab">';
                                echo '<label for="">' . $value['vQuestion'] . '</label>';
                                echo Checkboxes('', 'q' . $value['iQuesID'] . '[]', $value['OPTIONS']);
                                echo '</div>';
                                echo '</div>';
                            } else if ($value['cQtype'] == 'T') {
                                echo '<div class=q' . $value['iQuesID'] . '>';
                                echo '<div class="tab">';
                                echo '<label for="">' . $value['vQuestion'] . '</label>';
                                echo '<div class="form-group">
                                            <textarea class="form-control" rows="5" name="q8" id="abt_cmp"></textarea>
                                        </div>';
                                echo '</div>';
                                echo '</div>';
                            } else {

                                echo '<div class=q' . $value['iQuesID'] . '>';
                                echo '<div class="tab">';
                                echo '<label for="">' . $value['vQuestion'] . '</label>';
                                echo FillRadios('', 'q' . $value['iQuesID'], $value['OPTIONS']);
                                echo '</div>';
                                echo '</div>';
                            }
                        }


                        ?>


                        <div class="tab">
                            <h3 class="mb-3 text-center">User Details</h3>

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
                            <h4 class="mb-3 text-center"><strong>Your APPOINMENT BOOKING Summary</strong></h4>
                            <div id="confirm_display">

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
                            <!-- <span class="step"></span> -->
                            <!-- <span class="step"></span> -->
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
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
    $(function() {
        $('#date1').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
        });
        $('.datetime').bootstrapMaterialDatePicker({
            format: 'DD/MM/YYYY HH:mm',
            minDate: new Date()
        });

    });

    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        console.log('TAB' + currentTab);
        if (n == 8 || n == 6) {

        }
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
            // alert('Your right');
            showconfirmation();
            //document.getElementById("nextBtn").innerHTML = "Submit";
            document.getElementById("nextBtn").style.display = "none";
        } else {
            document.getElementById("nextBtn").style.display = "inline";
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
            //document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    function validateForm() {

        // This function deals with validation of the form fields
        var x, y, i, valid = true,
            type;
        x = document.getElementsByClassName("tab");
        //console.log(x);
        y = x[currentTab].getElementsByTagName("input");
        if (y.length == 0) {

        } else {
            //console.log(y.length==0);
            var RA = document.querySelector(`input[name="${y[0].name}"]`).type;

            console.log('currenttab=>' + currentTab + 'name=>' + y[0].name);
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                type = y[i].type;
                console.log(type);
                if (type == 'radio') {
                    var ELE = document.querySelector(`input[name="${y[i].name}"]:checked`);
                    if (!ELE) {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false
                        valid = false;
                    }

                } else if (type == 'checkbox') {
                    var ELE = document.querySelector(`input[name="${y[i].name}"]:checked`);
                    if (!ELE) {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false
                        valid = false;
                    }
                } else if (type == 'datetime-local') {
                    if (!y[i].value) {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false:
                        valid = false;
                    }

                } else if (type == 'text' || type == 'email') {
                    if (y[i].value == "") {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false:
                        valid = false;
                    }

                }

            }

        }
        //valid = true;
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

    function changeHandler(x) {
        var selectoption = x.value;
        var selected_Elem = x;
        if (selected_Elem.name == 'q5') {
            $('.q6').remove();
            var html = '<div class="q6"><div class="tab">';
            var Num_of_Q = selected_Elem.value.slice(2, 3);
            $('#num_of_Q').val(Num_of_Q);
            console.log(Num_of_Q);
            for (let i = 0, j = 1; i < parseInt(Num_of_Q); i++, j++) {
                html += `<div class="form-group">
                    <label style="font-weight: 700 !important"> Book your date & time for booking ${j}</label>
                    <div class="d-lg-flex">
                        <input type="text" id="date${j}" name="date${j}" placeholder="Click here to set up date" class="form-control result datetime m-2">    
                    
                        <select id="Time${j}" name="Time${j}" class="form-control mb-3 m-2">
                            <option selected>Click here to set up time</option>
                            <?php
                            foreach ($SCHEDULES_ARR as $key => $value) {
                                echo '<option value=' . $key . '>' . $value . '</option>';
                            }
                            ?>

                        </select>
                    </div>         
                    </div> `;

            }
            html += '</div></div>';
            $(".q5").after(html);
            const d = new Date();
            d.setDate(d.getDate() + 50);
            $('.datetime').bootstrapMaterialDatePicker({
                format: 'MM-DD-YYYY',
                minDate: '<?php echo date("m-d-Y", strtotime("+2 day")); ?>',
                maxDate: '<?php echo date("m-d-Y", strtotime("+1 month")); ?>',
                time: false,
                shortTime: true,
                disabledDays: [6,7],
            });


        }
        console.log(selected_Elem.id);
        console.log(selectoption);
        if (selectoption == '201' || selectoption == '203' || selectoption == '204' || selectoption == '205') {
            $('.q3').empty();
            $('.q4').empty();
            $('.q7').empty();

            // $('.changeincompany').css("display", "none");
        } else if (selectoption == '202') {
            var str1 = `<?php
                        echo '<div class="tab">';
                        echo '<label for="">' . $QUESTIONS_ARR[3]['vQuestion'] . '</label>';
                        echo Checkboxes('', 'q' . $QUESTIONS_ARR[3]['iQuesID'] . '[]', $QUESTIONS_ARR[3]['OPTIONS']);
                        echo '</div>';
                        ?>`;
            var str2 = `<?php
                        echo '<div class="tab">';
                        echo '<label for="">' . $QUESTIONS_ARR[4]['vQuestion'] . '</label>';
                        echo FillRadios('', 'q' . $QUESTIONS_ARR[4]['iQuesID'], $QUESTIONS_ARR[4]['OPTIONS']);
                        echo '</div>';
                        ?>`;
            



            $('.q3').html(str1);
            $('.q4').html(str2);
            // $('.q7').html(str3);
            // $('.changeincompany').css("display", "block");
        }
    }

    function showconfirmation() {
        const FORM = document.getElementById('regForm');
        console.log($('#regForm').serialize());

        //$('#last_tab').html(str);
        $.ajax({
            url: '_showconfirmation.php',
            method: 'POST',
            data: $('#regForm').serialize(),
            success: function(res) {
                console.log(res);
                $('#confirm_display').html(res);
            }
        });



        console.log($('#regForm').serialize());


    }


    $(document).ready(function() {

    });
</script>