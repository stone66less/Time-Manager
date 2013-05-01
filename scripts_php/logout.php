<?php
require_once 'includes/db_functions.php';
require_once 'classes/LoggedUsers.php';
require_once 'classes/BoilerPlate.php';
$def_lang = 'en-GB';
$nav_refn = 0;
$dpgconn = conn_db();
$lu_id = (int)$_SESSION['luid'];
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ($nav_refn > 0)  {
	$bp = new BoilerPlate();
	$bpres = $bp->find_boiler_plate_by_lang($dpgconn, $nav_refn, $def_lang);
	if ($bpres)  {
		$bye_bye = $bp->getHeadingTwo();
	}  else  {
		$bye_bye = 'Bye-bye text not found';
	}
}
if ($lu_id > 0)  {
	$luse = new LoggedUsers();
	$luseres = $luse->find_logged_users_by_id ($dpgconn, $lu_id);
	if ($luseres)  {
		$res = $luse->log_user_off($dpgconn, $lu_id);
	}
}
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 420000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
echo '<p style="font-weight: bold;">' . $bye_bye . '</p>';
?>