<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if !empty($post_item)}{$post_item.title}{/if}{/if}</title>
    {if $meta_seo_item.description || !empty($post_item)}<meta name="description" content="{if $meta_seo_item.description}{$meta_seo_item.description}{else}{$post_item.description_meta}{/if}" />
    {/if}{if $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />
    {/if}<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang}">
    {if !empty($post_item)}
        <meta property="og:title" content="{$post_item.title}"/>
        <meta property="og:type" content="article"/>
        <meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$post_item.id}"/>
        {if $post_item.photo_main AND $post_item.is_photo_top == 'yes'}<meta property="og:image" content="{$imagepath}upload/photos{$post_item.photo_main.ph_big}"/>{/if}
        <meta property="og:site_name" content="{$conf_vars.title}"/>
        <meta property="og:description" content="{if $meta_seo_item.description}{$meta_seo_item.description}{else}{$post_item.description_meta}{/if}"/>
    {/if}
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    {if ($section_type_id>0 || $current_page>0) && !empty($post_item)}
        <link rel="canonical" href="{$sitepath}blog/{$post_item.address}" />
    {/if}
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

            {include file="blog/blog_menu.tpl"}

            {if !empty($post_item)}
                <div class="gray_caption">
                    {include file="social.tpl"}
                    {$post_item.date_show|date_format:"%d"}  {$post_item.m} {$post_item.date_show|date_format:"%Y"} в {$post_item.date_show|date_format:"%H:%M"}
                </div>

                <div class="content_post post_item border{if $post_item.photo_gallery || $post_item.video_gallery} gallery_item{/if}">
                    {if !empty($post_item.is_main) && $post_item.is_main == 'yes'}<div class="news_main">&nbsp;Новость дня</div>{/if}
                    {if !empty($admin_user) && (!empty($admin_user.admin_status) || !empty($admin_user.publisher_status))} [<a href="{$imagepath}admin/?show=blog&get=edit&item={$post_item.id}" target="_blank">edit</a>]{/if}
                    <h1>
                        {$post_item.title}
                    </h1>
                    {*<div class="description">{$post_item.description}</div>*}

                    {if !empty($post_item.photo_gallery) && !empty($post_item.photo_gallery.photos) && $post_item.is_photo_top == 'yes'}
                        <div class="photo_big">
                            <div id="photo_slider" class="flexslider gallery_slider">
                                <ul class="slides">
                                    {foreach key=key item=item from=$post_item.photo_gallery.photos name=photo}
                                        <li class="gallery_slider_item">
                                        <a href="{$imagepath}upload/photos{$item.ph_big}"
                                           onclick="return hs.expand(this)"
                                           title="{$post_item.photo_gallery.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                           style="background-image: url({$imagepath}upload/photos{$item.ph_big});"></a>
                                        </li>{/foreach}
                                </ul>
                            </div>
                            <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
                                <ul class="slides">
                                    {foreach key=key item=item from=$post_item.photo_gallery.photos name=photo}
                                        <li class="gallery_slider_nav_item">
                                            <div class="image" style="background-image: url({$imagepath}upload/photos{$item.ph_med});"></div>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    {/if}
                    {if $post_item.video_gallery && $post_item.is_photo_top == 'yes'}
                        <div id="video_big" class="video_big">
                            {foreach key=v_key item=v_item from=$post_item.video_gallery.videos}
                                <div class="video_item">
                                    <h4>{$v_item.title}</h4>
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
                    {if !$post_item.photo_gallery && !$post_item.video_gallery && $post_item.photo_main && $post_item.is_photo_top == 'yes'}
                        <a href="{$imagepath}upload/photos{$post_item.photo_main.ph_big}" onclick="return hs.expand(this)" class="preview_big_image {if $post_item.photo_main.ph_big_info.0<690} preview_small{/if}" style="background-image: url('{$imagepath}upload/photos{$post_item.photo_main.ph_big}')">
                            {if $post_item.photo_main.ph_about != ''}<div class="about">Фото: {$post_item.photo_main.ph_about}</div>{/if}
                        </a>
                    {/if}
                    {$post_item.text}

                    {if !empty($post_item.sign) || !empty($post_item.staff) || !empty($post_item.team) || !empty($post_item.connection_country) || !empty($post_item.connection_champ)}
                        <div class="content_info">
                            {if !empty($post_item.sign)}
                                <div class="info_item">
                                    <div class="title">{$language.news_source}:</div>
                                    <div class="text">
                                        {if $post_item.sign_url}
                                            <a href="{$post_item.sign_url}">{$post_item.sign}</a>
                                        {else}
                                            {$post_item.sign}
                                        {/if}
                                    </div>
                                </div>
                            {/if}
                            {if !empty($post_item.staff)}
                                <div class="info_item">
                                    <div class="title">{$language.news_staff}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$post_item.staff name=news_staff}
                                            <a href="#{$item.address}">{$item.fio}</a>
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if !empty($post_item.team)}
                                <div class="info_item">
                                    <div class="title">{$language.news_team}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$post_item.team name=news_team}
                                            <a href="#{$item.address}">{$item.title}</a>
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if !empty($post_item.connection_country)}
                                <div class="info_item">
                                    <div class="title">{$language.news_countries}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$post_item.connection_country name=news_countries}
                                            {if $item.address != ''}<a href="{$sitepath}{$item.address}">{$item.title}</a>{else}{$item.title}{/if}{if !$smarty.foreach.news_countries.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                            {if !empty($post_item.connection_champ)}
                                <div class="info_item">
                                    <div class="title">{$language.news_championships}:</div>
                                    <div class="text">
                                        {foreach key=key item=item from=$post_item.connection_champ name=news_championships}
                                            {if $item.address != ''}<a href="{$sitepath}{$item.address}">{$item.title}</a>{else}{$item.title}{/if}{if !$smarty.foreach.news_countries.last}, {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                        </div>
                    {/if}
                </div>
                {if !empty($post_item.photo_gallery)}<a href="{$sitepath}photos/gal{$post_item.photo_gallery.id}" class="more_photo">Фото</a>{/if}
                {if !empty($post_item.video_gallery)}<a href="{$sitepath}video/gal{$post_item.video_gallery.id}" class="more_video">Видео</a>{/if}
                {if !empty($post_list)}
                    <div id="news">
                        <div class="left_rubrika"><b style="color: #333;">Другие новости</b></div>
                        {foreach key=key item=item from=$post_list name=news}
                            <div class="news_img">
                                {if $item.photo_main}<a href="{$sitepath}blog/{$item.address}" title="Новости: {$item.title}"><img src="{$sitepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_small}" alt="" width="133" /></a>{/if}
                                <div class="news_text">
                                    <div class="red_text"><span class="time">{$item.date_show|date_format:"%d"} {assign var="month_name" value=$item.date_show|date_format:"%m"} {$month.$month_name} {$item.date_show|date_format:"%Y"} г.</span></div>
                                    <h5><a href="{$sitepath}blog/{$item.address}" title="{$item.title}">{$item.title}</a></h5>
                                    <p>{$item.description}</p>
                                </div>
                            </div>
                        {/foreach}
                        {if !empty($post_pages)}
                            <div class="pages"><i>{$language.pages}: </i>
                                {foreach key=key item=item from=$post_pages}{if $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{if !empty($current_category)}{$current_category.adress}/{/if}{if $date_show}date-{$date_show}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
                            </div>
                        {/if}
                    </div>
                {/if}

            {else}
                {include file="blog/index_nmo.tpl"}
                <div class="title_caption title_caption_news"><a href="{$sitepath}blog/">{$page_item.title}</a></div>
                <div class="news_list">
                    {if $post_list}
                        {foreach key=key item=item from=$post_list}
                            <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}{if !empty($current_page) && $current_page>1}/page{$current_page}{/if}/{$item.address}" class="item">
                                {if $item.photo_main}
                                    <div class="photo" style="background-image: url({$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_med});"></div>
                                {/if}
                                <div class="texts">
                                    {if $item.phg_id>0 and $item.phg_is_active=='yes'}
                                        <div class="icons">
                                            <div class="photo_ico"></div>
                                        </div>
                                    {/if}
                                    {if $item.vg_id>0 and $item.vg_is_active=='yes'}
                                        <div class="icons">
                                            <div class="video_ico"></div>
                                        </div>
                                    {/if}
                                    <div class="date">{$item.date_show|date_format:"%d"}  {$item.m} {$item.date_show|date_format:"%Y"} г.</div>
                                    <div class="time">{$item.date_show|date_format:"%H:%M"}</div>
                                    <div class="title">{$item.title}</div>
                                    <div class="description">{$item.description}</div>
                                </div>
                            </a>
                        {/foreach}

                        {if $post_pages}
                            <div class="pages"><i>{$language.pages}: </i>
                                {foreach key=key item=item from=$post_pages}{if $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{if !empty($current_category)}{$current_category.adress}/{/if}{if $date_show}date-{$date_show}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
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
        var dates = [{/literal}{if !empty($results_list.soon_date_list)}{$results_list.soon_date_list}{/if}{literal}];
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
                maxDate: '{/literal}{if !empty($date_max)}{$date_max}{/if}{literal}',
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
