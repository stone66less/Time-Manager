<?php
// Validates a string for what we consider to be illegal characters.
// If boolean $esc_aped is TRUE, then we strip duplicate space characters from
// string, explode it into an array, and for each vale beginning with an ampersand
// it must terminate with a semi-colon. E.g., &#39; or &copy; are valid but  it;
// is treated as invalid.
function check_string ($string_in, $esc_aped)  {
	$not_allowed = '=;@()%';
	$bad_string = FALSE;
	$word_array = array();
	if ( strlen($string_in) != strcspn($string_in, $not_allowed) )  {
		if ($esc_aped)  {
			$word_array = explode(' ', $string_in);
			$word_count = count($word_array);
			if ($word_count > 0)  {
				foreach($word_array as $key => $value)  {
					$w_word = trim($value);
					$w_len = strlen($w_word);
					if ($w_len > 0)  {
						$scol_count = substr_count($w_word, ';');
						if ($scol_count > 0)  {
							$amp_count = substr_count($w_word, '&');
							if ($scol_count == $amp_count)  {
								$cool = TRUE;
							}  else  {
								$bad_string = TRUE;
							}
						}
					}
				}
			}
		}  else  {
			$bad_string = TRUE;
		}
   }  else  {
		$phptag_count = substr_count($string_in, '<?');
		if ($phptag_count > 0)  {
			$bad_string = TRUE;
		}
   }
   return $bad_string;
}

// Validates text input and escapes html entities.
function validate_string ($string_in, $ic_err, $s_len, $len_err, $esc_aped=FALSE)  {
	$vs_error = array();
	$vs_count = 0;
	$str_error = check_string($string_in, $esc_aped);
	if ($str_error)  {
   	$vs_error[$vs_count] = $ic_err;
   	$vs_count++;
   }
   $sav_string = htmlentities($string_in, ENT_QUOTES, 'UTF-8');
   $string_size = (int)$s_len;
   if ( strlen($sav_string) > $string_size )  {
   	$vs_error[$vs_count] = $len_err;
   	$vs_count++;
   }
   if ( $vs_count == 0 )  {
   	$vs_error = array('stringok' => $sav_string);
   }
   return $vs_error;
}

// an attempt to see if a string is in a date format.
function is_date ($string_in)   {
   if ( strlen(trim($string_in)) == 8  || strlen(trim($string_in)) == 10 )  {
   	$my_date = date_as_ymd ($string_in);
   	$day = substr($my_date, 8, 2 );
		$mon = substr($my_date, 5, 2 );
		$yar = substr($my_date, 0, 4 );
   	if ( !checkdate($mon, $day, $yar) )  {
   		return 109;
   	}  else  {
   		return 0;
   	}
   }  else  {
   	return 110;
   }
}

// Explodes a string into an array using any delimiter.
function blowup($delimiter,$string){
	if(strpos($string,$delimiter)===false){
		return array($string);
	} else {
		return explode($delimiter, $string);
	}
}

// A more sophisticated method to evaluate a text string as a date.
function valid_date($date_in, $format = 'YMD') {
$str_date = NULL;
$separator = NULL;
$date_array = array();
$date_size = strlen($date_in);
	if ( ($date_size >= 6) && ($date_size <= 10) ) {
		for ($i = 0; $i < $date_size; $i++)  {
			$c_char = substr($date_in, $i, 1);
			if (!ctype_digit($c_char)) {
				$separator = $c_char;
				break;
			}
		}
// trigger_error('separator is ' . $separator);
		if (!is_null($separator)) {
			$date_array = blowup($separator, $date_in);
			$date_parts = count($date_array);
			if ($date_parts == 3)  {
				switch($format) {
					case 'YMD':
					$year = $date_array[0];
					$mon  = $date_array[1];
					$day  = $date_array[2];
					break;
					case 'MDY':
					$year = $date_array[2];
					$mon  = $date_array[0];
					$day  = $date_array[1];
					break;
					case 'DMY':
					$year = $date_array[2];
					$mon  = $date_array[1];
					$day  = $date_array[0];
					break;
				}
				$year = str_pad($year, 2, '0', STR_PAD_LEFT);
				if ( strlen($year) != 4) {
					$year = '20' . $year;
				}
				$mon = str_pad($mon, 2, '0', STR_PAD_LEFT);
				$day = str_pad($day, 2, '0', STR_PAD_LEFT);
				$str_date = $day . '-' . $mon . '-' . $year;
			}
		}
	}
	return $str_date;
}

// A function to return a date in a given format.
function date_as_ymd ($string_in, $ymd=TRUE)   {
	if ( strlen(trim($string_in)) == 10 )  {
		$charattwo = substr(trim($string_in), 2, 1);
		if ( ($charattwo < '0')  || ($charattwo > '9') )   {
			$day = substr(trim($string_in), 0, 2 );
			$mon = substr(trim($string_in), 3, 2 );
			$yar = substr(trim($string_in), 6, 4 );
		}  else  {
			$day = substr(trim($string_in), 8, 2 );
			$mon = substr(trim($string_in), 5, 2 );
			$yar = substr(trim($string_in), 0, 4 );
		}
	}  else  {
			$day = substr(trim($string_in), 0, 2 );
			$mon = substr(trim($string_in), 3, 2 );
			$yar = '20' . substr(trim($string_in), 6, 2 );
	}
	if ($ymd)  {
		return $yar . '-' . $mon . '-' . $day;
	}  else  {
		return $day . '-' . $mon . '-' . $yar;
	}
}

// Returns today's date in specified format.
function todays_date ($format)  {
	$currdate = new DateTime('now', new DateTimeZone(date_default_timezone_get()));
	$todayd = (string)$currdate->format('Y-m-d');
	switch($format) {
	case  'Y-m-d' :
	$strdate = $todayd;
	break;	
	case  'yyyymmdd' :
	$strdate = substr($todayd,0,4) . substr($todayd,5,2) . substr($todayd,8,2);
	break;
	case 'ddmmyyyy':
	$strdate = substr($todayd,8,2) . substr($todayd,5,2) . substr($todayd,0,4);
	break;
	case 'dd-mm-yyyy' :
	$strdate = substr($todayd,8,2) . '-' . substr($todayd,5,2) . '-' . substr($todayd,0,4);
	break;
	case 'mmddyyyy':
	$strdate = substr($todayd,5,2) . substr($todayd,8,2) . substr($todayd,0,4);
	break;
	default:
	$strdate = $todayd;
	break;
	}
	return $strdate;
}

// Validates a string as having hours and minutes.
function valid_time ($time_in)  {
$error_no = 0;
$hours_in = 0;
$minutes_in = 0;
	$s_len = strlen($time_in);
	if ( ($s_len > 0)  && ($s_len < 5) )  {
		if ($s_len < 3)  {
			$hours_in = 0;
			$minutes_in = (int)$time_in;
		}
		if ($s_len == 3)  {
			$hours_in = (int)substr($time_in,0,1);
			$minutes_in = (int)substr($time_in,1,2);
		}
		if ($s_len == 4)  {
			$hours_in = (int)substr($time_in,0,2);
			$minutes_in = (int)substr($time_in,2,2);
		}
		if ( ($hours_in < 0 )  ||  ($hours_in > 24) )  {
			$error_no = 195;
		}
		if ( ($minutes_in < 0) || ($minutes_in > 59) )  {
			$error_no = 196;
		}
	}  else  {
		$error_no = 194;
	}
	return $error_no;
}

// Validates day value against month.
function valid_day_month ($daymon_in)  {
$error_no = 0;
$mon_array = array(31,29,31,30,31,30,31,31,30,31,30,31);
	$dm_len = strlen($daymon_in);
	if ( ($dm_len > 1) && ($dm_len < 6) )  {
		$day_month = blowup('|', $daymon_in);
		if ( is_array($day_month) )  {
			$day_val = $day_month[0];
			$mon_val = $day_month[1];
			if ( ($mon_val < 1) || ($mon_val > 12) )  {
				$error_no = 19;
			}  else  {
				$max_day = $mon_array[($mon_val - 1)];
				if ( ($day_val < 1)  || ($day_val > $max_day) )  {
					$error_no = 20;
				}
			}
		}  else  {
			$error_no = 71;
		}
	}  else  {
		$error_no = 72;
	}
	return $error_no;
}

// E-mail address validation. a@b.cd is a VALID e-mail address!
function valid_email ($in_email)   {
	$t_email = trim($in_email);
	$len_e   = strlen($t_email);
	$atr_pos = strrpos($t_email, '@');
	$atf_pos = strpos($t_email, '@');
	if ( $atr_pos == $atf_pos)   {
		$dot_pos = strrpos($t_email, '.');
		if ( (($atf_pos + 1) < $dot_pos)  &&  ($dot_pos < $len_e) )  {
			$dom_len = ($dot_pos - $atf_pos - 1);
			$dom_part = substr($t_email, ($atf_pos + 1), $dom_len);
			if ( !check_string ($dom_part) )  {
				$abool = checkdnsrr($dom_part, 'MX');
				return $abool;
			}  else  {
				return FALSE;
			}
		}  else  {
			return FALSE;
		}
	}  else  {
		return FALSE;
	}
}

// Assumes decimal point is a full stop.
// TODO -- internationalize this function.
function string_to_number ($in_value)   {
	$dig_array = array('9', '4', '49', '499', '4999', '49999');
	$curr_val = 0;
	$dot_pos = strpos($in_value, '.');
	$val_len = strlen($in_value);
	$sem_dp = str_replace('.', '', $in_value);
	$sem_dp = str_replace(',', '', $sem_dp);
	$curr_val = intval($sem_dp);
	if ( ($dot_pos == 0) || (is_null($dot_pos)) )  {
		$curr_val = $curr_val * 100;
	}  else  {
		$num_dec = $val_len - $dot_pos - 1;
		if ($num_dec == 1)  {
			$curr_val = $curr_val * 10;
		}
		if ($num_dec > 2)  {
			$x_dig = $num_dec - 2;
			$x_len = strlen($curr_val);
			$base_val = substr($curr_val, 0, ($x_len - $x_dig));
			$dig_over = substr($curr_val,($x_len - $x_dig));
			if ($dig_over > $dig_array[$x_dig]) {
				if ($curr_val > 0)  {
					$curr_val = $base_val + 1;
				} else  {
					$curr_val = $base_val - 1;
				}
			}
		}
	}
	return  $curr_val;	
}

// Validates an IP address.
function valid_ip_range ($ip_string)  {
	$ip_temp = trim($ip_string);
	$ip_return = 'N';
	$test_ipv4 = strcspn($ip_temp, '.');
	if ($test_ipv4 > 0)  {
		$ipv4_slash = strcspn($ip_temp, '/');
		if ( ($ipv4_slash > 0) && ($ipv4_slash < (strlen($ip_temp))) )  {
			$mask_ok = FALSE;
			$front_ok = FALSE;
			$first_part = substr($ip_temp,0,$ipv4_slash);
			$mask_part  = substr($ip_temp,($ipv4_slash + 1));
			if ( ($mask_part > 1) && ($mask_part < 32) )  {
				$mask_ok = TRUE;
			}
			$quad_arr = explode('.', $first_part);
			$q_count = 0;
			if ( is_array($quad_arr)  )  {
				$a_count = count($quad_arr);
				for($i=0; $i < $a_count; $i++) {
					$q_val = $quad_arr[$i];
					if ( ($q_val > -1) && ($q_val < 256) )  {
						$q_count++;
					}
				}
				if ( $mask_ok && ($a_count == $q_count) )  {
					$ip_return = '4';
				}	
			}
		}  else  {
			$ipv4_val = filter_var($ip_temp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
			if ($ipv4_val !== FALSE)  {
				$ip_return = '4';
			}
		}
	}  else  {
		$ipv6_val = filter_var($ip_temp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
		if ($ipv6_val !== FALSE)  {
			$ip_return = '6';
		}
	}
	return $ip_return;
}

// Check if a string is JSON encoded. I.e., it has the format '{"a":"b","c":"d"}' 
function json_string ($in_string)  {
	$is_json = FALSE;
	$json = json_decode($in_string);
	if (json_last_error() === JSON_ERROR_NONE) {
		$is_json = TRUE;
	}
	return $is_json;
}

// called at the successful conclusion of forms processing.
function sessvars_unset()  {
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
}
?>