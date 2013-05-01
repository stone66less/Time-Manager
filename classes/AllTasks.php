<?php
require_once 'fixers.php';
/*
* Getters and setters for table all_tasks
*
*	Primary Key
*		allt_id
*
*	Foreign Key
*		tu_id references time_users
*
*	Boolean columns
*		task_compl
*		task_resched
*		task_copied
*
*	Unique Index
*		tu_id, allt_id
*
* $Date$:
* $Rev$:
* $Author$:
* $Id$:
*/
class AllTasks extends fixers {

	private $allt_id;
	private $tu_id;
	private $task_class;
	private $sequence_no;
	private $todo_date;
	private $task_descrn;
	private $task_compl;
	private $task_resched;
	private $task_copied;
	private $resch_count;
	private $inserted_by;
	private $insert_time;
	private $updated_by;
	private $update_time;
	private $co_user_data;
	private $au_vers_numb;

	public function __construct()  {
		$this->allt_id = null;
	}

	public function __destruct() {
		foreach ($this as $key => $value) { 
			unset($this->$key);
		}
	}

	public function getAlltId()       {return $this->allt_id;}
	public function getTuId()         {return $this->tu_id;}
	public function getTaskClass()    {return $this->task_class;}
	public function getSequenceNo()   {return $this->sequence_no;}
	public function getTodoDate()     {return $this->todo_date;}
	public function getTaskDescrn()   {return $this->task_descrn;}
	public function getTaskCompl()    {if ($this->task_compl == "t") { return TRUE; } else { return FALSE;}}
	public function getTaskResched()  {if ($this->task_resched == "t") { return TRUE; } else { return FALSE;}}
	public function getTaskCopied()   {if ($this->task_copied == "t") { return TRUE; } else { return FALSE;}}
	public function getReschCount()   {return $this->resch_count;}
	public function getInsertedBy()   {return $this->inserted_by;}
	public function getInsertTime()   {return $this->insert_time;}
	public function getUpdatedBy()    {return $this->updated_by;}
	public function getUpdateTime()   {return $this->update_time;}
	public function getCoUserData()   {return $this->co_user_data;}
	public function getAuVersNumb()   {return $this->au_vers_numb;}

//	public function setAlltId($allt_id)      {$this->allt_id = self::fix_int($allt_id);}
	public function setTuId($tu_id)              {$this->tu_id = self::fix_int($tu_id);}
	public function setTaskClass($task_class)    {$this->task_class = self::fix_char($task_class);}
	public function setSequenceNo($sequence_no)  {$this->sequence_no = self::fix_int($sequence_no);}
	public function setTodoDate($todo_date)      {$this->todo_date = self::fix_date($todo_date);}
	public function setTaskDescrn($task_descrn)  {$this->task_descrn = self::fix_char($task_descrn);}
	public function setTaskCompl($task_compl)    {$this->task_compl = self::fix_bool($task_compl);}
	public function setTaskResched($task_resched) {$this->task_resched = self::fix_bool($task_resched);}
	public function setTaskCopied($task_copied)   {$this->task_copied = self::fix_bool($task_copied);}
	public function setReschCount($resch_count)   {$this->resch_count = self::fix_int($resch_count);}
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
				$this->allt_id = $row['allt_id'];
				$this->tu_id = $row['tu_id'];
				$this->task_class = $row['task_class'];
				$this->sequence_no = $row['sequence_no'];
				$this->todo_date = $row['todo_date'];
				$this->task_descrn = $row['task_descrn'];
				$this->task_compl = $row['task_compl'];
				$this->task_resched = $row['task_resched'];
				$this->task_copied = $row['task_copied'];
				$this->resch_count = $row['resch_count'];
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

	public function find_all_tasks_by_id ($dbc, $allt_id) {
		$query = "SELECT * FROM all_tasks WHERE allt_id = $allt_id";
		return self::db_select($dbc, $query);
	}

	public function lock_all_tasks_by_id ($dbc, $allt_id) {
		$query = "SELECT * FROM all_tasks WHERE allt_id = $allt_id FOR UPDATE";
		return self::db_select($dbc, $query);
	}

	public function update_all_tasks_by_id ($dbc, $allt_id) {
		$uquery = "UPDATE all_tasks SET task_class = $this->task_class,
			sequence_no = $this->sequence_no, todo_date = $this->todo_date,
			task_descrn = $this->task_descrn, task_compl = $this->task_compl,
			task_resched = $this->task_resched, task_copied = $this->task_copied,
			resch_count = $this->resch_count, updated_by = $this->updated_by,
			co_user_data = $this->co_user_data,
			au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE allt_id =  $allt_id";
		$uresult = pg_query($dbc, $uquery);
		if (!$uresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_new_task ($dbc) {
		$iquery = "INSERT INTO all_tasks(tu_id, task_class, sequence_no,
				todo_date, task_descrn,
				inserted_by, co_user_data
				) VALUES ($this->tu_id, $this->task_class, $this->sequence_no,
				$this->todo_date, $this->task_descrn,
				$this->inserted_by, $this->co_user_data)";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function count_all_tasks_for_user ($dbc, $user_id)  {
		$tcount = 0;
		$cquery = "SELECT COUNT(*) AS task_count FROM all_tasks WHERE tu_id = $user_id";
		$cres = pg_query($dbc, $cquery);
		if ($cres && (pg_num_rows($cres) == 1) )  {
			$row = pg_fetch_assoc($cres);
			$tcount = $row['task_count'];
		}
		return $tcount;
	}

	function update_copy_flag ($dbc, $task_id)  {
		$cupdate = "UPDATE all_tasks SET task_copied = $this->task_copied,
				updated_by = $this->updated_by,
				au_vers_numb = $this->au_vers_numb, update_time = now()
			WHERE allt_id =  $task_id";
		$curesult = pg_query($dbc, $cupdate);
		if (!$curesult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

	public function insert_copied_task ($dbc) {
		$iquery = "INSERT INTO all_tasks(tu_id, task_class, sequence_no,
				todo_date, task_descrn, task_compl,
				task_resched, task_copied, resch_count, 
				inserted_by, co_user_data
				) VALUES ($this->tu_id, '$this->task_class', $this->sequence_no,
				$this->todo_date, '$this->task_descrn', $this->task_compl,
				$this->task_resched, $this->task_copied, $this->resch_count,
				$this->inserted_by, '$this->co_user_data')";
		$iresult = pg_query ($dbc, $iquery);
		if (!$iresult)  {
			return FALSE;
		}  else  {
			return TRUE;
		}
	}

}
?>