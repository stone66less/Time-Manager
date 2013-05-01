<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/ErrorMessages.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$sav_errno = 0;
$sav_lang  = NULL;
$sav_messg = NULL;
$sav_ehelp = NULL;
$sav_notes = NULL;
$st_array = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$lang_code = $_SESSION['langcode'];
}  else  {
	$lang_code = $def_lang;
}

if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_POST['token']) && (strlen($_POST['token']) > 0) && ($_POST['token'] === $_SESSION['token']) )  {
		if ( isset($_POST['errNo']) && (strlen($_POST['errNo']) > 0) &&  (isset($_POST['errMsg'])) && (strlen($_POST['errMsg']) > 0 ) ) {
// form has delivered
			if ( (isset($_POST['recId'])) && ($_POST['recId'] > 0) )  {
				$rec_key = (int)$_POST['recId'];
			}
			$myerrno = trim($_POST['errNo']);
			$form_values['errNo'] = $myerrno;
			if ( ($myerrno < 1) || ($myerrno > 99999) )  {
				$lg_error[$lg_count] = 41;
				$lg_count++;
			}  else  {
				$sav_errno = (int)$myerrno;
			}

			if ( isset($_POST['messLang']) && (strlen($_POST['messLang']) > 0) )  {
				$sav_lang = trim($_POST['messLang']);
			}  else  {
				$lg_error[$lg_count] = 42;
				$lg_count++;
			}

			$mydesc = trim($_POST['errMsg']);
			$form_values['errMsg'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 80, 11);
			if (array_key_exists('stringok', $st_array))  {
				$sav_messg = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}

			if ( isset($_POST['errHelp']) && (strlen($_POST['errHelp']) > 0) )  {
				$mynotes = trim($_POST['errHelp']);
				$form_values['errHelp'] = $mynotes;
				$st_array = validate_string($mynotes, 15, 600, 16);
				if (array_key_exists('stringok', $st_array))  {
					$sav_ehelp = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}

			if ( isset($_POST['uNotes']) && (strlen($_POST['uNotes']) > 0) )  {
				$mynotes = trim($_POST['uNotes']);
				$form_values['uNotes'] = $mynotes;
				$st_array = validate_string($mynotes, 15, 240, 16);
				if (array_key_exists('stringok', $st_array))  {
					$sav_notes = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}		
		}  else  {
			$lg_error[$lg_count] = 14;
	   	$lg_count++;
		}
	}  else  {
		$lg_error[$lg_count] = 9;
   	$lg_count++;	
	}
} else  {
   $lg_error[$lg_count] = 9;
   $lg_count++;
}
$dpgconn = conn_db();
if ( $lg_count == 0 )  {
   $erm = new ErrorMessages ();
   if ($rec_key > 0)  {
   	$result = do_begin($dpgconn);
   	$ermres = $erm->lock_error_by_id($dpgconn, $rec_key);
   	if (!$ermres)  {
   		$lg_error[$lg_count] = 17;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$versno = $erm->getAuVersNumb();
   		$versno++;
   		$erm->setErrorMessg ($sav_messg);
			$erm->setErrorHelp  ($sav_ehelp);
   		$erm->setCoUserData($sav_notes);
   		$erm->setUpdatedBy($user_id);
   		$erm->setAuVersNumb($versno);
   		$uderm = $erm->update_error_message($dpgconn, $rec_key);
   		if (!$uderm)  {
   			$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
   		}  else  {
   			$result = do_commit($dpgconn);
   		}
   	}
   }  else  {
   	$erm->setErrorNumber ($sav_errno);
   	$erm->setLangCode ($sav_lang);
		$erm->setErrorMessg ($sav_messg);
		$erm->setErrorHelp  ($sav_ehelp);
  		$erm->setCoUserData($sav_notes);
   	$erm->setInsertedBy($user_id);
   	$result = do_begin($dpgconn);
   	$ierm = $erm->insert_error_message($dpgconn);
   	if (!$ierm)  {
   		$lg_error[$lg_count] = 12;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$result = do_commit($dpgconn);
   	}
   }
}

if ($lg_count > 0) {
	$_SESSION['lg_error'] = build_error_list($dpgconn, $lg_error, $def_lang, TRUE);
	$_SESSION['formvalues'] = $form_values;
	$_SESSION['recid'] = $rec_key;
	$callform = '../index.php?act=err';
}  else  {
	unset($_SESSION['token']);
	if ( isset($_SESSION['formvalues'])) {
		unset($_SESSION['formvalues']);
	}
	$callform = '../index.php?act=ret';
}
header("Location: $callform");
?>