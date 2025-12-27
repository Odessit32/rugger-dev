{if $game_item.g_is_done == 'yes'}
    {if !empty($game_action)}
        <ul class="game_time_list">
            <li class="game_time_list_caption">
                <span class="time">{$language.min}</span>
                <span class="owner">{$game_item.owner.title}</span>
                <span class="count">{$language.count}</span>
                <span class="guest">{$game_item.guest.title}</span>
            </li>
            <li class="game_time_list_item">
                <span class="time">0</span>
                <span class="owner"><span class="some_tile">{$language.game_begin}</span></span>
                <span class="count">0 : 0</span>
                <span class="guest"></span>
            </li>
            {foreach key=key item=item from=$game_action name=g_action}
                {if !empty($game_item.g_ft_time) && $key > $game_item.g_ft_time && empty($g_ft)}
                    {assign var="g_ft" value="1"}
                    <li class="game_time_list_item">
                        <span class="time">{$game_item.g_ft_time}</span>
                        <span class="owner"><span class="some_tile">{$language.game_st}</span></span>
                    </li>
                {/if}
                <li class="game_time_list_item">
                    <span class="time">{$key}</span>
                    <span class="owner">
                        {if !empty($item.owner)}
                            {foreach item=ga_item from=$item.owner name=ga_item}
                                {assign var="type" value="long_"|cat:$ga_item.ga_type}
                                {$ga_item.name} {$ga_item.family} - {$language[$type]}
                                {if !$smarty.foreach.ga_item.last}<br>{/if}
                            {/foreach}
                        {/if}
                    </span>
                    <span class="count">{$item.count_owner} : {$item.count_guest}</span>
                    <span class="guest">
                        {if !empty($item.guest)}
                            {foreach item=ga_item from=$item.guest name=ga_item}
                                {assign var="type" value="long_"|cat:$ga_item.ga_type}
                                {$ga_item.name} {$ga_item.family} - {$language[$type]}
                                {if !$smarty.foreach.ga_item.last}<br>{/if}
                            {/foreach}
                        {/if}
                    </span>
                </li>
            {/foreach}
            <li class="game_time_list_item">
                <span class="time"></span>
                <span class="owner"><span class="some_tile">{$language.game_end}</span></span>
                <span class="count">{$game_item.g_owner_points} : {$game_item.g_guest_points}</span>
                <span class="guest"></span>
            </li>
        </ul>
    {/if}
{/if}