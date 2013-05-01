<?php
// Fetch all Supported Languages rows in name sequence and place into
// a table display.
require_once 'includes/db_functions.php';
require_once 'includes/dispfunctions.php';
require_once 'classes/SupportedLanguages.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$nav_refn = 0;
$maint_go = 0;
$tab_capt = NULL;
$thtd_array = array();
$thtd_count = 0;
$first_time = TRUE;
$alt_row = TRUE;
$item_count = 0;
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}
ob_start();
$dpgconn = conn_db();
$tab_capt = $_SESSION['tabcapt'];
$thtd_array = $_SESSION['thtdarr'];
$grps = new SupportedLanguages();
$result = $grps->list_languages($dpgconn);
if (!$result)   {
   echo '<p class="error">Database error. Unable to query table supported_languages.</p>';
} else {
	$tab_header = setup_header($tab_capt, $thtd_array);
	if ( is_null($tab_header) )  {
		$err_text = return_error($dpgconn, 9011, $def_lang);
		echo $err_text;
	}  else  {
		echo $tab_header . PHP_EOL;
		while ($row = pg_fetch_assoc($result))   {
	      $lang_code = $row['lang_code'];
	      $lang_name = $row['lang_name'];
	      $well_text = $row['welcome_text'];
	      $fwel_text = $row['farwell_text'];
	 	   echo '<tr><td class="centred"><a href="../index.php?act=nav&nav=' . $maint_go . '&alfid=' . $lang_code . '">' . $lang_code . '</a></td><td>' . $lang_name . '</td><td>' . $well_text . '</td><td>' . $fwel_text . '</td></tr>';
	   }
	   echo '</tbody></table>' . PHP_EOL;
		echo '<p><form name="dummyone" id="dummyone" action="../index.php" method="GET">';
	 	echo '<input type="submit" name="submit" value="ADD NEW" class="addB" >';
	 	echo '<input type="hidden" id="maintGo" name="maintGo" value="' . $maint_go . '" /></form></p>' . PHP_EOL;
	 }
}
ob_end_flush();
?>