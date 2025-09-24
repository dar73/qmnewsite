<nav class="navbar navbar-expand-md navbar-light bg-light py-0">
    <a class="navbar-brand" href="index.php"><img class="logo_img" src="Images/logo.png" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarsExample04">
        <div class="col-12 col-lg-8 text-right ml-auto">
            <!-- <button type="button" class="btn bg-transparent me-2 font-weight-bold" id="log-btn">Login</button>

            <button type="button" class="btn btn-outline-success" id="cust-btn"><i class="fa fa-user-circle" aria-hidden="true"></i> Join as Customer</button>
            <button type="button" class="btn btn-success" id="prof-btn"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button> -->
            <?php
            if (empty($customer_id)) { ?>
                <button type="button" class="btn btn-success" onclick="window.location.href='new_vendor.php'" id="prof-btn"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                <!-- <a class="login_button hidebutton" onclick="openSignUpModal()" href="javascript:void();">Join as Customer</a> -->
                <button type="button" class="btn btn-outline-success" onclick="openSignUpModal()" id="cust-btn"><i class="fa fa-user-circle" aria-hidden="true"></i> Join as Customer</button>
            <?php } ?>

            <?php echo $LOGINBTN; ?>

        </div>
    </div>
</nav>