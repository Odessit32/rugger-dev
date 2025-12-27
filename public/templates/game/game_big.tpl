<body>
<div class="content">
    {include file="header.tpl"}
    <div class="content_block">
        <div class="left_block">
            {if !empty($game_item) && is_array($game_item)}
                {if !empty($admin_user) && (!empty($admin_user.admin_status) || !empty($admin_user.publisher_status))} 
                    <span class="admin_edit_game">[<a href="{$imagepath}admin/?show=games&get=edit&item={$game_item.g_id}" target="_blank">edit</a>]</span>
                {/if}
                <div class="block_game_title">
                    <div class="black_caption">
                        {include file="social_small.tpl"}
                        {if $competition_item.championship.chg_title}{$competition_item.championship.chg_title}.{/if}
                        {*{$competition_item.championship.title}.*}
                        {if $competition_item.competition.title && $competition_item.competition.title != $competition_item.championship.chg_title}{$competition_item.competition.title}.{/if}
                        {$game_item.g_date_schedule|date_format:"%d"}
                        {assign var="month_name" value=$game_item.g_date_schedule|date_format:"%m"} {$month.$month_name}
                        {$game_item.g_date_schedule|date_format:"%Y"}.
                        {if $game_item.g_is_schedule_time == 'yes'} {$game_item.g_date_schedule|date_format:"%H:%M"}{/if}
                        {if $game_item.g_is_stadium == 'yes'}<b>{$game_item.country}. {$game_item.city}. {$game_item.stadium}</b>{/if}
                    </div>
                    {include file="game/game_title_big.tpl"}
                </div>
                <div class="left_menu overflow">
                    <ul>
                        {if $is_teams}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$game_item.g_id}/teams"{if $game_page_mode == 'teams'} class="active"{/if}>{$language.teams}</a></li>{/if}
                        {if !empty($game_actions)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$game_item.g_id}/time"{if $game_page_mode == 'time'} class="active"{/if}>{$language.chronology}</a></li>{/if}
                        {if !empty($game_item.g_info->custom_report)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$game_item.g_id}/report"{if $game_page_mode == 'report'} class="active"{/if}>{if !empty($game_item.g_info->custom_report_title)}{$game_item.g_info->custom_report_title}{else}{$language.report}{/if}</a></li>{/if}
                        {if !empty($game_item.g_info->live)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$game_item.g_id}/live"{if $game_page_mode == 'live'} class="active"{/if}>{if !empty($game_item.g_info->live_title)}{$game_item.g_info->live_title}{else}Live{/if}</a></li>{/if}
                        {if !empty($game_item.g_info->town) || !empty($game_item.g_info->stadium) || !empty($game_item.g_info->viewers) ||
                        !empty($game_item.g_info->main_judge) || !empty($game_item.g_info->side_referee) || !empty($game_item.g_info->video_referee)}
                            <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$game_item.g_id}/info"{if $game_page_mode == 'info'} class="active"{/if}>{$language.information}</a></li>
                        {/if}
                        {if $competition_item.championship.chg_title}
                        <li>
                            <a href="{$sitepath}{$competition_item.championship.ch_settings.main_url}{if !empty($competition_item.competition.cp_tour)}/{$competition_item.competition.cp_tour}/{$competition_item.competition.cp_substage}{/if}">{$language.table}</a>
                        </li>
                        {/if}
                    </ul>
                </div>

                {*include file="game/game_nav.tpl"*}
                {include file="game/game_action_list.tpl"}
                {*<div class="title_caption title_caption_black">*}
                {*{if $competition_item.championship.chg_title}{$competition_item.championship.chg_title}.{/if}*}
                {*{$competition_item.championship.title}.*}
                {*{if $competition_item.competition.title}{$competition_item.competition.title}.{/if}*}
                {*</div>*}
                {if $game_page_mode == 'time' && !empty($game_actions)}
                    <div class="comp_item">
                        {include file="game/game_table_time.tpl"}
                        {include file="game/game_photo.tpl"}
                    </div>
                {elseif $game_page_mode == 'report'}
                    <div class="page_content">
                        <div class="content_post border">
                            {$game_item.g_info->custom_report}
                        </div>
                    </div>
                {elseif $game_page_mode == 'teams'}
                    {include file="game/game_teams.tpl"}
                {elseif $game_page_mode == 'live'}
                    <div class="page_content">
                        <div class="content_post border">
                            {if !empty($game_item.g_info->live)}<div class="live">{$game_item.g_info->live}</div>{/if}
                        </div>
                    </div>
                {elseif $game_page_mode == 'info'}
                    <div class="page_content">
                        <div class="content_post block_info">
                            {if !empty($game_item.g_info->town)}
                                <div class="info_item">
                                    <div class="title">{$language.town}:</div>
                                    <div class="text">{$game_item.g_info->town}</div>
                                </div>
                            {/if}
                            {if !empty($game_item.g_info->stadium)}
                                <div class="info_item">
                                    <div class="title">{$language.stadium}:</div>
                                    <div class="text">{$game_item.g_info->stadium}</div>
                                </div>
                            {/if}
                            {if !empty($game_item.g_info->viewers)}
                                <div class="info_item">
                                    <div class="title">{$language.viewers}:</div>
                                    <div class="text">{$game_item.g_info->viewers}</div>
                                </div>
                            {/if}
                            {if !empty($game_item.g_info->main_judge)}
                                <div class="info_item">
                                    <div class="title">{$language.main_judge}:</div>
                                    <div class="text">{$game_item.g_info->main_judge}</div>
                                </div>
                            {/if}
                            {if !empty($game_item.g_info->side_referee)}
                                <div class="info_item">
                                    <div class="title">{$language.side_referee}:</div>
                                    <div class="text">{$game_item.g_info->side_referee}</div>
                                </div>
                            {/if}
                            {if !empty($game_item.g_info->video_referee)}
                                <div class="info_item">
                                    <div class="title">{$language.video_referee}:</div>
                                    <div class="text">{$game_item.g_info->video_referee}</div>
                                </div>
                            {/if}
                            {if !empty($game_item.g_info->actions)}
                                <div class="content_game_teems game_action_list">
                                    <ul class="team_list teem_owner team_list_half">
                                        <li class="teem_title">{$game_item.owner.title}</li>
                                        {if !empty($game_item.g_info->actions->owner->pop)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.T_title}">{$language.pop}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->owner->pop}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->owner->pez)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.R_title}">{$language.pez}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->owner->pez}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->owner->sht)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.Sh_title}">{$language.sht}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->owner->sht}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->owner->d_g)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.DG_title}">{$language.d_g}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->owner->d_g}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->owner->y_c)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.Zhk_title}">{$language.y_c}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->owner->y_c}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->owner->r_c)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.KK_title}">{$language.r_c}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->owner->r_c}
                                                </div>
                                            </li>
                                        {/if}
                                    </ul>
                                    <ul class="team_list teem_guest team_list_half">
                                        <li class="teem_title">{$game_item.guest.title}</li>
                                        {if !empty($game_item.g_info->actions->guest->pop)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.T_title}">{$language.pop}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->guest->pop}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->guest->pez)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.R_title}">{$language.pez}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->guest->pez}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->guest->sht)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.Sh_title}">{$language.sht}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->guest->sht}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->guest->d_g)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.DG_title}">{$language.d_g}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->guest->d_g}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->guest->y_c)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.Zhk_title}">{$language.y_c}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->guest->y_c}
                                                </div>
                                            </li>
                                        {/if}
                                        {if !empty($game_item.g_info->actions->guest->r_c)}
                                            <li class="team_staff_item">
                                                <div class="actions">
                                                    <strong class="show-tooltip" title="{$language.KK_title}">{$language.r_c}</strong>:
                                                </div>
                                                <div class="names">
                                                    {$game_item.g_info->actions->guest->r_c}
                                                </div>
                                            </li>
                                        {/if}
                                    </ul>
                                </div>
                            {/if}
                        </div>
                    </div>
                {/if}
                {if !empty($game_item.connected_news)}
                    <div class="title_caption title_caption_black">
                        Новости
                    </div>
                    <div class="page_content">
                        <div class="content_post border">
                            {foreach key=key item=item from=$game_item.connected_news name=news}
                                <p><a href="/news/{$item.id}">{$item.title}</a></p>
                            {/foreach}
                        </div>
                    </div>
                {/if}
            {else}
                <div class="page_content">
                    <div class="content_post border">
                        <p>Игра не найдена.</p>
                    </div>
                </div>
            {/if}
        </div>
        <div class="right_block">
            {include file="right.tpl"}
        </div>
    </div>
    <div class="content_end"></div>
</div>
{include file="footer.tpl"}
</body>