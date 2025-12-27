{strip}
{include file="html_body.tpl"}
<div class="header">
    <div class="header_content">
        {*
        <ul class="social_list">
            {if $conf_vars.share_rss != ''}<li><a href="{$conf_vars.share_rss}" target="_blank" class="rss"></a></li>{/if}
            {if $conf_vars.share_tw != ''}<li><a href="{$conf_vars.share_tw}" target="_blank" class="tw"></a></li>{/if}
            {if $conf_vars.share_fb != ''}<li><a href="{$conf_vars.share_fb}" target="_blank" class="fb"></a></li>{/if}
            {if $conf_vars.share_vk != ''}<li><a href="{$conf_vars.share_vk}" target="_blank" class="vk"></a></li>{/if}
        </ul>
        *}
        <div itemscope itemtype="http://schema.org/Organization" class="logo"><a itemprop="url" href="{$sitepath}" title="Главная"><img itemprop="logo" src="{$imagepath}images/logo_header.png" alt="Rugger.info - новости регби"></a></div>
        <div class="search">
            <form class="s_form" method="post" action="{$sitepath}search/" role="search">
                <label for="q_search" class="visually-hidden">Поиск по сайту</label>
                <input type="text" class="s_text" name="q_search" id="q_search" placeholder="Поиск..." />
                <input type="submit" class="s_submit" name="submitsearch" value="Найти" title="Поиск" />
            </form>
        </div>
        {*
        <div class="lang">
            {if $lang_settings.rus.sl_is_active == 'yes'}<a href="{$sitepath_lang}{if $dlang != 'rus'}rus/{/if}{if !empty($page_item)}{$page_item.page_path}{$page_item.p_adress}{/if}{if !empty($current_category)}/{$current_category.adress}{/if}{if !empty($news_item)}/{$news_item.id}{/if}{if !empty($gallegy_item)}/{$gallegy_item.id}{/if}{if !empty($staff_item)}/{$staff_item.address}{/if}{if !empty($message_item)}/{$message_item.fb_locator}-{$message_item.fb_id}{/if}"{if $lang == 'rus'} class="active"{/if}>рус</a>{/if}
            {if $lang_settings.ukr.sl_is_active == 'yes'}<a href="{$sitepath_lang}{if $dlang != 'ukr'}ukr/{/if}{if !empty($page_item)}{$page_item.page_path}{$page_item.p_adress}{/if}{if !empty($current_category)}/{$current_category.adress}{/if}{if !empty($news_item)}/{$news_item.id}{/if}{if !empty($gallegy_item)}/{$gallegy_item.id}{/if}{if !empty($staff_item)}/{$staff_item.address}{/if}{if !empty($message_item)}/{$message_item.fb_locator}-{$message_item.fb_id}{/if}"{if $lang == 'ukr'} class="active"{/if}>укр</a>{/if}
            {if $lang_settings.eng.sl_is_active == 'yes'}<a href="{$sitepath_lang}{if $dlang != 'eng'}eng/{/if}{if !empty($page_item)}{$page_item.page_path}{$page_item.p_adress}{/if}{if !empty($current_category)}/{$current_category.adress}{/if}{if !empty($news_item)}/{$news_item.id}{/if}{if !empty($gallegy_item)}/{$gallegy_item.id}{/if}{if !empty($staff_item)}/{$staff_item.address}{/if}{if !empty($message_item)}/{$message_item.fb_locator}-{$message_item.fb_id}{/if}"{if $lang == 'eng'} class="active"{/if}>eng</a>{/if}
        </div>
        *}
    </div>
</div>
<div class="header_menu_icon">
    <span></span>
    <span></span>
    <span></span>
    Меню
</div>
<div class="header_menu">
    <div class="center_block">
        {if !empty($menu_tree)}
            <ul class="menu_list">
                {foreach item=item_mt_i from=$menu_tree.0 name="m_t"}
                    <li><a href="{$sitepath}{if !empty($item_mt_i.path)}{$item_mt_i.path}{/if}{$item_mt_i.adress}" title="{$item_mt_i.title}" class="{if $item_mt_i.is_dropdown && $item_mt_i.menu_dropdown}w_sub_menu{/if}{if !empty($item_mt_i.active) && $item_mt_i.active == 'yes'} active{/if}">{$item_mt_i.title}</a>
                    {if $item_mt_i.is_dropdown && $item_mt_i.menu_dropdown}
                        <ul class="ul_sub_menu">
                            {foreach item=item_dd from=$item_mt_i.menu_dropdown name="dd_m_t"}
                                <li><a href="{$sitepath}{if !empty($item_dd.path)}{$item_dd.path}{/if}{$item_dd.address}" title="{$item_dd.title}"{if !empty($item_dd.active) && $item_dd.active == 'yes'} class="active"{/if}>{$item_dd.title}</a></li>
                            {/foreach}
                        </ul>
                    {/if}
                    </li>
                    {if $smarty.foreach.m_t.first}
                        {if $section_country}
                            <li>
                                <a href="#" title="" class="w_sub_menu {if $section_type == 'country'} active{/if} no-link">
                                    {$language.Country}
                                </a>
                                <ul class="ul_sub_menu">
                                {foreach item=item_sm from=$section_country name="sc_m_t"}
                                    <li><a href="{$sitepath}{if !empty($item_sm.path)}{$item_sm.path}{/if}{$item_sm.adress}" title="{$item_sm.title}"{if !empty($item_sm.active) && $item_sm.active == 'yes'} class="active"{/if}>{$item_sm.title}</a></li>
                                {/foreach}
                                </ul>
                            </li>
                        {/if}
                        {if $section_championship}
                            <li>
                                <a href="#" title="" class="w_sub_menu {if $section_type == 'championship'} active{/if} no-link">
                                    {$language.Championship}
                                </a>
                                <div class="ul_sub_menu extra">
                                    {foreach item=item_sm_menu from=$section_championship}
                                        {if !empty($item_sm_menu)}
                                        <ul class="ul_sub_menu_extra">
                                            {foreach item=item_sm from=$item_sm_menu name="sc_m_t"}
                                                <li><a href="{$sitepath}{if !empty($item_sm.path)}{$item_sm.path}{/if}{$item_sm.adress}" title="{$item_sm.title}"{if !empty($item_sm.active) && $item_sm.active == 'yes'} class="active"{/if}>{$item_sm.title}</a></li>
                                            {/foreach}
                                        </ul>
                                        {/if}
                                    {/foreach}
                                </div>
                            </li>
                        {/if}
                    {/if}
                {/foreach}
            </ul>
            <span></span>
        {/if}
    </div>
    {if !empty($menu_tree.1)}
        {assign var="sub_menu_stop" value=0}
        {foreach item=item key=key from=$menu_tree name="m"}
            {if $key>0 && $item && $sub_menu_stop < 1}
                <div class="sub_menu sub_menu_pages">
                    <ul class="sub_menu_list">
                        {if $key == 1 && $section}
                            <li class="country_logo">
                                <a href="{$sitepath}{$section.page.page_path}{$section.page.p_adress}">
                                {if $section.logo}<img src="{$imagepath}{$section.logo.pe_item_id}" class="country_logo_img" />{/if}
                                <em>{$section.page.title}</em>
                                </a>
                            </li>
                        {/if}
                        {foreach item=item_mt_i from=$item name="m_t"}
                            <li>
                                <a href="{if $item_mt_i.is_dropdown && $item_mt_i.menu_dropdown}{$sitepath}{$item_mt_i.path}{$item_mt_i.adress}/{$item_mt_i.menu_dropdown[0].address}{else}{$sitepath}{$item_mt_i.path}{$item_mt_i.adress}{/if}" title="{$item_mt_i.title}" class="{if !empty($item_mt_i.active) && $item_mt_i.active == 'yes'}active{/if}{if $item_mt_i.is_dropdown && $item_mt_i.menu_dropdown} w_sub_menu{/if}">{$item_mt_i.title}</a>
                                {if !empty($item_mt_i.active) && $item_mt_i.active == 'yes' && !empty($item_mt_i.is_dropdown) && !empty($item_mt_i.menu_dropdown)}
                                    {assign var="sub_menu_stop" value=1}
                                {/if}
                                {if $item_mt_i.is_dropdown && $item_mt_i.menu_dropdown}
                                    <ul class="ul_sub_menu">
                                        {foreach item=item_dd from=$item_mt_i.menu_dropdown name="dd_m_t"}
                                            <li><a href="{$sitepath}{$item_mt_i.path}{$item_mt_i.adress}/{$item_dd.address}" title="{$item_dd.title}"{if !empty($item_dd.active) && $item_dd.active == 'yes'} class="active"{/if}>{$item_dd.title}</a></li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>
            {/if}
        {/foreach}
    {/if}

    {if !empty($page_item) && $page_item.p_mod_id == 31}{include file="competitions/competitions_menu.tpl"}{/if}
    {if !empty($page_item) && $page_item.p_mod_id == 37}{include file="competitions/s_competitions_menu.tpl"}{/if}

    {include file="breadcrumbs.tpl"}
</div>
{/strip}
