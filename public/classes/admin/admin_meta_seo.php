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
                "*",
                "item_id=$item_id && item_type = '$item_type'");
            if ($temp) {
                $temp = $temp[0];
                $temp['title_ru'] = stripcslashes($temp['title_ru']);
                $temp['title_ua'] = stripcslashes($temp['title_ua']);
                $temp['title_en'] = stripcslashes($temp['title_en']);
                $temp['description_ru'] = stripcslashes($temp['description_ru']);
                $temp['description_ua'] = stripcslashes($temp['description_ua']);
                $temp['description_en'] = stripcslashes($temp['description_en']);
                $temp['keywords_ru'] = stripcslashes($temp['keywords_ru']);
                $temp['keywords_ua'] = stripcslashes($temp['keywords_ua']);
                $temp['keywords_en'] = stripcslashes($temp['keywords_en']);
            }
            return $temp;
        } else return false;
    }
    
    public function saveMetaItem(){
        $_POST['meta_seo_item_id'] = intval($_POST['meta_seo_item_id']);
        $search = array("_", " ", "'", '"', ';', ':');
        $replace = array("-", "-", '', '', '', '');
        $_POST['meta_seo_item_type'] = str_replace($search, $replace, $_POST['meta_seo_item_type']);
        if ($_POST['meta_seo_item_id']>0 && $_POST['meta_seo_item_type'] != ''){
            if ($this->getMetaItem($_POST['meta_seo_item_id'], $_POST['meta_seo_item_type'])){
                $elems = array(
                    "title_ru" => addslashes($_POST['title_ru']),
                    "title_ua" => addslashes($_POST['title_ua']),
                    "title_en" => addslashes($_POST['title_en']),
                    "description_ru" => (strlen(trim(html_entity_decode(strip_tags($_POST['description_ru']))))>0) ? addslashes($_POST['description_ru']) : NULL,
                    "description_ua" => (strlen(trim(html_entity_decode(strip_tags($_POST['description_ua']))))>0) ? addslashes($_POST['description_ua']) : NULL,
                    "description_en" => (strlen(trim(html_entity_decode(strip_tags($_POST['description_en']))))>0) ? addslashes($_POST['description_en']) : NULL,
                    "keywords_ru" => (strlen(trim(html_entity_decode(strip_tags($_POST['keywords_ru']))))>0) ? addslashes($_POST['keywords_ru']) : NULL,
                    "keywords_ua" => (strlen(trim(html_entity_decode(strip_tags($_POST['keywords_ua']))))>0) ? addslashes($_POST['keywords_ua']) : NULL,
                    "keywords_en" => (strlen(trim(html_entity_decode(strip_tags($_POST['keywords_en']))))>0) ? addslashes($_POST['keywords_en']) : NULL,
                    "author" => USER_ID,
                    "datetime_edit" => 'NOW()'
                );
                $condition = array(
                    "item_id"=>$_POST['meta_seo_item_id'],
                    "item_type"=>$_POST['meta_seo_item_type'],
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."meta_seo",$elems, $condition)) return true;
                else return false;
            } else {
                $elem = array(
                    $_POST['meta_seo_item_id'],
                    $_POST['meta_seo_item_type'],
                    addslashes($_POST['title_ru']),
                    addslashes($_POST['title_ua']),
                    addslashes($_POST['title_en']),
                    (strlen(trim(html_entity_decode(strip_tags($_POST['description_ru']))))>0) ? addslashes($_POST['description_ru']) : NULL,
                    (strlen(trim(html_entity_decode(strip_tags($_POST['description_ua']))))>0) ? addslashes($_POST['description_ua']) : NULL,
                    (strlen(trim(html_entity_decode(strip_tags($_POST['description_en']))))>0) ? addslashes($_POST['description_en']) : NULL,
                    (strlen(trim(html_entity_decode(strip_tags($_POST['keywords_ru']))))>0) ? addslashes($_POST['keywords_ru']) : NULL,
                    (strlen(trim(html_entity_decode(strip_tags($_POST['keywords_ua']))))>0) ? addslashes($_POST['keywords_ua']) : NULL,
                    (strlen(trim(html_entity_decode(strip_tags($_POST['keywords_en']))))>0) ? addslashes($_POST['keywords_en']) : NULL,
                    USER_ID,
                    'NOW()',
                    'NOW()'
                );
                if ($this->hdl->addElem(DB_T_PREFIX."meta_seo", $elem)) return true;
                return false;
            }
        }
    }
}