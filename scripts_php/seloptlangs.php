<?php
require_once 'includes/db_functions.php';
require_once 'actions/SupportedLanguagesActions.php';
$sel_list = NULL;
$lang_table = NULL;
$first_optn = NULL;
$button_array = array();
$label_array = array();
$lerrs_val = NULL;
if ( isset($_SESSION['buttcount']) && ($_SESSION['buttcount'] > 0) )  {
	$button_array = $_SESSION['buttarr'];
	if ( array_key_exists('lerrs', $button_array) )  {
		$lerrs_val = trim($button_array['lerrs']);
	}  else  {
		$lerrs_val = 'Undefined';
	}
}
if ( isset($_SESSION['labcount']) && ($_SESSION['labcount'] > 0) )  {
	$label_array = $_SESSION['fieldarr'];
	if ( array_key_exists('langTable', $label_array) )  {
		$lang_table = trim($label_array['langTable']);
	}  else  {
		$lang_table = 'Undefined';
	}
	if ( array_key_exists('firstOption', $label_array) )  {
		$first_optn = trim($label_array['firstOption']);
	}  else  {
		$first_optn = 'Undefined';
	}
}
$dpgconn = conn_db();
$sel_list = lang_opt_list($dpgconn, $first_optn);
if ( substr($sel_list,0,7) == '<option')  {
	echo '<form id="sellang" name="sellang" method="post" />';
	echo '<fieldset><input type="hidden" id="andor" name="andor" value="?" />';
	echo '<div><label class="langlabel" for="langTable">' . $lang_table . ' :</label>';
	echo '<select id="langTable" name="langTable" style="width:240px;">';
	echo $sel_list . '</select></div>';
	echo '<input type="button" id="lerrs" name="lerrs" value="' . $lerrs_val . '" style="margin-top:-30px; float:right;" onclick="doitStart();" />';
	echo '</fieldset></form>' . PHP_EOL;
}  else  {
	echo '<p class="error">' . $sel_list . '</p>';
}
?>