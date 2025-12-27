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

    public function getMessageItem($id = 0, $locator = ''){
        $id = intval($id);
        $search = array("'", '"', ' ');
        $replace = array('', '', '');
        $locator = str_replace($search, $replace, trim($locator));
        if ($locator == '' or $id<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","*","fb_id = '$id' AND fb_locator = '$locator' LIMIT 1");
        if ($temp) $temp = $temp[0];
        $temp['fb_text'] = nl2br($temp['fb_text']);
        return $temp;
    }

    public function saveMessage(){
        global $sitepath;
        global $url;

        $message = false;
        $search = array("'", '"');
        $replace = array('`', '`');
        $text = str_replace($search, $replace, trim($_POST['text']));
        if ($text == '' or $text == 'Comments' ) $message['error']['text'] = true;
        $title = str_replace($search, $replace, trim($_POST['title']));
        $email = str_replace($search, $replace, trim($_POST['email']));
        if ($email == '' or strpos(' '.$email, '@') < 1) $message['error']['email'] = true;
        $name = str_replace($search, $replace, trim($_POST['name']));
        if ($name == '' or $name == 'Name*' or $name == '²ì`ÿ*' or $name == 'Èìÿ*' ) $message['error']['name'] = true;

        if ($message['error']) return $message;

        $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","fb_id as id","fb_ip = '".$_SERVER["REMOTE_ADDR"]."' AND fb_text = '$text' AND fb_title = '$title' LIMIT 1");
        if ($temp) $message['error']['message'] = true;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","fb_id as id","fb_ip = '".$_SERVER["REMOTE_ADDR"]."' AND fb_datetime_add > NOW()-86400 LIMIT 20");
        if ($temp and count($temp) == 20) $message['error']['count'] = true;

        if ($message['error']) return $message;

        $locator = md5(time().$_SERVER["REMOTE_ADDR"]);
        $iData = array(
            $title,
            $text,
            "NOW()",
            '',
            $name,
            $email,
            $_SERVER["REMOTE_ADDR"],
            'mail',
            '',
            '',
            'no',
            $locator,
            'no',
            ''
        );
        $m_id = $this->hdl->addElem(DB_T_PREFIX."feedback", $iData);
        if ($m_id>0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."feedback","*","fb_id = '$m_id' LIMIT 1");
            if ($temp){
                $temp = $temp[0];
                foreach ($temp as $key=>$item) $message[$key] = $item;
            }
            // sending e-mail message
            $this->mail = new mail;

            //for client
            $vars = array(
                "sitepath" => $sitepath,
                "page_path" => $url->page['page_path'],
                "page_address" => $url->page['p_adress'],
                "locator" => $message['fb_locator'],
                "fb_id" => $message['fb_id']
            );
            $headers = array(
                "To" => $email,
                "From" => "webmaster@".$sitepath,
                "Reply-To" => '',
                "X-Mailer" => $sitepath,
                "Content-type" => "text/plain; charset=windows-1251"
            );
            $this->mail->sendMailTemplate(2, $vars, $headers, LANG);

            // for admin
            global $conf;
            if ($conf->conf_settings['feedback_is_send_admin']>0 AND strpos($conf->conf_settings['feedback_email_admin'], '@')>0) {
                $vars = array(
                    "date_time" => date("d.m.Y H:i"),
                    "fb_id" => $message['fb_id']
                );
                $headers = array(
                    "To" => $conf->conf_settings['feedback_email_admin'],
                    "From" => "webmaster@".$sitepath,
                    "Reply-To" => '',
                    "X-Mailer" => $sitepath,
                    "Content-type" => "text/plain; charset=windows-1251"
                );
                $this->mail->sendMailTemplate(1, $vars, $headers, LANG);
            }
        }
        return $message;
    }

}
?>
