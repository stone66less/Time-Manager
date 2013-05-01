<?php
// A function to handle encryption and decryption of passwords stored on the database.
// First parameter is an action code to signify what to do.
// The variable $db_pword is only required for decryption and consists of the initialisation
// vector concatenated with the hashed password.
// A string is returned.
function enc_decrypt ($action, $plain_text, $db_pword) {
    $key = 'this9is8a7very6long5key,4even3too2long1for0the9cipher';
    /* Open module, and create IV */
    $td = mcrypt_module_open('rijndael-256', '', 'ctr', '');
    $key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));
    $iv_size = mcrypt_enc_get_iv_size($td);
    switch($action) {
    	case 'ENC' :
      $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);
    /* Initialize encryption handle */
      if (mcrypt_generic_init($td, $key, $iv) != -1) {
        /* Encrypt data */
        $c_t = mcrypt_generic($td, $plain_text);
        $c_t = $iv.$c_t;
        } else {
        	$c_t = 'A';
        	}
        break;
      case 'DEE' :
      $ivd = substr($db_pword, 0, $iv_size);
      if (mcrypt_generic_init($td, $key, $ivd) != -1) {
      	$p_t = rtrim(mdecrypt_generic($td, substr($db_pword, $iv_size)),"\0");
      	if (strncmp($p_t, $plain_text, strlen($plain_text)) == 0) {
      		$c_t = 'TRUE';
      		} else {
      		$c_t = 'FALSE';
      	}
     	} else {
     		$c_t = 'B';
     	}
     	break;
     	}   // end of switch.
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);
      return $c_t;
}
// Using Blowfish method.
function bf_encdec ($action, $plain_text, $db_pword) {
     switch($action) {
       case 'ENC' :
       $my_salt = makeSalt();
       $enc_pass = crypt($plain_text, $my_salt);
       return $enc_pass;
       break;
       case 'DEE' :
       if ($db_pword == crypt($plain_text, $db_pword) ) {
       	 return 'Y';
       } else {
       	 return 'N';
       }
       break;
       default:
       return 'N';
       break;
     }      // end of switch
}
function makeSalt() {
    static $seed = "./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $algo = '$2y';
    $strength = '$08';
    $salt = '$';
    for ($i = 0; $i < 22; $i++) {
        $salt .= substr($seed, mt_rand(0, 63), 1);
    }
    return $algo . $strength . $salt;
}
?>