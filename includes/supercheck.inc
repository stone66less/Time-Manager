<?php
// session_start();
if (isset($_SESSION['superu'])) {
	$arethey = $_SESSION['superu'];
	if ( $arethey != 'Y' ) {
	   $_SESSION['lg_error'] = '10';
	   header("Location: index.php");
      exit();	
	}
}  else  {
	$_SESSION['lg_error'] = '11';
   header("Location: index.php");
   exit();
}
?>