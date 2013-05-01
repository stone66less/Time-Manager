<?php
$label_list = array();
$field_label = NULL;
if ( isset($_SESSION['labcount']) )  {
	$field_no = (int)$_SESSION['labcount'];
	if ( isset($_SESSION['ffields']) && (strlen($_SESSION['ffields']) > 0) )  {
		$label_list = explode('|', $_SESSION['ffields']);
		$field_label = $label_list[$field_no];
		$field_no++;
		$_SESSION['labcount'] = $field_no;
	}
}
echo $field_label . ' :';
?>