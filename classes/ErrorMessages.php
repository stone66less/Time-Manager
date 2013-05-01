<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table error_messages.
*
*	Primary Key
*
*	Unique index
*		error_number, lang_code
*
*	Foreign Key
*		lang_code references supported_languages
*
*	Boolean columns
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class ErrorMessages extends fixers {

	private $erm_id;
	private $error_number;
	private $lang_code;
	private $error_messg;
	private $error_help;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	private $error_string;

	public function __construct()  {
		$this->error_number = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getErmId()          {return $this->erm_id;}
	public function getErrorNumber()    {return $this->error_number;}
	public function getLangCode()       {return $this->lang_code;}
	public function getErrorMessg()     {return $this->error_messg;}
	public function getErrorHelp()      {return $this->error_help;}
	public function getInsertedBy()     {return $this->inserted_by;}
	public function getInsertTime()     {return $this->insert_time;}
	public function getUpdatedBy()      {return $this->updated_by;}
	public function getUpdateTime()     {return $this->update_time;}
	public function getCoUserData()     {return $this->co_user_data;}
	public function getAuVersNumb()     {return $this->au_vers_numb;}

	public function getErrorString()    {return $this->error_string;}

	public function setErrorNumber($error_number)    {$this->error_number = self::fix_int($error_number);}
	public function setLangCode($lang_code)          {$this->lang_code = self::fix_char($lang_code);}
	public function setErrorMessg($error_messg)      {$this->error_messg = self::fix_char($error_messg);}
	public function setErrorHelp($error_help)        {$this->error_help = self::fix_char($error_help);}
	public function setInsertedBy($inserted_by)      {$this->inserted_by = self::fix_int($inserted_by);}
	public function setInsertTime($insert_time)      {$this->insert_time = $insert_time;}
	public function setUpdatedBy($updated_by)        {$this->updated_by = self::fix_int($updated_by);}
	public function setUpdateTime($update_time)      {$this->update_time = $update_time;}
	public function setCoUserData($co_user_data)     {$this->co_user_data = self::fix_char($co_user_data);}
	public function setAuVersNumb($au_vers_numb)     {$this->au_vers_numb = self::fix_int($au_vers_numb);}

	public function db_select ($dbc, $query)  {
		$result = pg_query($dbc, $query);
		if (!$result)  {
			return FALSE;
		}  else  {
			if ( pg_num_rows($result) == 1 )  {
				$row = pg_fetch_assoc($result);
				$this->erm_id = $row['erm_id'];
				$this->error_number = $row['error_number'];
				$this->lang_code = $row['lang_code'];
				$this->error_messg = $row['error_messg'];
				$this->error_help = $row['error_help'];
				$this->inserted_by = $row['inserted_by'];
				$this->insert_time = $row['insert_time'];
				$this->updated_by = $row['updated_by'];
				$this->update_time = $row['update_time'];
				$this->co_user_data = $row['co_user_data'];
				$this->au_vers_numb = $row['au_vers_numb'];
				if (strlen($this->error_help) > 0)  {
					$this->error_string = $this->error_messg . ' ' . $this->error_help;
				}  else  {
					$this->error_string = $this->error_messg;
				}
				return TRUE;
			}  else  {
				return FALSE;
			}
		}
	}

	public function find_error_by_id ($dbc, $errmid)  {
		$query = "SELECT * FROM error_messages WHERE erm_id = $errmid";
		return self::db_select ($dbc, $query);
	}

	public function lock_error_by_id ($dbc, $errmid)  {
		$query = "SELECT * FROM error_messages WHERE erm_id = $errmid FOR UPDATE";
		return self::db_select ($dbc, $query);
	}

	public function find_error ($dbc, $err_no, $lang_code)  {
		$query = "SELECT * FROM error_messages WHERE error_number = $err_no AND lang_code = '$lang_code'";
		return self::db_select ($dbc, $query);
	}

	public function insert_error_message ($dbc)  {
		$iquery = "INSERT INTO error_messages (error_number, lang_code, error_messg,
					error_help, inserted_by, co_user_data)
					VALUES ($this->error_number, $this->lang_code, $this->error_messg,
					$this->error_help, $this->inserted_by, $this->co_user_data)";
		$iresult = pg_query($dbc, $iquery);
		return $iresult;
	}

	public function update_error_message ($dbc, $ermid) {
		$dquery = "UPDATE error_messages SET error_messg = $this->error_messg,
			error_help = $this->error_help, updated_by = $this->updated_by,
			update_time = now(), co_user_data = $this->co_user_data,
			au_vers_numb = $this->au_vers_numb
			WHERE erm_id = $ermid";
		$dresult = pg_query($dbc, $dquery);
		if (!$dresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function list_error_messages ($dbc)  {
		$lquery = "SELECT * FROM error_messages ORDER BY error_number, lang_code";
		$lresult = pg_query($dbc, $lquery);
		return $lresult;
	}
	
	public function list_error_messages_by_lang ($dbc, $lang_code, $lim_it, $off_set)  {
		$lquery = "SELECT * FROM error_messages WHERE lang_code = '$lang_code' ORDER BY error_number LIMIT $lim_it OFFSET $off_set";
		$lresult = pg_query($dbc, $lquery);
		return $lresult;
	}


	public function count_error_messages_by_lang ($dbc, $lang_code)  {
		$lang_count = 0;
		$l_query = "SELECT COUNT(*) AS messg_count FROM error_messages WHERE lang_code = '$lang_code'";
		$cres = pg_query($dbc, $l_query);
		if ($cres)  {
			$row = pg_fetch_assoc($cres);
			$lang_count = $row['messg_count'];
		}
		return $lang_count;
	}

}
?>