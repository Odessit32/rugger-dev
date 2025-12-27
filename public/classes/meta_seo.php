<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class metaSeo{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getMetaItem($item_id = 0, $item_type = ''){
        $item_id = intval($item_id);
        $search = array("_", " ", "'", '"', ';', ':');
        $replace = array("-", "-", '', '', '', '');
        $item_type = str_replace($search, $replace, $item_type);
        if ($item_id >0 && $item_type != '') {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."meta_seo",
                "   title_".S_LANG." as title,
                    description_".S_LANG." as description,
                    keywords_".S_LANG." as keywords ",
                "   item_id=$item_id && item_type = '$item_type'");
            if ($temp) {
                $temp = $temp[0];
                $temp['title'] = strip_tags(stripcslashes($temp['title']));
                $temp['description'] = strip_tags(stripcslashes($temp['description']));
                $temp['keywords'] = strip_tags(stripcslashes($temp['keywords']));
            }
            return $temp;
        } else return false;
    }
}