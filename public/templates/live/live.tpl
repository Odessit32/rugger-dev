<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if $live_item}{$live_item.title} :: {/if}{if $current_category}{$current_category.title} :: {/if}{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}{/if}</title>
    {if $meta_seo_item.description}<meta name="description" content="{$meta_seo_item.description}" />
    {/if}{if $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />
    {/if}<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang}">
    {if $live_item}
        <meta property="og:title" content="{$live_item.title}"/>
        <meta property="og:type" content="article"/>
        <meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$live_item.id}"/>
        {if $live_item.photo_main AND $live_item.n_is_photo_top == 'yes'}<meta property="og:image" content="{$imagepath}upload/photos{$live_item.photo_main.ph_big}"/>{/if}
        <meta property="og:site_name" content="{$conf_vars.title}"/>
        <meta property="og:description" content="{$live_item.description_meta}"/>
    {/if}
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    {if $section_type_id>0 && $live_item}
    <link rel="canonical" href="{$sitepath}live/{$live_item.id}" />
    {/if}
    <link rel="stylesheet" type="text/css" href="{$imagepath}flexslider/flexslider.css" />
    {if $ENV =='PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
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

            {include file="live/live_menu.tpl"}

            {if $live_item}
                <div class="gray_caption">
                    {include file="social.tpl"}
                    {$live_item.date|date_format:"%d"}  {$live_item.m} {$live_item.date|date_format:"%Y"} в {$live_item.date|date_format:"%H:%M"}
                </div>

                <div class="content_post live_item border{if $live_item.photo_gallery || $live_item.video_gallery} gallery_item{/if}">
                    {if !empty($live_item.n_is_main) && $live_item.n_is_main == 'yes'}<div class="live_main">&nbsp;Новость дня</div>{/if}

                    <h1>{$live_item.title}</h1>
                    {*<div class="description">{$live_item.description}</div>*}

                    {if $live_item.photo_gallery && $live_item.photo_gallery.photos && $live_item.n_is_photo_top == 'yes'}
                        <div class="photo_big">
                            <div id="photo_slider" class="flexslider gallery_slider">
                                <ul class="slides">
                                    {foreach key=key item=item from=$live_item.photo_gallery.photos name=photo}
                                        <li class="gallery_slider_item">
                                        <a href="{$imagepath}upload/photos{$item.ph_big}"
                                           onclick="return hs.expand(this)"
                                           title="{$live_item.photo_gallery.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                           style="background-image: url({$imagepath}upload/photos{$item.ph_big});"></a>
                                        </li>{/foreach}
                                </ul>
                            </div>
                            <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
                                <ul class="slides">
                                    {foreach key=key item=item from=$live_item.photo_gallery.photos name=photo}
                                        <li class="gallery_slider_nav_item">
                                            <div class="image" style="background-image: url({$imagepath}upload/photos{$item.ph_med});"></div>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    {/if}
                    {if $live_item.video_gallery && $live_item.n_is_photo_top == 'yes'}
                        <div id="video_big" class="video_big">
                            {foreach key=v_key item=v_item from=$live_item.video_gallery.videos}
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
                    {/if}
                    {if !$live_item.photo_gallery && !$live_item.video_gallery && $live_item.photo_main && $live_item.n_is_photo_top == 'yes'}
                        <a href="{$imagepath}upload/photos{$live_item.photo_main.ph_big}" onclick="return hs.expand(this)" class="preview_big_image {if $live_item.photo_main.ph_big_info.0<690} preview_small{/if}" style="background-image: url('{$imagepath}upload/photos{$live_item.photo_main.ph_big}')">
                            {if $live_item.photo_main.ph_about != ''}<div class="about">Фото: {$live_item.photo_main.ph_about}</div>{/if}
                        </a>
                    {/if}
                    {$live_item.text}

                    {if $live_item.n_sign || $live_item.staff || $live_item.team || $live_item.connection_country || $live_item.connection_champ}
                        <div class="content_info">
                            {if $live_item.n_sign}
                                <div class="info_item">
                                    <div class="title">{$language.live_source}:</div>
                                    <div class="text">
                                        {if $live_item.n_sign_url}
                                            <a href="{$live_item.n_sign_url}">{$live_item.n_sign}</a>
                                        {else}
                                            {$live_item.n_sign}
                                        {/if}
                                    </div>
                                </div>
                            {/if}
                            {if $live_item.staff}
                                <div class="info_item">
                                    <div class="title">{$language.live_staff}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$live_item.staff name=live_staff}
                                            <a href="#{$item.address}">{$item.fio}</a>
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if $live_item.team}
                                <div class="info_item">
                                    <div class="title">{$language.live_team}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$live_item.team name=live_team}
                                            <a href="#{$item.address}">{$item.title}</a>
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if $live_item.connection_country}
                                <div class="info_item">
                                    <div class="title">{$language.live_countries}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$live_item.connection_country name=live_countries}
                                            {if $item.address != ''}<a href="{$sitepath}{$item.address}">{$item.title}</a>{else}{$item.title}{/if}{if !$smarty.foreach.live_countries.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if $live_item.connection_champ}
                                <div class="info_item">
                                    <div class="title">{$language.live_championships}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$live_item.connection_champ name=live_championships}
                                            {if $item.address != ''}<a href="{$sitepath}{$item.address}">{$item.title}</a>{else}{$item.title}{/if}{if !$smarty.foreach.live_countries.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                        </div>
                    {/if}
                </div>
                {if $live_item.photo_gallery}<a href="{$sitepath}gallery/photos/gal{$live_item.photo_gallery.id}" class="more_photo">Фото</a>{/if}
                {if $live_item.video_gallery}<a href="{$sitepath}gallery/video/gal{$live_item.video_gallery.id}" class="more_video">Видео</a>{/if}
                {if $live_list AND $list_live == 1}
                    <div id="live">
                        <div class="left_rubrika"><b style="color: #333;">Другие новости</b></div>
                        {foreach key=key item=item from=$live_list name=live}
                            <div class="live_img">
                                {if $item.photo_main}<a href="{$sitepath}live/{$item.n_id}" title="Новости: {$item.$lang.n_title}"><img src="{$sitepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_small}" alt="" width="133" /></a>{/if}
                                <div class="live_text">
                                    <div class="red_text"><span class="time">{$item.n_date_show|date_format:"%d"} {assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$m.$month_name} {$item.n_date_show|date_format:"%Y"} г.</span></div>
                                    <h5><a href="{$sitepath}live/{$item.n_id}" title="Новости: {$item.$lang.n_title}">{$item.$lang.n_title}</a></h5>
                                    <p>{$item.$lang.n_description}</p>
                                </div>
                            </div>
                        {/foreach}
                        {if $live_pages}
                            <div class="pages"><i>{$language.pages}: </i>
                                {foreach key=key item=item from=$live_pages}{if $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{if $current_category}{$current_category.adress}/{/if}{if $date_show}date-{$date_show}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
                            </div>
                        {/if}
                    </div>
                {/if}

            {else}
            <div class="title_caption title_caption_live"><a href="{$sitepath}live/">{$page_item.title}</a></div>
                <div class="live_list">
                    {if $live_list}
                        {foreach key=key item=item from=$live_list}
                            <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}{if $current_page>1}/page{$current_page}{/if}/{$item.address}" class="item">
                                <div class="date">{$item.date|date_format:"%d"}  {$item.m} {$item.date|date_format:"%Y"}, {$item.date|date_format:"%H:%M"}</div>
                                <div class="photo" style="background-image: url({if $item.photo_main}{$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_med}{/if});"></div>
                                <div class="text">
                                    {$item.title}
                                    <p>{$item.description}</p>
                                </div>
                            </a>
                        {/foreach}

                        {if $live_pages}
                            <div class="pages"><i>{$language.pages}: </i>
                                {foreach key=key item=item from=$live_pages}{if $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{if $current_category}{$current_category.adress}/{/if}{if $date_show}date-{$date_show}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
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
        var dates = [{/literal}{if $results_list.soon_date_list}{$results_list.soon_date_list}{/if}{literal}];
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
                maxDate: '{/literal}{$date_max}{literal}',
                firstDay: 1
            });
            $.datepicker.setDefaults($.datepicker.regional['ru']);
        }
        $('#date_soon').on('click', function(){
            if ($.fn.datepicker) {
                $(this).datepicker("show");
            }
        });
        $('#date_soon').on("change", function(){
            var date_str = $(this).val().split('.');
            window.location = $(this).data('url')+'/date-'+date_str[2]+'-'+date_str[1]+'-'+date_str[0];
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
