<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}{/if}</title>
    {if $meta_seo_item.description}<meta name="description" content="{$meta_seo_item.description}" />
    {/if}{if $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />
    {/if}<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang}" />
    <meta property="og:title" content="{if $meta_seo_item.title}{$meta_seo_item.title}{else}{$page_item.title}{/if}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}"/>
    <meta property="og:site_name" content="{$conf_vars.title}"/>
    {if $page_item.description_meta != ''}<meta property="og:description" content="{$page_item.description_meta}"/>{/if}
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    {if $ENV =='PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
    {* Flexslider - некритичный CSS, загружаем асинхронно *}
    <link rel="stylesheet" href="{$imagepath}flexslider/flexslider.css" media="print" onload="this.media='all'">
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
    <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="{$sitepath}css/main_ie.css" />
    <![endif]-->
    <!--[if lte IE 8]>
    <script src="{$sitepath}jscripts/DD_belatedPNG.js" type="text/javascript"></script>
    <script>
        DD_belatedPNG.fix("#logo");
    </script>
    <![endif]-->
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
</head>
<body>
<div class="content">
    {include file="header.tpl"}
    <div class="content_block">
        <div class="left_block">
            <div class="page_content">

                <div class="title_caption profile_block_caption">
                    {$language.team_profile}
                    {include file="social.tpl"}
                </div>

                <div class="profile_block">
                    <div class="profile_photo">
                        <img class="profile_logo" src="{$imagepath}upload/photos{$team_item.photo_main.ph_med}" loading="lazy" alt="">
                    </div>
                    <div class="profile_content">
                        <h1>{$team_item.title}</h1>
                        <ul class="pr_list">
                            {if !empty($team_item.general_staff) && is_array($team_item.general_staff)}
                                {foreach key=key item=item from=$team_item.general_staff name=general_staff}
                                    <li>
                                        <div class="pr_name">{$item.app_title}</div>
                                        <div class="pr_value">{$item.name} {$item.family}</div>
                                    </li>
                                {/foreach}
                            {/if}
                            {if !empty($team_item.t_std_id)}
                                <li>
                                    <div class="pr_name">Стадион</div>
                                    <div class="pr_value">{$team_item.stadium.title}</div>
                                </li>
                            {/if}
                            {if !empty($team_item.foundation_date[0])}
                                <li>
                                    <div class="pr_name">Год основания</div>
                                    <div class="pr_value">{$team_item.foundation_date[0]}</div>
                                </li>
                            {/if}
                        </ul>
                    </div>
                </div>

                <div class="left_menu overflow">
                    <ul>
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}" title="{$team_item.title}"{if $tesm_page == 'profile'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[1])}{$team_item.t_info.tab_titles[1]}{else}Профиль{/if}</a></li>
                        {if $team_item.staff}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}/staff" title="{$team_item.title}"{if $tesm_page == 'staff'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[2])}{$team_item.t_info.tab_titles[2]}{else}Состав{/if}</a></li>{/if}
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}/statistics" title="{$team_item.title}"{if $tesm_page == 'statistics'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[3])}{$team_item.t_info.tab_titles[3]}{else}Результаты{/if}</a></li>
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}/timetable" title="{$team_item.title}"{if $tesm_page == 'timetable'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[7])}{$team_item.t_info.tab_titles[7]}{else}Расписание{/if}</a></li>
                        {if !empty($team_item.news)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}/news" title="{$team_item.title}"{if $tesm_page == 'news'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[4])}{$team_item.t_info.tab_titles[4]}{else}Статьи{/if}</a></li>{/if}
                        {if !empty($team_item.photos)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}/photos" title="{$team_item.title}"{if $tesm_page == 'photos'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[5])}{$team_item.t_info.tab_titles[5]}{else}Фото{/if}</a></li>{/if}
                        {if !empty($team_item.videos)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address|default:$team_item.id}/video" title="{$team_item.title}"{if $tesm_page == 'videos'} class="active"{/if}>{if !empty($team_item.t_info.tab_titles[6])}{$team_item.t_info.tab_titles[6]}{else}Видео{/if}</a></li>{/if}
                    </ul>
                </div>

                <div class="content_post post{if $tesm_page != 'staff'} border{else} no-padding{/if}">
                    {if $team_item}
                        {if $tesm_page == 'profile'}
                            {if $team_item.text!=''}
                                {$team_item.text}
                            {/if}
                        {/if}
                    {if $tesm_page == 'staff'}
                    {if $team_item.staff}
                        <div class="team_staff_list_wrapper">
                            <h2>Нападающие:</h2>
                            <ul class="team_staff_list">
                                {*
                                <li class="heading_list">
                                    <span class="num">Позиция</span>
                                    <span class="flag"></span>
                                    <span class="name">Имя</span>
                                    <span class="bd">Возраст</span>
                                </li>
                                *}
                                {foreach key=key item=item from=$team_item.staff_by_type.1 name=staff}
                                    {if $item.name}
                                        <li class="team_staff_item">
                                            <span class="num">{$item.app}</span>
                                            <span class="flag"></span>
                                            <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                        <div class="team_staff_list_wrapper">
                            <h2>Полузащитники:</h2>
                            <ul class="team_staff_list">
                                {foreach key=key item=item from=$team_item.staff_by_type.2 name=staff}
                                    {if $item.name}
                                        <li class="team_staff_item">
                                            <span class="num">{$item.app}</span>
                                            <span class="flag"></span>
                                            <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                        <div class="team_staff_list_wrapper">
                            <h2>Защитники:</h2>
                            <ul class="team_staff_list">
                                {foreach key=key item=item from=$team_item.staff_by_type.3 name=staff}
                                    {if $item.name}
                                        <li class="team_staff_item">
                                            <span class="num">{$item.app}</span>
                                            <span class="flag"></span>
                                            <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {if $team_item.staff.head}
                        <div class="post" id="cont_3" style="display: none">
                            <h2>Руководство:</h2>
                            {if $team_item.staff}
                                {foreach key=key item=item from=$team_item.staff name=staff}
                                    {if $item.head}
                                        <div class="staff_item">
                                            {if $item.photo_main}<div class="photo"><a href="{$imagepath}upload/photos{$item.photo_main.ph_big}" data-fancybox="staff" data-caption="{$item.app}: {$item.family} {$item.name} {$item.surname}" title="{$item.app}: {$item.family} {$item.name} {$item.surname}" ><img src="{$imagepath}upload/photos{$item.photo_main.ph_med}" border="0" loading="lazy" alt="{$conf_vars.title} :: {$page_item.title} :: {$item.app}" /></a></div>{/if}
                                            <div class="fio">{$item.family} {$item.name} {$item.surname}</div>
                                            <p><b>{$item.app}</b></p>
                                            {$item.description}
                                        </div>
                                    {/if}
                                {/foreach}
                            {/if}
                        </div>
                    {/if}
                    {if $team_item.staff.rest}
                        <div class="post" id="cont_4" style="display: none">
                            <h2>Другой персонал:</h2>
                            {if $team_item.staff}
                                {foreach key=key item=item from=$team_item.staff name=staff}
                                    {if $item.rest}
                                        <div class="staff_item">
                                            {if $item.photo_main}<div class="photo"><a href="{$imagepath}upload/photos{$item.photo_main.ph_big}" data-fancybox="staff" data-caption="{$item.app}: {$item.family} {$item.name} {$item.surname}" title="{$item.app}: {$item.family} {$item.name} {$item.surname}" ><img src="{$imagepath}upload/photos{$item.photo_main.ph_med}" border="0" loading="lazy" alt="{$conf_vars.title} :: {$page_item.title} :: {$item.app}" /></a></div>{/if}
                                            <div class="fio">{$item.family} {$item.name} {$item.surname}</div>
                                            <p><b>{$item.app}</b></p>
                                            {$item.description}
                                        </div>
                                    {/if}
                                {/foreach}
                            {/if}
                        </div>
                    {/if}
                    {/if}
                        {if $tesm_page == 'news'}
                        {/if}
                    {if $tesm_page == 'photos'}
                    {if $team_item.photos.items}
                        <div class="photo_big">
                            <div id="photo_slider" class="flexslider gallery_slider">
                                <ul class="slides">
                                    {foreach key=key item=item from=$team_item.photos.items name=photo}{if $item.main == 'no'}
                                        <li class="gallery_slider_item">
                                        <a href="{$imagepath}{$item.ph_big}"
                                           data-fancybox="team-photos"
                                           data-caption="{$team_item.photos.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                           title="{$team_item.photos.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                           class="preview_team_image"
                                           style="background-image: url({$imagepath}{$item.ph_big});">{if $item.ph_about != ''}<div class="about">{$team_item.photos.title} : {$item.ph_about}</div>{/if}</a>
                                        </li>{/if}{/foreach}
                                </ul>
                            </div>
                            <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
                                <ul class="slides">
                                    {foreach key=key item=item from=$team_item.photos.items name=photo}{if $item.main == 'no'}
                                        <li class="gallery_slider_nav_item">
                                            <div class="image" style="background-image: url({$imagepath}{$item.ph_med});"></div>
                                        </li>
                                    {/if}{/foreach}
                                </ul>
                            </div>
                        </div>
                    {else}
                        <p>Фото пока не добавили</p>
                    {/if}
                    {/if}
                    {if $tesm_page == 'videos'}
                    {if $team_item.videos}
                        <div id="video_big" class="video_big">
                            {foreach key=v_key item=v_item from=$team_item.videos}
                                <div class="video_item">
                                    <p>{$v_item.title}</p>
                                    {if $v_item.v_code != ''}
                                        <iframe width="100%" height="400"
                                                src="//www.youtube.com/embed/{$v_item.v_code}"
                                                frameborder="0" allowfullscreen></iframe>
                                    {else}
                                        {$v_item.v_code_text}
                                    {/if}
                                </div>
                            {/foreach}
                        </div>
                    {else}
                        <p>Видео пока не добавили</p>
                    {/if}
                    {/if}
                    {if $tesm_page == 'statistics'}
                        <script>
                            {literal}
                            $(document).ready(function () {
                                $("body").on("click", ".heading_year", function () {
                                    var y = $(this).data("y");
                                    $(".heading_month").hide();
                                    $(".game_item_block").hide();
                                    $(".list_year_"+y).show();
                                })
                                $(".heading_month").hide();
                                $(".game_item_block").hide();
                                var y_f = $(".results > .heading_year").data("y");
                                $(".list_year_"+y_f).show();
                            });
                            {/literal}
                        </script>
                        <div class="results">
                            {if $team_item.statistics.soon}
                                <div class="heading_list">
                                    <div class="date">{$language.timetable_date}</div>
                                    <div class="tour">{$language.timetable_tour}</div>
                                    <div class="match">{$language.timetable_match}</div>
                                </div>
                                {assign var="list_year" value=""}
                                {foreach item=item_m from=$team_item.statistics.soon name=soon_m}
                                    {if $list_year != substr($item_m.caption, -4)}
                                        {assign var="list_year" value=substr($item_m.caption, -4)}
                                        <div class="heading_year" data-y="{$list_year}">{$list_year}</div>
                                    {/if}
                                    <div class="heading_month list_year_{$list_year}">{$item_m.caption}</div>
                                    {foreach item=item_d from=$item_m.data name=soon}
                                        <div class="game_item_block list_year_{$list_year}">
                                            <div class="day">{$item_d.caption}</div>
                                            {foreach item=item_ch from=$item_d.data name=soon}
                                                <a href="{$sitepath}{$item_ch.path}{$item_ch.address}" class="championship">{$item_ch.caption}</a>
                                                <div class="games_list_">
                                                    {foreach item=item from=$item_ch.data name=soon}
                                                        {if $item.an_type=='game' && !empty($item.owner) && !empty($item.guest)}
                                                            {if $item.is_detailed}<a href="{$sitepath}{$page_item.page_path}game/{$item.g_id}" title="{$item.owner.title} - {$item.guest.title}">{/if}
                                                            <span class="title_left">{$item.owner.title}</span>
                                                            <span class="points">{$item.g_owner_points} : {$item.g_guest_points}</span>
                                                            <span class="title_right">{$item.guest.title}</span>
                                                            {*if $item.g_is_schedule_time == 'yes'}<span class="date date_calculate"
                                                            data-time-gmt="{$item.datetime_gmt}"
                                                            data-time-zone="{$item.time_zone}">({$item.datetime_gmt|date_format:"%H:%M"} {$language.gmt})</span>{/if*}
                                                            {if $item.is_detailed}</a>{/if}
                                                        {/if}
                                                        {if $item.an_type=='competition'}
                                                            <a class="competition_title" href="{$sitepath}{$section.page.page_path}{$section.page.p_adress}/tables/{$item.chg_address}/{$item.ch_address}/{$item.cp_tour}/{$item.cp_substage}/{$item.g_cp_id}">
                                                                {if $item.ch_settings.tourTitle.ru[$item.cp_tour]}{$item.ch_settings.tourTitle.ru[$item.cp_tour]}{else}{$language.competition_tour_title} {$item.cp_tour+1}{/if}.
                                                                {$item.ch_settings.stageTitle.ru[$item.cp_tour][$item.cp_substage]}.
                                                                {if $item.ch_settings.isShowStageDateTime[$item.cp_tour][$item.cp_substage] == 1}
                                                                    <span class="date date_calculate"
                                                                          data-time-gmt="{$item.ch_settings.stageDateTimeS[$item.cp_tour][$item.cp_substage]}"
                                                                          data-time-zone="0">({$item.ch_settings.stageDateTimeDF[$item.cp_tour][$item.cp_substage]} {$language.gmt})
                                                                        </span>
                                                                {/if}
                                                            </a>
                                                        {/if}
                                                    {/foreach}
                                                </div>
                                            {/foreach}
                                        </div>
                                    {/foreach}
                                {/foreach}
                            {/if}
                        </div>
                    {/if}
                    {if $tesm_page == 'timetable'}
                        <script>
                            {literal}
                            $(document).ready(function () {
                                $("body").on("click", ".heading_year", function () {
                                    var y = $(this).data("y");
                                    $(".heading_month").hide();
                                    $(".game_item_block").hide();
                                    $(".list_year_"+y).show();
                                })
                                $(".heading_month").hide();
                                $(".game_item_block").hide();
                                var y_f = $(".results > .heading_year").data("y");
                                $(".list_year_"+y_f).show();
                            });
                            {/literal}
                        </script>
                        <div class="results">
                            {if $team_item.timetable.soon}
                                <div class="heading_list">
                                    <div class="date">{$language.timetable_date}</div>
                                    <div class="tour">{$language.timetable_tour}</div>
                                    <div class="match">{$language.timetable_match}</div>
                                </div>
                                {assign var="list_year" value=""}
                                {foreach item=item_m from=$team_item.timetable.soon name=soon_m}
                                    {if $list_year != substr($item_m.caption, -4)}
                                        {assign var="list_year" value=substr($item_m.caption, -4)}
                                        <div class="heading_year" data-y="{$list_year}">{$list_year}</div>
                                    {/if}
                                    <div class="heading_month list_year_{$list_year}">{$item_m.caption}</div>
                                    {foreach item=item_d from=$item_m.data name=soon}
                                        <div class="game_item_block list_year_{$list_year}">
                                            <div class="day">{$item_d.caption}</div>
                                            {foreach item=item_ch from=$item_d.data name=soon}
                                                <a href="{$sitepath}{$item_ch.path}{$item_ch.address}" class="championship">{$item_ch.caption}</a>
                                                <div class="games_list_">
                                                    {foreach item=item from=$item_ch.data name=soon}
                                                        {if $item.an_type=='game' && !empty($item.owner) && !empty($item.guest)}
                                                            {if $item.is_detailed}<a href="{$sitepath}{$page_item.page_path}game/{$item.g_id}" title="{$item.owner.title} - {$item.guest.title}">{/if}
                                                            <span class="title_left">{$item.owner.title}</span>
                                                            <span class="points"> : </span>
                                                            <span class="title_right">{$item.guest.title}</span>
                                                            {if $item.g_is_schedule_time == 'yes'}<span class="date date_calculate" data-date-gmt="{$item.date_gmt}" data-time-zone="{$item.time_zone}">({*$item.datetime|date_format:"%H:%M"} {$item.time_zone_title}, *}{$item.datetime_gmt|date_format:"%H:%M"} {$language.gmt})</span>{/if}
                                                            {if !empty($item.g_info->live)}<span class="live">live</span>{/if}
                                                            {*if $item.g_is_schedule_time == 'yes'}<span class="date date_calculate"
                                                            data-time-gmt="{$item.datetime_gmt}"
                                                            data-time-zone="{$item.time_zone}">({$item.datetime_gmt|date_format:"%H:%M"} {$language.gmt})</span>{/if*}
                                                            {if $item.is_detailed}</a>{/if}
                                                        {/if}
                                                        {if $item.an_type=='competition'}
                                                            <a class="competition_title" href="{$sitepath}{$section.page.page_path}{$section.page.p_adress}/tables/{$item.chg_address}/{$item.ch_address}/{$item.cp_tour}/{$item.cp_substage}/{$item.g_cp_id}">
                                                                {if $item.ch_settings.tourTitle.ru[$item.cp_tour]}{$item.ch_settings.tourTitle.ru[$item.cp_tour]}{else}{$language.competition_tour_title} {$item.cp_tour+1}{/if}.
                                                                {$item.ch_settings.stageTitle.ru[$item.cp_tour][$item.cp_substage]}.
                                                                {if $item.ch_settings.isShowStageDateTime[$item.cp_tour][$item.cp_substage] == 1}
                                                                    <span class="date date_calculate"
                                                                          data-time-gmt="{$item.ch_settings.stageDateTimeS[$item.cp_tour][$item.cp_substage]}"
                                                                          data-time-zone="0">({$item.ch_settings.stageDateTimeDF[$item.cp_tour][$item.cp_substage]} {$language.gmt})
                                                                </span>
                                                                {/if}
                                                            </a>
                                                        {/if}
                                                    {/foreach}
                                                </div>
                                            {/foreach}
                                        </div>
                                    {/foreach}
                                {/foreach}
                            {/if}
                        </div>
                    {/if}
                    {/if}
                </div>
            </div>


        </div>
        <div class="right_block">
            {include file="right.tpl"}
        </div>
    </div>
    <div class="content_end"></div>
</div>
{include file="footer.tpl"}

</body>
</html>

