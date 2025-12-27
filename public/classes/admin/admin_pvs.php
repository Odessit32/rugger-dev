<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class pvs{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getPVSList($page_id){
        $page_id = intval($page_id);
        if ($page_id == 0) return false;
        $list = $this->hdl->selectElem(DB_T_PREFIX."page_video_services","*"," pvs_page_id = '$page_id' ORDER BY pvs_id DESC");
        if ($list) return $list;
        else return false;
    }

    public function addPVS($page_id = '', $pvs_code = '', $pvs_service = 0){
        $page_id = intval($page_id);
        if ($page_id == 0) return false;
        $pvs_service = intval($pvs_service);
        if ($pvs_service == 0) $pvs_service = 'youtube';
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $iData = array(
            $page_id,
            "NOW()",
            "NOW()",
            USER_ID,
            str_replace($search, $replace, $pvs_code),
            $pvs_service
        );
        $id = $this->hdl->addElem(DB_T_PREFIX."page_video_services", $iData);
        if ($id) return $id;
        else return false;
    }

    public function savePVS($pvs_id = 0, $pvs_code = '', $pvs_service = 0){
        $pvs_id = intval($pvs_id);
        if ($pvs_id == 0) return false;
        $pvs_service = intval($pvs_service);
        if ($pvs_service == 0) $pvs_service = 'youtube';
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $iData = array();
        $iData['pvs_datetime_edit'] = 'NOW()';
        $iData['pvs_code '] = str_replace($search, $replace, $pvs_code);
        $iData['pvs_author'] = USER_ID;
        $condition = array(
            "pvs_id"=>$pvs_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."page_video_services",$iData, $condition)) return true;
        else return false;
    }

    public function deletePVS($pvs_id=0){
        $pvs_id = intval($pvs_id);
        if ($pvs_id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."page_video_services", "pvs_id = '$pvs_id' LIMIT 1")) return true;
            else return false;
        }else return false;
    }

}
?>
