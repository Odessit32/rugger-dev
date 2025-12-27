{* Standing Table for super 15 *}

<div class="comp_item comp_standing comp_standing_super_15">

    {$championship_item.description}

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <th>№</th>
            <th class="title">{$language.Team}</th>
            <th title="{$language.G_title}">{$language.G}</th>
            <th title="{$language.W_title}">{$language.W}</th>
            <th title="{$language.D_title}">{$language.D}</th>
            <th title="{$language.L_title}">{$language.L}</th>
            <th title="{$language.RIO_title}">{$language.RIO}</th>
            <th title="{$language.P_title}">{$language.P}</th>
        </tr>
        <tr>
            <td class="p_pre" colspan="8">

            </td>
        </tr>
        {foreach item=item_s from=$competition_data.standing name=standing}
            {foreach item=item from=$item_s name=team}
                <tr>
                    <td class="num">
                        {if $smarty.foreach.standing.iteration == 1}
                            {$smarty.foreach.team.iteration}
                            {assign var="standing_teams_count_first" value=$smarty.foreach.team.total}
                        {else}
                            {$smarty.foreach.team.iteration+$standing_teams_count_first}
                        {/if}</td>
                    <td class="title">{if $item.t_is_detailed == 'yes'}<a href="{$sitepath}team/{if !empty($item.t_address)}{$item.t_address}{else}{$item.t_id}{/if}">{$item.title}</a>{else}{$item.title}{/if}</td>
                    <td class="p_games">{if $item.games>0}{$item.games}{else}-{/if}</td>
                    <td class="p_win">{if $item.win>0}{$item.win}{else}-{/if}</td>
                    <td class="p_draw">{if $item.draw>0}{$item.draw}{else}-{/if}</td>
                    <td class="p_loss">{if $item.loss>0}{$item.loss}{else}-{/if}</td>
                    <td class="p_scored">{if $item.p_scored>0 or $item.p_missed>0}{$item.p_scored}-{$item.p_missed} ({if $item.p_scored-$item.p_missed>0}+{/if}{$item.p_scored-$item.p_missed}){else}-{/if}</td>
                    <td class="p_p">{if $item.p!=0}{$item.p}{else}-{/if}</td>
                </tr>
            {/foreach}
        {/foreach}
    </table>
    {if $championship_item.text != ''}<div class="post">{$championship_item.text}</div>{/if}
</div>