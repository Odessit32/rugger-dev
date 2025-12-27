<h1>Редактирование структуры соревнований {if $championship_item.ch_chc_id == 2}( Тур  {$competitions_item.cp_tour+1}){/if}</h1>


		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if empty($smarty.get.edit_comp)}active_left{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Соревнование</a></td>
				<td width="34%" id="{if !empty($smarty.get.edit_comp) && ($smarty.get.edit_comp == 'games_list' || $smarty.get.edit_comp == 'games_edit' || $smarty.get.edit_comp == 'games_edit')}active{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_list{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Игры соревнования</a></td>
			</tr>
		</table>

{if empty($smarty.get.edit_comp) && !empty($competitions_item)}
	<h1>Соревнование: &laquo;{$competitions_item.cp_title_ru}&raquo;</h1>
	
		<center>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="cp_id" value="{$competitions_item.cp_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cp_is_active"{if $competitions_item.cp_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">отображать в меню: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cp_is_menu"{if $competitions_item.cp_is_menu == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">отображать рейтинговую таблицу: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cp_is_rating_table"{if $competitions_item.cp_is_rating_table == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th width="50%" align="center">подэтап: </th>
							<td width="50%" align="center"><input type="text" name="cp_substage" value="{$competitions_item.cp_substage}" id="input50" maxlength="3"></td>
						</tr>
						<tr>
							<th width="50%" align="center">сортировка: </th>
							<td width="50%" align="center"><input type="text" name="cp_order" value="{$competitions_item.cp_order}" id="input50" maxlength="3"></td>
						</tr>
						{*
						<tr>
							<th width="50%" align="center">провести до: </th>
							<td width="50%" align="center">
							{if $competitions_item.games}
								перенести нельзя - в соревновании есть игры
							{else}
								<select name="cp_parent_id" id="input100">
									<option value="0">самым последним</option>
								{foreach key=key item=item from=$competitions_list}{if $competitions_item.cp_id != $item.cp_id}<option value="{$item.cp_id}"{if $competitions_item.cp_parent_id == $item.cp_id} selected{/if}>{section name = mySection start = 0 loop = $item.nesting}../{/section}{$item.cp_title_ru}</option>{/if}{/foreach}
								</select>
							{/if}
							</td>
						</tr>
						*}
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_comp_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						{if $competitions_item.games}
							удаление невозможно - <br>в соревновании есть игры
						{else}
							<input type="submit" name="delete_comp" id="submitdelete" value="Удалить">
						{/if}
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="cp_title_ru" value="{$competitions_item.cp_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="cp_description_ru" rows="5" style="width: 100%;">{$competitions_item.cp_description_ru}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="cp_text_ru" style="width: 100%; height: 400px;">{$competitions_item.cp_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="cp_title_ua" value="{$competitions_item.cp_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="cp_description_ua" rows="5" style="width: 100%;">{$competitions_item.cp_description_ua}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="cp_text_ua" style="width: 100%; height: 400px;">{$competitions_item.cp_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="cp_title_en" value="{$competitions_item.cp_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="cp_description_en" rows="5" style="width: 100%;">{$competitions_item.cp_description_en}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="cp_text_en" style="width: 100%; height: 400px;">{$competitions_item.cp_text_en}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
		
{/if}
{if !empty($smarty.get.edit_comp) && $smarty.get.edit_comp == 'games_list'}
	<h1>Игры соревнования: &laquo;{$competitions_item.cp_title_ru}&raquo;</h1>
		<center>
		{if $games_list}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_list&sort=team&sort_order={if empty($smarty.get.sort_order) || strtolower($smarty.get.sort_order)=='desc'}asc{else}desc{/if}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">команды</a></th>
					<th>счет</th>
					<th></th>
					<th><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_list&sort=date&sort_order={if empty($smarty.get.sort_order) || strtolower($smarty.get.sort_order)=='desc'}asc{else}desc{/if}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">дата</a></th>
					<th>вкл/откл</th>
					<th>отчет</th>
				</tr>
			{foreach key=key item=item from=$games_list name=games}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$item.g_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.g_owner_t_title} - {$item.g_guest_t_title}</a></td>
					<td align="center">{if $item.g_is_done == 'yes'}{$item.g_owner_points}&nbsp;-&nbsp;{$item.g_guest_points}{/if}</td>
					<td align="center">{$item.g_round} тур</td>
					<td align="center">{$item.g_date_schedule|date_format:"%d.%m.%Y"} {if $item.g_is_schedule_time == 'yes'}{$item.g_date_schedule|date_format:"%H:%M"}{else}<span style="color: #bbbbbb">{$item.g_date_schedule|date_format:"%H:%M"}</span{/if}</td>
					<td width="50" align="center">{if $item.g_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
					<td width="50" align="center">{if $item.g_is_done == 'no'}<img src="images/document_edit.jpg" alt="не сыграна" border="0">{else}<img src="images/yes.jpg" alt="сыграна" border="0">{/if}</td>
				</tr>
			{/foreach}
			</table>
		{else}
			В соревновании игр нет.<br>
		{/if}
			<br>
			{if $championship_item.ch_is_done == 'no'}<a href="?show=competitions&ch={$championship_item.ch_id}&get=addmanual&get_m=game&item_comp={$competitions_item.cp_id}&tour={$competitions_item.cp_tour}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Добавить игру</a>{/if}
			<br>
			<br>
		</center>
{/if}

{if !empty($smarty.get.edit_comp) && $smarty.get.edit_comp == 'games_edit'}
		<h1>Игра соревнования: &laquo;{$competitions_item.cp_title_ru}&raquo; {if $championship_item.ch_chc_id == 2} - Тур  {$competitions_item.cp_tour+1}{/if}</h1>
		
		<center>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="hidden" name="g_id" value="{$game_item.g_id}">
            {if $championship_item.ch_chc_id != 3}<input type="hidden" name="g_cp_id_g" value="0" />{/if}
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="g_is_active"{if $game_item.g_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">особенная игра: </th>
							<td width="50%" align="center"><input type="Checkbox" name="g_selected"{if $game_item.g_selected == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">тур: </th>
							<td width="50%" align="center"><input type="text" name="g_round" value="{$game_item.g_round}" align="center" id="input50"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Соревнование{if $championship_item.ch_chc_id == 3} (Хозяева){/if}: </th>
							<td width="50%" align="center">
							{if $game_item.g_is_done == 'no'}
								{if $competitions_list_form}
									<select name="g_cp_id" id="input100" onchange="getCompTeam({$smarty.get.ch}, this.value)">
									<option value="0">----------</option>
									{foreach key=key item=item from=$competitions_list_form}<option value="{$item.cp_id}"{if $game_item.g_cp_id == $item.cp_id} selected{/if}>{$item.title}</option>{/foreach}
									</select>
								{else}
									<span style="color: #f00;">нет соревнований</span>
								{/if}
							{else}
								{foreach key=key item=item from=$competitions_list_form}{if $game_item.g_cp_id == $item.cp_id}<b>{$item.title}</b>{/if}{/foreach}
							{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Хозяева: </th>
							<td width="50%" align="center" id="owner_t">
							{if $game_item.g_owner_t_g_staff OR $game_item.g_is_done == 'yes'}
								<b>{$game_item.g_owner_t_title_ru}</b>
							{else}
								{if $competition_team}
									<select name="g_owner_t_id" id="input100" onchange="getCompTeamGuest({$game_item.g_ch_id}, {$game_item.g_cp_id}, this.value, {if $game_item.g_guest_t_id>0}{$game_item.g_guest_t_id}{elseif $game_item.g_guest_t_comment>0}'{$game_item.g_guest_t_comment}'{else}0{/if})">
									<option value="0">----------</option>
									{foreach key=key item=item from=$competition_team}{if $game_item.g_guest_t_id !== $item.t_id and $game_item.g_guest_t_comment !== $item.t_id}<option value="{$item.t_id}"{if $game_item.g_owner_t_id == $item.t_id or $game_item.g_owner_t_comment == $item.t_id} selected{/if}>{$item.t_title_ru}</option>
									{/if}{/foreach}
									</select>
								{else}
									<span style="color: #f00;">нет команд</span>
								{/if}
							{/if}
							</td>
						</tr>
                        {if $championship_item.ch_chc_id == 3}
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Соревнование (Гости): </th>
                            <td width="50%" align="center">
                                {if $game_item.g_is_done == 'no'}
                                    {if $competitions_list_form}
                                        <select name="g_cp_id_g" id="input100" {*onchange="getCompTeam({$smarty.get.ch}, this.value)"*}>
                                            <option value="0">----------</option>
                                            {foreach key=key item=item from=$competitions_list_form}<option value="{$item.cp_id}"{if $game_item.g_cp_id_g == $item.cp_id} selected{/if}>{$item.title}</option>{/foreach}
                                        </select>
                                    {else}
                                        <span style="color: #f00;">нет соревнований</span>
                                    {/if}
                                {else}
                                    {foreach key=key item=item from=$competitions_list_form}{if $game_item.g_cp_id_g == $item.cp_id}<b>{$item.title}</b>{/if}{/foreach}
                                {/if}
                            </td>
                        </tr>
                        {/if}
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Гости: </th>
							<td width="50%" align="center" id="guest_t">
							{if $game_item.g_guest_t_g_staff OR $game_item.g_is_done == 'yes'}
								<b>{$game_item.g_guest_t_title_ru}</b>
							{else}
								{if $competition_team}
									<select name="g_guest_t_id" id="input100" onchange="getCompTeamOwner({$game_item.g_ch_id}, {$game_item.g_cp_id}, this.value, {if $game_item.g_owner_t_id>0}{$game_item.g_owner_t_id}{elseif $game_item.g_owner_t_comment>0}'{$game_item.g_owner_t_comment}'{else}0{/if})">
									<option value="0">----------</option>
									{foreach key=key item=item from=$competition_team}{if $game_item.g_owner_t_id !== $item.t_id and $game_item.g_owner_t_comment !== $item.t_id}<option value="{$item.t_id}"{if $game_item.g_guest_t_id == $item.t_id or $game_item.g_guest_t_comment == $item.t_id} selected{/if}>{$item.t_title_ru}</option>{/if}{/foreach}
									</select>
								{else}
									<span style="color: #f00;">нет команд</span>
								{/if}
							{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center">
								<select name="g_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $game_item.g_date_schedule|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="g_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $game_item.g_date_schedule|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="g_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $game_item.g_date_schedule|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">время: </th>
							<td width="50%" align="center">
								<select name="g_date_hour">
								{section name = day start = 0 loop = 24}
									<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $game_item.g_date_schedule|date_format:"%H"} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="g_date_minute">
								{section name = month start = 0 loop = 60}
									<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $game_item.g_date_schedule|date_format:"%M"} selected{/if}>{if $smarty.section.month.index <10}0{/if}{$smarty.section.month.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">часовой пояс: </th>
							<td width="50%" align="center">UTC
								<select name="g_date_time_zone">
								{section name = tzone start = 27 loop = 27 step=-1}
                                    {assign var="tz_title" value=$smarty.section.tzone.index-12}
                                    {if $tz_title == -4}
                                        <option value="-4.5"{if -4.5 == $game_item.g_date_time_zone} selected{/if}>-4:30</option>
                                    {/if}
                                    {if $tz_title == -3}
                                        <option value="-3.5"{if -3.5 == $game_item.g_date_time_zone} selected{/if}>-3:30</option>
                                    {/if}
                                    {if $tz_title == 4}
                                        <option value="4.5"{if 4.5 == $game_item.g_date_time_zone} selected{/if}>+4:30</option>
                                    {/if}
                                    {if $tz_title == 3}
                                        <option value="3.5"{if 3.5 == $game_item.g_date_time_zone} selected{/if}>+3:30</option>
                                    {/if}
                                    {if $tz_title == 5}
                                        <option value="5.5"{if 5.5 == $game_item.g_date_time_zone} selected{/if}>+5:30</option>
                                        <option value="5.75"{if 5.75 == $game_item.g_date_time_zone} selected{/if}>+5:45</option>
                                    {/if}
                                    {if $tz_title == 6}
                                        <option value="6.5"{if 6.5 == $game_item.g_date_time_zone} selected{/if}>+6:30</option>
                                    {/if}
                                    {if $tz_title == 8}
                                        <option value="8.75"{if 8.75 == $game_item.g_date_time_zone} selected{/if}>+8:45</option>
                                    {/if}
                                    {if $tz_title == 9}
                                        <option value="9.5"{if 9.5 == $game_item.g_date_time_zone} selected{/if}>+9:30</option>
                                    {/if}
                                    {if $tz_title == 10}
                                        <option value="10.5"{if 10.5 == $game_item.g_date_time_zone} selected{/if}>+10:30</option>
                                    {/if}
                                    {if $tz_title == 11}
                                        <option value="11.5"{if 11.5 == $game_item.g_date_time_zone} selected{/if}>+11:30</option>
                                    {/if}
                                    {if $tz_title == 12}
                                        <option value="12.75"{if 12.75 == $game_item.g_date_time_zone} selected{/if}>+12:45</option>
                                    {/if}
                                    {if $tz_title == 13}
                                        <option value="13.75"{if 13.75 == $game_item.g_date_time_zone} selected{/if}>+13:45</option>
                                    {/if}
									<option value="{$tz_title}"{if $tz_title == $game_item.g_date_time_zone} selected{/if}>{if $tz_title>0}+{/if}{$tz_title}</option>

								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать время: </th>
							<td width="50%" align="center"> <input type="Checkbox" name="g_is_schedule_time"{if $game_item.g_is_schedule_time == 'yes'} checked{/if}> </td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать стадион: </th>
							<td width="50%" align="center"> <input type="Checkbox" name="g_is_stadium"{if $game_item.g_is_stadium == 'yes'} checked{/if}> </td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="t_cn_id" id="input100" onchange='getCityList(this.value, {$game_item.g_ct_id}, 1)'>
									<option value="0"{if $item.cn_id == 0} selected{/if}>----------</option>
								{if $country_list}
								{foreach key=key item=item from=$country_list name=country}
									<option value="{$item.cn_id}"{if $game_item.g_cn_id == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100" onchange='getStadiumList(this.value, 0)'>
								
								<option value="">----------</option>
								{if $city_list}
								{foreach key=key item=item from=$city_list name=city}
									{if $game_item.g_cn_id > 0}
										{if $item.ct_cn_id == $game_item.g_cn_id}<option value="{$item.ct_id}"{if $game_item.g_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{/if}
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">Стадион: </th>
							<td width="50%" align="center" id="stadium">
								<select name="t_std_id" id="input100">
								{if $stadium_list}
								<option value="">----------</option>
								{foreach key=key item=item from=$stadium_list name=stadium}
									{if $game_item.g_ct_id > 0}
										{if $item.std_ct_id == $game_item.g_ct_id}<option value="{$item.std_id}"{if $game_item.g_std_id == $item.std_id} selected{/if}>{$item.std_title_ru}</option>{/if}
									{else}
										{if $item.std_ct_id == $city_list.0.ct_id}<option value="{$item.std_id}"{if $game_item.g_std_id == $item.std_id} selected{/if}>{$item.std_title_ru}</option>{/if}
									{/if}
								{/foreach}
								{else}
								нет стадионов
								{/if}
								</select>
							</td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						{if $game_item.g_owner_t_id > 0 and $game_item.g_guest_t_id > 0}<a href="?show=games&get=edit&item={$game_item.g_id}{if $smarty.get.country>0}&country={$smarty.get.country}{/if}"><div style="display: block; border: 1px solid #444444; width: 150px; height: 20px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;">{if $game_item.g_is_done == 'no'}<img src="images/document_edit.jpg" alt="отчета нет" border="0"> Написать отчет{else}<img src="images/yes.jpg" alt="отчета нет" border="0"> Посмотреть отчет{/if}</div></a>{else}Написание отчета невозможно <br>пока не определены команды.<br><br>{/if}
						<input type="submit" name="save_game_changes" id="submitsave" value="Сохранить">
						{if $game_item.g_is_done == 'no'}
						<br><br><br>
						<input type="submit" name="delete_game" id="submitdelete" value="Удалить">
						{/if}
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Состав хозяева</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав гости</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="g_description_ru" rows="5" style="width: 100%;">{$game_item.g_description_ru}</textarea></td></tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="g_text_ru" style="width: 100%; height: 400px;">{$game_item.g_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Состав хозяева</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав гости</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="g_description_ua" rows="5" style="width: 100%;">{$game_item.g_description_ua}</textarea></td></tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="g_text_ua" style="width: 100%; height: 400px;">{$game_item.g_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="20%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Состав хозяева</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав гости</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="g_description_en" rows="5" style="width: 100%;">{$game_item.g_description_en}</textarea></td></tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="g_text_en" style="width: 100%; height: 400px;">{$game_item.g_text_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		{* =========== СОСТАВ ХОЗЯЕВА ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Состав хозяева</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав гости</a></td>
				</tr>
			</table>
			{include file="competitions/admin_competitions_owner_t_staff.tpl"}
		</div>
		{* =========== СОСТАВ ГОСТИ ============ *}
		<div id="cont_5">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Состав хозяева</a></td>
					<td width="20%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав гости</a></td>
				</tr>
			</table>
			{include file="competitions/admin_competitions_guest_t_staff.tpl"}
		</div>
		<br>
		</center>
		
{/if}

		