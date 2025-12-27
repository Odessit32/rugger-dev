<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class PageCountryExtra{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // PAGE_EXTRA ////////// BEGIN //////////////////////////////////////////////////////////////////////////////////////////////
    public function savePageExtra(){
        $_POST['p_id'] = intval($_POST['p_id']);
        $_POST['pe_country_id'] = intval($_POST['pe_country_id']);
        if ($_POST['p_id'] < 1) return false;
        if (!empty($_FILES['country_flag'])){
            $file = $_FILES['country_flag'];
            if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                $dir = "upload/country_logo/";
                $n_file = $dir.$_POST['p_id'].strrchr($file['name'], ".");
                if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                    $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                        "	pe_id,
                            pe_p_id,
                            pe_item_id",
                        "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'country_logo'
						");
                    if (count($temp)>1) {
                        $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'country_logo'");
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
                        if ($this->hdl->updateElem(DB_T_PREFIX."pages_extra",$elems, $condition)) return true;
                    } else {
                        $elem = array(
                            $_POST['p_id'],
                            $n_file,
                            'country_logo',
                            0,
                            'no',
                            'no',
                            'NOW()',
                            'NOW()',
                            USER_ID
                        );
                        if ($this->hdl->addElem(DB_T_PREFIX."pages_extra", $elem)) return true;
                    }
                }
            }
        }
        if ($_POST['pe_country_id']>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "	pe_id,
                    pe_p_id,
                    pe_item_id",
                "	pe_p_id = '".$_POST['p_id']."' AND
							pe_item_type = 'country'
						");
            if (count($temp)>1) {
                $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'country'");
                $temp = false;
            }
            if ($temp){
                $temp = $temp[0];
                $elems = array(
                    "pe_item_id" => $_POST['pe_country_id'],
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
                    $_POST['pe_country_id'],
                    'country',
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
            $this->hdl->delElem(DB_T_PREFIX."pages_extra", "pe_p_id='".$_POST['p_id']."' AND pe_item_type = 'country'");
            return true;
        }
        return false;
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
				pe_item_type IN ('country', 'country_logo') AND
				pe_item_id != ''
			");
        if ($temp){
            foreach ($temp as $item){
                switch ($item['pe_item_type']) {
                    case 'country_logo':
                        $res['country_logo'] = $item;
                        break;
                    case 'country':
                        $res['country'] = $item;
                        break;
                }
            }
        }
        return $res;
    }

    public function getCountryList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."country","
					cn_id as id,
					cn_title_ru as title
					","1 ORDER BY cn_title_ru ASC, cn_id DESC");
        return $temp;
    }

    // PAGE_EXTRA ////////// END //////////////////////////////////////////////////////////////////////////////////////////////
}
