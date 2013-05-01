<?php
require_once 'includes/dbconnect.php';
require_once 'classes/ErpMenus.php';
require_once 'classes/SearchableTables.php';
require_once 'classes/SearchTableColumns.php';
// Build a select list of tables accessible by this User and set-up the search form.
$mod_array = array();
$sel_string = NULL;
ob_start();

$dpgconn = conn_db();
if ( isset($_SESSION['uname']) && (strlen($_SESSION['uname']) > 0) && (isset($_SESSION['userid'])) )  {
	$user_id = $_SESSION['userid'];
	$coy_id = $_SESSION['coyid'];
	$group_id = $_SESSION['grid'];
	$stab = new SearchableTables();
	$stabres = $stab->useable_searchable_tables ($dpgconn, $coy_id, $group_id, $user_id);
	if (!$stabres)  {
		echo '<p class="error">Database error attempting to find searchable tables available for this User.</p>';
	}  else  {
		if ( pg_num_rows($stabres) > 0 )  {
			$sel_string = '<option value="0">** Choose a Table to Search</option>';
			while ($row = pg_fetch_assoc($stabres))  {
				$srch_id = $row['search_id'];
				$tab_name = $row['stable_name'];
				$tab_descr = $row['table_description'];
				$nav_refn  = $row['navigation_reference'];
				$opt_val = $srch_id . '|' . $nav_refn . '|' . $tab_name;
				$sel_string = $sel_string . '<option value="' . $opt_val . '">' . $tab_descr . '</option>';
			}
			echo '<form id="searchform" name="searchform" method="post" />';
			echo '<fieldset><input type="hidden" id="andor" name="andor" value="?" />';
			echo '<div><label style="text-align: left; padding-left: 12px; width:100px;">Select Table :</label>';
			echo '<select id="srchTable" name="srchTable" style="width:240px;">';
			echo $sel_string . '</select></div>' . PHP_EOL;
			echo '<div id="fcolist"></div>';
			echo '</fieldset></form>' . PHP_EOL;
		}  else  {
			echo '<p>There are NO searchable tables available for this User.</p>';
		}
	}
}

ob_end_flush();

?>