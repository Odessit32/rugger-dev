<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class Redirects {
    protected $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // REDIRECTS ///////////////////////////////////////////////////////////////////////////////////////

    public function createRedirects($post){ // добавление новости
        if($post['is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['is_regexp']==true) $is_regexp ='yes';
        else $is_regexp = 'no';
        $post['url'] = trim($post['url']);
        $post['redirect_url'] = trim($post['redirect_url']);
        if (substr($post['url'], 0, 1) != '/') {
            $post['url'] = '/'.$post['url'];
        }
        if (substr($post['url'], 0, 6) == '/admin') {
            $post['url'] = substr($post['url'], 6);
        }
        if (substr($post['redirect_url'], 0, 1) != '/') {
            $post['redirect_url'] = '/'.$post['redirect_url'];
        }
        $post['url'] = str_replace(array('//', '//'), array('/', '/'), $post['url']);
        $post['redirect_url'] = str_replace(array('//', '//'), array('/', '/'), $post['redirect_url']);
        if (empty($post['url']) || empty($post['redirect_url']) || $post['url'] == '/') {
            return false;
        }
        $elem = array(
            addslashes($post['url']),
            addslashes($post['redirect_url']),
            $is_regexp,
            $is_active,
            'NOW()',
            'NOW()',
            USER_ID
        );
        if ($id = $this->hdl->addElem(DB_T_PREFIX."redirects", $elem)) {
            $this->_writeRedirectionFile();
            return true;
        }
        return false;
    }

    public function updateRedirects($post){ // редактирование новости
        if($post['is_active']==true) $is_active ='yes';
        else $is_active = 'no';
        if($post['is_regexp']==true) $is_regexp ='yes';
        else $is_regexp = 'no';
        $post['url'] = trim($post['url']);
        $post['redirect_url'] = trim($post['redirect_url']);
        if (substr($post['url'], 0, 1) != '/') {
            $post['url'] = '/'.$post['url'];
        }
        if (substr($post['url'], 0, 6) == '/admin') {
            $post['url'] = substr($post['url'], 6);
        }
        if (substr($post['redirect_url'], 0, 1) != '/') {
            $post['redirect_url'] = '/'.$post['redirect_url'];
        }
        $post['url'] = str_replace(array('//', '//'), array('/', '/'), $post['url']);
        $post['redirect_url'] = str_replace(array('//', '//'), array('/', '/'), $post['redirect_url']);
        if (empty($post['url']) || empty($post['redirect_url']) || $post['url'] == '/') {
            return false;
        }
        $elems = array(
            "url" => addslashes($post['url']),
            "redirect_url" => addslashes($post['redirect_url']),
            "is_active" => $is_active,
            "is_regexp" => $is_regexp,
            "datetime_edit" => 'NOW()',
            "author" => USER_ID
        );
        $condition = array(
            "id"=>$post['id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."redirects",$elems, $condition)) {
            $this->_writeRedirectionFile();
            return true;
        } else return false;
    }

    public function deleteRedirects($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."redirects", "id='$id'")) {
                $this->_writeRedirectionFile();
                return true;
            }
        }
        return false;
    }

    public function getRedirectsItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."redirects","*","id=$item");
            if (!empty($temp)) {
                $temp = $temp[0];
                $temp['url'] = stripcslashes($temp['url']);
                $temp['redirect_url'] = stripcslashes($temp['redirect_url']);
            }
            return $temp;
        }
        return false;
    }
    
    public function getRedirectsList($page=1, $perpage=10, $gallery = 0){
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $page = $perpage*$page;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."redirects","
				id,
				url,
				redirect_url,
				is_active
				","1 ORDER BY url DESC, redirect_url DESC LIMIT $page, $perpage");
        if ($temp) {
            foreach ($temp as &$item){
                $item['url'] = stripslashes($item['url']);
                $item['redirect_url'] = stripslashes($item['redirect_url']);
            }
        }
        return $temp;
    }

    public function getRedirectsPages($page=1, $perpage=10, $gallery = 0){
        $perpage = intval($perpage);
        if ($perpage < 2) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."redirects","COUNT(*) as C_N","1");
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

    public function getAuthorsList(){
        $temp = $this->hdl->selectElem(DB_T_PREFIX."admins","
                    a_id as id,
                    a_name as name,
                    a_f_name as f_name,
                    a_o_name as o_name ","1 ORDER BY a_name DESC, a_id asc");
        if ($temp) {
            foreach ($temp as $item){
                $list[$item['id']] = $item;
            }
            return $list;
        } else return false;
    }

    public function updateRedirectionFile(){
        return $this->_writeRedirectionFile();
    }

    private function _writeRedirectionFile() {
        $delimeter = "\n\n".'### --- redirects --- ###'."\n";
        $redirct_data = array();
        $temp = $this->hdl->selectElem(DB_T_PREFIX."redirects","
				id,
				url,
				redirect_url,
				is_active
				","1 ORDER BY url DESC, redirect_url DESC");
        if ($temp) {
            foreach ($temp as &$item){
                $item['url'] = stripslashes($item['url']);
                $item['redirect_url'] = stripslashes($item['redirect_url']);
                $redirct_data[] = 'Redirect 301 '.$item['url'].' '.$item['redirect_url'];
            }
        }
        $file = '../.htaccess';
        $myfile = fopen($file, "r") or die("Unable to open file!");
        $fite_text =  fread($myfile,filesize($file));
        fclose($myfile);
        if (!empty($fite_text)) {
            $fite_text_a = explode($delimeter, $fite_text);
            $fite_text_a[1] = implode("\n", $redirct_data);
        }

        $myfile = fopen($file, "w") or die("Unable to open file!");
        if (!empty($redirct_data)) {
            fwrite($myfile, implode($delimeter, $fite_text_a));
        } else {
            fwrite($myfile, $fite_text_a[0]);
        }
        fclose($myfile);
        return true;
    }

}

