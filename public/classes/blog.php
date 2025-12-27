<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class BlogClass extends clientBase{
    protected $hdl;

    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getPostItem($item_slug=''){
        global $month; // массив названий месяцев
        global $wday_l; // массив названий дней недели

        if (!empty($item_slug)) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts",
                "	id,
                    title,
					description,
					text,
					date_show,
					is_photo_top,
					sign,
					sign_url,
					address
				","	address='$item_slug' and
					is_active='yes'
					LIMIT 1");
            if ($temp){
                $search = array("'", '"');
                $replace = array('&quot;', '&quot;');
                $temp = $temp[0];
                $temp['title'] = str_replace($search, $replace, stripcslashes($temp['title']));
                $temp['description'] = stripcslashes($temp['description']);
                $temp['description_meta'] = strip_tags($temp['description']);
                $temp['text'] = str_replace(array('mce:script', '_mce_src'), array('script', 'src'), stripcslashes($temp['text']));
                $temp['photo_main'] = $this->getPostPhotoMain($temp['id']);
                $temp['m'] = $month[date('m', strtotime($temp['date_show']))];
                $temp['wd'] = $wday_l[date('N', strtotime($temp['date_show']))];
                $temp['photo_gallery'] = $this->getPostPhotoGallery($temp['id']);
                $temp['video_gallery'] = $this->getPostVideoGallery($temp['id']);

//                $temp['connection_country'] = $this->getConnectionCountry($temp['id'], false, 'blog');
//                $temp['connection_champ'] = $this->getConnectionChamp($temp['id'], false, 'blog');
//                $temp['connected_games'] = $this->getConnectionGame($temp['id'], false, 'blog');
            }
            return $temp;
        }
        return false;
    }

    public function getPostList($page=1, $perpage=10, $category = 0, $page_index = 1, $date_show = false){
        global $month; // массив названий месяцев
        global $wday; // массив названий дней недели
        global $section_type;
        global $section_type_id;
        if ($date_show){
            $date_now = date("Y-m-d 23:59:59", strtotime(substr($date_show, 0, 10)));
        } else {
            $date_now = date("Y-m-d H:i:00");
        }

        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND nc_id = '$category' ";
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
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON bp.id = c.type_id ";
                    $q_c_extra = " AND c.type = 'blog' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON bp.id = c.type_id";
                    $q_c_extra = " AND c.type = 'blog' AND c.ch_id = '$section_type_id'";
                    break;
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts bp
                    $f_c_extra
                    LEFT JOIN ".DB_T_PREFIX."photo_gallery phg ON bp.id = phg.phg_type_id
                    LEFT JOIN ".DB_T_PREFIX."video_gallery vg ON bp.id = vg.vg_type_id ",
                "	id,
                    title,
					description,
					date_show,
					address,
					phg_id,
					phg_ph_count,
					phg_is_active,
					vg_id,
					vg_v_count,
					vg_is_active
				","
				    (vg_type = 'blog' OR vg_type IS NULL) AND
				    (phg_type = 'blog' OR phg_type IS NULL) AND
				    is_active='yes' AND
					description != '' AND
					date_show < '".$date_now."' AND
					title != '' AND
					is_top = 0 AND
                    id = type_id AND
                    type = 'blog'
                    $q_c_extra
					$extra_q
                GROUP BY id
                ORDER BY is_top DESC,
					date_show DESC,
					id DESC
					LIMIT $limit, $perpage"
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts bp
                    LEFT JOIN ".DB_T_PREFIX."photo_gallery g ON bp.id = g.phg_type_id
                    LEFT JOIN ".DB_T_PREFIX."video_gallery vg ON bp.id = vg.vg_type_id ",
                "	id,
                    title,
					description,
					date_show,
					address,
					phg_id,
					phg_ph_count,
					phg_is_active,
					vg_id,
					vg_v_count,
					vg_is_active
				","
				    (vg_type = 'blog' OR vg_type IS NULL) AND
				    (phg_type = 'blog' OR phg_type IS NULL) AND
				    is_active='yes' AND
					description != '' AND
					date_show < '".$date_now."' AND
					is_top = 0 AND
					title != ''
					$extra_q
				GROUP BY id
				ORDER BY is_top DESC,
					date_show DESC,
					id DESC
					LIMIT $limit, $perpage"
                , false, true, 60 );
        }
        if (!empty($temp) && is_array($temp)){
            $search = array("'", '"');
            $replace = array('&quot;', '&quot;');
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = str_replace($search, $replace, stripcslashes($temp[$i]['title']));
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>" );
                $temp[$i]['photo_main'] = $this->getPostPhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date_show']))];
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date_show']));
                $temp[$i]['wd'] = $wday[date('N', strtotime($temp[$i]['date_show']))];
            }
            return $temp;
        } else return false;
    }

    public function getPostPages($page=1, $perpage=10, $category = 0, $page_index = 1, $date_show = false){
        global $section_type;
        global $section_type_id;
        $date_now = date("Y-m-d H:i:00");
        $extra_q = '';
        $category = intval($category);
        if ($category > 0) $extra_q = " AND nc_id = '$category' ";
        $f_c_extra = '';
        $q_c_extra = '';
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON bp.id = c.type_id ";
                    $q_c_extra = " AND c.type = 'blog' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON bp.id = c.type_id";
                    $q_c_extra = " AND c.type = 'blog' AND c.ch_id = '$section_type_id'";
                    break;
            }

            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts bp ".$f_c_extra,
                "	COUNT(*) as C_N
                ","
					is_active='yes' AND
					description != '' AND
					date_show < '".$date_now."' AND
					title != '' AND
                    id = type_id
					$extra_q
					$q_c_extra "
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts ",
                "	COUNT(*) as C_N
                ","
					is_active='yes' AND
					description != '' AND
					date_show < '".$date_now."' AND
					title != ''
					$extra_q "
                , false, true, 60 );
        }

        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        $page_index = intval($page_index);
        if ($page_index<2) $page_index = $perpage;
        if (($temp[0]['C_N']-$page_index) <= 0) return false;
        $c_pages = intval(($temp[0]['C_N']-$page_index)/$perpage)+1;

        $pages = [];
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

    public function getPostCategories(){
        global $language;
        $list = array(0);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."news_categories",
            "   nc_id,
                nc_title_".S_LANG." as title,
                    nc_address
                ",
            "   nc_is_active='yes'
                ORDER BY nc_id ASC");
        if (!empty($temp) && is_array($temp)){
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

    /**
     * Man posts from top posts for index blog page
     *
     * @return array|bool|string
     */
    public function getPostMainOne(){
        global $section_type;
        global $section_type_id;
        $date_now = date("Y-m-d H:i:00");
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_news_top c ON bp.id = c.n_id ";
                    $q_c_extra = " AND c.type = 'country' AND c.type_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_news_top c ON bp.id = c.n_id";
                    $q_c_extra = " AND c.type = 'champ' AND c.type_id = '$section_type_id'";
                    break;
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts bp
                                $f_c_extra
							","	id,
								title,
								description,
								date_show,
	                            address
							","	is_active='yes' AND
								date_show < '".$date_now."' AND
								title != ''
                                $q_c_extra
							ORDER BY is_top ASC
							LIMIT 4"
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts
							","	id,
								title,
								description,
								date_show,
								address,
								is_top
							","	is_active='yes' AND
								is_top != 0 AND
								date_show < '".$date_now."' AND
								title != ''
							ORDER BY is_top ASC
							LIMIT 4"
                , false, true, 60 );
        }
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++) {
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getPostPhotoMain($temp[$i]['id']);
            }
        }
        return $temp;
    }

    public function getPostMainList($limit = 0, $offset = 0, $allsections = false, $category = false){
        global $section_type;
        global $section_type_id;
        $limit = intval($limit);
        if ($limit<1) {
            $limit = 5;
        }
        $offset = intval($offset);
//        if ($offset>0) {
//            $limit = $offset.', '.$limit;
//        }
        $date_now = date("Y-m-d H:i:00");
        $q_category = '';
        $category = intval($category);
        if (!empty($category)){
            $q_category = " AND n_nc_id = '$category'";
        }

        if (!empty($section_type) && !empty($section_type_id) && !$allsections){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON n.n_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'blog' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON n.n_id = c.type_id";
                    $q_c_extra = " AND c.type = 'blog' AND c.ch_id = '$section_type_id'";
                    break;
            }
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts n
                    $f_c_extra
                    LEFT JOIN ".DB_T_PREFIX."photo_gallery phg ON n.n_id = phg.phg_type_id
                    LEFT JOIN ".DB_T_PREFIX."video_gallery vg ON n.n_id = vg.vg_type_id ",
                "	n_id,
                    n_title_".S_LANG." AS title,
					n_description_".S_LANG." AS description,
					n_date_show,
					phg_id,
					phg_ph_count,
					phg_is_active,
					vg_id,
					vg_v_count,
					vg_is_active
					",
                "	(vg_type = 'blog' OR vg_type IS NULL) AND
                    (phg_type = 'blog' OR phg_type IS NULL) AND
                    n_is_active='yes' AND
                    n_top = '0' AND
                    n_title_".S_LANG." != '' AND
					n_date_show < '".$date_now."' AND
					n_id = type_id AND
					type = 'blog'
					$q_c_extra
					$q_category
					GROUP BY n_id
					ORDER BY n_top DESC,
					n_date_show DESC,
					n_id DESC
					LIMIT $offset, $limit"
                , false, true, 60 );
        } else {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."blog_posts n
                    LEFT JOIN ".DB_T_PREFIX."photo_gallery g ON n.n_id = g.phg_type_id
                    LEFT JOIN ".DB_T_PREFIX."video_gallery vg ON n.n_id = vg.vg_type_id ",
                "	n_id,
                    n_title_".S_LANG." AS title,
					n_description_".S_LANG." AS description,
					n_date_show,
					phg_id,
					phg_ph_count,
					phg_is_active,
					vg_id,
					vg_v_count,
					vg_is_active",
                "	(vg_type = 'blog' OR vg_type IS NULL) AND
                    (phg_type = 'blog' OR phg_type IS NULL) AND
                    n_is_active='yes' AND
                    n_top = '0' AND
                    n_title_".S_LANG." != '' AND
					n_date_show < '".$date_now."'
					$q_category
					GROUP BY n_id
					ORDER BY n_top DESC,
					n_date_show DESC,
					n_id DESC
					LIMIT $offset, $limit"
                , false, true, 60 );
        }
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description_'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['description'] = strip_tags($temp[$i]['description_']);
                $temp[$i]['photo_main'] = $this->getPostPhotoMain($temp[$i]['n_id']);
            }
        }
        return $temp;
    }

    private function getPostPhotoMain($id){
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
				ph_type = 'blog'
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

    public function getPostPhotoGallery($n_id){
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery",
            "	phg_id as id,
                phg_title_".S_LANG." as title,
							phg_description_".S_LANG." as description,
							phg_datetime_pub as date,
							phg_ph_count
						","	phg_is_active='yes' AND
							phg_type = 'blog' AND
							phg_type_id = '$n_id'
							LIMIT 0, 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['photos'] = $this->getPostPhotos($temp['id']);
            return $temp;
        } else return false;
    }

    public function getPostPhotos($id, $limit=0){
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
        if (!empty($temp) && is_array($temp)){
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

    public function getPostVideoGallery($n_id){
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."video_gallery",
            "	vg_id as id,
                vg_title_".S_LANG." as title,
							vg_description_".S_LANG." as description,
							vg_datetime_pub as date,
							vg_v_count
						","	vg_is_active='yes' AND
							vg_type = 'blog' AND
							vg_type_id = '$n_id'
							LIMIT 0, 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = strip_tags(stripcslashes($temp['title']));
            $temp['description'] = strip_tags(stripcslashes($temp['description']));
            $temp['videos'] = $this->getPostVideos($temp['id']);
        }
        return $temp;
    }

    public function getPostVideos($id){
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
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['v_code_text'] = stripcslashes($temp[$i]['v_code_text']);
                $temp[$i]['v_title'] = strip_tags(stripcslashes($temp[$i]['v_title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
                $temp[$i]['v_small'] = $temp[$i]['v_folder'].$temp[$i]['v_id']."-small.jpg";
            }
        }
        return $temp;
    }

    public function getPostCategoryItem($id = 0){
        $id = intval($id);
        if ($id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."news_categories",
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

    public function getPostMenu($c_id = 0){
        $c_id = intval($c_id);
        // Все новости
        if (LANG == 'rus') $item['title'] = "Все";
        if (LANG == 'ukr') $item['title'] = "Все";
        if (LANG == 'eng') $item['title'] = "All";
        $item['adress'] = '';
        if ($c_id == 0) $item['active'] = 'yes';
        else $item['active'] = 'no';
        $res[] = $item;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."news_categories",
            "	nc_id as id,
                nc_title_".S_LANG." as title,
										nc_address as adress
									","nc_is_active='yes' ORDER BY nc_order DESC, nc_title_ru ASC");
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['adress'] = $temp[$i]['adress'];
                if ($temp[$i]['id'] == $c_id) $temp[$i]['active'] = 'yes';
                else $temp[$i]['active'] = 'no';
                $res[] = $temp[$i];
            }
            $r[] = $res;
            return $r;
        }
        return false;
    }



























    public function getPostMainPageList($c_news = 2, $category = 0){
        global $month; // массив названий месяцев

        $category = intval($category);
        if ($category >0) $q_categ = " AND n_nc_id = '$category' AND n_nc_main = 'no'";
        else $q_categ = " AND n_nc_id != '3' AND n_nc_main = 'no'";
        $c_news = intval($c_news);
        if ($c_news<1) $c_news = 2;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."news",
            "	n_id as id,
                n_title_".S_LANG." as title,
										n_description_".S_LANG." as description,
										n_date_show as date
									",
            "	n_is_active='yes' AND n_title_".S_LANG." != '' AND n_description_".S_LANG." != '' $q_categ AND ((n_nc_id != '3' AND n_date_show < NOW()) OR n_nc_id = '3') AND n_is_main = 'no' ORDER BY n_is_main DESC, n_date_show DESC LIMIT 0, $c_news");
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getPostPhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
            }
            return $temp;
        } else return false;
    }

    public function getPostMainPageMain($nc_id = 0){
        global $month; // массив названий месяцев

        $temp = $this->hdl->selectElem(DB_T_PREFIX."news",
            "	n_id as id,
                n_title_".S_LANG." as title,
										n_description_".S_LANG." as description,
										n_text_".S_LANG." as text,
										n_date_show as date
									",
            "	n_is_active='yes' AND
                n_main > 0
            ORDER BY n_main ASC,
                n_date_show DESC
            LIMIT 4");
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++ ){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['text'] = stripcslashes($temp[$i]['text']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
                $temp[$i]['photo_main'] = $this->getPostPhotoMain($temp[$i]['id']);
            }
        }
        return $temp;
    }

    public function getMediaMainPageList($c_media = 2, $category = 0, $title_photo = '', $title_video = ''){
        global $month; // массив названий месяцев

        $category = intval($category);
        $c_media = intval($c_media);
        if ($c_media<1) $c_media = 2;

        $res = [];
        $r = false;
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
            if (!empty($photo) && is_array($photo)){
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
            if (!empty($video) && is_array($video)){
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
        if (!empty($res) && is_array($res)){
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

    public function getPostPhoto($n_id, $limit=0){
        $q_limit = '';
        $limit = intval($limit);
        if ($limit>0) $q_limit = ' LIMIT 0, '.$limit;
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
                ph_path,
                ph_about_".S_LANG." as ph_about,
                ph_title_".S_LANG." as ph_title,
											ph_folder,
											ph_gallery_id",
            "	ph_is_active='yes' and
                ph_type_id = '".$n_id."' AND
											ph_type = 'news' and
											ph_type_main = 'no'
											ORDER BY ph_id ASC
											$q_limit");
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_about'] = strip_tags(stripcslashes($temp[$i]['ph_about']));
                $temp[$i]['ph_title'] = strip_tags(stripcslashes($temp[$i]['ph_title']));
                $temp[$i]['ph_small'] = substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
            }
            return $temp;
        } else return false;
    }

    public function getPostVideo($n_id){
        $n_id = intval($n_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."videos","
							v_id,
							v_code,
							v_title_".S_LANG." as v_title,
							v_about_".S_LANG." as v_about,
							v_folder,
							v_gallery_id",
            "	v_is_active='yes' and
                v_type_id = '".$n_id."' AND
							v_type = 'news'
						ORDER BY v_id ASC");
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['v_title'] = strip_tags(stripcslashes($temp[$i]['v_title']));
                $temp[$i]['v_about'] = strip_tags(stripcslashes($temp[$i]['v_about']));
            }
            return $temp;
        } else return false;
    }

    public function getInformerPost($c_n=0, $category_display=0, $category_not_display = 0){
        global $month; // массив названий месяцев

        $c_n = intval($c_n);
        $category_display = intval($category_display);
        $category_not_display = intval($category_not_display);

        $extra_q = '';
        if ($category_display > 0) $extra_q = " AND n_nc_id = '$category_display' ";
        else{
            if ($category_not_display > 0) $extra_q = " AND n_nc_id != '$category_not_display'";
        }
        if ($c_n < 1) $c_n = 5;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."news",
            "	n_id as id,
                n_title_".S_LANG." as title,
										n_description_".S_LANG." as description,
										n_date_show as date,
										n_is_main,
										n_nc_id,
										n_tags,
										n_nc_main
									","	n_is_active='yes' AND
										n_description_".S_LANG." != ''
										$extra_q
										ORDER BY n_date_show DESC, n_id DESC LIMIT $c_n");
        //AND n_is_main = 'no'
        if (!empty($temp) && is_array($temp)){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>" );
                $temp[$i]['photo_main'] = $this->getPostPhotoMain($temp[$i]['id']);
                $temp[$i]['m'] = $month[date('m', strtotime($temp[$i]['date']))];
                $temp[$i]['wd_n'] = date('N', strtotime($temp[$i]['date']));
            }
            return $temp;
        }
        return false;
    }

    public function getInformerPostWoPh($c_n=0, $category_display=0, $category_not_display = 0){
        global $month; // массив названий месяцев

        $c_n = intval($c_n);
        $category_display = intval($category_display);
        $category_not_display = intval($category_not_display);

        $extra_q = '';
        if ($category_display > 0) $extra_q = " AND n_nc_id = '$category_display' ";
        else{
            if ($category_not_display > 0) $extra_q = " AND n_nc_id != '$category_not_display'";
        }
        if ($c_n < 1) $c_n = 5;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."news",
            "	n_id as id,
                n_title_".S_LANG." as title,
										n_description_".S_LANG." as description,
										n_date_show as date,
										n_is_main,
										n_nc_id,
										n_tags,
										n_nc_main
									","	n_is_active='yes' AND
										n_description_".S_LANG." != ''
										$extra_q
										ORDER BY n_date_show DESC, n_id DESC LIMIT $c_n");
        //AND n_is_main = 'no'
        if (!empty($temp) && is_array($temp)){
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

