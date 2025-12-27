<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class banners{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }


    // НАЧАЛО ============= Баннеры на страницах ===============================================================================
    // добавление и редактирование связей страница-баннер (множественный список)
    public function saveBannerPage($b_id = 0, $page_id = false, $place = 'left', $order=0, $display_type = 'shuffle'){
        $b_id = intval($b_id);
        if ($b_id < 1) return false;
        if (!$page_id) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id"," pbi_item_id = '$b_id' AND pbi_item_type = 'banner' ORDER BY pbi_id DESC");
            if ($temp) foreach ($temp as $item) $this->hdl->delElem(DB_T_PREFIX."page_banner_informer", "pbi_pbi_id = '".$item['pbi_id']."' AND pbi_item_type = 'setting' ");
            $this->hdl->delElem(DB_T_PREFIX."page_banner_informer", "pbi_item_id = '$b_id' AND pbi_item_type = 'banner' ");
            return true;
        }
        if ($place == 'down') $place = 'down';
        elseif ($place == 'right') $place = 'right';
        elseif ($place == 'left') $place = 'left';
        else $place = 'center';
        if ($display_type == 'fixed') $display_type = 'fixed';
        else $display_type = 'shuffle';
        if (count($page_id)>0){
            $extra_q = $extra_q_1 = '';
            foreach ($page_id as $key => $val) {
                $val = intval($val);
                $extra_q .= " AND pbi_page_id != '$val'";
                $extra_q_1 .= " OR pbi_page_id = '$val'";
            }
            $extra_q_1 = substr($extra_q_1, 4);
            $extra_q_1 = " pbi_item_id = '$b_id' AND pbi_item_type = 'banner' AND (".$extra_q_1.") ";
            $extra_q = substr($extra_q, 5);
            $extra_q = " pbi_item_id = '$b_id' AND pbi_item_type = 'banner' AND (".$extra_q.") ";
            $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id","$extra_q"); // удаление настроек для удаляемых прикриплений
            if ($temp) {
                $q_setting = '';
                foreach ($temp as $t_item) $q_settings .= " OR pbi_pbi_id = '".$t_item['pbi_id']."' ";
                $q_settings = "pbi_item_type = 'setting' AND (".substr($q_settings, 3).")";
                $this->hdl->delElem(DB_T_PREFIX."page_banner_informer", $q_settings); // удалить все лишние донастройки
            }
            $this->hdl->delElem(DB_T_PREFIX."page_banner_informer", $extra_q); // удалить все лишние прикрепления баннеров
            $iData['pbi_place'] = $place;
            $iData['pbi_order'] = intval($order);
            $iData['pbi_display_type'] = $display_type;
            $this->hdl->updateElemExtra(DB_T_PREFIX."page_banner_informer",$iData, $extra_q_1); // обновить всё что осталось
            foreach ($page_id as $key => $val){
                $val = intval($val);
                $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","*"," pbi_item_id = '$b_id' AND pbi_item_type = 'banner' AND pbi_page_id = '$val' LIMIT 1");
                if (!$temp) {
                    $iData = array(
                        "NOW()",
                        USER_ID,
                        $val,
                        $b_id,
                        'banner',
                        $place,
                        'yes',
                        intval($order),
                        0,
                        $display_type
                    );
                    $this->hdl->addElem(DB_T_PREFIX."page_banner_informer", $iData); // добавить того чего нет
                }
            }
            return true;
        }
        return false;
    }

    public function getBannerPageList($b_id = 0){
        $b_id = intval($b_id);
        if ($b_id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","*"," pbi_item_id = '$b_id' AND pbi_item_type = 'banner' ORDER BY pbi_page_id ASC");
        if ($temp){
            foreach ($temp as $val) $list[$val['pbi_page_id']] = $val;
            return $list;
        }
        return false;
    }
    // КОНЕЦ ============= Баннеры на страницах ===============================================================================

    // чтение списка всех добавленных баннеров
    public function getBannersList(){
        return $this->hdl->selectElem(DB_T_PREFIX."banners","*"," b_is_delete = 'no' ORDER BY b_id DESC");
    }

    // чтение одного баннера
    public function getBannerItem($b_id = 0){
        $b_id = intval($b_id);
        if ($b_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."banners","*","b_id = $b_id AND b_is_delete = 'no' LIMIT 0, 1");
            if ($item) return $item[0];
        }
        return false;
    }

    // добавление нового баннера
    public function addBanner($file, $folder='/', $post){
        //$b_title_ru = '', $b_title_ua = '', $b_title_en = '', $b_is_active = true, $b_datetime_from = 'NOW()', $b_datetime_to = 'NOW()', $b_is_datetime = false, $b_order = 0, $b_url = '', $b_target = true, $b_noindex = true
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        if ($post['b_type'] == 'code') $b_type = 'code';
        else $b_type = 'image';
        if (($file['error'] != 0 or $file['size'] == 0) && $b_type == 'image' ) return false;
        $file['name'] = $this->getTranslit($file['name']);
        $file_name = $this->fileName('banners', $folder, $file['name'], 0);
        if ($post['b_is_active']) $b_is_active = 'yes';
        else $b_is_active = 'no';
        if ($post['b_is_datetime']) $b_is_datetime = 'yes';
        else $b_is_datetime = 'no';
        if ($post['b_target']) $b_target = 'yes';
        else $b_target = 'no';
        if ($post['b_noindex']) $b_noindex = 'yes';
        else $b_noindex = 'no';

        $b_datetime_from = $post['b_date_from_year']."-".$post['b_date_from_month']."-".$post['b_date_from_day']." 00:00:00";
        $b_datetime_to = $post['b_date_to_year']."-".$post['b_date_to_month']."-".$post['b_date_to_day']." 23:59:59";
        if (move_uploaded_file($file['tmp_name'], "../upload/banners".$folder.$file_name) || $b_type == 'code') {
            $iData = array(
                "NOW()",
                "NOW()",
                USER_ID,
                $b_is_active,
                'no',
                $folder.$file_name,
                str_replace($search, $replace, $b_datetime_from),
                str_replace($search, $replace, $b_datetime_to),
                $b_is_datetime,
                intval($post['b_order']),
                str_replace($search, $replace, $post['b_url']),
                $b_target,
                str_replace($search, $replace, $post['b_title_ru']),
                str_replace($search, $replace, $post['b_title_ua']),
                str_replace($search, $replace, $post['b_title_en']),
                $b_noindex,
                $b_type,
                addslashes($post['b_code'])
            );
            if ($this->hdl->addElem(DB_T_PREFIX."banners", $iData)) return true;
        }
        return false;
    }

    // редактирование баннера
    public function saveEditedBanner($b_id = 0, $file, $folder='/', $post){

        //$b_title_ru = '', $b_title_ua = '', $b_title_en = '', $b_is_active = true, $b_datetime_from = 'NOW()', $b_datetime_to = 'NOW()', $b_is_datetime = false, $b_order = 0, $b_url = '', $b_target = true, $b_noindex = true
        $b_id = intval($b_id);
        if ($b_id < 1) return false;
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $iData = array();
        $iData['b_title_ru'] = str_replace($search, $replace, $post['b_title_ru']);
        $iData['b_title_ua'] = str_replace($search, $replace, $post['b_title_ua']);
        $iData['b_title_en'] = str_replace($search, $replace, $post['b_title_en']);
        $iData['b_datetime_edit'] = 'NOW()';
        $iData['b_author'] = USER_ID;
        if ($post['b_is_active']) $iData['b_is_active'] = 'yes';
        else $iData['b_is_active'] = 'no';
        if ($post['b_is_datetime']) $iData['b_is_datetime'] = 'yes';
        else $iData['b_is_datetime'] = 'no';
        if ($post['b_target']) $iData['b_target'] = 'yes';
        else $iData['b_target'] = 'no';
        $b_datetime_from = $post['b_date_from_year']."-".$post['b_date_from_month']."-".$post['b_date_from_day']." 00:00:00";
        $iData['b_datetime_from'] = str_replace($search, $replace, $b_datetime_from);
        $b_datetime_to = $post['b_date_to_year']."-".$post['b_date_to_month']."-".$post['b_date_to_day']." 23:59:59";
        $iData['b_datetime_to'] = str_replace($search, $replace, $b_datetime_to);
        $iData['b_order'] = intval($post['b_order']);
        $iData['b_url'] = str_replace($search, $replace, $post['b_url']);
        if ($post['b_noindex']) $iData['b_noindex'] = 'yes';
        else $iData['b_noindex'] = 'no';
        if ($post['b_type'] == 'code') $iData['b_type'] = 'code';
        else $iData['b_type'] = 'image';
        $iData['b_code'] = addslashes($post['b_code']);
        if ($file['error'] == 0 and $file['size'] > 0) {
            $old_banner = $this->hdl->selectElem(DB_T_PREFIX."banners","*","b_id = '$b_id' AND b_is_delete = 'no' LIMIT 0, 1");
            if ($old_banner){
                if (unlink("../upload/banners".$old_banner[0]['b_path'])){
                    $file['name'] = $this->getTranslit($file['name']);
                    $file_name = $this->fileName('images', $folder, $file['name'], 0);
                    if (move_uploaded_file($file['tmp_name'], "../upload/banners".$folder.$file_name)) {
                        $iData['b_path'] = $folder.$file_name;
                    }
                }
            }
        }
        $condition = array(
            "b_id"=>$b_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."banners",$iData, $condition)) return true;
        else return false;
    }

    public function deleteBanner($b_id = 0){
        $b_id = intval($b_id);
        if ($b_id>0){
            $old_banner = $this->hdl->selectElem(DB_T_PREFIX."banners","b_id","b_id = '$b_id' LIMIT 1");
            if ($old_banner){
                $iData['b_is_delete'] = 'yes';
                $condition['b_id'] = $b_id;
                if ($this->hdl->updateElem(DB_T_PREFIX."banners",$iData, $condition)) {
                    //$this->hdl->delElem(DB_T_PREFIX."banner_pages", " bp_banner_id = '$b_id' ");
                    //$this->hdl->delElem(DB_T_PREFIX."main_banner_informer", " mbi_item_id = '$b_id' AND mbi_item_type = 'banner' ");
                    $this->hdl->delElem(DB_T_PREFIX."page_banner_informer", " pbi_item_id = '$b_id' AND pbi_item_type = 'banner' ");
                    return true;
                }
            }
        }
        return false;
    }

    private function fileName($type='files', $folder='/', $file_name, $corrector=0){
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
        }
        return $new_file_name;
    }

    private function getTranslit($st, $charset = 'utf-8'){
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
}
?>
