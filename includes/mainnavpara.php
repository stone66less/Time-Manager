<?php
if (isset($_SESSION['lg_error']) && (strlen($_SESSION['lg_error']) > 0) )  {
	$derror = $_SESSION['lg_error'];
	echo '<p class="error">' . $derror . '</p>';
	unset($_SESSION['lg_error']);
} else  {
	echo '<p>' . $_SESSION['pt'] . '</p>';
}
if (isset($_SESSION['errcount'])) {
	unset($_SESSION['errcount']);
}
?>