<?php
require_once '../classes/AnniVersaries.php';
require_once '../includes/charfunctions.php';

function list_current_annivs ($dbc, $tu_id, $lim_it, $anniv_nf)  {
	$date_from = substr(todays_date('mmddyyyy'),0,4);
	$lquery = "SELECT av_id, TO_CHAR(anni_tday, '09') || '/' || TO_CHAR(anni_month, '09') AS anni_date,
			anni_descr FROM anni_versaries WHERE tu_id = $tu_id AND anni_active IS TRUE
				AND anni_sday >= $date_from ORDER BY anni_sday LIMIT $lim_it";
	$lres = pg_query($dbc, $lquery);
	if ( (!$lres) || ($lres && (pg_num_rows($lres) == 0)) )  {
		echo '<tr><td>' . $anniv_nf . '</td></tr>';
	}  else  {
		while($row = pg_fetch_assoc($lres)) {
			$av_id = $row['av_id'];
			$anni_date = $row['anni_date'];
			$anni_descr = $row['anni_descr'];
			echo '<tr><td>' . $anni_date . '</td><td>' . $anni_descr . '</td></tr>';
		}
	}
}

function build_display_line ($row, $maint_go, $say_yes, $say_non) {
	$av_id = $row['av_id'];
	$anni_date = str_pad($row['anni_tday'],2,'0',STR_PAD_LEFT) . '/' . str_pad($row['anni_month'],2,'0',STR_PAD_LEFT);
	$anni_actv = ( ($row['anni_active'] == 't')? $say_yes : $say_non);
	$anni_desc = $row['anni_descr'];
	$user_notes = $row['co_user_data'];
	$disp_line = '<tr><td>' . $anni_date . '</td><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $av_id . '">' . $anni_desc . '</a></td><td class="centred">' . $anni_actv . '</td><td>' . $user_notes . '</td></tr>';
	return $disp_line;
}

function anniv_field_array ($dbc, $rec_id)  {
	$vals_array = array();
	$annv = new AnniVersaries();
	$annvres = $annv->find_anni_versaries_by_id($dbc, $rec_id);
	if ($annvres)  {
		$vals_array['tuId'] = $annv->getTuId();
		$vals_array['annDesc'] = $annv->getAnniDescr();
		$vals_array['annTday'] = $annv->getAnniTday();
		$vals_array['annMonth'] = $annv->getAnniMonth();
		$vals_array['annSday'] = $annv->getAnniSday();
		$vals_array['isActive'] = $annv->getAnniActive();
		$vals_array['userNotes'] = $annv->getCoUserData();
	}  else  {
		$vals_array[0] = 'Unable to find this Anniversary ' . $rec_id;	
	}
	return $vals_array;
}

?>