<!-- The Modal -->
<div class="modal" id="sign_up_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Join as a User</h4>
                <p>Registration for Customer</p>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id="sinupform">

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <input type="text" id="txtfirst_name" name="txtfirst_name" class="form-control" placeholder="Enter First Name" required>
                                <small class="errormsg"></small>
                            </div>

                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <input type="text" id="txtlast_name" name="txtlast_name" class="form-control" placeholder="Enter Last Name" required>
                                <small class="errormsg"></small>
                            </div>

                        </div>
                    </div>


                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <input type="text" class="form-control" required id="txtN_of_company" name="txtN_of_company" placeholder="Enter Company Name">
                                <small class="errormsg"></small>
                            </div>
                        </div>
                    </div>



                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <input type="text" id="txtPosition" name="txtPosition" class="form-control" placeholder="Position in the Company" required>
                                <small class="errormsg"></small>
                            </div>

                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <input type="text" id="txtphone" name="txtphone" onkeypress="return numbersonly(event);" class="form-control" placeholder="Phone" required>
                                <small class="errormsg"></small>
                            </div>

                        </div>
                    </div>

                    <div class="form-row">

                        <div class="col-md-12 ">
                            <div class="form-group">

                                <input type="text" class="form-control" id="txtemail" placeholder="Enter email" name="email">
                                <small class="errormsg"></small>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="col-md-12 ">
                            <div class="form-group">

                                <input type="password" class="form-control passwdd" id="txtpassword" placeholder="Create a new Password" name="txtpassword">
                                <small class="errormsg"></small>
                                <span><input type="checkbox" onclick="showpassword()">Show Password</span>
                            </div>
                        </div>
                    </div>




                </form>
            </div>



            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" onclick="customerSignUp()" class="btn btn-success">Register</button>
                <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
            </div>
        </div>


    </div>
</div>
<script>
    function showpassword() {
        var x = document.getElementById("txtpassword");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }

    function customerSignUp() {
        var ret = true;
        var txtnameofcompany = $('#txtN_of_company');
        var first_name = $('#txtfirst_name');
        var last_name = $('#txtlast_name');
        var company_name = $('#txtN_of_company');
        var position = $('#txtPosition');
        var txtphone = $('#txtphone');
        var txtemail = $('#txtemail');
        var password = $('#txtpassword');


        if ($.trim(txtnameofcompany.val()) == '') {
            ShowError(txtnameofcompany, "Please enter name of company");
            ret = false;
        } else {
            HideError(txtnameofcompany);
        }
        if ($.trim(first_name.val()) == '') {
            ShowError(first_name, "Please enter first name");
            ret = false;
        } else {
            HideError(first_name);
        }
        if ($.trim(last_name.val()) == '') {
            ShowError(last_name, "Please enter last name");
            ret = false;
        } else {
            HideError(last_name);
        }
        if ($.trim(position.val()) == '') {
            ShowError(position, "Please enter position");
            ret = false;
        } else {
            HideError(position);
        }
        if ($.trim(txtphone.val()) == '') {
            ShowError(txtphone, "Please enter phone");
            ret = false;
        } else {
            HideError(txtphone);
        }

        if ($.trim(txtemail.val()) == '') {
            ShowError(txtemail, "Please enter email");
            ret = false;
        } else {
            HideError(txtemail);
        }

        if ($.trim(password.val()) == '') {
            ShowError(password, "Please create a new password for login");
            ret = false;
        } else {
            HideError(password);
        }

        if (ret) {
            var data = {
                mode: 'REGISTRATION',
                email: txtemail.val(),
                passwd: password.val(),
                fname: first_name.val(),
                lname: last_name.val(),
                mobile: txtphone.val(),
                email: txtemail.val(),
                cname: company_name.val(),
                pname: position.val(),

            }
            $.ajax({
                url: '_customer_registration.php',
                method: 'POST',
                data: data,
                success: function(res) {
                    console.log(res);
                    if (res == 1) {
                        Swal.fire({
                            type: 'success',
                            title: 'Suceess',
                            text: 'Successfuly Registered'
                        });
                        location.reload();



                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Email already regsitered with quote master'
                        });

                    }

                },
                error: function(err) {
                    alert(err);
                }
            });

        }

        console.log(ret);

    }
</script>