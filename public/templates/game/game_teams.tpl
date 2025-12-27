{if !empty($game_teem_staff)  &&
        (
            (
                $competition_item.championship.ch_chc_id == 1 &&
                count($game_teem_staff.owner.main) >= 15 &&
                count($game_teem_staff.guest.main) >= 15
            ) ||
            (
                $competition_item.championship.ch_chc_id == 3 &&
                count($game_teem_staff.owner.main) >= 15 &&
                count($game_teem_staff.guest.main) >= 15
            ) ||
            (
                $competition_item.championship.ch_chc_id == 2 &&
                count($game_teem_staff.owner.main) >= 7 &&
                count($game_teem_staff.guest.main) >= 7
            )
        )

    }
{*

*}#
    <div class="page_content">
        <div class="content_game_teems">
            <ul class="team_list teem_owner team_list_half">
                <li class="teem_title">{$language.Base}</li>
                {foreach key=key item=item from=$game_teem_staff.owner.main name=towner}
                    {if $item.name && $item.app_title != 'technical'}
                        <li class="team_staff_item{if !empty($item.action_zam)} no_active{/if}">
                            <span class="num">{$item.app_title}</span>
                            <span class="action">
                                {if !empty($item.action_zam) && isset($item.action_zam.ga_zst_id) && isset($item.action_zam.ga_min)}
                                    <span class="ga_zam_out"
                                          title="{$language.replaced} {$game_teems.owner[$item.action_zam.ga_zst_id]['name']|default:''} {$game_teems.owner[$item.action_zam.ga_zst_id]['family']|default:''} ({$item.action_zam.ga_min} м.)"></span>
                                {/if}
                            </span>
                            <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                            {*<span class="bd">{if $item.st_is_show_birth == 'yes'}{$item.st_date_birth|date_format:"%d.%m.%Y"}{/if}</span>*}
                        </li>
                    {/if}
                {/foreach}
            </ul>
            <ul class="team_list teem_guest team_list_half">
                <li class="teem_title">{$language.Base}</li>
                {foreach key=key item=item from=$game_teem_staff.guest.main name=tguest}
                    {if $item.name && $item.app_title != 'technical'}
                        <li class="team_staff_item{if !empty($item.action_zam)} no_active{/if}">
                            <span class="num">{$item.app_title}</span>
                            <span class="action">
                                {if !empty($item.action_zam) && isset($item.action_zam.ga_zst_id) && isset($item.action_zam.ga_min)}
                                    <span class="ga_zam_out"
                                          title="{$language.replaced} {$game_teems.guest[$item.action_zam.ga_zst_id]['name']|default:''} {$game_teems.guest[$item.action_zam.ga_zst_id]['family']|default:''} ({$item.action_zam.ga_min} м.)"></span>
                                {/if}
                            </span>
                            <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                            {*<span class="bd">{if $item.st_is_show_birth == 'yes'}{$item.st_date_birth|date_format:"%d.%m.%Y"}{/if}</span>*}
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="page_content clear_both">
        <div class="content_game_teems">
            {if !empty($game_teem_staff.owner.zam_all)}
                <ul class="team_list teem_owner team_list_half">
                    <li class="green_title">{$language.Zam}</li>
                    {if !empty($game_teem_staff.owner.zam_all)}
                    {foreach key=key item=item from=$game_teem_staff.owner.zam_all name=towner}
                        {if $item.name && $item.app_title != 'technical'}
                            <li class="team_staff_item{if empty($item.action_zam)} no_active{/if}">
                                <span class="num">{$item.app_title}</span>
                                <span class="action">
                                    {if !empty($item.action_zam) && isset($item.action_zam.ga_zst_id) && isset($item.action_zam.ga_min)}
                                        <span class="ga_zam_in"
                                              title="{$language.replaced} {$game_teems.owner[$item.action_zam.ga_zst_id]['name']|default:''} {$game_teems.owner[$item.action_zam.ga_zst_id]['family']|default:''} ({$item.action_zam.ga_min} м.)"></span>
                                    {/if}
                                </span>
                                <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                                {*<span class="bd">{if $item.st_is_show_birth == 'yes'}{$item.st_date_birth|date_format:"%d.%m.%Y"}{/if}</span>*}
                            </li>
                        {/if}
                    {/foreach}
                    {/if}
                </ul>
            {/if}
            {if !empty($game_teem_staff.guest.zam_all) || !empty($game_teem_staff.guest.reserve)}
                <ul class="team_list teem_guest team_list_half">
                    <li class="green_title">{$language.Zam}</li>
                    {if !empty($game_teem_staff.guest.zam_all)}
                    {foreach key=key item=item from=$game_teem_staff.guest.zam_all name=towner}
                        {if $item.name && $item.app_title != 'technical'}
                            <li class="team_staff_item{if empty($item.action_zam)} no_active{/if}">
                                <span class="num">{$item.app_title}</span>
                                <span class="action">
                                    {if !empty($item.action_zam) && isset($item.action_zam.ga_zst_id) && isset($item.action_zam.ga_min)}
                                        <span class="ga_zam_in"
                                              title="{$language.replaced} {$game_teems.guest[$item.action_zam.ga_zst_id]['name']|default:''} {$game_teems.guest[$item.action_zam.ga_zst_id]['family']|default:''} ({$item.action_zam.ga_min} м.)"></span>
                                    {/if}
                                </span>
                                <span class="name">{$item.name} {*$item.surname*} {$item.family}</span>
                                {*<span class="bd">{if $item.st_is_show_birth == 'yes'}{$item.st_date_birth|date_format:"%d.%m.%Y"}{/if}</span>*}
                            </li>
                        {/if}
                    {/foreach}
                    {/if}
                </ul>
            {/if}
        </div>
    </div>
    {*
    <div class="page_content clear_both">
        <div class="content_game_teems">
            {if !empty($game_teem_staff.owner.zam) || !empty($game_teem_staff.owner.reserve)}
                <ul class="team_list teem_owner team_list_half">
                    <li class="green_title">{$language.Zam}</li>
                    {if !empty($game_teem_staff.owner.zam)}
                    {foreach key=key item=item from=$game_teem_staff.owner.zam name=towner}
                        {if $item.name && $item.app_title != 'technical'}
                            <li class="team_staff_item{if empty($item.action_zam)} no_active{/if}">
                                <span class="num">{$item.app_title}</span>
                                <span class="action">
                                    {if !empty($item.action_zam)}
                                        <span class="ga_zam_in"
                                              title="{$language.replaced} {$game_teems.owner[$item.action_zam.ga_zst_id]['name']} {$game_teems.owner[$item.action_zam.ga_zst_id]['family']} ({$item.action_zam.ga_min} м.)"></span>
                                    {/if}
                                </span>
                                <span class="name">{$item.name} {$item.family}</span>
                            </li>
                        {/if}
                    {/foreach}
                    {/if}
                    {if !empty($game_teem_staff.owner.reserve)}
                    {foreach key=key item=item from=$game_teem_staff.owner.reserve name=towner}
                        {if $item.name && $item.app_title != 'technical'}
                            <li class="team_staff_item{if empty($item.action_zam)} no_active{/if}">
                                <span class="num">{$item.app_title}</span>
                                <span class="action">
                                    {if !empty($item.action_zam)}
                                        <span class="ga_zam_in"
                                              title="{$language.replaced} {$game_teems.owner[$item.action_zam.ga_zst_id]['name']} {$game_teems.owner[$item.action_zam.ga_zst_id]['family']} ({$item.action_zam.ga_min} м.)"></span>
                                    {/if}
                                </span>
                                <span class="name">{$item.name} {$item.family}</span>
                            </li>
                        {/if}
                    {/foreach}
                    {/if}
                </ul>
            {/if}
            {if !empty($game_teem_staff.guest.zam) || !empty($game_teem_staff.guest.reserve)}
                <ul class="team_list teem_guest team_list_half">
                    <li class="green_title">{$language.Zam}</li>
                    {if !empty($game_teem_staff.guest.zam)}
                    {foreach key=key item=item from=$game_teem_staff.guest.zam name=towner}
                        {if $item.name && $item.app_title != 'technical'}
                            <li class="team_staff_item{if empty($item.action_zam)} no_active{/if}">
                                <span class="num">{$item.app_title}</span>
                                <span class="action">
                                    {if !empty($item.action_zam)}
                                        <span class="ga_zam_in"
                                              title="{$language.replaced} {$game_teems.guest[$item.action_zam.ga_zst_id]['name']} {$game_teems.guest[$item.action_zam.ga_zst_id]['family']} ({$item.action_zam.ga_min} м.)"></span>
                                    {/if}
                                </span>
                                <span class="name">{$item.name}  {$item.family}</span>
                            </li>
                        {/if}
                    {/foreach}
                    {/if}
                    {if !empty($game_teem_staff.guest.reserve)}
                    {foreach key=key item=item from=$game_teem_staff.guest.reserve name=towner}
                        {if $item.name && $item.app_title != 'technical'}
                            <li class="team_staff_item{if empty($item.action_zam)} no_active{/if}">
                                <span class="num">{$item.app_title}</span>
                                <span class="action">
                                    {if !empty($item.action_zam)}
                                        <span class="ga_zam_in"
                                              title="{$language.replaced} {$game_teems.guest[$item.action_zam.ga_zst_id]['name']} {$game_teems.guest[$item.action_zam.ga_zst_id]['family']} ({$item.action_zam.ga_min} м.)"></span>
                                    {/if}
                                </span>
                                <span class="name">{$item.name} {$item.family}</span>
                            </li>
                        {/if}
                    {/foreach}
                    {/if}
                </ul>
            {/if}
        </div>
    </div>
    *}

{/if}