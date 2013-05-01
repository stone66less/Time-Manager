<?php
function conn_db () {
// Open a connection to Time Manager database.
$conn_string = "dbname=timemanager user=timeman password=ManItisT1me";
$pgconn = pg_connect($conn_string) or die ('Connection Failed');
return $pgconn;
}
function do_begin ($dpgc)  {
$bquery = "BEGIN";
$res = pg_query($dpgc, $bquery);
$retres = dbf_error($res, $bquery);
return $retres;
}
function do_commit ($dpgc)  {
$cquery = "COMMIT";
$res = pg_query($dpgc, $cquery);
$retres = dbf_error($res, $cquery);
return $retres;
}
function do_rollback ($dpgc)  {
$rquery = "ROLLBACK";
$res = pg_query($dpgc, $rquery);
$retres = dbf_error($res, $rquery);
return $retres;
}
function dbf_error ($res, $qtext)  {
	if ($res === FALSE)  {
		trigger_error('DB Operation ' . $qtext . ' Failed');
		return FALSE;
	}  else  {
		return TRUE;
	}
}
?>