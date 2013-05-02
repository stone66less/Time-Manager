<?php
$infile = $argv[1];
echo ' ' . PHP_EOL;
if (!$inhandle = fopen($infile, 'r'))  {
   echo  'Unable to open input file' . PHP_EOL;
   exit();
}
$rread = 0;
$rwrite = 0;
$outputname = '';
$sing_apost = "'";
$getable = false;
$indentchar = chr(9);
$prime_key = NULL;
$desarr = array();
$desarr[0] = 'public function __destruct() {';
$desarr[1] = 'foreach ($this as $key => $value) { ';
$desarr[2] = 'unset($this->$key);';
$desarr[3] = '}';
$desarr[4] = '}';
$ignore_cols_upd = array('inserted_by' => '0', 'insert_time' => '1', 'update_time' => '2');
$ignore_cols_ins = array('insert_time' => '0', 'updated_by' => '1', 'update_time' => '2', 'au_vers_numb' => '3');

while (!feof($inhandle))   {
	$rread++;
	$line = fgets($inhandle);
	if (strlen($line) > 1)  {
	$nline = preg_replace('/  +/', ' ', $line);
	$line_arr = explode(' ', $nline);
	if (  ( (strcmp($line_arr[0], 'CREATE') == 0)  && (strcmp($line_arr[1], 'TABLE') == 0) )    ||
			( (strcmp($line_arr[0], 'CREATE') == 0)  && (strcmp($line_arr[1], 'UNIQUE') == 0) )   ||
			( (strcmp($line_arr[0], 'CREATE') == 0)  && (strcmp($line_arr[1], 'INDEX') == 0) )    ||
	      (  strcmp($line_arr[0], "INSERT") == 0)              ||
	      (  strcmp($line_arr[0], "TABLESPACE") == 0)          ||
	      (  strcmp($line_arr[0], "WITH(OIDS=FALSE)") == 0)    ||
	      (  strcmp($line_arr[0], "CONSTRAINT") == 0)  )  {
		if ($getable)  {
			if ($colcount > 0)  {
				echo 'Processing ' . $colcount . ' columns for table ' . $classname . PHP_EOL;
				foreach ($colnames as $key =>$value)  {
				   $col_arr = $value;
				   $col_name = $col_arr[0];
				   $outstring = $indentchar . 'private $' . $col_name . ';' . PHP_EOL;
				   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
				}
				$outstring = $indentchar . 'public function __construct()  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$first_time = true;
				foreach ($colnames as $key =>$value)  {
				   $col_arr = $value;
				   $col_name = $col_arr[0];
				   if ($first_time)  {
				      $outstring = $indentchar . '$this->' . $col_name . ' = null;  }' . PHP_EOL;
				      $rwrite = writearecord ($outhandle, $outstring, $rwrite);
				      $first_time = false;
				   }
				}
				foreach ($desarr as $key =>$value)  {
				   $outstring = $indentchar . $value . PHP_EOL;
				   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
				}

				foreach ($colnames as $key =>$value)  {
				   $col_arr = $value;
				   $col_name = $col_arr[0];
				   $cam_name = camelize($col_name);
				   $d_type = $col_arr[1];
				   if ($d_type == 'B')  {
						$outstring = $indentchar . 'public function get' . $cam_name . '()       {if ($this->' . $col_name . ' == "t") { return TRUE; } else { return FALSE;}}' . PHP_EOL;
					}  else  {
						$outstring = $indentchar . 'public function get' . $cam_name . '()       {return $this->' . $col_name . ';}' . PHP_EOL;
					}
				   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
				}
				foreach ($colnames as $key =>$value)  {
				   $col_arr = $value;
				   $col_name = $col_arr[0];
				   $cam_name = camelize($col_name);
				   $fix_string = NULL;
				   $d_type = $col_arr[1];
			   	if ($d_type == 'C')  {
			   		$fix_string = 'self::fix_char';
			   	}
			   	if ($d_type == 'I')  {
			   		$fix_string = 'self::fix_int';
			   	}
			   	if ($d_type == 'D')  {
			   		$fix_string = 'self::fix_date';
			   	}
			   	if ($d_type == 'N')  {
			   		$fix_string = 'self::fix_numb';
			   	}
			   	if ($d_type == 'M')  {
			   		$fix_string = 'self::fix_money';
			   	}
				   if ($d_type == 'B')   {
				   	$fix_string = 'self::fix_bool';
				   }
				   if ( is_null($fix_string) )  {
				   	$outstring = $indentchar . 'public function set' . $cam_name . '($' . $col_name . ')      {$this->' . $col_name . ' = $' . $col_name . ';}' .  PHP_EOL;
				   }  else {
				   	$outstring = $indentchar . 'public function set' . $cam_name . '($' . $col_name . ')      {$this->' . $col_name . ' = ' . $fix_string . '($' . $col_name . ');}' .  PHP_EOL;
				   }	
				   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
				}
				$outstring = 'public function db_select ($dbc, $query)  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$result = pg_query($dbc, $query);' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'if (!$result)  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return FALSE;' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}  else  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'if ( pg_num_rows($result) == 1 )  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$row = pg_fetch_assoc($result);' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				foreach ($colnames as $key =>$value)  {
				   $col_arr = $value;
				   $col_name = $col_arr[0];
				   $outstring = $indentchar . '$this->' . $col_name . ' = $row[' . $sing_apost . $col_name . $sing_apost . '];' . PHP_EOL;
				   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
				}
				$outstring = "return TRUE;" . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = "}  else  {" . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = "return FALSE;" . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = "}" . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = "}" . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = "}" . PHP_EOL;
         	$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			}
			if (!is_null($prime_key) )  {
				$outstring = 'public function find_' . $nclass . '_by_id ($dbc, $' . $prime_key . ') {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$query = "SELECT * FROM ' . $nclass . ' WHERE ' . $prime_key . ' = $' . $prime_key . '";' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return self::db_select($dbc, $query);' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			}
			if (!is_null($prime_key) )  {
				$outstring = 'public function lock_' . $nclass . '_by_id ($dbc, $' . $prime_key . ') {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$lquery = "SELECT * FROM ' . $nclass . ' WHERE ' . $prime_key . ' = $' . $prime_key . ' FOR UPDATE";' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return self::db_select($dbc, $query);' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			}
			if (!is_null($prime_key) )  {
				$outstring = 'public function update_' . $nclass . '_by_id ($dbc, $' . $prime_key . ') {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$uquery = "UPDATE ' . $nclass . ' SET ' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				foreach ($colnames as $key => $value)  {
					$col_arr = $value;
				   $col_name = $col_arr[0];
				   if ( array_key_exists($col_name, $ignore_cols_upd) )  {
				   	$cool = TRUE;
				   }  else  {
					   $outstring = $col_name . ' = $this->' . $col_name . ',' . PHP_EOL;
					   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
					}
				}
				$outstring = ' update_time = now()' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'WHERE ' . $prime_key . ' =  $' . $prime_key . '";' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$uresult = pg_query($dbc, $uquery);' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'if (!$uresult)  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return FALSE;' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}  else  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return TRUE;' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			}
			if (!is_null($prime_key) )  {
				$outstring = 'public function insert_' . $nclass . ' ($dbc) {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$iquery = "INSERT INTO ' . $nclass . '(' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				foreach ($colnames as $key => $value)  {
					$col_arr = $value;
				   $col_name = $col_arr[0];
				   if ( array_key_exists($col_name, $ignore_cols_ins) )  {
				   	$cool = TRUE;
				   }  else  {
					   $outstring = $col_name . ',' . PHP_EOL;
					   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
					}
				}
				$outstring = ') VALUES (' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				foreach ($colnames as $key => $value)  {
					$col_arr = $value;
				   $col_name = $col_arr[0];
				   if ( array_key_exists($col_name, $ignore_cols_ins) )  {
				   	$cool = TRUE;
				   }  else  {
					   $outstring = '$this->' . $col_name . ',' . PHP_EOL;
					   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
					}
				}
				$outstring = ')";' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '$iresult = pg_query ($dbc, $iquery);' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'if (!$iresult)  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return FALSE;' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}  else  {' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = 'return TRUE;' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
				$outstring = '}' . PHP_EOL;
				$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			}
			$outstring = "}" . PHP_EOL;
         $rwrite = writearecord ($outhandle, $outstring, $rwrite);
         $outstring = "?>" . PHP_EOL;
         $rwrite = writearecord ($outhandle, $outstring, $rwrite);
         fflush ($outhandle);
			fclose ($outhandle);
			echo 'Records Written ' . $rwrite . PHP_EOL;
			$getable = false;
			$rwrite = 0;
		}  // get_table is true
		if (  (strcmp($line_arr[0], 'CREATE') == 0)  && (strcmp($line_arr[1], 'TABLE') == 0) )  {
  		   $nclass = $line_arr[2];
		   $classname = camelize($nclass);
		   $outputname = $classname . ".php";
		   echo  'File Name ' . $outputname . PHP_EOL;
		   $outhandle = fopen($outputname, 'w');
		   if (!$outhandle)   {
		   	echo 'Unable to open output file ' . $outputname . PHP_EOL;
		   	exit();
		   }
		   $outstring = "<?php" . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
		   $outstring = 'require_once ' . $sing_apost . 'fixers.php' . $sing_apost . ';' . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
		   $outstring = '/*' . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
		   $outstring = '* $Date$:' . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
			$outstring = '* $Rev$:' . PHP_EOL;
			$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			$outstring = '* $Author$:' . PHP_EOL;
			$rwrite = writearecord ($outhandle, $outstring, $rwrite);
			$outstring = '* $Id$:' . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
		   $outstring = '*/' . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
		   $outstring = 'class ' . $classname . ' extends fixers {' . PHP_EOL;
		   $rwrite = writearecord ($outhandle, $outstring, $rwrite);
		   $getable = true;
		   $colnames = array();
		   $colcount = 0;
		   $prime_key = NULL;
		}
	}   // 	CREATE TABLE found
//	if (!$getable)  {
//		echo  'Processing without an output file ' . $line . PHP_EOL;
//		exit();
//	}
	$gsname = $line_arr[0];
	if ( count($line_arr) > 1 )  {
		$col_dt = strtoupper($line_arr[1]);
		$col_type = NULL;
		if ( (substr($col_dt,0,4) == 'CHAR') || (substr($col_dt,0,7) == 'VARCHAR') )  {
			$col_type = 'C';
		}
		if ( 	(substr($col_dt,0,7) == 'INTEGER')   || (substr($col_dt,0,6) == 'SERIAL') || 
				(substr($col_dt,0,9) == 'BIGSERIAL') || (substr($col_dt,0,6) == 'BIGINT') ||
				(substr($col_dt,0,8) == 'SMALLINT')  ) {
			$col_type = 'I';
		}
		if (substr($col_dt,0,5) == 'MONEY') {
			$col_type = 'M';
		}
		if (substr($col_dt,0,7) == 'NUMERIC' ) {
			$col_type = 'N';
		}
		if (substr($col_dt,0,4) == 'DATE')  {
			$col_type = 'D';
		}
		if (substr($col_dt,0,7) == 'BOOLEAN')  {
			$col_type = 'B';
		}
		if (substr($col_dt,0,9) == 'TIMESTAMP') {
			$col_type = 'Z';
		}
		if ( (substr($col_dt,0,6) == 'SERIAL') || (substr($col_dt,0,9) == 'BIGSERIAL') )  {
			$prime_key = $gsname;
		}
	   if (is_null($col_type))  {
	   	echo 'Unable to determine data type ' . $line . PHP_EOL;
	   }  else  {
	   	$req_bool = 'N';
	   	if ( (count($line_arr) > 2) && ($line_arr[2] == 'NOT') )  {
	      	$req_bool = 'Y';
	      }
	      $col_arr = array();
	      $col_arr[0] = $gsname;
	      $col_arr[1] = $col_type;
	      $col_arr[2] = $req_bool;
			$colnames[$colcount] = $col_arr;
			$colcount++;		
		}
	}
}
}

fclose($inhandle);
echo 'Records read ' . $rread . PHP_EOL;
exit();




function camelize ($string)
{
  $string = str_replace(array('-', '_'), ' ', $string);
  $string = ucwords($string);
  $string = str_replace(' ', '', $string); 
  return $string;
}

function writearecord ($outhandle, $recstring, $rwrite)  {
	if ( fwrite($outhandle, $recstring) === FALSE )  {
	   	echo PHP_EOL . 'Unable to write record ' . $recstring . PHP_EOL;
	   	exit();
	}
	$nwrite = $rwrite + 1;
	return $nwrite;
}

?>