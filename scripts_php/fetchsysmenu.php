<?php
// Find forms that may be used by the sys admin and place into a table display.
require_once 'includes/db_functions.php';
require_once 'classes/BoilerPlate.php';
require_once 'classes/FormsMenu.php';

$def_lang = 'en-GB';
$nav_refn = 0;
$tab_capt = NULL;
$first_time = TRUE;
$alt_row = TRUE;
$item_count = 0;
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) )  {
	$def_lang = $_SESSION['langcode'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
$dpgconn = conn_db();
$tab_capt = $_SESSION['tabcapt'];
$fms = new FormsMenu();
// system administrator may alter their password
$fmpres = $fms->list_forms_menu_by_type($dpgconn, 'P');
if ($fmpres)  {
	while($row = pg_fetch_assoc($fmpres)) {
		$item_count++;
		if ($first_time)  {
			echo '<table id="menut"><caption>' . $tab_capt . '</caption><thead><tr><th style="text-align:center">Select from the list below</th></tr></thead><tbody>' . PHP_EOL;
			$first_time = FALSE;
		}
		$form_id   = $row['fm_id'];
		$this_form = $row['navgn_refn'];
		$alt_row = find_title($dpgconn, $this_form, $form_id, $def_lang, $alt_row);
	}
}
// transactional forms accessible only by the sys admin
$fmtres = $fms->list_forms_menu_by_type($dpgconn, 'T');
if ($fmtres)  {
	while($row = pg_fetch_assoc($fmtres)) {
		$sup_us = ( ($row['super_user'] == 't')? TRUE : FALSE);
		if ( ($sup_us) )  {
			$item_count++;
			if ($first_time)  {
				echo '<table id="menut"><caption>' . $tab_capt . '</caption><thead><tr><th style="text-align:center">Select from the list below</th></tr></thead><tbody>' . PHP_EOL;
				$first_time = FALSE;
			}
			$form_id   = $row['fm_id'];
			$this_form = $row['navgn_refn'];
			$alt_row = find_title($dpgconn, $this_form, $form_id, $def_lang, $alt_row);
		}
	}
	if ($item_count > 0)  {
		echo '</tbody></table>' . PHP_EOL;
	}  else  {
		echo '<p class="error">There are NO System Administrator Menu Items.</p>';
	}
}  else  {
	echo '<p class="error">Failed to access Forms Menu table.</p>';
}

function find_title ($dbc, $this_form, $form_id, $def_lang, $alt_row)  {
	$men_line = NULL;
	$next_row = FALSE;
	$bp = new BoilerPlate();
	$bpres = $bp->find_boiler_plate_by_lang($dbc, $this_form, $def_lang);
	if ($bpres)  {
		$men_line = $bp->getPageTitle();
	}  else  {
		$men_line = 'UNDEFINED ITEM ' . $this_form . ' ' . $def_lang;
	}
	$td_cell = '<a href="../index.php?act=for&nav=' . $form_id . '">' . $men_line . '</a>';
	if ($alt_row)  {
		echo '<tr class="unf"><td>' . $td_cell . '</td></tr>';
		$next_row = FALSE;
	}  else  {
		echo '<tr class="alt"><td>' . $td_cell . '</td></tr>';
		$next_row = TRUE;
	}
	return $next_row;
}
?>