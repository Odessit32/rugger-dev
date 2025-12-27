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
    <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{$imagepath}css/style.css" />
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
                    {$staff_item.full_name}
                    {include file="social.tpl"}
                </div>

                <div class="profile_block">
                    <div class="profile_photo">
                        {if $staff_item.photo_main.ph_med}<img class="profile_logo" src="{$imagepath}upload/photos{$staff_item.photo_main.ph_med}" loading="lazy" alt="">{/if}
                    </div>
                    <div class="profile_content">
                        <ul class="pr_list">
                            {if !empty($staff_item.full_name) || !empty($staff_item.full_name_en)}
                            <li>
                                <div class="pr_name">Полное имя</div>
                                <div class="pr_value">{$staff_item.full_name} {if !empty($staff_item.full_name_en)}<span class="small">{$staff_item.full_name_en}</span>{/if}</div>
                            </li>
                            {/if}
                            {if !empty($staff_item.date_birth)}
                            <li>
                                <div class="pr_name">Дата рождения</div>
                                <div class="pr_value">{$staff_item.date_birth|date_format:"%d.%m.%Y"}</div>
                            </li>
                            {/if}
                            {if !empty($staff_item.age)}
                            <li>
                                <div class="pr_name">Возраст</div>
                                <div class="pr_value">{$staff_item.age}</div>
                            </li>
                            {/if}
                            {if !empty($staff_item.teams)}
                            <li>
                                <div class="pr_name">Команды</div>
                                <div class="pr_value"></div>
                            </li>
                            {/if}
                            {if !empty($staff_item.teams)}
                            <li>
                                <div class="pr_name">Позиция</div>
                                <div class="pr_value"></div>
                            </li>
                            {/if}
                            {if !empty($staff_item.teams)}
                            <li>
                                <div class="pr_name">Рост</div>
                                <div class="pr_value"></div>
                            </li>
                            {/if}
                            {if !empty($staff_item.teams)}
                            <li>
                                <div class="pr_name">Вес</div>
                                <div class="pr_value"></div>
                            </li>
                            {/if}
                        </ul>
                    </div>
                </div>

                <div class="left_menu overflow">
                    <ul>
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}" title="{$staff_item.title}"{if $staff_page == 'profile'} class="active"{/if}>Профиль</a></li>
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}/statistics" title="{$staff_item.title}"{if $staff_page == 'statistics'} class="active"{/if}>Статистика</a></li>
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}/photos" title="{$staff_item.title}"{if $staff_page == 'photos'} class="active"{/if}>Фото</a></li>
                        <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}/video" title="{$staff_item.title}"{if $staff_page == 'videos'} class="active"{/if}>Видео</a></li>
                    </ul>
                </div>

                <div class="content_post post border">
                    {if $staff_item}
                        {if $staff_page == 'profile'}
                            {if $staff_item.description!=''}
                                {$staff_item.description}
                            {/if}
                        {/if}
                        {if $staff_page == 'news'}
                        {/if}
                        {if $staff_page == 'photos'}
                            {if $staff_item.photos}
                                <div class="photo_big">
                                    <div id="photo_slider" class="flexslider gallery_slider">
                                        <ul class="slides">
                                            {foreach key=key item=item from=$staff_item.photos name=photo}
                                                <li class="gallery_slider_item">
                                                <a href="{$imagepath}{$item.ph_big}"
                                                   onclick="return hs.expand(this)"
                                                   title="{$staff_item.photo_gallery.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                                   style="background-image: url({$imagepath}{$item.ph_big});"></a>
                                                </li>{/foreach}
                                        </ul>
                                    </div>
                                    <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
                                        <ul class="slides">
                                            {foreach key=key item=item from=$staff_item.photos name=photo}
                                                <li class="gallery_slider_nav_item">
                                                    <div class="image" style="background-image: url({$imagepath}{$item.ph_med});"></div>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            {else}
                                <p>Фото пока не добавили</p>
                            {/if}
                        {/if}
                        {if $staff_page == 'videos'}
                            {if $staff_item.videos}
                                <div id="video_big" class="video_big">
                                    {foreach key=v_key item=v_item from=$staff_item.videos}
                                        <div class="video_item">
                                            <p>{$v_item.title}</p>
                                            {if $v_item.v_code != ''}
                                                <iframe width="100%" height="400"
                                                        src="http://www.youtube.com/embed/{$v_item.v_code}"
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
                        {if $staff_page == 'statistics'}

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

