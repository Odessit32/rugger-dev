<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class game extends clientBase {
    protected $hdl;

    public function __construct() {
        $this->hdl = database::getInstance();
    }

    public function getGameList($ch_id = 0, $g_id = 0, $page = 1, $perpage = 10) {
        $ch_id = intval($ch_id);
        $g_id = intval($g_id);
        if ($ch_id < 1 or $g_id < 1) return false;
        $perpage = intval($perpage);
        $page = intval($page);
        $page--;
        if ($page < 0) $page = 0;
        $limit = $page * $perpage;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games",
            "   g_id,
                    g_ch_id,
                    g_cp_id,
                    g_owner_t_id,
                    g_owner_t_comment,
                    g_guest_t_id,
                    g_guest_t_comment,
                    g_date_schedule,
                    g_is_done,
                    g_owner_points,
                    g_guest_points,
                    g_is_schedule_time,
                    g_owner_tehwin,
                    g_guest_tehwin,
                    g_round,
                    g_is_stadium,
                    g_std_id,
                    g_ct_id,
                    g_cn_id",
            "   g_is_active='yes' AND
                    g_owner_t_id != '0' AND
                    g_guest_t_id != '0' AND
                    g_id != '$g_id' AND
                    g_ch_id = '$ch_id'
                ORDER BY g_date_schedule DESC,
                    g_id DESC
                LIMIT $limit, $perpage");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $res_t = $this->hdl->selectElem(DB_T_PREFIX . "team",
                    "   t_title_" . S_LANG . " AS title,
                            t_std_id",
                    "   t_id = '" . $temp[$i]['g_owner_t_id'] . "' AND
                            t_is_delete = 'no'
                        LIMIT 1");
                $temp[$i]['owner_t_title'] = stripcslashes($res_t[0]['title']);
                $temp[$i]['g_owner_t_photo_main'] = $this->getTeamPhotoMain($temp[$i]['g_owner_t_id']);

                $res_t = $this->hdl->selectElem(DB_T_PREFIX . "team",
                    "   t_title_" . S_LANG . " AS title",
                    "t_id = '" . $temp[$i]['g_guest_t_id'] . "' AND t_is_delete = 'no' LIMIT 1");
                $temp[$i]['guest_t_title'] = stripcslashes($res_t[0]['title']);
                $temp[$i]['g_guest_t_photo_main'] = $this->getTeamPhotoMain($temp[$i]['g_guest_t_id']);
            }
            return $temp;
        } else return false;
    }

    public function getGamePages($page = 1, $perpage = 10) {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games", "COUNT(*) as C_N", "g_is_active='yes'");
        if ($perpage <= 0) $perpage = 10;
        $c_pages = intval($temp[0]['C_N'] / $perpage);

        if ($c_pages <= 9) {
            for ($i = 0; $i < 9; $i++) {
                if ($i <= $c_pages) $pages[$i] = $i + 1;
            }
        }
        if ($c_pages > 9) {
            if ($page < 6) {
                for ($i = 0; $i < 9; $i++) if ($i <= $c_pages) $pages[$i] = $i + 1;
            }
            if ($page > 5) {
                for ($i = $page - 5; $i < $page + 4; $i++) if ($i <= $c_pages) $pages[$i] = $i + 1;
            }
        }
        return $pages;
    }

public function getGameActionItem($id = 0, $t_o_id = 0, $t_g_id = 0) {
    $id = intval($id);
    $t_o_id = intval($t_o_id);
    $t_g_id = intval($t_g_id);
    $res = array();
    if ($id < 1 or $t_o_id < 1 or $t_g_id < 1) return false;
    $temp = $this->hdl->selectElem(DB_T_PREFIX . "games_actions, " . DB_T_PREFIX . "staff",
        DB_T_PREFIX . "games_actions.*,
                    st_family_" . S_LANG . " AS family, 
                    st_name_" . S_LANG . " AS name, 
                    st_surname_" . S_LANG . " AS surname",
        "   ga_g_id=$id
                    AND ga_st_id = st_id 
                    AND ga_is_delete='no' 
                ORDER BY ga_min ASC,
                    ga_zst_id ASC,
                    ga_type ASC");
    $count = array(
        'owner' => 0,
        'guest' => 0
    );
    if ($temp) {
        for ($i = 0; $i < count($temp); $i++) {
            $temp[$i]['family'] = stripcslashes($temp[$i]['family']);
            $temp[$i]['name'] = stripcslashes($temp[$i]['name']);
            $temp[$i]['surname'] = stripcslashes($temp[$i]['surname']);
            
            $minute = $temp[$i]['ga_min'];
            
            // Добавляем событие для хозяев
            if ($temp[$i]['ga_t_id'] == $t_o_id) {
                $res[$minute]['owner'][] = $temp[$i];
                $count['owner'] = $this->getPointsByAction($temp[$i]['ga_type'], $count['owner']);
            }
            
            // Добавляем событие для гостей
            if ($temp[$i]['ga_t_id'] == $t_g_id) {
                $res[$minute]['guest'][] = $temp[$i];
                $count['guest'] = $this->getPointsByAction($temp[$i]['ga_type'], $count['guest']);
            }
            
            // Устанавливаем счёт для обеих команд на текущей минуте
            $res[$minute]['count_owner'] = $count['owner'];
            $res[$minute]['count_guest'] = $count['guest'];
        }
    }
    return $res;
}
    private function getPointsByAction($type = '', $count = 0) {
        switch ($type) {
            case 'pop':
                $count += 5;
                break;
            case 'sht':
                $count += 3;
                break;
            case 'pez':
                $count += 2;
                break;
            case 'd_g':
                $count += 3;
                break;
            case 'y_c':
                $count += 0;
                break;
            case 'r_c':
                $count += 0;
                break;
        }
        return $count;
    }

    public function getGameItem($item = 0) {
        $item = intval($item);
        if ($item > 0) $q_item = " AND g_id='$item' ";
        else $q_item = ' ORDER BY g_datetime_edit DESC ';
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games
                            ", "    g_id,
                                g_ch_id,
                                g_cp_id,
                                g_owner_t_id,
                                g_owner_t_comment,
                                g_guest_t_id,
                                g_guest_t_comment,
                                g_description_" . S_LANG . " AS description,
                                g_text_" . S_LANG . " AS text,
                                g_date_schedule,
                                g_is_done,
                                g_owner_points,
                                g_owner_ft_points,
                                g_guest_points,
                                g_guest_ft_points,
                                g_is_schedule_time,
                                g_owner_tehwin,
                                g_guest_tehwin,
                                g_round,
                                g_is_stadium,
                                g_std_id,
                                g_ct_id,
                                g_cn_id,
                                g_ft_time,
                                g_info
                            ", "    
                                g_is_active='yes' AND 
                                g_owner_t_id != '0' AND 
                                g_guest_t_id != '0' 
                                $q_item
                            LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            if (!empty($temp['g_info'])) {
                $temp['g_info'] = json_decode($temp['g_info']);
            }
            if ($temp['g_owner_t_id'] > 0) {
                $res_t = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_title_" . S_LANG . " AS title, t_std_id, t_cn_id, t_ct_id", "t_id = '" . $temp['g_owner_t_id'] . "' AND t_is_delete = 'no' LIMIT 1");
                $temp['owner']['title'] = stripcslashes($res_t[0]['title']);
                $temp['owner']['std_id'] = $res_t[0]['t_std_id'];
                $temp['owner']['cn_id'] = $res_t[0]['t_cn_id'];
                $temp['owner']['ct_id'] = $res_t[0]['t_ct_id'];
                $temp['owner']['photo_main'] = $this->getTeamPhotoMain($temp['g_owner_t_id']);
            }
            if ($temp['g_guest_t_id'] > 0) {
                $res_t = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_title_" . S_LANG . " AS title", "t_id = '" . $temp['g_guest_t_id'] . "' AND t_is_delete = 'no' LIMIT 1");
                $temp['guest']['title'] = stripcslashes($res_t[0]['title']);
                $temp['guest']['photo_main'] = $this->getTeamPhotoMain($temp['g_guest_t_id']);
            }
            $temp['description'] = stripcslashes($temp['description']);
            $temp['text'] = stripcslashes($temp['text']);
            $temp['photo_main'] = $this->getGamePhotoMain($temp['g_id']);
            $temp['photos'] = $this->getGamePhoto($temp['g_id']);
            $temp['photo_gallery'] = $this->getGamePhotoGallery($temp['g_id']);
            $temp['videos'] = $this->getGameVideo($temp['g_id']);
            $temp['video_gallery'] = $this->getGameVideoGallery($temp['g_id']);
            $temp['connected_news'] = $this->getConnectionNews($item, false, 'news');
            if ($temp['g_is_stadium'] == 'yes') {
                if ($temp['g_std_id'] > 0) {
                    $temp['city'] = $this->getGameCity($temp['g_ct_id']);
                    $temp['country'] = $this->getGameCountry($temp['g_cn_id']);
                    $temp['stadium'] = $this->getGameStadium($temp['g_std_id']);
                } else {
                    $temp['city'] = $this->getGameCity($temp['owner']['ct_id']);
                    $temp['country'] = $this->getGameCountry($temp['owner']['cn_id']);
                    $temp['stadium'] = $this->getGameStadium($temp['owner']['std_id']);
                }
            }
            $staff_owner = $this->getGameStaff($temp['g_id'], $temp['g_owner_t_id']);
            $reserve_app = 16;
            if ($staff_owner)
                foreach ($staff_owner as $item) {
                    if ($item['app_type'] == 'player') {
                        if ($item['cngst_type'] == 'reserve') {
                            $item['app_id'] = 1000 + $reserve_app;
                            $item['app_title'] = "№" . $reserve_app;
                            $reserve_app++;
                        }
                        $temp[$item['cngst_type']]['staff_player'][$item['app_id']]['title'] = stripcslashes($item['app_title']);
                        $temp[$item['cngst_type']]['staff_player'][$item['app_id']]['owner'][] = $item;
                    }
                    if ($item['app_type'] == 'head') {
                        $temp[$item['cngst_type']]['staff_head'][$item['app_id']]['title'] = stripcslashes($item['app_title']);
                        $temp[$item['cngst_type']]['staff_head'][$item['app_id']]['owner'][] = $item;
                    }
                    if ($item['app_type'] == 'rest') {
                        $temp[$item['cngst_type']]['staff_rest'][$item['app_id']]['title'] = stripcslashes($item['app_title']);
                        $temp[$item['cngst_type']]['staff_rest'][$item['app_id']]['owner'][] = $item;
                    }
                }

            $staff_guest = $this->getGameStaff($temp['g_id'], $temp['g_guest_t_id']);
            $reserve_app = 16;
            if ($staff_guest)
                foreach ($staff_guest as $item) {
                    if ($item['app_type'] == 'player') {
                        if ($item['cngst_type'] == 'reserve') {
                            $item['app_id'] = 1000 + $reserve_app;
                            $item['app_title'] = "№" . $reserve_app;
                            $reserve_app++;
                        }
                        $temp[$item['cngst_type']]['staff_player'][$item['app_id']]['title'] = stripcslashes($item['app_title']);
                        $temp[$item['cngst_type']]['staff_player'][$item['app_id']]['guest'][] = $item;
                    }
                    if ($item['app_type'] == 'head') {
                        $temp[$item['cngst_type']]['staff_head'][$item['app_id']]['title'] = stripcslashes($item['app_title']);
                        $temp[$item['cngst_type']]['staff_head'][$item['app_id']]['guest'][] = $item;
                    }
                    if ($item['app_type'] == 'rest') {
                        $temp[$item['cngst_type']]['staff_rest'][$item['app_id']]['title'] = stripcslashes($item['app_title']);
                        $temp[$item['cngst_type']]['staff_rest'][$item['app_id']]['guest'][] = $item;
                    }
                }
            return $temp;
        } else return false;
    }

    public function getTeamPhotoMain($id) {
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                    ph_path,
                    ph_about_" . S_LANG . " as ph_about,
                    ph_title_" . S_LANG . " as ph_title,
                    ph_folder,
                    ph_gallery_id",
            "   ph_is_active='yes' AND
                    ph_type_id = '" . $id . "' AND
                    ph_type = 'team' AND
                    ph_type_main = 'yes'
                LIMIT 0, 1");
        if ($temp_photo) {
            $temp_photo[0]['ph_title'] = strip_tags(stripcslashes($temp_photo[0]['ph_title']));
            $temp_photo[0]['ph_about'] = strip_tags(stripcslashes($temp_photo[0]['ph_about']));
            $temp_photo[0]['ph_full'] = $temp_photo[0]['ph_path'];
            $temp_photo[0]['ph_main'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-s_main" . strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_small'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_med'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-med" . strrchr($temp_photo[0]['ph_path'], ".");
            return $temp_photo[0];
        } else return false;
    }

    public function getGameStadium($id) {
        // стадион
        $id = intval($id);
        if ($id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "stadium
                    ", "    std_title_" . S_LANG . " AS title
                    ", " std_id = '$id' AND std_is_active = 'yes' LIMIT 1");
        if ($temp) {
            $temp[0]['title'] = stripcslashes($temp[0]['title']);
            return $temp[0]['title'];
        } else return false;
    }

    public function getGameCountry($id) {
        // страна
        $id = intval($id);
        if ($id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "country
                    ", "    cn_title_" . S_LANG . " AS title
                    ", " cn_id = '$id' AND cn_is_active = 'yes' LIMIT 1");
        if ($temp) {
            $temp[0]['title'] = stripcslashes($temp[0]['title']);
            return $temp[0]['title'];
        } else return false;
    }

    public function getGameCity($id) {
        // город
        $id = intval($id);
        if ($id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "city
                    ", "    ct_title_" . S_LANG . " AS title
                    ", " ct_id = '$id' AND ct_is_active = 'yes' LIMIT 1");
        if ($temp) {
            $temp[0]['title'] = stripcslashes($temp[0]['title']);
            return $temp[0]['title'];
        } else return false;
    }

    public function getCompetitionItem($ch_id, $cp_id) {
        // соревнование
        $cp_id = intval($cp_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "competitions",
            "   cp_id,
                    cp_parent_id,
                    cp_title_" . S_LANG . " AS title,
                    cp_is_done,
                    cp_tour,
                    cp_is_rating_table,
                    cp_substage",
            "cp_id = '$cp_id' LIMIT 1");
        if ($temp) {
            $temp[0]['title'] = stripcslashes($temp[0]['title']);
        }
        $res['competition'] = $temp[0];
        // чемпионат
        $ch_id = intval($ch_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "championship
                    LEFT JOIN " . DB_T_PREFIX . "championship_categories ON chc_id = ch_chc_id
                    LEFT JOIN " . DB_T_PREFIX . "championship_group ON chg_id = ch_chg_id
                    LEFT JOIN " . DB_T_PREFIX . "championship_local ON chl_id = chg_chl_id",
            "   ch_id,
                    ch_title_" . S_LANG . " AS title,
                    ch_date_from,
                    ch_date_to,
                    ch_is_done,
                    ch_chc_id,
                    ch_cn_id,
                    ch_ct_id,
                    ch_cp_is_done,
                    ch_p_win,
                    ch_p_draw,
                    ch_p_loss,
                    ch_p_bonus_1,
                    ch_p_bonus_2,
                    ch_p_bonus_2_diff,
                    ch_p_tehwin,
                    ch_chg_id,
                    ch_tours,
                    ch_address,
                    ch_settings,
                    chc_title_" . S_LANG . " AS chc_title,
                    chg_title_" . S_LANG . " AS chg_title,
                    chl_title_" . S_LANG . " AS chl_title
                    ",
            "ch_id = '$ch_id' LIMIT 1");
        if ($temp) {
            $temp[0]['title'] = stripcslashes($temp[0]['title']);
            $temp[0]['chc_title'] = stripcslashes($temp[0]['chc_title']);
            $temp[0]['chg_title'] = stripcslashes($temp[0]['chg_title']);
            $temp[0]['chl_title'] = stripcslashes($temp[0]['chl_title']);
            $temp[0]['photo_main'] = $this->getChampionshipPhotoMain($temp[0]['ch_id']);
            $temp[0]['ch_settings'] = !empty($temp[0]['ch_settings']) ? unserialize($temp[0]['ch_settings']) : '';
        }
        $res['championship'] = $temp[0];
        return $res;
    }

    public function getChampionshipPhotoMain($id) {
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                    ph_path,
                    ph_about_" . S_LANG . " as ph_about,
                    ph_title_" . S_LANG . " as ph_title,
                    ph_folder,
                    ph_gallery_id",
            "   ph_is_active='yes' AND
                    ph_type_id = '" . $id . "' AND
                    ph_type = 'championship' AND
                    ph_type_main = 'yes'
                LIMIT 0, 1");
        if ($temp_photo) {
            $temp_photo[0]['ph_title'] = strip_tags(stripcslashes($temp_photo[0]['ph_title']));
            $temp_photo[0]['ph_about'] = strip_tags(stripcslashes($temp_photo[0]['ph_about']));
            $temp_photo[0]['ph_main'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-s_main" . strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_small'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp_photo[0]['ph_path'], ".");
            return $temp_photo[0];
        } else return false;
    }

    public function getGameStaff($g_id = 0, $t_id = 0) {
        $g_id = intval($g_id);
        $t_id = intval($t_id);
        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "connection_g_st,
                " . DB_T_PREFIX . "staff,
                " . DB_T_PREFIX . "team_appointment
                ", "
                    app_id,
                    app_title_" . S_LANG . " AS app_title,
                    app_type,
                    app_order,
                    
                    st_id, 
                    st_date_birth,
                    st_family_" . S_LANG . " AS family,
                    st_name_" . S_LANG . " AS name,
                    st_surname_" . S_LANG . " AS surname,
                    cngst_type
                ", "
                    cngst_is_delete='no' 
                AND cngst_t_id = '$t_id' 
                AND cngst_g_id = '$g_id'
                AND cngst_st_id = st_id 
                AND cngst_app_id = app_id 
                ORDER BY 
                app_order DESC,
                st_family_ru ASC, 
                st_name_ru ASC, 
                st_surname_ru ASC, 
                st_id ASC");
        $game_action = $this->hdl->selectElem(DB_T_PREFIX . "games_actions
                ", "
                    ga_st_id,
                    ga_type, 
                    ga_min
                ", "
                    ga_t_id = '$t_id' 
                AND ga_g_id = '$g_id'
                
                ORDER BY 
                ga_st_id ASC");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['family'] = stripcslashes($temp[$i]['family']);
                $temp[$i]['name'] = stripcslashes($temp[$i]['name']);
                $temp[$i]['surname'] = stripcslashes($temp[$i]['surname']);
                $temp[$i]['app_title'] = stripcslashes($temp[$i]['app_title']);
                $ga_st_item = array();
                $zam_st_item = array();
                if ($game_action) {
                    foreach ($game_action as $key => $item) {
                        if ($item['ga_st_id'] == $temp[$i]['st_id']) {
                            // Инициализация массивов для типов событий
                            if (!isset($ga_st_item[$item['ga_type']])) {
                                $ga_st_item[$item['ga_type']] = ['count' => 0, 'time' => ''];
                            }
                            if (!isset($zam_st_item[$item['ga_type']])) {
                                $zam_st_item[$item['ga_type']] = ['count' => 0, 'time' => ''];
                            }

                            if ($item['ga_type'] !== 'zam_out' AND $item['ga_type'] !== 'zam_in') {
                                $ga_st_item[$item['ga_type']]['count']++;
                                if (!empty($item['ga_min'])) {
                                    if (!empty($ga_st_item[$item['ga_type']]['time'])) {
                                        $ga_st_item[$item['ga_type']]['time'] .= ', ';
                                    }
                                    $ga_st_item[$item['ga_type']]['time'] .= $item['ga_min'];
                                }
                            }
                            if ($item['ga_type'] == 'zam_out' OR $item['ga_type'] == 'zam_in') {
                                $zam_st_item[$item['ga_type']]['count']++;
                                if ($item['ga_min'] > 0) {
                                    if (!empty($zam_st_item[$item['ga_type']]['time'])) {
                                        $zam_st_item[$item['ga_type']]['time'] .= ', ';
                                    }
                                    $zam_st_item[$item['ga_type']]['time'] .= $item['ga_min'];
                                }
                            }
                            unset($game_action[$key]);
                        }
                    }
                }
                $temp[$i]['game_zam'] = $zam_st_item;
                $temp[$i]['game_actions'] = $ga_st_item;
            }
            return $temp;
        }

        return false;
    }

    public function getGamePhotoGallery($id) {
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photo_gallery",
            "   phg_id,
                    phg_title_" . S_LANG . " as phg_title,
                    phg_description_" . S_LANG . " as phg_description,
                    phg_phc_id,
                    phg_datetime_pub,
                    phg_ph_count",
            "   phg_is_active='yes' AND
                    phg_type = 'championship' AND
                    phg_type_id = '$id'
                LIMIT 0, 1");
        if ($temp) {
            $temp[0]['phg_title'] = stripcslashes($temp[0]['phg_title']);
            $temp[0]['phg_description'] = stripcslashes($temp[0]['phg_description']);
            return $temp[0];
        } else return false;
    }

    public function getGameVideoGallery($id) {
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "video_gallery",
            "   vg_id,
                    vg_title_" . S_LANG . " as vg_title,
                    vg_description_" . S_LANG . " as vg_description,
                    vg_vc_id,
                    vg_datetime_pub,
                    vg_v_count",
            "   vg_is_active='yes' AND
                    vg_type = 'championship' AND
                    vg_type_id = '$id'
                LIMIT 0, 1");
        if ($temp) {
            $temp[0]['vg_title'] = stripcslashes($temp[0]['vg_title']);
            $temp[0]['vg_description'] = stripcslashes($temp[0]['vg_description']);
            return $temp[0];
        } else return false;
    }

    public function getGamePhotoMain($id) {
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                    ph_path,
                    ph_about_" . S_LANG . " as ph_about,
                    ph_title_" . S_LANG . " as ph_title,
                    ph_folder,
                    ph_gallery_id",
            "   ph_is_active='yes' AND
                    ph_type_id = '" . $id . "' AND
                    ph_type = 'championship' AND
                    ph_type_main = 'yes'
                LIMIT 0, 1");
        if ($temp_photo) {
            $temp_photo[0]['ph_title'] = strip_tags(stripcslashes($temp_photo[0]['ph_title']));
            $temp_photo[0]['ph_about'] = strip_tags(stripcslashes($temp_photo[0]['ph_about']));
            $temp_photo[0]['ph_main'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-s_main" . strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_small'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_med'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], ".")) * (-1)) . "-med" . strrchr($temp_photo[0]['ph_path'], ".");
            return $temp_photo[0];
        } else return false;
    }

    public function getGamePhoto($id) {
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                    ph_path,
                    ph_about_" . S_LANG . " as ph_about,
                    ph_title_" . S_LANG . " as ph_title,
                    ph_folder,
                    ph_gallery_id",
            "   ph_is_active='yes' AND
                    ph_type_id = '" . $id . "' AND
                    ph_type = 'championship' AND
                    ph_type_main = 'no'
                ORDER BY ph_id ASC");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_small'] = substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp[$i]['ph_path'], ".");
            }
            return $temp;
        } else return false;
    }

    public function getGameVideo($id) {
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "videos",
            "   v_id,
                    v_code,
                    v_title_" . S_LANG . " as v_title,
                    v_folder,
                    v_gallery_id",
            "   v_is_active='yes' AND
                    v_type_id = '" . $id . "' AND
                    v_type = 'championship'
                ORDER BY v_id ASC");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['v_title'] = stripcslashes($temp[$i]['v_title']);
                $temp[$i]['v_title'] = strip_tags($temp[$i]['v_title']);
            }
            return $temp;
        } else return false;
    }

    public function getGamesTeamStaff($g_id) {
        $q_extra = '';
        $g_id = intval($g_id);
        $res_temp_z = array();
        $res = array();
        $res_temp = $this->hdl->selectElem(DB_T_PREFIX . "games", "*", "g_id = '$g_id' LIMIT 1");
        $team_temp = $res_temp[0];
        // хозяева основной состав
        if ($team_temp['g_owner_t_id'] > 0) {
            $res_temp = $this->hdl->selectElem(
                DB_T_PREFIX . "connection_g_st,
                                " . DB_T_PREFIX . "staff,
                                " . DB_T_PREFIX . "team_appointment",
                "   st_family_" . S_LANG . " AS family,
                                st_name_" . S_LANG . " AS name,
                                st_surname_" . S_LANG . " AS surname,
                                st_id,
                                app_title_" . S_LANG . " AS app_title,
                                app_type,
                                cngst_type
                                ",
                "   cngst_g_id = '$g_id'
                                AND cngst_t_id = '" . $team_temp['g_owner_t_id'] . "'
                                AND cngst_st_id = st_id
                                AND cngst_app_id = app_id
                                AND cngst_is_delete = 'no'
                                AND app_type = 'player'
                                ORDER BY app_order DESC,
                                        st_family_ru ASC,
                                        st_name_ru ASC,
                                        st_surname_ru ASC");
            if ($res_temp) {
                foreach ($res_temp as $item) {
                    if ($item['cngst_type'] == 'main') {
                        $res['owner']['main'][] = $item;
                    }
                    if ($item['cngst_type'] == 'reserve') {
                        $res['owner']['reserve_id'][$item['st_id']] = $item['app_title'];
                    }
                }
            }
        }

        // хозяева замена
        $res_temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games_actions,
                                " . DB_T_PREFIX . "staff,
                                " . DB_T_PREFIX . "team_appointment",
            "   st_family_" . S_LANG . " AS family,
                                st_name_" . S_LANG . " AS name,
                                st_surname_" . S_LANG . " AS surname,
                                st_id,
                                app_title_" . S_LANG . " AS app_title,
                                app_type,
                                ga_zst_id,
                                ga_id
                                ",
            "   ga_g_id = '$g_id'
                                AND ga_t_id = '" . $team_temp['g_owner_t_id'] . "'
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
        if ($res_temp) {
            foreach ($res_temp as $item) {
                $item['app_title'] = $res['owner']['reserve_id'][$item['st_id']];
                $res_temp_z[$item['ga_zst_id']] = $item;
            }
        }
        $res['owner']['zam'] = $res_temp_z;
        unset($res_temp_z);
        // хозяева остальные игроки на замену
        if (!empty($res['owner']['main'])) {
            foreach ($res['owner']['main'] as $item) {
                $q_extra .= "AND cngst_st_id != '" . $item['st_id'] . "' ";
            }
        }
        if (!empty($res['owner']['zam'])) {
            foreach ($res['owner']['zam'] as $item) {
                $q_extra .= "AND cngst_st_id != '" . $item['st_id'] . "' ";
            }
        }
        $res_temp = $this->hdl->selectElem(
            DB_T_PREFIX . "connection_g_st,
                                " . DB_T_PREFIX . "staff,
                                " . DB_T_PREFIX . "team_appointment",
            "   st_family_" . S_LANG . " AS family,
                                st_name_" . S_LANG . " AS name,
                                st_surname_" . S_LANG . " AS surname,
                                st_id,
                                app_title_" . S_LANG . " AS app_title,
                                app_type
                                ",
            "   cngst_g_id = '$g_id'
                                AND cngst_t_id = '" . $team_temp['g_owner_t_id'] . "'
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
        for ($i = 1; $i < 30; $i++) {
            if (!empty($res['owner']['zam'])) {
                foreach ($res['owner']['zam'] as $item) {
                    if ($item['app_title'] == $i) {
                        $res['owner']['zam_all'][] = $item;
                    }
                }
            }
            if (!empty($res['owner']['reserve'])) {
                foreach ($res['owner']['reserve'] as $item) {
                    if ($item['app_title'] == $i) {
                        $res['owner']['zam_all'][] = $item;
                    }
                }
            }
        }
        $q_extra = '';
        // гости основной состав
        if ($team_temp['g_guest_t_id'] > 0) {
            $res_temp = $this->hdl->selectElem(
                DB_T_PREFIX . "connection_g_st,
                                " . DB_T_PREFIX . "staff,
                                " . DB_T_PREFIX . "team_appointment",
                "   st_family_" . S_LANG . " AS family,
                                st_name_" . S_LANG . " AS name,
                                st_surname_" . S_LANG . " AS surname,
                                st_id,
                                app_title_" . S_LANG . " AS app_title,
                                app_type,
                                cngst_type
                                ",
                "cngst_g_id = '$g_id'
                                AND cngst_t_id = '" . $team_temp['g_guest_t_id'] . "'
                                AND cngst_st_id = st_id
                                AND cngst_app_id = app_id
                                AND cngst_is_delete = 'no'
                                AND app_type = 'player'
                                ORDER BY app_order DESC,
                                        st_family_ru ASC,
                                        st_name_ru ASC,
                                        st_surname_ru ASC");
            if ($res_temp) {
                foreach ($res_temp as $item) {
                    if ($item['cngst_type'] == 'main') {
                        $res['guest']['main'][] = $item;
                    }
                    if ($item['cngst_type'] == 'reserve') {
                        $res['guest']['reserve_id'][$item['st_id']] = $item['app_title'];
                    }
                }
            }
        }
        // гости замена
        $res_temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games_actions,
                                " . DB_T_PREFIX . "staff,
                                " . DB_T_PREFIX . "team_appointment",
            "   st_family_" . S_LANG . " AS family,
                                st_name_" . S_LANG . " AS name,
                                st_surname_" . S_LANG . " AS surname,
                                st_id,
                                app_title_" . S_LANG . " AS app_title,
                                app_type,
                                ga_zst_id
                                ",
            "   ga_g_id = '$g_id'
                                AND ga_t_id = '" . $team_temp['g_guest_t_id'] . "'
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
        if ($res_temp) {
            foreach ($res_temp as $item) {
                $item['app_title'] = $res['guest']['reserve_id'][$item['st_id']];
                $res_temp_z[$item['ga_zst_id']] = $item;
            }
        }
        $res['guest']['zam'] = $res_temp_z;
        unset($res_temp_z);
        // гости остальные игроки на замену
        if (!empty($res['guest']['main'])) {
            foreach ($res['guest']['main'] as $item) {
                $q_extra .= "AND cngst_st_id != '" . $item['st_id'] . "' ";
            }
        }
        if (!empty($res['guest']['zam'])) {
            foreach ($res['guest']['zam'] as $item) {
                $q_extra .= "AND cngst_st_id != '" . $item['st_id'] . "' ";
            }
        }

        $res_temp = $this->hdl->selectElem(
            DB_T_PREFIX . "connection_g_st,
                                " . DB_T_PREFIX . "staff,
                                " . DB_T_PREFIX . "team_appointment",
            "   st_family_" . S_LANG . " AS family,
                                st_name_" . S_LANG . " AS name,
                                st_surname_" . S_LANG . " AS surname,
                                st_id,
                                app_title_" . S_LANG . " AS app_title,
                                app_type
                                ",
            "   cngst_g_id = '$g_id'
                                AND cngst_t_id = '" . $team_temp['g_guest_t_id'] . "'
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
        for ($i = 1; $i < 30; $i++) {
            if (!empty($res['guest']['zam'])) {
                foreach ($res['guest']['zam'] as $item) {
                    if ($item['app_title'] == $i) {
                        $res['guest']['zam_all'][] = $item;
                    }
                }
            }
            if (!empty($res['guest']['reserve'])) {
                foreach ($res['guest']['reserve'] as $item) {
                    if ($item['app_title'] == $i) {
                        $res['guest']['zam_all'][] = $item;
                    }
                }
            }
        }
        return $res;
    }

    public function getGamesActions($g_id) {
        global $language;
        $g_id = intval($g_id);
        $res = array();
        $res_temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games_actions",
            "   *",
            "   ga_g_id = '$g_id'
                AND ga_is_delete = 'no'
                ORDER BY ga_min ASC"
        );
        if (!empty($res_temp)) {
            foreach ($res_temp as $item) {
                if ($item['ga_type'] == 'zam_in') {
                    $zam_out[$item['ga_zst_id']] = $item['ga_st_id'];
                }
            }
            foreach ($res_temp as $item) {
                $res['by_staff'][$item['ga_st_id']][] = array(
                    'type' => $item['ga_type'],
                    'min' => $item['ga_min']
                );

                if (!empty($language[$item['ga_type']]) && (
                    empty($res['by_staff_s'][$item['ga_st_id']]['types']) ||
                    !in_array($language[$item['ga_type']], $res['by_staff_s'][$item['ga_st_id']]['types'])
                )) {
                    $res['by_staff_s'][$item['ga_st_id']]['types'][] = $language[$item['ga_type']];
                }
                if (in_array($item['ga_type'], array('zam_out', 'zam_in'))) {
                    if ($item['ga_type'] == 'zam_out') {
                        $item['ga_zst_id'] = isset($zam_out[$item['ga_st_id']]) ? $zam_out[$item['ga_st_id']] : null;
                    }
                    $res['by_staff_s'][$item['ga_st_id']]['zam'][$item['ga_type']] = array(
                        'count' => 1, // Всегда 1 замена за событие
                        'time' => !empty($item['ga_min']) ? $item['ga_min'] : '',
                        'zst_id' => $item['ga_zst_id']
                    );
                }
                $res['by_type'][$item['ga_t_id']][$item['ga_type']][$item['ga_st_id']][] = (!empty($item['ga_min'])) ? $item['ga_min'] : '';
            }
        }

        return $res;
    }
}
?>