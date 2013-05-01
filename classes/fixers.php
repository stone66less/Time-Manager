<?php
/*
* package info.timemanager.model;
*
* Routines to evaluate columns that may be null and set appropriate values.
*
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*
*/
class fixers  {
	
	const NVALUE = 'null';
		
	public function fix_bool ($bool_in)  {
		$my_bool = $bool_in? 't' : 'f';
		if ( ($my_bool == 't')  || ($my_bool == 'f') )  {
			$ret_bool = "'".$my_bool."'";
		}  else  {
			$ret_bool = "'f'";
		}
		return $ret_bool;
	}

	public function fix_char ($string_in)  {
		if ( strlen(trim($string_in)) == 0 )  {
			$ret_string = self::NVALUE;
		}  else  {
			$ret_string = "'".trim($string_in)."'";
		}
		return $ret_string;
	}

	public function fix_int ($int_in)  {
		if ( ctype_digit(trim($int_in)) )  {
			$ret_dig = trim($int_in);
		}  else  {
			$ret_dig = self::NVALUE;
		}
		return $ret_dig;
	}
	
	public function fix_numb ($numeric_in)  {
		if ( is_null($numeric_in) )  {
			$ret_num = self::NVALUE;
		}  else  {
			if ( is_scalar(trim($numeric_in)) )  {
				$ret_num = trim($numeric_in);
			}  else  {
				$ret_num = self::NVALUE;
			}
		}
		return $ret_num;
	}

	public function fix_date ($date_in)  {
		if ( is_null($date_in) )  {
			$ret_date = self::NVALUE;
		}  else  {
			$ret_date = "'".trim($date_in)."'";
		}
		return $ret_date;
	}

	public function fix_money ($money_in)  {
		$mon_len = strlen(trim($money_in));
		if ($mon_len > 0)  {
			$ret_money = "'".trim($money_in)."'";
		}  else  {
			$ret_money = self::NVALUE;
		}
		return $ret_money;
	}

}