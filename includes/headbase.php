<?php
// set up base header data
session_start();
require_once 'db_functions.php';
require_once '../actions/BoilerPlateActions.php';
require_once '../actions/TimeUsersActions.php';
require_once '../classes/FormsMenu.php';
// require_once 'includes/tmdebug.php';
$lang_code = 'en-GB';
$dir_ction = 'ltr';
$char_set = 'UTF-8';
$form_id = 0;
$user_id = 0;
$group_id = 0;
$form_title = 'NOT FOUND';
$nav_text = NULL;
function fetch_form ($dbc, $form_type)  {
	$form_id = 0;
	$fmus = new FormsMenu();
	$res  = $fmus->list_forms_menu_by_type ($dbc, $form_type);
	if ( ($res) && (pg_num_rows($res) == 1) )  {
		$row = pg_fetch_assoc($res);
		$form_id = $row['fm_id'];
	}
	return $form_id;
}
if (!isset($_SESSION['uname'])) {
	header("Location: ../index.php");
	exit();
}
$server_name = substr($_SERVER['PHP_SELF'],1);
$slash_pos = strrpos($server_name, '/');
$html_name = substr($server_name, ($slash_pos + 1));
// trigger_error('Form Name ' . $html_name);
if ( isset($_SESSION['htmlname']) && ($_SESSION['htmlname'] != $html_name) )  {
	header("Location: ../index.php?act=men");
	exit();
}
if ( isset($_SESSION['langcode']) && (strlen($_SESSION['langcode']) > 0) ) {
	$lang_code = $_SESSION['langcode'];
}
if ( isset($_SESSION['charset']) && (strlen($_SESSION['charset']) > 0) )  {
	$char_set = $_SESSION['charset'];
}
if ( isset($_SESSION['drctn']) && (strlen($_SESSION['drctn']) > 0) )  {
	if ($_SESSION['drctn'] == 'L')  {
		$dir_ction = 'ltr';
	}  else  {
		$dir_ction = 'rtl';
	}
}
if ( isset($_SESSION['currform']) && (strlen($_SESSION['currform']) > 0) )  {
	$form_id = (int)$_SESSION['currform'];
}
if ( isset($_SESSION['navrefn']) && (strlen($_SESSION['navrefn']) > 0) )  {
	$nav_refn = (int)$_SESSION['navrefn'];
}
if ( isset($_SESSION['userid']) )  {
	$user_id = (int)$_SESSION['userid'];
}
if ( isset($_SESSION['grid']) )  {
	$group_id = (int)$_SESSION['grid'];
}
if ( isset($_SESSION['navlist']) )  {
	unset($_SESSION['navlist']);
}
if ( isset($_SESSION['navtarr']))  {
	unset($_SESSION['navtarr']);
	unset($_SESSION['navtcount']);
}
if ( isset($_SESSION['thtdarr']))  {
	unset($_SESSION['thtdarr']);
}
if ( isset($_SESSION['tabcapt']))  {
	unset($_SESSION['tabcapt']);
}
if ( isset($_SESSION['headarr']))  {
	unset($_SESSION['headarr']);
}
if ( isset($_SESSION['flegend']))  {
	unset($_SESSION['flegend']);
}
if ( isset($_SESSION['fieldarr']))  {
	unset($_SESSION['fieldarr']);
	unset($_SESSION['labcount']);
}
if ( isset($_SESSION['buttarr']))  {
	unset($_SESSION['buttarr']);
	unset($_SESSION['buttcount']);
}
$navtext_array = array();
$head_array = array();
$field_array = array();
$buttn_array = array();
$thtd_array = array();
$dpgconn = conn_db();
$bpres_text = session_form_labels($dpgconn, $nav_refn, $lang_code);
if ( !is_null($bpres_text) )  {
	echo $bpres_text;
}  else  {

	if ( isset($_SESSION['navbar']) && (strlen($_SESSION['navbar']) > 0) && (isset($_SESSION['navtarr']))  && (count($_SESSION['navtarr']) > 0)  )  {
		$bara_count = 0;
		$nvt_count = 0;
		$first_time = TRUE;
		$help_wanted = FALSE;
		$bar_array = explode('+',$_SESSION['navbar']);
		$bara_count = count($bar_array);
		$navtext_array = $_SESSION['navtarr'];
		$nvt_count = $_SESSION['navtcount'];
		if ( ($bara_count * 2) == $nvt_count)  {
			$nb_string = NULL;
			foreach($bar_array as $key => $value)  {
				$nav_tag = $value;
				$f_type = NULL;
				$f_title = NULL;
				$f_disp = NULL;
				$tag_val = NULL;
				switch($nav_tag) {
					case 'logoff':
						$f_type = 'O';
						$nb_id = fetch_form ($dpgconn, $f_type);
						$ind_get = '?act=for&nav=' . $nb_id;
						$f_title = $navtext_array['logoffT'];
						$f_disp  = $navtext_array['logoffD'];
					break;
					case 'menu':
						$ind_get = '?act=men';
						$f_title = $navtext_array['menuT'];
						$f_disp  = $navtext_array['menuD'];
					break;
					case 'dash':
						$ind_get = '?act=men';
						$f_title = $navtext_array['dashT'];
						$f_disp  = $navtext_array['dashD'];
					break;
					case 'back':
						$ind_get = '?act=ret';
						$f_title = $navtext_array['backT'];
						$f_disp  = $navtext_array['backD'];
					break;
					case 'help':
						$help_wanted = TRUE;
						$f_title = $navtext_array['helpT'];
						$f_disp  = $navtext_array['helpD'];
					break;
					default:
						if (is_numeric($nav_tag))  {
							$go_to = (int)$nav_tag;
							$ntm = new FormsMenu();
							$ntmres = $ntm->find_forms_menu_by_navgn($dpgconn, $go_to);
							if ($ntmres)  {
								if ( ($ntm->getActiveItem()) && (!$ntm->getSuperUser()) )  {
									$disp_ok = test_useage($dpgconn, $user_id, $ntm->getSysgrpUser());
									if ($disp_ok == 'Y')  {
										$ind_get = '?act=nav&nav=' . (string)$go_to;
										$t_key = (string)$go_to . 'T';
										$d_key = (string)$go_to . 'D';
										$f_title = $navtext_array[$t_key];
										$f_disp  = $navtext_array[$d_key];
									}
								}
							}
						}  else  {
							$f_title = 'Nav Unknown ' . $nav_tag;
							$f_disp = 'ERROR';
						}
					break;
				}
				if (!$help_wanted)  {
					if (!is_null($f_title))  {
						$tag_val = '<a href="../index.php' . $ind_get . '" title="' . $f_title . '">' . $f_disp . '</a></li>';
					}
				}  else  {
					$tag_val = '<a href="../help_doco/tmhelper.php?cf=' . $form_id . '" title="' . $f_title . '" target="_blank">' . $f_disp . '</a></li>';
				}
				if ($first_time)  {
					$nb_string = '<ul id="nav"><li class="first">' . $tag_val;
					$first_time = FALSE;
				}  else  {
					$nb_string = $nb_string . '<li>' . $tag_val;
				}
			}
			if ( strlen($nb_string) > 0)  {
				$nb_string = $nb_string . '</ul>';
				$_SESSION['navlist'] = $nb_string;
			}
		}  else  {
			trigger_error('Error with nav bar ' . $bara_count . ' ' . $nvt_count);
		}
	}  else  {
		$_SESSION['navlist'] = ' ';
	}
	ob_start();
	echo 'xml:lang="' . $lang_code . '" lang="' . $lang_code . '" dir="' . $dir_ction . '">' . PHP_EOL;
	echo '<head>' . PHP_EOL;
	echo '<meta http-equiv="content-type" content="text/html; charset=' . $char_set . '" >' . PHP_EOL;
	echo '<meta name="author" content="rob stone" />';
	echo '<meta name="copyright" content="rob stone" />' . PHP_EOL;
	echo '<title>' . $_SESSION['formtitle'] . '</title>';
	echo '<style type="text/css" media="screen">' . PHP_EOL;
	echo '		@import url( tmgrstyles.css ); ' . PHP_EOL;
	echo '</style>' . PHP_EOL;
	echo '<script language="JavaScript" type="text/javascript" src="../scripts_js/' . $lang_code . '_logueclock.js">' . PHP_EOL;
	echo '</script>' . PHP_EOL;
	ob_end_flush();
}
?>