<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_base.php');

class photos extends adminBase{
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // РУБРИКИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['phc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['phc_address'] = strtolower($post['phc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '', '');
        $elem = array(
            addslashes($post['phc_title_ru']),
            addslashes($post['phc_title_ua']),
            addslashes($post['phc_title_en']),
            str_replace($search, $replace, $post['phc_address']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            intval($post['phc_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."photo_categories", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['phc_id'] <1) return false;
        if($post['phc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['phc_address'] = strtolower($post['phc_address']);
        $search = array("-", " ", "'", '"');
        $replace = array("_", "_", '', '');
        $elems = array(
            "phc_title_ru" => addslashes($post['phc_title_ru']),
            "phc_title_ua" => addslashes($post['phc_title_ua']),
            "phc_title_en" => addslashes($post['phc_title_en']),
            "phc_address" => str_replace($search, $replace, $post['phc_address']),
            "phc_is_active" => $is_active,
            "phc_datetime_edit" => 'NOW()',
            "phc_author" => USER_ID,
            "phc_order" => intval($post['phc_order'])
        );
        $condition = array(
            "phc_id"=>$post['phc_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."photo_categories",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."photo_categories", "phc_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_categories","*","phc_id=$item");
            $temp[0]['phc_title_ru'] = stripcslashes($temp[0]['phc_title_ru']);
            $temp[0]['phc_title_ua'] = stripcslashes($temp[0]['phc_title_ua']);
            $temp[0]['phc_title_en'] = stripcslashes($temp[0]['phc_title_en']);
            return $temp[0];
        } else return false;
    }

    public function getPhotosCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."photo_categories","*","1 ORDER BY phc_order DESC, phc_id asc");
    }

    public function getPhotosCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_categories","*","1 ORDER BY phc_order DESC, phc_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['phc_id']] = $item;
            }
            return $list;
        } else return false;
    }
    // РУБРИКИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

    // фото для разных разделов //////// начало ///////////////////////////////////////////////////////////

    public function resetTypeMainPhotos($type_id=0, $type = ''){
        $type = addslashes($type);
        $type_id = intval($type_id);
        if ($type_id > 0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_type_id = $type_id AND ph_type = '$type'");
            if ($temp){
                $iData = array(
                    "ph_type_main"=>'no'
                );
                $condition = array(
                    "ph_type_id"=>$type_id,
                    "ph_type"=>$type
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."photos",$iData, $condition)) return true;
                else return false;
            }
        }else return false;
    }

    public function getTypePhotoList($type_id = 0, $type = ''){
        $type = addslashes($type);
        $type_id = intval($type_id);
        $list = $this->hdl->selectElem(DB_T_PREFIX."photos","*"," ph_type_id = '$type_id' AND ph_type = '$type' ORDER BY ph_order DESC, ph_id DESC");
        if ($list) return $list;
        else return false;
    }

    public function getTypePhotoGallery($type_id = 0, $type = ''){
        $type_id = intval($type_id);
        $type = addslashes($type);
        if ($type_id >0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","*"," phg_type_id = '$type_id' AND phg_type = '$type' LIMIT 1");
            if ($temp){
                return $temp[0];
            } else return false;
        }
    }
    // фото для разных разделов //////// конец ///////////////////////////////////////////////////////////

    public function getPhotoCategoryList(){
        $list = $this->hdl->selectElem(DB_T_PREFIX."photo_categories","*"," 1 ORDER BY phc_order DESC");
        if ($list) return $list;
        else return false;
    }

    public function getPhotoGalleryListPage($sort_list = 'all', $page = 0, $perpage = 10){
        if ($sort_list == 'on') $q_active = "AND phg_is_active = 'yes'";
        elseif ($sort_list == 'off') $q_active = "AND phg_is_active = 'no'";
        elseif ($sort_list == 'staff') $q_active = "AND phg_type = 'staff'";
        elseif ($sort_list == 'news') $q_active = "AND phg_type = 'news'";
        elseif ($sort_list == 'team') $q_active = "AND phg_type = 'team'";
        elseif ($sort_list == 'club') $q_active = "AND phg_type = 'club'";
        elseif ($sort_list == 'game') $q_active = "AND phg_type = 'game'";
        elseif ($sort_list == 'championship') $q_active = "AND phg_type = 'championship'";
        elseif ($sort_list == 'partners') $q_active = "AND phg_type = 'partners'";
        elseif ($sort_list == 'none') $q_active = "AND phg_type = 'none'";
        elseif ($sort_list == 'count1') $q_active = "AND phg_ph_count < 2";
        elseif ($sort_list == 'countm1') $q_active = "AND phg_ph_count > 1";
        else $q_active = " ";
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 10;
        $page = intval($page);
        $page = $perpage*$page;


        $list = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery
			    "," phg_id,
			        phg_title_ru,
			        phg_is_active,
			        phg_is_informer,
			        phg_type,
			        phg_ph_count as count_photo
			    "," 1
			        $q_active
			        ORDER BY phg_id DESC
			        LIMIT $page, $perpage");
        return $list;
    }

    public function getPhotoGalleryListPageList($sort_list = 'all', $page = 0, $perpage = 10){
        if ($sort_list == 'on') $q_active = "AND phg_is_active = 'yes'";
        elseif ($sort_list == 'off') $q_active = "AND phg_is_active = 'no'";
        elseif ($sort_list == 'staff') $q_active = "AND phg_type = 'staff'";
        elseif ($sort_list == 'news') $q_active = "AND phg_type = 'news'";
        elseif ($sort_list == 'team') $q_active = "AND phg_type = 'team'";
        elseif ($sort_list == 'club') $q_active = "AND phg_type = 'club'";
        elseif ($sort_list == 'game') $q_active = "AND phg_type = 'game'";
        elseif ($sort_list == 'championship') $q_active = "AND phg_type = 'championship'";
        elseif ($sort_list == 'partners') $q_active = "AND phg_type = 'partners'";
        elseif ($sort_list == 'none') $q_active = "AND phg_type = 'none'";
        elseif ($sort_list == 'count1') $q_active = "AND phg_ph_count < 2";
        elseif ($sort_list == 'countm1') $q_active = "AND phg_ph_count > 1";
        else $q_active = " ";
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 10;
        $page = intval($page);


        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery
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

    public function getPhotoGalleryList($limit = 500){
        $limit = intval($limit);
        if ($limit < 1) $limit = 500;
        $list = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id as id, phg_title_ru as title"," 1 ORDER BY phg_id DESC LIMIT $limit");
        return $list;
    }

    public function getPhotoGaleryItem($phg_id){
        $phg_id = intval($phg_id);
        if ($phg_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","*","phg_id = $phg_id LIMIT 0, 1");
            $item=$item[0];
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_gallery_id = $phg_id");
            if ($temp) $item['count_photo'] = count($temp);
            else $item['count_photo'] = 0;


            $item['connection_country'] = $this->getConnectionCountry($phg_id, true, 'photo');
            $item['connection_country_val'] = '';
            if (!empty($item['connection_country'])){
                foreach($item['connection_country'] as $item_cc){
                    $item['connection_country_val'] .= $item_cc['id'].',';
                }
                $item['connection_country_val'] = substr($item['connection_country_val'], 0, -1);
            }
            $item['connection_champ'] = $this->getConnectionChamp($phg_id, true, 'photo');
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

    public function addPhotoGallery($post){
        $post['phg_phc_id'] = intval($post['phg_phc_id']);
        $post['phg_type_id'] = intval($post['phg_type_id']);
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $post['phg_type'] = str_replace($search, $replace, $post['phg_type']);
        if ($post['phg_type'] == '') $post['phg_type'] = 'none';
        if (!empty($post['phg_is_active'])) $post['phg_is_active'] = 'yes';
        else $post['phg_is_active'] = 'no';
        if (!empty($post['phg_is_informer'])) $post['phg_is_informer'] = 'yes';
        else $post['phg_is_informer'] = 'no';
        if (!empty($post['phg_date_day']) && $post['phg_date_day'] > 0 && !empty($post['phg_date_month']) && $post['phg_date_month'] > 0 && !empty($post['phg_date_year']) && $post['phg_date_year'] > 0) {
            $post['phg_date_day'] = intval($post['phg_date_day']);
            $post['phg_date_month'] = intval($post['phg_date_month']);
            $post['phg_date_year'] = intval($post['phg_date_year']);
            if ($post['phg_date_day'] < 10) $post['phg_date_day'] = '0'.$post['phg_date_day'];
            if ($post['phg_date_month'] < 10) $post['phg_date_month'] = '0'.$post['phg_date_month'];
            $date_show = $post['phg_date_year']."-".$post['phg_date_month']."-".$post['phg_date_day'];
        } elseif ($post['phg_datetime_pub'] != '') $date_show = str_replace($search, $replace, $post['phg_datetime_pub']);
        else $date_show = date("Y-m-d");
        $iData = array(
            (strlen(trim(html_entity_decode(strip_tags($post['phg_title_ru']))))>0) ? str_replace($search, $replace, $post['phg_title_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['phg_title_ua']))))>0) ? str_replace($search, $replace, $post['phg_title_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['phg_title_en']))))>0) ? str_replace($search, $replace, $post['phg_title_en']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['phg_description_ru']))))>0) ? str_replace($search, $replace, $post['phg_description_ru']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['phg_description_ua']))))>0) ? str_replace($search, $replace, $post['phg_description_ua']) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['phg_description_en']))))>0) ? str_replace($search, $replace, $post['phg_description_en']) : '',
            "NOW()",
            "NOW()",
            USER_ID,
            $post['phg_is_active'],
            $post['phg_type'],
            $post['phg_type_id'],
            $post['phg_phc_id'],
            $date_show,
            $post['phg_is_informer'],
            0
        );
        $id = $this->hdl->addElem(DB_T_PREFIX."photo_gallery", $iData);
        if (!empty($id)) {
            if (!empty($post['ph_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['ph_country_auto_val'],
                    'id'=>$id,
                    'type'=>'photo_gallery'
                ));
            }
            if (!empty($post['ph_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['ph_champ_auto_val'],
                    'id'=>$id,
                    'type'=>'photo_gallery'
                ));
            }
            return $id;
        }
        return false;
    }

    public function updatePhotoGalleryCategory($phg_id = 0, $phc_id = 0){
        $phg_id = intval($phg_id);
        $phc_id = intval($phc_id);
        if ($phg_id < 1) return false;

        $iData = array(
            "phg_phc_id" => $phc_id
        );
        $condition = array(
            "phg_id"=>$phg_id
        );
        $this->hdl->updateElem(DB_T_PREFIX."photo_gallery",$iData, $condition);

        return true;
    }

    public function saveEditedPhotoGallery($post){

        $post['phg_id'] = intval($post['phg_id']);
        if ($post['phg_id']<1) return false;

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        if ($post['phg_date_day'] > 0 and $post['phg_date_month'] > 0 and $post['phg_date_year'] > 0) {
            $post['phg_date_day'] = intval($post['phg_date_day']);
            $post['phg_date_month'] = intval($post['phg_date_month']);
            $post['phg_date_year'] = intval($post['phg_date_year']);
            $post['phg_date_hour'] = intval($post['phg_date_hour']);
            $post['phg_date_minute'] = intval($post['phg_date_minute']);
            if ($post['phg_date_day'] < 10) $post['phg_date_day'] = '0'.$post['phg_date_day'];
            if ($post['phg_date_month'] < 10) $post['phg_date_month'] = '0'.$post['phg_date_month'];
            if ($post['phg_date_hour'] < 10) $post['phg_date_hour'] = '0'.$post['phg_date_hour'];
            if ($post['phg_date_minute'] < 10) $post['phg_date_minute'] = '0'.$post['phg_date_minute'];
            $date_show = $post['phg_date_year']."-".$post['phg_date_month']."-".$post['phg_date_day']." ".$post['phg_date_hour'].":".$post['phg_date_minute'].":00";
        }

        $iData = array(
            "phg_datetime_pub" => $date_show,
            "phg_phc_id" => intval($post['phg_phc_id']),
            "phg_title_ru" => (strlen(trim(html_entity_decode(strip_tags($post['phg_title_ru']))))>0)? str_replace($search, $replace, $post['phg_title_ru']) : NULL,
            "phg_title_ua" => (strlen(trim(html_entity_decode(strip_tags($post['phg_title_ua']))))>0)? str_replace($search, $replace, $post['phg_title_ua']) : NULL,
            "phg_title_en" => (strlen(trim(html_entity_decode(strip_tags($post['phg_title_en']))))>0)? str_replace($search, $replace, $post['phg_title_en']) : NULL,
            "phg_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['phg_description_ru']))))>0)? str_replace($search, $replace, $post['phg_description_ru']) : NULL,
            "phg_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['phg_description_ua']))))>0)? str_replace($search, $replace, $post['phg_description_ua']) : NULL,
            "phg_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['phg_description_en']))))>0)? str_replace($search, $replace, $post['phg_description_en']) : NULL,
            "phg_is_active" => ($post['phg_is_active']) ? 'yes' : 'no',
            "phg_is_informer" => ($post['phg_is_informer']) ? 'yes' : 'no',
            "phg_datetime_edit"=> 'NOW()',
            "phg_author" => USER_ID
        );

        $condition = array(
            "phg_id"=>$post['phg_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."photo_gallery",$iData, $condition)) {
            if (!empty($post['ph_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['ph_country_auto_val'],
                    'id'=>$post['phg_id'],
                    'type'=>'photo_gallery'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['phg_id'],
                    'type'=>'photo_gallery'
                ));
            }
            if (!empty($post['ph_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['ph_champ_auto_val'],
                    'id'=>$post['phg_id'],
                    'type'=>'photo_gallery'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['phg_id'],
                    'type'=>'photo_gallery'
                ));
            }
            return true;
        }
        return false;
    }

    public function deletePhotoGallery($phg_id=0){
        $phg_id = intval($phg_id);
        if ($phg_id>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_gallery_id = $phg_id");
            if (!$temp){
                if ($this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_id = '$phg_id' LIMIT 1")) {
                    // delete connections
                    $this->_deleteConnectionCountry(array(
                        'id'=>$phg_id,
                        'type'=>'photo_gallery'
                    ));
                    $this->_deleteConnectionChamp(array(
                        'id'=>$phg_id,
                        'type'=>'photo_gallery'
                    ));
                    return true;
                }
            }
        }
        return false;
    }

    public function transPhotosFromGallery($from_id=0, $to_id=0){
        $from_id = intval($from_id);
        $to_id = intval($to_id);
        if ($from_id == $to_id) return false;
        if ($from_id>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_gallery_id = $from_id");
            if ($temp){
                $iData = array(
                    "ph_gallery_id"=>$to_id
                );
                $condition = array(
                    "ph_gallery_id"=>$from_id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."photos",$iData, $condition)) return true;
                else return false;
            }
        }else return false;
    }

    public function getPhotoList($gallery_id = 0, $folder = ''){
        $extra_q = "";
        if ($gallery_id == 'no') $extra_q .= " AND ph_gallery_id = '0' ";
        $gallery_id = intval($gallery_id);
        if ($gallery_id > 0) $extra_q .= " AND ph_gallery_id = '$gallery_id' ";
        if ($folder != '') $extra_q .= " AND ph_folder = '$folder' ";
        $list = $this->hdl->selectElem(DB_T_PREFIX."photos","*"," 1 ".$extra_q." ORDER BY ph_order DESC, ph_id DESC");
        if ($list) {
            foreach ($list as &$item){
                $item['small'] = "upload/photos".$item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
            }
        }
        return $list;
    }

    public function getPhotoListPage($gallery_id = 0, $page = 0, $perpage = 12){
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 12;
        $page = intval($page);
        $page = $perpage*$page;

        $extra_q = "";
        if ($gallery_id == 'no') $extra_q .= " AND ph_gallery_id = '0' ";
        $gallery_id = intval($gallery_id);
        if ($gallery_id > 0) $extra_q .= " AND ph_gallery_id = '$gallery_id' ";

        $list = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
						ph_path,
						ph_folder,
						ph_is_active,
						ph_type_main
					",
            " 1 ".$extra_q." ORDER BY ph_id DESC LIMIT $page, $perpage");
        if ($list) {
            foreach ($list as &$item) $item['med'] = $item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
        }
        return $list;
    }

    public function getPhotoListPageList($gallery_id = 0, $page = 0, $perpage = 12){
        $perpage = intval($perpage);
        if ($perpage <2) $perpage = 12;
        $page = intval($page);

        $extra_q = "";
        if ($gallery_id == 'no') $extra_q .= " AND ph_gallery_id = '0' ";
        $gallery_id = intval($gallery_id);
        if ($gallery_id > 0) $extra_q .= " AND ph_gallery_id = '$gallery_id' ";

        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","COUNT(*) as C_N"," 1 $extra_q");
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

    public function getPhotoItem($ph_id){
        $ph_id = intval($ph_id);
        if ($ph_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_id = $ph_id LIMIT 0, 1");
            if ($item){
                $item = $item[0];

                $item['photo'] = $item['ph_folder'].$item['ph_path'];
                $bytes = filesize("../upload/photos".$item['photo']);
                $item['photo_size'] = str_replace('.', ',', $this->formatBytes($bytes, $precision = 2));
                $item['photo_imagesize'] = getimagesize("../upload/photos".$item['photo']);

                $item['photo_med'] = $item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
                if (file_exists("../upload/photos".$item['photo_med'])) {
                    $bytes = filesize("../upload/photos".$item['photo_med']);
                    $item['photo_med_imagesize'] = getimagesize("../upload/photos".$item['photo_med']);
                } else {
                    $bytes = 0;
                    $item['photo_med_imagesize'] = 0;
                }
                $item['photo_med_size'] = str_replace('.', ',', $this->formatBytes($bytes, $precision = 2));


                $item['photo_small'] = $item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
                if (file_exists("../upload/photos".$item['photo_small'])) {
                    $bytes = filesize("../upload/photos".$item['photo_small']);
                    $item['photo_small_imagesize'] = getimagesize("../upload/photos".$item['photo_small']);
                } else {
                    $bytes = 0;
                    $item['photo_small_imagesize'] = 0;
                }
                $item['photo_small_size'] = str_replace('.', ',', $this->formatBytes($bytes, $precision = 2));

                $item['connection_country'] = $this->getConnectionCountry($ph_id, false, 'photo');
                $item['connection_country_val'] = '';
                if (!empty($item['connection_country'])){
                    foreach($item['connection_country'] as $item_cc){
                        $item['connection_country_val'] .= $item_cc['id'].',';
                    }
                    $item['connection_country_val'] = substr($item['connection_country_val'], 0, -1);
                }
                $item['connection_champ'] = $this->getConnectionChamp($ph_id, false, 'photo');
                $item['connection_champ_val'] = '';
                if (!empty($item['connection_champ'])){
                    foreach($item['connection_champ'] as $item_cc){
                        $item['connection_champ_val'] .= $item_cc['id'].',';
                    }
                    $item['connection_champ_val'] = substr($item['connection_champ_val'], 0, -1);
                }
            }
            return $item;
        }
        return false;
    }

    private function formatBytes($bytes, $precision = 2) { // для правильного отображения размера файла
        $units = array('б', 'Кб', 'Мб', 'Гб', 'Тб');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function savePhoto($file, $post){

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $post['ph_folder'] = str_replace($search, $replace, $post['ph_folder']);

        if (!empty($post['files_array'])){
            $files_array = array();
            $files_array_ = json_decode(base64_decode($post['files_array']));
            foreach ($files_array_ as $item) {
                if (!empty($item)) {
                    $path = str_replace(array('http://'.$_SERVER['SERVER_NAME'].'/admin/'), array(''), $item->url);
                    $path_a = explode('/', $path);
                    unset($path_a[count($path_a)-1]);
                    $path = implode('/', $path_a);
                    if (!empty($path) && !empty($item->name)) {
                        $file_name = $this->fileName('photos', $post['ph_folder'], $item->name, 0);
                        if (copy($path.'/'.$item->name, "../upload/photos".$post['ph_folder'].$file_name)) {
                            $files_array[] = array(
                                'path' => "../upload/photos".$post['ph_folder'],
                                'file_name' => $file_name,
                                'type' => $item->type,
                            );
                        }
                    }
                }
            }
        } else {
            // заливаем файл
            if (!isset($file['error']) || $file['error'] != 0 || !isset($file['size']) || $file['size'] == 0) {
                return false;
            }

            $file['name'] = $this->getTranslit($file['name']);
            $file_name = $this->fileName('photos', $post['ph_folder'], $file['name'], 0);
            if (move_uploaded_file($file['tmp_name'], "../upload/photos".$post['ph_folder'].$file_name)) {
                $files_array[] = array(
                    'path' => "../upload/photos".$post['ph_folder'],
                    'file_name' => $file_name,
                    'type' => $file["type"]
                );
            }
        }
        $post['ph_type'] = str_replace($search, $replace, $post['ph_type']);
        $post['ph_gallery_id'] = intval($post['ph_gallery_id']);
        $post['ph_type_id'] = intval($post['ph_type_id']);
        if (!empty($post['ph_is_active'])) $post['ph_is_active'] = 'yes';
        else $post['ph_is_active'] = 'no';
        if (!empty($post['ph_is_informer'])) $post['ph_is_informer'] = 'yes';
        else $post['ph_is_informer'] = 'no';
        if (!empty($files_array)) {
            foreach ($files_array as $file_item) {
                // пережимаем картинку
                $this->resizePhoto($file_item['path'] . $file_item['file_name'], $file_item["type"], !empty($post['ph_type_main']) ? $post['ph_type_main'] : null, $post['ph_signature']);

                //запись в БД
                if (!empty($post['ph_type_main'])) {
                    $post['ph_type_main'] = 'yes';
                    $this->resetGalMainPhotos($post['ph_gallery_id']);
                } else $post['ph_type_main'] = 'no';

                $iData = array(
                    $file_item['file_name'],
                    (strlen(trim(html_entity_decode(strip_tags($post['ph_about_ru'] ?? '')))) > 0) ? str_replace($search, $replace, $post['ph_about_ru']) : '',
                    (strlen(trim(html_entity_decode(strip_tags($post['ph_about_ua'] ?? '')))) > 0) ? str_replace($search, $replace, $post['ph_about_ua']) : '',
                    (strlen(trim(html_entity_decode(strip_tags($post['ph_about_en'] ?? '')))) > 0) ? str_replace($search, $replace, $post['ph_about_en']) : '',
                    "NOW()",
                    "NOW()",
                    USER_ID,
                    $post['ph_folder'],
                    $post['ph_is_active'],
                    $post['ph_gallery_id'],
                    $post['ph_is_informer'],
                    $post['ph_type'],
                    $post['ph_type_id'],
                    $post['ph_type_main'],
                    intval($post['ph_order']),
                    (strlen(trim(html_entity_decode(strip_tags($post['ph_title_ru'] ?? '')))) > 0) ? str_replace($search, $replace, $post['ph_title_ru']) : '',
                    (strlen(trim(html_entity_decode(strip_tags($post['ph_title_ua'] ?? '')))) > 0) ? str_replace($search, $replace, $post['ph_title_ua']) : '',
                    (strlen(trim(html_entity_decode(strip_tags($post['ph_title_en'] ?? '')))) > 0) ? str_replace($search, $replace, $post['ph_title_en']) : ''
                );
                if ($ph_id = $this->hdl->addElem(DB_T_PREFIX . "photos", $iData)) {
                    // если фото 2-е в тематическую галерею то галерея должна активироваться НАЧАЛО
                    if ($post['ph_type'] != 'none') {
                        $g_t = $this->hdl->selectElem(DB_T_PREFIX . "photo_gallery", "phg_id, phg_is_active", "phg_type = '" . $post['ph_type'] . "' AND phg_type_id = '" . $post['ph_type_id'] . "' AND phg_id = '" . $post['ph_gallery_id'] . "' LIMIT 0, 1");
                        if (!empty($g_t) && $g_t[0]['phg_is_active'] == 'no') {
                            if (count($this->hdl->selectElem(DB_T_PREFIX . "photos", "ph_id", "ph_gallery_id = '" . $post['ph_gallery_id'] . "' LIMIT 0, 3")) == 2) {
                                $uData['phg_is_active'] = 'yes';
                                $uData['phg_datetime_edit'] = 'NOW()';
                                $uData['phg_author'] = USER_ID;
                                $uCondition['phg_id'] = $post['ph_gallery_id'];
                                $this->hdl->updateElem(DB_T_PREFIX . "photo_gallery", $uData, $uCondition);
                            }
                        }
                    }
                    // если фото 2-е в тематическую галерею то галерея должна активироваться КОНЕЦ
                    $this->_updateConnectionCountry(array(
                        'country' => !empty($post['ph_country_auto_val']) ? $post['ph_country_auto_val'] : '',
                        'id' => $ph_id,
                        'type' => 'photo'
                    ));
                    $this->_updateConnectionChamp(array(
                        'champ' => !empty($post['ph_champ_auto_val']) ? $post['ph_champ_auto_val'] : '',
                        'id' => $ph_id,
                        'type' => 'photo'
                    ));
                    $this->updateCountPhotoGallery($post['ph_gallery_id']);

                }
            }
            return true;
        }
        return false;
    }

    private function updateCountPhotoGallery ($id = 0){
        $id=intval($id);
        if ($id<1) {
            return false;
        }
        $phg_c = $this->hdl->selectElem(DB_T_PREFIX."photos
                "," COUNT(*) as c_ph
                "," ph_gallery_id = '".$id."'");
        if ($phg_c){
            $uData['phg_ph_count'] = $phg_c[0]['c_ph'];
            $uCondition['phg_id'] = $id;
            $this->hdl->updateElem(DB_T_PREFIX."photo_gallery",$uData, $uCondition);
            return true;
        }
        return false;
    }

    public function saveEditedPhoto($file, $post){

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $post['ph_id'] = intval($post['ph_id']);
        $post['ph_folder'] = str_replace($search, $replace, $post['ph_folder']);
        if ($post['ph_type_main']) {
            $post['ph_type_main'] = 'yes';
            $this->resetGalMainPhotos(intval($post['ph_gallery_id']));
        } else $post['ph_type_main'] = 'no';

        // массив для сохранения
        $iData = array(
            'ph_about_ru' => (strlen(trim(html_entity_decode(strip_tags($post['ph_about_ru'] ?? ''))))>0) ? str_replace($search, $replace, $post['ph_about_ru']) : '',
            'ph_about_ua' => (strlen(trim(html_entity_decode(strip_tags($post['ph_about_ua'] ?? ''))))>0) ? str_replace($search, $replace, $post['ph_about_ua']) : '',
            'ph_about_en' => (strlen(trim(html_entity_decode(strip_tags($post['ph_about_en'] ?? ''))))>0) ? str_replace($search, $replace, $post['ph_about_en']) : '',
            'ph_title_ru' => (strlen(trim(html_entity_decode(strip_tags($post['ph_title_ru'] ?? ''))))>0) ? str_replace($search, $replace, $post['ph_title_ru']) : '',
            'ph_title_ua' => (strlen(trim(html_entity_decode(strip_tags($post['ph_title_ua'] ?? ''))))>0) ? str_replace($search, $replace, $post['ph_title_ua']) : '',
            'ph_title_en' => (strlen(trim(html_entity_decode(strip_tags($post['ph_title_en'] ?? ''))))>0) ? str_replace($search, $replace, $post['ph_title_en']) : '',
            'ph_is_active' => ($post['ph_is_active']) ? 'yes' : 'no',
            'ph_is_informer' => ($post['ph_is_informer']) ? 'yes' : 'no',
            'ph_type_main' => $post['ph_type_main'],
            'ph_folder' => $post['ph_folder'],
            'ph_datetime_edit' => 'NOW()',
            'ph_author' => USER_ID,
            'ph_gallery_id' => intval($post['ph_gallery_id']),
            'ph_order' => intval($post['ph_order'])
        );
        $old_image = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_id = '".$post['ph_id']."' LIMIT 0, 1");
        $old_image = $old_image[0];
        if ($file['error'] == 0 and $file['size'] > 0) {
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$old_image['ph_path'])) unlink("../upload/photos".$old_image['ph_folder'].$old_image['ph_path']);
            $o_file = substr($old_image['ph_path'], 0, strlen(strrchr($old_image['ph_path'], "."))*(-1))."-informer".strrchr($old_image['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$o_file)) unlink("../upload/photos".$old_image['ph_folder'].$o_file);
            $o_file = substr($old_image['ph_path'], 0, strlen(strrchr($old_image['ph_path'], "."))*(-1))."-small".strrchr($old_image['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$o_file)) unlink("../upload/photos".$old_image['ph_folder'].$o_file);
            $o_file = substr($old_image['ph_path'], 0, strlen(strrchr($old_image['ph_path'], "."))*(-1))."-s_main".strrchr($old_image['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$o_file)) unlink("../upload/photos".$old_image['ph_folder'].$o_file);
            $o_file = substr($old_image['ph_path'], 0, strlen(strrchr($old_image['ph_path'], "."))*(-1))."-main".strrchr($old_image['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$o_file)) unlink("../upload/photos".$old_image['ph_folder'].$o_file);
            $o_file = substr($old_image['ph_path'], 0, strlen(strrchr($old_image['ph_path'], "."))*(-1))."-med".strrchr($old_image['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$o_file)) unlink("../upload/photos".$old_image['ph_folder'].$o_file);
            $file['name'] = $this->getTranslit($file['name']);
            $file_name = $this->fileName('photos', $post['ph_folder'], $file['name'], 0);
            if (move_uploaded_file($file['tmp_name'], "../upload/photos".$post['ph_folder'].$file_name)) {
                $this->resizePhoto("../upload/photos".$post['ph_folder'].$file_name, $file["type"], $post['ph_type_main'], $post['ph_signature']);
            }
            $iData['ph_path'] = $file_name;
        } else {
            if (file_exists ("../upload/photos".$old_image['ph_folder'].$old_image['ph_path']) and trim($post['ph_signature']) != '') $this->drawTextPhoto("../upload/photos".$old_image['ph_folder'].$old_image['ph_path'], $post['ph_signature']);
            if ($post['ph_type_main'] == 'yes') {
                $this->resizeMainPhoto("../upload/photos".$old_image['ph_folder'].$old_image['ph_path'], strrchr($old_image['ph_path'], "."));
            }
        }

        $condition = array(
            "ph_id"=>$post['ph_id']
        );

        if ($this->hdl->updateElem(DB_T_PREFIX."photos",$iData, $condition)) {
            if (!empty($post['ph_country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['ph_country_auto_val'],
                    'id'=>$post['ph_id'],
                    'type'=>'photo'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['ph_id'],
                    'type'=>'photo'
                ));
            }
            if (!empty($post['ph_champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['ph_champ_auto_val'],
                    'id'=>$post['ph_id'],
                    'type'=>'photo'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['ph_id'],
                    'type'=>'photo'
                ));
            }
            return true;
        }
        return false;
    }

    public function resetGalMainPhotos($phg_id=0){
        $phg_id = intval($phg_id);
        if ($phg_id > 0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_gallery_id = '$phg_id'");
            if ($temp){
                $iData = array(
                    "ph_type_main"=>'no'
                );
                $condition = array(
                    "ph_gallery_id"=>$phg_id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."photos",$iData, $condition)) return true;
                else return false;
            }
        }else return false;
    }

    private function fileName($type='files', $folder='/', $file_name = '', $corrector=0){
        $corrector = intval($corrector);
        if ($corrector>0){
            $p = strpos($file_name, '.');
            $new_file_name = substr($file_name, 0, $p)."_".$corrector.substr($file_name, $p);
        } else $new_file_name = $file_name;
        $dir = opendir ("../upload/".$type.$folder);
        $flag = 0;
        while ($file = readdir ($dir)) {
            if($file == $new_file_name) $flag = 1;
        }
        closedir ($dir);
        if ($flag == 1) {
            $corrector++;
            return $this->fileName($type, $folder, $file_name, $corrector);
        }else return $new_file_name;
    }

    public function deletePhoto($ph_id=0){
        $ph_id = intval($ph_id);
        if ($ph_id>0){
            $old_image = $this->hdl->selectElem(DB_T_PREFIX."photos","*","ph_id = '$ph_id' LIMIT 0, 1");
            if ($old_image[0]['ph_folder'] == '') $old_image[0]['ph_folder'] = '/';
            if (file_exists ("../upload/photos".$old_image[0]['ph_folder'].$old_image[0]['ph_path'])) unlink("../upload/photos".$old_image[0]['ph_folder'].$old_image[0]['ph_path']);
            $o_file = substr($old_image[0]['ph_path'], 0, strlen(strrchr($old_image[0]['ph_path'], "."))*(-1))."-informer".strrchr($old_image[0]['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image[0]['ph_folder'].$o_file)) unlink("../upload/photos".$old_image[0]['ph_folder'].$o_file);
            $o_file = substr($old_image[0]['ph_path'], 0, strlen(strrchr($old_image[0]['ph_path'], "."))*(-1))."-small".strrchr($old_image[0]['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image[0]['ph_folder'].$o_file)) unlink("../upload/photos".$old_image[0]['ph_folder'].$o_file);
            $o_file = substr($old_image[0]['ph_path'], 0, strlen(strrchr($old_image[0]['ph_path'], "."))*(-1))."-main".strrchr($old_image[0]['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image[0]['ph_folder'].$o_file)) unlink("../upload/photos".$old_image[0]['ph_folder'].$o_file);
            $o_file = substr($old_image[0]['ph_path'], 0, strlen(strrchr($old_image[0]['ph_path'], "."))*(-1))."-s_main".strrchr($old_image[0]['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image[0]['ph_folder'].$o_file)) unlink("../upload/photos".$old_image[0]['ph_folder'].$o_file);
            $o_file = substr($old_image[0]['ph_path'], 0, strlen(strrchr($old_image[0]['ph_path'], "."))*(-1))."-med".strrchr($old_image[0]['ph_path'], ".");
            if (file_exists ("../upload/photos".$old_image[0]['ph_folder'].$o_file)) unlink("../upload/photos".$old_image[0]['ph_folder'].$o_file);

            $this->hdl->delElem(DB_T_PREFIX."photos", "ph_id = '$ph_id' LIMIT 1");
            // delete connections
            $this->_deleteConnectionCountry(array(
                'id'=>$ph_id,
                'type'=>'photo'
            ));
            $this->_deleteConnectionChamp(array(
                'id'=>$ph_id,
                'type'=>'photo'
            ));
            $this->updateCountPhotoGallery($old_image[0]['ph_gallery_id']);
            return true;
        }
        return false;
    }

    public function resizePhoto($file = false, $type = '', $main = 'no', $text = ''){
        $text = trim($text);
        $is_informer = false ; // вкл./откл. делать информер
        $is_medium = true ; // вкл./откл. делать средний размер
        $is_main = false ; // вкл./откл. делать картинку для главной
        if (!$file) return false;
        if ($type == '') return false;
        if ($main == 'yes') $main = true;
        else $main = false;
        // максимальный размер большой картинки
        $max_w = 1000;
        $max_h = 800;
        $max = 900;
        // максимальный размер средней картинки
        $max_m = 220;
        // максимальная ширина главной картинки
        $main_x = 220;
        // размеры превьюхи
        $p_x = 64;
        $p_y = 64;
        // размеры информера
        $i_x = 50;
        $i_y = 50;
        $image_size = getimagesize($file);
        if ($image_size[0] == 0 or $image_size[1] == 0) return false;

        // JPG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == 'image/jpeg' or $type == 'image/jpg') $im = imagecreatefromjpeg($file);
        // GIF /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == 'image/gif') $im = ImageCreateFromGif($file);
        // PNG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == 'image/png') $im = imagecreatefrompng($file);
        if ($im){
            // пережимаем превью
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
            imagealphablending($nim, false);
            imagesavealpha($nim,true);
//				$transparent = imagecolorallocatealpha($nim, 255, 255, 255, 127);
//				imagefilledrectangle($nim, 0, 0, $p_x, $p_y, $white);
            if ($image_size[0]<$p_x) $p_x = $image_size[0];
            if ($image_size[1]<$p_y) $p_y = $image_size[1];
            imagecopyresampled($nim, $im, $dest_x, $dest_y, $src_x, $src_y, $p_x, $p_y, $s_x, $s_y);
            $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-small".strrchr($file, ".");
            if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
            if ($type == 'image/gif') imagegif($nim, $n_file);
            if ($type == 'image/png') imagepng($nim, $n_file);
            imagedestroy($nim);

            // пережимаем информер
            if ($is_informer) {
                $i_aspect = $i_x / $i_y;
                $aspect = $image_size[0] / $image_size[1];
                if ($aspect >= $i_aspect){
                    $s_y = $image_size[1];
                    $s_x = round($s_y*$i_aspect);
                    $src_y = 0;
                    $src_x = round(($image_size[0]-$s_x)/2);
                } else {
                    $s_x = $image_size[0];
                    $s_y = round($s_x/$i_aspect);
                    $src_x = 0;
                    $src_y = round(($image_size[1]-$s_y)/2);
                }
                if ($image_size[0]>=$i_x) $dest_x = 0;
                else $dest_x = round(($i_x - $image_size[0]) / 2);
                if ($image_size[1]>=$i_y) $dest_y = 0;
                else $dest_y = round(($i_y - $image_size[1]) / 2);
                $nim = imagecreatetruecolor($i_x,$i_y);
                imagealphablending($nim, false);
                imagesavealpha($nim,true);
//					$transparent = imagecolorallocatealpha($nim, 255, 255, 255, 127);
//					imagefilledrectangle($nim, 0, 0, $i_x, $i_y, $white);
                if ($image_size[0]<$i_x) $i_x = $image_size[0];
                if ($image_size[1]<$i_y) $i_y = $image_size[1];
                imagecopyresampled($nim, $im, $dest_x, $dest_y, $src_x, $src_y, $i_x, $i_y, $s_x, $s_y);
                $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-informer".strrchr($file, ".");
                if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
                if ($type == 'image/gif') imagegif($nim, $n_file);
                if ($type == 'image/png') imagepng($nim, $n_file);
                imagedestroy($nim);
            }

            // пережимаем главную картинку
            if ($main and $is_main) {
                $aspect = $image_size[0] / $image_size[1];
                $main_y = $main_x/$aspect;
                $nim = imagecreatetruecolor($main_x,$main_y);
                imagealphablending($nim, false);
                imagesavealpha($nim,true);
//					$transparent = imagecolorallocatealpha($nim, 255, 255, 255, 127);
                imagecopyresampled($nim, $im, 0, 0, 0, 0, $main_x, $main_y, $image_size[0], $image_size[1]);
                $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-s_main".strrchr($file, ".");
                if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
                if ($type == 'image/gif') imagegif($nim, $n_file);
                if ($type == 'image/png') imagepng($nim, $n_file);
                imagedestroy($nim);
            }

            // пережимаем среднюю картинку
            if ($is_medium) {
                if ($max_m>$image_size[0]) $max_m = $image_size[0];
                $aspect = $image_size[0] / $image_size[1];
                $max_m_y = $max_m/$aspect;
                $nim = imagecreatetruecolor($max_m,$max_m_y);
                imagealphablending($nim, false);
                imagesavealpha($nim,true);
//					$transparent = imagecolorallocatealpha($nim, 255, 255, 255, 127);
                imagecopyresampled($nim, $im, 0, 0, 0, 0, $max_m, $max_m_y, $image_size[0], $image_size[1]);
                $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-med".strrchr($file, ".");
                if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
                if ($type == 'image/gif') imagegif($nim, $n_file);
                if ($type == 'image/png') imagepng($nim, $n_file);
                imagedestroy($nim);
            }

            // пережимаем большую копию
            if ($image_size[0]>$max_w or $image_size[1]>$max_h) {
                if ($image_size[0]>=$image_size[1]) {
                    $aspect = $image_size[1] / $image_size[0];
                    $nw = $max_w;
                    $nh = round($nw * $aspect);
                    if ($nh>$max_h){
                        $aspect = $image_size[0] / $image_size[1];
                        $nh = $max_h;
                        $nw = round($nh * $aspect);
                    }
                } else {
                    $aspect = $image_size[0] / $image_size[1];
                    $nh = $max_h;
                    $nw = round($nh * $aspect);
                    if ($nw>$max_w){
                        $aspect = $image_size[1] / $image_size[0];
                        $nw = $max_w;
                        $nh = round($nw * $aspect);
                    }
                }
            } else {
                $nw = $image_size[0];
                $nh = $image_size[1];
            }
            $nim = imagecreatetruecolor($nw,$nh);
            imagealphablending($nim, false);
            imagesavealpha($nim,true);
//				$transparent = imagecolorallocatealpha($nim, 255, 255, 255, 127);
            imagecopyresampled($nim, $im, 0,0,0,0,$nw,$nh,$image_size[0],$image_size[1]);

            if ($text != ''){
                $fontsize = 10; // размер шрифта
                // шрифт
                $font = 'fonts/arial.ttf';
                // цвет
                $white = imagecolorallocate($nim, 255, 255, 255);
                $grey = imagecolorallocate($nim, 128, 128, 128);
                $black = imagecolorallocate($nim, 0, 0, 0);
                $transparent_black = imagecolorallocatealpha($nim, 0,0,0, 50);
                $transparent_white = imagecolorallocatealpha($nim, 255,255,255, 50);
                // текст
                $text_a = explode ( "\n", $text );
                for ($i=0; $i<count($text_a); $i++){
                    $text_a[$i] = trim($text_a[$i]);
                    $text_a[$i] = iconv("cp1251", "UTF-8", $text_a[$i]);
                    $text_size = imageftbbox($fontsize, 0, $font, $text_a[$i]);
                    $x_text = $nw - $text_size[2] - 10;
                    $y_text = $nh - ceil($fontsize*1.3)*count($text_a) + ceil($fontsize*1.3)*$i + 5;
                    // тенька
                    imagettftext($nim, $fontsize, 0, $x_text+1, $y_text+1, $transparent_black, $font, $text_a[$i]);
                    // текст
                    imagettftext($nim, $fontsize, 0, $x_text, $y_text, $transparent_white, $font, $text_a[$i]);
                }
            }
            if ($image_size[0]>$max_w or $image_size[1]>$max_h or $text != '') {
                if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $file);
                if ($type == 'image/gif') imagegif($nim, $file);
                if ($type == 'image/png') imagepng($nim, $file);
            }
            imagedestroy($nim);

        }
    }

    public function drawTextPhoto($file = false, $text = ''){
        $text = trim($text);
        if ($text == '') return false;
        $text = iconv("UTF-8", "cp1251", $text).' ла-ла';
        if (!$file) return false;
        $image_size = getimagesize($file);
        if ($image_size[0] == 0 or $image_size[1] == 0) return false;

        // JPG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($image_size['mime'] == 'image/jpeg' or $image_size['mime'] == 'image/jpg') $im = imagecreatefromjpeg($file);
        // GIF /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($image_size['mime'] == 'image/gif') $im = ImageCreateFromGif($file);
        // PNG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($image_size['mime'] == 'image/png') $im = imagecreatefrompng($file);
        if ($im){
            $nw = $image_size[0];
            $nh = $image_size[1];
            $nim = imagecreatetruecolor($nw,$nh);
            imagecopyresampled($nim, $im, 0,0,0,0,$nw,$nh,$image_size[0],$image_size[1]);

            $fontsize = 10; // размер шрифта
            // шрифт
            $font = 'fonts/arial.ttf';
            // цвет
            $white = imagecolorallocate($nim, 255, 255, 255);
            $grey = imagecolorallocate($nim, 128, 128, 128);
            $black = imagecolorallocate($nim, 0, 0, 0);
            $transparent_black = imagecolorallocatealpha($nim, 0,0,0, 50);
            $transparent_white = imagecolorallocatealpha($nim, 255,255,255, 50);
            // текст
            $text_a = explode ( "\n", $text );
            for ($i=0; $i<count($text_a); $i++){
                $text_a[$i] = trim($text_a[$i]);
                $text_a[$i] = iconv("cp1251", "UTF-8", $text_a[$i]);
                $text_size = imageftbbox($fontsize, 0, $font, $text_a[$i]);
                $x_text = $nw - $text_size[2] - 10;
                $y_text = $nh - ceil($fontsize*1.3)*count($text_a) + ceil($fontsize*1.3)*$i + 5;
                // тенька
                imagettftext($nim, $fontsize, 0, $x_text+1, $y_text+1, $transparent_black, $font, $text_a[$i]);
                // текст
                imagettftext($nim, $fontsize, 0, $x_text, $y_text, $transparent_white, $font, $text_a[$i]);
            }
            unlink($file);
            if ($image_size['mime'] == 'image/jpeg' or $image_size['mime'] == 'image/jpg') imagejpeg($nim, $file);
            if ($image_size['mime'] == 'image/gif') imagegif($nim, $file);
            if ($image_size['mime'] == 'image/png') imagepng($nim, $file);
            imagedestroy($nim);
            imagedestroy($im);
        }
    }

    public function resizeMainPhoto($file = false, $type = ''){
        if (!$file) return false;
        if ($type == '') return false;

        $type = strtolower($type);
        // максимальная ширина главной картинки
        $main_x = 480;
        $image_size = getimagesize($file);

        // JPG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == '.jpeg' or $type == '.jpg') $im = imagecreatefromjpeg($file);
        // GIF /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == '.gif') $im = ImageCreateFromGif($file);
        // PNG /////////////////////////////////////////////////////////////////////////////////////////////////
        if ($type == '.png') $im = imagecreatefrompng($file);
        if ($im){
            // пережимаем главную картинку
            $aspect = $image_size[0] / $image_size[1];
            $main_y = $main_x/$aspect;
            $nim = imagecreatetruecolor($main_x,$main_y);
            imagecopyresampled($nim, $im, 0, 0, 0, 0, $main_x, $main_y, $image_size[0], $image_size[1]);
            $n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-s_main".strrchr($file, ".");
            if ($type == '.jpeg' or $type == '.jpg') imagejpeg($nim, $n_file);
            if ($type == '.gif') imagegif($nim, $n_file);
            if ($type == '.png') imagepng($nim, $n_file);
            imagedestroy($nim);
        }
    }

    public function getTranslit($st, $charset = 'utf-8'){
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

    // SETTINGS
    public function getPhotoSettings(){
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
        $item = $this->hdl->selectElem(DB_T_PREFIX."settings","*","set_name = 'photo_is_informer' LIMIT 0, 1");
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
            "set_name"=> 'photo_is_informer'
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$iData, $condition)) return true;
        else return false;
    }

}