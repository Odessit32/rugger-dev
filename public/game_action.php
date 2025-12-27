<?php
	header("Content-type: text/html; charset=UTF-8");
	//header("Content-encoding: windows-1251");
	session_start();
	require("classes/config.php");
	require("classes/DB.php");

	$db = new database;
	$db->connect();

	// Определяем USER_ID из сессии через базу данных
	if (!defined('USER_ID')) {
		$user_id = 0;
		if (isset($_SESSION['login_name'])) {
			$session_hash = $_SESSION['login_name'];
			$session_data = $db->selectElem(DB_T_PREFIX."sessions", "s_u_id", "s_hash='".$session_hash."' AND s_u_type = 'admin' AND s_datetime_end > NOW() LIMIT 1");
			if (!empty($session_data) && isset($session_data[0]['s_u_id'])) {
				$user_id = intval($session_data[0]['s_u_id']);
			}
		}
		define('USER_ID', $user_id);
	}

	$user = 0;
    $echo_ = '';
	
	if ($_GET['do']=='delete'){
		$ga_id = intval($_GET['ga_id']);
		if($ga_id>0){
			$item_temp = $db->selectElem(DB_T_PREFIX."games_actions","ga_type, ga_g_id, ga_t_id","ga_id = '".$ga_id."' LIMIT 1");
			if ($item_temp and ($item_temp[0]['ga_type'] == 'pop' OR $item_temp[0]['ga_type'] == 'sht' OR $item_temp[0]['ga_type'] == 'pez' OR $item_temp[0]['ga_type'] == 'd_g' OR $item_temp[0]['ga_type'] == 'y_c' OR $item_temp[0]['ga_type'] == 'r_c')){
				if ($db->delElem(DB_T_PREFIX."games_actions", " ga_id = '".$ga_id."' LIMIT 1")) {
					if ($item_temp[0]['ga_type'] == 'pop' OR $item_temp[0]['ga_type'] == 'sht' OR $item_temp[0]['ga_type'] == 'pez' OR $item_temp[0]['ga_type'] == 'd_g') {
						// �������� �����
						include_once('classes/admin/admin_games.php');
						$games = new games;
						$games->updatePointsGame($item_temp[0]['ga_g_id'], $item_temp[0]['ga_t_id']);
					}
					$echo_ .= '1';
				}
				//echo $echo_;
				echo iconv("cp1251", "UTF-8", $echo_);
			}
		}
	}
	if ($_GET['do']=='edit'){
		$ga_id = intval($_GET['ga_id']);
		if($ga_id>0){
			$min = intval($_GET['min']);
			$item_temp = $db->selectElem(DB_T_PREFIX."games_actions","ga_type, ga_min","ga_id = '".$ga_id."' AND ga_is_delete = 'no' LIMIT 1");
			if ($item_temp and ($item_temp[0]['ga_type'] == 'pop' OR $item_temp[0]['ga_type'] == 'sht' OR $item_temp[0]['ga_type'] == 'pez' OR $item_temp[0]['ga_type'] == 'd_g' OR $item_temp[0]['ga_type'] == 'y_c' OR $item_temp[0]['ga_type'] == 'r_c') AND $item_temp[0]['ga_min'] !== $min){
				$elems = array(
					"ga_min" => $min,
					"ga_date_add" => 'NOW()',
					"ga_add_author" => $user
				);
				$condition = array(
					"ga_id"=>$ga_id
				);
				if ($db->updateElem(DB_T_PREFIX."games_actions", $elems, $condition)) {
					$echo_ .= '<a href="javascript: void(0);" onclick="javascript: game_action_edit('.$ga_id.', '.$min.')" >';
					if ($min > 0) $echo_ .= $min.' �.';
					else $echo_ .= '+1 ��.';
					$echo_ .= '</a>';
				}
				//echo $echo_;
				echo iconv("cp1251", "UTF-8", $echo_);
			}
		}
	}
	if ($_GET['do']=='add'){
		$g_id = intval($_GET['g_id']);
		$t_id = intval($_GET['t_id']);
		$st_id = intval($_GET['st_id']);
		
		if($g_id>0 AND $t_id>0 AND $st_id>0){
			$min = intval($_GET['min']);
			$ga_zst_id = 0;
			$ga_zapp_id = 0;
			switch($_GET['action']){
				case 'pop':
					$action = 'pop';
					break;
				case 'sht':
					$action = 'sht';
					break;
				case 'pez':
					$action = 'pez';
					break;
				case 'd_g':
					$action = 'd_g';
					break;
				case 'y_c':
					$action = 'y_c';
					break;
				case 'r_c':
					$action = 'r_c';
					break;
				default :
					$action = '';
			}
			
			if ($action !== '') {
				// ���������� ��������
				$elem = array(
						$g_id,
						$t_id,
						$st_id,
						$action,
						$min,
						'NOW()',
						$user,
						'no',
						$ga_zst_id,
						$ga_zapp_id
				);
				if ($db->addElem(DB_T_PREFIX."games_actions", $elem)) {
					if ($action == 'pop' or  $action == 'sht' or $action == 'pez' or $action == 'd_g') {
						// �������� �����
						include_once('classes/admin/admin_games.php');
						$games = new games;
						$games->updatePointsGame($g_id, $t_id);
					}
					// ������� ����������� ��������
					$res = $db->selectElem(DB_T_PREFIX."games_actions","ga_id, ga_min","ga_g_id='".$g_id."' AND ga_t_id = '".$t_id."' AND ga_st_id = '".$st_id."' AND ga_type = '".$action."' ORDER BY ga_min ASC, ga_id ASC");
					if ($res){
						foreach ($res as $item){
							$echo_ .= '<div id="'.$item['ga_id'].'" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit('.$item['ga_id'].', '.$item['ga_min'].')" >';
							if ($item['ga_min']>0) $echo_ .= $item['ga_min']." �.";
							else $echo_ .= "+1 ��.";
							$echo_ .= '</a></div>';
						}
						$echo_ .= '<div id="'.$st_id.'_'.$action.'" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action('.$g_id.', '.$t_id.', '.$st_id.', \''.$action.'\')"  id="add">&nbsp;</a></div>';
						//echo $echo_;
						echo iconv("cp1251", "UTF-8", $echo_);
					}
				}
			}
		}
	}

?>