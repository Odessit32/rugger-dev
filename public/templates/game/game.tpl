<!DOCTYPE html>
<html{if $game_page_mode == 'small'} class="html_small"{/if}>
<head>
    <title>
        {if $meta_seo_item.title}
            {$meta_seo_item.title}
        {elseif !empty($game_item) && is_array($game_item) && !empty($game_item.owner) && !empty($game_item.guest)}
            «{$game_item.owner.title}» - «{$game_item.guest.title}»{if $game_item.g_is_done == 'yes'} ({$game_item.g_owner_points} : {$game_item.g_guest_points}){/if}, «регби»,{if !empty($competition_item.championship.chg_title)} {$competition_item.championship.chg_title}, {/if}{$game_item.g_date_schedule|date_format:"%d"}{assign var="month_name" value=$game_item.g_date_schedule|date_format:"%m"} {$month.$month_name} {$game_item.g_date_schedule|date_format:"%Y"} - {$conf_vars.title}
        {else}
            {$conf_vars.title}
        {/if}
    </title>
    {if $meta_seo_item.description}
        <meta name="description" content="{$meta_seo_item.description}" />
    {elseif !empty($game_item) && is_array($game_item) && !empty($game_item.owner) && !empty($game_item.guest)}
        <meta name="description" content="«{$game_item.owner.title}» - «{$game_item.guest.title}»{if $game_item.g_is_done == 'yes'} ({$game_item.g_owner_points} : {$game_item.g_guest_points}){/if}, регбийный матч, {$game_item.g_date_schedule|date_format:"%d"}{assign var="month_name" value=$game_item.g_date_schedule|date_format:"%m"} {$month.$month_name} {if !empty($competition_item.championship.chg_title)}«{$competition_item.championship.chg_title}», {/if}{if !empty($competition_item.championship.title)}«{$competition_item.championship.title}»{/if}{if !empty($competition_item.competition.title) && $competition_item.competition.title != $competition_item.championship.chg_title}, «{$competition_item.competition.title}»{/if}" />
    {/if}
    {if $meta_seo_item.keywords}
        <meta name="keywords" content="{$meta_seo_item.keywords}" />
    {/if}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="{$sLang}"/>
    <meta property="og:title" content="{if $meta_seo_item.title}{$meta_seo_item.title}{elseif !empty($game_item) && is_array($game_item) && !empty($game_item.owner) && !empty($game_item.guest)}«{$game_item.owner.title}» - «{$game_item.guest.title}»{if $game_item.g_is_done == 'yes'} ({$game_item.g_owner_points} : {$game_item.g_guest_points}){/if}, «регби»,{if !empty($competition_item.championship.chg_title)} {$competition_item.championship.chg_title}, {/if}{$game_item.g_date_schedule|date_format:"%d"}{assign var="month_name" value=$game_item.g_date_schedule|date_format:"%m"} {$month.$month_name} {$game_item.g_date_schedule|date_format:"%Y"} - {$conf_vars.title}{else}{$conf_vars.title}{/if}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{if !empty($game_item) && is_array($game_item)}{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$game_item.g_id}{else}{$sitepath}{/if}"/>
    <meta property="og:site_name" content="{$conf_vars.title}"/>
    {if $page_item.description_meta != ''}<meta property="og:description" content="{$page_item.description_meta}"/>{/if}
    <link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon"/>
    {if $ENV =='PRODUCTION'}
        <link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.1" type="text/css">
    {else}
        <link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
        <link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
    {/if}
    <link rel="canonical" href="{if !empty($game_item) && is_array($game_item)}{$sitepath}game/{$game_item.g_id}{else}{$sitepath}{/if}"/>
    <script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
    <base href="{$sitepath_lang}">
    {include file="highslide.tpl"}
    {include file="google_analitics.tpl"}
</head>
{if $game_page_mode == 'small'}
    {include file="game/game_small.tpl"}
{else}
    {include file="game/game_big.tpl"}
{/if}
{literal}
<script>
    jQuery(document).ready(function(){
        $( ".block_for_tooltip" ).tooltip();
    });
</script>
{/literal}
</html>