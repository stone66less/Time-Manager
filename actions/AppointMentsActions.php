<?php
require_once '../classes/AppointMents.php';
require_once '../includes/charfunctions.php';

function list_current_appoints ($dbc, $tu_id, $lim_it, $appnt_nf, $appnt_title, $appnt_with, $appnt_at, $appnt_dept)  {
	$date_from = todays_date('yyyymmdd');
	$lquery = "SELECT ap_id, TO_CHAR(appoint_date,'DD-MM-YYYY') || ' ' ||
			TO_CHAR(appoint_hour, '09') || ':' || TO_CHAR(appoint_min, '09') || ' ' ||
			meet_subjt AS table_cell,  with_whom, est_drtn,
			TO_CHAR(((depart_time - (depart_time % 60)) / 60),'09') || ':' || TRIM(TO_CHAR((depart_time % 60),'09')) AS leave_time,
			intl_meet, meet_where
			FROM appoint_ments WHERE tu_id = $tu_id AND meet_cancld IS FALSE
			AND  TO_CHAR(appoint_date,'YYYYMMDD') >= '$date_from' ORDER BY appoint_date, appoint_hour, appoint_min LIMIT $lim_it";
	$lres = pg_query($dbc, $lquery);
	if ( (!$lres) || ($lres && (pg_num_rows($lres) == 0)) )  {
		echo '<tr><td>' . $appnt_nf . '</td></tr>';
	}  else  {
		while($row = pg_fetch_assoc($lres)) {
			$ap_id = $row['ap_id'];
			$cell_data = $row['table_cell'];
			$with_whom = $row['with_whom'];
			$est_drtn  = $row['est_drtn'];
			$tto_leave = $row['leave_time'];
			$intl_meet = $row['intl_meet'];
			$meet_wher = $row['meet_where'];
			$td_title = $appnt_title . $est_drtn . 'mins';
			if ( strlen($with_whom) > 0 )  {
				$td_title = $td_title . $appnt_with . $with_whom;
			}
			if ( strlen($meet_wher) > 0 )  {
				$td_title = $td_title . $appnt_at . $meet_wher;
			}
			if ( $tto_leave != '00:00')  {
				$td_title = $td_title . $appnt_dept . $tto_leave;
			}
			echo '<tr><td title="' . $td_title . '">' . $cell_data . '</td></tr>';
		}
	}
}

function build_display_line_am ($dbc, $user_id, $lim_it, $off_set, $maint_go, $say_yes, $say_non)  {
	$disp_query = "SELECT ap_id, TO_CHAR(appoint_date,'DD-MM-YYYY') AS appoint_date,
				TO_CHAR(appoint_hour, '09') || ':' || TO_CHAR(appoint_min, '09') AS appoint_time,
				meet_subjt, with_whom, intl_meet, meet_cancld
				FROM appoint_ments
				WHERE tu_id = $user_id
				ORDER BY appoint_date DESC, appoint_hour, appoint_min LIMIT $lim_it OFFSET $off_set";
	$dispres = pg_query($dbc, $disp_query);
	if ($dispres !== FALSE)  {
		while($row = pg_fetch_assoc($dispres)) {
			$ap_id   = $row['ap_id'];
			$ap_date = $row['appoint_date'];
			$ap_time = $row['appoint_time'];
			$ap_subj = $row['meet_subjt'];
			$ap_whom = $row['with_whom'];
			$ap_intl = ( ($row['intl_meet'] == 't')? $say_yes : $say_non);
			$ap_cncl = ( ($row['meet_cancld'] == 't')? $say_yes : $say_non);
			echo '<tr><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $ap_id . '">' . $ap_date . '</a></td><td>' . $ap_time . '</td><td>' . $ap_whom . '</td><td> ' . $ap_subj . '</td><td class="centred"> ' . $ap_intl . '</td><td class="centred">' . $ap_cncl . '</td></tr>' . PHP_EOL;
		}
	}
}

function appoint_field_array ($dbc, $rec_id)  {
	$vals_array = array();
	$appnt = new AppointMents();
	$appntres = $appnt->find_appoint_ments_by_id($dbc, $rec_id);
	if ($appntres)  {
		$vals_array['tuId'] = $appnt->getTuId();
		$date_appnt = DateTime::createFromFormat('Y-m-d',$appnt->getAppointDate ());
		$vals_array['appntDate'] = $date_appnt->format('d-m-Y');
		$vals_array['appntHour'] = $appnt->getAppointHour ();
		$vals_array['appntMint'] = $appnt->getAppointMin ();
		$vals_array['appntWith'] = $appnt->getWithWhom ();
		$vals_array['appntSubj'] = $appnt->getMeetSubjt ();
		$vals_array['appntDrtn'] = $appnt->getEstDrtn ();
		$appnt_dept = $appnt->getDepartTime ();
		if ( $appnt_dept > 0 )  {
			$appnt_deptm = ($appnt_dept % 60);
			$appnt_depth = (($appnt_dept - $appnt_deptm) / 60);
			$appnt_deptm = str_pad($appnt_deptm, 2, '0', STR_PAD_LEFT);
			$vals_array['appntDepth'] = $appnt_depth;
			$vals_array['appntDeptm'] = $appnt_deptm;
		}  else  {
			$vals_array['appntDepth'] = '0';
			$vals_array['appntDeptm'] = '0';
		}
		$vals_array['appntIntl'] = $appnt->getIntlMeet ();
		$vals_array['appntWher'] = $appnt->getMeetWhere ();
		$vals_array['appntCanc'] = $appnt->getMeetCancld ();
		$vals_array['userNotes'] = $appnt->getCoUserData();
	}  else  {
		$vals_array[0] = 'Unable to find this Appointment ' . $rec_id;	
	}
	return $vals_array;
}

?>