<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class adminBase {
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    protected function _updateConnectionChampionship($post = array()){
        if (!empty($post['championship']) && !empty($post['type']) && !empty($post['id'])){
            $countries_ar = explode(',', $post['championship']);
            $this->hdl->delElem(DB_T_PREFIX."connection_chgroup", "type='".$post['type']."' AND type_id='".$post['id']."' AND chg_id NOT IN (".$post['championship'].")");
            $championship_added = $this->hdl->selectElem(DB_T_PREFIX."connection_chgroup", "chg_id", "type='".$post['type']."' AND type_id='".$post['id']."' AND chg_id IN (".$post['championship'].")");
            if (!empty($championship_added)){
                foreach($championship_added as $item){
                    $championship_added_ar[$item['chg_id']] = true;
                }
            }
            foreach($countries_ar as $item){
                if (empty($championship_added_ar[$item])){
                    $elem = array(
                        $item,
                        $post['type'],
                        $post['id'],
                        'NOW()'
                    );
                    $this->hdl->addElem(DB_T_PREFIX."connection_chgroup", $elem);
                }
            }
        }
    }

    protected function _deleteConnectionChampionship($post = array()){
        if (!empty($post['id']) && !empty($post['type'])){
            $this->hdl->delElem(DB_T_PREFIX."connection_chgroup", "type='".$post['type']."' AND type_id='".$post['id']."'");
        }
    }

    public function getConnectionChampionship($item = 0, $is_gallery = false, $type = ''){
        $item = intval($item);
        if (empty($type)) {
            return false;
        }
        $type = ($is_gallery) ? $type.'_gallery' : $type;
        return $this->hdl->selectElem(DB_T_PREFIX."connection_chgroup cc,
                ".DB_T_PREFIX."championship_group c",
            "   c.chg_id as id,
                c.chg_title_ru as title",
            "   cc.type_id=$item and
                cc.type='$type' and
                cc.chg_id = c.chg_id");
    }

    public function getChampionshipList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group","
				chg_id as id,
				chg_title_ru as title
				","1 ORDER BY chg_title_ru ASC");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    protected function _updateConnectionChamp($post = array()){
        if (!empty($post['champ']) && !empty($post['type']) && !empty($post['id'])){
            $countries_ar = explode(',', $post['champ']);
            $this->hdl->delElem(DB_T_PREFIX."connection_champ", "type='".$post['type']."' AND type_id='".$post['id']."' AND ch_id NOT IN (".$post['champ'].")");
            $champ_added = $this->hdl->selectElem(DB_T_PREFIX."connection_champ", "ch_id", "type='".$post['type']."' AND type_id='".$post['id']."' AND ch_id IN (".$post['champ'].")");
            if (!empty($champ_added)){
                foreach($champ_added as $item){
                    $champ_added_ar[$item['ch_id']] = true;
                }
            }
            foreach($countries_ar as $item){
                if (empty($champ_added_ar[$item])){
                    $elem = array(
                        $item,
                        $post['type'],
                        $post['id'],
                        'NOW()'
                    );
                    $this->hdl->addElem(DB_T_PREFIX."connection_champ", $elem);
                }
            }
        }
    }

    protected function _deleteConnectionChamp($post = array()){
        if (!empty($post['id']) && !empty($post['type'])){
            $this->hdl->delElem(DB_T_PREFIX."connection_champ", "type='".$post['type']."' AND type_id='".$post['id']."'");
        }
    }

    public function getConnectionChamp($item = 0, $is_gallery = false, $type = ''){
        $item = intval($item);
        if (empty($type)) {
            return false;
        }
        $type = ($is_gallery) ? $type.'_gallery' : $type;
        return $this->hdl->selectElem(DB_T_PREFIX."connection_champ cc,
                ".DB_T_PREFIX."champ c",
            "   c.id,
                c.title_ru as title",
            "   cc.type_id=$item and
                cc.type='$type' and
                cc.ch_id = c.id");
    }

    public function getChampList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."champ","
				id,
				title_ru as title
				","1 ORDER BY title_ru ASC");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    protected function _updateConnectionCountry($post = array()){
        if (!empty($post['country']) && !empty($post['type']) && !empty($post['id'])){
            $countries_ar = explode(',', $post['country']);
            $this->hdl->delElem(DB_T_PREFIX."connection_country", "type='".$post['type']."' AND type_id='".$post['id']."' AND cn_id NOT IN (".$post['country'].")");
            $country_added = $this->hdl->selectElem(DB_T_PREFIX."connection_country", "cn_id", "type='".$post['type']."' AND type_id='".$post['id']."' AND cn_id IN (".$post['country'].")");
            if (!empty($country_added)){
                foreach($country_added as $item){
                    $country_added_ar[$item['cn_id']] = true;
                }
            }
            foreach($countries_ar as $item){
                if (empty($country_added_ar[$item])){
                    $elem = array(
                        $item,
                        $post['type'],
                        $post['id'],
                        'NOW()'
                    );
                    $this->hdl->addElem(DB_T_PREFIX."connection_country", $elem);
                }
            }
        }
    }

    protected function _deleteConnectionCountry($post = array()){
        if (!empty($post['id']) && !empty($post['type'])){
            $this->hdl->delElem(DB_T_PREFIX."connection_country", "type='".$post['type']."' AND type_id='".$post['id']."'");
        }
    }

    public function getConnectionCountry($item = 0, $is_gallery = false, $type = ''){
        $item = intval($item);
        if (empty($type)) {
            return false;
        }
        $type = ($is_gallery) ? $type.'_gallery' : $type;
        return $this->hdl->selectElem(DB_T_PREFIX."connection_country cc,
                ".DB_T_PREFIX."country c",
            "   c.cn_id as id,
                c.cn_title_ru as title ",
            "   cc.type_id=$item and
                cc.type='$type' and
                cc.cn_id = c.cn_id");
    }

    public function getCountryList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."country","
				cn_id as id,
				cn_title_ru as title
				","1 ORDER BY cn_title_ru ASC");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    // Games

    protected function _updateConnectionGame($post = array()){
        if (!empty($post['game']) && !empty($post['type']) && !empty($post['id'])){
            $countries_ar = explode(',', $post['game']);
            $this->hdl->delElem(DB_T_PREFIX."connection_game", "type='".$post['type']."' AND type_id='".$post['id']."' AND g_id NOT IN (".$post['game'].")");
            $game_added = $this->hdl->selectElem(DB_T_PREFIX."connection_game", "g_id", "type='".$post['type']."' AND type_id='".$post['id']."' AND g_id IN (".$post['game'].")");
            if (!empty($game_added)){
                foreach($game_added as $item){
                    $game_added_ar[$item['g_id']] = true;
                }
            }
            foreach($countries_ar as $item){
                if (empty($game_added_ar[$item])){
                    $elem = array(
                        $item,
                        $post['type'],
                        $post['id'],
                        'NOW()'
                    );
                    $this->hdl->addElem(DB_T_PREFIX."connection_game", $elem);
                }
            }
        }
    }

    protected function _deleteConnectionGame($post = array()){
        if (!empty($post['id']) && !empty($post['type'])){
            $this->hdl->delElem(DB_T_PREFIX."connection_game", "type='".$post['type']."' AND type_id='".$post['id']."'");
        }
    }

    public function getConnectionGame($item = 0, $is_gallery = false, $type = ''){
        $item = intval($item);
        if (empty($type)) {
            return false;
        }
        $type = ($is_gallery) ? $type.'_gallery' : $type;
        $games_list = $this->hdl->selectElem(DB_T_PREFIX."connection_game cg
                LEFT JOIN ".DB_T_PREFIX."games g ON cg.g_id = g.g_id
                LEFT JOIN ".DB_T_PREFIX."championship ch ON ch.ch_id = g.g_ch_id
                LEFT JOIN ".DB_T_PREFIX."competitions cp ON cp.cp_id = g.g_cp_id
                LEFT JOIN ".DB_T_PREFIX."championship_group chg ON chg.chg_id = ch.ch_chg_id
            ",
            "   g.g_id as id,
                g.g_ch_id,
                ch.ch_title_ru as ch_title,
                g.g_cp_id,
                cp.cp_title_ru as cp_title,
                chg.chg_title_ru as chg_title,
                g.g_owner_t_id,
                g.g_guest_t_id,
                g.g_owner_points,
                g.g_guest_points,
                g.g_is_done",
            "   cg.type_id=$item and
                cg.type='$type' and
                cg.g_id = g.g_id");
        if ($games_list){
            foreach ($games_list as &$gl_item){
                $team_q_a[] = $gl_item['g_owner_t_id'];
                $team_q_a[] = $gl_item['g_guest_t_id'];
            }
            $team_list = $this->hdl->selectElem(DB_T_PREFIX."team",
                "   t_id as id,
                    t_title_ru as title
                "," t_id IN (".implode(',',$team_q_a).") AND
                    t_is_delete = 'no'");
            if ($team_list) {
                foreach ($team_list as $team_list_item) {
                    $teams[$team_list_item['id']] = $team_list_item['title'];
                }
            }
            foreach ($games_list as &$gl_item){
                $gl_item['title'] = stripslashes($gl_item['chg_title']).' '.
                    stripslashes($gl_item['ch_title']).' '.
                    stripslashes($gl_item['cp_title']).': '.
                    stripslashes($teams[$gl_item['g_owner_t_id']]).' ';
                if ($gl_item['g_is_done']){
                    $gl_item['title'] .= '('.$gl_item['g_owner_points'].':'.$gl_item['g_guest_points'].')';
                }
                $gl_item['title'] .= stripslashes($teams[$gl_item['g_guest_t_id']]).' ';
            }
        }
        return $games_list;
    }

    public function getGameList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."game","
				g_id as id
				","1 ORDER BY g_datetime ASC");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    // Staff

    protected function _updateConnectionStaff($post = array()){
        if (!empty($post['staff']) && !empty($post['type']) && !empty($post['id'])){
            $countries_ar = explode(',', $post['staff']);
            $this->hdl->delElem(DB_T_PREFIX."connection_staff", "type='".$post['type']."' AND type_id='".$post['id']."' AND st_id NOT IN (".$post['staff'].")");
            $staff_added = $this->hdl->selectElem(DB_T_PREFIX."connection_staff", "st_id", "type='".$post['type']."' AND type_id='".$post['id']."' AND st_id IN (".$post['staff'].")");
            if (!empty($staff_added)){
                foreach($staff_added as $item){
                    $staff_added_ar[$item['st_id']] = true;
                }
            }
            foreach($countries_ar as $item){
                if (!empty(trim($item))) {
                    if (empty($staff_added_ar[$item])){
                        $elem = array(
                            $item,
                            $post['type'],
                            $post['id'],
                            'NOW()'
                        );
                        $this->hdl->addElem(DB_T_PREFIX."connection_staff", $elem);
                    }
                }
            }
        }
    }

    protected function _deleteConnectionStaff($post = array()){
        if (!empty($post['id']) && !empty($post['type'])){
            $this->hdl->delElem(DB_T_PREFIX."connection_staff", "type='".$post['type']."' AND type_id='".$post['id']."'");
        }
    }

    public function getConnectionStaff($item = 0, $is_gallery = false, $type = ''){
        $item = intval($item);
        if (empty($type)) {
            return false;
        }
        $type = ($is_gallery) ? $type.'_gallery' : $type;
        $staff_list = $this->hdl->selectElem(DB_T_PREFIX."connection_staff cst
                LEFT JOIN ".DB_T_PREFIX."staff st ON cst.st_id = st.st_id
            ",
            "   st.st_id as id,
                st.st_name_".D_S_LANG." as name,
                st.st_family_".D_S_LANG." as family,
                st.st_family_".D_S_LANG." as surname
            ",
            "   cst.type_id=$item and
                cst.type='$type' and
                cst.st_id = st.st_id");
        if ($staff_list){
            foreach ($staff_list as &$st_item){
                $st_item['title'] = stripslashes($st_item['name']).' '.
                    ((!empty($st_item['surname']))? stripslashes($st_item['surname']).' ':'').
                    stripslashes($st_item['family']);
            }
        }
        return $staff_list;
    }

    // NEWS TOP

    protected function _updateConnectionNewsTop($post = array()){
        $post['top'] = intval($post['top']);
        $post['id'] = intval($post['id']);
        if (!empty($post['id']) && !empty($post['type']) && !empty($post['top'])){
            $auto_val_n_q = '';
            $auto_val_q = '';
            $auto_val = array();
            if (!empty($post['auto_val'])){
                $auto_val_n_q = " AND type_id NOT IN (".$post['auto_val'].") ";
                $auto_val_q = " AND type_id IN (".$post['auto_val'].") ";
                $auto_val = explode(',', $post['auto_val']);
            }
            $this->hdl->delElem(DB_T_PREFIX."connection_news_top", "type='".$post['type']."' AND top = '".$post['top']."' AND n_id='".$post['id']."' ".$auto_val_n_q);
            $counnection_added = $this->hdl->selectElem(DB_T_PREFIX."connection_news_top", "type_id", "type='".$post['type']."' AND n_id='".$post['id']."' ".$auto_val_q." GROUP BY type_id");
            if (!empty($counnection_added)){
                foreach($counnection_added as $item_ca){
                    $counnection_added_ar[$item_ca['type_id']] = true;
                }
            }
            foreach($auto_val as $item){
                if (empty($counnection_added_ar[$item])){
                    $this->hdl->delElem(DB_T_PREFIX."connection_news_top", "type='".$post['type']."' AND top = '".$post['top']."' AND type_id='".$item."' ");
                    $elem = array(
                        $post['top'],
                        $post['id'],
                        $post['type'],
                        $item,
                        'NOW()'
                    );
                    $this->hdl->addElem(DB_T_PREFIX."connection_news_top", $elem);
                }
            }
        }
    }

    protected function _deleteConnectionNewsTop($post = array()){
        if (!empty($post['cnt_id'])){
            $post['cnt_id'] = intval($post['cnt_id']);
            $this->hdl->delElem(DB_T_PREFIX."connection_news_top", "id ='".$post['cnt_id']."'");
        }
    }

    public function getConnectionNewsTop($item = 0, $top = 0){
        $item = intval($item);
        $top = intval($top);
        $res = array();
        if ($top>0 && $item>0){
            $res['countries']['auto_val'] = '';
            $res['countries']['list'] = $this->hdl->selectElem(DB_T_PREFIX."connection_news_top nt
                    LEFT JOIN ".DB_T_PREFIX."country c ON nt.type_id = c.cn_id",
                "   nt.top,
                    nt.type_id as id,
                    c.cn_title_ru as title",
                "   nt.n_id='".$item."' AND nt.top = '".$top."' AND nt.type = 'country'");
            if ($res['countries']['list']){
                for ($i=0; $i<count($res['countries']['list']); $i++) {
                    $res['countries']['list'][$i]['title'] = stripslashes($res['countries']['list'][$i]['title']);
                    $res['countries']['auto_val'] .= $res['countries']['list'][$i]['id'].',';
                }
                $res['countries']['auto_val'] = substr($res['countries']['auto_val'], 0, -1);
            }
            $res['champ']['auto_val'] = '';
            $res['champ']['list'] = $this->hdl->selectElem(DB_T_PREFIX."connection_news_top nt
                    LEFT JOIN ".DB_T_PREFIX."champ ch ON nt.type_id = ch.id",
                "   nt.top,
                    nt.type_id as id,
                    ch.title_ru as title",
                "   nt.n_id='".$item."' AND nt.top = '".$top."' AND nt.type = 'champ'");
            if ($res['champ']['list']){
                for ($i=0; $i<count($res['champ']['list']); $i++) {
                    $res['champ']['list'][$i]['title'] = stripslashes($res['champ']['list'][$i]['title']);
                    $res['champ']['auto_val'] .= $res['champ']['list'][$i]['id'].',';
                }
                $res['champ']['auto_val'] = substr($res['champ']['auto_val'], 0, -1);
            }
        }
        return $res;
    }

    public function getTranslit($st, $charset = 'utf-8'){
        if (strtolower($charset) == 'utf-8'){
            $st = iconv("UTF-8", "cp1251", $st);
            // Сначала заменяем "односимвольные" фонемы.
            $st=strtr($st, iconv("UTF-8", "cp1251", "абвгдеёзийклмнопрстуфхыэ"), "abvgdeeziyklmnoprstufhie");
            $st=strtr($st, iconv("UTF-8", "cp1251", "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ"), "ABVGDEEZIYKLMNOPRSTUFHIE");
            // Затем - "многосимвольные и др.".
            $st=strtr($st, array(
                    iconv("UTF-8", "cp1251", "ж")=>"zh",
                    iconv("UTF-8", "cp1251", "ц")=>"ts",
                    iconv("UTF-8", "cp1251", "ч")=>"ch",
                    iconv("UTF-8", "cp1251", "ш")=>"sh",
                    iconv("UTF-8", "cp1251", "щ")=>"shch",
                    iconv("UTF-8", "cp1251", "ь")=>"",
                    iconv("UTF-8", "cp1251", "ю")=>"yu",
                    iconv("UTF-8", "cp1251", "я")=>"ya",
                    iconv("UTF-8", "cp1251", "ъ")=>"",
                    iconv("UTF-8", "cp1251", "Ъ")=>"",
                    " "=>"_",
                    iconv("UTF-8", "cp1251", "Ж")=>"ZH",
                    iconv("UTF-8", "cp1251", "Ц")=>"TS",
                    iconv("UTF-8", "cp1251", "Ч")=>"CH",
                    iconv("UTF-8", "cp1251", "Ш")=>"SH",
                    iconv("UTF-8", "cp1251", "Щ")=>"SHCH",
                    iconv("UTF-8", "cp1251", "Ь")=>"",
                    iconv("UTF-8", "cp1251", "Ю")=>"YU",
                    iconv("UTF-8", "cp1251", "Я")=>"YA",
                    iconv("UTF-8", "cp1251", "ї")=>"i",
                    iconv("UTF-8", "cp1251", "Ї")=>"Yi",
                    iconv("UTF-8", "cp1251", "є")=>"ie",
                    iconv("UTF-8", "cp1251", "Є")=>"Ye",
                    iconv("UTF-8", "cp1251", "!")=>'',
                    iconv("UTF-8", "cp1251", "@")=>'',
                    iconv("UTF-8", "cp1251", "#")=>'',
                    iconv("UTF-8", "cp1251", "№")=>'',
                    iconv("UTF-8", "cp1251", "$")=>'',
                    iconv("UTF-8", "cp1251", "%")=>'',
                    iconv("UTF-8", "cp1251", "^")=>'',
                    iconv("UTF-8", "cp1251", "&")=>'',
                    iconv("UTF-8", "cp1251", "*")=>'',
                    iconv("UTF-8", "cp1251", "(")=>'',
                    iconv("UTF-8", "cp1251", ")")=>'',
                    iconv("UTF-8", "cp1251", "+")=>'',
                    iconv("UTF-8", "cp1251", "=")=>'',
                    iconv("UTF-8", "cp1251", "[")=>'',
                    iconv("UTF-8", "cp1251", "]")=>'',
                    iconv("UTF-8", "cp1251", "{")=>'',
                    iconv("UTF-8", "cp1251", "}")=>'',
                    iconv("UTF-8", "cp1251", "|")=>'',
                    iconv("UTF-8", "cp1251", "?")=>'',
                    iconv("UTF-8", "cp1251", ">")=>'',
                    iconv("UTF-8", "cp1251", "<")=>'',
                    iconv("UTF-8", "cp1251", "`")=>'',
                    iconv("UTF-8", "cp1251", "~")=>''
                )
            );
        } else {
            // Сначала заменяем "односимвольные" фонемы.
            $st=strtr($st, "абвгдеёзийклмнопрстуфхыэ", "abvgdeeziyklmnoprstufhie");
            $st=strtr($st, "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ", "ABVGDEEZIYKLMNOPRSTUFHIE");
            // Затем - "многосимвольные и др.".
            $st=strtr($st, array(
                    "ж"=>"zh",
                    "ц"=>"ts",
                    "ч"=>"ch",
                    "ш"=>"sh",
                    "щ"=>"shch",
                    "ь"=>"",
                    "ю"=>"yu",
                    "я"=>"ya",
                    "ъ"=>"",
                    "Ъ"=>"",
                    " "=>"_",
                    "Ж"=>"ZH",
                    "Ц"=>"TS",
                    "Ч"=>"CH",
                    "Ш"=>"SH",
                    "Щ"=>"SHCH",
                    "Ь"=>"",
                    "Ю"=>"YU",
                    "Я"=>"YA",
                    "ї"=>"i",
                    "Ї"=>"Yi",
                    "є"=>"ie",
                    "Є"=>"Ye",
                    "!"=>'',
                    "@"=>'',
                    "#"=>'',
                    "№"=>'',
                    "$"=>'',
                    "%"=>'',
                    "^"=>'',
                    "&"=>'',
                    "*"=>'',
                    "("=>'',
                    ")"=>'',
                    "+"=>'',
                    "="=>'',
                    "["=>'',
                    "]"=>'',
                    "{"=>'',
                    "}"=>'',
                    "|"=>'',
                    "?"=>'',
                    ">"=>'',
                    "<"=>'',
                    "`"=>'',
                    "~"=>''
                )
            );
        }
        // Возвращаем результат.
        return $st;
    }

    // SETTINGS ///////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get Settings
     *
     * @return array|bool
     */
    public function getSettings(){
        $list = array();
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

    /**
     * Save Settings
     *
     * @param $elems
     * @param $condition
     * @return bool
     */
    public function saveSettings($elems, $condition){
        if ($this->hdl->updateElem(DB_T_PREFIX."settings",$elems, $condition)) return true;
        else return false;
    }

    /**
     * Get Authors list
     *
     * @return array
     */
    public function getAuthorsList(){
        $list = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."admins","
                    a_id as id,
                    a_name as name,
                    a_f_name as f_name,
                    a_o_name as o_name ","1 ORDER BY a_name DESC, a_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['id']] = $item;
            }

        }
        return $list;
    }

}