<?php
// Fetch all time_users rows in name sequence and place into
// a table display.
require_once 'includes/db_functions.php';
require_once 'includes/dispfunctions.php';
require_once 'classes/TimeUsers.php';
require_once 'actions/ErrorMessagesActions.php';
require_once 'classes/UserGroupLang.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$maint_go = 0;
$tab_capt = NULL;
$thtd_array = array();
$thtd_count = 0;
$first_time = TRUE;
$alt_row = TRUE;
$item_count = 0;
$sys_lim = 10;
$yes_txt = 'Yes';
$non_txt = 'No';

if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}
if ( isset($_SESSION['syslim']) && ($_SESSION['syslim'] > 0) )  {
	$sys_lim = (int)$_SESSION['syslim'];
}

$dpgconn = conn_db();
reset($_POST);
if ( isset($_POST['drctn']) && (strlen($_POST['drctn']) > 0) )  {
	$act_ion = $_POST['drctn'];
	switch($act_ion) {
		case 'prev':
		$do_prev = do_prev_block;
		if ($do_prev)  {
			echo $_SESSION['headstr'] . PHP_EOL;
			display_rows($dpgconn, $maint_go, $def_lang);
		}
		break;
		case 'next':
		$do_next = do_next_block;
		if ($do_next)  {
			echo $_SESSION['headstr'] . PHP_EOL;
			display_rows($dpgconn, $maint_go, $def_lang);
		}
		break;
		default:
		echo '<p class="error">Invalid switch action ' . $act_ion . '</p>';
	}
}  else  {
// first time thru	
	$tab_capt = $_SESSION['tabcapt'];
	$thtd_array = $_SESSION['thtdarr'];
	$head_string = setup_header($tab_capt, $thtd_array);
	if ( is_null($head_string) )  {
		$err_text = return_error($dpgconn, 9011, $def_lang);
		echo $err_text;
	}  else  {
		echo $head_string . PHP_EOL;
		$_SESSION['headstr'] = $head_string;
		$tusers = new TimeUsers();
		$no_of_users = $tusers->count_time_users($dpgconn);
		if ($no_of_users > 0)  {
			build_pc_array($no_of_users, $sys_lim);
			display_rows($dpgconn, $maint_go, $def_lang);
		}  else  {
			echo '<p class="error">There are no users in the database.</p>';
		}
	}
}


function display_rows ($dbc, $maint_go, $lang)  {
	if ( (isset($_SESSION['pcarray']))   )  {
		$pc_array = $_SESSION['pcarray'];
		if ( (is_array($pc_array))  )  {
			if (isset($_SESSION['yestxt'])) {
				$yes_txt = $_SESSION['yestxt'];
			}
			if ( isset($_SESSION['nontxt'])) {
				$non_txt = $_SESSION['nontxt'];
			}
			ob_start();
			$max_pages = $pc_array['max'];
			$curr_page = $pc_array['cur'];
			$off_set   = $pc_array['off'];
			$lim_it    = $pc_array['lim'];
			$order_by = '3';
			$tus = new UserGroupLang();
			$result = $tus->list_all_users($dbc, $lim_it, $off_set, $order_by);
			while ($row = pg_fetch_assoc($result))   {
				$tu_id = $row['tu_id'];
				$logon_id = $row['logon_id'];
				$logon_name = $row['logon_name'];
				$gr_name = $row['gr_name'];
				$lang_name = $row['lang_name'];
				$active_user = ( ($row['active_user'])? $yes_txt : $non_txt);
				$sysgrp_user = ( ($row['sysgrp_user'])? $yes_txt : $non_txt);
				$disp_line = '<tr><td>' . $logon_id . '</td><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $tu_id. '">' . $logon_name . '</a></td><td>' . $gr_name . '</td><td>' . $lang_name . '</td><td class="centred">' . $active_user . '</td><td class="centred">' . $sysgrp_user . '</td></tr>';
				echo $disp_line . PHP_EOL;
		   }
		   table_list_end ($curr_page, $max_pages, $maint_go);
			ob_end_flush();
		}
	}
}

?>