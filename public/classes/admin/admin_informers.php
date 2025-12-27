<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class informers{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // НАЧАЛО ============= Информеры на страницах ===============================================================================
    // удаление информера со страницы
    public function deleteInformerForPage($ip_id = 0){
        $ip_id = intval($ip_id);
        if ($ip_id > 0) {
            if ($this->hdl->delElem(DB_T_PREFIX."informer_pages", " ip_id = '$ip_id' ")) return true;
            else return false;
        }else return false;
    }

    // сохранение изменений информера со страницы
    public function saveInformerForPage($ip_id = 0, $place = 'left'){
        $ip_id = intval($ip_id);
        if ($ip_id < 1) return false;
        if ($place == 'right') $place = 'right';
        else $place = 'left';
        $iData['ip_place'] = $place;
        $condition = array(
            "ip_id"=>$ip_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."informer_pages",$iData, $condition)) return true;
        else return false;
    }

    // сохранение информера на страницу
    public function addInformerForPage($i_id = 0, $p_id = 0, $place = 'left'){
        $p_id = intval($p_id);
        if ($p_id < 1) return false;
        $i_id = intval($i_id);
        if ($i_id < 1) return false;
        if ($place == 'right') $place = 'right';
        else $place = 'left';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."informer_pages","*"," ip_informer_id = '$i_id' AND ip_page_id = '$p_id' LIMIT 1");
        if (!$temp) {
            $iData = array(
                "NOW()",
                USER_ID,
                $i_id,
                $p_id,
                $place
            );
            if ($this->hdl->addElem(DB_T_PREFIX."informer_pages", $iData)) return true;
            else return false;
        } else return false;
    }

    // выборка информера на определенную страницу
    public function getInformersItemForPage($ip_id = 0){
        $ip_id = intval($ip_id);
        if ($ip_id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."informers, ".DB_T_PREFIX."informer_pages","*"," ip_id = '$ip_id' AND i_id = ip_informer_id LIMIT 1");
        if ($temp){
            return $temp[0];
        } else return false;
    }

    // выборка списка информеров на определенную страницу
    public function getInformersListForPage($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."informer_pages, ".DB_T_PREFIX."informers","*"," ip_page_id = '$p_id' and i_id = ip_informer_id ORDER BY i_id ASC");
        if ($temp){
            $ret['inf_on'] = $temp;
            $extra_q = '';
            foreach ($temp as $val) $extra_q .= " AND i_id != '".$val['i_id']."'";
            $extra_q = substr($extra_q, 5);
        } else {
            $ret['inf_on'] = false;
            $extra_q = '1';
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."informers","*"," $extra_q AND i_is_delete = 'no' ORDER BY i_id ASC");
        $ret['inf_off'] = $temp;
        return $ret;
    }

    // добавление и редактирование связей страница-информер (множественный список)
    public function saveInformerPage($i_id = 0, $page_id = false, $place = 'left'){
        $i_id = intval($i_id);
        if ($i_id < 1) return false;
        if (!$page_id) return false;
        if ($place == 'right') $place = 'right';
        else $place = 'left';
        if (count($page_id)>0){
            $extra_q = $extra_q_1 = '';
            foreach ($page_id as $key => $val) {
                $val = intval($val);
                $extra_q .= " AND ip_page_id != '$val'";
                $extra_q_1 .= " OR ip_page_id = '$val'";
            }
            $extra_q_1 = substr($extra_q_1, 4);
            $extra_q_1 = " ip_informer_id = '$i_id' AND (".$extra_q_1.") ";
            $extra_q = substr($extra_q, 5);
            $extra_q = " ip_informer_id = '$i_id' AND (".$extra_q.") ";
            $this->hdl->delElem(DB_T_PREFIX."informer_pages", $extra_q); // удалить всё лишнее
            $iData['ip_place'] = $place;
            $this->hdl->updateElemExtra(DB_T_PREFIX."informer_pages",$iData, $extra_q_1); // обновить всё что осталось
            foreach ($page_id as $key => $val){
                $val = intval($val);
                $temp = $this->hdl->selectElem(DB_T_PREFIX."informer_pages","*"," ip_informer_id = '$i_id' AND ip_page_id = '$val' LIMIT 1");
                if (!$temp) {
                    $iData = array(
                        "NOW()",
                        USER_ID,
                        $i_id,
                        $val,
                        $place
                    );
                    $this->hdl->addElem(DB_T_PREFIX."informer_pages", $iData); // добавить того чего нет
                }
            }
            return true;
        } else return false;
    }

    public function getInformerPageList($i_id = 0){
        $i_id = intval($i_id);
        if ($i_id < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer",
            "	pbi_page_id,
						pbi_item_id,
						pbi_item_type,
						pbi_place",
            "	pbi_item_id = '$i_id' AND
						pbi_item_type = 'informer' AND
						pbi_display = 'yes'
						ORDER BY pbi_page_id ASC");
        if ($temp){
            foreach ($temp as $val) $list[$val['pbi_page_id']] = $val;
            return $list;
        } else return false;
    }
    // КОНЕЦ ============= Информеры на страницах ===============================================================================

    // чтение списка всех добавленных информеров
    public function getInformersList(){
        return $this->hdl->selectElem(DB_T_PREFIX."informers","*"," i_is_delete = 'no' ORDER BY i_id DESC");
    }

    // чтоние одного информера
    public function getInformerItem($i_id = 0){
        $i_id = intval($i_id);
        if ($i_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."informers","*","i_id = $i_id AND i_is_delete = 'no' LIMIT 0, 1");
            if ($item) {
                if (!empty($item[0]['i_code'])){
                    $item[0]['i_code'] = stripslashes($item[0]['i_code']);
                }
                return $item[0];
            }
        }
        return false;
    }

    // добавление нового информера
    public function addInformer($post){
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        if ($post['i_is_active']) $i_is_active = 'yes';
        else $i_is_active = 'no';
        if ($post['i_is_datetime']) $i_is_datetime = 'yes';
        else $i_is_datetime = 'no';
        if ($post['i_noindex']) $i_noindex = 'yes';
        else $i_noindex = 'no';
        $i_datetime_from = $post['i_date_from_year']."-".$post['i_date_from_month']."-".$post['i_date_from_day']." 00:00:00";
        $i_datetime_to = $post['i_date_to_year']."-".$post['i_date_to_month']."-".$post['i_date_to_day']." 23:59:59";
        $iData = array(
            "NOW()",
            "NOW()",
            USER_ID,
            $i_is_active,
            'no',
            str_replace($search, $replace, $post['i_path']),
            str_replace($search, $replace, $i_datetime_from),
            str_replace($search, $replace, $i_datetime_to),
            $i_is_datetime,
            intval($post['i_order']),
            str_replace($search, $replace, $post['i_title_ru']),
            str_replace($search, $replace, $post['i_title_ua']),
            str_replace($search, $replace, $post['i_title_en']),
            $i_noindex,
            str_replace($search, $replace, $post['i_class']),
            str_replace($search, $replace, $post['i_description_ru']),
            str_replace($search, $replace, $post['i_description_ua']),
            str_replace($search, $replace, $post['i_description_en']),
            addslashes($post['i_code'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."informers", $iData)) return true;
        else return false;
    }

    // редактирование информера
    public function saveEditedInformer($i_id = 0, $post){

        $i_id = intval($i_id);
        if ($i_id < 1) return false;
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $iData = array();
        $iData['i_title_ru'] = str_replace($search, $replace, $post['i_title_ru']);
        $iData['i_title_ua'] = str_replace($search, $replace, $post['i_title_ua']);
        $iData['i_title_en'] = str_replace($search, $replace, $post['i_title_en']);
        $iData['i_description_ru'] = str_replace($search, $replace, $post['i_description_ru']);
        $iData['i_description_ua'] = str_replace($search, $replace, $post['i_description_ua']);
        $iData['i_description_en'] = str_replace($search, $replace, $post['i_description_en']);
        $iData['i_code'] = addslashes($post['i_code']);
        $iData['i_datetime_edit'] = 'NOW()';
        $iData['i_author'] = USER_ID;
        if ($post['i_is_active']) $iData['i_is_active'] = 'yes';
        else $iData['i_is_active'] = 'no';
        if ($post['i_is_datetime']) $iData['i_is_datetime'] = 'yes';
        else $iData['i_is_datetime'] = 'no';
        $i_datetime_from = $post['i_date_from_year']."-".$post['i_date_from_month']."-".$post['i_date_from_day']." 00:00:00";
        $iData['i_datetime_from'] = str_replace($search, $replace, $i_datetime_from);
        $i_datetime_to = $post['i_date_to_year']."-".$post['i_date_to_month']."-".$post['i_date_to_day']." 23:59:59";
        $iData['i_datetime_to'] = str_replace($search, $replace, $i_datetime_to);
        $iData['i_order'] = intval($post['i_order']);
        if ($post['i_noindex']) $iData['i_noindex'] = 'yes';
        else $iData['i_noindex'] = 'no';
        $iData['i_path'] = str_replace($search, $replace, $post['i_path']);
        $iData['i_class'] = str_replace($search, $replace, $post['i_class']);
        $condition = array(
            "i_id"=>$i_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."informers",$iData, $condition)) return true;
        else return false;
    }

    public function deleteInformer($i_id = 0){
        $i_id = intval($i_id);
        if ($i_id>0){
            $old_informer = $this->hdl->selectElem(DB_T_PREFIX."informers","*","i_id = '$i_id' LIMIT 1");
            if ($old_informer){
                $iData['i_is_delete'] = 'yes';
                $condition['i_id'] = $i_id;
                if ($this->hdl->updateElem(DB_T_PREFIX."informers",$iData, $condition)) {
                    $this->hdl->delElem(DB_T_PREFIX."informer_pages", " ip_informer_id = '$i_id' ");
                    return true;
                } else return false;
            }else return false;
        }else return false;
    }
}
?>
