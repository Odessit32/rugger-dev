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
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
</head>
<body>
<div id="main">
    {include file="header.tpl"}

    <div class="left">
        {if $team_item}
            {include file="club/team_item.tpl"}
        {elseif $club_item}
            {include file="club/club_item.tpl"}
        {/if}
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