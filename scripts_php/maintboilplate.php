<?php
session_start();
require_once 'includes/db_functions.php';
require_once 'includes/charfunctions.php';
require_once 'classes/BoilerPlate.php';
require_once 'actions/ErrorMessagesActions.php';
$def_lang = 'en-GB';
$lang_code = NULL;
$user_id = 0;
$rec_key = 0;
$lg_count = 0;
$lg_error = array();
$form_values = array();
$mydesc = NULL;
$sav_bp_lang = 0;
$sav_nav_refn  = 0;
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
		if ( isset($_POST['bplLang']) && (strlen($_POST['bplLang']) > 0) &&  (isset($_POST['formName'])) && (strlen($_POST['formName']) > 0 ) ) {
// form has delivered
			$sav_bp_lang = (string)$_POST['bplLang'];
			$form_values['bplLang'] = $_POST['bplLang'];
			$sav_nav_refn = (int)$_POST['formName'];
			$form_values['navRefn'] = $_POST['formName'];
			if ( isset($_POST['recId']) && (strlen($_POST['recId']) > 0) )  {
				$rec_key = (int)$_POST['recId'];
			}
			if ( isset($_POST['pgTitl']) && (strlen($_POST['pgTitl']) > 0) )  {
				$mydesc = trim($_POST['pgTitl']);
				$form_values['pgTitl'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11, TRUE);
				if (array_key_exists('stringok', $st_array))  {
					$sav_page_title = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['headOne']) && (strlen($_POST['headOne']) > 0) )  {
				$mydesc = trim($_POST['headOne']);
				$form_values['headOne'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11, TRUE);
				if (array_key_exists('stringok', $st_array))  {
					$sav_heading_one = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['headTwo']) && (strlen($_POST['headTwo']) > 0) )  {
				$mydesc = trim($_POST['headTwo']);
				$form_values['headTwo'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11, TRUE);
				if (array_key_exists('stringok', $st_array))  {
					$sav_heading_two = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['headTre']) && (strlen($_POST['headTre']) > 0) )  {
				$mydesc = trim($_POST['headTre']);
				$form_values['headTre'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11, TRUE);
				if (array_key_exists('stringok', $st_array))  {
					$sav_heading_tre = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['headQua']) && (strlen($_POST['headQua']) > 0) )  {
				$mydesc = trim($_POST['headQua']);
				$form_values['headQua'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_heading_qua = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['headCin']) && (strlen($_POST['headCin']) > 0) )  {
				$mydesc = trim($_POST['headCin']);
				$form_values['headCin'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_heading_cin = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['headSix']) && (strlen($_POST['headSix']) > 0) )  {
				$mydesc = trim($_POST['headSix']);
				$form_values['headSix'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_heading_six = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['navBar']) && (strlen($_POST['navBar']) > 0) && (strlen($_POST['navBar']) < 401) )  {
				$mydesc = trim($_POST['navBar']);
				$form_values['navBar'] = $mydesc;
				$is_json = json_string($mydesc);
				if (!$is_json)  {
					$lg_error[$lg_count] = 53;
					$lg_count++;
				}  else  {
					$sav_navign_bar = $mydesc;
				}
			}
			if ( isset($_POST['capTons']) && (strlen($_POST['capTons']) > 0) )  {
				$mydesc = trim($_POST['capTons']);
				$form_values['capTons'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_capt_ions = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['thtdCells']) && (strlen($_POST['thtdCells']) > 0) && (strlen($_POST['thtdCells']) < 401) )  {
				$mydesc = trim($_POST['thtdCells']);
				$form_values['thtdCells'] = $mydesc;
				$is_json = json_string($mydesc);
				if (!$is_json)  {
					$lg_error[$lg_count] = 53;
					$lg_count++;
				}  else  {
					$sav_thtd_cells = $mydesc;
				}
			}
			if ( isset($_POST['legEnd']) && (strlen($_POST['legEnd']) > 0) )  {
				$mydesc = trim($_POST['legEnd']);
				$form_values['legEnd'] = $mydesc;
				$st_array = validate_string($mydesc, 22, 80, 11);
				if (array_key_exists('stringok', $st_array))  {
					$sav_leg_end = $st_array['stringok'];
				}  else  {
					$lg_error = array_merge($lg_error, $st_array);
					$lg_count = count($lg_error);
				}
			}
			if ( isset($_POST['formFields']) && (strlen($_POST['formFields']) > 0) && (strlen($_POST['formFields']) < 801) )  {
				$mydesc = trim($_POST['formFields']);
				$form_values['formFields'] = $mydesc;
				$is_json = json_string($mydesc);
				if (!$is_json)  {
					$lg_error[$lg_count] = 53;
					$lg_count++;
				}  else  {
					$sav_form_fields = $mydesc;
				}
			}
			if ( isset($_POST['subtButt']) && (strlen($_POST['subtButt']) > 0) && (strlen($_POST['subtButt']) < 401) )  {
				$mydesc = trim($_POST['subtButt']);
				$form_values['subtButt'] = $mydesc;
				$is_json = json_string($mydesc);
				if (!$is_json)  {
					$lg_error[$lg_count] = 53;
					$lg_count++;
				}  else  {
					$sav_subt_buttons = $mydesc;
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
$dpgconn = conn_db();
if ( $lg_count == 0 )  {
   $bpl = new BoilerPlate ();
   if ($rec_key > 0)  {
   	$bres = $bpl->find_boiler_plate_by_lang($dpgconn, $sav_nav_refn, $sav_bp_lang);
   	if (!$bres)  {
   		$lg_error[$lg_count] = 54;
	   	$lg_count++;
   	}  else  {
   		$ex_bp_id = $bpl->getBpId();
   		if ($ex_bp_id != $rec_key)  {
   			$lg_error[$lg_count] = 55;
	   		$lg_count++;
   		}  else  {
   			$result = do_begin($dpgconn);
   			$lockres = $bpl->lock_boiler_plate_by_id($dpgconn, $rec_key);
   			if (!$lockres)  {
   				$lg_error[$lg_count] = 17;
	   			$lg_count++;
	   			$result = do_rollback($dpgconn);
   			}  else  {
   				$versno = $bpl->getAuVersNumb();
   				$versno++;
	   			$bpl->setPageTitle($sav_page_title);
					$bpl->setHeadingOne($sav_heading_one);
					$bpl->setHeadingTwo($sav_heading_two);
					$bpl->setHeadingTre($sav_heading_tre);
					$bpl->setHeadingQua($sav_heading_qua);
					$bpl->setHeadingCin($sav_heading_cin);
					$bpl->setHeadingSix($sav_heading_six);
					$bpl->setNavignBar($sav_navign_bar);
					$bpl->setCaptIons($sav_capt_ions);
					$bpl->setThtdCells($sav_thtd_cells);
					$bpl->setLegEnd($sav_leg_end);
					$bpl->setFormFields($sav_form_fields);
					$bpl->setSubtButtons($sav_subt_buttons);
					$bpl->setCoUserData($sav_notes);
					$bpl->setUpdatedBy($user_id);
					$bpl->setAuVersNumb($versno);
					$ubpres = $bpl->update_boiler_plate_by_id($dpgconn, $rec_key);
		   		if (!$ubpres)  {
		   			$lg_error[$lg_count] = 16;
			   		$lg_count++;
			   		$result = do_rollback($dpgconn);
		   		}  else  {
		   			$result = do_commit($dpgconn);
		   		}
		   	}
   		}
   	}
   }  else  {
		$bpl->setNavgnRefn($sav_nav_refn);
		$bpl->setLangCode($sav_bp_lang);
		$bpl->setPageTitle($sav_page_title);
		$bpl->setHeadingOne($sav_heading_one);
		$bpl->setHeadingTwo($sav_heading_two);
		$bpl->setHeadingTre($sav_heading_tre);
		$bpl->setHeadingQua($sav_heading_qua);
		$bpl->setHeadingCin($sav_heading_cin);
		$bpl->setHeadingSix($sav_heading_six);
		$bpl->setNavignBar($sav_navign_bar);
		$bpl->setCaptIons($sav_capt_ions);
		$bpl->setThtdCells($sav_thtd_cells);
		$bpl->setLegEnd($sav_leg_end);
		$bpl->setFormFields($sav_form_fields);
		$bpl->setSubtButtons($sav_subt_buttons);
		$bpl->setCoUserData($sav_notes);
   	$bpl->setInsertedBy ($user_id);
   	$result = do_begin($dpgconn);
   	$ibpres = $bpl->insert_boiler_plate($dpgconn);
   	if (!$ibpres)  {
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