<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Credits';
$TITLE = SITE_NAME . ' | ' . $page_title;
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.link.php'; ?>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->
        <!-- /.content-wrapper -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container py-2">
                    <!-- <div class="row justify-content-lg-end">
            <div class="col col-lg-8 col-12 text-right">
                <img class="logo_banner_image mt-sm-5 mt-3 mr-md-5" src="Images/logo.png" alt="">
            </div>
        </div> -->

                    <h4>Refund Policy</h4>

                    <!-- <p>The Fee is non-refundable unless Consumer cancels any scheduled appointment with Service Provider. Also, if customer requests for a reschedule and offers a different date and time than what was previously requested and accepted by the Service Provider, and if the Service Provider is not able to accommodate the new suggested date and time, the Service Provider will be offered a credit for the payment made for the lead. Finally, if an “Act of God” such as earthquake, tornado, hurricane or any natural disaster causes appointment to be cancelled and a new meeting time is found by the consumer, but the service provider is unable to attend then a credit will be awarded.If you attempt to contact the customer before the scheduled meeting you will not receive credit no matter the outcome of your contact.</p> -->
                    <ul>
                        <li>The Fee is non-refundable unless Consumer cancels any scheduled appointment with Service Provider.</li>
                        <li> Also, if customer requests for a reschedule and offers a different date and time than what was previously requested and accepted by the Service Provider, and if the Service Provider is not able to accommodate the new suggested date and time, the Service Provider will be offered a credit for the payment made for the lead.</li>
                        <li>If you attempt to contact the customer before the scheduled meeting you will not receive credit no matter the outcome of your contact.</li>
                        <li>Finally, if an “Act of God” such as earthquake, tornado, hurricane or any natural disaster causes appointment to be cancelled and a new meeting time is found by the consumer, but the service provider is unable to attend then a credit will be awarded.</li>
                    </ul>





                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->



        <!-- Main Footer -->

        <?php include 'footer.php'; ?>
    </div>
    <?php include 'load.scripts.php'; ?>
</body>

</html>