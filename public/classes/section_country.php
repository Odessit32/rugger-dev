<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class SectionCountry{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getCountryChanpionships(){
        global $section_type_id;
        $res = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group chg
                    INNER JOIN ".DB_T_PREFIX."connection_country c ON chg.chg_id = c.type_id ",
            "	chg.chg_id as id,
                    chg.chg_title_".S_LANG." as title,
                    chg.chg_address as address,
                    chg.chg_is_menu AS is_menu",
            "	chg.chg_is_active='yes' AND
                    chg.chg_address != '' AND
                    c.type = 'champ_group' AND c.cn_id = '$section_type_id'
                    ORDER BY chg.chg_is_main DESC,
                        chg.chg_order DESC");
        if ($temp){
            foreach ($temp as &$item){
                $item['title'] = stripcslashes($item['title']);
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                if ($item['is_menu'] == 'yes') {
                    $res['menu'][] = $item;
                }
            }
        }
        return $res;
    }

}
