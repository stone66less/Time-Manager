<?php

require_once '../classes/SupportedLanguages.php';

function lang_opt_list ($dbc, $first_option, $in_lang=NULL)  {
	$opt_list = NULL;
	$sl = new SupportedLanguages();
	$slres = $sl->list_languages($dbc);
	if ($slres)  {
		$opt_list = '<option value="*">' . $first_option . '</option>';
		while($row = pg_fetch_assoc($slres)) {
			$opt_type = $row['lang_code'];
			$opt_desc = $row['lang_name'];
			$in_use = ( ($row['lang_inuse'] == 't')? TRUE : FALSE);
			if ($in_use)  {
				if ( (!is_null($in_lang)) && ($opt_type == $in_lang) )  {
					$opt_string = '<option value="' . $opt_type . '" selected="selected" >' . $opt_desc . '</option>';
				}  else  {
					$opt_string = '<option value="' . $opt_type . '" >' . $opt_desc . '</option>';
				}
				$opt_list = $opt_list . $opt_string;
			}
		}
	}
	return $opt_list;
}
?>