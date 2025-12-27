<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class competitions{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // СОРЕВНОВАНИЯ ///// НАЧАЛО //////////////////////////////////////////////////////////////////////////////////

    public function getChampionshipCountryListNE(){
        return $this->hdl->selectElem(DB_T_PREFIX."country, ".DB_T_PREFIX."championship","*","ch_cn_id = cn_id GROUP BY cn_id ORDER BY cn_title_ru ASC");
    }

    public function getChampionshipList($is_aarchive = false){
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
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship
                                                        LEFT JOIN ".DB_T_PREFIX."championship_categories
                                                            ON ch_chc_id = chc_id
                                                        LEFT JOIN ".DB_T_PREFIX."championship_group
                                                            ON ch_chg_id = chg_id
                                                        LEFT JOIN ".DB_T_PREFIX."championship_local
                                                            ON chg_chl_id = chl_id
					","	ch_id,
						ch_title_ru,
						ch_is_main,
						ch_is_menu,
						ch_is_active,
						chc_title_ru ,
						chg_title_ru,
						chl_title_ru
					","	1
						$q_cn
						$q_archive
					ORDER BY chl_title_ru ASC, 
							chg_title_ru ASC, 
							ch_text_ru ASC, 
							ch_date_from DESC, 
							ch_id DESC");
        if ($temp) {
            for ($i=0; $i<count($temp); $i++){
                if ($this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id","cp_ch_id = '".$temp[$i]['ch_id']."' LIMIT 1")) $temp[$i]['is_strusture'] = 'yes';
                else $temp[$i]['is_strusture'] = 'no';
            }
            return $temp;
        } else return false;
    }

    public function getSeasonList(){
        return $this->hdl->selectElem(DB_T_PREFIX."championship
						","	ch_title_ru as title
						","	1
                                                GROUP BY ch_title_ru
						ORDER BY ch_title_ru ASC");
    }

    public function getChampionshipItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","*","ch_id=$item");
            $temp[0]['ch_title_ru'] = str_replace($search, $replace, $temp[0]['ch_title_ru']);
            $temp[0]['ch_title_ua'] = str_replace($search, $replace, $temp[0]['ch_title_ua']);
            $temp[0]['ch_title_en'] = str_replace($search, $replace, $temp[0]['ch_title_en']);
            $temp[0]['ch_description_ru'] = stripcslashes($temp[0]['ch_description_ru']);
            $temp[0]['ch_description_ua'] = stripcslashes($temp[0]['ch_description_ua']);
            $temp[0]['ch_description_en'] = stripcslashes($temp[0]['ch_description_en']);
            $temp[0]['ch_text_ru'] = stripcslashes($temp[0]['ch_text_ru']);
            $temp[0]['ch_text_ua'] = stripcslashes($temp[0]['ch_text_ua']);
            $temp[0]['ch_text_en'] = stripcslashes($temp[0]['ch_text_en']);
            $temp[0]['ch_tours'] = $temp[0]['ch_tours']+1;
            $temp[0]['ch_settings'] = unserialize($this->_fix_serialized_string($temp[0]['ch_settings']));
            $group = $this->hdl->selectElem(DB_T_PREFIX."championship_group","chg_title_ru","chg_id='".$temp[0]['ch_chg_id']."' LIMIT 1");
            if ($group) $temp[0]['chg_title_ru'] = $group[0]['chg_title_ru'];
            return $temp[0];
        } else return false;
    }

    public function getChampionshipTeam($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team","*","cntch_ch_id = '$item' AND cntch_t_id = t_id AND cntch_is_delete = 'no' ORDER BY t_title_ru ASC");
            if ($temp){
                for($i=0; $i<count($temp); $i++){
                    $temp[$i]['t_title_ru'] = str_replace($search, $replace, $temp[$i]['t_title_ru']);
                    $temp[$i]['t_title_ua'] = str_replace($search, $replace, $temp[$i]['t_title_ua']);
                    $temp[$i]['t_title_en'] = str_replace($search, $replace, $temp[$i]['t_title_en']);
                }
                return $temp;
            } else return false;
        } else return false;
    }

    public function getChampionshipTeamId($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team","*","cntch_ch_id = '$item' AND cntch_t_id = t_id AND cntch_is_delete = 'no' ORDER BY t_id ASC");
            if ($temp){
                for($i=0; $i<count($temp); $i++){
                    $temp[$i]['t_title_ru'] = str_replace($search, $replace, $temp[$i]['t_title_ru']);
                    $temp[$i]['t_title_ua'] = str_replace($search, $replace, $temp[$i]['t_title_ua']);
                    $temp[$i]['t_title_en'] = str_replace($search, $replace, $temp[$i]['t_title_en']);
                    $temp[$i]['stadium'] = $this->getStadium($temp[$i]['t_std_id']);
                    $res[$temp[$i]['t_id']] = $temp[$i];
                }
                return $res;
            }
        }
        return false;
    }

    public function getStadium($id) {
        $id = intval($id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."stadium, ".DB_T_PREFIX."country, ".DB_T_PREFIX."city","ct_title_ru, cn_title_ru, std_id, std_title_ru", "std_id = '$id' AND std_cn_id = cn_id AND std_ct_id = ct_id LIMIT 1");
        if ($temp){
            $temp[0]['ct_title_ru'] = stripcslashes($temp[0]['ct_title_ru']);
            $temp[0]['cn_title_ru'] = stripcslashes($temp[0]['cn_title_ru']);
            $temp[0]['std_title_ru'] = stripcslashes($temp[0]['std_title_ru']);
            return $temp[0];
        }
        return false;
    }

    public function SaveStructure($post){ // сохранение структуры через пошаговое добавление структуры
        $flag = 0;
        $search = array("'", '"');
        $replace = array('', '');
        $ch_stage = intval($post['ch_stage']);
        for ($i=1; $i<=$ch_stage; $i++){
            $substage = $ch_stage - $i + 1;
            $ch_competitions = intval($post['ch_competitions_'.$i]);
            for ($j=1; $j<=$ch_competitions; $j++){
                $parent = str_replace($search, $replace, $post['cp_parent_'.$i.'-'.$j]);
                if ($parent == '') $cp_parent = 0;
                else $cp_parent = $cp[$parent];
                $elem = array();
                $order = $ch_competitions - $j + 1;
                $elem = array(
                    $cp_parent,
                    intval($post['ch']),
                    str_replace($search, $replace, $post['cp_title_'.$i.'-'.$j]),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'yes',
                    'NOW()',
                    'NOW()',
                    USER_ID,
                    'no',
                    intval($post['tour']),
                    'yes',
                    'yes',
                    $substage,
                    $order
                );
                $cp[$i.'-'.$j] = $this->hdl->addElem(DB_T_PREFIX."competitions", $elem);
                if ($cp[$i.'-'.$j]>0 and $flag == 0) $flag = 2;
                if ($cp[$i.'-'.$j]<1) $flag = 1;
            }
        }
        if ($flag > 1) {
            $elems = array(
                "ch_cp_is_done" => 'yes',
                "ch_datetime_edit" => 'NOW()',
                "ch_author" => USER_ID
            );
            $condition = array(
                "ch_id"=>intval($post['ch'])
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        } else return false;
    }

    /*
    public function GetStructureFGames($ch = 0, $array_p_id = false){ // выборка структуры СТАРОЕ
        $ch = intval($ch);
        $p_q = '';
        if (!$array_p_id) return false;
        else
            foreach ($array_p_id as $key => $value)
                $p_q .= " OR cp_parent_id = '".$value['cp_id']."'";
        if (strlen($p_q)>0) $p_q = "AND (".substr($p_q, 3).")";
        $list = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id"," cp_ch_id = '".$ch."' ".$p_q." ORDER BY cp_id ASC");
        //var_dump($list);
        if ($list) return $list;
        else return false;
    }
    */

    public function GetStructureGames($ch = 0, $tour = 0){ // выборка структуры
        $ch = intval($ch);
        $tour = intval($tour);
        if ($ch<1) return false;
        $list = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id, cp_order, cp_substage"," cp_ch_id = '$ch' AND cp_tour = '$tour' ORDER BY cp_id ASC, cp_substage DESC, cp_order DESC");
        if (!$list) return false;
        $max_substage = 0;
        $max_order = array();
        for ($i=0; $i<count($list); $i++) {
            if ($list[$i]['cp_substage'] > $max_substage) $max_substage = $list[$i]['cp_substage'];
            if ($list[$i]['cp_order'] > $max_order[$list[$i]['cp_substage']]) $max_order[$list[$i]['cp_substage']] = $list[$i]['cp_order'];
        }
        foreach ($list as $item){
            $substage = $max_substage - $item['cp_substage'] + 1;
            $order = $max_order[$item['cp_substage']] - $item['cp_order'] + 1;
            $res[$substage][$order] = $item;
        }
        //var_dump($res);
        if (count($res)>0) return $res;
        return false;
    }

    public function SaveStructureGames($post){ // сохранение игр через пошаговое добавление структуры
        $ch = intval($post['ch']);
        $tour = intval($post['tour']);
        if ($ch<1) return false;
        $n_game = intval($post['n_game']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_id, ch_cp_is_done"," ch_id = '".$ch."' LIMIT 1");
        if (!$temp) return false;
        if ($temp[0]['ch_cp_is_done'] == 'no') $this->SaveStructure($post); // сохранение структуры


        // выборка структуры НАЧАЛО
        $list_struct = $this->GetStructureGames($ch, $tour);

        // выборка структуры НАЧАЛО СТАРОЕ
        /*
        $array_p_id[0]['cp_id'] = 0;
        $i = 1;
        while($array_p_id){
            $array_p_id = $this->GetStructureFGames($ch, $array_p_id);
            $list_struct[$i]=$array_p_id;
            $i++;
        }
        */
        // выборка структуры КОНЕЦ
        $flag = 0;

        for ($i=1; $i<=$n_game; $i++){
            if ($post['g_'.$i.'_is_save']){
                $g_owner_t_id = $g_owner_t_comment = $g_guest_t_id = $g_guest_t_comment = $g_date_schedule = $g_competition = '';
                if (strpos($post['g_'.$i.'_team_1'], "-")>0){
                    $g_owner_t_id = 0;
                    $g_o_c = explode('-', $post['g_'.$i.'_team_1']);
                    $g_o_c[0] = intval($g_o_c[0]);
                    $g_o_c[1] = intval($g_o_c[1]);
                    $g_o_c[2] = intval($g_o_c[2]);
                    $g_owner_t_comment = intval($g_o_c[0])."-".$list_struct[$g_o_c[1]][$g_o_c[2]]['cp_id'];
                    unset($g_o_c);
                } else {
                    $g_owner_t_id = $post['g_'.$i.'_team_1'];
                    $g_owner_t_comment = '';
                }
                if (strpos($post['g_'.$i.'_team_2'], "-")>0){
                    $g_guest_t_id = 0;
                    $g_o_c = explode('-', $post['g_'.$i.'_team_2']);
                    $g_o_c[0] = intval($g_o_c[0]);
                    $g_o_c[1] = intval($g_o_c[1]);
                    $g_o_c[2] = intval($g_o_c[2]);
                    $g_guest_t_comment = intval($g_o_c[0])."-".$list_struct[$g_o_c[1]][$g_o_c[2]]['cp_id'];
                    unset($g_o_c);
                } else {
                    $g_guest_t_id = $post['g_'.$i.'_team_2'];
                    $g_guest_t_comment = '';
                }
                $post['g_'.$i.'_date_day'] = intval($post['g_'.$i.'_date_day']);
                $post['g_'.$i.'_date_month'] = intval($post['g_'.$i.'_date_month']);
                $post['g_'.$i.'_date_year'] = intval($post['g_'.$i.'_date_year']);
                $post['g_'.$i.'_date_hour'] = intval($post['g_'.$i.'_date_hour']);
                $post['g_'.$i.'_date_minute'] = intval($post['g_'.$i.'_date_minute']);
                if ($post['g_'.$i.'_date_day'] < 10) $post['g_'.$i.'_date_day'] = '0'.$post['g_'.$i.'_date_day'];
                if ($post['g_'.$i.'_date_month'] < 10) $post['g_'.$i.'_date_month'] = '0'.$post['g_'.$i.'_date_month'];
                if ($post['g_'.$i.'_date_hour'] < 10) $post['g_'.$i.'_date_hour'] = '0'.$post['g_'.$i.'_date_hour'];
                if ($post['g_'.$i.'_date_minute'] < 10) $post['g_'.$i.'_date_minute'] = '0'.$post['g_'.$i.'_date_minute'];
                $g_date_schedule = $post['g_'.$i.'_date_year']."-".$post['g_'.$i.'_date_month']."-".$post['g_'.$i.'_date_day']." ".$post['g_'.$i.'_date_hour'].":".$post['g_'.$i.'_date_minute'].":00";
                $g_o_c = array();
                $g_o_c = explode('-', $post['g_'.$i.'_comp']);
                $g_o_c[0] = intval($g_o_c[0]);
                $g_o_c[1] = intval($g_o_c[1]);
                /*
                $c_pos = strpos($post['g_'.$i.'_comp'], "-");
                $c = intval(substr($post['g_'.$i.'_comp'], 0, $c_pos));
                $cc = intval(substr($post['g_'.$i.'_comp'], $c_pos+1));
                */
                $g_competition = $list_struct[$g_o_c[0]][$g_o_c[1]]['cp_id'];
                if ($post['g_'.$i.'_is_schedule_time']) $is_schedule_time = 'yes';
                else $is_schedule_time = 'no';
                $elem = array(
                    $ch,
                    $g_competition,
                    $g_owner_t_id,
                    $g_owner_t_comment,
                    $g_guest_t_id,
                    $g_guest_t_comment,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'yes',
                    'NOW()',
                    'NOW()',
                    USER_ID,
                    $g_date_schedule,
                    'no',
                    0,
                    0,
                    $is_schedule_time,
                    'no',
                    'no',
                    'no',
                    'no',
                    intval($post['g_'.$i.'_g_round']),
                    '',
                    'no',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '',
                    'no'
                );
                if (!$this->hdl->selectElem(DB_T_PREFIX."games","g_id"," g_ch_id = '$ch' AND g_cp_id = '$g_competition' AND g_owner_t_id = '$g_owner_t_id' AND g_owner_t_comment = '$g_owner_t_comment' AND g_guest_t_id = '$g_guest_t_id' AND g_guest_t_comment = '$g_guest_t_comment' AND g_date_schedule = '$g_date_schedule' LIMIT 1")){
                    $t = $this->hdl->addElem(DB_T_PREFIX."games", $elem);
                    if ($t>0 and $flag == 0) $flag = 2;
                    if ($t<1) $flag = 1;
                } else $flag = 1;
                unset($elem);
            }
        }
        if ($flag > 1) return true;
        else return false;
    }

    public function getCompetitionsListForm($ch, $tour = 0){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","
							cp_id,
							cp_parent_id,
							cp_title_ru AS title,
							cp_is_active,
							cp_is_done,
							cp_tour,
							cp_substage
							","cp_ch_id = '$ch' AND cp_tour = '$tour' ORDER BY cp_tour ASC, cp_substage DESC, cp_order DESC, cp_title_ru ASC");
        return $temp;
    }

    public function getCompetitionsList($tour = 0){
        $tour = intval($tour);
        return $this->getCompetitionsByParent(0,0,0,'', 'cp_id', 'DESC', $tour);
    }

    public function getCompetitionsListTour($ch = 0){
        $ch = intval($ch);
        if ($ch<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","
							cp_id,
							cp_parent_id,
							cp_title_ru AS title,
							cp_is_active,
							cp_is_done,
							cp_tour,
							cp_substage
							","cp_ch_id = '$ch' ORDER BY cp_tour ASC, cp_substage DESC, cp_order DESC, cp_title_ru ASC");
        if ($temp){
            $res = array();
            foreach ($temp as $item) {
                $temp_g = $this->hdl->selectElem(DB_T_PREFIX."games","COUNT(*) as C_G","g_cp_id = '".$item['cp_id']."'");
                if ($temp_g) $item['c_games'] = $temp_g[0]['C_G'];
                else $item['c_games'] = false;
                $res[$item['cp_tour']][$item['cp_substage']][] = $item;
            }
            //var_dump($res);
            return $res;
        }
        return false;
    }

    public function getCompetitionsByParent($parent_id = 0, $nesting = 0, $depth = 0, $is_active='', $order_by = '', $order = 'ASC', $tour = 0){
        if ($nesting<$depth or $depth == 0){
            $extra = '';
            $ch = intval($_GET['ch']);
            $tour = intval($tour);
            $parent_id = intval($parent_id);
            if ($is_active == 'yes') $extra .= " AND cp_is_active = 'yes'";
            if ($is_active == 'no') $extra .= " AND cp_is_active = 'no'";
            if ($order == 'desc' OR $order == 'DESC' OR $order == 'Desc') $order = 'DESC';
            else $order = 'ASC';
            if ($order_by != '') $extra .= " ORDER BY $order_by $order";
            $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","*","cp_parent_id = '$parent_id' AND cp_ch_id = '$ch' AND cp_tour = '$tour' ".$extra);
            if ($temp != false){
                $result = array();
                $new_nesting = $nesting+1;
                foreach ($temp as $key=>$item){
                    $temp_item = $item;
                    $temp_item['nesting'] = $nesting;
                    $temp_i = $this->hdl->selectElem(DB_T_PREFIX."games","COUNT(*) as C_G","g_cp_id = '".$temp_item['cp_id']."'");
                    $temp_item['c_games'] = $temp_i[0]['C_G'];
                    $result[] = $temp_item;
                    $parent_result = $this->getCompetitionsByParent($temp_item['cp_id'], $new_nesting, $depth, $is_active, $order_by, $order, $tour);
                    if ($parent_result != false){
                        foreach ($parent_result as $key_parent=>$item_parent) $result[] = $item_parent;
                    }
                }
                return $result;
            } else return false;
        } else return false;
    }

    public function createCompetitions($post){ // добавление соревнования в ручную
        $post['ch'] = intval($post['ch']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_id, ch_cp_is_done","ch_id = '".$post['ch']."'");
        if ($temp) {
            if($post['cp_is_active']==true) $is_active ='yes';
            else $is_active = 'no';
            $search = array("'", '"');
            $replace = array('', '');
            $elem = array(
                //intval($post['cp_parent_id']),
                0, // cp_parent_id ^^^
                $post['ch'],
                str_replace($search, $replace, $post['cp_title_ru']),
                str_replace($search, $replace, $post['cp_title_ua']),
                str_replace($search, $replace, $post['cp_title_en']),
                (strlen(trim(html_entity_decode(strip_tags($post['cp_description_ru']))))>0) ? addslashes($post['cp_description_ru']) : NULL,
                (strlen(trim(html_entity_decode(strip_tags($post['cp_description_ua']))))>0) ? addslashes($post['cp_description_ua']) : NULL,
                (strlen(trim(html_entity_decode(strip_tags($post['cp_description_en']))))>0) ? addslashes($post['cp_description_en']) : NULL,
                (strlen(trim(html_entity_decode(strip_tags($post['cp_text_ru']))))>0) ? addslashes($post['cp_text_ru']) : NULL,
                (strlen(trim(html_entity_decode(strip_tags($post['cp_text_ua']))))>0) ? addslashes($post['cp_text_ua']) : NULL,
                (strlen(trim(html_entity_decode(strip_tags($post['cp_text_en']))))>0) ? addslashes($post['cp_text_en']) : NULL,
                $is_active,
                'NOW()',
                'NOW()',
                USER_ID,
                'no',
                intval($post['tour']),
                ($post['cp_is_menu']) ? 'yes' : 'no',
                ($post['cp_is_rating_table']) ? 'yes' : 'no',
                intval($post['cp_substage']),
                intval($post['cp_order'])
            );
            if ($this->hdl->addElem(DB_T_PREFIX."competitions", $elem)) {
                if ($temp[0]['ch_cp_is_done'] == 'no'){
                    $elems['ch_cp_is_done'] = 'yes';
                    $condition['ch_id'] = $post['ch'];
                    $this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition);
                }
                return true;
            }
        }
        return false;
    }

    public function getCompetitionsItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","*","cp_id=$item");
            $temp[0]['cp_title_ru'] = str_replace($search, $replace, $temp[0]['cp_title_ru']);
            $temp[0]['cp_title_ua'] = str_replace($search, $replace, $temp[0]['cp_title_ua']);
            $temp[0]['cp_title_en'] = str_replace($search, $replace, $temp[0]['cp_title_en']);
            $temp[0]['cp_description_ru'] = stripcslashes($temp[0]['cp_description_ru']);
            $temp[0]['cp_description_ua'] = stripcslashes($temp[0]['cp_description_ua']);
            $temp[0]['cp_description_en'] = stripcslashes($temp[0]['cp_description_en']);
            $temp[0]['cp_text_ru'] = stripcslashes($temp[0]['cp_text_ru']);
            $temp[0]['cp_text_ua'] = stripcslashes($temp[0]['cp_text_ua']);
            $temp[0]['cp_text_en'] = stripcslashes($temp[0]['cp_text_en']);
            $temp[0]['games'] = $this->hdl->selectElem(DB_T_PREFIX."games","g_id","g_cp_id=$item");
            return $temp[0];
        } else return false;
    }

    public function updateCompetitions($post){ // редактирование соревнования
        if($post['cp_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $post['cp_id'] = intval($post['cp_id']);
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "cp_title_ru" => str_replace($search, $replace, $post['cp_title_ru']),
            "cp_title_ua" => str_replace($search, $replace, $post['cp_title_ua']),
            "cp_title_en" => str_replace($search, $replace, $post['cp_title_en']),
            "cp_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['cp_description_ru']))))>0) ? addslashes($post['cp_description_ru']) : NULL,
            "cp_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['cp_description_ua']))))>0) ? addslashes($post['cp_description_ua']) : NULL,
            "cp_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['cp_description_en']))))>0) ? addslashes($post['cp_description_en']) : NULL,
            "cp_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['cp_text_ru']))))>0) ? addslashes($post['cp_text_ru']) : NULL,
            "cp_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['cp_text_ua']))))>0) ? addslashes($post['cp_text_ua']) : NULL,
            "cp_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['cp_text_en']))))>0) ? addslashes($post['cp_text_en']) : NULL,
            "cp_is_active" => $is_active,
            "cp_datetime_edit" => 'NOW()',
            "cp_author" => USER_ID,
            "cp_is_menu" => ($post['cp_is_menu']) ? 'yes' : 'no',
            "cp_is_rating_table" => ($post['cp_is_rating_table']) ? 'yes' : 'no',
            "cp_substage" => intval($post['cp_substage']),
            "cp_order" => intval($post['cp_order'])
        );
        //$temp = $this->hdl->selectElem(DB_T_PREFIX."games","g_id","g_cp_id='".$post['cp_id']."' LIMIT 1");
        //if (!$temp) $elems['cp_parent_id'] = intval($post['cp_parent_id']);
        $condition = array(
            "cp_id"=>$post['cp_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."competitions",$elems, $condition)) return true;
        else return false;
    }

    public function deleteCompetitions($cp_id){
        $cp_id = intval($cp_id);
        if ($cp_id>0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."games","g_id","g_cp_id=$cp_id LIMIT 1");
            if (!$temp) {
                $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id","cp_parent_id=$cp_id LIMIT 1");
                if (!$temp) {
                    $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id, cp_ch_id","cp_id=$cp_id LIMIT 1");
                    if ($temp)
                        if ($this->hdl->delElem(DB_T_PREFIX."competitions", "cp_id='$cp_id'")) {
                            $cp_ch_id = $temp[0]['cp_ch_id'];
                            $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id, cp_ch_id","cp_ch_id = '".$cp_ch_id."' LIMIT 1");
                            if (!$temp) {
                                $elems = array(
                                    "ch_cp_is_done" => 'no'
                                );
                                $condition = array(
                                    "ch_id" => $cp_ch_id
                                );
                                $this->hdl->updateElem(DB_T_PREFIX."championship", $elems, $condition);
                            }
                            return true;
                        }
                }
            }
        }
        return false;
    }

    public function saveStageTitle ($post = false) {
        if (!$post || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            $temp['ch_settings']['stageTitle']['ru'][intval($post['tour'])][intval($post['stage'])] = $post['stage_title_ru'];
            $temp['ch_settings']['stageTitle']['ua'][intval($post['tour'])][intval($post['stage'])] = $post['stage_title_ua'];
            $temp['ch_settings']['stageTitle']['en'][intval($post['tour'])][intval($post['stage'])] = $post['stage_title_en'];
            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function saveStageDate ($post = false) {
        if (!$post || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            $temp['ch_settings']['stageDateTime'][intval($post['tour'])][intval($post['stage'])] = $post['stage_datetime'];
            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function saveTourTitle ($post = false) {
        if (!$post || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            $temp['ch_settings']['tourTitle']['ru'][intval($post['tour'])] = $post['tour_title_ru'];
            $temp['ch_settings']['tourTitle']['ua'][intval($post['tour'])] = $post['tour_title_ua'];
            $temp['ch_settings']['tourTitle']['en'][intval($post['tour'])] = $post['tour_title_en'];
            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function saveTourDate ($post = false) {
        if (!$post || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            $temp['ch_settings']['tourDateTime'][intval($post['tour'])] = $post['tour_datetime'];
            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function saveStageIsOnePage ($post = false) {
        if (!$post || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            if ($post['is_show_one_page']){
                $temp['ch_settings']['isShowOnePage'][intval($post['tour'])][intval($post['stage'])] = 1;
            } else {
                if (isset($temp['ch_settings']['isShowOnePage'][intval($post['tour'])][intval($post['stage'])])){
                    unset($temp['ch_settings']['isShowOnePage'][intval($post['tour'])][intval($post['stage'])]);
                }
            }

            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function saveStageIsShowDate ($post = false) {
        if (!$post || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            if ($post['is_show_stage_datetime']){
                $temp['ch_settings']['isShowStageDateTime'][intval($post['tour'])][intval($post['stage'])] = 1;
            } else {
                if (isset($temp['ch_settings']['isShowStageDateTime'][intval($post['tour'])][intval($post['stage'])])){
                    unset($temp['ch_settings']['isShowStageDateTime'][intval($post['tour'])][intval($post['stage'])]);
                }
            }

            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    public function createGame($post){ // добавление Игры в ручную
        if ($post['g_cp_id'] == 0 or $_GET['ch'] == 0 or $post['g_owner_t_id'] == 0 or $post['g_guest_t_id'] == 0) return false;
        if($post['g_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['g_is_schedule_time']==true) $g_is_schedule_time ='yes';
        else $g_is_schedule_time = 'no';
        if($post['g_is_stadium']==true) $g_is_stadium ='yes';
        else $g_is_stadium = 'no';
        if($post['g_selected']==true) $g_selected ='yes';
        else $g_selected = 'no';
        $g_date_schedule = '';
        if ($post['g_date_day'] != '' and $post['g_date_day']>0 and $post['g_date_month'] != '' and $post['g_date_month']>0 and $post['g_date_year'] != '' and $post['g_date_year']>0) {
            $post['g_date_minute'] = intval($post['g_date_minute']);
            $post['g_date_hour'] = intval($post['g_date_hour']);
            $post['g_date_day'] = intval($post['g_date_day']);
            $post['g_date_month'] = intval($post['g_date_month']);
            $post['g_date_year'] = intval($post['g_date_year']);
            if ($post['g_date_minute'] < 10) $post['g_date_minute'] = '0'.$post['g_date_minute'];
            if ($post['g_date_hour'] < 10) $post['g_date_hour'] = '0'.$post['g_date_hour'];
            if ($post['g_date_day'] < 10) $post['g_date_day'] = '0'.$post['g_date_day'];
            if ($post['g_date_month'] < 10) $post['g_date_month'] = '0'.$post['g_date_month'];
            $g_date_schedule = $post['g_date_year']."-".$post['g_date_month']."-".$post['g_date_day']." ".$post['g_date_hour'].":".$post['g_date_minute'].":00";
        }
        $g_owner_t_ = explode('-', $post['g_owner_t_id']);
        for($i=0; $i<count($g_owner_t_); $i++) $g_owner_t_[$i] = intval($g_owner_t_[$i]);
        if (count($g_owner_t_)>1) {
            $g_owner_t_comment = implode('-', $g_owner_t_);
            $g_owner_t_id = 0;
        } else {
            $g_owner_t_comment = '';
            $g_owner_t_id = $g_owner_t_[0];
        }
        $g_guest_t_ = explode('-', $post['g_guest_t_id']);
        for($i=0; $i<count($g_guest_t_); $i++) $g_guest_t_[$i] = intval($g_guest_t_[$i]);
        if (count($g_guest_t_)>1) {
            $g_guest_t_comment = implode('-', $g_guest_t_);
            $g_guest_t_id = 0;
        } else {
            $g_guest_t_comment = '';
            $g_guest_t_id = $g_guest_t_[0];
        }
        $post['g_cp_id_g'] = intval($post['g_cp_id_g']);
        $post['g_cp_id'] = intval($post['g_cp_id']);
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            intval($_GET['ch']),
            $post['g_cp_id'],
            $g_owner_t_id,
            $g_owner_t_comment,
            $g_guest_t_id,
            $g_guest_t_comment,
            (strlen(trim(html_entity_decode(strip_tags($post['g_description_ru']))))>0) ? addslashes($post['g_description_ru']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['g_description_ua']))))>0) ? addslashes($post['g_description_ua']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['g_description_en']))))>0) ? addslashes($post['g_description_en']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['g_text_ru']))))>0) ? addslashes($post['g_text_ru']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['g_text_ua']))))>0) ? addslashes($post['g_text_ua']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($post['g_text_en']))))>0) ? addslashes($post['g_text_en']) : NULL,
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $g_date_schedule,
            'no',
            0,
            0,
            $g_is_schedule_time,
            'no',
            'no',
            'no',
            'no',
            intval($post['g_round']),
            '',
            $g_is_stadium,
            intval($post['t_cn_id']),
            intval($post['t_ct_id']),
            intval($post['t_std_id']),
            '0',
            '0',
            (empty($post['g_cp_id_g']))?$post['g_cp_id']:$post['g_cp_id_g'],
            floatval($post['g_date_time_zone']),
            '0',
            '0',
            '0',
            '',
            $g_selected
        );
        if ($this->hdl->addElem(DB_T_PREFIX."games", $elem)) return true;
        else return false;
    }

    public function getGameItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('', '');
        if ($item < 1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games, ".DB_T_PREFIX."competitions",DB_T_PREFIX."games.*, ".DB_T_PREFIX."competitions.cp_tour","g_id=$item AND g_cp_id = cp_id");
        if ($temp){
            $temp = $temp[0];
            $temp['g_description_ru'] = stripcslashes($temp['g_description_ru']);
            $temp['g_description_ua'] = stripcslashes($temp['g_description_ua']);
            $temp['g_description_en'] = stripcslashes($temp['g_description_en']);
            $temp['g_text_ru'] = stripcslashes($temp['g_text_ru']);
            $temp['g_text_ua'] = stripcslashes($temp['g_text_ua']);
            $temp['g_text_en'] = stripcslashes($temp['g_text_en']);
            // ИГРОКИ ////////////////////////////////////////////////////////////////////
            if (empty($_GET['app_type']) || $_GET['app_type'] == 'player') $app_type = 'player';
            if (!empty($_GET['app_type']) && $_GET['app_type'] == 'head') $app_type = 'head';
            if (!empty($_GET['app_type']) && $_GET['app_type'] == 'rest') $app_type = 'rest';
            // хозяева ///////
            $temp_t_owner = $this->hdl->selectElem(DB_T_PREFIX."team","t_title_ru","t_id='".$temp['g_owner_t_id']."' LIMIT 1");
            $temp['g_owner_t_title_ru'] = $temp_t_owner[0]['t_title_ru'];
            // члены команды
            $sort = !empty($_GET['sort']) ? $_GET['sort'] : '';
            $st_t = $this->getConnectStT($temp['g_owner_t_id'], $app_type, $sort);
            $st_g = $this->getConnectGSt($temp['g_owner_t_id'], $temp['g_id'], $app_type);
            $st_r = $this->getConnectR($temp['g_owner_t_id'], $temp['g_ch_id'], $temp['cp_tour'], $app_type);
            if ($st_t) foreach ($st_t as &$item){
                if (isset($st_r[$item['st_id']])) {
                    $item['save'] = 'else';
                    $item['app_id'] = $st_r[$item['st_id']]['app_id'];
                    $item['cngst_type'] = $st_r[$item['st_id']]['tr_type'];
                }
                if (isset($st_g[$item['st_id']])) {
                    $item['save'] = 'yes';
                    $item['app_id'] = $st_g[$item['st_id']]['app_id'];
                    $item['cngst_type'] = $st_g[$item['st_id']]['cngst_type'];
                    $item['cngst_id'] = $st_g[$item['st_id']]['cngst_id'];
                }
                if (!isset($item['save'])) $item['save'] = 'no';
            }
            if ($st_g) $temp['g_owner_t_g_staff'] = true;
            else $temp['g_owner_t_g_staff'] = false;
            $temp['g_owner_t_staff'] = $st_t;
            //var_dump($st_t);
            // гости ///////
            $temp_t_owner = $this->hdl->selectElem(DB_T_PREFIX."team","t_title_ru","t_id='".$temp['g_guest_t_id']."' LIMIT 1");
            $temp['g_guest_t_title_ru'] = $temp_t_owner[0]['t_title_ru'];
            // члены команды
            $st_t = $this->getConnectStT($temp['g_guest_t_id'], $app_type, $sort);
            $st_g = $this->getConnectGSt($temp['g_guest_t_id'], $temp['g_id'], $app_type);
            $st_r = $this->getConnectR($temp['g_guest_t_id'], $temp['g_ch_id'], $temp['cp_tour'], $app_type);
            if ($st_t) foreach ($st_t as &$item){
                if (isset($st_r[$item['st_id']])) {
                    $item['save'] = 'else';
                    $item['app_id'] = $st_r[$item['st_id']]['app_id'];
                    $item['cngst_type'] = $st_r[$item['st_id']]['tr_type'];
                }
                if (isset($st_g[$item['st_id']])) {
                    $item['save'] = 'yes';
                    $item['app_id'] = $st_g[$item['st_id']]['app_id'];
                    $item['cngst_type'] = $st_g[$item['st_id']]['cngst_type'];
                    $item['cngst_id'] = $st_g[$item['st_id']]['cngst_id'];
                }
                if (!isset($item['save'])) $item['save'] = 'no';
            }
            if ($st_g) $temp['g_guest_t_g_staff'] = true;
            else $temp['g_guest_t_g_staff'] = false;
            $temp['g_guest_t_staff'] = $st_t;

            return $temp;
        } else return false;
    }

    public function getTeamCategoriesList(){
        return $this->hdl->selectElem(DB_T_PREFIX."team_appointment","*","1 ORDER BY app_type ASC, app_order DESC, app_title_ru ASC, app_id ASC");
    }

    public function getTeamCategoriesListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."team_appointment","*","1 ORDER BY app_type ASC, app_title_ru ASC, app_id ASC");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['app_id']] = $item;
            }
            return $list;
        } else return false;
    }

    public function getCompetitionsTeamList($item = 0, $ch=0){
        $item = intval($item);
        $ch = intval($ch);
        $search = array("'", '"');
        $replace = array('', '');
        if ($ch>0) {
            $ch_t = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_id, ch_chc_id","ch_id='".$ch."' LIMIT 1");
            if ($item >0 and $ch_t) {
                $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team, ".DB_T_PREFIX."competitions","t_id, t_title_ru, t_std_id, cp_substage ","cp_id = '$item' AND cp_ch_id = cntch_ch_id AND cntch_t_id = t_id AND cntch_is_delete = 'no' ORDER BY t_title_ru ASC");
                if ($temp){

                    for($i=0; $i<count($temp); $i++){
                        $temp[$i]['t_title_ru'] = str_replace($search, $replace, $temp[$i]['t_title_ru']);
                    }
                    if ($ch_t[0]['ch_chc_id'] != 2){
                        $cp_substage = $temp[0]['cp_substage'];
                        $c_team = count($temp);
                        $temp_cp_st = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_substage "," cp_substage < '$cp_substage' AND cp_is_active = 'yes' ORDER BY cp_substage DESC LIMIT 1");
                        $cp_substage = $temp_cp_st[0]['cp_substage'];
                        $temp_cp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id, cp_title_ru, cp_substage "," cp_substage = '$cp_substage' AND cp_ch_id='$ch' AND cp_is_active = 'yes' ORDER BY cp_order DESC, cp_title_ru ASC");
                        if ($temp_cp){
                            foreach ($temp_cp as $item){
                                $item['cp_title_ru'] = str_replace($search, $replace, $item['cp_title_ru']);
                                for ($i=1; $i<=$c_team/2; $i++){
                                    $t_item['t_id'] = $i."-".$item['cp_id'];
                                    if ($item['cp_title_ru'] !== '') $t_item['t_title_ru'] = $i." место в: ".$item['cp_title_ru'];
                                    else $t_item['t_title_ru'] = $i." место в соревновании без названия";
                                    $t_item['t_std_id'] = 0;
                                    $temp[] = $t_item;
                                }
                            }
                        }
                    }
                    return $temp;
                }
            }
        }
        return false;
    }

    public function updateGame($post){ // редактирование Игры
        $old_game_info = $this->hdl->selectElem(DB_T_PREFIX."games","*","g_id = '".$post['g_id']."' LIMIT 1");
        if ($old_game_info){
            $old_game_info = $old_game_info[0];
            if($post['g_is_active']==true) $is_active ='yes';
            else $is_active = 'no';
            if($post['g_is_schedule_time']==true) $g_is_schedule_time ='yes';
            else $g_is_schedule_time = 'no';
            if($post['g_is_stadium']==true) $g_is_stadium ='yes';
            else $g_is_stadium = 'no';
            if($post['g_selected']==true) $g_selected ='yes';
            else $g_selected = 'no';
            if ($post['g_date_day'] != '' and $post['g_date_day']>0 and $post['g_date_month'] != '' and $post['g_date_month']>0 and $post['g_date_year'] != '' and $post['g_date_year']>0) {
                $post['g_date_minute'] = intval($post['g_date_minute']);
                $post['g_date_hour'] = intval($post['g_date_hour']);
                $post['g_date_day'] = intval($post['g_date_day']);
                $post['g_date_month'] = intval($post['g_date_month']);
                $post['g_date_year'] = intval($post['g_date_year']);
                if ($post['g_date_minute'] < 10) $post['g_date_minute'] = '0'.$post['g_date_minute'];
                if ($post['g_date_hour'] < 10) $post['g_date_hour'] = '0'.$post['g_date_hour'];
                if ($post['g_date_day'] < 10) $post['g_date_day'] = '0'.$post['g_date_day'];
                if ($post['g_date_month'] < 10) $post['g_date_month'] = '0'.$post['g_date_month'];
                $g_date_schedule = $post['g_date_year']."-".$post['g_date_month']."-".$post['g_date_day']." ".$post['g_date_hour'].":".$post['g_date_minute'].":00";
            }
            $search = array("'", '"');
            $replace = array('', '');
            $elems = array(
                "g_date_schedule" => $g_date_schedule,
                "g_description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['g_description_ru']))))>0) ? addslashes($post['g_description_ru']) : NULL,
                "g_description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['g_description_ua']))))>0) ? addslashes($post['g_description_ua']) : NULL,
                "g_description_en" => (strlen(trim(html_entity_decode(strip_tags($post['g_description_en']))))>0) ? addslashes($post['g_description_en']) : NULL,
                "g_text_ru" => (strlen(trim(html_entity_decode(strip_tags($post['g_text_ru']))))>0) ? addslashes($post['g_text_ru']) : NULL,
                "g_text_ua" => (strlen(trim(html_entity_decode(strip_tags($post['g_text_ua']))))>0) ? addslashes($post['g_text_ua']) : NULL,
                "g_text_en" => (strlen(trim(html_entity_decode(strip_tags($post['g_text_en']))))>0) ? addslashes($post['g_text_en']) : NULL,
                "g_is_active" => $is_active,
                "g_datetime_edit" => 'NOW()',
                "g_author" => USER_ID,
                "g_is_schedule_time" => $g_is_schedule_time,
                "g_is_stadium" => $g_is_stadium,
                "g_cn_id" => intval($post['t_cn_id']),
                "g_ct_id" => intval($post['t_ct_id']),
                "g_std_id" => intval($post['t_std_id']),
                "g_round" => intval($post['g_round']),
                "g_date_time_zone" => floatval($post['g_date_time_zone']),
                "g_selected" => $g_selected
            );
            if ($old_game_info['g_is_done'] == 'no'){
                $post['g_cp_id'] = intval($post['g_cp_id']);
                if (!empty($post['g_cp_id'])) $elems['g_cp_id'] = $post['g_cp_id'];
                $post['g_cp_id_g'] = intval($post['g_cp_id_g']);
                $elems['g_cp_id_g'] = (empty($post['g_cp_id_g']))?$post['g_cp_id']:$post['g_cp_id_g'];

                if ($old_game_info['g_owner_t_id']>0) $owner_f_not = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st","cngst_id","cngst_g_id = '".$post['g_id']."' AND cngst_t_id = '".$old_game_info[0]['g_owner_t_id']."' AND cngst_is_delete = 'no' LIMIT 1");
                else $owner_f_not = false;
                if (!$owner_f_not){
                    $g_owner_t_ = explode('-', $post['g_owner_t_id']);
                    for($i=0; $i<count($g_owner_t_); $i++) $g_owner_t_[$i] = intval($g_owner_t_[$i]);
                    if (count($g_owner_t_)>1) {
                        $elems['g_owner_t_comment'] = implode('-', $g_owner_t_);
                        $elems['g_owner_t_id'] = 0;
                    } else {
                        $elems['g_owner_t_comment'] = '';
                        $elems['g_owner_t_id'] = $g_owner_t_[0];
                    }
                }
                if ($old_game_info['g_guest_t_id']>0) $guest_f_not = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st","cngst_id","cngst_g_id = '".$post['g_id']."' AND cngst_t_id = '".$old_game_info[0]['g_guest_t_id']."' AND cngst_is_delete = 'no' LIMIT 1");
                else $guest_f_not = false;
                if (!$guest_f_not){
                    $g_guest_t_ = explode('-', $post['g_guest_t_id']);
                    for($i=0; $i<count($g_guest_t_); $i++) $g_guest_t_[$i] = intval($g_guest_t_[$i]);
                    if (count($g_guest_t_)>1) {
                        $elems['g_guest_t_comment'] = implode('-', $g_guest_t_);
                        $elems['g_guest_t_id'] = 0;
                    } else {
                        $elems['g_guest_t_comment'] = '';
                        $elems['g_guest_t_id'] = $g_guest_t_[0];
                    }
                }
            }
            $condition = array(
                "g_id"=>$post['g_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."games", $elems, $condition)) return true;
        }
        return false;
    }

    public function deleteGame($id = 0){
        $id = intval($id);
        if ($id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","g_id, g_is_done"," g_id = '$id' LIMIT 1");
        if (!$temp or $temp[0]['g_id']<1 or $temp[0]['g_is_done'] == 'yes') return false;

        if ($this->hdl->delElem(DB_T_PREFIX."games", "g_id = '$id' LIMIT 1")) {
            $this->hdl->delElem(DB_T_PREFIX."games_actions", "ga_g_id = '$id'");
            $this->hdl->delElem(DB_T_PREFIX."connection_g_st", "cngst_g_id = '$id'");

            // удаление фото
            $news_media = $this->hdl->selectElem(DB_T_PREFIX."photos","ph_id, ph_path, ph_folder","ph_type = 'game' AND ph_type_id = '$id'");
            if ($news_media)
                foreach ($news_media as $item){
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
            unset($news_media);
            // удаление фото галерей
            $news_media = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id","phg_type = 'game' AND phg_type_id = '$id'");
            if ($news_media)
                foreach ($news_media as $item){
                    $this->hdl->delElem(DB_T_PREFIX."photo_gallery", "phg_id = '".$item['phg_id']."' LIMIT 1");
                }
            unset($news_media);
            // удаление видео
            $news_media = $this->hdl->selectElem(DB_T_PREFIX."videos","v_id, v_folder","v_type = 'game' AND v_type_id = '$id'");
            if ($news_media)
                foreach ($news_media as $item){
                    if ($item['v_folder'] == '') $item['v_folder'] = '/';
                    if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id'].".jpg");
                    if (file_exists ("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg")) unlink("../upload/video_thumbs".$item['v_folder'].$item['v_id']."-small.jpg");
                    $this->hdl->delElem(DB_T_PREFIX."videos", "v_id = '".$item['v_id']."' LIMIT 1");
                }
            unset($news_media);
            // удаление видео галерей
            $news_media = $this->hdl->selectElem(DB_T_PREFIX."video_gallery","vg_id","vg_type = 'game' AND vg_type_id = '$id'");
            if ($news_media)
                foreach ($news_media as $item){
                    $this->hdl->delElem(DB_T_PREFIX."video_gallery", "vg_id = '".$item['vg_id']."' LIMIT 1");
                }
            unset($news_media);

            return true;
        } else return false;
    }

    public function getGamesList($item = 0, $sort = '', $sort_order = 'ASC'){
        $item = intval($item);
        if ($item == 0) return false;
        $ch = intval($_GET['ch']);
        if (strtolower($sort_order) == 'asc') {
            $sort_order = 'ASC';
        } else  {
            $sort_order = 'DESC';
        }
        $q_sort = '';
        if ($sort == 'date') $q_sort = 'ORDER BY g_date_schedule '.$sort_order;
        elseif ($sort == 'team') $q_sort = 'ORDER BY g_owner_t_id '.$sort_order.', g_guest_t_id '.$sort_order;

        $search = array("'", '"');
        $replace = array('', '');
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."games","
											g_id,
											g_ch_id,
											g_cp_id,
											g_owner_t_id,
											g_owner_t_comment,
											g_guest_t_id,
											g_guest_t_comment,
											g_is_active,
											g_date_schedule,
											g_is_done,
											g_owner_points,
											g_guest_points,
											g_is_schedule_time,
											g_round,
											g_text_ru,
											g_text_ua,
											g_text_en,
											g_description_ru,
											g_description_ua,
											g_description_en
											","g_cp_id=$item OR g_cp_id_g=$item $q_sort");
            if ($temp){
                $team_t = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team","t_id, t_title_ru","cntch_ch_id='".$ch."' AND cntch_t_id = t_id ORDER BY t_id ASC ");
                foreach ($team_t as $item) $team_id[$item['t_id']] = $item['t_title_ru'];
                $comp_t = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_id, cp_title_ru","cp_ch_id='".$ch."' ORDER BY cp_id ASC");
                foreach ($comp_t as $item) $comp_id[$item['cp_id']] = $item['cp_title_ru'];
                for($i=0; $i<count($temp); $i++){
                    $g_owner_t_comment = $g_guest_t_comment = array();
                    if ($temp[$i]['g_owner_t_id']>0) {
                        $temp[$i]['g_owner_t_title'] = $team_id[$temp[$i]['g_owner_t_id']];
                    } else {
                        $g_owner_t_comment = explode('-', $temp[$i]['g_owner_t_comment']);
                        $temp[$i]['g_owner_t_title'] = $g_owner_t_comment[0]." место в: ".$comp_id[$g_owner_t_comment[1]];
                    }
                    if ($temp[$i]['g_guest_t_id']>0) {
                        $temp[$i]['g_guest_t_title'] = $team_id[$temp[$i]['g_guest_t_id']];
                    } else {
                        $g_guest_t_comment = explode('-', $temp[$i]['g_guest_t_comment']);
                        $temp[$i]['g_guest_t_title'] = $g_guest_t_comment[0]." место в: ".$comp_id[$g_guest_t_comment[1]];
                    }
                    $temp[$i]['g_description_ru'] = stripcslashes($temp[$i]['g_description_ru']);
                    $temp[$i]['g_description_ua'] = stripcslashes($temp[$i]['g_description_ua']);
                    $temp[$i]['g_description_en'] = stripcslashes($temp[$i]['g_description_en']);
                    $temp[$i]['g_text_ru'] = stripcslashes($temp[$i]['g_text_ru']);
                    $temp[$i]['g_text_ua'] = stripcslashes($temp[$i]['g_text_ua']);
                    $temp[$i]['g_text_en'] = stripcslashes($temp[$i]['g_text_en']);
                }
            }
            return $temp;
        } else return false;
    }

    // СОСТАВ КОМАНДЫ /////////////////////////////////////////////////////////////////////////////////////

    public function clearConnectGSt($t_id = 0, $g_id = 0){ // очистить список для команды на игру
        $t_id = intval($t_id);
        $g_id = intval($g_id);
        if ($t_id<1 or $g_id<1) return false;
        if ($this->hdl->delElem(DB_T_PREFIX."connection_g_st","cngst_g_id = '$g_id' AND cngst_t_id = '$t_id'")) return true;
        else return false;
    }

    public function createConnectGSt($g_id = 0, $t_id = 0, $staff_id = 0, $app_id = 0, $type = ''){
        $g_id = intval($g_id);
        $t_id = intval($t_id);
        $staff_id = intval($staff_id);
        $app_id = intval($app_id);
        if ($type == 'main') $type = 'main';
        elseif ($type == 'reserve') $type = 'reserve';
        else return false;
        if ($g_id<1 or $t_id<1 or $staff_id<1 or $app_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st","cngst_id"," cngst_st_id = '".$staff_id."' AND cngst_t_id = '".$t_id."' AND cngst_app_id = '".$app_id."' AND cngst_g_id = '".$g_id."' AND cngst_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cngst_id']>0) return false;
        $elem = array(
            $g_id,
            $t_id,
            $staff_id,
            $app_id,
            $type,
            'NOW()',
            USER_ID,
            'no'
        );
        if ($this->hdl->addElem(DB_T_PREFIX."connection_g_st", $elem)) return true;
        else return false;
    }

    public function updateConnectGSt($cngst_id = 0, $app_id = 0, $type = ''){
        $cngst_id = intval($cngst_id);
        $app_id = intval($app_id);
        if ($type == 'main') $type = 'main';
        elseif ($type == 'reserve') $type = 'reserve';
        else return false;
        if ($cngst_id<1 or $app_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st","cngst_id"," cngst_id = '".$cngst_id."' AND cngst_is_delete = 'no' LIMIT 1");
        if ($temp[0]['cngst_id']<1) return false;
        $elems = array(
            "cngst_app_id" => $app_id,
            "cngst_date_add" => 'NOW()',
            "cngst_add_author" => USER_ID,
            "cngst_type" => $type
        );
        $condition = array(
            "cngst_id"=>$cngst_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."connection_g_st",$elems, $condition)) return true;
        else return false;
    }

    public function deleteConnectGSt($cngst_id = 0){
        $cngst_id = intval($cngst_id);
        if ($cngst_id<1) return false;
        if ($this->hdl->delElem(DB_T_PREFIX."connection_g_st","cngst_id = '$cngst_id' LIMIT 1")) return true;
        else return false;
    }

    public function saveConnectGSt(){ // сохранить состав на игру одной команды
        $f = true;
        for ($i=1; $i<=intval($_POST['staff_count']); $i++){
            $st_id = intval($_POST['staff_'.$i]);
            if ($_POST['main_'.$i.'_'.$st_id] == true or $_POST['reserve_'.$i.'_'.$st_id] == true){
                if ($_POST['main_'.$i.'_'.$st_id] == true) $type = 'main';
                if ($_POST['reserve_'.$i.'_'.$st_id] == true) $type = 'reserve';

                if ($_POST['cngst_id_'.$i]>0){
                    if (!$this->updateConnectGSt($_POST['cngst_id_'.$i], $_POST['appointment_id_'.$i], $type)) $f = false;
                } else {
                    if (!$this->createConnectGSt($_POST['g_id'], $_POST['t_id'], $_POST['staff_'.$i], $_POST['appointment_id_'.$i], $type)) $f = false;
                }
            } else {
                if ($_POST['cngst_id_'.$i]>0){
                    if (!$this->deleteConnectGSt($_POST['cngst_id_'.$i])) $f = false;
                }
            }
        }
        return $f;
    }

    public function getConnectStT($t_id, $app_type = '', $sort = ''){ // члены команды вообще
        $t_id = intval($t_id);
        // СОРТИРОВКИ ////////////////////////////////////////////////////////////////////
        if ($sort == 'fio') $sort = 'ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, cnstt_id ASC';
        else $sort = 'ORDER BY app_order DESC, st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC, cnstt_id ASC';
        return $this->hdl->selectElem(DB_T_PREFIX."connection_st_t, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment",
            "st_family_ru, st_name_ru, st_surname_ru, st_id, app_title_ru, app_type, app_id",
            "cnstt_t_id='".$t_id."'
					AND cnstt_st_id = st_id
					AND cnstt_app_id = app_id
					AND cnstt_is_delete = 'no'
				AND (cnstt_date_quit IS NULL OR cnstt_date_quit = '0000-00-00 00:00:00')
					AND app_type = '$app_type'
					GROUP BY st_id
					$sort ");
    }

    public function getConnectGSt($t_id, $g_id, $app_type = ''){ // члены команды основной состав
        $t_id = intval($t_id);
        $g_id = intval($g_id);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."connection_g_st, ".DB_T_PREFIX."staff, ".DB_T_PREFIX."team_appointment","st_family_ru, st_name_ru, st_surname_ru, st_id, app_title_ru, app_type, app_id, cngst_id, cngst_type","cngst_t_id='".$t_id."' AND cngst_g_id='".$g_id."' AND cngst_st_id = st_id AND cngst_app_id = app_id AND cngst_is_delete = 'no' AND app_type = '$app_type' ORDER BY st_family_ru ASC, st_name_ru ASC, st_surname_ru ASC ");
        if ($temp) {
            foreach ($temp as $item)
                $res[$item['st_id']] = $item;
            return $res;
        }
        return false;
    }

    public function getConnectR($t_id=0, $ch_id=0, $tour=0, $app_type = ''){ // члены команды в заявке для этого тура
        $t_id = intval($t_id);
        $ch_id = intval($ch_id);
        $tour = intval($tour);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."team_request","tr_id, tr_st_id AS st_id, tr_app_id AS app_id, tr_type","tr_ch_id='".$ch_id."' AND tr_tour='".$tour."' AND tr_t_id = '".$t_id."' ORDER BY tr_id ASC ");
        if ($temp) {
            foreach ($temp as $item) $request[$item['st_id']] = $item;
            return $request;
        }
        return false;
    }

    // ТУРЫ /////////////////////////////////////////////////////////////////////////////////////

    public function createTour($ch_id = 0){ // добавление тура
        $ch_id = intval($ch_id);
        if ($ch_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_id, ch_tours ","ch_id ='$ch_id' LIMIT 1 ");
        if ($temp) {
            $ch_tours = $temp[0]['ch_tours']+1;
            $elems = array(
                "ch_tours" => $ch_tours
            );
            $condition = array(
                "ch_id"=>$ch_id
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
    }

    // ЗАЯВКИ /////////////////////////////////////////////////////////////////////////////////////

    public function getRequestList($ch_id = 0, $tour = 0){ // список заявок
        $ch_id = intval($ch_id);
        $tour = intval($tour);
        if ($ch_id > 0){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."team_request","tr_id, tr_t_id","tr_ch_id='".$ch_id."' AND tr_tour='".$tour."' GROUP BY tr_t_id ORDER BY tr_t_id ASC ");
            if ($temp) {
                foreach ($temp as $item) $res[$item['tr_t_id']] = $item['tr_id'];
                return $res;
            }
        }
        return false;
    }

    public function getRequestItem($ch_id = 0, $tour = 0, $t_id = 0){ // одна заявка
        $t_id = intval($t_id);
        $ch_id = intval($ch_id);
        $tour = intval($tour);
        if ($ch_id > 0 AND $t_id>0){
            $team = $this->hdl->selectElem(DB_T_PREFIX."team","t_id, t_title_ru","t_id='".$t_id."' LIMIT 1 ");
            if ($team){
                $res['team'] = $team[0];
                $temp = $this->hdl->selectElem(DB_T_PREFIX."team_request","tr_id, tr_st_id, tr_app_id AS app_id, tr_type","tr_ch_id='".$ch_id."' AND tr_tour='".$tour."' AND tr_t_id = '".$t_id."' ORDER BY tr_id ASC ");
                if ($temp) {
                    foreach ($temp as $item) $request[$item['tr_st_id']] = $item;
                }
                $sort = !empty($_GET['sort']) ? $_GET['sort'] : '';
                $st_team = $this->getConnectStT($t_id, 'player', $sort);
                if ($st_team)
                    foreach ($st_team as &$item){
                        if (isset($request[$item['st_id']])) {
                            $item['save'] = 'yes';
                            $item['app_id'] = $request[$item['st_id']]['app_id'];
                            $item['cngst_type'] = $request[$item['st_id']]['tr_type'];
                            $item['tr_id'] = $request[$item['st_id']]['tr_id'];
                        } else $item['save'] = 'no';

                    }
                $res['team_staff'] = $st_team;
                return $res;
            }
        }
        return false;
    }

    public function clearRequest($ch_id = 0, $tour = 0, $t_id = 0){ // очистить заявку
        $t_id = intval($t_id);
        $ch_id = intval($ch_id);
        $tour = intval($tour);
        if ($t_id<1 or $ch_id<1) return false;
        if ($this->hdl->delElem(DB_T_PREFIX."team_request","tr_ch_id='".$ch_id."' AND tr_tour='".$tour."' AND tr_t_id = '".$t_id."'")) return true;
        else return false;
    }

    public function saveRequest(){ // сохранить заявку
        $f = true;
        for ($i=1; $i<=intval($_POST['staff_count']); $i++){
            $st_id = intval($_POST['staff_'.$i]);
            if ($_POST['main_'.$i.'_'.$st_id] == true or $_POST['reserve_'.$i.'_'.$st_id] == true){
                if ($_POST['main_'.$i.'_'.$st_id] == true) $type = 'main';
                if ($_POST['reserve_'.$i.'_'.$st_id] == true) $type = 'reserve';

                if ($_POST['tr_id_'.$i]>0){
                    if (!$this->updateRequestItem($_POST['tr_id_'.$i], $_POST['appointment_id_'.$i], $type)) $f = false;
                } else {
                    if (!$this->createRequestItem($_POST['ch_id'], $_POST['tour'], $_POST['t_id'], $_POST['staff_'.$i], $_POST['appointment_id_'.$i], $type)) $f = false;
                }
            } else {
                if ($_POST['tr_id_'.$i]>0){
                    if (!$this->deleteRequestItem($_POST['tr_id_'.$i])) $f = false;
                }
            }
        }
        return $f;
    }

    public function createRequestItem($ch_id = 0, $tour = 0, $t_id = 0, $staff_id = 0, $app_id = 0, $type = ''){
        $ch_id = intval($ch_id);
        $tour = intval($tour);
        $t_id = intval($t_id);
        $staff_id = intval($staff_id);
        $app_id = intval($app_id);
        if ($ch_id<1 or $t_id<0 or $staff_id<0 or $app_id<1) return false;
        if ($type == 'main') $type = 'main';
        elseif ($type == 'reserve') $type = 'reserve';
        else return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."team_request","tr_id","
					tr_ch_id = '".$ch_id."' AND 
					tr_tour = '".$tour."' AND 
					tr_t_id = '".$t_id."' AND 
					tr_st_id = '".$staff_id."' AND 
					tr_app_id = '".$app_id."' 
					LIMIT 1");
        if ($temp[0]['tr_id']>0) return false;
        $elem = array(
            $ch_id,
            $tour,
            $t_id,
            $staff_id,
            $app_id,
            $type,
            'NOW()',
            USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX."team_request", $elem)) return true;
        else return false;
    }

    public function updateRequestItem($tr_id = 0, $app_id = 0, $type = ''){
        $tr_id = intval($tr_id);
        $app_id = intval($app_id);
        if ($type == 'main') $type = 'main';
        elseif ($type == 'reserve') $type = 'reserve';
        else return false;
        if ($tr_id<1 or $app_id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."team_request","tr_id"," tr_id = '".$tr_id."' LIMIT 1");
        if ($temp[0]['tr_id']<1) return false;
        $elems = array(
            "tr_app_id" => $app_id,
            "tr_date_add" => 'NOW()',
            "tr_add_author" => USER_ID,
            "tr_type" => $type
        );
        $condition = array(
            "tr_id"=>$tr_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."team_request",$elems, $condition)) return true;
        else return false;
    }

    public function deleteRequestItem($tr_id = 0){
        $tr_id = intval($tr_id);
        if ($tr_id<1) return false;
        if ($this->hdl->delElem(DB_T_PREFIX."team_request","tr_id = '$tr_id' LIMIT 1")) return true;
        else return false;
    }

    // СПИСКИ ГОРОДОВ, СТРАН, СТАДИОНОВ

    public function getCountryList(){
        return $this->hdl->selectElem(DB_T_PREFIX."country","cn_id, cn_title_ru","1 ORDER BY cn_order DESC, cn_title_ru ASC");
    }

    public function getCityList(){
        return $this->hdl->selectElem(DB_T_PREFIX."city","ct_id, ct_cn_id, ct_title_ru","1 ORDER BY ct_order DESC, ct_title_ru ASC");
    }

    public function getStadiumList(){
        return $this->hdl->selectElem(DB_T_PREFIX."stadium","std_id, std_cn_id, std_ct_id, std_title_ru","1 ORDER BY std_order DESC, std_title_ru ASC");
    }

    public function saveTourTeamCountPoints ($post = false) {
        if (empty($post) || $post['ch_id'] < 1) {
            return false;
        }
        $post['ch_id'] = intval($post['ch_id']);
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$post['ch_id']);
        if ($temp) {
            $temp = $temp[0];
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
            $temp['ch_settings']['tour_team_is_points'][$post['tour']] = $post['p_t_id'];
            $elems = array(
                "ch_settings" => $this->_fix_serialized_string(serialize($temp['ch_settings']))
            );
            $condition = array(
                "ch_id"=>$post['ch_id']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."championship",$elems, $condition)) return true;
        }
        return false;
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

