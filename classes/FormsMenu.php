<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table forms_menu.
*
*	Primary Key
*		fm_id
*
*	Unique index
*		navgn_refn
*
*	Foreign Key
*		form_type references form_types.form_type.
*
*	Boolean columns
*		active_item
*		super_user
*		sysgrp_user
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class FormsMenu extends fixers {

	private $fm_id;
	private $navgn_refn;
	private $form_name;
	private $active_item;
	private $super_user;
	private $sysgrp_user;
	private $form_type;
	private $navgn_bar;
	private $forward_to;
	private $second_to;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->fm_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getFmId()         {return $this->fm_id;}
	public function getNavgnRefn()    {return $this->navgn_refn;}
	public function getFormName()     {return $this->form_name;}
	public function getActiveItem()   {if ($this->active_item == "t") { return TRUE; } else { return FALSE;}}
	public function getSuperUser()    {if ($this->super_user == "t") { return TRUE; } else { return FALSE;}}
	public function getSysgrpUser()   {if ($this->sysgrp_user == "t") { return TRUE; } else { return FALSE;}}
	public function getFormType()     {return $this->form_type;}
	public function getNavgnBar()     {return $this->navgn_bar;}
	public function getForwardTo()    {if (is_null($this->forward_to)) { return 0; } else { return $this->forward_to;}}
	public function getSecondTo()     {if (is_null($this->second_to)) { return 0; } else { return $this->second_to;}}
	public function getInsertedBy()   {return $this->inserted_by;}
	public function getInsertTime()   {return $this->insert_time;}
	public function getUpdatedBy()    {return $this->updated_by;}
	public function getUpdateTime()   {return $this->update_time;}
	public function getCoUserData()   {return $this->co_user_data;}
	public function getAuVersNumb()   {return $this->au_vers_numb;}

//	public function setFmId($fm_id)      {$this->fm_id = self::fix_int($fm_id);}
	public function setNavgnRefn($navgn_refn)      {$this->navgn_refn = self::fix_int($navgn_refn);}
	public function setFormName($form_name)        {$this->form_name = self::fix_char($form_name);}
	public function setActiveItem($active_item)    {$this->active_item = self::fix_bool($active_item);}
	public function setSuperUser($super_user)      {$this->super_user = self::fix_bool($super_user);}
	public function setSysgrpUser($sysgrp_user)    {$this->sysgrp_user = self::fix_bool($sysgrp_user);}
	public function setFormType($form_type)        {$this->form_type = self::fix_char($form_type);}
	public function setNavgnBar($navgn_bar)        {$this->navgn_bar = self::fix_char($navgn_bar);}
	public function setForwardTo($forward_to)      {$this->forward_to = self::fix_int($forward_to);}
	public function setSecondTo($second_to)        {$this->second_to = self::fix_int($second_to);}
	public function setInsertedBy($inserted_by)    {$this->inserted_by = self::fix_int($inserted_by);}
	public function setInsertTime($insert_time)    {$this->insert_time = $insert_time;}
	public function setUpdatedBy($updated_by)      {$this->updated_by = self::fix_int($updated_by);}
	public function setUpdateTime($update_time)    {$this->update_time = $update_time;}
	public function setCoUserData($co_user_data)   {$this->co_user_data = self::fix_char($co_user_data);}
	public function setAuVersNumb($au_vers_numb)   {$this->au_vers_numb = self::fix_int($au_vers_numb);}

	public function db_select ($dbc, $query)  {
		$result = pg_query($dbc, $query);
		if (!$result)  {
			return FALSE;
		}  else  {
			if ( pg_num_rows($result) == 1 )  {
				$row = pg_fetch_assoc($result);
				$this->fm_id = $row['fm_id'];
				$this->navgn_refn = $row['navgn_refn'];
				$this->form_name = $row['form_name'];
				$this->active_item = $row['active_item'];
				$this->super_user = $row['super_user'];
				$this->sysgrp_user = $row['sysgrp_user'];
				$this->form_type = $row['form_type'];
				$this->navgn_bar = $row['navgn_bar'];
				$this->forward_to = $row['forward_to'];
				$this->second_to = $row['second_to'];
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

	public function find_forms_menu_by_id ($dbc, $fm_id) {
		$query = "SELECT * FROM forms_menu WHERE fm_id = $fm_id";
		return self::db_select($dbc, $query);
	}

	public function lock_forms_menu_by_id ($dbc, $fm_id) {
		$query = "SELECT * FROM forms_menu WHERE fm_id = $fm_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function find_forms_menu_by_navgn ($dbc, $nav_id) {
		$query = "SELECT * FROM forms_menu WHERE navgn_refn = $nav_id";
		return self::db_select($dbc, $query);
	}

	public function list_all_forms_menu ($dbc, $lim_it, $off_set) {
		$query = "SELECT * FROM forms_menu ORDER BY navgn_refn LIMIT $lim_it OFFSET $off_set";
		$result = pg_query($dbc, $query);
		return $result;
	}

	public function list_forms_menu_by_type ($dbc, $type_req) {
		$query = "SELECT * FROM forms_menu WHERE active_item IS TRUE AND form_type = '$type_req' ORDER BY form_name";
		$result = pg_query($dbc, $query);
		return $result;
	}

	public function update_forms_menu_by_id ($dbc, $fm_id) {
		$uquery = "UPDATE forms_menu SET navgn_refn = $this->navgn_refn, form_name = $this->form_name,
				active_item = $this->active_item, super_user = $this->super_user, sysgrp_user = $this->sysgrp_user,
				form_type = $this->form_type, navgn_bar = $this->navgn_bar, forward_to = $this->forward_to,
				second_to = $this->second_to, updated_by = $this->updated_by, co_user_data = $this->co_user_data,
				au_vers_numb = $this->au_vers_numb, update_time = now()
				WHERE fm_id =  $fm_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_forms_menu ($dbc) {
		$iquery = "INSERT INTO forms_menu(navgn_refn, form_name, active_item,
				super_user, sysgrp_user, form_type, navgn_bar,
				forward_to, second_to, inserted_by, co_user_data)
				VALUES ($this->navgn_refn, $this->form_name, $this->active_item,
				$this->super_user, $this->sysgrp_user, $this->form_type, $this->navgn_bar,
				$this->forward_to, $this->second_to, $this->inserted_by, $this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function count_forms_menu ($dbc)  {
		$f_count = 0;
		$query = "SELECT COUNT(*) AS forms_count FROM forms_menu";
		$result = pg_query($dbc, $query);
		if ($result)  {
			$row = pg_fetch_assoc($result);
			$f_count = $row['forms_count'];
		}
		return $f_count;
	}

}
?>