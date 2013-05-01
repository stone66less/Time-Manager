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
$head_string = setup_header($task_cap, $thtd_array);
$dpgconn = conn_db();
ob_start();
echo $head_string;
$us_lim = $_SESSION['tsklim'];
$tu_id = $_SESSION['userid'];
list_current_tasks($dpgconn, $tu_id, $us_lim, $tasks_nf);
echo '</tbody></table>';
ob_end_flush();
?>