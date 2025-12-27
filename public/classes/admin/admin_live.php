<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once(__DIR__.'/admin_base.php');

class live extends adminBase{
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // LIVE ///////////////////////////////////////////////////////////////////////////////////////

    public function createLive($post){ // добавление новости
        if($post['l_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['l_is_export']==true) $l_is_export ='yes';
        else $l_is_export = 'no';
        if($post['l_is_export_vk']==true) $l_is_export_vk ='yes';
        else $l_is_export_vk = 'no';
        if($post['l_is_export_fru']==true) $l_is_export_fru ='yes';
        else $l_is_export_fru = 'no';
        if($post['l_is_photo_top']==true) $l_is_photo_top ='yes';
        else $l_is_photo_top = 'no';
        $post['l_top'] = intval($post['l_top']);
        if ($post['l_top']>4) $post['l_top'] = 0;
        if ($post['l_date_day'] != '' and $post['l_date_day']>0 and $post['l_date_month'] != '' and $post['l_date_month']>0 and $post['l_date_year'] != '' and $post['l_date_year']>0) {
            $post['l_date_day'] = intval($post['l_date_day']);
            $post['l_date_month'] = intval($post['l_date_month']);
            $post['l_date_year'] = intval($post['l_date_year']);
            $post['l_date_hour'] = intval($post['l_date_hour']);
            $post['l_date_minute'] = intval($post['l_date_minute']);
            if ($post['l_date_day'] < 10) $post['l_date_day'] = '0'.$post['l_date_day'];
            if ($post['l_date_month'] < 10) $post['l_date_month'] = '0'.$post['l_date_month'];
            if ($post['l_date_hour'] < 10) $post['l_date_hour'] = '0'.$post['l_date_hour'];
            if ($post['l_date_minute'] < 10) $post['l_date_minute'] = '0'.$post['l_date_minute'];
            $l_date_show = $post['l_date_year']."-".$post['l_date_month']."-".$post['l_date_day']." ".$post['l_date_hour'].":".$post['l_date_minute'].":00";
        }
        $post['l_address'] = $this->getTranslit(strtolower($post['l_address']));
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $elem = array(
            (strlen(trim(html_entity_decode(strip_tags($post['l_title_ru']))))>0) ? str_replace($search, $replace, $post['l_title_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_title_ua']))))>0) ? str_replace($search, $replace, $post['l_title_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_title_en']))))>0) ? str_replace($search, $replace, $post['l_title_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_description_ru'], "<a>, <img>"))))>0) ? addslashes($post['l_description_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_description_ua'], "<a>, <img>"))))>0) ? addslashes($post['l_description_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_description_en'], "<a>, <img>"))))>0) ? addslashes($post['l_description_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_text_ru'], "<b>, <strong>, <em>, <u>, <i>, <p>, <a>, <img>, <iframe>, <object>"))))>0) ? addslashes($post['l_text_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_text_ua'], "<b>, <strong>, <em>, <u>, <i>, <p>, <a>, <img>, <iframe>"))))>0) ? addslashes($post['l_text_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['l_text_en'], "<b>, <strong>, <em>, <u>, <i>, <p>, <a>, <img>, <iframe>"))))>0) ? addslashes($post['l_text_en']) : '',
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $l_date_show,
            intval($post['l_lc_id']),
            str_replace($search, $replace, $post['l_tags']),
            $l_is_export,
            $l_is_export_vk,
            $l_is_export_fru,
            '',
            str_replace($search, $replace, $post['l_sign']),
            $post['l_top'],
            $l_is_photo_top,
            addslashes($post['l_sign_url']),
            $post['l_address']
        );

        if ($l_id = $this->hdl->addElem(DB_T_PREFIX."live", $elem)) {
            if (!empty($post['game_auto_val'])){
                $this->_updateConnectionGame(array(
                    'game'=>$post['game_auto_val'],
                    'id'=>$l_id,
                    'type'=>'live'
                ));
            }
            return true;
        } else return false;
    }

    public function updateLive($post){ // редактирование новости
        if($post['l_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['l_is_export']==true) $l_is_export ='yes';
        else $l_is_export = 'no';
        if($post['l_is_export_vk']==true) $l_is_export_vk ='yes';
        else $l_is_export_vk = 'no';
        if($post['l_is_export_fru']==true) $l_is_export_fru ='yes';
        else $l_is_export_fru = 'no';
        if($post['l_is_photo_top']==true) $l_is_photo_top ='yes';
        else $l_is_photo_top = 'no';
        $post['l_top'] = intval($post['l_top']);
        if ($post['l_top']>4) $post['l_top'] = 0;
        if ($post['l_date_day'] != '' and $post['l_date_day']>0 and $post['l_date_month'] != '' and $post['l_date_month']>0 and $post['l_date_year'] != '' and $post['l_date_year']>0) {
            $post['l_date_day'] = intval($post['l_date_day']);
            $post['l_date_month'] = intval($post['l_date_month']);
            $post['l_date_year'] = intval($post['l_date_year']);
            $post['l_date_hour'] = intval($post['l_date_hour']);
            $post['l_date_minute'] = intval($post['l_date_minute']);
            if ($post['l_date_day'] < 10) $post['l_date_day'] = '0'.$post['l_date_day'];
            if ($post['l_date_month'] < 10) $post['l_date_month'] = '0'.$post['l_date_month'];
            if ($post['l_date_hour'] < 10) $post['l_date_hour'] = '0'.$post['l_date_hour'];
            if ($post['l_date_minute'] < 10) $post['l_date_minute'] = '0'.$post['l_date_minute'];
            $l_date_show = $post['l_date_year']."-".$post['l_date_month']."-".$post['l_date_day']." ".$post['l_date_hour'].":".$post['l_date_minute'].":00";
        }
        $post['l_address'] = $this->getTranslit(strtolower($post['l_address']));
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $elems = array(
            "l_title_ru" => (strlen(trim(html_entity_decode(strip_tags($post['l_title_ru']))))>0) ? str_replace($search, $replace, $post['l_title_ru']) : '',
            "l_title_ua" => (strlen(trim(html_entity_decode(strip_tags($post['l_title_ua']))))>0) ? str_replace($search, $replace, $post['l_title_ua']) : '',
            "l_title_en" => (strlen(trim(html_entity_decode(strip_tags($post['l_title_en']))))>0) ? str_replace($search, $replace, $post['l_title_en']) : '',
            "l_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['l_description_ru'], "<a>, <img>"))))>0) ? addslashes($post['l_description_ru']) : '',
            "l_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['l_description_ua'], "<a>, <img>"))))>0) ? addslashes($post['l_description_ua']) : '',
            "l_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['l_description_en'], "<a>, <img>"))))>0) ? addslashes($post['l_description_en']) : '',
            "l_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['l_text_ru'], "<a>, <img>"))))>0) ? addslashes($post['l_text_ru']) : '',
            "l_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['l_text_ua'], "<a>, <img>"))))>0) ? addslashes($post['l_text_ua']) : '',
            "l_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['l_text_en'], "<a>, <img>"))))>0) ? addslashes($post['l_text_en']) : '',
            "l_is_active" => $is_active,
            "l_datetime_edit" => 'NOW()',
            "l_author" => USER_ID,
            "l_date_show" => $l_date_show,
            "l_lc_id" => intval($post['l_lc_id']),
            "l_tags" => str_replace($search, $replace, $post['l_tags']),
            "l_sign" => str_replace($search, $replace, $post['l_sign']),
            "l_sign_url" => addslashes($post['l_sign_url']),
            "l_is_export" => $l_is_export,
            "l_is_export_vk" => $l_is_export_vk,
            "l_is_export_fru" => $l_is_export_fru,
            "l_is_photo_top" => $l_is_photo_top,
            "l_top" => $post['l_top'],
            "l_address" => $post['l_address']
        );
        $condition = array(
            "l_id"=>$post['l_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."live",$elems, $condition)) {
            if (!empty($post['game_auto_val'])){
                $this->_updateConnectionGame(array(
                    'game'=>$post['game_auto_val'],
                    'id'=>$post['l_id'],
                    'type'=>'live'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionGame(array(
                    'id'=>$post['l_id'],
                    'type'=>'live'
                ));
            }
            return true;
        } else return false;
    }

    public function deleteLive($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."live", "l_id='$id'")) {
                // удаление фото
                $live_media = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_type = 'live' AND ph_type_id = '$id'");
                if ($live_media)
                    foreach ($live_media as $item){
                        if ($item['ph_folder'] == '') $item['ph_folder'] = '/';
                        if (file_exists ("../upload/photos".$item['ph_folder'].$item['ph_path'])) unlink("../upload/photos".$item['ph_folder'].$item['ph_path']);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-informer".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-main".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-s_main".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $this->hdl->delElem(DB_T_PREFIX."photos", "ph_id = '".$item['ph_id']."' LIMIT 1");
                    }
                unset($live_media);
                // удаление фото галерей
                $live_media = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id","phg_type = 'live' AND phg_type_id = '$id'");
                if ($live_media)
                    foreach ($live_media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_id = '".$item['phg_id']."' LIMIT 1");
                    }
                unset($live_media);
                // удаление видео
                $live_media = $this->hdl->selectElem(DB_T_PREFIX."videos","v_id, v_folder","v_type = 'live' AND v_type_id = '$id'");
                if ($live_media)
                    foreach ($live_media as $item){
                        if ($item['v_folder'] == '') $item['v_folder'] = '/';
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg");
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg");
                        $this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '".$item['v_id']."' LIMIT 1");
                    }
                unset($live_media);
                // удаление видео галерей
                $live_media = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","vg_id","vg_type = 'live' AND vg_type_id = '$id'");
                if ($live_media)
                    foreach ($live_media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_id = '".$item['vg_id']."' LIMIT 1");
                    }
                unset($live_media);
                // delete connections
                $this->_deleteConnectionGame(array(
                    'id'=>$id,
                    'type'=>'live'
                ));
                return true;
            }
        }
        return false;
    }

    public function getLiveItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $auto_val = array();
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live","*","l_id=$item");
            if (!$temp) return false;
            else $temp = $temp[0];
            $temp['l_title_ru'] = str_replace($search, $replace, stripcslashes($temp['l_title_ru']));
            $temp['l_title_ua'] = str_replace($search, $replace, stripcslashes($temp['l_title_ua']));
            $temp['l_title_en'] = str_replace($search, $replace, stripcslashes($temp['l_title_en']));
            $temp['l_description_ru'] = stripcslashes($temp['l_description_ru']);
            $temp['l_description_ua'] = stripcslashes($temp['l_description_ua']);
            $temp['l_description_en'] = stripcslashes($temp['l_description_en']);
            $temp['l_text_ru'] = stripcslashes($temp['l_text_ru']);
            $temp['l_text_ua'] = stripcslashes($temp['l_text_ua']);
            $temp['l_text_en'] = stripcslashes($temp['l_text_en']);
            $temp['l_sign_url'] = stripcslashes($temp['l_sign_url']);
            $temp['connection_games'] = $this->getGameListDay();
            $temp['connected_games'] = $this->getConnectionGame($item, false, 'live');
            if (!empty($temp['connected_games'])){
                foreach ($temp['connected_games'] as $gl_item){
                    $auto_val[] = $gl_item['id'];
                }
            }
            $temp['games_auto_val'] = implode(',', $auto_val);

            return $temp;
        }
        return false;
    }

    public function getGameListDay($date = false){
        if (empty($date)){
            $date = date("d.m.Y");
        }
        $games_list = $this->hdl->selectElem(DB_T_PREFIX."games g
                    LEFT JOIN ".DB_T_PREFIX."championship ch ON ch.ch_id = g.g_ch_id
                    LEFT JOIN ".DB_T_PREFIX."competitions cp ON cp.cp_id = g.g_cp_id
                    LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
                ","
                    g.g_id as id,
                    g.g_ch_id,
                    ch.ch_title_ru as ch_title,
                    g.g_cp_id,
                    cp.cp_title_ru as cp_title,
                    chg.chg_title_ru as chg_title,
                    g.g_owner_t_id,
                    g.g_guest_t_id,
                    g.g_owner_points,
                    g.g_guest_points,
                    g.g_is_done
                ","g.g_is_active = 'yes' AND
				    TO_DAYS(g.g_date_schedule) = TO_DAYS('".date("Y-m-d h:i:s", strtotime($date))."')
				    ORDER BY g.g_date_schedule DESC,
				    g.g_id DESC");
        if ($games_list) {
            foreach ($games_list as &$item){
                $team_q_a[] = $item['g_owner_t_id'];
                $team_q_a[] = $item['g_guest_t_id'];
            }
            $team_list = $this->hdl->selectElem(DB_T_PREFIX."team",
                "   t_id as id,
                    t_title_ru as title
                "," t_id IN (".implode(',',$team_q_a).") AND
                    t_is_delete = 'no'");
            if ($team_list) {
                foreach ($team_list as $team_list_item) {
                    $teams[$team_list_item['id']] = $team_list_item['title'];
                }
            }
            foreach ($games_list as &$item){
                $item['title'] = stripslashes($item['chg_title']).' '.
                    stripslashes($item['ch_title']).' '.
                    stripslashes($item['cp_title']).': '.
                    stripslashes($teams[$item['g_owner_t_id']]).' ';
                if ($item['g_is_done']){
                    $item['title'] .= '('.$item['g_owner_points'].':'.$item['g_guest_points'].')';
                }
                $item['title'] .= stripslashes($teams[$item['g_guest_t_id']]).' ';
            }
        }

        return $games_list;
    }

    public function getLiveList($page=1, $perpage=10, $gallery = 0){
        $extra_q = '1';
        $gallery = intval($gallery);
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page = $perpage*$page;
        if ($gallery > 0) $extra_q = "l_lc_id = '$gallery' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."live","
				l_id as id,
				l_title_ru as title,
				l_is_active,
				l_date_show,
				l_lc_id,
				l_is_export,
				l_is_export_vk,
				l_is_export_fru,
				l_export_id,
				l_top,
				l_is_photo_top
				","$extra_q ORDER BY l_date_show DESC, l_id DESC LIMIT $page, $perpage");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    public function getLivePages($page=1, $perpage=10, $gallery = 0){
        $extra_q = '';
        $gallery = intval($gallery);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        if ($gallery > 0) $extra_q = " AND l_lc_id = '$gallery' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."live","COUNT(*) as C_N","1 $extra_q");
        $c_pages = intval($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i <= $c_pages) $pages[$i] = $i+1;
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

    // SETTINGS ///////////////////////////////////////////////////////////////////////////////////////

    public function getLiveSettings(){
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

    public function savePhoto($live_item, &$photos_class){ // добавление фотографии в галерею
        $live_item['l_id'] = intval($live_item['l_id']);
        if ($live_item['l_id'] < 1) return false;
        $type = 'live';

        if ($_POST['ph_gallery_id'] == 'new') {
            if ($live_item['l_title_ru'] == '') $phg_post['phg_title_ru'] = $live_item['l_id'];
            else $phg_post['phg_title_ru'] = $live_item['l_title_ru'];
            if ($live_item['l_title_ua'] == '') $phg_post['phg_title_ua'] = $live_item['l_id'];
            else $phg_post['phg_title_ua'] = $live_item['l_title_ua'];
            if ($live_item['l_title_en'] == '') $phg_post['phg_title_en'] = $live_item['l_id'];
            else $phg_post['phg_title_en'] = $live_item['l_title_en'];

            $phg_post['phg_description_ru'] = "";
            $phg_post['phg_description_ua'] = "";
            $phg_post['phg_description_en'] = "";
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $live_item['l_id'];
            $phg_post['phg_phc_id'] = intval($_POST['phg_phc_id']);
            $phg_post['phg_datetime_pub'] = $live_item['l_date_show'];
            $phg_post['ph_country_auto_val'] = $_POST['ph_country_auto_val'];
            $phg_post['ph_champ_auto_val'] = $_POST['ph_champ_auto_val'];

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id']<1) return false;
        } else {
            $photos_class->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if ($_POST['ph_type_main']) $photos_class->resetTypeMainPhotos($live_item['l_id'], $type);
        $_POST['ph_type_id'] = $live_item['l_id'];
        $_POST['ph_type'] = $type;

        if ($photos_class->savePhoto($_FILES['file_photo'], $_POST)) return true;
        return false;
    }

    public function saveVideo($live_item, &$videos_class){ // добавление видео в галерею
        $live_item['l_id'] = intval($live_item['l_id']);
        if ($live_item['l_id'] < 1) return false;
        $type = 'live';

        if ($_POST['v_gallery_id'] == 'new') {
            if ($live_item['l_title_ru'] == '') $vg_post['vg_title_ru'] = $live_item['l_id'];
            else $vg_post['vg_title_ru'] = $live_item['l_title_ru'];
            if ($live_item['l_title_ua'] == '') $vg_post['vg_title_ua'] = $live_item['l_id'];
            else $vg_post['vg_title_ua'] = $live_item['l_title_ua'];
            if ($live_item['l_title_en'] == '') $vg_post['vg_title_en'] = $live_item['l_id'];
            else $vg_post['vg_title_en'] = $live_item['l_title_en'];

            $vg_post['vg_description_ru'] = "";
            $vg_post['vg_description_ua'] = "";
            $vg_post['vg_description_en'] = "";
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_is_informer'] = true;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $live_item['l_id'];
            $vg_post['vg_phc_id'] = intval($_POST['vg_vc_id']);
            $vg_post['vg_datetime_pub'] = $live_item['l_date_show'];
            $vg_post['v_country_auto_val'] = $_POST['v_country_auto_val'];
            $vg_post['v_champ_auto_val'] = $_POST['v_champ_auto_val'];

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        } else {
            $videos_class->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $live_item['l_id'];
        $_POST['v_type'] = $type;

        if ($videos_class->saveVideo($_POST)) return true;
        return false;
    }

    // CATEGORIES /////////////////////////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['lc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['lc_address'] = strtolower($post['lc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '&quot;', '&quot;');
        $elem = array(
            addslashes($post['lc_title_ru']),
            addslashes($post['lc_title_ua']),
            addslashes($post['lc_title_en']),
            str_replace($search, $replace, $post['lc_address']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            intval($post['lc_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."live_categories", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['lc_id'] <1) return false;
        if($post['lc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['lc_address'] = strtolower($post['lc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '&quot;', '&quot;');
        $elems = array(
            "lc_title_ru" => addslashes($post['lc_title_ru']),
            "lc_title_ua" => addslashes($post['lc_title_ua']),
            "lc_title_en" => addslashes($post['lc_title_en']),
            "lc_address" => str_replace($search, $replace, $post['lc_address']),
            "lc_is_active" => $is_active,
            "lc_datetime_edit" => 'NOW()',
            "lc_author" => USER_ID,
            "lc_order" => intval($post['lc_order'])
        );
        $condition = array(
            "lc_id"=>$post['lc_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."live_categories",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."live_categories", "lc_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live_categories","*","lc_id=$item");
            $temp[0]['l_title_ru'] = stripcslashes($temp[0]['lc_title_ru']);
            $temp[0]['l_title_ua'] = stripcslashes($temp[0]['lc_title_ua']);
            $temp[0]['l_title_en'] = stripcslashes($temp[0]['lc_title_en']);
            return $temp[0];
        } else return false;
    }

    public function getLiveCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."live_categories","*","1 ORDER BY lc_order DESC, lc_id asc");
    }

    public function getLiveCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."live_categories","*","1 ORDER BY lc_order DESC, lc_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['lc_id']] = $item;
            }
            return $list;
        } else return false;
    }

    public function getAuthorsList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."admins","
                    a_id as id,
                    a_name as name,
                    a_f_name as f_name,
                    a_o_name as o_name ","1 ORDER BY a_name DESC, a_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['id']] = $item;
            }
            return $list;
        } else return false;
    }

}

