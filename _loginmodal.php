<!-- The Modal -->
<div class="modal" id="loginmodal">
    <div class="modal-dialog">
        <div class="modal-content p-4">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title login_closea">Login</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <label for="Error"></label>
            <div class="onediv">
                <h3 class="createpass_text mb-3 text-center">Login</h3>
                <h3 class="access_our">Access to our Quote Master</h3>
                <form class="form_elements" action="">
                    <div id="LBL_INFO"></div>
                    <div class="col-sm-12 d-inline-flex">
                        <div class="form-check pb-3">
                            <input class="form-check-input usertype" type="radio" name="usertype" value="3" checked>
                            <label class="" for="radioCust">Login as Customer</label>
                        </div>
                        <hr>
                        <div class="form-check pb-3">
                            <input class="form-check-input usertype" type="radio" name="usertype" value="2">
                            <label class="" for="radioProv">Login as Provider</label>
                        </div>
                    </div>
                    <!-- <label for="email">Enter your email:</label> -->
                    <input type="email" id="txtEmail" name="txtEmail" placeholder="Enter Email" required><br>
                    <!-- <label for="password">Password</label> -->
                    <div class="input_eye">
                        <input type="password" id="txtPasswd" name="txtPasswd" placeholder="Enter Password" required><br>
                        <i class="fa fa-eye" onclick="showpassword1()" aria-hidden="true"><input id="checkbo" type="checkbox"></i>
                    </div>


                    <button class="login_but" onclick="customerLogin()" type="button">Login</button>
                </form>

                <label class="or_label" for="or">OR</label>

                <div class="twobutton_login">
                    <button class="join_Provider" onclick="window.location.href='new_vendor.php'">Join as Provider</button>
                    <button class="join_Provider" onclick="openSignUpModal()">Join as Customer</button>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
            </div>

        </div>
    </div>
</div>
<script>
    //  trigger_eye = ()=>{
    //      var elem1=document.getElementById("checkbo");
    //      $(elem1).prop("checked", true);

    // }

    function showpassword1() {
        var x = document.getElementById("txtPasswd");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }

    function customerLogin() {
        var ret = true;
        //getting form data
        var Email = $('#txtEmail');
        var passwd = $('#txtPasswd');
        var usertype = $(this).find('.usertype');
        console.log($('.usertype:checked').val());
        console.log(Email);
        console.log(passwd);

        // validating form data
        if ($.trim(Email.val()) == '') {
            ShowError(Email, "Please enter your email");
            ret = false;
        } else {
            HideError(Email);
        }

        if ($.trim(passwd.val()) == '') {
            ShowError(passwd, "Please enter your password");
            ret = false;
        } else {
            HideError(passwd);
        }

        if (!($('.usertype').is(':checked'))) {
            //alert('Please select the user type');
            $('#LBL_INFO').html(NotifyThis('Please select the usertype?', 'error'));
            err++;

        }

        if (ret) {
            var data = {
                mode: 'LOGIN',
                email: Email.val(),
                passwd: passwd.val(),
                usertype: $('.usertype:checked').val()

            }
            $.ajax({
                url: '_customer_registration.php',
                method: 'POST',
                data: data,
                success: function(res) {
                    console.log(res);
                    //alert(res);
                    //location.reload();
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
                            text: 'Email address or password does not match '
                        });

                    }

                },
                error: function(err) {
                    alert(err);
                }
            });

        }

    }
</script>