<?php


function format_input ($form_name, $script_name, $rec_id)  {
// $inp_array = array('sd' => 'Y', 'lc' => '*', 'tp' => 'text', 'cl' => '*', 'sz' => '4', 'ml' => '4',
//    'af' => 'Y', 'rq' => 'Y', 'ed' => 'Y', 'br' => 'Y');
$field_attributes = array();
$attrib_count = 0;
$field_array = array();
$field_count = 0;
$vals_array = array();
$vals_count = 0;
$selopt_array = array();
$errors_array = array();
$error_count = 0;
$legnd_text = NULL;
$buttn_array = array();
$buttn_count = 0;
	if ( isset($_SESSION['fieldarr']))  {
		$field_array = $_SESSION['fieldarr'];
		$field_count = $_SESSION['labcount'];
	}
	if ( isset($_SESSION['fattribs']) )  {
		$field_attributes = $_SESSION['fattribs'];
		if ( is_array($field_attributes) )  {
			$attrib_count = count($field_attributes);
		}
	}
	if ( isset($_SESSION['formvalues']) )  {
		if ( is_array($_SESSION['formvalues']) ) {
			$vals_array = $_SESSION['formvalues'];
			$vals_count = count($_SESSION['formvalues']);
		}
	}
	if ( isset($_SESSION['selopts']) )  {
		$selopt_array = $_SESSION['selopts'];
	}
	if ( isset($_SESSION['errlabs']) )  {
		$errors_array = $_SESSION['errlabs'];
		$error_count = count($errors_array);
	}
	if ( isset($_SESSION['flegend']))  {
		$legnd_text = $_SESSION['flegend'];
	}
	if ( isset($_SESSION['buttarr']))  {
		$buttn_array = $_SESSION['buttarr'];
		$buttn_count = $_SESSION['buttcount'];
	}
	$inp_line = NULL;
	if ( ($attrib_count > 0) && ($field_count > 0) )  {
		echo '<form id="' . $form_name . '" name="' . $form_name . '" action="' . $script_name . '" method="post">';
		echo '<fieldset><legend>' . $legnd_text . '</legend>';
		echo '<input type="hidden" id="recId" name="recId" value="' . $rec_id . '" />';
		echo '<input type="hidden" id="token" name="token" value="' . $_SESSION['token'] . '" />' . PHP_EOL;
		foreach($field_attributes as $key => $value)  {
			$field_name = $key;
			$attribs = $value;
			$inp_line = NULL;
			if ( is_array($attribs) )  {
				if ($attribs['sd'] == 'Y')  {
					$inp_line = '<div>';
				}
				$lab_class = $attribs['lc'];
				$lab_descr = $field_array[$field_name];
				if ( ($error_count > 0) && (array_key_exists($field_name, $errors_array) ) )  {
					$inp_line = $inp_line . '<label class="errorlabel" for="' . $field_name . '">' . $lab_descr . ' :</label>';
				}  else  {
					if ($lab_class == '*')  {
						$inp_line = $inp_line . '<label for="' . $field_name . '">' . $lab_descr . ' :</label>';
					}  else  {
						$inp_line = $inp_line . '<label class="' . $lab_class . '" for="' . $field_name . '">' . $lab_descr . '</label>';
					}
				}
				$l_val = NULL;
				if ( ($vals_count > 0) && (array_key_exists($field_name, $vals_array) ) )  {
					$l_val = $vals_array[$field_name];
				}
				$inp_type = $attribs['tp'];
				switch($inp_type) {
					case 'text':
					case 'number':
					$inp_line = $inp_line . '<input type="' . $inp_type . '" id="' . $field_name . '" name="' . $field_name . '" size="' . $attribs['sz'] . '" maxlength="' . $attribs['ml'] . '" ';
					if ($attribs['cl'] != '*')  {
						$inp_line = $inp_line . ' class="' . $attribs['cl'] . '" ';
					}
					if ($attribs['rq'] == 'Y')  {
						$inp_line = $inp_line . ' required="required" ';
					}
					if ( ($attribs['af'] == 'Y')  || ( ($attribs['af'] == '2') && ($vals_count > 0) ) ) {
						$inp_line = $inp_line . ' autofocus="autofocus" ';
					}
					if ( $attribs['af'] == '1' ) {
						if ( $vals_count > 0)  {
							$inp_line = $inp_line . ' readonly="readonly" ';
						}  else  {
							$inp_line = $inp_line . ' autofocus="autofocus" ';
						}
					}
					if ( ($inp_type == 'text') && ($attribs['uc'] == 'Y') )  {
						$f_len = $attribs['ml'];
						$inp_line = $inp_line . ' pattern="[A-Z]{1,' . $f_len . '}" onkeyup="' . $field_name . 'toUC();" ';
					}
					if ( array_key_exists('min', $attribs))  {
						$inp_line = $inp_line . ' minimum="' . $attribs['min'] . '" ';
					}
					if ( array_key_exists('max', $attribs))  {
						$inp_line = $inp_line . ' maximum="' . $attribs['max'] . '" ';
					}
					$inp_line = $inp_line . ' value="' . $l_val . '" />';
					break;
					case 'textarea':
					$inp_line = $inp_line . '<textarea id="' . $field_name . '" name="' . $field_name . '" cols="' . $attribs['sz'] . '" rows="' . $attribs['ml'] . '" wrap>' . $l_val . '</textarea>';
					break;
					case 'checkbox':
					$inp_line = $inp_line . '<input type="checkbox" id="' . $field_name . '" name="' . $field_name . '" ';
					if ($attribs['cl'] != '*')  {
						$inp_line = $inp_line . ' class="' . $attribs['cl'] . '" ';
					}
					if ($l_val === TRUE)  {
						$inp_line = $inp_line . 'checked="checked" ';
					}
					$inp_line = $inp_line . ' />';
					break;
					case 'select':
					$inp_line = $inp_line . '<select id="' . $field_name . '" name="' . $field_name . '" >';
					if ($attribs['cl'] != '*')  {
						$inp_line = $inp_line . ' class="' . $attribs['cl'] . '" ';
					}
					if (array_key_exists($field_name, $selopt_array) )  {
						$opt_string = $selopt_array[$field_name];
					}  else  {
						$opt_string = '<option value="*">No options for '. $field_name . '</option>';
					}
					$inp_line = $inp_line . $opt_string . '</select>';
 					break;
					default:
					$inp_line = 'Unknown data type for ' . $field_name;
				}
				if ($attribs['ed'] == 'Y')  {
					$inp_line = $inp_line . '</div>';
				}
				if ($attribs['br'] == 'Y')  {
					$inp_line = $inp_line . '<br />' . PHP_EOL;
				}  else  {
					$inp_line = $inp_line . PHP_EOL;
				}
				echo $inp_line;
			}  else  {
				echo 'ERROR. Attributes not an array for ' . $field_name;
			}
		}
		echo '</fieldset><p><input type="submit" name="submit" value="' . $buttn_array['submit'] . '" class="buttonSubmit" />';
		echo '<input type="reset"  name="reset" value="' . $buttn_array['reset'] . '" class="buttonReset" /></p></form>' . PHP_EOL;
	}  else  {
		echo 'ERROR with arrays. Attributes = ' . $attrib_count . ' Fields = ' . $field_count; 
	}
}

?>