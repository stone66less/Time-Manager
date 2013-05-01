<?php
// write out whatever is held in $_POST array.

	$currdate = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
   $fdate = (string)$currdate->format('Ymd');
   $logdate = (string)$currdate->format('Y-m-d H:i:s');
   $fname = '/var/www/timemanager.info/debug/tmpost' . $fdate . '.log';
   $opened = fopen($fname, 'a');
   if ( !$opened )   {
   	echo 'Unable to open file ' . $fname;
   	}  else  {
      foreach ($_POST as $key => $value)  {
      	if ( is_array($value)  )   {
      		foreach ($value as $seckey => $secvalue)  {
      			$wstring = $logdate . ' seckey ' . $seckey . ' ' . $secvalue . PHP_EOL;
      			fwrite($opened, $wstring);
      		}
      	}  else  {
      		$wstring = $logdate . ' ' . $key . ' ' . $value . PHP_EOL;
      		fwrite($opened, $wstring);
      	}
      }
      foreach ($_GET as $key => $value)  {
      	if ( is_array($value)  )   {
      		foreach ($value as $seckey => $secvalue)  {
      			$wstring = $logdate . ' seckey ' . $seckey . ' ' . $secvalue . PHP_EOL;
      			fwrite($opened, $wstring);
      		}
      	}  else  {
      		$wstring = $logdate . ' ' . $key . ' ' . $value . PHP_EOL;
      		fwrite($opened, $wstring);
      	}
      }
      fclose($opened);
   }
?>