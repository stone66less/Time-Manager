<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/AppointMents.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$sav_date = NULL;
$sav_hour = 0;
$sav_mint = 0;
$sav_subj = NULL;
$sav_with = NULL;
$sav_drtn = 0;
$sav_intl = FALSE;
$sav_wher = NULL;
$sav_dept = 0;
$sav_canc = FALSE;
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
		if ( isset($_POST['appntDate']) && (strlen($_POST['appntDate']) > 0)  ) {
// form has delivered
			if ( isset($_POST['recId']) && (strlen($_POST['recId']) > 0) )  {
				$rec_key = (int)$_POST['recId'];
			}
			$mydate = trim($_POST['appntDate']);
			$form_values['appntDate'] = $mydate;
			$date_val = valid_date($mydate, 'DMY');
			$derr_val = is_date($date_val);
			if ($derr_val > 0)  {
				$lg_error[$lg_count] = $derr_val;
				$lg_count++;
				$error_labels['appntDate'] = $lg_count;
			}  else  {
				$sav_date = $date_val;
			}
			$myhours = $_POST['appntHour'];
			$forms_values['appntHour'] = $myhours;
			$forms_values['appntMint'] = $_POST['appntMint'];
			$mymins = $myhours . str_pad($_POST['appntMint'], 2, '0', STR_PAD_LEFT);
			$timerr = valid_time($mymins);
			if ($timerr > 0)  {
				$lg_error[$lg_count] = $timerr;
				$lg_count++;
				$error_labels['appntHour'] = $lg_count;
			}  else  {
				$sav_hour = $myhours;
				$sav_mint = $_POST['appntMint'];
			}
			$mydesc = trim($_POST['appntSubj']);
			$form_values['appntSubj'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 128, 11);
			if (array_key_exists('stringok', $st_array))  {
				$sav_subj = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
				$error_labels['appntSubj'] = $lg_count;
			}
			$mydesc = trim($_POST['appntWith']);
			$form_values['appntWith'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 128, 11);
			if (array_key_exists('stringok', $st_array))  {
				$sav_with = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
				$error_labels['appntWith'] = $lg_count;
			}
			$mynumb = (int)$_POST['appntDrtn'];
			$form_values['appntDrtn'] = $mynumb;
			if ( ($mynumb > 6) && ($mynumb < 721) )  {
				$sav_drtn = $mynumb;
			}  else  {
				$lg_error[$lg_count] = 39;
				$lg_count++;
				$error_labels['appntDrtn'] = $lg_count;
			}
			if ( isset($_POST['appntIntl']))  {
				$sav_intl = TRUE;
				$form_values['appntIntl'] = TRUE;
			}  else  {
				$form_values['appntIntl'] = FALSE;
			}

			if ( isset($_POST['appntWher']) && (strlen($_POST['appntWher']) > 0) )  {
				$mydesc = trim($_POST['appntWher']);
				$form_values['appntWher'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 128, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_wher = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
					$error_labels['appntWher'] = $lg_count;
				}
			}

			if ( (isset($_POST['appntDepth'])) || (isset($_POST['appntDeptm'])) )  {
				if ( (isset($_POST['appntDepth'])) && (isset($_POST['appntDeptm'])) )  {
					$myhours = $_POST['appntDepth'];
					$forms_values['appntDepth'] = $myhours;
					$forms_values['appntDeptm'] = $_POST['appntDeptm'];
					$mymins = $myhours . str_pad($_POST['appntDeptm'], 2, '0', STR_PAD_LEFT);
					$timerr = valid_time($mymins);
					if ($timerr > 0)  {
						$lg_error[$lg_count] = $timerr;
						$lg_count++;
						$error_labels['appntDepth'] = $lg_count;
					}  else  {
						$sav_dept = ($myhours * 60) + (int)$_POST['appntDeptm'];
					}
				} else  {
					$lg_error[$lg_count] = 73;
					$lg_count++;
					$error_labels['appntDepth'] = $lg_count;
				}
			}

			if ( isset($_POST['appntCanc']))  {
				$sav_canc = TRUE;
				$form_values['appntCanc'] = TRUE;
			}  else  {
				$form_values['appntCanc'] = FALSE;
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
   $appnt = new AppointMents ();
   if ($rec_key > 0)  {
   	$result = do_begin($dpgconn);
   	$appntres = $appnt->lock_appoint_ments_by_id($dpgconn, $rec_key);
   	if (!$appntres)  {
   		$lg_error[$lg_count] = 17;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$vers_no = $appnt->getAuVersNumb();
   		$vers_no++;
			$appnt->setAppointDate($sav_date);
			$appnt->setAppointHour($sav_hour);
			$appnt->setAppointMin ($sav_mint);
			$appnt->setWithWhom ($sav_with);
			$appnt->setMeetSubjt($sav_subj);
			$appnt->setEstDrtn($sav_drtn);
			$appnt->setDepartTime($sav_dept);
			$appnt->setIntlMeet($sav_intl);
			$appnt->setMeetWhere($sav_wher);
			$appnt->setMeetCancld($sav_canc);
			$appnt->setCoUserData($sav_notes);
   		$appnt->setUpdatedBy ($user_id);
   		$appnt->setAuVersNumb ($vers_no);
   		$ures = $appnt->update_appoint_ments_by_id($dpgconn, $rec_key);
   		if (!$ures)  {
   			$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
   		} else  {
   			$result = do_commit($dpgconn);
   		}
   	}
   }  else  {
		$appnt->setTuId($user_id);
		$appnt->setAppointDate($sav_date);
		$appnt->setAppointHour($sav_hour);
		$appnt->setAppointMin ($sav_mint);
		$appnt->setWithWhom ($sav_with);
		$appnt->setMeetSubjt($sav_subj);
		$appnt->setEstDrtn($sav_drtn);
		$appnt->setDepartTime($sav_dept);
		$appnt->setIntlMeet($sav_intl);
		$appnt->setMeetWhere($sav_wher);
		$appnt->setMeetCancld($sav_canc);
		$appnt->setCoUserData($sav_notes);
   	$appnt->setInsertedBy ($user_id);
   	$result = do_begin($dpgconn);
   	$iapptres = $appnt->insert_appoint_ments($dpgconn);
   	if (!$iapptres)  {
   		$lg_error[$lg_count] = 40;
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