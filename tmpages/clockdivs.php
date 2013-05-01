<?php

$cdivs = <<<CLO
	<div id="timemandiv"><img src="../images/timeman.png" alt="Time Manager Logo" width="600px" height="90px" /></div>
   <div style="width:120px;height:100px;position:relative;left:58px;top:96px;">
   <div id="dig1" class="dig">1</div>
	<div id="dig2" class="dig">2</div>
	<div id="dig3" class="dig">3</div>
	<div id="dig4" class="dig">4</div>
	<div id="dig5" class="dig">5</div>
	<div id="dig6" class="dig">6</div>
	<div id="dig7" class="dig">7</div>
	<div id="dig8" class="dig">8</div>
	<div id="dig9" class="dig">9</div>
	<div id="dig10" class="dig">10</div>
	<div id="dig11" class="dig">11</div>
	<div id="dig12" class="dig">12</div>

	<div id="hour1" class="hour"></div>
	<div id="hour2" class="hour"></div>
	<div id="hour3" class="hour"></div>
	<div id="hour4" class="hour"></div>

	<div id="min1" class="min"></div>
	<div id="min2" class="min"></div>
	<div id="min3" class="min"></div>
	<div id="min4" class="min"></div>
	<div id="min5" class="min"></div>

	<div id="sec1" class="sec"></div>
	<div id="sec2" class="sec"></div>
	<div id="sec3" class="sec"></div>
	<div id="sec4" class="sec"></div>
	<div id="sec5" class="sec"></div>
	<div id="sec6" class="sec"></div>
	</div>
	<div id="welcomediv">
CLO;

echo $cdivs;
if ($_SESSION['frmtyp'] == 'O')  {
	echo $_SESSION['fwell'];
}  else  {
	echo $_SESSION['wlcm'];
}
echo ' : ' . $_SESSION['uname'] . '</div></div><div id="spacerdiv">&nbsp;</div>' . PHP_EOL;
echo '<div id="topNav">';
echo $_SESSION['navlist'] . '</div><div id="mainnav">';
?>
