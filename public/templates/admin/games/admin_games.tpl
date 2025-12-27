<H1>Игры:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=games{if !empty($smarty.get.ch)}&ch={$smarty.get.ch}{/if}">Список</a></td>
				<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=games&get=edit&item={$smarty.get.item}">Отчет</a>{else}Отчет{/if}</td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=games&get=settings">Настройки</a></td>
			</tr>
		</table>
	
	{if empty($smarty.get.get)}
		<h1>Список</h1>
		<center>
			<select class="input" style="width: 200px;" onchange="document.location.href='?show=games&ch='+this.value">
					<option value="0"{if empty($smarty.get.ch)} selected{/if}>все</option>
				{if !empty($championship_list)}
				{foreach key=key item=item from=$championship_list name=nc}
					<option value="{$item.ch_id}"{if !empty($smarty.get.ch) && $smarty.get.ch == $item.ch_id} selected{/if}>{$item.chg_title}. {$item.title}</option>
				{/foreach}
				{/if}
			</select>
			<br><br>
		{if $games_list}
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$games_list name=games}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=games&get=edit&item={$item.g_id}{if !empty($smarty.get.ch)}&ch={$smarty.get.ch}{/if}">{$item.g_owner_t_title} - {$item.g_guest_t_title}</a></td>
					<td align="center">{if $item.g_is_done == 'yes'}<nobr>{$item.g_owner_points}&nbsp;-&nbsp;{$item.g_guest_points}</nobr>{/if}</td>
					<td><a href="?show=championship&get=edit&item={$item.ch_id}">{$item.chg_title_ru}. {$item.ch_title_ru}</a></td>
					<td><a href="?show=competitions&ch={$item.ch_id}&get=edit{if !empty($item.cp_id)}&item={$item.cp_id}{/if}&tour={$item.cp_tour}">{if $item.ch_chc_id == 2}тур {$item.cp_tour+1}/{/if}{$item.cp_title_ru}</a></td>
					<td>{if $item.g_date_schedule != '0000-00-00 00:00:00'}{$item.g_date_schedule|date_format:"%d.%m.%Y"}г.{/if}</td>
					<td>{if !empty($item.g_is_schedule_time) && $item.g_is_schedule_time == 'yes'}{$item.g_date_schedule|date_format:"%H:%M"}{/if}</td>
					<td width="30">{if $item.g_is_done == 'yes'}<img src="images/yes.jpg" alt="отчет добавлен" border="0">{else}<img src="images/document_edit.jpg" alt="отчета нет" border="0">{/if}</td>
					<td width="30">{if !empty($item.g_is_active) && $item.g_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			</table>
			{if $games_pages|@count >1}
				<div class="pages"><i>Страницы: </i>
					{foreach key=key item=item from=$games_pages}
						{if !empty($smarty.get.page) && $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=games&page={$key}{if !empty($smarty.get.ch)}&ch={$smarty.get.ch}{/if}">{$item}</a>{/if}
					{/foreach}
				</div>
			{/if}
		{/if}
			<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && !empty($smarty.get.item)}
	{if !empty($games_item.g_owner_t_id) && !empty($games_item.g_guest_t_id)}
		<h1>Отчет игры: &laquo; {$games_item.g_owner_t_title} - {$games_item.g_guest_t_title} &raquo;</h1>
		<div class="view_page"><a href="{$sitepath}game/{$games_item.g_id}" target="_blank">Посмотреть страницу угры</a></div>
		<center>
			{*<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">*}
				{*<input type="Hidden" name="g_id" value="{$games_item.g_id}">*}
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><b>{if $games_item.g_is_active == 'yes'}вкл{else}откл{/if}</b>{*<input type="Checkbox" name="g_is_active"{if $games_item.g_is_active == 'yes'} checked{/if}>*}</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center">
							<b>{$games_item.g_date_schedule|date_format:"%d.%m.%Y"} </b>
							{*
								<select name="g_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $games_item.g_date_schedule|date_format:"%d" == $smarty.section.day.index} selected{/if}>{if $smarty.section.day.index <10}0{/if}{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="g_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $games_item.g_date_schedule|date_format:"%m" == $smarty.section.month.index} selected{/if}>{if $smarty.section.month.index <10}0{/if}{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="g_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $games_item.g_date_schedule|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							*}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">время: </th>
							<td width="50%" align="center">
							<b>{$games_item.g_date_schedule|date_format:"%H:%M"}</b>
							{*
								<select name="g_date_hour">
								{section name = hour start = 1 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $smarty.section.hour.index == $games_item.g_date_schedule|date_format:"%H"} selected{/if}>{if $smarty.section.hour.index <10}0{/if}{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="g_date_minute">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $smarty.section.minute.index == $games_item.g_date_schedule|date_format:"%M"} selected{/if}>{if $smarty.section.minute.index <10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select>
							*}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать время: </th>
							<td width="50%" align="center"> <b>{if $games_item.g_is_schedule_time == 'yes'}показывать{else}не показывать{/if}</b>{*<input type="Checkbox" name="g_is_schedule_time"{if $games_item.g_is_schedule_time == 'yes'} checked{/if}>*} </td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<a href="?show=competitions&ch={$games_item.g_ch_id}&get=edit&item={$games_item.g_cp_id}&edit_comp=games_edit&g_item={$games_item.g_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}"><div style="display: block; border: 1px solid #444444; width: 150px; height: 20px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;"><img src="images/edit.jpg" alt="редактировать" border="0"> Редактировать</div></a>
						<br>
						<a href="?show=competitions&ch={$games_item.g_ch_id}&get=edit&item={$games_item.g_cp_id}&edit_comp=games_list{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}"><b>Игры соревнования >>></b></a>
						{*
						<input type="submit" name="save_games_changes" id="submitsave" value="Сохранить">
						{if $games_item.g_is_done == 'no'}
						<input type="submit" name="delete_game" id="submitdelete" value="Удалить">
						{/if}
						*}
						
					</td>
				</tr>
			</table>
			{*</form>*}
			<br>
		{* =========== ОТЧЕТ ПОДРОБНО ============ *}
		<div id="cont_1">
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="16%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Отчет подробно</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">Отчет коротко</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">Галерея</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Live</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Информация</a></td>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Отчет-текст</a></td>
				</tr>
			</table>
			<h1>Отчет подробно</h1>
			
			{if (!empty($smarty.get.team) && $smarty.get.team == 'owner') || empty($smarty.get.team)}
				{include file="games/admin_games_report_owner.tpl"}
			{/if}
			{if !empty($smarty.get.team) && $smarty.get.team == 'guest'}
				{include file="games/admin_games_report_guest.tpl"}
			{/if}
			{if $games_item.g_is_done == 'no' and ($games_item.g_owner_points > 0 OR $games_item.g_guest_points > 0)}
			<br><br><br><br><br>
			<form method="post" onsubmit="if (!confirm('Внимание: Редактирование отчета будет не возможно! Вы уверены?')) return false">
				<input type="Hidden" name="g_id" value="{$games_item.g_id}">
                <label><input name="re_count_points" type="checkbox"> пересчитать очки для подробного отчета </label>
				<input type="submit" style="background: white url('images/save.gif') no-repeat 10px; display: block; border: 1px solid #444444; width: 400px; height: 30px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;" value="Закончить и сохранить отчет + закрыть редактирование" name="save_report">
			</form>
			{/if}
			{if $games_item.g_is_done == 'yes'}
				<br><br>
				
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="24%">Доп. очки(+) / Штраф(-):</td>
						<th width="25%" align="center">{$games_item.g_owner_extra_points}</th>
						<td width="1%"></td>
						<th width="25%" align="center">{$games_item.g_guest_extra_points}</th>
						<td width="25%" align="center"></td>
					</tr>
                    <tr>
                        <td width="24%">Длительность первого тайма:</td>
                        <th width="25%" align="center">{$games_item.g_ft_time}</th>
                        <td width="1%"></td>
                        <th width="25%" align="center"></th>
                        <td width="25%" align="center"></td>
                    </tr>
				</table>
				
				<br><br>
				<p>Отчет закончен <b>{$games_item.g_done_datetime}</b>.</p><br><br><br><br><br>
				<form method="post" onsubmit="if (!confirm('Внимание: данные о матче не будут учавствовать в рейтинге! Вы уверены?')) return false">
					<input type="Hidden" name="g_id" value="{$games_item.g_id}">
					<input type="submit" style="background: white url(images/return_save.gif) no-repeat 10px; display: block; border: 1px solid #444444; width: 300px; height: 30px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;" value="Вернуть редактирование отчета" name="return_report">
				</form>
			{else}
				<br />
				<br />
				<form method="post" action="?show=games&get=edit&item={$smarty.get.item}&cont=2" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="g_id" value="{$games_item.g_id}" />
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="24%">Доп. очки(+) / Штраф(-):</td>
						<td width="25%" align="center"><input type="text" name="g_owner_extra_points" value="{$games_item.g_owner_extra_points}" class="input50" style="text-align: center;" /></td>
						<td width="1%"></td>
						<td width="25%" align="center"><input type="text" name="g_guest_extra_points" value="{$games_item.g_guest_extra_points}" class="input50" style="text-align: center;" /></td>
						<td width="25%" align="center"><input type="submit" name="save_games_report_extra" id="submitsave" value="Сохранить" /></td>
					</tr>
                    <tr>
                        <td width="24%">Длительность первого тайма:</td>
                        <th width="25%" align="center"><input type="text" name="g_ft_time" value="{$games_item.g_ft_time}" class="input50" style="text-align: center;" /></th>
                        <td width="1%"></td>
                        <th width="25%" align="center"></th>
                        <td width="25%" align="center"></td>
                    </tr>
				</table>
				</form>
			{/if}
		</div>
		{* =========== ОТЧЕТ КОРОТКО ============ *}
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Отчет подробно</a></td>
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">Отчет коротко</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">Галерея</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Live</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Информация</a></td>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Отчет-текст</a></td>
				</tr>
			</table>
			<h1>Отчет коротко (счет)</h1>
			{if $games_item.g_is_done == 'no'}
			<form method="post" action="?show=games&get=edit&item={$smarty.get.item}&cont=2" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="g_id" value="{$games_item.g_id}">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				{if !empty($games_item.g_owner_points) || !empty($games_item.g_guest_points)}
				<tr>
					<td colspan="5" align="center"><b style="color: #ff0000;">Изменение очков команд приведет к удалению подробного отчета игры команды!<br><br></b></td>
				</tr>
				{/if}
				<tr>
					<th width="24%" align="center"></th>
					<th width="25%" align="center">{$games_item.g_owner_t_title}</th>
					<td width="1%"></td>
					<th width="25%" align="center">{$games_item.g_guest_t_title}</th>
					<td width="25%" align="center" rowspan="2"><input type="submit" name="save_games_report_short" id="submitsave" value="Сохранить"></td>
				</tr>
				<tr>
					<td width="24%">Очки:</td>
					<td width="25%" align="center"><input type="text" name="g_owner_points" value="{$games_item.g_owner_points}" class="input50" style="text-align: center;"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_guest_points" value="{$games_item.g_guest_points}" class="input50" style="text-align: center;"></td>
				</tr>
				<tr>
					<td width="24%">Очки в первом тайме:</td>
					<td width="25%" align="center"><input type="text" name="g_owner_ft_points" value="{$games_item.g_owner_ft_points}" class="input50" style="text-align: center;"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_guest_ft_points" value="{$games_item.g_guest_ft_points}" class="input50" style="text-align: center;"></td>
				</tr>
				{*
				<tr>
					<td width="24%">Бонус (4 попытки):</td>
					<td width="25%" align="center"><input onclick="this.form.g_guest_bonus_1.checked=false;" type="checkbox" name="g_owner_bonus_1" class="input"{if $games_item.g_owner_bonus_1 == 'yes'} checked{/if}></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input onclick="this.form.g_owner_bonus_1.checked=false;" type="checkbox" name="g_guest_bonus_1" class="input"{if $games_item.g_guest_bonus_1 == 'yes'} checked{/if}></td>
				</tr>
				*}
				{* === БОНУС 1 === *}
				<tr>
					<td width="24%">Бонус (4 попытки):</td>
					<td width="25%" align="center"><input type="checkbox" name="g_owner_bonus_1" class="input"{if $games_item.g_owner_bonus_1 == 'yes'} checked{/if}></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="checkbox" name="g_guest_bonus_1" class="input"{if $games_item.g_guest_bonus_1 == 'yes'} checked{/if}></td>
				</tr>
				<tr>
					<td width="24%">Техническая победа:</td>
					<td width="25%" align="center"><input onclick="this.form.g_guest_tehwin.checked=false;" type="checkbox" name="g_owner_tehwin" value="{$games_item.g_owner_t_id}" class="input"{if $games_item.g_owner_tehwin == 'yes'} checked{/if}></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input onclick="this.form.g_owner_tehwin.checked=false;" type="checkbox" name="g_guest_tehwin" value="{$games_item.g_guest_t_id}" class="input"{if $games_item.g_guest_tehwin == 'yes'} checked{/if}></td>
				</tr>
				<tr>
					<th width="100%" colspan="4" height="35"><b>Результативные действия:</b></th>
				</tr>
				<tr>
					<td width="24%">Попытки:</td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][owner][pop]" value="{if !empty($games_item.g_info.actions.owner.pop)}{$games_item.g_info.actions.owner.pop}{/if}" class="input50 center"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][guest][pop]" value="{if !empty($games_item.g_info.actions.guest.pop)}{$games_item.g_info.actions.guest.pop}{/if}" class="input50 center"></td>
				</tr>
				<tr>
					<td width="24%">Реализации:</td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][owner][pez]" value="{if !empty($games_item.g_info.actions.owner.pez)}{$games_item.g_info.actions.owner.pez}{/if}" class="input50 center"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][guest][pez]" value="{if !empty($games_item.g_info.actions.guest.pez)}{$games_item.g_info.actions.guest.pez}{/if}" class="input50 center"></td>
				</tr>
				<tr>
					<td width="24%">Штрафные:</td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][owner][sht]" value="{if !empty($games_item.g_info.actions.owner.sht)}{$games_item.g_info.actions.owner.sht}{/if}" class="input50 center"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][guest][sht]" value="{if !empty($games_item.g_info.actions.guest.sht)}{$games_item.g_info.actions.guest.sht}{/if}" class="input50 center"></td>
				</tr>
				<tr>
					<td width="24%">Дроп голы:</td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][owner][d_g]" value="{if !empty($games_item.g_info.actions.owner.d_g)}{$games_item.g_info.actions.owner.d_g}{/if}" class="input50 center"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][guest][d_g]" value="{if !empty($games_item.g_info.actions.guest.d_g)}{$games_item.g_info.actions.guest.d_g}{/if}" class="input50 center"></td>
				</tr>
				<tr>
					<td width="24%">Желтые карточки:</td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][owner][y_c]" value="{if !empty($games_item.g_info.actions.owner.y_c)}{$games_item.g_info.actions.owner.y_c}{/if}" class="input50 center"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][guest][y_c]" value="{if !empty($games_item.g_info.actions.guest.y_c)}{$games_item.g_info.actions.guest.y_c}{/if}" class="input50 center"></td>
				</tr>
				<tr>
					<td width="24%">Красные карточки:</td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][owner][r_c]" value="{if !empty($games_item.g_info.actions.owner.r_c)}{$games_item.g_info.actions.owner.r_c}{/if}" class="input50 center"></td>
					<td width="1%"></td>
					<td width="25%" align="center"><input type="text" name="g_info[actions][guest][r_c]" value="{if !empty($games_item.g_info.actions.guest.r_c)}{$games_item.g_info.actions.guest.r_c}{/if}" class="input50 center"></td>
				</tr>

			</table>
			</form>
				{if  $games_item.g_owner_points > 0 OR $games_item.g_guest_points > 0}
				<br><br><br><br><br><br>
				<form method="post" onsubmit="if (!confirm('Внимание: Редактирование отчета будет не возможно! Вы уверены?')) return false">
					<input type="Hidden" name="g_id" value="{$games_item.g_id}">
                    <label><input name="re_count_points" type="checkbox"> пересчитать очки для подробного отчета </label>
					<input type="submit" style="background: white url(images/save.gif) no-repeat 10px; display: block; border: 1px solid #444444; width: 400px; height: 30px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;" value="Закончить и сохранить отчет + закрыть редактирование" name="save_report">
				</form>
				{/if}
			{/if}
			{if $games_item.g_is_done == 'yes'}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="24%" align="center"></th>
					<th width="25%" align="center">{$games_item.g_owner_t_title}</th>
					<td width="1%"></td>
					<th width="25%" align="center">{$games_item.g_guest_t_title}</th>
					<td width="25%" align="center" rowspan="2"></td>
				</tr>
				<tr>
					<td width="24%">Очки:</td>
					<td width="25%" align="center"><b>{$games_item.g_owner_points}</b></td>
					<td width="1%"></td>
					<td width="25%" align="center"><b>{$games_item.g_guest_points}</b></td>
				</tr>
                <tr>
                    <td width="24%">Очки в первом тайме:</td>
                    <td width="25%" align="center"><b>{$games_item.g_owner_ft_points}</b></td>
                    <td width="1%"></td>
                    <td width="25%" align="center"><b>{$games_item.g_guest_ft_points}</b></td>
                </tr>
				{* === БОНУС 1 === *}
				<tr>
					<td width="24%">Бонус (4 попытки):</td>
					<td width="25%" align="center">{if $games_item.g_owner_bonus_1 == 'yes'}<b>да</b>{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if $games_item.g_guest_bonus_1 == 'yes'}<b>да</b>{/if}</td>
				</tr>
				<tr>
					<td width="24%">Техническая победа:</td>
					<td width="25%" align="center">{if $games_item.g_owner_tehwin == 'yes'}<b>да</b>{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if $games_item.g_guest_tehwin == 'yes'}<b>да</b>{/if}</td>
				</tr>
				<tr>
					<td width="100%" colspan="4"><b>Результативные действия:</b></td>
				</tr>
				<tr>
					<td width="24%">Попытки:</td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.owner.pop)}{$games_item.g_info.actions.owner.pop}{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.guest.pop)}{$games_item.g_info.actions.guest.pop}{/if}</td>
				</tr>
				<tr>
					<td width="24%">Реализации:</td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.owner.pez)}{$games_item.g_info.actions.owner.pez}{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.guest.pez)}{$games_item.g_info.actions.guest.pez}{/if}</td>
				</tr>
				<tr>
					<td width="24%">Штрафные:</td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.owner.sht)}{$games_item.g_info.actions.owner.sht}{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.guest.sht)}{$games_item.g_info.actions.guest.sht}{/if}</td>
				</tr>
				<tr>
					<td width="24%">Дроп голы:</td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.owner.d_g)}{$games_item.g_info.actions.owner.d_g}{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.guest.d_g)}{$games_item.g_info.actions.guest.d_g}{/if}</td>
				</tr>
				<tr>
					<td width="24%">Желтые карточки:</td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.owner.y_c)}{$games_item.g_info.actions.owner.y_c}{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.guest.y_c)}{$games_item.g_info.actions.guest.y_c}{/if}</td>
				</tr>
				<tr>
					<td width="24%">Красные карточки:</td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.owner.r_c)}{$games_item.g_info.actions.owner.r_c}{/if}</td>
					<td width="1%"></td>
					<td width="25%" align="center">{if !empty($games_item.g_info.actions.guest.r_c)}{$games_item.g_info.actions.guest.r_c}{/if}</td>
				</tr>
			</table>
			{/if}
			
			{if $games_item.g_is_done == 'yes'}
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="24%">Доп. очки(+) / Штраф(-):</td>
						<th width="25%" align="center">{$games_item.g_owner_extra_points}</th>
						<td width="1%"></td>
						<th width="25%" align="center">{$games_item.g_guest_extra_points}</th>
						<td width="25%" align="center"></td>
					</tr>
                    <tr>
                        <td width="24%">Длительность первого тайма:</td>
                        <th width="25%" align="center">{$games_item.g_ft_time}</th>
                        <td width="1%"></td>
                        <th width="25%" align="center"></th>
                        <td width="25%" align="center"></td>
                    </tr>
				</table>
				
				<br><br>
				<p>Отчет закончен <b>{$games_item.g_done_datetime}</b>.</p><br><br><br><br><br>
				<form method="post" onsubmit="if (!confirm('Внимание: данные о матче не будут учавствовать в рейтинге! Вы уверены?')) return false">
					<input type="Hidden" name="g_id" value="{$games_item.g_id}">
					<input type="submit" style="background: white url(images/return_save.gif) no-repeat 10px; display: block; border: 1px solid #444444; width: 300px; height: 30px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;" value="Вернуть редактирование отчета" name="return_report">
				</form>
			{else}
				<br />
				<br />
				<form method="post" action="?show=games&get=edit&item={$smarty.get.item}&cont=2" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="g_id" value="{$games_item.g_id}" />
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="24%">Доп. очки(+) / Штраф(-):</td>
						<td width="25%" align="center"><input type="text" name="g_owner_extra_points" value="{$games_item.g_owner_extra_points}" class="input50" style="text-align: center;" /></td>
						<td width="1%"></td>
						<td width="25%" align="center"><input type="text" name="g_guest_extra_points" value="{$games_item.g_guest_extra_points}" class="input50" style="text-align: center;" /></td>
						<td width="25%" align="center"><input type="submit" name="save_games_report_extra" id="submitsave" value="Сохранить" /></td>
					</tr>
                    <tr>
                        <td width="24%">Длительность первого тайма:</td>
                        <th width="25%" align="center"><input type="text" name="g_ft_time" value="{$games_item.g_ft_time}" class="input50" style="text-align: center;" /></th>
                        <td width="1%"></td>
                        <th width="25%" align="center"></th>
                        <td width="25%" align="center"></td>
                    </tr>
				</table>
				</form>
			{/if}
		</div>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Отчет подробно</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">Отчет коротко</a></td>
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">Галерея</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Live</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Информация</a></td>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Отчет-текст</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>
			
			{include file="games/admin_games_gallery.tpl"}
		</div>
        {* =========== LIVE ============ *}
        <div id="cont_4">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
                <tr>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Отчет подробно</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">Отчет коротко</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">Галерея</a></td>
                    <td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Live</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Информация</a></td>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Отчет-текст</a></td>
                </tr>
            </table>
            <h1>Live</h1>

            {include file="games/admin_games_live.tpl"}
        </div>
        {* =========== INFO ============ *}
        <div id="cont_5">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
                <tr>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Отчет подробно</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">Отчет коротко</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">Галерея</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Live</a></td>
                    <td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Информация</a></td>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Отчет-текст</a></td>
                </tr>
            </table>
            <h1>Информация</h1>

            {include file="games/admin_games_info.tpl"}
        </div>
        {* =========== CUSTOM REPORT ============ *}
        <div id="cont_6">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
                <tr>
                    <td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Отчет подробно</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">Отчет коротко</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">Галерея</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Live</a></td>
                    <td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Информация</a></td>
                    <td width="16%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Отчет-текст</a></td>
                </tr>
            </table>
            <h1>Отчет-текст</h1>

            {include file="games/admin_games_custom_report.tpl"}
        </div>
        {include file="admin_meta_seo.tpl" meta_seo_item_type="game" meta_seo_item_id="`$games_item.g_id`"}
		<br>
		</center>
	{else}
		<center>
			<h1>Не определены команды в игре!</h1>
			<br><br><br>
			<a href="?show=competitions&ch={$games_item.g_ch_id}&get=edit&item={$games_item.g_cp_id}&edit_comp=games_edit&g_item={$games_item.g_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}"><div style="display: block; border: 1px solid #444444; width: 150px; height: 20px; margin: -50px 0 20px 0; padding: 3px; color: #000; font-weight: bold;"><img src="images/edit.jpg" alt="редактировать" border="0"> Редактировать</div></a>
		</center>
	{/if}
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>
		
		<center>
		<b>Вкл/откл блока игр справа на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $games_settings_list.is_active_games_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_games_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество игр в блоке игр справа на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" class="input100" value="{$games_settings_list.count_games_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_games_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество игр в списке на одну страницу в разделе игр:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" class="input100" value="{$games_settings_list.count_games_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_games_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="games/admin_title_games.tpl"}
		{include file="games/admin_title_games_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>