<?php

require_once '../classes/GroupRoles.php';

function group_opt_list ($dbc, $in_group=NULL)  {
	$opt_list = NULL;
	if ( (!is_null($in_group) ) )  {
		$sel_group = (int)$in_group;
	}
	$gr = new GroupRoles();
	$grres = $gr->list_all_groups($dbc);
	if ($grres)  {
		$opt_list = '<option value="*">* * Choose a Group</option>';
		while($row = pg_fetch_assoc($grres)) {
			$opt_type = $row['gr_id'];
			$opt_desc = $row['gr_name'];
			$in_use = ( ($row['group_inuse'] == 't')? TRUE : FALSE);
			if ( $in_use )  {
				if ( (!is_null($in_group)) && ($opt_type == $sel_group) )  {
					$opt_string = '<option value="' . $opt_type . '" selected="selected" >' . $opt_desc . '</option>';
				}  else  {
					$opt_string = '<option value="' . $opt_type . '" >' . $opt_desc . '</option>';
				}
				$opt_list = $opt_list . $opt_string;
			}
		}
	}
	return $opt_list;
}

function group_field_array ($dbc, $gr_id)  {
	$vals_array = array();
	$grp = new GroupRoles();
	$grpres = $grp->find_group_roles_by_id($dbc, $gr_id);
	if ($grpres)  {
		$vals_array['grName']   = $grp->getGrName();
		$vals_array['supUser']  = $grp->getSuperUser();
		$vals_array['viOthers'] = $grp->getViewOthers();
		$vals_array['grpInuse'] = $grp->getGroupInuse();
		$vals_array['pwdDays']  = $grp->getChgPword();
		$vals_array['anvLmt']   = $grp->getAnnivLimit();
		$vals_array['aptLmt']   = $grp->getAppntLimit();
		$vals_array['tskLmt']   = $grp->getTasksLimit();
		$vals_array['sysLmt']   = $grp->getSysadmLmt();
		$vals_array['uNotes']   = $grp->getCoUserData();
	}  else  {
		$vals_array[0] = 'Unable to find Group Role ' . $gr_id;	
	}
	return $vals_array;
}
?>