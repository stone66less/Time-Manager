<?php
// Fetch all Forms Menu rows in navigation reference sequence and place into
// a table display.
require_once 'includes/db_functions.php';
require_once 'includes/dispfunctions.php';
require_once 'classes/FormsMenu.php';
require_once 'actions/FormsMenuActions.php';
require_once 'actions/ErrorMessagesActions.php';

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
$forms_types = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}


$dpgconn = conn_db();
reset($_POST);
if ( isset($_POST['drctn']) && (strlen($_POST['drctn']) > 0) )  {
	$act_ion = $_POST['drctn'];
	session_start();
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
	switch($act_ion) {
		case 'prev':
		$disp_prev = do_prev_block();
		if ($disp_prev)  {
			echo $_SESSION['headstr'] . PHP_EOL;
			display_rows($dpgconn, $maint_go, $def_lang);
		}
		break;
		case 'next':
		$disp_next = do_next_block();
		if ($disp_next)  {
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
		$fmen = new FormsMenu();
		$no_of_forms = $fmen->count_forms_menu($dpgconn);
		if ($no_of_forms > 0)  {
			build_pc_array($no_of_forms, $sys_lim);
			display_rows($dpgconn, $maint_go, $def_lang);
		}  else  {
			echo '<p class="error">There are no menu items in the database.</p>';
		}
	}
}


function display_rows ($dbc, $maint_go, $lang)  {
	if ( (isset($_SESSION['pcarray']))   )  {
		$pc_array = $_SESSION['pcarray'];
		if ( (is_array($pc_array))  )  {
			ob_start();
			$max_pages = $pc_array['max'];
			$curr_page = $pc_array['cur'];
			$off_set   = $pc_array['off'];
			$lim_it    = $pc_array['lim'];
			$fmen = new FormsMenu();
			$result = $fmen->list_all_forms_menu($dbc, $lim_it, $off_set);
			while ($row = pg_fetch_assoc($result))   {
				$fm_id = $row['fm_id'];
				$line_out = build_display_line ($dbc, $fm_id, $maint_go, $lang);
				echo $line_out . PHP_EOL;
		   }
		   table_list_end ($curr_page, $max_pages, $maint_go);
			ob_end_flush();
		}
	}
}

?>