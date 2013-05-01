<?php
require_once 'includes/db_functions.php';
require_once 'includes/dispfunctions.php';
require_once 'classes/BoilerPlate.php';
require_once 'actions/ErrorMessagesActions.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$maint_go = 0;
$tab_capt = NULL;
$thtd_array = array();
$thtd_count = 0;
$pc_array = array();
$lim_it = 10;

$dpgconn = conn_db();
ob_start();
session_start();

if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['syslim']) && ($_SESSION['syslim'] > 0) )  {
	$lim_it = (int)$_SESSION['syslim'];
}
reset($_POST);
$k_one = key($_POST);
$v_one = current($_POST);
list($rlang,$actn) = explode('|', $v_one);
switch($actn) {
	case 'start':
	$tab_capt = $_SESSION['tabcapt'];
	$thtd_array = $_SESSION['thtdarr'];
	$head_string = setup_header($tab_capt, $thtd_array);
	if ( is_null($head_string) )  {
		$err_text = return_error($dpgconn, 9011, $def_lang);
		echo $err_text;
	}  else  {
		echo $head_string . PHP_EOL;
		$_SESSION['headstr'] = $head_string;
		$boilp = new BoilerPlate();
		$max_rows = $boilp->count_boiler_plate_by_lang ($dpgconn, $rlang);
		if ($max_rows > 0)  {
			build_pc_array($max_rows, $lim_it);
			$_SESSION['errlang'] = $rlang;
			display_rows($dpgconn, $maint_go);
		}  else  {
			echo '<p class="error">There are NO Boiler Plate rows in this Language</p>';
		}
	}
	break;
	case 'next':
	$disp_next = do_next_block();
	if ($disp_next)  {
		echo $_SESSION['headstr'] . PHP_EOL;
		display_rows($dpgconn, $maint_go);
	}
	break;
	case 'prev':
	$disp_prev = do_prev_block();
	if ($disp_prev)  {
		echo $_SESSION['headstr'] . PHP_EOL;
		display_rows($dpgconn, $maint_go);
	}
	break;
	default:
	echo '<p class="error">Invalid switch action request ' . $actn . '<p>';;
}
ob_end_flush();

function display_rows ($dbc, $maint_go)  {
	if ( (isset($_SESSION['pcarray']))  &&  (isset($_SESSION['errlang'])) )  {
		$pc_array = $_SESSION['pcarray'];
		$rlang = $_SESSION['errlang'];
		if ( (is_array($pc_array)) && (strlen($rlang) > 0) )  {
			$max_pages = $pc_array['max'];
			$curr_page = $pc_array['cur'];
			$off_set   = $pc_array['off'];
			$lim_it    = $pc_array['lim'];
			$rlang = $_SESSION['errlang'];	
			$bpl = new BoilerPlate();
			$bplres = $bpl->list_boiler_plate_by_lang($dbc, $rlang, $lim_it, $off_set);
			while($row = pg_fetch_assoc($bplres)) {
				$bp_id = $row['bp_id'];
				$nav_ref = $row['navgn_refn'];
				$page_title = $row['page_title'];
				$head_one = $row['heading_one'];
				$leg_end = $row['leg_end'];
				if ( strlen($row['co_user_data']) > 0 )  {
					$user_notes = 'Yes';
				}  else  {
					$user_notes = 'No';
				}
				echo '<tr><td>' . $nav_ref . '</td><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $bp_id . '">' . $page_title . '</a></td><td>' . $head_one . '</td><td>' . $leg_end . '</td><td>' . $user_notes . '</td></tr>' . PHP_EOL;
			}
			table_list_end ($curr_page, $max_pages, $maint_go);
		}
	}
}
?>