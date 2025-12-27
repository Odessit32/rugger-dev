<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class live extends clientBase{
    protected $hdl;

    public function __construct(){
        parent::__construct();
        $this->hdl = database::getInstance();
    }

    public function getLiveItem($live_address = ''){
        global $month; //   
        global $wday_l; //    

        if (!empty($live_address)) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live",
                "	l_id as id,
                    l_title_".S_LANG." as title,
					l_description_".S_LANG." as description,
					l_text_".S_LANG." as text,
					l_date_show as date,
					l_lc_id,
					l_tags,
					l_is_photo_top,
					l_sign,
					l_sign_url
				","	l_address='$live_address' and
					l_is_active='yes'
					LIMIT 1");
            if ($temp){
                $search = array("'", '"');
                $replace = array('&quot;', '&quot;');
                $temp = $temp[0];
                $temp['title'] = str_replace($search, $replace, stripcslashes($temp['title']));
                $temp['description'] = stripcslashes($temp['description']);
                $temp['description_meta'] = strip_tags($temp['description']);
                $temp['text'] = stripcslashes($temp['text']);
                $temp['photo_main'] = $this->getLivePhotoMain($temp['id']);
                $temp['m'] = $month[date('m', strtotime($temp['date']))];
                $temp['wd'] = $wday_l[date('N', strtotime($temp['date']))];
                $temp['photo_gallery'] = $this->getLivePhotoGallery($temp['id']);
                $temp['video_gallery'] = $this->getLiveVideoGallery($temp['id']);

                $temp['connection_country'] = $this->getConnectionCountry($temp['id'], false, 'live');
                $temp['connection_champ'] = $this->getConnectionChamp($temp['id'], false, 'live');
            }
            return $temp;
        }
        return false;
    }

    public function getLiveList($page=1, $perpage=10, $category = 0, $page_index = 1, $date_show = false){
        global $month; //   
        global $wday; //    
        if ($date_show){
            $date_now = date("Y-m-d 23:59:59", strtotime(substr($date_show, 0, 10)));
        } else {
            $date_now = date("Y-m-d H:i:00");
        }

        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND l_lc_id = '$category' ";
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
        $temp = $this->hdl->selectElem(DB_T_PREFIX."live n",
            "	l_id AS id,
                l_title_".S_LANG." AS title,
			    l_description_".S_LANG." AS description,
			    l_date_show AS date,
			    l_lc_id,
			    l_tags,
			    l_address as address
			","
				l_is_active='yes' AND
				l_description_".S_LANG." != '' AND
				l_date_show < '".$date_now."' AND
				l_top = 0 AND
				l_title_".S_LANG." != ''
				$extra_q
				ORDER BY l_top DESC,
				l_date_show DESC,
				l_id DESC
				LIMIT $limit, $perpage"
            , false, true, 60 );
        if ($temp){
            $search = array("'", '"');
            $replace = array('&quot;', '&quot;');
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = str_replace($search, $replace, stripcslashes($temp[$i]['title']));
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>" );
                $temp[$i]['photo_main'] = $this->getLivePhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
                $temp[$i]['wd'] = $wday[date('N', strtotime($temp[$i]['date']))];
            }
            return $temp;
        } else return false;
    }

    public function getLivePages($page=1, $perpage=10, $category = 0, $page_index = 1){
        global $section_type;
        global $section_type_id;
        $date_now = date("Y-m-d H:i:00");
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND l_lc_id = '$category' ";
        $f_c_extra = '';
        $q_c_extra = '';
        $pages = array();
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON n.l_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'live' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON n.l_id = c.type_id";
                    $q_c_extra = " AND c.type = 'live' AND c.ch_id = '$section_type_id'";
                    break;
            }

            $temp = $this->hdl->selectElem(DB_T_PREFIX."live n ".$f_c_extra,
                "	COUNT(*) as C_N
                ","
					l_is_active='yes' AND
					l_description_".S_LANG." != '' AND
					l_date_show < '".$date_now."' AND
					l_title_".S_LANG." != '' AND
                    l_id = type_id
					$extra_q
					$q_c_extra
				"
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live ",
                "	COUNT(*) as C_N
                ","
					l_is_active='yes' AND
					l_description_".S_LANG." != '' AND
					l_date_show < '".$date_now."' AND
					l_title_".S_LANG." != ''
					$extra_q "
                , false, true, 60 );
        }

        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index<2) $page_index = $perpage;
        if (($temp[0]['C_N']-$page_index) <= 0) return false;
        $c_pages = intval(($temp[0]['C_N']-$page_index)/$perpage)+1;

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i <= $c_pages) $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i < $c_pages) $pages[$i] = $i+1;
                //$pages[9] = "...";
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i < $c_pages) $pages[$i] = $i+1;
                //$pages[$page-6] = "...";
                //if ($page+4 <= $c_pages) $pages[$page+4] = "...";
            }
        }
        if (count($pages)>1) return $pages;
        return false;
    }

    public function getLiveCategories(){
        global $language;
        $list = array(0);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."live_categories",
            "   nc_id,
                nc_title_".S_LANG." as title,
                    nc_address
                ",
            "   nc_is_active='yes'
                ORDER BY nc_id ASC");
        if ($temp){
            $list[0] = array(
                'nc_id'=>0,
                'title'=>$language['All'],
                'nc_address'=>''
            );
            foreach ($temp as $item){
                $item['title'] = stripcslashes($item['title']);
                $list[$item['nc_id']] = $item;
            }
            return $list;
        }
        return false;
    }

    public function getLiveMainOne(){ //         
        global $section_type;
        global $section_type_id;
        $date_now = date("Y-m-d H:i:00");
        $f_c_extra = $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_live_top c ON n.l_id = c.l_id ";
                    $q_c_extra = " AND c.type = 'country' AND c.type_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_live_top c ON n.l_id = c.l_id";
                    $q_c_extra = " AND c.type = 'champ' AND c.type_id = '$section_type_id'";
                    break;
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live n
                                $f_c_extra
							","	n.l_id,
								n.l_title_".S_LANG." AS title,
								n.l_description_".S_LANG." AS description,
								n.l_date_show,
								n.l_lc_id
							","	n.l_is_active='yes' AND
								n.l_date_show < '".$date_now."' AND
								n.l_title_".S_LANG." != ''
                                $q_c_extra
							ORDER BY c.top ASC
							LIMIT 4"
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live
							","	l_id,
								l_title_".S_LANG." AS title,
								l_description_".S_LANG." AS description,
								l_date_show,
								l_lc_id,
								l_top
							","	l_is_active='yes' AND
								l_top != 0 AND
								l_date_show < '".$date_now."' AND
								l_title_".S_LANG." != ''
							ORDER BY l_top ASC
							LIMIT 4"
                , false, true, 60 );
        }
        if ($temp){
            for ($i=0; $i<count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getLivePhotoMain($temp[$i]['l_id']);
            }
        }
        return $temp;
    }

    public function getLiveMainList($limit = 0, $offset = 0, $allsections = false, $category = false){
        global $section_type;
        global $section_type_id;
        $limit = intval($limit);
        if ($limit<1) {
            $limit = 5;
        }
        $offset = intval($offset);
        if ($offset>0) {
            $limit = $offset.', '.$limit;
        }
        $date_now = date("Y-m-d H:i:00");
        $q_category = '';
        $category = intval($category);
        if (!empty($category)){
            $q_category = " AND l_lc_id = '$category'";
        }
        $q_c_extra = $f_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id) && !$allsections){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON n.l_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'live' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON n.l_id = c.type_id";
                    $q_c_extra = " AND c.type = 'live' AND c.ch_id = '$section_type_id'";
                    break;
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live n
                    $f_c_extra
                    LEFT JOIN ".DB_T_PREFIX."photo_gallery phg ON n.l_id = phg.phg_type_id
                    LEFT JOIN ".DB_T_PREFIX."video_gallery vg ON n.l_id = vg.vg_type_id ",
                "	l_id,
                    l_title_".S_LANG." AS title,
					l_description_".S_LANG." AS description,
					l_date_show,
					phg_id,
					phg_ph_count,
					phg_is_active,
					vg_id,
					vg_v_count,
					vg_is_active
					",
                "	(vg_type = 'live' OR vg_type IS NULL) AND
                    (phg_type = 'live' OR phg_type IS NULL) AND
                    l_is_active='yes' AND
                    l_top = '0' AND
                    l_title_".S_LANG." != '' AND
					l_date_show < '".$date_now."' AND
					l_id = type_id AND
					type = 'live'
					$q_c_extra
					$q_category
					ORDER BY l_top DESC,
					l_date_show DESC,
					l_id DESC
					LIMIT $limit"
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."live n
                    LEFT JOIN ".DB_T_PREFIX."photo_gallery g ON n.l_id = g.phg_type_id
                    LEFT JOIN ".DB_T_PREFIX."video_gallery vg ON n.l_id = vg.vg_type_id ",
                "	l_id,
                    l_title_".S_LANG." AS title,
					l_description_".S_LANG." AS description,
					l_date_show,
					phg_id,
					phg_ph_count,
					phg_is_active,
					vg_id,
					vg_v_count,
					vg_is_active",
                "	(vg_type = 'live' OR vg_type IS NULL) AND
                    (phg_type = 'live' OR phg_type IS NULL) AND
                    l_is_active='yes' AND
                    l_top = '0' AND
                    l_title_".S_LANG." != '' AND
					l_date_show < '".$date_now."'
					$q_category
					ORDER BY l_top DESC,
					l_date_show DESC,
					l_id DESC
					LIMIT $limit"
                , false, true, 60 );
        }
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description_'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['description'] = strip_tags($temp[$i]['description_']);
                $temp[$i]['photo_main'] = $this->getLivePhotoMain($temp[$i]['l_id']);
            }
        }
        return $temp;
    }

    private function getLivePhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
                ph_path,
                ph_about_".S_LANG." as ph_about,
                ph_title_".S_LANG." as ph_title,
											ph_folder,
											ph_gallery_id
										","	ph_is_active='yes' AND 
											ph_type_id = '".$id."' AND 
											ph_type = 'live' 
											ORDER BY ph_type_main DESC 
											LIMIT 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_title'] = strip_tags(stripcslashes($temp_photo['ph_title']));
            $temp_photo['ph_about'] = strip_tags(stripcslashes($temp_photo['ph_about']));
            //$temp_photo['ph_main'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_small'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_med'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-med".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_big'] = $temp_photo['ph_folder'].$temp_photo['ph_path'];
            return $temp_photo;
        }
        return false;
    }

    public function getLivePhotoGallery($l_id){
        $l_id = intval($l_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery",
            "	phg_id as id,
                phg_title_".S_LANG." as title,
							phg_description_".S_LANG." as description,
							phg_datetime_pub as date,
							phg_ph_count
						","	phg_is_active='yes' AND 
							phg_type = 'live' AND 
							phg_type_id = '$l_id'
							LIMIT 0, 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['photos'] = $this->getLivePhotos($temp['id']);
            return $temp;
        } else return false;
    }

    public function getLivePhotos($id, $limit=0){
        $q_limit = '';
        $limit = intval($limit);
        if ($limit>0) $q_limit = ' LIMIT 0, '.$limit;
        $id = intval($id);
        if ($id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
                ph_path,
                ph_about_".S_LANG." as ph_about,
                ph_title_".S_LANG." as ph_title,
											ph_folder,
											ph_gallery_id",
            "	ph_is_active='yes' AND
                ph_gallery_id = '".$id."'
										ORDER BY ph_order DESC, 
											ph_id ASC
											$q_limit");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_small'] = $temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_med'] = $temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-med".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_inf'] = $temp[$i]['ph_folder'].substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-informer".strrchr($temp[$i]['ph_path'], ".");
                $temp[$i]['ph_big'] = $temp[$i]['ph_folder'].$temp[$i]['ph_path'];
            }
            return $temp;
        } else return false;
    }

    public function getLiveVideoGallery($l_id){
        $l_id = intval($l_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery",
            "	vg_id as id,
                vg_title_".S_LANG." as title,
							vg_description_".S_LANG." as description,
							vg_datetime_pub as date,
							vg_v_count
						","	vg_is_active='yes' AND 
							vg_type = 'live' AND 
							vg_type_id = '$l_id'
							LIMIT 0, 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = strip_tags(stripcslashes($temp['title']));
            $temp['description'] = strip_tags(stripcslashes($temp['description']));
            $temp['videos'] = $this->getLiveVideos($temp['id']);
        }
        return $temp;
    }

    public function getLiveVideos($id){
        $id = intval($id);
        if ($id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos
						","	v_id,
							v_code,
							v_code_text,
							v_title_".S_LANG." as title,
							v_about_".S_LANG." as v_about,
							v_folder
						","	v_is_active='yes' and 
							v_gallery_id = '$id'
							ORDER BY v_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['v_code_text'] = stripcslashes($temp[$i]['v_code_text']);
                $temp[$i]['v_title'] = strip_tags(stripcslashes($temp[$i]['v_title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
                $temp[$i]['v_small'] = $temp[$i]['v_folder'].$temp[$i]['v_id']."-small.jpg";
            }
        }
        return $temp;
    }

    public function getLiveCategoryItem($id = 0){
        $id = intval($id);
        if ($id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."live_categories",
            "	nc_id as id,
                nc_title_".S_LANG." as title,
										nc_address as adress
									","nc_id = $id LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
        }
        return $temp;
    }

    public function getLiveMenu($c_id = 0){
        $c_id = intval($c_id);
        //  
        if (LANG == 'rus') $item['title'] = " ";
        if (LANG == 'ukr') $item['title'] = " ";
        if (LANG == 'eng') $item['title'] = "All live";
        $item['adress'] = '';
        if ($c_id == 0) $item['active'] = 'yes';
        else $item['active'] = 'no';
        $res[] = $item;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."live_categories",
            "	nc_id as id,
                nc_title_".S_LANG." as title,
										nc_address as adress
									","nc_is_active='yes' ORDER BY nc_order DESC, nc_title_ru ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                if ($temp[$i]['id'] == $c_id) $temp[$i]['active'] = 'yes';
                else $temp[$i]['active'] = 'no';
                $res[] = $temp[$i];
            }
            $r[] = $res;
            return $r;
        }
        return false;
    }



























    public function getLiveMainPageList($c_live = 2, $category = 0){
        global $month; //   

        $category = intval($category);
        if ($category >0) $q_categ = " AND l_lc_id = '$category' AND l_lc_main = 'no'";
        else $q_categ = " AND l_lc_id != '3' AND l_lc_main = 'no'";
        $c_live = intval($c_live);
        if ($c_live<1) $c_live = 2;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."live",
            "	l_id as id,
                l_title_".S_LANG." as title,
										l_description_".S_LANG." as description,
										l_date_show as date
									",
            "	l_is_active='yes' AND l_title_".S_LANG." != '' AND l_description_".S_LANG." != '' $q_categ AND ((l_lc_id != '3' AND l_date_show < NOW()) OR l_lc_id = '3') AND l_is_main = 'no' ORDER BY l_is_main DESC, l_date_show DESC LIMIT 0, $c_live");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getLivePhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
            }
            return $temp;
        } else return false;
    }

    public function getLiveMainPageMain(){
        global $month; //   

        $temp = $this->hdl->selectElem(DB_T_PREFIX."live",
            "	l_id as id,
                l_title_".S_LANG." as title,
										l_description_".S_LANG." as description,
										l_text_".S_LANG." as text,
										l_date_show as date
									",
            "	l_is_active='yes' AND
                l_main > 0
            ORDER BY l_main ASC,
                l_date_show DESC
            LIMIT 4");
        if ($temp){
            for ($i=0; $i<count($temp); $i++ ){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['text'] = stripcslashes($temp[$i]['text']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
                $temp[$i]['photo_main'] = $this->getLivePhotoMain($temp[$i]['id']);
            }
        }
        return $temp;
    }

    public function getMediaMainPageList($c_media = 2, $category = 0, $title_photo = '', $title_video = ''){
        global $month; //   

        $category = intval($category);
        $c_media = intval($c_media);
        if ($c_media<1) $c_media = 2;

        $res = $r = false;
        if ($category == 0 or $category == 1){
            $photo = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery, ".DB_T_PREFIX."photos",
                "	phg_id as id,
                    phg_title_".S_LANG." as title,
										phg_description_".S_LANG." as description,
										phg_datetime_pub as date,
										ph_path,
										ph_folder
									",
                "	ph_gallery_id = phg_id AND
                    ph_is_active='yes' AND
                    ph_is_informer = 'yes' AND
                    phg_is_active = 'yes' AND
                    phg_is_informer = 'yes'
                    GROUP BY phg_id
                    ORDER BY phg_datetime_pub DESC,
                    phg_title_".S_LANG." ASC
										LIMIT 0, $c_media");
            if ($photo){
                foreach ($photo as $item){
                    $item['type'] = 'photo';
                    $item['type_adress'] = 'media/photo';
                    $item['type_title'] = $title_photo;
                    $item['title'] = stripcslashes($item['title']);
                    $item['description'] = stripcslashes($item['description']);
                    $item['m'] = $month[date('m', strtotime($item['date']))];
                    $item['photo'] = "upload/photos/".$item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
                    $item['time'] = strtotime($item['date']);
                    $res[] = $item;
                }
            }
        }
        if ($category == 0 or $category == 2){
            $video = $this->hdl->selectElem(DB_T_PREFIX."video_gallery, ".DB_T_PREFIX."videos",
                "	vg_id as id,
                    vg_title_".S_LANG." as title,
										vg_description_".S_LANG." as description,
										vg_datetime_pub as date,
										v_id,
										v_code
									",
                "	v_gallery_id = vg_id AND
                    v_is_active='yes' AND
                    vg_is_active = 'yes' AND
                    vg_is_informer = 'yes'
                    GROUP BY vg_id
                    ORDER BY vg_datetime_pub DESC,
                    vg_title_".S_LANG." ASC
										LIMIT 0, $c_media");
            if ($video){
                foreach ($video as $item){
                    $item['type'] = 'video';
                    $item['type_adress'] = 'media/video';
                    $item['type_title'] = $title_video;
                    $item['title'] = stripcslashes($item['title']);
                    $item['description'] = stripcslashes($item['description']);
                    $item['m'] = $month[date('m', strtotime($item['date']))];
                    $item['photo'] = "upload/video_thumbs/".$item['v_id']."-small.jpg";
                    $item['time'] = strtotime($item['date']);
                    $res[] = $item;
                }
            }
        }
        // sorting
        if ($res){
            $prev = array();
            for($j=0; $j<count($res); $j++){
                for($i=0; $i<count($res); $i++){
                    if (isset($res[$i]) and $res[$i]['id']>0){
                        if ($i>0) {
                            if ($res[$i]['time'] > $prev['time']) {
                                $res[$i-1] = $res[$i];
                                $res[$i] = $prev;
                            } else {
                                $prev = $res[$i];
                            }
                        } else {
                            $prev = $res[$i];
                        }
                    }
                }
            }
            for ($i=0; $i<$c_media; $i++){
                if (isset($res[$i]) and $res[$i]['id']>0) $r[] = $res[$i];
            }
        }

        return $r;
    }

    public function getLivePhoto($l_id, $limit=0){
        $q_limit = '';
        $limit = intval($limit);
        if ($limit>0) $q_limit = ' LIMIT 0, '.$limit;
        $l_id = intval($l_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
                ph_path,
                ph_about_".S_LANG." as ph_about,
                ph_title_".S_LANG." as ph_title,
											ph_folder,
											ph_gallery_id",
            "	ph_is_active='yes' and
                ph_type_id = '".$l_id."' AND
											ph_type = 'live' and 
											ph_type_main = 'no' 
											ORDER BY ph_id ASC 
											$q_limit");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_small'] = substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
            }
            return $temp;
        } else return false;
    }

    public function getLiveVideo($l_id){
        $l_id = intval($l_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos","
							v_id,
							v_code,
							v_title_".S_LANG." as v_title,
							v_about_".S_LANG." as v_about,
							v_folder,
							v_gallery_id",
            "	v_is_active='yes' and
                v_type_id = '".$l_id."' AND
							v_type = 'live' 
						ORDER BY v_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['v_title'] = strip_tags(stripcslashes($temp[$i]['v_title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
            }
            return $temp;
        } else return false;
    }

    public function getInformerLive($c_n=0, $category_display=0, $category_not_display = 0){
        global $month; //   

        $c_n = intval($c_n);
        $category_display = intval($category_display);
        $category_not_display = intval($category_not_display);

        $extra_q = '';
        if ($category_display > 0) $extra_q = " AND l_lc_id = '$category_display' ";
        else{
            if ($category_not_display > 0) $extra_q = " AND l_lc_id != '$category_not_display'";
        }
        if ($c_n < 1) $c_n = 5;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."live",
            "	l_id as id,
                l_title_".S_LANG." as title,
										l_description_".S_LANG." as description,
										l_date_show as date,
										l_is_main,
										l_lc_id,
										l_tags,
										l_lc_main
									","	l_is_active='yes' AND
										l_description_".S_LANG." != ''
										$extra_q 
										ORDER BY l_date_show DESC, l_id DESC LIMIT $c_n");
        //AND l_is_main = 'no'
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>" );
                $temp[$i]['photo_main'] = $this->getLivePhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
            }
            return $temp;
        }
        return false;
    }

    public function getInformerLiveWoPh($c_n=0, $category_display=0, $category_not_display = 0){
        global $month; //   

        $c_n = intval($c_n);
        $category_display = intval($category_display);
        $category_not_display = intval($category_not_display);

        $extra_q = '';
        if ($category_display > 0) $extra_q = " AND l_lc_id = '$category_display' ";
        else{
            if ($category_not_display > 0) $extra_q = " AND l_lc_id != '$category_not_display'";
        }
        if ($c_n < 1) $c_n = 5;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."live",
            "	l_id as id,
                l_title_".S_LANG." as title,
										l_description_".S_LANG." as description,
										l_date_show as date,
										l_is_main,
										l_lc_id,
										l_tags,
										l_lc_main
									","	l_is_active='yes' AND
										l_description_".S_LANG." != ''
										$extra_q 
										ORDER BY l_date_show DESC, l_id DESC LIMIT $c_n");
        //AND l_is_main = 'no'
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>" );
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
            }
            return $temp;
        }
        return false;
    }

}

