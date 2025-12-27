<div class="team_item">
    <div class="left_rubrika">{$team_item.title}</div>
    {include file="social.tpl"}
    <div class="breadcrumbs">
        <a href="{$sitepath}">{$conf_vars.main}</a> <b>/</b>
        {if $pages}
            {foreach key=key item=item from=$pages name="b_m"}
                <a href="{$sitepath}{$item.page_path}{$item.p_adress}">{$item.title}</a> <b>/</b>
            {/foreach}
        {/if}
        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}">{$club_item.title}</a> <b>/</b>
        {$team_item.title}
    </div>
    <ul class="menu">
        {if $team_item.text!=''}<li id="m_1" onclick="javascript:showBlock('1', '4')" class="active">История</li>{/if}
        {if $team_item.staff.player}<li id="m_2" onclick="javascript:showBlock('2', '4')">Команда</li>{/if}
        {if $team_item.staff.head}<li id="m_3" onclick="javascript:showBlock('3', '4')">Руководство</li>{/if}
        {if $team_item.staff.rest}<li id="m_4" onclick="javascript:showBlock('4', '4')">Тренерский штаб</li>{/if}
    </ul>
    <div id="more_text">
    {if $team_item.text!=''}
        <div id="cont_1">
            {if $team_item.title != ''}<h1>{$team_item.title}</h1>{/if}
            {if $team_item.photo_main}<div class="main_photo"><a href="{$imagepath}upload/photos{$team_item.photo_main.ph_big}" onclick="return hs.expand(this)" title="{$team_item.title}: {$team_item.family} {$team_item.name} {$team_item.surname}" ><img src="{$imagepath}upload/photos{$team_item.photo_main.ph_folder}{$team_item.photo_main.ph_med}" border="0" alt="{$conf_vars.title} :: {$page_item.title} :: {$team_item.family} {$team_item.name} {$team_item.surname}" /></a></div>{/if}
            {$team_item.text}
        </div>
    {/if}
    {if $team_item.staff.player}
        <div id="cont_2" style="display: none">
            <h2>Состав:</h2>
            {if $team_item.staff}
                <span id="t_s">
								<table border="0" cellspacing="0" cellpadding="0" width="100%">
                                    <tr>
                                        <th>Имя</th>
                                        <th>Позиция</th>
                                        <th>ДР</th>
                                    </tr>
                                    {foreach key=key item=item from=$team_item.staff name=staff}
                                        {if $item.player}
                                            <tr>
                                                <td><b>{$item.name} {*$item.surname*} {$item.family}</b></td>
                                                <td>{$item.app}</td>
                                                <td>{if $item.st_is_show_birth == 'yes'}{$item.st_date_birth|date_format:"%d.%m.%y"}г.{/if}</td>
                                            </tr>
                                        {/if}
                                    {/foreach}
                                </table>
								</span>
            {/if}
        </div>
    {/if}
    {if $team_item.staff.head}
        <div id="cont_3" style="display: none">
            <h2>Руководство:</h2>
            {if $team_item.staff}
                {foreach key=key item=item from=$team_item.staff name=staff}
                    {if $item.head}
                        <div class="staff_item">
                            {if $item.photo_main}<div class="photo"><a href="{$imagepath}upload/photos{$item.photo_main.ph_big}" onclick="return hs.expand(this)" title="{$item.app}: {$item.family} {$item.name} {$item.surname}" ><img src="{$imagepath}upload/photos{$item.photo_main.ph_med}" border="0" alt="{$conf_vars.title} :: {$page_item.title} :: {$item.app}" /></a></div>{/if}
                            <div class="fio">{$item.family} {$item.name} {$item.surname}</div>
                            <p><b>{$item.app}</b></p>
                            {$item.description}
                        </div>
                    {/if}
                {/foreach}
            {/if}
        </div>
    {/if}
    {if $team_item.staff.rest}
        <div id="cont_4" style="display: none">
            <h2>Другой персонал:</h2>
            {if $team_item.staff}
                {foreach key=key item=item from=$team_item.staff name=staff}
                    {if $item.rest}
                        <div class="staff_item">
                            {if $item.photo_main}<div class="photo"><a href="{$imagepath}upload/photos{$item.photo_main.ph_big}" onclick="return hs.expand(this)" title="{$item.app}: {$item.family} {$item.name} {$item.surname}" ><img src="{$imagepath}upload/photos{$item.photo_main.ph_med}" border="0" alt="{$conf_vars.title} :: {$page_item.title} :: {$item.app}" /></a></div>{/if}
                            <div class="fio">{$item.family} {$item.name} {$item.surname}</div>
                            <p><b>{$item.app}</b></p>
                            {$item.description}
                        </div>
                    {/if}
                {/foreach}
            {/if}
        </div>
    {/if}
    </div>
</div>