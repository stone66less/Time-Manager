<?php
// Action factory for table error_messages

require_once '../classes/ErrorMessages.php';

function return_error ($dbc, $err_no, $lang)  {
	$err_string = '<p class="error">';
	$erm = new ErrorMessages();
	$ermres = $erm->find_error($dbc, $err_no, $lang);
	if (!$ermres)  {
		$err_string = $err_string . 'DB Error finding this error ' . $err_no . ' Language ' . $lang . '</p>';  
	}  else  {
		$err_string = $err_string . $erm->getErrorMessg() . '</p>';
	}
	return $err_string;
}

function build_error_list ($dbc, $err_array, $lang_code, $inc_help)  {
	$error_string = NULL;
	if (is_array($err_array))  {
		$erm = new ErrorMessages();
		foreach($err_array as $key => $value)  {
			$err_no = $value;
			$res = $erm->find_error($dbc, $err_no, $lang_code);
			if (!$res)  {
				$error_string = $error_string . ' Missing Error ' . $err_no . ' Lang ' . $lang_code;
			}  else  {
				if ($inc_help)  {
					if (is_null($error_string))  {
						$error_string = $erm->getErrorString();
					}  else  {
						$error_string = $error_string . '<br />' . $erm->getErrorString();
					}
				}  else  {
					if (is_null($error_string))  {
						$error_string = $erm->getErrorMessg();
					}  else  {
						$error_string = $error_string . '<br />' . $erm->getErrorMessg();
					}
				}
			}
		}
	}  else  {
		$error_string = 'Unable to evaluate error array';
	}
	return $error_string;
}

?>