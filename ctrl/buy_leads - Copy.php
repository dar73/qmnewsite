<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Buy Leads';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'buy_leads.php';
$edit_url = 'buy_leads.php';

$execute_query = $is_query = true;
$txtFromD = $txtFromT = $cond = $params = $params2 = '';
$srch_style = 'display:none;';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_SESSION[PROJ_SESSION_ID]->user_id)) $txtid = $_SESSION[PROJ_SESSION_ID]->user_id;
else $mode = 'E';

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtFromD = $_POST['txtFromD'];
    $txtFromT = $_POST['txtFromT'];
    $params = '&txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['txtFromD'])) $txtFromD = $_GET['txtFromD'];
    if (isset($_GET['txtFromT'])) $txtFromT = $_GET['txtFromT'];
    $params2 = '?txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT;
}

if (!empty($txtFromD)) {
    $cond .= " and dDate>='$txtFromD' ";
    $execute_query = true;
}
if (!empty($txtFromT)) {
    $cond .= " and dDate<='$txtFromT' ";
    $execute_query = true;
}

if (!empty($cond)) $srch_style = '';

$q = "select * from booking where 1 and cService_status='P' " . $cond;
$r = sql_query($q);


$CUSTOMER_ARR =array();
$_q_c= "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r=sql_query($_q_c,'');
while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone)=sql_fetch_row($_qc_r)) {
    if (!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone'=> $vPhone);

    
}
//DFA($CUSTOMER_ARR);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
    <style>
        .show_data {
            display: none;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'load.header.php' ?>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?php echo $PAGE_TITLE2; ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Buy Leads</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">

                <!-- Default box -->
                <div class="card card-solid">
                    <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
                        <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                            <div class="app-page-title2">
                                <div class="page-title-wrapper">
                                    <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                        <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="date" name="txtFromD" id="txtFromD" value="<?php echo $txtFromD; ?>" placeholder="Keywords" class="form-control" />
                                        </div>
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="date" name="txtFromT" id="txtFromT" value="<?php echo $txtFromT; ?>" placeholder="Keywords" class="form-control" />
                                        </div>

                                        <div class="page-title-actions mb-2" style="width:100%;">
                                            <div class="d-inline-block dropdown">
                                                <button type="submit" class="btn btn-warning"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-danger" onClick="GoToPage('<?php echo $disp_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-times fa-w-20"></i> </span> Reset </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"> <i class="header-icon pe-7s-culture mr-3 text-muted opacity-6"> </i></div>
                        <div class="btn-actions-pane-right actions-icon-btn float-right">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info" onClick="ToggleVisibility('SEARCH_RECORDS');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                            <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php echo $edit_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Add New </button> -->
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row d-flex align-items-stretch">
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                <div class="card bg-light">
                                    <div class="card-header text-muted border-bottom-0">
                                        company name
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-8">
                                                <h2 class="lead"><b><span>First</span><span> Last</span></b></h2>
                                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                                    <li class="small mb-2"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>Address: Demo Street 123, Demo City 04312, NJ</li>
                                                    <li class="small mb-2"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                                        <span class="hide_data">XXXXXXXXX</span>
                                                        <span class="show_data">Phone #: + 800 - 12 12 23 52</span>
                                                    </li>
                                                    <li class="small mb-2"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span>
                                                        <span class="hide_data">XXXXXXXXX</span>
                                                        <span class="show_data">Email: company@gmail.com</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-4 text-center">
                                                <img src="../Images/user/user_admin.png" alt="" class="img-circle img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-right">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="buy_lead">
                                                <i class="fa fa-shopping-cart"></i> Buy Lead
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <nav aria-label="Contacts Page Navigation">
                            <ul class="pagination justify-content-center m-0">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item"><a class="page-link" href="#">6</a></li>
                                <li class="page-item"><a class="page-link" href="#">7</a></li>
                                <li class="page-item"><a class="page-link" href="#">8</a></li>
                            </ul>
                        </nav>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <?php include 'load.footer.php' ?>
    </div>
    <?php include 'load.scripts.php' ?>
    <script>
        $(document).ready(function() {
            $("#buy_lead").click(function() {
                $(".hide_data").css("display", "none");
                $(".show_data").css("display", "block");
            });
        });
    </script>
</body>

</html>