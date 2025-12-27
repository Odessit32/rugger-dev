<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class champ{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // CHAMPs /////////////////////////////////////////////////////////////////////////////////////

    public function createChamp($post){
        if($post['is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $search = array("'", '"');
        $replace = array('', '');
        $elem = array(
            str_replace($search, $replace, $post['title_ru']),
            str_replace($search, $replace, $post['title_ua']),
            str_replace($search, $replace, $post['title_en']),
            addslashes($post['description_ru']),
            addslashes($post['description_ua']),
            addslashes($post['description_en']),
            intval($post['order']),
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID,
            addslashes(str_replace($search_a, $replace_a, strtolower($post['address'])))
        );
        if ($this->hdl->addElem(DB_T_PREFIX."champ", $elem)) return true;
        else return false;
    }

    public function updateChamp($post){
        if ($post['id'] <1) return false;
        if($post['is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        $search_a = array("'", '"', ' ', '_');
        $replace_a = array('', '', '-', '-');
        $search = array("'", '"');
        $replace = array('', '');
        $elems = array(
            "title_ru" => str_replace($search, $replace, $post['title_ru']),
            "title_ua" => str_replace($search, $replace, $post['title_ua']),
            "title_en" => str_replace($search, $replace, $post['title_en']),
            "description_ru" => addslashes($post['description_ru']),
            "description_ua" => addslashes($post['description_ua']),
            "description_en" => addslashes($post['description_en']),
            "`order`" => intval($post['order']),
            "is_active" => $is_active,
            "datetime_edit" => 'NOW()',
            "author" => USER_ID,
            "address" => addslashes(str_replace($search_a, $replace_a, strtolower($post['address'])))
        );
        $condition = array(
            "id"=>$post['id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."champ",$elems, $condition)) return true;
        else return false;
    }

    public function deleteChamp($item){
        $item = intval($item);
        if ($item>0){
            if ($this->hdl->delElem(DB_T_PREFIX."champ", "id='$item'")) return true;
            else return false;
        }else return false;
    }

    public function getChampItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."champ","*","id=$item");
            $temp[0]['title_ru'] = stripcslashes($temp[0]['title_ru']);
            $temp[0]['title_ua'] = stripcslashes($temp[0]['title_ua']);
            $temp[0]['title_en'] = stripcslashes($temp[0]['title_en']);
            $temp[0]['description_ru'] = stripcslashes($temp[0]['description_ru']);
            $temp[0]['description_ua'] = stripcslashes($temp[0]['description_ua']);
            $temp[0]['description_en'] = stripcslashes($temp[0]['description_en']);
            return $temp[0];
        } else return false;
    }

    public function getChampListNE(){
        return $this->hdl->selectElem(DB_T_PREFIX."champ, ".DB_T_PREFIX."team",
            "	id,
                title_ru",
            "	t_id = id
            GROUP BY id
            ORDER BY title_ru ASC");
    }

    public function getChampList(){
        return $this->hdl->selectElem(DB_T_PREFIX."champ","*","1 ORDER BY `order` DESC, title_ru ASC, id ASC");
    }

    public function getChampListID(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."champ","*","1 ORDER BY `order` DESC, id ASC");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['id']] = $item;
            }
            return $list;
        } else return false;
    }

}

