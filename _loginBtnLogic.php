<?php
$LOGINBTN = '<li class="nav-item dropdown m-2">
                    <button class="btn nav-link dropdown-toggle px-2 float-right" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Login</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="plogin.php">Service Provider</a>
                        
                    </div>
                </li>
                <li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'why_to_join.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                    
                </li>
                ';

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