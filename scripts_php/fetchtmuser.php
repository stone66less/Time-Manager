<?php
// Either find an existing time_user row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'actions/TimeUsersActions.php';
require_once 'actions/GroupRolesActions.php';
require_once 'actions/SupportedLanguagesActions.php';
require_once 'actions/FormsFunctions.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$user_id = 0;
$rec_id = 0;
$field_array = array();
$field_count = 0;
$vals_array = array();
$have_frmvals = FALSE;
$field_attributes = array();
$selopt_array = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['fieldarr']))  {
	$field_array = $_SESSION['fieldarr'];
	$field_count = $_SESSION['labcount'];
}
ob_start();
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_SESSION['recid']) && ($_SESSION['recid'] > 0) )  {
		$rec_id = (int)$_SESSION['recid'];
	}
	if ( isset($_SESSION['formvalues']) )  {
		if ( (is_array($_SESSION['formvalues'])) && (count($_SESSION['formvalues']) > 0 ) )  {
			$have_frmvals = TRUE;
		}
	}  else  {
		if (!$have_frmvals)  {
			if ( $rec_id > 0 ) {
				$vals_array = time_users_field_array($dpgconn, $rec_id);
				if ( (is_array($vals_array)) && (count($vals_array) > 1) )  {
					$_SESSION['formvalues'] = $vals_array;
					$have_frmvals = TRUE;
				}  else  {
					$err_line = $vals_array[0];
					echo '<p class="error">' . $err_line . '</p>';
				}
			}
		}
	}
	if (!$have_frmvals)  {
		if ( isset($_SESSION['formvalues']) )  {
			unset($_SESSION['formvalues']);
		}
	}
	$form_name = 'usermaint';
	$script_name = '../scripts_php/tmusermaint.php';
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'Y', 'sz' => '4', 'ml' => '4', 'af' => '1', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['logonId'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '40', 'ml' => '60', 'af' => '2', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['logonName'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'select', 'cl' => '*', 'uc' => 'N', 'sz' => '4', 'ml' => '4', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['grId'] = $inp_array;
	$this_grid = NULL;
	if ( ( isset($_SESSION['formvalues'])) && ( array_key_exists('grId', $_SESSION['formvalues'])) )  {
		$this_array = $_SESSION['formvalues'];
		$this_grid = $this_array['grId'];
	}
	$selopt_string = group_opt_list($dpgconn, $this_grid);
	$selopt_array['grId'] = $selopt_string;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'select', 'cl' => '*', 'uc' => 'N', 'sz' => '5', 'ml' => '5', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['langCode'] = $inp_array;
	$this_lang = NULL;
	if ( ( isset($_SESSION['formvalues'])) && ( array_key_exists('langCode', $_SESSION['formvalues'])) )  {
		$this_array = $_SESSION['formvalues'];
		$this_lang = $this_array['langCode'];
	}
	if ( ($field_count > 0) && (array_key_exists('firstOption', $field_array)) )   {
		$first_optn = $field_array['firstOption'];
	}  else  {
		$first_optn = 'Undefined';
	}
	$selopt_string = lang_opt_list($dpgconn, $first_optn, $this_lang);
	$selopt_array['langCode'] = $selopt_string;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['activeUser'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['superUser'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['sysgrpUser'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '40', 'ml' => '40', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['fixedIp'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['utcOffset'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '4', 'ml' => '4', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['phoneExtn'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '40', 'ml' => '256', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['emailAddr'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'textarea', 'cl' => '*', 'uc' => 'N', 'sz' => '50', 'ml' => '5', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'N');
	$field_attributes['userNotes'] = $inp_array;
	$_SESSION['fattribs'] = $field_attributes;
	$_SESSION['selopts'] = $selopt_array;
	format_input($form_name, $script_name, $rec_id);
	unset($_SESSION['fattribs']);
	unset($_SESSION['selopts']);
	if ( isset($_SESSION['formvalues']) )  {
		unset($_SESSION['formvalues']);
	}
	if ( isset($_SESSION['errlabs']) )  {
		unset($_SESSION['errlabs']);
	}
}   else  {
	echo 'what the . . . ';
}
ob_end_flush();
?>