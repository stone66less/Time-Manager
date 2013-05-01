<?php
// Action Factory for boiler_plate.
require_once '../classes/BoilerPlate.php';
require_once 'ErrorMessagesActions.php';

function session_form_labels ($dbc, $nav_refn, $lang_code)  {
$err_text = NULL;
$navtext_array = array();
$head_array = array();
$field_array = array();
$buttn_array = array();
$thtd_array = array();
$bp = new BoilerPlate();
$bpres = $bp->find_boiler_plate_by_lang($dbc, $nav_refn, $lang_code);
if ($bpres)  {
	$_SESSION['formtitle'] = $bp->getPageTitle();
	$nav_text  = $bp->getNavignBar();
	if ( strlen($nav_text) > 0 )  {
		$json = json_decode($nav_text);
		if (json_last_error() === JSON_ERROR_NONE) {
			$navtext_array = json_decode($nav_text, TRUE);
			$nvt_count = count($navtext_array);
			$_SESSION['navtarr'] = $navtext_array;
			$_SESSION['navtcount'] = $nvt_count;
		}  else  {
			$err_text = return_error($dbc, 9011, $lang_code) . ' Nav ' . $nav_refn . ' Lang ' . $lang_code;
		}
	}
	$_SESSION['pt'] = $bp->getHeadingOne();
	if ( strlen($bp->getHeadingTwo() > 0) )  {
		$head_array['h_two'] = $bp->getHeadingTwo();
	}
	if ( strlen($bp->getHeadingTre() > 0) )  {
		$head_array['h_tre'] = $bp->getHeadingTre();
	}
	if ( strlen($bp->getHeadingQua() > 0) )  {
		$head_array['h_qua'] = $bp->getHeadingQua();
	}
	if ( strlen($bp->getHeadingCin() > 0) )  {
		$head_array['h_cin'] = $bp->getHeadingCin();
	}
	if ( strlen($bp->getHeadingSix() > 0) )  {
		$head_array['h_six'] = $bp->getHeadingSix();
	}
	if ( count($head_array) > 0 )  {
		$_SESSION['headarr'] = $head_array;
	}
	if ( strlen($bp->getLegEnd()) > 0) {
		$_SESSION['flegend'] = $bp->getLegEnd();
	}
	$field_text = $bp->getFormFields();
	if ( strlen($field_text) > 0 )  {
		$json = json_decode($field_text);
		if (json_last_error() === JSON_ERROR_NONE) {
			$field_array = json_decode($field_text, TRUE);
			$field_count = count($field_array);
			$_SESSION['fieldarr'] = $field_array;
			$_SESSION['labcount'] = $field_count;
		} else {
			echo '<p class="error">Form Labels are NOT JSON encoded.</p>';
		}
	}
	$buttn_text = $bp->getSubtButtons();
	if ( strlen($buttn_text) > 0 )  {
		$json = json_decode($buttn_text);
		if (json_last_error() === JSON_ERROR_NONE) {
			$buttn_array = json_decode($buttn_text, TRUE);
			$buttn_count = count($buttn_array);
			$_SESSION['buttarr'] = $buttn_array;
			$_SESSION['buttcount'] = $buttn_count;
		} else {
			echo '<p class="error">Button Labels are NOT JSON encoded.</p>';
		}
	}
	$tab_capt = $bp->getCaptIons();
	$_SESSION['tabcapt'] = $tab_capt;
	$thtd_cells = $bp->getThtdCells();
	if (strlen($thtd_cells) > 0)  {
		$json = json_decode($thtd_cells);
		if (json_last_error() === JSON_ERROR_NONE) {
			$thtd_array = json_decode($thtd_cells, TRUE);
			$thtd_count = count($thtd_array);
			$_SESSION['thtdarr'] = $thtd_array;
		} else {
			$err_text = return_error($dbc, 9008, $lang_code) . ' Nav ' . $nav_refn . ' Lang ' . $lang_code;
		} 
	}
}  else  {
	$err_text = return_error($dbc, 9009, $lang_code) . ' Nav ' . $nav_refn . ' Lang ' . $lang_code;
}
	return $err_text;
}
?>