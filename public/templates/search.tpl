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
                    {if $page_item.p_is_title == 'yes'}<h1>{$page_item.title}</h1>{/if}
                    {if $page_item.p_is_socials == 'yes'}{include file="social.tpl"}{/if}
                </div>

                <div class="content_post border">

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

                    <div class="search_form">
                        <form action="{$sitepath}search/" method="post">
                            <input type="text" name="q_search" class="search_input" value="{$q_search}">
                            <input type="submit" name="submitsearch" class="search_submit" value="{$language.search_form_title}">
                        </form>
                    </div>

                    {if $search_list}
                        <h4>{$language.search_results} &laquo;{$q_search}&raquo;.</h4>
                        <br>
                        <div class="search_result">
                            {foreach key=key item=item from=$search_list name=search}
                                {if $item.type == 'page'}
                                    <h4><a href="{$sitepath}{$item.address}">{$item.title}</a></h4>
                                    <p>{$item.description}</p><br>
                                {/if}
                                {if $item.type == 'news'}
                                    <h4><a href="{$sitepath}{$item.address}" title="{$conf_vars.title} :: {$item.title}">{$item.title}</a></h4>
                                    <div class="date">{$item.n_date_show|date_format:"%d.%m.%Y"}</div>
                                    {$item.description}
                                {/if}
                            {/foreach}
                        </div>
                        {if $search_pages}
                            <div class="pages"><i>{$language.pages}: </i>
                                {foreach key=key item=item from=$search_pages}
                                    {if $current_page == $item}<b>{$item}</b>{else}<a href="{$sitepath}search/page{$item}/{$q_search_url}">{$item}</a>{/if}
                                {/foreach}
                            </div>
                        {/if}
                    {else}
                        {if $q_search != ''}
                            <h2>{$language.on_your_request} &laquo;{$q_search}&raquo; {$language.no_results}.</h2><br>
                            <div id="description">{$language.please_try_again}.</div>
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