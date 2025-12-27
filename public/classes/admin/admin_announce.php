<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class announce{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // јнонсы ///////////////////////////////////////////////////////////////////////////////////////

    public function createAnnounce($post){ // добавление анонса
        if($post['an_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['an_type']=='game') $an_type ='game';
        else $an_type = 'event';
        if($post['an_is_main']==true){
            $is_main ='yes';
            $this->discardMainAnnounce();
        } else $is_main = 'no';
        if ($post['an_date_day'] != '' and $post['an_date_day']>0 and $post['an_date_month'] != '' and $post['an_date_month']>0 and $post['an_date_year'] != '' and $post['an_date_year']>0 and $post['an_date_hour'] != '' and $post['an_date_minute'] != '') {
            $post['an_date_day'] = intval($post['an_date_day']);
            $post['an_date_month'] = intval($post['an_date_month']);
            $post['an_date_year'] = intval($post['an_date_year']);
            $post['an_date_hour'] = intval($post['an_date_hour']);
            $post['an_date_minute'] = intval($post['an_date_minute']);
            if ($post['an_date_day'] < 10) $post['an_date_day'] = '0'.$post['an_date_day'];
            if ($post['an_date_month'] < 10) $post['an_date_month'] = '0'.$post['an_date_month'];
            if ($post['an_date_hour'] < 10) $post['an_date_hour'] = '0'.$post['an_date_hour'];
            if ($post['an_date_minute'] < 10) $post['an_date_minute'] = '0'.$post['an_date_minute'];
            $an_date_show = $post['an_date_year']."-".$post['an_date_month']."-".$post['an_date_day']." ".$post['an_date_hour'].":".$post['an_date_minute'].":00";
        }
        $elem = array(
            $an_type,
            (strlen(trim(html_entity_decode(strip_tags($post['an_description_ru']))))>0) ? addslashes(strip_tags($post['an_description_ru'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['an_description_ua']))))>0) ? addslashes(strip_tags($post['an_description_ua'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['an_description_en']))))>0) ? addslashes(strip_tags($post['an_description_en'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL,
            $an_date_show,
            '',
            intval($post['an_owner_t_id']),
            addslashes($post['an_owner_t_title']),
            '',
            intval($post['an_guest_t_id']),
            addslashes($post['an_guest_t_title']),
            '',
            addslashes($post['an_link']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $is_main
        );
        $an_description_ru = (strlen(trim(html_entity_decode(strip_tags($post['an_description_ru']))))>0) ? addslashes(strip_tags($post['an_description_ru'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL;
        $an_description_ua = (strlen(trim(html_entity_decode(strip_tags($post['an_description_ua']))))>0) ? addslashes(strip_tags($post['an_description_ua'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL;
        $an_description_en = (strlen(trim(html_entity_decode(strip_tags($post['an_description_en']))))>0) ? addslashes(strip_tags($post['an_description_en'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."announce","an_id","an_type='$an_type'
				AND an_description_ru = '$an_description_ru'
				AND an_description_ua = '$an_description_ua'
				AND an_description_en = '$an_description_en'
				AND an_datetime = '$an_date_show'
				AND an_owner_t_id = '".intval($post['an_owner_t_id'])."'
				AND an_owner_t_title = '".addslashes($post['an_owner_t_title'])."'
				AND an_guest_t_id = '".intval($post['an_guest_t_id'])."'
				AND an_guest_t_title = '".addslashes($post['an_guest_t_title'])."'
				AND an_link = '".addslashes($post['an_link'])."' LIMIT 1
			");

        if (!$temp) $id = $this->hdl->addElem(DB_T_PREFIX."announce", $elem);
        if ($id>0) {
            $dir = "upload/announce/";
            if ($an_type == 'event'){
                $file = $_FILES['an_photo_event'];
                if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                    $n_file = $dir."event_".$id.strrchr($file['name'], ".");
                    if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                        $elems["an_photo_event"] = $n_file;
                        $condition["an_id"] = $id;
                        $this->hdl->updateElem(DB_T_PREFIX."announce",$elems, $condition);
                    }
                }
            }
            if ($an_type == 'game'){
                $file = $_FILES['an_owner_t_logo'];
                if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                    $n_file = $dir."game_".$id."_owner".strrchr($file['name'], ".");
                    if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                        $elems["an_owner_t_logo"] = $n_file;
                        $condition["an_id"] = $id;
                        $this->hdl->updateElem(DB_T_PREFIX."announce",$elems, $condition);
                    }
                }
                $file = $_FILES['an_guest_t_logo'];
                if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                    $n_file = $dir."game_".$id."_guest".strrchr($file['name'], ".");
                    if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                        $elems["an_guest_t_logo"] = $n_file;
                        $condition["an_id"] = $id;
                        $this->hdl->updateElem(DB_T_PREFIX."announce",$elems, $condition);
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function updateAnnounce($post){ // редактирование анонса
        $temp = $this->hdl->selectElem(DB_T_PREFIX."announce","an_id, an_type, an_photo_event, an_owner_t_logo, an_guest_t_logo","an_id='".$post['an_id']."' LIMIT 1");
        if ($temp) $temp = $temp[0];
        else return false;
        if($post['an_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['an_type']=='game') $an_type ='game';
        else $an_type = 'event';
        if($post['an_is_main']==true){
            $is_main ='yes';
            $this->discardMainAnnounce();
        } else $is_main = 'no';
        if ($post['an_date_day'] != '' and $post['an_date_day']>0 and $post['an_date_month'] != '' and $post['an_date_month']>0 and $post['an_date_year'] != '' and $post['an_date_year']>0 and $post['an_date_hour'] != '' and $post['an_date_minute'] != '') {
            $post['an_date_day'] = intval($post['an_date_day']);
            $post['an_date_month'] = intval($post['an_date_month']);
            $post['an_date_year'] = intval($post['an_date_year']);
            $post['an_date_hour'] = intval($post['an_date_hour']);
            $post['an_date_minute'] = intval($post['an_date_minute']);
            if ($post['an_date_day'] < 10) $post['an_date_day'] = '0'.$post['an_date_day'];
            if ($post['an_date_month'] < 10) $post['an_date_month'] = '0'.$post['an_date_month'];
            if ($post['an_date_hour'] < 10) $post['an_date_hour'] = '0'.$post['an_date_hour'];
            if ($post['an_date_minute'] < 10) $post['an_date_minute'] = '0'.$post['an_date_minute'];
            $an_date_show = $post['an_date_year']."-".$post['an_date_month']."-".$post['an_date_day']." ".$post['an_date_hour'].":".$post['an_date_minute'].":00";
        }
        $elems = array(
            "an_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['an_description_ru']))))>0) ? addslashes(strip_tags($post['an_description_ru'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL,
            "an_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['an_description_ua']))))>0) ? addslashes(strip_tags($post['an_description_ua'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL,
            "an_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['an_description_en']))))>0) ? addslashes(strip_tags($post['an_description_en'], "<p>, <i>, <strong>, <a>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>")) : NULL,
            "an_is_active" => $is_active,
            "an_datetime_edit" => 'NOW()',
            "an_author" => USER_ID,
            "an_datetime" => $an_date_show,
            "an_link" => addslashes($post['an_link']),
            "an_is_main" => $is_main
        );
        $dir = "upload/announce/";
        if ($temp['an_type'] == 'event'){
            $file = $_FILES['an_photo_event'];
            if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                if (is_file($temp['an_photo_event'])) unlink ($temp['an_photo_event']);
                $n_file = $dir."event_".$post['an_id'].strrchr($file['name'], ".");
                if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                    $elems["an_photo_event"] = $n_file;
                }
            }
        }
        if ($temp['an_type'] == 'game'){
            $elems["an_owner_t_id"] = intval($post['an_owner_t_id']);
            $elems["an_owner_t_title"] = addslashes($post['an_owner_t_title']);
            $file = $_FILES['an_owner_t_logo'];
            if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                if (is_file($temp['an_owner_t_logo'])) unlink ($temp['an_owner_t_logo']);
                $n_file = $dir."game_".$post['an_id']."_owner".strrchr($file['name'], ".");
                if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                    $elems["an_owner_t_logo"] = $n_file;
                }
            }
            $elems["an_guest_t_id"] = intval($post['an_guest_t_id']);
            $elems["an_guest_t_title"] = addslashes($post['an_guest_t_title']);
            $file = $_FILES['an_guest_t_logo'];
            if ($file['error'] == 0 AND $file['size'] != 0 AND $file['name'] != ''){
                if (is_file($temp['an_guest_t_logo'])) unlink ($temp['an_guest_t_logo']);
                $n_file = $dir."game_".$post['an_id']."_guest".strrchr($file['name'], ".");
                if (move_uploaded_file($file['tmp_name'], "../".$n_file)) {
                    $elems["an_guest_t_logo"] = $n_file;
                }
            }
        }
        $condition = array(
            "an_id"=>$post['an_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."announce",$elems, $condition)) return true;
        else return false;
    }

    public function discardMainAnnounce(){
        $elems = array(
            "an_is_main " => 'no'
        );
        if ($this->hdl->updateAll(DB_T_PREFIX."announce",$elems)) return true;
        else return false;
    }

    public function getAnnounceList($page=1, $perpage=10, $gallery = ''){
        $extra_q = '';
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage<1) $perpage = 10;
        $page = $perpage*$page;
        if ($gallery == 'event') $extra_q = " AND an_type = 'event' ";
        if ($gallery == 'game') $extra_q = " AND an_type = 'game' ";

        $teams_temp = $this->hdl->selectElem(DB_T_PREFIX."team","t_title_ru, t_id","1 ORDER BY t_id ASC");
        if ($teams_temp) foreach ($teams_temp as $item) $teams[$item['t_id']] = $item['t_title_ru'];

        $temp = $this->hdl->selectElem(DB_T_PREFIX."announce","*","1 $extra_q ORDER BY an_id DESC LIMIT $page, $perpage");
        for ($i=0; $i<count($temp); $i++){
            if ($temp[$i]['an_owner_t_id']>0 and $teams[$temp[$i]['an_owner_t_id']] != '') $temp[$i]['an_owner_t_id_title'] = $teams[$temp[$i]['an_owner_t_id']];
            if ($temp[$i]['an_guest_t_id']>0 and $teams[$temp[$i]['an_guest_t_id']] != '') $temp[$i]['an_guest_t_id_title'] = $teams[$temp[$i]['an_guest_t_id']];
        }
        return $temp;
    }

    public function getAnnounceItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."announce","*","an_id=$item");
            $temp = $temp[0];
            $temp['an_description_ru'] = stripcslashes($temp['an_description_ru']);
            $temp['an_description_ua'] = stripcslashes($temp['an_description_ua']);
            $temp['an_description_en'] = stripcslashes($temp['an_description_en']);
            return $temp;
        } else return false;
    }

    public function deleteAnnounce($an_id){
        $an_id = intval($an_id);
        if ($an_id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."announce", "an_id='$an_id'")) {
                //unlink ( string filename);
                return true;
            }
        }
        return false;
    }

    public function getAnnouncePages($page=1, $perpage=10, $gallery = 0){
        $extra_q = '';
        $gallery = intval($gallery);
        if ($gallery > 0) $extra_q = " AND an_nc_id = '$gallery' ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."announce","COUNT(*) as C_N","an_is_active='yes' and an_date_show<=NOW() $extra_q");
        $c_pages = intval($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i <= $c_pages) $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                //$pages[9] = "...";
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                //$pages[$page-6] = "...";
                //if ($page+4 <= $c_pages) $pages[$page+4] = "...";
            }
        }
        return $pages;
    }

    public function getAnnounceSettings(){
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

    public function getAnnounceTeams(){
        return $this->hdl->selectElem(DB_T_PREFIX."team","t_id, t_title_ru as title","t_is_active = 'yes' ORDER BY t_title_ru ASC");
    }

    public function getChampionshipList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_local
						","	chl_id,
							chl_title_ru
						","1 ORDER BY chl_id ASC");
        if ($temp) foreach ($temp as $item) $locals[$item['chl_id']] = $item;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group
						","	chg_id,
							chg_title_ru,
							chg_chl_id
						","1 ORDER BY chg_id ASC");
        if ($temp) foreach ($temp as $item) {
            $item['chl_title_ru'] = $locals[$item['chg_chl_id']]['chl_title_ru'];
            $groups[$item['chg_id']] = $item;
        }

        $ch_list = $this->hdl->selectElem(DB_T_PREFIX."championship
						","	ch_id,
							ch_title_ru,
							ch_chg_id
						","1 ORDER BY ch_text_ru ASC, ch_chg_id ASC, ch_date_from DESC, ch_id DESC");
        if ($ch_list) foreach ($ch_list as &$item) $item['title'] = $groups[$item['ch_chg_id']]['chl_title_ru']." / ".$groups[$item['ch_chg_id']]['chg_title_ru']." / ".$item['ch_title_ru'];
        return $ch_list;
    }
}
?>
