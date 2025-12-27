{strip}
<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
    <title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{$language.main} :: {$conf_vars.title}{/if}</title>
    {if $meta_seo_item.description}<meta name="description" content="{$meta_seo_item.description}" />
    {/if}{if $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />
    {/if}<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="content-language" content="ru">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
    {if $ENV =='PRODUCTION'}
    <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.3" type="text/css">
    {else}
    <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
    <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.3" type="text/css">

    {/if}

    <script src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    {*<script type="text/javascript" src="{$sitepath}jscripts/ajax_func.js"></script> - old *}
    <base href="{$sitepath_lang}" />
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
    <meta property="fb:pages" content="1519837891565229" />
</head>
<body>
<div class="content">
    {include file="header.tpl"}
    <main class="content_block" role="main">
        <div class="left_block">
        {include file="index_nmo.tpl"}
        {include file="index_left.tpl"}
        </div>
        <div class="right_block">
        {include file="index_right.tpl"}
        </div>

    </main>
    <div class="content_end"></div>
</div>
{include file="footer.tpl"}
</body>
</html>
{/strip}
