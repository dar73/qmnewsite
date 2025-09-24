<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Service Provider Details';
$disp_url = 'index.php';
$TITLE = SITE_NAME . ' | ' . $page_title;
$spid = (isset($_GET['spid'])) ? $_GET['spid'] : '';
$appid = (isset($_GET['appid'])) ? $_GET['appid'] : '';
if (empty($spid) && empty($appid)) {
    header('location: ' . $disp_url);
}
$dataArr = GetDataFromID("service_providers", "id", $spid);
if (empty($dataArr)) {
    header("location: $disp_url");
    exit;
}
// DFA($dataArr);
// exit;

$licence_check = db_output2($dataArr[0]->iLicence);
$insurance_check = db_output2($dataArr[0]->iInsurance);
$brochure_check = db_output2($dataArr[0]->iBrochure);
$certificate1_check = db_output2($dataArr[0]->iCertificate1);
$certificate2_check = db_output2($dataArr[0]->iCertificate2);
$awards_check = db_output2($dataArr[0]->iAwards);
$fblink_check = db_output2($dataArr[0]->iFacebook);
$instagram_check = db_output2($dataArr[0]->iInstagram);
$linkdn_check = db_output2($dataArr[0]->iLikendn);

$First_name = db_output2($dataArr[0]->First_name);
$Last_name = db_output2($dataArr[0]->Last_name);
$company_name = db_output2($dataArr[0]->company_name);
$phone = db_output2($dataArr[0]->phone);
$email_address = db_output2($dataArr[0]->email_address);
$license_number = db_output2($dataArr[0]->license_number);
$vLicence_file = db_output2($dataArr[0]->vLicence_file);
$dDate_Licence_expiry = db_output2($dataArr[0]->dDate_Licence_expiry);
$vInsurance_file = db_output2($dataArr[0]->vInsurance_file);
$dDate_insurance_expiry = db_output2($dataArr[0]->dDate_insurance_expiry);
$street = db_output2($dataArr[0]->street);
$state = db_output2($dataArr[0]->state);
$county = db_output2($dataArr[0]->county);
$city = db_output2($dataArr[0]->city);
$vBrochure = db_output2($dataArr[0]->vBrochure);
$vCertificate1 = db_output2($dataArr[0]->vCertificate1);
$vCertificate2 = db_output2($dataArr[0]->vCertificate2);
$vCertificate3 = db_output2($dataArr[0]->vCertificate3);
$vFblink = db_output2($dataArr[0]->vFblink);
$vInstalink = db_output2($dataArr[0]->vInstalink);
$vLinkedInlink = db_output2($dataArr[0]->vLinkedInlink);
$vGovtID = db_output2($dataArr[0]->vGovtID);

$CITY_NAME=GetXFromYID("SELECT city_name FROM cities WHERE city_id='$city'");
$COUNTRY=GetXFromYID("SELECT country_name FROM countries WHERE country_id='$county'");
$STATE_NAME=GetXFromYID("SELECT state_name FROM states WHERE state_id='$state'");
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
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>SERVICE PROVIDER'S DETAILS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container -->
            </section>
            <section class="content">
                <div class="container py-5">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="profile-username text-center"><?php echo $company_name; ?></h3>
                                    <p class="text-muted text-center">Company Name</p>
                                </div>
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <!-- <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture"> -->
                                    </div>

                                    

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong><i class="far fa-envelope mr-1"></i> Email</strong>
                                            <p class="text-muted"><?php echo $email_address; ?></p>
                                        </div>
                                    </div>
                            
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- About Me Box -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">About Company</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <strong><i class="fas fa-book mr-1"></i> Full Name</strong>

                                            <p class="text-muted">
                                                <?php echo $First_name . ' ' . $Last_name; ?>
                                            </p>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                            <p class="text-muted"><?php echo $STATE_NAME; ?>, <?php echo $COUNTRY; ?> ,<?php echo $CITY_NAME; ?></p>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <strong><i class="fas fa-phone mr-1"></i> Phone</strong>

                                            <p class="text-muted">
                                                <span class="tag tag-danger"><?php echo $phone; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <?php
                                            if (IsExistFile($vLicence_file, LICENCE_UPLOAD)) {
                                            ?>
                                                <strong><i class="far fa-file-alt mr-1"></i> Business Licence</strong>

                                                <ul class="list-unstyled">
                                                    <li>
                                                        <a href="<?php echo LICENCE_PATH . $vLicence_file ?>" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> licence</a>
                                                    </li>
                                                </ul>

                                            <?php
                                            } ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <?php
                                            if (IsExistFile($vInsurance_file, INSURANCE_UPLOAD)) {
                                            ?>
                                                <strong><i class="far fa-file-alt mr-1"></i> Insurance</strong>

                                                <ul class="list-unstyled">
                                                    <li>
                                                        <a href="<?php echo INSURANCE_PATH . $vInsurance_file ?>" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> insurance</a>
                                                    </li>
                                                </ul>

                                            <?php
                                            } ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <?php
                                            if (IsExistFile($vGovtID, GOVTID_UPLOAD)) {
                                            ?>
                                                <strong><i class="far fa-address-card mr-1"></i>Govt ID/Drivers License</strong>

                                                <ul class="list-unstyled">
                                                    <li>
                                                        <a href="<?php echo GOVTID_PATH . $vGovtID; ?>" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i>Govt ID/Drivers License</a>
                                                    </li>
                                                </ul>

                                            <?php
                                            } ?>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->

                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container -->
            </section>
            <?php include 'footer.php'; ?>
        </div>
        <?php include 'load.scripts.php'; ?>
</body>

</html>