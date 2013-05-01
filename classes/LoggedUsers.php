<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table logged_users.
*
*	Primary Key
*		lu_id
*
*	Foreign Key
*		tu_id references time_users
*
*	Boolean columns
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class LoggedUsers extends fixers {

	private $lu_id;
	private $tu_id;
	private $ip_address;
	private $logon_time;
	private $logoff_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->lu_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getLuId()       {return $this->lu_id;}
	public function getTuId()       {return $this->tu_id;}
	public function getIpAddress()  {return $this->ip_address;}
	public function getLogonTime()  {return $this->logon_time;}
	public function getLogoffTime() {return $this->logoff_time;}
	public function getCoUserData() {return $this->co_user_data;}
	public function getAuVersNumb() {return $this->au_vers_numb;}

//	public function setLuId($lu_id)      {$this->lu_id = self::fix_int($lu_id);}
	public function setTuId($tu_id)              {$this->tu_id = self::fix_int($tu_id);}
	public function setIpAddress($ip_address)    {$this->ip_address = self::fix_char($ip_address);}
	public function setLogonTime($logon_time)    {$this->logon_time = $logon_time;}
	public function setLogoffTime($logoff_time)  {$this->logoff_time = $logoff_time;}
	public function setCoUserData($co_user_data) {$this->co_user_data = self::fix_char($co_user_data);}
	public function setAuVersNumb($au_vers_numb) {$this->au_vers_numb = self::fix_int($au_vers_numb);}

	public function db_select ($dbc, $query)  {
		$result = pg_query($dbc, $query);
		if (!$result)  {
			return FALSE;
		}  else  {
			if ( pg_num_rows($result) == 1 )  {
				$row = pg_fetch_assoc($result);
				$this->lu_id = $row['lu_id'];
				$this->tu_id = $row['tu_id'];
				$this->ip_address = $row['ip_address'];
				$this->logon_time = $row['logon_time'];
				$this->logoff_time = $row['logoff_time'];
				$this->co_user_data = $row['co_user_data'];
				$this->au_vers_numb = $row['au_vers_numb'];
				return TRUE;
			}  else  {
				return FALSE;
			}
		}
	}

	public function find_logged_users_by_id ($dbc, $lu_id) {
		$query = "SELECT * FROM logged_users WHERE lu_id = $lu_id";
		return self::db_select($dbc, $query);
	}

	public function lock_logged_users_by_id ($dbc, $lu_id) {
		$query = "SELECT * FROM logged_users WHERE lu_id = $lu_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function update_logged_users_by_id ($dbc, $lu_id) {
		$uquery = "UPDATE logged_users SET logoff_time = now(),
			au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE lu_id =  $lu_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_logged_users ($dbc) {
		$iquery = "INSERT INTO logged_users(tu_id, ip_address)
				 VALUES ($this->tu_id, $this->ip_address)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function return_last_lu_id ($dbc)  {
		$last_val = 0;
		$lvquery = "SELECT lastval() AS last_used_id";
		$lvresult = pg_query($dbc, $lvquery);
		if ($lvresult)  {
			$row = pg_fetch_assoc($lvresult);
			$last_val = $row['last_used_id'];
		}
		return $last_val;
	}

	public function count_logged_user ($dbc, $user_id)  {
		$u_count = 0;
		$cquery = "SELECT COUNT(*) AS user_count FROM logged_users WHERE tu_id = $user_id";
		$cres = pg_query ($dbc, $cquery);
		if ( ($cres) && (pg_num_rows($cres) == 1) )  {
			$row = pg_fetch_assoc($cres);
			$u_count = $row['user_count'];
		}
		return $u_count;
	}

	public function log_user_off ($dbc, $lu_id)  {
		$lquery = "SELECT * FROM logged_users WHERE lu_id = $lu_id FOR UPDATE";
		$result = pg_query($dbc, $lquery);
		if ($result)  {
			$oquery = "UPDATE logged_users SET logoff_time = now() WHERE lu_id = $lu_id";
			$ores = pg_query($dbc, $oquery);
		}
	}

}
?>