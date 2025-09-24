<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Leads Details';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'leads.php';
$edit_url = 'leads_edit.php';


if (isset($_GET['id'])) $txtid = $_GET['id'];
if (empty($txtid)) {
    header('location:' . $disp_url);
    exit;
}
$SCHEDULE_ARR = GetDataFromID("appointments", "iApptID", $txtid);
$ZIP_CODE = $SCHEDULE_ARR[0]->vZip;
$GET_AREA_ARRAY = array();
$_qa = "SELECT 
            z.zip_code,
            c.country_name,
            s.state_name,
            ci.city_name
        FROM 
            zip_codes z
        JOIN 
            cities ci ON z.city_id = ci.city_id
        JOIN 
            states s ON ci.state_id = s.state_id
        JOIN 
            countries c ON ci.country_id = c.country_id where  z.zip_code in ($ZIP_CODE) ";

$_qr = sql_query($_qa);
if (sql_num_rows($_qr)) {
    while ($row = sql_fetch_assoc($_qr)) {
        $zipCode = $row['zip_code'];
        $GET_AREA_ARRAY[$zipCode] = [
            'country' => $row['country_name'],
            'state' => $row['state_name'],
            'city' => $row['city_name'],
        ];
    }
}
$BOOKING_ARR = GetDataFromID("appointments", "iApptID", $txtid);
//DFA($BOOKING_ARR);
$zip = $BOOKING_ARR[0]->vZip;
$BID = $BOOKING_ARR[0]->iBookingID;

$Leads_Ans = $Leads_Ans2 = array();
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' and iQuesID not in ('8','7','5') ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5')";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        $Leads_Ans[] = $row;
    }
}
$_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3') "; // multiple choice answer
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        $Leads_Ans2[] = $row;
    }
}

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
//DFA($TIMEPICKER_ARR);
// DFA($Leads_Ans2);
// DFA($Leads_Ans);
// DFA($Question_ARR);


$customerid = $BOOKING_ARR[0]->iCustomerID;
$CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);
//DFA($Leads_Ans);
//DFA($CUSTOMERARR);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php'; ?>
    <style>
        .qmbgtheme {
            background-image: url("../Images/faded-logo-large.png");
            background-repeat: no-repeat;
            background-size: contain;
        }
    </style>
</head>
<?php include '_include_form.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'load.header.php' ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?php echo $PAGE_TITLE2 ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $PAGE_TITLE2 ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="card qmbgtheme">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $PAGE_TITLE2 ?></h3>



                    </div>
                    <div class="card-body">
                        <h4 style="color: blue;">BOOKING ID # <?php echo $BID; ?></h4>
                        <div class="row">
                            <div class="col-12 col-md-8 order-2 order-md-1 my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Lead Number</span>
                                                <span class="info-box-number text-center text-muted mb-0"><?php echo 'QM-' . $txtid; ?></span>
                                            </div>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-muted">Date of Submission</span>
                                                <span class="info-box-number text-muted mb-0"><?php echo date('l' . ', ' . 'm/d/Y' . ', ' . 'h:i A', strtotime($Leads_Ans[0]->dtAnswer)); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12">

                                        <?php
                                        if (!empty($Leads_Ans)) {
                                            for ($i = 0; $i < count($Leads_Ans); $i++) {  ?>

                                                <div class="post clearfix pb-0">
                                                    <div class="user-block">
                                                        <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                        <span class="username">
                                                            <a href="#"><?php echo $Question_ARR[$Leads_Ans[$i]->iQuesID]; ?></a>
                                                        </span>
                                                        <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p class="ml-5">
                                                        <?php echo $Ans_ARR[$Leads_Ans[$i]->iAnswerID]; ?>
                                                    </p>
                                                    <p>
                                                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                                    </p>
                                                </div>

                                        <?php    }
                                        }


                                        ?>

                                        <?php
                                        if (!empty($Leads_Ans2)) { ?>

                                            <div class="post clearfix pb-0">
                                                <div class="user-block">
                                                    <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                    <span class="username">
                                                        <a href="#"><?php echo $Question_ARR[$Leads_Ans2[0]->iQuesID]; ?></a>
                                                    </span>
                                                    <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                </div>
                                                <!-- /.user-block -->
                                                <p class="ml-5">
                                                    <?php
                                                    $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
                                                    foreach ($Ansarr as  $value) {
                                                        echo $Ans_ARR[$value] . ',';
                                                    }

                                                    ?>
                                                </p>
                                                <p>
                                                    <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                                </p>
                                            </div>
                                        <?php  }

                                        ?>





                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 order-1 order-md-2">
                                <h3 class="text-primary"><i class="fas fa-paint-brush"></i>Company Details</h3>

                                <br>
                                <div class="text-muted">
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Client Company</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vName_of_comapny; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">First Name</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vFirstname; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Last Name</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vLastname; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Position</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vPosition; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Email</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vEmail; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Phone</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vPhone; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Address</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $CUSTOMER_DET_ARR[0]->vAddress; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">zip</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo str_pad($zip, 5, '0', STR_PAD_LEFT); ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">country</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $GET_AREA_ARRAY[$ZIP_CODE]['country']; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">City</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $GET_AREA_ARRAY[$ZIP_CODE]['city']; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">State</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $GET_AREA_ARRAY[$ZIP_CODE]['state']; ?></b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Time</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12"><?php echo $TIMEPICKER_ARR[$BOOKING_ARR[0]->iAppTimeID]; ?></b>
                                    </p>

                                    <?php
                                    if (!empty($SCHEDULE_ARR)) {
                                        for ($i = 0, $j = 1; $i < count($SCHEDULE_ARR); $i++, $j++) {
                                            echo '<hr>';
                                            echo '<b class="d-block ml-md-0 col-7 col-md-12">Appointment</b>';

                                            echo date('l' . ', ' . 'm/d/Y', strtotime($SCHEDULE_ARR[0]->dDateTime));
                                        }
                                    }
                                    //DFA($SCHEDULE_ARR);

                                    ?>

                                </div>



                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?php include 'load.footer.php' ?>


    </div>
    <?php include 'load.scripts.php' ?>
    <script>
        $(document).ready(function() {
            //fetch_data();


        });
    </script>
</body>

</html>