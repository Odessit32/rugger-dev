<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{if $local.item}:: {$local.item.title}{/if} {if $group.item}:: {$group.item.title}{/if} {if $championship.item}:: {$championship.item.title}{/if} {if $competition.item} :: {$competition.item.title}{/if}{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang}">
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon">
    {if $ENV =='PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    {*<script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle.all.js"></script>*}
    {*
    <script type="text/javascript" src="{$sitepath}jscripts/tip_script.js"></script>
    <script type="text/javascript" src="{$sitepath}jscripts/ajax_func.js"></script>
    <script type="text/javascript" src="{$sitepath}jscripts/popup.js"></script>
    *}
    <base href="{$sitepath_lang}">
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
</head>
<body>
<div class="content">
    {include file="header.tpl"}
    <div class="content_block">
        <div class="left_block">

{*<b>{if $championship_item}{$local_item.title} / {$group_item.title} / {$championship_item.title}{else}{$page_item.title}{/if}</b>*}
            {if $championship_type}{include file="competitions/competitions_`$championship_type`.tpl"}{/if}


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
