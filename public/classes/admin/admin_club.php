<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class club{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // СОСТАВ КЛУБА КОМАНДЫ /////////////////////////////////////////////////////////////////////////////////////

    public function createConnectTCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl","cntcl_id"," cntcl_t_id = '".intval($post['team_id'])."' AND cntcl_cl_id = '".intval($post['cl_id'])."' AND cntcl_is_delete = 'no' AND cntcl_date_quit = '0000-00-00 00:00:00' LIMIT 1");
        //var_dump($temp);
        if ($temp[0]['cntcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $cl_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elem = array(
            intval($post['team_id']),
            intval($post['cl_id']),
            $cl_date_add,
            USER_ID,
            '',
            '',
            'no'
        );
        if ($this->hdl->addElem(DB_T_PREFIX."connection_t_cl", $elem)) return true;
        else return false;
    }

    public function updateConnectTCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl","cntcl_id"," cntcl_t_id = '".intval($post['team_id'])."' AND cntcl_id = '".intval($post['cntcl_id'])."' AND cntcl_cl_id = '".intval($post['cl_id'])."' AND cntcl_is_delete = 'no' AND cntcl_date_quit = '0000-00-00 00:00:00' LIMIT 1");
        if ($temp[0]['cntcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $cl_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elems = array(
            "cntcl_date_add" => $cl_date_add,
            "cntcl_add_author" => USER_ID
        );
        $condition = array(
            "cntcl_id"=>$post['cntcl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_t_cl",$elems, $condition)) return true;
        else return false;
    }

    public function quitConnectTCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl","cntcl_id"," AND cntcl_id = '".intval($post['cntcl_id'])."' AND cntcl_date_quit != '0000-00-00 00:00:00' AND cntcl_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cntcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $cl_date_quit = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elems = array(
            "cntcl_date_quit" => $cl_date_quit,
            "cntcl_quit_author" => USER_ID
        );
        $condition = array(
            "cntcl_id"=>$post['cntcl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_t_cl",$elems, $condition)) return true;
        else return false;
    }

    public function deleteConnectTCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl","cntcl_id"," AND cntcl_id = '".intval($post['cntcl_id'])."' AND cntcl_is_delete = 'yes' LIMIT 1");
        if ($temp[0]['cntcl_id']>0) return false;
        $elems = array(
            "cntcl_is_delete" => 'yes'
        );
        $condition = array(
            "cntcl_id"=>$post['cntcl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_t_cl",$elems, $condition)) return true;
        else return false;
    }

    public function getClTItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl, ".DB_T_PREFIX."team","*","cntcl_id = '$item' AND cntcl_is_delete = 'no' AND cntcl_t_id = t_id LIMIT 1");
            return $temp[0];
        } else return false;
    }

    public function getHistoryConnectTCl($cl_id){
        $cl_id = intval($cl_id);
        if ($cl_id > 0) return $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl, ".DB_T_PREFIX."team","*","cntcl_cl_id = '$cl_id' AND cntcl_is_delete = 'no' AND cntcl_t_id = t_id ORDER BY cntcl_date_add DESC");
        else return false;
    }

    public function getClubTeamList($club_id = 0){
        $club_id = intval($club_id);
        if ($club_id<1) return false;
        $q_team = '';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_cl, ".DB_T_PREFIX."team","*"," cntcl_cl_id = '$club_id' AND cntcl_t_id=t_id AND cntcl_is_delete = 'no' AND cntcl_date_quit = '0000-00-00 00:00:00' ORDER BY cntcl_date_add ASC");
        if ($temp){
            foreach ($temp as $val){
                $q_team .= " AND t_id != '".$val['cntcl_t_id']."'";
            }
            $list['on'] = $temp;
        } else $list['on'] = false;
        if (strlen($q_team)>0) $q_team = substr($q_team, 5);
        else $q_team = 1;
        $list['off'] = $this->hdl->selectElem(DB_T_PREFIX."team","*"," $q_team ORDER BY t_title_ru ASC");
        //var_dump($list);
        return $list;
    }

    // СОСТАВ КЛУБА ЛЮДИ /////////////////////////////////////////////////////////////////////////////////////

    public function createConnectStCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id"," cnstcl_st_id = '".intval($post['staff_id'])."' AND cnstcl_cl_id = '".intval($post['cl_id'])."' AND cnstcl_app_id = '".intval($post['appointment_id'])."' AND cnstcl_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cnstcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $cl_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elem = array(
            intval($post['staff_id']),
            intval($post['cl_id']),
            intval($post['appointment_id']),
            $cl_date_add,
            USER_ID,
            '',
            '',
            'no',
            intval($post['cnstcl_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."connection_st_cl", $elem)) return true;
        else return false;
    }

    public function createNewConnectStCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id"," cnstcl_st_id = '".intval($post['staff_id'])."' AND cnstcl_cl_id = '".intval($post['cl_id'])."' AND cnstcl_app_id = '".intval($post['appointment_id'])."' AND cnstcl_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cnstcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $t_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elem = array(
            intval($post['staff_id']),
            intval($post['cl_id']),
            intval($post['appointment_id']),
            $t_date_add,
            USER_ID,
            '',
            '',
            'no',
            intval($post['cnstcl_order'])
        );
        if ($this->hdl->addElem(DB_T_PREFIX."connection_st_cl", $elem)) return true;
        else return false;
    }

    public function updateConnectStCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id"," cnstcl_st_id = '".intval($post['staff_id'])."' AND cnstcl_id = '".intval($post['cnstcl_id'])."' AND cnstcl_app_id = '".intval($post['appointment_id'])."' AND cnstcl_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cnstcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $cl_date_add = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elems = array(
            "cnstcl_app_id" => intval($post['appointment_id']),
            "cnstcl_date_add" => $cl_date_add,
            "cnstcl_add_author" => USER_ID,
            "cnstcl_order" => intval($post['cnstcl_order'])
        );
        $condition = array(
            "cnstcl_id"=>$post['cnstcl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_st_cl",$elems, $condition)) return true;
        else return false;
    }

    public function returnConnectStCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id, cnstcl_st_id, cnstcl_cl_id, cnstcl_app_id, cnstcl_date_quit"," cnstcl_id = '".intval($post['cnstcl_id'])."' AND cnstcl_is_delete = 'no' LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $same = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id","
						cnstcl_st_id = '".$temp['cnstcl_st_id']."' AND  
						cnstcl_cl_id = '".$temp['cnstcl_cl_id']."' AND  
						cnstcl_app_id = '".$temp['cnstcl_app_id']."' AND  
						cnstcl_date_quit = '0000-00-00 00:00:00' AND
						cnstcl_is_delete = 'no'
						LIMIT 1");
            if ($same) return false;

            $elems = array(
                "cnstcl_date_quit" => '',
                "cnstcl_add_author" => USER_ID
            );
            $condition = array(
                "cnstcl_id"=>$temp['cnstcl_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."connection_st_cl",$elems, $condition)) return true;
        }
        return false;
    }

    public function quitConnectStCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id"," AND cnstcl_id = '".intval($post['cnstcl_id'])."' AND cnstcl_date_quit != '0000-00-00 00:00:00' AND cnstcl_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cnstcl_id']>0) return false;
        if ($post['app_date_day'] != '' and $post['app_date_day']>0 and $post['app_date_month'] != '' and $post['app_date_month']>0 and $post['app_date_year'] != '' and $post['app_date_year']>0) {
            $post['app_date_day'] = intval($post['app_date_day']);
            $post['app_date_month'] = intval($post['app_date_month']);
            $post['app_date_year'] = intval($post['app_date_year']);
            if ($post['app_date_day'] < 10) $post['app_date_day'] = '0'.$post['app_date_day'];
            if ($post['app_date_month'] < 10) $post['app_date_month'] = '0'.$post['app_date_month'];
            $cl_date_quit = $post['app_date_year']."-".$post['app_date_month']."-".$post['app_date_day'];
        }
        $elems = array(
            "cnstcl_date_quit" => $cl_date_quit,
            "cnstcl_quit_author" => USER_ID
        );
        $condition = array(
            "cnstcl_id"=>$post['cnstcl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_st_cl",$elems, $condition)) return true;
        else return false;
    }

    public function deleteConnectStCl($post){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl","cnstcl_id"," AND cnstcl_id = '".intval($post['cnstcl_id'])."' AND cnstcl_is_delete = 'yes' LIMIT 1");
        if ($temp[0]['cnstcl_id']>0) return false;
        $elems = array(
            "cnstcl_is_delete" => 'yes'
        );
        $condition = array(
            "cnstcl_id"=>$post['cnstcl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_st_cl",$elems, $condition)) return true;
        else return false;
    }

    public function getClStaffItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","cnstcl_id = '$item' AND cnstcl_is_delete = 'no' AND cnstcl_st_id = st_id AND cnstcl_app_id = app_id LIMIT 1");
            return $temp[0];
        } else return false;
    }

    public function getConnectStClByapp($cl_id, $app_type){ // выборка сотрудников с сортировкой по должностям и группировкой имён
        if ($app_type == 'head') $extra_q = "AND app_type = 'head'";
        elseif ($app_type == 'rest') $extra_q = "AND app_type = 'rest'";
        else $extra_q = "AND app_type = 'player'";
        $cl_id = intval($cl_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","cnstcl_id, st_id, st_family_ru, st_name_ru, st_surname_ru, app_title_ru, app_id","cnstcl_cl_id = '$cl_id' AND cnstcl_is_delete = 'no' AND cnstcl_date_quit = '0000-00-00 00:00:00' AND cnstcl_st_id = st_id AND cnstcl_app_id = app_id $extra_q ORDER BY app_title_ru ASC");
        $k = 0;
        if ($temp) foreach ($temp as $item){
            $st_app = array(
                "name" => $item['st_family_ru']." ".$item['st_name_ru']." ".$item['st_surname_ru'],
                "cnstcl_id" => $item['cnstcl_id'],
                "st_id" => $item['st_id']
            );
            if ($prev_item['app_id'] == $item['app_id']) {
                $res[$k-1]['st_app'][] = $st_app;
            } else {
                $res[$k]['app_title_ru'] = $item['app_title_ru'];
                $res[$k]['st_app'][] = $st_app;
                $k++;
            }
            $prev_item = $item;
        }
        if (count($res)>0) return $res;
        else return false;
    }

    public function getConnectStClByname($cl_id, $app_type){ // выборка сотрудников с сортировкой по имени и групировкой должностей
        if ($app_type == 'head') $extra_q = "AND app_type = 'head'";
        elseif ($app_type == 'rest') $extra_q = "AND app_type = 'rest'";
        else $extra_q = "AND app_type = 'player'";
        $cl_id = intval($cl_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","cnstcl_id, st_id, st_family_ru, st_name_ru, st_surname_ru, app_title_ru","cnstcl_cl_id = '$cl_id' AND cnstcl_is_delete = 'no' AND cnstcl_date_quit = '0000-00-00 00:00:00' AND cnstcl_st_id = st_id AND cnstcl_app_id = app_id $extra_q ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, st_id ASC");
        $k = 0;
        if ($temp) foreach ($temp as $item){
            $st_app = array(
                "app_title_ru" => $item['app_title_ru'],
                "cnstcl_id" => $item['cnstcl_id'],
                "st_id" => $item['st_id']
            );
            if ($prev_item['st_id'] == $item['st_id']) {
                $res[$k-1]['st_app'][] = $st_app;
            } else {
                $res[$k]['name'] = $item['st_family_ru']." ".$item['st_name_ru']." ".$item['st_surname_ru'];
                $res[$k]['st_app'][] = $st_app;
                $k++;
            }
            $prev_item = $item;
        }
        if (count($res)>0) return $res;
        else return false;
    }

    public function getHistoryConnectStCl($cl_id){
        $cl_id = intval($cl_id);
        return $this->hdl->selectElem(DB_T_PREFIX."connection_st_cl, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","*","cnstcl_cl_id = '$cl_id' AND cnstcl_is_delete = 'no' AND cnstcl_st_id = st_id AND cnstcl_app_id = app_id ORDER BY cnstcl_date_add DESC");
    }

    public function getClStaffList(){
        return $this->hdl->selectElem(DB_T_PREFIX."staff","*","1 ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, st_id ASC");
    }

    // СТАДИОНЫ /////////////////////////////////////////////////////////////////////////////////////

    public function createStadium($post){
        if($post['std_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            intval($post['std_cn_id']),
            intval($post['t_ct_id']),
            str_replace($search, $replace, $post['std_title_ru']),
            str_replace($search, $replace, $post['std_title_ua']),
            str_replace($search, $replace, $post['std_title_en']),
            addslashes($post['std_description_ru']),
            addslashes($post['std_description_ua']),
            addslashes($post['std_description_en']),
            intval($post['std_order']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX."stadium", $elem)) return true;
        else return false;
    }

    public function updateStadium($post){
        if ($post['std_id'] <1) return false;
        if($post['std_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "std_cn_id" => intval($post['std_cn_id']),
            "std_ct_id" => intval($post['t_ct_id']),
            "std_title_ru" => str_replace($search, $replace, $post['std_title_ru']),
            "std_title_ua" => str_replace($search, $replace, $post['std_title_ua']),
            "std_title_en" => str_replace($search, $replace, $post['std_title_en']),
            "std_description_ru" => addslashes($post['std_description_ru']),
            "std_description_ua" => addslashes($post['std_description_ua']),
            "std_description_en" => addslashes($post['std_description_en']),
            "std_order" => intval($post['std_order']),
            "std_is_active" => $is_active,
            "std_datetime_edit" => 'NOW()',
            "std_author" => USER_ID
        );
        $condition = array(
            "std_id"=>$post['std_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."stadium",$elems, $condition)) return true;
        else return false;
    }

    public function deleteStadium($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."stadium", "std_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getStadiumItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."stadium","*","std_id=$item");
            $temp[0]['std_title_ru'] = str_replace($search, $replace, $temp[0]['std_title_ru']);
            $temp[0]['std_title_ua'] = str_replace($search, $replace, $temp[0]['std_title_ua']);
            $temp[0]['std_title_en'] = str_replace($search, $replace, $temp[0]['std_title_en']);
            $temp[0]['std_description_ru'] = stripcslashes($temp[0]['std_description_ru']);
            $temp[0]['std_description_ua'] = stripcslashes($temp[0]['std_description_ua']);
            $temp[0]['std_description_en'] = stripcslashes($temp[0]['std_description_en']);
            return $temp[0];
        } else return false;
    }

    public function getClubStadiumList(){
        return $this->hdl->selectElem(DB_T_PREFIX."stadium","*","1 ORDER BY std_order DESC, std_title_ru ASC, std_id ASC");
    }

    public function getClubStadiumListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."stadium","*","1 ORDER BY std_id ASC");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['std_id']] = $item;
            }
            return $list;
        } else return false;
    }

    // ГОРОДА /////////////////////////////////////////////////////////////////////////////////////

    public function createCity($post){
        if($post['ct_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            intval($post['ct_cn_id']),
            str_replace($search, $replace, $post['ct_title_ru']),
            str_replace($search, $replace, $post['ct_title_ua']),
            str_replace($search, $replace, $post['ct_title_en']),
            addslashes($post['ct_description_ru']),
            addslashes($post['ct_description_ua']),
            addslashes($post['ct_description_en']),
            intval($post['ct_order']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX."city", $elem)) return true;
        else return false;
    }

    public function updateCity($post){
        if ($post['ct_id'] <1) return false;
        if($post['ct_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "ct_cn_id" => intval($post['ct_cn_id']),
            "ct_title_ru" => str_replace($search, $replace, $post['ct_title_ru']),
            "ct_title_ua" => str_replace($search, $replace, $post['ct_title_ua']),
            "ct_title_en" => str_replace($search, $replace, $post['ct_title_en']),
            "ct_description_ru" => addslashes($post['ct_description_ru']),
            "ct_description_ua" => addslashes($post['ct_description_ua']),
            "ct_description_en" => addslashes($post['ct_description_en']),
            "ct_order" => intval($post['ct_order']),
            "ct_is_active" => $is_active,
            "ct_datetime_edit" => 'NOW()',
            "ct_author" => USER_ID
        );
        $condition = array(
            "ct_id"=>$post['ct_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."city",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCity($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."city", "ct_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCityItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."city","*","ct_id=$item");
            $temp[0]['ct_title_ru'] = stripcslashes($temp[0]['ct_title_ru']);
            $temp[0]['ct_title_ua'] = stripcslashes($temp[0]['ct_title_ua']);
            $temp[0]['ct_title_en'] = stripcslashes($temp[0]['ct_title_en']);
            $temp[0]['ct_description_ru'] = stripcslashes($temp[0]['ct_description_ru']);
            $temp[0]['ct_description_ua'] = stripcslashes($temp[0]['ct_description_ua']);
            $temp[0]['ct_description_en'] = stripcslashes($temp[0]['ct_description_en']);
            return $temp[0];
        } else return false;
    }

    public function getClubCityList(){
        return $this->hdl->selectElem(DB_T_PREFIX."city","*","1 ORDER BY ct_order DESC, ct_id ASC");
    }

    public function getClubCityListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."city","*","1 ORDER BY ct_order DESC, ct_id ASC");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['ct_id']] = $item;
            }
            return $list;
        } else return false;
    }

    // СТРАНЫ /////////////////////////////////////////////////////////////////////////////////////

    public function createCountry($post){
        if($post['cn_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            str_replace($search, $replace, $post['cn_title_ru']),
            str_replace($search, $replace, $post['cn_title_ua']),
            str_replace($search, $replace, $post['cn_title_en']),
            addslashes($post['cn_description_ru']),
            addslashes($post['cn_description_ua']),
            addslashes($post['cn_description_en']),
            intval($post['cn_order']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            addslashes(str_replace($search_a, $replace_a, strtolower($post['cn_address'])))
        );
        if ($this->hdl->addElem(DB_T_PREFIX."country", $elem)) return true;
        else return false;
    }

    public function updateCountry($post){
        if ($post['cn_id'] <1) return false;
        if($post['cn_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "cn_title_ru" => str_replace($search, $replace, $post['cn_title_ru']),
            "cn_title_ua" => str_replace($search, $replace, $post['cn_title_ua']),
            "cn_title_en" => str_replace($search, $replace, $post['cn_title_en']),
            "cn_description_ru" => addslashes($post['cn_description_ru']),
            "cn_description_ua" => addslashes($post['cn_description_ua']),
            "cn_description_en" => addslashes($post['cn_description_en']),
            "cn_order" => intval($post['cn_order']),
            "cn_is_active" => $is_active,
            "cn_datetime_edit" => 'NOW()',
            "cn_author" => USER_ID,
            "cn_address" => addslashes(str_replace($search_a, $replace_a, strtolower($post['cn_address'])))
        );
        $condition = array(
            "cn_id"=>$post['cn_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."country",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCountry($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."country", "cn_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCountryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."country","*","cn_id=$item");
            $temp[0]['cn_title_ru'] = stripcslashes($temp[0]['cn_title_ru']);
            $temp[0]['cn_title_ua'] = stripcslashes($temp[0]['cn_title_ua']);
            $temp[0]['cn_title_en'] = stripcslashes($temp[0]['cn_title_en']);
            $temp[0]['cn_description_ru'] = stripcslashes($temp[0]['cn_description_ru']);
            $temp[0]['cn_description_ua'] = stripcslashes($temp[0]['cn_description_ua']);
            $temp[0]['cn_description_en'] = stripcslashes($temp[0]['cn_description_en']);
            return $temp[0];
        } else return false;
    }

    public function getClubCountryList(){
        return $this->hdl->selectElem(DB_T_PREFIX."country","*","1 ORDER BY cn_order DESC, cn_title_ru ASC, cn_id ASC");
    }

    public function getClubCountryListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."country","*","1 ORDER BY cn_order DESC, cn_id ASC");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['cn_id']] = $item;
            }
            return $list;
        } else return false;
    }

    // ДОЛЖНОСТИ /////////////////////////////////////////////////////////////////////////////////////

    public function createCategory($post){
        if($post['app_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['app_type']== '1') $app_type ='player';
        elseif ($post['app_type']== '2') $app_type ='head';
        else $app_type ='rest';
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            str_replace($search, $replace, $post['app_title_ru']),
            str_replace($search, $replace, $post['app_title_ua']),
            str_replace($search, $replace, $post['app_title_en']),
            addslashes($post['app_description_ru']),
            addslashes($post['app_description_ua']),
            addslashes($post['app_description_en']),
            $app_type,
            intval($post['app_order']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX."team_appointment", $elem)) return true;
        else return false;
    }

    public function updateCategory($post){
        if ($post['app_id'] <1) return false;
        if($post['app_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['app_type']== '1') $app_type ='player';
        elseif ($post['app_type']== '2') $app_type ='head';
        else $app_type ='rest';
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "app_title_ru" => str_replace($search, $replace, $post['app_title_ru']),
            "app_title_ua" => str_replace($search, $replace, $post['app_title_ua']),
            "app_title_en" => str_replace($search, $replace, $post['app_title_en']),
            "app_description_ru" => addslashes($post['app_description_ru']),
            "app_description_ua" => addslashes($post['app_description_ua']),
            "app_description_en" => addslashes($post['app_description_en']),
            "app_type" => $app_type,
            "app_order" => intval($post['app_order']),
            "app_is_active" => $is_active,
            "app_datetime_edit" => 'NOW()',
            "app_author" => USER_ID
        );
        $condition = array(
            "app_id"=>$post['app_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."team_appointment",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCategory($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."team_appointment", "app_id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getCategoryItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."team_appointment","*","app_id=$item");
            $temp[0]['app_title_ru'] = stripcslashes($temp[0]['app_title_ru']);
            $temp[0]['app_title_ua'] = stripcslashes($temp[0]['app_title_ua']);
            $temp[0]['app_title_en'] = stripcslashes($temp[0]['app_title_en']);
            $temp[0]['app_description_ru'] = stripcslashes($temp[0]['app_description_ru']);
            $temp[0]['app_description_ua'] = stripcslashes($temp[0]['app_description_ua']);
            $temp[0]['app_description_en'] = stripcslashes($temp[0]['app_description_en']);
            return $temp[0];
        } else return false;
    }

    public function getClubCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."team_appointment","*","1 ORDER BY app_order DESC, app_title_ru DESC, app_id ASC");
    }

    public function getClubCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."team_appointment","*","1 ORDER BY app_id ASC");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['app_id']] = $item;
            }
            return $list;
        } else return false;
    }

    // КЛУБЫ ///////////////////////////////////////////////////////////////////////////////////////

    public function createClub($post){ // добавление клуба
        if($post['cl_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['cl_is_main']==true) {
            $this->discardMainClub();
            $cl_is_main ='yes';
        } else $cl_is_main = 'no';
        if ($post['cl_date_day'] != '' and $post['cl_date_day']>0 and $post['cl_date_month'] != '' and $post['cl_date_month']>0 and $post['cl_date_year'] != '' and $post['cl_date_year']>0) {
            $post['cl_date_day'] = intval($post['cl_date_day']);
            $post['cl_date_month'] = intval($post['cl_date_month']);
            $post['cl_date_year'] = intval($post['cl_date_year']);
            if ($post['cl_date_day'] < 10) $post['cl_date_day'] = '0'.$post['cl_date_day'];
            if ($post['cl_date_month'] < 10) $post['cl_date_month'] = '0'.$post['cl_date_month'];
            $cl_date_foundation = $post['cl_date_year']."-".$post['cl_date_month']."-".$post['cl_date_day'];
        }
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            $cl_date_foundation,
            intval($post['cl_cn_id']),
            intval($post['t_ct_id']),
            str_replace($search, $replace, $post['cl_title_ru']),
            str_replace($search, $replace, $post['cl_title_ua']),
            str_replace($search, $replace, $post['cl_title_en']),
            addslashes($post['cl_description_ru']),
            addslashes($post['cl_description_ua']),
            addslashes($post['cl_description_en']),
            addslashes($post['cl_text_ru']),
            addslashes($post['cl_text_ua']),
            addslashes($post['cl_text_en']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $cl_is_main
        );
        if ($this->hdl->addElem(DB_T_PREFIX."club", $elem)) return true;
        else return false;
    }

    public function updateClub($post){ // редактирование клуба
        if($post['cl_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['cl_is_main']==true) {
            $this->discardMainClub();
            $cl_is_main ='yes';
        } else $cl_is_main = 'no';
        if ($post['cl_date_day'] != '' and $post['cl_date_day']>0 and $post['cl_date_month'] != '' and $post['cl_date_month']>0 and $post['cl_date_year'] != '' and $post['cl_date_year']>0) {
            $post['cl_date_day'] = intval($post['cl_date_day']);
            $post['cl_date_month'] = intval($post['cl_date_month']);
            $post['cl_date_year'] = intval($post['cl_date_year']);
            if ($post['cl_date_day'] < 10) $post['cl_date_day'] = '0'.$post['cl_date_day'];
            if ($post['cl_date_month'] < 10) $post['cl_date_month'] = '0'.$post['cl_date_month'];
            $cl_date_foundation = $post['cl_date_year']."-".$post['cl_date_month']."-".$post['cl_date_day'];
        }
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "cl_date_foundation" => $cl_date_foundation,
            "cl_cn_id" => intval($post['cl_cn_id']),
            "cl_ct_id" => intval($post['t_ct_id']),
            "cl_title_ru" => str_replace($search, $replace, $post['cl_title_ru']),
            "cl_title_ua" => str_replace($search, $replace, $post['cl_title_ua']),
            "cl_title_en" => str_replace($search, $replace, $post['cl_title_en']),
            "cl_description_ru" => addslashes($post['cl_description_ru']),
            "cl_description_ua" => addslashes($post['cl_description_ua']),
            "cl_description_en" => addslashes($post['cl_description_en']),
            "cl_text_ru" => addslashes($post['cl_text_ru']),
            "cl_text_ua" => addslashes($post['cl_text_ua']),
            "cl_text_en" => addslashes($post['cl_text_en']),
            "cl_is_active" => $is_active,
            "cl_is_main" => $cl_is_main,
            "cl_datetime_edit" => 'NOW()',
            "cl_author" => USER_ID
        );
        $condition = array(
            "cl_id"=>$post['cl_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."club",$elems, $condition)) return true;
        else return false;
    }

    public function discardMainClub(){
        $elems = array(
            "cl_is_main " => 'no'
        );
        if ($this->hdl->updateAll(DB_T_PREFIX."club",$elems)) return true;
        else return false;
    }

    public function getClubList(){
        return $this->hdl->selectElem(DB_T_PREFIX."club","*","1 ORDER BY cl_title_ru ASC, cl_id DESC");
    }

    public function getClubItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."club","*","cl_id=$item");
            $temp[0]['cl_title_ru'] = str_replace($search, $replace, $temp[0]['cl_title_ru']);
            $temp[0]['cl_title_ua'] = str_replace($search, $replace, $temp[0]['cl_title_ua']);
            $temp[0]['cl_title_en'] = str_replace($search, $replace, $temp[0]['cl_title_en']);
            $temp[0]['cl_description_ru'] = stripcslashes($temp[0]['cl_description_ru']);
            $temp[0]['cl_description_ua'] = stripcslashes($temp[0]['cl_description_ua']);
            $temp[0]['cl_description_en'] = stripcslashes($temp[0]['cl_description_en']);
            $temp[0]['cl_text_ru'] = stripcslashes($temp[0]['cl_text_ru']);
            $temp[0]['cl_text_ua'] = stripcslashes($temp[0]['cl_text_ua']);
            $temp[0]['cl_text_en'] = stripcslashes($temp[0]['cl_text_en']);
            return $temp[0];
        } else return false;
    }

    public function deleteClub($cl_id){
        $cl_id = intval($cl_id);
        if ($cl_id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."club", "cl_id='$cl_id'")) return true;
            else return false;
        }else return false;
    }

    // ГАЛЕРЕЯ ///////////////////////////////////////////////////////////////////////////////////////

    public function savePhoto($club_item, &$photos_class){ // добавление фотографии в галерею
        $club_item['cl_id'] = intval($club_item['cl_id']);
        if ($club_item['cl_id'] < 1) return false;
        $type = 'club';

        if ($_POST['ph_gallery_id'] == 'new') {
            if ($club_item['cl_title_ru'] == '') $phg_post['phg_title_ru'] = $club_item['cl_id'];
            else $phg_post['phg_title_ru'] = $club_item['cl_title_ru'];
            if ($club_item['cl_title_ua'] == '') $phg_post['phg_title_ua'] = $club_item['cl_id'];
            else $phg_post['phg_title_ua'] = $club_item['cl_title_ua'];
            if ($club_item['cl_title_en'] == '') $phg_post['phg_title_en'] = $club_item['cl_id'];
            else $phg_post['phg_title_en'] = $club_item['cl_title_en'];

            $phg_post['phg_description_ru'] = "Фото галерея клуба &laquo;".$phg_post['phg_title_ru']."&raquo;.";
            $phg_post['phg_description_ua'] = "Фото галерея клубу &laquo;".$phg_post['phg_title_ua']."&raquo;.";
            $phg_post['phg_description_en'] = "Photo gallery of club &laquo;".$phg_post['phg_title_en']."&raquo;.";
            $phg_post['phg_is_active'] = false;
            $phg_post['phg_type'] = $type;
            $phg_post['phg_type_id'] = $club_item['cl_id'];
            $phg_post['phg_phc_id'] = 0;
            $phg_post['phg_datetime_pub'] = 'NOW()';

            $_POST['ph_gallery_id'] = $photos_class->addPhotoGallery($phg_post);
            if ($_POST['ph_gallery_id']<1) return false;
        }
        $_POST['ph_gallery_id'] = intval($_POST['ph_gallery_id']);
        if ($_POST['ph_type_main']) $photos_class->resetTypeMainPhotos($club_item['cl_id'], $type);
        $_POST['ph_type_id'] = $club_item['cl_id'];
        $_POST['ph_type'] = $type;

        if ($photos_class->savePhoto($_FILES['file_photo'], $_POST)) return true;
        return false;
    }

    public function saveVideo($club_item, &$videos_class){ // добавление видео в галерею
        $club_item['cl_id'] = intval($club_item['cl_id']);
        if ($club_item['cl_id'] < 1) return false;
        $type = 'club';

        if ($_POST['v_gallery_id'] == 'new') {
            if ($club_item['cl_title_ru'] == '') $vg_post['vg_title_ru'] = $club_item['cl_id'];
            else $vg_post['vg_title_ru'] = $club_item['cl_title_ru'];
            if ($club_item['cl_title_ua'] == '') $vg_post['vg_title_ua'] = $club_item['cl_id'];
            else $vg_post['vg_title_ua'] = $club_item['cl_title_ua'];
            if ($club_item['cl_title_en'] == '') $vg_post['vg_title_en'] = $club_item['cl_id'];
            else $vg_post['vg_title_en'] = $club_item['cl_title_en'];

            $vg_post['vg_description_ru'] = "Видео галерея клуба &laquo;".$vg_post['vg_title_ru']."&raquo;.";
            $vg_post['vg_description_ua'] = "Відео галерея клубу &laquo;".$vg_post['vg_title_ua']."&raquo;.";
            $vg_post['vg_description_en'] = "Video gallery of club &laquo;".$vg_post['vg_title_en']."&raquo;.";
            $vg_post['vg_is_active'] = true;
            $vg_post['vg_type'] = $type;
            $vg_post['vg_type_id'] = $club_item['cl_id'];
            $vg_post['vg_phc_id'] = 0;
            $vg_post['vg_datetime_pub'] = 'NOW()';

            $_POST['v_gallery_id'] = $videos_class->addVideoGallery($vg_post);
            if ($_POST['v_gallery_id']<1) return false;
        }
        $_POST['v_gallery_id'] = intval($_POST['v_gallery_id']);
        $_POST['v_type_id'] = $club_item['cl_id'];
        $_POST['v_type'] = $type;

        if ($videos_class->saveVideo($_POST)) return true;
        return false;
    }

    // SETTINGS ///////////////////////////////////////////////////////////////////////////////////////

    public function getClubSettings(){
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
