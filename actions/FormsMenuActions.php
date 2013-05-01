<?php
// factory for table forms_menu.

require_once '../classes/FormsMenu.php';

function forms_menu_opt_list ($dbc, $in_navref=NULL)  {
	$opt_list = NULL;
	$frm = new FormsMenu();
	$frmres = $frm->list_all_forms_menu ($dbc, 999999, 0);
	if ($frmres)  {
		$opt_list = '<option value="*">* * Choose a Menu Item</option>';
		while($row = pg_fetch_assoc($frmres)) {
			$is_active = ( ($row['active_item'] == 't')? TRUE : FALSE);
			if ($is_active)  {
				$nav_ref = $row['navgn_refn'];
				$form_name = $row['form_name'];
				if ( (!is_null($in_navref)) && ($nav_ref == $in_navref) )  {
					$opt_string = '<option value="' . $nav_ref . '" selected="selected" >' . $form_name . '</option>';
				}  else  {
					$opt_string = '<option value="' . $nav_ref . '" >' . $form_name . '</option>';
				}
				$opt_list = $opt_list . $opt_string;
			}
		}
	}
	return $opt_list;
}

function build_display_line ($dbc, $fm_id, $maint_go, $lang)  {
	$is_yes = $_SESSION['yestxt'];
	$is_non = $_SESSION['nontxt'];
	$disp_line = NULL;
	$query = "SELECT FM.navgn_refn, FM.form_name, FT.type_descr,
				FM.active_item, FM.super_user, FM.sysgrp_user,
				FM.forward_to
				FROM form_types FT,
					  forms_menu FM
				WHERE FM.fm_id = $fm_id
				AND	FM.form_type = FT.form_type
				AND	FT.lang_code = '$lang'"; 
	$result = pg_query($dbc, $query);
	if ($result === FALSE)  {
		$disp_line = 'Query failed for ' . $fm_id . ' lang ' . $lang;
	}  else  {
		$row = pg_fetch_assoc($result);
		$navgn_ref = $row['navgn_refn'];
		$html_name = $row['form_name'];
		$type_descr = $row['type_descr'];
		$is_actv = ( ($row['active_item'] == 't')? $is_yes : $is_non);
		$super_only = ( ($row['super_user'] == 't')? $is_yes : $is_non);
		$vi_others  = ( ($row['sysgrp_user'] == 't')? $is_yes : $is_non);
		$fwd_to = $row['forward_to'];
		$disp_line = '<tr><td>' . $navgn_ref . '</td><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $fm_id. '">' . $html_name . '</a></td><td>' . $type_descr . '</td><td style="text-align:center;">' . $is_actv . '</td><td style="text-align:center;">' . $super_only . '</td><td style="text-align:center;">' . $vi_others . '</td><td style="text-align:center;">' . $fwd_to . '</td></tr>';
	}
	return $disp_line;				
}

?>