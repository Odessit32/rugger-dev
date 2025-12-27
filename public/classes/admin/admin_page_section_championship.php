<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class PageChampionshipExtra{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // PAGE_EXTRA ////////// BEGIN //////////////////////////////////////////////////////////////////////////////////////////////
    public function savePageExtra(){
        $_POST['p_id'] = intval($_POST['p_id']);
        $_POST['pe_championship_id'] = intval($_POST['pe_championship_id']);
        if ($_POST['p_id'] < 1) return false;
        $return = false;
        if (!empty($_FILES['championship_flag'])){
            $file = $_FILES['championship_flag'];
            if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                $dir = "upload/championship_logo/";
                $n_file = $dir.$_POST['p_id'].strrchr($file['name'], ".");
                if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                    $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                        "	pe_id,
                            pe_p_id,
                            pe_item_id",
                        "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'championship_logo'
						");
                    if (count($temp)>1) {
                        $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'championship_logo'");
                        $temp = false;
                    }
                    if ($temp){
                        $temp = $temp[0];
                        $elems = array(
                            "pe_item_id" => $n_file,
                            "pe_author" => USER_ID,
                            "pe_datetime_edit" => 'NOW()'
                        );
                        $condition = array(
                            "pe_id"=>$temp['pe_id']
                        );
                        if ($this->hdl->updateElem(DB_T_PREFIX."pages_extra",$elems, $condition)) {
                            $return = true;
                        }
                    } else {
                        $elem = array(
                            $_POST['p_id'],
                            $n_file,
                            'championship_logo',
                            0,
                            'no',
                            'no',
                            'NOW()',
                            'NOW()',
                            USER_ID
                        );
                        if ($this->hdl->addElem(DB_T_PREFIX."pages_extra", $elem)) {
                            $return = true;
                        }
                    }
                }
            }
        }
        if (!empty($_POST['pe_championship_type'])){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "	pe_id,
                    pe_p_id,
                    pe_item_id",
                "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'championship_type'
						");
            if (count($temp)>1) {
                $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'championship_type'");
                $temp = false;
            }
            if (!empty($temp)){
                $temp = $temp[0];
                $elems = array(
                    "pe_item_id" => $_POST['pe_championship_type'],
                    "pe_author" => USER_ID,
                    "pe_datetime_edit" => 'NOW()'
                );
                $condition = array(
                    "pe_id"=>$temp['pe_id']
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."pages_extra",$elems, $condition)) {
                    $return = true;
                }
            } else {
                $elem = array(
                    $_POST['p_id'],
                    $_POST['pe_championship_type'],
                    'championship_type',
                    0,
                    'no',
                    'no',
                    'NOW()',
                    'NOW()',
                    USER_ID
                );
                if ($this->hdl->addElem(DB_T_PREFIX."pages_extra", $elem)) {
                    $return = true;
                }
            }
        }
        if (!empty($_POST['pe_championship_id'])){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "	pe_id,
                    pe_p_id,
                    pe_item_id",
                "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'championship'
						");
            if (count($temp)>1) {
                $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'championship'");
                $temp = false;
            }
            if ($temp){
                $temp = $temp[0];
                $elems = array(
                    "pe_item_id" => $_POST['pe_championship_id'],
                    "pe_author" => USER_ID,
                    "pe_datetime_edit" => 'NOW()'
                );
                $condition = array(
                    "pe_id"=>$temp['pe_id']
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."pages_extra",$elems, $condition)) {
                    $return = true;
                }
            } else {
                $elem = array(
                    $_POST['p_id'],
                    $_POST['pe_championship_id'],
                    'championship',
                    0,
                    'no',
                    'no',
                    'NOW()',
                    'NOW()',
                    USER_ID
                );
                if ($this->hdl->addElem(DB_T_PREFIX."pages_extra", $elem)) {
                    $return = true;
                }
            }
        } else {
            $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'championship'");
            $return = true;
        }
        return $return;
    }

    public function getPageExtraItem($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $res = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
            "	pe_id,
                pe_p_id,
                pe_item_id,
                pe_item_type",
            "	pe_p_id = '$p_id' AND
						pe_item_type IN ('championship', 'championship_logo', 'championship_type') AND
						pe_item_id != ''
					");
        if ($temp){
            foreach ($temp as $item){
                $res[$item['pe_item_type']] = $item;
            }
        }
        return $res;
    }

    public function getChampionshipList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group","
					chg_id as id,
					chg_title_ru as title
					","1 ORDER BY chg_title_ru ASC, chg_id DESC");
        return $temp;
    }

    public function getChampList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."champ","
					id,
					title_ru as title
					","1 ORDER BY title_ru ASC, id DESC");
        return $temp;
    }

    // PAGE_EXTRA ////////// END //////////////////////////////////////////////////////////////////////////////////////////////
}
