{if !empty($championship_list)}
    <div class="archive_menu">
        <span class="">Архив</span>
        <ul class="archive_submenu">
            {foreach key=key item=item from=$championship_list name=stages}
                <li{if $item.active == 'yes'} class="active"{/if}><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$item.address}">{$item.title}</a></li>
            {/foreach}
        </ul>
    </div>
{/if}
{if $stage_list}
    <div class="left_menu overflow">
        <ul>
            {foreach key=key item=item from=$stage_list name=stages}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$championship_item.address}/{$tour_item.address}{if $item.address != ''}/{$item.address}{/if}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a></li>
            {/foreach}
        </ul>
    </div>
{else}
    <div class="left_menu_under_space"></div>
{/if}
<div class="title_caption title_caption_black"><h1>{$group_item.title}{*{$championship_item.title}. {if $stage_item.title}. {$stage_item.title}{/if}*}{if !empty($championship_item.ch_settings.tourTitle[$sLang][$tour_item.address]) && $championship_item.ch_settings.tourTitle[$sLang][$tour_item.address] != 'Тур 1'}. {$championship_item.ch_settings.tourTitle[$sLang][$tour_item.address]}{/if}</h1></div>
{if $competition_list and $current_part_type == 'competition'}
    <div class="left_menu_under">
        <ul>
            {foreach key=key item=item from=$competition_list name=comp}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$championship_item.address}/{$tour_item.address}/{$stage_item.address}{if $item.address != ''}/{$item.address}{/if}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a></li>
            {/foreach}
        </ul>
    </div>
{/if}

{if !empty($competition_data.standing)}
    {include file="competitions/standing_7.tpl"} {* Standing Table for 7 *}
{/if}

{if !empty($championship_item.text)}
    <div class="comp_item">
        <div class="post">{$championship_item.text}</div>
    </div>
{/if}

{if !empty($competition_data.games)}
    {include file="competitions/games_7.tpl"} {* Games for 15 *}
{/if}

{if !empty($competition_data.games_list)}
    {include file="competitions/games_list_7.tpl"} {* Games List (Stage) for 7 *}
{/if}