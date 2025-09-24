<!-- The Modal -->
<div class="modal" id="loginmodal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4>Login</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <label for="Error"></label>
            <div class="onediv">
                <h3 class="mabile_num">Access to our Quote Master</h3>
                <form class="form_elements" action="">
                    <label for="email">Enter your email:</label>
                    <input type="email" id="txtEmail" name="txtEmail" placeholder="Enter Email" required><br><br>
                    <label for="password">Password</label>
                    <input type="password" id="txtPasswd" name="txtPasswd" placeholder="Enter Password" required><br>
                    <input type="checkbox" onclick="showpassword1()">Show Password<br><br>

                    <button type="button" onclick="customerLogin()">Login</button>
                </form>

                <label class="or_label" for="or">OR</label>

                <div class="twobutton_login">
                    <button class="join_Provider" onclick="window.location.href='new_vendor.php'">Join as Provider</button>
                    <button class="join_Provider" onclick="openSignUpModal()">Join as User</button>
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

        if (ret) {
            var data = {
                mode: 'LOGIN',
                email: Email.val(),
                passwd: passwd.val(),

            }
            $.ajax({
                url: '_customer_registration.php',
                method: 'POST',
                data: data,
                success: function(res) {
                    if (res == 1) {
                        Swal.fire({
                            type: 'success',
                            title: 'Suceess',
                            text: 'Successfuly Logged In'
                        });
                        location.reload();



                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Not Registered with Quote master'
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