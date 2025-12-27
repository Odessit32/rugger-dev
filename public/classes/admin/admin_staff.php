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

    // Люди ///////////////////////////////////////////////////////////////////////////////////////

    public function createStaff($post){ // добавление человека
        $search = array("'", '"');
        $replace = array('', '');
        if (strlen(str_replace($search, $replace, trim($post['st_family_ru'])))==0) return false;
        if($post['st_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['st_is_show_birth']==true) $st_is_show_birth ='yes';
        else $st_is_show_birth = 'no';
        if ($post['st_date_day'] != '' and $post['st_date_day']>0 and $post['st_date_month'] != '' and $post['st_date_month']>0 and $post['st_date_year'] != '' and $post['st_date_year']>0) {
            $post['st_date_day'] = intval($post['st_date_day']);
            $post['st_date_month'] = intval($post['st_date_month']);
            $post['st_date_year'] = intval($post['st_date_year']);
            if ($post['st_date_day'] < 10) $post['st_date_day'] = '0'.$post['st_date_day'];
            if ($post['st_date_month'] < 10) $post['st_date_month'] = '0'.$post['st_date_month'];
            $st_date_birth = $post['st_date_year']."-".$post['st_date_month']."-".$post['st_date_day'];
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."staff","st_id","
				st_date_birth = '$st_date_birth'
				AND st_family_ru = '".str_replace($search, $replace, $post['st_family_ru'])."'
				AND st_family_ua = '".str_replace($search, $replace, $post['st_family_ua'])."'
				AND st_family_en = '".str_replace($search, $replace, $post['st_family_en'])."'
				AND st_name_ru = '".str_replace($search, $replace, $post['st_name_ru'])."'
				AND st_name_ua = '".str_replace($search, $replace, $post['st_name_ua'])."'
				AND st_name_en = '".str_replace($search, $replace, $post['st_name_en'])."'
				AND st_surname_ru = '".str_replace($search, $replace, $post['st_surname_ru'])."'
				AND st_surname_ua = '".str_replace($search, $replace, $post['st_surname_ua'])."'
				AND st_surname_en = '".str_replace($search, $replace, $post['st_surname_en'])."'
				AND st_description_ru = '".addslashes($post['st_description_ru'])."'
				AND st_description_ua = '".addslashes($post['st_description_ua'])."'
				AND st_description_en = '".addslashes($post['st_description_en'])."'
				AND st_text_ru = '".addslashes($post['st_text_ru'])."'
				AND st_text_ua = '".addslashes($post['st_text_ua'])."'
				AND st_text_en = '".addslashes($post['st_text_en'])."'
				LIMIT 1");
        if ($temp) return false;
        $elem = array(
            $st_date_birth,
            str_replace($search, $replace, $post['st_family_ru']),
            str_replace($search, $replace, $post['st_family_ua']),
            str_replace($search, $replace, $post['st_family_en']),
            str_replace($search, $replace, $post['st_name_ru']),
            str_replace($search, $replace, $post['st_name_ua']),
            str_replace($search, $replace, $post['st_name_en']),
            str_replace($search, $replace, $post['st_surname_ru']),
            str_replace($search, $replace, $post['st_surname_ua']),
            str_replace($search, $replace, $post['st_surname_en']),
            (strlen(trim(html_entity_decode(strip_tags($post['st_description_ru']))))>0) ? addslashes($post['st_description_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['st_description_ua']))))>0) ? addslashes($post['st_description_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['st_description_en']))))>0) ? addslashes($post['st_description_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['st_text_ru']))))>0) ? addslashes($post['st_text_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['st_text_ua']))))>0) ? addslashes($post['st_text_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['st_text_en']))))>0) ? addslashes($post['st_text_en']) : '',
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $st_is_show_birth,
            str_replace($search, $replace, trim($post['st_height'])),
            str_replace($search, $replace, trim($post['st_weight'])),
            str_replace($search, $replace, trim($post['st_email'])),
            str_replace($search, $replace, trim($this->getTranslit(strtolower($_POST['st_address'])))),
            str_replace($search, $replace, trim(serialize($post['st_info'])))
        );
        if ($this->hdl->addElem(DB_T_PREFIX."staff", $elem)) return true;
        else return false;
    }

    public function updateStaff($post){ // редактирование человека
        $search = array("'", '"');
        $replace = array('', '');
        if (strlen(str_replace($search, $replace, trim($post['st_family_ru'])))==0) return false;
        if($post['st_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['st_is_show_birth']==true) $st_is_show_birth ='yes';
        else $st_is_show_birth = 'no';
        if ($post['st_date_day'] != '' and $post['st_date_day']>0 and $post['st_date_month'] != '' and $post['st_date_month']>0 and $post['st_date_year'] != '' and $post['st_date_year']>0) {
            $post['st_date_day'] = intval($post['st_date_day']);
            $post['st_date_month'] = intval($post['st_date_month']);
            $post['st_date_year'] = intval($post['st_date_year']);
            if ($post['st_date_day'] < 10) $post['st_date_day'] = '0'.$post['st_date_day'];
            if ($post['st_date_month'] < 10) $post['st_date_month'] = '0'.$post['st_date_month'];
            $st_date_birth = $post['st_date_year']."-".$post['st_date_month']."-".$post['st_date_day'];
        }
        $elems = array(
            "st_date_birth" => $st_date_birth,
            "st_family_ru" => str_replace($search, $replace, $post['st_family_ru']),
            "st_family_ua" => str_replace($search, $replace, $post['st_family_ua']),
            "st_family_en" => str_replace($search, $replace, $post['st_family_en']),
            "st_name_ru" => str_replace($search, $replace, $post['st_name_ru']),
            "st_name_ua" => str_replace($search, $replace, $post['st_name_ua']),
            "st_name_en" => str_replace($search, $replace, $post['st_name_en']),
            "st_surname_ru" => str_replace($search, $replace, $post['st_surname_ru']),
            "st_surname_ua" => str_replace($search, $replace, $post['st_surname_ua']),
            "st_surname_en" => str_replace($search, $replace, $post['st_surname_en']),
            "st_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['st_description_ru']))))>0) ? addslashes($post['st_description_ru']) : '',
            "st_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['st_description_ua']))))>0) ? addslashes($post['st_description_ua']) : '',
            "st_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['st_description_en']))))>0) ? addslashes($post['st_description_en']) : '',
            "st_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['st_text_ru']))))>0) ? addslashes($post['st_text_ru']) : '',
            "st_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['st_text_ua']))))>0) ? addslashes($post['st_text_ua']) : '',
            "st_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['st_text_en']))))>0) ? addslashes($post['st_text_en']) : '',
            "st_is_active" => $is_active,
            "st_is_show_birth" => $st_is_show_birth,
            "st_height" => str_replace($search, $replace, $post['st_height']),
            "st_address" => str_replace($search, $replace, strtolower($this->getTranslit(strtolower($post['st_address'])))),
            "st_weight" => str_replace($search, $replace, $post['st_weight']),
            "st_email" => str_replace($search, $replace, $post['st_email']),
            "st_datetime_edit" => 'NOW()',
            "st_author" => USER_ID
        );
        $condition = array(
            "st_id"=>$post['st_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."staff",$elems, $condition)) return true;
        else return false;
    }

    private function getPhotoAv($st_id = 0){
        $st_id = intval($st_id);
        if ($st_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id
					","ph_type = 'staff' AND ph_type_id = $st_id LIMIT 1");
        if ($temp) return true;
        return false;
    }

    public function getStaffList($sort = 0, $letter = '', $search = ''){
        $sort = intval($sort);
//			$letter = substr($letter, 0, 1);

        // Текстовый поиск по ФИО
        if ($search != '') {
            $search_arr = array("'", '"', "\\");
            $replace_arr = array('', '', '');
            $search = str_replace($search_arr, $replace_arr, trim($search));
            $q_letter = "(st_family_ru LIKE '%$search%' OR st_name_ru LIKE '%$search%' OR st_surname_ru LIKE '%$search%' OR st_family_ua LIKE '%$search%' OR st_name_ua LIKE '%$search%' OR st_family_en LIKE '%$search%' OR st_name_en LIKE '%$search%')";
        }
        // Поиск по букве
        elseif ($letter != ''){
            if ($sort == 2) $q_letter = "st_surname_ru LIKE '$letter%'";
            elseif ($sort == 1) $q_letter = "st_name_ru LIKE '$letter%'";
            else $q_letter = "st_family_ru LIKE '$letter%'";
        }

        if (!empty($q_letter)){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."staff",
                "	st_id,
						st_family_ru,
						st_name_ru,
						st_surname_ru,
						st_is_active
					","$q_letter ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, st_id ASC");
            if ($temp){
                $q_club = $q_team = '';
                foreach ($temp as &$item_t){
                    $item_t['st_family_ru'] = stripslashes($item_t['st_family_ru']);
                    $item_t['st_name_ru'] = stripslashes($item_t['st_name_ru']);
                    $item_t['st_surname_ru'] = stripslashes($item_t['st_surname_ru']);
                    $item_t['photo_av'] = $this->getPhotoAv($item_t['st_id']);
                    $q_team .= "OR cnstt_st_id = '".$item_t['st_id']."' ";
                    $q_club .= "OR cnstcl_st_id = '".$item_t['st_id']."' ";
                }
                $q_team = " AND (".substr($q_team, 2).")";
                $q_club = " AND (".substr($q_club, 2).")";
                $team = $this->hdl->selectElem(DB_T_PREFIX."connection_st_t, ".DB_T_PREFIX."team, ".DB_T_PREFIX."team_appointment",
                    "	t_id,
							cnstt_st_id AS st_id,
							t_title_ru,
							cnstt_date_quit,
							app_title_ru
						"," cnstt_t_id = t_id AND cnstt_app_id = app_id AND cnstt_is_delete = 'no' AND t_is_delete = 'no' $q_team ORDER BY cnstt_st_id ASC, t_title_ru ASC");
                if ($team)
                    foreach ($team as $item){
                        if (isset($team_id[$item['st_id']])) {
                            $team_id[$item['st_id']] .= ', <br><a href="?show=team&get=edit&item='.$item['t_id'].'" title="'.$item['app_title_ru'].'"';
                        } else {
                            $team_id[$item['st_id']] = '<a href="?show=team&get=edit&item='.$item['t_id'].'" title="'.$item['app_title_ru'].'"';
                        }
                        if ($item['cnstt_date_quit'] != '0000-00-00 00:00:00') $team_id[$item['st_id']] .= 'style="color: #d30000;"';
                        else $team_id[$item['st_id']] .= 'style="color: #01980f;"';
                        $team_id[$item['st_id']] .= '>'.$item['t_title_ru'].'</a>';
                    }
                $club = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl, ".DB_T_PREFIX."club, ".DB_T_PREFIX."team_appointment",
                    "	cl_id,
							cnstcl_st_id AS st_id,
							cl_title_ru,
							cnstcl_date_quit,
							app_title_ru
						"," cnstcl_cl_id = cl_id AND cnstcl_app_id = app_id AND cnstcl_is_delete = 'no' $q_club ORDER BY cnstcl_st_id ASC, cl_title_ru ASC");
                if ($club)
                    foreach ($club as $item){
                        if (isset($club_id[$item['st_id']])) {
                            $club_id[$item['st_id']] .= ', <br><a href="?show=club&get=edit&item='.$item['cl_id'].'" title="'.$item['app_title_ru'].'"';
                        } else {
                            $club_id[$item['st_id']] = '<a href="?show=club&get=edit&item='.$item['cl_id'].'" title="'.$item['app_title_ru'].'"';
                        }
                        if ($item['cnstcl_date_quit'] != '0000-00-00 00:00:00') $club_id[$item['st_id']] .= 'style="color: #d30000;"';
                        else $club_id[$item['st_id']] .= 'style="color: #01980f;"';
                        $club_id[$item['st_id']] .= '>'.$item['cl_title_ru'].'</a>';
                    }

                foreach ($temp as &$item){
                    if (isset ($team_id[$item['st_id']])) $item['team'] = $team_id[$item['st_id']];
                    if (isset ($club_id[$item['st_id']])) $item['club'] = $club_id[$item['st_id']];
                }
                return $temp;
            }
        }
        return false;
    }

    public function getLetterList($sort = 0){
        $sort = intval($sort);
        $abc = array(
            "а"=>'',
            "б"=>'',
            "в"=>'',
            "г"=>'',
            "д"=>'',
            "е"=>'',
            "ё"=>'',
            "ж"=>'',
            "з"=>'',
            "и"=>'',
            "й"=>'',
            "к"=>'',
            "л"=>'',
            "м"=>'',
            "н"=>'',
            "о"=>'',
            "п"=>'',
            "р"=>'',
            "с"=>'',
            "т"=>'',
            "у"=>'',
            "ф"=>'',
            "х"=>'',
            "ц"=>'',
            "ч"=>'',
            "ш"=>'',
            "щ"=>'',
            "ы"=>'',
            "э"=>'',
            "ю"=>'',
            "я"=>''
        );
        if ($sort == 2) {
            $q_sort = "st_surname_ru as val";
            $q_order = "st_surname_ru ASC";
        } elseif($sort == 1) {
            $q_sort = "st_name_ru as val";
            $q_order = "st_name_ru ASC";
        } else {
            $q_sort = "st_family_ru as val";
            $q_order = "st_family_ru ASC";
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."staff",$q_sort,"1 ORDER BY $q_order , st_id ASC");
        if ($temp){
            foreach ($temp as $item){
                // Используем UTF-8 напрямую
                $val = mb_substr(trim($item['val']), 0, 1, 'UTF-8');
                $val_lower = $this->strtolower_ru($val);
                $abc[$val_lower] = rawurlencode($val_lower);
            }
            return $abc;
        } else return false;
    }

    public function strtolower_ru($text) {
        $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
        $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

        return str_replace($alfavitupper,$alfavitlover,strtolower($text));
    }

    public function getStaffItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."staff","*","st_id=$item");
            if ($temp) {
                $temp = $temp[0];
                foreach ($temp as $key=>&$item) {
                    if ($key == 'st_info') {
                        $item = unserialize($item);
                    } else {
                        $item = stripcslashes($item);
                    }
                }
            }
            return $temp;
        } else return false;
    }

    public function deleteStaff($st_id){
        $st_id = intval($st_id);
        if ($st_id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."staff", "st_id='$st_id'")) return true;
            else return false;
        }else return false;
    }

    // SETTINGS ///////////////////////////////////////////////////////////////////////////////////////

    public function getStaffSettings(){
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

    // ГАЛЕРЕЯ ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($staff_item, &$photos_class){ // добавление фотографии в галерею
        $staff_item['st_id'] = intval($staff_item['st_id']);
        if ($staff_item['st_id'] < 1) return false;
        $type = 'staff';

        if ($_POST['ph_gallery_id'] == 'new') {
            if ($staff_item['st_family_ru'] == '' AND $staff_item['st_name_ru'] == '' AND $staff_item['st_surname_ru']) $phg_post['phg_title_ru'] = $staff_item['st_id'];
            else $phg_post['phg_title_ru'] = $staff_item['st_family_ru']." ".$staff_item['st_name_ru']." ".$staff_item['st_surname_ru'];
            if ($staff_item['st_family_ua'] == '' AND $staff_item['st_name_ua'] == '' AND $staff_item['st_surname_ua']) $phg_post['phg_title_ua'] = $staff_item['st_id'];
            else $phg_post['phg_title_ua'] = $staff_item['st_family_ua']." ".$staff_item['st_name_ua']." ".$staff_item['st_surname_ua'];
            if ($staff_item['st_family_en'] == '' AND $staff_item['st_name_en'] == '' AND $staff_item['st_surname_en']) $phg_post['phg_title_en'] = $staff_item['st_id'];
            else $phg_post['phg_title_en'] = $staff_item['st_family_en']." ".$staff_item['st_name_en']." ".$staff_item['st_surname_en'];

            $phg_post['phg_description_ru'] = "Фото галерея <b>".$phg_post['phg_title_ru']."</b>.";
            $phg_post['phg_description_ua'] = "Фото галерея <b>".$phg_post['phg_title_ua']."</b>.";
            $phg_post['phg_description_en'] = "Photo gallery <b>".$phg_post['phg_title_en']."</b>.";
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_is_informer'] = false;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $staff_item['st_id'];
            $phg_post['phg_phc_id'] = intval($_POST['phg_phc_id']);
            $phg_post['phg_phc_id'] = 0;
            $phg_post['phg_datetime_pub'] = false;

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id']<1) return false;
        } else {
            $photos_class->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if ($_POST['ph_type_main']) $photos_class->resetTypeMainPhotos($staff_item['st_id'], $type);
        $_POST['ph_type_id'] = $staff_item['st_id'];
        $_POST['ph_type'] = $type;

        if ($photos_class->savePhoto($_FILES['file_photo'], $_POST)) return true;
        return false;
    }

    public function saveVideo($staff_item, &$videos_class){ // добавление видео в галерею
        $staff_item['st_id'] = intval($staff_item['st_id']);
        if ($staff_item['st_id'] < 1) return false;
        $type = 'staff';

        if ($_POST['v_gallery_id'] == 'new') {
            if ($staff_item['st_family_ru'] == '' AND $staff_item['st_name_ru'] == '' AND $staff_item['st_surname_ru']) $vg_post['vg_title_ru'] = $staff_item['st_id'];
            else $vg_post['vg_title_ru'] = $staff_item['st_family_ru']." ".$staff_item['st_name_ru']." ".$staff_item['st_surname_ru'];
            if ($staff_item['st_family_ua'] == '' AND $staff_item['st_name_ua'] == '' AND $staff_item['st_surname_ua']) $vg_post['vg_title_ua'] = $staff_item['st_id'];
            else $vg_post['vg_title_ua'] = $staff_item['st_family_ua']." ".$staff_item['st_name_ua']." ".$staff_item['st_surname_ua'];
            if ($staff_item['st_family_en'] == '' AND $staff_item['st_name_en'] == '' AND $staff_item['st_surname_en']) $vg_post['vg_title_en'] = $staff_item['st_id'];
            else $vg_post['vg_title_en'] = $staff_item['st_family_en']." ".$staff_item['st_name_en']." ".$staff_item['st_surname_en'];

            $vg_post['vg_description_ru'] = "Видео галерея &laquo;".$vg_post['vg_title_ru']."&raquo;.";
            $vg_post['vg_description_ua'] = "Відео галерея &laquo;".$vg_post['vg_title_ua']."&raquo;.";
            $vg_post['vg_description_en'] = "Video gallery &laquo;".$vg_post['vg_title_en']."&raquo;.";
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_is_informer'] = false;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $staff_item['st_id'];
            $vg_post['vg_vc_id'] = intval($_POST['vg_vc_id']);


            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        } else {
            $videos_class->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $staff_item['st_id'];
        $_POST['v_type'] = 'staff';

        if ($videos_class->saveVideo($_POST)) return true;
        return false;
    }

    private function getTranslit($st, $charset = 'utf-8'){
        if (strtolower($charset) == 'utf-8'){
            $st = iconv("UTF-8", "cp1251", $st);
            // Сначала заменяем "односимвольные" фонемы.
            $st=strtr($st, iconv("UTF-8", "cp1251", "абвгдеёзийклмнопрстуфхыэ"), "abvgdeeziyklmnoprstufhie");
            $st=strtr($st, iconv("UTF-8", "cp1251", "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ"), "ABVGDEEZIYKLMNOPRSTUFHIE");
            // Затем - "многосимвольные и др.".
            $st=strtr($st, array(
                    iconv("UTF-8", "cp1251", "ж")=>"zh",
                    iconv("UTF-8", "cp1251", "ц")=>"ts",
                    iconv("UTF-8", "cp1251", "ч")=>"ch",
                    iconv("UTF-8", "cp1251", "ш")=>"sh",
                    iconv("UTF-8", "cp1251", "щ")=>"shch",
                    iconv("UTF-8", "cp1251", "ь")=>"",
                    iconv("UTF-8", "cp1251", "ю")=>"yu",
                    iconv("UTF-8", "cp1251", "я")=>"ya",
                    iconv("UTF-8", "cp1251", "ъ")=>"",
                    iconv("UTF-8", "cp1251", "Ъ")=>"",
                    " "=>"_",
                    iconv("UTF-8", "cp1251", "Ж")=>"ZH",
                    iconv("UTF-8", "cp1251", "Ц")=>"TS",
                    iconv("UTF-8", "cp1251", "Ч")=>"CH",
                    iconv("UTF-8", "cp1251", "Ш")=>"SH",
                    iconv("UTF-8", "cp1251", "Щ")=>"SHCH",
                    iconv("UTF-8", "cp1251", "Ь")=>"",
                    iconv("UTF-8", "cp1251", "Ю")=>"YU",
                    iconv("UTF-8", "cp1251", "Я")=>"YA",
                    iconv("UTF-8", "cp1251", "ї")=>"i",
                    iconv("UTF-8", "cp1251", "Ї")=>"Yi",
                    iconv("UTF-8", "cp1251", "є")=>"ie",
                    iconv("UTF-8", "cp1251", "Є")=>"Ye",
                    iconv("UTF-8", "cp1251", "!")=>'',
                    iconv("UTF-8", "cp1251", "@")=>'',
                    iconv("UTF-8", "cp1251", "#")=>'',
                    iconv("UTF-8", "cp1251", "№")=>'',
                    iconv("UTF-8", "cp1251", "$")=>'',
                    iconv("UTF-8", "cp1251", "%")=>'',
                    iconv("UTF-8", "cp1251", "^")=>'',
                    iconv("UTF-8", "cp1251", "&")=>'',
                    iconv("UTF-8", "cp1251", "*")=>'',
                    iconv("UTF-8", "cp1251", "(")=>'',
                    iconv("UTF-8", "cp1251", ")")=>'',
                    iconv("UTF-8", "cp1251", "+")=>'',
                    iconv("UTF-8", "cp1251", "=")=>'',
                    iconv("UTF-8", "cp1251", "[")=>'',
                    iconv("UTF-8", "cp1251", "]")=>'',
                    iconv("UTF-8", "cp1251", "{")=>'',
                    iconv("UTF-8", "cp1251", "}")=>'',
                    iconv("UTF-8", "cp1251", "|")=>'',
                    iconv("UTF-8", "cp1251", "?")=>'',
                    iconv("UTF-8", "cp1251", ">")=>'',
                    iconv("UTF-8", "cp1251", "<")=>'',
                    iconv("UTF-8", "cp1251", "`")=>'',
                    iconv("UTF-8", "cp1251", "~")=>''
                )
            );
        } else {
            // Сначала заменяем "односимвольные" фонемы.
            $st=strtr($st, "абвгдеёзийклмнопрстуфхыэ", "abvgdeeziyklmnoprstufhie");
            $st=strtr($st, "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ", "ABVGDEEZIYKLMNOPRSTUFHIE");
            // Затем - "многосимвольные и др.".
            $st=strtr($st, array(
                    "ж"=>"zh",
                    "ц"=>"ts",
                    "ч"=>"ch",
                    "ш"=>"sh",
                    "щ"=>"shch",
                    "ь"=>"",
                    "ю"=>"yu",
                    "я"=>"ya",
                    "ъ"=>"",
                    "Ъ"=>"",
                    " "=>"_",
                    "Ж"=>"ZH",
                    "Ц"=>"TS",
                    "Ч"=>"CH",
                    "Ш"=>"SH",
                    "Щ"=>"SHCH",
                    "Ь"=>"",
                    "Ю"=>"YU",
                    "Я"=>"YA",
                    "ї"=>"i",
                    "Ї"=>"Yi",
                    "є"=>"ie",
                    "Є"=>"Ye",
                    "!"=>'',
                    "@"=>'',
                    "#"=>'',
                    "№"=>'',
                    "$"=>'',
                    "%"=>'',
                    "^"=>'',
                    "&"=>'',
                    "*"=>'',
                    "("=>'',
                    ")"=>'',
                    "+"=>'',
                    "="=>'',
                    "["=>'',
                    "]"=>'',
                    "{"=>'',
                    "}"=>'',
                    "|"=>'',
                    "?"=>'',
                    ">"=>'',
                    "<"=>'',
                    "`"=>'',
                    "~"=>''
                )
            );
        }
        // Возвращаем результат.
        return $st;
    }

    public function createStaffCustomStatistics($post){ // add custom statistics for one people
        $search = array("'", '"');
        $replace = array('', '');
        $team_list_by_id = array();
        $team_list = $this->hdl->selectElem(DB_T_PREFIX."team","
					t_id as id,
					t_title_ru as title
					","t_is_delete = 'no'");
        if ($team_list) {
            foreach ($team_list as $item){
                $team_list_by_id[$item['id']] = $item['title'];
            }
        }

        if (!empty($post['st_id']) && $post['st_id']>0){
            $post['st_id'] = intval($post['st_id']);
        } else {
            return false;
        }
        $st_item = $this->hdl->selectElem(DB_T_PREFIX."staff","st_id, st_info","st_id={$post['st_id']}");
        if ($st_item) {
            $st_info = $st_item[0]['st_info'];
            if (!empty($st_info)) {
                $st_info = unserialize($st_info);
            }
        }
        if (empty($st_info)) {
            $st_info = array();
        }
        if (empty($st_info['statistic'])){
            $st_info['statistic'] = array(
                'custom' => array()
            );
        }
        $is_delete = true;
        $key_check_delete = array('team_id', 'team_title', 'staging', 'season', 'games', 'points', 'p', 'sh', 'r', 'dg', 'yc', 'rc');
        foreach ($key_check_delete as $check_item) {
            if (!empty($post[$check_item])) {
                $is_delete = false;
            }
        }
        if ($is_delete) {
            return false;
        }
        $st_info['statistic']['custom'][] = array(
            'team_id' => intval($post['team_list_id']),
            'team_title' => ($post['team_list_id']>0) ? $team_list_by_id[$post['team_list_id']] : str_replace($search, $replace, $post['team_new_item']),
            'staging' => str_replace($search, $replace, $post['staging']),
            'season' => str_replace($search, $replace, $post['season']),
            'games' => str_replace($search, $replace, $post['games']),
            'points' => str_replace($search, $replace, $post['points']),
            'p' => str_replace($search, $replace, $post['p']),
            'sh' => str_replace($search, $replace, $post['sh']),
            'r' => str_replace($search, $replace, $post['r']),
            'dg' => str_replace($search, $replace, $post['dg']),
            'yc' => str_replace($search, $replace, $post['yc']),
            'rc' => str_replace($search, $replace, $post['rc']),
            'date_add'=>date("Y.m.d h:i:s"),
            'date_edit'=>'',
            'author'=>USER_ID,
        );
        $elems = array(
            "st_info" => serialize($st_info),
            "st_datetime_edit" => 'NOW()',
            "st_author" => USER_ID
        );
        $condition = array(
            "st_id"=>$post['st_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."staff",$elems, $condition)) return true;
        else return false;
    }

    public function updateStaffCustomStatistics($post){ // add custom statistics for one people
        $search = array("'", '"');
        $replace = array('', '');
        if (!empty($post['st_id']) && $post['st_id']>0){
            $post['st_id'] = intval($post['st_id']);
        } else {
            return false;
        }
        $team_list_by_id = array();
        $team_list = $this->hdl->selectElem(DB_T_PREFIX."team","
					t_id as id,
					t_title_ru as title
					","t_is_delete = 'no'");
        if ($team_list) {
            foreach ($team_list as $item){
                $team_list_by_id[$item['id']] = $item['title'];
            }
        }
        if (isset($post['c_stat_key'])){
            $post['c_stat_key'] = intval($post['c_stat_key']);
        } else {
            return false;
        }
        $st_item = $this->hdl->selectElem(DB_T_PREFIX."staff","st_id, st_info","st_id={$post['st_id']}");
        if ($st_item) {
            $st_info = $st_item[0]['st_info'];
            if (!empty($st_info)) {
                $st_info = unserialize($st_info);
            }
        }
        $is_delete = true;
        $key_check_delete = array('team_id', 'team_title', 'staging', 'season', 'games', 'points', 'p', 'sh', 'r', 'dg', 'yc', 'rc');
        foreach ($key_check_delete as $check_item) {
            if (!empty($post[$check_item])) {
                $is_delete = false;
            }
        }
        if ($is_delete && isset($st_info['statistic']['custom'][$post['c_stat_key']])) {
            unset($st_info['statistic']['custom'][$post['c_stat_key']]);
        } else {
            if (!isset($st_info['statistic']['custom'][$post['c_stat_key']])) {
                return false;
            } else {
                $st_info['statistic']['custom'][$post['c_stat_key']] = array(
                    'team_id' => intval($post['team_list_id']),
                    'team_title' => ($post['team_list_id']>0) ? $team_list_by_id[$post['team_list_id']] : str_replace($search, $replace, $post['team_new_item']),
                    'staging' => str_replace($search, $replace, $post['staging']),
                    'season' => str_replace($search, $replace, $post['season']),
                    'games' => str_replace($search, $replace, $post['games']),
                    'points' => str_replace($search, $replace, $post['points']),
                    'p' => str_replace($search, $replace, $post['p']),
                    'sh' => str_replace($search, $replace, $post['sh']),
                    'r' => str_replace($search, $replace, $post['r']),
                    'dg' => str_replace($search, $replace, $post['dg']),
                    'yc' => str_replace($search, $replace, $post['yc']),
                    'rc' => str_replace($search, $replace, $post['rc']),
                    'date_add'=>date("Y.m.d h:i:s"),
                    'date_edit'=>'',
                    'author'=>USER_ID,
                );
            }
        }

        $elems = array(
            "st_info" => serialize($st_info),
            "st_datetime_edit" => 'NOW()',
            "st_author" => USER_ID
        );
        $condition = array(
            "st_id"=>$post['st_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."staff",$elems, $condition)) return true;
        else return false;
    }

    /**
     * Объединение двух игроков: перенос всех данных из старого профиля в текущий
     * @param int $target_id - ID игрока, в который переносим данные (текущий профиль)
     * @param int $source_id - ID игрока, из которого переносим данные (дубль)
     * @return array - результат операции с деталями
     */
    public function mergeStaff($target_id, $source_id) {
        $target_id = intval($target_id);
        $source_id = intval($source_id);

        if ($target_id < 1 || $source_id < 1) {
            return array('success' => false, 'error' => 'Некорректные ID игроков');
        }

        if ($target_id == $source_id) {
            return array('success' => false, 'error' => 'Нельзя объединить игрока с самим собой');
        }

        // Проверяем существование обоих игроков
        $target = $this->getStaffItem($target_id);
        $source = $this->getStaffItem($source_id);

        if (!$target) {
            return array('success' => false, 'error' => 'Целевой игрок не найден');
        }
        if (!$source) {
            return array('success' => false, 'error' => 'Исходный игрок (дубль) не найден');
        }

        $merged = array();

        // 1. Перенос связей игрок-команда (rgr_connection_st_t)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_st_t",
            array('cnstt_st_id' => $target_id),
            "cnstt_st_id = $source_id");
        $merged[] = 'Связи с командами перенесены';

        // 2. Перенос участия в матчах (rgr_connection_g_st)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_g_st",
            array('cngst_st_id' => $target_id),
            "cngst_st_id = $source_id");
        $merged[] = 'Участие в матчах перенесено';

        // 3. Перенос игровых действий (rgr_games_actions) - основной игрок
        $this->hdl->updateElemExtra(DB_T_PREFIX."games_actions",
            array('ga_st_id' => $target_id),
            "ga_st_id = $source_id");
        $merged[] = 'Игровые действия перенесены';

        // 4. Перенос игровых действий - замены (ga_zst_id)
        $this->hdl->updateElemExtra(DB_T_PREFIX."games_actions",
            array('ga_zst_id' => $target_id),
            "ga_zst_id = $source_id");
        $merged[] = 'Замены в матчах перенесены';

        // 5. Перенос связей с новостями (rgr_connection_staff)
        $this->hdl->updateElemExtra(DB_T_PREFIX."connection_staff",
            array('st_id' => $target_id),
            "st_id = $source_id");
        $merged[] = 'Связи с новостями перенесены';

        // 6. Перенос фотографий (rgr_photos)
        $this->hdl->updateElemExtra(DB_T_PREFIX."photos",
            array('ph_type_id' => $target_id),
            "ph_type = 'staff' AND ph_type_id = $source_id");
        $merged[] = 'Фотографии перенесены';

        // 7. Перенос фото-галерей (rgr_photos_gallery)
        $this->hdl->updateElemExtra(DB_T_PREFIX."photos_gallery",
            array('phg_type_id' => $target_id),
            "phg_type = 'staff' AND phg_type_id = $source_id");
        $merged[] = 'Фото-галереи перенесены';

        // 8. Перенос видео (rgr_videos)
        $this->hdl->updateElemExtra(DB_T_PREFIX."videos",
            array('v_type_id' => $target_id),
            "v_type = 'staff' AND v_type_id = $source_id");
        $merged[] = 'Видео перенесены';

        // 9. Перенос видео-галерей (rgr_videos_gallery)
        $this->hdl->updateElemExtra(DB_T_PREFIX."videos_gallery",
            array('vg_type_id' => $target_id),
            "vg_type = 'staff' AND vg_type_id = $source_id");
        $merged[] = 'Видео-галереи перенесены';

        // 10. Объединение кастомной статистики из st_info
        if (!empty($source['st_info']['statistic']['custom'])) {
            $target_info = $target['st_info'];
            if (empty($target_info)) $target_info = array();
            if (empty($target_info['statistic'])) $target_info['statistic'] = array('custom' => array());
            if (empty($target_info['statistic']['custom'])) $target_info['statistic']['custom'] = array();

            foreach ($source['st_info']['statistic']['custom'] as $stat) {
                $target_info['statistic']['custom'][] = $stat;
            }

            $this->hdl->updateElemExtra(DB_T_PREFIX."staff",
                array('st_info' => addslashes(serialize($target_info))),
                "st_id = $target_id");
            $merged[] = 'Кастомная статистика объединена';
        }

        // 11. Создание 301 редиректа со старой страницы на новую
        $source_url = '/player/' . ($source['st_address'] ? $source['st_address'] : $source_id);
        $target_url = '/player/' . ($target['st_address'] ? $target['st_address'] : $target_id);

        // Также редирект по ID
        $source_url_id = '/player/' . $source_id;
        $target_url_id = '/player/' . $target_id;

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

        // 12. Помечаем старого игрока как удалённого (мягкое удаление)
        $this->hdl->updateElem(DB_T_PREFIX."staff",
            array('st_is_active' => 'no', 'st_datetime_edit' => 'NOW()', 'st_author' => USER_ID),
            array('st_id' => $source_id));
        $merged[] = 'Дубль деактивирован';

        return array(
            'success' => true,
            'merged' => $merged,
            'source' => $source['st_family_ru'] . ' ' . $source['st_name_ru'] . ' (ID: ' . $source_id . ')',
            'target' => $target['st_family_ru'] . ' ' . $target['st_name_ru'] . ' (ID: ' . $target_id . ')'
        );
    }
}
