<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class informers{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getWasCalendar($date=false){
        if ($date && $date>time())
            $date_now = date("Y-m-d", $date);
        else
            $date_now = date("Y-m-d");
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
					LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
					LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
				","
					DISTINCT g_date_schedule AS datetime
				","
					g_owner_t_id>0 AND 
					g_guest_t_id>0 AND 
					g_is_active='yes' AND 
					g_is_done = 'yes' AND 
					MONTH(g_date_schedule) = MONTH('$date_now') AND
					YEAR(g_date_schedule) = YEAR('$date_now') AND
					cp_is_active = 'yes' AND
					ch_is_active = 'yes'
				ORDER BY g_date_schedule ASC");
        return $temp;
    }

    public function getWasNext($date=false){
        if ($date && $date>time())
            $date_now = date("Y-m-d", $date);
        else
            $date_now = date("Y-m-d");
        $date_now = date('Y-m-t', strtotime($date_now));
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
					LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
					LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
				","
					DISTINCT g_date_schedule AS datetime
				","
					g_owner_t_id>0 AND 
					g_guest_t_id>0 AND 
					g_is_active='yes' AND 
					g_is_done = 'yes' AND 
					g_date_schedule > '$date_now' AND
					cp_is_active = 'yes' AND
					ch_is_active = 'yes'
				ORDER BY g_date_schedule ASC
				LIMIT 1");
        return $temp;
    }

    public function getWasPrev($date=false){
        if ($date && $date>time())
            $date_now = date("Y-m-1", $date);
        else
            $date_now = date("Y-m-1");
        $now = date("Y-m-d").' 00:00:00';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
					LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
					LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
				","
					DISTINCT g_date_schedule AS datetime
				","
					g_owner_t_id>0 AND 
					g_guest_t_id>0 AND 
					g_is_active='yes' AND 
					g_is_done = 'yes' AND 
					g_date_schedule < '$date_now' AND
					cp_is_active = 'yes' AND
					ch_is_active = 'yes'
				ORDER BY g_date_schedule DESC
				LIMIT 1");
        return $temp;
    }

    public function getSoonCalendar($date=false){
        if ($date && $date>time())
            $date_now = date("Y-m-d", $date);
        else
            $date_now = date("Y-m-d");
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
					LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
					LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
				","
					DISTINCT g_date_schedule AS datetime
				","
					g_owner_t_id>0 AND 
					g_guest_t_id>0 AND 
					g_is_active='yes' AND 
					g_is_done = 'no' AND 
					MONTH(g_date_schedule) = MONTH('$date_now') AND
					YEAR(g_date_schedule) = YEAR('$date_now') AND
					cp_is_active = 'yes' AND
					ch_is_active = 'yes'
				ORDER BY g_date_schedule ASC");
        return $temp;
    }

    public function getSoonNext($date=false){
        if ($date && $date>time())
            $date_now = date("Y-m-d", $date);
        else
            $date_now = date("Y-m-d");
        $date_now = date('Y-m-t', strtotime($date_now));
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
					LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
					LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
				","
					DISTINCT g_date_schedule AS datetime
				","
					g_owner_t_id>0 AND 
					g_guest_t_id>0 AND 
					g_is_active='yes' AND 
					g_is_done = 'no' AND 
					g_date_schedule > '$date_now' AND
					cp_is_active = 'yes' AND
					ch_is_active = 'yes'
				ORDER BY g_date_schedule ASC
				LIMIT 1");
        return $temp;
    }

    public function getSoonPrev($date=false){
        if ($date && $date>time())
            $date_now = date("Y-m-1", $date);
        else
            $date_now = date("Y-m-1");
        $now = date("Y-m-d").' 00:00:00';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
					LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
					LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
				","
					DISTINCT g_date_schedule AS datetime
				","
					g_owner_t_id>0 AND 
					g_guest_t_id>0 AND 
					g_is_active='yes' AND 
					g_is_done = 'no' AND 
					g_date_schedule < '$date_now' AND
					g_date_schedule >= '$now' AND
					cp_is_active = 'yes' AND
					ch_is_active = 'yes'
				ORDER BY g_date_schedule DESC
				LIMIT 1");
        return $temp;
    }

    // RESULT INFORMER
    public function getResultInformer($date=false, $getdata=false, $get_ch_id=0){
        global $conf;
        global $month;
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON chg.chg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '$section_type_id'";
                    break;
            }
        }
        $a_ch_id = intval($conf->conf_settings['announce_championship']);
        if ($get_ch_id>0){
            $q_ch_id = " AND ch_id = '".$get_ch_id."'";
        } else {
            $q_ch_id = " AND ch.ch_is_done = 'no' ";
        }
        //  //////////////////////////////////
        if (!$getdata || $getdata == 1) {
            $soon = [];
            $g_ids = array();
            $res['soon_date_list'] = '';
            if ($date && $date>time())
                $date_now = date("Y-m-d", $date);
            else
                $date_now = date("Y-m-d");
            $temp_dates = $this->hdl->selectElem(DB_T_PREFIX."games g
						LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
						LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
						LEFT JOIN ".DB_T_PREFIX."championship_group chg ON ch.ch_chg_id = chg.chg_id
						$f_c_extra
					","
						g.g_date_schedule AS datetime
					","
						g.g_owner_t_id>0 AND
						g.g_guest_t_id>0 AND
						g.g_is_active='yes' AND
						g.g_is_done = 'no' AND
						g.g_date_schedule >= '$date_now' AND
						cp.cp_is_active = 'yes' AND
						ch.ch_is_active = 'yes'
						$q_c_extra
				    GROUP BY g.g_date_schedule
					ORDER BY g.g_date_schedule ASC
					LIMIT 100");
            if ($temp_dates) {
                $i=0;
                $games_data = array();
                $count_dates_item = count($temp_dates);
                $date_now_past = '';
                foreach($temp_dates as $item){
                    $res['soon_date_list'] .= "'".date("n/j/Y", strtotime($item['datetime']))."'";
                    $i++;
                    if($i!=$count_dates_item){
                        $res['soon_date_list'] .= ', ';
                    }
                    if (count($games_data) < 4) {
                        $date_now = date("Y-m-d 01:00:00", strtotime($item['datetime']));
                        if ($date_now_past != $date_now) {
                            $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
                                LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
                                LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
                                LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
                                LEFT JOIN ".DB_T_PREFIX."championship_local chl ON chl.chl_id = chg.chg_chl_id
                                LEFT JOIN ".DB_T_PREFIX."games_actions ga ON ga.ga_g_id=g.g_id
                                $f_c_extra
                            ","
                                g.g_date_schedule AS datetime,
                                g.g_owner_t_id,
                                g.g_guest_t_id,
                                g.g_id,
                                g.g_ch_id,
                                g.g_cp_id,
                                ch.ch_title_".S_LANG." as ch_title,
                                cp.cp_title_".S_LANG." as cp_title,
                                chg.chg_title_".S_LANG." as chg_title,
                                chl.chl_title_".S_LANG." as chl_title,
                                ch.ch_address,
                                chg.chg_address,
                                chl.chl_address,
                                ch.ch_chc_id as type,
                                cp.cp_substage,
                                cp.cp_tour,
                                ga_id,
                                g_info
                            ","
                                g.g_owner_t_id>0 AND
                                g.g_guest_t_id>0 AND
                                g.g_is_active='yes' AND
                                g.g_is_done = 'no' AND
                                TO_DAYS(g.g_date_schedule) = TO_DAYS('$date_now')
                                $q_ch_id
                                $q_c_extra
                                GROUP BY g.g_id
                                ORDER BY g.g_date_schedule ASC
                                LIMIT 100");
                            if (!empty($temp)) {
                                foreach ($temp as $g_item){
                                    $g_ids[] = $g_item['g_id'];
                                }
                                $games_data = (empty($games_data)) ? $temp : array_merge($games_data, $temp);
                            }
                            $date_now_past = $date_now;
                        }

                    }
                }
                $date_now = $temp_dates[0]['datetime'];
            }
            if (!empty($g_ids)){
                $staff_count = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st g_st
					","
					    cngst_g_id as g_id,
					    count(*) as g_count
					","
						cngst_g_id IN ( ".implode(', ', $g_ids)." )
						GROUP BY cngst_g_id
						");
            } else {
                $staff_count = array();
            }
            $staff_count_a = [];
            if (!empty($staff_count)){
                foreach ($staff_count as $staff_count_item) {
                    $staff_count_a[$staff_count_item['g_id']] = $staff_count_item['g_count'];
                }
            }
            $res['soon_date_now'] = $date_now;
            if (!empty($games_data)) {
                // Preload all team photos in one query to avoid N+1
                $allTeamIds = [];
                foreach ($games_data as $gd) {
                    $allTeamIds[] = $gd['g_owner_t_id'];
                    $allTeamIds[] = $gd['g_guest_t_id'];
                }
                $this->preloadTeamPhotos($allTeamIds);

                $count_items = 0;
                $count_temp = count($games_data);
                for ($i=0; $i<$count_temp; $i++){
//                    $games_data[$i]['is_detailed'] = (!is_null($games_data[$i]['ga_id']))?true:false;
                    $games_data[$i]['g_info'] = json_decode($games_data[$i]['g_info']);
                    if (!empty($games_data[$i]['ga_id']) ||
                        !empty($games_data[$i]['g_info']->live) || (
                            !empty($games_data[$i]['g_info']->custom_report) && (
                                !empty($games_data[$i]['g_info']->town) ||
                                !empty($games_data[$i]['g_info']->stadium) ||
                                !empty($games_data[$i]['g_info']->viewers) ||
                                !empty($games_data[$i]['g_info']->main_judge) ||
                                !empty($games_data[$i]['g_info']->side_referee) ||
                                !empty($games_data[$i]['g_info']->video_referee)
                            )
                        ) || (
                            !empty($staff_count_a[$games_data[$i]['g_id']]) &&
                            $staff_count_a[$games_data[$i]['g_id']] >= 30
                        )
                    ){
                        $temp[$i]['is_detailed'] = true;
                    } else {
                        $temp[$i]['is_detailed'] = false;
                    }
                    if ($games_data[$i]['type'] == 1 || $games_data[$i]['type'] == 3){
                        $games_data[$i]['an_type'] = 'game';
                        $games_data[$i]['title'] = trim($games_data[$i]['chg_title'])/*.'. '.trim($games_data[$i]['ch_title'])*/;
                        $q_extra = " AND ( t_id = '".$games_data[$i]['g_owner_t_id']."' OR t_id = '".$games_data[$i]['g_guest_t_id']."' ) ";
                        $team = $this->hdl->selectElem(DB_T_PREFIX."team","t_id, t_title_".S_LANG." as title","t_is_delete = 'no' $q_extra ORDER BY t_id");
                        if ($team) {
                            foreach ($team as $item) {
                                if ($item['t_id'] == $games_data[$i]['g_owner_t_id']) {
                                    $games_data[$i]['owner']['title'] = stripcslashes($item['title']);
                                    $games_data[$i]['owner']['photo_main'] = $this->getPhotoMain($item['t_id'], 'team');
                                }
                                if ($item['t_id'] == $games_data[$i]['g_guest_t_id']) {
                                    $games_data[$i]['guest']['title'] = stripcslashes($item['title']);
                                    $games_data[$i]['guest']['photo_main'] = $this->getPhotoMain($item['t_id'], 'team');
                                }
                            }
                        }
                        $games_data[$i]['month_name'] = $month[date("m", strtotime($games_data[$i]['datetime']))];
                        $soon[$games_data[$i]['g_ch_id'].'-'.$games_data[$i]['g_id']] = $games_data[$i];
                        $count_items++;
                    } elseif ($games_data[$i]['type'] == 2 && empty($soon[$games_data[$i]['g_ch_id']])) {
                        $games_data[$i]['an_type'] = 'competition';
                        $games_data[$i]['month_name'] = $month[date("m", strtotime($games_data[$i]['datetime']))];
                        $soon[$games_data[$i]['g_ch_id']] = $games_data[$i];
                        $count_items++;
                    }
                    if ($count_items>=20) {
                        break;
                    }
                }
            }
            $res['soon'] = $soon;
        }
        //  //////////////////////////////////
        if (!$getdata || $getdata == 2) {
            //$a_ch_id = intval($a_ch_id);
            //if ($a_ch_id>0) $q_a_ch_id = " AND ch_id = '$a_ch_id' ";
            //else $q_a_ch_id = '';
            $was = [];
            $res['game_list_date_list'] = '';
            if ($date && $date<time())
                $date_now = date("Y-m-d", $date).' 23:59:59';
            else
                $date_now = date("Y-m-d").' 23:59:59';
            $temp = $this->hdl->selectElem(DB_T_PREFIX."games g
						LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
						LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
						LEFT JOIN ".DB_T_PREFIX."championship_group chg ON ch.ch_chg_id = chg.chg_id
						$f_c_extra
					","
						g.g_date_schedule AS datetime
					","
						g.g_owner_t_id>0 AND
						g.g_guest_t_id>0 AND
						g.g_is_active='yes' AND
						g.g_is_done = 'yes' AND
						g.g_date_schedule <= '$date_now' AND
						cp.cp_is_active = 'yes' AND
						ch.ch_is_active = 'yes'
						$q_c_extra
				    GROUP BY g.g_date_schedule
					ORDER BY g.g_date_schedule DESC
					LIMIT 100");
            if ($temp) {
                $i=0;
                $count_item = count($temp);
                foreach($temp as $item){
                    $res['game_list_date_list'] .= "'".date("n/j/Y", strtotime($item['datetime']))."'";
                    $i++;
                    if($i!=$count_item){
                        $res['game_list_date_list'] .= ', ';
                    }
                }
                $date_now = $temp[0]['datetime'];
            }
            $res['game_list_date_now'] = $date_now;
            $games = $this->hdl->selectElem(DB_T_PREFIX."games g
						LEFT JOIN ".DB_T_PREFIX."championship ch ON g.g_ch_id = ch.ch_id
						LEFT JOIN ".DB_T_PREFIX."competitions cp ON g.g_cp_id = cp.cp_id
						LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
						LEFT JOIN ".DB_T_PREFIX."championship_local chl ON chl.chl_id = chg.chg_chl_id
						LEFT JOIN ".DB_T_PREFIX."games_actions ga ON ga.ga_g_id=g.g_id
						$f_c_extra
						","
							g.g_id,
							g.g_cp_id,
							g.g_ch_id,
							g.g_owner_t_id,
							g.g_guest_t_id,
							g.g_owner_points,
							g.g_guest_points,
							g.g_owner_tehwin,
							g.g_guest_tehwin,
							g.g_date_schedule AS datetime,
							g.g_is_schedule_time,
							ch.ch_title_".S_LANG." as ch_title,
							cp.cp_title_".S_LANG." as cp_title,
							chg.chg_title_".S_LANG." as chg_title,
							chl.chl_title_".S_LANG." as chl_title,
							ch.ch_address,
							chg.chg_address,
							chl.chl_address,
							ch.ch_chc_id as type,
							cp.cp_substage,
                            cp.cp_tour,
                            ga_id,
                            g_info
						","	g.g_is_active='yes' AND
							g.g_owner_t_id != '0' AND
							g.g_guest_t_id != '0' AND
							cp.cp_id = g.g_cp_id AND
							cp.cp_ch_id = ch.ch_id AND
							g.g_is_done = 'yes' AND
							TO_DAYS(g.g_date_schedule) = TO_DAYS('$date_now')
							$q_ch_id
							$q_c_extra
							GROUP BY g.g_id
							ORDER BY g.g_date_schedule ASC, g.g_id DESC
							LIMIT 100");
            $g_ids = [];
            $staff_count_a = [];
            if (!empty($games) && is_array($games)){
                // Preload all team photos in one query to avoid N+1
                $allTeamIds = [];
                foreach ($games as $g_item){
                    $g_ids[] = $g_item['g_id'];
                    $allTeamIds[] = $g_item['g_owner_t_id'];
                    $allTeamIds[] = $g_item['g_guest_t_id'];
                }
                $this->preloadTeamPhotos($allTeamIds);

                if (!empty($g_ids)){
                    $staff_count = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st g_st
					","
					    cngst_g_id as g_id,
					    count(*) as g_count
					","
						cngst_g_id IN ( ".implode(', ', $g_ids)." )
						GROUP BY cngst_g_id
						");
                } else {
                    $staff_count = array();
                }
                if (!empty($staff_count) && is_array($staff_count)){
                    foreach ($staff_count as $staff_count_item) {
                        $staff_count_a[$staff_count_item['g_id']] = $staff_count_item['g_count'];
                    }
                }
                $count_items = 0;
                $count_games = count($games);
                for ($i=0; $i<$count_games; $i++){
//                    $games[$i]['is_detailed'] = (!is_null($games[$i]['ga_id']))?true:false;
                    $games[$i]['g_info'] = (!empty($games[$i]['g_info']))?json_decode($games[$i]['g_info']):false;
                    if (!empty($games[$i]['ga_id']) ||
                        !empty($games[$i]['g_info']->live) || (
                            !empty($games[$i]['g_info']->custom_report) && (
                                !empty($games[$i]['g_info']->town) ||
                                !empty($games[$i]['g_info']->stadium) ||
                                !empty($games[$i]['g_info']->viewers) ||
                                !empty($games[$i]['g_info']->main_judge) ||
                                !empty($games[$i]['g_info']->side_referee) ||
                                !empty($games[$i]['g_info']->video_referee)
                            )
                        ) || (
                            !empty($staff_count_a[$games[$i]['g_id']]) &&
                            $staff_count_a[$games[$i]['g_id']] >= 30
                        )
                    ){
                        $temp[$i]['is_detailed'] = true;
                    } else {
                        $temp[$i]['is_detailed'] = false;
                    }
                    if ($games[$i]['type'] == 1 || $games[$i]['type'] == 3){
                        $games[$i]['title'] = trim($games[$i]['chg_title'])/*.'. '.trim($games[$i]['ch_title'])*/;
                        $games[$i]['an_type'] = 'game';
                        $res_t = $this->hdl->selectElem(DB_T_PREFIX."team
							","	t_title_".S_LANG." AS title,
								t_std_id
							","	t_id = '".$games[$i]['g_owner_t_id']."' AND t_is_delete = 'no' LIMIT 1");
                        $games[$i]['owner']['title'] = stripcslashes($res_t[0]['title']);
                        $games[$i]['owner']['photo_main'] = $this->getPhotoMain($games[$i]['g_owner_t_id'], 'team');

                        $res_t = $this->hdl->selectElem(DB_T_PREFIX."team
							","	t_title_".S_LANG." AS title
							","	t_id = '".$games[$i]['g_guest_t_id']."' AND t_is_delete = 'no' LIMIT 1");
                        $games[$i]['guest']['title'] = stripcslashes($res_t[0]['title']);
                        $games[$i]['guest']['photo_main'] = $this->getPhotoMain($games[$i]['g_guest_t_id'], 'team');

                        $games[$i]['cp_title'] = stripcslashes($games[$i]['cp_title']);
                        $games[$i]['ch_title'] = stripcslashes($games[$i]['ch_title']);

                        $res_ga = $this->hdl->selectElem(DB_T_PREFIX."games_actions","ga_id","ga_g_id = '".$games[$i]['g_id']."' AND ga_is_delete = 'no' LIMIT 1");
                        if ($res_ga) $games[$i]['report'] = true;
                        else $games[$i]['report'] = false;
                        $games[$i]['month_name'] = $month[date("m", strtotime($games[$i]['datetime']))];
                        $was[$games[$i]['g_ch_id'].'-'.$games[$i]['g_id']] = $games[$i];
                        $count_items++;
                    } elseif ($games[$i]['type'] == 2 && empty($was[$games[$i]['g_ch_id']])) {
                        $games[$i]['an_type'] = 'competition';
                        $games[$i]['month_name'] = $month[date("m", strtotime($games[$i]['datetime']))];
                        $was[$games[$i]['g_ch_id']] = $games[$i];
                        $count_items++;
                    }
                    if ($count_items>=20) {
                        break;
                    }
                }
            }
            $res['game_list'] = $games;
        }
        //  //////////////////////////////////
        if (!$getdata || $getdata == 3) {
            $ch_temp = $this->hdl->selectElem(DB_T_PREFIX."championship ch
                        LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
                        LEFT JOIN ".DB_T_PREFIX."championship_local chl ON chl.chl_id = chg.chg_chl_id
						$f_c_extra
                    ","	ch.ch_id,
						ch.ch_title_".S_LANG." as title,
                        chg.chg_title_".S_LANG." as chg_title,
                        chl.chl_title_".S_LANG." as chl_title,
                        ch.ch_address,
                        chg.chg_address,
                        chl.chl_address,
						chg.chg_chl_id,
						chg.chg_id,
						ch.ch_chc_id",
                "	ch.ch_is_active='yes' AND
					    ch.ch_is_informer = 'yes' AND
					    chg.chg_id > 0 AND
					    chl.chl_id > 0
					    $q_ch_id
					    $q_c_extra
					ORDER BY chl.chl_is_main DESC,
					    chg.chg_is_main DESC,
					    ch.ch_is_main DESC,
					    ch.ch_id ASC
					LIMIT 1");
            if ($ch_temp){
                $ch_temp = $ch_temp[0];
                $res['tables_type'] = $ch_temp['ch_chc_id'];
                include_once('classes/competitions.php');
                $competitions = new competitions;
                $res['tables_title'] = /*$ch_temp['chg_title'] . '. ' .*/ $ch_temp['title'];
                if ($ch_temp['ch_chc_id'] == 1){
                    $cp_temp = $this->hdl->selectElem(DB_T_PREFIX."competitions cp, ".DB_T_PREFIX."games g",
                        "	cp.cp_id,
                                cp.cp_title_".S_LANG." as title,
							cp.cp_substage,
                            cp.cp_tour,
                            cp.cp_is_rating_table",
                        "	cp.cp_ch_id='".$ch_temp['ch_id']."' AND
							cp.cp_is_active = 'yes' AND
							cp.cp_id = g.g_cp_id AND
							g.g_is_done='yes'
						GROUP BY cp.cp_id
						ORDER BY cp.cp_id ASC");
                    if (!empty($cp_temp) && is_array($cp_temp)){
                        foreach ($cp_temp as $item){
                            if ($item['cp_is_rating_table'] == 'yes'){
                                $t_item['title'] = '<a href="/tables/'.$ch_temp['chg_address'].'/'
                                    .$ch_temp['ch_address'].'/'.$item['cp_tour'].'/'.$item['cp_substage'].'/'.$item['cp_id'].'">'
                                    .$item['title'].'</a>';
                                $t_item['data'] = $competitions->_getStandings($ch_temp['ch_id'], $item['cp_id']); //  
                                $res['tables'][] = $t_item;
                            }
                        }
                    }
                } elseif($ch_temp['ch_chc_id'] == 2) {
                    $res['tables']['title'] = '<a href="/competitions/'.$ch_temp['chl_address'].'/'.$ch_temp['chg_address'].'/'.$ch_temp['ch_address'].'">'
                        .$ch_temp['chl_title'].". ".$ch_temp['chg_title'].". ".$ch_temp['title'].'</a>';
                    $res['tables']['data'] = $competitions->_getStandingsChampTeam($ch_temp['ch_id']); //  
                }
            }

            //  
            $ch_list_temp = $this->hdl->selectElem(
                DB_T_PREFIX."championship ch
                        LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
						LEFT JOIN ".DB_T_PREFIX."championship_local chl ON chl.chl_id = chg.chg_chl_id
						INNER JOIN ".DB_T_PREFIX."games g ON g.g_ch_id = ch.ch_id
						$f_c_extra
                    ","	ch.ch_id,
						ch.ch_title_".S_LANG." as title,
						chg.chg_title_".S_LANG." as group_title,
						chl.chl_title_".S_LANG." as local_title,
						chg.chg_chl_id,
						chg.chg_id",
                "	ch.ch_is_active='yes' AND
					    ch.ch_is_informer = 'yes' AND
					    ch.ch_is_done = 'no' AND
						chg.chg_is_active='yes' AND
						g.g_is_done = 'yes'
						$q_c_extra
					GROUP BY ch.ch_id
					ORDER BY chl.chl_is_main DESC,
					    chg.chg_is_main DESC,
						chl.chl_title_".S_LANG." ASC,
						chg.chg_title_".S_LANG." ASC,
						ch.ch_title_".S_LANG." ASC,
						ch.ch_id ASC");
            if (!empty($ch_list_temp) && is_array($ch_list_temp))
                foreach ($ch_list_temp as &$item_ch) {
                    if ($item_ch['ch_id'] == $ch_temp['ch_id']) $item_ch['active'] = true;
                    else $item_ch['active'] = false;
                    $item_ch['title'] = $item_ch['group_title'];
                }
            $res['ch_list'] = $ch_list_temp;
            if ($ch_temp) {
                $ch_other_list_temp = $this->hdl->selectElem(
                    DB_T_PREFIX."championship ch
                        LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
						LEFT JOIN ".DB_T_PREFIX."championship_local chl ON chl.chl_id = chg.chg_chl_id
						INNER JOIN ".DB_T_PREFIX."games g ON g.g_ch_id = ch.ch_id
						$f_c_extra
                    ","	ch.ch_id,
						ch.ch_title_".S_LANG." as title,
						chg.chg_title_".S_LANG." as group_title,
						chl.chl_title_".S_LANG." as local_title,
						chg.chg_chl_id,
						chg.chg_id",
                    "	ch.ch_is_active='yes' AND
					    ch.ch_is_informer = 'yes' AND
						chg.chg_is_active='yes' AND
						g.g_is_done = 'yes' AND
						chg.chg_id = '".$ch_temp['chg_id']."'
						$q_c_extra
					GROUP BY ch.ch_id
					ORDER BY chl.chl_is_main DESC,
					    chg.chg_is_main DESC,
					    ch.ch_is_done ASC,
						chl.chl_title_".S_LANG." ASC,
						chg.chg_title_".S_LANG." ASC,
						ch.ch_title_".S_LANG." ASC,
						ch.ch_id ASC");
                if (!empty($ch_other_list_temp) && is_array($ch_other_list_temp))
                    foreach ($ch_other_list_temp as &$item_other_ch) {
                        if ($item_other_ch['ch_id'] == $ch_temp['ch_id']) $item_other_ch['active'] = true;
                        else $item_other_ch['active'] = false;
                    }
                $res['ch_other_list'] = $ch_other_list_temp;
            }
        }
        return $res;
    }

    // Cache for team photos to avoid N+1 queries
    private static $photoCache = [];

    private function getPhotoMain($id, $type = ''){
        if ($type == '') return false;
        $id = intval($id);

        // Check cache first
        $cacheKey = $type . '_' . $id;
        if (isset(self::$photoCache[$cacheKey])) {
            return self::$photoCache[$cacheKey];
        }

        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_is_active='yes' and ph_type_id = '".$id."' AND ph_type = '$type' AND ph_type_main = 'yes' LIMIT 0, 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_about'] = (!empty($temp_photo['ph_about']))?strip_tags(stripcslashes($temp_photo['ph_about'])):'';
            $temp_photo['ph_main'] = substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_small'] = substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_informer'] = substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-informer".strrchr($temp_photo['ph_path'], ".");
            self::$photoCache[$cacheKey] = $temp_photo;
            return $temp_photo;
        }
        self::$photoCache[$cacheKey] = false;
        return false;
    }

    /**
     * Batch preload photos for multiple team IDs to avoid N+1 queries
     */
    private function preloadTeamPhotos($teamIds) {
        if (empty($teamIds)) return;

        $teamIds = array_unique(array_filter($teamIds));
        if (empty($teamIds)) return;

        // Filter out already cached
        $toLoad = [];
        foreach ($teamIds as $id) {
            $cacheKey = 'team_' . $id;
            if (!isset(self::$photoCache[$cacheKey])) {
                $toLoad[] = intval($id);
            }
        }

        if (empty($toLoad)) return;

        $photos = $this->hdl->selectElem(
            DB_T_PREFIX."photos",
            "*",
            "ph_is_active='yes' AND ph_type = 'team' AND ph_type_main = 'yes' AND ph_type_id IN (".implode(',', $toLoad).")"
        );

        // Mark all as loaded (even if no photo found)
        foreach ($toLoad as $id) {
            self::$photoCache['team_' . $id] = false;
        }

        if ($photos) {
            foreach ($photos as $photo) {
                $photo['ph_about'] = (!empty($photo['ph_about'])) ? strip_tags(stripcslashes($photo['ph_about'])) : '';
                $photo['ph_main'] = substr($photo['ph_path'], 0, strlen(strrchr($photo['ph_path'], "."))*(-1))."-s_main".strrchr($photo['ph_path'], ".");
                $photo['ph_small'] = substr($photo['ph_path'], 0, strlen(strrchr($photo['ph_path'], "."))*(-1))."-small".strrchr($photo['ph_path'], ".");
                $photo['ph_informer'] = substr($photo['ph_path'], 0, strlen(strrchr($photo['ph_path'], "."))*(-1))."-informer".strrchr($photo['ph_path'], ".");
                self::$photoCache['team_' . $photo['ph_type_id']] = $photo;
            }
        }
    }

    public function getVoteCode(){
        list($usec, $sec) = explode(" ", microtime());
        $code = $sec.$usec;
        $code = $code*32;
        $search = array(1,2,3,4,5,6,7,8,9,0);
        $replace = array('F','O','M','A','B','Z','D','P','C','K');
        $code = str_replace($search, $replace, $code);
        $elem = array(
            $code,
            $_SERVER["REMOTE_ADDR"],
            'NOW()'
        );
        $this->hdl->addElem(DB_T_PREFIX."vote_code", $elem);
        return $code;
    }

    public function getVotesInformer(){
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $date_now = date("Y-m-d H:i:00");
        $temp = $this->hdl->selectElem(DB_T_PREFIX."vote","
			        vt_id,
			        vt_question_".S_LANG." as vt_question,
			        vt_a_count,
			        vt_is_active,
			        vt_date_from,
			        vt_date_to,
			        vt_always,
			        vt_voters_type
		        "," vt_is_active = 'yes' AND (vt_always = 'yes' OR (vt_date_from < '$date_now' AND vt_date_to > '$date_now')) ORDER BY vt_id DESC LIMIT 1"
                , false, true, 60 );
        if ($temp) {
            $temp = $temp[0];
            $temp['voted'] = false;
            $temp_ip = $this->hdl->selectElem(DB_T_PREFIX."voters","vts_id, vts_datetime","vts_vt_id = '".$temp['vt_id']."' AND vts_ip = '".$_SERVER["REMOTE_ADDR"]."' ORDER BY vts_datetime DESC LIMIT 1");
            if ($temp_ip) {
                $temp_ip = $temp_ip[0];
                if ($temp['vt_voters_type'] == 'ip') $temp['voted'] = true;
                elseif ($temp['vt_voters_type'] == 'day_ip' && (time()-strtotime($temp_ip['vts_datetime'])) < 86400) $temp['voted'] = true;
            }
            $temp['answers'] = $this->_getVoteAnswer($temp['vt_id']);
            $temp['vt_question'] = str_replace($search, $replace, stripcslashes($temp['vt_question']));

            $t = '';
            if ($temp['vt_a_count']>0 and $temp['voted'] and in_array($temp['vt_voters_type'], array('day_ip', 'ip'))){
                $temp['img'] = "https://chart.googleapis.com/chart?cht=p3&chd=t:";
                foreach ($temp['answers'] as $i=>$val){
                    $temp['answers'][$i]['percent'] = round(($val['vta_a_count']/$temp['vt_a_count'])*100);
                    if ($temp['answers'][$i]['percent']>0) {
                        $temp['img'] .= $temp['answers'][$i]['percent'];
                        if ($i<count($temp['answers'])-1 and $val['vta_answer'] != '') $temp['img'] .= ",";
                    }
                    if ($val['vta_answer'] != '') {
                        $t .= $i;
                        if ($i<count($temp['answers'])-1) $t .= "|";
                    }
                }
                $temp['img'] .= "&chs=305x150&chl=".$t."&chco=003f7c";
            }
        }
        return $temp;
    }

    private function _getVoteAnswer ($vt_id = 0) {
        $vt_id = intval($vt_id);
        if ($vt_id < 1) return false;
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $answer_list = $this->hdl->selectElem(DB_T_PREFIX."vote_answer",
            "   vta_id,
                    vta_answer_".S_LANG." as vta_answer,
                    vta_a_count",
            "   vta_vt_id=$vt_id");
        if (!empty($answer_list)) {
            foreach ($answer_list as &$item) {
                $item['vta_answer'] = str_replace($search, $replace, stripcslashes($item['vta_answer']));
            }
        }
        return $answer_list;
    }

    public function getPhotoInformer(){
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON phg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'photo_gallery' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON phg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'photo_gallery' AND c.ch_id = '$section_type_id'";
                    break;
            }
        }
        $date_now = date("Y-m-d H:i:00");
        $num_photo_gal = 3;
        $gal = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery
			        $f_c_extra ",
                "	phg_id,
					phg_title_".S_LANG." as phg_title, 
					phg_description_".S_LANG." as phg_description, 
					phg_datetime_pub
				","	phg_is_informer = 'yes' 
					AND phg_is_active = 'yes' 
					AND phg_datetime_pub < '".$date_now."'
					AND phg_ph_count > 0
					$q_c_extra
				ORDER BY phg_datetime_pub DESC
					LIMIT $num_photo_gal"
            , false, true, 60 );
        if ($gal){
            $i = rand(1, count($gal));
            $i--;
            $gal = $gal[$i];
            if ($gal){
                $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
                    "	ph_id,
								ph_path, 
								ph_folder, 
								ph_gallery_id,
								ph_about_".S_LANG." as ph_about,
								ph_title_".S_LANG." as ph_title
							","	ph_is_informer = 'yes'
								AND ph_is_active = 'yes' 
								AND ph_gallery_id = '".$gal['phg_id']."' 
							ORDER BY ph_id ASC LIMIT 10");
                if ($temp) {
                    shuffle($temp);
                    unset($temp[5]);
                    unset($temp[6]);
                    unset($temp[7]);
                    unset($temp[8]);
                    unset($temp[9]);
                    foreach ($temp as &$item){
                        $item['ph_title'] = strip_tags(stripslashes($item['ph_title']));
                        $item['ph_about'] = strip_tags(stripslashes($item['ph_about']));
                        $item['ph_big'] = "upload/photos".$item['ph_folder'].$item['ph_path'];
                        $item['ph_small'] = "upload/photos".$item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
                        $item['ph_med'] = "upload/photos".$item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
                        $item['ph_informer'] = "upload/photos".$item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-informer".strrchr($item['ph_path'], ".");
                    }

                }
                $gal['photos'] = $temp;
            }
        }
        return $gal;
    }

    public function getVideoInformer($limit = 0){
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        $limit = intval($limit);
        if ($limit<1) $limit = 5;
        $date_now = date("Y-m-d H:i:00");
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON vg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'video_gallery' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON vg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'video_gallery' AND c.ch_id = '$section_type_id'";
                    break;
            }
        }
        $gal = $this->hdl->selectElem(DB_T_PREFIX."video_gallery
			        INNER JOIN ".DB_T_PREFIX."videos ON v_gallery_id = vg_id
			        $f_c_extra",
                "	vg_id,
					vg_title_".S_LANG." as vg_title, 
					vg_description_".S_LANG." as vg_description, 
					vg_datetime_pub,
					v_id,
					v_folder
				","	vg_is_informer = 'yes'
					AND vg_is_active = 'yes' 
					AND vg_datetime_pub < '".$date_now."'
					$q_c_extra
				GROUP BY vg_id
				ORDER BY vg_datetime_pub DESC
					LIMIT $limit"
            , false, true, 60 );
        if ($gal){
            foreach ($gal as &$item){
                $item['vg_title'] = strip_tags(stripslashes($item['vg_title']));
                $item['vg_description'] = strip_tags(stripslashes($item['vg_description']));
            }
        }
        return $gal;
    }

    public function getAnnounceInformer(){
        global $conf;
        if ($conf->conf_settings['announce_is_informer']==0) return false;
        global $month;
        $q_extra = '';
        $date_now = date("Y-m-d H:i:00");
        $anons = $this->hdl->selectElem(DB_T_PREFIX."announce",
            "	an_id,
					an_type,
					an_description_".S_LANG." as description, 
					an_datetime,
					an_photo_event,
					an_owner_t_id,
					an_owner_t_title,
					an_owner_t_logo,
					an_guest_t_id,
					an_guest_t_title,
					an_guest_t_logo,
					an_link
				","	an_is_active = 'yes' 
				ORDER BY an_is_main DESC
					LIMIT 1");
        if ($anons) {
            $anons = $anons[0];
            $anons['description'] = trim(stripslashes($anons['description']));
            if ($anons['an_type'] == 'game') {
                $anons['month_name'] = $month[date("m", strtotime($anons['an_datetime']))];
                $anons['an_datetime_m'] = date("d", strtotime($anons['an_datetime'])).' '.$anons['month_name'].'  '.date("H:i", strtotime($anons['an_datetime']));
            }
            if ($anons['an_type'] == 'game' and ($anons['an_owner_t_id']>0 OR $anons['an_guest_t_id']>0)){
                if ($anons['an_owner_t_id']>0) $q_extra .= "t_id = '".$anons['an_owner_t_id']."'";
                if ($anons['an_guest_t_id']>0) {
                    if ($anons['an_owner_t_id']>0) $q_extra .= " OR ";
                    $q_extra .= "t_id = '".$anons['an_guest_t_id']."'";
                }
                if ($q_extra != '') $q_extra = " AND ( $q_extra )";
                $team = $this->hdl->selectElem(DB_T_PREFIX."team","t_id, t_title_ru","t_is_delete = 'no' $q_extra ORDER BY t_id");
                if ($team) {
                    foreach ($team as $item) {
                        if ($item['t_id'] == $anons['an_owner_t_id']) {
                            $anons['an_owner_t_id_title'] = $item['t_title_ru'];
                            $anons['an_owner_t_id_photo'] = $this->getPhotoMain($item['t_id'], 'team');
                        }
                        if ($item['t_id'] == $anons['an_guest_t_id']) {
                            $anons['an_guest_t_id_title'] = $item['t_title_ru'];
                            $anons['an_guest_t_id_photo'] = $this->getPhotoMain($item['t_id'], 'team');
                        }
                    }
                }
            }
        }
        return $anons;
    }

}
