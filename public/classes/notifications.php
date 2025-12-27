<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class Notifications extends clientBase{
    protected $hdl;

    public function __construct(){
        parent::__construct();
        $this->hdl = database::getInstance();
    }

    /**
     * Get List of actual Notifications
     *
     * @return array|bool
     */
    public function getList(){
        $date_now = date("Y-m-d H:i:00");
        $temp = $this->hdl->selectElem(DB_T_PREFIX."notifications ",
                "	id,
                    title_".S_LANG." AS title,
					description_".S_LANG." AS description,
					color,
					url,
					time_show,
					time_hide
				","
				    is_active='yes' AND
					date_show_start < '".$date_now."' AND
					date_show_finish > '".$date_now."'
				ORDER BY date_show_start DESC,
					id DESC "
            , false, false, 60 );

        if (!empty($temp) && is_array($temp)){
            $search = array("'", '"');
            $replace = array('&quot;', '&quot;');
            for ($i=0; $i<count($temp); $i++){
                $temp[$i]['title'] = str_replace($search, $replace, stripcslashes($temp[$i]['title']));
                $temp[$i]['description'] = trim(strip_tags(stripcslashes($temp[$i]['description']), "<a>, <b>, <i>, <em>, <img>, <strong>" ));
            }
            return $temp;
        }
        return false;
    }

}

