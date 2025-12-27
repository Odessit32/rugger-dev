{* Standing Table for 7 *}
<div class="comp_item comp_standing{if $competition_item.id < 1} comp_standing_margintop{/if}">

    {$championship_item.description}
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <th>№</th>
            <th class="title">{$language.Team}</th>
            {if $competition_item.id > 0}<th title="{$language.G_title}">{$language.G}</th>
                <th title="{$language.W_title}">{$language.W}</th>
                <th title="{$language.D_title}">{$language.D}</th>
                <th title="{$language.L_title}">{$language.L}</th>
                <th title="{$language.RIO_title}">{$language.RIO}</th>
                <th title="{$language.B1_title}">{$language.B1}</th>
                <th title="{$language.B2_title}">{$language.B2}</th>
                <th title="{$language.P_title}">{$language.P}</th>
            {else}
            {if $tour_list|@count > 1}{foreach key=key item=item from=$tour_list name=t_list}{if !$smarty.foreach.t_list.first}<th>{$item.title}</th>{/if}{/foreach}{/if}
                <th>{$language.points}</th>
            {/if}
        </tr>
        <tr>
            <td class="p_pre" colspan="8">

            </td>
        </tr>
        {foreach key=key item=item from=$competition_data.standing name=team}
            {if $item.title && (empty($item.t_is_technical) || $item.t_is_technical == 'no') && (empty($item.cntch_is_technical) || $item.cntch_is_technical == 'no')}
            <tr>
                <td class="num">{$smarty.foreach.team.iteration}</td>
                <td class="title">{if $item.t_is_detailed == 'yes'}<a href="{$sitepath}team/{if !empty($item.t_address)}{$item.t_address}{else}{$item.t_id}{/if}">{$item.title}</a>{else}{$item.title}{/if}</td>
                {if $competition_item.id > 0}
                    <td class="p_games">{if $item.games>0}{$item.games}{else}-{/if}</td>
                    <td class="p_win">{if $item.win>0}{$item.win}{else}-{/if}</td>
                    <td class="p_draw">{if $item.draw>0}{$item.draw}{else}-{/if}</td>
                    <td class="p_loss">{if $item.loss>0}{$item.loss}{else}-{/if}</td>
                    <td class="p_scored">{if $item.p_scored>0 or $item.p_missed>0}{$item.p_scored}-{$item.p_missed} ({$item.p_scored-$item.p_missed}){else}-{/if}</td>
                    <td class="p_b1">{if !empty($item.bonus_1)}{$item.bonus_1}{else}-{/if}</td>
                    <td class="p_b2">{if !empty($item.bonus_2)}{$item.bonus_2}{else}-{/if}</td>
                    <td class="p_p">{if !empty($item.p)}{$item.p}{else}-{/if}</td>
                {else}
                    {foreach item=item_t from=$item.p_tour}<td class="p_p">{if $item_t}{$item_t}{else}0{/if}</td>{/foreach}
                    {if $item.p_tour|@count<$championship_item.ch_tours+1}<!-- t: {$item.p_tour|@count}-{$championship_item.ch_tours+1} -->
                        {assign var="count_p_tours" value=$item.p_tour|@count}
                        {assign var="count_rest_tours" value=$championship_item.ch_tours-$count_p_tours+1}
                        {section name = day start = 0 loop = $count_rest_tours}
                        <td>0</td>
                    {/section}{/if}
                    <td class="p_p">{if $item.p>0}{$item.p}{else}0{/if}</td>
                {/if}
            </tr>
            {/if}
        {/foreach}
    </table>
</div>