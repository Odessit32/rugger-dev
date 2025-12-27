<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class url {
    private $hdl;

    public $pages;
    public $page;
    public $type;
    public $module;
    public $page_path = '';
    public $rest_path = [];
    public $menu;
    public $is_submenu = false;
    public $array_menu = false;
    public $isSection = false;
    public $section = array();
    public $isSectionHome = false;
    public $sectionId = 0;
    public $menu_sections = array();

    public $lang = false;
    public $sLang = 'ua';
    public $htmlLang = 'ua-UA';

    private $m_ids = array();

    public function __construct() {
        $this->hdl = database::getInstance();
    }

    public function getURL($path='') {
        $path_a = $this->getPath($path);
        if (count($path_a) == 0) { // Строка 242
            $this->type = 'main';
            $menu = $this->hdl->selectElem(DB_T_PREFIX."pages",
                "	p_id,
                        p_parent_id, 
                        p_adress, 
                        p_title_{$this->sLang} as title",
                "	p_parent_id='0' AND
                    p_is_menu = 'yes' AND
                    p_is_delete = 'no' AND
                    p_is_active = 'yes'
                    ORDER BY p_order DESC");
            if ($menu) {
                for ($j = 0; $j < count($menu); $j++) {
                    $menu[$j]['page_path'] = "/";
                    $menu[$j]['title'] = stripslashes($menu[$j]['title']);
                    $menu[$j]['alt'] = strip_tags($menu[$j]['title']);
                }
                $this->page['menu'] = $menu;
            }
        } else {
            $parent_id = 0;
            for ($i = 0; $i < count($path_a); $i++) {
                if ($this->isSectionHome) {
                    $this->isSectionHome = false;
                }
                if ($i == (count($path_a) - 1)) {
                    $page = $this->hdl->selectElem(DB_T_PREFIX."pages",
                        "	p_id,
                            p_parent_id,
                            p_mod_id,
                            p_adress,
                            p_title_".$this->sLang." as title,
                            p_text_".$this->sLang." as text, 
                            p_description_".$this->sLang." as description, 
                            p_title_files, 
                            p_is_title, 
                            p_is_description, 
                            p_is_text, 
                            p_is_files, 
                            p_is_first_subpage, 
                            p_is_scrol,
                            p_is_socials",
                        "	p_parent_id='$parent_id' AND
                            p_adress='$path_a[$i]' AND 
                            p_is_delete = 'no' AND 
                            p_is_active = 'yes' 
                            LIMIT 1");
                    if ($page && is_array($page) && isset($page[0]) && $page[0]['p_id'] > 0) {
                        $page = $page[0];
                        $this->type = 'page';
                        $page['page_path'] = $this->page_path;

                        if ($page['p_is_first_subpage'] == 'yes' && $page['p_mod_id'] == 0) {
                            $flag_stop = true;
                            while ($flag_stop) {
                                $this->page = $page;
                                $parent_id = $page['p_id'];
                                $page = $this->hdl->selectElem(DB_T_PREFIX."pages",
                                    "	p_id,
                                        p_parent_id,
                                        p_mod_id,
                                        p_adress,
                                        p_title_".$this->sLang." as title,
                                        p_text_".$this->sLang." as text, 
                                        p_description_".$this->sLang." as description, 
                                        p_title_files, 
                                        p_is_title, 
                                        p_is_description, 
                                        p_is_text, 
                                        p_is_files, 
                                        p_is_first_subpage, 
                                        p_is_scrol,
                                        p_is_socials",
                                    "	p_parent_id='$parent_id' AND
                                        p_is_delete = 'no' AND 
                                        p_is_active = 'yes' 
                                        ORDER BY p_order DESC LIMIT 1");
                                if ($page && is_array($page) && isset($page[0])) {
                                    $page = $page[0];
                                    if ($page['p_is_first_subpage'] == 'no' || $page['p_mod_id'] > 0) {
                                        $this->page_path .= $this->page['p_adress']."/";
                                        $page['page_path'] = $this->page_path;
                                        $this->pages[] = $this->page;
                                        $this->page = $page;
                                        $flag_stop = false;
                                    } else {
                                        $this->page_path .= $this->page['p_adress']."/";
                                        $page['page_path'] = $this->page_path;
                                        $this->pages[] = $this->page;
                                    }
                                } else {
                                    $flag_stop = false;
                                }
                            }
                        } else {
                            $this->page = $page;
                        }
                        if (in_array($page['p_mod_id'], array(100, 101))) {
                            $this->isSection = true;
                            $this->isSectionHome = true;
                            $this->sectionId = $page['p_mod_id'];
                            $this->section = $this->_getSectionInfo($page['p_id'], $page['p_mod_id']);
                            $this->section['page'] = $page;
                        }
                    } else {
                        $this->type = '404';
                    }
                } else {
                    $page = $this->hdl->selectElem(DB_T_PREFIX."pages",
                        "	p_id,
                            p_parent_id,
                            p_mod_id,
                            p_adress,
                            p_title_".$this->sLang." as title,
                            p_is_first_subpage,
                            p_text_".$this->sLang." as text, 
                            p_description_".$this->sLang." as description, 
                            p_is_title, 
                            p_is_description, 
                            p_is_text",
                        "	p_parent_id='$parent_id' AND
                            p_adress='$path_a[$i]' AND 
                            p_is_delete = 'no' AND 
                            p_is_active = 'yes' 
                            LIMIT 1");
                    if ($page && is_array($page) && isset($page[0]) && $page[0]['p_id'] > 0) {
                        $page = $page[0];
                        $page['page_path'] = $this->page_path;
                        if (!in_array($page['p_mod_id'], array(0, 100, 101))) {
                            $this->page = $page;
                            if ($i + 1 < count($path_a)) {
                                for ($j = $i + 1; $j < count($path_a); $j++) {
                                    $this->rest_path[] = $path_a[$j];
                                }
                            }
                            $this->type = 'page';
                            $i = count($path_a) + 1;
                        } else {
                            $this->page_path .= $page['p_adress']."/";
                            $parent_id = $page['p_id'];
                            $this->pages[] = $page;
                            if (in_array($page['p_mod_id'], array(100, 101))) {
                                $this->isSection = true;
                                $this->isSectionHome = true;
                                $this->sectionId = $page['p_mod_id'];
                                $this->section = $this->_getSectionInfo($page['p_id'], $page['p_mod_id']);
                                $this->section['page'] = $page;
                            }
                        }
                    } else {
                        $i = count($path_a) + 1;
                        $this->type = '404';
                    }
                }
            }
            if (isset($this->page['p_id']) && $this->page['p_id'] > 0) {
                $menu = $this->hdl->selectElem(DB_T_PREFIX."pages",
                    "	p_id,
                        p_parent_id, 
                        p_adress as adress, 
                        p_title_{$this->sLang} as title",
                    "	p_parent_id='".$this->page['p_id']."' AND
                        p_is_menu = 'yes' AND 
                        p_is_delete = 'no' AND 
                        p_is_active = 'yes' 
                        ORDER BY p_order DESC");
                if ($menu) {
                    for ($j = 0; $j < count($menu); $j++) {
                        $menu[$j]['page_path'] = $this->page['page_path'].$this->page['p_adress']."/";
                        $menu[$j]['title'] = stripslashes($menu[$j]['title']);
                    }
                    $this->page['menu'] = $menu;
                }
                $this->page['title'] = (!empty($this->page['title'])) ? strip_tags(stripslashes($this->page['title'])) : '';
                $this->page['description'] = (!empty($this->page['description'])) ? stripslashes($this->page['description']) : '';
                $this->page['description_meta'] = (!empty($this->page['description_meta'])) ? strip_tags($this->page['description']) : '';
                $this->page['text'] = (!empty($this->page['text'])) ? stripslashes($this->page['text']) : '';
                $search = array('<mce:script', '</mce:script>');
                $replace = array('<script', '</script>');
                $this->page['text'] = str_replace($search, $replace, $this->page['text']);
                $this->page['p_title_files'] = (!empty($this->page['p_title_files'])) ? stripslashes($this->page['p_title_files']) : '';
                $module = $this->hdl->selectElem(DB_T_PREFIX."modules",
                    "	mod_id,
                        mod_title,
                        mod_class,
                        mod_template",
                    "	mod_id='".$this->page['p_mod_id']."'
                        LIMIT 1");
                if ($module && is_array($module) && isset($module[0])) {
                    $this->module = $module[0];
                } else {
                    $this->type = '404';
                }
                if (isset($this->page['p_is_scrol']) && $this->page['p_is_scrol'] == 'yes') {
                    $this->page['scrol'] = $this->getPageList($this->page['p_id']);
                }
                if (isset($this->page['p_is_files']) && $this->page['p_is_files'] == 'yes') {
                    $this->page['files'] = $this->getPageFiles($this->page['p_id']);
                }
                $this->page['pvs'] = $this->getPVS($this->page['p_id']);
                if (!is_array($this->pages)) {
                    $this->pages = [];
                }
                if (count($this->pages) > 0) {
                    foreach ($this->pages as &$item) {
                        $item['title'] = strip_tags($item['title']);
                    }
                }
                if (!isset($this->page['p_c_banners'])) {
                    $this->page['p_c_banners'] = 0;
                }
            }
        }
        $this->getMenuAll();
        return null;
    }

    private function getPath($path='') {
        $temp_path = array();
        // Проверяем, что $path - строка, и приводим к пустой строке, если это не так
        if (!is_string($path)) {
            error_log("[DEBUG] getPath: \$path is not a string, value: " . print_r($path, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/url_debug.log");
            $path = '';
        }
        if (strlen($path) > 0) {
            $path = explode('/', $path);
            $i = 0;
            foreach ($path as $item) {
                $item = trim($item);
                if ($item !== '') {
                    if ($i == 0) {
                        // Проверяем, определена ли константа SITEPATH
                        $sitePath = defined('SITEPATH') ? SITEPATH : '';
                        if ($item !== $sitePath) {
                            if ($item == 'ukr') {
                                $this->lang = 'ukr';
                                $this->sLang = 'ua';
                                $this->htmlLang = 'ua-UA';
                            } elseif ($item == 'rus') {
                                $this->lang = 'rus';
                                $this->sLang = 'ru';
                                $this->htmlLang = 'ru-RU';
                            } elseif ($item == 'eng') {
                                $this->lang = 'eng';
                                $this->sLang = 'en';
                                $this->htmlLang = 'en-US';
                            } else {
                                $temp_path[] = $item;
                            }
                            $i++;
                        }
                    } else {
                        if (($i == 1 || $i == count($path) - 1) && !$this->lang) {
                            if ($item == 'ukr') {
                                $this->lang = 'ukr';
                                $this->sLang = 'ua';
                                $this->htmlLang = 'ua-UA';
                            } elseif ($item == 'rus') {
                                $this->lang = 'rus';
                                $this->sLang = 'ru';
                                $this->htmlLang = 'ru-RU';
                            } elseif ($item == 'eng') {
                                $this->lang = 'eng';
                                $this->sLang = 'en';
                                $this->htmlLang = 'en-US';
                            } else {
                                $temp_path[] = $item;
                            }
                        } else {
                            $temp_path[] = $item;
                        }
                        $i++;
                    }
                }
            }
            if (!$this->lang) {
                $this->lang = D_LANG;
                $this->sLang = D_S_LANG;
                $this->htmlLang = D_HTML_LANG;
            } else {
                if (!defined('LANG')) {
                    define('LANG', $this->lang);
                }
                if (!defined('S_LANG')) {
                    define('S_LANG', $this->sLang);
                }
            }
        }
        return $temp_path;
    }

    private function _getSectionInfo($page_id = 0, $type = 0) {
        $page_id = intval($page_id);
        $data = array();
        switch($type){
            case 100:
                $type_str = 'country';
                switch($this->sLang){
                    case 'ua':
                        $title_str = '';
                        break;
                    case 'ru':
                        $title_str = '';
                        break;
                    case 'en':
                        $title_str = 'countries';
                        break;
                }
                break;
            case 101:
                $type_str = 'championship';
                switch($this->sLang){
                    case 'ua':
                        $title_str = '';
                        break;
                    case 'ru':
                        $title_str = '';
                        break;
                    case 'en':
                        $title_str = 'tournaments';
                        break;
                }
                break;
            default:
                $type_str = '';
        }
        if ($page_id>0 && !empty($type_str)){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "   *",
                "   pe_p_id = $page_id
                        ");
            if ($temp){
                foreach ($temp as $item){
                    switch ($item['pe_item_type']) {
                        case 'championship_logo':
                            $data['logo'] = $item;
                            break;
                        case 'championship':
                            $item['title'] = $title_str;
                            $data['info'] = $item;
                            break;
                        case 'country_logo':
                            $data['logo'] = $item;
                            break;
                        case 'country':
                            $item['title'] = $title_str;
                            $data['info'] = $item;
                            break;
                    }
                }
            }
        }
        return $data;
    }

    private function _getMenuDropdown($page_id = 0, $is_dropdown = 'no', $path = '', $page_mod = 0, $is_archive = false){
        if ($page_id<1 || $is_dropdown !== 'yes') {
            return false;
        }
        $menu = [];
        if ($page_mod == 37){
            $f_c_extra = '';
            $q_c_extra = '';
            if (!empty($this->section['info'])){
                switch($this->section['info']['pe_item_type']) {
                    case 'country':
                        $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_country c ON chg.chg_id = c.type_id ";
                        $q_c_extra = " AND c.type = 'champ_group' AND c.cn_id = '".$this->section['info']['pe_item_id']."'";
                        break;
                    case 'championship':
                        $f_c_extra = " INNER JOIN ".DB_T_PREFIX."connection_champ c ON chg.chg_id = c.type_id";
                        $q_c_extra = " AND c.type = 'champ_group' AND c.ch_id = '".$this->section['info']['pe_item_id']."'";
                        break;
                }
            }
            if ($is_archive){
                $q_archive = " AND ch.ch_is_done = 'yes' ";
            } else {
                $q_archive = "";
            }

            $temp = $this->hdl->selectElem(DB_T_PREFIX."championship_group chg
                    LEFT JOIN rgr_championship ch ON ch.ch_chg_id = chg.chg_id
                    ".$f_c_extra,
                "	COUNT( * ) AS count_ch,
                    chg.chg_id as id,
                    chg.chg_title_".$this->sLang." as title,
                    chg.chg_address as address,
                    chg.chg_is_menu AS is_menu,
                    ch.ch_id,
                    ch.ch_address",
                "	chg.chg_is_active='yes' AND
                    chg.chg_is_menu='yes' AND
                    chg.chg_address != ''
                    $q_archive
                    $q_c_extra
                    GROUP BY chg.chg_id
                    ORDER BY chg.chg_is_main DESC,
                        chg.chg_order DESC,
                        ch.ch_order DESC");
            if ($page_id == 18){
            }
            if ($temp){
                foreach ($temp as $item_p){
                    if ($item_p['count_ch']>1){
                        $temp_ch = $this->hdl->selectElem(DB_T_PREFIX."championship_group chg
                                LEFT JOIN rgr_championship ch ON ch.ch_chg_id = chg.chg_id
                                ".$f_c_extra,
                            "	chg.chg_id as id,
                                chg.chg_title_".$this->sLang." as title,
                                chg.chg_address as address,
                                chg.chg_is_menu AS is_menu,
                                ch.ch_id,
                                ch.ch_address",
                            "	chg.chg_is_active='yes' AND
                                chg.chg_address != '' AND
                                chg.chg_id = '".$item_p['id']."'
                                $q_archive
                                $q_c_extra
                                ORDER BY
                                ch.ch_is_done ASC,
                                ch.ch_order DESC
                                LIMIT 1");
                        if ($temp_ch && is_array($temp_ch) && isset($temp_ch[0])) {
                            $temp_ch = $temp_ch[0];
                            $temp_ch['title'] = stripslashes($temp_ch['title']);
                            $temp_ch['address'] .= '/'.$temp_ch['ch_address'];
                            $temp_ch['path'] = $path;
                            $menu[] = $temp_ch;
                        }
                    } else {
                        $item_p['title'] = stripslashes($item_p['title']);
                        $item_p['address'] .= '/'.$item_p['ch_address'];
                        $item_p['path'] = $path;
                        $menu[] = $item_p;
                    }
                }
            }
        } else {
            $pages = $this->hdl->selectElem(DB_T_PREFIX."pages",
                "	p_id,
                    p_parent_id,
                    p_adress as address,
                    p_title_".$this->sLang." as title,
                    p_mod_id as mod_id,
                    p_is_menu_dropdown as is_dropdown",
                "	p_is_menu = 'yes' AND
                    p_is_delete = 'no' AND
                    p_is_active = 'yes' AND
                    p_title_".$this->sLang." != '' AND
                    p_parent_id = $page_id
                    ORDER BY p_order DESC");
            if ($pages){
                $menu = array();
                foreach ($pages as $item_p){
                    $item_p['title'] = stripslashes($item_p['title']);
                    $item_p['menu_dropdown'] = $this->_getMenuDropdown(
                        $item_p['p_id'],
                        $item_p['is_dropdown'],
                        $path.'/'.$item_p['address'],
                        $item_p['mod_id']
                    );
                    if (isset($this->page['p_id']) && $item_p['p_id'] == $this->page['p_id']) $item_p['active'] = 'yes';
                    else $item_p['active'] = 'no';
                    $item_p['path'] = $path;
                    $menu[] = $item_p;
                }
            }
        }
        return $menu;
    }

    private function getMenuAll() {
        $is_menu = true;
        $array_menu = array();
        $q_pages = " AND ( p_parent_id = '0'";
        if (is_array($this->pages) && !empty($this->pages)){
            foreach($this->pages as $item){
                $q_pages .= " OR p_parent_id = '".$item['p_id']."'";
            }
        }
        $q_pages .= (!empty($this->page['p_id'])) ? " OR p_parent_id = '".$this->page['p_id']."'" : '';
        $q_pages .= " )";

        $pages = array();
        $pages = $this->hdl->selectElem(DB_T_PREFIX."pages",
            "	p_id,
                p_parent_id,
                p_adress as adress,
                p_title_".$this->sLang." as title,
                p_mod_id as mod_id,
                p_is_menu_dropdown as is_dropdown",
            "	p_is_menu = 'yes' AND
                p_is_delete = 'no' AND
                p_is_active = 'yes' AND
                p_title_".$this->sLang." != ''
                $q_pages
                ORDER BY p_order DESC");
        if (!empty($pages)){
            $item_menu_section = array();
            if (!$this->pages) {
                $prnt_id = 0;
                $item_menu = array();
                foreach ($pages as $item_p){
                    if ($prnt_id == $item_p['p_parent_id']) {
                        $item_p['title'] = str_replace(' ', '&nbsp;', stripslashes($item_p['title']));
                        $item_p['menu_dropdown'] = $this->_getMenuDropdown($item_p['p_id'], $item_p['is_dropdown'], $item_p['adress'].'/', $item_p['mod_id']);
                        if (isset($this->page['p_id']) && $item_p['p_id'] == $this->page['p_id']) $item_p['active'] = 'yes';
                        else $item_p['active'] = 'no';
                        $item_p['path'] = '';
                        if (in_array($item_p['mod_id'], array(100, 101))){
                            $item_menu_section[$item_p['mod_id']][] = $item_p;
                        } else {
                            $item_menu[] = $item_p;
                        }
                    }
                }
                $menu[] = $item_menu;
            } else {
                $path = '';
                foreach($this->pages as $item){
                    $prnt_id = $item['p_parent_id'];
                    $item_menu = array();
                    foreach ($pages as $item_p){
                        if ($prnt_id == $item_p['p_parent_id']) {
                            $item_p['title'] = str_replace(' ', '&nbsp;', stripslashes($item_p['title']));
                            $item_p['menu_dropdown'] = $this->_getMenuDropdown($item_p['p_id'], $item_p['is_dropdown'], $path.$item_p['adress'].'/', $item_p['mod_id']);
                            if ($item_p['p_id'] == $item['p_id']) $item_p['active'] = 'yes';
                            else $item_p['active'] = 'no';
                            if ($prnt_id>0) $item_p['path'] = $path;
                            if (in_array($item_p['mod_id'], array(100, 101))){
                                $item_menu_section[$item_p['mod_id']][] = $item_p;
                            } else {
                                $item_menu[] = $item_p;
                            }
                        }
                    }
                    $menu[] = $item_menu;
                    $path .= $item['p_adress'].'/';
                }
                $prnt_id = $this->pages[count($this->pages)-1]['p_id'];
                $item_menu = array();
                foreach ($pages as $item_p){
                    if ($prnt_id == $item_p['p_parent_id']) {
                        $item_p['title'] = str_replace(' ', '&nbsp;', stripslashes($item_p['title']));
                        $item_p['menu_dropdown'] = $this->_getMenuDropdown($item_p['p_id'], $item_p['is_dropdown'], $path.$item_p['adress'].'/', $item_p['mod_id']);
                        if (isset($this->page['p_id']) && $item_p['p_id'] == $this->page['p_id']) $item_p['active'] = 'yes';
                        else $item_p['active'] = 'no';
                        $item_p['path'] = $path;
                        if (in_array($item_p['mod_id'], array(100, 101))){
                            $item_menu_section[$item_p['mod_id']][] = $item_p;
                        } else {
                            $item_menu[] = $item_p;
                        }
                    }
                }
                $menu[] = $item_menu;
            }
            if (isset($this->page['p_id']) && $this->page['p_id']>0) {
                $prnt_id = (isset($this->page['p_id']))?$this->page['p_id']:0;
                $item_menu = array();
                foreach ($pages as $item_p){
                    if ($prnt_id == $item_p['p_parent_id']) {
                        $item_p['title'] = str_replace(' ', '&nbsp;', stripslashes($item_p['title']));
                        $item_p['menu_dropdown'] = $this->_getMenuDropdown(
                            $item_p['p_id'],
                            $item_p['is_dropdown'],
                            $item_p['adress'].'/',
                            $item_p['mod_id']
                        );
                        if (isset($this->page['p_id']) && $item_p['p_id'] == $this->page['p_id']) $item_p['active'] = 'yes';
                        else $item_p['active'] = 'no';
                        $item_p['path'] = $this->page_path.((isset($this->page['p_adress']))?$this->page['p_adress']:'').'/';
                        if (in_array($item_p['mod_id'], array(100, 101))){
                            $item_menu_section[$item_p['mod_id']][] = $item_p;
                        } else {
                            $item_menu[] = $item_p;
                        }
                    }
                }
                if (count($item_menu)>0) $menu[] = $item_menu;
            }
            $this->menu_sections = $item_menu_section;
            $this->menu = $menu;
        }
    }

    private function getPageFiles($id = 0) {
        $id = intval($id);
        $list = false;
        if ($id >0){
            $list = $this->hdl->selectElem(DB_T_PREFIX."attachments, ".DB_T_PREFIX."files",
                "	f_id,
                    f_path,
                    f_type,
                    f_folder,
                    f_about_".$this->sLang." as about",
                "	at_p_id='".$id."' AND
                                            ( at_lang = '".$this->lang."' OR at_lang = 'all') AND
                                            at_f_id = f_id 
                                            ORDER BY at_id ASC");
            if (!empty($list)) {
                for ($i = 0; $i < count($list); $i++) {
                    if (!empty($list[$i]['about'])) {
                        $list[$i]['about'] = strip_tags(stripslashes($list[$i]['about']));
                    }
                    $file_name = $list[$i]['f_path'];
                    $file_name = substr(strrchr($file_name, "."), 1);
                    $file_name = strtolower($file_name);
                    switch ($file_name) {
                        case "pdf":
                            array_push($list[$i], array('ico'=>'images/pdf.gif'));
                            break;
                        case "rar":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/rar.gif'));
                            break;
                        case "zip":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/zip.gif'));
                            break;
                        case "doc":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/doc.gif'));
                            break;
                        case "docx":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/doc.gif'));
                            break;
                        case "xls":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/xls.gif'));
                            break;
                        case "xlsx":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/xls.gif'));
                            break;
                        case "txt":
                            $ico = array('ico'=>'images/txt.gif');
                            array_push($list[$i], array('ico'=>'images/txt.gif'));
                            break;
                        default:
                            $ico = array('ico'=>'images/file.gif');
                            array_push($list[$i], $ico);
                    }
                    $bytes = filesize("upload/" . $list[$i]['f_type'] . $list[$i]['f_folder'] . $list[$i]['f_path']);
                    $list[$i]['size'] = str_replace('.', ',', $this->formatBytes($bytes, $precision = 2));
                }
            }
            return $list;
        }
        return false;
    }

    private function getPVS($id = 0) {
        $id = intval($id);
        if ($id >0){
            $list = $this->hdl->selectElem(DB_T_PREFIX."page_video_services",
                "	pvs_id,
                    pvs_code,
                    pvs_service
                ","	pvs_page_id = '".$id."'
                                            ORDER BY pvs_id DESC");
            return $list;
        }
        return false;
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function getPageList($id = 0) {
        $id = intval($id);

        if ($id >0){
            $list = $this->hdl->selectElem(DB_T_PREFIX."pages",
                "	p_id,
                    p_parent_id,
                    p_adress as adress,
                    p_title_".$this->sLang." as title,
                    p_description_".$this->sLang." as description",
                "	p_parent_id='".$id."' AND
                    p_is_delete = 'no' AND 
                    p_is_active = 'yes' 
                    ORDER BY p_order DESC");
            if ($list){
                $q_extra = '';
                for ($i=0; $i<count($list); $i++){
                    $list[$i]['title'] = strip_tags(stripslashes($list[$i]['title']));
                    $list[$i]['description'] = stripslashes($list[$i]['description']);
                    $q_extra .= " OR pe_p_id = '".$list[$i]['p_id']."' ";
                }
                $q_extra = substr($q_extra, 3);
                $photos = $this->hdl->selectElem(DB_T_PREFIX."pages_extra, ".DB_T_PREFIX."photos ",
                    "	pe_style,
                        pe_p_id,
                        ph_id,
                        ph_about_".$this->sLang." as about,
                        ph_title_".$this->sLang." as title,
                        ph_path,
                        ph_folder",
                    "	ph_gallery_id=pe_item_id AND
                        pe_item_type = 'photo' AND
                        pe_is_on_descr = 'yes' AND
                        ph_is_active = 'yes' AND
                        ( $q_extra ) 
                        GROUP BY pe_p_id
                        ORDER BY ph_type_main ASC, ph_id ASC, pe_p_id ASC");
                if ($photos){
                    foreach ($photos as $item) {
                        $item['ph_med'] = "upload/photos".$item['ph_folder'].substr($item['ph_path'], 0, strlen(strrchr($item['ph_path'], "."))*(-1))."-med".strrchr($item['ph_path'], ".");
                        $ph[$item['pe_p_id']] = $item;
                    }
                    foreach ($list as &$item) if (isset($ph[$item['p_id']])) $item['photo'] = $ph[$item['p_id']];
                }
            }
            return $list;
        }
        return false;
    }

    public function getMainMenu() {
        $menu = $this->hdl->selectElem(DB_T_PREFIX."pages",
            "	p_id,
                p_parent_id,
                p_adress as adress,
                p_title_".$this->sLang." as title",
            "	p_parent_id='0' AND
                p_is_delete = 'no' AND
                p_is_active = 'yes' AND
                p_is_menu = 'yes'
                ORDER BY p_order DESC");
        return $menu;
    }

    public function getFuterMenu() {
        $menu = $this->hdl->selectElem(DB_T_PREFIX."pages",
            "	p_id,
                p_parent_id,
                p_adress as adress,
                p_title_".$this->sLang." as title",
            "	p_is_f_menu ='yes' AND
                p_is_delete = 'no' AND
                p_is_active = 'yes'
                ORDER BY p_parent_id ASC, p_order DESC");
        if ($menu)
            foreach ($menu as &$item) $item['title'] = strip_tags($item['title']);
        return $menu;
    }

    public function getExtraParamsMenuCompetitions($menu = array()) {
        if (!empty($menu)) {
            $ids = array();
            $menu_extra_by_id = array();
            $menu_extra_separated = array(
                'none' => array()
            );
            foreach ($menu as $menu_item) {
                $ids[] = $menu_item['p_id'];
            }
            $menu_extra = $this->hdl->selectElem(DB_T_PREFIX."pages_extra",
                "	pe_p_id,
                    pe_item_id",
                "	
                    pe_p_id IN (".implode(', ', $ids).") AND pe_item_type = 'championship_type' GROUP BY pe_p_id
                ");
            if ($menu_extra) {
                foreach ($menu_extra as $menu_extra_item) {
                    $menu_extra_by_id[$menu_extra_item['pe_p_id']] = $menu_extra_item['pe_item_id'];
                }
            }
            if (!empty($menu_extra_by_id)) {
                foreach ($menu as $menu_item) {
                    if (!empty($menu_extra_by_id[$menu_item['p_id']])) {
                        $menu_extra_separated[$menu_extra_by_id[$menu_item['p_id']]][] = $menu_item;
                    } else {
                        $menu_extra_separated['none'][] = $menu_item;
                    }
                }
            } else {
                $menu_extra_separated = array(
                    'none' => $menu
                );
            }
            $menu = $menu_extra_separated;
        }
        return $menu;
    }
}
?>