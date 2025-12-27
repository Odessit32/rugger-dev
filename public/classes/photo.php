<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class photo{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getPhotoGalleryItem($phg_id = 0){
        global $month; // массив названий мес€цев
        $phg_id = intval($phg_id);
        if ($phg_id > 0) $extra_q = "AND phg_id = '$phg_id'";
        else $extra_q = "AND phg_ph_count > 0 ORDER BY phg_datetime_pub DESC";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery",
            "	phg_id as id,
											phg_title_".S_LANG." as title,
											phg_description_".S_LANG." as description,
											phg_type,
											phg_type_id,
											phg_phc_id,
											phg_datetime_pub,
											phg_ph_count",
            "	phg_is_active = 'yes'
											$extra_q
											LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = strip_tags(stripcslashes($temp['title']));
            $temp['description'] = stripcslashes($temp['description']);
            $temp['description_meta'] = strip_tags($temp['description']);
            $temp['photos'] = $this->getPhotoList($temp['id']);
            $temp['photos_count_div'] = count($temp['photos'])/5;
            $temp['m'] = $month[date('m', strtotime($temp['phg_datetime_pub']))];
            $temp['is_hours'] = (date("Hi", strtotime($temp['phg_datetime_pub'])) == 0) ? false : true;
            return $temp;
        } else return false;
    }

    public function getPhotoList($gallery = 0){
        $extra_q = '';
        $gallery = intval($gallery);
        if ($gallery>0) $extra_q .= " AND ph_gallery_id = '$gallery' ";
        else return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
							ph_path,
							ph_about_".S_LANG." as ph_about,
							ph_title_".S_LANG." as ph_title,
							ph_folder,
							ph_gallery_id
							",
            "ph_is_active='yes' $extra_q ORDER BY ph_order DESC, ph_id DESC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_small'] = $temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_med'] = $temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-med".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_big'] = $temp[$i]['ph_folder'].$temp[$i]['ph_path'];
            }
            return $temp;
        }
        return false;
    }

    public function getPhotoCategories(){
        global $language;
        $list = $this->hdl->selectElem(DB_T_PREFIX."photo_categories",
            "	phc_id as id,
										phc_title_".S_LANG." as title, 
										phc_address as address
									","	phc_is_active='yes' 
										ORDER BY phc_title_".S_LANG." ASC, 
										phc_id ASC");
        if ($list){
            $res['list'][] = array(
                'id'=>0,
                'title'=>$language['All'],
                'address'=>''
            );
            foreach ($list as $item){
                $item['title'] = stripcslashes($item['title']);
                $by_id[$item['id']] = $item;
                $res['list'][] = $item;
            }
            $res['by_id'] = $by_id;
            return $res;
        }
        return false;
    }

    public function getGalleryList($page = 1, $perpage = 10, $category = 0, $phg_id = 0, $page_index = 1){
        global $month; // массив названий мес€цев
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON phg.phg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'photo_gallery' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON phg.phg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'photo_gallery' AND c.ch_id = '$section_type_id'";
                    break;
            }

        }

        $date_now = date("Y-m-d H:i:00");
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q .= " AND phg_phc_id = '$category' ";
        $phg_id = intval($phg_id);
        if ($phg_id > 0) $extra_q .= " AND phg_id != '$phg_id' ";
        $page--;
        if ($page<0) $page = 0;
        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index<2) $page_index = $perpage;
        if ($page == 0) {
            $perpage = $page_index;
            $limit = $page*$perpage;
        } else {
            $limit = ($page-1)*$perpage+$page_index;
        }

        $temp_list = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery phg ".$f_c_extra,
            "	phg_id as id,
				phg_title_".S_LANG." as title,
				phg_description_".S_LANG." as description,
				phg_type,
				phg_type_id,
				phg_phc_id,
				phg_datetime_pub,
				phg_ph_count",
            "	phg_ph_count > 0 AND
				phg_is_active = 'yes' AND
				phg_datetime_pub < '$date_now' AND 
				phg_type NOT IN ('team', 'staff')
				$extra_q
				$q_c_extra
				ORDER BY phg_datetime_pub DESC, phg_id DESC
				LIMIT $limit, $perpage"
            , false, true, 60 );

        if ($temp_list){
            for ($i=0; $i<count($temp_list); $i++){
                $temp = $this->hdl->selectElem(DB_T_PREFIX."photos","
								ph_id,
								ph_path,
								ph_about_".S_LANG." as ph_about,
								ph_title_".S_LANG." as ph_title,
								ph_folder",
                    "	ph_is_active = 'yes' AND
								ph_gallery_id = '".$temp_list[$i]['id']."' 
							ORDER BY ph_id ASC 
							LIMIT 1");
                if ($temp) {
                    $temp_list[$i]['title'] = strip_tags(stripcslashes($temp_list[$i]['title']));
                    $temp_list[$i]['description'] = stripcslashes($temp_list[$i]['description']);
                    $temp_list[$i]['m'] = $month[date('m', strtotime($temp_list[$i]['phg_datetime_pub']))];
                    $temp_list[$i]['phg_ph_title'] = strip_tags(stripcslashes($temp[0]['ph_title']));
                    $temp_list[$i]['phg_ph_about'] = strip_tags(stripcslashes($temp[0]['ph_about']));
                    $temp_list[$i]['phg_ph_path'] = $temp[0]['ph_folder'].$temp[0]['ph_path'];
                    $temp_list[$i]['phg_ph_small'] = $temp[0]['ph_folder'].substr($temp[0]['ph_path'], 0, strlen(strrchr($temp[0]['ph_path'], "."))*(-1))."-small".strrchr($temp[0]['ph_path'], ".");
                    $temp_list[$i]['phg_ph_med'] = $temp[0]['ph_folder'].substr($temp[0]['ph_path'], 0, strlen(strrchr($temp[0]['ph_path'], "."))*(-1))."-med".strrchr($temp[0]['ph_path'], ".");
                    $temp_list[$i]['is_hours'] = (date("Hi", strtotime($temp_list[$i]['phg_datetime_pub'])) == 0) ? false : true;
                    $list[] = $temp_list[$i];
                }
            }
            if (count($list)>0) return $list;
            else return false;
        } else return false;
    }

    public function getGalleryPages($page=1, $perpage=10, $category = 0, $phg_id = 0, $page_index = 1){
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON phg.phg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'photo_gallery' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON phg.phg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'photo_gallery' AND c.ch_id = '$section_type_id'";
                    break;
            }

        }
        $date_now = date("Y-m-d H:i:00");
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q .= " AND phg_phc_id = '$category' ";
        $phg_id = intval($phg_id);
        if ($phg_id > 0) $extra_q .= " AND phg_id != '$phg_id' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery phg ".$f_c_extra,"COUNT(*) as C_N",
            "	phg_is_active = 'yes' AND
                phg_type NOT IN ('team', 'staff') AND
				phg_datetime_pub < '$date_now'
				$extra_q
				$q_c_extra"
            , false, true, 60 );
        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index<2) $page_index = $perpage;
        $c_pages = intval(($temp[0]['C_N']-$page_index)/$perpage)+1;
        if ($c_pages <2) {
            return false;
        }
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
        if (count($pages)>1) return $pages;
        return false;
    }

}
?>
