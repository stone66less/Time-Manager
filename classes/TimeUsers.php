<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table time_users.
*
*	Primary Key
*		tu_id
*
*	Unique Key
*		logon_id
*
*	Foreign Keys
*		lang_code references supported_languages
*		gr_id references group_roles
*
*	Boolean columns
*		active_user
*		super_user
*		sysgrp_user
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class TimeUsers extends fixers {

	private $tu_id;
	private $logon_id;
	private $logon_name;
	private $gr_id;
	private $lang_code;
	private $active_user;
	private $super_user;
	private $sysgrp_user;
	private $fixed_ip;
	private $pass_word;
	private $utc_offset;
	private $phone_extn;
	private $email_addr;
	private $last_pword;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->tu_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getTuId()          {return $this->tu_id;}
	public function getLogonId()       {return $this->logon_id;}
	public function getLogonName()     {return $this->logon_name;}
	public function getGrId()          {return $this->gr_id;}
	public function getLangCode()      {return $this->lang_code;}
	public function getActiveUser()    {if ($this->active_user == "t") { return TRUE; } else { return FALSE;}}
	public function getSuperUser()     {if ($this->super_user == "t") { return TRUE; } else { return FALSE;}}
	public function getSysgrpUser()    {if ($this->sysgrp_user == "t") { return TRUE; } else { return FALSE;}}
	public function getFixedIp()       {return $this->fixed_ip;}
	public function getPassWord()      {return $this->pass_word;}
	public function getUtcOffset()     {return $this->utc_offset;}
	public function getPhoneExtn()     {return $this->phone_extn;}
	public function getEmailAddr()     {return $this->email_addr;}
	public function getLastPword()     {return $this->last_pword;}
	public function getInsertedBy()    {return $this->inserted_by;}
	public function getInsertTime()    {return $this->insert_time;}
	public function getUpdatedBy()     {return $this->updated_by;}
	public function getUpdateTime()    {return $this->update_time;}
	public function getCoUserData()    {return $this->co_user_data;}
	public function getAuVersNumb()    {return $this->au_vers_numb;}

//	public function setTuId($tu_id)      {$this->tu_id = self::fix_int($tu_id);}
	public function setLogonId($logon_id)        {$this->logon_id = self::fix_char($logon_id);}
	public function setLogonName($logon_name)    {$this->logon_name = self::fix_char($logon_name);}
	public function setGrId($gr_id)              {$this->gr_id = self::fix_int($gr_id);}
	public function setLangCode($lang_code)      {$this->lang_code = self::fix_char($lang_code);}
	public function setActiveUser($active_user)  {$this->active_user = self::fix_bool($active_user);}
	public function setSuperUser($super_user)    {$this->super_user = self::fix_bool($super_user);}
	public function setSysgrpUser($sysgrp_user)  {$this->sysgrp_user = self::fix_bool($sysgrp_user);}
	public function setFixedIp($fixed_ip)        {$this->fixed_ip = self::fix_char($fixed_ip);}
	public function setPassWord($pass_word)      {$this->pass_word = self::fix_char($pass_word);}
	public function setUtcOffset($utc_offset)    {$this->utc_offset = self::fix_int($utc_offset);}
	public function setPhoneExtn($phone_extn)    {$this->phone_extn = self::fix_int($phone_extn);}
	public function setEmailAddr($email_addr)    {$this->email_addr = self::fix_char($email_addr);}
	public function setLastPword($last_pword)    {$this->last_pword = self::fix_date($last_pword);}
	public function setInsertedBy($inserted_by)  {$this->inserted_by = self::fix_int($inserted_by);}
	public function setInsertTime($insert_time)  {$this->insert_time = $insert_time;}
	public function setUpdatedBy($updated_by)    {$this->updated_by = self::fix_int($updated_by);}
	public function setUpdateTime($update_time)  {$this->update_time = $update_time;}
	public function setCoUserData($co_user_data) {$this->co_user_data = self::fix_char($co_user_data);}
	public function setAuVersNumb($au_vers_numb) {$this->au_vers_numb = self::fix_int($au_vers_numb);}

	public function db_select ($dbc, $query)  {
		$result = pg_query($dbc, $query);
		if (!$result)  {
			return FALSE;
		}  else  {
			if ( pg_num_rows($result) == 1 )  {
				$row = pg_fetch_assoc($result);
				$this->tu_id = $row['tu_id'];
				$this->logon_id = $row['logon_id'];
				$this->logon_name = $row['logon_name'];
				$this->gr_id = $row['gr_id'];
				$this->lang_code = $row['lang_code'];
				$this->active_user = $row['active_user'];
				$this->super_user = $row['super_user'];
				$this->sysgrp_user = $row['sysgrp_user'];
				$this->fixed_ip = $row['fixed_ip'];
				$this->pass_word = $row['pass_word'];
				$this->utc_offset = $row['utc_offset'];
				$this->phone_extn = $row['phone_extn'];
				$this->email_addr = $row['email_addr'];
				$this->last_pword = $row['last_pword'];
				$this->inserted_by = $row['inserted_by'];
				$this->insert_time = $row['insert_time'];
				$this->updated_by = $row['updated_by'];
				$this->update_time = $row['update_time'];
				$this->co_user_data = $row['co_user_data'];
				$this->au_vers_numb = $row['au_vers_numb'];
				return TRUE;
			}  else  {
				return FALSE;
			}
		}
	}

	public function find_time_users_by_id ($dbc, $tu_id) {
		$query = "SELECT * FROM time_users WHERE tu_id = $tu_id";
		return self::db_select($dbc, $query);
	}

	public function find_time_users_by_logon ($dbc, $log_on) {
		$query = "SELECT * FROM time_users WHERE logon_id = '$log_on'";
		return self::db_select($dbc, $query);
	}

	public function lock_time_users_by_id ($dbc, $tu_id) {
		$query = "SELECT * FROM time_users WHERE tu_id = $tu_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function update_time_users_by_id ($dbc, $tu_id) {
		$uquery = "UPDATE time_users SET logon_name = $this->logon_name, gr_id = $this->gr_id,
					lang_code = $this->lang_code, active_user = $this->active_user, super_user = $this->super_user,
					sysgrp_user = $this->sysgrp_user, fixed_ip = $this->fixed_ip,
					utc_offset = $this->utc_offset, phone_extn = $this->phone_extn, email_addr = $this->email_addr,
					updated_by = $this->updated_by, co_user_data = $this->co_user_data,
					au_vers_numb = $this->au_vers_numb, update_time = now()
					WHERE tu_id = $tu_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_time_users ($dbc) {
		$iquery = "INSERT INTO time_users(logon_id, logon_name, gr_id,
			lang_code, active_user, super_user, sysgrp_user,
			fixed_ip, utc_offset, phone_extn, pass_word,
			email_addr, inserted_by, co_user_data)
			VALUES ($this->logon_id, $this->logon_name, $this->gr_id,
			$this->lang_code, $this->active_user, $this->super_user, $this->sysgrp_user,
			$this->fixed_ip, $this->utc_offset, $this->phone_extn, $this->pass_word,
			$this->email_addr, $this->inserted_by, $this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function update_pass_word_only ($dbc, $tu_id) {
		$uquery = "UPDATE time_users SET pass_word = $this->pass_word,
			last_pword = TO_DATE($this->last_pword,'YYYY-MM-DD'),
			updated_by = $this->updated_by,
			au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE tu_id =  $tu_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function count_time_users ($dbc)  {
		$tu_count = 0;
		$cquery = "SELECT COUNT(*) AS user_count FROM time_users";
		$result = pg_query($dbc, $cquery);
		if ( ($result) && (pg_num_rows($result) == 1) )  {
			$row = pg_fetch_assoc($result);
			$tu_count = $row['user_count'];
		}
		return $tu_count;
	}

	public function list_all_time_users ($dbc, $lim_it, $off_set)  {
		$lquery = "SELECT * FROM time_users ORDER BY logon_name OFFSET $off_set LIMIT $lim_it";
		$result = pg_query($dbc, $lquery);
		return $result;
	}

}
?>