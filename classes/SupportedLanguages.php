<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table supported_languages.
*
*	Primary Key
*		lang_code
*
*	Boolean columns
*		lang_inuse
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class SupportedLanguages extends fixers {

	private $lang_code;
	private $lang_name;
	private $lang_inuse;
	private $char_set;
	private $dir_ection;
	private $welcome_text;
	private $farwell_text;
	private $footer_text;
	private $yes_text;
	private $no_text;
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

	public function getLangCode()      {return $this->lang_code;}
	public function getLangName()      {return $this->lang_name;}
	public function getLangInuse()     {if ($this->lang_inuse == "t") { return TRUE; } else { return FALSE;}}
	public function getCharSet()       {return $this->char_set;}
	public function getDirEction()     {return $this->dir_ection;}
	public function getWelcomeText()   {return $this->welcome_text;}
	public function getFarwellText()   {return $this->farwell_text;}
	public function getFooterText()    {return $this->footer_text;}
	public function getYesText()       {return $this->yes_text;}
	public function getNoText()        {return $this->no_text;}
	public function getInsertedBy()    {return $this->inserted_by;}
	public function getInsertTime()    {return $this->insert_time;}
	public function getUpdatedBy()     {return $this->updated_by;}
	public function getUpdateTime()    {return $this->update_time;}
	public function getCoUserData()    {return $this->co_user_data;}
	public function getAuVersNumb()    {return $this->au_vers_numb;}

	public function setLangCode($lang_code)      {$this->lang_code = self::fix_char($lang_code);}
	public function setLangName($lang_name)      {$this->lang_name = self::fix_char($lang_name);}
	public function setLangInuse($lang_inuse)    {$this->lang_inuse = self::fix_bool($lang_inuse);}
	public function setCharSet($char_set)        {$this->char_set = self::fix_char($char_set);}
	public function setDirEction($dir_ection)    {$this->dir_ection = self::fix_char($dir_ection);}
	public function setWelcomeText($welcome_text) {$this->welcome_text = self::fix_char($welcome_text);}
	public function setFarwellText($farwell_text) {$this->farwell_text = self::fix_char($farwell_text);}
	public function setFooterText($footer_text)  {$this->footer_text = self::fix_char($footer_text);}
	public function setYesText($yes_text)        {$this->yes_text = self::fix_char($yes_text);}
	public function setNoText($no_text)          {$this->no_text = self::fix_char($no_text);}
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
				$this->lang_name = $row['lang_name'];
				$this->lang_inuse = $row['lang_inuse'];
				$this->char_set = $row['char_set'];
				$this->dir_ection = $row['dir_ection'];
				$this->welcome_text = $row['welcome_text'];
				$this->farwell_text = $row['farwell_text'];
				$this->footer_text = $row['footer_text'];
				$this->yes_text = $row['yes_text'];
				$this->no_text = $row['no_text'];
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

	public function find_language_by_code ($dbc, $lang_code)  {
		$query = "SELECT * FROM supported_languages WHERE lang_code = '$lang_code'";
		return self::db_select ($dbc, $query);
	}

	public function lock_language_by_code ($dbc, $lang_code)  {
		$query = "SELECT * FROM supported_languages WHERE lang_code = '$lang_code' FOR UPDATE";
		return self::db_select ($dbc, $query);
	}

	public function list_languages ($dbc)  {
		$lquery = "SELECT * FROM supported_languages ORDER BY lang_name";
		$lresult = pg_query($dbc, $lquery);
		return $lresult;
	}

	public function update_language_by_code ($dbc, $lang_code)  {
		$uquery = "UPDATE supported_languages SET lang_name = $this->lang_name,
				lang_inuse = $this->lang_inuse, char_set = $this->char_set, dir_ection = $this->dir_ection,
				welcome_text = $this->welcome_text, farwell_text = $this->farwell_text,
				footer_text = $this->footer_text, yes_text = $this->yes_text,
				no_text = $this->no_text, co_user_data = $this->co_user_data,
				updated_by = $this->updated_by, update_time = now(),
				au_vers_numb = $this->au_vers_numb
				WHERE lang_code = '$lang_code'";
		$ures = pg_query($dbc, $uquery);
		if (!$ures)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_language_by_code ($dbc)  {
		$iquery = "INSERT INTO supported_languages (lang_code, lang_name, lang_inuse,
				char_set, dir_ection, welcome_text, farwell_text,
				footer_text, yes_text, no_text, co_user_data, inserted_by)
				VALUES ($this->lang_code, $this->lang_name, $this->lang_inuse,
				$this->char_set, $this->dir_ection, $this->welcome_text, $this->farwell_text,
				$this->footer_text, $this->yes_text, $this->no_text, $this->co_user_data, $this->inserted_by)";
		$ires = pg_query($dbc, $iquery);
		return $ires;
	}

}
?>