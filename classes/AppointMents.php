<?php
require_once 'fixers.php';
/*
*	Getters and setters for table appoint_ments.
*
*	Primary Key
*		ap_id
*
*	Foreign Key
*		tu_id references time_users
*
*	Boolean Columns
*		intl_meet
*		meet_cancld
*
*	Unique index
*		tu_id, ap_id
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class AppointMents extends fixers {

	private $ap_id;
	private $tu_id;
	private $appoint_date;
	private $appoint_hour;
	private $appoint_min;
	private $with_whom;
	private $meet_subjt;
	private $est_drtn;
	private $depart_time;
	private $intl_meet;
	private $meet_where;
	private $meet_cancld;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->ap_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getApId()          {return $this->ap_id;}
	public function getTuId()          {return $this->tu_id;}
	public function getAppointDate()   {return $this->appoint_date;}
	public function getAppointHour()   {return $this->appoint_hour;}
	public function getAppointMin()    {return $this->appoint_min;}
	public function getWithWhom()      {return $this->with_whom;}
	public function getMeetSubjt()     {return $this->meet_subjt;}
	public function getEstDrtn()       {return $this->est_drtn;}
	public function getDepartTime()    {return $this->depart_time;}
	public function getIntlMeet()      {if ($this->intl_meet == "t") { return TRUE; } else { return FALSE;}}
	public function getMeetWhere()     {return $this->meet_where;}
	public function getMeetCancld()    {if ($this->meet_cancld == "t") { return TRUE; } else { return FALSE;}}
	public function getInsertedBy()    {return $this->inserted_by;}
	public function getInsertTime()    {return $this->insert_time;}
	public function getUpdatedBy()     {return $this->updated_by;}
	public function getUpdateTime()    {return $this->update_time;}
	public function getCoUserData()    {return $this->co_user_data;}
	public function getAuVersNumb()    {return $this->au_vers_numb;}

//	public function setApId($ap_id)      {$this->ap_id = self::fix_int($ap_id);}
	public function setTuId($tu_id)                {$this->tu_id = self::fix_int($tu_id);}
	public function setAppointDate($appoint_date)  {$this->appoint_date = self::fix_date($appoint_date);}
	public function setAppointHour($appoint_hour)  {$this->appoint_hour = self::fix_int($appoint_hour);}
	public function setAppointMin($appoint_min)  {$this->appoint_min = self::fix_int($appoint_min);}
	public function setWithWhom($with_whom)      {$this->with_whom = self::fix_char($with_whom);}
	public function setMeetSubjt($meet_subjt)    {$this->meet_subjt = self::fix_char($meet_subjt);}
	public function setEstDrtn($est_drtn)        {$this->est_drtn = self::fix_int($est_drtn);}
	public function setDepartTime($depart_time)  {$this->depart_time = self::fix_int($depart_time);}
	public function setIntlMeet($intl_meet)      {$this->intl_meet = self::fix_bool($intl_meet);}
	public function setMeetWhere($meet_where)    {$this->meet_where = self::fix_char($meet_where);}
	public function setMeetCancld($meet_cancld)  {$this->meet_cancld = self::fix_bool($meet_cancld);}
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
				$this->ap_id = $row['ap_id'];
				$this->tu_id = $row['tu_id'];
				$this->appoint_date = $row['appoint_date'];
				$this->appoint_hour = $row['appoint_hour'];
				$this->appoint_min = $row['appoint_min'];
				$this->with_whom = $row['with_whom'];
				$this->meet_subjt = $row['meet_subjt'];
				$this->est_drtn = $row['est_drtn'];
				$this->depart_time = $row['depart_time'];
				$this->intl_meet = $row['intl_meet'];
				$this->meet_where = $row['meet_where'];
				$this->meet_cancld = $row['meet_cancld'];
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

	public function find_appoint_ments_by_id ($dbc, $ap_id) {
		$query = "SELECT * FROM appoint_ments WHERE ap_id = $ap_id";
		return self::db_select($dbc, $query);
	}

	public function lock_appoint_ments_by_id ($dbc, $ap_id) {
		$lquery = "SELECT * FROM appoint_ments WHERE ap_id = $ap_id FOR UPDATE";
		return self::db_select($dbc, $lquery);
	}

	public function update_appoint_ments_by_id ($dbc, $ap_id) {
		$uquery = "UPDATE appoint_ments SET  appoint_date = $this->appoint_date,
				appoint_hour = $this->appoint_hour, appoint_min = $this->appoint_min, with_whom = $this->with_whom,
				meet_subjt = $this->meet_subjt, est_drtn = $this->est_drtn, depart_time = $this->depart_time,
				intl_meet = $this->intl_meet, meet_where = $this->meet_where, meet_cancld = $this->meet_cancld,
				updated_by = $this->updated_by, co_user_data = $this->co_user_data,
				au_vers_numb = $this->au_vers_numb, update_time = now()
				WHERE ap_id =  $ap_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_appoint_ments ($dbc) {
		$iquery = "INSERT INTO appoint_ments(tu_id, appoint_date, appoint_hour,
				appoint_min, with_whom, meet_subjt,
				est_drtn, depart_time, intl_meet,
				meet_where, meet_cancld, inserted_by,
				co_user_data
				) VALUES ($this->tu_id, $this->appoint_date, $this->appoint_hour,
				$this->appoint_min, $this->with_whom, $this->meet_subjt,
				$this->est_drtn, $this->depart_time, $this->intl_meet,
				$this->meet_where, $this->meet_cancld, $this->inserted_by,
				$this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function count_appoint_ments ($dbc, $tu_id)  {
		$ap_count = 0;
		$cquery = "SELECT COUNT(*) AS count_appnts FROM appoint_ments WHERE tu_id = $tu_id";
		$cres = pg_query($dbc, $cquery);
		if ( $cres && (pg_num_rows($cres) == 1) )  {
			$row = pg_fetch_assoc($cres);
			$ap_count = $row['count_appnts'];
		}
		return $ap_count;
	}


}
?>