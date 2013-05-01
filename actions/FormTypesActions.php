<?php
// factory for table form_types.

require_once '../classes/FormTypes.php';

function form_type_opt_list ($dbc, $lang_code, $in_type=NULL)  {
	$opt_list = NULL;
	$fmt = new FormTypes();
	$fmtres = $fmt->list_types_by_lang($dbc, $lang_code);
	if ($fmtres)  {
		$opt_list = '<option value="*">* * Choose a Form Type</option>';
		while($row = pg_fetch_assoc($fmtres)) {
			$opt_type = $row['form_type'];
			$opt_desc = $row['type_descr'];
			if ( (!is_null($in_type)) && ($opt_type == $in_type) )  {
				$opt_string = '<option value="' . $opt_type . '" selected="selected" >' . $opt_desc . '</option>';
			}  else  {
				$opt_string = '<option value="' . $opt_type . '" >' . $opt_desc . '</option>';
			}
			$opt_list = $opt_list . $opt_string;
		}
	}
	return $opt_list;
}
?>