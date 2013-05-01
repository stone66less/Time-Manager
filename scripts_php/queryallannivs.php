<?php
// Fetch all Anniversaries pertaininbg to a specific User and place into
// a table display.
require_once 'includes/db_functions.php';
require_once 'includes/dispfunctions.php';
require_once 'classes/AnniVersaries.php';
require_once 'actions/AnniVersariesActions.php';
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
$user_id = 0;
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}

$user_id = $_SESSION['userid'];
$say_yes = $_SESSION['yestxt'];
$say_non = $_SESSION['nontxt'];
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
	$user_id = $_SESSION['userid'];
	$say_yes = $_SESSION['yestxt'];
	$say_non = $_SESSION['nontxt'];
	switch($act_ion) {
		case 'prev':
		$disp_prev = do_prev_block();
		if ($disp_prev)  {
			echo $_SESSION['headstr'] . PHP_EOL;
			display_rows($dpgconn, $user_id, $maint_go, $say_yes, $say_non);
		}
		break;
		case 'next':
		$disp_next = do_next_block();
		if ($disp_next)  {
			echo $_SESSION['headstr'] . PHP_EOL;
			display_rows($dpgconn, $user_id, $maint_go, $say_yes, $say_non);
		}
		break;
		default:
		echo '<p class="error">Invalid switch action ' . $act_ion . '</p>';
	}
}  else  {
// first time thru	
	$tab_capt = $_SESSION['tabcapt'];
	$thtd_array = $_SESSION['thtdarr'];
	$say_yes = $_SESSION['yestxt'];
	$say_non = $_SESSION['nontxt'];
	$head_string = setup_header($tab_capt, $thtd_array);
	if ( is_null($head_string) )  {
		$err_text = return_error($dpgconn, 9011, $def_lang);
		echo $err_text;
	}  else  {
		echo $head_string . PHP_EOL;
		$_SESSION['headstr'] = $head_string;
		$annivs = new AnniVersaries();
		$no_of_forms = $annivs->count_anni_versaries($dpgconn, $user_id);
		if ($no_of_forms > 0)  {
			build_pc_array($no_of_forms, $sys_lim);
			display_rows($dpgconn, $user_id, $maint_go, $say_yes, $say_non);
		}  else  {
			echo '<p class="error">There are no Anniversaries in the database for this User.</p>';
		}
	}
}


function display_rows ($dbc, $user_id, $maint_go, $say_yes, $say_non)  {
	if ( (isset($_SESSION['pcarray']))   )  {
		$pc_array = $_SESSION['pcarray'];
		if ( (is_array($pc_array))  )  {
			ob_start();
			$max_pages = $pc_array['max'];
			$curr_page = $pc_array['cur'];
			$off_set   = $pc_array['off'];
			$lim_it    = $pc_array['lim'];
			$annivs = new AnniVersaries();
			$result = $annivs->list_all_anniversaries($dbc, $user_id, $lim_it, $off_set);
			while ($row = pg_fetch_assoc($result))   {
				$line_out = build_display_line ($row, $maint_go, $say_yes, $say_non);
				echo $line_out . PHP_EOL;
		   }
		   table_list_end ($curr_page, $max_pages, $maint_go);
			ob_end_flush();
		}
	}
}

?>