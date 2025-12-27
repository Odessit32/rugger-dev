<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class page_club{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // PAGE_CLUB ////////// ÍÀ×ÀËÎ //////////////////////////////////////////////////////////////////////////////////////////////
    public function savePageClub(){
        $_POST['p_id'] = intval($_POST['p_id']);
        $_POST['pe_cl_id'] = intval($_POST['pe_cl_id']);
        if ($_POST['p_id'] < 1) return false;
        if ($_POST['pe_cl_id']>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "	pe_id,
							pe_p_id, 
							pe_item_id",
                "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'club'
						");
            if (count($temp)>1) {
                $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'club'");
                $temp = false;
            }
            if ($temp){
                $temp = $temp[0];
                $elems = array(
                    "pe_item_id" => $_POST['pe_cl_id'],
                    "pe_author" => USER_ID,
                    "pe_datetime_edit" => 'NOW()'
                );
                $condition = array(
                    "pe_id"=>$temp['pe_id']
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."pages_extra",$elems, $condition)) return true;
            } else {
                $elem = array(
                    $_POST['p_id'],
                    $_POST['pe_cl_id'],
                    'club',
                    0,
                    'no',
                    'no',
                    'NOW()',
                    'NOW()',
                    USER_ID
                );
                if ($this->hdl->addElem(DB_T_PREFIX."pages_extra", $elem)) return true;
            }
        } else {
            $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'club'");
            return true;
        }
        return false;
    }

    public function getPageClubItem($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
            "	pe_id,
						pe_p_id, 
						pe_item_id",
            "	pe_p_id = '$p_id' AND
						pe_item_type = 'club' AND
						pe_item_id != ''
					");
        if ($temp){
            $temp = $temp[0];
        }
        return $temp;
    }

    public function getClubList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."club","
					cl_id as id,
					cl_title_ru as title
					","1 ORDER BY cl_title_ru ASC, cl_id DESC");
        return $temp;
    }

    // PAGE_CLUB ////////// ÊÎÍÅÖ //////////////////////////////////////////////////////////////////////////////////////////////
}
?>
