<?php
// Either find an existing error_messages row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'classes/ErrorMessages.php';
require_once 'actions/SupportedLanguagesActions.php';
$def_lang = 'en-GB';
$nav_refn = 0;
$legnd_text = NULL;
$field_text = NULL;
$buttn_text = NULL;
$user_id = 0;
$rec_id = 0;
$err_no = 0;
$err_lang = NULL;
$err_messg = NULL;
$err_help = NULL;
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
ob_start();
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_SESSION['recid']) && ($_SESSION['recid'] > 0) )  {
		$rec_id = (int)$_SESSION['recid'];
	}
	if ( isset($_SESSION['errlang']) && (strlen($_SESSION['errlang']) > 0) )  {
		$err_lang = $_SESSION['errlang'];
	}
	if ( isset($_SESSION['formvalues']) )  {
		if ( count($_SESSION['formvalues']) > 0 )  {
			$vals_array = $_SESSION['formvalues'];
			$have_frmvals = TRUE;
			if ( array_key_exists('errNo', $vals_array) && ($rec_id == 0) )  {
				$err_no = $vals_array['errNo'];
			}
			if ( array_key_exists('messLang', $vals_array) )  {
				$err_lang = $vals_array['messLang'];
			}
			if ( array_key_exists('errMsg', $vals_array) )  {
				$err_messg = $vals_array['errMsg'];
			}
			if ( array_key_exists('errHelp', $vals_array) )  {
				$err_help = $vals_array['errHelp'];
			}
			if ( array_key_exists('uNotes', $vals_array) )  {
				$user_notes = $vals_array['uNotes'];
			}
		}
		unset($_SESSION['formvalues']);
	}  else  {
		if (!$have_frmvals)  {
			if ( ($rec_id > 0)  &&  (!is_null($err_lang)) ) {
				$erm = new ErrorMessages();
				$ermres = $erm->find_error_by_id($dpgconn, $rec_id);
				if ($ermres)  {
					$err_no = $erm->getErrorNumber();
					$err_lang = $erm->getLangCode();
					$err_messg = $erm->getErrorMessg();
					$err_help = html_entity_decode($erm->getErrorHelp(), ENT_QUOTES, 'UTF-8');
					$user_notes = html_entity_decode($erm->getCoUserData(), ENT_QUOTES, 'UTF-8');
				}  else  {
					echo '<p class="error">Unable to find Error Message ' . $rec_id . ' ' . $err_lang . '</p>';	
				}
			}
		}
	}
	echo '<form id="errmaint" name="errmaint" action="../scripts_php/mainterror.php" method="post">';
	echo '<fieldset><legend>' . $legnd_text . '</legend>';
	echo '<input type="hidden" id="recId" name="recId" value="' . $rec_id . '" />';
	echo '<input type="hidden" id="token" name="token" value="' . $_SESSION['token'] . '" />' . PHP_EOL;

	echo '<div><label for="errNo">' . $field_array['errNo'] . ' : </label>';
	echo '<input type="number" id="errNo" name="errNo" class="numb" size="6" maxlength="6"';
	if ($rec_id > 0)  {
		echo ' readonly="readonly" ';
	}  else  {
		echo ' required="required" autofocus="autofocus" min="1" max="99999" ';
	}
	echo ' value="' . $err_no . '" /></div><br />';
	echo '<div><label for="messLang">' . $field_array['messLang'] . ' :</label><select id="messLang" name="messLang" >';
	$opt_string = lang_opt_list($dpgconn, $field_array['firstOption'], $err_lang);
	echo $opt_string . '</select></div><br />' . PHP_EOL;
	echo '<div><label for="errMsg">' . $field_array['errMsg'] . ' :</label>';
	echo '<input type="text" id="errMsg" name="errMsg" required="required" size="60" maxlength="80" ';
	if ($rec_id > 0)  {
		echo ' autofocus="autofocus" ';
	}
	echo ' value="' . $err_messg . '" /></div><br />' . PHP_EOL;
	echo '<div><label for="errHelp">' . $field_array['errHelp'] . ' :</label>';
	echo '<textarea id="errHelp" name="errHelp" cols="60" rows="8" wrap >' . $err_help . '</textarea></div>' . PHP_EOL;
	echo '<div><label for="uNotes">' . $field_array['uNotes'] . ' :</label>';
	echo '<textarea id="uNotes" name="uNotes" cols="60" rows="4" wrap >' . $user_notes . '</textarea></div>' . PHP_EOL;
	echo '</fieldset><p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" class="buttonSubmit" />';
	echo '<input type="reset"  name="reset" value="' . $buttn_array['reset'] . '" class="buttonReset" /></p></form>' . PHP_EOL;
}   else  {
	echo 'what the . . .';
}
ob_end_flush();
?>