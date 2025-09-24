<?php
require_once('includes/common.php');
echo (isset($_SESSION['COVERAGE'])) ? count($_SESSION['COVERAGE']) : 0;

?>