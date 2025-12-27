<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_base.php');

class videos extends adminBase{
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // ������� ///////// ������ ///////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['vc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['vc_address'] = strtolower($post['vc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '', '');
        $elem = array(
            addslashes($post['vc_title_ru']),
            addslashes($post['vc_title_ua']),
            addslashes($post['vc_title_en']),
            str_replace($search, $replace, $post['vc_address']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            intval($post['vc_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."video_categories", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['vc_id'] <1) return false;
        if($post['vc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['vc_address'] = strtolower($post['vc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '', '');
        $elems = array(
            "vc_title_ru" => addslashes($post['vc_title_ru']),
            "vc_title_ua" => addslashes($post['vc_title_ua']),
            "vc_title_en" => addslashes($post['vc_title_en']),
            "vc_address" => str_replace($search, $replace, $post['vc_address']),
            "vc_is_active" => $is_active,
            "vc_datetime_edit" => 'NOW()',
            "vc_author" => USER_ID,
            "vc_order" => intval($post['vc_order'])
        );
        $condition = array(
            "vc_id"=>$post['vc_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."video_categories",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."video_categories", "vc_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."video_categories","*","vc_id=$item");
            $temp[0]['vc_title_ru'] = stripcslashes($temp[0]['vc_title_ru']);
            $temp[0]['vc_title_ua'] = stripcslashes($temp[0]['vc_title_ua']);
            $temp[0]['vc_title_en'] = stripcslashes($temp[0]['vc_title_en']);
            return $temp[0];
        } else return false;
    }

    public function getVideosCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."video_categories","*","1 ORDER BY vc_order DESC, vc_id asc");
    }

    public function getVideosCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_categories","*","1 ORDER BY vc_order DESC, vc_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['vc_id']] = $item;
            }
            return $list;
        } else return false;
    }
    // ������� ///////// �����  ///////////////////////////////////////////////////////////////////

    // ����� ��� ������ �������� ///////// ������ ////////////////////////////////////////////////////////////
    public function getTypeVideoList($type_id=0, $type = ''){
        $type = addslashes($type);
        $type_id = intval($type_id);
        $list = $this->hdl->selectElem(DB_T_PREFIX."videos","*"," v_type_id = '$type_id' AND v_type = '$type' ORDER BY v_id DESC");
        if ($list) return $list;
        else return false;
    }

    public function getTypeVideoGallery($type_id=0, $type = ''){
        $type = addslashes($type);
        $type_id = intval($type_id);
        if ($type_id >0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","*"," vg_type_id = '$type_id' AND vg_type = '$type' LIMIT 1");
            if ($temp){
                return $temp[0];
            } else return false;
        }
    }
    // ����� ��� ������ �������� ///////// ����� /////////////////////////////////////////////////////////////

    public function getVideoCategoryList(){
        $list = $this->hdl->selectElem(DB_T_PREFIX."video_categories","*"," 1 ORDER BY vc_order DESC");
        if ($list) return $list;
        else return false;
    }

    public function getVideoGalleryListPage($sort_list = 'all', $page = 0, $perpage = 10){
        if ($sort_list == 'on') $q_active = "AND vg_is_active = 'yes'";
        elseif ($sort_list == 'off') $q_active = "AND vg_is_active = 'no'";
        elseif ($sort_list == 'staff') $q_active = "AND vg_type = 'staff'";
        elseif ($sort_list == 'news') $q_active = "AND vg_type = 'news'";
        elseif ($sort_list == 'team') $q_active = "AND vg_type = 'team'";
        elseif ($sort_list == 'club') $q_active = "AND vg_type = 'club'";
        elseif ($sort_list == 'game') $q_active = "AND vg_type = 'game'";
        elseif ($sort_list == 'championship') $q_active = "AND vg_type = 'championship'";
        elseif ($sort_list == 'partners') $q_active = "AND vg_type = 'partners'";
        elseif ($sort_list == 'none') $q_active = "AND vg_type = 'none'";
        elseif ($sort_list == 'count1') $q_active = "AND vg_v_count < 2";
        elseif ($sort_list == 'countm1') $q_active = "AND vg_v_count > 1";
        else $q_active = " ";
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 10;
        $page = intval($page);
        $page = $perpage*$page;

        $list = $this->hdl->selectElem(DB_T_PREFIX."video_gallery",
            "   vg_id,
                vg_title_ru,
                vg_is_active,
                vg_type,
                vg_v_count as count_video
            "," 1
                    $q_active
                    ORDER BY vg_id DESC
                    LIMIT $page, $perpage");
        return $list;
    }

    public function getVideoGalleryListPageList($sort_list = 'all', $page = 0, $perpage = 10){
        if ($sort_list == 'on') $q_active = "AND vg_is_active = 'yes'";
        elseif ($sort_list == 'off') $q_active = "AND vg_is_active = 'no'";
        elseif ($sort_list == 'staff') $q_active = "AND vg_type = 'staff'";
        elseif ($sort_list == 'news') $q_active = "AND vg_type = 'news'";
        elseif ($sort_list == 'team') $q_active = "AND vg_type = 'team'";
        elseif ($sort_list == 'club') $q_active = "AND vg_type = 'club'";
        elseif ($sort_list == 'game') $q_active = "AND vg_type = 'game'";
        elseif ($sort_list == 'championship') $q_active = "AND vg_type = 'championship'";
        elseif ($sort_list == 'partners') $q_active = "AND vg_type = 'partners'";
        elseif ($sort_list == 'none') $q_active = "AND vg_type = 'none'";
        elseif ($sort_list == 'count1') $q_active = "AND vg_v_count < 2";
        elseif ($sort_list == 'countm1') $q_active = "AND vg_v_count > 1";
        else $q_active = " ";
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 10;
        $page = intval($page);

        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery
			    "," COUNT(*) as C_N
			    "," 1
			        $q_active");
        $c_pages = ceil($temp[0]['C_N']/$perpage)-1;

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

    public function getVideoGalleryList($limit = 500){
        $limit = intval($limit);
        if ($limit < 1) $limit = 500;
        $list = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","vg_id as id, vg_title_ru as title"," 1 ORDER BY vg_id DESC LIMIT $limit");
        return $list;
    }

    public function getVideoGaleryItem($vg_id){
        $vg_id = intval($vg_id);
        if ($vg_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","*","vg_id = $vg_id LIMIT 0, 1");
            $item = $item[0];
            $temp = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_gallery_id = $vg_id");
            if ($temp) $item['count_video'] = count($temp);
            else $item['count_video'] = 0;
            $item['connection_country'] = $this->getConnectionCountry($vg_id, true, 'video');
            $item['connection_country_val'] = '';
            if (!empty($item['connection_country'])){
                foreach($item['connection_country'] as $item_cc){
                    $item['connection_country_val'] .= $item_cc['id'].',';
                }
                $item['connection_country_val'] = substr($item['connection_country_val'], 0, -1);
            }
            $item['connection_champ'] = $this->getConnectionChamp($vg_id, true, 'video');
            $item['connection_champ_val'] = '';
            if (!empty($item['connection_champ'])){
                foreach($item['connection_champ'] as $item_cc){
                    $item['connection_champ_val'] .= $item_cc['id'].',';
                }
                $item['connection_champ_val'] = substr($item['connection_champ_val'], 0, -1);
            }
            return $item;
        }
        return false;
    }

    public function addVideoGallery($post){
        $post['vg_vc_id'] = intval($post['vg_vc_id'] ?? 0);
        $post['vg_type_id'] = intval($post['vg_type_id'] ?? 0);
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $post['vg_type'] = str_replace($search, $replace, $post['vg_type'] ?? '');
        if (($post['vg_type'] ?? '') == '') $post['vg_type'] = 'none';
        if (!empty($post['vg_is_active'])) $post['vg_is_active'] = 'yes';
        else $post['vg_is_active'] = 'no';
        if (!empty($post['vg_is_informer'])) $post['vg_is_informer'] = 'yes';
        else $post['vg_is_informer'] = 'no';
        if (($post['vg_date_day'] ?? 0) > 0 and ($post['vg_date_month'] ?? 0) > 0 and ($post['vg_date_year'] ?? 0) > 0) {
            $post['vg_date_day'] = intval($post['vg_date_day']);
            $post['vg_date_month'] = intval($post['vg_date_month']);
            $post['vg_date_year'] = intval($post['vg_date_year']);
            $post['vg_date_hour'] = intval($post['vg_date_hour']);
            $post['vg_date_minute'] = intval($post['vg_date_minute']);
            if ($post['vg_date_day'] < 10) $post['vg_date_day'] = '0'.$post['vg_date_day'];
            if ($post['vg_date_month'] < 10) $post['vg_date_month'] = '0'.$post['vg_date_month'];
            if ($post['vg_date_hour'] < 10) $post['vg_date_hour'] = '0'.$post['vg_date_hour'];
            if ($post['vg_date_minute'] < 10) $post['vg_date_minute'] = '0'.$post['vg_date_minute'];
            $date_show = $post['vg_date_year']."-".$post['vg_date_month']."-".$post['vg_date_day']." ".$post['vg_date_hour'].":".$post['vg_date_minute'].":00";
        } elseif (($post['vg_datetime_pub'] ?? '') != '') $date_show = str_replace($search, $replace, $post['vg_datetime_pub']);
        else $date_show = date("Y-m-d H:i:s");
        $iData = array(
            (strlen(trim(html_entity_decode(strip_tags($post['vg_title_ru'] ?? ''))))>0) ? str_replace($search, $replace, $post['vg_title_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['vg_title_ua'] ?? ''))))>0) ? str_replace($search, $replace, $post['vg_title_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['vg_title_en'] ?? ''))))>0) ? str_replace($search, $replace, $post['vg_title_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['vg_description_ru'] ?? ''))))>0) ? str_replace($search, $replace, $post['vg_description_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['vg_description_ua'] ?? ''))))>0) ? str_replace($search, $replace, $post['vg_description_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['vg_description_en'] ?? ''))))>0) ? str_replace($search, $replace, $post['vg_description_en']) : '',
            "NOW()",
            "NOW()",
            USER_ID,
            $post['vg_is_active'],
            $post['vg_type'],
            $post['vg_type_id'],
            $post['vg_vc_id'],
            $date_show,
            $post['vg_is_informer'],
            0
        );
        $id = $this->hdl->addElem(DB_T_PREFIX."video_gallery", $iData);
        if ($id) {
            if (!empty($post['v_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['v_country_auto_val'],
                    'id'=>$id,
                    'type'=>'video_gallery'
                ));
            }
            if (!empty($post['v_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['v_champ_auto_val'],
                    'id'=>$id,
                    'type'=>'video_gallery'
                ));
            }
            return $id;
        }
        return false;
    }

    public function updateVideoGalleryCategory($vg_id = 0, $vc_id = 0){
        $vg_id = intval($vg_id);
        $vc_id = intval($vc_id);
        if ($vg_id < 1) return false;

        $iData = array(
            "vg_vc_id" => $vc_id
        );
        $condition = array(
            "vg_id"=>$vg_id
        );
        $this->hdl->updateElem(DB_T_PREFIX."video_gallery",$iData, $condition);

        return true;
    }

    public function saveEditedVideoGallery($post){

        $post['vg_id'] = intval($post['vg_id']);
        if ($post['vg_id']<1) return false;

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        if ($post['vg_date_day'] > 0 and $post['vg_date_month'] > 0 and $post['vg_date_year'] > 0) {
            $post['vg_date_day'] = intval($post['vg_date_day']);
            $post['vg_date_month'] = intval($post['vg_date_month']);
            $post['vg_date_year'] = intval($post['vg_date_year']);
            $post['vg_date_hour'] = intval($post['vg_date_hour']);
            $post['vg_date_minute'] = intval($post['vg_date_minute']);
            if ($post['vg_date_day'] < 10) $post['vg_date_day'] = '0'.$post['vg_date_day'];
            if ($post['vg_date_month'] < 10) $post['vg_date_month'] = '0'.$post['vg_date_month'];
            if ($post['vg_date_hour'] < 10) $post['vg_date_hour'] = '0'.$post['vg_date_hour'];
            if ($post['vg_date_minute'] < 10) $post['vg_date_minute'] = '0'.$post['vg_date_minute'];
            $date_show = $post['vg_date_year']."-".$post['vg_date_month']."-".$post['vg_date_day']." ".$post['vg_date_hour'].":".$post['vg_date_minute'].":00";
        }

        $iData = array(
            "vg_datetime_pub" => $date_show,
            "vg_vc_id" => intval($post['vg_vc_id']),
            "vg_title_ru" => (strlen(trim(html_entity_decode(strip_tags($post['vg_title_ru']))))>0)? str_replace($search, $replace, $post['vg_title_ru']) : NULL,
            "vg_title_ua" => (strlen(trim(html_entity_decode(strip_tags($post['vg_title_ua']))))>0)? str_replace($search, $replace, $post['vg_title_ua']) : NULL,
            "vg_title_en" => (strlen(trim(html_entity_decode(strip_tags($post['vg_title_en']))))>0)? str_replace($search, $replace, $post['vg_title_en']) : NULL,
            "vg_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['vg_description_ru']))))>0)? str_replace($search, $replace, $post['vg_description_ru']) : NULL,
            "vg_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['vg_description_ua']))))>0)? str_replace($search, $replace, $post['vg_description_ua']) : NULL,
            "vg_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['vg_description_en']))))>0)? str_replace($search, $replace, $post['vg_description_en']) : NULL,
            "vg_is_active" => ($post['vg_is_active']) ? 'yes' : 'no',
            "vg_is_informer" => ($post['vg_is_informer']) ? 'yes' : 'no',
            "vg_datetime_edit"=> 'NOW()',
            "vg_author" => USER_ID
        );

        $condition = array(
            "vg_id"=>$post['vg_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."video_gallery",$iData, $condition)) {
            if (!empty($post['v_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['v_country_auto_val'],
                    'id'=>$post['vg_id'],
                    'type'=>'video_gallery'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['vg_id'],
                    'type'=>'video_gallery'
                ));
            }
            if (!empty($post['v_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['v_champ_auto_val'],
                    'id'=>$post['vg_id'],
                    'type'=>'video_gallery'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['vg_id'],
                    'type'=>'video_gallery'
                ));
            }
            return true;
        }
        return false;
    }

    public function deleteVideoGallery($vg_id=0){
        $vg_id = intval($vg_id);
        if ($vg_id>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_gallery_id = $vg_id");
            if (!$temp){
                if ($this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_id = '$vg_id' LIMIT 1")) {
                    // delete connections
                    $this->_deleteConnectionCountry(array(
                        'id'=>$vg_id,
                        'type'=>'video_gallery'
                    ));
                    $this->_deleteConnectionChamp(array(
                        'id'=>$vg_id,
                        'type'=>'video_gallery'
                    ));
                    return true;
                }
            }
        }
        return false;
    }

    public function transVideosFromGallery($from_id=0, $to_id=0){
        $from_id = intval($from_id);
        $to_id = intval($to_id);
        if ($from_id == $to_id) return false;
        if ($from_id>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_gallery_id = $from_id");
            if ($temp){
                $iData = array(
                    "v_gallery_id"=>$to_id
                );
                $condition = array(
                    "v_gallery_id"=>$from_id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."videos",$iData, $condition)) return true;
                else return false;
            }
        }else return false;
    }

    public function getVideoList($gallery_id = 0, $folder = ''){
        $extra_q = "";
        if ($gallery_id == 'no') $extra_q .= " AND v_gallery_id = '0' ";
        $gallery_id = intval($gallery_id);
        if ($gallery_id > 0) $extra_q .= " AND v_gallery_id = '$gallery_id' ";
        if ($folder != '') $extra_q .= " AND v_folder = '$folder' ";
        $list = $this->hdl->selectElem(DB_T_PREFIX."videos","*"," 1 ".$extra_q." ORDER BY v_id DESC");
        if ($list) return $list;
        else return false;
    }

    public function getVideoListPage($gallery_id = 0, $page = 0, $perpage = 12){
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 12;
        $page = intval($page);
        $page = $perpage*$page;

        $extra_q = "";
        if ($gallery_id == 'no') $extra_q .= " AND v_gallery_id = '0' ";
        $gallery_id = intval($gallery_id);
        if ($gallery_id > 0) $extra_q .= " AND v_gallery_id = '$gallery_id' ";

        $list = $this->hdl->selectElem(DB_T_PREFIX."videos",
            "	v_id,
                v_code,
                v_folder,
                v_is_active
            ",
            " 1 ".$extra_q." ORDER BY v_id DESC LIMIT $page, $perpage");
        if ($list) return $list;

        return false;
    }

    public function getVideoListPageList($gallery_id = 0, $page = 0, $perpage = 12){
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 12;
        $page = intval($page);

        $extra_q = "";
        if ($gallery_id == 'no') $extra_q .= " AND v_gallery_id = '0' ";
        $gallery_id = intval($gallery_id);
        if ($gallery_id > 0) $extra_q .= " AND v_gallery_id = '$gallery_id' ";

        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos","COUNT(*) as C_N"," 1 $extra_q");
        $c_pages = ceil($temp[0]['C_N']/$perpage)-1;

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

    public function getVideoItem($v_id){
        $v_id = intval($v_id);
        if ($v_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_id = $v_id LIMIT 0, 1");
            $item = $item[0];
            $item['v_code_text'] = stripcslashes($item['v_code_text']);
            $item['connection_country'] = $this->getConnectionCountry($v_id, false, 'video');
            $item['connection_country_val'] = '';
            if (!empty($item['connection_country'])){
                foreach($item['connection_country'] as $item_cc){
                    $item['connection_country_val'] .= $item_cc['id'].',';
                }
                $item['connection_country_val'] = substr($item['connection_country_val'], 0, -1);
            }
            $item['connection_champ'] = $this->getConnectionChamp($v_id, false, 'video');
            $item['connection_champ_val'] = '';
            if (!empty($item['connection_champ'])){
                foreach($item['connection_champ'] as $item_cc){
                    $item['connection_champ_val'] .= $item_cc['id'].',';
                }
                $item['connection_champ_val'] = substr($item['connection_champ_val'], 0, -1);
            }

            return $item;
        } else return false;
    }

//    public function getConnectionCountry($item, $is_gallery = false){
//        $item = intval($item);
//        $type = ($is_gallery) ? 'video_gallery' : 'video';
//        return $this->hdl->selectElem(DB_T_PREFIX."connection_country cc, ".DB_T_PREFIX."country c","c.cn_id as id, c.cn_title_ru as title", "cc.type_id=$item and cc.type='$type' and cc.cn_id = c.cn_id");
//    }

    public function saveVideo($post){

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $post['v_folder'] = str_replace($search, $replace, $post['v_folder'] ?? '');
        $post['v_code'] = str_replace($search, $replace, $post['v_code'] ?? '');

        // Гарантируем что заголовки не будут NULL или пустыми
        $v_title_ru = trim(html_entity_decode(strip_tags($post['v_title_ru'] ?? '')));
        $v_title_ua = trim(html_entity_decode(strip_tags($post['v_title_ua'] ?? '')));
        $v_title_en = trim(html_entity_decode(strip_tags($post['v_title_en'] ?? '')));

        // Если заголовок пустой, используем fallback
        if (empty($v_title_ru)) $v_title_ru = 'Видео ' . date('Y-m-d H:i:s');
        if (empty($v_title_ua)) $v_title_ua = $v_title_ru;
        if (empty($v_title_en)) $v_title_en = $v_title_ru;

        // Удаляем sandbox="" из iframe кода (блокирует воспроизведение VK видео)
        $v_code_text = $post['v_code_text'] ?? '';
        $v_code_text = preg_replace('/\s*sandbox\s*=\s*["\'][^"\']*["\']/i', '', $v_code_text);
        $v_code_text = preg_replace('/\s*sandbox\s*=\s*""/i', '', $v_code_text);
        $v_code_text = preg_replace('/\s*sandbox(?=\s|>)/i', '', $v_code_text);

        $iData = array(
            $post['v_code'] ?? '',
            str_replace($search, $replace, $v_title_ru),
            str_replace($search, $replace, $v_title_ua),
            str_replace($search, $replace, $v_title_en),
            (strlen(trim(html_entity_decode(strip_tags($post['v_about_ru'] ?? ''))))>0) ? str_replace($search, $replace, $post['v_about_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['v_about_ua'] ?? ''))))>0) ? str_replace($search, $replace, $post['v_about_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['v_about_en'] ?? ''))))>0) ? str_replace($search, $replace, $post['v_about_en']) : '',
            "NOW()",
            "NOW()",
            USER_ID,
            $post['v_folder'] ?? '',
            ($post['v_is_active'] ?? false) ? 'yes' : 'no',
            intval($post['v_gallery_id'] ?? 0),
            ($post['v_type'] ?? '') ? str_replace($search, $replace, $post['v_type']) : '',
            intval($post['v_type_id'] ?? 0),
            addslashes($v_code_text)
        );
        $id_video = $this->hdl->addElem(DB_T_PREFIX."videos", $iData);
        if ($id_video>0) {
            if (!empty($post['v_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['v_country_auto_val'],
                    'id'=>$id_video,
                    'type'=>'video'
                ));
            }
            if (!empty($post['v_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['v_champ_auto_val'],
                    'id'=>$id_video,
                    'type'=>'video'
                ));
            }
            $this->updateCountVideoGallery($post['v_gallery_id']);
            $filename = $id_video.".jpg";
            if (!empty($post['v_code'])){
                $file = "http://i1.ytimg.com/vi/".str_replace($search, $replace, $post['v_code'])."/hqdefault.jpg";
                if (copy($file, '../upload/video_thumbs/'.$post['v_folder'].$filename)) {
                    $this->resizePhoto("../upload/video_thumbs".$post['v_folder'].$filename, "jpg");
                }
            }
            return true;
        }
        return false;
    }

    private function updateCountVideoGallery ($id = 0){
        $id=intval($id);
        if ($id<1) {
            return false;
        }
        $phg_c = $this->hdl->selectElem(DB_T_PREFIX."videos
                "," COUNT(*) as c_v
                "," v_gallery_id = '".$id."'");
        if ($phg_c){
            $uData['vg_v_count'] = $phg_c[0]['c_v'];
            $uCondition['vg_id'] = $id;
            $this->hdl->updateElem(DB_T_PREFIX."video_gallery",$uData, $uCondition);
            return true;
        }
        return false;
    }

    public function saveEditedVideo($post){

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $post['v_id'] = intval($post['v_id']);
        if ($post['v_id'] <= 0) return false;
        $post['v_folder'] = str_replace($search, $replace, $post['v_folder']);
        $post['v_code'] = str_replace($search, $replace, $post['v_code']);

        // ������ ��� ����������
        $iData = array(
            'v_title_ru' => (strlen(trim(html_entity_decode(strip_tags($post['v_title_ru']))))>0) ? str_replace($search, $replace, $post['v_title_ru']) : NULL,
            'v_title_ua' => (strlen(trim(html_entity_decode(strip_tags($post['v_title_ua']))))>0) ? str_replace($search, $replace, $post['v_title_ua']) : NULL,
            'v_title_en' => (strlen(trim(html_entity_decode(strip_tags($post['v_title_en']))))>0) ? str_replace($search, $replace, $post['v_title_en']) : NULL,
            'v_about_ru' => (strlen(trim(html_entity_decode(strip_tags($post['v_about_ru']))))>0) ? str_replace($search, $replace, $post['v_about_ru']) : NULL,
            'v_about_ua' => (strlen(trim(html_entity_decode(strip_tags($post['v_about_ua']))))>0) ? str_replace($search, $replace, $post['v_about_ua']) : NULL,
            'v_about_en' => (strlen(trim(html_entity_decode(strip_tags($post['v_about_en']))))>0) ? str_replace($search, $replace, $post['v_about_en']) : NULL,
            'v_code' => $post['v_code'],
            'v_code_text' => addslashes($post['v_code_text']),
            'v_is_active' => ($post['v_is_active']) ? 'yes' : 'no',
            'v_folder' => $post['v_folder'],
            'v_datetime_edit' => 'NOW()',
            'v_author' => USER_ID,
            'v_gallery_id' => intval($post['v_gallery_id'])
        );
        $condition = array(
            "v_id"=>$post['v_id']
        );
        $old_video = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_id = '".$post['v_id']."' LIMIT 0, 1");
        if ($old_video[0]['v_code'] !== $post['v_code'] && !empty($post['v_code'])) {
            $old_video = $old_video[0];
            if (file_exists("../upload/video_thumbs/".$old_video['v_folder'].$old_video['v_id'].".jpg"))
                unlink("../upload/video_thumbs".$old_video['v_folder'].$old_video['v_id'].".jpg");
            if (file_exists("../upload/video_thumbs/".$old_video['v_folder'].$old_video['v_id']."-med.jpg"))
                unlink("../upload/video_thumbs".$old_video['v_folder'].$old_video['v_id']."-med.jpg");
            if (file_exists("../upload/video_thumbs/".$old_video['v_folder'].$old_video['v_id']."-small.jpg"))
                unlink("../upload/video_thumbs".$old_video['v_folder'].$old_video['v_id']."-small.jpg");
            $filename = $post['v_id'].".jpg";
            $file = "http://i1.ytimg.com/vi/".$post['v_code']."/hqdefault.jpg";
            if (copy($file, '../upload/video_thumbs/'.$post['v_folder'].$filename)) $this->resizePhoto("../upload/video_thumbs".$post['v_folder'].$filename, "jpg");
        }
        if ($this->hdl->updateElem(DB_T_PREFIX."videos",$iData, $condition)) {
            if (!empty($post['v_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['v_country_auto_val'],
                    'id'=>$post['v_id'],
                    'type'=>'video'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['v_id'],
                    'type'=>'video'
                ));
            }
            if (!empty($post['v_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['v_champ_auto_val'],
                    'id'=>$post['v_id'],
                    'type'=>'video'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['v_id'],
                    'type'=>'video'
                ));
            }
            return true;
        }
        return false;
    }

    public function saveEditedVideoPreview($file, $v_id = 0){
        if ($file['error'] == 0 and $file['size'] > 0) {
            $old_image = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_id = '$v_id' LIMIT 0, 1");
            if (file_exists("../upload/video_thumbs/".$old_image[0]['v_folder'].$old_image[0]['v_id'].".jpg"))
                unlink("../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id'].".jpg");
            if (file_exists("../upload/video_thumbs/".$old_image[0]['v_folder'].$old_image[0]['v_id']."-small.jpg"))
                unlink("../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id']."-small.jpg");
            if (move_uploaded_file($file['tmp_name'], "../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id'].".jpg"))
                $this->resizePhoto("../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id'].".jpg", "jpg");
            return true;
        } else return false;
    }

    public function deleteVideo($v_id=0){
        $v_id = intval($v_id);
        if ($v_id>0){
            $old_image = $this->hdl->selectElem(DB_T_PREFIX."videos","*","v_id = '$v_id' LIMIT 0, 1");
            if ($old_image[0]['v_id'] >0) {
                if (unlink("../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id']."-small.jpg") and unlink("../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id'].".jpg") and unlink("../upload/video_thumbs".$old_image[0]['v_folder'].$old_image[0]['v_id']."-med.jpg")){
                    if ($this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '$v_id' LIMIT 1")) {
                        $this->updateCountVideoGallery($old_image[0]['v_gallery_id']);
                        return true;
                    }
                }
            }
            // delete connections
            $this->_deleteConnectionCountry(array(
                'id'=>$v_id,
                'type'=>'video'
            ));
            $this->_deleteConnectionChamp(array(
                'id'=>$v_id,
                'type'=>'video'
            ));
        }
        return false;
    }

    public function resizePhoto($file = false, $type = ''){
        if (!$file) return false;
        if ($type == '') return false;
        $is_medium = true ; // ���./����. ������ ������� ������
        // ������������ ������ ������� ��������
        $max = 500;
        // ������������ ������ ������� ��������
        $max_m = 249;
        // ������� ��������
        $p_x = 64;
        $p_y = 64;

        $image_size = getimagesize($file);

        // JPG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == 'jpeg' or $type == 'jpg'){
            $im = imagecreatefromjpeg($file);
            // ���������� ������
            $p_aspect = $p_x / $p_y;
            $aspect = $image_size[0] / $image_size[1];
            if ($aspect >= $p_aspect){
                $s_y = $image_size[1];
                $s_x = round($s_y*$p_aspect);
                $src_y = 0;
                $src_x = round(($image_size[0]-$s_x)/2);
            } else {
                $s_x = $image_size[0];
                $s_y = round($s_x/$p_aspect);
                $src_x = 0;
                $src_y = round(($image_size[1]-$s_y)/2);
            }
            if ($image_size[0]>=$p_x) $dest_x = 0;
            else $dest_x = round(($p_x - $image_size[0]) / 2);
            if ($image_size[1]>=$p_y) $dest_y = 0;
            else $dest_y = round(($p_y - $image_size[1]) / 2);
            $nim = imagecreatetruecolor($p_x,$p_y);
            $white = imagecolorallocate($nim, 255, 255, 255);
            imagefilledrectangle($nim, 0, 0, $p_x, $p_y, $white);
            if ($image_size[0]<$p_x) $p_x = $image_size[0];
            if ($image_size[1]<$p_y) $p_y = $image_size[1];
            imagecopyresampled($nim, $im, $dest_x, $dest_y, $src_x, $src_y, $p_x, $p_y, $s_x, $s_y);
            $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-small".strrchr($file, ".");
            imagejpeg($nim, $n_file);
            imagedestroy($nim);

            // ���������� ������� ��������
            if ($is_medium) {
                $aspect = $image_size[0] / $image_size[1];
                $max_m_y = $max_m/$aspect;
                $nim = imagecreatetruecolor($max_m,$max_m_y);
                imagecopyresampled($nim, $im, 0, 0, 0, 0, $max_m, $max_m_y, $image_size[0], $image_size[1]);
                $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-med".strrchr($file, ".");
                imagejpeg($nim, $n_file);
                imagedestroy($nim);
            }

            // ���������� ������� �����
            if ($image_size[0]>$max or $image_size[1]>$max) {
                if ($image_size[0]>=$image_size[1]) {
                    $aspect = $image_size[1] / $image_size[0];
                    $nw = $max;
                    $nh = round($nw * $aspect);
                } else {
                    $aspect = $image_size[0] / $image_size[1];
                    $nh = $max;
                    $nw = round($nh * $aspect);
                }
                $nim = imagecreatetruecolor($nw,$nh);
                imagecopyresampled($nim, $im, 0,0,0,0,$nw,$nh,$image_size[0],$image_size[1]);
                imagejpeg($nim, $file);
                imagedestroy($nim);
            }
        }
    }

    // SETTINGS
    public function getVideoSettings(){
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

    public function getInformer(){
        $item = $this->hdl->selectElem(DB_T_PREFIX."settings","*","set_name = 'video_is_informer' LIMIT 0, 1");
        if ($item) return $item[0];
        else return false;
    }

    public function saveInformer($is_active = true){
        if ($is_active) $is_active = 1;
        else $is_active = 0;
        $iData = array(
            "set_author"=> USER_ID,
            "set_datetime_edit"=> 'NOW()',
            "set_value" => $is_active
        );
        $condition = array(
            "set_name"=> 'video_is_informer'
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$iData, $condition)) return true;
        else return false;
    }

//    private function _updateConnectionCountry($post = array()){
//        if (!empty($post['country']) && !empty($post['type']) && !empty($post['id'])){
//            $countries_ar = explode(',', $post['country']);
//            $this->hdl->delElem(DB_T_PREFIX."connection_country", "type='".$post['type']."' AND type_id='".$post['id']."' AND cn_id NOT IN (".$post['country'].")");
//            $country_added = $this->hdl->selectElem(DB_T_PREFIX."connection_country", "cn_id", "type='".$post['type']."' AND type_id='".$post['id']."' AND cn_id IN (".$post['country'].")");
//            if (!empty($country_added)){
//                foreach($country_added as $item){
//                    $country_added_ar[$item['cn_id']] = true;
//                }
//            }
//            foreach($countries_ar as $item){
//                if (empty($country_added_ar[$item])){
//                    $elem = array(
//                        $item,
//                        $post['type'],
//                        $post['id'],
//                        'NOW()'
//                    );
//                    $this->hdl->addElem(DB_T_PREFIX."connection_country", $elem);
//                }
//            }
//        }
//    }

//    private function _deleteConnectionCountry($post = array()){
//        if (!empty($post['id']) && !empty($post['type'])){
//            $this->hdl->delElem(DB_T_PREFIX."connection_country", "type='".$post['type']."' AND type_id='".$post['id']."'");
//        }
//    }

//    public function getCountryList(){
//        $temp = $this->hdl->selectElem(DB_T_PREFIX."country","
//				cn_id as id,
//				cn_title_ru as title
//				","1 ORDER BY cn_title_ru ASC");
//        if ($temp) {
//            foreach ($temp as &$item){
//                $item['title'] = stripslashes($item['title']);
//            }
//        }
//        return $temp;
//    }
}

