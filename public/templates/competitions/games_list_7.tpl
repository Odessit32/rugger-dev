{* Games List for 7 *}

<div class="title_caption title_caption_black">{$language.competition_games_title}:</div>
<div class="comp_item games_table">
    {if $competition_data.games_list}
        {foreach item=item_gl key=games_list_key from=$competition_data.games_list name=soon_m}
            {foreach item=item_m key=key_m from=$item_gl name=soon_m}
                <div class="heading_month" data-key_m="{$key_m}">{$item_m.caption}</div>
                {foreach item=item_d from=$item_m.data name=soon}
                    <div class="game_item_block game_list_w_c gib_{$key_m}">
                        <div class="day">{$item_d.caption}</div>
                        <div class="championship ch_type_7">{$competition_list.$games_list_key.title}</div>
                        <div class="games_list_">
                            {foreach item=item from=$item_d.data name=soon}
                                {if $item.is_detailed}<a href="{$sitepath}{$page_item.page_path}game/{$item.g_id}" title="{$item.owner.title} - {$item.guest.title}" class="games_list_item game-{$item.g_id}">{else}<span class="games_list_item game-{$item.g_id}">{/if}
                                <span class="title_left">{$item.owner.title}</span>
                                <span class="points">{$item.g_owner_points} : {$item.g_guest_points}</span>
                                <span class="title_right">{$item.guest.title} {if $item.g_is_schedule_time == 'yes'}(в {$item.datetime|date_format:"%H:%M"}){/if}</span>
                                {if !empty($item.g_info->live) && $item.g_is_done == 'no' && $live_date_now < $item.g_date_schedule}<span class="live">live</span>{/if}
                                {if $item.is_detailed}</a>{else }</span>{/if}
                            {/foreach}
                        </div>
                    </div>
                {/foreach}
            {/foreach}
        {/foreach}
    {/if}

</div>