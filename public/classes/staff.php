<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class staff{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getStaffItem($id = 0){
        if (empty($id)) return false;
        if (is_numeric($id)){
            $id = intval($id);
            $where_q = "st_id = $id AND";
            $type_address = 'u';
        } else {
            $search = array(" ", "'", '"', ';');
            $replace = array("-", '', '', '');
            $id = str_replace($search, $replace, trim($id));
            $where_q = "st_address = '$id' AND";
            $type_address = 'people';
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."staff ",
            "	st_id as id,
				st_family_".S_LANG." as family,
				st_name_".S_LANG." as name,
				st_surname_".S_LANG." as surname,
				st_family_en as family_en,
				st_name_en as name_en,
				st_surname_en as surname_en,
				st_family_en as family_en,
				st_name_en as name_en,
				st_surname_en as surname_en,
				st_height as height,
				st_weight as weight,
				st_description_".S_LANG." as description,
				st_text_".S_LANG." as text,
				st_date_birth as date_birth,
				st_address,
				st_info
			","	$where_q
			    st_is_active = 'yes'
				LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $temp['address'] = $id;
            $temp['family'] = stripcslashes($temp['family']);
            $temp['family_en'] = stripcslashes($temp['family_en']);
            $temp['name'] = stripcslashes($temp['name']);
            $temp['name_en'] = stripcslashes($temp['name_en']);
            $temp['surname'] = stripcslashes($temp['surname']);
            $temp['surname_en'] = stripcslashes($temp['surname_en']);
            $temp['full_name'] = $temp['name'].' '.$temp['family'];
            $temp['full_name_en'] = !empty($temp['name_en'])?$temp['name_en'].' ':'';
            $temp['full_name_en'] .= !empty($temp['surname_en'])?$temp['surname_en'].' ':'';
            $temp['full_name_en'] .= !empty($temp['family_en'])?$temp['family_en']:'';
            $temp['full_name_en'] = trim($temp['full_name_en']);
            $temp['age'] = $this->calculateAge($temp['date_birth']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['text'] = stripcslashes($temp['text']);
            $temp['photo_main'] = $this->getStaffPhotoMain($temp['id']);
            //$temp['m'] = $month[date('m', strtotime($temp['date']))];
            $temp['type_address'] = $type_address;

            $temp_c_video = $this->hdl->selectElem(DB_T_PREFIX."videos","count(*) as v_count","v_type_id={$temp['id']} AND v_type='staff'");
            if ($temp_c_video) {
                $temp['v_count'] = $temp_c_video[0]['v_count'];
            }
            $temp_c_photos = $this->hdl->selectElem(DB_T_PREFIX."photos","count(*) as ph_count","ph_type_id={$temp['id']} AND ph_type='staff'");
            if ($temp_c_photos) {
                $temp['ph_count'] = $temp_c_photos[0]['ph_count'];
            }
            $info_statistic_custom = array();
            if (!empty($temp['st_info'])) {
                $temp['info'] = unserialize($temp['st_info']);
                if (!empty($temp['info']['statistic']['custom'])){
                    $info_statistic_custom = $temp['info']['statistic']['custom'];
                }
            }
            $temp['statistics'] = $this->getStaffStatistics($temp['id'], $info_statistic_custom);
        }
        return $temp;
    }

    public function getPageStaffItem($item = false){

        if ($item AND trim($item) != '') {
            $search = array("_", " ", "'", '"');
            $replace = array("-", "-", '', '');
            $item = str_replace($search, $replace, trim($item));
            $temp = $this->hdl->selectElem(DB_T_PREFIX."page_staff, ".DB_T_PREFIX."staff ",
                "	st_id as id,
					st_family_".S_LANG." as family,
					st_name_".S_LANG." as name,
					st_surname_".S_LANG." as surname,
					st_description_".S_LANG." as description,
					st_text_".S_LANG." as text,
					st_phone as phone,
					st_fax as fax,
					st_email as email,
					st_date_birth,
					st_is_show_birth,
					ps_title_".S_LANG." as title,
					ps_address as address,
					ps_id,
					ps_s_id,
					ps_order
				","	ps_address = '$item' AND
					ps_s_id = st_id
					LIMIT 1");
            if ($temp){
                $temp = $temp[0];
                $temp['title'] = stripcslashes($temp['title']);
                $temp['description'] = stripcslashes($temp['description']);
                $temp['text'] = stripcslashes($temp['text']);
                $temp['family'] = stripcslashes($temp['family']);
                $temp['name'] = stripcslashes($temp['name']);
                $temp['surname'] = stripcslashes($temp['surname']);
                if ($temp['email'] != '') {
                    $temp['email'] = explode(';', $temp['email']);
                    foreach ($temp['email'] as &$item) $item = trim($item);
                }
                $temp['photo_main'] = $this->getStaffPhotoMain($temp['id']);
                //$temp['photos'] = $this->getStaffPhoto($temp['id']);
                //$temp['videos'] = $this->getStaffVideo($temp['id']);
            }
            return $temp;
        }
        return false;
    }



    public function getStaffList($page=1, $perpage=10, $p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;

        //global $month; // массив названий месяцев

        $page--;
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        if ($page<0) $page = 0;
        $limit = $page*$perpage;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_staff, ".DB_T_PREFIX."staff ",
            "	st_id as id,
				st_family_".S_LANG." as family, 
				st_name_".S_LANG." as name, 
				st_surname_".S_LANG." as surname, 
				st_description_".S_LANG." as description, 
				ps_title_".S_LANG." as title,
				ps_address as address, 
				ps_id, 
				ps_s_id, 
				ps_order
			","	ps_p_id = '$p_id' AND 
				ps_s_id = st_id AND
				st_is_active = 'yes'
				ORDER BY ps_order DESC, 
				ps_title_ru ASC, 
				ps_s_id ASC
				LIMIT $limit, $perpage");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['family'] = stripcslashes($temp[$i]['family']);
                $temp[$i]['name'] = stripcslashes($temp[$i]['name']);
                $temp[$i]['surname'] = stripcslashes($temp[$i]['surname']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getStaffPhotoMain($temp[$i]['id']);
                //$temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
            }
            return $temp;
        }
        return false;
    }

    public function getStaffPages($page=1, $perpage=10, $p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_staff, ".DB_T_PREFIX."staff ",
            "	COUNT(*) as C_N
            ","	ps_p_id = '$p_id' AND 
				ps_s_id = st_id 
				ORDER BY ps_order DESC, 
				ps_title_".S_LANG." ASC, 
				ps_s_id ASC");
        $c_pages = intval($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i < $c_pages) $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i < $c_pages) $pages[$i] = $i+1;
                //$pages[9] = "...";
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i < $c_pages) $pages[$i] = $i+1;
                //$pages[$page-6] = "...";
                //if ($page+4 <= $c_pages) $pages[$page+4] = "...";
            }
        }
        if (count($pages)>1) return $pages;
        return false;
    }

    public function getStaffPhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
				ph_path,
				ph_about_".S_LANG." as ph_about,
				ph_title_".S_LANG." as ph_title,
				ph_folder,
				ph_gallery_id
										","	ph_is_active='yes' AND 
				ph_type_id = '".$id."' AND 
				ph_type = 'staff' AND 
				ph_type_main = 'yes' 
				LIMIT 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_title'] = strip_tags(stripcslashes($temp_photo['ph_title']));
            $temp_photo['ph_about'] = strip_tags(stripcslashes($temp_photo['ph_about']));
            //$temp_photo['ph_main'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_small'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_med'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-med".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_big'] = $temp_photo['ph_folder'].$temp_photo['ph_path'];
            return $temp_photo;
        }
        return false;
    }



    private function getGalleryVideo($id, $limit=0){
        $q_limit = '';
        $limit = intval($limit);
        if ($limit>0) $q_limit = ' LIMIT 0, '.$limit;
        $id = intval($id);

        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery",
            "	vg_id as id,
				vg_title_".S_LANG." as title,
				vg_description_".S_LANG." as description
										",
            "	vg_id = '$id'
				LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $ret['id'] = $id;
            $ret['title'] = strip_tags(stripcslashes($temp['title']));
            $ret['description'] = stripcslashes($temp['description']);
        } else return false;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos",
            "	v_id as id,
				v_code,
				v_title_".S_LANG." as title,
				v_about_".S_LANG." as about,
				v_folder",
            "	v_is_active='yes' and
				v_gallery_id = '".$id."' 
				ORDER BY v_id DESC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] =  strip_tags(stripcslashes($temp[$i]['title']));
                $temp[$i]['about'] =  strip_tags(stripcslashes($temp[$i]['about']));
            }
            $ret['items'] = $temp;
        }

        return $ret;
    }

    private function getGalleryPhoto($id, $limit=0){
        $q_limit = '';
        $limit = intval($limit);
        if ($limit>0) $q_limit = ' LIMIT 0, '.$limit;
        $id = intval($id);

        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery",
            "	phg_id as id,
				phg_title_".S_LANG." as title,
				phg_description_".S_LANG." as description
										",
            "	phg_id = '$id'
				LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $ret['id'] = $id;
            $ret['title'] = strip_tags(stripcslashes($temp['title']));
            $ret['description'] = stripcslashes($temp['description']);
        } else return false;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
				ph_path,
				ph_about_".S_LANG." as ph_about,
				ph_title_".S_LANG." as ph_title,
				ph_folder,
				ph_gallery_id",
            "	ph_is_active='yes' AND
				ph_gallery_id = '".$id."'
				ORDER BY ph_type_main ASC, 
				ph_order DESC, ph_id DESC
				$q_limit");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_small'] = "upload/photos".$temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_med'] = "upload/photos".$temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-med".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_big'] = "upload/photos".$temp[$i]['ph_folder'].$temp[$i]['ph_path'];
            }
            $ret['items'] = $temp;
        }
        return $ret;
    }

    public function getStaffPhoto($id, $limit=0){
        $q_limit = '';
        $limit = intval($limit);
        if ($limit>0) $q_limit = ' LIMIT 0, '.$limit;
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
				ph_path,
				ph_about_".S_LANG." as ph_about,
				ph_title_".S_LANG." as ph_title,
				ph_folder,
				ph_gallery_id",
            "	ph_is_active='yes' AND
				ph_type_id = '".$id."' AND 
				ph_type = 'staff' and 
				ph_type_main = 'no' 
				ORDER BY ph_order DESC, ph_id DESC 
				$q_limit");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_small'] = "upload/photos".$temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_med'] = "upload/photos".$temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-med".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_big'] = "upload/photos".$temp[$i]['ph_folder'].$temp[$i]['ph_path'];
            }
            return $temp;
        } else return false;
    }

    public function getStaffVideo($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos",
            "	v_id as id,
				v_code,
				v_code_text,
				v_title_".S_LANG." as title,
				v_about_".S_LANG." as about,
				v_folder",
            "	v_is_active='yes' and
				v_type_id = '".$id."' AND 
				v_type = 'staff' 
				ORDER BY v_id DESC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] =  strip_tags(stripcslashes($temp[$i]['title']));
                $temp[$i]['about'] =  strip_tags(stripcslashes($temp[$i]['about']));
            }
            return $temp;
        }
        return false;
    }

    public function calculateAge($birthday) {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }

    public function getStaffStatistics ($id=0, $custom_statistics=array()) {
        $id = intval($id);
        if ($id >0) {
            $statistics = array(
                'app'=>array(),
                'teams'=>array(),
                'games_c'=>array(),
                'ga'=>array(),
            );

            $by_time_team_ch = $this->getPlayerTable($id);
            if (!empty($by_time_team_ch)) {
                foreach ($by_time_team_ch as $t_stat_key=>$t_stat_item) {
                    $statistics['by_table'][$t_stat_item['champ']['ch_title'].'-'.$t_stat_key] = $t_stat_item;
                }
//                $statistics['by_table'] = $by_time_team_ch;
            }
            if (!empty($custom_statistics)) {
                foreach($custom_statistics as $st_item){
                    $games = explode('(', $st_item['games']);

                    $statistics['by_table'][$st_item['season']] = array(
                        'team'=> array(
                            'title'=> $st_item['team_title'],
                            'id'=> $st_item['team_id']
                        ),
                        'champ'=> array(
                            'chg_title'=> $st_item['staging'],
                            'ch_title'=> $st_item['season']
                        ),
                        'games' => array(
                            'all' => intval($games[0]),
                            'reserve' => (!empty($games[1]))?intval($games[1]):0
                        ),
                        'actions' => array(
                            'points' => $st_item['points'],
                            'pop' => $st_item['p'],
                            'sht' => $st_item['sh'],
                            'pez' => $st_item['r'],
                            'd_g' => $st_item['dg'],
                            'y_c' => $st_item['yc'],
                            'r_c' => $st_item['rc'],
                        )
                    );
                }
            }

            if (!empty($statistics['by_table'])) {
                krsort($statistics['by_table']);
            }

            $app = $this->getPlayerApp($id);
            $statistics['app'] = (!empty($app))?$app:array();
            $teams = $this->getPlayerTeam($id);
            $statistics['teams'] = (!empty($teams))?$teams:array();
//            $games_c = $this->getPlayerGamesC($id);
//            if (!empty($games_c)) {
//                $statistics['games_c'] = $games_c;
//            }
//            $ga = $this->getPlayerGameActionC($id);
//            if (!empty($ga)) {
//                $statistics['ga'] = $ga;
//            }
//            if (!empty($custom_statistics)) {
//                foreach($custom_statistics as $st_item){
//                    $statistics['teams'][] = array(
//                        'title'=>$st_item['team_title'],
//                        't_id' =>$st_item['team_id'],
//                    );
//                    $statistics['ga']['pop'] += $st_item['p'];
//                    $statistics['ga']['pop'] += $st_item['p'];
//                    $statistics['ga']['sht'] += $st_item['sh'];
//                    $statistics['ga']['pez'] += $st_item['r'];
//                    $statistics['ga']['d_g'] += $st_item['dg'];
//                    $statistics['ga']['y_c'] += $st_item['yc'];
//                    $statistics['ga']['r_c'] += $st_item['rc'];
//                    $statistics['games_c']['main'] += $st_item['games'];
//                }
//            }

            return $statistics;
        }
        return false;
    }


    public function getPlayerTable($id){
        $id = intval($id);
        if (empty($id)) {
            return false;
        }
        $table = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st gst LEFT JOIN ".DB_T_PREFIX."games g ON g.g_id=gst.cngst_g_id",
            "   g.g_id,
                gst.cngst_t_id, 
                gst.cngst_type,
                g.g_ch_id",
            "   gst.cngst_st_id = ".$id." AND g_is_done = 'yes' AND g.g_id IS NOT NULL");

        $teams_id = array();
        $teams_by_id = array();
        $chs_id = array();
        $chs_by_id = array();
        $games_id = array();
        $ch_by_game_id = array();
        $game_actions_by_key = array();
        // 1 попытка - 5 очков
        // 1 штрафной - 3 очка
        // 1 реализация - 2 очка
        // 1 дроп-гол - 3 очка
        $points = array(
            'pop' => 5,
            'sht' => 3,
            'pez' => 2,
            'd_g' => 3,
        );
        if ($temp){
            foreach ($temp as $item) {
                $teams_id[] = $item['cngst_t_id'];
                $chs_id[] = $item['g_ch_id'];
                $games_id[] = $item['g_id'];
                $ch_by_game_id[$item['g_id']] = $item['g_ch_id'];
            }
            if (!empty($teams_id)){
                $teams = $this->hdl->selectElem(DB_T_PREFIX."team",
                    "   t_id as id,
                        t_title_".S_LANG." as title,
                        t_address as address,
                        t_is_detailed as is_detailed,
                        t_is_delete as is_delete",
                    "   t_id IN (".implode(", ", $teams_id).") ");
                if (!empty($teams)) {
                    foreach ($teams as $team_item) {
                        $teams_by_id[$team_item['id']] = $team_item;
                    }
                }
            }
            if (!empty($chs_id)){
                $championships = $this->hdl->selectElem(DB_T_PREFIX."championship ch LEFT JOIN ".DB_T_PREFIX."championship_group chg ON ch.ch_chg_id=chg.chg_id",
                    "   ch_id,
                        ch_title_".S_LANG." as ch_title,
                        ch_address as ch_address,
                        chg_id,
                        chg_title_".S_LANG." as chg_title,
                        chg_address as chg_address
                        ",
                    "   ch_id IN (".implode(", ", $chs_id).") 
                        GROUP BY ch_id");
                if (!empty($championships)) {
                    foreach ($championships as $championship_item) {
                        $chs_by_id[$championship_item['ch_id']] = $championship_item;
                    }
                }
            }
            if (!empty($chs_id)){
                $game_actions = $this->hdl->selectElem(DB_T_PREFIX."games_actions",
                    "   ga_g_id,
                        ga_t_id,
                        ga_type
                        ",
                    "   ga_g_id IN (".implode(", ", $games_id).")
                        AND ga_st_id = $id
                        AND ga_is_delete = 'no'
                        GROUP BY ga_id");
                if (!empty($game_actions)) {
                    foreach ($game_actions as $game_action_item) {
                        $key = $game_action_item['ga_t_id'].'-'.$ch_by_game_id[$game_action_item['ga_g_id']];
                        if (!in_array($game_action_item['ga_type'], array('zam_out', 'zam_in'))) {
                            if (empty($game_actions_by_key[$key])){
                                $game_actions_by_key[$key] = array(
                                    'points' => 0,
                                    'pop' => 0,
                                    'sht' => 0,
                                    'pez' => 0,
                                    'd_g' => 0,
                                    'y_c' => 0,
                                    'r_c' => 0
                                );
                            }
                            $game_actions_by_key[$key][$game_action_item['ga_type']]++;
                            // 1 попытка - 5 очков
                            // 1 штрафной - 3 очка
                            // 1 реализация - 2 очка
                            // 1 дроп-гол - 3 очка
                            $game_actions_by_key[$key]['points'] += $points[$game_action_item['ga_type']];
                        }
                    }
                }
            }
            foreach ($temp as $item) {
                $key = $item['cngst_t_id'].'-'.$item['g_ch_id'];
                if (!empty($teams_by_id[$item['cngst_t_id']]) && !empty($chs_by_id[$item['g_ch_id']])){
                    if (empty($table[$key])) {
                        $table[$key] = array(
                            'team' => $teams_by_id[$item['cngst_t_id']],
                            'champ' => $chs_by_id[$item['g_ch_id']],
                            'games' => array(
                                'all' => 1,
                                'reserve' => ($item['cngst_type'] == 'reserve')?1:0
                            ),
                            'actions' => $game_actions_by_key[$key]
                        );
                    } else {
                        $table[$key]['games']['all']++;
                        if ($item['cngst_type'] == 'reserve') {
                            $table[$key]['games']['reserve']++;
                        }
                    }
                }
            }
        }
        return $table;
    }

    public function getPlayerGameActionC($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","COUNT(*) AS C_G","ga_st_id='$id' AND ga_type = 'pop' AND ga_is_delete = 'no'");
        $res['pop'] = $temp[0]['C_G'];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","COUNT(*) AS C_G","ga_st_id='$id' AND ga_type = 'sht' AND ga_is_delete = 'no'");
        $res['sht'] = $temp[0]['C_G'];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","COUNT(*) AS C_G","ga_st_id='$id' AND ga_type = 'pez' AND ga_is_delete = 'no'");
        $res['pez'] = $temp[0]['C_G'];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","COUNT(*) AS C_G","ga_st_id='$id' AND ga_type = 'd_g' AND ga_is_delete = 'no'");
        $res['d_g'] = $temp[0]['C_G'];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","COUNT(*) AS C_G","ga_st_id='$id' AND ga_type = 'y_c' AND ga_is_delete = 'no'");
        $res['y_c'] = $temp[0]['C_G'];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games_actions","COUNT(*) AS C_G","ga_st_id='$id' AND ga_type = 'r_c' AND ga_is_delete = 'no'");
        $res['r_c'] = $temp[0]['C_G'];
        return $res;
    }

    public function getPlayerGamesC($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st",
                        "COUNT(*) AS C_G",
                        "cngst_st_id='$id' AND cngst_type = 'main' AND cngst_is_delete = 'no'");
        $res['main'] = $temp[0]['C_G'];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st",
                        "COUNT(*) AS C_G",
                        "cngst_st_id='$id' AND cngst_type = 'reserve' AND cngst_is_delete = 'no'");
        $res['reserve'] = $temp[0]['C_G'];
        return $res;
    }

    public function getPlayerTeam($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_t, ".DB_T_PREFIX."team",
                        "DISTINCT t_title_".S_LANG." as title, t_id",
            "   cnstt_st_id='$id' AND
                cnstt_t_id=t_id AND
                cnstt_is_delete = 'no' AND
                (cnstt_date_quit IS NULL OR cnstt_date_quit = '0000-00-00 00:00:00')
                
                ORDER BY t_title_ru ASC, t_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
            }
            return $temp;
        } else return false;
    }

    public function getStaffNews($id, $limit = 10){
        global $month;
        $id = intval($id);
        if ($id < 1) return false;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_staff cst
                LEFT JOIN ".DB_T_PREFIX."news n ON cst.type_id = n.n_id
            ",
            "   n.n_id as id,
                n.n_title_".S_LANG." as title,
                n.n_description_".S_LANG." as description,
                n.n_date_show as date
            ",
            "   cst.st_id = $id AND
                cst.type = 'news' AND
                n.n_is_active = 'yes'
                ORDER BY n.n_date_show DESC
                LIMIT $limit");

        if ($temp){
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
                $item['description'] = stripslashes($item['description']);
                $item['m'] = $month[date('m', strtotime($item['date']))] ?? '';
                $item['photo_main'] = $this->getNewsPhotoMain($item['id']);
            }
            return $temp;
        }
        return false;
    }

    private function getNewsPhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "   ph_id,
                ph_path,
                ph_about_".S_LANG." as ph_about,
                ph_title_".S_LANG." as ph_title,
                ph_folder,
                ph_gallery_id
            ","   ph_is_active='yes' AND
                ph_type_id = '".$id."' AND
                ph_type = 'news' AND
                ph_type_main = 'yes'
                LIMIT 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_title'] = strip_tags(stripcslashes($temp_photo['ph_title']));
            $temp_photo['ph_about'] = strip_tags(stripcslashes($temp_photo['ph_about']));
            $temp_photo['ph_small'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_med'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-med".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_big'] = $temp_photo['ph_folder'].$temp_photo['ph_path'];
            return $temp_photo;
        }
        return false;
    }

    public function getPlayerApp($id){
        $id = intval($id);
        // player
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_t, ".DB_T_PREFIX."team_appointment, ".DB_T_PREFIX."team",
                        "app_title_".S_LANG." as title, app_type",
                        "   app_type = 'player' AND
                            cnstt_st_id='$id' AND
                            cnstt_t_id=t_id AND
                            cnstt_app_id=app_id AND
                            cnstt_is_delete = 'no'
                        GROUP BY app_title_ru
                        ORDER BY app_title_ru ASC,
                            app_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
            }
            $ret['player'] = $temp;

        } else $ret['player'] = false;
        // head
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_t, ".DB_T_PREFIX."team_appointment, ".DB_T_PREFIX."team",
                        "app_title_".S_LANG." as title, app_type",
                        "   app_type = 'head' AND
                            cnstt_st_id='$id' AND
                            cnstt_t_id=t_id AND
                            cnstt_app_id=app_id AND
                            cnstt_is_delete = 'no'
                        GROUP BY app_title_ru
                        ORDER BY app_title_ru ASC,
                            app_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
            }
            $ret['head'] = $temp;

        } else $ret['head'] = false;
        // rest
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_t, ".DB_T_PREFIX."team_appointment, ".DB_T_PREFIX."team",
                        "app_title_".S_LANG." as title, app_type",
                        "   app_type = 'rest' AND
                            cnstt_st_id='$id' AND
                            cnstt_t_id=t_id AND
                            cnstt_app_id=app_id AND
                            cnstt_is_delete = 'no'
                        GROUP BY app_title_ru
                        ORDER BY app_title_ru ASC,
                            app_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
            }
            $ret['rest'] = $temp;

        } else $ret['rest'] = false;

        return $ret;
    }

}
