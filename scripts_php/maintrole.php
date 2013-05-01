<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/GroupRoles.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$sav_name = NULL;
$sav_super = FALSE;
$sav_others = FALSE;
$sav_inuse = FALSE;
$sav_pdays = 0;
$sav_anniv = NULL;
$sav_appnt = NULL;
$sav_tasks = NULL;
$sav_sysad = NULL;
$sav_notes = NULL;
$st_array = array();
$error_labels = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$lang_code = $_SESSION['langcode'];
}  else  {
	$lang_code = $def_lang;
}

if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_POST['token']) && (strlen($_POST['token']) > 0) && ($_POST['token'] === $_SESSION['token']) )  {
		if ( isset($_POST['grName']) && (strlen($_POST['grName']) > 0) &&  (isset($_POST['pwdDays'])) && (strlen($_POST['pwdDays']) > 0 ) ) {
// form has delivered
			if ( isset($_POST['recId']) && (strlen($_POST['recId']) > 0) )  {
				$rec_key = (int)$_POST['recId'];
			}
			$mydesc = trim($_POST['grName']);
			$form_values['grName'] = $mydesc;
			$st_array = validate_string($mydesc, 22, 60, 11);
			if (array_key_exists('stringok', $st_array))  {
				$sav_name = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
				$error_labels['grName'] = $lg_count;
			}
		
			if ( isset($_POST['supUser']) )  {
				$sav_super = TRUE;
			}
			$form_values['supUser'] = $sav_super;
			
			if ( isset($_POST['viOthers']) )  {
				$sav_others = TRUE;
			}
			$form_values['viOthers'] = $sav_others;

			if ( isset($_POST['grpInuse']) )  {
				$sav_inuse = TRUE;
			}
			$form_values['grpInuse'] = $sav_inuse;
	
			$mypdays = trim($_POST['pwdDays']);
			$form_values['pwdDays'] = $mypdays;
			$sav_pdays = (int)$mypdays;
			if ( ($sav_pdays < 1) || ($sav_pdays > 186) ) {
				$lg_error[$lg_count] = 33;
				$lg_count++;
				$error_labels['pwdDays'] = $lg_count;
			}

			if ( isset($_POST['anvLmt']) )  {
				if ($_POST['anvLmt'] > 0)  {
					$my_lim = (int)$_POST['anvLmt'];
					$form_values['anvLmt'] = $my_lim;
					if ( $my_lim > 99 )  {
						$lg_error[$lg_count] = 52;
						$lg_count++;
						$error_labels['anvLmt'] = $lg_count;
					}  else  {
						$sav_anniv = $my_lim;
					}
				}
			}

			if ( isset($_POST['aptLmt']) )  {
				if ($_POST['aptLmt'] > 0)  {
					$my_lim = (int)$_POST['aptLmt'];
					$form_values['aptLmt'] = $my_lim;
					if ( $my_lim > 99 )  {
						$lg_error[$lg_count] = 52;
						$lg_count++;
						$error_labels['aptLmt'] = $lg_count;
					}  else  {
						$sav_appnt = $my_lim;
					}
				}
			}

			if ( isset($_POST['tskLmt']) )  {
				if ($_POST['tskLmt'] > 0)  {
					$my_lim = (int)$_POST['tskLmt'];
					$form_values['tskLmt'] = $my_lim;
					if ( ($my_lim < 0) || ($my_lim > 99) )  {
						$lg_error[$lg_count] = 52;
						$lg_count++;
						$error_labels['tskLmt'] = $lg_count;
					}  else  {
						$sav_tasks = $my_lim;
					}
				}
			}

			if ( isset($_POST['sysLmt']) )  {
				if ($_POST['sysLmt'] > 0)  {
					$my_lim = (int)$_POST['sysLmt'];
					$form_values['sysLmt'] = $my_lim;
					if ( ($my_lim < 0) || ($my_lim > 99) )  {
						$lg_error[$lg_count] = 52;
						$lg_count++;
						$error_labels['sysLmt'] = $lg_count;
					}  else  {
						$sav_sysad = $my_lim;
					}
				}
			}

			if ( isset($_POST['uNotes']) && (strlen($_POST['uNotes']) > 0) )  {
				$mynotes = trim($_POST['uNotes']);
				$form_values['uNotes'] = $mynotes;
				$st_array = validate_string($mynotes, 22, 240, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_notes = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
					$error_labels['uNotes'] = $lg_count;
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
   $grp = new GroupRoles ();
   if ($rec_key > 0)  {
   	$result = do_begin($dpgconn);
   	$gres = $grp->lock_group_roles_by_id($dpgconn, $rec_key);
   	if (!$gres)  {
   		$lg_error[$lg_count] = 17;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$versno = $grp->getAuVersNumb();
   		$versno++;
   		$grp->setGrName ($sav_name);
			$grp->setSuperUser ($sav_super);
			$grp->setViewOthers ($sav_others);
			$grp->setGroupInuse ($sav_inuse);
   		$grp->setChgPword ($sav_pdays);
   		$grp->setAnnivLimit ($sav_anniv);
			$grp->setAppntLimit ($sav_appnt);
			$grp->setTasksLimit ($sav_tasks);
			$grp->setSysadmLmt ($sav_sysad);
   		$grp->setCoUserData ($sav_notes);
   		$grp->setUpdatedBy ($user_id);
   		$grp->setAuVersNumb($versno);
   		$ugres = $grp->update_group_roles_by_id($dpgconn, $rec_key);
   		if (!$ugres)  {
   			$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
   		}  else  {
   			$result = do_commit($dpgconn);
   		}
   	}
   }  else  {
   	$grp->setGrName ($sav_name);
		$grp->setSuperUser ($sav_super);
		$grp->setViewOthers ($sav_others);
		$grp->setGroupInuse ($sav_inuse);
   	$grp->setChgPword ($sav_pdays);
   	$grp->setAnnivLimit ($sav_anniv);
		$grp->setAppntLimit ($sav_appnt);
		$grp->setTasksLimit ($sav_tasks);
		$grp->setSysadmLmt ($sav_sysad);
   	$grp->setCoUserData ($sav_notes);
   	$grp->setInsertedBy ($user_id);
   	$result = do_begin($dpgconn);
   	$igres = $grp->insert_group_roles($dpgconn);
   	if (!$igres)  {
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