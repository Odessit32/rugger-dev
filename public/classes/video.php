<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class video{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getVideoCategories(){
        global $language;
        $list = $this->hdl->selectElem(DB_T_PREFIX."video_categories",
            "	vc_id as id,
										vc_title_".S_LANG." as title, 
										vc_address as address
									","	vc_is_active='yes' 
										ORDER BY vc_order DESC,
										vc_title_".S_LANG." ASC,
										vc_id ASC");
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

    public function getGalleryItem($id = 0){
        global $month;
        $id = intval($id);
        if ($id > 0) $extra_q = "AND vg_id = '$id'";
        else $extra_q = "AND vg_v_count > 0 ORDER BY vg_datetime_pub DESC";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery",
            "	vg_id as id,
											vg_title_".S_LANG." as title,
											vg_description_".S_LANG." as description,
											vg_type,
											vg_type_id,
											vg_vc_id,
											vg_datetime_pub,
											vg_v_count",
            "	vg_is_active = 'yes'
											$extra_q
											LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
            $temp['title'] = strip_tags($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['description_meta'] = strip_tags($temp['description']);
            $temp['videos'] = $this->getVideoList($temp['id']);
            $temp['video_count_div'] = count($temp['videos'])/5;
            $temp['m'] = $month[date('m', strtotime($temp['vg_datetime_pub']))];
            $temp['is_hours'] = (date("Hi", strtotime($temp['vg_datetime_pub'])) == 0) ? false : true;
            return $temp;
        } else return false;
    }

    public function getGalleryList($page=1, $perpage=10, $category = 0, $vg_id = 0, $page_index = 1){
        global $month; // ������ �������� �������
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON vg.vg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'video_gallery' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON vg.vg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'video_gallery' AND c.ch_id = '$section_type_id'";
                    break;
            }

        }

        $date_now = date("Y-m-d H:i:00");
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND vg_vc_id = '$category' ";
        $vg_id = intval($vg_id);
        if ($vg_id > 0) $extra_q .= " AND vg_id != '$vg_id' ";
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

        $temp_list = $this->hdl->selectElem(DB_T_PREFIX."video_gallery vg ".$f_c_extra,
            "	vg_id as id,
				vg_title_".S_LANG." as title,
				vg_description_".S_LANG." as description,
				vg_type,
				vg_type_id,
				vg_vc_id,
				vg_datetime_pub,
				vg_v_count",
            "	vg_v_count > 0 AND
                vg_type NOT IN ('team', 'staff') AND
				vg_is_active = 'yes' AND
				vg_datetime_pub < '$date_now'
				$extra_q
				$q_c_extra
				ORDER BY vg_id DESC
				LIMIT $limit, $perpage"
            , false, true, 60 );
        if ($temp_list){
            for ($i=0; $i<count($temp_list); $i++){
                $temp = $this->hdl->selectElem(DB_T_PREFIX."videos",
                    "	v_id,
						v_code,
						v_title_".S_LANG." as v_title,
						v_about_".S_LANG." as v_about,
						v_folder",
                    "	v_is_active = 'yes' AND
						v_gallery_id = '".$temp_list[$i]['id']."'
						ORDER BY v_id ASC
						LIMIT 1");
                if ($temp) {
                    $temp = $temp[0];
                    $temp['v_title'] = strip_tags(stripcslashes($temp['v_title']));
                    $temp['v_about'] = strip_tags(stripcslashes($temp['v_about']));
                    $temp_list[$i]['title'] = strip_tags(stripcslashes($temp_list[$i]['title']));
                    $temp_list[$i]['description'] = stripcslashes($temp_list[$i]['description']);
                    $temp_list[$i]['m'] = $month[date('m', strtotime($temp_list[$i]['vg_datetime_pub']))];
                    $temp_list[$i]['vg_med'] = $temp['v_folder'].$temp['v_id']."-med.jpg";
                    $temp_list[$i]['vg_small'] = $temp['v_folder'].$temp['v_id']."-small.jpg";
                    $temp_list[$i]['is_hours'] = (date("Hi", strtotime($temp_list[$i]['vg_datetime_pub'])) == 0) ? false : true;
                    $list[] = $temp_list[$i];
                }
            }
            if (count($list)>0) return $list;
            else return false;
        } else return false;
    }

    public function getGalleryPages($page=1, $perpage=10, $category = 0, $vg_id = 0, $page_index = 1){
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON vg.vg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'video_gallery' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON vg.vg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'video_gallery' AND c.ch_id = '$section_type_id'";
                    break;
            }

        }
        $date_now = date("Y-m-d H:i:00");
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND vg_vc_id = '$category' ";
        $vg_id = intval($vg_id);
        if ($vg_id > 0) $extra_q .= " AND vg_id != '$vg_id' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery vg ".$f_c_extra,"DISTINCT vg_id, COUNT(*) as C_N",
            "	vg_is_active='yes' and
                vg_type NOT IN ('team', 'staff') AND
				vg_datetime_pub < '$date_now'
				$extra_q
				$q_c_extra "
            , false, true, 60 );
        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index<2) $page_index = $perpage;
//            $c_pages = intval(($temp[0]['C_N']-$page_index)/$perpage)+1;
        $c_pages = intval(($temp[0]['C_N']-1)/$perpage)+1;
        if ($c_pages <1) {
            return false;
        }
        $pages = [];
        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i < $c_pages) $pages[$i] = $i+1;
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

    public function getVideoList($gallery = 0){
        $extra_q = '';
        $gallery = intval($gallery);
        if ($gallery>0) $extra_q .= " AND v_gallery_id = '$gallery' ";
        else return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos",
            "	v_id,
				v_code,
				v_title_".S_LANG." as v_title,
				v_about_".S_LANG." as v_about,
				v_code_text,
				v_folder",
            "v_is_active='yes' $extra_q ORDER BY v_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['v_title'] = strip_tags(stripcslashes($temp[$i]['v_title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
                $temp[$i]['v_code_text'] = stripcslashes($temp[$i]['v_code_text']);
                $temp[$i]['v_small'] = $temp[$i]['v_folder'].$temp[$i]['v_id']."-small.jpg";
                $temp[$i]['v_med'] = $temp[$i]['v_folder'].$temp[$i]['v_id']."-med.jpg";
                $temp[$i]['v_big'] = $temp[$i]['v_folder'].$temp[$i]['v_id'].".jpg";
            }
            return $temp;
        }
        return false;
    }

    public function getVideoMenu(&$list, $c_id = 0){
        $c_id = intval($c_id);
        if ($list){
            for ($i=0; $i<count($list); $i++){
                if ($list[$i]['id'] == $c_id) $list[$i]['active'] = 'yes';
                else $list[$i]['active'] = 'no';
            }
        }
    }

}

