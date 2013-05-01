<?php
require_once 'includes/db_functions.php';
require_once 'includes/crypt_funcs.php';
require_once 'classes/TimeUsers.php';
require_once 'classes/ErrorMessages.php';

$string = '';
$valid_user = FALSE;
$db_pass = '';
$tu_lname = '';
$tu_uuid = '';
$err_text = '';
$tu_super = FALSE;
$error_no = 0;
$def_lang = 'en-GB';
$haverror = FALSE;
$d_o_w = 0;
$db_time = 0;
$off_set = 0;
$corr_time = 0;

ob_start();

reset($_POST);
$k_one = key($_POST);
$v_one = current($_POST);
$dpgconn = conn_db();
if ( $k_one == 'logonCode')  {
	$l_code = strtoupper(trim($v_one));
   $tus = new TimeUsers();
	$fbool = $tus->find_time_users_by_logon ($dpgconn, $l_code);
	if (!$fbool) {
		$error_no  = 3;
		$haverror = TRUE;
	} else {
		$u_act = $tus->getActiveUser();
		$def_lang = $tus->getLangCode();
		if (!$u_act)  {
			$error_no = 4;
			$haverror = TRUE;
		}  else  {
			$valid_user = TRUE;
			$tu_uuid  = $tus->getTuId();
			$tu_lname = $tus->getLogonName();
		}  // user is active
	}  // user exists on database
	if ($haverror)   {
		$string = disp_error($dpgconn, $error_no, $def_lang);
	}  else  {
		$string = '<span class="ajax">' . $tu_lname . '</span></div><br /><div>';
	}
}
if ($k_one == 'userPass')  {
	if ($valid_user)  {
		if ( strlen($db_pass > 0) && strlen($v_one) > 0 )  {
			$p_val = bf_encdec('DEE', $v_one, $db_pass);
			if ($p_val != 'Y')  {
				$lg_error = '5';
				$string = disp_error($dpgconn,  $lg_error);
			}  else  {
				$string = '<span class="ajax">Password Field Processed.</span>';
			}
		}
	}
}
$postdata = ob_get_clean();
echo $string;
exit();

function disp_error ($dbc, $err_no, $lang_code)  {
	$erm = new ErrorMessages();
	$emess = $erm->find_error($dbc, $err_no, $lang_code);
	if (!$emess)  {
		$err_text = '<span class="error">Unable to find error ' . $err_no . '</span>';
	}  else  {
		$the_error = $erm->getErrorMessg();
		$error_string = $erm->getErrorString();
		$err_text = '<span class="error">ERROR ' . $err_no . ' ' . substr($the_error,0,20) . '<img src="../images/unknown.png" width="32" height="20" onmouseover="alert(' . $error_string . ');"/></span>';
	}
	return $err_text;
}
?>