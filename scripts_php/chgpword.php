<?php
//Verify and change a user's password.
require_once 'includes/db_functions.php';
require_once 'includes/crypt_funcs.php';
require_once 'includes/charfunctions.php';
require_once 'classes/TimeUsers.php';
require_once 'actions/ErrorMessagesActions.php';
session_start();
$lg_error = array();
$lg_count = 0;
$newpass = NULL;
$def_lang = NULL;
$callform = NULL;
if ( (isset($_SESSION['uname'])) && (isset($_SESSION['userid'])) && (isset($_SESSION['langcode'])) ) {
	$def_lang = $_SESSION['langcode'];
	if ( isset($_POST['token']) && (strlen($_POST['token']) > 0) && ($_POST['token'] === $_SESSION['token'])
		&& (isset($_SESSION['strtime'])) 
		&& ( $_SERVER['REQUEST_TIME'] < (int)$_SESSION['strtime'] + ( (int)$_SESSION['idletime'] * 60)  ) )  {
		if ( (empty($_POST['inputPassword'])) || (empty($_POST['confirmPword'])) ) {
			$lg_error[$lg_count] = 34;
			$lg_count++;
		} else {
			$inp_pw = trim($_POST['inputPassword']);
			$cnf_pw = trim($_POST['confirmPword']);
			if ( $inp_pw != $cnf_pw)  {
				$lg_error[$lg_count] = 35;
				$lg_count++;
			} else {
// Encrypt user's password.
				$newpass = bf_encdec('ENC', $inp_pw, 'abc');
				if ( (trim($newpass) == '') || (rtrim($newpass,"\0") == '') ) {
					$lg_error[$lg_count] = 36;
					$lg_count++;
				}
			}
		}
	}  else  {
		$lg_error[$lg_count] = 9;
   	$lg_count++;	
	}
} else {
// User NOT registered.
   $lg_error[$lg_count] = 9;
   $lg_count++;
}

$dpgconn = conn_db();
if ( $lg_count == 0 )  {
	$t_id = (int)$_SESSION['userid'];
	$ntu = new TimeUsers();
	$result = do_begin($dpgconn);
	$result = $ntu->lock_time_users_by_id($dpgconn, $t_id);
	if (!$result)  {
		$lg_error[$lg_count] = 4;
		$lg_count++;
		$result = do_rollback($dpgconn);
	}  else  {
		$vers_no = $ntu->getAuVersNumb();
		$vers_no++;
		$tday = todays_date('Y-m-d');
		$ntu->setPassWord($newpass);
		$ntu->setLastPword($tday);
		$ntu->setUpdatedBy($t_id);
		$ntu->setAuVersNumb($vers_no);
		$updated = $ntu->update_pass_word_only($dpgconn, $t_id);
		if (!$updated) {
     		$lg_error[$lg_count] = 37;
     		$lg_count++;
     		$result = do_rollback($dpgconn);
     	}  else  {
     		$result = do_commit($dpgconn);
     	}
	}
}
if ( $lg_count > 0 )  {
  	$_SESSION['lg_error'] = build_error_list($dpgconn, $lg_error, $def_lang, TRUE);
  	$callform = '../index.php?act=err';
}  else  {
	if ( isset($_SESSION['pwdch']) )  {  // set by log-in script
		unset($_SESSION['pwdch']);
	}
   $callform = '../index.php?act=men';
}
header("Location: $callform");
exit();
?>