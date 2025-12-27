<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class feedback{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // Сообщения ///////////////////////////////////////////////////////////////////////////////////////

    public function sendFeedbackResponse($post){ //
        global $sitepath;

        if($post['fb_is_posted']==true) $fb_is_posted ='yes';
        else $fb_is_posted = 'no';
        $post['fb_id'] = intval($post['fb_id']);
        if ($post['fb_id']<1) return false;

        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $post['fb_response'] = (strlen(trim(html_entity_decode(strip_tags($post['fb_response']))))>0) ? addslashes($post['fb_response']) : NULL;
        $elems = array(
            "fb_response" => $post['fb_response'],
            "fb_datetime_response" => 'NOW()',
            "fb_is_posted" => $fb_is_posted
        );
        $condition = array(
            "fb_id"=>$post['fb_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."feedback",$elems, $condition)) {
            if ($fb_is_posted == 'yes'){
                $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","*","fb_id = '".$post['fb_id']."' LIMIT 1");
                if ($temp){
                    $temp = $temp[0];
                    // sending e-mail message
                    $this->mail = new mail;
                    global $sitepath;

                    $vars = array(
                        "response" => $post['fb_response'],
                        "sitepath" => $sitepath
                    );
                    $headers = array(
                        "To" => $temp['fb_email'],
                        "From" => "webmaster@".$sitepath,
                        "Reply-To" => '',
                        "X-Mailer" => $sitepath,
                        "Content-type" => "text/plain; charset=windows-1251"
                    );
                    $this->mail->sendMailTemplate(3, $vars, $headers, 'eng');
                }
            }
            return true;
        }
        return false;
    }

    public function updateFeedback($post){ // редактирование сообщения
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        $post['fb_id'] = intval($post['fb_id']);
        if ($post['fb_id']<1) return false;
        $elems = array(
            "fb_title" => (strlen(trim(html_entity_decode(strip_tags($post['fb_title']))))>0) ? addslashes($post['fb_title']) : NULL,
            "fb_text" => (strlen(trim(html_entity_decode(strip_tags($post['fb_text']))))>0) ? addslashes($post['fb_text']) : NULL,
            "fb_name" => (strlen(trim(html_entity_decode(strip_tags($post['fb_name']))))>0) ? addslashes($post['fb_name']) : NULL,
            "fb_email" => (strlen(trim(html_entity_decode(strip_tags($post['fb_email']))))>0) ? addslashes($post['fb_email']) : NULL,
            "fb_datetime_edit" => 'NOW()'
        );
        $condition = array(
            "fb_id"=>$post['fb_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."feedback",$elems, $condition)) return true;
        else return false;
    }

    public function getFeedbackItem($item = 0){
        $item = intval($item);
        $search = array("'", '"');
        $replace = array('&quot;', '&quot;');
        if ($item > 0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","*","fb_id=$item");
            if ($temp){
                $temp = $temp[0];
                if ($temp['fb_id'] > 0){
                    $elems = array(
                        "fb_datetime_viewed" => 'NOW()',
                        "fb_is_viewed" => 'yes'
                    );
                    $condition = array(
                        "fb_id"=>$item,
                        "fb_datetime_viewed"=>'0000-00-00 00:00:00'
                    );
                    $this->hdl->updateElem(DB_T_PREFIX."feedback",$elems, $condition);
                }

                $temp['fb_text'] = str_replace($search, $replace, stripcslashes($temp['fb_text']));
                $temp['fb_title'] = str_replace($search, $replace, stripcslashes($temp['fb_title']));
                $temp['fb_name'] = str_replace($search, $replace, stripcslashes($temp['fb_name']));
                $temp['fb_email'] = str_replace($search, $replace, stripcslashes($temp['fb_email']));
                $temp['fb_response'] = str_replace($search, $replace, stripcslashes($temp['fb_response']));
            }
            return $temp;
        }
        return false;
    }

    public function deleteFeedback($id){
        $id = intval($id);
        if ($id>0){
            if ($this->hdl->delElem(DB_T_PREFIX."feedback", "fb_id='$id'")) return true;
        }
        return false;
    }

    public function getFeedbackList($page=1, $perpage=10, $fbc = 0){
        if ($fbc == 'none') {
            $fbc = 'none';
            $q_fbc = " AND fb_type = '$fbc'";
        } elseif ($fbc == 'mail') {
            $fbc = 'mail';
            $q_fbc = " AND fb_type = '$fbc'";
        } elseif ($fbc == 'request') {
            $fbc = 'request';
            $q_fbc = " AND fb_type = '$fbc'";
        } else {
            $fbc = 'all';
            $q_fbc = '';
        }
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage<2) $perpage = 10;
        $page = $perpage*$page;
        return $this->hdl->selectElem(DB_T_PREFIX."feedback","*","1 $q_fbc ORDER BY fb_datetime_add DESC, fb_id DESC LIMIT $page, $perpage");
    }

    public function getFeedbackPages($page=1, $perpage=10, $fbc = 0){
        if ($fbc == 'none') {
            $fbc = 'none';
            $q_fbc = " AND fb_type = '$fbc'";
        } elseif ($fbc == 'mail') {
            $fbc = 'mail';
            $q_fbc = " AND fb_type = '$fbc'";
        } elseif ($fbc == 'request') {
            $fbc = 'request';
            $q_fbc = " AND fb_type = '$fbc'";
        } else {
            $fbc = 'all';
            $q_fbc = '';
        }
        $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","COUNT(*) as C_N","1 $q_fbc");
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

    public function getFeedbackSettings(){
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

    // LETTERS ///////////////////////////////////////////////////////////////////////////////////////

    public function getLetter($item = 0){
        $item = intval($item);
        if ($item >=0) {
            if ($item == 0) $item = 1;
            $temp = $this->hdl->selectElem(DB_T_PREFIX."config_vars","*","cnv_name='letter_$item'");
            if ($temp){
                for ($i=0; $i<count($temp); $i++){
                    $list[$temp[$i]['cnv_lang']] = $temp[$i];
                }
                return $list;
            }
        }
        return false;
    }

    public function getLetterT($item = 0){
        $item = intval($item);
        if ($item >=0) {
            if ($item == 0) $item = 1;
            $temp = $this->hdl->selectElem(DB_T_PREFIX."config_vars","*","cnv_name='letter_title_$item'");
            if ($temp){
                for ($i=0; $i<count($temp); $i++){
                    $list[$temp[$i]['cnv_lang']] = $temp[$i];
                }
                return $list;
            }
        }
        return false;
    }

    public function saveLetter(){
        $item = intval($_GET['l_item']);
        if ($item == 0) $item = 1;
        if ($_POST['cnv_name'] != 'letter_'.$item) return false;
        if ($_POST['lang'] == 'ukr') $_POST['lang'] = 'ukr';
        elseif ($_POST['lang'] == 'rus') $_POST['lang'] = 'rus';
        elseif ($_POST['lang'] == 'eng') $_POST['lang'] = 'eng';
        else return false;
        $search = array("'", '"');
        $replace = array('`', '`');
        if ($item>0){
            $elems = array(
                "cnv_value" => (strlen(trim(html_entity_decode(strip_tags($_POST['cnv_value']))))>0) ? str_replace($search, $replace, strip_tags($_POST['cnv_value'])) : NULL
            );
            $condition = array(
                "cnv_name"=>'letter_'.$item,
                "cnv_lang"=>$_POST['lang']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."config_vars",$elems, $condition)) return true;
        }
        return false;
    }

    public function saveLetterT(){
        $item = intval($_GET['l_item']);
        if ($item == 0) $item = 1;
        if ($_POST['cnv_name'] != 'letter_title_'.$item) return false;
        if ($_POST['lang'] == 'ukr') $_POST['lang'] = 'ukr';
        elseif ($_POST['lang'] == 'rus') $_POST['lang'] = 'rus';
        elseif ($_POST['lang'] == 'eng') $_POST['lang'] = 'eng';
        else return false;
        $search = array("'", '"');
        $replace = array('`', '`');
        if ($item>0){
            $elems = array(
                "cnv_value" => (strlen(trim(html_entity_decode(strip_tags($_POST['cnv_value']))))>0) ? str_replace($search, $replace, strip_tags($_POST['cnv_value'])) : NULL
            );
            $condition = array(
                "cnv_name"=>'letter_title_'.$item,
                "cnv_lang"=>$_POST['lang']
            );
            if ($this->hdl->updateElem(DB_T_PREFIX."config_vars",$elems, $condition)) return true;
        }
        return false;
    }

}
?>
