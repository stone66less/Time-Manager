<?php
// Either find an existing group_role for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'classes/GroupRoles.php';
require_once 'actions/FormsFunctions.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$legnd_text = NULL;
$field_text = NULL;
$buttn_text = NULL;
$user_id = 0;
$rec_id = 0;
$field_array = array();
$field_count = 0;
$buttn_array = array();
$buttn_count = 0;
$vals_array = array();
$have_frmvals = FALSE;
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['flegend']))  {
	$legnd_text = $_SESSION['flegend'];
}
if ( isset($_SESSION['fieldarr']))  {
	$field_array = $_SESSION['fieldarr'];
	$field_count = $_SESSION['labcount'];
}
if ( isset($_SESSION['buttarr']))  {
	$buttn_array = $_SESSION['buttarr'];
	$buttn_count = $_SESSION['buttcount'];
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
				$grp = new GroupRoles();
				$grpres = $grp->find_group_roles_by_id($dpgconn, $rec_id);
				if ($grpres)  {
					$vals_array['grName']   = $grp->getGrName();
					$vals_array['supUser']  = $grp->getSuperUser();
					$vals_array['viOthers'] = $grp->getViewOthers();
					$vals_array['grpInuse'] = $grp->getGroupInuse();
					$vals_array['pwdDays']  = $grp->getChgPword();
					$vals_array['anvLmt']   = $grp->getAnnivLimit();
					$vals_array['aptLmt']   = $grp->getAppntLimit();
					$vals_array['tskLmt']   = $grp->getTasksLimit();
					$vals_array['sysLmt']   = $grp->getSysadmLmt();
					$vals_array['uNotes']   = $grp->getCoUserData();
					$_SESSION['formvalues'] = $vals_array;
					$have_frmvals = TRUE;
				}  else  {
					echo '<p class="error">Unable to find Group Role ' . $rec_id . '</p>';	
				}
			}  else  {
				if ( isset($_SESSION['formvalues']) )  {
					unset($_SESSION['formvalues']);
				}
			}
		}
	}
	echo '<form id="rolemaint" name="rolemaint" action="../scripts_php/maintrole.php" method="post">';
	echo '<fieldset><legend>' . $legnd_text . '</legend>';
	echo '<input type="hidden" id="recId" name="recId" value="' . $rec_id . '" />';
	echo '<input type="hidden" id="token" name="token" value="' . $_SESSION['token'] . '" />' . PHP_EOL;
	
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'uc' => 'N', 'sz' => '40', 'ml' => '60', 'af' => 'Y', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['grName'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['supUser'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['viOthers'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'checkbox', 'cl' => '*', 'uc' => 'N', 'sz' => '1', 'ml' => '1', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['grpInuse'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['pwdDays'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['anvLmt'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['aptLmt'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['tskLmt'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'number', 'cl' => 'numb', 'uc' => 'N', 'sz' => '3', 'ml' => '3', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'Y');
	$field_attributes['sysLmt'] = $inp_array;
	$inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'textarea', 'cl' => '*', 'uc' => 'N', 'sz' => '50', 'ml' => '5', 'af' => 'N', 'rq' => 'N', 'ed' => 'Y', 'br' => 'N');
	$field_attributes['uNotes'] = $inp_array;
	$_SESSION['fattribs'] = $field_attributes;
	format_input($have_frmvals);
	echo '</fieldset><p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" class="buttonSubmit" />';
	echo '<input type="reset"  name="reset" value="' . $buttn_array['reset'] . '" class="buttonReset" /></p></form>' . PHP_EOL;
	unset($_SESSION['fattribs']);
	if ( isset($_SESSION['formvalues']) )  {
		unset($_SESSION['formvalues']);
	}
	if ( isset($_SESSION['errlabs']) )  {
		unset($_SESSION['errlabs']);
	}
}   else  {
	echo 'what';
}
ob_end_flush();
?>