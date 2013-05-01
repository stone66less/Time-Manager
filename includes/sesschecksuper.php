<?php
//  Exit if NOT the system administrator.
require_once 'includes/db_functions.php';
require_once 'classes/ErrorMessages.php';
$error_no = 0;
if (isset($_SESSION['uname']) && isset($_SESSION['tmver']) && (strlen($_SESSION['uname']) > 0) ) {
	if (isset($_SESSION['superu']) && ($_SESSION['superu'] == 'Y') ) {
		$cool = TRUE;
	}  else  {
		$error_no = 9012;
	}
}  else  {
	$error_no = 9013;
}
if ($error_no > 0)  {
	$dpgconn = conn_db();
	$erm = new ErrorMessages();
	$_SESSION['lg_error'] = $erm->build_error_list($dpgconn, $error_no, 'en-GB', TRUE);
  	$_SESSION['errcount'] = 1;
   header("Location: ../index.php");
   exit();
}
?>