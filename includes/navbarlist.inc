<?php
require_once 'inc/formlist.inc';
$curr_self = $_SERVER['PHP_SELF'];
$curr_form = substr($curr_self,1);
$form_no = array_search($curr_form, $formsarray);
// trigger_error('array key is ' . $form_no);

echo '<ul id="nav">';
echo '<li class="first"><a href="logout.html" title="Log out from Time Manager">Log Out</a></li>';
if ( $form_no != 3 )  {
echo '<li><a href="navdriver.html?to=3" title="Return to main Dashboard">Dashboard</a></li>';
}
if ( $form_no != 15 )  {
echo '<li><a href="navdriver.html?to=15" title="Change your Details">You</a></li>';
}
if ( $form_no != 5 )  {
echo '<li><a href="navdriver.html?to=5" title="Change Your Password">Password</a></li>';
}
if ( $form_no != 18 ) {
if ( isset($_SESSION['viewot']) && !empty($_SESSION['viewot'])  && ($_SESSION['viewot'] == 'Y') )  {        
   echo '<li><a href="navdriver.html?to=18" title="See Tasks for Other Users">Other User</a></li>';
}
}
if ( isset($_SESSION['superu']) && !empty($_SESSION['superu'])  && ($_SESSION['superu'] == 'Y') )  {
	if ( $form_no != 9 )  {
   echo '<li><a href="navdriver.html?to=9" title="Maintain Users">User Maint</a></li>';
	}
	if ( $form_no != 7 )  {
   echo '<li><a href="navdriver.html?to=7" title="Error Message Listing">Errors</a></li>';
	}
	if ( $form_no != 8 )  {
   echo '<li><a href="navdriver.html?to=8" title="List All Group Roles">Groups</a></li>';
	}
}
echo '<li><a href="help_doco/tmhelp.html" target="_blank" title="Click to display help information in a separate window">Help</a></li>';
echo '</ul>';

?>