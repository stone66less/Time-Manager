<?php
// functions used for setting up displays of data in tables.

function setup_header ($tab_capt, $thtd_array)  {
	$string_out = NULL;
	if (is_array($thtd_array))  {
		$count_thtd = count($thtd_array);
		if ($count_thtd > 0)  {
			$string_out = '<table id="avail"><caption>' . $tab_capt . '</caption><thead><tr>';
			foreach ($thtd_array as $key => $value)  {
				$col_key   = $key;
				$col_align = substr($col_key,0,1);
				if ( ($col_align == '-') || ($col_align == '_') || ($col_align == '+') )  {
					$col_head = substr($col_key,1);
				}  else  {
					$col_align = NULL;
					$col_head = $col_key;
				}
				$col_width = $value;
				if ($col_width > 0)  {
					if ( is_null($col_align) )  {
						$string_out = $string_out . '<th style="width:' . $col_width . '%";>' . $col_head . '</th>';
					}  else  {
						switch($col_align) {
							case '-':
							$string_out = $string_out . '<th style="text-align:left; width:' . $col_width . '%;">' . $col_head . '</th>';
							break;
							case '_':
							$string_out = $string_out . '<th class="centred" style="width:' . $col_width . '%;">' . $col_head . '</th>';
							break;
							case '+':
							$string_out = $string_out . '<th style="text-align:right; width:' . $col_width . '%;">' . $col_head . '</th>';
							break;
							default:
							$string_out = $string_out . '<th style="width:' . $col_width . '%;">' . $col_head . '</th>';
						}
					}
				}  else  {
					if ( is_null($col_align) )  {
						$string_out = $string_out . '<th>' . $col_head . '</th>';
					}  else  {
						switch($col_align) {
							case '-':
							$string_out = $string_out . '<th style="text-align:left;">' . $col_head . '</th>';
							break;
							case '_':
							$string_out = $string_out . '<th class="centred">' . $col_head . '</th>';
							break;
							case '+':
							$string_out = $string_out . '<th style="text-align:right;">' . $col_head . '</th>';
							break;
							default:
							$string_out = $string_out . '<th>' . $col_head . '</th>';
						}
					}
				}
			}  // end of looping thru array
			$string_out = $string_out . '</tr></thead><tbody>';
		} // array contains data
	}  //  array passed
	return $string_out;
}

function build_pc_array ($max_rows, $def_limit)  {
	$pc_array = array();
	$max_pages = ceil($max_rows / $def_limit);
	$curr_page = 1;
	$off_set = 0;
	$pc_array['max'] = $max_pages;
	$pc_array['cur'] = $curr_page;
	$pc_array['off'] = $off_set;
	$pc_array['lim'] = $def_limit;
	$_SESSION['pcarray'] = $pc_array;
}

function do_next_block () {
	$more_pages = FALSE;
	$pc_array = $_SESSION['pcarray'];
	$max_pages = $pc_array['max'];
	$curr_page = $pc_array['cur'];
	$off_set   = $pc_array['off'];
	$lim_it    = $pc_array['lim'];
	if ( $max_pages >= ($curr_page + 1) )  {
		$curr_page++;
		$off_set = $off_set + $lim_it;
		$pc_array['cur'] = $curr_page;
		$pc_array['off'] = $off_set;
		$pc_array['max'] = $max_pages;
		$pc_array['lim'] = $lim_it;
		$_SESSION['pcarray'] = $pc_array;
		$more_pages = TRUE;
	}
	return $more_pages;
}

function do_prev_block () {
	$prior_page = FALSE;
	$pc_array = $_SESSION['pcarray'];
	$max_pages = $pc_array['max'];
	$curr_page = $pc_array['cur'];
	$off_set   = $pc_array['off'];
	$lim_it    = $pc_array['lim'];
	if ( $curr_page > 1)   {
		$curr_page--;
		$off_set = $off_set - $lim_it;
		$pc_array['cur'] = $curr_page;
		$pc_array['off'] = $off_set;
		$pc_array['max'] = $max_pages;
		$pc_array['lim'] = $lim_it;
		$_SESSION['pcarray'] = $pc_array;
		$prior_page = TRUE;
	}
	return $prior_page;
}

function table_list_end ($curr_page, $max_pages, $maint_go)  {
	echo '</tbody></table><p>' . PHP_EOL;
	if ( ($curr_page > 1) && ($curr_page <= $max_pages) )  {
		echo '<img src="../images/sblu_w.gif" style="margin-right: 12px;" width="32" height="32" alt="Click to go back one page" onclick="doitPrev();">';
	}  else  {
		echo '<img src="../images/sqbkgrnd.png" style="margin-right: 12px;" width="32" height="32" />';
	}
	echo 'Page &nbsp;' . $curr_page . '&nbsp; of&nbsp; ' . $max_pages;
	if ( $max_pages >= ($curr_page + 1) )  {
		echo '<img src="../images/sblu_e.gif" style="margin-left: 12px;" width="32" height="32" alt="Click to go forward one page" onclick="doitNext();">';
	}  else  {
		echo '<img src="../images/sqbkgrnd.png" style="margin-left: 12px;" width="32" height="32" />';
	}
	echo '</p><p><form name="dummyone" id="dummyone" action="../index.php?act=nav&nav=' . $maint_go . '&recid=0"' . ' method="POST">';
	echo '<input type="hidden" id="goTo" name = "goTo" value="' . $maint_go . '" /><p><input type="submit" name="submit" value="ADD NEW" class="addB" />';
	echo '</form></p>';	
}


?>