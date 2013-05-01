<?php
// carry out log-in processing

session_start();
require_once 'includes/db_functions.php';
require_once 'includes/crypt_funcs.php';
require_once 'classes/TimeUsers.php';
require_once 'classes/GroupRoles.php';
require_once 'classes/LoggedUsers.php';
require_once 'classes/SupportedLanguages.php';
require_once 'actions/ErrorMessagesActions.php';
$x_super = 'N';
$x_view  = 'N';
$x_sysgr = 'N';
$lg_error = array();
$lg_count = 0;
session_unset();
$_SESSION = array();
$def_lang = 'en-GB';
$force_change = 'N';
$user_id = NULL;
$user_pw = NULL;
$user_ip = NULL;
$logged_user = 0;
$my_form = 0;
$navgn_array = array();
$callform = NULL;
$dpgconn = conn_db();
if (empty($_POST))  {
	$lg_error[$lg_count] = 2;
	$lg_count++;
}
if (isset($_POST['sesscheck'])) {
   $user_id = trim(strtoupper($_POST['logonCode']));
   $user_pw = trim($_POST['userpass']);
   if ( strlen($user_id) > 4 )  {
      $lg_error[$lg_count] = 3;
		$lg_count++;	
   }
   if (!ctype_alpha($user_id))  {
   	$lg_error[$lg_count] = 13;
		$lg_count++;
   }
   $result1 = new TimeUsers();
   $fbool = $result1->find_time_users_by_logon ($dpgconn, $user_id);
   if (!$fbool) {
      $lg_error[$lg_count] = 4;
      $lg_count++;
   } else {
     $tu_uuid  = $result1->getTuId();
     $tu_lname = $result1->getLogonName();
     $tu_grid  = $result1->getGrId();
     $tu_lang  = $result1->getLangCode();
     $tu_activ = $result1->getActiveUser();
     $tu_passw = $result1->getPassWord();
     $tu_utcff = $result1->getUtcOffset();
     $tu_lastp = $result1->getLastPword();
     $tu_super = $result1->getSuperUser();
     $tu_sysgr = $result1->getSysgrpUser();
     $def_lang = $tu_lang;
// Found our user. Test if they are still active.
     if (!$tu_activ) {
     	  $lg_error[$lg_count] = 5;
     	  $lg_count++;
     	}  else {
//  If password is null, then force a change.
        if ((trim($tu_passw) == '') || (empty($tu_passw)))  {
        	  $force_change = 'Y';
     	  } else {
        	    $hasher = bf_encdec('DEE', $user_pw, $tu_passw);
        	    if ($hasher == 'N') 	{
        	    	$lg_error[$lg_count] = 6;
        	    	$lg_count++;
        	    } elseif($hasher == 'B') {
        	    		$lg_error[$lg_count] = 7;
        	    		$lg_count++;
        	    }
        }
// Now fetch their group role.
        $result2 = new GroupRoles();
        $fbool = $result2->find_group_roles_by_id($dpgconn, $tu_grid);
        if (!$fbool) {
        	  $lg_error[$lg_count] = 62;
        	  $lg_count++;
        	} else {
           $gr_super = $result2->getSuperUser();
           $gr_view  = $result2->getViewOthers();
           $gr_chgpw = $result2->getChgPword();
           $gr_anvlim = $result2->getAnnivLimit();
           $gr_aptlim = $result2->getAppntLimit();
           $gr_tsklim = $result2->getTasksLimit();
           $gr_syslim = $result2->getSysadmLmt();
           if ( (($tu_super) && ($gr_super)) || ((!$tu_super) && (!$gr_super)) )  {
					$cool = TRUE;
           }  else  {
					$lg_error[$lg_count] = 70;
        	  		$lg_count++;
           }
// If never logged on before, force password change as next page.
           $result3 = new LoggedUsers();
           $ucount = $result3->count_logged_user ($dpgconn, $tu_uuid);
           if ( $ucount  == 0)  {
              $force_change = 'Y';
           	} else {
           		if (empty($tu_lastp) || ($tu_lastp == NULL))  {
           			$force_change = 'Y';
// Need to assess if a password change should be forced.
           		} else {
        				$currdate = new DateTime('now');
        				$format = 'Y-m-d';
                  $lastp = DateTime::createFromFormat($format, $tu_lastp);
        				if ( ($currdate->diff($lastp)->days) > $gr_chgpw ) {
        					$force_change = 'Y';
        				}
        			}
        		}
         }  // found group role
      }	// user is active
    }	// user found
}  else  {
	$lg_error[$lg_count] = 9;
	$lg_count++;
}

// If any errors detected we direct them back to the log-in page,
// otherwise if a password change is required they are directed
// to that page, else we launch the application at the default
// page according to user type.

$_SESSION['lastform'] = 'login.html';

if ( $lg_count == 0 ) {
// Register log-on
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$beg = do_begin($dpgconn);
	$nlu = new LoggedUsers();
	$nlu->setTuId($tu_uuid);
	$nlu->setIpAddress($user_ip);
   $result4 = $nlu->insert_logged_users($dpgconn);
   if (!$result4) {
     	  $lg_error[$lg_count] = 10;
     	  $lg_count++;	  
   }  else  {
   	$logged_user = $nlu->return_last_lu_id($dpgconn);
   }
}
if ( $lg_count > 0 ) {
	if (!is_null($user_ip))  {
		$rol = do_rollback($dpgconn);
	}
   unset($_POST['sesscheck']);
   unset($_POST['youruserid']);
   unset($_POST['yourpass']);
  	$_SESSION['lg_error'] = build_error_list($dpgconn, $lg_error, $def_lang, TRUE);
  	$_SESSION['errcount'] = $lg_count;
  	$callform = '../index.php?act=err';	
}   else  {
	$com = do_commit($dpgconn);
	$supl = new SupportedLanguages();
	$suplres = $supl->find_language_by_code($dpgconn, $def_lang);
	$charset = $supl->getCharSet();
	$lorr    = $supl->getDirEction();
	$wcome   = $supl->getWelcomeText();
	$fwell   = $supl->getFarwellText();
	$footr   = $supl->getFooterText();
	$yestxt  = $supl->getYesText();
	$nontxt  = $supl->getNoText();
  	 if ($gr_super) {
      $x_super = 'Y';
      if ( (is_null($gr_syslim)) || ($gr_syslim == 0) )  {
      	$gr_syslim = 10;
      }
  	 }  else  {
  	 	if ( (is_null($gr_anvlim)) || ($gr_anvlim == 0) )  {
      	$gr_anvlim = 3;
      }
      if ( (is_null($gr_aptlim)) || ($gr_aptlim == 0) )  {
      	$gr_aptlim = 5;
      }
      if ( (is_null($gr_tsklim)) || ($gr_tsklim == 0) )  {
      	$gr_tsklim = 10;
      }
  	 }
  	 if ($gr_view) {
      $x_view = 'Y';
  	 }
  	 if ($tu_sysgr)  {
  	 	$x_sysgr = 'Y';
  	 }
  	 $sp_posn = strcspn($tu_lname, ' ');
  	 $_SESSION['uname'] = substr($tu_lname, 0, $sp_posn);
  	 $_SESSION['userid'] = $tu_uuid;
  	 $_SESSION['grid']   = $tu_grid;
  	 $_SESSION['superu'] = $x_super;
  	 $_SESSION['viewot'] = $x_view;
  	 $_SESSION['sysgr']  = $x_sysgr;
  	 $_SESSION['langcode'] = $def_lang;
  	 $_SESSION['charset'] = $charset;
  	 $_SESSION['drctn']  = $lorr;
  	 $_SESSION['wlcm']   = $wcome;
  	 $_SESSION['fwell']  = $fwell;
  	 $_SESSION['footxt'] = $footr;
  	 $_SESSION['yestxt'] = $yestxt;
  	 $_SESSION['nontxt'] = $nontxt;
  	 $_SESSION['luid']   =  $logged_user;
  	 $_SESSION['tmver']  = '1:0';
  	 $_SESSION['pwdch'] = $force_change;
  	 $_SESSION['anvlim'] = $gr_anvlim;
  	 $_SESSION['aptlim'] = $gr_aptlim;
  	 $_SESSION['tsklim'] = $gr_tsklim;
  	 $_SESSION['syslim'] = $gr_syslim;
  	 $navgn_array[0] = '1P0';
  	 $_SESSION['navgnarr'] = $navgn_array;
  	 $callform = '../index.php?act=men';
}
header("Location: $callform");
exit();
?>