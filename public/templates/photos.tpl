<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "//www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}{/if}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-language" content="{$sLang}" />
{if !empty($gallegy_item)}
		<meta property="og:title" content="{$gallegy_item.title}"/>
		<meta property="og:type" content="article"/>
		<meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/gal{$gallegy_item.id}"/>
		{if $gallegy_item.photos}
		{foreach key=key item=item from=$gallegy_item.photos name=photo}{if $smarty.foreach.photo.iteration <4}
		<meta property="og:image" content="{$imagepath}upload/photos{$item.ph_big}"/>
		{/if}{/foreach}
		{/if}
		<meta property="og:site_name" content="{$conf_vars.title}"/>
		{if $gallegy_item.description_meta != ''}<meta property="og:description" content="{$gallegy_item.description_meta}"/>{/if}
{/if}
		<link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/style.css?v=1.0.8" />
        {* Flexslider - некритичный CSS, загружаем асинхронно *}
        <link rel="stylesheet" href="{$imagepath}flexslider/flexslider.css" media="print" onload="this.media='all'">
		<script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
		<script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
		<base href="{$sitepath_lang}">

		{include file="highslide_slsh.tpl"}
		{include file="google_analitics.tpl"}
	</head>
	<body>
    <div class="content">
        {include file="header.tpl"}
        <div class="content_block">
            <div class="left_block">

                {include file="menu_categories.tpl"}

			    {if !empty($gallegy_item)}
                    <div class="title_caption title_caption_photo">
                        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}">{if $page_item.p_is_title == 'yes'}{$page_item.title}{/if}</a>
                    </div>
                    <div class="page_content">
                        <div class="content_post gallery_item border">
                            {if $gallegy_item.photos}
                                <div class="date">
                                    {$gallegy_item.phg_datetime_pub|date_format:"%d"} {$gallegy_item.m} {$gallegy_item.phg_datetime_pub|date_format:"%Y"}
                                    {if $gallegy_item.is_hours}, {$gallegy_item.phg_datetime_pub|date_format:"%H:%M"}{/if}
                                </div>
                                <h1>{$gallegy_item.title}</h1>
                                {$gallegy_item.description}
                                <div class="photo_big">
                                    <div id="photo_slider" class="flexslider gallery_slider">
                                        <ul class="slides">
                                            {foreach key=key item=item from=$gallegy_item.photos name=photo}
                                            <li class="gallery_slider_item">
                                                <a itemprop="image" itemscope itemtype="https://schema.org/ImageObject"
                                                   href="{$imagepath}upload/photos{$item.ph_big}"
                                                   class="preview_big_image photo_preview"
                                                   data-fancybox="gallery"
                                                   data-caption="{$gallegy_item.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                                   title="{$gallegy_item.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}">
                                                    <img class="image"
                                                         src="{$imagepath}upload/photos{$item.ph_big}"
                                                         loading="lazy"
                                                         alt="{$gallegy_item.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}">
                                                    <meta itemprop="url" content="{$imagepath}upload/photos{$item.ph_big}">
                                                </a>
                                            </li>{/foreach}
                                        </ul>
                                    </div>
                                    <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
                                        <ul class="slides">
                                            {foreach key=key item=item from=$gallegy_item.photos name=photo}
                                                <li class="gallery_slider_nav_item">
                                                    <div class="image" style="background-image: url({$imagepath}upload/photos{$item.ph_med});"></div>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            {/if}
                            <div class="social_block">{include file="social.tpl"}</div>
                        </div>
                    </div>
                    <div class="gray_caption after_space">
                        <p>{$language.gallery_photo_other} {if $current_page > 1}- {$language.page} {$current_page}{/if}</p>
                    </div>
                {else}
                    <div class="title_caption title_caption_photo">
                        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}">{$language.gallery_photo}</a>
                        {*<span class="filter_date">{$language.select_date}</span>*}
                    </div>
                {/if}
                <div class="photo_list">
                    {if !empty($gallery_list)}
                        {foreach key=key item=item from=$gallery_list name=news}
                            <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}{if $current_page>1}/page{$current_page}{/if}/gal{$item.id}" class="item">
                                <div class="date">{$item.phg_datetime_pub|date_format:"%d"} {$item.m} {$item.phg_datetime_pub|date_format:"%Y"}{if $item.is_hours}, {$item.phg_datetime_pub|date_format:"%H:%M"}{/if}</div>
                                <div class="photo" style="background-image: url('{$imagepath}upload/photos{$item.phg_ph_med}');"></div>
                                <div class="text">
                                    {$item.title}

                                    {*<p>{$item.description}</p>*}
                                </div>
                            </a>
                        {/foreach}
                    {/if}
                    {if !empty($gallery_pages)}
                        <div class="pages"><i>{$language.pages}: </i>
                            {foreach key=key item=item from=$gallery_pages}{if $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{if $current_category}{$current_category.adress}/{/if}page{$item}">{$item}</a>{/if}{/foreach}
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

    </body>
</html>
