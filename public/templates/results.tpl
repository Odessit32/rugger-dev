<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if isset($meta_seo_item.title) && $meta_seo_item.title}{$meta_seo_item.title}{elseif isset($page_item.title)}{$page_item.title} :: {/if}{if isset($conf_vars.title)}{$conf_vars.title}{/if}</title>
    {if isset($meta_seo_item.description) && $meta_seo_item.description}<meta name="description" content="{$meta_seo_item.description}" />{/if}
    {if isset($meta_seo_item.keywords) && $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />{/if}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang|default:''}" />
    <meta property="og:title" content="{if isset($page_item.title)}{$page_item.title}{/if}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}"/>
    <meta property="og:site_name" content="{if isset($conf_vars.title)}{$conf_vars.title}{/if}"/>
    {if isset($page_item.description_meta) && $page_item.description_meta != ''}<meta property="og:description" content="{$page_item.description_meta}"/>{/if}
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    {if $ENV == 'PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/dayjs.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/dayjs-utc.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/dayjs-timezone.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/dayjs-ru.min.js"></script>
    <script>dayjs.extend(dayjs_plugin_utc); dayjs.extend(dayjs_plugin_timezone); dayjs.locale('ru');</script>
    <base href="{$sitepath_lang|default:''}">
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
</head>
<body>
<div class="content">
    {include file="header.tpl"}
    <div class="content_block">
        <div class="left_block left_block_visible left_block_min_height_400">
            <div class="page_content">
                <div class="left_menu">
                    <ul>
                        <li><span class="date_soon_picker w_sub_menu{if isset($date_type) && $date_type == 'date'} active{/if}"><input type="text" id="date_soon" data-url="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($ch_type) && $ch_type != 'all'}/ch-{$one_ch|default:''}{/if}" value="{if isset($date_now)}{$date_now|date_format:"%d.%m.%Y"}{/if}" readonly></span></li>
                        <li><a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($ch_type) && $ch_type != 'all'}/ch-{$one_ch|default:''}{/if}/week-{if isset($date_now)}{$date_now|date_format:"%Y-%m-%d"}{/if}"{if isset($date_type) && $date_type == 'week'} class="active"{/if}>Неделя</a></li>
                        <li><a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($ch_type) && $ch_type != 'all'}/ch-{$one_ch|default:''}{/if}/month-{if isset($date_now)}{$date_now|date_format:"%Y-%m-%d"}{/if}"{if isset($date_type) && $date_type == 'month'} class="active"{/if}>Месяц</a></li>
                        <li>
                            <a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}/ch-all/{if isset($date_type_address)}{$date_type_address}{/if}" class="active w_sub_menu">{if isset($ch_type) && $ch_type == 'all'}{$language.all_championships|default:'Все чемпионаты'}{elseif isset($ch_title)}{$ch_title}{/if}</a>
                            <div class="ul_sub_menu sub_menu_list_{if isset($championships_wg_list_menu_class)}{$championships_wg_list_menu_class}{/if}">
                                <ul class="ul_sub_menu_list">
                                    {if isset($championships_wg_list)}
                                        {foreach item=item_local from=$championships_wg_list name=local_list}
                                            {if isset($item_local.data) && $item_local.data}
                                                {if $championships_wg_list|@count > 1}
                                                    <li><span class="ch_local_title">{if isset($item_local.title)}{$item_local.title}{/if}</span>
                                                        <ul class="ch_list">
                                                            {foreach item=item_chwg from=$item_local.data name=chwg_list}
                                                                <li><a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}/ch-{if isset($item_chwg.address)}{$item_chwg.address}{/if}/">{if isset($item_chwg.title)}{$item_chwg.title}{/if}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </li>
                                                {else}
                                                    {foreach item=item_chwg from=$item_local.data name=chwg_list}
                                                        <li><a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}/ch-{if isset($item_chwg.address)}{$item_chwg.address}{/if}/">{if isset($item_chwg.title)}{$item_chwg.title}{/if}</a></li>
                                                    {/foreach}
                                                {/if}
                                            {/if}
                                            {if $smarty.foreach.local_list.index%2}</ul><ul class="ul_sub_menu_list">{/if}
                                        {/foreach}
                                    {/if}
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <div class="left_menu_subnav">
                        {if isset($date_next) && $date_next}<a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($ch_type) && $ch_type != 'all'}/ch-{$one_ch|default:''}{/if}/{$date_next}" class="next"></a>{/if}
                        {if isset($date_prev) && $date_prev}<a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($ch_type) && $ch_type != 'all'}/ch-{$one_ch|default:''}{/if}/{$date_prev}" class="prev"></a>{/if}
                        <span class="current">
                            {if isset($date_type_title)}{$date_type_title}{/if}
                            {if isset($date_type_title_list) && $date_type_title_list}
                                <ul class="ul_sub_menu_title_list">
                                    {foreach item=item_tl from=$date_type_title_list}
                                        <li><a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($ch_type) && $ch_type != 'all'}/ch-{$one_ch|default:''}{/if}/{if isset($item_tl.address)}{$item_tl.address}{/if}"{if isset($item_tl.active) && $item_tl.active == 'yes'} class="active"{/if}>{if isset($item_tl.title)}{$item_tl.title}{/if}</a></li>
                                    {/foreach}
                                </ul>
                            {/if}
                        </span>
                    </div>
                </div>
                {if isset($is_empty) && !$is_empty}
                    <div class="content_post">
                        {if isset($page_item.p_is_description) && $page_item.p_is_description == 'yes'}<div class="description">{if isset($page_item.description)}{$page_item.description}{/if}</div>{/if}
                        {if isset($page_item.p_is_text) && $page_item.p_is_text == 'yes'}
                            {if isset($ph_page_item.pe_is_on_text) && $ph_page_item.pe_is_on_text == 'yes'}
                                <div class="pe_photo">
                                    <a href="{$imagepath}upload/photos{if isset($ph_page_item.photo_main.ph_big)}{$ph_page_item.photo_main.ph_big}{/if}" onclick="return hs.expand(this)" title="{if isset($ph_page_item.photo_main.ph_alt)}{$ph_page_item.photo_main.ph_alt}{/if}" >
                                        <img src="{$imagepath}upload/photos{if isset($ph_page_item.photo_main.ph_med)}{$ph_page_item.photo_main.ph_med}{/if}" alt="{if isset($ph_page_item.photo_main.ph_alt)}{$ph_page_item.photo_main.ph_alt}{/if}" />
                                    </a>
                                </div>
                            {/if}
                            {if isset($page_item.text)}{$page_item.text}{/if}
                        {/if}
                        <div class="results">
                            {if isset($results_list.soon) && $results_list.soon}
                                <div class="heading_list">
                                    <div class="date">{$language.timetable_date|default:'Дата'}</div>
                                    <div class="tour">{$language.timetable_tour|default:'Тур'}</div>
                                    <div class="match">{$language.timetable_match|default:'Матч'}</div>
                                </div>
                                {foreach item=item_m from=$results_list.soon name=soon_m}
                                    {foreach item=item_d from=$item_m.data name=soon}
                                        <div class="game_item_block">
                                            <div class="day">{if isset($item_d.caption)}{$item_d.caption}{else}День неизвестен{/if}</div>
                                            {foreach item=item_ch from=$item_d.data name=soon}
                                                <a href="{$sitepath}{if isset($item_ch.path)}{$item_ch.path}{/if}{if isset($item_ch.address)}{$item_ch.address}{/if}" class="championship">{if isset($item_ch.caption)}{$item_ch.caption}{/if}</a>
                                                <div class="games_list_">
                                                    {foreach item=item from=$item_ch.data name=soon}
                                                        {if isset($item.an_type) && $item.an_type == 'game'}
                                                            {if isset($item.is_detailed) && $item.is_detailed}<a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}game/{if isset($item.g_id)}{$item.g_id}{/if}" title="{if isset($item.owner.title) && isset($item.guest.title)}{$item.owner.title} - {$item.guest.title}{/if}" class="games_list_item game-{$item.g_id|default:''}{if isset($item.g_selected) && $item.g_selected == 'yes'} selected{/if}">{else}<span class="games_list_item game-{$item.g_id|default:''}{if isset($item.g_selected) && $item.g_selected == 'yes'} selected{/if}">{/if}
                                                                <span class="title_left">{if isset($item.owner.title)}{$item.owner.title}{else}Команда 1{/if}</span>
                                                                <span class="points">{if isset($item.g_owner_points) && isset($item.g_guest_points)}{$item.g_owner_points} : {$item.g_guest_points}{else} : {/if}</span>
                                                                <span class="title_right">{if isset($item.guest.title)}{$item.guest.title}{else}Команда 2{/if}</span>
                                                            {if isset($item.is_detailed) && $item.is_detailed}</a>{else}</span>{/if}
                                                        {elseif isset($item.an_type) && $item.an_type == 'competition'}
                                                            {if isset($item.chg_address) && isset($item.ch_address) && isset($item.cp_tour) && isset($item.cp_substage) && isset($item.g_cp_id)}
                                                                <a class="competition_title" href="{$sitepath}{if isset($section.page.page_path)}{$section.page.page_path}{/if}{if isset($section.page.p_adress)}{$section.page.p_adress}{/if}/tables/{$item.chg_address}/{$item.ch_address}/{$item.cp_tour}/{$item.cp_substage}/{$item.g_cp_id}">
                                                                    {if isset($item.ch_settings.tourTitle.ru[$item.cp_tour]) && $item.ch_settings.tourTitle.ru[$item.cp_tour]}{$item.ch_settings.tourTitle.ru[$item.cp_tour]}{else}{$language.competition_tour_title|default:'Тур'} {$item.cp_tour+1}{/if}.
                                                                    {if isset($item.ch_settings.stageTitle.ru[$item.cp_tour][$item.cp_substage])}{$item.ch_settings.stageTitle.ru[$item.cp_tour][$item.cp_substage]}{/if}.
                                                                    {if isset($item.ch_settings.isShowStageDateTime[$item.cp_tour][$item.cp_substage]) && $item.ch_settings.isShowStageDateTime[$item.cp_tour][$item.cp_substage] == 1}
                                                                        <span class="date date_calculate"
                                                                              data-time-gmt="{if isset($item.ch_settings.stageDateTimeS[$item.cp_tour][$item.cp_substage])}{$item.ch_settings.stageDateTimeS[$item.cp_tour][$item.cp_substage]}{/if}"
                                                                              data-time-zone="0">({if isset($item.ch_settings.stageDateTimeDF[$item.cp_tour][$item.cp_substage])}{$item.ch_settings.stageDateTimeDF[$item.cp_tour][$item.cp_substage]}{/if} {$language.gmt|default:'GMT'})
                                                                        </span>
                                                                    {/if}
                                                                </a>
                                                            {/if}
                                                        {/if}
                                                    {/foreach}
                                                </div>
                                            {/foreach}
                                        </div>
                                    {/foreach}
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                {else}
                    <div class="content_post">
                        <p>{$language.no_timetable_results|default:'Нет результатов'}</p>
                    </div>
                {/if}
            </div>
        </div>
        <div class="right_block">
            {include file="right.tpl"}
        </div>
    </div>
    <div class="content_end"></div>
</div>
{include file="footer.tpl"}
{literal}
<script>
    $(window).on('load', function(){
        var datepiker_timeout;
        var dates = [{/literal}{if isset($results_list.soon_date_list) && $results_list.soon_date_list}{$results_list.soon_date_list}{/if}{literal}];
        if ($.fn.datepicker) {
            $('#date_soon').datepicker({
                dateFormat: 'dd.mm.yy',
                beforeShowDay: function(date) {
                    for (var i = 0; i < dates.length; i++) {
                        if (new Date(dates[i]).toString() == date.toString()) {
                            return [true, 'highlight', ''];
                        }
                    }
                    return [true, ''];
                },
                showOtherMonths: true,
                maxDate: '{/literal}{if isset($date_max)}{$date_max}{/if}{literal}',
                firstDay: 1
            });
            $.datepicker.setDefaults($.datepicker.regional['ru']);
        }
        $('#date_soon').on("change", function(){
            var date_str = $(this).val().split('.');
            window.location = $(this).data('url')+'/date-'+date_str[2]+'-'+date_str[1]+'-'+date_str[0];
        });
        $('#date_soon').on('click', function(){
            if ($.fn.datepicker) {
                $(this).datepicker("show");
            }
        });
        $(".date_soon_picker").on('mouseover', function(){
            clearTimeout(datepiker_timeout);
            datepiker_timeout = setTimeout(function(){
                if ($.fn.datepicker) {
                    $('#date_soon').datepicker("show");
                }
            }, 500);
        });
        $(".date_soon_picker").on('mouseout', function(){
            clearTimeout(datepiker_timeout);
        });
    });
</script>
{/literal}
</body>
</html>