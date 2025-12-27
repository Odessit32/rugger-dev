<?php
/**
 * Class competitions
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class competitions{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    /**
     * Get parts of competitions information
     *
     * @param bool $type
     * @param int $s
     * @param array $params
     * @return bool
     */
    public function getCompetitionPart ($type = false, $s = 0, $params = array()) {
        if (!$type) return false;
        global $url;
        $data = array();

        // list /////////////////////////////////////////////////////////
        switch($type){
            case 'local':
                $data = $this->_getLocalList();
                break;
            case 'group':
                $data = $this->_getGroupList($params['local_id']);
                break;
            case 'championship':
                $data = $this->_getChampionshipList($params['group_id']);
                break;
            case 'tour':
                $data = $this->_getTourList($params['championship_id'], $params['type']);
                break;
            case 'stage':
                $data = $this->_getStageList($params['championship_id'], $params['type'], $params['ch_settings'], $params['tour_id']);
                if (isset($url->rest_path[$s]) && empty($url->rest_path[$s])) {
                    unset($url->rest_path[$s]);
                }
                break;
            case 'competition':
                $data = $this->_getCompetitionList($params['championship_id'], $params['tour_id'], $params['stage_id']);
                break;
            default:
                return false;
        }
        if (empty($data['address'])){
            return false;
        }

        // item /////////////////////////////////////////////////////////
        if (isset($url->rest_path[$s])) {
            if (!empty($data['address'][$url->rest_path[$s]])) {
                $data['item_id'] = $data['address'][$url->rest_path[$s]];
            } else {
                $data['item_id'] = false;
            }
        } else {
            $data['item_id'] = $data['menu'][0]['id'];
        }
        if (empty($data['item_id']) && !empty($data['menu'][0]['id'])) {
            $data['item_id'] = $data['menu'][0]['id'];
        }
        $data['item'] = $data['by_id'][$data['item_id']];

        // menu /////////////////////////////////////////////////////////
        if (!empty($data['menu'])){
            foreach ($data['menu'] as &$m_item) {
                if ($m_item['id'] == $data['item_id']) $m_item['active'] = 'yes';
                else $m_item['active'] = 'no';
            }
        }

        return $data;
    }

    /**
     * get type of competition page
     *
     * @param int $s
     * @param int $championshipType: 1 - rugby 15, 2 - rugby 7
     * @param array $ch_settings
     * @return string: championship|tour|stage|competition
     */
    public function getCompetitionPartType ($s = 0, $championshipType = 1, $ch_settings = array()){
        global $url;
        if (isset($url->rest_path[$s+2]) && $url->rest_path[$s+2] > 0) { // competition
            return 'competition';
        } elseif (isset($url->rest_path[$s+1])) { // stage
            if (!empty($ch_settings['isShowOnePage'][$url->rest_path[$s]][$url->rest_path[$s+1]])){
                return 'stage';
            } else {
                return 'competition';
            }
        } elseif (isset($url->rest_path[$s]) && $championshipType != 3) { // tour for not rugby super 15
            return 'competition';
        } else { // championship
            switch($championshipType) {
                case 1: { // rugby 15
                    return 'competition';
                    break;
                }
                case 2: { // rugby 7
                    return 'championship';
                    break;
                }
                case 3: { // rugby super 15
                    if (!empty($ch_settings['table_type_super_15']) && $ch_settings['table_type_super_15'] == 2) {
                        return 'competition';
                    } else {
                        return 'championship';
                    }
                    break;
                }
            }
        }
        return 'competition';
    }

    /**
     * Get list of Competitions
     *
     * @param int $championship_id
     * @param int $tour_id
     * @param int $stage_id
     * @return array|bool
     */
    private function _getCompetitionList($championship_id = 0, $tour_id = 0,  $stage_id = 0){
        $res = array();
        $tour_id--;
        if ($tour_id<0){
            return $res;
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions, ".DB_T_PREFIX."games
						","	DISTINCT cp_id AS id,
							cp_title_".S_LANG." AS title,
							cp_description_".S_LANG." AS description,
							cp_text_".S_LANG." AS text,
							cp_is_menu as is_menu,
							cp_is_rating_table as is_rating_table
						","	cp_is_active='yes' AND
							cp_ch_id = '$championship_id' AND
							cp_tour = '$tour_id' AND
							cp_substage = '$stage_id' AND
							g_cp_id = cp_id
						ORDER BY cp_order DESC,
							cp_title_".S_LANG." ASC");

        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripcslashes($item['title']);
                $item['address'] = $item['id'];
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                if ($item['is_menu'] == 'yes') {
                    $res['menu'][] = $item;
                }
            }
        }
        return $res;
    }

    /**
     * Get list of Stages
     *
     * @param int $championship_id
     * @param int $type
     * @param bool $ch_settings
     * @param int $tour_id
     * @return array|bool
     */
    private function _getStageList($championship_id = 0, $type = 0, $ch_settings = false, $tour_id = 0){
        global $language;

        $res = array();
        $tour_id--;
        if ($tour_id < 0){
            return $res;
        }

        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions, ".DB_T_PREFIX."games
						","	DISTINCT cp_substage
						","	cp_is_active='yes' AND
							cp_ch_id = '$championship_id' AND
							g_cp_id = cp_id AND
							cp_is_menu = 'yes' AND
							cp_tour = $tour_id
							ORDER BY cp_substage ASC");

        if (!$temp) return false;
        else {
            /*
            if ($type == 2){ // rugby 7
                $item = array(
                    'id'=> 0,
                    'title' => $language['competition_stage_root'],
                    'address' => '',
                    'is_menu' => 'yes'
                );
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                $res['menu'][] = $item;
            }
            */
            if ($type == 3){ // rugby super 15
                $item = array(
                    'id'=> 0,
                    'title' => $language['competition_stage_root'],
                    'address' => '',
                    'is_menu' => 'yes'
                );
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                if (!empty($ch_settings['table_type_super_15']) && $ch_settings['table_type_super_15'] != 2) {
                    $res['menu'][] = $item;
                }
            }
            $c_s = count($temp)-1;
            for ($i=0; $i<=$c_s; $i++){
                $item = array(
                    'id'=> $temp[$i]['cp_substage'],
                    'address' => $temp[$i]['cp_substage']>0 ? $temp[$i]['cp_substage'] : 1,
                    'is_menu' => 'yes'
                );
                if (!empty($ch_settings['stageTitle'][S_LANG][$tour_id][$temp[$i]['cp_substage']])){
                    $item['title'] = $ch_settings['stageTitle'][S_LANG][$tour_id][$temp[$i]['cp_substage']];
                } else {
                    if ($i==0){
                        $item['title'] = $language['competition_group_stage'];
                    } elseif ($c_s == $i){
                        $item['title'] = $language['competition_finale'];
                    } else {
                        if ($c_s < 3){
                            $item['title'] = $language['competition_semifinal'];
                        } else {
                            $item['title'] = '1/'.pow(2, $c_s-$i).'&nbsp;'.$language['competition_finals'];
                        }
                    }
                }
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                $res['menu'][] = $item;
            }
        }
        return $res;
    }

    /**
     * Get list of Tours & current Tour item
     *
     * @param int $championship_id
     * @param int $type: 1 - rugby 15, 2 - rugby 7
     * @return array
     */
    private function _getTourList($championship_id = 0, $type = 0){
        global $language;
        $res = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions, ".DB_T_PREFIX."games
                    ","	DISTINCT cp_tour
                    ","	cp_is_active='yes' AND
                        cp_ch_id = '$championship_id' AND
                        g_cp_id = cp_id AND
                        cp_is_menu = 'yes'
                        ORDER BY cp_tour ASC");

        if ($temp){
            $championship_data = $this->hdl->selectElem(DB_T_PREFIX."championship","ch_settings","ch_id=".$championship_id);
            if (!empty($championship_data)){
                $ch_settings = unserialize($this->_fix_serialized_string($championship_data[0]['ch_settings']));
            } else {
                $ch_settings = array();
            }
            if ($type == 2){ // rugby 7
                $item = array(
                    'id'=> 0,
                    'title' => $language['competition_tour_root'],
                    'address' => '',
                    'is_menu' => 'yes'
                );
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                $res['menu'][] = $item;
            }
            foreach ($temp as $item_) {
                $item = array(
                    'id'=> $item_['cp_tour']+1,
                    'title' => (!empty($ch_settings['tourTitle'][S_LANG][$item_['cp_tour']]))?$ch_settings['tourTitle'][S_LANG][$item_['cp_tour']]:$language['competition_tour_title'].($item_['cp_tour']+1),
                    'address' => $item_['cp_tour'],
                    'is_menu' => 'yes'
                );
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                $res['menu'][] = $item;
            }
        }
        return $res;
    }

    /**
     * get list of locals
     *
     * @return mixed
     */
    private function _getLocalList(){
        $res = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_local",
                    "	chl_id as id,
                        chl_title_".S_LANG." as title,
                        chl_address as address,
                        chl_is_menu AS is_menu",
                    "	chl_is_active='yes' AND
                        chl_address != ''
                    ORDER BY chl_is_main DESC,
                        chl_order DESC");
        if ($temp){
            foreach ($temp as &$item){
                $item['title'] = stripcslashes($item['title']);
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                if ($item['is_menu'] == 'yes') {
                    $res['menu'][] = $item;
                }
            }
        }
        return $res;
    }

    /**
     * Get list of groups
     *
     * @param int $local_id
     * @return bool
     */
    private function _getGroupList($local_id = 0){
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        $local_id = intval($local_id);
        if ($local_id>0) {
            $q_local_extra = "AND chg_chl_id='$local_id'";
        } else {
            $q_local_extra = '';
        }
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON chg.chg_id = c.type_id ";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '$section_type_id'";
                    break;
            }

        }
        $res = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group chg $f_c_extra",
                    "	chg_id as id,
                        chg_title_".S_LANG." as title,
                        chg_address as address,
                        chg_is_menu AS is_menu",
                    "	chg_is_active='yes' AND
                        chg_address != ''
                        $q_local_extra
                        $q_c_extra
                    ORDER BY chg_is_main DESC,
                        chg_order DESC");
        if ($temp){
            foreach ($temp as &$item){
                $item['title'] = stripcslashes($item['title']);
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                if ($item['is_menu'] == 'yes') {
                    $res['menu'][] = $item;
                }
            }
        }
        return $res;
    }

    /**
     * Get list of Campionship
     *
     * @param int $group_id
     * @return bool
     */
    private function _getChampionshipList($group_id = 0){
        global $section_type;
        global $section_type_id;
        $f_c_extra = '';
        $q_c_extra = '';
        $group_id = intval($group_id);
        if ($group_id<1) return false;
        if (!empty($section_type) && !empty($section_type_id)){
            switch($section_type) {
                case 'country':
                    $f_c_extra = "
                        LEFT JOIN ".DB_T_PREFIX."championship_group chg ON ch.ch_chg_id = chg.chg_id
                        INNER JOIN ".DB_T_PREFIX."connection_country c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '$section_type_id'";
                    break;
                case 'championship':
                    $f_c_extra = "
                        LEFT JOIN ".DB_T_PREFIX."championship_group chg ON ch.ch_chg_id = chg.chg_id
                        INNER JOIN ".DB_T_PREFIX."connection_champ c ON chg.chg_id = c.type_id";
                    $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '$section_type_id'";
                    break;
            }

        }
        $res = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship ch $f_c_extra",
                    "	ch.ch_id as id,
                        ch.ch_title_".S_LANG." as title,
                        ch.ch_description_".S_LANG." as description,
                        ch.ch_text_".S_LANG." as text,
                        ch.ch_address as address,
                        ch.ch_is_menu AS is_menu,
                        ch.ch_chc_id,
                        ch.ch_settings",
                    "	ch.ch_is_active='yes' AND
                        ch.ch_address != '' AND
                        ch.ch_chg_id = '$group_id'
                        $q_c_extra
                    ORDER BY ch.ch_is_main DESC,
                        ch.ch_order DESC");
        if ($temp){
            foreach ($temp as &$item){
                $item['ch_settings'] = (!empty($item['ch_settings']))?unserialize($this->_fix_serialized_string($item['ch_settings'])):array();
                $item['title'] = isset($item['title']) ? stripcslashes($item['title']) : '';
                $item['text'] = isset($item['text']) ? stripcslashes($item['text']) : '';
                $item['description'] = isset($item['description']) ? stripcslashes($item['description']) : '';
                $res['by_id'][$item['id']] = $item;
                $res['address'][$item['address']] = $item['id'];
                if ($item['is_menu'] == 'yes') {
                    $res['menu'][] = $item;
                }
            }
        }
        return $res;
    }

    /**
     * Get one Campionship
     *
     * @param int $ch_id
     * @return array|bool
     */

    public function getChampionshipItem($ch_id = 0){ // get one championship content
        $ch_id = intval($ch_id);
        if ($ch_id<1) return false;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."championship",
                    "	ch_id AS id,
                        ch_address AS address,
                        ch_title_".S_LANG." AS title,
                        ch_description_".S_LANG." AS description,
                        ch_text_".S_LANG." AS text,
                        ch_is_menu AS is_menu,
                        ch_date_from,
                        ch_date_to,
                        ch_is_done,
                        ch_chc_id,
                        ch_cn_id,
                        ch_ct_id,
                        ch_cp_is_done,
                        ch_tours,
                        ch_settings
                        ",
                    "	ch_is_active='yes' AND
                        ch_id = '$ch_id'
                    LIMIT 1 ");
        if ($temp) {
            $temp = $temp[0];
            $temp['title'] = stripslashes($temp['title']);
            $temp['description'] = stripslashes($temp['description']);
            $temp['text'] = stripslashes($temp['text']);
            $temp['ch_settings'] = unserialize($this->_fix_serialized_string($temp['ch_settings']));
        }
        return $temp;
    }

    /**
     * get Competition Data
     *
     * @param int $type: 1 - rugby 15, 2 - rugby 7
     * @param string $part_type: championship|tour|stage|competition
     * @param array $params
     * @return array|bool
     */
    public function getCompetitionData($type = 0, $part_type = 'competition', $params = array()){
        $type = intval($type);
        $data = array();
        // Pass show_stuff_rating to the returned data
        $data['show_stuff_rating'] = !empty($params['show_stuff_rating']) ? $params['show_stuff_rating'] : 0;
        switch($type){
            case '1': // rugby 15
                if ($params['is_rating_table'] == 'yes'){
                    $data['standing'] = $this->_getStandings($params['championship_id'], $params['competition_id']);
                }
                $data['round'] = $this->_getRound($params['competition_id']);
                if (!empty($data['round'])){
                    $data['games'] = $this->_getGames($params['championship_id'], $params['competition_id'], $data['round']['type'], $data['round']['last_round']);
                }
                $data['staff'] = $this->_getCompetitionsStaff($params['championship_id'], false, false, 0, $params['championship_count_stuff_rating']);
                break;
            case '2': // rugby 7
                switch ($part_type){
                    case'competition':
                        if ($params['is_rating_table'] == 'yes'){
                            $data['standing'] = $this->_getStandings($params['championship_id'], $params['competition_id']);
                        }
                        $data['round'] = $this->_getRound($params['competition_id']);
                        if (!empty($data['round'])){
                            $data['games'] = $this->_getGames($params['championship_id'], $params['competition_id'], $data['round']['type'], $data['round']['last_round']);
                        }
                        break;
                    case'stage':
                        if (!empty($params['competition_list'])){
                            foreach ($params['competition_list'] as $key=>$val){
                                $data['round_list'][$key] = $this->_getRound($val['id']);
                                if (!empty($data['round_list'][$key])){
                                    $data['games_list'][$key] = $this->_getGames($params['championship_id'], $val['id'], $data['round_list'][$key]['type'], $data['round_list'][$key]['last_round']);
                                }
                            }
                        }
                        break;
                    case'tour':
                        $data['standing'] = $this->_getStandingsTourTeam($params['championship_id'], $params['tour_id']);
                        break;
                    case'championship':
                        $data['standing'] = $this->_getStandingsChampTeam($params['championship_id']);
                        break;
                }
                break;
            case '3': // rugby super 15
                switch ($part_type){
                    case'competition':
                        if ($params['is_rating_table'] == 'yes'){
                            $data['standing'] = $this->_getStandings($params['championship_id'], $params['competition_id']);
                        }
                        $data['round'] = $this->_getRound($params['competition_id']);
                        if (!empty($data['round'])){
                            $data['games'] = $this->_getGames($params['championship_id'], $params['competition_id'], $data['round']['type'], $data['round']['last_round']);
                        }
                        break;
                    case'stage':
                        if (!empty($params['competition_list'])){
                            foreach ($params['competition_list'] as $key=>$val){
                                $data['round_list'][$key] = $this->_getRound($val['id']);
                                if (!empty($data['round_list'][$key])){
                                    $data['games_list'][$key] = $this->_getGames($params['championship_id'], $val['id'], $data['round_list'][$key]['type'], $data['round_list'][$key]['last_round']);
                                }
                            }
                        }
                        break;
                    case'championship':
                        $data['standing'] = (empty($params['table_type_super_15']) || $params['table_type_super_15']==1)?$this->_getStandingsChampTeamSuper15($params['championship_id'], $params['table_type_super_15']):false;
                        break;
                }
                break;
        }
        return $data;
    }

    private function _getStandingsChampTeamSuper15($id = false, $type=0){
        $id = intval($id);
        if ($id<1) return false;
        $type = intval($type);
        if ($type < 0 || $type > 2) {
            $type = 0;
        }
        $tour_id = 0; // if needed in future
        $stage_id = 1;
        $competitions = array();
        $standing_res = array();

        // get list of competitions
        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions, ".DB_T_PREFIX."games
						","	DISTINCT cp_id AS id,
							cp_title_".S_LANG." AS title,
							cp_is_menu as is_menu,
							cp_is_rating_table as is_rating_table
						","	cp_is_active='yes' AND
							cp_ch_id = '$id' AND
							cp_tour = '$tour_id' AND
							cp_substage = '$stage_id' AND
							g_cp_id = cp_id
						ORDER BY cp_order DESC,
							cp_title_".S_LANG." ASC");
        if ($temp) {
            foreach ($temp as $item){
                $item['title'] = stripcslashes($item['title']);
                $item['address'] = $item['id'];
                $competitions[$item['id']] = $item;
            }
            foreach ($competitions as $c_item){
                $standing = $this->_getStandings($id, $c_item['id']);
                if (!empty($standing)) {
                    if (empty($type)) {
                        $standing_res[0][] = $standing[0];
                        for ($i = 1; $i < count($standing); $i++) {
                            $standing_res[1][] = $standing[$i];
                        }
                    } elseif($type == 1) {
                        for ($i = 0; $i < count($standing); $i++) {
                            $standing_res[0][] = $standing[$i];
                        }
                    }
                }
            }
            for ($i=0; $i<count($standing_res); $i++){
                $standing_res[$i] = $this->_sortStandings($standing_res[$i]);
            }
        }
        return $standing_res;
    }

    private function _sortStandings($team = array()){
        $p_team = array();
        if (!empty($team)){
            for ($i=1; $i<count($team); $i++){
                for ($j=0; $j<count($team); $j++){
                    if ($j>0) {
                        if ($team[$j]['p'] > $p_team['p']) {
                            $team[$j-1] = $team[$j];
                            $team[$j] = $p_team;
                        } elseif ($team[$j]['p'] < $p_team['p']){
                            $p_team = $team[$j];
                        } else { // + дополнительная сортировка по РИО
                            // разница забитых / пропущеных
                            if ($team[$j]['p_diff'] > $p_team['p_diff']) { // если число забитых больше - то наверх
                                $team[$j-1] = $team[$j];
                                $team[$j] = $p_team;
                            } elseif($team[$j]['p_diff'] < $p_team['p_diff']) {
                                $p_team = $team[$j];
                            }
                            //else $p_team = $team[$j];
                            // отдельно по забитым и пропущеным
                            elseif ($team[$j]['p_scored'] > $p_team['p_scored']) { // если число забитых больше - то наверх
                                $team[$j-1] = $team[$j];
                                $team[$j] = $p_team;
                            } elseif($team[$j]['p_scored'] < $p_team['p_scored']) {
                                $p_team = $team[$j];
                            } else {
                                if ($team[$j]['p_missed'] < $p_team['p_missed']) { // если число пропущеных меньше - то наверх
                                    $team[$j-1] = $team[$j];
                                    $team[$j] = $p_team;
                                } elseif($team[$j]['p_missed'] > $p_team['p_missed']) $p_team = $team[$j];
                                else $p_team = $team[$j]; // добавить выборку и просмотр личных встреч: выигрыш - выше проигрыша (возможно отдельная функция)
                            }
                        }
                    } else {
                        $p_team = $team[$j];
                    }
                }
            }
        }
        return $team;
    }

    public function _getCompetitionsStaff($id = 0, $tour = false, $cp_id = false, $sort = 0, $count_st = 15){ // Лучшие игроки
        $id = intval($id);
        $sort = intval($sort);
        $count_st = intval($count_st);
        if ($count_st < 1) {
            $count_st = 5;
        }
        if ($id<1) return false;

        $q_extra_w = $q_extra_f = '';
        if ($tour !== false) {
            $tour = intval($tour);
            $q_extra_f .= ", ".DB_T_PREFIX."competitions";
            $q_extra_w .= "AND g_cp_id = cp_id AND cp_tour = '$tour' ";
        }
        if ($cp_id !== false) {
            $cp_id = intval($cp_id);
            $q_extra_w .= " AND g_cp_id = '$cp_id' ";
        }

$staff = $this->hdl->selectElem(DB_T_PREFIX."games_actions ga
            JOIN ".DB_T_PREFIX."games g ON ga.ga_g_id = g.g_id
            JOIN ".DB_T_PREFIX."staff s ON ga.ga_st_id = s.st_id
            JOIN ".DB_T_PREFIX."team t ON ga.ga_t_id = t.t_id $q_extra_f","
        DISTINCT s.st_id,
        s.st_family_".S_LANG." AS family,
        s.st_name_".S_LANG." AS name,
        s.st_surname_".S_LANG." AS surname,
        ga.ga_t_id AS t_id
        ","
        g.g_ch_id = '$id'
        AND t.t_is_technical = 'no'
        AND g.g_is_active = 'yes'
        AND g.g_is_done = 'yes'
        $q_extra_w
        ");

        if ($staff){
            $c_staff = count($staff);
            for ($i=0; $i<$c_staff; $i++){
                $staff[$i]['family'] = stripcslashes($staff[$i]['family']);
                $staff[$i]['name'] = stripcslashes($staff[$i]['name']);
                $staff[$i]['surname'] = stripcslashes($staff[$i]['surname']);
                if (!isset($ga_team[$staff[$i]['t_id']]))
                    $ga_team[$staff[$i]['t_id']] = $this->hdl->selectElem(
                                DB_T_PREFIX."games_actions,
								".DB_T_PREFIX."games
								$q_extra_f
							","
								ga_st_id,
								ga_type
							","
								ga_is_delete='no' AND
								ga_type != 'zam_out' AND
								ga_type != 'zam_in' AND
								ga_t_id = '".$staff[$i]['t_id']."' AND
								g_id = ga_g_id AND
								g_is_done = 'yes' AND
								g_is_active = 'yes' AND
								g_ch_id = '$id'
								$q_extra_w"
                    );
                if ($ga_team[$staff[$i]['t_id']])
                    foreach ($ga_team[$staff[$i]['t_id']] as $item) {
                        if ($item['ga_st_id'] == $staff[$i]['st_id']) {
                            if (isset($staff[$i][$item['ga_type']])) $staff[$i][$item['ga_type']] ++;
                            else $staff[$i][$item['ga_type']] = 1;
                        }
                    }
                // 1 попытка - 5 очков
                // 1 штрафной - 3 очка
                // 1 реализация - 2 очка
                // 1 дроп-гол - 3 очка
                if (empty($staff[$i]['points'])) $staff[$i]['points'] = 0;
                if (empty($staff[$i]['pop'])) $staff[$i]['pop'] = 0;
                if (empty($staff[$i]['sht'])) $staff[$i]['sht'] = 0;
                if (empty($staff[$i]['pez'])) $staff[$i]['pez'] = 0;
                if (empty($staff[$i]['d_g'])) $staff[$i]['d_g'] = 0;
                if (empty($staff[$i]['y_c'])) $staff[$i]['y_c'] = 0;
                if (empty($staff[$i]['r_c'])) $staff[$i]['r_c'] = 0;
                $staff[$i]['points'] = $staff[$i]['pop']*5 + $staff[$i]['sht']*3 + $staff[$i]['pez']*2 + $staff[$i]['d_g']*3;

                if ($staff[$i]['pop'] == 0) $staff[$i]['pop'] = '-';
                if ($staff[$i]['sht'] == 0) $staff[$i]['sht'] = '-';
                if ($staff[$i]['pez'] == 0) $staff[$i]['pez'] = '-';
                if ($staff[$i]['d_g'] == 0) $staff[$i]['d_g'] = '-';
                if ($staff[$i]['y_c'] == 0) $staff[$i]['y_c'] = '-';
                if ($staff[$i]['r_c'] == 0) $staff[$i]['r_c'] = '-';
            }
        } else return false;
        // сортировка по очкам
        if ($staff)
            $p_staff = array();
            switch($sort) {
                case 1: { // по pop
                    for ($i=1; $i<count($staff); $i++){
                        for ($j=0; $j<count($staff); $j++){
                            if ($j>0) {
                                if ($staff[$j]['pop'] > $p_staff['pop']) {
                                    $staff[$j-1] = $staff[$j];
                                    $staff[$j] = $p_staff;
                                } else {
                                    $p_staff = $staff[$j];
                                }
                            } else {
                                $p_staff = $staff[$j];
                            }
                        }
                    }
                    break;
                }
                case 2: { // по pez
                    for ($i=1; $i<count($staff); $i++){
                        for ($j=0; $j<count($staff); $j++){
                            if ($j>0) {
                                if ($staff[$j]['pez'] > $p_staff['pez']) {
                                    $staff[$j-1] = $staff[$j];
                                    $staff[$j] = $p_staff;
                                } else {
                                    $p_staff = $staff[$j];
                                }
                            } else {
                                $p_staff = $staff[$j];
                            }
                        }
                    }
                    break;
                }
                case 3: { // по sht
                    for ($i=1; $i<count($staff); $i++){
                        for ($j=0; $j<count($staff); $j++){
                            if ($j>0) {
                                if ($staff[$j]['sht'] > $p_staff['sht']) {
                                    $staff[$j-1] = $staff[$j];
                                    $staff[$j] = $p_staff;
                                } else {
                                    $p_staff = $staff[$j];
                                }
                            } else {
                                $p_staff = $staff[$j];
                            }
                        }
                    }
                    break;
                }
                case 4: { // по d_g
                    for ($i=1; $i<count($staff); $i++){
                        for ($j=0; $j<count($staff); $j++){
                            if ($j>0) {
                                if ($staff[$j]['d_g'] > $p_staff['d_g']) {
                                    $staff[$j-1] = $staff[$j];
                                    $staff[$j] = $p_staff;
                                } else {
                                    $p_staff = $staff[$j];
                                }
                            } else {
                                $p_staff = $staff[$j];
                            }
                        }
                    }
                    break;
                }
                case 5: { // по y_c
                    for ($i=1; $i<count($staff); $i++){
                        for ($j=0; $j<count($staff); $j++){
                            if ($j>0) {
                                if ($staff[$j]['y_c'] > $p_staff['y_c']) {
                                    $staff[$j-1] = $staff[$j];
                                    $staff[$j] = $p_staff;
                                } else {
                                    $p_staff = $staff[$j];
                                }
                            } else {
                                $p_staff = $staff[$j];
                            }
                        }
                    }
                    break;
                }
                case 6: { // по r_c
                    for ($i=1; $i<count($staff); $i++){
                        for ($j=0; $j<count($staff); $j++){
                            if ($j>0) {
                                if ($staff[$j]['r_c'] > $p_staff['r_c']) {
                                    $staff[$j-1] = $staff[$j];
                                    $staff[$j] = $p_staff;
                                } else {
                                    $p_staff = $staff[$j];
                                }
                            } else {
                                $p_staff = $staff[$j];
                            }
                        }
                    }
                    break;
                }
                default : { // по points
                for ($i=1; $i<count($staff); $i++){
                    for ($j=0; $j<count($staff); $j++){
                        if ($j>0) {
                            if ($staff[$j]['points'] > $p_staff['points']) {
                                $staff[$j-1] = $staff[$j];
                                $staff[$j] = $p_staff;
                            } else {
                                $p_staff = $staff[$j];
                            }
                        } else {
                            $p_staff = $staff[$j];
                        }
                    }
                }
                }
            }
        $c_s = $count_st;
        if (count($staff)<=15) $c_s = count($staff);
        for ($i=0; $i<$c_s; $i++) $res[] = $staff[$i];
        return $res;
    }

    /**
     * get Table
     *
     * @param int $championship_id
     * @param int $competition_id
     * @return array|bool
     */
    public function _getStandings($championship_id = 0, $competition_id = 0){
        $championship_id = intval($championship_id);
        if ($championship_id < 1) return false;
        $competition_id = intval($competition_id);
        if ($competition_id < 1) return false;

        if ($competition_id>0) {
            $q_cp = " AND (g_cp_id = '$competition_id' OR g_cp_id_g = '$competition_id') ";
        } else $q_cp = '';

        // select teams
        $team = array();
        $team_t = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team, ".DB_T_PREFIX."games","
					t_id,
					t_title_".S_LANG." AS title,
					t_is_detailed,
					t_address,
					g_cp_id,
					g_cp_id_g,
					g_owner_t_id,
					g_guest_t_id
				","
					cntch_is_delete='no' AND
					cntch_ch_id = '$championship_id' AND
					cntch_t_id = t_id
					$q_cp
					AND ( g_owner_t_id = t_id OR g_guest_t_id = t_id)
					AND t_is_delete = 'no'
					ORDER BY
					t_id ASC");
        if ($team_t)
        foreach($team_t as $team_t_item){
            if (($team_t_item['g_cp_id'] == $competition_id && $team_t_item['t_id'] == $team_t_item['g_owner_t_id']) ||
                ($team_t_item['g_cp_id_g'] == $competition_id && $team_t_item['t_id'] == $team_t_item['g_guest_t_id'])){
                if (empty($team_id[$team_t_item['t_id']])){
                    $team_id[$team_t_item['t_id']] = array(
                        't_id' => $team_t_item['t_id'],
                        'title' => $team_t_item['title'],
                        't_is_detailed' => $team_t_item['t_is_detailed'],
                        't_address' => $team_t_item['t_address']
                    );
                    $team[] = array(
                        't_id' => $team_t_item['t_id'],
                        'title' => $team_t_item['title'],
                        't_is_detailed' => $team_t_item['t_is_detailed'],
                        't_address' => $team_t_item['t_address']
                    );
                }
            }
        }
        $championship = $this->hdl->selectElem(DB_T_PREFIX."championship","
                    ch_id,
                    ch_p_win,
                    ch_p_draw,
                    ch_p_loss,
                    ch_p_bonus_1,
                    ch_p_bonus_2,
                    ch_p_bonus_2_diff,
                    ch_p_tehwin,
                    ch_settings,
                    ch_chc_id
                ","
                    ch_id = '$championship_id'
                LIMIT 1");
        if (!$championship) return false;
        $championship = $championship[0];
        $championship['ch_settings'] = unserialize($this->_fix_serialized_string($championship['ch_settings']));
        if (!empty($championship['ch_settings']['table_order_priority'])){
            $championship['table_order_priority'] = explode(',', $championship['ch_settings']['table_order_priority']);
        } else {
            $championship['table_order_priority'] = array('z_p_p', 'z_p_t', 'b', 'w_p_v', 'p_p_v', 't_p_v', 'win');
        }
        if ($championship['ch_chc_id'] == 3) {
            $q_cp_games = '';
        } else {
            $q_cp_games = $q_cp;
        }
        $games = $this->hdl->selectElem(DB_T_PREFIX."games","
                    g_id,
                    g_ch_id,
                    g_cp_id,
                    g_cp_id_g,
                    g_owner_t_id,
                    g_guest_t_id,
                    g_owner_points,
                    g_guest_points,
                    g_owner_tehwin,
                    g_guest_tehwin,
                    g_owner_bonus_1,
                    g_guest_bonus_1,
                    g_owner_extra_points,
                    g_guest_extra_points
				","
				    g_ch_id = '$championship_id'
				    $q_cp_games
                    AND g_is_done = 'yes'
                    AND g_is_active = 'yes'
                GROUP BY g_id
				ORDER BY
                    g_cp_id ASC,
                    g_owner_t_id ASC,
                    g_guest_t_id ASC");
        if ($team){
            $q_g_ids = array();
            if (!empty($games)){
                foreach ($games as $g_item){
                    $q_g_ids[] = $g_item['g_id'];
                }
            }
            $games_actions = $this->getGamesActions($q_g_ids);
            for ($i=0; $i<count($team); $i++){
                $team[$i]['title'] = stripcslashes($team[$i]['title']);
                $team[$i]['games'] = $team[$i]['win'] = $team[$i]['loss'] = $team[$i]['draw'] = $team[$i]['p'] = $team[$i]['p_scored'] = $team[$i]['p_missed'] = $team[$i]['p_diff'] = 0;
                // рейтинг команд и подсчет очков
                if ($games)
                    foreach ($games as $item){
                        if ($item['g_owner_t_id'] != $item['g_guest_t_id']){
                            $f = false;
                            // && $competition_id == $team[$i]['g_cp_id']
                            if ($item['g_owner_t_id'] == $team[$i]['t_id'] &&
                                (
                                    $item['g_cp_id'] == $competition_id || $championship['ch_chc_id'] == 3
                                )
                            ){
                                $opponent_team_id = $item['g_guest_t_id'];
                                if (empty($team[$i]['teams'][$opponent_team_id])){ // extra info for teams
                                    $team[$i]['teams'][$opponent_team_id] = array(
                                        'g'         => 0,
                                        'win'       => 0,
                                        'loss'      => 0,
                                        'draw'      => 0,
                                        'p'         => 0,
                                        'p_scored'  => 0,
                                        'p_missed'  => 0,
                                        'extra_p'   => 0,
                                        't'         => 0,
                                        'zp_p'      => 0,
                                        'zp_t'      => 0,
                                        'b_d'       => 0,
                                        'b_a'       => 0
                                    );
                                }
                                if (empty($team[$i]['t'])) {
                                    $team[$i]['t'] = 0;
                                }
                                if (empty($team[$i]['t_diff'])) {
                                    $team[$i]['t_diff'] = 0;
                                }
                                $pop = (!empty($games_actions['games'][$item['g_id']][$opponent_team_id]['pop']))?
                                    $games_actions['games'][$item['g_id']][$opponent_team_id]['pop'] : 0;
                                $pop_ = (!empty($games_actions['games'][$item['g_id']][$team[$i]['t_id']]['pop']))?
                                    $games_actions['games'][$item['g_id']][$team[$i]['t_id']]['pop'] : 0;
                                $team[$i]['teams'][$opponent_team_id]['zp_t'] += $pop_ - $pop ; // zp_t
                                $team[$i]['t_diff'] += $pop_ - $pop ; // t_diff
                                $team[$i]['games']++;
                                $team[$i]['teams'][$opponent_team_id]['g']++; // g
                                $team[$i]['p_scored'] = $team[$i]['p_scored'] + $item['g_owner_points'];
                                $team[$i]['teams'][$opponent_team_id]['p_scored'] += $item['g_owner_points']; //p_scored
                                $team[$i]['p_missed'] = $team[$i]['p_missed'] + $item['g_guest_points'];
                                $team[$i]['teams'][$opponent_team_id]['p_missed'] += $item['g_guest_points']; //p_missed
                                $team[$i]['teams'][$opponent_team_id]['zp_p'] += $team[$i]['teams'][$opponent_team_id]['p_scored'] -
                                    $team[$i]['teams'][$opponent_team_id]['p_missed']; //zp_p
                                if (isset($team[$i]['extra_p'])) {
                                    $team[$i]['extra_p'] += $item['g_owner_extra_points'];
                                } else {
                                    $team[$i]['extra_p'] = $item['g_owner_extra_points'];
                                }
                                $team[$i]['teams'][$opponent_team_id]['extra_p'] += $item['g_owner_extra_points']; //extra_p
                                if ($item['g_owner_tehwin'] == 'yes') {
                                    $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_tehwin'];
                                    $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_tehwin']; //p ch_p_tehwin
                                    $team[$i]['win']++;
                                    $team[$i]['teams'][$opponent_team_id]['win']++; //win ch_p_tehwin
                                } else {
                                    if ($item['g_owner_points'] > $item['g_guest_points']) {
                                        $team[$i]['win']++;
                                        $team[$i]['teams'][$opponent_team_id]['win']++; //win ch_p_win
                                        $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_win'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_win']; //p ch_p_win
                                    }
                                    if ($item['g_owner_points'] < $item['g_guest_points']) {
                                        $team[$i]['loss']++;
                                        $team[$i]['teams'][$opponent_team_id]['loss']++; //loss
                                        $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_loss'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_loss']; //p ch_p_loss
                                        // bonus <= N points when loose
                                        $bonus_2_diff = !empty($championship['ch_p_bonus_2_diff']) ? intval($championship['ch_p_bonus_2_diff']) : 7;
                                        if (($item['g_guest_points'] - $item['g_owner_points']) <= $bonus_2_diff) {
                                            $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_2'];
                                            $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_bonus_2']; //p ch_p_bonus_2
                                            $team[$i]['teams'][$opponent_team_id]['b_d']++; //b_d
                                            if (!empty($team[$i]['bonus_2'])) {
                                                $team[$i]['bonus_2'] += $championship['ch_p_bonus_2'];
                                            } else {
                                                $team[$i]['bonus_2'] = $championship['ch_p_bonus_2'];
                                            }
                                        }
                                    }
                                    if ($item['g_owner_points'] == $item['g_guest_points']) {
                                        $team[$i]['draw']++;
                                        $team[$i]['teams'][$opponent_team_id]['draw']++; //draw
                                        $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_draw'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_draw']; //p ch_p_draw
                                    }
                                    if ($item['g_owner_bonus_1'] == 'yes') {
                                        $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_1'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_bonus_1']; //p ch_p_bonus_1
                                        $team[$i]['teams'][$opponent_team_id]['b_a']++; //b_a
                                        if (!empty($team[$i]['bonus_1'])) {
                                            $team[$i]['bonus_1'] += $championship['ch_p_bonus_1'];
                                        } else {
                                            $team[$i]['bonus_1'] = $championship['ch_p_bonus_1'];
                                        }
                                    }
                                }
                                $team[$i]['teams'][$opponent_team_id]['b'] = $team[$i]['teams'][$opponent_team_id]['b_a'] +
                                    $team[$i]['teams'][$opponent_team_id]['b_d'];
                                $f = true;
                            }
                            if ($item['g_guest_t_id'] == $team[$i]['t_id'] &&
                                (
                                    $item['g_cp_id'] == $competition_id || $championship['ch_chc_id'] == 3
                                )
                            ){
                                $opponent_team_id = $item['g_owner_t_id'];
                                if (empty($team[$i]['teams'][$opponent_team_id])){ // extra info for teams
                                    $team[$i]['teams'][$opponent_team_id] = array(
                                        'g'         => 0,
                                        'win'       => 0,
                                        'loss'      => 0,
                                        'draw'      => 0,
                                        'p'         => 0,
                                        'p_scored'  => 0,
                                        'p_missed'  => 0,
                                        'extra_p'   => 0,
                                        't'         => 0,
                                        'zp_p'      => 0,
                                        'zp_t'      => 0,
                                        'b_d'       => 0,
                                        'b_a'       => 0
                                    );
                                }
                                if (empty($team[$i]['t'])) {
                                    $team[$i]['t'] = 0;
                                }
                                if (empty($team[$i]['t_diff'])) {
                                    $team[$i]['t_diff'] = 0;
                                }
                                $pop = (!empty($games_actions['games'][$item['g_id']][$team[$i]['t_id']]['pop']))?
                                    $games_actions['games'][$item['g_id']][$team[$i]['t_id']]['pop'] : 0;
                                $pop_ = (!empty($games_actions['games'][$item['g_id']][$opponent_team_id]['pop']))?
                                    $games_actions['games'][$item['g_id']][$opponent_team_id]['pop'] : 0;
                                $team[$i]['teams'][$opponent_team_id]['zp_t'] += $pop - $pop_ ; // zp_t
                                $team[$i]['t_diff'] += $pop - $pop_ ; // t_diff
                                $team[$i]['games']++;
                                $team[$i]['teams'][$opponent_team_id]['g']++; // g
                                $team[$i]['p_scored'] = $team[$i]['p_scored'] + $item['g_guest_points'];
                                $team[$i]['teams'][$opponent_team_id]['p_scored'] += $item['g_guest_points']; //p_scored
                                $team[$i]['p_missed'] = $team[$i]['p_missed'] + $item['g_owner_points'];
                                $team[$i]['teams'][$opponent_team_id]['p_missed'] += $item['g_owner_points']; //p_missed
                                $team[$i]['teams'][$opponent_team_id]['zp_p'] += $team[$i]['teams'][$opponent_team_id]['p_scored'] -
                                    $team[$i]['teams'][$opponent_team_id]['p_missed']; //zp_p
                                if (isset($team[$i]['extra_p'])) {
                                    $team[$i]['extra_p'] += $item['g_guest_extra_points'];
                                } else {
                                    $team[$i]['extra_p'] = $item['g_guest_extra_points'];
                                }
                                //if ($team[$i]['t_id'] == 36) echo $team[$i]['p'];
                                $team[$i]['teams'][$opponent_team_id]['extra_p'] += $item['g_guest_extra_points']; //extra_p
                                if ($item['g_guest_tehwin'] == 'yes') {
                                    $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_tehwin'];
                                    $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_tehwin']; //p ch_p_tehwin
                                    $team[$i]['win']++;
                                    $team[$i]['teams'][$opponent_team_id]['win']++; //win ch_p_tehwin
                                } else {
                                    if ($item['g_guest_points'] > $item['g_owner_points']) {
                                        $team[$i]['win']++;
                                        $team[$i]['teams'][$opponent_team_id]['win']++; //win ch_p_win
                                        $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_win'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_win']; //p ch_p_win
                                    }
                                    if ($item['g_guest_points'] < $item['g_owner_points']) {
                                        $team[$i]['loss']++;
                                        $team[$i]['teams'][$opponent_team_id]['loss']++; //loss
                                        $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_loss'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_loss']; //p ch_p_loss
                                        // bonus <= N points when loose
                                        $bonus_2_diff = !empty($championship['ch_p_bonus_2_diff']) ? intval($championship['ch_p_bonus_2_diff']) : 7;
                                        if (($item['g_owner_points'] - $item['g_guest_points']) <= $bonus_2_diff) {
                                            $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_2'];
                                            $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_bonus_2']; //p ch_p_bonus_2
                                            $team[$i]['teams'][$opponent_team_id]['b_d']++; //b_d
                                            if (!empty($team[$i]['bonus_2'])) {
                                                $team[$i]['bonus_2'] += $championship['ch_p_bonus_2'];
                                            } else {
                                                $team[$i]['bonus_2'] = $championship['ch_p_bonus_2'];
                                            }
                                        }
                                    }
                                    if ($item['g_guest_points'] == $item['g_owner_points']) {
                                        $team[$i]['draw']++;
                                        $team[$i]['teams'][$opponent_team_id]['draw']++; //draw
                                        $team[$i]['p'] = $team[$i]['p']+$championship['ch_p_draw'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_draw']; //p ch_p_draw
                                    }
                                    if ($item['g_guest_bonus_1'] == 'yes') {
                                        $team[$i]['p'] = $team[$i]['p'] + $championship['ch_p_bonus_1'];
                                        $team[$i]['teams'][$opponent_team_id]['p'] += $championship['ch_p_bonus_1']; //p ch_p_bonus_1
                                        $team[$i]['teams'][$opponent_team_id]['b_a']++; //b_a
                                        if (!empty($team[$i]['bonus_1'])) {
                                            $team[$i]['bonus_1'] += $championship['ch_p_bonus_1'];
                                        } else {
                                            $team[$i]['bonus_1'] = $championship['ch_p_bonus_1'];
                                        }
                                    }
                                }
                                $team[$i]['teams'][$opponent_team_id]['b'] = $team[$i]['teams'][$opponent_team_id]['b_a'] +
                                    $team[$i]['teams'][$opponent_team_id]['b_d'];
                                //if ($team[$i]['t_id'] == 36) echo $team[$i]['p'];

                                $f = true;
                            }
                            //if ($team[$i]['t_id'] == 36 and $f) echo "[". $item['g_id'] ." - ". $team[$i]['p']."] , ";
                        }

                    }
                // extra points
                $team[$i]['p'] = $team[$i]['p']+((!empty($team[$i]['extra_p']))?$team[$i]['extra_p']:0);
                $team[$i]['p_diff'] = $team[$i]['p_scored'] - $team[$i]['p_missed'];
            }
            // сортировка команд по очкам
            $p_team = array();
            for ($i=1; $i<count($team); $i++){
                for ($j=0; $j<count($team); $j++){
                    if ($j>0) {
                        if ($team[$j]['p'] > $p_team['p']) {
                            $team[$j-1] = $team[$j];
                            $team[$j] = $p_team;
                        } elseif ($team[$j]['p'] < $p_team['p']){
                            $p_team = $team[$j];
                        } else { // + дополнительная сортировка
                            $equel_check = 0;
                            foreach($championship['table_order_priority'] as $item){
                                $equel_check = $this->checkEqualPointsTeams($team[$j], $p_team, $item);
                                if ($equel_check>0){
                                    break;
                                }
                            }
                            if ($equel_check == 1){
                                $team[$j-1] = $team[$j];
                                $team[$j] = $p_team;
                            } elseif ($equel_check == 2){
                                $p_team = $team[$j];
                            } else {
                                $p_team = $team[$j];
                            }
                        }
                    } else {
                        $p_team = $team[$j];
                    }
                }
            }
        }
        return $team;
    }

    /**
     * @param array $g_ids
     * @return array
     */
    private function getGamesActions($g_ids = array()) {
        $res = array();
        if (empty($g_ids)){
            return $res;
        }
        $actions = $this->hdl->selectElem(DB_T_PREFIX."games_actions","
				ga_type as type,
				ga_t_id AS t_id,
				ga_g_id AS g_id
				","
				ga_g_id IN (".implode(', ', $g_ids).") AND
				ga_is_delete = 'no'
				");
        if ($actions){
            foreach($actions as $item){
                if (empty($res['teams'][$item['t_id']])){
                    $res['teams'][$item['t_id']] = array(
                        'zam_out'=>0,
                        'zam_in'=>0,
                        'pop'=>0,
                        'sht'=>0,
                        'pez'=>0,
                        'd_g'=>0,
                        'y_c'=>0,
                        'r_c'=>0
                    );
                }
                $res['teams'][$item['t_id']][$item['type']]++;
                if (empty($res['games'][$item['g_id']][$item['t_id']])){
                    $res['games'][$item['g_id']][$item['t_id']] = array(
                        'zam_out'=>0,
                        'zam_in'=>0,
                        'pop'=>0,
                        'sht'=>0,
                        'pez'=>0,
                        'd_g'=>0,
                        'y_c'=>0,
                        'r_c'=>0
                    );
                }
                $res['games'][$item['g_id']][$item['t_id']][$item['type']]++;
            }
        }
        return $res;
    }

    /**
     * @param $c_team - current team in iteration
     * @param $p_team - previous team
     * @param $type - in witch way is compare two teams
     * @return int - 0 - equal, 1 - current is greater, 2 - previous is greater
     */
    private function checkEqualPointsTeams($c_team, $p_team, $type){
        $equel_check = 0;
        if (empty($c_team['win'])) $c_team['win'] = 0;
        if (empty($c_team['t_diff'])) $c_team['t_diff'] = 0;
        if (empty($c_team['p_diff'])) $c_team['p_diff'] = 0;
        if (empty($c_team['teams'][$p_team['t_id']]['b'])) $c_team['teams'][$p_team['t_id']]['b'] = 0;
        if (empty($c_team['teams'][$p_team['t_id']]['win'])) $c_team['teams'][$p_team['t_id']]['win'] = 0;
        if (empty($c_team['teams'][$p_team['t_id']]['zp_p'])) $c_team['teams'][$p_team['t_id']]['zp_p'] = 0;
        if (empty($c_team['teams'][$p_team['t_id']]['zp_t'])) $c_team['teams'][$p_team['t_id']]['zp_t'] = 0;
        if (empty($p_team['win'])) $p_team['win'] = 0;
        if (empty($p_team['t_diff'])) $p_team['t_diff'] = 0;
        if (empty($p_team['p_diff'])) $p_team['p_diff'] = 0;
        if (empty($p_team['teams'][$c_team['t_id']]['b'])) $p_team['teams'][$c_team['t_id']]['b'] = 0;
        if (empty($p_team['teams'][$c_team['t_id']]['win'])) $p_team['teams'][$c_team['t_id']]['win'] = 0;
        if (empty($p_team['teams'][$c_team['t_id']]['zp_p'])) $p_team['teams'][$c_team['t_id']]['zp_p'] = 0;
        if (empty($p_team['teams'][$c_team['t_id']]['zp_t'])) $p_team['teams'][$c_team['t_id']]['zp_t'] = 0;
        switch ($type) {
            case 'win': // количество побед
                if ($c_team['win'] > $p_team['win']) {
                    $equel_check = 1;
                } elseif($c_team['win'] < $p_team['win']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
            case 'z_p_p': // разница забитых / пропущеных очков
                if ($c_team['p_diff'] > $p_team['p_diff']) {
                    $equel_check = 1;
                } elseif($c_team['p_diff'] < $p_team['p_diff']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
            case 'z_p_t': // разница забитых / пропущеных попыток
                if ($c_team['t_diff'] > $p_team['t_diff']) {
                    $equel_check = 1;
                } elseif($c_team['t_diff'] < $p_team['t_diff']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
            case 'b': // разница количества бонусов
                if ($c_team['teams'][$p_team['t_id']]['b'] > $p_team['teams'][$c_team['t_id']]['b']) {
                    $equel_check = 1;
                } elseif($c_team['teams'][$p_team['t_id']]['b'] < $p_team['teams'][$c_team['t_id']]['b']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
            case 'w_p_v': // разница количества побед в личных встречах
                if ($c_team['teams'][$p_team['t_id']]['win'] > $p_team['teams'][$c_team['t_id']]['win']) {
                    $equel_check = 1;
                } elseif($c_team['teams'][$p_team['t_id']]['win'] < $p_team['teams'][$c_team['t_id']]['win']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
            case 'p_p_v': // разница игровых очнов в личных встречах
                if ($c_team['teams'][$p_team['t_id']]['zp_p'] > $p_team['teams'][$c_team['t_id']]['zp_p']) {
                    $equel_check = 1;
                } elseif($c_team['teams'][$p_team['t_id']]['zp_p'] < $p_team['teams'][$c_team['t_id']]['zp_p']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
            case 't_p_v': // разница попыток в личных встречах
                if ($c_team['teams'][$p_team['t_id']]['zp_t'] > $p_team['teams'][$c_team['t_id']]['zp_t']) {
                    $equel_check = 1;
                } elseif($c_team['teams'][$p_team['t_id']]['zp_t'] < $p_team['teams'][$c_team['t_id']]['zp_t']) {
                    $equel_check = 2;
                } else {
                    $equel_check = 0;
                }
                break;
        }
        return $equel_check;
    }

    /**
     * get Round
     *
     * @param int $id
     * @return array|bool
     */
    private function _getRound($id = 0){
        $id = intval($id);
        if ($id<1) return false;
        $res = array(
            'last_round' => 0
        );
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","
				g_round
			"," (g_cp_id = '$id' OR g_cp_id_g = '$id')
				AND g_is_active = 'yes'
				AND g_owner_t_id > 0
				AND g_guest_t_id > 0
				GROUP BY g_round ORDER BY g_round ASC");
       /* if ($temp and count($temp)>1 and $temp[count($temp)-1]['g_round']>0){
            $res['type'] = 'round';
            for ($i=0; $i< count($temp); $i++){
                $res['title'][] = $temp[$i]['g_round'].' тур';
            }
        } else*///закомметирована сортировка по турам 
        {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."games","
					g_date_schedule
				"," (g_cp_id = '$id' OR g_cp_id_g = '$id')
					AND g_is_active = 'yes'
					AND g_owner_t_id > 0
					AND g_guest_t_id > 0
					GROUP BY g_date_schedule ORDER BY g_date_schedule ASC");
            if ($temp)
                $res['type'] = 'date';
            $m = array(
                "01" => "января",
                "02" => "февраля",
                "03" => "марта",
                "04" => "апреля",
                "05" => "мая",
                "06" => "июня",
                "07" => "июля",
                "08" => "августа",
                "09" => "сентября",
                "10" => "октября",
                "11" => "ноября",
                "12" => "декабря"
            );
            $date_prev = '';
            for ($i=0; $i< count($temp); $i++){
                $t = strtotime($temp[$i]['g_date_schedule']);
                $date_temp = date("d", $t)." ".$m[date("m", $t)];
                if ($i==0 or $date_temp !== $date_prev) {
                    $date_prev = $date_temp;
                    $res['title'][] = $date_temp;
                }
            }
        }
        return $res;
    }

    /**
     * get Games
     *
     * @param int $id
     * @param int $cp_id
     * @param string $round_type
     * @param $last_round
     * @return array|bool
     */
    private function _getGames($id = 0, $cp_id = 0, $round_type = 'round', &$last_round = null){ // Игры чемпионата (соревнования)
        global $month;
        global $month_i;
        global $wday;
        global $section_type;
        global $section_type_id;

        $id = intval($id);
        if ($id<1) return false;

        $games_table = array();

        if ($round_type == 'date') $q_round = 'g_date_schedule ASC,';
        else $q_round = 'g_round DESC,';

        $cp_id = intval($cp_id);
        $q_cp = '';
        if ($cp_id>0) $q_cp = " AND (g_cp_id = '$cp_id' OR g_cp_id_g = '$cp_id')";
        else {
            $game_item = $this->hdl->selectElem(DB_T_PREFIX."games","
					g_id,
					g_cp_id,
					g_cp_id_g
					","
					g_ch_id = '$id'
					AND g_owner_t_id > 0
					AND g_guest_t_id > 0
					AND g_is_active = 'yes'
					ORDER BY
					g_is_done DESC,
					g_cp_id ASC,
					g_cp_id_g ASC,
					g_owner_t_id ASC,
					g_guest_t_id ASC
					LIMIT 1");
            $q_cp = " AND (g_cp_id = '".$game_item[0]['g_cp_id']."' OR
                g_cp_id_g = '".$game_item[0]['g_cp_id']."')";
        }

        $games = $this->hdl->selectElem(DB_T_PREFIX."games g
                INNER JOIN ".DB_T_PREFIX."competitions c ON g.g_cp_id=c.cp_id
                LEFT JOIN ".DB_T_PREFIX."games_actions ga ON ga.ga_g_id=g.g_id
                ","
				g_id,
				g_cp_id,
				g_cp_id_g,
				g_owner_t_id,
				g_guest_t_id,
				g_owner_t_comment,
				g_guest_t_comment,
				g_owner_points,
				g_guest_points,
				g_owner_tehwin,
				g_guest_tehwin,
				g_is_done,
				g_date_schedule,
				g_is_schedule_time,
				g_round,
				g_info,
				cp_title_".S_LANG." AS title,
				ga_id
				","
				g_is_active = 'yes'
				$q_cp
				GROUP BY g_id
				ORDER BY $q_round
				g_is_done DESC,
				g_cp_id ASC,
				g_cp_id_g ASC
				");

        if ($games){
            // выбрать каждую команду в заданном чемпионате
            $temp = $this->hdl->selectElem(DB_T_PREFIX."team, ".DB_T_PREFIX."connection_t_ch","t_std_id, t_id, t_title_".S_LANG." AS title, t_is_detailed", "cntch_t_id = t_id AND cntch_ch_id = '$id' AND t_is_delete = 'no' ORDER BY t_id ASC");
            if ($temp)
                foreach ($temp as $item){
                    $item['title'] = stripcslashes($item['title']);
                    $item['photo_main'] = $this->_getTeamsPhotoMain($item['t_id']);
                    $team[$item['t_id']] = $item;
                }
            $temp = $this->hdl->selectElem(DB_T_PREFIX."stadium, ".DB_T_PREFIX."country, ".DB_T_PREFIX."city","ct_title_".S_LANG." AS city, cn_title_".S_LANG." AS country, std_id, std_title_".S_LANG." AS title", "std_cn_id = cn_id AND std_ct_id = ct_id ORDER BY std_order DESC");
            if ($temp)
                foreach ($temp as $item){
                    $item['city'] = stripcslashes($item['city']);
                    $item['country'] = stripcslashes($item['country']);
                    $item['title'] = stripcslashes($item['title']);
                    $stadium[$item['std_id']] = $item;
                }

            // вычисляем последнюю сыгранную игру
            if ($games) foreach ($games as $item) if ($item['g_is_done'] == 'yes') $last_game = $item['g_id'];

            // проход по играм и заполняем инфу
            $t_d_prev = $r = 0;
            $c_games = count($games);
            for ($i=0; $i < $c_games; $i++){
                $games[$i]['g_info'] = json_decode($games[$i]['g_info']);
                // is detailed report
                $games[$i]['is_detailed'] = (!is_null($games[$i]['ga_id']))?true:false;
                // название соревнования
                $games[$i]['title'] = stripcslashes($games[$i]['title']);
                // название команд
                if ($games[$i]['g_owner_t_id']>0) $games[$i]['owner'] = $team[$games[$i]['g_owner_t_id']];
                else $games[$i]['owner'] = $this->_getTitleTeamComment($games[$i]['g_owner_t_comment']);
                if ($games[$i]['g_guest_t_id']>0) $games[$i]['guest'] = $team[$games[$i]['g_guest_t_id']];
                else $games[$i]['guest'] = $this->_getTitleTeamComment($games[$i]['g_guest_t_comment']);
                // стадионы
                if ($team[$games[$i]['g_owner_t_id']]['t_std_id']) $games[$i]['stadium'] = $stadium[$team[$games[$i]['g_owner_t_id']]['t_std_id']];
                // для разделения по страницам - турам
                if ($round_type == 'date') {
                    $t_d = date("d_m_Y", strtotime($games[$i]['g_date_schedule']));
                } elseif ($round_type == 'round') {
                    $t_d = $games[$i]['g_round'];
                }
                if ($i==0) $t_d_prev = $t_d;
                elseif ($t_d !== $t_d_prev) {
                    $r++;
                    $t_d_prev = $t_d;
                }
                $games[$i]['round'] = $r;
                // вычисляем последний сыграный тур
                if (!empty($last_game) && $games[$i]['g_id'] == $last_game) {
                    $games[$i]['last'] = true;
                    $last_round = $r;
                } elseif (empty($last_game) && $i==0) {
                    $games[$i]['last'] = true;
                    $last_round = $r;
                } else {
                    $games[$i]['last'] = false;
                }
                // таблица с играми
                $key = date("Y-m", strtotime($games[$i]['g_date_schedule']));
                $d_key = date("d", strtotime($games[$i]['g_date_schedule']));
                $games_table[$key]['caption'] = $month_i[date("m", strtotime($games[$i]['g_date_schedule']))].' '.date("Y", strtotime($games[$i]['g_date_schedule']));
                $games_table[$key]['data'][$d_key]['caption'] = $wday[date("N", strtotime($games[$i]['g_date_schedule']))].'. '.date("j", strtotime($games[$i]['g_date_schedule']));

                $games_table[$key]['data'][$d_key]['data'][] = $games[$i];
            }
        }

        return $games_table;
    }

    /**
     * get Table for tour item
     *
     * @param int $id
     * @param int $tour_id
     * @return bool
     */
    private function _getStandingsTourTeam($id = 0, $tour_id = 0){ // турнирная таблица ДЛЯ ТУРА Регби 7
        $tour_id = intval($tour_id);
        $id = intval($id);
        if ($id<1) return false;

        // чемпионат (нужен для очков)
        $championship = $this->hdl->selectElem(DB_T_PREFIX."championship"," ch_id, ch_p_win, ch_p_draw, ch_p_loss, ch_p_bonus_1, ch_p_bonus_2, ch_p_bonus_2_diff, ch_p_tehwin, ch_settings"," ch_id = '$id' LIMIT 1");
        if (!$championship) return false;
        $championship = $championship[0];
        $championship['ch_settings'] = unserialize($this->_fix_serialized_string($championship['ch_settings']));
        $tour_points = empty($championship['ch_settings']['tourPoints']) ? false : true;
        //выборка команд тура
        $team = $this->hdl->selectElem(DB_T_PREFIX."connection_t_ch, ".DB_T_PREFIX."team, ".DB_T_PREFIX."games, ".DB_T_PREFIX."competitions","
					DISTINCT t_title_".S_LANG." AS title,
					t_id, cp_tour, t_is_technical, cntch_is_technical, t_is_detailed
				","
					cntch_is_delete='no' AND
					cntch_ch_id = '$id' AND
					cntch_t_id = t_id AND
					g_cp_id = cp_id AND
					cp_tour = '$tour_id' AND
					( g_owner_t_id = t_id OR g_guest_t_id = t_id)
					ORDER BY
					t_id ASC");
        //выборка соревнований тура
        $comp_done = $this->hdl->selectElem(DB_T_PREFIX."games, ".DB_T_PREFIX."competitions","
				DISTINCT cp_id
				","
				g_ch_id = '$id' AND
				g_cp_id = cp_id AND
				cp_tour = '$tour_id' AND
				g_is_done = 'yes' AND
				g_is_active = 'yes'
				ORDER BY
				cp_substage DESC,
				cp_order DESC,
				cp_title_".S_LANG." ASC");
        $comp = $this->hdl->selectElem(DB_T_PREFIX."competitions","
				cp_id,
				cp_is_rating_table
				","
				cp_ch_id = '$id' AND
				cp_tour = '$tour_id'
				ORDER BY
				cp_substage DESC,
				cp_order DESC,
				cp_title_".S_LANG." ASC");
        if (count($comp_done) != count($comp)) return false; // полностью ли закончен тур?
//        if ($comp)
//            foreach ($comp as $item) $comp_ar[$item['cp_id']] = 0;
        //выборка игр тура
        $games = $this->hdl->selectElem(DB_T_PREFIX."games, ".DB_T_PREFIX."competitions","
				DISTINCT g_id,
				g_ch_id,
				g_cp_id,
				g_owner_t_id,
				g_guest_t_id,
				g_owner_points,
				g_guest_points,
				g_owner_bonus_1,
				g_owner_tehwin
				","
				g_ch_id = '$id' AND
				g_cp_id = cp_id AND
				cp_tour = '$tour_id' AND
				g_is_done = 'yes' AND
				g_is_active = 'yes'
				ORDER BY
				g_cp_id ASC,
				g_owner_t_id ASC,
				g_guest_t_id ASC");

        $place = 1;
        for($i=0; $i<count($comp); $i++){
            if ($place < count($team)){
                if ($comp[$i]['cp_is_rating_table'] == 'no') {
                    $temp_t = array();
                    foreach ($games as $item) {
                        if ($item['g_cp_id'] == $comp[$i]['cp_id']) {
                            if ($item['g_owner_points'] > $item['g_guest_points']) {
                                if (isset($temp_t[$item['g_owner_t_id']])) {
                                    $temp_t[$item['g_owner_t_id']] = $temp_t[$item['g_owner_t_id']]+1;
                                } else {
                                    $temp_t[$item['g_owner_t_id']] = 1;
                                }
                                if (!isset($temp_t[$item['g_guest_t_id']])) {
                                    $temp_t[$item['g_guest_t_id']] = 0;
                                }
                            }
                            if ($item['g_owner_points'] < $item['g_guest_points']) {
                                if (isset($temp_t[$item['g_guest_t_id']])) {
                                    $temp_t[$item['g_guest_t_id']] = $temp_t[$item['g_guest_t_id']]+1;
                                } else {
                                    $temp_t[$item['g_guest_t_id']] = 1;
                                }
                                if (!isset($temp_t[$item['g_owner_t_id']])) {
                                    $temp_t[$item['g_owner_t_id']] = 0;
                                }
                            }

                        }

                    }
                    // sorting
                    arsort($temp_t);
                }
                if ($comp[$i]['cp_is_rating_table'] == 'yes') {
                    $temp_t = array();
                    foreach ($games as $item) {
                        if ($item['g_cp_id'] == $comp[$i]['cp_id']) {
                            // про хозяев
                            if (!isset($temp_t[$item['g_owner_t_id']])) {
                                $temp_t[$item['g_owner_t_id']] = array(
                                    't_id'=> $item['g_owner_t_id'],
                                    'p_scored' => 0,
                                    'p_missed' => 0,
                                    'games' => 0,
                                    'win' => 0,
                                    'loss' => 0,
                                    'p' => 0
                                );
                            }
                            if (empty($temp_t[$i]['p'])){
                                $temp_t[$i]['p'] = 0;
                            }
                            $temp_t[$item['g_owner_t_id']]['p_scored'] = $temp_t[$item['g_owner_t_id']]['p_scored'] + $item['g_owner_points'];
                            $temp_t[$item['g_owner_t_id']]['p_missed'] = $temp_t[$item['g_owner_t_id']]['p_missed'] + $item['g_guest_points'];
                            $temp_t[$item['g_owner_t_id']]['games']++;

                            if ($item['g_owner_points'] > $item['g_guest_points']) {
                                $temp_t[$item['g_owner_t_id']]['win']++;
                                $temp_t[$item['g_owner_t_id']]['p'] = $temp_t[$item['g_owner_t_id']]['p']+$championship['ch_p_win'];
                            }
                            if ($item['g_owner_points'] < $item['g_guest_points']) {
                                $temp_t[$item['g_owner_t_id']]['loss']++;
                                $temp_t[$item['g_owner_t_id']]['p'] = $temp_t[$item['g_owner_t_id']]['p']+$championship['ch_p_loss'];
                                // бонус <= N очков проигрыш
                                $bonus_2_diff = !empty($championship['ch_p_bonus_2_diff']) ? intval($championship['ch_p_bonus_2_diff']) : 7;
                                if (($item['g_guest_points'] - $item['g_owner_points']) <= $bonus_2_diff) $temp_t[$item['g_owner_t_id']]['p'] = $temp_t[$item['g_owner_t_id']]['p'] + $championship['ch_p_bonus_2'];
                            }
                            if ($item['g_owner_points'] == $item['g_guest_points']) {
                                if (!empty($temp_t[$item['g_owner_t_id']]['draw'])){
                                    $temp_t[$item['g_owner_t_id']]['draw']++;
                                } else {
                                    $temp_t[$item['g_owner_t_id']]['draw'] = 1;
                                }
                                $temp_t[$item['g_owner_t_id']]['p'] = $temp_t[$i]['p']+$championship['ch_p_draw'];
                            }
                            if ($item['g_owner_bonus_1'] == 'yes') $temp_t[$item['g_owner_t_id']]['p'] = $temp_t[$item['g_owner_t_id']]['p'] + $championship['ch_p_bonus_1'];
                            if ($item['g_owner_tehwin'] == 'yes') $temp_t[$item['g_owner_t_id']]['p'] = $temp_t[$item['g_owner_t_id']]['p'] + $championship['ch_p_tehwin'];

                            // про гостей
                            if (!isset($temp_t[$item['g_guest_t_id']])) {
                                $temp_t[$item['g_guest_t_id']] = array(
                                    't_id'=> $item['g_guest_t_id'],
                                    'p_scored' => 0,
                                    'p_missed' => 0,
                                    'games' => 0,
                                    'win' => 0,
                                    'loss' => 0,
                                    'p' => 0
                                );
                            }

                            $temp_t[$item['g_guest_t_id']]['p_scored'] = $temp_t[$item['g_guest_t_id']]['p_scored'] + $item['g_guest_points'];
                            $temp_t[$item['g_guest_t_id']]['p_missed'] = $temp_t[$item['g_guest_t_id']]['p_missed'] + $item['g_owner_points'];
                            $temp_t[$item['g_guest_t_id']]['games']++;

                            if ($item['g_guest_points'] > $item['g_owner_points']) {
                                $temp_t[$item['g_guest_t_id']]['win']++;
                                $temp_t[$item['g_guest_t_id']]['p'] = $temp_t[$item['g_guest_t_id']]['p']+$championship['ch_p_win'];
                            }
                            if ($item['g_guest_points'] < $item['g_owner_points']) {
                                $temp_t[$item['g_guest_t_id']]['loss']++;
                                $temp_t[$item['g_guest_t_id']]['p'] = $temp_t[$item['g_guest_t_id']]['p']+$championship['ch_p_loss'];
                                // бонус <= N очков проигрыш
                                $bonus_2_diff = !empty($championship['ch_p_bonus_2_diff']) ? intval($championship['ch_p_bonus_2_diff']) : 7;
                                if (($item['g_owner_points'] - $item['g_guest_points']) <= $bonus_2_diff) $temp_t[$item['g_guest_t_id']]['p'] = $temp_t[$item['g_guest_t_id']]['p'] + $championship['ch_p_bonus_2'];
                            }
                            if ($item['g_guest_points'] == $item['g_owner_points']) {
                                if (!empty($temp_t[$item['g_guest_t_id']]['draw'])){
                                    $temp_t[$item['g_guest_t_id']]['draw']++;
                                } else {
                                    $temp_t[$item['g_guest_t_id']]['draw'] = 1;
                                }
                                $temp_t[$item['g_guest_t_id']]['p'] = $temp_t[$i]['p']+$championship['ch_p_draw'];
                            }
                            if (!empty($item['g_guest_bonus_1']) && $item['g_guest_bonus_1'] == 'yes') $temp_t[$item['g_guest_t_id']]['p'] = $temp_t[$item['g_guest_t_id']]['p'] + $championship['ch_p_bonus_1'];
                            if (!empty($item['g_guest_tehwin']) && $item['g_guest_tehwin'] == 'yes') $temp_t[$item['g_guest_t_id']]['p'] = $temp_t[$item['g_guest_t_id']]['p'] + $championship['ch_p_tehwin'];
                        }
                    }
                    // sorting
                    $p_team = array();
                    foreach ($temp_t as $item) $team_s[] = $item;
                    for ($i=1; $i<count($team_s); $i++){
                        for ($j=0; $j<count($team_s); $j++){
                            if ($j>0) {
                                if ($team_s[$j]['p'] > $p_team['p']) {
                                    $team_s[$j-1] = $team_s[$j];
                                    $team_s[$j] = $p_team;
                                } elseif ($team_s[$j]['p'] < $p_team['p']){
                                    $p_team = $team_s[$j];
                                } else { // + дополнительная сортировка по РИО
                                    if (empty($team_s[$j]['p_scored'])) {
                                        $team_s[$j]['p_scored'] = 0;
                                    }
                                    if (empty($p_team['p_scored'])) {
                                        $p_team['p_scored'] = 0;
                                    }
                                    if ($team_s[$j]['p_scored'] > $p_team['p_scored']) { // если число забитых больше - то наверх
                                        $team_s[$j-1] = $team_s[$j];
                                        $team_s[$j] = $p_team;
                                    } elseif($team_s[$j]['p_scored'] < $p_team['p_scored']) $p_team = $team_s[$j];
                                    else {
                                        if ($team_s[$j]['p_missed'] < $p_team['p_missed']) { // если число пропущеных меньше - то наверх
                                            $team_s[$j-1] = $team_s[$j];
                                            $team_s[$j] = $p_team;
                                        } elseif($team_s[$j]['p_missed'] > $p_team['p_missed']) $p_team = $team_s[$j];
                                        else $p_team = $team_s[$j]; // добавить выборку и просмотр личных встреч: выигрыш - выше проигрыша (возможно отдельная функция)
                                    }
                                }
                            } else {
                                $p_team = $team_s[$j];
                            }
                        }
                    }
                    $temp_t = array();
                    foreach ($team_s as $item) {
                        if (isset($item['t_id'])) {
                            $temp_t[$item['t_id']] = $item;
                        }
                    }
                }

                if (!empty($temp_t)) {
                    $c_team = count($team);
                    foreach ($temp_t as $key=>$item){
                        for($j=0; $j<$c_team; $j++){
                            if ($team[$j]['cntch_is_technical'] == 'yes'){
                                $team[$j]['place'] = '';
                            } else{
                                if ($team[$j]['t_id'] == $key AND !isset($team[$j]['place'])) {
                                    $team[$j]['place'] = $place;
                                    if (empty($championship['ch_settings']['tour_team_is_points'][$tour_id][$team[$j]['t_id']])) {
                                        $team[$j]['p'] = $tour_points ? $championship['ch_settings']['tourPoints'][$place-1] : $place;
                                    } else {
                                        $team[$j]['p'] = 0;
                                    }
                                    $place++;
                                }
                            }
                        }
                    }
                }
            }
        }
        $c_team = count($team);
        for($j=0; $j<$c_team; $j++){
            if ($team[$j]['cntch_is_technical'] == 'no' && empty($team[$j]['place']) && empty($team[$j]['p'])){
                $team[$j]['place'] = $place;
                if (empty($championship['ch_settings']['tour_team_is_points'][$tour_id][$team[$j]['t_id']])) {
                    $team[$j]['p'] = $tour_points ? $championship['ch_settings']['tourPoints'][$place-1] : $place;
                } else {
                    $team[$j]['p'] = 0;
                }
                $place++;
            }
        }
        if (count($team)>0){
            for($j=0; $j<=count($team); $j++){
                for($i=0; $i<count($team); $i++){
                    if ($j == $team[$i]['place']) $res[$j] = $team[$i];
                }
            }
        } else $res = false;
        return $res;
    }

    /**
     * get Table for championat item
     *
     * @param int $id
     * @return array|bool
     */
    public function _getStandingsChampTeam($id = 0){ // турнирная таблица ДЛЯ ЧЕМПИОНАТА Регби 7
        $id = intval($id);
        if ($id<1) return false;

        // чемпионат (нужен для очков)
        $championship = $this->hdl->selectElem(DB_T_PREFIX."championship",
            "   ch_id,
                ch_p_win,
                ch_p_draw,
                ch_p_loss,
                ch_p_bonus_1,
                ch_p_bonus_2,
                ch_p_bonus_2_diff,
                ch_p_tehwin,
                ch_tours,
                ch_settings
            ","
                ch_id = '$id'
                LIMIT 1");
        if (!$championship) return false;
        $championship = $championship[0];
        $championship['ch_settings'] = unserialize($this->_fix_serialized_string($championship['ch_settings']));
        if ($championship['ch_settings']['tours_points_order']){
            $sort_order = 'asc';
        } else {
            $sort_order = 'desc';
        }
        $team = array();
        for($i=0; $i<=$championship['ch_tours']; $i++){
            $temp = $this->_getStandingsTourTeam($id, $i);
            if ($temp)
                foreach ($temp as $item){
                    if (isset($team[$item['t_id']])) {
                        $team[$item['t_id']]['p'] = intval($team[$item['t_id']]['p']+$item['p']);
                    } else {
                        $team[$item['t_id']] = $item;
                        $team[$item['t_id']]['p'] = intval($item['p']);
                    }
                    $team[$item['t_id']]['p_tour'][$i] = $item['p'];
                }
        }
        foreach ($team as $item) $res[] = $item;

        if (!empty($res)){
            $p_team = array();
            $c_res = count($res);
            for($i=1; $i<=$c_res; $i++){
                for($j=0; $j<$c_res; $j++){
                    if ($j>0) {
                        if ($sort_order == 'desc'){ // reverse sort from highest to lowest
                            if ($res[$j]['p'] > $p_team['p']) {
                                $res[$j-1] = $res[$j];
                                $res[$j] = $p_team;
                            } elseif ($res[$j]['p'] < $p_team['p']){
                                $p_team = $res[$j];
                            } else {
                                if (!isset($p[$res[$j]['t_id']][$p_team['t_id']])) {
                                    $p[$res[$j]['t_id']][$p_team['t_id']] = $this->_getPersonalMeetings($res[$j]['t_id'], $p_team['t_id'], $id);
                                }
                                if ($p[$res[$j]['t_id']][$p_team['t_id']] == 2) {
                                    $res[$j-1] = $res[$j];
                                    $res[$j] = $p_team;
                                } else $p_team = $res[$j];
                            }
                        } else {
                            if ($res[$j]['p'] < $p_team['p']) {
                                $res[$j-1] = $res[$j];
                                $res[$j] = $p_team;
                            } elseif ($res[$j]['p'] > $p_team['p']){
                                $p_team = $res[$j];
                            } else {
                                if (!isset($p[$res[$j]['t_id']][$p_team['t_id']])) {
                                    $p[$res[$j]['t_id']][$p_team['t_id']] = $this->_getPersonalMeetings($res[$j]['t_id'], $p_team['t_id'], $id);
                                }
                                if ($p[$res[$j]['t_id']][$p_team['t_id']] == 2) {
                                    $res[$j-1] = $res[$j];
                                    $res[$j] = $p_team;
                                } else $p_team = $res[$j];
                            }
                        }
                    } else {
                        $p_team = $res[$j];
                    }
                }
            }
            return $res;
        }
        return false;
    }

    private function _getPersonalMeetings($f_t = 0, $s_t = 0, $id = 0){
        $id = intval($id);
        $f_t = intval($f_t);
        $s_t = intval($s_t);
        if ($id<1 or $f_t<1 or $s_t<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."games","
				g_owner_t_id,
				g_guest_t_id,
				g_owner_points,
				g_guest_points
				","
				g_ch_id = '$id' AND
				(( g_guest_t_id = '$f_t' AND g_owner_t_id = '$s_t' ) OR ( g_owner_t_id = '$f_t' AND g_guest_t_id = '$s_t')) AND
				g_is_done = 'yes' AND
				g_is_active = 'yes'");
        $f_t_p = $s_t_p = 0;
        if ($temp){
            $f_t_p_score = $f_t_p_missed = $s_t_p_score = $s_t_p_missed = 0;
            foreach ($temp as $item){
                if ($item['g_owner_points'] > $item['g_guest_points']) {
                    if ($item['g_owner_t_id'] == $f_t) {
                        $f_t_p = $f_t_p + 2;
                        $f_t_p_score = $f_t_p_score + $item['g_owner_points'];
                        $f_t_p_missed = $f_t_p_missed + $item['g_guest_points'];
                    }
                    if ($item['g_owner_t_id'] == $s_t) {
                        $s_t_p = $s_t_p + 2;
                        $s_t_p_score = $s_t_p_score + $item['g_owner_points'];
                        $s_t_p_missed = $s_t_p_missed + $item['g_guest_points'];
                    }
                } elseif ($item['g_guest_points'] > $item['g_owner_points']) {
                    if ($item['g_guest_t_id'] == $f_t) {
                        $f_t_p = $f_t_p + 2;
                        $f_t_p_score = $f_t_p_score + $item['g_guest_points'];
                        $f_t_p_missed = $f_t_p_missed + $item['g_owner_points'];
                    }
                    if ($item['g_guest_t_id'] == $s_t) {
                        $s_t_p = $s_t_p + 2;
                        $s_t_p_score = $s_t_p_score + $item['g_guest_points'];
                        $s_t_p_missed = $s_t_p_missed + $item['g_owner_points'];
                    }
                }
            }
            if ($f_t_p>$s_t_p) return '2';
            elseif ($f_t_p<$s_t_p) return '1';
            else {
                $f_t_p_dif = $f_t_p_score - $f_t_p_missed;
                $s_t_p_dif = $s_t_p_score - $s_t_p_missed;
                if ($f_t_p_dif>$s_t_p_dif) return '2';
                elseif ($f_t_p_dif<$s_t_p_dif) return '1';
                return '0';
            }
        } else return false;
    }

    private function _getTeamsPhotoMain($id){
        $id = intval($id);
        $temp_photo = $this->hdl->selectElem(DB_T_PREFIX."photos",
            "	ph_id,
                ph_path,
                ph_about_".S_LANG." as ph_about,
                ph_title_".S_LANG." as ph_title,
											ph_folder,
											ph_gallery_id
										","	ph_is_active='yes' AND
											ph_type_id = '".$id."' AND
											ph_type = 'team'
											ORDER BY ph_type_main DESC
											LIMIT 1");
        if ($temp_photo){
            $temp_photo = $temp_photo[0];
            $temp_photo['ph_title'] = strip_tags(stripcslashes($temp_photo['ph_title']));
            $temp_photo['ph_about'] = strip_tags(stripcslashes($temp_photo['ph_about']));
            //$temp_photo['ph_main'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-s_main".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_small'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-small".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_informer'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-informer".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_med'] = $temp_photo['ph_folder'].substr($temp_photo['ph_path'], 0, strlen(strrchr($temp_photo['ph_path'], "."))*(-1))."-med".strrchr($temp_photo['ph_path'], ".");
            $temp_photo['ph_big'] = $temp_photo['ph_folder'].$temp_photo['ph_path'];
            return $temp_photo;
        }
        return false;
    }

    private function _getTitleTeamComment($comment = ''){ // "название" команды по комментарию, если нет явной команды - 1,2,3... место в другом соревновании
        $comment = trim(strip_tags(stripslashes($comment)));

        if ($comment == '') return false;
        list($plase, $comp, $rest) = explode ( '-', $comment, 3);
        if ($plase<1 OR $comp<1) return false;
        $plase = intval($plase);
        $comp = intval($comp);
        $title = $plase." место. ";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."competitions","cp_title_".S_LANG." AS title, cp_substage","cp_id='$comp' LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $title .= stripcslashes($temp['title']);
        }
        $item['title'] = $title;
        $item['photo_main'] = false;
        $item['t_id'] = 0;
        return $item;
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