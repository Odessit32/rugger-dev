<?php
include_once('../classes/config.php');
include_once('../classes/DB.php');
include_once('../classes/admin/admin_login.php');

$db = new database;
$db->connect();

// адрес сайта
$sitepath = SERVER."/";
$sitepath .= (SITEPATH != '/') ? SITEPATH.'/' : '' ;

session_start();

if(isset($_SESSION['login_name'])){
	// проверка прав пользователя
    $login = new login;
    $login_info = $login->getLoginSession();
	if (USER_IS_ADMIN != 'yes' && USER_IS_PUBLISHER != 'yes' && !$user->checkAdminRights()) {
        exit;
    }

    $_GET['lang'] = (!empty($_GET['lang']))?$_GET['lang']:'rus';
	switch($_GET['lang']){
		case 'ukr':
			$lang = 'ukr';
			break;
		case 'eng':
			$lang = 'eng';
			break;
		default :
			$lang = 'rus';
	}

	// месяцы
	$m = array(
		"01" => "января",
		"02" => "февраля",
		"03" => "марта",
		"04" => "апреля",
		"05" => "мая",
		"06" => "июня",
		"07" => "июля",
		"08" => "августа",
		"09" => "сентября",
		"10" => "октября",
		"11" => "ноября",
		"12" => "декабря"
	);

	switch($_GET['action']) {
		case 'file_multi_upload_action': {
            require('../classes/third_party/upload_files_ajax/UploadHandler.php');
            $upload_handler = new UploadHandler();
			break;
		}

		case 'photo_order': {
			$ph_id = intval($_GET['ph_id']);
			if ($ph_id > 0){
				$ph_order = intval($_GET['ph_order']);
				$elems = array(
					"ph_order" => $ph_order
				);
				$condition = array(
					"ph_id"=>$ph_id
				);
				if ($db->updateElem(DB_T_PREFIX."photos",$elems, $condition)) $echo_ .= 'сохранено';
				else $echo_ .= 'ошибка';
				echo $echo_;
			}
			break;
		}

		case 'add_pub_st': {
			$pub_id = intval($_GET['pub_id']);
			$st_id = intval($_GET['st_id']);
			if ($_GET['type'] == 'a') $type = 'author';
			elseif ($_GET['type'] == 'r') $type = 'reviewer';
			elseif ($_GET['type'] == 'c') $type = 'compiler';
			elseif ($_GET['type'] == 'e') $type = 'editor';
			elseif ($_GET['type'] == 'me') $type = 'maineditor';
			elseif ($_GET['type'] == 'ec') $type = 'editorcompiler';
			else $type = false;

			if ($pub_id > 0 AND $st_id > 0 AND $type){
				$temp = $db->selectElem(DB_T_PREFIX."pub_staff","ps_id","
					ps_st_id = '".$st_id."'
					AND ps_pub_id = '".$pub_id."'
					AND ps_type = '".$type."'
					LIMIT 1");
				if (!$temp){
					$elem = array(
						$st_id,
						$pub_id,
						$type
					);
					$db->addElem(DB_T_PREFIX."pub_staff", $elem);

					$temp = $db->selectElem(DB_T_PREFIX."pub_staff, ".DB_T_PREFIX."staff","ps_id, ps_pub_id, ps_type, st_id as id, st_name_ru, st_family_ru, st_surname_ru","st_id = ps_st_id AND ps_type = '$type' AND ps_pub_id = '$pub_id'");
					if ($temp) {
						foreach ($temp as $item) {
							$q_st_ne .= " AND st_id != '".$item['id']."' ";
							$echo_ .= '<nobr>'.$item['st_family_ru'].' '.$item['st_name_ru'].' '.$item['st_surname_ru'].' <a href="javascript:void(0)" onclick="javascript:delPub(\''.$item['ps_id'].'\', \''.$item['ps_pub_id'].'\', \''.$_GET['type'].'\');"><img src="images/delete.gif" border="0"></a></nobr><br>';
						}
					}
					$list = $db->selectElem(DB_T_PREFIX."staff","st_id as id, st_name_ru, st_family_ru, st_surname_ru","st_is_active = 'yes' $q_st_ne ORDER BY st_name_ru ASC, st_family_ru ASC, st_family_ru ASC ");
					if ($list) {
						$echo_ .= '<nobr><select name="pub_st_'.$_GET['type'].'_id" id="input100"><option value="0">----- добавить -----</option>';
						foreach ($list as $item) $echo_ .= '<option value="'.$item['id'].'">'.$item['st_family_ru'].' '.$item['st_name_ru'].' '.$item['st_surname_ru'].'</option>';
						$echo_ .= '</select><a href="javascript:void(0)" onclick="javascript:addPub(\''.$_GET['type'].'\');"><img src="images/yes.jpg" border="0"></a></nobr>';
					}
				}
				echo $echo_;
			}
			break;
		}

		case 'del_pub_st': {
			$pub_id = intval($_GET['pub_id']);
			$ps_id = intval($_GET['ps_id']);
			if ($_GET['type'] == 'a') $type = 'author';
			elseif ($_GET['type'] == 'r') $type = 'reviewer';
			elseif ($_GET['type'] == 'c') $type = 'compiler';
			elseif ($_GET['type'] == 'e') $type = 'editor';
			elseif ($_GET['type'] == 'me') $type = 'maineditor';
			elseif ($_GET['type'] == 'ec') $type = 'editorcompiler';
			else $type = false;

			if ($pub_id > 0 AND $ps_id > 0 AND $type){
				$temp = $db->selectElem(DB_T_PREFIX."pub_staff","ps_id","
					ps_id = '".$ps_id."'
					AND ps_pub_id = '".$pub_id."'
					AND ps_type = '".$type."'
					LIMIT 1");
				if ($temp){
					$db->delElem(DB_T_PREFIX."pub_staff", "ps_id='$ps_id' AND ps_pub_id = '".$pub_id."' AND ps_type = '".$type."'");

					$temp = $db->selectElem(DB_T_PREFIX."pub_staff, ".DB_T_PREFIX."staff","ps_id, ps_pub_id, ps_type, st_id as id, st_name_ru, st_family_ru, st_surname_ru","st_id = ps_st_id AND ps_type = '$type' AND ps_pub_id = '$pub_id'");
					if ($temp) {
						foreach ($temp as $item) {
							$q_st_ne .= " AND st_id != '".$item['id']."' ";
							$echo_ .= '<nobr>'.$item['st_family_ru'].' '.$item['st_name_ru'].' '.$item['st_surname_ru'].' <a href="javascript:void(0)" onclick="javascript:delPub(\''.$item['ps_id'].'\', \''.$item['ps_pub_id'].'\', \''.$_GET['type'].'\');"><img src="images/delete.gif" border="0"></a></nobr><br>';
						}
					}
					$list = $db->selectElem(DB_T_PREFIX."staff","st_id as id, st_name_ru, st_family_ru, st_surname_ru","st_is_active = 'yes' $q_st_ne ORDER BY st_name_ru ASC, st_family_ru ASC, st_family_ru ASC ");
					if ($list) {
						$echo_ .= '<nobr><select name="pub_st_'.$_GET['type'].'_id" id="input100"><option value="0">----- добавить -----</option>';
						foreach ($list as $item) $echo_ .= '<option value="'.$item['id'].'">'.$item['st_family_ru'].' '.$item['st_name_ru'].' '.$item['st_surname_ru'].'</option>';
						$echo_ .= '</select><a href="javascript:void(0)" onclick="javascript:addPub(\''.$_GET['type'].'\');"><img src="images/yes.jpg" border="0"></a></nobr>';
					}
				}
				echo $echo_;
			}
			break;
		}

        case 'get_games_by_date': {
            $echo_ = '<option value="">----------------</option>';
            $date_show = explode('.',$_GET['date']);
            $date_q = intval($date_show[0]).'.'.intval($date_show[1]).'.'.intval($date_show[2]);
			if (!empty($date_show)){
                include_once('../classes/admin/admin_live.php');
                $live = new live;
                $games = $live->getGameListDay($date_q);
				if ($games){
                    foreach ($games as $item) {
                        $echo_ .= '<option value="'.$item['id'].'">'.$item['title'].'</option>';
                    }
				}
				echo $echo_;
			}
			break;
		}

        case 'get_staff_by_name': {
            $term = $_GET['term'];
            $staff = array();
            if (!empty($term) && strlen($term) > 1){
                $temp = $db->selectElem(DB_T_PREFIX."staff",
                    "st_id as id,
                    st_name_ru,
                    st_name_ua,
                    st_name_en,
                    st_family_ru,
                    st_family_ua,
                    st_family_en",
					"st_is_active = 'yes' AND (
					    st_name_ru LIKE '".$term."%' OR
					    st_name_ua LIKE '".$term."%' OR
					    st_name_en LIKE '".$term."%' OR
					    st_family_ru LIKE '".$term."%' OR
					    st_family_ua LIKE '".$term."%' OR
					    st_family_en LIKE '".$term."%'
					)
					LIMIT 20");
                if (!empty($temp)) {
                    foreach ($temp as $item){
                        $name = '';
                        if (!empty($item['st_name_ru'])){
                            $name .= $item['st_name_ru'];
                        } elseif (!empty($item['st_name_ua'])){
                            $name .= $item['st_name_ua'];
                        } elseif (!empty($item['st_name_en'])){
                            $name .= $item['st_name_en'];
                        }
                        $name .= ' ';
                        if (!empty($item['st_family_ru'])){
                            $name .= $item['st_family_ru'];
                        } elseif (!empty($item['st_family_ua'])){
                            $name .= $item['st_family_ua'];
                        } elseif (!empty($item['st_family_en'])){
                            $name .= $item['st_family_en'];
                        }
                        $staff[] = array(
                            'id' => $item['id'],
                            'value' => trim($name)
                        );
                    }
                }
            }
            echo json_encode($staff, JSON_UNESCAPED_UNICODE);
			break;
		}

        case 'get_teams_by_name': {
            $term = $_GET['term'];
            $teams = array();
            if (!empty($term) && strlen($term) > 1){
                $temp = $db->selectElem(DB_T_PREFIX."team",
                    "t_id as id, t_title_ru, t_title_ua, t_title_en",
                    "t_is_active = 'yes' AND (
                        t_title_ru LIKE '".$term."%' OR
                        t_title_ua LIKE '".$term."%' OR
                        t_title_en LIKE '".$term."%'
                    )
                    ORDER BY t_title_ru ASC
                    LIMIT 20");
                if (!empty($temp)) {
                    foreach ($temp as $item){
                        $title = !empty($item['t_title_ru']) ? $item['t_title_ru'] : (!empty($item['t_title_ua']) ? $item['t_title_ua'] : $item['t_title_en']);
                        $teams[] = array(
                            'id' => $item['id'],
                            'value' => trim($title)
                        );
                    }
                }
            }
            echo json_encode($teams, JSON_UNESCAPED_UNICODE);
            break;
        }
	}
}
exit();
