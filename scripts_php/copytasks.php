<?php
require_once '../includes/db_functions.php';
require_once '../includes/charfunctions.php';
require_once '../actions/AllTasksActions.php';
require_once '../classes/AllTasks.php';
session_start();
$callform = '../index.php?act=ret';
$dpgconn = conn_db();
$user_id = $_SESSION['userid'];
$uncomp_tasks = find_uncompleted_tasks($dpgconn, $user_id);
if ( (is_array($uncomp_tasks)) && (count($uncomp_tasks) > 0) )  {
	$errs_occurred = FALSE;
	$res = do_begin($dpgconn);
	$allt = new AllTasks();
	foreach($uncomp_tasks as $key => $value)  {
		$task_id = (int)$value;
		$tlres = $allt->lock_all_tasks_by_id($dpgconn,$task_id);
		if (!$tlres)  {
			$errs_occurred = TRUE;
		}  else  {
			$versno = $allt->getAuVersNumb();
			$versno++;
			$allt->setTaskCopied(TRUE);
			$allt->setAuVersNumb($versno);
			$allt->setUpdatedBy($user_id);
			$tures = $allt->update_copy_flag($dpgconn, $task_id);
			if (!$tures)  {
				$errs_occurred = TRUE;
			}
		}
	}
	reset($uncomp_tasks);
	$todo_date = todays_date('yyyymmdd');
	foreach($uncomp_tasks as $key => $value)  {
		$task_id = (int)$value;
		$tlres = $allt->lock_all_tasks_by_id($dpgconn,$task_id);
		if (!$tlres)  {
			$errs_occurred = TRUE;
		}  else  {
			$r_c = $allt->getReschCount();
			if ( (is_null($r_c)) || (!is_numeric($r_c)) )  {
				$r_c = 1;
			}  else  {
				$r_c++;
			}
			$allt->setReschCount($r_c);
      	$allt->setTaskCompl(FALSE);
      	$allt->setTaskResched(TRUE);
      	$allt->setTaskCopied(FALSE);
      	$allt->setTodoDate($todo_date);
      	$allt->setInsertedBy($user_id);
      	$tires = $allt->insert_copied_task($dpgconn);
      	if (!$tires)  {
      		$errs_occurred = TRUE;
      	}
		}
	}
	if ($errs_occurred)  {
		$res = do_rollback($dpgconn);
	}  else  {
		$res = do_commit($dpgconn);
	}
}
header("Location: $callform");
exit();
?>