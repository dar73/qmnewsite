<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Home';
$TITLE = SITE_NAME . ' | ' . $page_title;
$LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'why_to_join.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                    <!-- <a href="index3.html" class="nav-link">Home</a> -->
                </li>
                <li class="nav-item m-2">
                    <button onclick="window.location.href=\'clogin.php\'" class="btn nav-link px-2 float-right quote_btn">Your Request</button>
                    <!-- floating ginnie -->
                    <div class="hide_ginnie">
                        <div class="card">
                            <div class="card-body d-flex">
                                <div class="col-6 my-auto">
                                    <img src="Images/prati/guide_ginnie.png" alt="">
                                </div>
                                <div class="col-6 my-auto">
                                    <p class="card-title">Check your quote here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- floating ginnie -->
                </li>';

$customer_name = $customer_email = $customer_phone = $customer_id = ''; 
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
    $LOGINBTN = '<a class="login_button hidebutton" href="logout.php">LOG OUT</a>';
    if ($_SESSION['udat_DC']->user_level == 2) {
        //$LOGINBTN .= '<a class="login_button hidebutton" href="ctrl/v_profile.php">Dashboard</a>';
        $LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'ctrl/v_profile.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i>Dashboard</button>
                   
                </li>
                <li class="nav-item m-2">
                    <button onclick="window.location.href=\'logout.php\';" class="btn nav-link px-2 float-right quote_btn">Log Out</button>
                </li>';
    }
    if ($_SESSION['udat_DC']->user_level == 3) {
        $LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'ctrl/c_profile.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i>My Profile</button>
                   
                </li>
                <li class="nav-item m-2">
                    <button onclick="window.location.href=\'logout.php\';" class="btn nav-link px-2 float-right quote_btn">Log Out</button>
                </li>';
        $dataArr = GetDataFromCOND("customers",  " and iCustomerID=$sess_user_id");
        //DFA($dataArr);
        $customer_first_name = $dataArr[0]->vFirstname;
        $customer_last_name = $dataArr[0]->vLastname;
        $customer_company_name = $dataArr[0]->vName_of_comapny;
        $position = $dataArr[0]->vPosition;
        $cemail = $dataArr[0]->vEmail;
        $phone = $dataArr[0]->vPhone;
    }

    $customer_id = $_SESSION['udat_DC']->user_id;
    $customer_name = $_SESSION['udat_DC']->user_name;
    //$customer_email = $_SESSION['udat_DC']->user_email;
    //$customer_email = GetXFromYID("select vEmail from customer where iCustID = $customer_id");

    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<?php include 'load.link.php'; ?>

</head>

<body>
    <!-- Header -->
    <?php include 'header.php'; ?>
    <!-- Header -->

    <div class="jumbotron thank_page" >
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>Please check your email</strong> for further instructions on how to complete your account setup.</p>
  <hr>
  <p>
    Having trouble? <a href="#">Contact us</a>
  </p>
  <p class="lead">
    <a class="btn btn-sm" href="index.php" role="button">Continue to homepage</a>
  </p>
</div>

    
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <!-- Footer -->

</body>

</html>