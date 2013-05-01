<?php
// generic search function
require_once '../includes/dbconnect.php';
require_once '../includes/charfunctions.php';
require_once '../classes/SearchTableColumns.php';

function fix_comp_string ($string_in, $comp_oper, $data_type)  {
	$string_out = NULL;
	$str_len = strlen($string_in);
	$first_char = substr($string_in,0,1);
	$last_char = substr($string_in, ($str_len - 1), 1);
	if ( ($data_type == 'V') || ($data_type == 'C') ) {
		if ($comp_oper == 'LK')  {
			$string_out = strtoupper($string_in);
			if ( ($first_char == '%')  && ($last_char == '%') ) {
				$cool = TRUE;
			}  else  {
				if ($first_char != '%')  {
					$string_out = '%' . $string_out;
				}
				if ($last_char != '%')  {
					$string_out = $string_out . '%';
				}
			}
			$string_out = "'" . $string_out . "'";
		}  else  {
			$string_out = "'" . $string_in . "'";
		}
	}
	if ($data_type == 'D')  {
		if ( ($first_char == "'")  && ($last_char == "'") ) {
			$cool = TRUE;
		}  else  {
			if ($first_char != "'")  {
				$string_out = "'" . $string_out;
			}
			if ($last_char != "'")  {
				$string_out = $string_out . "'";
			}
		}
	}
	if ($data_type == 'B')  {
		$cool = TRUE;
	}
	return $string_out;
}

$comp_array = array('LT' => '<', 'GT' => '>', 'EQ' => '=', 'NE' => '<>', 'LK' => 'LIKE', 'TR' => 'IS TRUE', 'FS' => 'IS NOT TRUE', 'IN' => 'IN');
$db_string = NULL;
$cols_array = array();
$col_join = 'X';
$key_value = 0;
$first_time = TRUE;
$tab_head = NULL;
$this_query = NULL;
$fcomp_string = NULL;
$scomp_string = NULL;
$input_arr = array();
ob_start();
session_start();
reset($_POST);
$k_one = key($_POST);
$v_one = current($_POST);
// trigger_error('v_one ' . $v_one);
$bar_count = strpos($v_one , '|');
if ($bar_count > 0)  {
	$input_arr = explode('|', $v_one);
	$ia_count = count($input_arr);
// trigger_error('arr count ' . $ia_count);
	if ($ia_count > 4)  {
		$dpgconn = conn_db();
		$coy_id = (int)$_SESSION['coyid'];
		$srcht_id  = (int)$input_arr[0];
		$nav_refn  = (int)$input_arr[1];
		$tab_name  = $input_arr[2];
		$fcol_name  = $input_arr[3];
		$fcomp_oper = $input_arr[4];
		$fcomp_val  = $input_arr[5];
		$col_join = 'X';
		if ( $ia_count == 11)  {
			$scol_name  = $input_arr[6];
			$scomp_oper = $input_arr[7];
			$scomp_val  = $input_arr[8];
			$col_join   = $input_arr[9];
		}
// trigger_error('col_join ' . $col_join);
		$stc = new SearchTableColumns();
		$stcres = $stc->list_search_table_columns($dpgconn, $srcht_id);
		if ( ($stcres) && (pg_num_rows($stcres) > 0) )  {
			$cols_array = array();
			$cols_count = 0;
			$tab_head = '<table id="avail"><thead><tr>';
			while ($row = pg_fetch_assoc($stcres))  {
				$is_act = ( ($row['is_active'] == 't')? TRUE : FALSE);
				if ( $is_act )  {
					$col_name = $row['column_name'];
					$col_descr = $row['column_dscrptn'];
					$dat_type = $row['data_type'];
					$ent_code = ( ($row['ent_decode'] == 't')? TRUE : FALSE);
					$prim_key = ( ($row['primary_key'] == 't')? TRUE : FALSE);
					if ($prim_key)  {
						$dat_type = 'P';
					}  else  {
						$tab_head = $tab_head . '<th>' . $col_descr . '</th>';
					}
					$cols_array[$cols_count] = $dat_type;
					$cols_count++;
					if ( $first_time )  {
						$this_query = 'SELECT ' . $col_name;
						$first_time = FALSE;
					}  else  {
						$this_query = $this_query . ', ' . $col_name;
					}
					if ($col_name == $fcol_name)  {
						if ($ent_code)  {
							$fcomp_val = htmlspecialchars($fcomp_val, ENT_QUOTES, 'UTF-8');
						}
						$fcomp_string = fix_comp_string ($fcomp_val, $fcomp_oper, $dat_type);
						if ($fcomp_oper == 'LK')  {
							$fcol_name = 'UPPER(' . $fcol_name . ')';
						}
						if ($dat_type == 'D')  {
							$fcol_name = 'TO_CHAR(' . $fcol_name . ",'DD-MM-YYYY')";
						}
					}
					if ( ($col_join == 'A') || ($col_join == 'O') )  {
						if ($col_name == $scol_name)  {
							if ($ent_code)  {
								$scomp_val = htmlspecialchars($scomp_val, ENT_QUOTES, 'UTF-8');
							}
							$scomp_string = fix_comp_string ($scomp_val, $scomp_oper, $dat_type);
							if ($scomp_oper == 'LK')  {
									$scol_name = 'UPPER(' . $scol_name . ')';
							}
							if ($dat_type == 'D')  {
								$scol_name = 'TO_CHAR(' . $scol_name . ",'DD-MM-YYYY')";
							}
						}
					}
				}
			}
			$tab_head = $tab_head . '</tr></thead><tbody>';
			$this_query = $this_query . ' FROM ' . $tab_name . ' WHERE ' . $fcol_name;
			$q_op = $comp_array[$fcomp_oper];
			$this_query = $this_query . ' ' . $q_op . ' ' . $fcomp_string . ' ';
// trigger_error('col_join 2 ' . $col_join);
			if ( ($col_join == 'A') || ($col_join == 'O') ) {
				if ($col_join == 'A')  {
					$col_oper = ' AND ';
				}  else  {
					$col_oper = ' OR ';
				}
				$q_op = $comp_array[$scomp_oper];
				$this_query = $this_query . $col_oper . $scol_name . ' ' . $q_op . ' ' . $scomp_string;
			}
			$res = pg_query($dpgconn, $this_query);
			if ( ($res) && (pg_num_rows($res) > 0) )  {
				echo $tab_head . PHP_EOL;
				$do_link = FALSE;
				while ($row = pg_fetch_row($res))  {
					$row_count = count($row);
					if ($row_count > 0)  {
						$disp_row = '<tr>';
						for ($i = 0; $i < $row_count; $i++)  {
							$col_val = $row[$i];
							$col_len = strlen(trim($col_val));
							if ( ($col_len == 0) )  {
								$col_val = '&nbsp;';
							}
							$dat_type = $cols_array[$i];
// trigger_error('col val ' . $col_val . ' dat ' . $dat_type);
							if ($dat_type == 'P')  {
								$key_value = $col_val;
								$do_link = TRUE;
							}  else  {
								if ($dat_type == 'B')  {
									$arr_val = ( ($col_val == 't')? 'TRUE' : 'FALSE');
								}  else  {
									if ($do_link)  {
										$arr_val = '<a href="index.php?act=for&nav=' . $nav_refn . '&erpid=' . $key_value . '" title="Click to maintain this row">' . $col_val . '</a>';
// trigger_error('arr val dl ' . $arr_val);
										$do_link = FALSE;
									}  else  {
										$arr_val = $col_val;
									}
								}
// trigger_error('arr val ' . $arr_val);
								$disp_row = $disp_row . '<td>' . $arr_val . '</td>';
							}
						}
						$disp_row = $disp_row . '</tr>';
						echo $disp_row;
						$disp_row = NULL;
					}  else  {
						echo '<tr><td>row count is zero</td></tr>';
					}
				}
				echo '</tbody></table>';
			}  else  {
				echo '<p class="error">Your query did not find any rows in this table.</p>';
			}
		}  else  {
			echo '<p class="error">Unable to find columns for ' . $srcht_id . '</p>';
		}
	}  else  {
		echo '<p class="error">Invalid search parameters passed</p>';
	}
}  else  {
	echo '<p class="error">Form error. Please advise your System Administrator.</p>';
}
ob_end_flush();
?>