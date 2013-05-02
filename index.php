<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'classes/FormsMenu.php';
require_once 'actions/TimeUsersActions.php';
require_once 'classes/ErrorMessages.php';

/* Time Manager controller.
	If $_SESSION variables are not set according to some rules,
	then it is cleared and the user is taken to the log-on page.
	All pages return here, and this controller will determine what to do next.
	Not worried about TTL here, let Apache handle it.
	Not worried about users NOT logging off.
	Access security is dealt within each page via an include script.
*/
$nextact = 0;
$unfund = FALSE;
$do_header = TRUE;
$callform = NULL;
$form_name = NULL;
$seems_ok = FALSE;
$alph_field = NULL;
$ob_error = NULL;
$on_error = FALSE;
$err_ob = 0;
$def_lang = 'en-GB';
$navgn_array = array();
$navarr_count = 0;
$navarr_add = TRUE;
$p_pos = 0;
$last_form = 0;
$last_act = 0;
$req_act = NULL;
$go_to = 0;
$rec_id = 0;
$s_user_id = 0;
$s_group_id = 0;
$rec_id = 0;

$dpgconn = conn_db();

// If user NOT logged on according to session variables, then go to log-on page.
if (isset($_SESSION['uname']) && isset($_SESSION['tmver']) && (strlen($_SESSION['uname']) > 0) ) {
// User appears to be logged-on.
	if ( isset($_SESSION['langcode'] ) && (strlen($_SESSION['langcode']) > 0) )  {
		$def_lang = $_SESSION['langcode'];
	}
	if ( isset($_SESSION['userid']) && ($_SESSION['userid'] > 0) )  {
		$s_user_id = (int)$_SESSION['userid'];
	}
	if ( isset($_SESSION['grid']) && ($_SESSION['grid'] > 0) )  {
		$s_group_id = (int)$_SESSION['grid'];
	}
	if ( ($s_user_id > 0) && ($s_group_id > 0) )  {
		$seems_ok = test_if_active($dpgconn, $s_user_id);
		if (!$seems_ok)  {
			$err_ob = 5;
		}  else  {

			if ( isset($_SESSION['navbar']))  {
				unset($_SESSION['navbar']);
			}
			if ( isset($_SESSION['pwdch']) && ($_SESSION['pwdch'] == 'Y') )  {
				$form_name = find_form($dpgconn, 'P', 9002);
			}  else  {
				if ( isset($_GET['act']) && (strlen($_GET['act']) > 0) )  {
					$act_ion = $_GET['act'];
					switch($act_ion) {
						case 'men':
							$form_name = setup_menu_call($dpgconn);
						break;
						case 'err':
							if ( isset($_SESSION['currform']) && ($_SESSION['currform'] > 0) )  {
								$fm_id = (int)$_SESSION['currform'];
							}  else  {
								$fm_id = 1;
							}
							$form_name = menu_by_id ($dpgconn, $fm_id);
							$navarr_add = FALSE;
						break;
						case 'for':
							if ( isset($_GET['nav']) && ($_GET['nav'] > 0) )  {
								$next_form = (int)$_GET['nav'];
								$form_name = menu_by_id ($dpgconn, $next_form);
								if ( isset($_GET['recid']) && (strlen($_GET['recid']) > 0) )  {
									$_SESSION['recid'] = (int)$_GET['recid'];
								}  else  {
									$_SESSION['recid'] = 0;
								}
							}  else  {
								$do_header = FALSE;
								$ob_error = set_error_messg($dpgconn, 9006, $def_lang);
								ob_output();
							}
						break;
						case 'nav':
							if ( isset($_GET['nav']) && ($_GET['nav'] > 0) )  {
								$next_form = (int)$_GET['nav'];
								$form_name = menu_by_navgn ($dpgconn, $next_form);
								if ( isset($_GET['recid']) && (strlen($_GET['recid']) > 0) )  {
									$_SESSION['recid'] = (int)$_GET['recid'];
								}  else  {
									$_SESSION['recid'] = 0;
								}
								if ( isset($_SESSION['alfid']) )  {
									unset($_SESSION['alfid']);
								}
								if ( isset($_GET['alfid']) && (strlen($_GET['alfid']) > 0) )  {
									$_SESSION['alfid'] = $_GET['alfid'];
								}
							}  else  {
								$do_header = FALSE;
								$ob_error = set_error_messg($dpgconn, 9007, $def_lang);
								ob_output();
							}
						break;
						case 'ret':
							$set_menu = FALSE;
							if (isset($_SESSION['navgnarr']))  {
								$navgn_array = $_SESSION['navgnarr'];
								$navarr_count = count($navgn_array);
								if ($navarr_count > 2)  {
									$nav_back = $navarr_count - 2;
									$arr_val = $navgn_array[$nav_back];
									$val_arr = explode('P',$arr_val);
									$form_id = $val_arr[0];
									$recd_id = $val_arr[1];
									$form_name = menu_by_id ($dpgconn, $form_id);
									$_SESSION['recid'] = $recd_id;
									$n_array = array_pop($navgn_array);
									$_SESSION['navgnarr'] = $navgn_array;
								}  else  {
									$set_menu = TRUE;
								}
							}  else  {
								$set_menu = TRUE;
							}
							if ($set_menu)  {
								$form_name = setup_menu_call($dpgconn);
							}
						break;
	
						default:
						$on_error = TRUE;
					}
				}  else  {
					$on_error = TRUE;
				}
			}  // force password change
		}	// of checking if user and group are still "active".
	}  else  {
		$on_error = TRUE;
	}
}  else  {
	$on_error = TRUE;
}

if ($on_error)  {
	$form_name = find_form($dpgconn, 'L', 9001);
}

if ( isset($_SESSION['fwdto']) )  {
	unset($_SESSION['fwdto']);
}
if ( isset($_SESSION['secnto']) )  {
	unset($_SESSION['secnto']);
}

if (!is_null($form_name))  {
	$form_parts = explode('|', $form_name);
	$go_to  = $form_parts[0];
	$form_n = $form_parts[1];
	$form_t = $form_parts[2];
	$err_ob = $form_parts[3];
	$fwd_to = $form_parts[4];
	$sec_to = $form_parts[5];
	$nav_br = $form_parts[6];
	$nav_rf = $form_parts[7];
	if ($go_to > 0)  {
		$callform = 'tmpages/' . $form_n;
		$_SESSION['htmlname'] = $form_n;
		if ($navarr_add)  {
			if (isset($_SESSION['navgnarr']))  {
				$navgn_array = $_SESSION['navgnarr'];
				$navarr_count = count($navgn_array);
			}  else  {
				$navgn_array = array();
				$navarr_count = 0;
			}
			$navgn_array[$navarr_count] = $go_to . 'P' . $rec_id;
			$_SESSION['navgnarr'] = $navgn_array;
		}
		$_SESSION['fwdto'] = $fwd_to;
		$_SESSION['secnto'] = $sec_to;
		$_SESSION['frmtyp'] = $form_t;
		$new_token = md5($go_to . mt_rand());
		$_SESSION['token'] = $new_token;
		$_SESSION['navrefn'] = $nav_rf;
		if ( !is_null($nav_br) )  {
			$_SESSION['navbar'] = $nav_br;
		}
		$_SESSION['strtime'] = $_SERVER['REQUEST_TIME'];
		$_SESSION['idletime'] = 5;  // in minutes
	}  else  {
		$do_header = FALSE;
		$ob_error = set_error_messg($dpgconn, $err_ob, $def_lang);
		ob_output();
	}
}  else  {
	$do_header = FALSE;
	$ob_error = set_error_messg($dpgconn, $err_ob, $def_lang);
	ob_output();
}

if ($do_header)  {
	$_SESSION['currform'] = $go_to;   // holds fm_id
	header("Location: $callform");
}  else  {
	ob_end_flush();
}
exit();

function setup_menu_call ($dbc)  {
	$menu_string = NULL;
	if (isset($_SESSION['navgnarr']))  {
		unset($_SESSION['navgnarr']);
	}
	if (isset($_SESSION['pcarray']))  {
		unset($_SESSION['pcarray']);
	}
	if ( isset($_SESSION['thtdarr']))  {
		unset($_SESSION['thtdarr']);
	}
	if ( isset($_SESSION['tabcapt']))  {
		unset($_SESSION['tabcapt']);
	}
	if ( isset($_SESSION['alfid']) )  {
		unset($_SESSION['alfid']);
	}
	if ( isset($_SESSION['headstr']) )  {
		unset($_SESSION['headstr']);
	}
	if ( isset($_SESSION['superu']) && ($_SESSION['superu'] == 'Y') )  {
		$menu_string = find_form($dbc, 'M', 9003);
	}  else  {
		$menu_string = find_form($dbc, 'D', 9004);
	}
	return $menu_string;
}

function find_form ($dbc, $f_type, $ob_err)  {
	$log_name = NULL;
	$fmus = new FormsMenu();
	$res  = $fmus->list_forms_menu_by_type ($dbc, $f_type);
	if ( ($res) && (pg_num_rows($res) == 1) )  {
		$row = pg_fetch_assoc($res);
		$log_name = $row['fm_id'] . '|' . $row['form_name'] . '|' . $f_type . '|0|0|0|' . $row['navgn_bar'] . '|' . $row['navgn_refn'];
	}  else  {
		$log_name = '0|0|0|' . $ob_err . '|0|0|0|0';
	}
	return $log_name;
}

function menu_by_id ($dbc, $fm_id)  {
	$log_name = NULL;
	$fmus = new FormsMenu();
	$res  = $fmus->find_forms_menu_by_id ($dbc, $fm_id);
	if ( ($res) )  {
		$f_id = $fmus->getFmId();
		$f_nm = $fmus->getFormName();
		$f_typ = $fmus->getFormType();
		$f_fwd = $fmus->getForwardTo();
		$f_sec = $fmus->getSecondTo();
		$f_nbr = $fmus->getNavgnBar();
		$f_nref = $fmus->getNavgnRefn();
		$log_name = $f_id  . '|' . $f_nm . '|' . $f_typ . '|0|'. $f_fwd . '|' . $f_sec . '|' . $f_nbr . '|' . $f_nref;
	}  else  {
		$log_name = '0|0|0|9006|0|0|0|0';
	}
	return $log_name;
}

function menu_by_navgn ($dbc, $nav_id)  {
	$log_name = NULL;
	$fmus = new FormsMenu();
	$res  = $fmus->find_forms_menu_by_navgn ($dbc, $nav_id);
	if ( ($res) )  {
		$f_id = $fmus->getFmId();
		$f_nm = $fmus->getFormName();
		$f_typ = $fmus->getFormType();
		$f_fwd = $fmus->getForwardTo();
		$f_sec = $fmus->getSecondTo();
		$f_nbr = $fmus->getNavgnBar();
		$log_name = $f_id  . '|' . $f_nm . '|' . $f_typ . '|0|'. $f_fwd . '|' . $f_sec . '|' . $f_nbr . '|' . $nav_id;
	}  else  {
		$log_name = '0|0|0|9007|0|0|0|0';
	}
	return $log_name;
}

function ob_output ()  {
global $ob_error;
	ob_start();
	echo '<!DOCTYPE html>' . PHP_EOL;
	echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en-GB" xml:lang="en-GB" dir="ltr">';
	echo '<head><title>Time Manager FAILURE</title><meta http-equiv="content-type" content="text/html; charset=UTF-8" />' . PHP_EOL;
	echo '<meta name="author" content="rob stone" /><meta name="copyright" content="rob stone" />' . PHP_EOL;
	echo '</head><body><div style="margin:1em auto;width:980px;text-align:left;background:#fff;border:1px solid #676767;">' . PHP_EOL;
	echo '<div style="float:left;width:100%;background:#FFF;">' . PHP_EOL;
	echo '<div style="text-align:center; color:red;"><p>' . PHP_EOL;
	echo $ob_error;
	echo '</p></div></div></div></body></html>' . PHP_EOL;
}

function set_error_messg ($dbc, $errno, $lang)  {
	$error_text = NULL;
	$erm = new ErrorMessages();
	$ermres = $erm->find_error($dbc, $errno, $lang);
	if ($ermres)  {
		$error_text = $erm->getErrorMessg();
	}  else  {
		$error_text = 'FAILED TO FIND ERROR ' .  $errno . ' ' . $lang;
	}
	return $error_text;
}

?>