<?php
require_once('includes/common.php');
require_once("includes/userdat.php");

UpdateField('users', 'cActive', 'N', " iUserID=$sess_user_id"); //setting active status to N;

session_destroy();

header('location:index.php');
