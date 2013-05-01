<?php
require_once '../includes/db_functions.php';
require_once '../actions/AppointMentsActions.php';
list($anniv_cap, $appnt_cap, $task_cap) = explode('+',$_SESSION['tabcapt']);
$field_array = array();
$field_array = $_SESSION['fieldarr'];
$appnt_nf = $field_array['appnt'];
$appnt_title = $field_array['appntTitle'];
$appnt_with = $field_array['appntWith'];
$appnt_at = $field_array['appntAt'];
$appnt_dept = $field_array['appntDept'];
$dpgconn = conn_db();
ob_start();
echo '<table id="annappts"><caption>' . $appnt_cap . '</caption><tbody>';
$us_lim = $_SESSION['aptlim'];
$tu_id = $_SESSION['userid'];
list_current_appoints($dpgconn, $tu_id, $us_lim, $appnt_nf, $appnt_title, $appnt_with, $appnt_at, $appnt_dept);
echo '</tbody></table>';
ob_end_flush();
?>