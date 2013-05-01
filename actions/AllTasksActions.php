<?php
require_once '../includes/charfunctions.php';
require_once '../classes/AllTasks.php';

function list_current_tasks ($dbc, $tu_id, $lim_it, $task_nf)  {
	$date_from = todays_date('yyyymmdd');
	$is_yes = $_SESSION['yestxt'];
	$is_non = $_SESSION['nontxt'];
	$tquery = "SELECT * FROM all_tasks WHERE tu_id = $tu_id AND TO_CHAR(todo_date,'YYYYMMDD') = '$date_from'
			ORDER BY sequence_no, task_class LIMIT $lim_it";
	$tqres = pg_query($dbc, $tquery);
	if ( (!$tqres) || ($tqres && (pg_num_rows($tqres) == 0)) )  {
		echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>' . $task_nf . '</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	}  else  {
		while($row = pg_fetch_assoc($tqres)) {
			$alt_id    = $row['allt_id'];
			$alt_seqn  = $row['sequence_no'];
			$alt_class = $row['task_class'];
			$alt_desc  = $row['task_descrn'];
			$alt_rc    = $row['resch_count'];
			$alt_compl = ( ($row['task_compl'] == 't')? $is_yes : $is_non);
			$alt_resch = ( ($row['task_resched'] == 't')? $is_yes : $is_non);
			if ( $alt_rc > 0 )  {
				$alt_resch = $alt_resch . ' / ' . $alt_rc;
			}
			echo '<tr><td>' . $alt_seqn . '</td><td class="centred">' . $alt_class . '</td><td>' . $alt_desc . '</td><td><a href="toggcompl.html?altidd='.$alt_id.'" title="Click to Toggle Completed Status">' . $alt_compl . '</a></td><td>' . $alt_resch . '</td></tr>';
		}
	}
}

function find_uncompleted_tasks ($dbc, $tu_id)  {
	$date_from = todays_date('yyyymmdd');
	$uncomp_tasks = array();
	$task_count = 0;
	$ucquery = "SELECT allt_id FROM all_tasks WHERE tu_id = $tu_id AND TO_CHAR(todo_date,'YYYYMMDD') < '$date_from'
					AND task_compl IS FALSE AND task_copied IS FALSE ORDER BY allt_id";
	$uctres = pg_query($dbc, $ucquery);
	if ( ($uctres !== FALSE) && (pg_num_rows($uctres) > 0) )  {
		while($row = pg_fetch_assoc($uctres)) {
			$row_id = $row['allt_id'];
			$uncomp_tasks[$task_count] = $row_id;
			$task_count++;
		}
	}
	return $uncomp_tasks;
}

function all_tasks_field_array($dbc, $rec_id)  {
	$vals_array = array();
	$atsk = new AllTasks();
	$atskres = $atsk->find_all_tasks_by_id($dbc, $rec_id);
	if ($atskres)  {
		$vals_array['alltId'] = $atsk->getAlltId();
		$vals_array['tuId']   = $atsk->getTuId();
		$vals_array['taskClass'] = $atsk->getTaskClass();
		$vals_array['seqnNo']  = $atsk->getSequenceNo();
		$date_todo = DateTime::createFromFormat('Y-m-d',$atsk->getTodoDate ());
		$vals_array['todoDate'] = $date_todo->format('d-m-Y');
		$vals_array['taskDesc'] = $atsk->getTaskDescrn();
		$vals_array['taskComp'] = $atsk->getTaskCompl();
		$vals_array['taskResc'] = $atsk->getTaskResched();
		$vals_array['taskCopy'] = $atsk->getTaskCopied();
		$vals_array['resCount'] = $atsk->getReschCount();
		$vals_array['userNotes'] = $atsk->getCoUserData();		
	}  else  {
		$vals_array[0] = 'Unable to find this Task ' . $rec_id;
	}
	return $vals_array;
}
?>