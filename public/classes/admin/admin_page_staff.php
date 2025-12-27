<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class page_staff{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // PAGE_STAFF ////////// ÍÀ×ÀËÎ //////////////////////////////////////////////////////////////////////////////////////////////
    public function createPageStaff(){
        $search = array("_", " ", "'", '"');
        $replace = array("-", "-", '', '');
        $_POST['ps_address'] = strtolower($_POST['ps_address']);
        $elem = array(
            intval($_POST['p_id']),
            intval($_POST['ps_s_id']),
            'NOW()',
            'NOW()',
            USER_ID,
            addslashes($_POST['ps_title_ru']),
            addslashes($_POST['ps_title_ua']),
            addslashes($_POST['ps_title_en']),
            str_replace($search, $replace, $_POST['ps_address']),
            intval($_POST['ps_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."page_staff", $elem)) return true;
        else return false;
    }

    public function updatePageStaff(){
        $search = array("_", " ", "'", '"');
        $replace = array("-", "-", '', '');
        $_POST['p_adress'] = strtolower($_POST['p_adress']);
        $elems = array(
            "ps_address" => str_replace($search, $replace, $_POST['ps_address']),
            "ps_title_ru" => addslashes($_POST['ps_title_ru']),
            "ps_title_ua" => addslashes($_POST['ps_title_ua']),
            "ps_title_en" => addslashes($_POST['ps_title_en']),
            "ps_author" => USER_ID,
            "ps_datetime_edit" => 'NOW()',
            "ps_order" => intval($_POST['ps_order']),
            "ps_s_id" => intval($_POST['ps_s_id'])
        );
        $condition = array(
            "ps_id"=>$_POST['ps_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."page_staff",$elems, $condition)) return true;
        else return false;
    }

    public function deletePageStaff($ps_id = 0){
        $ps_id = intval($ps_id);
        if ($ps_id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."page_staff", "ps_id='$ps_id'")) return true;
            else return false;
        }else return false;
    }

    public function getPageStaffList($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_staff, ".DB_T_PREFIX."staff ","st_id, st_family_ru, st_name_ru, st_surname_ru, ps_id, ps_p_id, ps_s_id, ps_title_ru, ps_title_ua, ps_title_en, ps_address, ps_order","ps_p_id = '$p_id' AND ps_s_id = st_id ORDER BY ps_order DESC, ps_title_ru ASC, ps_s_id ASC");
        if ($temp){
            foreach ($temp as &$item){
                $item['ps_title_ru'] = stripcslashes($item['ps_title_ru']);
                $item['ps_title_ua'] = stripcslashes($item['ps_title_ua']);
                $item['ps_title_en'] = stripcslashes($item['ps_title_en']);
                $item['st_family_ru'] = stripcslashes($item['st_family_ru']);
                $item['st_name_ru'] = stripcslashes($item['st_name_ru']);
                $item['st_surname_ru'] = stripcslashes($item['st_surname_ru']);
            }
        }
        return $temp;
    }

    public function getStaffList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."staff","st_id, st_family_ru, st_name_ru, st_surname_ru","st_is_active = 'yes' ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC");
        return $temp;
    }
    // PAGE_STAFF ////////// ÊÎÍÅÖ //////////////////////////////////////////////////////////////////////////////////////////////
}
?>
