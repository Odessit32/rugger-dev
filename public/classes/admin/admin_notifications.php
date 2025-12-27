<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once(__DIR__.'/admin_base.php');

class Notification extends adminBase{
    protected $hdl;
    public function __construct(){
        parent::__construct();
        $this->hdl = database::getInstance();
    }

    /**
     * Validate before save item
     *
     * @param array $post
     * @return array
     */
    public function validate($post=array()) {
        global $language;
        $res = array(
            'status' => true,
            'message' => ''
        );
        if (empty($post['title_ua']) && empty($post['title_ru']) && empty($post['title_en'])) {
            $res['status'] = false;
            $res['message'] = $language['title_required'];
        }
        if (empty(strip_tags($post['description_ua'])) && empty(strip_tags($post['description_ru'])) && empty(strip_tags($post['description_en']))) {
            $res['status'] = false;
            $res['message'] = $language['description_required'];
        }
        return $res;
    }

    /**
     * Create one Item 
     * 
     * @param $post
     * @return bool|int|string
     */
    public function create($post){
        $is_active = (!empty($post['is_active']))? 'yes':'no';
        $date_show_start = (!empty(strtotime($post['date_show_start'])))?date("Y-m-d H:i:s", strtotime($post['date_show_start'])):date("Y-m-d H:i:s");
        $date_show_finish = (!empty(strtotime($post['date_show_finish'])))?date("Y-m-d H:i:s", strtotime($post['date_show_finish'])):date("Y-m-d H:i:s");
        $color = (!empty($post['color']))?strip_tags($post['color']):'';
        $url = (!empty($post['url']))?strip_tags($post['url']):'';
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $elem = array(
            (strlen(trim(html_entity_decode(strip_tags($post['title_ru']))))>0) ? str_replace($search, $replace, strip_tags($post['title_ru'])) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['title_ua']))))>0) ? str_replace($search, $replace, strip_tags($post['title_ua'])) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['title_en']))))>0) ? str_replace($search, $replace, strip_tags($post['title_en'])) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['description_ru'], "<a>, <img>"))))>0) ? addslashes(strip_tags($post['description_ru'])) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['description_ua'], "<a>, <img>"))))>0) ? addslashes(strip_tags($post['description_ua'])) : '',
            (strlen(trim(html_entity_decode(strip_tags($post['description_en'], "<a>, <img>"))))>0) ? addslashes(strip_tags($post['description_en'])) : '',
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            $date_show_start,
            $date_show_finish,
            $color,
            $url,
            (!empty(intval($post['time_show'])))?intval($post['time_show']):0,
            (!empty(intval($post['time_hide'])))?intval($post['time_hide']):0
        );

        if ($id = $this->hdl->addElem(DB_T_PREFIX."notifications", $elem)) {
            return $id;
        }
        return false;
    }

    /**
     * Update one item
     *
     * @param $post
     * @return bool
     */
    public function update($post){ // редактирование новости
        $is_active = (!empty($post['is_active']))?'yes':'no';
        $date_show_start = (!empty(strtotime($post['date_show_start'])))?date("Y-m-d H:i:s", strtotime($post['date_show_start'])):date("Y-m-d H:i:s");
        $date_show_finish = (!empty(strtotime($post['date_show_finish'])))?date("Y-m-d H:i:s", strtotime($post['date_show_finish'])):date("Y-m-d H:i:s");
        $color = (!empty($post['color']))?strip_tags($post['color']):'';
        $url = (!empty($post['url']))?strip_tags($post['url']):'';
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $elems = array(
            "title_ru" => (strlen(trim(html_entity_decode(strip_tags($post['title_ru']))))>0) ? str_replace($search, $replace, strip_tags($post['title_ru'])) : '',
            "title_ua" => (strlen(trim(html_entity_decode(strip_tags($post['title_ua']))))>0) ? str_replace($search, $replace, strip_tags($post['title_ua'])) : '',
            "title_en" => (strlen(trim(html_entity_decode(strip_tags($post['title_en']))))>0) ? str_replace($search, $replace, strip_tags($post['title_en'])) : '',
            "description_ru" => (strlen(trim(html_entity_decode(strip_tags($post['description_ru'], "<a>, <img>"))))>0) ? addslashes(strip_tags($post['description_ru'])) : '',
            "description_ua" => (strlen(trim(html_entity_decode(strip_tags($post['description_ua'], "<a>, <img>"))))>0) ? addslashes(strip_tags($post['description_ua'])) : '',
            "description_en" => (strlen(trim(html_entity_decode(strip_tags($post['description_en'], "<a>, <img>"))))>0) ? addslashes(strip_tags($post['description_en'])) : '',
            "is_active" => $is_active,
            "datetime_edit" => 'NOW()',
            "author" => USER_ID,
            "date_show_start" => $date_show_start,
            "date_show_finish" => $date_show_finish,
            "color" => $color,
            "url" => $url
        );
        $condition = array(
            "id"=>$post['id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."notifications",$elems, $condition)) {
            return true;
        }
        return false;
    }

    /**
     * Delete One Item
     *
     * @param $id
     * @return bool
     */
    public function delete($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."notifications", "id='$id' LIMIT 1")) {
                return true;
            }
        }
        return false;
    }

    public function getItem($id = 0){
        $id = intval($id);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        if ($id >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."notifications","*","id=$id LIMIT 1");
            if (!$temp) return false;
            else $temp = $temp[0];
            $temp['title_ru'] = str_replace($search, $replace, stripcslashes($temp['title_ru']));
            $temp['title_ua'] = str_replace($search, $replace, stripcslashes($temp['title_ua']));
            $temp['title_en'] = str_replace($search, $replace, stripcslashes($temp['title_en']));
            $temp['description_ru'] = stripcslashes($temp['description_ru']);
            $temp['description_ua'] = stripcslashes($temp['description_ua']);
            $temp['description_en'] = stripcslashes($temp['description_en']);
            $temp['url'] = stripcslashes($temp['url']);
            
            return $temp;
        }
        return false;
    }

    /**
     * Get list of Items
     *
     * @param int $page
     * @param int $perpage
     * @return array|bool|string
     */
    public function getList($page=1, $perpage=10){
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page = $perpage*$page;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."notifications","
				id as id,
				title_".D_S_LANG." as title,
				is_active,
				date_show_start,
				date_show_finish,
				color
				","1 ORDER BY date_show_start DESC, id DESC LIMIT $page, $perpage");
        if ($temp) {
            foreach ($temp as &$item){
                $item['title'] = stripslashes($item['title']);
            }
        }
        return $temp;
    }

    /**
     * Get Pages for List of Items
     *
     * @param int $page
     * @param int $perpage
     * @return array
     */
    public function getPages($page=1, $perpage=10){
        $pages = array();
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."notifications","COUNT(*) as C_N","1");
        $c_pages = intval($temp[0]['C_N']/$perpage);

        if ($c_pages<= 9){
            for ($i=0; $i<9; $i++){
                if ($i <= $c_pages) $pages[$i] = $i+1;
            }
        }
        if ($c_pages > 9){
            if ($page<6){
                for ($i=0; $i<9; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                //$pages[9] = "...";
            }
            if ($page>5){
                for ($i=$page-5; $i<$page+4; $i++) if ($i <= $c_pages) $pages[$i] = $i+1;
                //$pages[$page-6] = "...";
                //if ($page+4 <= $c_pages) $pages[$page+4] = "...";
            }
        }
        return $pages;
    }

}

