<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table group_roles.
*
*	Primary Key
*		gr_id
*
*	Boolean columns
*		super_user
*		view_others
*		group_inuse
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class GroupRoles extends fixers {

	private $gr_id;
	private $gr_name;
	private $super_user;
	private $view_others;
	private $group_inuse;
	private $chg_pword;
	private $anniv_limit;
	private $appnt_limit;
	private $tasks_limit;
	private $sysadm_lmt;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->gr_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getGrId()         {return $this->gr_id;}
	public function getGrName()       {return $this->gr_name;}
	public function getSuperUser()    {if ($this->super_user == "t") { return TRUE; } else { return FALSE;}}
	public function getViewOthers()   {if ($this->view_others == "t") { return TRUE; } else { return FALSE;}}
	public function getGroupInuse()   {if ($this->group_inuse == "t") { return TRUE; } else { return FALSE;}}
	public function getChgPword()     {return $this->chg_pword;}
	public function getAnnivLimit()   {return $this->anniv_limit;}
	public function getAppntLimit()   {return $this->appnt_limit;}
	public function getTasksLimit()   {return $this->tasks_limit;}
	public function getSysadmLmt()    {return $this->sysadm_lmt;}
	public function getInsertedBy()   {return $this->inserted_by;}
	public function getInsertTime()   {return $this->insert_time;}
	public function getUpdatedBy()    {return $this->updated_by;}
	public function getUpdateTime()   {return $this->update_time;}
	public function getCoUserData()   {return $this->co_user_data;}
	public function getAuVersNumb()   {return $this->au_vers_numb;}

//	public function setGrId($gr_id)      {$this->gr_id = self::fix_int($gr_id);}
	public function setGrName($gr_name)          {$this->gr_name = self::fix_char($gr_name);}
	public function setSuperUser($super_user)    {$this->super_user = self::fix_bool($super_user);}
	public function setViewOthers($view_others)  {$this->view_others = self::fix_bool($view_others);}
	public function setGroupInuse($group_inuse)  {$this->group_inuse = self::fix_bool($group_inuse);}
	public function setChgPword($chg_pword)      {$this->chg_pword = self::fix_int($chg_pword);}
	public function setAnnivLimit($anniv_limit)  {$this->anniv_limit = self::fix_int($anniv_limit);}
	public function setAppntLimit($appnt_limit)  {$this->appnt_limit = self::fix_int($appnt_limit);}
	public function setTasksLimit($tasks_limit)  {$this->tasks_limit = self::fix_int($tasks_limit);}
	public function setSysadmLmt($sysadm_lmt)    {$this->sysadm_lmt = self::fix_int($sysadm_lmt);}
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
				$this->gr_id = $row['gr_id'];
				$this->gr_name = $row['gr_name'];
				$this->super_user = $row['super_user'];
				$this->view_others = $row['view_others'];
				$this->group_inuse = $row['group_inuse'];
				$this->chg_pword = $row['chg_pword'];
				$this->anniv_limit = $row['anniv_limit'];
				$this->appnt_limit = $row['appnt_limit'];
				$this->tasks_limit = $row['tasks_limit'];
				$this->sysadm_lmt = $row['sysadm_lmt'];
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

	public function find_group_roles_by_id ($dbc, $gr_id) {
		$query = "SELECT * FROM group_roles WHERE gr_id = $gr_id";
		return self::db_select($dbc, $query);
	}

	public function lock_group_roles_by_id ($dbc, $gr_id) {
		$query = "SELECT * FROM group_roles WHERE gr_id = $gr_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function update_group_roles_by_id ($dbc, $gr_id) {
		$uquery = "UPDATE group_roles SET gr_name = $this->gr_name, super_user = $this->super_user,
			view_others = $this->view_others, group_inuse = $this->group_inuse,
			chg_pword = $this->chg_pword,
			anniv_limit = $this->anniv_limit, appnt_limit = $this->appnt_limit,
			tasks_limit = $this->tasks_limit, sysadm_lmt = $this->sysadm_lmt,
			updated_by = $this->updated_by, co_user_data = $this->co_user_data,
			au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE gr_id =  $gr_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_group_roles ($dbc) {
		$iquery = "INSERT INTO group_roles(gr_name, super_user, view_others, group_inuse,
				chg_pword, anniv_limit, appnt_limit, tasks_limit, sysadm_lmt,
				inserted_by, co_user_data) VALUES ($this->gr_name, $this->super_user,
				$this->view_others, $this->group_inuse, $this->chg_pword, $this->anniv_limit,
				$this->appnt_limit, $this->tasks_limit, $this->sysadm_lmt,
				$this->inserted_by,$this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function list_all_groups($dbc)  {
		$qquery = "SELECT * FROM group_roles ORDER BY gr_name";
		$qresult = pg_query($dbc, $qquery);
		return $qresult;
	}

}
?>