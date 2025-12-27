<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class games{
    private $hdl;
    public function __construct(){
        $this->hdl = new database();
    }

    public function getChampionshipList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship,
			        ".DB_T_PREFIX."championship_group",
            "   ch_id,
                        ch_title_ru AS title,
                        chg_title_ru AS chg_title",
            "   ch_chg_id = chg_id AND
                        ch_is_done = 'no'
                        ORDER BY chg_order DESC,
                        chg_title_ru ASC,
                        ch_order DESC,
                        ch_title_ru");
        if ($temp){
            foreach ($temp as &$item)
                $item['title'] = stripslashes($item['title']);
        }
        return $temp;
    }

    // Игры ///////////////////////////////////////////////////////////////////////////////////////

    public function deleteGame($id = 0){
        $id = intval($id);
        if ($id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","g_id, g_is_done"," g_id = '$id' LIMIT 1");
        if (!$temp or $temp[0]['g_id']<1 or $temp[0]['g_is_done'] == 'yes') return false;

        if ($this->hdl->delElem(DB_T_PREFIX."games", "g_id = '$id' LIMIT 1")) {
            $this->hdl->delElem(DB_T_PREFIX."games_actions", "ga_g_id = '$id'");
            $this->hdl->delElem(DB_T_PREFIX."connection_g_st", "cngst_g_id = '$id'");
            return true;
        } else return false;
    }

    public function getGamesAction($g_id){
        $q_extra = '';
        $g_id = intval($g_id);
        $res = array();
        $res_temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$g_id' LIMIT 1");
        $team_temp = $res_temp[0];
        /*
        if ($team_temp['g_is_done'] == 'no'){
            $elems = array(
                "g_is_done" => 'yes',
                "g_datetime_edit" => 'NOW()',
                "g_author" => USER_ID
            );
            $condition = array(
                "g_id"=>$g_id
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) return true;
        }
        */
        // хозяева
        if ($team_temp['g_owner_t_id']>0) $res_temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","*","ga_g_id = '$g_id'
								AND ga_t_id = '".$team_temp['g_owner_t_id']."' 
								AND ga_is_delete = 'no' 
								ORDER BY ga_st_id ASC, 
										ga_min ASC");
        if ($res_temp)
            foreach ($res_temp as $item)
                $res['owner'][$item['ga_st_id']][$item['ga_type']][] = array("ga_min" => $item['ga_min'], "ga_id" => $item['ga_id']);
        // гости
        if ($team_temp['g_guest_t_id']>0) $res_temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","*","ga_g_id = '$g_id'
								AND ga_t_id = '".$team_temp['g_guest_t_id']."' 
								AND ga_is_delete = 'no' 
								ORDER BY ga_st_id ASC, 
										ga_min ASC");
        if ($res_temp) {
            foreach ($res_temp as $item) {
                $res['guest'][$item['ga_st_id']][$item['ga_type']][] = array("ga_min" => $item['ga_min'], "ga_id" => $item['ga_id']);
            }
        }
        return $res;
    }

    public function clearGameReport($g_id = 0, $t_id = 0){ // очистить подробный отчет об игре
        $g_id = intval($g_id);
        $t_id = intval($t_id);
        if ($g_id>0 and $t_id>0)
            if ($this->hdl->delElem(DB_T_PREFIX."games_actions", "ga_g_id='$g_id' AND ga_t_id = '$t_id'")) return true;
            else return false;
    }

    public function editGameAction($post){
        $item_temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","*","ga_id = '".intval($post['ga_id'])."' LIMIT 1");
        if ($item_temp)
            if ($post['a_id'] == 'pop' or $post['a_id'] == 'sht' or $post['a_id'] == 'pez' or $post['a_id'] == 'd_g' or $post['a_id'] == 'y_c' or $post['a_id'] == 'r_c'){

                if (intval($post['min']) > 0){
                    $elems = array(
                        "ga_min" => intval($post['min']),
                        "ga_date_add" => 'NOW()',
                        "ga_add_author" => USER_ID
                    );
                    $condition = array(
                        "ga_id"=>intval($post['ga_id'])
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) {
                        $this->updatePointsGame($item_temp[0]['ga_g_id']);
                        return true;
                    } else return false;
                } else {
                    $elems = array(
                        "ga_is_delete" => 'yes',
                        "ga_date_add" => 'NOW()',
                        "ga_add_author" => USER_ID
                    );
                    $condition = array(
                        "ga_id"=>intval($post['ga_id'])
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) {
                        $this->updatePointsGame($item_temp[0]['ga_g_id']);
                        return true;
                    } else return false;
                }
            } elseif ($post['a_id'] == 'zam_out') {
                if (intval($post['min']) > 0){
                    $elems = array(
                        "ga_min" => intval($post['min']),
                        "ga_date_add" => 'NOW()',
                        "ga_add_author" => USER_ID
                    );
                    $condition = array(
                        "ga_id"=>intval($post['ga_id']),
                        "ga_type" => 'zam_out'
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) {
                        $elems = array(
                            "ga_min" => intval($post['min']),
                            "ga_date_add" => 'NOW()',
                            "ga_add_author" => USER_ID
                        );
                        $condition = array(
                            "ga_zst_id" => $item_temp[0]['ga_st_id'],
                            "ga_g_id" => $item_temp[0]['ga_g_id'],
                            "ga_t_id" => $item_temp[0]['ga_t_id'],
                            "ga_type" => 'zam_in',
                            "ga_is_delete" => 'no'
                        );
                        if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) return true;
                        else return false;
                    } else return false;
                } else {
                    $elems = array(
                        "ga_is_delete" => 'yes',
                        "ga_date_add" => 'NOW()',
                        "ga_add_author" => USER_ID
                    );
                    $condition = array(
                        "ga_id"=>intval($post['ga_id']),
                        "ga_type" => 'zam_out'
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) {
                        $item_temp_del = $this->hdl->selectElem(DB_T_PREFIX."games_actions","*","ga_zst_id = '".intval($item_temp[0]['ga_st_id'])."' AND ga_is_delete = 'no' AND ga_type = 'zam_in' LIMIT 1");
                        if ($item_temp_del){
                            $elems = array(
                                "ga_is_delete" => 'yes',
                                "ga_date_add" => 'NOW()',
                                "ga_add_author" => USER_ID
                            );
                            $condition = array(
                                "ga_st_id" => $item_temp_del[0]['ga_st_id'],
                                "ga_g_id" => $item_temp_del[0]['ga_g_id'],
                                "ga_t_id" => $item_temp_del[0]['ga_t_id'],
                                "ga_is_delete" => 'no'
                            );
                            if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) return true;
                        } else return false;
                    } else return false;
                }
            } elseif ($post['a_id'] == 'zam_in') {
                $elems = array(
                    "ga_st_id" => intval($post['st_id']),
                    "ga_date_add" => 'NOW()',
                    "ga_add_author" => USER_ID
                );
                $condition = array(
                    "ga_st_id" => $item_temp[0]['ga_st_id'],
                    "ga_g_id" => $item_temp[0]['ga_g_id'],
                    "ga_t_id" => $item_temp[0]['ga_t_id'],
                    "ga_is_delete" => 'no'
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."games_actions",$elems, $condition)) return true;
                else return false;
            } else return false;

    }

    public function addGameAction($post){
        $ga_zst_id = $ga_zapp_id = '';
        switch ($post['a_id']) {
            case 'zam_out':
                $type = $post['a_id'];
                $ga_zst_id = intval($post['st_id']);
                break;
            case 'zam_in':
                $type = $post['a_id'];
                $ga_zst_id = intval($post['zst_id']);
                $ga_zapp_id = intval($post['zapp_id']);
                break;
            case 'pop':
                $type = $post['a_id'];
                break;
            case 'sht':
                $type = $post['a_id'];
                break;
            case 'pez':
                $type = $post['a_id'];
                break;
            case 'd_g':
                $type = $post['a_id'];
                break;
            case 'y_c':
                $type = $post['a_id'];
                break;
            case 'r_c':
                $type = $post['a_id'];
                break;
            default:
                return false;
        }
        if (intval($post['min']) <1) return false;
        if (intval($post['g_id']) <1) return false;
        if (intval($post['t_id']) <1) return false;
        if (intval($post['st_id']) <1) return false;
        $elem = array(
            intval($post['g_id']),
            intval($post['t_id']),
            intval($post['st_id']),
            $type,
            intval($post['min']),
            'NOW()',
            USER_ID,
            'no',
            $ga_zst_id,
            $ga_zapp_id
        );
        if ($this->hdl->addElem(DB_T_PREFIX."games_actions", $elem)) {
            if ($type == 'pop' or  $type == 'sht' or $type == 'pez' or $type == 'd_g') $this->updatePointsGame($post['g_id']);
            return true;
        } else return false;
    }

    public function updatePointsGame($g_id = 0){
        $g_id = intval($g_id);
        $elems = array();
        if ($g_id < 1) {
            return false;
        }
        $elems['g_owner_bonus_1'] = 'no';
        $elems['g_guest_bonus_1'] = 'no';
        $game = $this->hdl->selectElem(DB_T_PREFIX."games","g_owner_t_id, g_guest_t_id, g_owner_bonus_1, g_guest_bonus_1, g_ft_time, g_ch_id","g_id = '$g_id' LIMIT 1");
        if ($game){
            $game = $game[0];
            $championship_settings = array();
            $championship = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_id, ch_settings","ch_id = '".$game['g_ch_id']."' LIMIT 1", true);
            if (!empty($championship)) {
                $championship_settings = unserialize($this->_fix_serialized_string($championship[0]['ch_settings']));
            }
            $g_pop = $o_pop = 0;
            $team_ids = array($game['g_owner_t_id'], $game['g_guest_t_id']);
            foreach ($team_ids as $team_id){
                $points = $points_ft = $pop = 0;
                $res_temp = $this->hdl->selectElem(DB_T_PREFIX . "games_actions", "ga_type, ga_min", "ga_g_id = '$g_id'
									AND ga_t_id = '" . $team_id . "'
									AND ga_is_delete = 'no' 
									AND (ga_type = 'pop' OR ga_type = 'sht' OR ga_type = 'pez' OR ga_type = 'd_g')
									ORDER BY ga_min ASC,
											 ga_type ASC");
                if ($res_temp) {
                    foreach ($res_temp as $item) {
                        switch ($item['ga_type']) {
                            case 'pop':
                                $points = $points + 5;
                                $pop++;
                                break;
                            case 'sht':
                                $points = $points + 3;
                                break;
                            case 'pez':
                                $points = $points + 2;
                                break;
                            case 'd_g':
                                $points = $points + 3;
                                break;
                        }
                        if (!empty($game['g_ft_time']) && $item['ga_min'] <= $game['g_ft_time']) {
                            $points_ft = $points;
                        }
                    }
                }
                // Сохранение очков в игре
                if ($game['g_owner_t_id'] == $team_id) {
                    $elems['g_owner_points'] = $points;
                    $elems['g_owner_ft_points'] = $points_ft;
                    if ( empty($championship_settings['bonus_1_type']) && $pop >= 4 ) {
                        $elems['g_owner_bonus_1'] = 'yes';
                    }
                    $o_pop = $pop;
                }
                if ($game['g_guest_t_id'] == $team_id) {
                    $elems['g_guest_points'] = $points;
                    $elems['g_guest_ft_points'] = $points_ft;
                    if ( empty($championship_settings['bonus_1_type']) && $pop >= 4 ) {
                        $elems['g_guest_bonus_1'] = 'yes';
                    }
                    $g_pop = $pop;
                }
            }
            if ( !empty($championship_settings['bonus_1_type']) && $championship_settings['bonus_1_type'] == 1 ) {
                if ($o_pop - $g_pop >= 3 && $o_pop >= 4) {
                    $elems['g_owner_bonus_1'] = 'yes';
                } elseif ($g_pop - $o_pop >= 3 && $g_pop >= 4) {
                    $elems['g_guest_bonus_1'] = 'yes';
                }
            }
            $elems['g_datetime_edit'] = 'NOW()';
            $elems['g_author'] = (defined('USER_ID'))?USER_ID:0;
            $elems['g_owner_tehwin'] = 'no';
            $elems['g_guest_tehwin'] = 'no';
            $condition = array(
                "g_id" => $g_id
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) return true;
            else return false;
        }
        return false;
    }

    public function getGamesTeamStaff($g_id){
        $q_extra = '';
        $g_id = intval($g_id);
        $res_temp_z = array();
        $res_temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$g_id' LIMIT 1");
        $team_temp = $res_temp[0];
        // хозяева основной состав
        if ($team_temp['g_owner_t_id']>0) $res_temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","cngst_g_id = '$g_id'
								AND cngst_t_id = '".$team_temp['g_owner_t_id']."' 
								AND cngst_st_id = st_id 
								AND cngst_app_id = app_id 
								AND cngst_is_delete = 'no' 
								AND app_type = 'player'
								AND cngst_type = 'main'
								ORDER BY app_order DESC, 
										st_family_ru ASC, 
										st_name_ru ASC, 
										st_surname_ru ASC");
        $res['owner']['main'] = $res_temp;
        // хозяева замена
        $res_temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","ga_g_id = '$g_id'
								AND ga_t_id = '".$team_temp['g_owner_t_id']."' 
								AND ga_type = 'zam_in'
								AND ga_st_id = st_id 
								AND ga_zapp_id = app_id 
								AND ga_is_delete = 'no' 
								AND app_type = 'player'
								ORDER BY app_order DESC, 
										st_family_ru ASC, 
										st_name_ru ASC, 
										st_surname_ru ASC");
        $res_temp_z = array();
        if ($res_temp) foreach ($res_temp as $item)
            $res_temp_z[$item['ga_zst_id']] = $item;
        $res['owner']['zam'] = $res_temp_z;
        unset($res_temp_z);
        // хозяева остальные игроки на замену
        if ($res['owner']['main'])
            foreach ($res['owner']['main'] as $item)
                $q_extra .= "AND cngst_st_id != '".$item['st_id']."' ";
        if ($res['owner']['zam'])
            foreach ($res['owner']['zam'] as $item)
                $q_extra .= "AND cngst_st_id != '".$item['st_id']."' ";
        $res_temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","cngst_g_id = '$g_id'
								AND cngst_t_id = '".$team_temp['g_owner_t_id']."' 
								AND cngst_st_id = st_id 
								AND cngst_app_id = app_id 
								AND cngst_is_delete = 'no' 
								AND app_type = 'player'
								AND cngst_type = 'reserve'
								$q_extra
								ORDER BY app_order DESC,
										st_family_ru ASC, 
										st_name_ru ASC, 
										st_surname_ru ASC");
        $res['owner']['reserve'] = $res_temp;
        $q_extra = '';
        // гости основной состав
        if ($team_temp['g_guest_t_id']>0) $res_temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","cngst_g_id = '$g_id'
								AND cngst_t_id = '".$team_temp['g_guest_t_id']."' 
								AND cngst_st_id = st_id 
								AND cngst_app_id = app_id 
								AND cngst_is_delete = 'no' 
								AND app_type = 'player'
								AND cngst_type = 'main'
								ORDER BY app_order DESC,
										st_family_ru ASC, 
										st_name_ru ASC, 
										st_surname_ru ASC");
        $res['guest']['main'] = $res_temp;
        // хозяева замена
        $res_temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","ga_g_id = '$g_id'
								AND ga_t_id = '".$team_temp['g_guest_t_id']."' 
								AND ga_type = 'zam_in'
								AND ga_st_id = st_id 
								AND ga_zapp_id = app_id 
								AND ga_is_delete = 'no' 
								AND app_type = 'player'
								ORDER BY app_order DESC,
										st_family_ru ASC, 
										st_name_ru ASC, 
										st_surname_ru ASC");
        $res_temp_z = array();
        if ($res_temp) foreach ($res_temp as $item)
            $res_temp_z[$item['ga_zst_id']] = $item;
        $res['guest']['zam'] = $res_temp_z;
        unset($res_temp_z);
        // гости остальные игроки на замену
        if ($res['guest']['main'])
            foreach ($res['guest']['main'] as $item)
                $q_extra .= "AND cngst_st_id != '".$item['st_id']."' ";
        //echo $q_extra;
        if ($res['guest']['zam'])
            foreach ($res['guest']['zam'] as $item)
                $q_extra .= "AND cngst_st_id != '".$item['st_id']."' ";

        $res_temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","cngst_g_id = '$g_id'
								AND cngst_t_id = '".$team_temp['g_guest_t_id']."' 
								AND cngst_st_id = st_id 
								AND cngst_app_id = app_id 
								AND cngst_is_delete = 'no' 
								AND app_type = 'player'
								AND cngst_type = 'reserve'
								$q_extra
								ORDER BY app_order DESC,
										st_family_ru ASC, 
										st_name_ru ASC, 
										st_surname_ru ASC");
        $res['guest']['reserve'] = $res_temp;
        return $res;
    }

    public function updateGames($post){ // редактирование Игры
        if($post['g_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['g_is_schedule_time']==true) $is_time ='yes';
        else $is_time = 'no';
        if ($post['g_date_day'] != '' and $post['g_date_day']>0 and $post['g_date_month'] != '' and $post['g_date_month']>0 and $post['g_date_year'] != '' and $post['g_date_year']>0) {
            $post['g_date_day'] = intval($post['g_date_day']);
            $post['g_date_month'] = intval($post['g_date_month']);
            $post['g_date_year'] = intval($post['g_date_year']);
            $post['g_date_hour'] = intval($post['g_date_hour']);
            $post['g_date_minute'] = intval($post['g_date_minute']);
            if ($post['g_date_day'] < 10) $post['g_date_day'] = '0'.$post['g_date_day'];
            if ($post['g_date_month'] < 10) $post['g_date_month'] = '0'.$post['g_date_month'];
            if ($post['g_date_hour'] < 10) $post['g_date_hour'] = '0'.$post['g_date_hour'];
            if ($post['g_date_minute'] < 10) $post['g_date_minute'] = '0'.$post['g_date_minute'];
            $g_date_schedule = $post['g_date_year']."-".$post['g_date_month']."-".$post['g_date_day']." ".$post['g_date_hour'].":".$post['g_date_minute'].":00";
        }
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "g_is_active" => $is_active,
            "g_is_schedule_time" => $is_time,
            "g_datetime_edit" => 'NOW()',
            "g_author" => USER_ID,
            "g_date_schedule" => $g_date_schedule
        );
        $condition = array(
            "g_id"=>$post['g_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) return true;
        else return false;
    }

    public function getGamesList($page=1, $perpage=10, $ch_id=0){
        $extra_q = '';
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage<1) $perpage = 10;
        $page = $perpage*$page;
        $ch_id = intval($ch_id);
        if ($ch_id>0) $q_ch = " AND g_ch_id = '$ch_id' ";
        else $q_ch = '';
        //if ($gallery > 0) $extra_q = " AND n_nc_id = '$gallery' ";
        $res = $this->hdl->selectElem(DB_T_PREFIX."games, ".DB_T_PREFIX."championship, ".DB_T_PREFIX."competitions, ".DB_T_PREFIX."championship_group",
            "	g_id,
						g_ch_id,
						g_cp_id,
						g_owner_t_id,
						g_owner_t_comment,
						g_guest_t_id,
						g_guest_t_comment,
						g_datetime_add,
						g_date_schedule,
						g_is_done,
						g_round,
						g_is_done,
						g_owner_points,
						g_guest_points,
						cp_tour,
						cp_title_ru,
						ch_title_ru,
						chg_title_ru,
						ch_id,
						ch_chc_id	",
            "	g_ch_id = ch_id AND
						g_cp_id = cp_id AND
						ch_chg_id = chg_id
						$q_ch
						GROUP BY g_id
						ORDER BY g_date_schedule DESC, g_id DESC LIMIT $page, $perpage");
        $q_temp = '';
        if ($res) foreach ($res as $item) {
            if ($item['g_owner_t_id']>0) $q_temp .= " OR t_id = '".$item['g_owner_t_id']."'";
            if ($item['g_guest_t_id']>0) $q_temp .= " OR t_id = '".$item['g_guest_t_id']."'";
        }
        $q_temp = substr($q_temp, 4);
        if ($q_temp != '') $res_team = $this->hdl->selectElem(DB_T_PREFIX."team","t_id, t_title_ru","$q_temp ORDER BY t_id ASC");
        if ($res_team) foreach ($res_team as $item)
            $team[$item['t_id']] = $item['t_title_ru'];
        for ($i=0;$i<count($res); $i++){
            if ($res[$i]['g_owner_t_id']>0) $res[$i]['g_owner_t_title'] = $team[$res[$i]['g_owner_t_id']];
            elseif ($res[$i]['g_owner_t_comment'] != '') {
                $t_comment = array();
                $t_comment = explode("-", $res[$i]['g_owner_t_comment']);
                $res_cp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_title_ru","cp_id = '".$t_comment[1]."' LIMIT 1");
                $res[$i]['g_owner_t_title'] = $t_comment[0]." место «".$res_cp[0]['cp_title_ru']."»";
            }
            if ($res[$i]['g_guest_t_id']>0) $res[$i]['g_guest_t_title'] = $team[$res[$i]['g_guest_t_id']];
            elseif ($res[$i]['g_guest_t_comment'] != '') {
                $t_comment = array();
                $t_comment = explode("-", $res[$i]['g_guest_t_comment']);
                $res_cp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_title_ru","cp_id = '".$t_comment[1]."' LIMIT 1");
                $res[$i]['g_guest_t_title'] = $t_comment[0]." место «".$res_cp[0]['cp_title_ru']."»";
            }
        }
        return $res;
    }

    public function getGamesPages($page=1, $perpage=10, $ch_id=0){
        $ch_id = intval($ch_id);
        if ($ch_id>0) $q_ch = " AND g_ch_id = '$ch_id' ";
        else $q_ch = '';
        $perpage = intval($perpage);
        if ($perpage<1) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","COUNT(*) as C_N","g_is_active='yes' $q_ch");
        $c_pages = ceil($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<$c_pages; $i++){
                $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                //$pages[9] = "...";
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                //$pages[$page-6] = "...";
                //if ($page+4 <= $c_pages) $pages[$page+4] = "...";
            }
        }
        return $pages;
    }

    public function getGamesItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."games, ".DB_T_PREFIX."championship",DB_T_PREFIX."games.*, ".DB_T_PREFIX."championship.ch_p_tehwin","g_id=$item LIMIT 1");
            if ($temp[0]['g_owner_t_id']>0) {
                $res_t = $this->hdl->selectElem(DB_T_PREFIX."team","t_title_ru","t_id = '".$temp[0]['g_owner_t_id']."' LIMIT 1");
                $temp[0]['g_owner_t_title'] = $res_t[0]['t_title_ru'];
            } elseif ($temp[0]['g_owner_t_comment'] != '') {
                $t_comment = array();
                $t_comment = explode("-", $temp[0]['g_owner_t_comment']);
                $res_cp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_title_ru","cp_id = '".$t_comment[1]."' LIMIT 1");
                $temp[0]['g_owner_t_title'] = $t_comment[0]." место «".$res_cp[0]['cp_title_ru']."»";
            }
            if ($temp[0]['g_guest_t_id']>0) {
                $res_t = $this->hdl->selectElem(DB_T_PREFIX."team","t_title_ru","t_id = '".$temp[0]['g_guest_t_id']."' LIMIT 1");
                $temp[0]['g_guest_t_title'] = $res_t[0]['t_title_ru'];
            } elseif ($temp[0]['g_guest_t_comment'] != '') {
                $t_comment = array();
                $t_comment = explode("-", $temp[0]['g_guest_t_comment']);
                $res_cp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_title_ru","cp_id = '".$t_comment[1]."' LIMIT 1");
                $temp[0]['g_guest_t_title'] = $t_comment[0]." место «".$res_cp[0]['cp_title_ru']."»";
            }

            $temp[0]['g_description_ru'] = stripcslashes($temp[0]['g_description_ru']);
            $temp[0]['g_description_ua'] = stripcslashes($temp[0]['g_description_ua']);
            $temp[0]['g_description_en'] = stripcslashes($temp[0]['g_description_en']);
            $temp[0]['g_text_ru'] = stripcslashes($temp[0]['g_text_ru']);
            $temp[0]['g_text_ua'] = stripcslashes($temp[0]['g_text_ua']);
            $temp[0]['g_text_en'] = stripcslashes($temp[0]['g_text_en']);
            if(!empty($temp[0]['g_info'])) {
                $temp[0]['g_info'] = json_decode($temp[0]['g_info'], true);
            } else {
                $temp[0]['g_info'] = array();
            }
            return $temp[0];
        } else return false;
    }

    public function saveReport($id = 0, $re_count_points = false){ // сохранение отчета
        $id = intval($id);
        if ($id>0){
            $game_temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$id' LIMIT 1");
            if ($game_temp){
                $game_temp = $game_temp[0];
                if (!empty($re_count_points)){
                    $this->updatePointsGame($id);
                }
                $elems = array(
                    "g_datetime_edit" => 'NOW()',
                    "g_author" => USER_ID,
                    "g_is_done" => 'yes'
                );
                if ($game_temp['g_done_datetime'] == '0000-00-00 00:00:00') $elems['g_done_datetime'] = 'NOW()';
                $condition = array(
                    "g_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) {
                    // закрыть соревнование
                    $this->updateCompetition($game_temp['g_cp_id']);
                    return true;
                }
            }
        }
        return false;
    }

    public function returnReport($id = 0){ // возвращение редактирования отчета
        $id = intval($id);
        if ($id>0){
            $game_temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$id' LIMIT 1");
            if ($game_temp){
                $game_temp = $game_temp[0];
                $elems = array(
                    "g_datetime_edit" => 'NOW()',
                    "g_author" => USER_ID,
                    "g_is_done" => 'no'
                );
                $condition = array(
                    "g_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function updateCompetition($id = 0){ // закрыть соревнование
        $id = intval($id);
        if ($id>0){
            $games_temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_cp_id = '$id' AND g_is_done = 'no' LIMIT 1");
            if (!$games_temp){
                $elems = array(
                    "cp_datetime_edit" => 'NOW()',
                    "cp_author" => USER_ID,
                    "cp_is_done" => 'yes'
                );
                $condition = array(
                    "cp_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."competitions",$elems, $condition)) {
                    // настройки чемпионата
                    $championship = $this->hdl->selectElem(DB_T_PREFIX."championship, ".DB_T_PREFIX."competitions"," ch_id, ch_p_win, ch_p_draw, ch_p_loss, ch_p_bonus_1, ch_p_bonus_2, ch_p_bonus_2_diff, ch_p_tehwin "," cp_id = '$id' AND ch_id = cp_ch_id LIMIT 1");
                    if ($championship){
                        $championship = $championship[0];
                        // рейтинг команд в соревновании
                        $team = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team"," t_id "," cntch_is_delete='no' AND cntch_ch_id = '".$championship['ch_id']."' AND cntch_t_id = t_id ORDER BY t_id ASC");
                        $games = $this->hdl->selectElem(DB_T_PREFIX."games"," g_id, g_ch_id, g_cp_id, g_owner_t_id, g_guest_t_id, g_owner_points, g_guest_points, g_owner_tehwin, g_guest_tehwin, g_owner_bonus_1, g_guest_bonus_1 "," g_cp_id = '$id' AND g_is_done = 'yes' AND g_is_active = 'yes' ORDER BY g_owner_t_id ASC, g_guest_t_id ASC");
                        if ($team and $games){
                            for ($i=0; $i<count($team); $i++){
                                $team[$i]['p'] = 0;
                                // рейтинг команд и подсчет очков
                                foreach ($games as $item){
                                    if ($item['g_owner_t_id'] != $item['g_guest_t_id']){
                                        if ($item['g_owner_t_id'] == $team[$i]['t_id']){
                                            if ($item['g_owner_points'] > $item['g_guest_points']) {
                                                $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_win'];
                                            }
                                            if ($item['g_owner_points'] < $item['g_guest_points']) {
                                                $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_loss'];
                                                // бонус <= N очков проигрыш
                                                $bonus_2_diff = !empty($championship['ch_p_bonus_2_diff']) ? intval($championship['ch_p_bonus_2_diff']) : 7;
                                                if (($item['g_guest_points'] - $item['g_owner_points']) <= $bonus_2_diff) $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_2'];
                                            }
                                            if ($item['g_owner_points'] == $item['g_guest_points']) {
                                                $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_draw'];
                                            }
                                            if ($team[$i]['g_owner_bonus_1'] = 'yes') $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_1'];
                                            if ($team[$i]['g_owner_tehwin'] = 'yes') $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_tehwin'];
                                        }
                                        if ($item['g_guest_t_id'] == $team[$i]['t_id']){
                                            if ($item['g_guest_points'] > $item['g_owner_points']) {
                                                $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_win'];
                                            }
                                            if ($item['g_guest_points'] < $item['g_owner_points']) {
                                                $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_loss'];
                                                // бонус <= N очков проигрыш
                                                $bonus_2_diff = !empty($championship['ch_p_bonus_2_diff']) ? intval($championship['ch_p_bonus_2_diff']) : 7;
                                                if (($item['g_owner_points'] - $item['g_guest_points']) <= $bonus_2_diff) $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_2'];
                                            }
                                            if ($item['g_guest_points'] == $item['g_owner_points']) {
                                                $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_draw'];
                                            }
                                            if ($team[$i]['g_guest_bonus_1'] = 'yes') $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_1'];
                                            if ($team[$i]['g_guest_tehwin'] = 'yes') $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_tehwin'];
                                        }
                                    }
                                }
                            }
                            // сортировка команд по очкам
                            for ($i=1; $i<count($team); $i++){
                                for ($j=0; $j<count($team); $j++){
                                    if ($j>0) {
                                        if ($team[$j]['p'] > $p_team['p']) {
                                            $team[$j-1] = $team[$j];
                                            $team[$j] = $p_team;
                                        } elseif ($team[$j]['p'] < $p_team['p']){
                                            $p_team = $team[$j];
                                        }
                                    } else {
                                        $p_team = $team[$j];
                                    }
                                }
                            }
                            ///////////////////////////
                            // запись новых команд в игры др. соревнований ( 1,2,3... места )
                            $games_edit = $this->hdl->selectElem(DB_T_PREFIX."games, ".DB_T_PREFIX."competitions"," g_id, g_owner_t_comment, g_guest_t_comment "," `g_owner_t_comment` like('%-$id') or `g_guest_t_comment` like('%-$id') AND g_is_done = 'no' AND g_cp_id = cp_id AND cp_is_done = 'no' AND g_cp_id != '$id' ORDER BY g_id ASC");
                            if ($games_edit)
                                foreach ($games_edit as $item){
                                    $n_p = substr($item['g_owner_t_comment'], 0, strpos($item['g_owner_t_comment'], '-'));
                                    $cp = substr($item['g_owner_t_comment'], strpos($item['g_owner_t_comment'], '-')+1);
                                    $n_p = $n_p-1;
                                    if ($cp == $id and $team[$n_p]['t_id']>0){
                                        $elems = array(
                                            "g_datetime_edit" => 'NOW()',
                                            "g_author" => USER_ID,
                                            "g_owner_t_id" => $team[$n_p]['t_id']
                                        );
                                        $condition = array( "g_id"=>$item['g_id'] );
                                        $this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition);
                                    }
                                    $n_p = substr($item['g_guest_t_comment'], 0, strpos($item['g_guest_t_comment'], '-'));
                                    $cp = substr($item['g_guest_t_comment'], strpos($item['g_guest_t_comment'], '-')+1);
                                    $n_p = $n_p-1;
                                    if ($cp == $id and $team[$n_p]['t_id']>0){
                                        $elems = array(
                                            "g_datetime_edit" => 'NOW()',
                                            "g_author" => USER_ID,
                                            "g_guest_t_id" => $team[$n_p]['t_id']
                                        );
                                        $condition = array( "g_id"=>$item['g_id'] );
                                        $this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition);
                                    }
                                }
                            ///////////////////////////////////////
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function saveReportShort($post){ // сохранение короткого отчета
        $g_id = intval($post['g_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$g_id' LIMIT 1");
        if ($temp){
            $g_owner_bonus_1 = (!empty($post['g_owner_bonus_1']))?'yes':'no';
            $g_guest_bonus_1 = (!empty($post['g_guest_bonus_1']))?'yes':'no';
            $g_owner_tehwin = (!empty($post['g_owner_tehwin']))?'yes':'no';
            $g_guest_tehwin = (!empty($post['g_guest_tehwin']))?'yes':'no';
            if(!empty($temp['g_info'])) {
                $g_info = json_decode($temp['g_info'], true);
            } else {
                $g_info = json_decode("{}", true);
            }
            $g_info['actions'] = (!empty($post['g_info']['actions']))?$post['g_info']['actions']:array();
            $elems = array(
                "g_datetime_edit" => 'NOW()',
                "g_author" => USER_ID,
                "g_owner_points" => intval($post['g_owner_points']),
                "g_guest_points" => intval($post['g_guest_points']),
                "g_owner_ft_points" => intval($post['g_owner_ft_points']),
                "g_guest_ft_points" => intval($post['g_guest_ft_points']),
                "g_owner_bonus_1" => $g_owner_bonus_1,
                "g_guest_bonus_1" => $g_guest_bonus_1,
                "g_owner_tehwin" => $g_owner_tehwin,
                "g_guest_tehwin" => $g_guest_tehwin,
                "g_info" => json_encode($g_info, JSON_UNESCAPED_UNICODE )
            );
            $condition = array(
                "g_id"=>$g_id
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) {
                if ($temp[0]['g_owner_points'] != intval($post['g_owner_points'])) $this->clearGameReport($g_id, $temp[0]['g_owner_t_id']);
                if ($temp[0]['g_guest_points'] != intval($post['g_guest_points'])) $this->clearGameReport($g_id, $temp[0]['g_guest_t_id']);
                return true;
            } else return false;
        }
    }

    public function saveReportExtra($post){ // сохранение дополнительных очков
        $g_id = intval($post['g_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$g_id' LIMIT 1");
        if ($temp){
            $elems = array(
                "g_datetime_edit" => 'NOW()',
                "g_author" => USER_ID,
                "g_owner_extra_points" => intval($post['g_owner_extra_points']),
                "g_guest_extra_points" => intval($post['g_guest_extra_points']),
                "g_ft_time" => intval($post['g_ft_time'])
            );
            $condition = array(
                "g_id"=>$g_id
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) return true;
            else return false;
        }
    }

    public function saveGameInfo($post){ // сохранение дополнительной информации
        $g_id = intval($post['g_id']);
        if (!empty($post['g_info'])){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '$g_id' LIMIT 1");
            if ($temp){
                $temp = $temp[0];
                if(!empty($temp['g_info'])) {
                    $g_info = json_decode($temp['g_info']);
                } else {
                    $g_info = json_decode("{}");
                }
                foreach ($post['g_info'] as $key=>$item){
                    $g_info->$key = $item;
                }
                foreach ($g_info as &$item_o){
                    $item_o = stripslashes($item_o);
                    if (trim(strip_tags($item_o, '<iframe><object><script><img><embed>')) == '') {
                        $item_o = '';
                    } else {
                        $item_o = addslashes($item_o);
                    }
                }
                $elems = array(
                    "g_datetime_edit" => 'NOW()',
                    "g_author" => USER_ID,
                    "g_info" => json_encode($g_info, JSON_UNESCAPED_UNICODE )
                );
                $condition = array(
                    "g_id"=>$g_id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."games",$elems, $condition)) return true;
            }
        }
        return false;
    }

    // ГАЛЕРЕЯ ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($games_item, &$photos_class){ // добавление фотографии в галерею
        $games_item['n_id'] = intval($games_item['g_id']);
        if ($games_item['g_id'] < 1) return false;
        $type = 'game';

        if ($_POST['ph_gallery_id'] == 'new') {

            if ($games_item['g_owner_t_title'] == '' OR $games_item['g_guest_t_title'] == '') $phg_post['phg_title_ru'] = $phg_post['phg_title_ua'] = $phg_post['phg_title_en'] = $games_item['g_id'];
            else $phg_post['phg_title_ru'] = $phg_post['phg_title_ua'] = $phg_post['phg_title_en'] = $games_item['g_owner_t_title']."-".$games_item['g_guest_t_title'];

            $phg_post['phg_description_ru'] = "Фото галерея к игре &laquo;".$phg_post['phg_title_ru']."&raquo;.";
            $phg_post['phg_description_ua'] = "Фото галерея до гри &laquo;".$phg_post['phg_title_ua']."&raquo;.";
            $phg_post['phg_description_en'] = "Photo gallery for game &laquo;".$phg_post['phg_title_en']."&raquo;.";
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $games_item['g_id'];
            $phg_post['phg_phc_id'] = 0;
            $phg_post['phg_datetime_pub'] = $games_item['g_date_show'];

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id']<1) return false;
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if ($_POST['ph_type_main']) $photos_class->resetTypeMainPhotos($games_item['g_id'], $type);
        $_POST['ph_type_id'] = $games_item['g_id'];
        $_POST['ph_type'] = $type;

        if ($photos_class->savePhoto($_FILES['file_photo'], $_POST)) return true;
        return false;
    }

    public function saveVideo($games_item, &$videos_class){ // добавление видео в галерею
        $games_item['g_id'] = intval($games_item['g_id']);
        if ($games_item['g_id'] < 1) return false;
        $type = 'game';

        if ($_POST['v_gallery_id'] == 'new') {
            if ($games_item['g_owner_t_title'] == '' OR $games_item['g_guest_t_title'] == '') $phg_post['vg_title_ru'] = $phg_post['vg_title_ua'] = $phg_post['vg_title_en'] = $games_item['g_id'];
            else $phg_post['vg_title_ru'] = $phg_post['vg_title_ua'] = $phg_post['vg_title_en'] = $games_item['g_owner_t_title']."-".$games_item['g_guest_t_title'];

            $vg_post['vg_description_ru'] = "Видео галерея к игре &laquo;".$vg_post['vg_title_ru']."&raquo;.";
            $vg_post['vg_description_ua'] = "Відео галерея до гри &laquo;".$vg_post['vg_title_ua']."&raquo;.";
            $vg_post['vg_description_en'] = "Video gallery for game &laquo;".$vg_post['vg_title_en']."&raquo;.";
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $games_item['g_id'];
            $vg_post['vg_phc_id'] = 0;
            $vg_post['vg_datetime_pub'] = $games_item['g_date_show'];

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $games_item['g_id'];
        $_POST['v_type'] = $type;

        if ($videos_class->saveVideo($_POST)) return true;
        return false;
    }

    // SETTINGS ///////////////////////////////////////////////////////////////////////////////////////

    public function getGamesSettings(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."settings","*"," 1 ORDER BY set_id");
        if ($temp){
            if (count($temp)>0){
                foreach($temp as $val){
                    $list[$val['set_name']] = $val['set_value'];
                }
            }else return false;
        } else return false;
        return $list;
    }

    public function saveSettings($elems, $condition){
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$elems, $condition)) return true;
        else return false;
    }

    /**
     * repair serialized data
     *
     * @param string $settings_data
     * @return mixed
     */
    private function _fix_serialized_string ($settings_data='') {
        return preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        },$settings_data );
    }
}

