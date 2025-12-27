<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class conf_vars{
    private $hdl;

    public function __construct(){
        $this->hdl = database::getInstance();
    }
    public function getConfVars(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."config_vars","*"," cnv_lang = 'rus'");
        if ($temp)
            if (count($temp)>0)
                foreach($temp as $val){
                    $list['rus'][$val['cnv_name']] = $val['cnv_value'];
                }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."config_vars","*"," cnv_lang = 'ukr'");
        if ($temp)
            if (count($temp)>0)
                foreach($temp as $val){
                    $list['ukr'][$val['cnv_name']] = $val['cnv_value'];
                }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."config_vars","*"," cnv_lang = 'eng'");
        if ($temp)
            if (count($temp)>0)
                foreach($temp as $val){
                    $list['eng'][$val['cnv_name']] = $val['cnv_value'];
                }
        return $list;
    }

    public function getConfSettings(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."settings","*"," 1 ORDER BY set_id");
        if ($temp){
            if (count($temp)>0){
                foreach($temp as $val){
                    $list[$val['set_name']] = $val['set_value'];
                }
            }else return false;
        } else return false;
        return $list;
    }

    public function saveVarTitle($elems, $condition){
        if ($this->hdl->updateElem(DB_T_PREFIX."config_vars", $elems, $condition)) return true;
        else return false;
    }
}
?>
