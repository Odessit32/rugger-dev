<?php
// Отключаем отображение ошибок для пользователей
ini_set('display_errors', '0');
// Убедимся, что ошибки записываются в лог
ini_set('log_errors', '1');
// Укажем путь к файлу лога ошибок (если нужно изменить)
ini_set('error_log', '/home/k/kredoo3g/rugger.info/public_html/error_log');
// Подключаем класс database
require_once __DIR__ . '/DB.php'; // Укажи правильный путь, если DB.php находится в другой папке
class timetable {
    private $hdl;

    public function __construct() {
        $this->hdl = database::getInstance();
    }

    /**
     * @param string|bool $date_type Тип даты ('week', 'month', 'date') или false
     * @param string|null &$date_now Текущая дата (передаётся по ссылке)
     * @param string|bool $one_ch ID чемпионата или false
     * @param bool &$is_empty Флаг пустого результата (передаётся по ссылке)
     * @return array
     */
    public function getTimetableSoon($date_type = false, &$date_now = null, $one_ch = false, &$is_empty = false): array {
        global $conf, $month, $month_i, $wday, $section_type, $section_type_id;

        $res = ['soon' => [], 'soon_date_list' => '', 'soon_date_now' => ''];
        if (empty($date_type)) {
            return $res;
        }

        $q_date = '';
        $f_c_extra = '';
        $q_c_extra = '';
        $q_ch_id = '';
        $search = ["\\'", '\\"', "'", '"'];
        $replace = ['', '', '', ''];

        if ($one_ch && $one_ch !== 'all') {
            $q_ch_id = " AND chg_address = '" . str_replace($search, $replace, $one_ch) . "'";
        }

        $date_now = $date_now ?: date("Y-m-d") . ' 00:00:00';
        $date_now = date("Y-m-d", strtotime($date_now)) . ' 00:00:00';

        if (!empty($section_type) && !empty($section_type_id)) {
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON chg.chg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '$section_type_id'";
                    break;
            }
        }

        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games g
                LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON ch.ch_chg_id = chg.chg_id
                $f_c_extra",
            "g_date_schedule AS datetime",
            "g_owner_t_id > 0 AND
                g_guest_t_id > 0 AND
                g_is_active = 'yes' AND
                g_is_done = 'no' AND
                g_date_schedule >= '$date_now' AND
                cp_is_active = 'yes' AND
                ch_is_active = 'yes'
                $q_ch_id
                $q_c_extra
            GROUP BY g_date_schedule
            ORDER BY g_date_schedule ASC
            LIMIT 100"
        );

        if ($temp) {
            $dates = [];
            foreach ($temp as $item) {
                $dates[] = "'" . date("n/j/Y", strtotime($item['datetime'])) . "'";
            }
            $res['soon_date_list'] = implode(', ', $dates);
            $date_now = date("Y-m-d", strtotime($temp[0]['datetime'])) . ' 00:00:00';
        } else {
            $is_empty = true;
        }

        switch ($date_type) {
            case 'week':
                $q_date = " AND WEEK(g_date_schedule, 1) = WEEK('$date_now', 1) AND YEAR(g_date_schedule) = YEAR('$date_now')";
                break;
            case 'month':
                $q_date = " AND MONTH(g_date_schedule) = MONTH('$date_now') AND YEAR(g_date_schedule) = YEAR('$date_now')";
                break;
            case 'date':
                $q_date = " AND DAY(g_date_schedule) = DAY('$date_now') AND WEEK(g_date_schedule, 1) = WEEK('$date_now', 1) AND YEAR(g_date_schedule) = YEAR('$date_now')";
                break;
        }

        $res['soon_date_now'] = $date_now;
        $date_current_now = date("Y-m-d H:i:00");

        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games g
                LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON chg.chg_id = ch.ch_chg_id
                LEFT JOIN " . DB_T_PREFIX . "championship_local chl ON chl.chl_id = chg.chg_chl_id
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
                g_info,
                g_selected",
            "g_owner_t_id > 0 AND
                g_guest_t_id > 0 AND
                g_is_active = 'yes' AND
                g_is_done = 'no' AND
                TO_DAYS(g_date_schedule) >= TO_DAYS('$date_current_now')
                $q_date
                $q_ch_id
                $q_c_extra
            ORDER BY g_date_schedule ASC",
            false,
            true,
            60
        );

        $soon = [];
        if ($temp) {
            $g_ids = array_column($temp, 'g_id');
            $staff_count_a = [];

            if (!empty($g_ids)) {
                $staff_count = $this->hdl->selectElem(
                    DB_T_PREFIX . "connection_g_st g_st",
                    "cngst_g_id as g_id, count(*) as g_count",
                    "cngst_g_id IN (" . implode(', ', $g_ids) . ") GROUP BY cngst_g_id"
                );
                if ($staff_count) {
                    foreach ($staff_count as $item) {
                        $staff_count_a[$item['g_id']] = $item['g_count'];
                    }
                }
            }

            foreach ($temp as $i => $item) {
                $key = date("Y-m", strtotime($item['datetime']));
                $d_key = date("d", strtotime($item['datetime']));
                $ch_key = $item['g_ch_id'];

                $item['g_info'] = json_decode($item['g_info'] ?? '{}', true) ?: [];
                $item['ch_settings'] = unserialize($item['ch_settings'] ?? 'a:0:{}') ?: [];

                $current_tz = date_default_timezone_get();
                date_default_timezone_set("Europe/Moscow");
                $item['time_zone_title'] = date("T");
                $item['date_gmt'] = date("r", strtotime($item['datetime']));
                date_default_timezone_set($current_tz);

                $item['datetime_gmt'] = strtotime($item['datetime']) - ($item['time_zone'] ?? 0) * 3600;
                $item['time_gmt'] = date("H:i", $item['datetime_gmt']);

                $soon[$key]['caption'] = $month_i[date("m", strtotime($item['datetime']))] . ' ' . date("Y", strtotime($item['datetime']));
                $soon[$key]['data'][$d_key]['caption'] = $wday[date("N", strtotime($item['datetime']))] . '. ' . date("j", strtotime($item['datetime']));
                $soon[$key]['data'][$d_key]['data'][$ch_key]['caption'] = $item['chg_title'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['address'] = $item['ch_address'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['path'] = $item['ch_settings']['ch_address_path'] ?? '';

                if (in_array($item['type'], [1, 3])) { // rugby 15, rugby super 15
                    $item['an_type'] = 'game';
                    $item['title'] = trim($item['chg_title']) . '. ' . trim($item['ch_title']);
                    $q_extra = " AND ( t_id = '" . $item['g_owner_t_id'] . "' OR t_id = '" . $item['g_guest_t_id'] . "' ) ";
                    $team = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_id, t_title_" . S_LANG . " as title", "t_is_delete = 'no' $q_extra ORDER BY t_id");

                    if ($team) {
                        foreach ($team as $team_item) {
                            if ($team_item['t_id'] == $item['g_owner_t_id']) {
                                $item['owner'] = [
                                    'title' => stripslashes($team_item['title']),
                                    'photo_main' => $this->getPhotoMain($team_item['t_id'], 'team')
                                ];
                            }
                            if ($team_item['t_id'] == $item['g_guest_t_id']) {
                                $item['guest'] = [
                                    'title' => stripslashes($team_item['title']),
                                    'photo_main' => $this->getPhotoMain($team_item['t_id'], 'team')
                                ];
                            }
                        }
                    }

                    $item['is_detailed'] = !empty($item['ga_id']) ||
                        !empty($item['g_info']['live']) || (
                            !empty($item['g_info']['custom_report']) && (
                                !empty($item['g_info']['town']) ||
                                !empty($item['g_info']['stadium']) ||
                                !empty($item['g_info']['viewers']) ||
                                !empty($item['g_info']['main_judge']) ||
                                !empty($item['g_info']['side_referee']) ||
                                !empty($item['g_info']['video_referee'])
                            )
                        ) || (!empty($staff_count_a[$item['g_id']]) && $staff_count_a[$item['g_id']] >= 30);

                    $item['month_name'] = $month[date("m", strtotime($item['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$item['g_ch_id'] . '-' . $item['g_id']] = $item;
                } elseif ($item['type'] == 2 && empty($soon[$item['g_ch_id']])) { // rugby 7
                    $item['an_type'] = 'competition';
                    $item['month_name'] = $month[date("m", strtotime($item['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$item['g_ch_id']] = $item;
                }
            }
        }

        $res['soon'] = $soon;
        $res['files'] = []; // Добавляем для совместимости с шаблоном
        return $res;
    }

    /**
     * @param string|bool $date_type Тип даты ('week', 'month', 'date') или false
     * @param string|null &$date_now Текущая дата (передаётся по ссылке)
     * @param string|bool $one_ch ID чемпионата или false
     * @param bool &$is_empty Флаг пустого результата (передаётся по ссылке)
     * @return array
     */
    public function getTimetableResults($date_type = false, &$date_now = null, $one_ch = false, &$is_empty = false): array {
        global $conf, $month, $month_i, $wday, $section_type, $section_type_id;

        $res = ['soon' => [], 'soon_date_list' => '', 'soon_date_now' => ''];
        if (empty($date_type)) {
            return $res;
        }

        $g_ids = [];
        $q_date = '';
        $f_c_extra = '';
        $q_c_extra = '';
        $q_ch_id = '';
        $search = ["\\'", '\\"', "'", '"'];
        $replace = ['', '', '', ''];

        if ($one_ch && $one_ch !== 'all') {
            $q_ch_id = " AND chg_address = '" . str_replace($search, $replace, $one_ch) . "'";
        }

        $date_now = $date_now ?: date("Y-m-d") . ' 23:59:59';
        $date_now = date("Y-m-d", strtotime($date_now)) . ' 23:59:59';

        if (!empty($section_type) && !empty($section_type_id)) {
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON chg.chg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '$section_type_id'";
                    break;
            }
        }

        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games g
                LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON ch.ch_chg_id = chg.chg_id
                $f_c_extra",
            "g_date_schedule AS datetime",
            "g_owner_t_id > 0 AND
                g_guest_t_id > 0 AND
                g_is_active = 'yes' AND
                g_is_done = 'yes' AND
                g_date_schedule <= '$date_now' AND
                cp_is_active = 'yes' AND
                ch_is_active = 'yes'
                $q_ch_id
                $q_c_extra
            GROUP BY g_date_schedule
            ORDER BY g_date_schedule DESC
            LIMIT 100"
        );

        if ($temp) {
            $dates = [];
            foreach ($temp as $item) {
                $dates[] = "'" . date("n/j/Y", strtotime($item['datetime'])) . "'";
            }
            $res['soon_date_list'] = implode(', ', $dates);
            $date_now = date("Y-m-d", strtotime($temp[0]['datetime'])) . ' 23:59:59';
        } else {
            $is_empty = true;
        }

        switch ($date_type) {
            case 'week':
                $q_date = " AND WEEK(g_date_schedule, 1) = WEEK('$date_now', 1) AND YEAR(g_date_schedule) = YEAR('$date_now')";
                break;
            case 'month':
                $q_date = " AND MONTH(g_date_schedule) = MONTH('$date_now') AND YEAR(g_date_schedule) = YEAR('$date_now')";
                break;
            case 'date':
                $q_date = " AND DAY(g_date_schedule) = DAY('$date_now') AND WEEK(g_date_schedule, 1) = WEEK('$date_now', 1) AND YEAR(g_date_schedule) = YEAR('$date_now')";
                break;
        }

        $res['soon_date_now'] = $date_now;
        $date_current_now = date("Y-m-d H:i:00");

        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "games g
                LEFT JOIN " . DB_T_PREFIX . "championship ch ON g.g_ch_id = ch.ch_id
                LEFT JOIN " . DB_T_PREFIX . "competitions cp ON g.g_cp_id = cp.cp_id
                LEFT JOIN " . DB_T_PREFIX . "championship_group chg ON chg.chg_id = ch.ch_chg_id
                LEFT JOIN " . DB_T_PREFIX . "championship_local chl ON chl.chl_id = chg.chg_chl_id
                LEFT JOIN " . DB_T_PREFIX . "games_actions ga ON ga.ga_g_id = g.g_id
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
                ga_id,
                g_info,
                g_selected",
            "g_owner_t_id > 0 AND
                g_guest_t_id > 0 AND
                g_is_active = 'yes' AND
                g_is_done = 'yes' AND
                TO_DAYS(g_date_schedule) <= TO_DAYS('$date_current_now')
                $q_date
                $q_ch_id
                $q_c_extra
            GROUP BY g_id
            ORDER BY g_date_schedule DESC",
            false,
            true,
            60
        );

        $soon = [];
        if ($temp) {
            $g_ids = array_column($temp, 'g_id');
            $staff_count_a = [];

            if (!empty($g_ids)) {
                $staff_count = $this->hdl->selectElem(
                    DB_T_PREFIX . "connection_g_st g_st",
                    "cngst_g_id as g_id, count(*) as g_count",
                    "cngst_g_id IN (" . implode(', ', $g_ids) . ") GROUP BY cngst_g_id"
                );
                if ($staff_count) {
                    foreach ($staff_count as $item) {
                        $staff_count_a[$item['g_id']] = $item['g_count'];
                    }
                }
            }

            foreach ($temp as $i => $item) {
                $key = date("Y-m", strtotime($item['datetime']));
                $d_key = date("d", strtotime($item['datetime']));
                $ch_key = $item['g_ch_id'];

                $item['g_info'] = json_decode($item['g_info'] ?? '{}', true) ?: [];
                $item['ch_settings'] = unserialize($item['ch_settings'] ?? 'a:0:{}') ?: [];

                $current_tz = date_default_timezone_get();
                date_default_timezone_set("Europe/Moscow");
                $item['time_zone_title'] = date("T");
                date_default_timezone_set($current_tz);

                $item['datetime_gmt'] = strtotime($item['datetime']) - ($item['time_zone'] ?? 0) * 3600;
                $item['time_gmt'] = date("H:i", $item['datetime_gmt']);

                $item['ch_settings']['stageDateTimeS'][$item['cp_tour']][$item['cp_substage']] = !empty($item['ch_settings']['stageDateTime'][$item['cp_tour']][$item['cp_substage']])
                    ? strtotime($item['ch_settings']['stageDateTime'][$item['cp_tour']][$item['cp_substage']] . " +00:00")
                    : '';
                $item['ch_settings']['stageDateTimeDF'][$item['cp_tour']][$item['cp_substage']] = !empty($item['ch_settings']['stageDateTime'][$item['cp_tour']][$item['cp_substage']])
                    ? date("H:i", strtotime($item['ch_settings']['stageDateTime'][$item['cp_tour']][$item['cp_substage']] . " +00:00"))
                    : '';

                $soon[$key]['caption'] = $month_i[date("m", strtotime($item['datetime']))] . ' ' . date("Y", strtotime($item['datetime']));
                $soon[$key]['data'][$d_key]['caption'] = $wday[date("N", strtotime($item['datetime']))] . '. ' . date("j", strtotime($item['datetime']));
                $soon[$key]['data'][$d_key]['data'][$ch_key]['caption'] = $item['chg_title'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['address'] = $item['ch_address'];
                $soon[$key]['data'][$d_key]['data'][$ch_key]['path'] = $item['ch_settings']['ch_address_path'] ?? '';

                if (in_array($item['type'], [1, 3])) { // rugby 15, rugby super 15
                    $item['an_type'] = 'game';
                    $item['title'] = trim($item['chg_title']) . '. ' . trim($item['ch_title']);
                    $q_extra = " AND ( t_id = '" . $item['g_owner_t_id'] . "' OR t_id = '" . $item['g_guest_t_id'] . "' ) ";
                    $team = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_id, t_title_" . S_LANG . " as title", "t_is_delete = 'no' $q_extra ORDER BY t_id");

                    if ($team) {
                        foreach ($team as $team_item) {
                            if ($team_item['t_id'] == $item['g_owner_t_id']) {
                                $item['owner'] = [
                                    'title' => stripslashes($team_item['title']),
                                    'photo_main' => $this->getPhotoMain($team_item['t_id'], 'team')
                                ];
                            }
                            if ($team_item['t_id'] == $item['g_guest_t_id']) {
                                $item['guest'] = [
                                    'title' => stripslashes($team_item['title']),
                                    'photo_main' => $this->getPhotoMain($team_item['t_id'], 'team')
                                ];
                            }
                        }
                    }

                    $item['is_detailed'] = !empty($item['ga_id']) ||
                        !empty($item['g_info']['live']) || (
                            !empty($item['g_info']['custom_report']) && (
                                !empty($item['g_info']['town']) ||
                                !empty($item['g_info']['stadium']) ||
                                !empty($item['g_info']['viewers']) ||
                                !empty($item['g_info']['main_judge']) ||
                                !empty($item['g_info']['side_referee']) ||
                                !empty($item['g_info']['video_referee'])
                            )
                        ) || (!empty($staff_count_a[$item['g_id']]) && $staff_count_a[$item['g_id']] >= 30);

                    $item['month_name'] = $month[date("m", strtotime($item['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$item['g_ch_id'] . '-' . $item['g_id']] = $item;
                } elseif ($item['type'] == 2 && empty($soon[$item['g_ch_id']])) { // rugby 7
                    $item['an_type'] = 'competition';
                    $item['month_name'] = $month[date("m", strtotime($item['datetime']))];
                    $soon[$key]['data'][$d_key]['data'][$ch_key]['data'][$item['g_ch_id']] = $item;
                }
            }
        }

        $res['soon'] = $soon;
        $res['files'] = []; // Добавляем для совместимости с шаблоном
        return $res;
    }

    /**
     * @param int $id ID объекта
     * @param string $type Тип объекта
     * @return array|bool
     */
    private function getPhotoMain(int $id, string $type = '') {
        if (empty($type)) {
            return false;
        }

        $temp_photo = $this->hdl->selectElem(
            DB_T_PREFIX . "photos",
            "*",
            "ph_is_active = 'yes' AND ph_type_id = '$id' AND ph_type = '$type' AND ph_type_main = 'yes' LIMIT 0, 1"
        );

        if ($temp_photo) {
            $photo = $temp_photo[0];
            $photo['ph_about'] = !empty($photo['ph_about']) ? stripslashes($photo['ph_about']) : '';
            $photo['ph_about'] = strip_tags($photo['ph_about']);
            $photo['ph_main'] = substr($photo['ph_path'], 0, strrpos($photo['ph_path'], '.')) . "-s_main" . strrchr($photo['ph_path'], ".");
            $photo['ph_small'] = substr($photo['ph_path'], 0, strrpos($photo['ph_path'], '.')) . "-small" . strrchr($photo['ph_path'], ".");
            $photo['ph_informer'] = substr($photo['ph_path'], 0, strrpos($photo['ph_path'], '.')) . "-informer" . strrchr($photo['ph_path'], ".");
            return $photo;
        }

        return false;
    }

    /**
     * @param string $type_page Тип страницы ('time' или 'done')
     * @return array
     */
    public function getChampionshipsWGroups(string $type_page = 'time'): array {
        global $section_type, $section_type_id;

        $f_c_extra = '';
        $q_c_extra = '';

        if (!empty($section_type) && !empty($section_type_id)) {
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON chg.chg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '$section_type_id'";
                    break;
            }
        }

        $game_q = $type_page === 'time'
            ? " AND g_is_done = 'no' AND g_date_schedule > '" . date("Y-m-d 00:00:00") . "' "
            : " AND g_is_done = 'yes' ";

        $res = [];
        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "championship_group chg
                LEFT JOIN " . DB_T_PREFIX . "championship_local chl ON chg.chg_chl_id = chl.chl_id
                LEFT JOIN " . DB_T_PREFIX . "championship ch ON ch.ch_chg_id = chg.chg_id
                LEFT JOIN " . DB_T_PREFIX . "games g ON g.g_ch_id = ch.ch_id
                $f_c_extra",
            "chg_id as id,
                chg_title_" . S_LANG . " as title,
                chg_address as address,
                chg_is_menu AS is_menu,
                chl_title_" . S_LANG . " as l_title,
                chl_id as l_id",
            "chg_is_active = 'yes' AND
                chg_is_menu = 'yes' AND
                chg_address != ''
                $game_q
                $q_c_extra
            GROUP BY chg_id
            ORDER BY chg_is_main DESC, chg_order DESC"
        );

        if ($temp) {
            foreach ($temp as $item) {
                $item['title'] = stripslashes($item['title']);
                if (empty($res[$item['l_id']]['title'])) {
                    $res[$item['l_id']]['title'] = $item['l_title'];
                }
                $res[$item['l_id']]['data'][] = $item;
            }
        }

        return $res;
    }
}