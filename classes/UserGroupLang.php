<?php
/*
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class UserGroupLang  {
	
	private $tu_id;
	private $logon_id;
	private $logon_name;
	private $active_user;
	private $sysgrp_user;
	private $lang_code;
	private $gr_id;
	private $gr_name;
	private $view_others;
	private $lang_name;

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
	public function getSysgrpUser()    {if ($this->sysgrp_user == "t") { return TRUE; } else { return FALSE;}}
	public function getGrName()        {return $this->gr_name;}
	public function getViewOthers()    {if ($this->view_others == "t") { return TRUE; } else { return FALSE;}}
	public function getLangName()      {return $this->lang_name;}

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
				$this->sysgrp_user = $row['sysgrp_user'];
				$this->gr_name = $row['gr_name'];
				$this->view_others = $row['view_others'];
				$this->lang_name = $row['lang_name'];
				return TRUE;
			}  else  {
				return FALSE;
			}
		}
	}

	public function find_ugl_by_id ($dbc, $user_id)  {
		$query = "SELECT * FROM user_group_lang WHERE tu_id = $user_id";
		return self::db_select($dbc, $query);
	}

	public function list_all_users ($dbc, $lim_it, $off_set, $order_by)  {
		$lquery = "SELECT * FROM user_group_lang ORDER BY $order_by OFFSET $off_set LIMIT $lim_it";
		$result = pg_query($dbc, $lquery);
		return $result;
	}

}
?>