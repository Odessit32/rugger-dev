<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class club{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getClubMenu(&$menu, $c_id = 0){
        $f_cl = false; // ���������� ������ ������
        $c_id = intval($c_id);
        if (count($menu['menu'][1])>0) $t_menu = $menu['menu'][1];
        else $t_menu = false;
        $menu['menu'][1] = array();
        /*
        $item['title'] = "������� �����:";
        $item['type'] = 'club';
        $item['url'] = '';
        $item['url_pre'] = '';
        $item['url_post'] = '';
        $item['active'] = 'no';
        $menu['menu'][1][] = $item;
        */
        if ($f_cl){
            $temp = $this->hdl->selectElem("c63n_club","cl_id, cl_title_".S_LANG." AS title","cl_is_active='yes' ORDER BY cl_is_main DESC, cl_title_ru ASC");
            if ($temp){
                for ($i=0; $i<count($temp); $i++){
                    $item['title'] = stripcslashes($temp[$i]['title']);
                    $item['type'] = 'club';
                    $item['url'] = $temp[$i]['cl_id'];
                    $item['url_pre'] = 'club/';
                    $item['url_post'] = '';
                    if ($temp[$i]['cl_id'] == $c_id) $item['active'] = 'yes';
                    else $item['active'] = 'no';
                    $menu['menu'][1][] = $item;
                }
            }
        }
        $temp = $this->hdl->selectElem("c63n_team, c63n_connection_t_cl","t_id, t_title_".S_LANG." AS title","t_is_active='yes' AND cntcl_t_id = t_id AND cntcl_cl_id = '$c_id' ORDER BY t_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $item['title'] = stripcslashes($temp[$i]['title']);
                $item['type'] = 'team';
                $item['url'] = $temp[$i]['t_id'];
                $item['url_pre'] = 'team/';
                $item['url_post'] = '';
                $item['active'] = 'no';
                $menu['menu'][1][] = $item;
            }
        }
        if ($t_menu)
            foreach ($t_menu as $item)
                $menu['menu'][1][] = $item;
    }

    public function getClubList($page=1, $perpage=10){
        $page--;
        if ($page<0) $page = 0;
        $limit = $page*$perpage;
        $temp = $this->hdl->selectElem("c63n_club","
					cl_id, 
					cl_date_foundation,
					cl_cn_id,
					cl_ct_id,
					cl_title_".S_LANG." AS title,
					cl_description_".S_LANG." AS description
				","cl_is_active='yes' ORDER BY cl_title_".S_LANG." ASC, cl_id ASC LIMIT $limit, $perpage");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getClubPhotoMain($temp[$i]['cl_id']);
            }
            return $temp;
        } else return false;
    }

    public function getClubPages($page=1, $perpage=10){
        $temp = $this->hdl->selectElem("c63n_club","COUNT(*) as C_N","cl_is_active='yes'");
        $c_pages = intval($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i < $c_pages) $pages[$i] = $i+1;
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
        return $pages;
    }

    public function getClubItem($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra pe
                LEFT JOIN ".DB_T_PREFIX."club cl ON cl.cl_id = pe.pe_item_id",
            "	cl_id,
					cl_date_foundation,
					cl_cn_id,
					cl_ct_id,
					cl_title_".S_LANG." AS title,
					cl_description_".S_LANG." AS description,
					cl_text_".S_LANG." AS text
                ","	pe_p_id = '$p_id' AND
                    pe_item_type = 'club'
					LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            $temp['title'] = stripcslashes($temp['title']);
            $temp['description'] = stripcslashes($temp['description']);
            $temp['text'] = stripcslashes($temp['text']);
            $temp['text'] = ( trim(strip_tags($temp['text'])) == '' ) ? '' : $temp['text'];
            $temp['photo_main'] = $this->getPhotoMain($temp['cl_id'], 'club');
            $temp['photos'] = $this->getClubPhoto($temp['cl_id']);
            $temp['photo_gallery'] = $this->getClubPhotoGallery($temp['cl_id']);
            $temp['videos'] = $this->getClubVideo($temp['cl_id']);
            $temp['video_gallery'] = $this->getClubVideoGallery($temp['cl_id']);
            $temp['staff'] = $this->getClubStaff($temp['cl_id']);
            $temp['teams'] = $this->getClubTeams($temp['cl_id']);
        }
        return $temp;
    }

    public function getPhotoMain($id, $type = ''){
        $id = intval($id);
        if ($type == 'team') $type = 'team';
        elseif ($type == 'staff') $type = 'staff';
        elseif ($type == 'club') $type = 'club';
        else $type = 'none';
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
                    ph_path,
                    ph_about_".S_LANG." as ph_about,
                    ph_title_".S_LANG." as ph_title,
											ph_folder,
											ph_gallery_id
										","	ph_is_active='yes' AND
											ph_type_id = '".$id."' AND
											ph_type = '".$type."' AND
											ph_type_main = 'yes'
											LIMIT 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_title'] = strip_tags(stripcslashes($temp_photo['ph_title']));
            $temp_photo['ph_about'] = strip_tags(stripcslashes($temp_photo['ph_about']));
            //$temp_photo['ph_main'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_small'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_med'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-med".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_big'] = $temp_photo['ph_folder'].$temp_photo['ph_path'];
        }
        return $temp_photo;
    }

    public function getClubTeams($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem("c63n_connection_t_cl, c63n_team",
            "   t_id AS id,
                    t_title_".S_LANG." AS title,
                    t_description_".S_LANG." AS description,
                    t_address as address,
                    t_is_detailed
                    ",
            "   t_is_delete = 'no'
                    AND t_is_technical = 'no'
                    AND t_is_active = 'yes'
                    AND cntcl_is_delete='no'
				    AND cntcl_date_quit IS NULL
				    AND cntcl_cl_id = '$id'
				    AND cntcl_t_id = t_id
				ORDER BY
				    t_title_ru ASC,
				    t_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = stripcslashes($temp[$i]['title']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['photo_main'] = $this->getTeamPhotoMain($temp[$i]['t_id']);
            }
            return $temp;
        } else return false;
    }

    public function getClubStaff($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem("c63n_connection_st_cl, c63n_staff, c63n_team_appointment","
					st_id,
					st_date_birth,
					st_description_".S_LANG." AS description,
					st_text_".S_LANG." AS text,
					st_family_".S_LANG." AS family,
					st_name_".S_LANG." AS name,
					st_surname_".S_LANG." AS surname,
					app_title_".S_LANG." AS app_title,
					app_type,
					app_id
				","
					cnstcl_is_delete='no'
				AND cnstcl_date_quit IS NULL
				AND cnstcl_cl_id = '$id' 
				AND cnstcl_st_id = st_id 
				AND cnstcl_app_id = app_id 
				ORDER BY 
				cnstcl_order DESC, 
				app_order DESC,
				st_family_".S_LANG." ASC, 
				st_name_".S_LANG." ASC, 
				st_surname_".S_LANG." ASC, 
				st_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['family'] = stripcslashes($temp[$i]['family']);
                $temp[$i]['name'] = stripcslashes($temp[$i]['name']);
                $temp[$i]['surname'] = stripcslashes($temp[$i]['surname']);
                $temp[$i]['description'] = stripcslashes($temp[$i]['description']);
                $temp[$i]['text'] = stripcslashes($temp[$i]['text']);
                $temp[$i]['app_title'] = stripcslashes($temp[$i]['app_title']);
                $temp[$i]['photo_main'] = $this->getPlayerPhotoMain($temp[$i]['st_id']);
            }
            return $temp;
        } else return false;
    }

    public function getTeamPhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem("c63n_photos","*","ph_is_active='yes' and ph_type_id = '".$id."' AND ph_type = 'team' AND ph_type_main = 'yes' LIMIT 0, 1");
        if ($temp_photo){
            $temp_photo[0]['ph_about'] = stripcslashes($temp_photo[0]['ph_about']);
            $temp_photo[0]['ph_about'] = strip_tags($temp_photo[0]['ph_about']);
            $temp_photo[0]['ph_main'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_small'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], "."))*(-1))."-small".strrchr($temp_photo[0]['ph_path'], ".");
            return $temp_photo[0];
        } else return false;
    }

    public function getPlayerPhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem("c63n_photos","*","ph_is_active='yes' and ph_type_id = '".$id."' AND ph_type = 'staff' AND ph_type_main = 'yes' LIMIT 0, 1");
        if ($temp_photo){
            $temp_photo[0]['ph_about'] = stripcslashes($temp_photo[0]['ph_about']);
            $temp_photo[0]['ph_about'] = strip_tags($temp_photo[0]['ph_about']);
            $temp_photo[0]['ph_main'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_small'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], "."))*(-1))."-small".strrchr($temp_photo[0]['ph_path'], ".");
            $temp_photo[0]['ph_med'] = substr($temp_photo[0]['ph_path'], 0, strlen(strrchr($temp_photo[0]['ph_path'], "."))*(-1))."-med".strrchr($temp_photo[0]['ph_path'], ".");
            return $temp_photo[0];
        } else return false;
    }

    public function getClubMainOne($id = 0){
        $id = intval($id);
        if ($id>0) $q_extra = " AND cl_id = '$id'";
        else $q_extra = "";
        $temp = $this->hdl->selectElem("c63n_club","*","cl_is_active='yes' $q_extra ORDER BY RAND() LIMIT 1");
        if ($temp){
            $temp[0]['rus']['title'] = stripcslashes($temp[0]['cl_title_ru']);
            $temp[0]['ukr']['title'] = stripcslashes($temp[0]['cl_title_ua']);
            $temp[0]['eng']['title'] = stripcslashes($temp[0]['cl_title_en']);
            $temp[0]['rus']['description'] = stripcslashes($temp[0]['cl_description_ru']);
            $temp[0]['ukr']['description'] = stripcslashes($temp[0]['cl_description_ua']);
            $temp[0]['eng']['description'] = stripcslashes($temp[0]['cl_description_en']);
            $temp[0]['photo_main'] = $this->getClubPhotoMain($temp[0]['cl_id']);
            $temp[0]['photos'] = $this->getClubPhoto($temp[0]['cl_id']);
            $temp[0]['photo_gallery'] = $this->getClubPhotoGallery($temp[0]['cl_id']);
            $temp[0]['videos'] = $this->getClubVideo($temp[0]['cl_id']);
            $temp[0]['video_gallery'] = $this->getClubVideoGallery($temp[0]['cl_id']);
            return $temp[0];
        } else return false;
    }

    public function getClubPhotoGallery($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem("c63n_photo_gallery","*","phg_is_active='yes' AND phg_type = 'club' AND phg_type_id = '$id' LIMIT 0, 1");
        if ($temp){
            $temp[0]['phg_title'] = stripcslashes($temp[0]['phg_title']);
            $temp[0]['phg_description'] = stripcslashes($temp[0]['phg_description']);
            return $temp[0];
        } else return false;
    }

    public function getClubVideoGallery($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem("c63n_video_gallery","*","vg_is_active='yes' AND vg_type = 'club' AND vg_type_id = '$id' LIMIT 0, 1");
        if ($temp){
            $temp[0]['vg_title'] = stripcslashes($temp[0]['vg_title']);
            $temp[0]['vg_description'] = stripcslashes($temp[0]['vg_description']);
            return $temp[0];
        } else return false;
    }

    public function getClubPhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem("c63n_photos","*","ph_is_active='yes' and ph_type_id = '".$id."' AND ph_type = 'club' AND ph_type_main = 'yes' LIMIT 0, 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_about'] = stripcslashes($temp_photo['ph_about']);
            $temp_photo['ph_about'] = strip_tags($temp_photo['ph_about']);
            $temp_photo['ph_main'] = substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_small'] = substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            return $temp_photo;
        } else return false;
    }

    public function getClubPhoto($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem("c63n_photos","*","ph_is_active='yes' and ph_type_id = '".$id."' AND ph_type = 'club' and ph_type_main = 'no' ORDER BY ph_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['ph_about'] = stripcslashes($temp[$i]['ph_about']);
                $temp[$i]['ph_about'] = strip_tags($temp[$i]['ph_about']);
                $temp[$i]['ph_small'] = substr($temp[$i]['ph_path'], 0, strlen(strrchr($temp[$i]['ph_path'], "."))*(-1))."-small".strrchr($temp[$i]['ph_path'], ".");
            }
            return $temp;
        } else return false;
    }

    public function getClubVideo($id){
        $id = intval($id);
        $temp = $this->hdl->selectElem("c63n_videos","*","v_is_active='yes' and v_type_id = '".$id."' AND v_type = 'club' ORDER BY v_id ASC");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['v_title'] = stripcslashes($temp[$i]['v_title']);
                $temp[$i]['v_title'] = strip_tags($temp[$i]['v_title']);
            }
            return $temp;
        } else return false;
    }
}
?>
