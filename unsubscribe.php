<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Service Provider Details';
$disp_url = 'index.php';
$TITLE = SITE_NAME . ' | ' . $page_title;
$SPID = (isset($_GET['p'])) ? $_GET['p'] : '0';
$BID = (isset($_GET['b'])) ? $_GET['b'] : '0';
$ch = (isset($_GET['ch'])) ? $_GET['ch'] : '0';
if (empty($SPID) && empty($BID)) {
    header('location: ' . $disp_url);
}

$SPID = DecodeParam($SPID);
$BID = DecodeParam($BID);
$dataArr = GetDataFromID("service_providers", "id", $SPID);
if (empty($dataArr)) {
    header("location: $disp_url");
    exit;
}

$First_name = db_output2($dataArr[0]->First_name);
$Last_name = db_output2($dataArr[0]->Last_name);

// DFA($dataArr);
// exit;

?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.link.php'; ?>
    <style>
        .SP_card i.fa {
            color: #315292;
        }

        .welcome_container {
            background: #fff;
        }

        .bg-license,
        .bg-insurance,
        .bg-arrive {
            background: #315292;
        }

        .bg-license h4,
        .bg-insurance h4,
        .bg-arrive h4 {
            color: #fff300;
        }

        .small-box.bg-license,
        .bg-arrive {
            margin-right: 40%;
        }

        .bg-insurance {
            margin-left: 40%;
        }

        h2.cost {
            font-size: 50px;
            background: #315292;
            padding: 20px;
            width: fit-content;
            border-radius: 5px;
            color: #fff300;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card card-primary">

                                <!-- /.card-header -->
                                <!-- form start -->

                                <div class="card-body">
                                    <h3>Hello <?php echo $First_name; ?>,</h3>
                                    <?php
                                    if ($ch == 'N') {
                                        $_q = "SELECT  * FROM unsubscribe_leads WHERE 1 and  iSPID='$SPID' and  iBookingID='$BID' and cStatus!='X' ";
                                        $_r = sql_query($_q);
                                        if (sql_num_rows($_r)) { ?>
                                            <p>Thank you for the feedback, you will not receive notification for this lead.</p>
                                        <?php } else {
                                            LockTable('unsubscribe_leads');
                                            $UsubID = NextID('iUsubID', 'unsubscribe_leads');
                                            $_q2 = "INSERT INTO unsubscribe_leads(iUsubID, iSPID, iBookingID, dDate, cStatus) VALUES ('$UsubID','$SPID','$BID',NOW(),'A')";
                                            sql_query($_q2);
                                            UnlockTable();
                                        ?>
                                            <p>Thank you for the feedback, you will not receive notification for this lead.</p>
                                        <?php
                                        }
                                    } elseif ($ch == 'Y') { ?>
                                        <p>Thanks for your feedback. </p>
                                    <?php } ?>


                                </div>
                                <!-- /.card-body -->


                            </div>

                        </div>

                    </div>
                </div><!-- /.container -->
            </section>

            <?php include 'footer.php'; ?>
        </div>
        <?php include 'load.scripts.php'; ?>
</body>

</html>