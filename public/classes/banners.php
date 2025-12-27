<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class banners{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getPageBanInfList($id = 0, $c_b = 0){
        $setting_id = array();
        $id = intval($id);
        if ($id<1) return false;
        $c_b = intval($c_b);
        if ($c_b <1) $c_b = 5;
        $list = [];
        $q_id = ''; // ������� �� ������� ���������

        $pages = [];
        $p_temp = $this->hdl->selectElem(DB_T_PREFIX."pages
			    "," p_id,
                    p_parent_id
                "," p_id = '$id'
                    limit 1
                ");
        if ($p_temp) {
            $q_id .= " OR pbi_page_id = '".$p_temp[0]['p_id']."' ";
            $pages[$p_temp[0]['p_id']] = $p_temp[0];
        } else return false;
        if ($p_temp[0]['p_parent_id']>0){
            while ($p_temp[0]['p_parent_id']>0){
                $p_temp = $this->hdl->selectElem(DB_T_PREFIX."pages
					"," p_id,
					    p_parent_id
					"," p_id = '".$p_temp[0]['p_parent_id']."'
					    limit 1
					");
                if ($p_temp) {
                    $q_id .= " OR pbi_page_id = '".$p_temp[0]['p_id']."' ";
                    $pages[$p_temp[0]['p_id']] = $p_temp[0];
                }
            }
        }
        $pages = array_reverse($pages, true);
        $setting = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer
			    "," pbi_pbi_id as id,
			        pbi_display,
			        pbi_page_id
			    "," pbi_item_type = 'setting'
			        AND ( pbi_page_id = '0' $q_id )
			        ORDER BY pbi_page_id ASC
			    ");
        $setting_id = [];
        if ($setting){
            foreach ($setting as $item){
                $pages[$item['pbi_page_id']]['settings'][] = $item;
            }
            foreach ($pages as $p_item){
                if (!empty($p_item['settings'])) {
                    foreach ($p_item['settings'] as $s_item) {
                        $setting_id[$s_item['id']] = $s_item;
                    }
                }
            }
        }
        $q_setting = '';
        if (!empty($setting_id)){
            foreach ($setting_id as $s_item)
                if ($s_item['pbi_display'] == 'no') $q_setting .= " AND pbi_id != '".$s_item['id']."' ";
        } else $q_setting = '';
        $list['center'] = $this->getPBIList('center', $setting_id, $q_id, $q_setting, $c_b);
        $list['left'] = $this->getPBIList('left', $setting_id, $q_id, $q_setting, $c_b);
        $list['right'] = $this->getPBIList('right', $setting_id, $q_id, $q_setting, $c_b);
        $classes = array();
        if (isset($list['center']) && is_array($list['center'])) foreach ($list['center'] as $item) if ($item['pbi_item_type'] == 'informer') $classes[] = $item['item']['i_class'];
        if (isset($list['left']) && is_array($list['left'])) foreach ($list['left'] as $item) if ($item['pbi_item_type'] == 'informer') $classes[] = $item['item']['i_class'];
        if (isset($list['right']) && is_array($list['right'])) foreach ($list['right'] as $item) if ($item['pbi_item_type'] == 'informer') $classes[] = $item['item']['i_class'];
        $list['classes'] = $classes;
        return $list;

    }

    private function getPBIList($place = '', $setting_id = false, $q_id = null, $q_setting = null, $c_b = 5){
        $c_b = intval($c_b);
        if ($c_b<1) $c_b = 5;
        if ($place == 'center') $place = 'center';
        elseif ($place == 'left') $place = 'left';
        elseif ($place == 'right') $place = 'right';
        else return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","*","pbi_item_type != 'setting' AND pbi_place = '$place' AND pbi_display_type = 'fixed' AND ( pbi_page_id = '0' $q_id ) $q_setting ORDER BY pbi_order DESC LIMIT 0, $c_b");
        if (!is_array($temp) || count($temp) < $c_b) {
            if ($temp) $c_b = $c_b-count($temp);
            $t_c = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","*","pbi_item_type != 'setting' AND pbi_place = '$place' AND pbi_display_type = 'shuffle' AND ( pbi_page_id = '0' $q_id ) $q_setting ORDER BY RAND() LIMIT 0, $c_b");
            if ($t_c) foreach ($t_c as $item) $temp[] = $item;
        }
        if ($temp){
            $q_banners = $q_informer = '';
            foreach ($temp as $item){
                if ($item['pbi_item_type'] == 'banner'){
                    $q_banners .= " OR b_id = '".$item['pbi_item_id']."'";
                }
                if ($item['pbi_item_type'] == 'informer'){
                    $q_informer .= " OR i_id = '".$item['pbi_item_id']."'";
                }
            }
            $banners_id = array();
            if (!empty($q_banners)) {
                $q_banners = " AND (".substr($q_banners, 4)." )";
                $banners = $this->hdl->selectElem(DB_T_PREFIX."banners",
                    "	b_id as id,
												b_is_active as is_active,
												b_is_datetime as is_datetime,
												b_datetime_from as datetime_from,
												b_datetime_to as datetime_to,
												b_url as url,
												b_path as path,
												b_target as target,
												b_noindex as noindex,
												b_title_".S_LANG." as title,
												b_type,
												b_code",
                    "	b_is_delete = 'no' AND
												( b_is_datetime = 'yes' OR ( b_is_datetime = 'NO' AND b_datetime_from < NOW() AND b_datetime_to > NOW() ))
												$q_banners
												ORDER BY b_id ASC");
                if ($banners)
                    foreach ($banners as $item){
                        $item['b_code'] = stripslashes($item['b_code']);
                        $banners_id[$item['id']] = $item;
                    }
            }
            $informers_id = array();
            if (!empty($q_informer)) {
                $q_informer = " AND (".substr($q_informer, 4)." )";
                $informers = $this->hdl->selectElem(DB_T_PREFIX."informers",
                    "	i_id as id,
												i_is_active as is_active,
												i_is_datetime as is_datetime,
												i_datetime_from as datetime_from,
												i_datetime_to as datetime_to,
												i_path,
												i_class,
												i_code,
												i_title_".S_LANG." as i_title,
												i_description_".S_LANG." as i_description",
                    "	i_is_delete = 'no' AND
												( i_is_datetime = 'yes' OR ( i_is_datetime = 'NO' AND i_datetime_from < NOW() AND i_datetime_to > NOW() ))
												$q_informer
												ORDER BY i_id ASC");
                if ($informers){
                    foreach ($informers as &$item){
                        $item['i_code'] = stripslashes($item['i_code']);
                        $informers_id[$item['id']] = $item;
                    }
                }
            }

            for ($k=0; $k<count($temp); $k++){
                for ($i=0; $i<count($temp); $i++){
                    if ($i==0) $temp_item = $temp[$i];
                    else {
                        if ($temp[$i]['pbi_order'] > $temp_item['pbi_order']) {
                            $temp[$i-1] = $temp[$i];
                            $temp[$i] = $temp_item;
                        } else $temp_item = $temp[$i];
                    }
                }
            }

            for ($i=0; $i<count($temp); $i++){
                if (is_array($temp[$i])) if ($temp[$i]['pbi_item_type'] == 'banner' && isset($banners_id[$temp[$i]['pbi_item_id']])) $temp[$i]['item'] = $banners_id[$temp[$i]['pbi_item_id']];
                if (is_array($temp[$i])) if ($temp[$i]['pbi_item_type'] == 'informer' && isset($informers_id[$temp[$i]['pbi_item_id']])) $temp[$i]['item'] = $informers_id[$temp[$i]['pbi_item_id']];
                if (is_array($temp[$i])) if (isset($setting_id[$temp[$i]['pbi_id']])) $temp[$i]['pbi_display'] = $setting_id[$temp[$i]['pbi_id']]['pbi_display'];
                //$list[$temp[$i]['pbi_place']][] = $temp[$i];
            }
        }
        return $temp;
    }

}
?>
