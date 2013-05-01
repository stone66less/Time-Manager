<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'includes/crypt_funcs.php';
require_once 'classes/GroupRoles.php';
require_once 'classes/SupportedLanguages.php';
require_once 'classes/TimeUsers.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$sav_logon = NULL;
$sav_name = NULL;
$sav_grid = 0;
$sav_lang = NULL;
$sav_active = FALSE;
$sav_super = FALSE;
$sav_sysgrp = FALSE;
$sav_utc = NULL;
$sav_fixip = NULL;
$sav_phone = NULL;
$sav_email = NULL;
$sav_notes = NULL;
$st_array = array();
$error_labels = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$lang_code = $_SESSION['langcode'];
}  else  {
	$lang_code = $def_lang;
}
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_POST['token']) && (strlen($_POST['token']) > 0) && ($_POST['token'] === $_SESSION['token']) 
		&& (isset($_SESSION['strtime'])) 
		&& ( $_SERVER['REQUEST_TIME'] < (int)$_SESSION['strtime'] + ( (int)$_SESSION['idletime'] * 60)  ) )  {
		if ( isset($_POST['logonId']) && (strlen($_POST['logonId']) > 0) &&  (isset($_POST['logonName'])) && (strlen($_POST['logonName']) > 0 ) ) {
// form has delivered
			if ( isset($_POST['recId']) && (strlen($_POST['recId']) > 0) )  {
				$rec_key = (int)$_POST['recId'];
			}
			$mydesc = trim($_POST['logonId']);
			$form_values['logonId'] = $mydesc;
			$sav_logon = strtoupper($mydesc);
			if ( (strlen($sav_logon) < 1)  || (strlen($sav_logon) > 4) )  {
				$lg_error[$lg_count] = 8;
				$lg_count++;
				$error_labels['logonId'] = $lg_count;
			}  else  {
				$my_bool = preg_match("/^[A-Z]{1,4}$/", $sav_logon);
				if ($my_bool === FALSE)  {
					$lg_error[$lg_count] = 3;
					$lg_count++;
					$error_labels['logonId'] = $lg_count;
				}  else  {
					$log = new TimeUsers();
					$logres = $log->find_time_users_by_logon($dpgconn, $sav_logon);
					if (!$logres)  {
						if ($rec_key > 0)  {
							$lg_error[$lg_count] = 18;
							$lg_count++;
							$error_labels['logonId'] = $lg_count;
						}
					}  else  {
						$tu_id = $log->getTuId();
						if ($rec_key != $tu_id)  {
							$lg_error[$lg_count] = 18;
							$lg_count++;
							$error_labels['logonId'] = $lg_count;
						}
					}
				}
			}
			$mydesc = trim($_POST['logonName']);
			$form_values['logonName'] = $mydesc;
			$mydesc = ucwords(strtolower($mydesc));
			$st_array = validate_string($mydesc, 22, 60, 11);
			if (array_key_exists('stringok', $st_array))  {
				$sav_name = $st_array['stringok'];
			}  else  {
				$lg_error = array_merge($lg_error, $st_array);
				$lg_count = count($lg_error);
				$error_labels['logonName'] = $lg_count;
			}
			
			if ( isset($_POST['grId']) && ($_POST['grId'] > 0) )  {
				$form_values['grId'] = $_POST['grId'];
				$sav_grid = (int)$_POST['grId'];
				$grp = new GroupRoles();
				$grpres = $grp->find_group_roles_by_id($dpgconn, $sav_grid);
				if (!$grpres)  {
					$lg_error[$lg_count] = 62;
					$lg_count++;
					$error_labels['grId'] = $lg_count;
				}  else  {
					$in_use = $grp->getGroupInuse();
					if (!$in_use) {
						$lg_error[$lg_count] = 63;
						$lg_count++;
						$error_labels['grId'] = $lg_count;
					}
				}
			}  else  {
				$lg_error[$lg_count] = 64;
				$lg_count++;
				$error_labels['grId'] = $lg_count;
			}

			if ( isset($_POST['langCode']) && (strlen($_POST['langCode']) > 0) )  {
				$form_values['langCode'] = $_POST['langCode'];
				$sav_lang = $_POST['langCode'];
				$sl = new SupportedLanguages();
				$slres = $sl->find_language_by_code($dpgconn, $sav_lang);
				if (!$slres)  {
					$lg_error[$lg_count] = 67;
					$lg_count++;
					$error_labels['langCode'] = $lg_count;
				}  else  {
					if (!$sl->getLangInuse()) {
						$lg_error[$lg_count] = 66;
						$lg_count++;
						$error_labels['langCode'] = $lg_count;
					}
				}
			}  else  {
				$lg_error[$lg_count] = 65;
				$lg_count++;
				$error_labels['langCode'] = $lg_count;
			}

			if ( isset($_POST['activeUser']) )  {
				$sav_active = TRUE;
		   }
			$form_values['activeUser'] = $sav_active;
		
			if ( isset($_POST['superUser']) )  {
				$sav_super = TRUE;
		   }
			$form_values['superUser'] = $sav_super;
			
			if ( isset($_POST['sysgrpUser']) )  {
				$sav_sysgrp = TRUE;
		   }
			$form_values['sysgrpUser'] = $sav_sysgrp;

			if ( isset($_POST['fixedIp']) && (strlen($_POST['fixedIp']) > 0) )  {
				$form_values['fixedIp'] = $_POST['fixedIp'];
				$mydesc = trim($_POST['fixedIp']);
				if (strlen($mydesc) > 40)  {
					$lg_error[$lg_count] = 68;
					$lg_count++;
					$error_labels['fixedIp'] = $lg_count;
				}  else  {
					$ip_type = valid_ip_range($mydesc);
					if ( ($ip_type == '4') || ($ip_type == '6') )  {
						$sav_fixip = $mydesc;
					}  else  {
						$lg_error[$lg_count] = 69;
						$lg_count++;
						$error_labels['fixedIp'] = $lg_count;
					}
				}
			}
			
			if ( isset($_POST['utcOffset']) )  {
				if ($_POST['utcOffset'] != 0)  {
					$form_values['utcOffset'] = $_POST['utcOffset'];
					$sav_utc = (int)$_POST['utcOffset'];
					if ( ($sav_utc < -12) || ($sav_utc > 12) )  {
						$lg_error[$lg_count] = 27;
						$lg_count++;
						$error_labels['utcOffset'] = $lg_count;
					}
				}
			}
			
			if ( isset($_POST['phoneExtn']) )  {
				if ($_POST['phoneExtn'] != 0)  {
					$form_values['phoneExtn']  = $_POST['phoneExtn'];
					$sav_phone = (int)$_POST['phoneExtn'];
					if ( ($sav_phone < 0) || ($sav_phone > 999) )  {
						$lg_error[$lg_count] = 28;
						$lg_count++;
						$error_labels['phoneExtn'] = $lg_count;
					}
				}
			}

			if ( (isset($_POST['emailAddr']) )  &&  (strlen($_POST['emailAddr']) > 0) )  {
				$form_values['emailAddr'] = $_POST['emailAddr'];
				$sav_email = trim($_POST['emailAddr']);
				$c_e = valid_email($sav_email);
				if ($c_e === FALSE) {
					$lg_error[$lg_count] = 29;
					$lg_count++;
					$error_labels['emailAddr'] = $lg_count;
				}
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
   $tu = new TimeUsers ();
   if ($rec_key > 0)  {
   	$result = do_begin($dpgconn);
   	$tures = $tu->lock_time_users_by_id($dpgconn, $rec_key);
   	if (!$tures)  {
   		$lg_error[$lg_count] = 17;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$vers_no = $tu->getAuVersNumb();
   		$vers_no++;
			$tu->setLogonName($sav_name);
			$tu->setGrId($sav_grid);
			$tu->setLangCode($sav_lang);
			$tu->setActiveUser($sav_active);
			$tu->setSuperUser($sav_super);
			$tu->setSysgrpUser($sav_sysgrp);
			$tu->setFixedIp($sav_fixip);
			$tu->setUtcOffset($sav_utc);
			$tu->setPhoneExtn($sav_phone);
			$tu->setEmailAddr($sav_email);
			$tu->setCoUserData($sav_notes);
   		$tu->setUpdatedBy ($user_id);
   		$tu->setAuVersNumb ($vers_no);
   		$utures = $tu->update_time_users_by_id($dpgconn, $rec_key);
   		if (!$utures)  {
   			$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
   		} else  {
   			$result = do_commit($dpgconn);
   		}
   	}
   }  else  {
   	$sp_posn = strcspn($sav_name, ' ');
		$first_name = substr($sav_name, 0, $sp_posn);
		$temp_pass = bf_encdec('ENC', $first_name, 'avc');
   	$tu->setLogonId($sav_logon);
   	$tu->setLogonName($sav_name);
		$tu->setGrId($sav_grid);
		$tu->setLangCode($sav_lang);
		$tu->setActiveUser($sav_active);
		$tu->setSuperUser($sav_super);
		$tu->setSysgrpUser($sav_sysgrp);
		$tu->setFixedIp($sav_fixip);
		$tu->setUtcOffset($sav_utc);
		$tu->setPhoneExtn($sav_phone);
		$tu->setPassWord($temp_pass);
		$tu->setEmailAddr($sav_email);
		$tu->setCoUserData($sav_notes);
   	$tu->setInsertedBy ($user_id);
   	$result = do_begin($dpgconn);
   	$itues = $tu->insert_time_users($dpgconn);
   	if (!$itues)  {
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
/*
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
*/
	sessvars_unset();
	$callform = '../index.php?act=ret';
}
header("Location: $callform");
?>