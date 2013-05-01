<?php
// Either find an existing boiler_plate row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'classes/BoilerPlate.php';
require_once 'actions/SupportedLanguagesActions.php';
require_once 'actions/FormsMenuActions.php';
$def_lang = 'en-GB';
$goto_men = '../index.php?act=men';
$nav_refn = 0;
$legnd_text = NULL;
$field_text = NULL;
$buttn_text = NULL;
$user_id = 0;
$rec_id = 0;
$sav_nav_refn = NULL;
$sav_lang_code = NULL;
$sav_page_title = NULL;
$sav_heading_one = NULL;
$sav_heading_two = NULL;
$sav_heading_tre = NULL;
$sav_heading_qua = NULL;
$sav_heading_cin = NULL;
$sav_heading_six = NULL;
$sav_navign_bar  = NULL;
$sav_capt_ions   = NULL;
$sav_thtd_cells  = NULL;
$sav_leg_end     = NULL;
$sav_form_fields = NULL;
$sav_subt_buttons = NULL;
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

$dpgconn = conn_db();
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
			if ( array_key_exists('bplLang', $vals_array) )  {
				$sav_lang_code = $vals_array['bplLang'];
			}
			if ( array_key_exists('pgTitl', $vals_array) )  {
				$sav_page_title = $vals_array['pgTitl'];
			}
			if ( array_key_exists('headOne', $vals_array) )  {
				$sav_heading_one = $vals_array['headOne'];
			}
			if ( array_key_exists('headTwo', $vals_array) )  {
				$sav_heading_two = $vals_array['headTwo'];
			}
			if ( array_key_exists('headTre', $vals_array) )  {
				$sav_heading_tre = $vals_array['headTre'];
			}
			if ( array_key_exists('headQua', $vals_array) )  {
				$sav_heading_qua = $vals_array['headQua'];
			}
			if ( array_key_exists('headCin', $vals_array) )  {
				$sav_heading_cin = $vals_array['headCin'];
			}
			if ( array_key_exists('headSix', $vals_array) )  {
				$sav_heading_six = $vals_array['headSix'];
			}
			if ( array_key_exists('navBar', $vals_array) )  {
				$sav_navign_bar = $vals_array['navBar'];
			}
			if ( array_key_exists('capTons', $vals_array) )  {
				$sav_capt_ions = $vals_array['capTons'];
			}
			if ( array_key_exists('thtdCells', $vals_array) )  {
				$sav_thtd_cells = $vals_array['thtdCells'];
			}
			if ( array_key_exists('legEnd', $vals_array) )  {
				$sav_leg_end = $vals_array['legEnd'];
			}
			if ( array_key_exists('formFields', $vals_array) )  {
				$sav_form_fields = $vals_array['formFields'];
			}
			if ( array_key_exists('subtButt', $vals_array) )  {
				$sav_subt_buttons = $vals_array['subtButt'];
			}
			if ( array_key_exists('uNotes', $vals_array) )  {
				$user_notes = $vals_array['uNotes'];
			}
		}
		unset($_SESSION['formvalues']);
	}  else  {
		if (!$have_frmvals)  {
			if ( ($rec_id > 0) ) {
				$fboil = new BoilerPlate();
				$fbres = $fboil->find_boiler_plate_by_id($dpgconn, $rec_id);
				if ($fbres)  {
					$sav_nav_refn = $fboil->getNavgnRefn();
					$sav_lang_code = $fboil->getLangCode();
					$sav_page_title = $fboil->getPageTitle();
					$sav_heading_one = $fboil->getHeadingOne();
					$sav_heading_two = $fboil->getHeadingTwo();
					$sav_heading_tre = $fboil->getHeadingTre();
					$sav_heading_qua = $fboil->getHeadingQua();
					$sav_heading_cin = $fboil->getHeadingCin();
					$sav_heading_six = $fboil->getHeadingSix();
					$sav_navign_bar  = $fboil->getNavignBar();
					$sav_capt_ions   = $fboil->getCaptIons();
					$sav_thtd_cells  = $fboil->getThtdCells();
					$sav_leg_end     = $fboil->getLegEnd();
					$sav_form_fields = $fboil->getFormFields();
					$sav_subt_buttons = $fboil->getSubtButtons();
					$user_notes = $fboil->getCoUserData();
				}  else  {
					echo '<p class="error">Unable to find Boiler Plate ' . $rec_id .  '</p>';	
				}
			}
		}
	}
	ob_start();
	echo '<form id="boilmaint" name="boilmaint" action="../scripts_php/maintboilplate.php" method="post">';
	echo '<fieldset><legend>' . $legnd_text . '</legend>';
	echo '<input type="hidden" id="token" name="token" value="' . $_SESSION['token'] . '" />';
	echo '<input type="hidden" id="recId" name="recId" value="' . $rec_id . '" />';
	echo '<div><label for="bplLang">' . $field_array['bplLang'] . ' :</label><select id="bplLang" name="bplLang" >';
	$avail_lang = lang_opt_list ($dpgconn, $field_array['firstOption'], $sav_lang_code);
	echo $avail_lang . '</select></div><br />' . PHP_EOL;
	echo '<div><label for="formName">' . $field_array['formName'] . ' :</label><select id="formName" name="formName" >';
	$form_list = forms_menu_opt_list ($dpgconn, $sav_nav_refn);
	echo $form_list . '</select></div><br />' . PHP_EOL;
	echo '<div><label for="pgTitl">' . $field_array['pgTitl'] . ' :</label>';
	echo '<input type="text" id="pgTitl" name="pgTitl" size="40" maxlength="80" value="' . $sav_page_title . '" /></div><br />';
	echo '<div><label for="headOne">' . $field_array['headOne'] . ' :</label>';
	echo '<input type="text" id="headOne" name="headOne" size="40" maxlength="80" value="' . $sav_heading_one . '" /></div><br />';
	echo '<div><label for="headTwo">' . $field_array['headTwo'] . ' :</label>';
	echo '<input type="text" id="headTwo" name="headTwo" size="40" maxlength="80" value="' . $sav_heading_two . '" /></div><br />';
	echo '<div><label for="headTre">' . $field_array['headTre'] . ' :</label>';
	echo '<input type="text" id="headTre" name="headTre" size="40" maxlength="80" value="' . $sav_heading_tre . '" /></div><br />';
	echo '<div><label for="headQua">' . $field_array['headQua'] . ' :</label>';
	echo '<input type="text" id="headQua" name="headQua" size="40" maxlength="80" value="' . $sav_heading_qua . '" /></div><br />';
	echo '<div><label for="headCin">' . $field_array['headCin'] . ' :</label>';
	echo '<input type="text" id="headCin" name="headCin" size="40" maxlength="80" value="' . $sav_heading_cin . '" /></div><br />';
	echo '<div><label for="headSix">' . $field_array['headSix'] . ' :</label>';
	echo '<input type="text" id="headSix" name="headSix" size="40" maxlength="80" value="' . $sav_heading_six . '" /></div><br />' . PHP_EOL;

	echo '<div><label for="navBar">' . $field_array['navBar'] . ' :</label>';
	echo '<textarea id="navBar" name="navBar" cols="50" rows="8" wrap >' . $sav_navign_bar . '</textarea></div><br />';
	echo '<div><label for="capTons">' . $field_array['capTons'] . ' :</label>';
	echo '<input type="text" id="capTons" name="capTons" size="40" maxlength="80" value="' . $sav_capt_ions . '" /></div><br />';
	echo '<div><label for="thtdCells">' . $field_array['thtdCells'] . ' :</label>';
	echo '<textarea id="thtdCells" name="thtdCells" cols="50" rows="8" wrap >' . $sav_thtd_cells . '</textarea></div><br />';

	echo '<div><label for="legEnd">' . $field_array['legEnd'] . ' :</label>';
	echo '<input type="text" id="legEnd" name="legEnd" size="40" maxlength="80" value="' . $sav_leg_end . '" /></div><br />' . PHP_EOL;

	echo '<div><label for="formFields">' . $field_array['formFields'] . ' :</label>';
	echo '<textarea id="formFields" name="formFields" cols="50" rows="16" wrap >' . $sav_form_fields . '</textarea></div><br />';

	echo '<div><label for="subtButt">' . $field_array['subtButt'] . ' :</label>';
	echo '<textarea id="subtButt" name="subtButt" cols="50" rows="8" wrap >' . $sav_subt_buttons . '</textarea></div><br />';

	echo '<div><label for="uNotes">' . $field_array['uNotes'] . ' :</label>';
	echo '<textarea id="uNotes" name="uNotes" cols="50" rows="5" wrap >' . $user_notes . '</textarea></div>' . PHP_EOL;
	echo '</fieldset><p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" class="buttonSubmit" />';
	echo '<input type="reset"  name="reset" value="' . $buttn_array['reset'] . '" class="buttonReset" /></p></form>' . PHP_EOL;
	ob_end_flush();
}   else  {
	header("Location: $goto_men");
	exit();
}
?>