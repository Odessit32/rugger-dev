{if !empty($game_actions) && !empty($game_teems)}
<div class="page_content">
    <div class="content_game_teems game_action_list">
        <ul class="team_list teem_owner team_list_half">
            <li class="teem_title">{$game_item.owner.title}</li>
            {if !empty($game_actions.by_type[$game_item.g_owner_t_id].pop)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.T_title}">П</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_owner_t_id].pop name=o_pop}
                        {if !empty($game_teems.owner.all[$key]) && $game_teems.owner.all[$key]['app'] != 'technical'}
                            {$game_teems.owner.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.o_pop.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_owner_t_id].pez)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.R_title}">Р</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_owner_t_id].pez name=o_r}
                        {if !empty($game_teems.owner.all[$key]) && $game_teems.owner.all[$key]['app'] != 'technical'}
                            {$game_teems.owner.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.o_r.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_owner_t_id].sht)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.Sh_title}">Ш</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_owner_t_id].sht name=sht}
                        {if !empty($game_teems.owner.all[$key]) && $game_teems.owner.all[$key]['app'] != 'technical'}
                            {$game_teems.owner.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.sht.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_owner_t_id].d_g)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.DG_title}">ДГ</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_owner_t_id].d_g name=d_g}
                        {if !empty($game_teems.owner.all[$key]) && $game_teems.owner.all[$key]['app'] != 'technical'}
                            {$game_teems.owner.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.d_g.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_owner_t_id].y_c)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.Zhk_title}">ЖК</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_owner_t_id].y_c name=y_c}
                        {if !empty($game_teems.owner.all[$key]) && $game_teems.owner.all[$key]['app'] != 'technical'}
                            {$game_teems.owner.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.y_c.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_owner_t_id].r_c)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.KK_title}">КК</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_owner_t_id].r_c name=r_c}
                        {if !empty($game_teems.owner.all[$key]) && $game_teems.owner.all[$key]['app'] != 'technical'}
                            {$game_teems.owner.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.r_c.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
        </ul>
        <ul class="team_list teem_guest team_list_half">
            <li class="teem_title">{$game_item.guest.title}</li>
            {if !empty($game_actions.by_type[$game_item.g_guest_t_id].pop)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.T_title}">П</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_guest_t_id].pop name=o_pop}
                        {if !empty($game_teems.guest.all[$key]) && $game_teems.guest.all[$key]['app'] != 'technical'}
                            {$game_teems.guest.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.o_pop.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_guest_t_id].pez)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.R_title}">Р</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_guest_t_id].pez name=o_r}
                        {if !empty($game_teems.guest.all[$key]) && $game_teems.guest.all[$key]['app'] != 'technical'}
                            {$game_teems.guest.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.o_r.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_guest_t_id].sht)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.Sh_title}">Ш</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_guest_t_id].sht name=sht}
                        {if !empty($game_teems.guest.all[$key]) && $game_teems.guest.all[$key]['app'] != 'technical'}
                            {$game_teems.guest.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.sht.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_guest_t_id].d_g)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.DG_title}">ДГ</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_guest_t_id].d_g name=d_g}
                        {if !empty($game_teems.guest.all[$key]) && $game_teems.guest.all[$key]['app'] != 'technical'}
                            {$game_teems.guest.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.d_g.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_guest_t_id].y_c)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.Zhk_title}">ЖК</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_guest_t_id].y_c name=y_c}
                        {if !empty($game_teems.guest.all[$key]) && $game_teems.guest.all[$key]['app'] != 'technical'}
                            {$game_teems.guest.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.y_c.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
            {if !empty($game_actions.by_type[$game_item.g_guest_t_id].r_c)}
                <li class="team_staff_item">
                    <div class="actions">
                        <strong class="show-tooltip" title="{$language.KK_title}">КК</strong>:
                    </div>
                    <div class="names">
                    {foreach key=key item=item from=$game_actions.by_type[$game_item.g_guest_t_id].r_c name=r_c}
                        {if !empty($game_teems.guest.all[$key]) && $game_teems.guest.all[$key]['app'] != 'technical'}
                            {$game_teems.guest.all[$key]['family']} {if !empty(implode('', $item))}({implode(', ', $item)}){elseif count($item)>0}({$item|count}){/if}{if !$smarty.foreach.r_c.last}, {/if}
                        {/if}
                    {/foreach}
                    </div>
                </li>
            {/if}
        </ul>
    </div>
</div>
{/if}