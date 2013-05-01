<?php
require_once 'includes/db_functions.php';
require_once 'includes/dispfunctions.php';
require_once 'classes/ErrorMessages.php';
require_once 'actions/ErrorMessagesActions.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$maint_go = 0;
$lim_it = 10;
$tab_capt = NULL;
$thtd_array = array();
$thtd_count = 0;
$pc_array = array();

$dpgconn = conn_db();
ob_start();
session_start();

if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fwdto']) )  {
	$maint_go = (int)$_SESSION['fwdto'];
}
if ( isset($_SESSION['syslim']) )  {
	$lim_it = (int)$_SESSION['syslim'];
}

if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
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
		$erm = new ErrorMessages();
		$max_rows = $erm->count_error_messages_by_lang($dpgconn, $rlang);
		if ($max_rows > 0)  {
			build_pc_array($max_rows, $lim_it);
			$_SESSION['errlang'] = $rlang;
			display_rows($dpgconn, $maint_go);
		}  else  {
			echo '<p class="error">There are NO Error Messages in this Language</p>';
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
			$erm = new ErrorMessages();
			$ermres = $erm->list_error_messages_by_lang($dbc, $rlang, $lim_it, $off_set);
			while($row = pg_fetch_assoc($ermres)) {
				$hlp_err = 'No';
				$not_err = 'No';
				$err_id = $row['erm_id'];
				$err_no = $row['error_number'];
				$err_msg = $row['error_messg'];
				$err_hlp = $row['error_help'];
				$err_not = $row['co_user_data'];
				if ( strlen($err_hlp) > 0)  {
					$hlp_err = 'Yes';
				}
				if ( strlen($err_not) > 0)  {
					$not_err = 'Yes';
				}
				echo '<tr><td><a href="../index.php?act=nav&nav=' . $maint_go . '&recid=' . $err_id . '">' . $err_no . '</a></td><td>' . $err_msg . '</td><td>' . $hlp_err . '</td><td>' . $not_err . '</td></tr>' . PHP_EOL;
			}
			table_list_end ($curr_page, $max_pages, $maint_go);
		}
	}
}

?>