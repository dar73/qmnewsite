<?php
include "config.inc.php"; // db configurations
include "define.inc.php"; // # defines
include "generic.inc.php"; // # common functions
include "common.inc.php"; // # project specific functions
include "userdat.php"; // #
include "sql.inc.php"; // # sql functions
include "custom.php"; // custom functions created for this project
include "dynamic.inc.php"; // */
include "common.master.php";

if (!$logged && $NO_REDIRECT == 0) {
	session_destroy();
	ForceOut(9);
	exit;
}
if ($logged) {

	//include "access.inc.php"; // */
	//$USER_PROPERTY_STR = GetIDString2('select iPropertyID from users_property_assoc where iUserID=' . $sess_user_id);

}

$PAGE_TITLE = "Quote Master | ";
