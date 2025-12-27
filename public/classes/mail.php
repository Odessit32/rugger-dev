<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class mail{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    // SEND MAIL ///////////////////////////////////////////////////////////////////////////////////////

    public function sendMailTemplate($template=0, $vars=false, $headers=false, $lang = 'eng'){
        if ($lang == 'rus') $lang = 'rus';
        elseif ($lang == 'ukr') $lang = 'ukr';
        else $lang = 'eng';

        $template = intval($template);
        if ($template<1) return false;
        $letter = $this->hdl->selectElem(DB_T_PREFIX."config_vars",
            "	cnv_value
					","	cnv_name = 'letter_".$template."' AND 
						cnv_lang = '".$lang."'
						LIMIT 1");
        $title = $this->hdl->selectElem(DB_T_PREFIX."config_vars",
            "	cnv_value
					","	cnv_name = 'letter_title_".$template."' AND 
						cnv_lang = '".$lang."'
						LIMIT 1");
        if ($letter) {
            if ($title) $title = $title[0]['cnv_value'];
            $letter = $letter[0]['cnv_value'];
            $header = '';
            if ($vars) {
                foreach ($vars as $key=>$item) {
                    $letter = str_replace('{$'.$key.'}', $item, $letter);
                    $title = str_replace('{$'.$key.'}', $item, $title);
                }
            }
            $letter = wordwrap($letter, 70, "\n");
            if ($headers) foreach ($headers as $key=>$item) if ($key != 'To') $header .= $key.': '.$item."\r\n";
            else $header = 'From: webmaster@localhost' . "\r\n" . 'Reply-To: ' . "\r\n" . 'X-Mailer: ' . "\r\n" . 'Content-type: text/plain; charset=windows-1251';
            return mail($headers['To'], $title, $letter, $header);
        }
    }

}
?>
