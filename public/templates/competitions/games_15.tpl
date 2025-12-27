{* Games for 15 *}
{if isset($competition_data.standing) && $competition_data.standing}
<div class="title_caption title_caption_black">{$language.competition_games_title}:</div>
{/if}
<div class="comp_item games_table">
    {if $competition_data.games}
        {foreach item=item_m key=key_m from=$competition_data.games name=soon_m}
            <div class="heading_month{if $key_m == $date_key_m} active{/if}" data-key_m="{$key_m}">{$item_m.caption}</div>
            {foreach item=item_d key=key_d from=$item_m.data name=soon}
                <div class="game_item_block gib_{$key_m}">
                    <div class="day">{$item_d.caption}</div>
                    <div class="games_list_">
                        {foreach item=item from=$item_d.data name=soon}
                            {if $item.is_detailed}<a href="{$sitepath}{$page_item.page_path}game/{$item.g_id}" title="{$item.owner.title} - {$item.guest.title}" class="games_list_item game-{$item.g_id}">{else}<span class="games_list_item game-{$item.g_id}">{/if}
                            <span class="title_left">{$item.owner.title}</span>
                            <span class="points">{if $item.g_is_done == 'yes'}{$item.g_owner_points} : {$item.g_guest_points}{else}:{/if}</span>
                            <span class="title_right">{$item.guest.title}</span>
                            {if !empty($item.g_info->live) && $item.g_is_done == 'no' && $live_date_now < $item.g_date_schedule}<span class="live">live</span>{/if}
                            {if $item.is_detailed}</a>{else}</span>{/if}
                        {/foreach}
                    </div>
                </div>
            {/foreach}
        {/foreach}
    {/if}
</div>