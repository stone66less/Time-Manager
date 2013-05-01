<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/SupportedLanguages.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$inp_lang = NULL;
$sav_code = NULL;
$sav_name = NULL;
$sav_inuse = FALSE;
$sav_char = NULL;
$sav_dctn = NULL;
$sav_welc = NULL;
$sav_fwel = NULL;
$sav_foot = NULL;
$sav_yest = NULL;
$sav_nont = NULL;
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
		if ( isset($_POST['langCode']) && (strlen($_POST['langCode']) > 0) &&  (isset($_POST['langName'])) && (strlen($_POST['langName']) > 0 ) ) {
// form has delivered
			if ( isset($_POST['inpLang']) && (strlen($_POST['inpLang']) > 0) )  {
				$inp_lang = trim($_POST['inpLang']);
			}
			$sav_code = trim($_POST['langCode']);
			$form_values['langCode'] = $sav_code;
			$len_code = strlen($sav_code);
			if ( $len_code > 5)  {
				$lg_error[$lg_count] = 56;
				$lg_count++;
			}  else  {
				if ($len_code > 2)  {
					$char_three = substr($sav_code,2,1);
					if ($char_three != '-')  {
						$lg_error[$lg_count] = 57;
						$lg_count++;
					}
				}
				$my_bool = preg_match("/^[a-zA-Z-]{1,5}$/", $sav_code);
				if ($my_bool === FALSE)  {
					$lg_error[$lg_count] = 58;
					$lg_count++;
				}
			}

			$mydesc = trim($_POST['langName']);
			$form_values['langName'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 40, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_name = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}
			if ( isset($_POST['langInuse']) )  {
				$sav_inuse = TRUE;
			}
			$mydesc = trim($_POST['chrSet']);
			$form_values['chrSet'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 16, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_char = strtoupper($st_array['stringok']);
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}
			$form_values['drCtn'] = $_POST['drCtn'];
			if ( ($_POST['drCtn'] == 'L') || ($_POST['drCtn'] == 'R') )  {
				$sav_dctn = $_POST['drCtn'];
			}  else  {
				$lg_error[$lg_count] = 59;
				$lg_count++;
			}
			$mydesc = trim($_POST['welTxt']);
			$form_values['welTxt'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 20, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_welc = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}
			$mydesc = trim($_POST['frwTxt']);
			$form_values['frwTxt'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 20, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_fwel = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}
			$mydesc = trim($_POST['footTxt']);
			$form_values['footTxt'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 80, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_foot = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}
			$mydesc = trim($_POST['yesTxt']);
			$form_values['yesTxt'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 16, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_yest = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}
			$mydesc = trim($_POST['nonTxt']);
			$form_values['nonTxt'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 16, 11, TRUE);
			if (array_key_exists('stringok', $st_array))  {
				$sav_nont = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
			}

			if ( isset($_POST['uNotes']) && (strlen($_POST['uNotes']) > 0) )  {
				$mynotes = trim($_POST['uNotes']);
				$form_values['uNotes'] = $mynotes;
				$st_array = validate_string($mynotes, 22, 240, 11, TRUE);
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
	$result = do_begin($dpgconn);
   $sl = new SupportedLanguages ();
   $slres = $sl->lock_language_by_code($dpgconn, $sav_code);
   if (!$slres)  {
   	if ( is_null($inp_lang) )  {
   		$sl->setLangCode ($sav_code);
   		$sl->setLangName ($sav_name);
   		$sl->setLangInuse ($sav_inuse);
   		$sl->setCharSet ($sav_char);
   		$sl->setDirEction ($sav_dctn);
			$sl->setWelcomeText ($sav_welc);
			$sl->setFarwellText ($sav_fwel);
			$sl->setFooterText ($sav_foot);
			$sl->setYesText ($sav_yest);
			$sl->setNoText ($sav_nont);
			$sl->setCoUserData ($sav_notes);
			$sl->setInsertedBy ($user_id);
			$islres = $sl->insert_language_by_code($dpgconn);
			if (!$islres)  {
				$lg_error[$lg_count] = 12;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
			}  else  {
				$result = do_commit($dpgconn);
			}
   	}  else  {
   		$lg_error[$lg_count] = 60;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}
   }  else  {
   	if ( $sav_code != $inp_lang )  {
   		$lg_error[$lg_count] = 61;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$versno = $sl->getAuVersNumb();
   		$versno++;
   		$sl->setLangName ($sav_name);
   		$sl->setLangInuse ($sav_inuse);
   		$sl->setCharSet ($sav_char);
   		$sl->setDirEction ($sav_dctn);
			$sl->setWelcomeText ($sav_welc);
			$sl->setFarwellText ($sav_fwel);
			$sl->setFooterText ($sav_foot);
			$sl->setYesText ($sav_yest);
			$sl->setNoText ($sav_nont);
			$sl->setCoUserData ($sav_notes);
			$sl->setUpdatedBy ($user_id);
			$sl->setAuVersNumb($versno);
			$uslres = $sl->update_language_by_code($dpgconn, $sav_code);
			if (!$uslres)  {
				$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
			}  else  {
				$result = do_commit($dpgconn);
			}
   	}
   }
}

if ($lg_count > 0) {
	$_SESSION['lg_error'] = build_error_list($dpgconn, $lg_error, $def_lang, TRUE);
	$_SESSION['formvalues'] = $form_values;
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