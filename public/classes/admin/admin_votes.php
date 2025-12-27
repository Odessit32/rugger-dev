<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class votes{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // Голосования ///////////////////////////////////////////////////////////////////////////////////////

    public function getVotesItem($item = 0){
        $item = intval($item);
        if ($item < 1) return false;
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $temp = $this->hdl->selectElem(DB_T_PREFIX."vote","*","vt_id=$item");
        if ($temp) {
            $t = '';
            $temp = $temp[0];
            $answer_list = $this->hdl->selectElem(DB_T_PREFIX."vote_answer",
                "   vta_id,
                        vta_answer_ru,
                        vta_answer_ua,
                        vta_answer_en,
                        vta_a_count",
                "   vta_vt_id=$item");
            if (!empty($answer_list)) {
                foreach ($answer_list as &$item) {
                    $item['vta_answer_ru'] = str_replace($search, $replace, stripcslashes($item['vta_answer_ru']));
                    $item['vta_answer_ua'] = str_replace($search, $replace, stripcslashes($item['vta_answer_ua']));
                    $item['vta_answer_en'] = str_replace($search, $replace, stripcslashes($item['vta_answer_en']));
                }
            }
            $temp['answer_list'] = $answer_list;
//				foreach ($temp as &$item) $item = str_replace($search, $replace, stripcslashes($item));
//				if ($temp['vt_a_count']>0){
//					$temp['img'] = "https://chart.googleapis.com/chart?cht=p3&chd=t:";
//					for($i=1; $i<11; $i++){
//						$temp['percent_'.$i] = round(($temp['vt_a_count_'.$i]/$temp['vt_a_count'])*100);
//						if ($temp['percent_'.$i]>0) {
//							$temp['img'] .= $temp['percent_'.$i];
//							if ($i<10 and $temp['vt_answer_'.($i+1)] != '') $temp['img'] .= ",";
//						}
//						if ($temp['vt_answer_'.$i] != '') {
//							$t .= $i;
//							if ($i<10) $t .= "|";
//						}
//					}
//					$temp['img'] .= "&chs=500x200&chl=".$t."&chco=003f7c";
//				}
        }
        return $temp;
    }

    public function deleteVotes($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."vote", "vt_id='$id'")) {
                $this->hdl->delElem(DB_T_PREFIX."voters", "vts_vt_id='$id'");
                $this->hdl->delElem(DB_T_PREFIX."vote_answer", "vta_vt_id='$id'");
                return true;
            }
        }
        return false;
    }

    public function updateVotes($post){ // редактирование новости
        if($post['vt_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['vt_always']==true) $vt_always ='yes';
        else $vt_always = 'no';
        if ($post['vt_date_from_day'] != '' and $post['vt_date_from_day']>0 and $post['vt_date_from_month'] != '' and $post['vt_date_from_month']>0 and $post['vt_date_from_year'] != '' and $post['vt_date_from_year']>0) {
            $post['vt_date_from_day'] = intval($post['vt_date_from_day']);
            $post['vt_date_from_month'] = intval($post['vt_date_from_month']);
            $post['vt_date_from_year'] = intval($post['vt_date_from_year']);
            $post['vt_date_from_hour'] = intval($post['vt_date_from_hour']);
            $post['vt_date_from_minute'] = intval($post['vt_date_from_minute']);
            if ($post['vt_date_from_day'] < 10) $post['vt_date_from_day'] = '0'.$post['vt_date_from_day'];
            if ($post['vt_date_from_month'] < 10) $post['vt_date_from_month'] = '0'.$post['vt_date_from_month'];
            if ($post['vt_date_from_hour'] < 10) $post['vt_date_from_hour'] = '0'.$post['vt_date_from_hour'];
            if ($post['vt_date_from_minute'] < 10) $post['vt_date_from_minute'] = '0'.$post['vt_date_from_minute'];
            $vt_date_from = $post['vt_date_from_year']."-".$post['vt_date_from_month']."-".$post['vt_date_from_day']." ".$post['vt_date_from_hour'].":".$post['vt_date_from_minute'].":00";
        }
        if ($post['vt_date_to_day'] != '' and $post['vt_date_to_day']>0 and $post['vt_date_to_month'] != '' and $post['vt_date_to_month']>0 and $post['vt_date_to_year'] != '' and $post['vt_date_to_year']>0) {
            $post['vt_date_to_day'] = intval($post['vt_date_to_day']);
            $post['vt_date_to_month'] = intval($post['vt_date_to_month']);
            $post['vt_date_to_year'] = intval($post['vt_date_to_year']);
            $post['vt_date_to_hour'] = intval($post['vt_date_to_hour']);
            $post['vt_date_to_minute'] = intval($post['vt_date_to_minute']);
            if ($post['vt_date_to_day'] < 10) $post['vt_date_to_day'] = '0'.$post['vt_date_to_day'];
            if ($post['vt_date_to_month'] < 10) $post['vt_date_to_month'] = '0'.$post['vt_date_to_month'];
            if ($post['vt_date_to_hour'] < 10) $post['vt_date_to_hour'] = '0'.$post['vt_date_to_hour'];
            if ($post['vt_date_to_minute'] < 10) $post['vt_date_to_minute'] = '0'.$post['vt_date_to_minute'];
            $vt_date_to = $post['vt_date_to_year']."-".$post['vt_date_to_month']."-".$post['vt_date_to_day']." ".$post['vt_date_to_hour'].":".$post['vt_date_to_minute'].":00";
        }
        if ($post['vt_voters_type'] == 'day_ip') $vt_voters_type = 'day_ip';
        elseif ($post['vt_voters_type'] == 'ip') $vt_voters_type = 'ip';
        else $vt_voters_type = 'no_ban';
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $vt_a_count = 0;
        for ($i=1; $i<=27; $i++){
            $vt_a_count += intval($post['vt_a_count_'.$i]);
        }
        $elems = array(
            "vt_question_ru" => (strlen(trim(html_entity_decode(strip_tags($post['vt_question_ru']))))>0) ? str_replace($search, $replace, $post['vt_question_ru']) : '',
            "vt_question_ua" => (strlen(trim(html_entity_decode(strip_tags($post['vt_question_ua']))))>0) ? str_replace($search, $replace, $post['vt_question_ua']) : '',
            "vt_question_en" => (strlen(trim(html_entity_decode(strip_tags($post['vt_question_en']))))>0) ? str_replace($search, $replace, $post['vt_question_en']) : '',
            "vt_is_active" => $is_active,
            "vt_datetime_edit" => 'NOW()',
            "vt_author" => USER_ID,
            "vt_date_from" => $vt_date_from,
            "vt_date_to" => $vt_date_to,
            "vt_always" => $vt_always,
            "vt_voters_type" => $vt_voters_type
        );
        $condition = array(
            "vt_id"=>$post['vt_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."vote",$elems, $condition)) {
            if (!empty($post['vta_answer_ru'])){
                foreach($post['vta_answer_ru'] as $key=>$item){
                    if (!empty($post['vta_id'][$key]) && (
                            !empty($post['vta_answer_ru'][$key]) ||
                            !empty($post['vta_answer_ua'][$key]) ||
                            !empty($post['vta_answer_en'][$key]))) {
                        $elems = array(
                            "vta_answer_ru" => (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_ru'][$key]))))>0) ? str_replace($search, $replace, $post['vta_answer_ru'][$key]) : '',
                            "vta_answer_ua" => (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_ua'][$key]))))>0) ? str_replace($search, $replace, $post['vta_answer_ua'][$key]) : '',
                            "vta_answer_en" => (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_en'][$key]))))>0) ? str_replace($search, $replace, $post['vta_answer_en'][$key]) : '',
                            "vta_datetime_edit" => 'NOW()',
                            "vta_author" => USER_ID
                        );
                        $condition = array(
                            "vta_id"=>$post['vta_id'][$key]
                        );
                        $this->hdl->updateElem(DB_T_PREFIX."vote_answer",$elems, $condition);
                    } elseif (empty($post['vta_id'][$key])) {
                        $elem = array(
                            $post['vt_id'],
                            (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_ru'][$key]))))>0) ? str_replace($search, $replace, $post['vta_answer_ru'][$key]) : '',
                            (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_ua'][$key]))))>0) ? str_replace($search, $replace, $post['vta_answer_ua'][$key]) : '',
                            (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_en'][$key]))))>0) ? str_replace($search, $replace, $post['vta_answer_en'][$key]) : '',
                            0,
                            'NOW()',
                            'NOW()',
                            USER_ID
                        );
                        $this->hdl->addElem(DB_T_PREFIX."vote_answer", $elem);
                    } elseif (!empty($post['vta_id'][$key]) &&
                        empty($post['vta_answer_ru'][$key]) &&
                        empty($post['vta_answer_ua'][$key]) &&
                        empty($post['vta_answer_en'][$key])) {
                        $this->hdl->delElem(DB_T_PREFIX."vote_answer", "vta_id='".$post['vta_id'][$key]."'");
                    }

                }
            }
            return true;
        }
        return false;
    }

    public function createVotes($post){ // добавление голосования
        if($post['vt_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['vt_always']==true) $vt_always ='yes';
        else $vt_always = 'no';
        if ($post['vt_date_from_day'] != '' and $post['vt_date_from_day']>0 and $post['vt_date_from_month'] != '' and $post['vt_date_from_month']>0 and $post['vt_date_from_year'] != '' and $post['vt_date_from_year']>0) {
            $post['vt_date_from_day'] = intval($post['vt_date_from_day']);
            $post['vt_date_from_month'] = intval($post['vt_date_from_month']);
            $post['vt_date_from_year'] = intval($post['vt_date_from_year']);
            $post['vt_date_from_hour'] = intval($post['vt_date_from_hour']);
            $post['vt_date_from_minute'] = intval($post['vt_date_from_minute']);
            if ($post['vt_date_from_day'] < 10) $post['vt_date_from_day'] = '0'.$post['vt_date_from_day'];
            if ($post['vt_date_from_month'] < 10) $post['vt_date_from_month'] = '0'.$post['vt_date_from_month'];
            if ($post['vt_date_from_hour'] < 10) $post['vt_date_from_hour'] = '0'.$post['vt_date_from_hour'];
            if ($post['vt_date_from_minute'] < 10) $post['vt_date_from_minute'] = '0'.$post['vt_date_from_minute'];
            $vt_date_from = $post['vt_date_from_year']."-".$post['vt_date_from_month']."-".$post['vt_date_from_day']." ".$post['vt_date_from_hour'].":".$post['vt_date_from_minute'].":00";
        }
        if ($post['vt_date_to_day'] != '' and $post['vt_date_to_day']>0 and $post['vt_date_to_month'] != '' and $post['vt_date_to_month']>0 and $post['vt_date_to_year'] != '' and $post['vt_date_to_year']>0) {
            $post['vt_date_to_day'] = intval($post['vt_date_to_day']);
            $post['vt_date_to_month'] = intval($post['vt_date_to_month']);
            $post['vt_date_to_year'] = intval($post['vt_date_to_year']);
            $post['vt_date_to_hour'] = intval($post['vt_date_to_hour']);
            $post['vt_date_to_minute'] = intval($post['vt_date_to_minute']);
            if ($post['vt_date_to_day'] < 10) $post['vt_date_to_day'] = '0'.$post['vt_date_to_day'];
            if ($post['vt_date_to_month'] < 10) $post['vt_date_to_month'] = '0'.$post['vt_date_to_month'];
            if ($post['vt_date_to_hour'] < 10) $post['vt_date_to_hour'] = '0'.$post['vt_date_to_hour'];
            if ($post['vt_date_to_minute'] < 10) $post['vt_date_to_minute'] = '0'.$post['vt_date_to_minute'];
            $vt_date_to = $post['vt_date_to_year']."-".$post['vt_date_to_month']."-".$post['vt_date_to_day']." ".$post['vt_date_to_hour'].":".$post['vt_date_to_minute'].":00";
        }
        if ($post['vt_voters_type'] == 'day_ip') $vt_voters_type = 'day_ip';
        elseif ($post['vt_voters_type'] == 'ip') $vt_voters_type = 'ip';
        else $vt_voters_type = 'no_ban';
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $elem = array(
            (strlen(trim(html_entity_decode(strip_tags($post['vt_question']))))>0) ? str_replace($search, $replace, $post['vt_question']) : '',
            0,
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $vt_date_from,
            $vt_date_to,
            $vt_always,
            $vt_voters_type
        );

        if ($vt_id = $this->hdl->addElem(DB_T_PREFIX."vote", $elem)) {
            $post['count_answer'] = intval($post['count_answer']);
            if (!empty($post['count_answer']) && $vt_id>0){
                for ($i=1; $i<=$post['count_answer']; $i++){
                    $elem = array(
                        $vt_id,
                        (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_ru'][$i]))))>0) ? str_replace($search, $replace, $post['vta_answer_ru'][$i]) : '',
                        (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_ua'][$i]))))>0) ? str_replace($search, $replace, $post['vta_answer_ua'][$i]) : '',
                        (strlen(trim(html_entity_decode(strip_tags($post['vta_answer_en'][$i]))))>0) ? str_replace($search, $replace, $post['vta_answer_en'][$i]) : '',
                        0,
                        'NOW()',
                        'NOW()',
                        USER_ID
                    );
                    $this->hdl->addElem(DB_T_PREFIX."vote_answer", $elem);
                }
            }
            return true;
        }
        else return false;
    }

    public function getVotesList($page=1, $perpage=10){
        $extra_q = '1';
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page = $perpage*$page;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."vote","
				vt_id as id,
				vt_question_ru as vt_question,
				vt_is_active,
				vt_a_count,
				vt_always,
				vt_date_from,
				vt_date_to
				","1 ORDER BY vt_id DESC LIMIT $page, $perpage");
        if ($temp) {
            foreach ($temp as &$item){
                $item['vt_question'] = stripslashes($item['vt_question']);
            }
        }
        return $temp;
    }

    public function getVotesPages($page=1, $perpage=10){
        $extra_q = '';
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."vote","COUNT(*) as C_N","1");
        $c_pages = intval($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i <= $c_pages) $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
            }
        }
        return $pages;
    }

    // Настройки ///////////////////////////////////////////////////////////////////////////////////////

    public function getVotesSettings(){
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
