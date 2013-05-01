<?php
// Either find an existing supported_languages row for update or set-up form for data input.
require_once 'includes/db_functions.php';
require_once 'classes/SupportedLanguages.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$legnd_text = NULL;
$field_text = NULL;
$buttn_text = NULL;
$user_id = 0;
$inp_lang = NULL;
$rec_lang = NULL;
$lang_code = NULL;
$lang_name = NULL;
$lang_inuse = FALSE;
$char_set = NULL;
$dir_ctn = NULL;
$wel_text = NULL;
$frw_text = NULL;
$foot_text = NULL;
$yes_text = NULL;
$non_text = NULL;
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
	if ( isset($_SESSION['alfid']) && (strlen($_SESSION['alfid']) > 0) )  {
		$rec_lang = $_SESSION['alfid'];
		$inp_lang = $_SESSION['alfid'];
	}
	if ( isset($_SESSION['formvalues']) )  {
		if ( count($_SESSION['formvalues']) > 0 )  {
			$vals_array = $_SESSION['formvalues'];
			$have_frmvals = TRUE;
			if ( array_key_exists('langCode', $vals_array) && (is_null($inp_lang) ) )  {
				$rec_lang = $vals_array['langCode'];
			}
			if ( array_key_exists('langName', $vals_array) )  {
				$lang_name = $vals_array['langName'];
			}
			if ( array_key_exists('langInuse', $vals_array) )  {
				$lang_inuse = $vals_array['langInuse'];
			}
			if ( array_key_exists('chrSet', $vals_array) )  {
				$char_set = $vals_array['chrSet'];
			}
			if ( array_key_exists('drCtn', $vals_array) )  {
				$dir_ctn = $vals_array['drCtn'];
			}
			if ( array_key_exists('welTxt', $vals_array) )  {
				$wel_text = $vals_array['welTxt'];
			}
			if ( array_key_exists('frwTxt', $vals_array) )  {
				$frw_text = $vals_array['frwTxt'];
			}
			if ( array_key_exists('footTxt', $vals_array) )  {
				$foot_text = $vals_array['footTxt'];
			}
			if ( array_key_exists('yesTxt', $vals_array) )  {
				$yes_text = $vals_array['yesTxt'];
			}
			if ( array_key_exists('nonTxt', $vals_array) )  {
				$non_text = $vals_array['nonTxt'];
			}
			if ( array_key_exists('uNotes', $vals_array) )  {
				$user_notes = $vals_array['uNotes'];
			}
		}
		unset($_SESSION['formvalues']);
	}  else  {
		if (!$have_frmvals)  {
			if ( !is_null($rec_lang) ) {
				$sl = new SupportedLanguages();
				$slres = $sl->find_language_by_code($dpgconn, $rec_lang);
				if ($slres)  {
					$lang_name  = $sl->getLangName();
					$lang_inuse = $sl->getLangInuse();
					$char_set   = $sl->getCharSet();
					$dir_ctn    = $sl->getDirEction();
					$wel_text   = $sl->getWelcomeText();
					$frw_text   = $sl->getFarwellText();
					$foot_text  = $sl->getFooterText();
					$yes_text   = $sl->getYesText();
					$non_text  =  $sl->getNoText();
					$user_notes = $sl->getCoUserData();
				}  else  {
					echo '<p class="error">Unable to find Language ' . $rec_lang . '</p>';
				}
			}
		}
	}
	echo '<form id="langmaint" name="langmaint" action="../scripts_php/maintsupplang.php" method="post">';
	echo '<fieldset><legend>' . $legnd_text . '</legend>';
	echo '<input type="hidden" id="token" name="token" value="' . $_SESSION['token'] . '" />';
	echo '<input type="hidden" id="inpLang" name="inpLang" value="' . $inp_lang . '" />' . PHP_EOL;

	echo '<div><label for="langCode">' . $field_array['langCode'] . ' : </label>';
	echo '<input type="text" id="langCode" name="langCode" size="5" maxlength="5"';
	if (!is_null($inp_lang) )  {
		echo ' readonly="readonly" ';
	}  else  {
		echo ' required="required" autofocus="autofocus" ';
	}
	echo ' value="' . $rec_lang . '" /></div><br />';
	echo '<div><label for="langName">' . $field_array['langName'] . ' :</label>';
	echo '<input type="text" id="langName" name="langName" size="40" maxlength="40" ';
	if (!is_null($inp_lang)) {
		echo 'autofocus="autofocus" ';
	}
	echo ' required="required" value="' . $lang_name . '" /></div><br />' . PHP_EOL;
	echo '<div><label for="langInuse">' . $field_array['langInuse'] . ' :</label>';
	echo '<input type="checkbox" id="langInuse" name="langInuse" ';
	if ($lang_inuse)  {
		echo ' checked="checked" ';
	}
	echo ' /></div><br />' . PHP_EOL;
	echo '<div><label for="chrSet">' . $field_array['chrSet'] . ' :</label>';
	echo '<input type="text" id="chrSet" name="chrSet" required="required" size="16" maxlength="16" value="' . $char_set . '" /></div><br />';

	echo '<div><label for="drCtn">' . $field_array['drCtn'] . ' :</label>';
	if ( (!is_null($dir_ctn)) && ($dir_ctn == 'L') )  {
		echo '<input type="radio" id="drCtnL" name="drCtn" checked="checked" value="L" /><label class="labelradio">Left-to-Right</label>';
	}  else {
		echo '<input type="radio" id="drCtnL" name="drCtn" value="L" /><label class="labelradio">Left-to-Right</label>';
	}
	if ( (!is_null($dir_ctn)) && ($dir_ctn == 'R') )  {
		echo '<input type="radio" id="drCtnR" name="drCtn" checked="checked" value="R" style="margin-left:40px;" /><label class="labelradio">Right-to-Left</label>';
	}  else {
		echo '<input type="radio" id="drCtnR" name="drCtn" value="R" style="margin-left:40px;" /><label class="labelradio">Right-to-Left</label>';
	}
	echo ' </div><br />' . PHP_EOL;
	echo '<div><label for="welTxt">' . $field_array['welTxt'] . ' :</label>';
	echo '<input type="text" id="welTxt" name="welTxt" size="20" maxlength="20" required="required" value="' . $wel_text . '" /></div><br />';
	echo '<div><label for="frwTxt">' . $field_array['frwTxt'] . ' :</label>';
	echo '<input type="text" id="frwTxt" name="frwTxt" size="20" maxlength="20" required="required" value="' . $frw_text . '" /></div><br />';
	echo '<div><label for="footTxt">' . $field_array['footTxt'] . ' :</label>';
	echo '<input type="text" id="footTxt" name="footTxt" size="60" maxlength="80" required="required" value="' . $foot_text . '" /></div><br />';
	echo '<div><label for="yesTxt">' . $field_array['yesTxt'] . ' :</label>';
	echo '<input type="text" id="yesTxt" name="yesTxt" size="16" maxlength="16" required="required" value="' . $yes_text . '" /></div><br />';
	echo '<div><label for="nonTxt">' . $field_array['nonTxt'] . ' :</label>';
	echo '<input type="text" id="nonTxt" name="nonTxt" size="16" maxlength="16" required="required" value="' . $non_text . '" /></div><br />';
	echo '<div><label for="uNotes">' . $field_array['uNotes'] . ' :</label>';
	echo '<textarea id="uNotes" name="uNotes" cols="60" rows="4" wrap >' . $user_notes . '</textarea></div>' . PHP_EOL;
	echo '</fieldset><p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" class="buttonSubmit" />';
	echo '<input type="reset"  name="reset" value="' . $buttn_array['reset'] . '" class="buttonReset" /></p></form>' . PHP_EOL;
}   else  {
	echo 'what the . . .';
}
ob_end_flush();
?>