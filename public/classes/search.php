<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class search{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getSearchList($page=1, $perpage=10, $q_search = ''){
        $perpage = intval($perpage);
        $page = intval($page);
        if ($perpage < 1) $perpage = 10;
        if ($q_search == '') return false;
        $page--;
        if ($page<0) $page = 0;
        $limit = $page*$perpage;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."pages",
            "	p_id as id,
					p_parent_id,
					p_adress as address,
					p_title_".S_LANG." as title,
					p_description_".S_LANG." as description
					",
            "	p_is_active = 'yes' AND
					p_is_delete = 'no' AND (
					p_title_ru LIKE '%$q_search%' OR 
					p_title_ua LIKE '%$q_search%' OR
					p_title_en LIKE '%$q_search%' OR
					p_description_ru LIKE '%$q_search%' OR
					p_description_ua LIKE '%$q_search%' OR
					p_description_en LIKE '%$q_search%' OR
					p_text_ru LIKE '%$q_search%' OR
					p_text_ua LIKE '%$q_search%' OR
					p_text_en LIKE '%$q_search%'
					)");
        if ($temp){
            foreach ($temp as $item){
                $temp_item = $item;
                $temp_item['type'] = 'page';
                $temp_item['title'] = stripcslashes($temp_item['title']);
                $temp_item['description'] = stripcslashes($temp_item['description']);
                if ($temp_item['p_parent_id']>0) {
                    $temp_item['address'] = $this->getURLPage($temp_item['p_parent_id']).$temp_item['address'];
                }
                $list[] = $temp_item;
            }
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."news",
            "	n_id as id,
					n_title_".S_LANG." as title,
					n_description_".S_LANG." as description,
					n_date_show,
					n_nc_id",
            "	n_is_active = 'yes' and n_date_show<=NOW() and (
					n_title_ru LIKE '%$q_search%' OR 
					n_title_ua LIKE '%$q_search%' OR
					n_title_en LIKE '%$q_search%' OR
					n_description_ru LIKE '%$q_search%' OR
					n_description_ua LIKE '%$q_search%' OR
					n_description_en LIKE '%$q_search%' OR
					n_text_ru LIKE '%$q_search%' OR
					n_text_ua LIKE '%$q_search%' OR
					n_text_en LIKE '%$q_search%'
					) ORDER BY n_date_show DESC");
        if ($temp){
            foreach ($temp as $item){
                $temp_item = $item;
                $temp_item['type'] = 'news';
                $temp_item['address'] = 'news/'.$temp_item['id'];
                $temp_item['title'] = stripcslashes($temp_item['title']);
                $temp_item['description'] = stripcslashes($temp_item['description']);
                $list[] = $temp_item;
            }
        }
        if (count($list)>$limit)
            for ($i=$limit; $i<$limit+$perpage; $i++ )
                if ($i<count($list)) $res['res'][] = $list[$i];

        $res['count'] = count($list);
        return $res;
    }

    public function getURLPage($id = 0){
        $id = intval($id);
        if ($id>0) $p_parent_id = $id;
        else return false;
        $url = '';
        $flag = true;
        while ($flag){
            $temp = $this->hdl->selectElem(DB_T_PREFIX."pages",
                "	p_id as id,
						p_parent_id,
						p_adress as address
						",
                "	p_is_delete = 'no' AND
						p_id = '$p_parent_id' LIMIT 1");
            if ($temp){
                $temp = $temp[0];
                $url = $temp['address']."/".$url;
                if ($temp['p_parent_id'] > 0) $p_parent_id = $temp['p_parent_id'];
                else $flag = false;
            } else $flag = false;
        }
        return $url;
    }


    public function getSearchPages($page=1, $perpage=10, $count = 0){
        $count = intval($count);
        $perpage = intval($perpage);
        $page = intval($page);
        if ($perpage < 1) $perpage = 10;
        if ($count == 0) return false;
        $c_pages = $count/$perpage;
        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i <= $c_pages) $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                $pages[9] = "...";
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                $pages[$page-6] = "...";
                if ($page+4 <= $c_pages) $pages[$page+4] = "...";
            }
        }
        return $pages;
    }

    public function saveSearch($q_search = ''){
        if ($q_search == '') return false;
        $iData = array(
            "NOW()",
            $q_search,
            $_SERVER["REMOTE_ADDR"]
        );
        $this->hdl->addElem(DB_T_PREFIX."search_stat", $iData);
    }

}
?>
