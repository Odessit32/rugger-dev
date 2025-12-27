<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if $staff_item}{$staff_item.full_name} :: {else}{if $page_item.title}{$page_item.title} :: {/if}{/if}{$conf_vars.title}{/if}</title>
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
    {if !empty($staff_item.st_address) && $staff_item.address != $staff_item.st_address}
    <link rel="canonical" href="{$sitepath}{$page_item.page_path}people/{$staff_item.st_address}" />
    {/if}
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="{$imagepath}flexslider/flexslider.css" />
    {if $ENV =='PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
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

            {include file="people/people_info.tpl"}

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

