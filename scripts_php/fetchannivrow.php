<?php
// Either find an existing anni_versaries row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'actions/AnniVersariesActions.php';
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
				$vals_array = anniv_field_array($dpgconn, $rec_id);
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
	$form_name = 'annivmaint';
	$script_name = '../scripts_php/anniversmaint.php';
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '40', 'ml' => '60', 'af' => 'Y', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['annDesc'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '2', 'ml' => '2', 'min' => '1', 'max' => '31', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['annTday'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '2', 'ml' => '2', 'min' => '1', 'max' => '12', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['annMonth'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['isActive'] = $inp_array;
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
	echo 'what the . . . ';
}
ob_end_flush();
?>