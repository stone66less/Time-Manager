<?php
require_once 'fixers.php';
/*
*Getters and setters for table anni_versaries.
*
*	Prime key
*		av_id
*
*	Foreign Keys
*		tu_id references time_users
*
*	Boolean columns
*		anni_active
*
*	Unique Index
*		tu_id, av_id
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class AnniVersaries extends fixers {

	private $av_id;
	private $tu_id;
	private $anni_descr;
	private $anni_tday;
	private $anni_month;
	private $anni_sday;
	private $anni_active;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->av_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getAvId()       {return $this->av_id;}
	public function getTuId()       {return $this->tu_id;}
	public function getAnniDescr()  {return $this->anni_descr;}
	public function getAnniTday()   {return $this->anni_tday;}
	public function getAnniMonth()  {return $this->anni_month;}
	public function getAnniSday()   {return $this->anni_sday;}
	public function getAnniActive() {if ($this->anni_active == "t") { return TRUE; } else { return FALSE;}}
	public function getInsertedBy() {return $this->inserted_by;}
	public function getInsertTime() {return $this->insert_time;}
	public function getUpdatedBy()  {return $this->updated_by;}
	public function getUpdateTime() {return $this->update_time;}
	public function getCoUserData() {return $this->co_user_data;}
	public function getAuVersNumb() {return $this->au_vers_numb;}

//	public function setAvId($av_id)      {$this->av_id = self::fix_int($av_id);}
	public function setTuId($tu_id)              {$this->tu_id = self::fix_int($tu_id);}
	public function setAnniDescr($anni_descr)    {$this->anni_descr = self::fix_char($anni_descr);}
	public function setAnniTday($anni_tday)      {$this->anni_tday = self::fix_int($anni_tday);}
	public function setAnniMonth($anni_month)    {$this->anni_month = self::fix_int($anni_month);}
	public function setAnniSday($anni_sday)      {$this->anni_sday = self::fix_int($anni_sday);}
	public function setAnniActive($anni_active)  {$this->anni_active = self::fix_bool($anni_active);}
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
				$this->av_id = $row['av_id'];
				$this->tu_id = $row['tu_id'];
				$this->anni_descr = $row['anni_descr'];
				$this->anni_tday = $row['anni_tday'];
				$this->anni_month = $row['anni_month'];
				$this->anni_sday = $row['anni_sday'];
				$this->anni_active = $row['anni_active'];
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

	public function find_anni_versaries_by_id ($dbc, $av_id) {
		$query = "SELECT * FROM anni_versaries WHERE av_id = $av_id";
		return self::db_select($dbc, $query);
	}

	public function lock_anni_versaries_by_id ($dbc, $av_id) {
		$query = "SELECT * FROM anni_versaries WHERE av_id = $av_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function update_anni_versaries_by_id ($dbc, $av_id) {
		$uquery = "UPDATE anni_versaries SET 
			anni_descr = $this->anni_descr, anni_tday = $this->anni_tday,
			anni_month = $this->anni_month, anni_sday = $this->anni_sday,
			anni_active = $this->anni_active, updated_by = $this->updated_by,
			co_user_data = $this->co_user_data,
			au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE av_id =  $av_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_anni_versaries ($dbc) {
		$iquery = "INSERT INTO anni_versaries(tu_id, anni_descr, anni_tday,
				anni_month, anni_sday, anni_active, inserted_by, co_user_data
				) VALUES ($this->tu_id, $this->anni_descr, $this->anni_tday,
				$this->anni_month, $this->anni_sday, $this->anni_active,
				$this->inserted_by, $this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function count_anni_versaries($dbc, $user_id)  {
		$anni_count = 0;
		$cquery = "SELECT COUNT(*) AS count_annie FROM anni_versaries WHERE tu_id = $user_id";
		$cres = pg_query($dbc, $cquery);
		if ($cres)  {
			$row = pg_fetch_assoc($cres);
			$anni_count = $row['count_annie'];
		}
		return $anni_count;
	}

	public function list_all_anniversaries($dbc, $user_id, $lim_it, $off_set)  {
		$query = "SELECT * FROM anni_versaries WHERE tu_id = $user_id ORDER BY anni_sday LIMIT $lim_it OFFSET $off_set";
		$result = pg_query($dbc, $query);
		return $result;
	}

}
?>