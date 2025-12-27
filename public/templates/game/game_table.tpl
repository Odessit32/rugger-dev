{if $game_item.g_is_done == 'yes'}
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="block_for_tooltip">
        {if !empty($game_item.main.staff_head)}
            {*<tr><th colspan="3">Руководство:</th></tr>*}
            {foreach key=key item=item from=$game_item.main.staff_head name=staff}
                <tr>
                    <td class="game_table_left_title">
                        {foreach item=item_ from=$item.owner name=staff_}
                            <a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>
                            {if !$smarty.foreach.staff_.last}<br>{/if}
                        {/foreach}
                    </td>
                    <td class="game_table_center_title">{$item.title}</td>
                    <td class="game_table_right_title">
                        {foreach item=item_ from=$item.guest name=staff_}
                            <a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>
                            {if !$smarty.foreach.staff_.last}<br>{/if}
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            <tr></tr>
        {/if}
        {if !empty($game_item.reserve.staff_head)}
            {foreach key=key item=item from=$game_item.reserve.staff_head name=staff}
                <tr>
                    <td class="game_table_left_title">
                        {foreach item=item_ from=$item.owner name=staff_}
                            <a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>
                            {if !$smarty.foreach.staff_.last}<br>{/if}
                        {/foreach}
                    </td>
                    <td class="game_table_center_title">{$item.title}</td>
                    <td class="game_table_right_title">
                        {foreach item=item_ from=$item.guest name=staff_}
                            <a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>
                            {if !$smarty.foreach.staff_.last}<br>{/if}
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            <tr></tr>
        {/if}

        {if !empty($game_item.main.staff_player)}
            {*<tr><th colspan="3">{$language.Position}:</th></tr>*}
            {foreach key=key item=item from=$game_item.main.staff_player name=staff}
                <tr>
                    <td class="game_table_left_title">
                        {foreach item=item_ from=$item.owner name=staff_}
                            <div>{$item_.name} <b>{$item_.family}</b></div>
                            {if $item_.game_actions}{foreach item=item_ga key=key_ga from=$item_.game_actions name=staff_ga}<div class="ga_o_{$key_ga}" title="{if $key_ga == 'pop'}Попытка{/if}{if $key_ga == 'pez'}Реализация{/if}{if $key_ga == 'd_g'}Дроп-гол{/if}{if $key_ga == 'sht'}Штрафной{/if}{if $key_ga == 'y_c'}Желтая карточка{/if}{if $key_ga == 'r_c'}Красная карточка{/if}{if $item_ga.time>0}: {$item_ga.time}{/if}">{$item_ga.time}</div>{/foreach}{/if}
                            {if $item_.game_zam}{foreach item=item_ga_z key=key_ga_z from=$item_.game_zam name=staff_ga_zam}<div class="ga_o_{$key_ga_z}" title="Замена{if $item_ga_z.time>0}: {$item_ga_z.time}{/if}">&nbsp;</div>{/foreach}{/if}
                            {*<a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>*}
                        {/foreach}
                    </td>
                    <td class="game_table_center_title">
                        {if $competition_item.championship.ch_chc_id != 2}{$item.title}{/if}
                    </td>
                    <td class="game_table_right_title">
                        {foreach item=item_ from=$item.guest name=staff_}
                            <div>{$item_.name} <b>{$item_.family}</b></div>
                            {if $item_.game_actions}{foreach item=item_ga key=key_ga from=$item_.game_actions name=staff_ga}<div class="ga_g_{$key_ga}" title="{if $key_ga == 'pop'}Попытка{/if}{if $key_ga == 'pez'}Реализация{/if}{if $key_ga == 'd_g'}Дроп-гол{/if}{if $key_ga == 'sht'}Штрафной{/if}{if $key_ga == 'y_c'}Желтая карточка{/if}{if $key_ga == 'r_c'}Красная карточка{/if}{if $item_ga.time>0}: {$item_ga.time}{/if}">{$item_ga.time}</div>{/foreach}{/if}
                            {if $item_.game_zam}{foreach item=item_ga_z key=key_ga from=$item_.game_zam name=staff_ga_zam}<div class="ga_g_{$key_ga_z}" title="Замена{if $item_ga_z.time>0}: {$item_ga_z.time}{/if}">&nbsp;</div>{/foreach}{/if}
                            {*<a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>*}
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            <tr></tr>
        {/if}

        {if !empty($game_item.reserve.staff_player)}
            <tr><th colspan="3">Замены:</th></tr>
            {foreach key=key item=item from=$game_item.reserve.staff_player name=staff}
                <tr>
                    <td class="game_table_left_title">
                        {foreach item=item_ from=$item.owner name=staff_}
                            <div>
                                {if $item_.game_actions}{foreach item=item_ga key=key_ga from=$item_.game_actions name=staff_ga}<div class="ga_o_{$key_ga}" onmouseover="tooltip.show('{if $key_ga == 'pop'}Попытка{/if}{if $key_ga == 'pez'}Реализация{/if}{if $key_ga == 'd_g'}Дроп-гол{/if}{if $key_ga == 'sht'}Штрафной{/if}{if $key_ga == 'y_c'}Желтая карточка{/if}{if $key_ga == 'r_c'}Красная карточка{/if}{if $item_ga.time>0}: {$item_ga.time}{/if}');" onmouseout="tooltip.hide();">{$item_ga.count}</div>{/foreach}{/if}
                                {if $item_.game_zam}{foreach item=item_ga_z key=key_ga_z from=$item_.game_zam name=staff_ga_zam}<div class="ga_o_{$key_ga_z}" onmouseover="tooltip.show('Замена{if $item_ga_z.time>0}: {$item_ga_z.time}{/if}');" onmouseout="tooltip.hide();">&nbsp;</div>{/foreach}{/if}
                                {$item_.name} <b>{$item_.family}</b>
                                {*<a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>*}
                            </div>
                        {/foreach}
                    </td>
                    <td class="game_table_center_title">{$item.title}</td>
                    <td class="game_table_right_title">
                        {foreach item=item_ from=$item.guest name=staff_}
                            <div>
                                {if $item_.game_actions}{foreach item=item_ga key=key_ga from=$item_.game_actions name=staff_ga}<div class="ga_g_{$key_ga}" onmouseover="tooltip.show('{if $key_ga == 'pop'}Попытка{/if}{if $key_ga == 'pez'}Реализация{/if}{if $key_ga == 'd_g'}Дроп-гол{/if}{if $key_ga == 'sht'}Штрафной{/if}{if $key_ga == 'y_c'}Желтая карточка{/if}{if $key_ga == 'r_c'}Красная карточка{/if}{if $item_ga.time>0}: {$item_ga.time}{/if}');" onmouseout="tooltip.hide();">{$item_ga.count}</div>{/foreach}{/if}
                                {if $item_.game_zam}{foreach item=item_ga_z key=key_ga from=$item_.game_zam name=staff_ga_zam}<div class="ga_g_{$key_ga_z}" onmouseover="tooltip.show('Замена{if $item_ga_z.time>0}: {$item_ga_z.time}{/if}');" onmouseout="tooltip.hide();">&nbsp;</div>{/foreach}{/if}
                                {$item_.name} <b>{$item_.family}</b>
                                {*<a href="{$sitepath}player/{$item_.st_id}" onClick="new_div(event, {$item_.st_id}); return false;">{$item_.name} <b>{$item_.family}</b></a>*}
                            </div>
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            <tr></tr>
        {/if}
    </table>
{/if}