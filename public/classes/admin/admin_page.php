<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class page{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // PAGES ////////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////////////////////
    public function createPage(){
        if($_POST['p_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($_POST['p_is_menu']==true) $p_is_menu ='yes';
        else $p_is_menu = 'no';
        if($_POST['p_is_f_menu']==true) $p_is_f_menu ='yes';
        else $p_is_f_menu = 'no';
        if($_POST['p_is_menu_dropdown']==true) $p_is_menu_dropdown ='yes';
        else $p_is_menu_dropdown = 'no';
        $search = array("_", " ", "'", '"');
        $replace = array("-", "-", '', '');
        $_POST['p_adress'] = $this->getTranslit(strtolower($_POST['p_adress']));
        $elem = array(
            intval($_POST['p_parent_id']),
            '0',
            str_replace($search, $replace, $_POST['p_adress']),
            addslashes($_POST['p_title_ru']),
            addslashes($_POST['p_title_ua']),
            addslashes($_POST['p_title_en']),
            (strlen(trim(html_entity_decode(strip_tags($_POST['p_description_ru']))))>0) ? addslashes($_POST['p_description_ru']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($_POST['p_description_ua']))))>0) ? addslashes($_POST['p_description_ua']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($_POST['p_description_en']))))>0) ? addslashes($_POST['p_description_en']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($_POST['p_text_ru']))))>0) ? addslashes($_POST['p_text_ru']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($_POST['p_text_ua']))))>0) ? addslashes($_POST['p_text_ua']) : NULL,
            (strlen(trim(html_entity_decode(strip_tags($_POST['p_text_en']))))>0) ? addslashes($_POST['p_text_en']) : NULL,
            USER_ID,
            'NOW()',
            'NOW()',
            $is_active,
            $p_is_menu,
            'no',
            intval($_POST['p_order']),
            '',
            'yes',
            'no',
            'yes',
            'no',
            'yes',
            'no',
            5,
            5,
            5,
            $p_is_f_menu,
            $p_is_menu_dropdown,
            'yes'
        );
        if ($this->hdl->addElem(DB_T_PREFIX."pages", $elem)) return true;
        return false;
    }

    public function updatePage(){
        if($_POST['p_is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($_POST['p_is_menu']==true) $p_is_menu ='yes';
        else $p_is_menu = 'no';
        if($_POST['p_is_f_menu']==true) $p_is_f_menu ='yes';
        else $p_is_f_menu = 'no';
        if($_POST['p_is_menu_dropdown']==true) $p_is_menu_dropdown ='yes';
        else $p_is_menu_dropdown = 'no';
        $search = array("_", " ", "'", '"');
        $replace = array("-", "-", '', '');
        $_POST['p_adress'] = $this->getTranslit(strtolower($_POST['p_adress']));
        $elems = array(
            "p_parent_id" => intval($_POST['p_parent_id']),
            "p_adress" => str_replace($search, $replace, $_POST['p_adress']),
            "p_title_ru" => addslashes($_POST['p_title_ru']),
            "p_title_ua" => addslashes($_POST['p_title_ua']),
            "p_title_en" => addslashes($_POST['p_title_en']),
            "p_description_ru" => (strlen(trim(html_entity_decode(strip_tags($_POST['p_description_ru'], "<img>"))))>0) ? addslashes($_POST['p_description_ru']) : NULL,
            "p_description_ua" => (strlen(trim(html_entity_decode(strip_tags($_POST['p_description_ua'], "<img>"))))>0) ? addslashes($_POST['p_description_ua']) : NULL,
            "p_description_en" => (strlen(trim(html_entity_decode(strip_tags($_POST['p_description_en'], "<img>"))))>0) ? addslashes($_POST['p_description_en']) : NULL,
            "p_text_ru" => (strlen(trim(html_entity_decode(strip_tags($_POST['p_text_ru'], "<img>"))))>0) ? addslashes($_POST['p_text_ru']) : NULL,
            "p_text_ua" => (strlen(trim(html_entity_decode(strip_tags($_POST['p_text_ua'], "<img>"))))>0) ? addslashes($_POST['p_text_ua']) : NULL,
            "p_text_en" => (strlen(trim(html_entity_decode(strip_tags($_POST['p_text_en'], "<img>"))))>0) ? addslashes($_POST['p_text_en']) : NULL,
            "p_author" => USER_ID,
            "p_datetime_edit" => 'NOW()',
            "p_is_active" => $is_active,
            "p_is_menu" => $p_is_menu,
            "p_order" => intval($_POST['p_order']),
            "p_is_f_menu" => $p_is_f_menu,
            "p_is_menu_dropdown" => $p_is_menu_dropdown
        );
        $condition = array(
            "p_id"=>$_POST['p_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        else return false;
    }

    public function getPagesList(){
        return $this->getPagesByParent(0,0,0,'','','no', 'p_order', 'DESC');
        //return $this->hdl->selectElem(DB_T_PREFIX."pages","*","p_is_delete = 'no'");
    }

    public function getPagesByParent($parent_id = 0, $nesting = 0, $depth = 0, $is_active='', $is_menu='', $is_delete='', $order_by = '', $order = 'ASC'){
        if ($nesting<$depth or $depth == 0){
            $extra = '';
            if ($is_active == 'yes') $extra .= " AND p_is_active = 'yes'";
            if ($is_active == 'no') $extra .= " AND p_is_active = 'no'";
            if ($is_menu == 'yes') $extra .= " AND p_is_menu = 'yes'";
            if ($is_menu == 'no') $extra .= " AND p_is_menu = 'no'";
            if ($is_delete == 'yes') $extra .= " AND p_is_delete = 'yes'";
            if ($is_delete == 'no') $extra .= " AND p_is_delete = 'no'";
            if ($order == 'desc' OR $order == 'DESC' OR $order == 'Desc') $order = 'DESC';
            else $order = 'ASC';
            if ($order_by != '') $extra .= " ORDER BY $order_by $order";
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages","p_id, p_parent_id, p_title_ru, p_is_active, p_is_menu, p_order, p_is_f_menu","p_parent_id = '$parent_id'".$extra);
            if ($temp != false){
                $result = array();
                $new_nesting = $nesting+1;
                foreach ($temp as $key=>$item){
                    $temp_item = $item;
                    $temp_item['p_title_ru'] = strip_tags($temp_item['p_title_ru']);
                    $temp_item['nesting'] = $nesting;
                    $result[] = $temp_item;
                    $parent_result = $this->getPagesByParent($temp_item['p_id'], $new_nesting, $depth, $is_active, $is_menu, $is_delete, $order_by, $order);
                    if ($parent_result != false){
                        foreach ($parent_result as $key_parent=>$item_parent) $result[] = $item_parent;
                    }
                }
                return $result;
            } else return false;
        } else return false;
    }

    public function getPageItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages","*","p_id=$item");
            if ($temp) {
                $temp = $temp[0];
                $temp['p_title_ru'] = stripcslashes($temp['p_title_ru']);
                $temp['p_title_ua'] = stripcslashes($temp['p_title_ua']);
                $temp['p_title_en'] = stripcslashes($temp['p_title_en']);
                $temp['p_description_ru'] = stripcslashes($temp['p_description_ru']);
                $temp['p_description_ua'] = stripcslashes($temp['p_description_ua']);
                $temp['p_description_en'] = stripcslashes($temp['p_description_en']);
                $temp['p_text_ru'] = stripcslashes($temp['p_text_ru']);
                $temp['p_text_ua'] = stripcslashes($temp['p_text_ua']);
                $temp['p_text_en'] = stripcslashes($temp['p_text_en']);
                if ($temp['p_mod_id']>0){
                    $mod = $this->hdl->selectElem(DB_T_PREFIX."modules","*","mod_id='".$temp['p_mod_id']."'");
                    if ($mod) {
                        $mod = $mod[0];
                        $temp['mod'] = $mod;
                    } else $temp['mod'] = false;
                }
            }
            return $temp;
        } else return false;
    }

    public function deletePage($p_id){
        $elems = array(
            "p_is_delete"=>'yes'
        );
        $condition = array(
            "p_id"=>$_POST['p_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
    }

    public function doPageUp($p){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages","*","p_parent_id = '".$p['p_parent_id']."' order by p_order DESC");
        for ($i=1; $i<count($temp); $i++){
            if ($temp[$i]['p_id'] == $p['p_id']){
                if ($temp[$i-1]['p_order'] == $p['p_order']) {
                    $new_order = $p['p_order']+1;
                } else {
                    $new_order = $temp[$i-1]['p_order'];
                    $temp[$i-1]['p_order'] = $p['p_order'];
                    $elems = array(
                        "p_order"=>$temp[$i-1]['p_order']
                    );
                    $condition = array(
                        "p_id"=>$temp[$i-1]['p_id']
                    );
                    $this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition);
                }
                if ($new_order>0){
                    $elems = array(
                        "p_order"=>$new_order
                    );
                    $condition = array(
                        "p_id"=>$p['p_id']
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
                }
            }
        }
    }

    public function doPageDown($p){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages","*","p_parent_id = '".$p['p_parent_id']."' order by p_order DESC");
        for ($i=0; $i<count($temp)-1; $i++){
            if ($temp[$i]['p_id'] == $p['p_id']){
                if ($temp[$i+1]['p_order'] == $p['p_order'] and $p['p_order']>0) {
                    $new_order = $p['p_order']-1;
                } elseif ($temp[$i+1]['p_order'] == $p['p_order'] and $p['p_order'] == 0) {
                    $temp[$i+1]['p_order']++;
                    $elems = array(
                        "p_order"=>$temp[$i+1]['p_order']
                    );
                    $condition = array(
                        "p_id"=>$temp[$i+1]['p_id']
                    );
                    $this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition);
                } else {
                    $new_order = $temp[$i+1]['p_order'];
                    $temp[$i+1]['p_order'] = $p['p_order'];
                    $elems = array(
                        "p_order"=>$temp[$i+1]['p_order']
                    );
                    $condition = array(
                        "p_id"=>$temp[$i+1]['p_id']
                    );
                    $this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition);
                }
                if ($new_order>=0){
                    $elems = array(
                        "p_order"=>$new_order
                    );
                    $condition = array(
                        "p_id"=>$p['p_id']
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
                }
            }
        }
    }
    // PAGES ////////// КОНЕЦ //////////////////////////////////////////////////////////////////////////////////////////////


    // PAGES НАСТРОЙКИ ////////// НАЧАЛО ///////////////////////////////////////////////////////////////////////////////////
    public function savePageIs(){
        $page = intval($_POST['p_id']);
        if ($page<1) return false;
        if($_POST['p_is_title']==true) $p_is_title ='yes';
        else $p_is_title = 'no';
        if($_POST['p_is_description']==true) $p_is_description ='yes';
        else $p_is_description = 'no';
        if($_POST['p_is_text']==true) $p_is_text ='yes';
        else $p_is_text = 'no';
        if($_POST['p_is_scrol']==true) $p_is_scrol ='yes';
        else $p_is_scrol = 'no';
        if($_POST['p_is_files']==true) $p_is_files ='yes';
        else $p_is_files = 'no';
        if($_POST['p_is_first_subpage']==true) $p_is_first_subpage ='yes';
        else $p_is_first_subpage = 'no';
        if($_POST['p_is_socials']==true) $p_is_socials ='yes';
        else $p_is_socials = 'no';
        $elems = array(
            "p_is_title" => $p_is_title,
            "p_is_description" => $p_is_description,
            "p_is_text" => $p_is_text,
            "p_is_scrol" => $p_is_scrol,
            "p_is_files" => $p_is_files,
            "p_is_first_subpage" => $p_is_first_subpage,
            "p_is_socials" => $p_is_socials
        );
        $condition = array(
            "p_id"=>$page
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        return false;
    }

    public function savePageModule($page = 0, $mod = 0){
        $page = intval($page);
        $mod = intval($mod);
        if ($page<1) return false;
        $elems = array(
            "p_mod_id" => $mod
        );
        $condition = array(
            "p_id"=>$page
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        return false;
    }

    public function getPageModuleList(){
        return $this->hdl->selectElem(DB_T_PREFIX."modules","*","1 ORDER BY mod_title ASC");
    }

    public function savePageTitleFiles($page = 0, $c_b = 0){
        $page = intval($page);
        if ($page<1) return false;
        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $elems = array(
            "p_title_files" => str_replace($search, $replace, $_POST['p_title_files'])
        );
        $condition = array(
            "p_id"=>$page
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        return false;
    }

    public function savePageBannerCountC($page = 0, $c_b = 0){
        $page = intval($page);
        if ($page<1) return false;
        $elems = array(
            "p_c_banners_center" => intval($c_b)
        );
        $condition = array(
            "p_id"=>$page
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        return false;
    }

    public function savePageBannerCountR($page = 0, $c_b = 0){
        $page = intval($page);
        if ($page<1) return false;
        $elems = array(
            "p_c_banners_right" => intval($c_b)
        );
        $condition = array(
            "p_id"=>$page
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        return false;
    }

    public function savePageBannerCountL($page = 0, $c_b = 0){
        $page = intval($page);
        if ($page<1) return false;
        $elems = array(
            "p_c_banners_left" => intval($c_b)
        );
        $condition = array(
            "p_id"=>$page
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."pages",$elems, $condition)) return true;
        return false;
    }

    public function getPageFileList($folder = '', $page = 0){
        if ($folder != '') $extra_q = " AND f_folder = '$folder' ";
        else $extra_q = "";
        $temp = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_type = 'files'".$extra_q." ORDER BY f_id DESC");
        return $temp;
    }

    public function atachFile($page_id, $file_id, $lang){
        $file_id = intval($file_id);
        $page_id = intval($page_id);
        if ($lang == 'rus') $lang = 'rus';
        elseif ($lang == 'ukr') $lang = 'ukr';
        elseif ($lang == 'eng') $lang = 'eng';
        else $lang = 'all';
        $iData = array(
            $file_id,
            $page_id,
            $lang,
            "NOW()",
            USER_ID
        );
        if ($this->hdl->addElem(DB_T_PREFIX."attachments", $iData)) return true;
        else return false;
    }

    public function atachDeleteFile($at_id){
        $at_id = intval($at_id);
        if ($this->hdl->delElem(DB_T_PREFIX."attachments", " at_id ='$at_id'")) return true;
        else return false;
    }

    public function getPageAttachList($page = 0){
        if ($page > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."attachments, ".DB_T_PREFIX."files","*","at_p_id = '$page' and at_f_id = f_id ORDER BY at_id DESC");
            return $temp;
        } else return false;
    }
    // PAGES НАСТРОЙКИ ////////// КОНЕЦ ////////////////////////////////////////////////////////////////////////////////////

    // PAGES PHOTO GALLERY ////////// НАЧАЛО ///////////////////////////////////////////////////////////////////////////////
    public function getPhotoGalList($pe_item_id){
        $list = $this->hdl->selectElem(DB_T_PREFIX."photo_gallery","phg_id as id, phg_title_ru as title"," phg_is_active = 'yes' OR phg_id = '$pe_item_id' ORDER BY phg_title_ru ASC, phg_id DESC");
        if ($list) {
            foreach ($list as &$item) {
                $item['title'] = stripcslashes($item['title']);
            }
        }
        return $list;
    }

    public function getPhPageItem($p_id = 0){
        $p_id = intval($p_id);
        if ($p_id<1) return false;
        $item = $this->hdl->selectElem(DB_T_PREFIX."pages_extra","pe_p_id, pe_item_id, pe_style, pe_is_on_descr, pe_is_on_text"," pe_p_id = '$p_id' AND pe_item_type = 'photo' LIMIT 1");
        return $item[0];
    }

    public function savePhPage(){
        $p_id = intval($_POST['p_id']);
        $phg_id = intval($_POST['phg_id']);
        $style = intval($_POST['style_id']);
        if($_POST['pe_is_on_descr']==true) $pe_is_on_descr ='yes';
        else $pe_is_on_descr = 'no';
        if($_POST['pe_is_on_text']==true) $pe_is_on_text ='yes';
        else $pe_is_on_text = 'no';

        if ($p_id<1) return false;
        if ($phg_id>0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."pages_extra","pe_id"," pe_p_id = '$p_id' AND pe_item_type = 'photo' LIMIT 1");
            if ($item){
                $item = $item[0];
                $elems = array(
                    "pe_item_id" => $phg_id,
                    "pe_style" => $style,
                    "pe_is_on_descr" => $pe_is_on_descr,
                    "pe_is_on_text" => $pe_is_on_text,
                    "pe_datetime_edit" => 'NOW()'
                );
                $condition = array(
                    "pe_id"=>$item['pe_id']
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."pages_extra",$elems, $condition)) return true;
            } else {
                $iData = array(
                    $p_id,
                    $phg_id,
                    'photo',
                    $style,
                    $pe_is_on_descr,
                    $pe_is_on_text,
                    "NOW()",
                    "NOW()",
                    USER_ID
                );
                if ($this->hdl->addElem(DB_T_PREFIX."pages_extra", $iData)) return true;
            }
        } else {
            if ($this->hdl->delElem(DB_T_PREFIX."pages_extra", " pe_p_id ='$p_id' AND pe_item_type = 'photo'")) return true;
        }
        return false;
    }
    // PAGES PHOTO GALLERY ////// КОНЕЦ ////////////////////////////////////////////////////////////////////////////////////

    // PAGE BANNER INFORMER ////////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////
    public function getPageBanInfList($id = 0){ // список баннеров и информеров для страницы (уже добавленых)
        $id = intval($id);
        if ($id<1) return false;
        $q_id = ''; // баннера со страниц родителей

        $p_temp = $this->hdl->selectElem(DB_T_PREFIX."pages","p_id, p_parent_id","p_id = '$id' limit 1");
        if ($p_temp) {
            $q_id .= " OR pbi_page_id = '".$p_temp[0]['p_id']."' ";
            $pages[$p_temp[0]['p_id']] = $p_temp[0];
        } else return false;
        if ($p_temp[0]['p_parent_id']>0){
            while ($p_temp[0]['p_parent_id']>0){
                $p_temp = $this->hdl->selectElem(DB_T_PREFIX."pages","p_id, p_parent_id","p_id = '".$p_temp[0]['p_parent_id']."' limit 1");
                if ($p_temp) {
                    $q_id .= " OR pbi_page_id = '".$p_temp[0]['p_id']."' ";
                    $pages[$p_temp[0]['p_id']] = $p_temp[0];
                }
            }
        }

        $pages = array_reverse($pages, true);
        $setting = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_pbi_id as id, pbi_display, pbi_page_id","pbi_item_type = 'setting' AND pbi_page_id = '0' $q_id ORDER BY pbi_page_id ASC");
        if ($setting){
            foreach ($setting as $item){
                $pages[$item['pbi_page_id']]['settings'][] = $item;
            }
            foreach ($pages as $p_item){
                if ($p_item['settings'])
                    foreach ($p_item['settings'] as $s_item)
                        $setting_id[$s_item['id']] = $s_item;
            }
        }

        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","*","pbi_item_type != 'setting' AND pbi_page_id = '0' $q_id ORDER BY pbi_order DESC");
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
            $banners = $this->hdl->selectElem(DB_T_PREFIX."banners",
                "	b_id as id,
												b_is_active as is_active,
												b_is_datetime as is_datetime,
												b_datetime_from as datetime_from,
												b_datetime_to as datetime_to,
												b_title_ru as title","b_is_delete = 'no' $q_banners ORDER BY b_id ASC");
            if ($banners)
                foreach ($banners as $item){
                    $banners_id[$item['id']] = $item;
                }
            $informers = $this->hdl->selectElem(DB_T_PREFIX."informers",
                "	i_id as id,
												i_is_active as is_active,
												i_is_datetime as is_datetime,
												i_datetime_from as datetime_from,
												i_datetime_to as datetime_to,
												i_title_ru as title","i_is_delete = 'no' $q_informer ORDER BY i_id ASC");
            if ($informers)
                foreach ($informers as $item){
                    $informers_id[$item['id']] = $item;
                }
            for ($i=0; $i<count($temp); $i++){
                if ($temp[$i]['pbi_item_type'] == 'banner' AND isset($banners_id[$temp[$i]['pbi_item_id']])) $temp[$i]['item'] = $banners_id[$temp[$i]['pbi_item_id']];
                if ($temp[$i]['pbi_item_type'] == 'informer' AND isset($informers_id[$temp[$i]['pbi_item_id']])) $temp[$i]['item'] = $informers_id[$temp[$i]['pbi_item_id']];
                if (!empty($setting_id[$temp[$i]['pbi_id']])) $temp[$i]['pbi_display'] = $setting_id[$temp[$i]['pbi_id']]['pbi_display'];
            }
        }
        return $temp;
    }

    public function getBanInfList($id = 0){ // список баннеров и информеров (не добавленных)
        $id = intval($id);
        if ($id<1) return false;
        $q_id = ''; // баннера со страниц родителей

        $p_temp = $this->hdl->selectElem(DB_T_PREFIX."pages","p_id, p_parent_id","p_id = '$id' limit 1");
        if ($p_temp) $q_id .= " OR pbi_page_id = '".$p_temp[0]['p_id']."' ";
        else return false;
        if ($p_temp[0]['p_parent_id']>0){
            while ($p_temp[0]['p_parent_id']>0){
                $p_temp = $this->hdl->selectElem(DB_T_PREFIX."pages","p_id, p_parent_id","p_id = '".$p_temp[0]['p_parent_id']."' limit 1");
                if ($p_temp) $q_id .= " OR pbi_page_id = '".$p_temp[0]['p_id']."' ";
            }
        }
        $q_banners = $q_informer = '';
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","*","pbi_item_type != 'setting' AND pbi_page_id = '0' $q_id ORDER BY pbi_order DESC");
        if ($temp){
            foreach ($temp as $item){
                if ($item['pbi_item_type'] == 'banner'){
                    $q_banners .= " AND b_id != '".$item['pbi_item_id']."'";
                }
                if ($item['pbi_item_type'] == 'informer'){
                    $q_informer .= " AND i_id != '".$item['pbi_item_id']."'";
                }
            }
        }
        $banners = $this->hdl->selectElem(DB_T_PREFIX."banners",
            "	b_id as id,
												b_is_active as is_active,
												b_is_datetime as is_datetime,
												b_datetime_from as datetime_from,
												b_datetime_to as datetime_to,
												b_title_ru as title","b_is_delete = 'no' $q_banners ORDER BY b_title_ru ASC, b_id ASC");
        $list['banners'] = $banners;
        $informers = $this->hdl->selectElem(DB_T_PREFIX."informers",
            "	i_id as id,
												i_is_active as is_active,
												i_is_datetime as is_datetime,
												i_datetime_from as datetime_from,
												i_datetime_to as datetime_to,
												i_title_ru as title","i_is_delete = 'no' $q_informer ORDER BY i_title_ru ASC, i_id ASC");
        $list['informers'] = $informers;
        return $list;
    }

    public function deletePageBannerInformer($id = 0){ // удаление баннера или информера со страницы
        $id = intval($id);
        if ($id <1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id, pbi_page_id","pbi_id = '$id' LIMIT 1");
        if ($temp and $temp[0]['pbi_page_id'] == $_GET['item'])
            if ($this->hdl->delElem(DB_T_PREFIX."page_banner_informer", " pbi_id ='$id'")) {
                $this->hdl->delElem(DB_T_PREFIX."page_banner_informer", " pbi_pbi_id ='$id'");
                return true;
            } else return false;
    }

    public function savePageBannerInformer($id = 0){ // сохранение баннера или информера на странице
        $id = intval($id);
        if ($id <1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id, pbi_page_id","pbi_id = '$id' LIMIT 1");
        if ($temp){
            if ($temp[0]['pbi_page_id'] == $_GET['item']) {
                if ($_POST['pbi_item_type'] == 'banner') {
                    $elems['pbi_item_type'] = 'banner';
                    $elems['pbi_item_id'] = intval($_POST['pbi_banner_id']);
                    if ($_POST['pbi_place'] == 'right') $elems['pbi_place'] = 'right';
                    if ($_POST['pbi_place'] == 'left') $elems['pbi_place'] = 'left';
                    if ($_POST['pbi_place'] == 'down') $elems['pbi_place'] = 'down';
                    else $elems['pbi_place'] == 'center';
                    $elems['pbi_order'] = intval($_POST['pbi_order']);
                    if ($_POST['pbi_display_type'] == 'fixed') $elems['pbi_display_type'] = 'fixed';
                    else $elems['pbi_display_type'] = 'shuffle';
                    $condition = array(
                        "pbi_id"=>$id
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."page_banner_informer",$elems, $condition)) return true;
                }
                if ($_POST['pbi_item_type'] == 'informer') {
                    $elems['pbi_item_type'] = 'informer';
                    $elems['pbi_item_id'] = intval($_POST['pbi_informer_id']);
                    if ($_POST['pbi_place'] == 'right') $elems['pbi_place'] = 'right';
                    if ($_POST['pbi_place'] == 'left') $elems['pbi_place'] = 'left';
                    if ($_POST['pbi_place'] == 'down') $elems['pbi_place'] = 'down';
                    else $elems['pbi_place'] == 'center';
                    $elems['pbi_order'] = intval($_POST['pbi_order']);
                    if ($_POST['pbi_display_type'] == 'fixed') $elems['pbi_display_type'] = 'fixed';
                    else $elems['pbi_display_type'] = 'shuffle';
                    $condition = array(
                        "pbi_id"=>$id
                    );
                    if ($this->hdl->updateElem(DB_T_PREFIX."page_banner_informer",$elems, $condition)) return true;
                }
            }
        }
        return false;
    }

    public function offPageBannerInformer($id = 0){ // выключение баннера или информера на странице
        $id = intval($id);
        if ($id <1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id, pbi_page_id, pbi_display, pbi_place, pbi_display_type","pbi_id = '$id' LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            if ($temp['pbi_page_id'] == $_GET['item']) {
                $elems = array(
                    "pbi_display" => 'no'
                );
                $condition = array(
                    "pbi_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."page_banner_informer",$elems, $condition)) return true;
            } else {
                $temp_s = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id","pbi_item_type = 'setting' AND pbi_pbi_id = '$id' AND pbi_page_id = '".intval($_GET['item'])."' LIMIT 1");
                if ($temp_s){
                    $temp_s = $temp_s[0];
                    $elems['pbi_display'] = 'no';
                    $condition['pbi_id'] = $temp_s['pbi_id'];
                    if ($this->hdl->updateElem(DB_T_PREFIX."page_banner_informer",$elems, $condition)) return true;
                } else {
                    $iData = array(
                        "NOW()",
                        USER_ID,
                        intval($_GET['item']),
                        0,
                        'setting',
                        $temp['pbi_place'],
                        'no',
                        0,
                        $temp['pbi_id'],
                        $temp['pbi_display_type']
                    );
                    if ($this->hdl->addElem(DB_T_PREFIX."page_banner_informer", $iData)) return true;
                }
            }
        }
    }

    public function onPageBannerInformer($id = 0){ // включение баннера или информера на странице
        $id = intval($id);
        if ($id <1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id, pbi_page_id","pbi_id = '$id' LIMIT 1");
        if ($temp){
            $temp = $temp[0];
            if ($temp['pbi_page_id'] == $_GET['item']) {
                $elems = array(
                    "pbi_display" => 'yes'
                );
                $condition = array(
                    "pbi_id"=>$id
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."page_banner_informer",$elems, $condition)) return true;
            } else {
                $temp_s = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id","pbi_item_type = 'setting' AND pbi_pbi_id = '$id' AND pbi_page_id = '".intval($_GET['item'])."' LIMIT 1");
                if ($temp_s){
                    $temp_s = $temp_s[0];
                    $elems['pbi_display'] = 'yes';
                    $condition['pbi_id'] = $temp_s['pbi_id'];
                    if ($this->hdl->updateElem(DB_T_PREFIX."page_banner_informer",$elems, $condition)) return true;
                } else {
                    $iData = array(
                        "NOW()",
                        USER_ID,
                        intval($_GET['item']),
                        0,
                        'setting',
                        'center',
                        'yes',
                        0,
                        $temp['pbi_id']
                    );
                    if ($this->hdl->addElem(DB_T_PREFIX."page_banner_informer", $iData)) return true;
                }
            }
        }
    }

    public function addPageBannerInformer(){ // добавление баннера или информера на страницу
        $_POST['p_id'] = intval($_POST['p_id']);
        if ($_POST['p_id']<1) return false;
        if ($_POST['pbi_place'] == 'right') $_POST['pbi_place'] = 'right';
        if ($_POST['pbi_place'] == 'left') $_POST['pbi_place'] = 'left';
        if ($_POST['pbi_place'] == 'down') $_POST['pbi_place'] = 'down';
        else $_POST['pbi_place'] == 'center';
        if ($_POST['pbi_display_type'] == 'fixed') $_POST['pbi_display_type'] = 'fixed';
        else $_POST['pbi_display_type'] = 'shuffle';
        if ($_POST['add_page_banner']){
            $_POST['pbi_banner_id'] = intval($_POST['pbi_banner_id']);
            if ($_POST['pbi_banner_id']<1) return false;
            $iData = array(
                "NOW()",
                USER_ID,
                $_POST['p_id'],
                $_POST['pbi_banner_id'],
                'banner',
                $_POST['pbi_place'],
                'yes',
                intval($_POST['pbi_order']),
                0,
                $_POST['pbi_display_type']
            );
            $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id",
                "	pbi_page_id = '".$_POST['p_id']."'
											AND pbi_item_id = '".$_POST['pbi_banner_id']."' 
											AND pbi_item_type = 'banner'
											AND pbi_place = '".$_POST['pbi_place']."'
											LIMIT 1");
            if ($temp) return false;
            if ($this->hdl->addElem(DB_T_PREFIX."page_banner_informer", $iData)) return true;
        }
        if ($_POST['add_page_informer']){
            $_POST['pbi_informer_id'] = intval($_POST['pbi_informer_id']);
            if ($_POST['pbi_informer_id']<1) return false;
            $iData = array(
                "NOW()",
                USER_ID,
                $_POST['p_id'],
                $_POST['pbi_informer_id'],
                'informer',
                $_POST['pbi_place'],
                'yes',
                intval($_POST['pbi_order']),
                0,
                $_POST['pbi_display_type']
            );
            $temp = $this->hdl->selectElem(DB_T_PREFIX."page_banner_informer","pbi_id",
                "	pbi_page_id = '".$_POST['p_id']."'
											AND pbi_item_id = '".$_POST['pbi_informer_id']."' 
											AND pbi_item_type = 'informer'
											AND pbi_place = '".$_POST['pbi_place']."'
											LIMIT 1");
            if ($temp) return false;
            if ($this->hdl->addElem(DB_T_PREFIX."page_banner_informer", $iData)) return true;
        }
        return false;
    }

    // PAGE BANNER INFORMER ////////// КОНЕЦ ///////////////////////////////////////////////////////////////////////////////

    private function getTranslit($st, $charset = 'utf-8'){
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
}
?>
