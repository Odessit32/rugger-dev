<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class page_team{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // PAGE_TEAM ////////// ÍÀ×ÀËÎ //////////////////////////////////////////////////////////////////////////////////////////////
    public function savePageTeam(){
        $_POST['p_id'] = intval($_POST['p_id']);
        $_POST['pe_t_id'] = intval($_POST['pe_t_id']);
        if ($_POST['p_id'] < 1) return false;
        if ($_POST['pe_t_id']>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "	pe_id,
							pe_p_id, 
							pe_item_id",
                "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'team'
						");
            if (count($temp)>1) {
                $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'team'");
                $temp = false;
            }
            if ($temp){
                $temp = $temp[0];
                $elems = array(
                    "pe_item_id" => $_POST['pe_t_id'],
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
                    $_POST['pe_t_id'],
                    'team',
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
            $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'team'");
            return true;
        }
        return false;
    }

    public function getPageTeamItem($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
            "	pe_id,
						pe_p_id, 
						pe_item_id",
            "	pe_p_id = '$p_id' AND
						pe_item_type = 'team' AND
						pe_item_id != ''
					");
        if ($temp){
            $temp = $temp[0];
        }
        return $temp;
    }

    public function getTeamList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."team","
					t_id as id,
					t_title_ru as title
					","t_is_delete = 'no' ORDER BY t_title_ru ASC, t_id DESC");
        return $temp;
    }

    // PAGE_TEAM ////////// ÊÎÍÅÖ //////////////////////////////////////////////////////////////////////////////////////////////
}
?>
