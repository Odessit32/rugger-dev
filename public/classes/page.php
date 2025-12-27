<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class page{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getPageItem($id = 0){
        $id = intval($id);
        if ($id >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages",
                "   p_id as id,
                    p_parent_id as parent_id,
                    p_adress as adress,
                    p_title_".S_LANG." as title,
                    p_description_".S_LANG." as description,
                    p_text_".S_LANG." as text,
                    p_author as author,
                    p_title_files as title_files,
                    p_is_title as is_title,
                    p_is_description as is_description,
                    p_is_text as is_text,
                    p_is_scrol as is_scrol,
                    p_is_files as is_files,
                    p_is_socials as is_socials
                ",
                "p_id=$id");
            if ($temp) {
                $temp = $temp[0];
                $temp['title'] = stripcslashes($temp['title']);
                $temp['description'] = stripcslashes($temp['description']);
                $temp['description_meta'] = strip_tags($temp['description']);
                $temp['text'] = stripcslashes($temp['text']);
            }
            return $temp;
        } else return false;
    }

    public function getPhPageItem($p_id){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $search = array("<br>", "<br />", '</p>', "\n\n");
        $replace = array("<br>\n", "<br />\n", "</p>\n", "\n");
        $gal_item = $this->hdl->selectElem(DB_T_PREFIX."pages_extra, ".DB_T_PREFIX."photo_gallery","phg_id, pe_style, pe_is_on_descr, pe_is_on_text, phg_title_".S_LANG." as phg_title","pe_item_id = phg_id AND pe_p_id = '$p_id' AND pe_item_type = 'photo' LIMIT 1");
        if ($gal_item){
            $gal_item = $gal_item[0];
            $gal_item['phg_title'] = stripcslashes(str_replace($search, $replace, $gal_item['phg_title']));
            $photos = $this->hdl->selectElem(DB_T_PREFIX."photos",
                "   ph_id,
                    ph_path,
                    ph_about_".S_LANG." as ph_about,
                    ph_title_".S_LANG." as ph_title,
                    ph_folder
                "," ph_is_active = 'yes' AND
                    ph_gallery_id = '".$gal_item['phg_id']."'
                    ORDER BY ph_type_main DESC,
                    ph_order DESC,
                    ph_id ASC");
            if ($photos){
                foreach ($photos as &$item){
                    $item['ph_about'] = stripcslashes(str_replace($search, $replace, $item['ph_about']));
                    $item['ph_title'] = stripcslashes(str_replace($search, $replace, $item['ph_title']));
                    $item['ph_alt'] = trim(strip_tags($item['ph_about']));
                    $item['ph_small'] = $item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
                    $item['ph_med'] = $item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
                    $item['ph_big'] = $item['ph_folder'].$item['ph_path'];
                }
                $gal_item['photos'] = $photos;
            }
            $photo_m = $this->hdl->selectElem(DB_T_PREFIX."photos",
                "   ph_id,
                    ph_path,
                    ph_about_".S_LANG." as ph_about,
                    ph_title_".S_LANG." as ph_title,
                    ph_folder
                "," ph_type_main = 'yes' AND
                    ph_gallery_id = '".$gal_item['phg_id']."'
                    LIMIT 1");
            if ($photo_m){
                $photo_m = $photo_m[0];
                $photo_m['ph_about'] = stripcslashes(str_replace($search, $replace, $photo_m['ph_about']));
                $photo_m['ph_title'] = stripcslashes(str_replace($search, $replace, $photo_m['ph_title']));
                $photo_m['ph_alt'] = strip_tags($photo_m['ph_about'])."\n";
                $photo_m['ph_small'] = $photo_m['ph_folder'].substr($photo_m['ph_path'], 0, strlen(strrchr($photo_m['ph_path'], "."))*(-1))."-small".strrchr($photo_m['ph_path'], ".");
                $photo_m['ph_med'] = $photo_m['ph_folder'].substr($photo_m['ph_path'], 0, strlen(strrchr($photo_m['ph_path'], "."))*(-1))."-med".strrchr($photo_m['ph_path'], ".");
                $photo_m['ph_big'] = $photo_m['ph_folder'].$photo_m['ph_path'];
                $gal_item['photo_main'] = $photo_m;
            } else {
                $gal_item['photo_main'] = $photos[0];
            }
            unset($photos[0]);
            $gal_item['photos'] = $photos;
        }
        return $gal_item;
    }

    public function getSiteMap($parent_id = 0, $nesting = 0, $depth = 0, $is_active='yes', $is_menu='', $is_delete='no', $order_by = '', $order = 'ASC', $path = '') {
        if ($nesting<$depth or $depth == 0){
            $extra = '';
            if ($is_active == 'yes') $extra .= " AND p_is_active = 'yes'";
            if ($is_active == 'no') $extra .= " AND p_is_active = 'no'";
            if ($is_menu == 'yes') $extra .= " AND p_is_menu = 'yes'";
            if ($is_menu == 'no') $extra .= " AND p_is_menu = 'no'";
            if ($is_delete == 'yes') $extra .= " AND p_is_delete = 'yes'";
            if ($is_delete == 'no') $extra .= " AND p_is_delete = 'no'";
            if ($order == 'desc' OR $order == 'DESC' OR $order == 'Desc') $order = 'DESC';
            else $order = 'ASC';
            if ($order_by != '') $extra .= " ORDER BY $order_by $order";
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages",
                "   p_id,
                    p_parent_id,
                    p_adress as address,
                    p_title_".S_LANG." as title
                        ",
                "p_parent_id = '$parent_id'".$extra);
            if ($temp != false){
                $result = array();
                $new_nesting = $nesting+1;
                foreach ($temp as $item){
                    $temp_item = $item;
                    $new_path = $path.$temp_item['address'].'/';
                    $temp_item['nesting'] = $nesting;
                    $temp_item['path'] = $path;
                    $result[] = $temp_item;
                    $parent_result = $this->getSiteMap($temp_item['p_id'], $new_nesting, $depth, $is_active, $is_menu, $is_delete, $order_by, $order, $path.$temp_item['address'].'/');
                    if ($parent_result != false){
                        foreach ($parent_result as $key_parent=>$item_parent) $result[] = $item_parent;
                    }
                }
                return $result;
            } else return false;
        } else return false;
    }

    public function getPageChildList($p_id){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages",
            "   p_id as id,
                p_parent_id,
                p_adress as address,
                p_title_".S_LANG." as title,
                p_description_".S_LANG." as description,
                p_text_".S_LANG." as text,
                p_is_title as is_title
                        ",
            "   p_parent_id = '$p_id' AND
                p_is_delete = 'no' AND
                p_is_active = 'yes'
                ORDER BY p_order DESC
            ");
        if ($temp){
            $c_temp = count($temp);
            for ($i=0; $i<$c_temp; $i++){
                $temp[$i]['title'] = strip_tags(stripslashes($temp[$i]['title']));
                $temp[$i]['description'] = strip_tags(stripslashes($temp[$i]['description']));
                $temp[$i]['text'] = stripslashes($temp[$i]['text']);
            }
        }
        return $temp;
    }

    public function getPageImages($id = 0){
        $id = intval($id);
        if ($id >0) {
            $list = $this->hdl->selectElem(DB_T_PREFIX."photos",
                "   ph_path as path, 
                    ph_folder as folder,
                    ph_about_".S_LANG." as ph_about,
                    ph_title_".S_LANG." as ph_title",
                "   ph_type_id = $id AND 
                    ph_type = 'page' AND 
                    ph_is_active = 'yes' 
                    ORDER BY ph_id DESC");
            return $list;
        }
        return false;
    }
}

