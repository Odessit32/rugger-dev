<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class team {
    private $hdl;

    public function __construct() {
        $this->hdl = database::getInstance();
    }

    public function getTeamItemBySlug($url = '') {
        $search = array("'", '"');
        $replace = array('', '');
        $url = trim($url);
        if (is_numeric($url)) {
            $url = intval($url);
            $q_url = "t_id = $url";
        } else {
            $url = str_replace($search, $replace, $url);
            $q_url = "t_address = '$url'";
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "team ",
            "t_id as id,
                    t_date_foundation,
                    t_cn_id,
                    t_ct_id,
                    t_title_" . S_LANG . " as title,
                    t_description_" . S_LANG . " as description,
                    t_text_" . S_LANG . " as text,
                    t_std_id,
                    t_is_technical,
                    t_is_detailed,
                    t_address as address,
                    t_info",
            "$q_url
                    AND t_is_active = 'yes'
                    AND t_is_delete = 'no'
                    AND t_is_technical = 'no'
                    LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $temp['t_info'] = unserialize($temp['t_info']);
            $temp['foundation_date'] = explode('-', $temp['t_date_foundation']);
            $temp['title'] = stripcslashes($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['text'] = stripcslashes($temp['text']);
            $temp['text'] = (trim(strip_tags($temp['text'])) == '') ? '' : $temp['text'];
            if (!empty($temp['t_std_id'])) {
                $temp['stadium'] = $this->getStadiumItemShort($temp['t_std_id']);
            }
            $temp['photo_main'] = $this->getPhotoMain($temp['id'], 'team');
            $temp['photos'] = $this->getTeamPhotoGallery($temp['id']);
            $temp['videos'] = $this->getTeamVideo($temp['id']);
            $show_app = (!empty($temp['t_info']['show_app'])) ? $temp['t_info']['show_app'] : array();
            $temp['general_staff'] = $this->getTeamGeneralStaff($temp['id'], $show_app);
            $staff = $this->getTeamStaff($temp['id']);
            $temp['staff'] = $staff['all'];
            $temp['staff_by_type'] = $staff['by_type'];
        }
        return $temp;
    }

    private function getTeamGeneralStaff($id = 0, $show_app = array()) {
        if ($id < 1 || empty($show_app)) {
            return false;
        }
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t st 
                LEFT JOIN " . DB_T_PREFIX . "staff s ON s.st_id = st.cnstt_st_id
                LEFT JOIN " . DB_T_PREFIX . "team_appointment a ON st.cnstt_app_id = a.app_id",
            "st_id as id,
                    st_name_" . S_LANG . " as name,
                    st_family_" . S_LANG . " as family,
                    cnstt_app_id as app_id,
                    st_address,
                    app_title_" . S_LANG . " as app_title",
            "cnstt_app_id IN (" . implode(", ", $show_app) . ") AND
                    cnstt_t_id = $id AND 
                    cnstt_is_delete = 'no' AND 
                    st_is_active = 'yes'
                    ORDER BY cnstt_order ASC");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['app_title'] = stripcslashes($temp[$i]['app_title']);
                $temp[$i]['name'] = stripcslashes($temp[$i]['name']);
                $temp[$i]['family'] = stripcslashes($temp[$i]['family']);
            }
        }
        return $temp;
    }

    private function getStadiumItemShort($id = 0) {
        if ($id < 1) {
            return false;
        }
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "stadium ",
            "std_id as id,
                    std_title_" . S_LANG . " as title,
                    std_description_" . S_LANG . " as description",
            "std_id = '$id'
                    LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
        }
        return $temp;
    }

    public function getTeamStaff($id = 0, $g_id = 0) {
        $id = intval($id);
        $g_id = intval($g_id);
        if ($id < 1) return false;
        $res = [];

        if ($g_id > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_g_st
                    LEFT JOIN " . DB_T_PREFIX . "staff ON " . DB_T_PREFIX . "staff.st_id=" . DB_T_PREFIX . "connection_g_st.cngst_st_id
                    LEFT JOIN " . DB_T_PREFIX . "team_appointment ON " . DB_T_PREFIX . "team_appointment.app_id=" . DB_T_PREFIX . "connection_g_st.cngst_app_id",
                "st_id,
                    st_date_birth,
                    st_family_" . S_LANG . " AS family,
                    st_name_" . S_LANG . " AS name,
                    st_is_show_birth,
                    app_title_" . S_LANG . " AS app,
                    st_text_" . S_LANG . " AS text,
                    app_type",
                "cngst_is_delete='no'
                    AND cngst_t_id = $id
                    AND cngst_g_id = $g_id
                ORDER BY app_order DESC,
                    st_family_" . S_LANG . " ASC,
                    st_name_" . S_LANG . " ASC,
                    st_surname_" . S_LANG . " ASC");
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t
                    LEFT JOIN " . DB_T_PREFIX . "staff ON " . DB_T_PREFIX . "staff.st_id=" . DB_T_PREFIX . "connection_st_t.cnstt_st_id
                    LEFT JOIN " . DB_T_PREFIX . "team_appointment ON " . DB_T_PREFIX . "team_appointment.app_id=" . DB_T_PREFIX . "connection_st_t.cnstt_app_id",
                "st_id,
                    st_date_birth,
                    st_family_" . S_LANG . " AS family,
                    st_name_" . S_LANG . " AS name,
                    st_is_show_birth,
                    app_title_" . S_LANG . " AS app,
                    st_text_" . S_LANG . " AS text,
                    app_type",
                "cnstt_is_delete='no'
                    AND (cnstt_date_quit IS NULL OR cnstt_date_quit = '0000-00-00 00:00:00')
                    AND cnstt_t_id = '$id'
                ORDER BY app_order DESC,
                    st_family_" . S_LANG . " ASC,
                    st_name_" . S_LANG . " ASC,
                    st_surname_" . S_LANG . " ASC");
        }
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $staff_type = 0;
                if ($temp[$i]['app'] >= 1 && $temp[$i]['app'] <= 8) {
                    $staff_type = 1;
                } elseif ($temp[$i]['app'] >= 9 && $temp[$i]['app'] <= 10) {
                    $staff_type = 2;
                } elseif ($temp[$i]['app'] >= 11 && $temp[$i]['app'] <= 15) {
                    $staff_type = 3;
                }

                if (isset($res['all'][$temp[$i]['st_id']])) {
                    $res['all'][$temp[$i]['st_id']]['app'] .= ', ' . stripcslashes($temp[$i]['app'] ?? '');
                    $res['all'][$temp[$i]['st_id']][$temp[$i]['app_type']] = true;
                    $res['all'][$temp[$i]['app_type']] = true;
                } else {
                    $age = !empty($temp[$i]['st_date_birth']) ? time() - strtotime($temp[$i]['st_date_birth']) : 0;
                    $st_item = array(
                        'family' => !empty($temp[$i]['family']) ? stripcslashes($temp[$i]['family']) : '',
                        'name' => !empty($temp[$i]['name']) ? stripcslashes($temp[$i]['name']) : '',
                        'surname' => !empty($temp[$i]['surname']) ? stripcslashes($temp[$i]['surname']) : '',
                        'app' => trim(stripcslashes($temp[$i]['app'] ?? '')),
                        'st_date_birth' => $temp[$i]['st_date_birth'],
                        'age' => intval($age / (60 * 60 * 24 * 365.242199)),
                        'st_is_show_birth' => $temp[$i]['st_is_show_birth'],
                        'photo_main' => $this->getPhotoMain($temp[$i]['st_id'], 'staff'),
                        $temp[$i]['app_type'] => true
                    );
                    if (!empty($st_item['st_date_birth']) && $st_item['st_date_birth'] != '0000-00-00' && strtotime($st_item['st_date_birth']) < strtotime('2010-01-01')) {
                        $st_item['bd'] = date("j.m.Y", strtotime($st_item['st_date_birth']));
                    } else {
                        $st_item['bd'] = '';
                    }
                    $res['by_type'][$staff_type][$temp[$i]['st_id']] = $res['all'][$temp[$i]['st_id']] = $st_item;
                    $res['all'][$temp[$i]['app_type']] = true;
                }
            }
        }
        return $res;
    }

    public function getPhotoMain($id, $type = '') {
        $id = intval($id);
        if ($type == 'team') $type = 'team';
        elseif ($type == 'staff') $type = 'staff';
        else $type = 'none';
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "ph_id,
                                            ph_path,
                                            ph_about_" . S_LANG . " as ph_about,
                                            ph_title_" . S_LANG . " as ph_title,
                                            ph_folder,
                                            ph_gallery_id",
            "ph_is_active='yes' AND 
                                            ph_type_id = '" . $id . "' AND 
                                            ph_type = '" . $type . "' AND 
                                            ph_type_main = 'yes' 
                                            LIMIT 1");
        if ($temp_photo) {
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_title'] = strip_tags(stripcslashes($temp_photo['ph_title']));
            $temp_photo['ph_about'] = strip_tags(stripcslashes($temp_photo['ph_about']));
            $temp_photo['ph_small'] = $temp_photo['ph_folder'] . substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_med'] = $temp_photo['ph_folder'] . substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], ".")) * (-1)) . "-med" . strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_big'] = $temp_photo['ph_folder'] . $temp_photo['ph_path'];
            return $temp_photo;
        }
        return false;
    }

    private function getGalleryVideo($id, $limit = 0) {
        $q_limit = '';
        $limit = intval($limit);
        if ($limit > 0) $q_limit = ' LIMIT 0, ' . $limit;
        $id = intval($id);

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "video_gallery",
            "vg_id as id,
                                            vg_title_" . S_LANG . " as title,
                                            vg_description_" . S_LANG . " as description",
            "vg_id = '$id'
                                            LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $ret['id'] = $id;
            $ret['title'] = strip_tags(stripcslashes($temp['title']));
            $ret['description'] = stripcslashes($temp['description']);
        } else return false;

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "videos",
            "v_id as id,
                                            v_code,
                                            v_title_" . S_LANG . " as title,
                                            v_about_" . S_LANG . " as about,
                                            v_folder",
            "v_is_active='yes' and
                                            v_gallery_id = '" . $id . "' 
                                            ORDER BY v_id DESC");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = strip_tags(stripcslashes($temp[$i]['title']));
                $temp[$i]['about'] = strip_tags(stripcslashes($temp[$i]['about']));
            }
            $ret['items'] = $temp;
        }

        return $ret;
    }

    private function getTeamPhotoGallery($id = 0) {
        if ($id < 1) {
            return false;
        }
        $id = intval($id);

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photo_gallery",
            "phg_id as id,
                phg_title_" . S_LANG . " as title,
                phg_description_" . S_LANG . " as description",
            "phg_type_id = '$id' AND 
                phg_type = 'team'
                LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $temp['title'] = strip_tags(stripcslashes($temp['title']));
            $temp['description'] = stripcslashes($temp['description']);

            $photos = $this->hdl->selectElem(DB_T_PREFIX . "photos",
                "ph_id,
                ph_path,
                ph_about_" . S_LANG . " as ph_about,
                ph_title_" . S_LANG . " as ph_title,
                ph_folder,
                ph_gallery_id, 
                ph_type_main as main",
                "ph_is_active='yes' AND
                ph_gallery_id = '" . $temp['id'] . "'
                ORDER BY ph_type_main ASC, 
                ph_order DESC, ph_id DESC");
            if ($photos) {
                for ($i = 0; $i < count($photos); $i++) {
                    $photos[$i]['ph_title'] = strip_tags(stripcslashes($photos[$i]['ph_title']));
                    $photos[$i]['ph_about'] = strip_tags(stripcslashes($photos[$i]['ph_about']));
                    $photos[$i]['ph_small'] = "upload/photos" . $photos[$i]['ph_folder'] . substr($photos[$i]['ph_path'], 0, strlen(strrchr($photos[$i]['ph_path'], ".")) * (-1)) . "-small" . strrchr($photos[$i]['ph_path'], ".");
                    $photos[$i]['ph_med'] = "upload/photos" . $photos[$i]['ph_folder'] . substr($photos[$i]['ph_path'], 0, strlen(strrchr($photos[$i]['ph_path'], ".")) * (-1)) . "-med" . strrchr($photos[$i]['ph_path'], ".");
                    $photos[$i]['ph_big'] = "upload/photos" . $photos[$i]['ph_folder'] . $photos[$i]['ph_path'];
                }
                $temp['items'] = $photos;
            }
            return $temp;
        }
        return false;
    }

    public function getTeamPhoto($id, $limit = 0) {
        $q_limit = '';
        $limit = intval($limit);
        if ($limit > 0) $q_limit = ' LIMIT 0, ' . $limit;
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "ph_id,
                                            ph_path,
                                            ph_about_" . S_LANG . " as ph_about,
                                            ph_title_" . S_LANG . " as ph_title,
                                            ph_folder,
                                            ph_gallery_id",
            "ph_is_active='yes' AND
                                            ph_type_id = '" . $id . "' AND 
                                            ph_type = 'team' and 
                                            ph_type_main = 'no' 
                                            ORDER BY ph_order DESC, ph_id DESC 
                                            $q_limit");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_small'] = "upload/photos" . $temp[$i]['ph_folder'] . substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_med'] = "upload/photos" . $temp[$i]['ph_folder'] . substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-med" . strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_big'] = "upload/photos" . $temp[$i]['ph_folder'] . $temp[$i]['ph_path'];
            }
            return $temp;
        } else return false;
    }

    public function getTeamVideo($id) {
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "videos",
            "v_id as id,
                                            v_code,
                                            v_code_text,
                                            v_title_" . S_LANG . " as title,
                                            v_about_" . S_LANG . " as about,
                                            v_folder",
            "v_is_active='yes' and
                                            v_type_id = '" . $id . "' AND 
                                            v_type = 'team' 
                                            ORDER BY v_id DESC");
        if ($temp) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = strip_tags(stripcslashes($temp[$i]['title']));
                $temp[$i]['about'] = strip_tags(stripcslashes($temp[$i]['about']));
            }
            return $temp;
        }
        return false;
    }

    public function getTeamStatistics($id) {
        $id = intval($id);
        global $month, $month_i, $wday;
        $res = array();
        $q_date = '';
        $f_c_extra = '';
        $q_c_extra = '';
        $search = array("\\'", '\\"', "'", '"');
        $replace = array('', '', '', '');
        
        // Проверка глобальной переменной $one_ch
        $q_ch_id = '';
        if (isset($GLOBALS['one_ch']) && $GLOBALS['one_ch'] && $GLOBALS['one_ch'] != 'all') {
            $q_ch_id = " AND chg_address = '" . str_replace($search, $replace, $GLOBALS['one_ch']) . "'";
        }

        $soon = [];
        $res['soon_date_list'] = '';
        $date_now = isset($date_now) ? date("Y-m-d", strtotime($date_now)) . ' 23:59:59' : date("Y-m-d") . ' 23:59:59';

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games g
                        LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                        LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                        LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON ch.ch_chg_id = chg.chg_id
                        $f_c_extra",
            "g_date_schedule AS datetime",
            "g_owner_t_id>0 AND
                        g_guest_t_id>0 AND
                        (
                            g_owner_t_id = $id OR
                            g_guest_t_id = $id
                        ) AND
                        g_is_active='yes' AND
                        g_is_done = 'yes' AND
                        g_date_schedule <= '$date_now' AND
                        cp_is_active = 'yes' AND
                        ch_is_active = 'yes'
                        $q_ch_id
                        $q_c_extra
                    GROUP BY g_date_schedule
                    ORDER BY g_date_schedule DESC
                    LIMIT 100");
        if ($temp) {
            $i = 0;
            $count_item = count($temp);
            foreach ($temp as $item) {
                $res['soon_date_list'] .= "'" . date("n/j/Y", strtotime($item['datetime'])) . "'";
                $i++;
                if ($i != $count_item) {
                    $res['soon_date_list'] .= ', ';
                }
            }
            $date_now = date("Y-m-d", strtotime($temp[0]['datetime'])) . ' 23:59:59';
        }
        $res['soon_date_now'] = $date_now;
        $date_current_now = date("Y-m-d H:i:00");

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games g
                        LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                        LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                        LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON chg.chg_id = ch.ch_chg_id
                        LEFT JOIN " . DB_T_PREFIX . "championship_local chl ON chl.chl_id = chg.chg_chl_id
                        LEFT JOIN " . DB_T_PREFIX . "games_actions ga ON ga.ga_g_id=g.g_id
                        $f_c_extra",
            "g_owner_points,
                        g_guest_points,
                        g_owner_tehwin,
                        g_guest_tehwin,
                        g_date_schedule AS 'datetime',
                        g_is_schedule_time,
                        g_owner_t_id,
                        g_guest_t_id,
                        g_id,
                        g_ch_id,
                        g_cp_id,
                        g_cp_id_g,
                        g_date_time_zone as time_zone,
                        ch_title_" . S_LANG . " as ch_title,
                        ch_address,
                        cp_title_" . S_LANG . " as cp_title,
                        chg_title_" . S_LANG . " as chg_title,
                        chg_address,
                        chl_title_" . S_LANG . " as chl_title,
                        ch_address,
                        chg_address,
                        chl_address,
                        ch_settings,
                        ch_chc_id as type,
                        cp_substage,
                        cp_tour,
                        ga_id",
            "g_owner_t_id>0 AND
                        g_guest_t_id>0 AND
                        (
                            g_owner_t_id = $id OR
                            g_guest_t_id = $id
                        ) AND
                        g_is_active='yes' AND
                        g_is_done = 'yes' AND
                        TO_DAYS(g_date_schedule) <= TO_DAYS('$date_current_now')
                        GROUP BY g_id
                        ORDER BY g_date_schedule DESC",
            false, true, 60);

        if ($temp) {
            $count_temp = count($temp);
            $default_timezone = date_default_timezone_get();
            for ($i = 0; $i < $count_temp; $i++) {
                $key = date("Y-m", strtotime($temp[$i]['datetime']));
                $d_key = date("d", strtotime($temp[$i]['datetime']));
                $ch_key = $temp[$i]['g_ch_id'];
                $temp[$i]['ch_settings'] = unserialize($temp[$i]['ch_settings']) ?? [];

                // Обработка часового пояса
                $time_zone = $temp[$i]['time_zone'] ?? 0;
                if (is_numeric($time_zone)) {
                    $offset = (int)$time_zone;
                    $timezone = $offset >= 0 ? "Etc/GMT-" . $offset : "Etc/GMT+" . abs($offset);
                    if (date_default_timezone_set($timezone)) {
                        $temp[$i]['time_zone_title'] = date("T");
                    } else {
                        $temp[$i]['time_zone_title'] = 'Unknown';
                    }
                } else {
                    $temp[$i]['time_zone_title'] = 'Unknown';
                    $time_zone = 0;
                }
                $temp[$i]['datetime_gmt'] = strtotime($temp[$i]['datetime']) - ($time_zone * 3600);
                $temp[$i]['time_gmt'] = date("H:i", $temp[$i]['datetime_gmt']);

                // Обработка stageDateTime
                $tour = $temp[$i]['cp_tour'] ?? '';
                $substage = $temp[$i]['cp_substage'] ?? '';
                if (isset($temp[$i]['ch_settings']['stageDateTime'][$tour][$substage])) {
                    $stage_datetime = $temp[$i]['ch_settings']['stageDateTime'][$tour][$substage];
                    $temp[$i]['ch_settings']['stageDateTimeS'][$tour][$substage] = strtotime("$stage_datetime +00:00");
                    $temp[$i]['ch_settings']['stageDateTimeDF'][$tour][$substage] = date("H:i", strtotime("$stage_datetime +00:00"));
                }

                $soon[$key]['caption'] = $month_i[date("m", strtotime($temp[$i]['datetime']))] . ' ' . date("Y", strtotime($temp[$i]['datetime']));
                $soon[$key]['data'][$d_key]['caption'] = $wday[date("N", strtotime($temp[$i]['datetime']))] . '. ' . date("j", strtotime($temp[$i]['datetime']));
                $soon[$key]['data'][$d_key]['data'][$ch_key]['caption'] = $temp[$i]['chg_title'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['address'] = $temp[$i]['ch_address'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['path'] = !empty($temp[$i]['ch_settings']['ch_address_path']) ? $temp[$i]['ch_settings']['ch_address_path'] . '/' : '';

                if ($temp[$i]['type'] == 1 || $temp[$i]['type'] == 3) { // rugby 15, rugby super 15
                    $temp[$i]['an_type'] = 'game';
                    $temp[$i]['title'] = trim($temp[$i]['chg_title']) . '. ' . trim($temp[$i]['ch_title']);
                    $q_extra = " AND ( t_id = '" . $temp[$i]['g_owner_t_id'] . "' OR t_id = '" . $temp[$i]['g_guest_t_id'] . "' ) ";
                    $team = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_id, t_title_" . S_LANG . " as title", "t_is_delete = 'no' $q_extra ORDER BY t_id");
                    if ($team) {
                        foreach ($team as $item) {
                            if ($item['t_id'] == $temp[$i]['g_owner_t_id']) {
                                $temp[$i]['owner']['title'] = stripcslashes($item['title']);
                                $temp[$i]['owner']['photo_main'] = $this->getPhotoMain($item['t_id'], 'team');
                            }
                            if ($item['t_id'] == $temp[$i]['g_guest_t_id']) {
                                $temp[$i]['guest']['title'] = stripcslashes($item['title']);
                                $temp[$i]['guest']['photo_main'] = $this->getPhotoMain($item['t_id'], 'team');
                            }
                        }
                    }
                    $temp[$i]['is_detailed'] = !is_null($temp[$i]['ga_id']);
                    $temp[$i]['month_name'] = $month[date("m", strtotime($temp[$i]['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$temp[$i]['g_ch_id'] . '-' . $temp[$i]['g_id']] = $temp[$i];
                } elseif ($temp[$i]['type'] == 2 && empty($soon[$temp[$i]['g_ch_id']])) { // rugby 7
                    $temp[$i]['an_type'] = 'competition';
                    $temp[$i]['month_name'] = $month[date("m", strtotime($temp[$i]['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$temp[$i]['g_ch_id']] = $temp[$i];
                }
            }
            date_default_timezone_set($default_timezone);
        }
        $res['soon'] = $soon;
        return $res;
    }

    public function getTeamTimetable($id) {
        $id = intval($id);
        global $month, $month_i, $wday;
        $res = array();
        $q_date = '';
        $f_c_extra = '';
        $q_c_extra = '';
        $search = array("\\'", '\\"', "'", '"');
        $replace = array('', '', '', '');

        // Проверка глобальной переменной $one_ch
        $q_ch_id = '';
        if (isset($GLOBALS['one_ch']) && $GLOBALS['one_ch'] && $GLOBALS['one_ch'] != 'all') {
            $q_ch_id = " AND chg_address = '" . str_replace($search, $replace, $GLOBALS['one_ch']) . "'";
        }

        $soon = [];
        $res['soon_date_list'] = '';
        $date_now = isset($date_now) ? date("Y-m-d", strtotime($date_now)) . ' 23:59:59' : date("Y-m-d") . ' 23:59:59';

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games g
                        LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                        LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                        LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON ch.ch_chg_id = chg.chg_id
                        $f_c_extra",
            "g_date_schedule AS datetime",
            "g_owner_t_id>0 AND
                        g_guest_t_id>0 AND
                        (
                            g_owner_t_id = $id OR
                            g_guest_t_id = $id
                        ) AND
                        g_is_active='yes' AND
                        g_is_done = 'no' AND
                        g_date_schedule >= '$date_now' AND
                        cp_is_active = 'yes' AND
                        ch_is_active = 'yes'
                        $q_ch_id
                        $q_c_extra
                    GROUP BY g_date_schedule
                    ORDER BY g_date_schedule ASC
                    LIMIT 100");
        if ($temp) {
            $i = 0;
            $count_item = count($temp);
            foreach ($temp as $item) {
                $res['soon_date_list'] .= "'" . date("n/j/Y", strtotime($item['datetime'])) . "'";
                $i++;
                if ($i != $count_item) {
                    $res['soon_date_list'] .= ', ';
                }
            }
            $date_now = date("Y-m-d", strtotime($temp[0]['datetime'])) . ' 23:59:59';
        }
        $res['soon_date_now'] = $date_now;
        $date_current_now = date("Y-m-d H:i:00");

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "games g
                        LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                        LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                        LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON chg.chg_id = ch.ch_chg_id
                        LEFT JOIN " . DB_T_PREFIX . "championship_local chl ON chl.chl_id = chg.chg_chl_id
                        LEFT JOIN " . DB_T_PREFIX . "games_actions ga ON ga.ga_g_id=g.g_id
                        $f_c_extra",
            "g_owner_points,
                        g_guest_points,
                        g_owner_tehwin,
                        g_guest_tehwin,
                        g_date_schedule AS 'datetime',
                        g_is_schedule_time,
                        g_owner_t_id,
                        g_guest_t_id,
                        g_id,
                        g_ch_id,
                        g_cp_id,
                        g_cp_id_g,
                        g_date_time_zone as time_zone,
                        ch_title_" . S_LANG . " as ch_title,
                        ch_address,
                        cp_title_" . S_LANG . " as cp_title,
                        chg_title_" . S_LANG . " as chg_title,
                        chg_address,
                        chl_title_" . S_LANG . " as chl_title,
                        ch_address,
                        chg_address,
                        chl_address,
                        ch_settings,
                        ch_chc_id as type,
                        cp_substage,
                        cp_tour,
                        ga_id",
            "g_owner_t_id>0 AND
                        g_guest_t_id>0 AND
                        (
                            g_owner_t_id = $id OR
                            g_guest_t_id = $id
                        ) AND
                        g_is_active='yes' AND
                        g_is_done = 'no' AND
                        TO_DAYS(g_date_schedule) >= TO_DAYS('$date_current_now')
                        GROUP BY g_id
                        ORDER BY g_date_schedule ASC",
            false, true, 60);

        if ($temp) {
            $count_temp = count($temp);
            $default_timezone = date_default_timezone_get();
            for ($i = 0; $i < $count_temp; $i++) {
                $key = date("Y-m", strtotime($temp[$i]['datetime']));
                $d_key = date("d", strtotime($temp[$i]['datetime']));
                $ch_key = $temp[$i]['g_ch_id'];
                $temp[$i]['ch_settings'] = unserialize($temp[$i]['ch_settings']) ?? [];

                // Обработка часового пояса
                $time_zone = $temp[$i]['time_zone'] ?? 0;
                if (is_numeric($time_zone)) {
                    $offset = (int)$time_zone;
                    $timezone = $offset >= 0 ? "Etc/GMT-" . $offset : "Etc/GMT+" . abs($offset);
                    if (date_default_timezone_set($timezone)) {
                        $temp[$i]['time_zone_title'] = date("T");
                    } else {
                        $temp[$i]['time_zone_title'] = 'Unknown';
                    }
                } else {
                    $temp[$i]['time_zone_title'] = 'Unknown';
                    $time_zone = 0;
                }
                $temp[$i]['datetime_gmt'] = strtotime($temp[$i]['datetime']) - ($time_zone * 3600);
                $temp[$i]['time_gmt'] = date("H:i", $temp[$i]['datetime_gmt']);
                $temp[$i]['date_gmt'] = date("r", $temp[$i]['datetime_gmt']);

                // Обработка stageDateTime
                $tour = $temp[$i]['cp_tour'] ?? '';
                $substage = $temp[$i]['cp_substage'] ?? '';
                if (isset($temp[$i]['ch_settings']['stageDateTime'][$tour][$substage])) {
                    $stage_datetime = $temp[$i]['ch_settings']['stageDateTime'][$tour][$substage];
                    $temp[$i]['ch_settings']['stageDateTimeS'][$tour][$substage] = strtotime("$stage_datetime +00:00");
                    $temp[$i]['ch_settings']['stageDateTimeDF'][$tour][$substage] = date("H:i", strtotime("$stage_datetime +00:00"));
                }

                $soon[$key]['caption'] = $month_i[date("m", strtotime($temp[$i]['datetime']))] . ' ' . date("Y", strtotime($temp[$i]['datetime']));
                $soon[$key]['data'][$d_key]['caption'] = $wday[date("N", strtotime($temp[$i]['datetime']))] . '. ' . date("j", strtotime($temp[$i]['datetime']));
                $soon[$key]['data'][$d_key]['data'][$ch_key]['caption'] = $temp[$i]['chg_title'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['address'] = $temp[$i]['ch_address'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['path'] = !empty($temp[$i]['ch_settings']['ch_address_path']) ? $temp[$i]['ch_settings']['ch_address_path'] . '/' : '';

                if ($temp[$i]['type'] == 1 || $temp[$i]['type'] == 3) { // rugby 15, rugby super 15
                    $temp[$i]['an_type'] = 'game';
                    $temp[$i]['title'] = trim($temp[$i]['chg_title']) . '. ' . trim($temp[$i]['ch_title']);
                    $q_extra = " AND ( t_id = '" . $temp[$i]['g_owner_t_id'] . "' OR t_id = '" . $temp[$i]['g_guest_t_id'] . "' ) ";
                    $team = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_id, t_title_" . S_LANG . " as title", "t_is_delete = 'no' $q_extra ORDER BY t_id");
                    if ($team) {
                        foreach ($team as $item) {
                            if ($item['t_id'] == $temp[$i]['g_owner_t_id']) {
                                $temp[$i]['owner']['title'] = stripcslashes($item['title']);
                                $temp[$i]['owner']['photo_main'] = $this->getPhotoMain($item['t_id'], 'team');
                            }
                            if ($item['t_id'] == $temp[$i]['g_guest_t_id']) {
                                $temp[$i]['guest']['title'] = stripcslashes($item['title']);
                                $temp[$i]['guest']['photo_main'] = $this->getPhotoMain($item['t_id'], 'team');
                            }
                        }
                    }
                    $temp[$i]['is_detailed'] = !is_null($temp[$i]['ga_id']);
                    $temp[$i]['month_name'] = $month[date("m", strtotime($temp[$i]['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$temp[$i]['g_ch_id'] . '-' . $temp[$i]['g_id']] = $temp[$i];
                } elseif ($temp[$i]['type'] == 2 && empty($soon[$temp[$i]['g_ch_id']])) { // rugby 7
                    $temp[$i]['an_type'] = 'competition';
                    $temp[$i]['month_name'] = $month[date("m", strtotime($temp[$i]['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$temp[$i]['g_ch_id']] = $temp[$i];
                }
            }
            date_default_timezone_set($default_timezone);
        }
        $res['soon'] = $soon;
        return $res;
    }
}