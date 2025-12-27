<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang}" />
    <meta property="og:title" content="{$page_item.title}"/>
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
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
    <base href="{$sitepath_lang}">
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

                <div class="gray_caption">
                    {include file="social.tpl"}
                </div>

                <div class="content_post border">
                    {if $page_item.p_is_title == 'yes'}<h1>{$page_item.title}</h1>{/if}
                    {if $page_item.p_is_description == 'yes'}<div class="description">{$page_item.description}</div>{/if}
                    {if $page_item.p_is_text == 'yes'}
                        {if $ph_page_item.pe_is_on_text == 'yes'}
                            <div class="pe_photo">
                                <a href="{$imagepath}upload/photos{$ph_page_item.photo_main.ph_big}" onclick="return hs.expand(this)" title="{$ph_page_item.photo_main.ph_alt}" >
                                    <img src="{$imagepath}upload/photos{$ph_page_item.photo_main.ph_med}" alt="{$ph_page_item.photo_main.ph_alt}" />
                                </a>
                            </div>
                        {/if}
                        {$page_item.text}
                    {/if}
                    {if $page_child_list}
                        <div class="about_position_menu">
                            {foreach key=key item=item from=$page_child_list name="b_m"}
                                <a class="about_position_menu_item" data-id="{$item.id}" href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}#{$item.address}" title="{$item.description}">{$item.title}</a>
                            {/foreach}
                        </div>
                        <div class="about_position_list_page">
                            {foreach key=key item=item from=$page_child_list name="b_m"}
                                <div class="about_position_page_item" data-id="{$item.id}">
                                    {if $item.title != '' && $item.is_title == 'yes'}<h2>{$item.title}</h2>{/if}
                                    {if $item.text != ''}{$item.text}{/if}
                                </div>
                            {/foreach}
                        </div>
                    {/if}

                    {if $page_item.files and $page_item.p_is_files == 'yes'}
                        <div class="files">
                            {if $page_item.p_title_files != ''}<h3>{$page_item.p_title_files}</h3>{/if}
                            {foreach key=key item=item from=$page_item.files}
                                <div class="item">
                                    <div class="text">
                                        {$item.about}<br>
                                        <a href="{$imagepath}upload/files{$item.f_folder}{$item.f_path}">{$item.f_path}</a> <i>({$item.size}).</i>
                                    </div>
                                    <div class="ico">
                                        <a href="{$imagepath}upload/files{$item.f_folder}{$item.f_path}"><img src="{$imagepath}{$item.ico}" border="0"></a>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    {/if}

                    {if $page_item.pvs}
                        <div class="pvs">
                            {foreach key=key item=item from=$page_item.pvs}
                                {if $item.pvs_service == 'youtube'}<iframe width="460" height="400" src="//www.youtube.com/embed/{$item.pvs_code}" frameborder="0" allowfullscreen></iframe>{/if}
                            {/foreach}
                        </div>
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
