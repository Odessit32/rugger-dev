<?php
include_once('classes/config.php');
include_once('classes/DB.php');
include_once('classes/conf_vars.php');
include_once('classes/client_base.php');


$db = new database;
//$db->connect;
$echo_ = '';

$conf = new conf_vars;
$month = $conf->getMonthI('rus');

// адрес сайта
$sitepath = SERVER."/";
$sitepath .= (SITEPATH != '/') ? SITEPATH.'/' : '' ;

//if(isset($_SESSION['user_name'])){
// проверка прав пользователя
//if (count($_GET)>0 and USER_IS_ADMIN == 'no' and USER_IS_PUBLISHER == 'no')
//	if (!$user->checkAdminRights() and USER_IS_ADMIN != 'yes') exit;
$lang = '';
if (!empty($_GET['lang'])){
    $lang = $_GET['lang'];
}
switch($lang){
    case 'ukr':
        $lang = 'ukr';
        define('LANG', 'ukr');
        define('S_LANG', 'ua');
        break;
    case 'eng':
        $lang = 'eng';
        define('LANG', 'eng');
        define('S_LANG', 'en');
        break;
default:
    $lang = 'rus';
    if (!defined('LANG')) define('LANG', 'rus');
    if (!defined('S_LANG')) define('S_LANG', 'ru');
}
// месяцы
$m = array(
    "01" => "января",
    "02" => "февраля",
    "03" => "марта",
    "04" => "апреля",
    "05" => "мая",
    "06" => "июня",
    "07" => "июля",
    "08" => "августа",
    "09" => "сентября",
    "10" => "октября",
    "11" => "ноября",
    "12" => "декабря"
);
include_once('classes/language/lang_'.S_LANG.'.php');
$action = '';
if (!empty($_POST['action'])){
    $action = $_POST['action'];
}
switch($action) {
    case 'vote': {
        $db->delElem(DB_T_PREFIX."vote_code", "TO_DAYS(vtc_datetime) < TO_DAYS(NOW())-2", true);
        $id = intval($_POST['id']);
        $a = intval($_POST['a']);
        $replace = array(' ', ',', ';');
        $search = array('','','');
        $code = str_replace($search, $replace, $_POST['c']);
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($code != ''){
            $temp = $db->selectElem(DB_T_PREFIX."vote_code","*","vtc_code = '$code' AND vtc_ip = '".$ip."' AND TO_DAYS(vtc_datetime) >= TO_DAYS(NOW())-2 LIMIT 1");
            if ($temp) {
                $db->delElem(DB_T_PREFIX."vote_code", "vtc_id = '".$temp[0]['vtc_id']."'");
            } else {
                $code = '';
            }
        }
        if ($id > 0 && $code != ''){
            if (strlen($ip) < 7) exit();
            $voted = true;

            $temp = $db->selectElem(DB_T_PREFIX."vote","vt_id,
			        vt_question_".S_LANG." as vt_question,
			        vt_a_count,
			        vt_is_active,
			        vt_date_from,
			        vt_date_to,
			        vt_always,
			        vt_voters_type","vt_is_active = 'yes' AND vt_id = '$id' LIMIT 1");
            $temp_a = $db->selectElem(DB_T_PREFIX."vote_answer","*","vta_id = '$a' LIMIT 1");
            if ($temp && $temp_a) {
                $temp = $temp[0];
                $temp_a = $temp_a[0];

                $temp_ip = $db->selectElem(DB_T_PREFIX."voters","vts_id, vts_datetime","vts_vt_id = '".$id."' AND vts_ip = '".$_SERVER["REMOTE_ADDR"]."' ORDER BY vts_datetime DESC LIMIT 1");

                if ($temp['vt_voters_type'] == 'no_ban'){
                    $voted = false;
                } elseif ($temp['vt_voters_type'] == 'ip' && !$temp_ip) {
                    $voted = false;
                } elseif ($temp['vt_voters_type'] == 'day_ip') {
                    if ($temp_ip){
                        $temp_ip = $temp_ip[0];
                        if ((time()-strtotime($temp_ip['vts_datetime'])) > 86400) $voted = false;
                    } else $voted = false;
                }
                if (((strtotime($temp['vt_date_from'])>time() or strtotime($temp['vt_date_to'])<time()) and $temp['vt_always'] == 'no') or $voted) $result = true;
                elseif ($a>0) {
                    // saving result ///////
                    $result = false;
                    $elem = array(
                        $id,
                        $a,
                        $ip,
                        'NOW()'
                    );
                    if ($db->addElem(DB_T_PREFIX."voters", $elem)) {
                        $temp["vt_a_count"]++;
                        $elems = array(
                            "vt_a_count" => $temp["vt_a_count"]
                        );
                        $condition = array(
                            "vt_id"=>$id
                        );
                        if ($db->updateElem(DB_T_PREFIX."vote",$elems, $condition)) $result = true;

                        $temp_a["vta_a_count"]++;
                        $elems_a = array(
                            "vta_a_count" => $temp_a["vta_a_count"]
                        );
                        $condition_a = array(
                            "vta_id"=>$a
                        );
                        if ($db->updateElem(DB_T_PREFIX."vote_answer",$elems_a, $condition_a)) $result = true;
                    }
                } else $result = false;
                // result /////////
                if ($result == true) {
                    $temp['img'] = "https://chart.googleapis.com/chart?cht=p3&chd=t:";
                    $temp['answers'] = _getVoteAnswer($temp['vt_id']);
                    $t = '';
                    $temp['img'] = "https://chart.googleapis.com/chart?cht=p3&chd=t:";
                    foreach ($temp['answers'] as $i=>$val){
                        $temp['answers'][$i]['percent'] = round(($val['vta_a_count']/$temp['vt_a_count'])*100);
                        if ($temp['answers'][$i]['percent']>0) {
                            $temp['img'] .= $temp['answers'][$i]['percent'];
                            if ($i<count($temp['answers'])-1 and $val['vta_answer'] != '') $temp['img'] .= ",";
                        }
                        if ($val['vta_answer'] != '') {
                            $t .= $i;
                            if ($i<count($temp['answers'])-1) $t .= "|";
                        }
                    }
                    $temp['img'] .= "&chs=305x150&chl=".$t."&chco=003f7c";

                    //$echo_ .= '<div class="img"><img src="'.$temp['img'].'" border="0" /></div>';
                }
                $echo_ .= '<div class="question">'.strip_tags(stripslashes($temp['vt_question'])).'</div>';
                //if ($result == true) $echo_ .= '<form method="post">';
                foreach ($temp['answers'] as $i=>$val){
                    if ($val['vta_answer'] != '') {
                        if ($result == true) {
                            $echo_ .= '<div class="answer">'.$i.'. '.$val['vta_answer'].'</div>';
                            $echo_ .= '<div class="res"><div class="res_b"><img src="https://'.$sitepath.'images/vote_bg_b.gif" height="20" width="'.$val['percent'].'%" border="0" /></div><div class="res_text'.(($val['percent']<50)?'_w':'').'">'.$val['percent'].'%</div></div>';
                        } else $echo_ .= '<div class="answer">'.$i.'. <label><input type="radio" name="answer" value="'.$i.'" /> '.$val['vta_answer'].'</label></div>';
                    }
                }
                //if ($result == true) $echo_ .= '<div class="button"><input type="submit" class="submit_b" name="sub_vote" value="Проголосовать"></div></form>';
            }

            echo $echo_;
//            echo iconv("cp1251", "UTF-8", $echo_);
        }
        break;
    }
    // показать календарь для информера результатов, вкладка "Скоро"
    case 'inf_result_table': {
        $ch_id = (int)$_POST['ch_id'];
        if ($ch_id<1) {
            return false;
        }
        $echo_ = '';
        include_once('classes/informers.php');
        $informers = new informers;
        $result_informer_table = $informers->getResultInformer(false, 3, $ch_id);
        if (!empty($result_informer_table['tables'])){
            $echo_ .= '<div class="comp_title"><div class="item_selected '. ((count($result_informer_table['ch_other_list']) > 1) ? ' more_select' : '') .'">'.$result_informer_table['tables_title'].'</div>';
            if (count($result_informer_table['ch_other_list']) > 1) {
                $echo_ .= '<ul class="ul_other_menu">';
                foreach ($result_informer_table['ch_other_list'] as $item) {
                    $echo_ .= '<li data-ch_id="' . $item['ch_id'] . '"' . (($item['active']) ? ' class="active"' : '') . '>' . $item['title'] . '</li>';
                }
                $echo_ .= '</ul>';
            }
            $echo_ .= '</div>';
            if ($result_informer_table['tables_type'] == 1){
                foreach ($result_informer_table['tables'] as $item_t) {
                    //$echo_ .= '<div class="table_title">'.$item_t['title'].'</div>';
                    $echo_ .= '<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><th>№</th><th>Команда</th><th>И</th><th>В</th><th>Н</th><th>П</th><th>РИО</th><th>О</th></tr>';
                    $i=0;
                    foreach ($item_t['data'] as $item){
                        $i++;
                        $echo_ .= '<tr>';
                        $echo_ .= '<td align="center">'.$i.'</td>';
                        $echo_ .= '<td align="center">' . (($item['t_is_detailed'] == 'yes') ? '<a href="{$sitepath}team/{$item.t_id}"><b>'.$item['title'].'</b></a>' : '<b>'.$item['title'].'</b>') . '</td>';
                        $echo_ .= '<td align="center">' . ($item['games']>0 ? $item['games'] : '-' ) . '</td>';
                        $echo_ .= '<td align="center">' . ($item['win']>0 ? $item['win'] : '-' ) . '</td>';
                        $echo_ .= '<td align="center">' . ($item['draw']>0 ? $item['draw'] : '-' ) . '</td>';
                        $echo_ .= '<td align="center">' . ($item['loss']>0 ? $item['loss'] : '-' ) . '</td>';
                        $echo_ .= '<td align="center">' . ($item['p_scored']>0 ? $item['p_scored'] : '' ) . '-' . ($item['p_missed']>0 ? $item['p_missed'] : '' ) . '</td>';
                        $echo_ .= '<td align="center">' . ($item['p']>0 ? $item['p'] : '-' ) . '</td>';
                        $echo_ .= '</tr>';
                    }
                    $echo_ .= '</table>';
                }
            } elseif ($result_informer_table['tables_type'] == 2) {
                $echo_ .= '<h4>'.$result_informer_table['tables']['title'].'</h4>';
                $echo_ .= '<table border="0" cellspacing="0" cellpadding="0" width="100%">';
                $echo_ .= '<tr><th>№</th><th>'.$language['Team'].'</th><th>'.$language['points'].'</th></tr>';
                $i=0;
                foreach ($result_informer_table['tables']['data'] as $item) {
                    $i++;
                    $echo_ .= '<tr>';
                    $echo_ .= '<td align="center">'.$i.'</td>';
                    $echo_ .= '<td align="center">'. ($item['t_is_detailed'] == 'yes' ? '<a href="/team/'.$item['t_id'].'"><b>'.$item['title'].'</b></a>' : '<b>'.$item['title'].'</b>' ) . '</td>';
                    $echo_ .= '<td align="center">'.$item['p'].'</td>';
                    $echo_ .= '</tr>';
                }
                $echo_ .= '</table>';
            }
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
    case 'inf_result_soon': {
        $date_soon = explode('.', $_POST['date_soon']);
        if (count($date_soon) != 3) {
            return '';
        }
        $echo_ = '';
        include_once('classes/informers.php');
        $informers = new informers;
        $time_soon = strtotime((int)$date_soon[0].'-'.(int)$date_soon[1].'-'.(int)$date_soon[2]);
        $result_informer = $informers->getResultInformer($time_soon, 1);
        if (!empty($result_informer['soon'])){
            foreach ($result_informer['soon'] as $item) {
                if ($item['an_type']=='game'){
                    $echo_ .= '<a class="game_link_popup" href="https://'.$sitepath.'game/'.$item['g_id'].'/small" title="'.$item['owner']['title'].' - '.$item['guest']['title'].'">';
                    $echo_ .= '<div class="item">';
                    $echo_ .= '<div class="date">'.date("d.m.Y", strtotime($item['datetime'])).(!empty($item['g_is_schedule_time']) && $item['g_is_schedule_time'] == 'yes' ?
                            'в ' . date("H:M", strtotime($item['datetime'])) . ' ' : ' ' ) . '</div>';
                    $echo_ .= '<div class="game">'.$item['owner']['title'] . ' - ' . $item['guest']['title'] . '</div>';
                    $echo_ .= '<div class="c_title">' . $item['title'] . '</div>';
//                    $echo_ .= '<div class="logo_l">';
//                        $echo_ .= ($item['owner']['photo_main']?
//                            '<img src="/upload/photos/'.$item['owner']['photo_main']['ph_informer'].
//                            '" alt="'.$item['owner']['title'].'" style="max-width: 100px; max-height: 88px;" />':
//                            '<img src="/images/def_logo.jpg" alt="" border="0">').'<br>';
//                    $echo_ .= $item['owner']['title'].'</div>';
//                    $echo_ .= '<div class="center">VS</div>';
//                    $echo_ .= '<div class="logo_r">';
//                        $echo_ .= ($item['guest']['photo_main']?
//                            '<img src="upload/photos/'.$item['guest']['photo_main']['ph_informer'].
//                            '" alt="'.$item['guest']['title'].'" style="max-width: 100px; max-height: 88px;" />':
//                            '<img src="images/def_logo.jpg" alt="" border="0">').'<br>';
//                    $echo_ .= $item['guest']['title'].'</div>';
//                    $echo_ .= '</div></div>';
                    $echo_ .= '</a>';
                }
                if ($item['an_type']=='competition'){
                    $echo_ .= '<a href="/competitions/'.$item['chl_address'].'/'.$item['chg_address'].'/'.$item['ch_address'].'/'.
                        $item['cp_tour'].'/'.$item['cp_substage'].'/'.$item['g_cp_id'].'">';
                    $echo_ .= '<div class="item"><div class="date">'.date("d.m.Y", strtotime($item['datetime'])).
                        ($item['g_is_schedule_time'] == 'yes' ? 'в ' . date("H:M", strtotime($item['datetime'])) . ' ' : ' ' ).
                        '<br/>'.$item['chl_title'].'. '.$item['chg_title'].'. '.$item['ch_title'].'. '.$item['cp_title'].'.</div></div>';
                    $echo_ .= '</a>';
                }
            }
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
    case 'inf_result_game_list': {
        $date_game_list = explode('.', $_POST['date_game_list']);
        if (count($date_game_list) != 3) {
            return '';
        }
        $echo_ = '';
        include_once('classes/informers.php');
        $informers = new informers;
        $time_game_list = strtotime((int)$date_game_list[0].'-'.(int)$date_game_list[1].'-'.(int)$date_game_list[2]);
        $result_informer = $informers->getResultInformer($time_game_list, 2);
        if (!empty($result_informer['game_list'])){
            foreach ($result_informer['game_list'] as $item) {
                if ($item['an_type']=='game'){
                    $echo_ .= '<a href="https://'.$sitepath.'game/'.$item['g_id'].'" title="'.$item['owner']['title'].' - '.$item['guest']['title'].'">';
                    $echo_ .= '<div class="item">';
                    $echo_ .= '<div class="date">'.date("d.m.Y", strtotime($item['datetime'])).($item['g_is_schedule_time'] == 'yes' ?
                            'в ' . date("H:M", strtotime($item['datetime'])) . ' ' : ' ' ) . '</div>';
                    $echo_ .= '<div class="game">'.$item['owner']['title'] . ' ('.$item['g_owner_points'].' : '.$item['g_guest_points'].') ' . $item['guest']['title'] . '</div>';
                    $echo_ .= '<div class="c_title">' . $item['title'] . '</div>';

//                    $echo_ .= '<div class="game">';
//                    $echo_ .= '<div class="logo_l">';
//                        $echo_ .= ($item['owner']['photo_main']?
//                            '<img src="/upload/photos/'.$item['owner']['photo_main']['ph_informer'].
//                                '" alt="'.$item['owner']['title'].'" style="max-width: 100px; max-height: 88px;" />':
//                            '<img src="/images/def_logo.jpg" alt="" border="0">').'<br>';
//                    $echo_ .= $item['owner']['title'].'</div>';
//                    $echo_ .= '<div class="center"><nobr>'.$item['g_owner_points'].' : '.$item['g_guest_points'].'</nobr></div>';
//                    $echo_ .= '<div class="logo_r">';
//                        $echo_ .= ($item['guest']['photo_main']?
//                            '<img src="upload/photos/'.$item['guest']['photo_main']['ph_informer'].
//                                '" alt="'.$item['guest']['title'].'" style="max-width: 100px; max-height: 88px;" />':
//                            '<img src="images/def_logo.jpg" alt="" border="0">').'<br>';
//                    $echo_ .= $item['guest']['title'].'</div>';
//                    $echo_ .= '</div></div>';
                    $echo_ .= '</a>';
                }
                if ($item['an_type']=='competition'){
                    $echo_ .= '<a href="/competitions/'.$item['chl_address'].'/'.$item['chg_address'].'/'.$item['ch_address'].'/'.
                        $item['cp_tour'].'/'.$item['cp_substage'].'/'.$item['g_cp_id'].'">';
                    $echo_ .= '<div class="item"><div class="date">'.date("d.m.Y", strtotime($item['datetime'])).
                        ($item['g_is_schedule_time'] == 'yes' ? 'в ' . date("H:M", strtotime($item['datetime'])) . ' ' : ' ' ).
                        '<br/>'.$item['chl_title'].'. '.$item['chg_title'].'. '.$item['ch_title'].'. '.$item['cp_title'].'.</div></div>';
                    $echo_ .= '</a>';
                }
            }
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
    case 'inf_index_news': {
        $offset = intval($_POST['offset']);
        $page = intval($_POST['page']);
        $search = array(' ', ',', ';', "'", '"');
        $replace = array('','','','','');
        $section_type_id = intval($_POST['section_id']);
        $section_type = str_replace($search, $replace, $_POST['section_type']);
        $section_address = str_replace($search, $replace, $_POST['section_address']);

        $echo_ = '';
        include_once('classes/news.php');
        $news = new news;
        $temp = $news->getNewsMainList($conf->conf_settings['count_news_left'], $page*$conf->conf_settings['count_news_left']+$offset);


        if (!empty($temp)){
            foreach ($temp as $item) {
                $echo_ .= '<a href="https://'.$sitepath.$section_address.'news/'.$item['n_id'].'" title="Новости: '.$item['title'].'" class="item">';
                if ($item['photo_main']){
                    $echo_ .= '<div class="photo" style="background-image: url(\'https://'.$sitepath.'/upload/photos'.$item['photo_main']['ph_folder'].$item['photo_main']['ph_med'].'\');"></div>';
                }
                $echo_ .= '<div class="texts">';
                if ($item['phg_id']>0 && $item['phg_is_active']=='yes'){
                    $echo_ .= '<div class="icons"><div class="photo_ico"></div></div>';
                }
                if ($item['vg_id']>0 && $item['vg_is_active']=='yes'){
                    $echo_ .= '<div class="icons"><div class="video_ico"></div></div>';
                }
                $echo_ .= '<div class="date">'.date("d", strtotime($item['n_date_show'])).' '.$month[date("m", strtotime($item['n_date_show']))].' '.date("Y", strtotime($item['n_date_show'])).' г.</div>';
                $echo_ .= '<div class="time">'.date("H:i",strtotime($item['n_date_show'])).'</div>';
                $echo_ .= '<div class="title">'.$item['title'].'</div>';
                $echo_ .= '<div class="description">'.$item['description'].'</div>';
                $echo_ .= '</div>';
                $echo_ .= '</a>';
            }
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
    case 'inf_newsfeed_loadmore': {
        $search = array(' ', ',', ';', "'", '"');
        $replace = array('','','','','');
        if (!empty($_POST['page'])){
            $page = intval($_POST['page']);
        } else {
            $page = 0;
        }
        if (!empty($_POST['section_id'])){
            $section_type_id = intval($_POST['section_id']);

        } else {
            $section_type_id = 0;
        }
        if (!empty($_POST['category'])){
            $category = intval($_POST['category']);

        } else {
            $category = 0;
        }
        if (!empty($_POST['section_type'])){
            $section_type = str_replace($search, $replace, $_POST['section_type']);
        } else {
            $section_type = '';
        }
        $is_not_section = ($section_type_id>0)?false:true;

        $echo_ = '';
        include_once('classes/news.php');
        $news = new news;
        $temp = $news->getNewsMainList($conf->conf_settings['count_news_left'], $page*$conf->conf_settings['count_news_left'], $is_not_section, $category);
        $section = false;
        if ($section_type_id>0){
            $section = $news->getSection($section_type_id, $section_type);
        }
        if (!empty($temp)){
            $date_cur = '';
            foreach ($temp as $item) {
                if (date("d.m.Y", strtotime($item['n_date_show'])) != $date_cur){
                    $echo_ .= '<li class="date">'.date("d ", strtotime($item['n_date_show'])) . $month[date("m", strtotime($item['n_date_show']))].'</li>';
                    $date_cur = date("d.m.Y", strtotime($item['n_date_show']));
                }
                $echo_ .= '<li class="item">';
                $echo_ .= '<a href="https://'.$sitepath.(($section)?$section['address'].'/':'').'news/'.$item['n_id'].'" title="'.$item['title'].'">';
                $echo_ .= '<div class="date">'.date("H:i", strtotime($item['n_date_show'])).'</div>';
                $echo_ .= '<div class="n_title">'.$item['title'].'</div>';
                $echo_ .= '</a>';
                $echo_ .= '</li>';
            }
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
    case 'inf_newsarticle_loadmore': {
        $search = array(' ', ',', ';', "'", '"');
        $replace = array('','','','','');
        if (!empty($_POST['page'])){
            $page = intval($_POST['page']);
        } else {
            $page = 0;
        }
        if (!empty($_POST['section_id'])){
            $section_type_id = intval($_POST['section_id']);
        } else {
            $section_type_id = 0;
        }
        if (!empty($_POST['section_type'])){
            $section_type = str_replace($search, $replace, $_POST['section_type']);
        } else {
            $section_type = '';
        }
        $is_not_section = ($section_type_id>0)?false:true;
        $category = 2;
        $echo_ = '';
        include_once('classes/news.php');
        $news = new news;
        $temp = $news->getNewsMainList(3, $page*3, $is_not_section, $category);
        $section = false;
        if ($section_type_id>0){
            $section = $news->getSection($section_type_id, $section_type);
        }
        if (!empty($temp)){
            foreach ($temp as $item) {
                $echo_ .= '<li class="item">';
                $echo_ .= '<a href="https://'.$sitepath.(($section)?$section['address'].'/':'').'news/'.$item['n_id'].'" title="'.$item['title'].'"';
                if (!empty($item['photo_main'])){
                    $echo_ .= ' style="background-image: url(\'https://'.$sitepath.'upload/photos'.$item['photo_main']['ph_folder'].$item['photo_main']['ph_med'].'\');"';
                }
                $echo_ .= '>';
                $echo_ .= '<div class="n_title">';
                $echo_ .= '<div class="date">'.date("d", strtotime($item['n_date_show'])).' '.$month[date("m", strtotime($item['n_date_show']))].' '.date("Y", strtotime($item['n_date_show'])).' г.</div>';
                $echo_ .= $item['title'].'</div>';
                $echo_ .= '</a>';
                $echo_ .= '</li>';
            }
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
    case 'get_competition_staff': {
        $search = array(' ', ',', ';', "'", '"');
        $replace = array('','','','','');
        $championship_id = 0;
        if (!empty($_POST['championship_id'])){
            $championship_id = intval($_POST['championship_id']);
        }
        $sort = 0;
        if (!empty($_POST['type'])){
            $type = str_replace($search, $replace, $_POST['type']);
            switch ($type) {
                case 'points':
                    $sort = 0;
                    break;
                case 'pop':
                    $sort = 1;
                    break;
                case 'pez':
                    $sort = 2;
                    break;
                case 'sht':
                    $sort = 3;
                    break;
                case 'd_g':
                    $sort = 4;
                    break;
                case 'y_c':
                    $sort = 5;
                    break;
                case 'r_c':
                    $sort = 6;
                    break;
            }
        }

        if (!empty($championship_id)) {
            $echo_ = '';
            include_once('classes/competitions.php');
            /* @var competitions $competition */
            $competitions = new competitions;

            $conf_settings = $conf->getConfSettings();

            $championship_item = $competitions->getChampionshipItem($championship_id);

            $c_staff = (!empty($championship_item['ch_settings']['count_stuff_rating']) && $championship_item['ch_settings']['count_stuff_rating'] > 1) ? $championship_item['ch_settings']['count_stuff_rating'] : $conf_settings['championship_count_stuff_rating'];
            $temp = $competitions->_getCompetitionsStaff($championship_id, false, false, $sort, $c_staff);
            $echo_data = array();
            if (!empty($temp)) {
                foreach ($temp as $key=>$item) {
                    $echo_data['staff'][] = array(
                        'i' => $key,
                        'title' => $item['name'] . ' ' . $item['surname'] .' ' . $item['family'],
                        'points' => $item['points'],
                        'pop' => $item['pop'],
                        'sht' => $item['sht'],
                        'pez' => $item['pez'],
                        'd_g' => $item['d_g'],
                        'y_c' => $item['y_c'],
                        'r_c' => $item['r_c'],
                    );
                }
            }
            $echo_ = json_encode($echo_data, JSON_UNESCAPED_UNICODE);
        }
        echo $echo_;
//        echo iconv("cp1251", "UTF-8", $echo_);
        break;
    }
}

function _getVoteAnswer ($vt_id = 0) {
    global $db;
    $vt_id = intval($vt_id);
    if ($vt_id < 1) return false;
    $search = array("'", '"');
    $replace = array('&quot;', '&quot;');
    $answer_list = $db->selectElem(DB_T_PREFIX."vote_answer",
        "   vta_id,
            vta_answer_".S_LANG." as vta_answer,
                    vta_a_count",
        "   vta_vt_id=$vt_id");
    if (!empty($answer_list)) {
        foreach ($answer_list as &$item) {
            $item['vta_answer'] = str_replace($search, $replace, stripcslashes($item['vta_answer']));
        }
    }
    return $answer_list;
}

//}