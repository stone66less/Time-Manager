<?php
require_once '../includes/db_functions.php';
require_once '../actions/AnniVersariesActions.php';
list($anniv_cap, $appnt_cap, $task_cap) = explode('+',$_SESSION['tabcapt']);
$field_array = array();
$field_array = $_SESSION['fieldarr'];
$anniv_nf = $field_array['anniv'];
$dpgconn = conn_db();
ob_start();
echo '<table id="annannivs"><caption>' . $anniv_cap . '</caption><tbody>';
$us_lim = $_SESSION['anvlim'];
$tu_id = $_SESSION['userid'];
list_current_annivs($dpgconn, $tu_id, $us_lim, $anniv_nf);
echo '</tbody></table>';
ob_end_flush();
?>