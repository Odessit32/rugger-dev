<?php
// Отключаем отображение ошибок для пользователей
ini_set('display_errors', '0');
// Убедимся, что ошибки записываются в лог
ini_set('log_errors', '1');
// Укажем путь к файлу лога ошибок (если нужно изменить)
ini_set('error_log', '/home/k/kredoo3g/rugger.info/public_html/error_log');

class news extends clientBase {
    protected $hdl;

    public function __construct() {
        $this->hdl = database::getInstance();
        if (!$this->hdl) {
        }
    }

    public function getNewsItem($item = 0, $isAdmin = false) {
        global $month; // массив названий месяцев
        global $wday_l; // массив названий дней недели

        $item = intval($item);
        if ($item > 0) {
            // Будущие новости доступны по прямой ссылке (для превью в мессенджерах)
            // но не показываются в лентах новостей
            $date_condition = "";

            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news",
                "   n_id as id,
                    n_title_" . S_LANG . " as title,
                    n_description_" . S_LANG . " as description,
                    n_text_" . S_LANG . " as text,
                    n_date_show as date,
                    n_nc_id,
                    n_tags,
                    n_is_photo_top,
                    n_sign,
                    n_sign_url,
                    n_related_news
                ",
                "   n_id=$item and
                    n_is_active='yes'
                    $date_condition
                    LIMIT 1"
            );
            if ($temp && is_array($temp)) {
                $search = array("'", '"');
                $replace = array('"', '"');
                $temp = $temp[0];
                $temp['title'] = str_replace($search, $replace, stripcslashes($temp['title']));
                $temp['title_meta'] = strip_tags($temp['title']);
                $temp['description'] = stripcslashes($temp['description']);
                $temp['description_meta'] = strip_tags($temp['description']);
                $temp['text'] = str_replace(array('mce:script', '_mce_src'), array('script', 'src'), stripcslashes($temp['text']));
                $temp['photo_main'] = $this->getNewsPhotoMain($temp['id']);
                $temp['m'] = $month[date('m', strtotime($temp['date']))] ?? '';
                $temp['wd'] = $wday_l[date('N', strtotime($temp['date']))] ?? '';
                $temp['photo_gallery'] = $this->getNewsPhotoGallery($temp['id']);
                $temp['video_gallery'] = $this->getNewsVideoGallery($temp['id']);
                $temp['connection_country'] = $this->getConnectionCountry($temp['id'], false, 'news');
                $temp['connection_champ'] = $this->getConnectionChamp($temp['id'], false, 'news');
                $temp['connected_games'] = $this->getConnectionGame($item, false, 'news');
                $temp['staff'] = $this->getConnectionStaff($temp['id'], false, 'news');

                // Загружаем связанные новости "Читайте также"
                $temp['related_news'] = array();
                if (!empty($temp['n_related_news'])) {
                    $related_ids = json_decode($temp['n_related_news'], true);
                    if (!empty($related_ids) && is_array($related_ids)) {
                        foreach ($related_ids as $rel_id) {
                            if (!empty($rel_id)) {
                                $rel_news = $this->hdl->selectElem(DB_T_PREFIX."news",
                                    "n_id, n_title_".S_LANG." as title, n_sign_url, n_date_show",
                                    "n_id = ".intval($rel_id)." AND n_is_active = 'yes' LIMIT 1");
                                if (!empty($rel_news[0])) {
                                    $rel_photo = $this->getNewsPhotoMain($rel_news[0]['n_id']);
                                    $temp['related_news'][] = array(
                                        'id' => $rel_news[0]['n_id'],
                                        'title' => strip_tags($rel_news[0]['title']),
                                        'url' => $rel_news[0]['n_sign_url'],
                                        'date' => $rel_news[0]['n_date_show'],
                                        'm' => $month[date('m', strtotime($rel_news[0]['n_date_show']))] ?? '',
                                        'photo' => $rel_photo
                                    );
                                }
                            }
                        }
                    }
                }

                return $temp;
            }
        }
        return [];
    }

    public function getNewsList($page = 1, $perpage = 10, $category = 0, $page_index = 1, $date_show = false, $isAdmin = false) {
        global $month; // массив названий месяцев
        global $wday; // массив названий дней недели
        global $section_type;
        global $section_type_id;

        // Будущие новости не показываем никому в публичной части (только в админке)
        $current_time = date("Y-m-d H:i:00");
        if ($date_show) {
            $date_show_end = date("Y-m-d 23:59:59", strtotime(substr($date_show, 0, 10)));
            // Если выбранная дата в будущем - ограничиваем текущим временем
            $date_now = ($date_show_end > $current_time) ? $current_time : $date_show_end;
        } else {
            $date_now = $current_time;
        }
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND n_nc_id = '$category' ";
        $page--;
        if ($page < 0) $page = 0;
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index < 2) $page_index = $perpage;
        $limit = ($page == 0) ? $page * $perpage : ($page - 1) * $perpage + $page_index;

        if (!empty($section_type) && !empty($section_type_id)) {
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON n.n_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'news' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON n.n_id = c.type_id";
                    $q_c_extra = " AND c.type = 'news' AND c.ch_id = '$section_type_id'";
                    break;
                default:
                    $f_c_extra = '';
                    $q_c_extra = '';
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news n
                    $f_c_extra
                    LEFT JOIN " . DB_T_PREFIX . "photo_gallery phg ON n.n_id = phg.phg_type_id
                    LEFT JOIN " . DB_T_PREFIX . "video_gallery vg ON n.n_id = vg.vg_type_id ",
                "   n_id AS id,
                    n_title_" . S_LANG . " AS title,
                    n_description_" . S_LANG . " AS description,
                    n_date_show AS date,
                    n_nc_id,
                    n_tags,
                    phg_id,
                    phg_ph_count,
                    phg_is_active,
                    vg_id,
                    vg_v_count,
                    vg_is_active
                ",
                "   (vg_type = 'news' OR vg_type IS NULL) AND
                    (phg_type = 'news' OR phg_type IS NULL) AND
                    n_is_active='yes' AND
                    n_description_" . S_LANG . " != '' AND
                    n_date_show < '$date_now' AND
                    n_title_" . S_LANG . " != '' AND
                    n_top = 0 AND
                    n_id = type_id AND
                    type = 'news'
                    $q_c_extra
                    $extra_q
                GROUP BY n_id
                ORDER BY n_top DESC,
                    n_date_show DESC,
                    n_id DESC
                    LIMIT $limit, $perpage",
                false, true, 60
            );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news n
                    LEFT JOIN " . DB_T_PREFIX . "photo_gallery g ON n.n_id = g.phg_type_id
                    LEFT JOIN " . DB_T_PREFIX . "video_gallery vg ON n.n_id = vg.vg_type_id ",
                "   n_id AS id,
                    n_title_" . S_LANG . " AS title,
                    n_description_" . S_LANG . " AS description,
                    n_date_show AS date,
                    n_nc_id,
                    n_tags,
                    phg_id,
                    phg_ph_count,
                    phg_is_active,
                    vg_id,
                    vg_v_count,
                    vg_is_active
                ",
                "   (vg_type = 'news' OR vg_type IS NULL) AND
                    (phg_type = 'news' OR phg_type IS NULL) AND
                    n_is_active='yes' AND
                    n_description_" . S_LANG . " != '' AND
                    n_date_show < '$date_now' AND
                    n_top = 0 AND
                    n_title_" . S_LANG . " != ''
                    $extra_q
                GROUP BY n_id
                ORDER BY n_top DESC,
                    n_date_show DESC,
                    n_id DESC
                    LIMIT $limit, $perpage",
                false, true, 60
            );
        }
        if (!empty($temp) && is_array($temp)) {
            $search = array("'", '"');
            $replace = array('"', '"');

            // Batch-загрузка фотографий (решение N+1)
            $newsIds = array_column($temp, 'id');
            $photosMap = $this->getNewsPhotoMainBatch($newsIds);

            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = str_replace($search, $replace, stripcslashes($temp[$i]['title']));
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>");
                $temp[$i]['photo_main'] = $photosMap[$temp[$i]['id']] ?? ['ph_big_info' => ''];
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))] ?? '';
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
                $temp[$i]['wd'] = $wday[date('N', strtotime($temp[$i]['date']))] ?? '';
            }
            return $temp;
        }
        return [];
    }

    public function getNewsPages($page = 1, $perpage = 10, $category = 0, $page_index = 1, $date_show = false, $isAdmin = false) {
        global $section_type;
        global $section_type_id;
        // Будущие новости не показываем никому в публичной части (только в админке)
        $current_time = date("Y-m-d H:i:00");
        if ($date_show) {
            $date_show_end = date("Y-m-d 23:59:59", strtotime(substr($date_show, 0, 10)));
            $date_now = ($date_show_end > $current_time) ? $current_time : $date_show_end;
        } else {
            $date_now = $current_time;
        }
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND n_nc_id = '$category' ";
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)) {
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON n.n_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'news' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON n.n_id = c.type_id";
                    $q_c_extra = " AND c.type = 'news' AND c.ch_id = '$section_type_id'";
                    break;
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news n " . $f_c_extra,
                "   COUNT(*) as C_N
                ",
                "   n_is_active='yes' AND
                    n_description_" . S_LANG . " != '' AND
                    n_date_show < '$date_now' AND
                    n_title_" . S_LANG . " != '' AND
                    n_id = type_id
                    $extra_q
                    $q_c_extra",
                false, true, 60
            );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news ",
                "   COUNT(*) as C_N
                ",
                "   n_is_active='yes' AND
                    n_description_" . S_LANG . " != '' AND
                    n_date_show < '$date_now' AND
                    n_title_" . S_LANG . " != ''
                    $extra_q",
                false, true, 60
            );
        }
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index < 2) $page_index = $perpage;
        if (!isset($temp[0]['C_N']) || ($temp[0]['C_N'] - $page_index) <= 0) return [];
        $c_pages = intval(($temp[0]['C_N'] - $page_index) / $perpage) + 1;

        $pages = [];
        if ($c_pages <= 9) {
            for ($i = 0; $i < 9 && $i < $c_pages; $i++) {
                $pages[$i] = $i + 1;
            }
        } elseif ($page < 6) {
            for ($i = 0; $i < 9 && $i < $c_pages; $i++) {
                $pages[$i] = $i + 1;
            }
        } elseif ($page > 5) {
            for ($i = $page - 5; $i < $page + 4 && $i < $c_pages; $i++) {
                $pages[$i] = $i + 1;
            }
        }
        return count($pages) > 1 ? $pages : [];
    }

    public function getNewsCategories() {
        global $language;
        $list = [];
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "news_categories",
            "   nc_id,
                nc_title_" . S_LANG . " as title,
                nc_address
            ",
            "   nc_is_active='yes'
                ORDER BY nc_id ASC"
        );
        $list = [];
        if (!empty($temp) && is_array($temp)) {
            $list[0] = [
                'nc_id' => 0,
                'title' => $language['All'] ?? 'All',
                'nc_address' => ''
            ];
            foreach ($temp as $item) {
                $item['title'] = stripcslashes($item['title']);
                $list[$item['nc_id']] = $item;
            }
            return $list;
        }
        return $list;
    }

    /**
     * Получить ID рубрик, отмеченных для вывода в виджете
     * @return array Массив ID рубрик
     */
    public function getWidgetCategoryIds() {
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "news_categories",
            "nc_id",
            "nc_is_active='yes' AND nc_show_in_widget='yes' ORDER BY nc_order DESC, nc_id ASC"
        );
        if (!empty($temp) && is_array($temp)) {
            return array_column($temp, 'nc_id');
        }
        return [];
    }

    public function getNewsMainOne() {
        global $section_type;
        global $section_type_id;
        $date_now = date("Y-m-d H:i:00");

        if (!empty($section_type) && !empty($section_type_id)) {
            // В разделах (страна/чемпионат) — берём последнюю новость раздела
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON n.n_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'news' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON n.n_id = c.type_id";
                    $q_c_extra = " AND c.type = 'news' AND c.ch_id = '$section_type_id'";
                    break;
                default:
                    $f_c_extra = '';
                    $q_c_extra = '';
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news n $f_c_extra",
                "   n.n_id,
                    n.n_title_" . S_LANG . " AS title,
                    n.n_description_" . S_LANG . " AS description,
                    n.n_date_show,
                    n.n_nc_id
                ",
                "   n.n_is_active='yes' AND
                    n.n_date_show < '$date_now' AND
                    n.n_title_" . S_LANG . " != ''
                    $q_c_extra
                ORDER BY n.n_date_show DESC
                LIMIT 1",
                false, true, 60
            );
        } else {
            // На главной — по-прежнему используем n_top для ручного выбора
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news",
                "   n_id,
                    n_title_" . S_LANG . " AS title,
                    n_description_" . S_LANG . " AS description,
                    n_date_show,
                    n_nc_id,
                    n_top
                ",
                "   n_is_active='yes' AND
                    n_top != 0 AND
                    n_date_show < '$date_now' AND
                    n_title_" . S_LANG . " != ''
                ORDER BY n_top ASC
                LIMIT 4",
                false, true, 60
            );
        }
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['title_meta'] = strip_tags($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getNewsPhotoMain($temp[$i]['n_id']);
            }
        }
        return $temp ?: [];
    }

    public function getNewsMainList($limit = 0, $offset = 0, $allsections = false, $category = false) {
        global $section_type;
        global $section_type_id;
        $limit = intval($limit) < 1 ? 5 : intval($limit);
        $offset = intval($offset);
        $date_now = date("Y-m-d H:i:00");

        // Поддержка массива категорий или одиночной категории
        if (is_array($category) && !empty($category)) {
            $category_ids = array_map('intval', $category);
            $q_category = " AND n_nc_id IN (" . implode(',', $category_ids) . ")";
        } elseif ($category) {
            $q_category = " AND n_nc_id = '" . intval($category) . "'";
        } else {
            $q_category = '';
        }

        if (!empty($section_type) && !empty($section_type_id) && !$allsections) {
            switch ($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_country c ON n.n_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'news' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN " . DB_T_PREFIX . "connection_champ c ON n.n_id = c.type_id";
                    $q_c_extra = " AND c.type = 'news' AND c.ch_id = '$section_type_id'";
                    break;
                default:
                    $f_c_extra = '';
                    $q_c_extra = '';
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news n
                    $f_c_extra
                    LEFT JOIN " . DB_T_PREFIX . "photo_gallery phg ON n.n_id = phg.phg_type_id
                    LEFT JOIN " . DB_T_PREFIX . "video_gallery vg ON n.n_id = vg.vg_type_id ",
                "   n_id,
                    n_title_" . S_LANG . " AS title,
                    n_description_" . S_LANG . " AS description,
                    n_date_show,
                    phg_id,
                    phg_ph_count,
                    phg_is_active,
                    vg_id,
                    vg_v_count,
                    vg_is_active
                ",
                "   (vg_type = 'news' OR vg_type IS NULL) AND
                    (phg_type = 'news' OR phg_type IS NULL) AND
                    n_is_active='yes' AND
                    n_top = '0' AND
                    n_title_" . S_LANG . " != '' AND
                    n_date_show < '$date_now' AND
                    n_id = type_id AND
                    type = 'news'
                    $q_c_extra
                    $q_category
                GROUP BY n_id
                ORDER BY n_top DESC,
                    n_date_show DESC,
                    n_id DESC
                LIMIT $offset, $limit",
                false, true, 60
            );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX . "news n
                    LEFT JOIN " . DB_T_PREFIX . "photo_gallery g ON n.n_id = g.phg_type_id
                    LEFT JOIN " . DB_T_PREFIX . "video_gallery vg ON n.n_id = vg.vg_type_id ",
                "   n_id,
                    n_title_" . S_LANG . " AS title,
                    n_description_" . S_LANG . " AS description,
                    n_date_show,
                    phg_id,
                    phg_ph_count,
                    phg_is_active,
                    vg_id,
                    vg_v_count,
                    vg_is_active",
                "   (vg_type = 'news' OR vg_type IS NULL) AND
                    (phg_type = 'news' OR phg_type IS NULL) AND
                    n_is_active='yes' AND
                    n_top = '0' AND
                    n_title_" . S_LANG . " != '' AND
                    n_date_show < '$date_now'
                    $q_category
                GROUP BY n_id
                ORDER BY n_top DESC,
                    n_date_show DESC,
                    n_id DESC
                LIMIT $offset, $limit",
                false, true, 60
            );
        }
        if (!empty($temp) && is_array($temp)) {
            // Batch-загрузка фотографий (решение N+1)
            $newsIds = array_column($temp, 'n_id');
            $photosMap = $this->getNewsPhotoMainBatch($newsIds);

            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description_'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['description'] = strip_tags($temp[$i]['description_']);
                $temp[$i]['photo_main'] = $photosMap[$temp[$i]['n_id']] ?? ['ph_big_info' => ''];
            }
        }
        return $temp ?: [];
    }

    private function getNewsPhotoMain($id) {
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                ph_path,
                ph_about_" . S_LANG . " as ph_about,
                ph_title_" . S_LANG . " as ph_title,
                ph_folder,
                ph_gallery_id
            ",
            "   ph_is_active='yes' AND
                ph_type_id = '$id' AND
                ph_type = 'news'
            ORDER BY ph_type_main DESC
            LIMIT 1"
        );
        if ($temp_photo && is_array($temp_photo)) {
            $temp_photo = $temp_photo[0];
            return $this->formatPhotoData($temp_photo);
        }
        return ['ph_big_info' => ''];
    }

    /**
     * Batch-загрузка главных фото для списка новостей (решение N+1)
     * @param array $newsIds Массив ID новостей
     * @return array Ассоциативный массив [news_id => photo_data]
     */
    private function getNewsPhotoMainBatch(array $newsIds) {
        if (empty($newsIds)) {
            return [];
        }

        // Очищаем и валидируем ID
        $newsIds = array_map('intval', $newsIds);
        $newsIds = array_filter($newsIds, function($id) { return $id > 0; });

        if (empty($newsIds)) {
            return [];
        }

        $idsString = implode(',', $newsIds);

        // Один запрос для всех фотографий с использованием подзапроса для выбора главной
        $temp_photos = $this->hdl->selectElem(
            "(SELECT p.*, ROW_NUMBER() OVER (PARTITION BY ph_type_id ORDER BY ph_type_main DESC, ph_id ASC) as rn
              FROM " . DB_T_PREFIX . "photos p
              WHERE ph_is_active='yes' AND ph_type='news' AND ph_type_id IN ($idsString)) as ranked",
            "ph_id, ph_path, ph_about_" . S_LANG . " as ph_about, ph_title_" . S_LANG . " as ph_title, ph_folder, ph_gallery_id, ph_type_id",
            "rn = 1",
            false, true, 60
        );

        $result = [];

        // Инициализируем пустыми данными для всех ID
        foreach ($newsIds as $id) {
            $result[$id] = ['ph_big_info' => ''];
        }

        if ($temp_photos && is_array($temp_photos)) {
            foreach ($temp_photos as $photo) {
                $result[$photo['ph_type_id']] = $this->formatPhotoData($photo);
            }
        }

        return $result;
    }

    /**
     * Форматирует данные фото (общий метод)
     */
    private function formatPhotoData($photo) {
        $photo['ph_title'] = strip_tags(stripcslashes($photo['ph_title'] ?? ''));
        $photo['ph_about'] = strip_tags(stripcslashes($photo['ph_about'] ?? ''));
        // Добавляем слэш в начало если ph_folder пустой
        $folder = !empty($photo['ph_folder']) ? $photo['ph_folder'] : '/';
        $photo['ph_small'] = $folder . substr($photo['ph_path'], 0, strlen(strrchr($photo['ph_path'], ".")) * (-1)) . "-small" . strrchr($photo['ph_path'], ".");
        $photo['ph_med'] = $folder . substr($photo['ph_path'], 0, strlen(strrchr($photo['ph_path'], ".")) * (-1)) . "-med" . strrchr($photo['ph_path'], ".");
        $photo['ph_big'] = $folder . $photo['ph_path'];
        $photo['ph_big_info'] = $photo['ph_big_info'] ?? '';
        return $photo;
    }

    public function getNewsPhotoGallery($n_id) {
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photo_gallery",
            "   phg_id as id,
                phg_title_" . S_LANG . " as title,
                phg_description_" . S_LANG . " as description,
                phg_datetime_pub as date,
                phg_ph_count
            ",
            "   phg_is_active='yes' AND
                phg_type = 'news' AND
                phg_type_id = '$n_id'
            LIMIT 0, 1"
        );
        if ($temp && is_array($temp)) {
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['photos'] = $this->getNewsPhotos($temp['id']);
            return $temp;
        }
        return [];
    }

    public function getNewsPhotos($id, $limit = 0) {
        $q_limit = $limit > 0 ? ' LIMIT 0, ' . intval($limit) : '';
        $id = intval($id);
        if ($id < 1) return [];
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                ph_path,
                ph_about_" . S_LANG . " as ph_about,
                ph_title_" . S_LANG . " as ph_title,
                ph_folder,
                ph_gallery_id
            ",
            "   ph_is_active='yes' AND
                ph_gallery_id = '$id'
            ORDER BY ph_order DESC,
                ph_id ASC
                $q_limit"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_small'] = $temp[$i]['ph_folder'] . substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_med'] = $temp[$i]['ph_folder'] . substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-med" . strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_inf'] = $temp[$i]['ph_folder'] . substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-informer" . strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_big'] = $temp[$i]['ph_folder'] . $temp[$i]['ph_path'];
            }
            return $temp;
        }
        return [];
    }

    public function getNewsVideoGallery($n_id) {
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "video_gallery",
            "   vg_id as id,
                vg_title_" . S_LANG . " as title,
                vg_description_" . S_LANG . " as description,
                vg_datetime_pub as date,
                vg_v_count
            ",
            "   vg_is_active='yes' AND
                vg_type = 'news' AND
                vg_type_id = '$n_id'
            LIMIT 0, 1"
        );
        if ($temp && is_array($temp)) {
            $temp = $temp[0];
            $temp['title'] = strip_tags(stripcslashes($temp['title']));
            $temp['description'] = strip_tags(stripcslashes($temp['description']));
            $temp['videos'] = $this->getNewsVideos($temp['id']);
            return $temp;
        }
        return [];
    }

    public function getNewsVideos($id) {
        $id = intval($id);
        if ($id < 1) return [];
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "videos",
            "   v_id,
                v_code,
                v_code_text,
                v_title_" . S_LANG . " as title,
                v_about_" . S_LANG . " as v_about,
                v_folder
            ",
            "   v_is_active='yes' AND
                v_gallery_id = '$id'
            ORDER BY v_id ASC"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['v_code_text'] = stripcslashes($temp[$i]['v_code_text']);
                $temp[$i]['title'] = strip_tags(stripcslashes($temp[$i]['title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
                $temp[$i]['v_small'] = $temp[$i]['v_folder'] . $temp[$i]['v_id'] . "-small.jpg";
            }
        }
        return $temp ?: [];
    }

public function getNewsCategoryItem($id = 0) {
    $id = intval($id);
    if ($id < 1) return [];
    $temp = $this->hdl->selectElem(DB_T_PREFIX . "news_categories",
        "   nc_id as id,
            nc_title_" . S_LANG . " as title,
            nc_address as address
        ",
        "   nc_id = $id LIMIT 1"
    );
    if ($temp && is_array($temp)) {
        $temp = $temp[0];
        $temp['title'] = stripcslashes($temp['title']);
        return $temp;
    }
    return [];
}
public function getNewsMenu($c_id = 0) {
    $c_id = intval($c_id);
    $res = [];
    $item = [];
    $item['title'] = (LANG == 'rus') ? "Все новости" : ((LANG == 'ukr') ? "Всі новини" : "All news");
    $item['address'] = '';
    $item['active'] = ($c_id == 0) ? 'yes' : 'no';
    $res[] = $item;

    $temp = $this->hdl->selectElem(DB_T_PREFIX . "news_categories",
        "   nc_id as id,
            nc_title_" . S_LANG . " as title,
            nc_address as address
        ",
        "   nc_is_active='yes' ORDER BY nc_order DESC, nc_title_ru ASC"
    );
    if (!empty($temp) && is_array($temp)) {
        for ($i = 0; $i < count($temp); $i++) {
            $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
            $temp[$i]['address'] = $temp[$i]['address'];
            $temp[$i]['active'] = ($temp[$i]['id'] == $c_id) ? 'yes' : 'no';
            $res[] = $temp[$i];
        }
        return [$res];
    }
    return [];
}
    public function getNewsMainPageList($c_news = 2, $category = 0) {
        global $month;
        $category = intval($category);
        $q_categ = ($category > 0) ? " AND n_nc_id = '$category' AND n_nc_main = 'no'" : " AND n_nc_id != '3' AND n_nc_main = 'no'";
        $c_news = max(1, intval($c_news));

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "news",
            "   n_id as id,
                n_title_" . S_LANG . " as title,
                n_description_" . S_LANG . " as description,
                n_date_show as date
            ",
            "   n_is_active='yes' AND n_title_" . S_LANG . " != '' AND n_description_" . S_LANG . " != '' $q_categ AND ((n_nc_id != '3' AND n_date_show < NOW()) OR n_nc_id = '3') AND n_is_main = 'no' ORDER BY n_is_main DESC, n_date_show DESC LIMIT 0, $c_news"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getNewsPhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))] ?? '';
            }
            return $temp;
        }
        return [];
    }

    public function getNewsMainPageMain($nc_id = 0) {
        global $month;
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "news",
            "   n_id as id,
                n_title_" . S_LANG . " as title,
                n_description_" . S_LANG . " as description,
                n_text_" . S_LANG . " as text,
                n_date_show as date
            ",
            "   n_is_active='yes' AND
                n_main > 0
            ORDER BY n_main ASC,
                n_date_show DESC
            LIMIT 4"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['text'] = stripcslashes($temp[$i]['text']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))] ?? '';
                $temp[$i]['photo_main'] = $this->getNewsPhotoMain($temp[$i]['id']);
            }
        }
        return $temp ?: [];
    }

    public function getMediaMainPageList($c_media = 2, $category = 0, $title_photo = '', $title_video = '') {
        global $month;
        $category = intval($category);
        $c_media = max(1, intval($c_media));
        $res = [];

        if ($category == 0 || $category == 1) {
            $photo = $this->hdl->selectElem(DB_T_PREFIX . "photo_gallery, " . DB_T_PREFIX . "photos",
                "   phg_id as id,
                    phg_title_" . S_LANG . " as title,
                    phg_description_" . S_LANG . " as description,
                    phg_datetime_pub as date,
                    ph_path,
                    ph_folder
                ",
                "   ph_gallery_id = phg_id AND
                    ph_is_active='yes' AND
                    ph_is_informer = 'yes' AND
                    phg_is_active = 'yes' AND
                    phg_is_informer = 'yes'
                GROUP BY phg_id
                ORDER BY phg_datetime_pub DESC,
                    phg_title_" . S_LANG . " ASC
                LIMIT 0, $c_media"
            );
            if (!empty($photo) && is_array($photo)) {
                foreach ($photo as $item) {
                    $item['type'] = 'photo';
                    $item['type_address'] = 'media/photo';
                    $item['type_title'] = $title_photo;
                    $item['title'] = stripcslashes($item['title']);
                    $item['description'] = stripcslashes($item['description']);
                    $item['m'] = $month[date('m', strtotime($item['date']))] ?? '';
                    $item['photo'] = "upload/photos/" . $item['ph_folder'] . substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], ".")) * (-1)) . "-small" . strrchr($item['ph_path'], ".");
                    $item['time'] = strtotime($item['date']);
                    $res[] = $item;
                }
            }
        }
        if ($category == 0 || $category == 2) {
            $video = $this->hdl->selectElem(DB_T_PREFIX . "video_gallery, " . DB_T_PREFIX . "videos",
                "   vg_id as id,
                    vg_title_" . S_LANG . " as title,
                    vg_description_" . S_LANG . " as description,
                    vg_datetime_pub as date,
                    v_id,
                    v_code
                ",
                "   v_gallery_id = vg_id AND
                    v_is_active='yes' AND
                    vg_is_active = 'yes' AND
                    vg_is_informer = 'yes'
                GROUP BY vg_id
                ORDER BY vg_datetime_pub DESC,
                    vg_title_" . S_LANG . " ASC
                LIMIT 0, $c_media"
            );
            if (!empty($video) && is_array($video)) {
                foreach ($video as $item) {
                    $item['type'] = 'video';
                    $item['type_address'] = 'media/video';
                    $item['type_title'] = $title_video;
                    $item['title'] = stripcslashes($item['title']);
                    $item['description'] = stripcslashes($item['description']);
                    $item['m'] = $month[date('m', strtotime($item['date']))] ?? '';
                    $item['photo'] = "upload/video_thumbs/" . $item['v_id'] . "-small.jpg";
                    $item['time'] = strtotime($item['date']);
                    $res[] = $item;
                }
            }
        }
        if (!empty($res) && is_array($res)) {
            usort($res, function ($a, $b) {
                return $b['time'] <=> $a['time'];
            });
            return array_slice($res, 0, $c_media);
        }
        return [];
    }

    public function getNewsPhoto($n_id, $limit = 0) {
        $q_limit = $limit > 0 ? ' LIMIT 0, ' . intval($limit) : '';
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "photos",
            "   ph_id,
                ph_path,
                ph_about_" . S_LANG . " as ph_about,
                ph_title_" . S_LANG . " as ph_title,
                ph_folder,
                ph_gallery_id
            ",
            "   ph_is_active='yes' AND
                ph_type_id = '$n_id' AND
                ph_type = 'news' AND
                ph_type_main = 'no'
            ORDER BY ph_id ASC
                $q_limit"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_small'] = substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], ".")) * (-1)) . "-small" . strrchr($temp[$i]['ph_path'], ".");
            }
            return $temp;
        }
        return [];
    }

    public function getNewsVideo($n_id) {
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX . "videos",
            "   v_id,
                v_code,
                v_title_" . S_LANG . " as v_title,
                v_about_" . S_LANG . " as v_about,
                v_folder,
                v_gallery_id
            ",
            "   v_is_active='yes' AND
                v_type_id = '$n_id' AND
                v_type = 'news'
            ORDER BY v_id ASC"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['v_title'] = strip_tags(stripcslashes($temp[$i]['v_title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
            }
            return $temp;
        }
        return [];
    }

    public function getInformerNews($c_n = 0, $category_display = 0, $category_not_display = 0) {
        global $month;
        $c_n = max(1, intval($c_n));
        $category_display = intval($category_display);
        $category_not_display = intval($category_not_display);
        $extra_q = $category_display > 0 ? " AND n_nc_id = '$category_display' " : ($category_not_display > 0 ? " AND n_nc_id != '$category_not_display'" : '');
        $date_now = date("Y-m-d H:i:00");

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "news",
            "   n_id as id,
                n_title_" . S_LANG . " as title,
                n_description_" . S_LANG . " as description,
                n_date_show as date,
                n_is_main,
                n_nc_id,
                n_tags,
                n_nc_main
            ",
            "   n_is_active='yes' AND
                n_description_" . S_LANG . " != '' AND
                n_date_show <= '$date_now'
                $extra_q
            ORDER BY n_date_show DESC, n_id DESC LIMIT $c_n"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>");
                $temp[$i]['photo_main'] = $this->getNewsPhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))] ?? '';
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
            }
            return $temp;
        }
        return [];
    }

    public function getInformerNewsWoPh($c_n = 0, $category_display = 0, $category_not_display = 0) {
        global $month;
        $c_n = max(1, intval($c_n));
        $category_display = intval($category_display);
        $category_not_display = intval($category_not_display);
        $extra_q = $category_display > 0 ? " AND n_nc_id = '$category_display' " : ($category_not_display > 0 ? " AND n_nc_id != '$category_not_display'" : '');
        $date_now = date("Y-m-d H:i:00");

        $temp = $this->hdl->selectElem(DB_T_PREFIX . "news",
            "   n_id as id,
                n_title_" . S_LANG . " as title,
                n_description_" . S_LANG . " as description,
                n_date_show as date,
                n_is_main,
                n_nc_id,
                n_tags,
                n_nc_main
            ",
            "   n_is_active='yes' AND
                n_description_" . S_LANG . " != '' AND
                n_date_show <= '$date_now'
                $extra_q
            ORDER BY n_date_show DESC, n_id DESC LIMIT $c_n"
        );
        if (!empty($temp) && is_array($temp)) {
            for ($i = 0; $i < count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>");
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))] ?? '';
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
            }
            return $temp;
        }
        return [];
    }
}