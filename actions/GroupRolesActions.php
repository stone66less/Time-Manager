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
?>