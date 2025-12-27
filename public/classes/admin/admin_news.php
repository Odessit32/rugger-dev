<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once(__DIR__.'/admin_base.php');

class news extends adminBase{
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // NEWS ///////////////////////////////////////////////////////////////////////////////////////

    public function validateSave($post=array()) {
        global $language;
        $res = array(
            'status' => true,
            'message' => ''
        );
        if (empty($post['n_title_ua']) && empty($post['n_title_ru']) && empty($post['n_title_en'])) {
            $res['status'] = false;
            $res['message'] = $language['title_required'];
        }
        if (empty($post['n_text_ua']) && empty($post['n_text_ru']) && empty($post['n_text_en'])) {
            $res['status'] = false;
            $res['message'] = $language['text_required'];
        }
        return $res;
    }

    public function createNews($post){ // ���������� �������
        $is_active = (!empty($post['n_is_active']))? 'yes':'no';
        $n_is_export = (!empty($post['n_is_export']))? 'yes':'no';
        $n_is_export_vk = (!empty($post['n_is_export_vk']))? 'yes':'no';
        $n_is_photo_top = (!empty($post['n_is_photo_top']))? 'yes':'no';
        $n_date_show = date("Y-m-d H:i:s");
        $post['n_top'] = intval($post['n_top']);
        if ($post['n_top']>4) $post['n_top'] = 0;
        if ($post['n_top']>0) $this->discardTopNewsNum($post['n_top']);
        if ($post['n_date_day'] != '' and $post['n_date_day']>0 and $post['n_date_month'] != '' and $post['n_date_month']>0 and $post['n_date_year'] != '' and $post['n_date_year']>0) {
            $post['n_date_day'] = intval($post['n_date_day']);
            $post['n_date_month'] = intval($post['n_date_month']);
            $post['n_date_year'] = intval($post['n_date_year']);
            $post['n_date_hour'] = intval($post['n_date_hour']);
            $post['n_date_minute'] = intval($post['n_date_minute']);
            if ($post['n_date_day'] < 10) $post['n_date_day'] = '0'.$post['n_date_day'];
            if ($post['n_date_month'] < 10) $post['n_date_month'] = '0'.$post['n_date_month'];
            if ($post['n_date_hour'] < 10) $post['n_date_hour'] = '0'.$post['n_date_hour'];
            if ($post['n_date_minute'] < 10) $post['n_date_minute'] = '0'.$post['n_date_minute'];
            $n_date_show = $post['n_date_year']."-".$post['n_date_month']."-".$post['n_date_day']." ".$post['n_date_hour'].":".$post['n_date_minute'].":00";
        }
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');

        // Обрабатываем связанные новости "Читайте также"
        $n_related_news = '';
        if (isset($post['related_news_id']) && is_array($post['related_news_id'])) {
            $related_ids = array();
            foreach ($post['related_news_id'] as $idx => $rel_id) {
                if (!empty($rel_id)) {
                    $related_ids[$idx] = intval($rel_id);
                }
            }
            if (!empty($related_ids)) {
                $n_related_news = json_encode($related_ids, JSON_UNESCAPED_UNICODE);
            }
        }

        $elem = array(
            (strlen(trim(html_entity_decode(strip_tags($post['n_title_ru']))))>0) ? str_replace($search, $replace, $post['n_title_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_title_ua']))))>0) ? str_replace($search, $replace, $post['n_title_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_title_en']))))>0) ? str_replace($search, $replace, $post['n_title_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_description_ru'], "<a>, <img>"))))>0) ? addslashes($post['n_description_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_description_ua'], "<a>, <img>"))))>0) ? addslashes($post['n_description_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_description_en'], "<a>, <img>"))))>0) ? addslashes($post['n_description_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_text_ru'], "<a>, <img>"))))>0) ? addslashes($post['n_text_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_text_ua'], "<a>, <img>"))))>0) ? addslashes($post['n_text_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['n_text_en'], "<a>, <img>"))))>0) ? addslashes($post['n_text_en']) : '',
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $n_date_show,
            intval($post['n_nc_id']),
            str_replace($search, $replace, !empty($post['n_tags'])?$post['n_tags']:''),
            $n_is_export,
            $n_is_export_vk,
            'no',  // n_is_export_fru
            '',    // n_export_id
            str_replace($search, $replace, $post['n_sign']),
            $post['n_top'],
            $n_related_news,  // n_related_news
            $n_is_photo_top,
            addslashes($post['n_sign_url']),
        );

        if ($n_id = $this->hdl->addElem(DB_T_PREFIX."news", $elem)) {
            $this->_updateConnectionCountry(array(
                'country'=>$post['country_auto_val'],
                'id'=>$n_id,
                'type'=>'news'
            ));
            $this->_updateConnectionChamp(array(
                'champ'=>$post['champ_auto_val'],
                'id'=>$n_id,
                'type'=>'news'
            ));
            if (!empty($post['game_auto_val'])){
                $this->_updateConnectionGame(array(
                    'game'=>$post['game_auto_val'],
                    'id'=>$n_id,
                    'type'=>'news'
                ));
            }
            if (!empty($post['staff_auto_val'])){
                $this->_updateConnectionStaff(array(
                    'staff'=>$post['staff_auto_val'],
                    'id'=>$n_id,
                    'type'=>'news'
                ));
            }
            return $n_id;
        }
        return false;
    }

    private function discardTopNewsNum($n_top){
        $n_top = intval($n_top);
        if ($n_top<1) return false;
        $elems = array(
            "n_top" => '0'
        );
        $condition = array(
            "n_top"=>$n_top
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."news",$elems, $condition)) return true;
        else return false;
    }

    public function updateNews($post){ // �������������� �������
        $is_active = (!empty($post['n_is_active']))?'yes':'no';
        $n_is_export = (!empty($post['n_is_export']))?'yes':'no';
        $n_is_export_vk = (!empty($post['n_is_export_vk']))?'yes':'no';
        $n_is_photo_top = (!empty($post['n_is_photo_top']))?'yes':'no';
        $post['n_top'] = intval($post['n_top']);
        if ($post['n_top']>4) $post['n_top'] = 0;
        if ($post['n_top']>0) $this->discardTopNewsNum($post['n_top']);
        if ($post['n_date_day'] != '' and $post['n_date_day']>0 and $post['n_date_month'] != '' and $post['n_date_month']>0 and $post['n_date_year'] != '' and $post['n_date_year']>0) {
            $post['n_date_day'] = intval($post['n_date_day']);
            $post['n_date_month'] = intval($post['n_date_month']);
            $post['n_date_year'] = intval($post['n_date_year']);
            $post['n_date_hour'] = intval($post['n_date_hour']);
            $post['n_date_minute'] = intval($post['n_date_minute']);
            if ($post['n_date_day'] < 10) $post['n_date_day'] = '0'.$post['n_date_day'];
            if ($post['n_date_month'] < 10) $post['n_date_month'] = '0'.$post['n_date_month'];
            if ($post['n_date_hour'] < 10) $post['n_date_hour'] = '0'.$post['n_date_hour'];
            if ($post['n_date_minute'] < 10) $post['n_date_minute'] = '0'.$post['n_date_minute'];
            $n_date_show = $post['n_date_year']."-".$post['n_date_month']."-".$post['n_date_day']." ".$post['n_date_hour'].":".$post['n_date_minute'].":00";
        }
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $post['n_tags'] = (!empty($post['n_tags']))?$post['n_tags']:'';
        $elems = array(
            "n_title_ru" => (strlen(trim(html_entity_decode(strip_tags($post['n_title_ru']))))>0) ? str_replace($search, $replace, $post['n_title_ru']) : '',
            "n_title_ua" => (strlen(trim(html_entity_decode(strip_tags($post['n_title_ua']))))>0) ? str_replace($search, $replace, $post['n_title_ua']) : '',
            "n_title_en" => (strlen(trim(html_entity_decode(strip_tags($post['n_title_en']))))>0) ? str_replace($search, $replace, $post['n_title_en']) : '',
            "n_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['n_description_ru'], "<a>, <img>"))))>0) ? addslashes($post['n_description_ru']) : '',
            "n_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['n_description_ua'], "<a>, <img>"))))>0) ? addslashes($post['n_description_ua']) : '',
            "n_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['n_description_en'], "<a>, <img>"))))>0) ? addslashes($post['n_description_en']) : '',
            "n_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['n_text_ru'], "<a>, <img>"))))>0) ? addslashes($post['n_text_ru']) : '',
            "n_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['n_text_ua'], "<a>, <img>"))))>0) ? addslashes($post['n_text_ua']) : '',
            "n_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['n_text_en'], "<a>, <img>"))))>0) ? addslashes($post['n_text_en']) : '',
            "n_is_active" => $is_active,
            "n_datetime_edit" => 'NOW()',
            "n_author" => USER_ID,
            "n_date_show" => $n_date_show,
            "n_nc_id" => intval($post['n_nc_id']),
            "n_tags" => str_replace($search, $replace, $post['n_tags']),
            "n_sign" => str_replace($search, $replace, $post['n_sign']),
            "n_sign_url" => addslashes($post['n_sign_url']),
            "n_is_export" => $n_is_export,
            "n_is_export_vk" => $n_is_export_vk,
            "n_is_photo_top" => $n_is_photo_top,
            "n_top" => $post['n_top']
        );

        // Сохраняем связанные новости "Читайте также"
        if (isset($post['related_news_id']) && is_array($post['related_news_id'])) {
            $related_ids = array();
            foreach ($post['related_news_id'] as $idx => $rel_id) {
                if (!empty($rel_id)) {
                    $related_ids[$idx] = intval($rel_id);
                }
            }
            $elems['n_related_news'] = json_encode($related_ids, JSON_UNESCAPED_UNICODE);
        }

        $condition = array(
            "n_id"=>$post['n_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."news",$elems, $condition)) {
            if (!empty($post['country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['country_auto_val'],
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            }
            if (!empty($post['champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['champ_auto_val'],
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            }
            if (!empty($post['game_auto_val'])){
                $this->_updateConnectionGame(array(
                    'game'=>$post['game_auto_val'],
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionGame(array(
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            }
            if (!empty($post['staff_auto_val'])){
                $this->_updateConnectionStaff(array(
                    'staff'=>$post['staff_auto_val'],
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionStaff(array(
                    'id'=>$post['n_id'],
                    'type'=>'news'
                ));
            }
            return true;
        } else return false;
    }

    public function deleteNews($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."news", "n_id='$id'")) {
                // delete photos
                $news_media = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_type = 'news' AND ph_type_id = '$id'");
                if ($news_media)
                    foreach ($news_media as $item){
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
                unset($news_media);
                // delete photo gallery
                $news_media = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id","phg_type = 'news' AND phg_type_id = '$id'");
                if ($news_media)
                    foreach ($news_media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_id = '".$item['phg_id']."' LIMIT 1");
                    }
                unset($news_media);
                // delete videos
                $news_media = $this->hdl->selectElem(DB_T_PREFIX."videos","v_id, v_folder","v_type = 'news' AND v_type_id = '$id'");
                if ($news_media)
                    foreach ($news_media as $item){
                        if ($item['v_folder'] == '') $item['v_folder'] = '/';
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg");
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg");
                        $this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '".$item['v_id']."' LIMIT 1");
                    }
                unset($news_media);
                // delete video gallery
                $news_media = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","vg_id","vg_type = 'news' AND vg_type_id = '$id'");
                if ($news_media)
                    foreach ($news_media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_id = '".$item['vg_id']."' LIMIT 1");
                    }
                unset($news_media);
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$id,
                    'type'=>'news'
                ));
                $this->_deleteConnectionChamp(array(
                    'id'=>$id,
                    'type'=>'news'
                ));
                return true;
            }
        }
        return false;
    }

    public function getNewsItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $auto_val = array();
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."news","*","n_id=$item");
            if (!$temp) return false;
            else $temp = $temp[0];
            $temp['n_title_ru'] = str_replace($search, $replace, stripcslashes($temp['n_title_ru']));
            $temp['n_title_ua'] = str_replace($search, $replace, stripcslashes($temp['n_title_ua']));
            $temp['n_title_en'] = str_replace($search, $replace, stripcslashes($temp['n_title_en']));
            $temp['n_description_ru'] = stripcslashes($temp['n_description_ru']);
            $temp['n_description_ua'] = stripcslashes($temp['n_description_ua']);
            $temp['n_description_en'] = stripcslashes($temp['n_description_en']);
            $temp['n_text_ru'] = stripcslashes($temp['n_text_ru']);
            $temp['n_text_ua'] = stripcslashes($temp['n_text_ua']);
            $temp['n_text_en'] = stripcslashes($temp['n_text_en']);
            $temp['n_sign_url'] = stripcslashes($temp['n_sign_url']);
            $temp['connection_country'] = $this->getConnectionCountry($item, false, 'news');
            $temp['connection_country_val'] = '';
            if (!empty($temp['connection_country'])){
                foreach($temp['connection_country'] as $item_c){
                    $temp['connection_country_val'] .=$item_c['id'].',';
                }
                $temp['connection_country_val'] = substr($temp['connection_country_val'], 0, -1);
            }
            $temp['connection_champ'] = $this->getConnectionChamp($item, false, 'news');
            $temp['connection_champ_val'] = '';
            if (!empty($temp['connection_champ'])){
                foreach($temp['connection_champ'] as $item_c){
                    $temp['connection_champ_val'] .=$item_c['id'].',';
                }
                $temp['connection_champ_val'] = substr($temp['connection_champ_val'], 0, -1);
            }
            $connection_news_top_f = false;
            $temp['connection_news_top_f'] = 0;
            for ($i=1; $i<=4; $i++){
                $temp['connection_news_top'][$i] = $this->getConnectionNewsTop($item, $i);
                if ((!empty($temp['connection_news_top'][$i]['countries']['auto_val'])
                    || !empty($temp['connection_news_top'][$i]['champ']['auto_val']) )
                    && !$connection_news_top_f) {
                    $temp['connection_news_top_f'] = $i;
                    $connection_news_top_f = true;
                }
            }
            $temp['connection_games'] = $this->getGameListDay();
            $temp['connected_games'] = $this->getConnectionGame($item, false, 'news');
            if (!empty($temp['connected_games'])){
                foreach ($temp['connected_games'] as $gl_item){
                    $auto_val[] = $gl_item['id'];
                }
            }
            $temp['games_auto_val'] = implode(',', $auto_val);
            $temp['connected_staff'] = $this->getConnectionStaff($item, false, 'news');
            $staff_auto_val = array();
            if (!empty($temp['connected_staff'])){
                foreach ($temp['connected_staff'] as $st_item){
                    $staff_auto_val[] = $st_item['id'];
                }
            }
            $temp['staff_auto_val'] = implode(',', $staff_auto_val);

            // Загружаем связанные новости "Читайте также"
            $temp['related_news'] = array();
            if (!empty($temp['n_related_news'])) {
                $related_ids = json_decode($temp['n_related_news'], true);
                if (!empty($related_ids) && is_array($related_ids)) {
                    foreach ($related_ids as $idx => $rel_id) {
                        if (!empty($rel_id)) {
                            $rel_news = $this->hdl->selectElem(DB_T_PREFIX."news", "n_id, n_title_ru, n_title_ua, n_title_en, n_sign_url", "n_id = ".intval($rel_id)." LIMIT 1");
                            if (!empty($rel_news[0])) {
                                $title = !empty($rel_news[0]['n_title_ru']) ? $rel_news[0]['n_title_ru'] : (!empty($rel_news[0]['n_title_ua']) ? $rel_news[0]['n_title_ua'] : $rel_news[0]['n_title_en']);
                                $temp['related_news'][$idx] = array(
                                    'id' => $rel_news[0]['n_id'],
                                    'title' => strip_tags($title),
                                    'url' => $rel_news[0]['n_sign_url']
                                );
                            }
                        }
                    }
                }
            }

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

    public function getNewsList($page=1, $perpage=10, $gallery = 0){
        $extra_q = '1';
        $gallery = intval($gallery);
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page = $perpage*$page;
        if ($gallery > 0) $extra_q = "n_nc_id = '$gallery' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."news","
				n_id as id,
				n_title_".D_S_LANG." as title,
				n_is_active,
				n_date_show,
				n_nc_id,
				n_is_export,
				n_is_export_vk,
				n_export_id,
				n_top,
				n_is_photo_top
				","$extra_q ORDER BY n_date_show DESC, n_id DESC LIMIT $page, $perpage");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    public function getNewsPages($page=1, $perpage=10, $gallery = 0){
        $extra_q = '';
        $gallery = intval($gallery);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        if ($gallery > 0) $extra_q = " AND n_nc_id = '$gallery' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."news","COUNT(*) as C_N","1 $extra_q");
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

    // ������� ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($news_item, &$photos_class){ // Сохранение фотографии к новости
        $news_item['n_id'] = intval($news_item['n_id']);
        if ($news_item['n_id'] < 1) return false;
        $type = 'news';

        // Значения по умолчанию для полей формы
        if (!isset($_POST['phg_phc_id'])) $_POST['phg_phc_id'] = 0;
        if (!isset($_POST['ph_country_auto_val'])) $_POST['ph_country_auto_val'] = '';
        if (!isset($_POST['ph_champ_auto_val'])) $_POST['ph_champ_auto_val'] = '';

        if ($_POST['ph_gallery_id'] == 'new') {
            if ($news_item['n_title_ru'] == '') $phg_post['phg_title_ru'] = $news_item['n_id'];
            else $phg_post['phg_title_ru'] = $news_item['n_title_ru'];
            if ($news_item['n_title_ua'] == '') $phg_post['phg_title_ua'] = $news_item['n_id'];
            else $phg_post['phg_title_ua'] = $news_item['n_title_ua'];
            if ($news_item['n_title_en'] == '') $phg_post['phg_title_en'] = $news_item['n_id'];
            else $phg_post['phg_title_en'] = $news_item['n_title_en'];

            $phg_post['phg_description_ru'] = $news_item['n_description_ru'] ?? '';
            $phg_post['phg_description_ua'] = $news_item['n_description_ua'] ?? '';
            $phg_post['phg_description_en'] = $news_item['n_description_en'] ?? '';
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_is_informer'] = true;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $news_item['n_id'];
            $phg_post['phg_phc_id'] = intval($_POST['phg_phc_id']);
            $phg_post['phg_datetime_pub'] = $news_item['n_date_show'];
            $phg_post['ph_country_auto_val'] = $_POST['ph_country_auto_val'];
            $phg_post['ph_champ_auto_val'] = $_POST['ph_champ_auto_val'];

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id']<1) return false;
        } else {
            $photos_class->updatePhotoGalleryCategory($_POST['ph_gallery_id'], intval($_POST['phg_phc_id']));
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if (!empty($_POST['ph_type_main'])) $photos_class->resetTypeMainPhotos($news_item['n_id'], $type);
        $_POST['ph_type_id'] = $news_item['n_id'];
        $_POST['ph_type'] = $type;

        $file_photo = $_FILES['file_photo'] ?? [];
        if ($photos_class->savePhoto($file_photo, $_POST)) return true;
        return false;
    }

    public function saveVideo($news_item, &$videos_class){ // Сохранение видео к новости
        $news_item['n_id'] = intval($news_item['n_id']);
        if ($news_item['n_id'] < 1) return false;
        $type = 'news';

        // Значения по умолчанию для полей формы
        if (!isset($_POST['vg_vc_id'])) $_POST['vg_vc_id'] = 0;
        if (!isset($_POST['v_country_auto_val'])) $_POST['v_country_auto_val'] = '';
        if (!isset($_POST['v_champ_auto_val'])) $_POST['v_champ_auto_val'] = '';

        // Гарантируем что заголовки никогда не будут NULL
        $default_title = $news_item['n_title_ru'] ?? 'Видео ' . $news_item['n_id'];
        if (empty($default_title)) $default_title = 'Видео ' . $news_item['n_id'];

        if (!isset($_POST['v_title_ru']) || $_POST['v_title_ru'] === null) {
            $_POST['v_title_ru'] = $default_title;
        }
        if (!isset($_POST['v_title_ua']) || $_POST['v_title_ua'] === null) {
            $_POST['v_title_ua'] = ($news_item['n_title_ua'] ?? '') ?: $default_title;
        }
        if (!isset($_POST['v_title_en']) || $_POST['v_title_en'] === null) {
            $_POST['v_title_en'] = ($news_item['n_title_en'] ?? '') ?: $default_title;
        }
        if (!isset($_POST['v_about_ru'])) $_POST['v_about_ru'] = '';
        if (!isset($_POST['v_about_ua'])) $_POST['v_about_ua'] = '';
        if (!isset($_POST['v_about_en'])) $_POST['v_about_en'] = '';

        if ($_POST['v_gallery_id'] == 'new') {
            // Используем default_title который уже гарантированно не NULL
            $vg_post['vg_title_ru'] = !empty($news_item['n_title_ru']) ? $news_item['n_title_ru'] : $default_title;
            $vg_post['vg_title_ua'] = !empty($news_item['n_title_ua']) ? $news_item['n_title_ua'] : $default_title;
            $vg_post['vg_title_en'] = !empty($news_item['n_title_en']) ? $news_item['n_title_en'] : $default_title;

            $vg_post['vg_description_ru'] = $news_item['n_description_ru'] ?? '';
            $vg_post['vg_description_ua'] = $news_item['n_description_ua'] ?? '';
            $vg_post['vg_description_en'] = $news_item['n_description_en'] ?? '';
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_is_informer'] = true;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $news_item['n_id'];
            $vg_post['vg_vc_id'] = intval($_POST['vg_vc_id']);
            $vg_post['vg_datetime_pub'] = $news_item['n_date_show'];
            $vg_post['v_country_auto_val'] = $_POST['v_country_auto_val'];
            $vg_post['v_champ_auto_val'] = $_POST['v_champ_auto_val'];

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        } else {
            $videos_class->updateVideoGalleryCategory($_POST['v_gallery_id'], intval($_POST['vg_vc_id']));
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $news_item['n_id'];
        $_POST['v_type'] = $type;

        if ($videos_class->saveVideo($_POST)) {
            // Отключаем главное фото когда добавлена видеогалерея
            $this->hdl->updateElem(DB_T_PREFIX."news",
                array("n_is_photo_top" => "no"),
                array("n_id" => $news_item['n_id'])
            );
            return true;
        }
        return false;
    }

    // CATEGORIES /////////////////////////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['nc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $show_in_widget = !empty($post['nc_show_in_widget']) ? 'yes' : 'no';
        $post['nc_address'] = strtolower($post['nc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '&quot;', '&quot;');
        $elem = array(
            addslashes($post['nc_title_ru']),
            addslashes($post['nc_title_ua']),
            addslashes($post['nc_title_en']),
            str_replace($search, $replace, $post['nc_address']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            intval($post['nc_order']),
            $show_in_widget
        );
        if ($this->hdl->addElem(DB_T_PREFIX."news_categories", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['nc_id'] <1) return false;
        if($post['nc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $show_in_widget = !empty($post['nc_show_in_widget']) ? 'yes' : 'no';
        $post['nc_address'] = strtolower($post['nc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '&quot;', '&quot;');
        $elems = array(
            "nc_title_ru" => addslashes($post['nc_title_ru']),
            "nc_title_ua" => addslashes($post['nc_title_ua']),
            "nc_title_en" => addslashes($post['nc_title_en']),
            "nc_address" => str_replace($search, $replace, $post['nc_address']),
            "nc_is_active" => $is_active,
            "nc_datetime_edit" => 'NOW()',
            "nc_author" => USER_ID,
            "nc_order" => intval($post['nc_order']),
            "nc_show_in_widget" => $show_in_widget
        );
        $condition = array(
            "nc_id"=>$post['nc_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."news_categories",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."news_categories", "nc_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."news_categories","*","nc_id=$item");
            $temp[0]['n_title_ru'] = stripcslashes($temp[0]['nc_title_ru']);
            $temp[0]['n_title_ua'] = stripcslashes($temp[0]['nc_title_ua']);
            $temp[0]['n_title_en'] = stripcslashes($temp[0]['nc_title_en']);
            return $temp[0];
        } else return false;
    }

    public function getNewsCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."news_categories","*","1 ORDER BY nc_order DESC, nc_id asc");
    }

    public function getNewsCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."news_categories","*","1 ORDER BY nc_order DESC, nc_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['nc_id']] = $item;
            }
            return $list;
        } else return false;
    }

}

