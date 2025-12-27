{* Standing Table for 15 *}

{if $competition_item.description != ''}
    <div class="post">{$competition_item.description}</div>
{/if}

<div class="comp_item comp_standing comp_standing_margintop">

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <th>№</th>
            <th class="title">{$language.Team}</th>
            <th class="min_w_small" title="{$language.G_title}">{$language.G}</th>
            <th class="min_w_small" title="{$language.W_title}">{$language.W}</th>
            <th class="min_w_small" title="{$language.D_title}">{$language.D}</th>
            <th class="min_w_small" title="{$language.L_title}">{$language.L}</th>
            <th title="{$language.RIO_title}">{$language.RIO}</th>
            <th class="min_w_small" title="{$language.B1_title}">{$language.B1}</th>
            <th class="min_w_small" title="{$language.B2_title}">{$language.B2}</th>
            <th class="min_w_small" title="{$language.P_title}">{$language.P}</th>
        </tr>
        <tr>
            <td class="p_pre" colspan="8">

            </td>
        </tr>
        <!--
        {*$competition_data|@var_dump*}
        -->
    {foreach key=key item=item from=$competition_data.standing name=team}
        <tr>
            <td class="num">{$smarty.foreach.team.iteration}</td>
            <td class="title">{if $item.t_is_detailed == 'yes'}<a href="{$sitepath}team/{if !empty($item.t_address)}{$item.t_address}{else}{$item.t_id}{/if}">{$item.title}</a>{else}{$item.title}{/if}</td>
            <td class="p_games">{if $item.games>0}{$item.games}{else}-{/if}</td>
            <td class="p_win">{if $item.win>0}{$item.win}{else}-{/if}</td>
            <td class="p_draw">{if $item.draw>0}{$item.draw}{else}-{/if}</td>
            <td class="p_loss">{if $item.loss>0}{$item.loss}{else}-{/if}</td>
            <td class="p_scored">{if $item.p_scored>0 or $item.p_missed>0}{$item.p_scored}-{$item.p_missed} ({if $item.p_scored-$item.p_missed>0}+{/if}{$item.p_scored-$item.p_missed}){else}-{/if}</td>
            <td class="p_b1">{if !empty($item.bonus_1)}{$item.bonus_1}{else}-{/if}</td>
            <td class="p_b2">{if !empty($item.bonus_2)}{$item.bonus_2}{else}-{/if}</td>
            <td class="p_p">{if !empty($item.p)}{$item.p}{else}-{/if}</td>
        </tr>
    {/foreach}
    </table>
    {if $competition_item.text != ''}
        {$competition_item.text}
    {/if}
</div>