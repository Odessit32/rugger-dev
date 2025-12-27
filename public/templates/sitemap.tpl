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
    <script type="text/javascript" src="{$imagepath}jscripts/index.js"></script>
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
<div id="main">
    {include file="header.tpl"}

    <div class="left">
        {if $page_item.p_is_title == 'yes'}<div class="left_rubrika"><b style="color: #333;">{$page_item.title}</b></div>{/if}
        <div class="page_content">
            {include file="social.tpl"}
            <div class="breadcrumbs">
                <a href="{$sitepath}">{$conf_vars.main}</a> <b>/</b>
                {if $pages}
                    {foreach key=key item=item from=$pages name="b_m"}
                        <a href="{$sitepath}{$item.page_path}{$item.p_adress}">{$item.title}</a> <b>/</b>
                    {/foreach}
                {/if}
                {$page_item.title}
            </div>
            <div class="post">
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

                {if $sitemap_list}
                    <ul class="sitemap">
                        {foreach key=key item=item from=$sitemap_list name=page}
                            <li>{assign var=p_left value=`$item.nesting*30`}<a href="{$sitepath}{$item.path}{$item.address}" style="padding-left: {$p_left}px;">{$item.title}</a></li>
                        {/foreach}
                    </ul>
                {/if}
            </div>
        </div>
    </div>
    <div id="right">
        {include file="right.tpl"}
    </div>
    <div id="center">
        {include file="center.tpl"}
    </div>

    <div id="main_end"></div>
</div>
{include file="footer.tpl"}

</body>
</html>


