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
{if !empty($stage_list)}
    <div class="left_menu overflow">
        <ul>
            {foreach key=key item=item from=$stage_list name=stages}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$championship_item.address}/{$tour_item.address}{if $item.address != ''}/{$item.address}{/if}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a></li>
            {/foreach}
        </ul>
    </div>
{/if}
<div class="title_caption title_caption_black"><h1>{$group_item.title}{*. {$championship_item.title}. {$stage_item.title}. {if $competition_item.title}{$competition_item.title}{/if}*}</h1></div>
{if !empty($competition_list)}
    <div class="left_menu_under">
        <ul>
            {foreach key=key item=item from=$competition_list name=comp}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$championship_item.address}/{$tour_item.address}/{$stage_item.address}{if $item.address != ''}/{$item.address}{/if}"{if $item.active == 'yes' && $current_part_type != 'championship'} class="active"{/if}>{$item.title}</a></li>
            {/foreach}
        </ul>
    </div>
{/if}

{if !empty($competition_data.standing) && $current_part_type != 'championship'}
    {include file="competitions/standing_15.tpl"} {* Standing Table for super 15 *}
{/if}
{if !empty($competition_data.standing) && $current_part_type == 'championship'}
    {include file="competitions/standing_super_15.tpl"} {* Standing Table for super 15 *}
{/if}

{if !empty($championship_item.text)}
    <div class="comp_item">
        <div class="post">{$championship_item.text}</div>
    </div>
{/if}

{if !empty($competition_data.games)}
    {include file="competitions/games_15.tpl"} {* Games for super 15 *}
{/if}

{if !empty($competition_data.staff) && !empty($competition_data.show_stuff_rating)}
    {include file="competitions/staff_15.tpl"} {* Staff for super 15 *}
{/if}
