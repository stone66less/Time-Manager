<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/AnniVersaries.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$sav_descr = NULL;
$sav_tday = 0;
$sav_month = 0;
$sav_monday = 0;
$sav_active = FALSE;
$sav_notes = NULL;

$st_array = array();
$error_labels = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$lang_code = $_SESSION['langcode'];
}  else  {
	$lang_code = $def_lang;
}
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid']))  )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_POST['token']) && (strlen($_POST['token']) > 0) && ($_POST['token'] === $_SESSION['token']) 
		&& (isset($_SESSION['strtime'])) 
		&& ( $_SERVER['REQUEST_TIME'] < (int)$_SESSION['strtime'] + ( (int)$_SESSION['idletime'] * 60)  ) )  {
		if ( isset($_POST['annDesc']) && (strlen($_POST['annDesc']) > 0)  ) {
// form has delivered
			if ( isset($_POST['recId']) && (strlen($_POST['recId']) > 0) )  {
				$rec_key = (int)$_POST['recId'];
			}
			$mydesc = trim($_POST['annDesc']);
			$form_values['annDesc'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 128, 11);
			if (array_key_exists('stringok', $st_array))  {
				$sav_descr = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
				$error_labels['annDesc'] = $lg_count;
			}
			$myday = $_POST['annTday'];
			$form_values['annTday'] = $myday;
			$form_values['annMonth'] = $_POST['annMonth'];
			if ( ($myday < 1) || ($myday > 31) )  {
				$lg_error[$lg_count] = 19;
				$lg_count++;
				$error_labels['annTday'] = $lg_count;
			}  else  {
				$mymon = $_POST['annMonth'];
				$err_no = valid_day_month($myday . '|' . $mymon);
				if ( $err_no > 0 )  {
					$lg_error[$lg_count] = $err_no;
					$lg_count++;
					$error_labels['annMonth'] = $lg_count;
				}  else  {
					$sav_tday = $myday;
					$sav_month = $mymon;
					$sav_monday = $mymon . str_pad($myday, 2, '0');
				}
			}
			if ( isset($_POST['isActive']))  {
				$sav_active = TRUE;
				$form_values['isActive'] = TRUE;
			}  else  {
				$form_values['isActive'] = FALSE;
			}
			if ( isset($_POST['userNotes']) && (strlen($_POST['userNotes']) > 0) )  {
				$mynotes = trim($_POST['userNotes']);
				$form_values['userNotes'] = $mynotes;
				$st_array = validate_string($mynotes, 22, 240, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_notes = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
					$error_labels['userNotes'] = $lg_count;
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

if ( $lg_count == 0 )  {
   $anniv = new AnniVersaries ();
   if ($rec_key > 0)  {
   	$result = do_begin($dpgconn);
   	$annivres = $anniv->lock_anni_versaries_by_id($dpgconn, $rec_key);
   	if (!$annivres)  {
   		$lg_error[$lg_count] = 17;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$vers_no = $anniv->getAuVersNumb();
   		$vers_no++;
			$anniv->setAnniDescr($sav_descr);
			$anniv->setAnniTday($sav_tday);
			$anniv->setAnniMonth($sav_month);
			$anniv->setAnniSday($sav_monday);
			$anniv->setAnniActive($sav_active);
			$anniv->setCoUserData($sav_notes);
   		$anniv->setUpdatedBy ($user_id);
   		$anniv->setAuVersNumb ($vers_no);
   		$ures = $anniv->update_anni_versaries_by_id($dpgconn, $rec_key);
   		if (!$ures)  {
   			$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
   		} else  {
   			$result = do_commit($dpgconn);
   		}
   	}
   }  else  {
   	$anniv->setTuId($user_id);
   	$anniv->setAnniDescr($sav_descr);
		$anniv->setAnniTday($sav_tday);
		$anniv->setAnniMonth($sav_month);
		$anniv->setAnniSday($sav_monday);
		$anniv->setAnniActive($sav_active);
		$anniv->setCoUserData($sav_notes);
   	$anniv->setInsertedBy ($user_id);
   	$result = do_begin($dpgconn);
   	$iannres = $anniv->insert_anni_versaries($dpgconn);
   	if (!$iannres)  {
   		$lg_error[$lg_count] = 15;
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
	$_SESSION['errlabs'] = $error_labels;
	$_SESSION['recid'] = $rec_key;
	$callform = '../index.php?act=err';
}  else  {
	unset($_SESSION['token']);
	if ( isset($_SESSION['formvalues'])) {
		unset($_SESSION['formvalues']);
	}
	if ( isset($_SESSION['fattribs']) )  {
		unset($_SESSION['fattribs']);
	}
	if ( isset($_SESSION['selopts']) )  {
		unset($_SESSION['selopts']);
	}
	if ( isset($_SESSION['errlabs']) )  {
		unset($_SESSION['errlabs']);
	}
	$callform = '../index.php?act=ret';
}
header("Location: $callform");
?>