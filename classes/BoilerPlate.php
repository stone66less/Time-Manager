<?php
require_once 'fixers.php';
/*
* package info.timemanager.model;
*
*	Getters and setters for table boiler_plate.
*
*	Primary Key
*		bp_id
*
*	Unique index
*		navgn_refn, lang_code
*
*	Foreign Key
*		navgn_refn references forms_menu
*		lang_code references supported_languages
*
*	Boolean columns
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class BoilerPlate extends fixers {

	private $bp_id;
	private $navgn_refn;
	private $lang_code;
	private $page_title;
	private $heading_one;
	private $heading_two;
	private $heading_tre;
	private $heading_qua;
	private $heading_cin;
	private $heading_six;
	private $navign_bar;
	private $capt_ions;
	private $thtd_cells;
	private $leg_end;
	private $form_fields;
	private $subt_buttons;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->bp_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getBpId()         {return $this->bp_id;}
	public function getNavgnRefn()    {return $this->navgn_refn;}
	public function getLangCode()     {return $this->lang_code;}
	public function getPageTitle()    {return $this->page_title;}
	public function getHeadingOne()   {return $this->heading_one;}
	public function getHeadingTwo()   {return $this->heading_two;}
	public function getHeadingTre()   {return $this->heading_tre;}
	public function getHeadingQua()   {return $this->heading_qua;}
	public function getHeadingCin()   {return $this->heading_cin;}
	public function getHeadingSix()   {return $this->heading_six;}
	public function getNavignBar()    {return $this->navign_bar;}
	public function getCaptIons()     {return $this->capt_ions;}
	public function getThtdCells()    {return $this->thtd_cells;}
	public function getLegEnd()       {return $this->leg_end;}
	public function getFormFields()   {return $this->form_fields;}
	public function getSubtButtons()  {return $this->subt_buttons;}
	public function getInsertedBy()   {return $this->inserted_by;}
	public function getInsertTime()   {return $this->insert_time;}
	public function getUpdatedBy()    {return $this->updated_by;}
	public function getUpdateTime()   {return $this->update_time;}
	public function getCoUserData()   {return $this->co_user_data;}
	public function getAuVersNumb()   {return $this->au_vers_numb;}

//	public function setBpId($bp_id)      {$this->bp_id = self::fix_int($bp_id);}
	public function setNavgnRefn($navgn_refn)    {$this->navgn_refn = self::fix_int($navgn_refn);}
	public function setLangCode($lang_code)      {$this->lang_code = self::fix_char($lang_code);}
	public function setPageTitle($page_title)    {$this->page_title = self::fix_char($page_title);}
	public function setHeadingOne($heading_one)  {$this->heading_one = self::fix_char($heading_one);}
	public function setHeadingTwo($heading_two)  {$this->heading_two = self::fix_char($heading_two);}
	public function setHeadingTre($heading_tre)  {$this->heading_tre = self::fix_char($heading_tre);}
	public function setHeadingQua($heading_qua)  {$this->heading_qua = self::fix_char($heading_qua);}
	public function setHeadingCin($heading_cin)  {$this->heading_cin = self::fix_char($heading_cin);}
	public function setHeadingSix($heading_six)  {$this->heading_six = self::fix_char($heading_six);}
	public function setNavignBar($navign_bar)    {$this->navign_bar = self::fix_char($navign_bar);}
	public function setCaptIons($capt_ions)      {$this->capt_ions = self::fix_char($capt_ions);}
	public function setThtdCells($thtd_cells)    {$this->thtd_cells = self::fix_char($thtd_cells);}
	public function setLegEnd($leg_end)          {$this->leg_end = self::fix_char($leg_end);}
	public function setFormFields($form_fields)  {$this->form_fields = self::fix_char($form_fields);}
	public function setSubtButtons($subt_buttons) {$this->subt_buttons = self::fix_char($subt_buttons);}
	public function setInsertedBy($inserted_by)   {$this->inserted_by = self::fix_int($inserted_by);}
	public function setInsertTime($insert_time)   {$this->insert_time = $insert_time;}
	public function setUpdatedBy($updated_by)     {$this->updated_by = self::fix_int($updated_by);}
	public function setUpdateTime($update_time)   {$this->update_time = $update_time;}
	public function setCoUserData($co_user_data)  {$this->co_user_data = self::fix_char($co_user_data);}
	public function setAuVersNumb($au_vers_numb)  {$this->au_vers_numb = self::fix_int($au_vers_numb);}

	public function db_select ($dbc, $query)  {
		$result = pg_query($dbc, $query);
		if (!$result)  {
			return FALSE;
		}  else  {
			if ( pg_num_rows($result) == 1 )  {
				$row = pg_fetch_assoc($result);
				$this->bp_id = $row['bp_id'];
				$this->navgn_refn = $row['navgn_refn'];
				$this->lang_code = $row['lang_code'];
				$this->page_title = $row['page_title'];
				$this->heading_one = $row['heading_one'];
				$this->heading_two = $row['heading_two'];
				$this->heading_tre = $row['heading_tre'];
				$this->heading_qua = $row['heading_qua'];
				$this->heading_cin = $row['heading_cin'];
				$this->heading_six = $row['heading_six'];
				$this->navign_bar = $row['navign_bar'];
				$this->capt_ions = $row['capt_ions'];
				$this->thtd_cells = $row['thtd_cells'];
				$this->leg_end = $row['leg_end'];
				$this->form_fields = $row['form_fields'];
				$this->subt_buttons = $row['subt_buttons'];
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

	public function find_boiler_plate_by_id ($dbc, $bp_id) {
		$query = "SELECT * FROM boiler_plate WHERE bp_id = $bp_id";
		return self::db_select($dbc, $query);
	}

	public function lock_boiler_plate_by_id ($dbc, $bp_id) {
		$query = "SELECT * FROM boiler_plate WHERE bp_id = $bp_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function find_boiler_plate_by_lang ($dbc, $form_id, $lang_code) {
		$query = "SELECT * FROM boiler_plate WHERE navgn_refn = $form_id AND lang_code = '$lang_code'";
		return self::db_select($dbc, $query);
	}

	public function update_boiler_plate_by_id ($dbc, $bp_id) {
		$uquery = "UPDATE boiler_plate SET page_title = $this->page_title, heading_one = $this->heading_one,
			heading_two = $this->heading_two, heading_tre = $this->heading_tre, heading_qua = $this->heading_qua,
			heading_cin = $this->heading_cin, heading_six = $this->heading_six,
			navign_bar = $this->navign_bar, capt_ions = $this->capt_ions,
			thtd_cells = $this->thtd_cells, leg_end = $this->leg_end, form_fields = $this->form_fields,
			subt_buttons = $this->subt_buttons, updated_by = $this->updated_by, co_user_data = $this->co_user_data,
			au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE bp_id =  $bp_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_boiler_plate ($dbc) {
		$iquery = "INSERT INTO boiler_plate(navgn_refn, lang_code, page_title, heading_one,
				heading_two, heading_tre, heading_qua, heading_cin,
				heading_six, navign_bar, capt_ions, thtd_cells,
				leg_end, form_fields, subt_buttons, inserted_by,
				co_user_data)
				VALUES ($this->navgn_refn, $this->lang_code, $this->page_title, $this->heading_one,
				$this->heading_two, $this->heading_tre, $this->heading_qua, $this->heading_cin,
				$this->heading_six, $this->navign_bar, $this->capt_ions, $this->thtd_cells,
				$this->leg_end, $this->form_fields, $this->subt_buttons, $this->inserted_by,
				$this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function list_bp_by_form ($dbc, $fm_id)  {
		$lquery = "SELECT * FROM boiler_plate WHERE navgn_refn = $fm_id ORDER BY lang_code";
		$lresult = pg_query($dbc, $lquery);
		return $lresult;
	}

	public function count_boiler_plate_by_lang ($dbc, $lang)  {
		$b_count = 0;
		$query = "SELECT COUNT(*) AS bp_count FROM boiler_plate WHERE lang_code = '$lang'";
		$res = pg_query($dbc, $query);
		if ($res)  {
			$row = pg_fetch_assoc($res);
			$b_count = $row['bp_count'];
		}
		return $b_count;
	}

	public function list_boiler_plate_by_lang ($dbc, $lang, $lim_it, $off_set)  {
		$query = "SELECT * FROM boiler_plate WHERE lang_code = '$lang' ORDER BY page_title LIMIT $lim_it OFFSET $off_set";
		$result = pg_query($dbc, $query);
		return $result;
	} 

}
?>