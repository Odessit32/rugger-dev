<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/timetable.php');
$timetable = new timetable;
$s = 0;

$date_now = date("Y-m-d").' 23:59:59';
$date_show = false;
$date_type = 'month';
$date_type_title = '';
$date_type_title_list = array();
$one_ch = false;
$ch_type = 'all';
$next = false;
$is_empty = false;

if (!empty($url->rest_path[$s])){
    // filter by championships
    if (substr($url->rest_path[$s], 0, 3) == 'ch-'){
        $one_ch = substr($url->rest_path[$s], 3);
        if ($one_ch != 'all'){
            $ch_type = 'one';
        }
        $s++;
    }
    // filter by date type
    if (substr($url->rest_path[$s], 0, 5) == 'date-'){
        $date_show = substr($url->rest_path[$s], 5);
        $date_type = 'date';
        $s++;
    } elseif (substr($url->rest_path[$s], 0, 5) == 'week-'){
        $date_show = substr($url->rest_path[$s], 5);
        $date_type = 'week';
        $s++;
    } elseif (substr($url->rest_path[$s], 0, 6) == 'month-'){
        $date_show = substr($url->rest_path[$s], 6);
        $date_type = 'month';
        $s++;
    }
}
if ($date_show){
    if (strlen($date_show)==10){
        $date_show_a = explode('-', $date_show);
        if (!empty($date_show_a[0]) && !empty($date_show_a[1]) && !empty($date_show_a[2]) &&
            is_numeric($date_show_a[0]) && is_numeric($date_show_a[1]) && is_numeric($date_show_a[2]) &&
            strlen($date_show_a[0])==4){
            switch($date_type){
                case 'date':
                    $date_now = date("Y-m-d", strtotime($date_show)).' 23:59:59';
                break;
                case 'week':
                    $w_day = date('w', strtotime($date_show));
                    if ($w_day>0){
                        $w_day = $w_day-1;
                    } else {
                        $w_day = 6;
                    }
                    $date_now = date("Y-m-d", strtotime($date_show.' +'.(6-$w_day).' days')).' 23:59:59';
                    break;
                case 'month':
                    $date_now = date("Y-m-t", strtotime($date_show)).' 23:59:59';
                    break;
            }
        }
    }
}
$smarty->assign("results_list", $timetable->getTimetableResults($date_type, $date_now, $one_ch, $is_empty));
$championships_wg_list = $timetable->getChampionshipsWGroups('done');
foreach($championships_wg_list as $chg_item){
    foreach($chg_item['data'] as $ch_item){
        if ($ch_item['address'] == $one_ch) {
            $ch_title = $ch_item['title'];
        }
    }
}
$smarty->assign("championships_wg_list", $championships_wg_list);
$smarty->assign("championships_wg_list_menu_class", ceil(count($championships_wg_list)/2));

switch($date_type){
    case 'date':
        if (strtotime($date_now)+60*60*24 < time()){
            $next = 'date-'.date("Y-m-d", strtotime($date_now)+60*60*24);
        }
        $prev = 'date-'.date("Y-m-d", strtotime($date_now)-60*60*24);
        $date_type_title = date("d ", strtotime($date_now)).$month[date("m", strtotime($date_now))];
        $date_type_address = 'date-'.date("Y-m-d", strtotime($date_now));
        break;
    case 'week':
        if (strtotime($date_now)+60*60*24*7 < time()){
            $next = 'week-'.date("Y-m-d", strtotime($date_now)+60*60*24*7);
        }
        $prev = 'week-'.date("Y-m-d", strtotime($date_now)-60*60*24*7);
        $w_day = date('w', strtotime($date_now));
        if ($w_day>0){
            $w_day = $w_day-1;
        } else {
            $w_day = 6;
        }
        $week_start = strtotime($date_now.' -'.$w_day.' days');
        $week_end = strtotime($date_now.' +'.(6-$w_day).' days');
        $date_type_title = $language['week'] . ': ' .
            date("d ", $week_start).$month[date("m", $week_start)] . ' - ' .
            date("d ", $week_end).$month[date("m", $week_end)];
        $date_type_address = 'week-'.date("Y-m-d", strtotime($date_now));
        break;
    case 'month':
        if (strtotime(date("Y-m-01", strtotime(date("Y-m-t", strtotime($date_now))." +1 day"))) < time()){
            $next = 'month-'.date("Y-m-01", strtotime($date_now.' +1 month'));
        }
        $prev = 'month-'.date("Y-m-01", strtotime(date("Y-m-01", strtotime($date_now)).' -1 day'));
        $date_type_title = $month_i[date("m", strtotime($date_now))];
        $dn_y = date("Y", strtotime($date_now));
        $dn_m = intval(date("n", strtotime($date_now)));
        for ($i=1; $i<13; $i++){
//            if ($i >= $dn_m){
            $date_type_title_list[] = array(
                'address' => 'month-'.date("Y", strtotime($date_now)).'-'.(($i<10)?'0':'').$i.'-01',
                'title' => $month_i[(($i<10)?'0':'').$i],
                'active' => ($i == $dn_m)?'yes':'no'
            );
//            }
        }
        $date_type_address = 'month-'.date("Y-m-01", strtotime($date_now));
        break;
}
if (date("Y", strtotime($date_now)) != date("Y")){
    $date_type_title .= ' '.date("Y", strtotime($date_now));
}
$smarty->assign("is_empty", $is_empty);
$smarty->assign("date_type", $date_type);
$smarty->assign("date_next", $next);
$smarty->assign("date_prev", $prev);
$smarty->assign("date_now", $date_now);
$smarty->assign("one_ch", $one_ch);
$smarty->assign("ch_type", $ch_type);
$smarty->assign("ch_title", (!empty($ch_title))?$ch_title:'');
$smarty->assign("month_now", $month_i[date("m", strtotime($date_now))]);
$smarty->assign("date_type_title", $date_type_title);
$smarty->assign("date_type_title_list", $date_type_title_list);
$smarty->assign("date_type_address", $date_type_address);
$smarty->assign("date_max", date("d.m.Y"));


// meta SEO functions start
$meta_seo_item = $url->page['p_id'];
$meta_seo_item_type = 'page';
include_once('classes/inc_meta_seo.php');
// meta SEO functions finish


include_once('classes/page.php');
$page = new page;


if ($url->page['p_adress'] == 'history'){
    include_once('classes/informers.php');
    $informers = new informers;
    $photo_informer = $informers->getPhotoInformer(15);
    $smarty->assign("photo_informer", $photo_informer);
}

$ph_page_item = $page->getPhPageItem($url->page['p_id']);
$smarty->assign("ph_page_item", $ph_page_item);

// Баннеры
include_once('classes/banners.php');
$banners = new banners;
$pbi = $banners->getPageBanInfList($url->page['p_id'], $url->page['p_c_banners']);
$smarty->assign("pbi", $pbi);

if ($pbi['classes']) {
    foreach ($pbi['classes'] as $item) {
        if (!empty($item) && file_exists('classes/'.$item)){
            include_once('classes/'.$item);
        }
    }
}