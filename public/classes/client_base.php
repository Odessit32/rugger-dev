<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class clientBase {
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
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
                c.cn_title_".S_LANG." as title,
                c.cn_address as address",
            "   cc.type_id=$item and
                cc.type='$type' and
                cc.cn_id = c.cn_id");
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
                c.title_".S_LANG." as title,
                c.address",
            "   cc.type_id=$item and
                cc.type='$type' and
                cc.ch_id = c.id");
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

    public function getSection($id = 0, $type = ''){
        $id = intval($id);
        switch($type) {
            case 'country':
                $type = 'country';
                break;
            case 'championship':
                $type = 'championship';
                break;
            default:
                return false;
                break;
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra pe
                    LEFT JOIN ".DB_T_PREFIX."pages p ON p.p_id=pe.pe_p_id","
                        p_id,
                        p_title_".S_LANG." as title,
                        p_adress as address,
                        p_is_active
                    ","
                        pe_item_id = $id AND
                        pe_item_type = '$type' AND
                        p_is_delete = 'no'
                        LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            $temp['title'] = stripslashes($temp['title']);
        }
        return $temp;
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
                st.st_name_".S_LANG." as name,
                st.st_family_".S_LANG." as family,
                st.st_surname_".S_LANG." as surname,
                st.st_address as address
            ",
            "   cst.type_id=$item and
                cst.type='$type' and
                st.st_is_active = 'yes'");
        if ($staff_list){
            foreach ($staff_list as &$st_item){
                $st_item['fio'] = stripslashes($st_item['name']).' '.
                    ((!empty($st_item['surname']))? stripslashes($st_item['surname']).' ':'').
                    stripslashes($st_item['family']);
            }
        }
        return $staff_list;
    }

    public function getConnectionNews($item = 0, $is_gallery = false, $type = ''){
        $item = intval($item);
        if (empty($type)) {
            return false;
        }
        $type = ($is_gallery) ? $type.'_gallery' : $type;
        $news_list = $this->hdl->selectElem(DB_T_PREFIX."connection_game cg
                LEFT JOIN ".DB_T_PREFIX."news n ON cg.type_id = n.n_id
            ",
            "   n.n_id as id,
                n.n_title_".S_LANG." as title,
                n.n_description_".S_LANG." as description,
                n.n_date_show as date_show,
                n.n_nc_id as nc_id
                ",
            "   cg.g_id=$item and
                cg.type='$type'");
        if ($news_list){
            foreach ($news_list as &$nl_item){
                $nl_item['title'] = stripslashes($nl_item['title']);
                $nl_item['description'] = stripslashes($nl_item['description']);
            }
        }
        return $news_list;
    }

}