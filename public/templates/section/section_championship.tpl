<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}{/if}</title>
    {if $meta_seo_item.description}<meta name="description" content="{$meta_seo_item.description}" />
    {/if}{if $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />
    {/if}<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="ru">
    <meta property="og:title" content="{if $meta_seo_item.title}{$meta_seo_item.title}{else}{$page_item.title}{/if}"/>
    <meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}"/>
    <meta property="og:site_name" content="{$conf_vars.title}"/>    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    {if $ENV =='PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
    {*<script type="text/javascript" src="{$sitepath}jscripts/ajax_func.js"></script> - old *}
    <base href="{$sitepath_lang}" />
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
</head>
<body>
<div class="content">
    {include file="header.tpl"}
    <div class="content_block">
        <div class="left_block">
        {include file="index_nmo.tpl"}
        {include file="left.tpl"}
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
