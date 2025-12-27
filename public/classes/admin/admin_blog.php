<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('../classes/admin/admin_base.php');

class Blog extends adminBase{
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // BLOG ///////////////////////////////////////////////////////////////////////////////////////

    public function createPost($post){ // create new blog post
        $post['is_active'] = (!empty($post['is_active']))?'yes':'no';
        $post['is_photo_top'] = (!empty($post['is_photo_top']))?'yes':'no';
        $post['is_top'] = intval($post['is_top']);
        if ($post['is_top']>4) $post['is_top'] = 0;
        if ($post['is_top']>0) $this->discardTopPostNum($post['is_top']);
        if ($post['date_day'] != '' and $post['date_day']>0 and $post['date_month'] != '' and $post['date_month']>0 and $post['date_year'] != '' and $post['date_year']>0) {
            $post['date_day'] = intval($post['date_day']);
            $post['date_month'] = intval($post['date_month']);
            $post['date_year'] = intval($post['date_year']);
            $post['date_hour'] = intval($post['date_hour']);
            $post['date_minute'] = intval($post['date_minute']);
            if ($post['date_day'] < 10) $post['date_day'] = '0'.$post['date_day'];
            if ($post['date_month'] < 10) $post['date_month'] = '0'.$post['date_month'];
            if ($post['date_hour'] < 10) $post['date_hour'] = '0'.$post['date_hour'];
            if ($post['date_minute'] < 10) $post['date_minute'] = '0'.$post['date_minute'];
            $post['date_show'] = $post['date_year']."-".$post['date_month']."-".$post['date_day']." ".$post['date_hour'].":".$post['date_minute'].":00";
        }
        unset($post['date_year']);
        unset($post['date_month']);
        unset($post['date_day']);
        unset($post['date_hour']);
        unset($post['date_minute']);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $post['title'] = (strlen(trim(html_entity_decode(strip_tags($post['title']))))>0) ? str_replace($search, $replace, $post['title']) : '';
        $post['description'] = (strlen(trim(html_entity_decode(strip_tags($post['description']))))>0) ? str_replace($search, $replace, $post['description']) : '';
        $post['text'] = (strlen(trim(html_entity_decode(strip_tags($post['text']))))>0) ? str_replace($search, $replace, $post['text']) : '';
        $post['sign'] = (strlen(trim(html_entity_decode(strip_tags($post['sign']))))>0) ? str_replace($search, $replace, $post['sign']) : '';
        $post['sign_url'] = addslashes($post['sign_url']);
        $post['datetime_add'] = 'NOW()';
        $post['datetime_edit'] = 'NOW()';
        $post['author'] = USER_ID;
        $post['author_type'] = 'admin';
        $post['address'] = $this->_getPostAddress($post['address'], $post['title']);
        $country_auto_val = (!empty($post['country_auto_val']))?$post['country_auto_val']:'';
        unset($post['country_auto_val']);
        $champ_auto_val = (!empty($post['champ_auto_val']))?$post['champ_auto_val']:'';
        unset($post['champ_auto_val']);
        $game_auto_val = (!empty($post['game_auto_val']))?$post['game_auto_val']:'';
        unset($post['game_auto_val']);
        $staff_auto_val = (!empty($post['staff_auto_val']))?$post['staff_auto_val']:'';
        unset($post['staff_auto_val']);
        unset($post['game_date']);
        unset($post['game_id']);
        unset($post['add_new_post']);
        unset($post['champ_']);
        unset($post['country_']);
        if ($id = $this->hdl->addElem(DB_T_PREFIX."blog_posts", $post)) {
            $this->_updateConnectionCountry(array(
                'country'=>$country_auto_val,
                'id'=>$id,
                'type'=>'blog'
            ));
            $this->_updateConnectionChamp(array(
                'champ'=>$champ_auto_val,
                'id'=>$id,
                'type'=>'blog'
            ));
            if (!empty($game_auto_val)){
                $this->_updateConnectionGame(array(
                    'game'=>$game_auto_val,
                    'id'=>$id,
                    'type'=>'blog'
                ));
            }
            return true;
        } else return false;
    }

    private function _getPostAddress($address = '', $title = ''){
        if (empty($address) && empty($title)) {
            $address = time();
        } elseif (empty($address)) {
            $address = $title;
        }
        $address = $this->getTranslit(strtolower($address));
        $check_address = $address;
        $k=0;
        while($this->hdl->selectElem(DB_T_PREFIX."blog_posts","id","address = '".$check_address."' LIMIT 1")){
            $k++;
            $check_address = $address.'-'.$k;
        }
        if ($check_address != $address) {
            $address = $check_address;
        }

        return $address;
    }

    private function discardTopPostNum($is_top){
        $is_top = intval($is_top);
        if ($is_top<1) return false;
        $elems = array(
            "is_top " => '0'
        );
        $condition = array(
            "is_top"=>$is_top
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."blog_posts",$elems, $condition)) return true;
        else return false;
    }

    /**
     * saving changes for one post
     *
     * @param $post
     * @return bool
     */
    public function updatePost($post){
        $is_active = (!empty($post['is_active']))?'yes':'no';
        $is_photo_top = (!empty($post['is_photo_top']))?'yes':'no';
        $post['is_top'] = intval($post['is_top']);
        if ($post['is_top']>4) $post['is_top'] = 0;
        if ($post['is_top']>0) $this->discardTopPostNum($post['is_top']);
        if ($post['date_day'] != '' and $post['date_day']>0 and $post['date_month'] != '' and $post['date_month']>0 and $post['date_year'] != '' and $post['date_year']>0) {
            $post['date_day'] = intval($post['date_day']);
            $post['date_month'] = intval($post['date_month']);
            $post['date_year'] = intval($post['date_year']);
            $post['date_hour'] = intval($post['date_hour']);
            $post['date_minute'] = intval($post['date_minute']);
            if ($post['date_day'] < 10) $post['date_day'] = '0'.$post['date_day'];
            if ($post['date_month'] < 10) $post['date_month'] = '0'.$post['date_month'];
            if ($post['date_hour'] < 10) $post['date_hour'] = '0'.$post['date_hour'];
            if ($post['date_minute'] < 10) $post['date_minute'] = '0'.$post['date_minute'];
            $date_show = $post['date_year']."-".$post['date_month']."-".$post['date_day']." ".$post['date_hour'].":".$post['date_minute'].":00";
        } else {
            $date_show = date("Y-m-d H:i:s");
        }
        $post['lang'] = (in_array($post['lang'], array('ua', 'ru', 'en')))?$post['lang']:'ru';
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $elems = array(
            "title" => (strlen(trim(html_entity_decode(strip_tags($post['title']))))>0) ? str_replace($search, $replace, $post['title']) : '',
            "description" => (strlen(trim(html_entity_decode(strip_tags($post['description'], "<a>, <img>"))))>0) ? addslashes($post['description']) : '',
            "text" => (strlen(trim(html_entity_decode(strip_tags($post['text'], "<a>, <img>"))))>0) ? addslashes($post['text']) : '',
            "is_active" => $is_active,
            "datetime_edit" => 'NOW()',
            "author" => USER_ID,
            "author_type" => 'admin',
            "date_show" => $date_show,
            "sign" => str_replace($search, $replace, $post['sign']),
            "sign_url" => addslashes($post['sign_url']),
            "is_photo_top" => $is_photo_top,
            "is_top" => $post['is_top'],
            "lang" => $post['lang']
        );
        $condition = array(
            "id"=>$post['id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."blog_posts",$elems, $condition)) {
            if (!empty($post['country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['country_auto_val'],
                    'id'=>$post['id'],
                    'type'=>'blog'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['id'],
                    'type'=>'blog'
                ));
            }
            if (!empty($post['champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['champ_auto_val'],
                    'id'=>$post['id'],
                    'type'=>'blog'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['id'],
                    'type'=>'blog'
                ));
            }
            if (!empty($post['game_auto_val'])){
                $this->_updateConnectionGame(array(
                    'game'=>$post['game_auto_val'],
                    'id'=>$post['id'],
                    'type'=>'blog'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionGame(array(
                    'id'=>$post['id'],
                    'type'=>'blog'
                ));
            }
            return true;
        } else return false;
    }

    public function deletePost($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."blog_posts", "id='$id'")) {
                // удаление фото
                $list_media = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_type = 'blog' AND ph_type_id = '$id'");
                if ($list_media)
                    foreach ($list_media as $item){
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
                unset($list_media);
                // удаление фото галерей
                $list_media = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id","phg_type = 'blog' AND phg_type_id = '$id'");
                if ($list_media)
                    foreach ($list_media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_id = '".$item['phg_id']."' LIMIT 1");
                    }
                unset($list_media);
                // удаление видео
                $list_media = $this->hdl->selectElem(DB_T_PREFIX."videos","v_id, v_folder","v_type = 'blog' AND v_type_id = '$id'");
                if ($list_media)
                    foreach ($list_media as $item){
                        if ($item['v_folder'] == '') $item['v_folder'] = '/';
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg");
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg");
                        $this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '".$item['v_id']."' LIMIT 1");
                    }
                unset($list_media);
                // удаление видео галерей
                $list_media = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","vg_id","vg_type = 'blog' AND vg_type_id = '$id'");
                if ($list_media)
                    foreach ($list_media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_id = '".$item['vg_id']."' LIMIT 1");
                    }
                unset($list_media);
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$id,
                    'type'=>'blog'
                ));
                $this->_deleteConnectionChamp(array(
                    'id'=>$id,
                    'type'=>'blog'
                ));
                return true;
            }
        }
        return false;
    }

    public function getPostItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $auto_val = array();
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts","*","id=$item");
            if (!$temp) return false;
            else $temp = $temp[0];
            $temp['title'] = str_replace($search, $replace, stripcslashes($temp['title']));
            $temp['description'] = stripcslashes($temp['description']);
            $temp['text'] = stripcslashes($temp['text']);
            $temp['sign_url'] = stripcslashes($temp['sign_url']);
            $temp['connection_country'] = $this->getConnectionCountry($item, false, 'blog');
            $temp['connection_country_val'] = '';
            if (!empty($temp['connection_country'])){
                foreach($temp['connection_country'] as $item_c){
                    $temp['connection_country_val'] .=$item_c['id'].',';
                }
                $temp['connection_country_val'] = substr($temp['connection_country_val'], 0, -1);
            }
            $temp['connection_champ'] = $this->getConnectionChamp($item, false, 'blog');
            $temp['connection_champ_val'] = '';
            if (!empty($temp['connection_champ'])){
                foreach($temp['connection_champ'] as $item_c){
                    $temp['connection_champ_val'] .=$item_c['id'].',';
                }
                $temp['connection_champ_val'] = substr($temp['connection_champ_val'], 0, -1);
            }
//            $connection_blog_top_f = false;
//            $temp['connection_blog_top_f'] = 0;
//            for ($i=1; $i<=4; $i++){
//                $temp['connection_blog_top'][$i] = $this->getConnectionPostTop($item, $i);
//                if ((!empty($temp['connection_blog_top'][$i]['countries']['auto_val'])
//                    || !empty($temp['connection_blog_top'][$i]['champ']['auto_val']) )
//                    && !$connection_blog_top_f) {
//                    $temp['connection_blog_top_f'] = $i;
//                    $connection_blog_top_f = true;
//                }
//            }
            $temp['connection_games'] = $this->getGameListDay();
            $temp['connected_games'] = $this->getConnectionGame($item, false, 'blog');
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

    public function getPostList($page=1, $perpage=10, $gallery = 0){
        $gallery = intval($gallery);
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page = $perpage*$page;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts","
				id,
				title,
				lang,
				is_active,
				date_show,
				is_top,
				is_photo_top,
				author,
				author_type
				","1 ORDER BY date_show DESC, id DESC LIMIT $page, $perpage");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    public function getPostPages($page=1, $perpage=10, $gallery = 0){
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts","COUNT(*) as C_N","1");
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

    public function getListSettings(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."settings","*"," 1 ORDER BY set_id");
        if ($temp){
            if (count($temp)>0){
                foreach($temp as $val){
                    $list[$val['set_name']] = $val['set_value'];
                }
            }
        }
        return $list;
    }

    public function saveSettings($elems, $condition){
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$elems, $condition)) return true;
        else return false;
    }

    // ГАЛЕРЕЯ ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($blog_item, &$photos_class){ // добавление фотографии в галерею
        $blog_item['id'] = intval($blog_item['id']);
        if (empty($blog_item['id'])) return false;
        $type = 'blog';
        $_POST['ph_type_main'] = (!empty($_POST['ph_type_main']))?$_POST['ph_type_main']:false;
        if ($_POST['ph_gallery_id'] == 'new') {
            if (empty($blog_item['title'])) $phg_post['phg_title_ru'] = $blog_item['id'];
            else $phg_post['phg_title_ru'] = $blog_item['title'];
            if (empty($blog_item['title'])) $phg_post['phg_title_ua'] = $blog_item['id'];
            else $phg_post['phg_title_ua'] = $blog_item['title'];
            if (empty($blog_item['title'])) $phg_post['phg_title_en'] = $blog_item['id'];
            else $phg_post['phg_title_en'] = $blog_item['title'];

            $phg_post['phg_description_ru'] = $blog_item['description'];
            $phg_post['phg_description_ua'] = $blog_item['description'];
            $phg_post['phg_description_en'] = $blog_item['description'];
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_is_informer'] = true;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $blog_item['id'];
            $phg_post['phg_phc_id'] = intval($_POST['phg_phc_id']);
            $phg_post['phg_datetime_pub'] = $blog_item['date_show'];
            $phg_post['ph_country_auto_val'] = $_POST['ph_country_auto_val'];
            $phg_post['ph_champ_auto_val'] = $_POST['ph_champ_auto_val'];

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if (empty($_POST['ph_gallery_id'])) return false;
        } else {
            $photos_class->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if (!empty($_POST['ph_type_main'])) $photos_class->resetTypeMainPhotos($blog_item['id'], $type);
        $_POST['ph_type_id'] = $blog_item['id'];
        $_POST['ph_type'] = $type;
        if ($photos_class->savePhoto((!empty($_FILES['file_photo']))?$_FILES['file_photo']:false, $_POST)) return true;
        return false;
    }

    public function saveVideo($blog_item, &$videos_class){ // добавление видео в галерею
        $blog_item['id'] = intval($blog_item['id']);
        if ($blog_item['id'] < 1) return false;
        $type = 'blog';

        if ($_POST['v_gallery_id'] == 'new') {
            if ($blog_item['title'] == '') $vg_post['vg_title_ru'] = $blog_item['id'];
            else $vg_post['vg_title_ru'] = $blog_item['title'];
            if ($blog_item['title'] == '') $vg_post['vg_title_ua'] = $blog_item['id'];
            else $vg_post['vg_title_ua'] = $blog_item['title'];
            if ($blog_item['title'] == '') $vg_post['vg_title_en'] = $blog_item['id'];
            else $vg_post['vg_title_en'] = $blog_item['title'];

            $vg_post['vg_description_ru'] = $blog_item['description'];
            $vg_post['vg_description_ua'] = $blog_item['description'];
            $vg_post['vg_description_en'] = $blog_item['description'];
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_is_informer'] = true;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $blog_item['id'];
            $vg_post['vg_vc_id'] = intval($_POST['vg_vc_id']);
            $vg_post['vg_datetime_pub'] = $blog_item['date_show'];
            $vg_post['v_country_auto_val'] = $_POST['v_country_auto_val'];
            $vg_post['v_champ_auto_val'] = $_POST['v_champ_auto_val'];

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        } else {
            $videos_class->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $blog_item['id'];
        $_POST['v_type'] = $type;

        if ($videos_class->saveVideo($_POST)) return true;
        return false;
    }

    // CATEGORIES /////////////////////////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['nc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
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
            intval($post['nc_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."blog_categories", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['nc_id'] <1) return false;
        if($post['nc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
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
            "nc_order" => intval($post['nc_order'])
        );
        $condition = array(
            "nc_id"=>$post['nc_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."blog_categories",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."blog_categories", "nc_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_categories","*","nc_id=$item");
            $temp[0]['title'] = stripcslashes($temp[0]['title']);
            return $temp[0];
        } else return false;
    }

    public function getPostCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."blog_categories","*","1 ORDER BY nc_order DESC, nc_id asc");
    }

    public function getPostCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_categories","*","1 ORDER BY nc_order DESC, nc_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['nc_id']] = $item;
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

