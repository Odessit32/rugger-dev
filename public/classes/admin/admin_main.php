<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class main{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // CACHE VARS ///////////// BEGIN //////////
    public function ConfigUpdateVar($var_name = '', $var_value = '0'){
        $conf_vars = array(
            array(
                'name' => 'IS_CACHING',
                'value' => $var_name=='IS_CACHING'?($var_value?'true':'false'):(IS_CACHING?'true':'false')
            ),
            array(
                'name' => 'CACHING_TYPE',
                'value' => $var_name=='CACHING_TYPE'?($var_value?'1':'0'):CACHING_TYPE
            ),
            array(
                'name' => 'CACHING_LIFETIME',
                'value' => $var_name=='CACHING_LIFETIME'?$var_value:CACHING_LIFETIME
            )
        );
        $saving_text = "<?php\n";
        foreach($conf_vars as $conf_var){
            $saving_text .= 'if (!defined(\''.$conf_var['name'].'\')) {'."\n".
                "\t".'define(\''.$conf_var['name'].'\', '.$conf_var['value'].');'."\n".
                '}';
        }
        if (file_exists("../classes/config_".php_uname('n').".php")){
            $fp = fopen("../classes/config_".php_uname('n').".php", 'w+');
            if (fwrite($fp, $saving_text)){
                fclose($fp);
                return true;
            }
            fclose($fp);
        }
        return false;
    }
    // CACHE VARS ///////////// END   //////////

    // LANG SETTINGS ////////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////

    public function getMainSettings(){
        $list_temp = $this->hdl->selectElem(DB_T_PREFIX."settings_lang","*","1 ORDER BY sl_id ASC LIMIT 0, 3");
        if ($list_temp)
            foreach ($list_temp as $item) $list[$item['sl_title']] = $item;
        return $list;
    }

    public function updateMainSettings($elems, $condition){
        if ($this->hdl->updateElem(DB_T_PREFIX."settings_lang",$elems, $condition)) return true;
        else return false;
    }

    // MAIN BANNER INFORMER ////////// КОНЕЦ ///////////////////////////////////////////////////////////////////////////////

    // MAIN BANNER INFORMER ////////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////
    public function getMainBanInfList(){ // список баннеров и информеров для страницы (уже добавленых)

        $temp = $this->hdl->selectElem(DB_T_PREFIX."main_banner_informer","*","1 ORDER BY mbi_order DESC");
        if ($temp){
            $q_banners = '';
            $q_informer = '';
            if ($temp){
                $q_banners = ' AND b_id IN (';
                $q_informer = ' AND i_id IN (';
                foreach ($temp as $item){
                    if ($item['mbi_item_type'] == 'banner'){
                        $q_banners .= " ".$item['mbi_item_id'].", ";
                    }
                    if ($item['mbi_item_type'] == 'informer'){
                        $q_informer .= " ".$item['mbi_item_id'].", ";
                    }
                }
                $q_banners = substr($q_banners, 0, -2).') ';
                $q_informer = substr($q_informer, 0, -2).') ';
            }
            $banners = $this->hdl->selectElem(DB_T_PREFIX."banners",
                "	b_id as id,
												b_is_active as is_active,
												b_is_datetime as is_datetime,
												b_datetime_from as datetime_from,
												b_datetime_to as datetime_to,
												b_title_ru as title","b_is_delete = 'no' $q_banners ORDER BY b_id ASC");
            if ($banners)
                foreach ($banners as $item){
                    $banners_id[$item['id']] = $item;
                }
            $informers = $this->hdl->selectElem(DB_T_PREFIX."informers",
                "	i_id as id,
												i_is_active as is_active,
												i_is_datetime as is_datetime,
												i_datetime_from as datetime_from,
												i_datetime_to as datetime_to,
												i_title_ru as title","i_is_delete = 'no' $q_informer ORDER BY i_id ASC");
            if ($informers)
                foreach ($informers as $item){
                    $informers_id[$item['id']] = $item;
                }
            $c_temp = count($temp);
            for ($i=0; $i<$c_temp; $i++){
                if ($temp[$i]['mbi_item_type'] == 'banner') {
                    if (isset($banners_id[$temp[$i]['mbi_item_id']])) {
                        $temp[$i]['item'] = $banners_id[$temp[$i]['mbi_item_id']];
                    } else {
                        unset($temp[$i]);
                    }
                } elseif ($temp[$i]['mbi_item_type'] == 'informer') {
                    if (isset($informers_id[$temp[$i]['mbi_item_id']])) {
                        $temp[$i]['item'] = $informers_id[$temp[$i]['mbi_item_id']];
                    } else {
                        unset($temp[$i]);
                    }
                } else {
                    unset($temp[$i]);
                }
            }
        }
        return $temp;
    }

    public function getBanInfList(){ // список баннеров и информеров (не добавленных)

        $q_id = ''; // баннера со страниц родителей

        $q_banners = $q_informer = '';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."main_banner_informer","*","1 ORDER BY mbi_order DESC");
        if ($temp){
            foreach ($temp as $item){
                if ($item['mbi_item_type'] == 'banner'){
                    $q_banners .= " AND b_id != '".$item['mbi_item_id']."'";
                }
                if ($item['mbi_item_type'] == 'informer'){
                    $q_informer .= " AND i_id != '".$item['mbi_item_id']."'";
                }
            }
        }
        $banners = $this->hdl->selectElem(DB_T_PREFIX."banners",
            "	b_id as id,
												b_is_active as is_active,
												b_is_datetime as is_datetime,
												b_datetime_from as datetime_from,
												b_datetime_to as datetime_to,
												b_title_ru as title","b_is_delete = 'no' $q_banners ORDER BY b_title_ru ASC, b_id ASC");
        $list['banners'] = $banners;
        $informers = $this->hdl->selectElem(DB_T_PREFIX."informers",
            "	i_id as id,
												i_is_active as is_active,
												i_is_datetime as is_datetime,
												i_datetime_from as datetime_from,
												i_datetime_to as datetime_to,
												i_title_ru as title","i_is_delete = 'no' $q_informer ORDER BY i_title_ru ASC, i_id ASC");
        $list['informers'] = $informers;
        return $list;
    }

    public function deleteMainBannerInformer($id = 0){ // удаление баннера или информера со страницы
        $id = intval($id);
        if ($id <1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."main_banner_informer","mbi_id","mbi_id = '$id' LIMIT 1");
        if ($temp) if ($this->hdl->delElem(DB_T_PREFIX."main_banner_informer", " mbi_id ='$id'")) return true;
        return false;
    }

    public function saveMainBannerInformer($id = 0){ // сохранение баннера или информера на странице
        $id = intval($id);
        if ($id <1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."main_banner_informer","mbi_id","mbi_id = '$id' LIMIT 1");
        if ($temp){
            if ($_POST['mbi_item_type'] == 'banner') {
                $elems['mbi_item_type'] = 'banner';
                $elems['mbi_item_id'] = intval($_POST['mbi_banner_id']);
                if ($_POST['mbi_place'] == 'right') $elems['mbi_place'] = 'right';
                elseif ($_POST['mbi_place'] == 'left') $elems['mbi_place'] = 'left';
                elseif ($_POST['mbi_place'] == 'down') $elems['mbi_place'] = 'down';
                else $elems['mbi_place'] = 'center';
                $elems['mbi_order'] = intval($_POST['mbi_order']);
                if ($_POST['mbi_display_type'] == 'fixed') $elems['mbi_display_type'] = 'fixed';
                else $elems['mbi_display_type'] = 'shuffle';
                $condition = array(
                    "mbi_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."main_banner_informer",$elems, $condition)) return true;
            }
            if ($_POST['mbi_item_type'] == 'informer') {
                $elems['mbi_item_type'] = 'informer';
                $elems['mbi_item_id'] = intval($_POST['mbi_informer_id']);
                if ($_POST['mbi_place'] == 'right') $elems['mbi_place'] = 'right';
                elseif ($_POST['mbi_place'] == 'left') $elems['mbi_place'] = 'left';
                elseif ($_POST['mbi_place'] == 'down') $elems['mbi_place'] = 'down';
                else $elems['mbi_place'] = 'center';
                $elems['mbi_order'] = intval($_POST['mbi_order']);
                if ($_POST['mbi_display_type'] == 'fixed') $elems['mbi_display_type'] = 'fixed';
                else $elems['mbi_display_type'] = 'shuffle';
                $condition = array(
                    "mbi_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."main_banner_informer",$elems, $condition)) return true;
            }
        }
        return false;
    }

    public function addMainBannerInformer(){ // добавление баннера или информера на страницу

        if ($_POST['mbi_place'] == 'right') $_POST['mbi_place'] = 'right';
        elseif ($_POST['mbi_place'] == 'left') $_POST['mbi_place'] = 'left';
        elseif ($_POST['mbi_place'] == 'down') $_POST['mbi_place'] = 'down';
        else $_POST['mbi_place'] = 'center';
        if ($_POST['mbi_display_type'] == 'fixed') $_POST['mbi_display_type'] = 'fixed';
        else $_POST['mbi_display_type'] = 'shuffle';
        if ($_POST['add_main_banner']){
            $_POST['mbi_banner_id'] = intval($_POST['mbi_banner_id']);
            if ($_POST['mbi_banner_id']<1) return false;
            $iData = array(
                "NOW()",
                USER_ID,
                $_POST['mbi_banner_id'],
                'banner',
                $_POST['mbi_place'],
                intval($_POST['mbi_order']),
                $_POST['mbi_display_type']
            );
            $temp = $this->hdl->selectElem(DB_T_PREFIX."main_banner_informer","mbi_id",
                "	mbi_item_id = '".$_POST['mbi_banner_id']."'
											AND mbi_item_type = 'banner'
											AND mbi_place = '".$_POST['mbi_place']."'
											LIMIT 1");
            if ($temp) return false;
            if ($this->hdl->addElem(DB_T_PREFIX."main_banner_informer", $iData)) return true;
        }
        if ($_POST['add_main_informer']){
            $_POST['mbi_informer_id'] = intval($_POST['mbi_informer_id']);
            if ($_POST['mbi_informer_id']<1) return false;
            $iData = array(
                "NOW()",
                USER_ID,
                $_POST['mbi_informer_id'],
                'informer',
                $_POST['mbi_place'],
                intval($_POST['mbi_order']),
                $_POST['mbi_display_type']
            );
            $temp = $this->hdl->selectElem(DB_T_PREFIX."main_banner_informer","mbi_id",
                "	mbi_item_id = '".$_POST['mbi_informer_id']."'
											AND mbi_item_type = 'informer'
											AND mbi_place = '".$_POST['mbi_place']."'
											LIMIT 1");
            if ($temp) return false;
            if ($this->hdl->addElem(DB_T_PREFIX."main_banner_informer", $iData)) return true;
        }
        return false;
    }

    // MAIN BANNER INFORMER ////////// КОНЕЦ ///////////////////////////////////////////////////////////////////////////////

    public function getSettings(){
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

    public function saveSettings($elems, $condition){
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$elems, $condition)) return true;
        else return false;
    }
}
?>
