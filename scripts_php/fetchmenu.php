<?php
// Either find an existing forms_menu row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'classes/FormsMenu.php';
require_once 'actions/FormTypesActions.php';

$def_lang = 'en-GB';
$goto_men = '../index.php?act=men';
$nav_refn = 0;
$legnd_text = NULL;
$field_text = NULL;
$buttn_text = NULL;
$user_id = 0;
$rec_id = 0;
$sav_navgn_refn = 0;
$sav_form_name = NULL;
$sav_active_item = FALSE;
$sav_super_user = FALSE;
$sav_sysgrp_user = FALSE;
$sav_form_type = NULL;
$sav_navgn_bar = NULL;
$sav_forward_to = 0;
$sav_second_to = 0;
$user_notes = NULL;
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
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_SESSION['recid']) && ($_SESSION['recid'] > 0) )  {
		$rec_id = (int)$_SESSION['recid'];
	}
	if ( isset($_SESSION['formvalues']) )  {
		if ( count($_SESSION['formvalues']) > 0 )  {
			$vals_array = $_SESSION['formvalues'];
			$have_frmvals = TRUE;
			if ( array_key_exists('navRefn', $vals_array) )  {
				$sav_navgn_refn = $vals_array['navRefn'];
			}
			if ( array_key_exists('formName', $vals_array) )  {
				$sav_form_name = $vals_array['formName'];
			}
			if ( array_key_exists('frmTyp', $vals_array) )  {
				$sav_form_type = $vals_array['frmTyp'];
			}
			if ( array_key_exists('actvItem', $vals_array) )  {
				$sav_active_item = $vals_array['actvItem'];
			}
			if ( array_key_exists('superOnly', $vals_array) )  {
				$sav_super_user = $vals_array['superOnly'];
			}
			if ( array_key_exists('grpUser', $vals_array) )  {
				$sav_sysgrp_user = $vals_array['grpUser'];
			}
			if ( array_key_exists('navBar', $vals_array) )  {
				$sav_navgn_bar = $vals_array['navBar'];
			}
			if ( array_key_exists('fwdTo', $vals_array) )  {
				$sav_forward_to = $vals_array['fwdTo'];
			}
			if ( array_key_exists('secnTo', $vals_array) )  {
				$sav_second_to = $vals_array['secnTo'];
			}
			if ( array_key_exists('uNotes', $vals_array) )  {
				$user_notes = $vals_array['uNotes'];
			}
		}
		unset($_SESSION['formvalues']);
	}  else  {
		if (!$have_frmvals)  {
			if ( ($rec_id > 0) ) {
				$fmenu = new FormsMenu();
				$fmres = $fmenu->find_forms_menu_by_id($dpgconn, $rec_id);
				if ($fmres)  {
					$sav_navgn_refn = $fmenu->getNavgnRefn();
					$sav_form_name = $fmenu->getFormName();
					$sav_active_item = $fmenu->getActiveItem();
					$sav_super_user = $fmenu->getSuperUser();
					$sav_sysgrp_user = $fmenu->getSysgrpUser();
					$sav_form_type = $fmenu->getFormType();
					$sav_navgn_bar = $fmenu->getNavgnBar();
					$sav_forward_to = $fmenu->getForwardTo();
					$sav_second_to = $fmenu->getSecondTo();
					$user_notes = html_entity_decode($fmenu->getCoUserData(), ENT_QUOTES, 'UTF-8');
				}  else  {
					echo '<p class="error">Unable to find Menu Item ' . $rec_id .  '</p>';	
				}
			}
		}
	}
	ob_start();
	echo '<form id="menumaint" name="menumaint" action="../scripts_php/maintformsmenu.php" method="post">';
	echo '<fieldset><legend>' . $legnd_text . '</legend>';
	echo '<input type="hidden" id="token" name="token" value="' . $_SESSION['token'] . '" />';
	echo '<input type="hidden" id="recId" name="recId" value="' . $rec_id . '" />';
	echo '<div><label for="navRefn">' . $field_array['navRefn'] . ' : </label>';
	echo '<input type="number" id="navRefn" name="navRefn" class="numb" size="6" maxlength="6" required="required" autofocus="autofocus" min="1" max="99999" value="' . $sav_navgn_refn . '" /></div><br />';
	echo '<div><label for="formName">' . $field_array['formName'] . ' :</label>';
	echo '<input type="text" id="formName" name="formName" required="required" size="40" maxlength="40" value="' . $sav_form_name . '" /></div><br />' . PHP_EOL;
	echo '<div><label for="frmTyp">' . $field_array['frmTyp'] . ' :</label><select id="frmTyp" name="frmTyp" >' . PHP_EOL;
	$opt_list = form_type_opt_list($dpgconn, $def_lang, $sav_form_type);
	echo $opt_list . '</select></div><br />' . PHP_EOL;
	echo '<div><label for="actvItem">' . $field_array['actvItem'] . ' :</label>';
	echo '<input type="checkbox" id="actvItem" name="actvItem" ';
	if ( $sav_active_item )  {
		echo ' checked="checked" ';
	}
	echo ' /></div><br />';
	echo '<div><label for="superOnly">' . $field_array['superOnly'] . ' :</label>';
	echo '<input type="checkbox" id="superOnly" name="superOnly" ';
	if ( $sav_super_user )  {
		echo ' checked="checked" ';
	}
	echo ' /></div><br />';
	echo '<div><label for="grpUser">' . $field_array['grpUser'] . ' :</label>';
	echo '<input type="checkbox" id="grpUser" name="grpUser" ';
	if ( $sav_sysgrp_user )  {
		echo ' checked="checked" ';
	}
	echo ' /></div><br />';
	echo '<div><label for="navBar">' . $field_array['navBar'] . ' :</label>';
	echo '<input type="text" id="navBar" name="navBar" size="40" maxlength="160" value="' . $sav_navgn_bar . '" /></div><br />';
	echo '<div><label for="fwdTo">' . $field_array['fwdTo'] . ' :</label>';
	echo '<input type="number" class="numb" id="fwdTo" name="fwdTo" size="3" maxlength="4" value="' . $sav_forward_to . '" /></div><br />';
	echo '<div><label for="secnTo">' . $field_array['secnTo'] . ' :</label>';
	echo '<input type="number" class="numb" id="secnTo" name="secnTo" size="3" maxlength="4" value="' . $sav_second_to . '" /></div><br />';
	echo '<div><label for="uNotes">' . $field_array['uNotes'] . ' :</label>';
	echo '<textarea id="uNotes" name="uNotes" cols="60" rows="4" wrap >' . $user_notes . '</textarea></div>' . PHP_EOL;
	echo '</fieldset><p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" class="buttonSubmit" />';
	echo '<input type="reset"  name="reset" value="' . $buttn_array['reset'] . '" class="buttonReset" /></p></form>' . PHP_EOL;
	ob_end_flush();
}   else  {
	header("Location: $goto_men");
	exit();
}
?>