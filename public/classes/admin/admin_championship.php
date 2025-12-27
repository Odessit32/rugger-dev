<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_base.php');

class championship extends adminBase{
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getCityList(){
        return $this->hdl->selectElem(DB_T_PREFIX."city","*","1 ORDER BY ct_order DESC, ct_id ASC");
    }

    // COMPOSITION OF THE CHAMPIONSHIP TEAM //////////// START /////////////////////////////////////////////////////////////////////////

    public function createConnectTCh($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch","cntch_id"," cntch_t_id = '".intval($post['team_id'])."' AND cntch_ch_id = '".intval($post['ch_id'])."' AND cntch_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cntch_id']>0) return false;
        if($post['cntch_is_technical']==true) $is_technical ='yes';
        else $is_technical = 'no';
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $ch_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elem = array(
            intval($post['team_id']),
            intval($post['ch_id']),
            $ch_date_add,
            USER_ID,
            'no',
            $is_technical
        );
        if ($this->hdl->addElem(DB_T_PREFIX."connection_t_ch", $elem)) return true;
        else return false;
    }

    public function updateConnectTCh($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch","cntch_id"," cntch_t_id = '".intval($post['team_id'])."' AND cntch_id = '".intval($post['cntch_id'])."' AND cntch_ch_id = '".intval($post['ch_id'])."' AND cntch_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cntch_id']>0) return false;
        if($post['cntch_is_technical']==true) $is_technical ='yes';
        else $is_technical = 'no';
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $ch_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elems = array(
            "cntch_date_add" => $ch_date_add,
            "cntch_add_author" => USER_ID,
            "cntch_is_technical" => $is_technical
        );
        $condition = array(
            "cntch_id"=>$post['cntch_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_t_ch",$elems, $condition)) return true;
        else return false;
    }

    public function deleteConnectTCh($post){
        $tch = intval($post['cntch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch","cntch_id, cntch_t_id, cntch_ch_id"," cntch_id = '".$tch."' LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $t_id = $temp['cntch_t_id'];
            $ch_id = $temp['cntch_ch_id'];
            if ($this->hdl->delElem(DB_T_PREFIX."connection_t_ch", "cntch_id = '$tch' LIMIT 1")) {
                $games = $this->hdl->selectElem(DB_T_PREFIX."games","g_id","g_ch_id = '".$ch_id."' AND (g_owner_t_id = '".$t_id."' OR g_guest_t_id = '".$t_id."') ORDER BY g_id ASC");
                if ($games)
                    foreach ($games as $item) {
                        $this->hdl->delElem(DB_T_PREFIX."games_actions", "ga_g_id = '".$item['g_id']."'");
                        $this->hdl->delElem(DB_T_PREFIX."connection_g_st", "cngst_g_id = '".$item['g_id']."'");
                        // удаление фото и видео
                        $this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_type = 'game' AND phg_type_id = '".$item['g_id']."'");
                        $photos = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_type = 'game' AND ph_type_id = '".$item['g_id']."' ORDER BY ph_id ASC");
                        if (!empty($photos))
                            foreach ($photos as $i_photo) {
                                if (file_exists ("../upload/photos".$i_photo['ph_folder'].$i_photo['ph_path'])) unlink("../upload/photos".$i_photo['ph_folder'].$i_photo['ph_path']);
                                $o_file = "../upload/photos".$i_photo['ph_folder'].substr($i_photo['ph_path'], 0, strlen(strrchr($i_photo['ph_path'], "."))*(-1))."-informer".strrchr($i_photo['ph_path'], ".");
                                if (file_exists ($o_file)) unlink($o_file);
                                $o_file = "../upload/photos".$i_photo['ph_folder'].substr($i_photo['ph_path'], 0, strlen(strrchr($i_photo['ph_path'], "."))*(-1))."-small".strrchr($i_photo['ph_path'], ".");
                                if (file_exists ($o_file)) unlink($o_file);
                                $o_file = "../upload/photos".$i_photo['ph_folder'].substr($i_photo['ph_path'], 0, strlen(strrchr($i_photo['ph_path'], "."))*(-1))."-main".strrchr($i_photo['ph_path'], ".");
                                if (file_exists ($o_file)) unlink($o_file);
                                $o_file = "../upload/photos".$i_photo['ph_folder'].substr($i_photo['ph_path'], 0, strlen(strrchr($i_photo['ph_path'], "."))*(-1))."-s_main".strrchr($i_photo['ph_path'], ".");
                                if (file_exists ($o_file)) unlink($o_file);
                                $this->hdl->delElem(DB_T_PREFIX."photos", "ph_id = '".$i_photo['ph_id']."' LIMIT 1");
                            }
                        $this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_type = 'game' AND vg_type_id = '".$item['g_id']."'");
//						$photos = $this->hdl->selectElem(DB_T_PREFIX."videos","v_id, v_folder","v_type = 'game' AND v_type_id = '".$item['g_id']."' ORDER BY v_id ASC");
                        if (!empty($videos))
                            foreach ($videos as $i_video) {
                                if (file_exists ("../upload/video_thumbs".$i_video['v_folder'].$i_video['v_id'].".jpg")) unlink("../upload/video_thumbs".$i_video['v_folder'].$i_video['v_id'].".jpg");
                                if (file_exists ("../upload/video_thumbs".$i_video['v_folder'].$i_video['v_id']."-small.jpg")) unlink("../upload/video_thumbs".$i_video['v_folder'].$i_video['v_id']."-small.jpg");
                                $this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '".$i_video['v_id']."' LIMIT 1");
                            }
                        $this->hdl->delElem(DB_T_PREFIX."games", "g_id = '".$item['g_id']."' LIMIT 1");
                    }
                return true;
            } else return false;
        } return false;
        $elems = array(
            "cntch_is_delete" => 'yes'
        );
        $condition = array(
            "cntch_id"=>$post['cntch_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_t_ch",$elems, $condition)) return true;
        else return false;
    }

    public function getChTItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team","*","cntch_id = '$item' AND cntch_is_delete = 'no' AND cntch_t_id = t_id LIMIT 1");
            return $temp[0];
        } else return false;
    }

    public function getChampionshipTeamList($ch_id = 0){
        $ch_id = intval($ch_id);
        if ($ch_id<1) return false;
        $q_team = '';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team","*"," cntch_ch_id = '$ch_id' AND cntch_t_id=t_id AND cntch_is_delete = 'no' ORDER BY t_title_ru ASC");
        if ($temp){
            foreach ($temp as $val){
                $q_team .= " AND t_id != '".$val['cntch_t_id']."'";
            }
            $list['on'] = $temp;
        } else $list['on'] = false;
        if (strlen($q_team)>0) $q_team = substr($q_team, 5);
        else $q_team = 1;
        $list['off'] = $this->hdl->selectElem(DB_T_PREFIX."team","*"," $q_team ORDER BY t_title_ru ASC");
        //var_dump($list);
        return $list;
    }
    // COMPOSITION OF THE CHAMPIONSHIP TEAM //////////// END  /////////////////////////////////////////////////////////////////////////

    // Categories /////////////////////////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['chc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            str_replace($search, $replace, $post['chc_title_ru']),
            str_replace($search, $replace, $post['chc_title_ua']),
            str_replace($search, $replace, $post['chc_title_en']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX."championship_categories", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['chc_id'] <1) return false;
        if($post['chc_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "chc_title_ru" => str_replace($search, $replace, $post['chc_title_ru']),
            "chc_title_ua" => str_replace($search, $replace, $post['chc_title_ua']),
            "chc_title_en" => str_replace($search, $replace, $post['chc_title_en']),
            "chc_is_active" => $is_active,
            "chc_datetime_edit" => 'NOW()',
            "chc_author" => USER_ID
        );
        $condition = array(
            "chc_id"=>$post['chc_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."championship_categories",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."championship_categories", "chc_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_categories","*","chc_id=$item");
            $temp[0]['ch_title_ru'] = str_replace($search, $replace, $temp[0]['chc_title_ru']);
            $temp[0]['ch_title_ua'] = str_replace($search, $replace, $temp[0]['chc_title_ua']);
            $temp[0]['ch_title_en'] = str_replace($search, $replace, $temp[0]['chc_title_en']);
            return $temp[0];
        } else return false;
    }

    public function getChampionshipCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."championship_categories","*","1 ORDER BY chc_title_ru ASC, chc_id asc");
    }

    public function getChampionshipCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_categories","*","1 ORDER BY chc_title_ru ASC, chc_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['chc_id']] = $item;
            }
            return $list;
        } else return false;
    }

    // LOCATION    START //////////////////////////////////////////////////////////////////////////////////

    public function createLocal($post){
        if($post['chl_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['chl_is_menu']==true) $chl_is_menu ='yes';
        else $chl_is_menu = 'no';
        if($post['chl_is_main']==true) {
            $chl_is_main ='yes';
            $this->discardMainLocal();
        } else $chl_is_main = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $elem = array(
            str_replace($search, $replace, $post['chl_title_ru']),
            str_replace($search, $replace, $post['chl_title_ua']),
            str_replace($search, $replace, $post['chl_title_en']),
            (strlen(trim(html_entity_decode(strip_tags($post['chl_description_ru']))))>0) ? addslashes($post['chl_description_ru']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['chl_description_ua']))))>0) ? addslashes($post['chl_description_ua']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['chl_description_en']))))>0) ? addslashes($post['chl_description_en']) : NULL,
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $chl_is_main,
            intval($post['chl_order']),
            $chl_is_menu,
            str_replace($search_a, $replace_a, strtolower(trim($post['chl_address'])))
        );
        if ($this->hdl->addElem(DB_T_PREFIX."championship_local", $elem)) return true;
        else return false;
    }

    public function discardMainLocal(){
        $elems = array(
            "chl_is_main " => 'no'
        );
        if ($this->hdl->updateAll(DB_T_PREFIX."championship_local",$elems)) return true;
        else return false;
    }

    public function updateLocal($post){
        $post['chl_id'] = intval($post['chl_id']);
        if ($post['chl_id'] <1) return false;
        if($post['chl_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['chl_is_menu']==true) $chl_is_menu ='yes';
        else $chl_is_menu = 'no';
        if($post['chl_is_main']==true) {
            $chl_is_main ='yes';
            $this->discardMainGroup();
        } else $chl_is_main = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $elems = array(
            "chl_title_ru" => str_replace($search, $replace, $post['chl_title_ru']),
            "chl_title_ua" => str_replace($search, $replace, $post['chl_title_ua']),
            "chl_title_en" => str_replace($search, $replace, $post['chl_title_en']),
            "chl_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['chl_description_ru']))))>0) ? addslashes($post['chl_description_ru']) : NULL,
            "chl_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['chl_description_ua']))))>0) ? addslashes($post['chl_description_ua']) : NULL,
            "chl_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['chl_description_en']))))>0) ? addslashes($post['chl_description_en']) : NULL,
            "chl_is_active" => $is_active,
            "chl_is_main" => $chl_is_main,
            "chl_datetime_edit" => 'NOW()',
            "chl_author" => USER_ID,
            "chl_order" => intval($post['chl_order']),
            "chl_is_menu" => $chl_is_menu,
            "chl_address" => str_replace($search_a, $replace_a, strtolower(trim($post['chl_address'])))
        );
        $condition = array(
            "chl_id"=>$post['chl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."championship_local",$elems, $condition)) return true;
        else return false;
    }

    public function deleteLocal($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."championship_local", "chl_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getLocalItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_local","*","chl_id=$item");
            $temp[0]['chl_title_ru'] = str_replace($search, $replace, $temp[0]['chl_title_ru']);
            $temp[0]['chl_title_ua'] = str_replace($search, $replace, $temp[0]['chl_title_ua']);
            $temp[0]['chl_title_en'] = str_replace($search, $replace, $temp[0]['chl_title_en']);
            $temp[0]['chl_description_ru'] = stripcslashes($temp[0]['chl_description_ru']);
            $temp[0]['chl_description_ua'] = stripcslashes($temp[0]['chl_description_ua']);
            $temp[0]['chl_description_en'] = stripcslashes($temp[0]['chl_description_en']);
            return $temp[0];
        }
        return false;
    }

    public function getChampionshipLocalList(){
        return $this->hdl->selectElem(DB_T_PREFIX."championship_local","chl_id, chl_title_ru AS title, chl_address, chl_is_active AS is_active, chl_is_menu AS is_menu, chl_is_main AS is_main","1 ORDER BY chl_order DESC, chl_title_ru ASC, chl_id ASC");
    }

    public function getChampionshipLocalListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_local","chl_id, chl_title_ru AS title","1 ORDER BY chl_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['chl_id']] = $item['title'];
            }
            return $list;
        } else return false;
    }

    // LOCATION    END //////////////////////////////////////////////////////////////////////////////////

    // GROUPS    START //////////////////////////////////////////////////////////////////////////////////

    public function createGroup($post){
        if($post['chg_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['chg_is_menu']==true) $chg_is_menu ='yes';
        else $chg_is_menu = 'no';
        if($post['chg_is_main']==true) {
            $chg_is_main ='yes';
            $this->discardMainGroup();
        } else $chg_is_main = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $elem = array(
            str_replace($search, $replace, $post['chg_title_ru']),
            str_replace($search, $replace, $post['chg_title_ua']),
            str_replace($search, $replace, $post['chg_title_en']),
            (strlen(trim(html_entity_decode(strip_tags($post['chg_description_ru']))))>0) ? addslashes($post['chg_description_ru']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['chg_description_ua']))))>0) ? addslashes($post['chg_description_ua']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['chg_description_en']))))>0) ? addslashes($post['chg_description_en']) : NULL,
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $chg_is_main,
            intval($post['chg_order']),
            $chg_is_menu,
            str_replace($search_a, $replace_a, strtolower(trim($post['chg_address']))),
            intval($post['chg_chl_id'])
        );
        if ($id = $this->hdl->addElem(DB_T_PREFIX."championship_group", $elem)) {
            $this->_updateConnectionCountry(array(
                'country'=>$post['country_auto_val'],
                'id'=>$id,
                'type'=>'champ_group'
            ));
            $this->_updateConnectionChamp(array(
                'champ'=>$post['champ_auto_val'],
                'id'=>$id,
                'type'=>'champ_group'
            ));
            return true;
        }
        return false;
    }

    public function discardMainGroup(){
        $elems = array(
            "chg_is_main " => 'no'
        );
        if ($this->hdl->updateAll(DB_T_PREFIX."championship_group",$elems)) return true;
        else return false;
    }

    public function updateGroup($post){
        if ($post['chg_id'] <1) return false;
        if($post['chg_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['chg_is_menu']==true) $chg_is_menu ='yes';
        else $chg_is_menu = 'no';
        if($post['chg_is_main']==true) {
            $chg_is_main ='yes';
            $this->discardMainGroup();
        } else $chg_is_main = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $elems = array(
            "chg_title_ru" => str_replace($search, $replace, $post['chg_title_ru']),
            "chg_title_ua" => str_replace($search, $replace, $post['chg_title_ua']),
            "chg_title_en" => str_replace($search, $replace, $post['chg_title_en']),
            "chg_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['chg_description_ru']))))>0) ? addslashes($post['chg_description_ru']) : NULL,
            "chg_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['chg_description_ua']))))>0) ? addslashes($post['chg_description_ua']) : NULL,
            "chg_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['chg_description_en']))))>0) ? addslashes($post['chg_description_en']) : NULL,
            "chg_is_active" => $is_active,
            "chg_is_main" => $chg_is_main,
            "chg_datetime_edit" => 'NOW()',
            "chg_author" => USER_ID,
            "chg_order" => intval($post['chg_order']),
            "chg_is_menu" => $chg_is_menu,
            "chg_address" => str_replace($search_a, $replace_a, strtolower(trim($post['chg_address']))),
            "chg_chl_id" => intval($post['chg_chl_id'])
        );
        $condition = array(
            "chg_id"=>$post['chg_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."championship_group",$elems, $condition)) {
            if (!empty($post['country_auto_val'])){
                $this->_updateConnectionCountry(array(
                    'country'=>$post['country_auto_val'],
                    'id'=>$post['chg_id'],
                    'type'=>'champ_group'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$post['chg_id'],
                    'type'=>'champ_group'
                ));
            }
            if (!empty($post['champ_auto_val'])){
                $this->_updateConnectionChamp(array(
                    'champ'=>$post['champ_auto_val'],
                    'id'=>$post['chg_id'],
                    'type'=>'champ_group'
                ));
            } else {
                // delete connections
                $this->_deleteConnectionChamp(array(
                    'id'=>$post['chg_id'],
                    'type'=>'champ_group'
                ));
            }
            return true;
        }
        else return false;
    }

    public function deleteGroup($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."championship_group", "chg_id='$item'")) {
                // delete connections
                $this->_deleteConnectionCountry(array(
                    'id'=>$item,
                    'type'=>'champ_group'
                ));
                return true;
            }
            else return false;
        }else return false;
    }

    public function getGroupItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group","*","chg_id=$item");
            $temp = $temp[0];
            $temp['chg_title_ru'] = str_replace($search, $replace, $temp['chg_title_ru']);
            $temp['chg_title_ua'] = str_replace($search, $replace, $temp['chg_title_ua']);
            $temp['chg_title_en'] = str_replace($search, $replace, $temp['chg_title_en']);
            $temp['chg_description_ru'] = stripcslashes($temp['chg_description_ru']);
            $temp['chg_description_ua'] = stripcslashes($temp['chg_description_ua']);
            $temp['chg_description_en'] = stripcslashes($temp['chg_description_en']);

            $temp['connection_country'] = $this->getConnectionCountry($item, false, 'champ_group');
            $temp['connection_country_val'] = '';
            if (!empty($temp['connection_country'])){
                foreach($temp['connection_country'] as $item_cc){
                    $temp['connection_country_val'] .= $item_cc['id'].',';
                }
                $temp['connection_country_val'] = substr($temp['connection_country_val'], 0, -1);
            }

            $temp['connection_champ'] = $this->getConnectionChamp($item, false, 'champ_group');
            $temp['connection_champ_val'] = '';
            if (!empty($temp['connection_champ'])){
                foreach($temp['connection_champ'] as $item_cc){
                    $temp['connection_champ_val'] .= $item_cc['id'].',';
                }
                $temp['connection_champ_val'] = substr($temp['connection_champ_val'], 0, -1);
            }

            return $temp;
        } else return false;
    }

    public function getChampionshipGroupList(){
        return $this->hdl->selectElem(DB_T_PREFIX."championship_group","*","1 ORDER BY chg_order DESC, chg_title_ru ASC, chg_id ASC");
    }

    public function getChampionshipGroupListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group","*","1 ORDER BY chg_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['chg_id']] = $item;
            }
            return $list;
        } else return false;
    }

    // GROUPS    END //////////////////////////////////////////////////////////////////////////////////

    // Championship ///////////////////////////////////////////////////////////////////////////////////////

    public function createChampionship($post){ // добавление чемпионата
        if($post['ch_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['ch_is_menu']==true) $ch_is_menu ='yes';
        else $ch_is_menu = 'no';
        if($post['ch_is_informer']==true) $ch_is_informer ='yes';
        else $ch_is_informer = 'no';
        if($post['ch_is_main']==true) {
            $ch_is_main ='yes';
            $this->discardMainChampionship();
        } else $ch_is_main = 'no';
        if($post['ch_is_done']==true) $ch_is_done ='yes';
        else $ch_is_done = 'no';
        if ($post['ch_date_from_day'] != '' and $post['ch_date_from_day']>0 and $post['ch_date_from_month'] != '' and $post['ch_date_from_month']>0 and $post['ch_date_from_year'] != '' and $post['ch_date_from_year']>0) {
            $post['ch_date_from_day'] = intval($post['ch_date_from_day']);
            $post['ch_date_from_month'] = intval($post['ch_date_from_month']);
            $post['ch_date_from_year'] = intval($post['ch_date_from_year']);
            if ($post['ch_date_from_day'] < 10) $post['ch_date_from_day'] = '0'.$post['ch_date_from_day'];
            if ($post['ch_date_from_month'] < 10) $post['ch_date_from_month'] = '0'.$post['ch_date_from_month'];
            $ch_date_from = $post['ch_date_from_year']."-".$post['ch_date_from_month']."-".$post['ch_date_from_day'];
        }
        if ($post['ch_date_to_day'] != '' and $post['ch_date_to_day']>0 and $post['ch_date_to_month'] != '' and $post['ch_date_to_month']>0 and $post['ch_date_to_year'] != '' and $post['ch_date_to_year']>0) {
            $post['ch_date_to_day'] = intval($post['ch_date_to_day']);
            $post['ch_date_to_month'] = intval($post['ch_date_to_month']);
            $post['ch_date_to_year'] = intval($post['ch_date_to_year']);
            if ($post['ch_date_to_day'] < 10) $post['ch_date_to_day'] = '0'.$post['ch_date_to_day'];
            if ($post['ch_date_to_month'] < 10) $post['ch_date_to_month'] = '0'.$post['ch_date_to_month'];
            $ch_date_to = $post['ch_date_to_year']."-".$post['ch_date_to_month']."-".$post['ch_date_to_day'];
        }
        $search = array("'", '"');
        $replace = array('', '');
        $post['ch_address'] = str_replace($search, $replace, $post['ch_address']);
        $post['ch_address_path'] = str_replace($search, $replace, $post['ch_address_path']);
        $settings = array(
            'ch_address_path' => $post['ch_address_path'],
            'main_url' => ((!empty($post['ch_address_path']))?$post['ch_address_path']."/":'').$post['ch_address']
        );
        $elem = array(
            "ch_title_ru" => str_replace($search, $replace, $post['ch_title_ru']),
            "ch_title_ua" => str_replace($search, $replace, $post['ch_title_ua']),
            "ch_title_en" => str_replace($search, $replace, $post['ch_title_en']),
            "ch_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['ch_description_ru']))))>0) ? addslashes($post['ch_description_ru']) : NULL,
            "ch_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['ch_description_ua']))))>0) ? addslashes($post['ch_description_ua']) : NULL,
            "ch_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['ch_description_en']))))>0) ? addslashes($post['ch_description_en']) : NULL,
            "ch_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['ch_text_ru']))))>0) ? addslashes($post['ch_text_ru']) : NULL,
            "ch_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['ch_text_ua']))))>0) ? addslashes($post['ch_text_ua']) : NULL,
            "ch_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['ch_text_en']))))>0) ? addslashes($post['ch_text_en']) : NULL,
            "ch_is_active" => $is_active,
            "ch_datetime_add" => 'NOW()',
            "ch_datetime_edit" => 'NOW()',
            "ch_author" => USER_ID,
            "ch_date_from" => $ch_date_from,
            "ch_date_to" => $ch_date_to,
            "ch_is_done" => $ch_is_done,
            "ch_chc_id" => intval($post['ch_chc_id']),
            "ch_cn_id" => intval($post['ch_cn_id']),
            "ch_ct_id" => intval($post['t_ct_id']),
            "ch_cp_is_done" => 'no',
            "ch_p_win" => intval($post['ch_p_win']),
            "ch_p_draw" => intval($post['ch_p_draw']),
            "ch_p_loss" => intval($post['ch_p_loss']),
            "ch_p_bonus_1" => intval($post['ch_p_bonus_1']),
            "ch_p_bonus_2" => intval($post['ch_p_bonus_2']),
            "ch_p_bonus_2_diff" => (isset($post['ch_p_bonus_2_diff']) && $post['ch_p_bonus_2_diff'] !== '' ? intval($post['ch_p_bonus_2_diff']) : 7),
            "ch_p_tehwin" => intval($post['ch_p_tehwin']),
            "ch_is_main" => $ch_is_main,
            "ch_chg_id" => intval($post['ch_chg_id']),
            "ch_tours" => '0',
            "ch_address" => $post['ch_address'],
            "ch_is_menu" => $ch_is_menu,
            "ch_order" => intval($post['ch_order']),
            "ch_settings" => $this->_fix_serialized_string(serialize($settings)),
            "ch_is_informer" => $ch_is_informer
        );

        if ($this->hdl->addElem(DB_T_PREFIX."championship", $elem)) return true;
        else return false;
    }

    public function discardMainChampionship(){
        $elems = array(
            "ch_is_main " => 'no'
        );
        $condition = array(
            "ch_chg_id"=>$_POST['ch_chg_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        else return false;
    }

    public function updateChampionship($post){ // редактирование чемпионата
        //var_dump(serialize($post['ch_tour_points']));
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_id, ch_cp_is_done, ch_is_done, ch_settings","ch_id = '".$post['ch_id']."' LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            if(!empty($post['ch_is_active'])) $is_active ='yes';
            else $is_active = 'no';
            if(!empty($post['ch_is_menu'])) $ch_is_menu ='yes';
            else $ch_is_menu = 'no';
            if(!empty($post['ch_is_informer'])) $ch_is_informer ='yes';
            else $ch_is_informer = 'no';
            if(!empty($post['ch_is_main'])) {
                $ch_is_main ='yes';
                $this->discardMainChampionship();
            } else $ch_is_main = 'no';
            if(!empty($post['ch_is_done'])) $ch_is_done ='yes';
            else $ch_is_done = 'no';
            if (!empty($post['ch_date_from_day']) && !empty($post['ch_date_from_day']) && !empty($post['ch_date_from_month']) && !empty($post['ch_date_from_month']) && !empty($post['ch_date_from_year']) && !empty($post['ch_date_from_year'])) {
                $post['ch_date_from_day'] = intval($post['ch_date_from_day']);
                $post['ch_date_from_month'] = intval($post['ch_date_from_month']);
                $post['ch_date_from_year'] = intval($post['ch_date_from_year']);
                if ($post['ch_date_from_day'] < 10) $post['ch_date_from_day'] = '0'.$post['ch_date_from_day'];
                if ($post['ch_date_from_month'] < 10) $post['ch_date_from_month'] = '0'.$post['ch_date_from_month'];
                $ch_date_from = $post['ch_date_from_year']."-".$post['ch_date_from_month']."-".$post['ch_date_from_day'];
            }
            if (!empty($post['ch_date_to_day']) && !empty($post['ch_date_to_day']) && !empty($post['ch_date_to_month']) && !empty($post['ch_date_to_month']) && !empty($post['ch_date_to_year']) && !empty($post['ch_date_to_year'])) {
                $post['ch_date_to_day'] = intval($post['ch_date_to_day']);
                $post['ch_date_to_month'] = intval($post['ch_date_to_month']);
                $post['ch_date_to_year'] = intval($post['ch_date_to_year']);
                if ($post['ch_date_to_day'] < 10) $post['ch_date_to_day'] = '0'.$post['ch_date_to_day'];
                if ($post['ch_date_to_month'] < 10) $post['ch_date_to_month'] = '0'.$post['ch_date_to_month'];
                $ch_date_to = $post['ch_date_to_year']."-".$post['ch_date_to_month']."-".$post['ch_date_to_day'];
            }

            if (!empty($post['ch_tour_points'])) {
                $temp['ch_settings']['tourPoints'] = $post['ch_tour_points'];
            }
            $temp['ch_settings']['tours_points_order'] = (!empty($post['tours_points_order']))?true:false;
            $temp['ch_settings']['show_stuff_rating'] = (!empty($post['show_stuff_rating']))?1:0;
            $temp['ch_settings']['count_stuff_rating'] = intval($post['count_stuff_rating']);
            $temp['ch_settings']['bonus_1_type'] = intval($post['bonus_1_type']);
            if ($temp['ch_settings']['bonus_1_type'] < 0 || $temp['ch_settings']['bonus_1_type'] > 1) {
                $temp['ch_settings']['bonus_1_type'] = 0;
            }
            $temp['ch_settings']['table_type_super_15'] = intval($post['table_type_super_15']);
            if ($temp['ch_settings']['table_type_super_15'] < 0 || $temp['ch_settings']['table_type_super_15'] > 2) {
                $temp['ch_settings']['table_type_super_15'] = 0;
            }
            $temp['ch_settings']['ch_address_path'] = $post['ch_address_path'];
            $temp['ch_settings']['main_url'] = ((!empty($post['ch_address_path']))?$post['ch_address_path']."/":'').$post['ch_address'];
            $temp['ch_settings']['table_order_priority'] = $post['table_order_priority'];
            $search = array("'", '"');
            $replace = array('', '');
            $elems = array(
                "ch_title_ru" => str_replace($search, $replace, $post['ch_title_ru']),
                "ch_title_ua" => str_replace($search, $replace, $post['ch_title_ua']),
                "ch_title_en" => str_replace($search, $replace, $post['ch_title_en']),
                "ch_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['ch_description_ru']))))>0) ? addslashes($post['ch_description_ru']) : NULL,
                "ch_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['ch_description_ua']))))>0) ? addslashes($post['ch_description_ua']) : NULL,
                "ch_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['ch_description_en']))))>0) ? addslashes($post['ch_description_en']) : NULL,
                "ch_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['ch_text_ru']))))>0) ? addslashes($post['ch_text_ru']) : '',
                "ch_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['ch_text_ua']))))>0) ? addslashes($post['ch_text_ua']) : '',
                "ch_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['ch_text_en']))))>0) ? addslashes($post['ch_text_en']) : '',
                "ch_is_active" => $is_active,
                "ch_datetime_edit" => 'NOW()',
                "ch_author" => USER_ID,
                "ch_date_from" => $ch_date_from,
                "ch_date_to" => $ch_date_to,
                "ch_is_done" => $ch_is_done,
                //"ch_chc_id" => intval($post['ch_chc_id']),
                "ch_cn_id" => intval($post['ch_cn_id']),
                "ch_ct_id" => intval($post['t_ct_id']),
                "ch_is_main" => $ch_is_main,
                "ch_chg_id" => intval($post['ch_chg_id']),
                "ch_is_menu" => $ch_is_menu,
                "ch_is_informer" => $ch_is_informer,
                "ch_order" => intval($post['ch_order']),
                "ch_address" => (strlen(trim(html_entity_decode(strip_tags($post['ch_address']))))>0) ? addslashes($post['ch_address']) : $post['ch_id'],
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            // Разница очков для бонуса 2 можно менять всегда
            $elems["ch_p_bonus_2_diff"] = (isset($post['ch_p_bonus_2_diff']) && $post['ch_p_bonus_2_diff'] !== '' ? intval($post['ch_p_bonus_2_diff']) : 7);

            $temp_cp_done = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id","cp_ch_id='".$post['ch_id']."' AND cp_is_done = 'yes' LIMIT 1");
            if (!$temp_cp_done) {
                $elems["ch_p_win"] = intval($post['ch_p_win']);
                $elems["ch_p_draw"] = intval($post['ch_p_draw']);
                $elems["ch_p_loss"] = intval($post['ch_p_loss']);
                $elems["ch_p_bonus_1"] = intval($post['ch_p_bonus_1']);
                $elems["ch_p_bonus_2"] = intval($post['ch_p_bonus_2']);
                $elems["ch_p_tehwin"] = intval($post['ch_p_tehwin']);
            }
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function getChampionshipList( $is_aarchive = false){
        $q_cn = '';
        if (isset($_GET['country'])){
            $cn_id = $_GET['country'];
            if ($cn_id == 'all') $q_cn = "";
            elseif ($cn_id == 0) $q_cn = " AND ch_cn_id = '0' ";
            else $q_cn = " AND ch_cn_id = '".intval($cn_id)."' ";
        }
        if (!empty($_GET['season'])){
            $search = array("'", '"');
            $replace = array('', '');
            $season = str_replace($search, $replace, $_GET['season']);
            $q_cn .= " AND ch_title_ru LIKE '%$season%' ";
        }
        $q_archive = " AND ch_is_done = '".(($is_aarchive)?'yes':'no')."'";

        return $this->hdl->selectElem(DB_T_PREFIX."championship
                                                        LEFT JOIN ".DB_T_PREFIX."championship_categories
                                                            ON ch_chc_id = chc_id
                                                        LEFT JOIN ".DB_T_PREFIX."championship_group
                                                            ON ch_chg_id = chg_id
                                                        LEFT JOIN ".DB_T_PREFIX."championship_local
                                                            ON chg_chl_id = chl_id
						","	ch_id,
							ch_title_ru,
							ch_is_active,
							ch_chc_id,
							ch_is_menu,
							ch_order,
							ch_address,
							ch_chg_id,
							chc_title_ru ,
							chg_title_ru,
							chl_title_ru,
							ch_is_main
						","	1
							$q_cn
							$q_archive
						ORDER BY chl_title_ru ASC, 
							chg_title_ru ASC, 
							ch_text_ru ASC, 
							ch_date_from DESC, 
							ch_id DESC");
    }

    public function getSeasonList(){
        return $this->hdl->selectElem(DB_T_PREFIX."championship
						","	ch_title_ru as title
						","	1
                                                GROUP BY ch_title_ru
						ORDER BY ch_title_ru ASC");
    }

    public function getChampionshipCountryListNE(){
        return $this->hdl->selectElem(DB_T_PREFIX."country, ".DB_T_PREFIX."championship","*","ch_cn_id = cn_id GROUP BY cn_id ORDER BY cn_title_ru ASC");
    }

    public function getChampionshipItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","*","ch_id=$item");
            if (!empty($temp)) {
                $temp = $temp[0];
                $temp['ch_title_ru'] = str_replace($search, $replace, $temp['ch_title_ru']);
                $temp['ch_title_ua'] = str_replace($search, $replace, $temp['ch_title_ua']);
                $temp['ch_title_en'] = str_replace($search, $replace, $temp['ch_title_en']);
                $temp['ch_description_ru'] = stripcslashes($temp['ch_description_ru']);
                $temp['ch_description_ua'] = stripcslashes($temp['ch_description_ua']);
                $temp['ch_description_en'] = stripcslashes($temp['ch_description_en']);
                $temp['ch_text_ru'] = stripcslashes($temp['ch_text_ru']);
                $temp['ch_text_ua'] = stripcslashes($temp['ch_text_ua']);
                $temp['ch_text_en'] = stripcslashes($temp['ch_text_en']);
                $temp_cp = $this->hdl->selectElem(DB_T_PREFIX . "competitions", "cp_id", "cp_ch_id=$item LIMIT 1");
                if ($temp_cp) $temp['competitions'] = true;
                else $temp['competitions'] = false;
                $temp_cp_done = $this->hdl->selectElem(DB_T_PREFIX . "competitions", "cp_id", "cp_ch_id=$item AND cp_is_done = 'yes' LIMIT 1");
                if ($temp_cp_done) $temp['competitions_done'] = true;
                else $temp['competitions_done'] = false;
                $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            }
            return $temp;
        }
        return false;
    }

    public function deleteChampionship($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."championship", "ch_id='$id'")) {
                $this->hdl->delElem(DB_T_PREFIX."connection_t_ch", "cntch_ch_id = '$id'");

                // deleting photo
                $media = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_type = 'championship' AND ph_type_id = '$id'");
                if ($media)
                    foreach ($media as $item){
                        if ($item['ph_folder'] == '') $item['ph_folder'] = '/';
                        if (file_exists ("../upload/photos".$item['ph_folder'].$item['ph_path'])) unlink("../upload/photos".$item['ph_folder'].$item['ph_path']);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-informer".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-small".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-main".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-s_main".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $o_file = substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
                        if (file_exists ("../upload/photos".$item['ph_folder'].$o_file)) unlink("../upload/photos".$item['ph_folder'].$o_file);
                        $this->hdl->delElem(DB_T_PREFIX."photos", "ph_id = '".$item['ph_id']."' LIMIT 1");
                    }
                unset($media);
                // deleting photo gallery
                $media = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id","phg_type = 'championship' AND phg_type_id = '$id'");
                if ($media)
                    foreach ($media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_id = '".$item['phg_id']."' LIMIT 1");
                    }
                unset($media);
                // deleting video
                $media = $this->hdl->selectElem(DB_T_PREFIX."videos","v_id, v_folder","v_type = 'championship' AND v_type_id = '$id'");
                if ($media)
                    foreach ($media as $item){
                        if ($item['v_folder'] == '') $item['v_folder'] = '/';
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg");
                        if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg");
                        $this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '".$item['v_id']."' LIMIT 1");
                    }
                unset($media);
                // deleting video gallery
                $media = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","vg_id","vg_type = 'championship' AND vg_type_id = '$id'");
                if ($media)
                    foreach ($media as $item){
                        $this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_id = '".$item['vg_id']."' LIMIT 1");
                    }
                unset($media);

                return true;
            } else return false;
        }else return false;
    }

    // GALLERY ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($championship_item, &$photos_class){ // добавление фотографии в галерею
        $championship_item['ch_id'] = intval($championship_item['ch_id']);
        if ($championship_item['ch_id'] < 1) return false;
        $type = 'championship';

        if ($_POST['ph_gallery_id'] == 'new') {
            if ($championship_item['ch_title_ru'] == '') $phg_post['phg_title_ru'] = $championship_item['ch_id'];
            else $phg_post['phg_title_ru'] = $championship_item['ch_title_ru'];
            if ($championship_item['ch_title_ua'] == '') $phg_post['phg_title_ua'] = $championship_item['ch_id'];
            else $phg_post['phg_title_ua'] = $championship_item['ch_title_ua'];
            if ($championship_item['ch_title_en'] == '') $phg_post['phg_title_en'] = $championship_item['ch_id'];
            else $phg_post['phg_title_en'] = $championship_item['ch_title_en'];

            $phg_post['phg_description_ru'] = "Фото галерея к чемпионату &laquo;".$phg_post['phg_title_ru']."&raquo;.";
            $phg_post['phg_description_ua'] = "Фото галерея до чемпіонату &laquo;".$phg_post['phg_title_ua']."&raquo;.";
            $phg_post['phg_description_en'] = "Photo gallery for championship &laquo;".$phg_post['phg_title_en']."&raquo;.";
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $championship_item['ch_id'];
            $phg_post['phg_phc_id'] = 0;
            $phg_post['phg_datetime_pub'] = 'NOW()';

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id']<1) return false;
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if ($_POST['ph_type_main']) $photos_class->resetTypeMainPhotos($championship_item['ch_id'], $type);
        $_POST['ph_type_id'] = $championship_item['ch_id'];
        $_POST['ph_type'] = $type;

        if ($photos_class->savePhoto($_FILES['file_photo'], $_POST)) return true;
        return false;
    }

    public function saveVideo($championship_item, &$videos_class){ // добавление видео в галерею
        $championship_item['ch_id'] = intval($championship_item['ch_id']);
        if ($championship_item['ch_id'] < 1) return false;
        $type = 'championship';

        if ($_POST['v_gallery_id'] == 'new') {
            if ($championship_item['ch_title_ru'] == '') $vg_post['vg_title_ru'] = $championship_item['ch_id'];
            else $vg_post['vg_title_ru'] = $championship_item['ch_title_ru'];
            if ($championship_item['ch_title_ua'] == '') $vg_post['vg_title_ua'] = $championship_item['ch_id'];
            else $vg_post['vg_title_ua'] = $championship_item['ch_title_ua'];
            if ($championship_item['ch_title_en'] == '') $vg_post['vg_title_en'] = $championship_item['ch_id'];
            else $vg_post['vg_title_en'] = $championship_item['ch_title_en'];

            $vg_post['vg_description_ru'] = "Видео галерея к чемпионату &laquo;".$vg_post['vg_title_ru']."&raquo;.";
            $vg_post['vg_description_ua'] = "Відео галерея до чемпіонату &laquo;".$vg_post['vg_title_ua']."&raquo;.";
            $vg_post['vg_description_en'] = "Video gallery for championship &laquo;".$vg_post['vg_title_en']."&raquo;.";
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $championship_item['ch_id'];
            $vg_post['vg_phc_id'] = 0;
            $vg_post['vg_datetime_pub'] = 'NOW()';

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $championship_item['ch_id'];
        $_POST['v_type'] = $type;

        if ($videos_class->saveVideo($_POST)) return true;
        return false;
    }

    // SETTINGS ///////////////////////////////////////////////////////////////////////////////////////

    public function getChampionshipSettings(){
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
        if (!empty($condition) && is_array($condition)) {
            $where_condition_a = array();
            foreach ($condition as $key=>$val) {
                $where_condition_a[] = $key."='".$val."'";
            }
            if (!$this->hdl->selectElem(DB_T_PREFIX."settings", "*", implode(' AND ', $where_condition_a)) &&
                !empty($condition['set_name'])){
                $this->hdl->addElem(DB_T_PREFIX."settings", array( $condition['set_name'], '', 'NOW()', USER_ID), true);
            };
        }
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$elems, $condition)) return true;
        else return false;
    }

    /**
     * repair serialized data
     *
     * @param string $settings_data
     * @return mixed
     */
    private function _fix_serialized_string ($settings_data='') {
        return preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        },$settings_data );
    }
}
