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

    //  ///////////////////////////////////////////////////////////////////////////////////////

    public function createTeam($post) {  
        if (!$this->hdl) {
            error_log("[ERROR] Не удалось подключиться к базе данных в createTeam()", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }

        if (!defined('USER_ID')) {
            error_log("[ERROR] Константа USER_ID не определена в createTeam()", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }

        if (!defined('DB_T_PREFIX')) {
            error_log("[ERROR] Константа DB_T_PREFIX не определена в createTeam()", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }

        // Безопасная обработка чекбоксов
        $is_active = isset($post['t_is_active']) && $post['t_is_active'] ? 'yes' : 'no';
        $t_is_technical = isset($post['t_is_technical']) && $post['t_is_technical'] ? 'yes' : 'no';
        $t_is_detailed = isset($post['t_is_detailed']) && $post['t_is_detailed'] ? 'yes' : 'no';

        $t_date_foundation = '0000-00-00'; // Значение по умолчанию
        if (!empty($post['t_date_day']) && $post['t_date_day'] > 0 && !empty($post['t_date_month']) && $post['t_date_month'] > 0 && !empty($post['t_date_year']) && $post['t_date_year'] > 0) {
            $post['t_date_day'] = intval($post['t_date_day']);
            $post['t_date_month'] = intval($post['t_date_month']);
            $post['t_date_year'] = intval($post['t_date_year']);
            $t_date_foundation = sprintf("%04d-%02d-%02d", $post['t_date_year'], $post['t_date_month'], $post['t_date_day']);
        }

        $search = array("'", '"');
        $replace = array('', '');
        $search_a = array("_", " ", "'", '"');
        $replace_a = array("-", "-", '', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elem = array(
            't_date_foundation' => $t_date_foundation,
            't_cn_id' => (!empty($post['t_cn_id']) && $post['t_cn_id'] > 0) ? intval($post['t_cn_id']) : null, // t_cn_id (страна)
            't_ct_id' => (!empty($post['t_ct_id']) && $post['t_ct_id'] > 0) ? intval($post['t_ct_id']) : null, // t_ct_id (город)
            't_title_ru' => str_replace($search, $replace, $post['t_title_ru'] ?? '') ?: 'Без названия', // t_title_ru
            't_title_ua' => str_replace($search, $replace, $post['t_title_ua'] ?? '') ?: 'Без назви',    // t_title_ua
            't_title_en' => str_replace($search, $replace, $post['t_title_en'] ?? '') ?: 'No name',      // t_title_en
            't_description_ru' => addslashes($post['t_description_ru'] ?? ''),
            't_description_ua' => addslashes($post['t_description_ua'] ?? ''),
            't_description_en' => addslashes($post['t_description_en'] ?? ''),
            't_text_ru' => addslashes($post['t_text_ru'] ?? ''),
            't_text_ua' => addslashes($post['t_text_ua'] ?? ''),
            't_text_en' => addslashes($post['t_text_en'] ?? ''),
            't_is_active' => $is_active,
            't_datetime_add' => $current_time,
            't_datetime_edit' => $current_time,
            't_author' => USER_ID,
            't_std_id' => (!empty($post['t_std_id']) && $post['t_std_id'] > 0) ? intval($post['t_std_id']) : null, // t_std_id (стадион)
            't_is_technical' => $t_is_technical,
            't_is_detailed' => $t_is_detailed,
            't_is_delete' => 'no',
            't_address' => str_replace($search_a, $replace_a, $post['t_address'] ?? ''),
            't_filter' => str_replace($search, $replace, $post['t_filter'] ?? ''),
            't_info' => ''
        );

        try {
            if ($this->hdl->addElem(DB_T_PREFIX . "team", $elem)) {
                error_log("[INFO] Команда успешно добавлена: " . ($post['t_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            } else {
                error_log("[ERROR] Не удалось добавить команду: неизвестная ошибка", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return false;
            }
        } catch (Exception $e) {
            error_log("[ERROR] Исключение при добавлении команды: " . $e->getMessage(), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
    }

    public function updateTeam($post) {  
        $post['t_id'] = intval($post['t_id']);
        if ($post['t_id'] < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "team", "*", "t_id='" . $post['t_id'] . "' AND t_is_delete = 'no'");
        if (!$temp) return false;

        // Безопасная обработка чекбоксов
        $is_active = isset($post['t_is_active']) && $post['t_is_active'] ? 'yes' : 'no';
        $t_is_technical = isset($post['t_is_technical']) && $post['t_is_technical'] ? 'yes' : 'no';
        $t_is_detailed = isset($post['t_is_detailed']) && $post['t_is_detailed'] ? 'yes' : 'no';

        $t_date_foundation = '0000-00-00'; // Значение по умолчанию
        if (!empty($post['t_date_day']) && $post['t_date_day'] > 0 && !empty($post['t_date_month']) && $post['t_date_month'] > 0 && !empty($post['t_date_year']) && $post['t_date_year'] > 0) {
            $post['t_date_day'] = intval($post['t_date_day']);
            $post['t_date_month'] = intval($post['t_date_month']);
            $post['t_date_year'] = intval($post['t_date_year']);
            $t_date_foundation = sprintf("%04d-%02d-%02d", $post['t_date_year'], $post['t_date_month'], $post['t_date_day']);
        }

        $search = array("'", '"');
        $replace = array('', '');
        $search_a = array("_", " ", "'", '"');
        $replace_a = array("-", "-", '', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elems = array(
            "t_date_foundation" => $t_date_foundation,
            "t_cn_id" => (!empty($post['t_cn_id']) && $post['t_cn_id'] > 0) ? intval($post['t_cn_id']) : null,
            "t_ct_id" => (!empty($post['t_ct_id']) && $post['t_ct_id'] > 0) ? intval($post['t_ct_id']) : null,
            "t_title_ru" => str_replace($search, $replace, $post['t_title_ru'] ?? '') ?: 'Без названия',
            "t_title_ua" => str_replace($search, $replace, $post['t_title_ua'] ?? '') ?: 'Без назви',
            "t_title_en" => str_replace($search, $replace, $post['t_title_en'] ?? '') ?: 'No name',
            "t_description_ru" => addslashes($post['t_description_ru'] ?? ''),
            "t_description_ua" => addslashes($post['t_description_ua'] ?? ''),
            "t_description_en" => addslashes($post['t_description_en'] ?? ''),
            "t_text_ru" => addslashes($post['t_text_ru'] ?? ''),
            "t_text_ua" => addslashes($post['t_text_ua'] ?? ''),
            "t_text_en" => addslashes($post['t_text_en'] ?? ''),
            "t_is_active" => $is_active,
            "t_datetime_edit" => $current_time,
            "t_author" => USER_ID,
            "t_std_id" => (!empty($post['t_std_id']) && $post['t_std_id'] > 0) ? intval($post['t_std_id']) : null,
            "t_is_technical" => $t_is_technical,
            "t_is_detailed" => $t_is_detailed,
            "t_address" => str_replace($search_a, $replace_a, $post['t_address'] ?? ''),
            "t_filter" => str_replace($search, $replace, $post['t_filter'] ?? '')
        );
        $condition = array(
            "t_id" => $post['t_id']
        );

        if ($this->hdl->updateElem(DB_T_PREFIX . "team", $elems, $condition)) {
            error_log("[INFO] Команда успешно обновлена: ID " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        } else {
            error_log("[ERROR] Не удалось обновить команду: ID " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
    }

    public function saveTeamInfo($post) {
        $post['t_id'] = intval($post['t_id']);
        if ($post['t_id'] < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "team", "t_id, t_info", "t_id='" . $post['t_id'] . "' AND t_is_delete = 'no'");
        if (!$temp) return false;

        $temp = $temp[0];
        $info = unserialize($temp['t_info']);
        $info['show_app'] = array();
        if (!empty($post['show_app'])) {
            foreach ($post['show_app'] as $item) {
                if (!empty($item) && $item > 0) {
                    $info['show_app'][] = intval($item);
                }
            }
        }

        $info['tab_titles'] = array();
        if (!empty($post['tab_titles'])) {
            $info['tab_titles'] = $post['tab_titles'];
        }
        $elems = array(
            "t_info" => serialize($info)
        );
        $condition = array(
            "t_id" => $post['t_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "team", $elems, $condition)) {
            error_log("[INFO] Информация о команде успешно сохранена: ID " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось сохранить информацию о команде: ID " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function getTeamList() {
        $q_cn = "";
        if (isset($_GET['country'])) {
            $cn_id = $_GET['country'];
            if ($cn_id == 'all') $q_cn = "";
            elseif ($cn_id == 0) $q_cn = " AND t_cn_id = '0' ";
            else $q_cn = " AND t_cn_id = '" . intval($cn_id) . "' ";
        }
        if (isset($_GET['filter'])) {
            $filter = $_GET['filter'];
            if ($filter == 'all') {
                $q_cn .= "";
            } elseif ($filter == 'null') {
                $q_cn .= " AND t_filter = '' ";
            } else {
                $q_cn .= " AND t_filter = '" . str_replace(array(), array(), $filter) . "' ";
            }
        }
        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "team t LEFT JOIN " . DB_T_PREFIX . "photos p ON p.ph_type = 'team' AND p.ph_type_id = t.t_id AND p.ph_type_main = 'yes'",
            "t.t_id,
             t.t_title_ru,
             t.t_is_active,
             t.t_is_technical,
             t.t_is_detailed,
             t.t_filter,
             p.ph_id,
             p.ph_path,
             p.ph_folder",
            "t.t_is_delete = 'no' $q_cn ORDER BY t.t_title_ru ASC, t.t_id DESC"
        );
        if ($temp) {
            foreach ($temp as &$item) {
                if ($item['ph_id']) {
                    $item['logo'] = "/upload/photos" . $item['ph_folder'] . substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], ".")) * (-1)) . "-small" . strrchr($item['ph_path'], ".");
                } else {
                    $item['logo'] = false;
                }
            }
        }
        return $temp;
    }

    public function getTeamItem($item = 0) {
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "team", "*", "t_id=$item AND t_is_delete = 'no'");
            if (!$temp) return false;
            $temp = $temp[0];
            $temp['t_filter'] = str_replace($search, $replace, $temp['t_filter']);
            $temp['t_title_ru'] = str_replace($search, $replace, $temp['t_title_ru']);
            $temp['t_title_ua'] = str_replace($search, $replace, $temp['t_title_ua']);
            $temp['t_title_en'] = str_replace($search, $replace, $temp['t_title_en']);
            $temp['t_description_ru'] = stripcslashes($temp['t_description_ru']);
            $temp['t_description_ua'] = stripcslashes($temp['t_description_ua']);
            $temp['t_description_en'] = stripcslashes($temp['t_description_en']);
            $temp['t_text_ru'] = stripcslashes($temp['t_text_ru']);
            $temp['t_text_ua'] = stripcslashes($temp['t_text_ua']);
            $temp['t_text_en'] = stripcslashes($temp['t_text_en']);
            // info about team
            $temp['games'] = $this->hdl->selectElem(DB_T_PREFIX . "games", "COUNT(g_id) as c", "g_owner_t_id='$item' OR g_guest_t_id = '$item'");
            $temp['games'] = $temp['games'][0]['c'];
            $temp['championships'] = $this->hdl->selectElem(DB_T_PREFIX . "championship LEFT JOIN " . DB_T_PREFIX . "connection_t_ch ON ch_id = cntch_ch_id", "COUNT(ch_id) as c", "cntch_t_id = '$item'");
            $temp['championships'] = $temp['championships'][0]['c'];
            $temp['clubs'] = $this->hdl->selectElem(DB_T_PREFIX . "club LEFT JOIN " . DB_T_PREFIX . "connection_t_cl ON cl_id = cntcl_cl_id", "COUNT(cl_id) as c", "cntcl_t_id = '$item'");
            $temp['clubs'] = $temp['clubs'][0]['c'];
            $temp['t_info'] = unserialize($temp['t_info']);
            return $temp;
        } else return false;
    }

    public function deleteTeam($t_id) {
        $t_id = intval($t_id);
        if ($t_id > 0) {
            $elems = array(
                "t_is_delete" => 'yes'
            );
            $condition = array(
                "t_id" => $t_id
            );
            if ($this->hdl->updateElem(DB_T_PREFIX . "team", $elems, $condition)) {
                // Удаляем связи людей с этой командой
                $conn_elems = array("cnstt_is_delete" => 'yes');
                $conn_condition = array("cnstt_t_id" => $t_id);
                $this->hdl->updateElem(DB_T_PREFIX . "connection_st_t", $conn_elems, $conn_condition);

                error_log("[INFO] Команда успешно помечена как удалённая: ID " . $t_id, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            } else {
                error_log("[ERROR] Не удалось пометить команду как удалённую: ID " . $t_id, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return false;
            }
        }
        error_log("[ERROR] Неверный ID команды для удаления: " . $t_id, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    //   /////////////////////////////////////////////////////////////////////////////////////

    // Метод для сброса файлового кеша состава команды
    private function clearTeamStaffCache($t_id) {
        $t_id = intval($t_id);
        $types = array('player', 'rest', 'head');
        $prefixes = array('team_staff_byapp_', 'team_staff_byname_');

        foreach ($types as $type) {
            foreach ($prefixes as $prefix) {
                $cache_key = $prefix . $t_id . '_' . $type;
                $cache_file = sys_get_temp_dir() . '/' . $cache_key . '.cache';
                if (file_exists($cache_file)) {
                    @unlink($cache_file);
                }
            }
        }
    }

    public function createConnectStT($post) {
        error_log("[DEBUG] createConnectStT вызван с параметрами: " . print_r($post, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id", " cnstt_st_id = '" . intval($post['staff_id']) . "' AND cnstt_t_id = '" . intval($post['t_id']) . "' AND cnstt_app_id = '" . intval($post['appointment_id']) . "' AND cnstt_is_delete = 'no' LIMIT 1");

        error_log("[DEBUG] Результат проверки существующей связи: " . print_r($temp, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");

        if (!empty($temp[0]['cnstt_id']) && $temp[0]['cnstt_id'] > 0) {
            error_log("[INFO] Связь уже существует, возврат false", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }

        // Если дата указана пользователем - используем её, иначе используем текущую дату
        if (!empty($post['app_date_day']) && $post['app_date_day'] > 0 && !empty($post['app_date_month']) && $post['app_date_month'] > 0 && !empty($post['app_date_year']) && $post['app_date_year'] > 0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            $t_date_add = sprintf("%04d-%02d-%02d", $post['app_date_year'], $post['app_date_month'], $post['app_date_day']);
        } else {
            // Автоматически подставляем текущую дату
            $t_date_add = date('Y-m-d');
        }

        $elem = array(
            'cnstt_st_id' => intval($post['staff_id']),
            'cnstt_t_id' => intval($post['t_id']),
            'cnstt_app_id' => intval($post['appointment_id']),
            'cnstt_date_add' => $t_date_add,
            'cnstt_add_author' => USER_ID,
            'cnstt_date_quit' => NULL,
            'cnstt_quit_author' => NULL,
            'cnstt_is_delete' => 'no',
            'cnstt_order' => !empty($post['cnstt_order']) ? intval($post['cnstt_order']) : 0
        );

        error_log("[DEBUG] Попытка вставить данные: " . print_r($elem, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");

        $result = $this->hdl->addElem(DB_T_PREFIX . "connection_st_t", $elem);

        error_log("[DEBUG] Результат addElem: " . ($result ? 'TRUE' : 'FALSE'), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");

        if ($result) {
            error_log("[INFO] Связь staff-team успешно создана: staff_id " . $post['staff_id'] . ", team_id " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            // Сбрасываем кеш состава команды
            $this->clearTeamStaffCache($post['t_id']);
            return true;
        }
        error_log("[ERROR] Не удалось создать связь staff-team: staff_id " . $post['staff_id'] . ", team_id " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function createNewConnectStT($post) {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id", " cnstt_st_id = '" . intval($post['staff_id']) . "' AND cnstt_t_id = '" . intval($post['t_id']) . "' AND cnstt_app_id = '" . intval($post['appointment_id']) . "' AND cnstt_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cnstt_id'] > 0) return false;

        $t_date_add = '0000-00-00'; // Значение по умолчанию
        if (!empty($post['app_date_day']) && $post['app_date_day'] > 0 && !empty($post['app_date_month']) && $post['app_date_month'] > 0 && !empty($post['app_date_year']) && $post['app_date_year'] > 0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            $t_date_add = sprintf("%04d-%02d-%02d", $post['app_date_year'], $post['app_date_month'], $post['app_date_day']);
        }

        $elem = array(
            'cnstt_st_id' => intval($post['staff_id']),
            'cnstt_t_id' => intval($post['t_id']),
            'cnstt_app_id' => intval($post['appointment_id']),
            'cnstt_date_add' => $t_date_add,
            'cnstt_add_author' => USER_ID,
            'cnstt_date_quit' => '',
            'cnstt_quit_author' => '',
            'cnstt_is_delete' => 'no',
            'cnstt_order' => intval($post['cnstt_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX . "connection_st_t", $elem)) {
            error_log("[INFO] Новая связь staff-team успешно создана: staff_id " . $post['staff_id'] . ", team_id " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось создать новую связь staff-team: staff_id " . $post['staff_id'] . ", team_id " . $post['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function updateConnectStT($post) {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id", " cnstt_st_id = '" . intval($post['staff_id']) . "' AND cnstt_id = '" . intval($post['cnstt_id']) . "' AND cnstt_app_id = '" . intval($post['appointment_id']) . "' AND cnstt_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cnstt_id'] > 0) return false;

        $t_date_add = '0000-00-00'; // Значение по умолчанию
        if (!empty($post['app_date_day']) && $post['app_date_day'] > 0 && !empty($post['app_date_month']) && $post['app_date_month'] > 0 && !empty($post['app_date_year']) && $post['app_date_year'] > 0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            $t_date_add = sprintf("%04d-%02d-%02d", $post['app_date_year'], $post['app_date_month'], $post['app_date_day']);
        }

        $elems = array(
            "cnstt_app_id" => intval($post['appointment_id']),
            "cnstt_date_add" => $t_date_add,
            "cnstt_add_author" => USER_ID,
            "cnstt_order" => intval($post['cnstt_order'])
        );
        $condition = array(
            "cnstt_id" => $post['cnstt_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "connection_st_t", $elems, $condition)) {
            error_log("[INFO] Связь staff-team успешно обновлена: cnstt_id " . $post['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            // Получаем t_id команды для сброса кеша
            $team_info = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_t_id", "cnstt_id = '" . intval($post['cnstt_id']) . "' LIMIT 1");
            if (!empty($team_info[0]['cnstt_t_id'])) {
                $this->clearTeamStaffCache($team_info[0]['cnstt_t_id']);
            }
            return true;
        }
        error_log("[ERROR] Не удалось обновить связь staff-team: cnstt_id " . $post['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function returnConnectStT($post) {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id, cnstt_st_id, cnstt_t_id, cnstt_app_id, cnstt_date_quit", " cnstt_id = '" . intval($post['cnstt_id']) . "' AND cnstt_is_delete = 'no' LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $same = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id", "
                        cnstt_st_id = '" . $temp['cnstt_st_id'] . "' AND
                        cnstt_t_id = '" . $temp['cnstt_t_id'] . "' AND
                        cnstt_app_id = '" . $temp['cnstt_app_id'] . "' AND
                        (cnstt_date_quit IS NULL OR cnstt_date_quit = '0000-00-00 00:00:00') AND
                        cnstt_is_delete = 'no'
                        LIMIT 1");
            if ($same) return false;

            $elems = array(
                "cnstt_date_quit" => NULL,
                "cnstt_add_author" => USER_ID
            );
            $condition = array(
                "cnstt_id" => $temp['cnstt_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX . "connection_st_t", $elems, $condition)) {
                error_log("[INFO] Связь staff-team успешно восстановлена: cnstt_id " . $temp['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            }
            error_log("[ERROR] Не удалось восстановить связь staff-team: cnstt_id " . $temp['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        }
        return false;
    }

    public function quitConnectStT($post) {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id", " AND cnstt_id = '" . intval($post['cnstt_id']) . "' AND cnstt_date_quit IS NOT NULL AND cnstt_is_delete = 'no' LIMIT 1");
        if (!empty($temp[0]['cnstt_id']) && $temp[0]['cnstt_id'] > 0) return false;

        $t_date_quit = NULL; // Значение по умолчанию для MySQL 8.0
        if (!empty($post['app_date_day']) && $post['app_date_day'] > 0 && !empty($post['app_date_month']) && $post['app_date_month'] > 0 && !empty($post['app_date_year']) && $post['app_date_year'] > 0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            $t_date_quit = sprintf("%04d-%02d-%02d", $post['app_date_year'], $post['app_date_month'], $post['app_date_day']);
        }

        $elems = array(
            "cnstt_date_quit" => $t_date_quit,
            "cnstt_quit_author" => USER_ID
        );
        $condition = array(
            "cnstt_id" => $post['cnstt_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "connection_st_t", $elems, $condition)) {
            error_log("[INFO] Связь staff-team успешно завершена: cnstt_id " . $post['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось завершить связь staff-team: cnstt_id " . $post['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function deleteConnectStT($post) {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_id", " AND cnstt_id = '" . intval($post['cnstt_id']) . "' AND cnstt_is_delete = 'yes' LIMIT 1");
        if (!empty($temp[0]['cnstt_id']) && $temp[0]['cnstt_id'] > 0) return false;
        $elems = array(
            "cnstt_is_delete" => 'yes'
        );
        $condition = array(
            "cnstt_id" => $post['cnstt_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "connection_st_t", $elems, $condition)) {
            error_log("[INFO] Связь staff-team успешно удалена: cnstt_id " . $post['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            // Получаем t_id команды для сброса кеша
            $team_info = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t", "cnstt_t_id", "cnstt_id = '" . intval($post['cnstt_id']) . "' LIMIT 1");
            if (!empty($team_info[0]['cnstt_t_id'])) {
                $this->clearTeamStaffCache($team_info[0]['cnstt_t_id']);
            }
            return true;
        }
        error_log("[ERROR] Не удалось удалить связь staff-team: cnstt_id " . $post['cnstt_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function getTStaffItem($item = 0) {
        $item = intval($item);
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t, " . DB_T_PREFIX . "staff, " . DB_T_PREFIX . "team_appointment", "*", "cnstt_id = '$item' AND cnstt_is_delete = 'no' AND cnstt_st_id = st_id AND cnstt_app_id = app_id LIMIT 1");
            return $temp[0];
        }
        return false;
    }

    public function getConnectStTByapp($t_id, $app_type) { //
        if ($app_type == 'head') $extra_q = "AND app_type = 'head'";
        elseif ($app_type == 'rest') $extra_q = "AND app_type = 'rest'";
        else $extra_q = "AND app_type = 'player'";
        $t_id = intval($t_id);

        // Простой файловый кеш для админ-панели (так как Memcache отключен для админов)
        $cache_key = 'team_staff_byapp_' . $t_id . '_' . $app_type;
        $cache_file = sys_get_temp_dir() . '/' . $cache_key . '.cache';
        $cache_ttl = 60; // 60 секунд

        // Проверяем кеш
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
            $temp = unserialize(file_get_contents($cache_file));
        } else {
            // Загружаем из БД
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t, " . DB_T_PREFIX . "staff, " . DB_T_PREFIX . "team_appointment", "cnstt_id, st_id, st_family_ru, st_name_ru, st_surname_ru, app_title_ru, app_id, app_order", "cnstt_t_id = '$t_id' AND cnstt_is_delete = 'no' AND (cnstt_date_quit IS NULL OR cnstt_date_quit = '0000-00-00 00:00:00') AND cnstt_st_id = st_id AND cnstt_app_id = app_id $extra_q ORDER BY app_order DESC, app_title_ru ASC", false, false, 0);
            // Сохраняем в кеш
            if ($temp !== false) {
                file_put_contents($cache_file, serialize($temp));
            }
        }

        $k = 0;
        $prev_item = $res = array();
        if ($temp) foreach ($temp as $item) {
            $st_app = array(
                "name" => $item['st_family_ru'] . " " . $item['st_name_ru'] . " " . $item['st_surname_ru'],
                "cnstt_id" => $item['cnstt_id'],
                "st_id" => $item['st_id']
            );
            if (($prev_item['app_id'] ?? null) == ($item['app_id'] ?? null)) {
                $res[$k - 1]['st_app'][] = $st_app;
            } else {
                $res[$k]['app_title_ru'] = $item['app_title_ru'];
                $res[$k]['st_app'][] = $st_app;
                $k++;
            }
            $prev_item = $item;
        }
        if (count($res) > 0) return $res;
        return false;
    }

    public function getConnectStTByname($t_id, $app_type) { //
        if ($app_type == 'head') $extra_q = "AND app_type = 'head'";
        elseif ($app_type == 'rest') $extra_q = "AND app_type = 'rest'";
        else $extra_q = "AND app_type = 'player'";
        $t_id = intval($t_id);

        // Простой файловый кеш для админ-панели (так как Memcache отключен для админов)
        $cache_key = 'team_staff_byname_' . $t_id . '_' . $app_type;
        $cache_file = sys_get_temp_dir() . '/' . $cache_key . '.cache';
        $cache_ttl = 60; // 60 секунд

        // Проверяем кеш
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
            $temp = unserialize(file_get_contents($cache_file));
        } else {
            // Загружаем из БД
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t, " . DB_T_PREFIX . "staff, " . DB_T_PREFIX . "team_appointment", "cnstt_id, st_id, st_family_ru, st_name_ru, st_surname_ru, app_title_ru", "cnstt_t_id = '$t_id' AND cnstt_is_delete = 'no' AND (cnstt_date_quit IS NULL OR cnstt_date_quit = '0000-00-00 00:00:00') AND cnstt_st_id = st_id AND cnstt_app_id = app_id $extra_q ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, st_id ASC", false, false, 0);
            // Сохраняем в кеш
            if ($temp !== false) {
                file_put_contents($cache_file, serialize($temp));
            }
        }

        $k = 0;
        $prev_item = $res = array();
        if ($temp) foreach ($temp as $item) {
            $st_app = array(
                "app_title_ru" => $item['app_title_ru'],
                "cnstt_id" => $item['cnstt_id'],
                "st_id" => $item['st_id']
            );
            if (($prev_item['st_id'] ?? null) == ($item['st_id'] ?? null)) {
                $res[$k - 1]['st_app'][] = $st_app;
            } else {
                $res[$k]['name'] = $item['st_family_ru'] . " " . $item['st_name_ru'] . " " . $item['st_surname_ru'];
                $res[$k]['st_app'][] = $st_app;
                $k++;
            }
            $prev_item = $item;
        }
        if (count($res) > 0) return $res;
        return false;
    }

    public function getHistoryConnectStT($t_id) {
        $t_id = intval($t_id);
        return $this->hdl->selectElem(DB_T_PREFIX . "connection_st_t, " . DB_T_PREFIX . "staff, " . DB_T_PREFIX . "team_appointment", "*", "cnstt_t_id = '$t_id' AND cnstt_is_delete = 'no' AND cnstt_st_id = st_id AND cnstt_app_id = app_id ORDER BY cnstt_date_add DESC");
    }

    public function getTStaffList() {
        return $this->hdl->selectElem(DB_T_PREFIX . "staff", "*", "1 ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, st_id ASC");
    }

    //  /////////////////////////////////////////////////////////////////////////////////////

    public function createStadium($post) {
        $is_active = isset($post['std_is_active']) && $post['std_is_active'] ? 'yes' : 'no';
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elem = array(
            'std_cn_id' => intval($post['std_cn_id']),
            'std_ct_id' => intval($post['t_ct_id']),
            'std_title_ru' => str_replace($search, $replace, $post['std_title_ru'] ?? '') ?: 'Без названия',
            'std_title_ua' => str_replace($search, $replace, $post['std_title_ua'] ?? '') ?: 'Без назви',
            'std_title_en' => str_replace($search, $replace, $post['std_title_en'] ?? '') ?: 'No name',
            'std_description_ru' => addslashes($post['std_description_ru'] ?? ''),
            'std_description_ua' => addslashes($post['std_description_ua'] ?? ''),
            'std_description_en' => addslashes($post['std_description_en'] ?? ''),
            'std_order' => intval($post['std_order']),
            'std_is_active' => $is_active,
            'std_datetime_add' => $current_time,
            'std_datetime_edit' => $current_time,
            'std_author' => USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX . "stadium", $elem)) {
            error_log("[INFO] Стадион успешно создан: " . ($post['std_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось создать стадион: " . ($post['std_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function updateStadium($post) {
        if ($post['std_id'] < 1) return false;
        $is_active = isset($post['std_is_active']) && $post['std_is_active'] ? 'yes' : 'no';
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elems = array(
            "std_cn_id" => intval($post['std_cn_id']),
            "std_ct_id" => intval($post['t_ct_id']),
            "std_title_ru" => str_replace($search, $replace, $post['std_title_ru'] ?? '') ?: 'Без названия',
            "std_title_ua" => str_replace($search, $replace, $post['std_title_ua'] ?? '') ?: 'Без назви',
            "std_title_en" => str_replace($search, $replace, $post['std_title_en'] ?? '') ?: 'No name',
            "std_description_ru" => addslashes($post['std_description_ru'] ?? ''),
            "std_description_ua" => addslashes($post['std_description_ua'] ?? ''),
            "std_description_en" => addslashes($post['std_description_en'] ?? ''),
            "std_order" => intval($post['std_order']),
            "std_is_active" => $is_active,
            "std_datetime_edit" => $current_time,
            "std_author" => USER_ID
        );
        $condition = array(
            "std_id" => $post['std_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "stadium", $elems, $condition)) {
            error_log("[INFO] Стадион успешно обновлён: ID " . $post['std_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось обновить стадион: ID " . $post['std_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function deleteStadium($item) {
        $item = intval($item);
        if ($item > 0) {
            if ($this->hdl->delElem(DB_T_PREFIX . "stadium", "std_id='$item'")) {
                error_log("[INFO] Стадион успешно удалён: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            }
            error_log("[ERROR] Не удалось удалить стадион: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
        error_log("[ERROR] Неверный ID стадиона для удаления: " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function getStadiumItem($item = 0) {
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "stadium", "*", "std_id=$item");
            $temp[0]['std_title_ru'] = str_replace($search, $replace, $temp[0]['std_title_ru']);
            $temp[0]['std_title_ua'] = str_replace($search, $replace, $temp[0]['std_title_ua']);
            $temp[0]['std_title_en'] = str_replace($search, $replace, $temp[0]['std_title_en']);
            $temp[0]['std_description_ru'] = stripcslashes($temp[0]['std_description_ru']);
            $temp[0]['std_description_ua'] = stripcslashes($temp[0]['std_description_ua']);
            $temp[0]['std_description_en'] = stripcslashes($temp[0]['std_description_en']);
            return $temp[0];
        }
        return false;
    }

    public function getTeamStadiumList() {
        return $this->hdl->selectElem(DB_T_PREFIX . "stadium", "*", "1 ORDER BY std_order DESC, std_title_ru ASC, std_id ASC");
    }

    public function getTeamStadiumListID() {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "stadium", "*", "1 ORDER BY std_id ASC");
        if ($temp) {
            foreach ($temp as $item) {
                $list[$item['std_id']] = $item;
            }
            return $list;
        }
        return false;
    }

    //  /////////////////////////////////////////////////////////////////////////////////////

    public function createCity($post) {
        $is_active = isset($post['ct_is_active']) && $post['ct_is_active'] ? 'yes' : 'no';
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elem = array(
            'ct_cn_id' => intval($post['ct_cn_id']),
            'ct_title_ru' => str_replace($search, $replace, $post['ct_title_ru'] ?? '') ?: 'Без названия',
            'ct_title_ua' => str_replace($search, $replace, $post['ct_title_ua'] ?? '') ?: 'Без назви',
            'ct_title_en' => str_replace($search, $replace, $post['ct_title_en'] ?? '') ?: 'No name',
            'ct_description_ru' => addslashes($post['ct_description_ru'] ?? ''),
            'ct_description_ua' => addslashes($post['ct_description_ua'] ?? ''),
            'ct_description_en' => addslashes($post['ct_description_en'] ?? ''),
            'ct_order' => intval($post['ct_order']),
            'ct_is_active' => $is_active,
            'ct_datetime_add' => $current_time,
            'ct_datetime_edit' => $current_time,
            'ct_author' => USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX . "city", $elem)) {
            error_log("[INFO] Город успешно создан: " . ($post['ct_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось создать город: " . ($post['ct_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function updateCity($post) {
        if ($post['ct_id'] < 1) return false;
        $is_active = isset($post['ct_is_active']) && $post['ct_is_active'] ? 'yes' : 'no';
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elems = array(
            "ct_cn_id" => intval($post['ct_cn_id']),
            "ct_title_ru" => str_replace($search, $replace, $post['ct_title_ru'] ?? '') ?: 'Без названия',
            "ct_title_ua" => str_replace($search, $replace, $post['ct_title_ua'] ?? '') ?: 'Без назви',
            "ct_title_en" => str_replace($search, $replace, $post['ct_title_en'] ?? '') ?: 'No name',
            "ct_description_ru" => addslashes($post['ct_description_ru'] ?? ''),
            "ct_description_ua" => addslashes($post['ct_description_ua'] ?? ''),
            "ct_description_en" => addslashes($post['ct_description_en'] ?? ''),
            "ct_order" => intval($post['ct_order']),
            "ct_is_active" => $is_active,
            "ct_datetime_edit" => $current_time,
            "ct_author" => USER_ID
        );
        $condition = array(
            "ct_id" => $post['ct_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "city", $elems, $condition)) {
            error_log("[INFO] Город успешно обновлён: ID " . $post['ct_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось обновить город: ID " . $post['ct_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function deleteCity($item) {
        $item = intval($item);
        if ($item > 0) {
            if ($this->hdl->delElem(DB_T_PREFIX . "city", "ct_id='$item'")) {
                error_log("[INFO] Город успешно удалён: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            }
            error_log("[ERROR] Не удалось удалить город: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
        error_log("[ERROR] Неверный ID города для удаления: " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function getCityItem($item = 0) {
        $item = intval($item);
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "city", "*", "ct_id=$item");
            $temp[0]['ct_title_ru'] = stripcslashes($temp[0]['ct_title_ru']);
            $temp[0]['ct_title_ua'] = stripcslashes($temp[0]['ct_title_ua']);
            $temp[0]['ct_title_en'] = stripcslashes($temp[0]['ct_title_en']);
            $temp[0]['ct_description_ru'] = stripcslashes($temp[0]['ct_description_ru']);
            $temp[0]['ct_description_ua'] = stripcslashes($temp[0]['ct_description_ua']);
            $temp[0]['ct_description_en'] = stripcslashes($temp[0]['ct_description_en']);
            return $temp[0];
        }
        return false;
    }

    public function getTeamCityList() {
        return $this->hdl->selectElem(DB_T_PREFIX . "city", "*", "1 ORDER BY ct_order DESC, ct_title_ru ASC");
    }

    public function getTeamCityListID() {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "city", "*", "1 ORDER BY ct_order DESC, ct_id ASC");
        if ($temp) {
            foreach ($temp as $item) {
                $list[$item['ct_id']] = $item;
            }
            return $list;
        }
        return false;
    }

    //  /////////////////////////////////////////////////////////////////////////////////////

    public function createCountry($post) {
        $is_active = isset($post['cn_is_active']) && $post['cn_is_active'] ? 'yes' : 'no';
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elem = array(
            'cn_title_ru' => str_replace($search, $replace, $post['cn_title_ru'] ?? '') ?: 'Без названия',
            'cn_title_ua' => str_replace($search, $replace, $post['cn_title_ua'] ?? '') ?: 'Без назви',
            'cn_title_en' => str_replace($search, $replace, $post['cn_title_en'] ?? '') ?: 'No name',
            'cn_description_ru' => addslashes($post['cn_description_ru'] ?? ''),
            'cn_description_ua' => addslashes($post['cn_description_ua'] ?? ''),
            'cn_description_en' => addslashes($post['cn_description_en'] ?? ''),
            'cn_order' => intval($post['cn_order']),
            'cn_is_active' => $is_active,
            'cn_datetime_add' => $current_time,
            'cn_datetime_edit' => $current_time,
            'cn_author' => USER_ID,
            'cn_address' => addslashes(str_replace($search_a, $replace_a, strtolower($post['cn_address'] ?? '')))
        );
        if ($this->hdl->addElem(DB_T_PREFIX . "country", $elem)) {
            error_log("[INFO] Страна успешно создана: " . ($post['cn_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось создать страну: " . ($post['cn_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function updateCountry($post) {
        if ($post['cn_id'] < 1) return false;
        $is_active = isset($post['cn_is_active']) && $post['cn_is_active'] ? 'yes' : 'no';
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elems = array(
            "cn_title_ru" => str_replace($search, $replace, $post['cn_title_ru'] ?? '') ?: 'Без названия',
            "cn_title_ua" => str_replace($search, $replace, $post['cn_title_ua'] ?? '') ?: 'Без назви',
            "cn_title_en" => str_replace($search, $replace, $post['cn_title_en'] ?? '') ?: 'No name',
            "cn_description_ru" => addslashes($post['cn_description_ru'] ?? ''),
            "cn_description_ua" => addslashes($post['cn_description_ua'] ?? ''),
            "cn_description_en" => addslashes($post['cn_description_en'] ?? ''),
            "cn_order" => intval($post['cn_order']),
            "cn_is_active" => $is_active,
            "cn_datetime_edit" => $current_time,
            "cn_author" => USER_ID,
            "cn_address" => addslashes(str_replace($search_a, $replace_a, strtolower($post['cn_address'] ?? '')))
        );
        $condition = array(
            "cn_id" => $post['cn_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "country", $elems, $condition)) {
            error_log("[INFO] Страна успешно обновлена: ID " . $post['cn_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось обновить страну: ID " . $post['cn_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function deleteCountry($item) {
        $item = intval($item);
        if ($item > 0) {
            if ($this->hdl->delElem(DB_T_PREFIX . "country", "cn_id='$item'")) {
                error_log("[INFO] Страна успешно удалена: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            }
            error_log("[ERROR] Не удалось удалить страну: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
        error_log("[ERROR] Неверный ID страны для удаления: " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function getCountryItem($item = 0) {
        $item = intval($item);
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "country", "*", "cn_id=$item");
            $temp[0]['cn_title_ru'] = stripcslashes($temp[0]['cn_title_ru']);
            $temp[0]['cn_title_ua'] = stripcslashes($temp[0]['cn_title_ua']);
            $temp[0]['cn_title_en'] = stripcslashes($temp[0]['cn_title_en']);
            $temp[0]['cn_description_ru'] = stripcslashes($temp[0]['cn_description_ru']);
            $temp[0]['cn_description_ua'] = stripcslashes($temp[0]['cn_description_ua']);
            $temp[0]['cn_description_en'] = stripcslashes($temp[0]['cn_description_en']);
            return $temp[0];
        }
        return false;
    }

    public function getTeamCountryListNE() {
        return $this->hdl->selectElem(DB_T_PREFIX . "country, " . DB_T_PREFIX . "team",
            " cn_id,
                        cn_title_ru",
            " t_cn_id = cn_id
                    GROUP BY cn_id 
                    ORDER BY cn_title_ru ASC");
    }

    public function getTeamFilterList() {
        return $this->hdl->selectElem(
            DB_T_PREFIX . "team",
            "t_filter AS filter",
            "1 GROUP BY t_filter ORDER BY t_filter ASC"
        );
    }

    public function getTeamCountryList() {
        return $this->hdl->selectElem(DB_T_PREFIX . "country", "*", "1 ORDER BY cn_order DESC, cn_title_ru ASC, cn_id ASC");
    }

    public function getTeamCountryListID() {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "country", "*", "1 ORDER BY cn_order DESC, cn_id ASC");
        if ($temp) {
            foreach ($temp as $item) {
                $list[$item['cn_id']] = $item;
            }
            return $list;
        }
        return false;
    }

    //  /////////////////////////////////////////////////////////////////////////////////////

    public function createCategory($post) {
        $is_active = isset($post['app_is_active']) && $post['app_is_active'] ? 'yes' : 'no';

        // Обработка app_type - может приходить как строка или число
        $app_type = 'rest'; // значение по умолчанию
        if (!empty($post['app_type'])) {
            if ($post['app_type'] == 'player' || $post['app_type'] == '1') {
                $app_type = 'player';
            } elseif ($post['app_type'] == 'head' || $post['app_type'] == '2') {
                $app_type = 'head';
            } elseif ($post['app_type'] == 'rest') {
                $app_type = 'rest';
            }
        }
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elem = array(
            'app_title_ru' => str_replace($search, $replace, $post['app_title_ru'] ?? '') ?: 'Без названия',
            'app_title_ua' => str_replace($search, $replace, $post['app_title_ua'] ?? '') ?: 'Без назви',
            'app_title_en' => str_replace($search, $replace, $post['app_title_en'] ?? '') ?: 'No name',
            'app_description_ru' => addslashes($post['app_description_ru'] ?? ''),
            'app_description_ua' => addslashes($post['app_description_ua'] ?? ''),
            'app_description_en' => addslashes($post['app_description_en'] ?? ''),
            'app_type' => $app_type,
            'app_order' => intval($post['app_order']),
            'app_is_active' => $is_active,
            'app_datetime_add' => $current_time,
            'app_datetime_edit' => $current_time,
            'app_author' => USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX . "team_appointment", $elem)) {
            error_log("[INFO] Категория успешно создана: " . ($post['app_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось создать категорию: " . ($post['app_title_ru'] ?? ''), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function updateCategory($post) {
        if ($post['app_id'] < 1) return false;
        $is_active = isset($post['app_is_active']) && $post['app_is_active'] ? 'yes' : 'no';

        // Обработка app_type - может приходить как строка или число
        $app_type = 'rest'; // значение по умолчанию
        if (!empty($post['app_type'])) {
            if ($post['app_type'] == 'player' || $post['app_type'] == '1') {
                $app_type = 'player';
            } elseif ($post['app_type'] == 'head' || $post['app_type'] == '2') {
                $app_type = 'head';
            } elseif ($post['app_type'] == 'rest') {
                $app_type = 'rest';
            }
        }
        $search = array("'", '"');
        $replace = array('', '');

        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x

        $elems = array(
            "app_title_ru" => str_replace($search, $replace, $post['app_title_ru'] ?? '') ?: 'Без названия',
            "app_title_ua" => str_replace($search, $replace, $post['app_title_ua'] ?? '') ?: 'Без назви',
            "app_title_en" => str_replace($search, $replace, $post['app_title_en'] ?? '') ?: 'No name',
            "app_description_ru" => addslashes($post['app_description_ru'] ?? ''),
            "app_description_ua" => addslashes($post['app_description_ua'] ?? ''),
            "app_description_en" => addslashes($post['app_description_en'] ?? ''),
            "app_type" => $app_type,
            "app_order" => intval($post['app_order']),
            "app_is_active" => $is_active,
            "app_datetime_edit" => $current_time,
            "app_author" => USER_ID
        );
        $condition = array(
            "app_id" => $post['app_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX . "team_appointment", $elems, $condition)) {
            error_log("[INFO] Категория успешно обновлена: ID " . $post['app_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось обновить категорию: ID " . $post['app_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function deleteCategory($item) {
        $item = intval($item);
        if ($item > 0) {
            if ($this->hdl->delElem(DB_T_PREFIX . "team_appointment", "app_id='$item'")) {
                error_log("[INFO] Категория успешно удалена: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return true;
            }
            error_log("[ERROR] Не удалось удалить категорию: ID " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
        error_log("[ERROR] Неверный ID категории для удаления: " . $item, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function getCategoryItem($item = 0) {
        $item = intval($item);
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "team_appointment", "*", "app_id=$item");
            $temp[0]['app_title_ru'] = stripcslashes($temp[0]['app_title_ru']);
            $temp[0]['app_title_ua'] = stripcslashes($temp[0]['app_title_ua']);
            $temp[0]['app_title_en'] = stripcslashes($temp[0]['app_title_en']);
            $temp[0]['app_description_ru'] = stripcslashes($temp[0]['app_description_ru']);
            $temp[0]['app_description_ua'] = stripcslashes($temp[0]['app_description_ua']);
            $temp[0]['app_description_en'] = stripcslashes($temp[0]['app_description_en']);
            return $temp[0];
        }
        return false;
    }

    public function getTeamCategoriesList() {
        return $this->hdl->selectElem(DB_T_PREFIX . "team_appointment", "*", "1 ORDER BY app_type ASC, app_order DESC, app_title_ru ASC, app_id ASC");
    }

    public function getTeamCategoriesListID() {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "team_appointment", "*", "1 ORDER BY app_id ASC");
        if ($temp) {
            foreach ($temp as $item) {
                $list[$item['app_id']] = $item;
            }
            return $list;
        }
        return false;
    }

    // SETTINGS /////////////////////////////////////////////////////////////////////////

    public function getTeamSettings() {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "settings", "*", " 1 ORDER BY set_id");
        if ($temp) {
            if (count($temp) > 0) {
                foreach ($temp as $val) {
                    $list[$val['set_name']] = $val['set_value'];
                }
            } else return false;
        } else return false;
        return $list;
    }

    public function saveSettings($elems, $condition) {
        $current_time = date('Y-m-d H:i:s'); // Текущее время в формате строки для MySQL 8.x
        $elems['set_datetime_edit'] = $current_time; // Обновляем время редактирования
        if ($this->hdl->updateElem(DB_T_PREFIX . "settings", $elems, $condition)) {
            error_log("[INFO] Настройки успешно сохранены", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось сохранить настройки", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    //  ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($team_item, &$photos_class) { //
        error_log("[DEBUG] savePhoto вызван. Team ID: " . ($team_item['t_id'] ?? 'не указан'), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        error_log("[DEBUG] FILES: " . print_r($_FILES, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        error_log("[DEBUG] POST: " . print_r($_POST, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");

        $team_item['t_id'] = intval($team_item['t_id']);
        if ($team_item['t_id'] < 1) {
            error_log("[ERROR] team_item['t_id'] < 1", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }

        if ($_POST['ph_gallery_id'] == 'new') {
            if ($team_item['t_title_ru'] == '') $phg_post['phg_title_ru'] = $team_item['t_id'];
            else $phg_post['phg_title_ru'] = $team_item['t_title_ru'];
            if ($team_item['t_title_ua'] == '') $phg_post['phg_title_ua'] = $team_item['t_id'];
            else $phg_post['phg_title_ua'] = $team_item['t_title_ua'];
            if ($team_item['t_title_en'] == '') $phg_post['phg_title_en'] = $team_item['t_id'];
            else $phg_post['phg_title_en'] = $team_item['t_title_en'];

            $phg_post['phg_description_ru'] = "   &laquo;" . $phg_post['phg_title_ru'] . "&raquo;.";
            $phg_post['phg_description_ua'] = "   &laquo;" . $phg_post['phg_title_ua'] . "&raquo;.";
            $phg_post['phg_description_en'] = "Photo gallery of team &laquo;" . $phg_post['phg_title_en'] . "&raquo;.";
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_type'] = 'team';
            $phg_post['phg_type_id'] = $team_item['t_id'];
            $phg_post['phg_phc_id'] = 0;
            $phg_post['phg_datetime_pub'] = date('Y-m-d H:i:s'); // Текущее время в формате строки

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id'] < 1) {
                error_log("[ERROR] Не удалось создать фотогалерею для команды: ID " . $team_item['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return false;
            }
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if (!empty($_POST['ph_type_main'])) $photos_class->resetTypeMainPhotos($team_item['t_id'], 'team');
        $_POST['ph_type_id'] = $team_item['t_id'];
        $_POST['ph_type'] = 'team';

        $file_photo = (!empty($_FILES['file_photo'])) ? $_FILES['file_photo'] : array('error' => 4, 'size' => 0);
        if ($photos_class->savePhoto($file_photo, $_POST)) {
            error_log("[INFO] Фото успешно сохранено для команды: ID " . $team_item['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось сохранить фото для команды: ID " . $team_item['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    public function saveVideo($team_item, &$videos_class) { //    
        $team_item['t_id'] = intval($team_item['t_id']);
        if ($team_item['t_id'] < 1) return false;

        if ($_POST['v_gallery_id'] == 'new') {
            if ($team_item['t_title_ru'] == '') $vg_post['vg_title_ru'] = $team_item['t_id'];
            else $vg_post['vg_title_ru'] = $team_item['t_title_ru'];
            if ($team_item['t_title_ua'] == '') $vg_post['vg_title_ua'] = $team_item['t_id'];
            else $vg_post['vg_title_ua'] = $team_item['t_title_ua'];
            if ($team_item['t_title_en'] == '') $vg_post['vg_title_en'] = $team_item['t_id'];
            else $vg_post['vg_title_en'] = $team_item['t_title_en'];

            $vg_post['vg_description_ru'] = "   &laquo;" . $vg_post['vg_title_ru'] . "&raquo;.";
            $vg_post['vg_description_ua'] = "Â³   &laquo;" . $vg_post['vg_title_ua'] . "&raquo;.";
            $vg_post['vg_description_en'] = "Video gallery of team &laquo;" . $vg_post['vg_title_en'] . "&raquo;.";
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_type'] = 'team';
            $vg_post['vg_type_id'] = $team_item['t_id'];
            $vg_post['vg_phc_id'] = 0;
            $vg_post['vg_datetime_pub'] = date('Y-m-d H:i:s'); // Текущее время в формате строки

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_date_quit'] < 1) {
                error_log("[ERROR] Не удалось создать видеогалерею для команды: ID " . $team_item['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
                return false;
            }
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $team_item['t_id'];
        $_POST['v_type'] = 'team';

        if ($videos_class->saveVideo($_POST)) {
            error_log("[INFO] Видео успешно сохранено для команды: ID " . $team_item['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return true;
        }
        error_log("[ERROR] Не удалось сохранить видео для команды: ID " . $team_item['t_id'], 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
        return false;
    }

    /**
     * Объединение двух команд: перенос всех данных из старой команды в текущую
     * @param int $target_id - ID команды, в которую переносим данные (текущая)
     * @param int $source_id - ID команды, из которой переносим данные (дубль)
     * @return array - результат операции с деталями
     */
    public function mergeTeam($target_id, $source_id) {
        $target_id = intval($target_id);
        $source_id = intval($source_id);

        if ($target_id < 1 || $source_id < 1) {
            return array('success' => false, 'error' => 'Некорректные ID команд');
        }

        if ($target_id == $source_id) {
            return array('success' => false, 'error' => 'Нельзя объединить команду с самой собой');
        }

        // Проверяем существование обеих команд
        $target = $this->getTeamItem($target_id);
        $source = $this->getTeamItem($source_id);

        if (!$target) {
            return array('success' => false, 'error' => 'Целевая команда не найдена');
        }
        if (!$source) {
            return array('success' => false, 'error' => 'Исходная команда (дубль) не найдена');
        }

        $merged = array();

        // 1. Перенос связей игрок-команда (rgr_connection_st_t)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_st_t",
            array('cnstt_t_id' => $target_id),
            "cnstt_t_id = $source_id");
        $merged[] = 'Связи с игроками перенесены';

        // 2. Перенос матчей - хозяева (rgr_games)
        $this->hdl->updateElemExtra(DB_T_PREFIX."games",
            array('g_owner_t_id' => $target_id),
            "g_owner_t_id = $source_id");
        $merged[] = 'Матчи (хозяева) перенесены';

        // 3. Перенос матчей - гости
        $this->hdl->updateElemExtra(DB_T_PREFIX."games",
            array('g_guest_t_id' => $target_id),
            "g_guest_t_id = $source_id");
        $merged[] = 'Матчи (гости) перенесены';

        // 4. Перенос участия в матчах (rgr_connection_g_st)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_g_st",
            array('cngst_t_id' => $target_id),
            "cngst_t_id = $source_id");
        $merged[] = 'Участие игроков в матчах перенесено';

        // 5. Перенос игровых действий (rgr_games_actions)
        $this->hdl->updateElemExtra(DB_T_PREFIX."games_actions",
            array('ga_t_id' => $target_id),
            "ga_t_id = $source_id");
        $merged[] = 'Игровые действия перенесены';

        // 6. Перенос связей с чемпионатами (rgr_connection_t_ch)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_t_ch",
            array('cntch_t_id' => $target_id),
            "cntch_t_id = $source_id");
        $merged[] = 'Связи с чемпионатами перенесены';

        // 7. Перенос связей с клубами (rgr_connection_t_cl)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_t_cl",
            array('cntcl_t_id' => $target_id),
            "cntcl_t_id = $source_id");
        $merged[] = 'Связи с клубами перенесены';

        // 8. Перенос фотографий (rgr_photos)
        $this->hdl->updateElemExtra(DB_T_PREFIX."photos",
            array('ph_type_id' => $target_id),
            "ph_type = 'team' AND ph_type_id = $source_id");
        $merged[] = 'Фотографии перенесены';

        // 9. Перенос фото-галерей (rgr_photos_gallery)
        $this->hdl->updateElemExtra(DB_T_PREFIX."photos_gallery",
            array('phg_type_id' => $target_id),
            "phg_type = 'team' AND phg_type_id = $source_id");
        $merged[] = 'Фото-галереи перенесены';

        // 10. Перенос видео (rgr_videos)
        $this->hdl->updateElemExtra(DB_T_PREFIX."videos",
            array('v_type_id' => $target_id),
            "v_type = 'team' AND v_type_id = $source_id");
        $merged[] = 'Видео перенесены';

        // 11. Перенос видео-галерей (rgr_videos_gallery)
        $this->hdl->updateElemExtra(DB_T_PREFIX."videos_gallery",
            array('vg_type_id' => $target_id),
            "vg_type = 'team' AND vg_type_id = $source_id");
        $merged[] = 'Видео-галереи перенесены';

        // 12. Создание 301 редиректов со старой страницы на новую
        $source_url = '/team/' . ($source['t_address'] ? $source['t_address'] : $source_id);
        $target_url = '/team/' . ($target['t_address'] ? $target['t_address'] : $target_id);

        // Также редирект по ID
        $source_url_id = '/team/' . $source_id;
        $target_url_id = '/team/' . $target_id;

        // Добавляем редиректы через addElem
        $redirect_data = array(
            'url' => $source_url,
            'redirect_url' => $target_url,
            'is_regexp' => 'no',
            'is_active' => 'yes',
            'datetime_add' => 'NOW()',
            'datetime_edit' => 'NOW()',
            'author' => USER_ID
        );
        $this->hdl->addElem(DB_T_PREFIX."redirects", $redirect_data);

        if ($source_url != $source_url_id) {
            $redirect_data2 = array(
                'url' => $source_url_id,
                'redirect_url' => $target_url_id,
                'is_regexp' => 'no',
                'is_active' => 'yes',
                'datetime_add' => 'NOW()',
                'datetime_edit' => 'NOW()',
                'author' => USER_ID
            );
            $this->hdl->addElem(DB_T_PREFIX."redirects", $redirect_data2);
        }
        $merged[] = 'Редиректы созданы: ' . $source_url . ' → ' . $target_url;

        // 13. Помечаем старую команду как удалённую (мягкое удаление)
        $this->hdl->updateElem(DB_T_PREFIX."team",
            array('t_is_delete' => 'yes', 't_is_active' => 'no', 't_datetime_edit' => 'NOW()', 't_author' => USER_ID),
            array('t_id' => $source_id));
        $merged[] = 'Дубль деактивирован и помечен как удалённый';

        // Сбрасываем кеш
        $this->clearTeamStaffCache($target_id);
        $this->clearTeamStaffCache($source_id);

        return array(
            'success' => true,
            'merged' => $merged,
            'source' => $source['t_title_ru'] . ' (ID: ' . $source_id . ')',
            'target' => $target['t_title_ru'] . ' (ID: ' . $target_id . ')'
        );
    }
}