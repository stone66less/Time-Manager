<?php
// Either find an existing appoint_ments row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'actions/AppointMentsActions.php';
require_once 'actions/FormsFunctions.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$user_id = 0;
$rec_id = 0;
$vals_array = array();
$have_frmvals = FALSE;
$field_attributes = array();

if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}

ob_start();
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid']))  )  {
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
				$vals_array = appoint_field_array($dpgconn, $rec_id);
				if ( is_array($vals_array) )  {
					$val_count = count($vals_array);
					if ($val_count == 1)  {
						$err_line = $vals_array[0];
						echo '<p class="error">' . $err_line . '</p>';
					}  else  {
						$_SESSION['formvalues'] = $vals_array;
						$have_frmvals = TRUE;
					}
				}
			}
		}
	}
	if (!$have_frmvals)  {
		if ( isset($_SESSION['formvalues']) )  {
			unset($_SESSION['formvalues']);
		}
	}
	$form_name = 'appointmaint';
	$script_name = '../scripts_php/appointsmaint.php';
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '10', 'ml' => '10', 'af' => 'Y', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntDate'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '2', 'ml' => '2', 'min' => '0', 'max' => '24', 'af' => 'N', 'rq' => 'Y', 'ed' => 'N', 'br' => 'N');
	$field_attributes['appntHour'] = $inp_array;
	$inp_array = array('sd' => 'N', 'lc' => 'smalllabel', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '2', 'ml' => '2', 'min' => '0', 'max' => '59', 'af' => 'N', 'rq' => 'y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntMint'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '50', 'ml' => '128', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntSubj'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '50', 'ml' => '128', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntWith'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntDrtn'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntIntl'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '50', 'ml' => '128', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntWher'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '2', 'ml' => '2', 'min' => '0', 'max' => '24', 'af' => 'N', 'rq' => 'N', 'ed' => 'N', 'br' => 'N');
	$field_attributes['appntDepth'] = $inp_array;
	$inp_array = array('sd' => 'N', 'lc' => 'smalllabel', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '2', 'ml' => '2', 'min' => '0', 'max' => '59', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntDeptm'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['appntCanc'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'textarea', 'cl' => '*', 'uc' => 'N', 'sz' => '50', 'ml' => '5', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'N');
	$field_attributes['userNotes'] = $inp_array;
	$_SESSION['fattribs'] = $field_attributes;
	format_input($form_name, $script_name, $rec_id);
	unset($_SESSION['fattribs']);
	if ( isset($_SESSION['formvalues']) )  {
		unset($_SESSION['formvalues']);
	}
	if ( isset($_SESSION['errlabs']) )  {
		unset($_SESSION['errlabs']);
	}
}   else  {
	echo 'what the . . &#@ . ';
}
ob_end_flush();
?>