<?php
// actions for time_users
require_once 'classes/TimeUsers.php';

function time_user_display ($dbc, $tu_id, $maint_go)  {
	$disp_line = NULL;
	$tuquery = "SELECT TU.logon_id, TU.logon_name, TU.active_user,
				TU.sysgrp_user, GR.gr_name, SL.lang_name
				FROM supported_languages SL,
				group_roles GR,
				time_users TU
				WHERE TU.gr_id = GR.gr_id
				AND   TU.lang_code = SL.lang_code
				AND   TU.tu_id = $tu_id";
	$result = pg_query($dbc, $tuquery);
	if ( $result === FALSE)  {
		$disp_line = 'Unable to access this user ' . $tu_id;
	}  else  {
		$row = pg_fetch_assoc($result);
		$logon_id = $row['logon_id'];
		$logon_name = $row['logon_name'];
		$gr_name = $row['gr_name'];
		$lang_name = $row['lang_name'];
		$active_user = ( ($row['active_user'] == 't')? 'Yes' : 'No');
		$sysgrp_user = ( ($row['sysgrp_user'] == 't')? 'Yes' : 'No');
		$disp_line = '<tr><td>' . $logon_id . '</td><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $tu_id. '">' . $logon_name . '</a></td><td>' . $gr_name . '</td><td>' . $lang_name . '</td><td style="text-align:center;">' . $active_user . '</td><td style="text-align:center;">' . $sysgrp_user . '</td></tr>';
	}
	return $disp_line;
}

function test_useage ($dbc, $user_id, $sys_groupie)  {
	$no_go = 'N';
	$tu_query = "SELECT TU.super_user, TU.sysgrp_user, GR.view_others,
			GR.group_inuse
			FROM group_roles GR,
				  time_users TU
			WHERE TU.gr_id = GR.gr_id
			AND   TU.tu_id = $user_id";
	$tures = pg_query($dbc, $tu_query);
	if ($tures)  {
		$row = pg_fetch_assoc($tures);
		if ( ($row['group_inuse'] == 't') && ($row['super_user'] != 't') ) {
			if ($sys_groupie)  {
				if ( ($row['sysgrp_user'] == 't') && ($row['view_others'] == 't') )  {
					$no_go = 'Y';
				}
			}  else  {
				$no_go = 'Y';
			}
		}
	}
	return $no_go;
}

function test_if_active ($dbc, $user_id)  {
	$aquery = "SELECT 'Y' FROM group_roles GR, time_users TU
			WHERE TU.gr_id = GR.gr_id
			AND	TU.tu_id = $user_id
			AND	TU.active_user IS TRUE
			AND	GR.group_inuse IS TRUE";
	$ares = pg_query($dbc, $aquery);
	if ($ares)  {
		return TRUE;
	}  else  {
		return FALSE;
	}
}

function time_users_field_array ($dbc, $rec_id)  {
	$vals_array = array();
	$tusr = new TimeUsers();
	$tusrres = $tusr->find_time_users_by_id($dbc, $rec_id);
	if ($tusrres)  {
		$vals_array['logonId'] = $tusr->getLogonId();
		$vals_array['logonName'] = $tusr->getLogonName();
		$vals_array['grId']  = $tusr->getGrId();
		$vals_array['langCode'] = $tusr->getLangCode();
		$vals_array['activeUser'] = $tusr->getActiveUser();
		$vals_array['superUser'] = $tusr->getSuperUser();
		$vals_array['sysgrpUser'] = $tusr->getSysgrpUser();
		$vals_array['fixedIp'] = $tusr->getFixedIp();
		$vals_array['utcOffset'] = $tusr->getUtcOffset();
		$vals_array['phoneExtn'] = $tusr->getPhoneExtn();
		$vals_array['emailAddr'] = $tusr->getEmailAddr();
		$vals_array['userNotes'] = $tusr->getCoUserData();
	}  else  {
		$vals_array[0] = 'Unable to find this User ' . $rec_id;	
	}
	return $vals_array;
}

?>