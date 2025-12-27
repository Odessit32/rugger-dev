<?php
if (!defined('LANG')) {
    define('LANG', 'ru');
}
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class conf_vars{
    private $hdl;
    public $conf_settings = false; //переменная с переменными из БД для сайта
    public $conf_vars = false; //переменная с переменными из БД для сайта

    public function __construct(){
        $this->hdl = database::getInstance();
        $this->conf_settings = $this->getConfSettings();
        $this->conf_vars = $this->getConfVars();
    }

    public function getConfVars(){
        if (!empty($this->conf_vars)) {
            return $this->conf_vars;
        }
        $list = [];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."config_vars","*"," cnv_lang = '".LANG."'");
        if ($temp)
            if (count($temp)>0)
                foreach($temp as $val)
                    $list[$val['cnv_name']] = $val['cnv_value'];
        return $list;
    }

    public function getConfSettings(){
        if (!empty($this->conf_settings)) {
            return $this->conf_settings;
        }
        $list = [];
        $temp = $this->hdl->selectElem(DB_T_PREFIX."settings","*"," 1 ORDER BY set_id");
        if ($temp){
            if (count($temp)>0){
                foreach($temp as $val){
                    $list[$val['set_name']] = $val['set_value'];
                }
                return $list;
            }
        }
        return false;
    }

    public function getLandSettings(){
        $list_temp = $this->hdl->selectElem(DB_T_PREFIX."settings_lang","*","1 ORDER BY sl_id ASC");
        $list = array();
        $lang_switch = 0;
        if (is_array($list_temp)) {
            foreach ($list_temp as $item) {
                $list[$item['sl_title']] = $item;
                if ($item['sl_is_active'] == 'yes') {
                    $lang_switch++;
                }
            }
        }

        return array(
            'list'          => $list,
            'lang_switch'   => ( $lang_switch > 1 ) ? true : false
        );
    }

    public function getMonth($lang = ''){
        $month = false;
        if ($lang == 'ukr') {
            $month = array(
                '01' => 'січня',
                '02' => 'лютого',
                '03' => 'березня',
                '04' => 'квітня',
                '05' => 'травня',
                '06' => 'червня',
                '07' => 'липня',
                '08' => 'серпня',
                '09' => 'вересня',
                '10' => 'жовтня',
                '11' => 'листопада',
                '12' => 'грудня'
            );
        }
        if ($lang == 'rus') {
            $month = array(
                '01' => 'января',
                '02' => 'февраля',
                '03' => 'марта',
                '04' => 'апреля',
                '05' => 'мая',
                '06' => 'июня',
                '07' => 'июля',
                '08' => 'августа',
                '09' => 'сентября',
                '10' => 'октября',
                '11' => 'ноября',
                '12' => 'декабря'
            );
        }
        if ($lang == 'eng') {
            $month = array(
                '01' => 'january',
                '02' => 'february',
                '03' => 'march',
                '04' => 'april',
                '05' => 'may',
                '06' => 'june',
                '07' => 'july',
                '08' => 'august',
                '09' => 'september',
                '10' => 'october',
                '11' => 'november',
                '12' => 'december'
            );
        }
        return $month;
    }

    public function getMonthI($lang = ''){
        $month = false;
        if ($lang == 'ukr') {
            $month = array(
                '01' => 'січень',
                '02' => 'лютий',
                '03' => 'березень',
                '04' => 'квітннь',
                '05' => 'травннь',
                '06' => 'червень',
                '07' => 'липень',
                '08' => 'серпень',
                '09' => 'вересень',
                '10' => 'жовтень',
                '11' => 'листопад',
                '12' => 'грудень'
            );
        }
        if ($lang == 'rus') {
            $month = array(
                '01' => 'январь',
                '02' => 'февраль',
                '03' => 'март',
                '04' => 'апрель',
                '05' => 'май',
                '06' => 'июнь',
                '07' => 'июль',
                '08' => 'август',
                '09' => 'сентябрь',
                '10' => 'октябрь',
                '11' => 'ноябрь',
                '12' => 'декабрь'
            );
        }
        if ($lang == 'eng') {
            $month = array(
                '01' => 'january',
                '02' => 'february',
                '03' => 'march',
                '04' => 'april',
                '05' => 'may',
                '06' => 'june',
                '07' => 'july',
                '08' => 'august',
                '09' => 'september',
                '10' => 'october',
                '11' => 'november',
                '12' => 'december'
            );
        }
        return $month;
    }

    public function getWDay($lang = ''){
        $wday = false;
        if ($lang == 'ukr') {
            $wday = array(
                '1' => 'пн',
                '2' => 'вт',
                '3' => 'ср',
                '4' => 'чт',
                '5' => 'пт',
                '6' => 'сб',
                '7' => 'нд'
            );
        }
        if ($lang == 'rus') {
            $wday = array(
                '1' => 'пн',
                '2' => 'вт',
                '3' => 'ср',
                '4' => 'чт',
                '5' => 'пт',
                '6' => 'сб',
                '7' => 'вс'
            );
        }
        if ($lang == 'eng') {
            $wday = array(
                '1' => 'mon',
                '2' => 'tue',
                '3' => 'wed',
                '4' => 'thu',
                '5' => 'fri',
                '6' => 'sat',
                '7' => 'sun'
            );
        }
        return $wday;
    }
    public function getWDayL($lang = ''){
        $wday = false;
        if ($lang == 'ukr') {
            $wday = array(
                '1' => 'понеділок',
                '2' => 'вівторок',
                '3' => 'середа',
                '4' => 'четвер',
                '5' => 'п`ятниця',
                '6' => 'субота',
                '7' => 'неділя'
            );
        }
        if ($lang == 'rus') {
            $wday = array(
                '1' => 'понедельник',
                '2' => 'вторник',
                '3' => 'среда',
                '4' => 'четверг',
                '5' => 'пятница',
                '6' => 'суббота',
                '7' => 'воскресенье'
            );
        }
        if ($lang == 'eng') {
            $wday = array(
                '1' => 'monday',
                '2' => 'tuesday',
                '3' => 'wednesday',
                '4' => 'thursday',
                '5' => 'friday',
                '6' => 'saturday',
                '7' => 'sunday'
            );
        }
        return $wday;
    }
}
?>
