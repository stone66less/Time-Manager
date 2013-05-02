<?php
require_once '../includes/db_functions.php';
require_once '../includes/dispfunctions.php';
require_once '../actions/AllTasksActions.php';
list($anniv_cap, $appnt_cap, $task_cap) = explode('+',$_SESSION['tabcapt']);
$field_array = array();
$field_array = $_SESSION['fieldarr'];
$tasks_nf = $field_array['tasks'];
$thtd_array = array();
$thtd_array = $_SESSION['thtdarr'];
$buttn_array = array();
$buttn_count = 0;
$maint_go = 0;
if ( isset($_SESSION['buttarr']))  {
	$buttn_array = $_SESSION['buttarr'];
	$buttn_count = $_SESSION['buttcount'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}
$head_string = setup_header($task_cap, $thtd_array);
$dpgconn = conn_db();
ob_start();
echo $head_string;
$us_lim = $_SESSION['tsklim'];
$tu_id = $_SESSION['userid'];
list_current_tasks($dpgconn, $tu_id, $us_lim, $tasks_nf);
echo '</tbody></table><form id="actpost" name="actpost" action="../index.php?act=nav&nav=' . $maint_go . '&recid=0" method="POST">';
echo '<p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" title="' . $buttn_array['title'] . '" class="addBT" >';
echo '</p></form>' . PHP_EOL;
ob_end_flush();
?>