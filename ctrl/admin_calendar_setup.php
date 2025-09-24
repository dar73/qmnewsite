<?php
include "../includes/common.php";


$_SESSION['ADMIN_CALENDAR_SETUP']=true;

header("location: $googleOauthURL");
exit;

?>