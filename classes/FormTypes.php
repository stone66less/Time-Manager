<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table form_types.
*
*	Primary Key
*
*	Unique index
*		lang_code, form_type
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
class FormTypes extends fixers {

	private $lang_code;
	private $form_type;
	private $type_descr;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->lang_code = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getLangCode()       {return $this->lang_code;}
	public function getFormType()       {return $this->form_type;}
	public function getTypeDescr()      {return $this->type_descr;}
	public function getInsertedBy()     {return $this->inserted_by;}
	public function getInsertTime()     {return $this->insert_time;}
	public function getUpdatedBy()      {return $this->updated_by;}
	public function getUpdateTime()     {return $this->update_time;}
	public function getCoUserData()     {return $this->co_user_data;}
	public function getAuVersNumb()     {return $this->au_vers_numb;}

	public function setLangCode($lang_code)      {$this->lang_code = self::fix_char($lang_code);}
	public function setFormType($form_type)      {$this->form_type = self::fix_char($form_type);}
	public function setTypeDescr($type_descr)    {$this->type_descr = self::fix_char($type_descr);}
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
				$this->lang_code = $row['lang_code'];
				$this->form_type = $row['form_type'];
				$this->type_descr = $row['type_descr'];
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

	public function find_form_type ($dbc, $lang_code, $f_type)  {
		$fquery = "SELECT * FROM form_types WHERE lang_code = '$lang_code' AND form_type = '$f_type'";
		return self::db_select ($dbc, $fquery);
	}

	public function list_types_by_lang ($dbc, $lang)  {
		$lquery = "SELECT * FROM form_types WHERE lang_code = '$lang' ORDER BY type_descr";
		$result = pg_query($dbc, $lquery);
		return $result;
	}

}
?>