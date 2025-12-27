<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if isset($meta_seo_item.title) && $meta_seo_item.title}{$meta_seo_item.title}{elseif isset($news_item) && !empty($news_item)}{$news_item.title}{/if}</title>
    {if (isset($meta_seo_item.description) && $meta_seo_item.description) || (isset($news_item) && !empty($news_item))}<meta name="description" content="{if isset($meta_seo_item.description) && $meta_seo_item.description}{$meta_seo_item.description}{elseif isset($news_item.description_meta)}{$news_item.description_meta}{/if}" />{/if}
    {if isset($meta_seo_item.keywords) && $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />{/if}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang|default:''}">
    {if isset($news_item) && !empty($news_item)}
        <meta property="og:title" content="{if isset($news_item.title)}{$news_item.title}{/if}"/>
        <meta property="og:type" content="article"/>
        <meta property="og:url" content="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}/{if isset($news_item.id)}{$news_item.id}{/if}"/>
        {if isset($news_item.photo_main) && isset($news_item.photo_main.ph_big)}
            <meta property="og:image" content="{$imagepath}upload/photos{$news_item.photo_main.ph_big}"/>
            <meta property="og:image:width" content="1200"/>
            <meta property="og:image:height" content="630"/>
        {/if}
        <meta property="og:site_name" content="{$conf_vars.title|default:''}"/>
        <meta property="og:description" content="{if isset($meta_seo_item.description) && $meta_seo_item.description}{$meta_seo_item.description}{elseif isset($news_item.description_meta)}{$news_item.description_meta}{/if}"/>
    {/if}
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    {if (isset($section_type_id) && $section_type_id > 0) || (isset($current_page) && $current_page > 0) && isset($news_item) && !empty($news_item)}
        <link rel="canonical" href="{$sitepath}news/{if isset($news_item.id)}{$news_item.id}{/if}" />
    {/if}
    {if $ENV == 'PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
    {* Flexslider - некритичный CSS, загружаем асинхронно *}
    <link rel="stylesheet" href="{$imagepath}flexslider/flexslider.css" media="print" onload="this.media='all'">
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
</head>
<body>
<div class="content">
    {include file="header.tpl"}
    <div class="content_block">
        <div class="left_block">
            {include file="news/news_menu.tpl"}
            {if isset($news_item) && !empty($news_item)}
                <div class="gray_caption">
                    {include file="social.tpl"}
                    {if isset($news_item.date)}{$news_item.date|date_format:"%d"}  {$news_item.m|default:''} {$news_item.date|date_format:"%Y"} в {$news_item.date|date_format:"%H:%M"}{else}Дата неизвестна{/if}
                </div>
                <div class="content_post news_item border{if isset($news_item.photo_gallery) || isset($news_item.video_gallery)} gallery_item{/if}">
                    {if isset($news_item.n_is_main) && $news_item.n_is_main == 'yes'}<div class="news_main">&nbsp;Новость дня</div>{/if}
                    {if isset($admin_user) && (!empty($admin_user.admin_status) || !empty($admin_user.publisher_status))} [<a href="{$imagepath}admin/?show=news&get=edit&item={if isset($news_item.id)}{$news_item.id}{/if}" target="_blank">edit</a>]{/if}
<h1>{if isset($news_item.title)}{$news_item.title}{else}Без заголовка{/if}</h1>

{* Упрощенное условие для отображения одиночной фотографии *}
{if isset($news_item.photo_main) && $news_item.photo_main && isset($news_item.n_is_photo_top) && $news_item.n_is_photo_top == 'yes'}
    <a itemprop="image" itemscope itemtype="https://schema.org/ImageObject"
       href="{$imagepath}upload/photos{if isset($news_item.photo_main.ph_big)}{$news_item.photo_main.ph_big}{/if}"
       data-fancybox="news"
       data-caption="{if isset($news_item.title_meta)}{$news_item.title_meta}{/if}"
       class="preview_big_image {if isset($news_item.photo_main.ph_big_info.0) && $news_item.photo_main.ph_big_info.0 < 690}preview_small{/if}"
       title="{if isset($news_item.title_meta)}{$news_item.title_meta}{/if}">
        <img class="image"
             src="{$imagepath}upload/photos{if isset($news_item.photo_main.ph_big)}{$news_item.photo_main.ph_big}{/if}"
             loading="lazy"
             alt="{if isset($news_item.title_meta)}{$news_item.title_meta}{/if}">
        <meta itemprop="url" content="{$imagepath}upload/photos{if isset($news_item.photo_main.ph_big)}{$news_item.photo_main.ph_big}{/if}">
        {if isset($news_item.photo_main.ph_about) && $news_item.photo_main.ph_about != ''}<div class="about" itemprop="caption description">Фото: {$news_item.photo_main.ph_about}</div>{/if}
    </a>
{/if}

{* Блок для галереи фотографий *}
{if isset($news_item.photo_gallery) && !empty($news_item.photo_gallery.photos) && isset($news_item.n_is_photo_top) && $news_item.n_is_photo_top == 'yes'}
    <div class="photo_big">
        <div id="photo_slider" class="flexslider gallery_slider">
            <ul class="slides">
                {foreach key=key item=item from=$news_item.photo_gallery.photos name=photo}
                    <li class="gallery_slider_item">
                        <a href="{$imagepath}upload/photos{if isset($item.ph_big)}{$item.ph_big}{/if}"
                           data-fancybox="news-gallery"
                           data-caption="{if isset($news_item.photo_gallery.title)}{$news_item.photo_gallery.title}{/if}{if isset($item.ph_about) && $item.ph_about != ''}: {$item.ph_about}{/if}"
                           title="{if isset($news_item.photo_gallery.title)}{$news_item.photo_gallery.title}{/if}{if isset($item.ph_about) && $item.ph_about != ''}: {$item.ph_about}{/if}"
                           style="background-image: url({$imagepath}upload/photos{if isset($item.ph_big)}{$item.ph_big}{/if});"></a>
                    </li>
                {/foreach}
            </ul>
        </div>
        <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
            <ul class="slides">
                {foreach key=key item=item from=$news_item.photo_gallery.photos name=photo}
                    <li class="gallery_slider_nav_item">
                        <div class="image" style="background-image: url({$imagepath}upload/photos{if isset($item.ph_med)}{$item.ph_med}{/if});"></div>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}

{* Блок для видео - показывается всегда если есть видеогалерея *}
{if isset($news_item.video_gallery) && $news_item.video_gallery}
    <div id="video_big" class="video_big">
        {foreach key=v_key item=v_item from=$news_item.video_gallery.videos}
            <div class="video_item">
                <h4>{if isset($v_item.title)}{$v_item.title}{else}Без названия{/if}</h4>
                {if isset($v_item.v_code) && $v_item.v_code != ''}
                    <iframe width="100%" height="400"
                            src="//www.youtube.com/embed/{$v_item.v_code}"
                            frameborder="0" allowfullscreen></iframe>
                {elseif isset($v_item.v_code_text)}{$v_item.v_code_text}{/if}
            </div>
        {/foreach}
    </div>
{/if}

{if isset($news_item.text)}{$news_item.text}{/if}

{if isset($news_item.n_sign) || isset($news_item.staff) || isset($news_item.team) || isset($news_item.connection_country) || isset($news_item.connection_champ)}
                        <div class="content_info">
                            {if isset($news_item.n_sign) && !empty($news_item.n_sign)}
                                <div class="info_item">
                                    <div class="title">{$language.news_source|default:'Источник'}:</div>
                                    <div class="text">
                                        {if isset($news_item.n_sign_url) && $news_item.n_sign_url}<a href="{$news_item.n_sign_url}">{$news_item.n_sign}</a>{else}{$news_item.n_sign}{/if}
                                    </div>
                                </div>
                            {/if}
                            {if isset($news_item.team) && !empty($news_item.team)}
                                <div class="info_item">
                                    <div class="title">{$language.news_team|default:'Команда'}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$news_item.team name=news_team}
                                            <a href="#{if isset($item.address)}{$item.address}{/if}">{if isset($item.title)}{$item.title}{/if}</a>
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if isset($news_item.connection_country) && !empty($news_item.connection_country)}
                                <div class="info_item">
                                    <div class="title">{$language.news_countries|default:'Страны'}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$news_item.connection_country name=news_countries}
                                            {if isset($item.address) && $item.address != ''}<a href="{$sitepath}{$item.address}">{if isset($item.title)}{$item.title}{/if}</a>{elseif isset($item.title)}{$item.title}{/if}{if !$smarty.foreach.news_countries.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if isset($news_item.connection_champ) && !empty($news_item.connection_champ)}
                                <div class="info_item">
                                    <div class="title">{$language.news_championships|default:'Чемпионаты'}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$news_item.connection_champ name=news_championships}
                                            {if isset($item.address) && $item.address != ''}<a href="{$sitepath}{$item.address}">{if isset($item.title)}{$item.title}{/if}</a>{elseif isset($item.title)}{$item.title}{/if}{if !$smarty.foreach.news_championships.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if isset($news_item.staff) && !empty($news_item.staff)}
                                <div class="info_item info_item_staff">
                                    <div class="title">{$language.news_staff|default:'Люди'}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$news_item.staff name=news_staff}
                                            <a href="{$sitepath}people/{if isset($item.address)}{$item.address}{/if}">{if isset($item.fio)}{$item.fio}{/if}</a>{if !$smarty.foreach.news_staff.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                        </div>
                    {/if}

{* Блок "Читайте также" - после источника *}
{if isset($news_item.related_news) && !empty($news_item.related_news)}
<div class="related_news_block">
    <div class="related_news_title">Читайте также</div>
    <div class="related_news_list">
        {foreach key=key item=rel_item from=$news_item.related_news name=related}
        {if $smarty.foreach.related.iteration <= 3}
        <a href="{$sitepath}news/{$rel_item.id}" class="related_news_item">
            {if isset($rel_item.photo) && $rel_item.photo}
            <div class="related_news_photo" style="background-image: url({$imagepath}upload/photos{$rel_item.photo.ph_folder}{$rel_item.photo.ph_path});"></div>
            {/if}
            <div class="related_news_text">
                <div class="related_news_date">{$rel_item.date|date_format:"%d"} {$rel_item.m} {$rel_item.date|date_format:"%Y"}</div>
                <div class="related_news_item_title">{$rel_item.title}</div>
            </div>
        </a>
        {/if}
        {/foreach}
    </div>
</div>
{/if}

                </div>
                <div class="comments">
                    {include file="comments.tpl"}
                </div>
                {if isset($news_item.photo_gallery) && !empty($news_item.photo_gallery)}<a href="{$sitepath}photos/gal{if isset($news_item.photo_gallery.id)}{$news_item.photo_gallery.id}{/if}" class="more_photo">Фото</a>{/if}
                {if isset($news_item.video_gallery) && !empty($news_item.video_gallery)}<a href="{$sitepath}video/gal{if isset($news_item.video_gallery.id)}{$news_item.video_gallery.id}{/if}" class="more_video">Видео</a>{/if}
                {if isset($news_list) && !empty($news_list) && isset($list_news) && $list_news == 1}
                    <div id="news">
                        <div class="left_rubrika"><b style="color: #333;">Другие новости</b></div>
                        {foreach key=key item=item from=$news_list name=news}
                            <div class="news_img">
                                {if isset($item.photo_main) && $item.photo_main}<a href="{$sitepath}news/{$item.n_id|default:''}" title="Новости: {if isset($item.$lang.n_title)}{$item.$lang.n_title}{/if}"><img src="{$sitepath}upload/photos{if isset($item.photo_main.ph_folder)}{$item.photo_main.ph_folder}{/if}{if isset($item.photo_main.ph_small)}{$item.photo_main.ph_small}{/if}" alt="" width="133" loading="lazy" /></a>{/if}
                                <div class="news_text">
                                    <div class="red_text"><span class="time">{if isset($item.n_date_show)}{$item.n_date_show|date_format:"%d"} {assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$m.$month_name|default:''} {$item.n_date_show|date_format:"%Y"} г.{else}Дата неизвестна{/if}</span></div>
                                    <h5><a href="{$sitepath}news/{$item.n_id|default:''}" title="Новости: {if isset($item.$lang.n_title)}{$item.$lang.n_title}{/if}">{if isset($item.$lang.n_title)}{$item.$lang.n_title}{else}Без заголовка{/if}</a></h5>
                                    <p>{if isset($item.$lang.n_description)}{$item.$lang.n_description}{else}Описание отсутствует{/if}</p>
                                </div>
                            </div>
                        {/foreach}
                        {if isset($news_pages) && $news_pages}
                            <div class="pages"><i>{$language.pages|default:'Страницы'}: </i>
                                {foreach key=key item=item from=$news_pages}{if isset($current_page) && $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}/{if isset($current_category.address) && $current_category.address}{$current_category.address}/{/if}{if isset($date_show)}date-{$date_show}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
                            </div>
                        {/if}
                    </div>
                {/if}
            {else}
                {if isset($current_category.address) && $current_category.address != 'articles'}
                    {include file="index_nmo.tpl"}
                {/if}
                <div class="title_caption title_caption_news"><a href="{$sitepath}news/">{if isset($page_item.title)}{$page_item.title}{else}Новости{/if}</a></div>
                <div class="news_list">
                    {if isset($news_list) && $news_list}
                        {foreach key=key item=item from=$news_list}
                            <a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}{if isset($current_page) && $current_page > 1}/page{$current_page}{/if}/{if isset($item.id)}{$item.id}{/if}" class="item">
                                {if isset($item.photo_main) && $item.photo_main}
                                    <div class="photo" style="background-image: url({$imagepath}upload/photos{if isset($item.photo_main.ph_med)}{$item.photo_main.ph_med}{/if});"></div>
                                {/if}
                                <div class="texts">
                                    {if isset($item.phg_id) && $item.phg_id > 0 && isset($item.phg_is_active) && $item.phg_is_active == 'yes'}
                                        <div class="icons">
                                            <div class="photo_ico"></div>
                                        </div>
                                    {/if}
                                    {if isset($item.vg_id) && $item.vg_id > 0 && isset($item.vg_is_active) && $item.vg_is_active == 'yes'}
                                        <div class="icons">
                                            <div class="video_ico"></div>
                                        </div>
                                    {/if}
                                    <div class="date">{if isset($item.date)}{$item.date|date_format:"%d"}  {$item.m|default:''} {$item.date|date_format:"%Y"} г.{else}Дата неизвестна{/if}</div>
                                    <div class="time">{if isset($item.date)}{$item.date|date_format:"%H:%M"}{else}Время неизвестно{/if}</div>
                                    <div class="title">{if isset($item.title)}{$item.title}{else}Без заголовка{/if}</div>
                                    <div class="description">{if isset($item.description)}{$item.description}{else}Описание отсутствует{/if}</div>
                                </div>
                            </a>
                        {/foreach}
                        {if isset($news_pages) && $news_pages}
                            <div class="pages"><i>{$language.pages|default:'Страницы'}: </i>
                                {foreach key=key item=item from=$news_pages}{if isset($current_page) && $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{if isset($page_item.page_path)}{$page_item.page_path}{/if}{if isset($page_item.p_adress)}{$page_item.p_adress}{/if}/{if isset($current_category.address) && $current_category.address}{$current_category.address}/{/if}{if isset($date_show)}date-{$date_show}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
                            </div>
                        {/if}
                    {/if}
                </div>
            {/if}
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