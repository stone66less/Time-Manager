<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/FormsMenu.php';
require_once 'classes/FormTypes.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$sav_navgn_refn = 0;
$sav_form_name = NULL;
$sav_active_item = FALSE;
$sav_super_user = FALSE;
$sav_sysgrp_user = FALSE;
$sav_form_type = NULL;
$sav_navgn_bar = NULL;
$sav_forward_to = 0;
$sav_second_to = 0;
$sav_notes = NULL;
$st_array = array();
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$lang_code = $_SESSION['langcode'];
}  else  {
	$lang_code = $def_lang;
}
$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (isset($_SESSION['userid'])) && (isset($_SESSION['superu'])) && ($_SESSION['superu'] == 'Y') )  {
	$user_id = (int)$_SESSION['userid'];
	if ( isset($_POST['token']) && (strlen($_POST['token']) > 0) && ($_POST['token'] === $_SESSION['token']) )  {
		if ( (isset($_POST['navRefn'])) &&  (isset($_POST['formName'])) && (strlen($_POST['formName']) > 0)   ) {
// form has delivered
			if ( isset($_POST['recId']) && (strlen($_POST['recId']) > 0)  )  {
				$rec_key = (int)$_POST['recId'];
			}
			$mynav = (int)$_POST['navRefn'];
			$form_values['navRefn'] = $mynav;
			if ( ($mynav < 0) || ($mynav > 9999) )  {
				$lg_error[$lg_count] = 43;
				$lg_count++;
			}  else  {
				$sav_navgn_refn = $mynav;
			}
			$myform = trim($_POST['formName']);
			$form_values['formName'] = $myform;
			$last_dot = strrpos($myform, '.');
			if ($last_dot > 0)  {
				$form_suff = substr($myform,($last_dot + 1));
				if ( ($form_suff == 'html') || ($form_suff == 'php') )  {
					$sav_form_name = $myform;
				}  else  {
					$lg_error[$lg_count] = 44;
					$lg_count++;
				} 
			}  else  {
				$lg_error[$lg_count] = 49;
				$lg_count++;
			}
			if ( (isset($_POST['frmTyp'])) && (strlen($_POST['frmTyp']) == 1) )  {
				$f_type = $_POST['frmTyp'];
				$form_values['frmTyp'] = $f_type;
				$ftyp = new FormTypes();
				$ftypres = $ftyp->find_form_type($dpgconn, $lang_code, $f_type);
				if (!$ftypres)  {
					$lg_error[$lg_count] = 46;
					$lg_count++;
				}  else  {
					$sav_form_type = $f_type;
				}
			}  else  {
				$lg_error[$lg_count] = 45;
				$lg_count++;
			}
			
			if ( isset($_POST['actvItem']))  {
				$sav_active_item = TRUE;
			}  else  {
				$sav_active_item = FALSE;
			}
			$form_values['actvItem'] = $sav_active_item;
			if ( isset($_POST['superOnly']))  {
				$sav_super_user = TRUE;
			}  else  {
				$sav_super_user = FALSE;
			}
			$form_values['superOnly'] = $sav_super_user;
			if ( isset($_POST['grpUser']))  {
				$sav_sysgrp_user = TRUE;
			}  else  {
				$sav_sysgrp_user = FALSE;
			}
			$form_values['grpUser'] = $sav_sysgrp_user;

			if ( (isset($_POST['navBar'])) && (strlen($_POST['navBar']) > 0) )  {
				$my_navgn_bar = trim($_POST['navBar']);
				$form_values['navBar'] = $my_navgn_bar;
				$no_errors = TRUE;
				if ( strlen($my_navgn_bar) > 0)   {
					$st_array = explode('+', $my_navgn_bar);
					$st_count = count($st_array);
					if ( $st_count > 0)  {
						foreach($st_array as $key => $value)  {
							$my_bool = preg_match("/^[a-z0-9]{1,10}$/", $value);
							if ( ($my_bool === FALSE) || ($my_bool != 1) )  {
								$lg_error[$lg_count] = 51;
								$lg_count++;
								$no_errors = FALSE;
							}
						}
					}  else  {
						$lg_error[$lg_count] = 50;
						$lg_count++;
					}
				}
				if ($no_errors)  {
					$sav_navgn_bar = $my_navgn_bar;
				}
			}  else  {
				if (isset($form_values['navBar']))  {
					unset($form_values['navBar']);
				}
			}

			if ( isset($_POST['fwdTo']))  {
				$myfwdto = (int)$_POST['fwdTo'];
				if ($myfwdto == 0)  {
					$sav_forward_to = NULL;
				}  else  {
					if ( ($myfwdto < 1) || ($myfwdto > 9999) )  {
						$lg_error[$lg_count] = 47;
						$lg_count++;
					}  else  {
						$sav_forward_to = $myfwdto;
					}
				}
				$form_values['fwdTo'] = $myfwdto;
			}  else  {
				if (isset($form_values['fwdTo']))  {
					unset($form_values['fwdTo']);
				}
			}

			if ( isset($_POST['secnTo']))  {
				$mysecnto = (int)$_POST['secnTo'];
				if ($mysecnto == 0)  {
					$sav_second_to = NULL;
				}  else  {
					if ( ($mysecnto < 1) || ($mysecnto > 9999) )  {
						$lg_error[$lg_count] = 48;
						$lg_count++;
					}  else  {
						$sav_second_to = $mysecnto;
					}
				}
				$form_values['secnTo'] = $mysecnto;
			}  else  {
				if (isset($form_values['secnTo']))  {
					unset($form_values['secnTo']);
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
   $frm = new FormsMenu ();
   if ($rec_key > 0)  {
   	$result = do_begin($dpgconn);
   	$frmres = $frm->lock_forms_menu_by_id($dpgconn, $rec_key);
   	if (!$frmres)  {
   		$lg_error[$lg_count] = 17;
	   	$lg_count++;
	   	$result = do_rollback($dpgconn);
   	}  else  {
   		$versno = $frm->getAuVersNumb();
   		$versno++;
   		$frm->setNavgnRefn($sav_navgn_refn);
			$frm->setFormName($sav_form_name);
			$frm->setActiveItem($sav_active_item);
			$frm->setSuperUser($sav_super_user);
			$frm->setSysgrpUser($sav_sysgrp_user);
			$frm->setFormType($sav_form_type);
			$frm->setNavgnBar($sav_navgn_bar);
			$frm->setForwardTo($sav_forward_to);
			$frm->setSecondTo($sav_second_to);
			$frm->setCoUserData($sav_notes);
			$frm->setUpdatedBy($user_id);
			$frm->setAuVersNumb($versno);
   		$udfrm = $frm->update_forms_menu_by_id($dpgconn, $rec_key);
   		if (!$udfrm)  {
   			$lg_error[$lg_count] = 16;
	   		$lg_count++;
	   		$result = do_rollback($dpgconn);
   		}  else  {
   			$result = do_commit($dpgconn);
   		}
   	}
   }  else  {
   	$frm->setNavgnRefn($sav_navgn_refn);
		$frm->setFormName($sav_form_name);
		$frm->setActiveItem($sav_active_item);
		$frm->setSuperUser($sav_super_user);
		$frm->setSysgrpUser($sav_sysgrp_user);
		$frm->setFormType($sav_form_type);
		$frm->setNavgnBar($sav_navgn_bar);
		$frm->setForwardTo($sav_forward_to);
		$frm->setSecondTo($sav_second_to);
		$frm->setCoUserData($sav_notes);
		$frm->setInsertedBy($user_id);
		$result = do_begin($dpgconn);
   	$ifrm = $frm->insert_forms_menu($dpgconn);
   	if (!$ifrm)  {
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