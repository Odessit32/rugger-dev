<H1>Команды:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="20%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=team">Список</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=team&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=team&get=add">Добавить</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd' or $smarty.get.get == 'country' or $smarty.get.get == 'countryedit' or $smarty.get.get == 'countryadd' or $smarty.get.get == 'city' or $smarty.get.get == 'cityedit' or $smarty.get.get == 'cityadd' or $smarty.get.get == 'stadium' or $smarty.get.get == 'stadiumedit' or $smarty.get.get == 'stadiumadd')}active{else}notactive{/if}"><a href="?show=team&get=categories">Переменные</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=team&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd')}
		{include file="team/admin_team_categories.tpl"}
	{/if}
	{if !empty($smarty.get.get) && ($smarty.get.get == 'country' or $smarty.get.get == 'countryedit' or $smarty.get.get == 'countryadd')}
		{include file="team/admin_team_country.tpl"}
	{/if}
	{if !empty($smarty.get.get) && ($smarty.get.get == 'champ' or $smarty.get.get == 'champedit' or $smarty.get.get == 'champadd')}
		{include file="team/admin_team_champ.tpl"}
	{/if}
	{if !empty($smarty.get.get) && ($smarty.get.get == 'city' or $smarty.get.get == 'cityedit' or $smarty.get.get == 'cityadd')}
		{include file="team/admin_team_city.tpl"}
	{/if}
	{if !empty($smarty.get.get) && ($smarty.get.get == 'stadium' or $smarty.get.get == 'stadiumedit' or $smarty.get.get == 'stadiumadd')}
		{include file="team/admin_team_stadium.tpl"}
	{/if}
	
	{if empty($smarty.get.get)}
		<h1>Список команд</h1>

			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="teams_table">
			<tr>
				<td align="center" colspan="6">
					<input type="text" id="team_search" placeholder="Поиск по названию..." style="width: 300px; padding: 5px; margin-bottom: 10px; font-size: 14px;" onkeyup="filterTeams()">
					<br>
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=team{if !empty($smarty.get.filter)}&filter={$smarty.get.filter}{/if}&country='+this.value">
							<option value="all"{if !empty($smarty.get.country) && $smarty.get.country == 'all'} selected{/if}>все</option>
							<option value="0"{if isset($smarty.get.country) && $smarty.get.country == 0} selected{/if}>без страны</option>

						{if !empty($team_country_list_ne)}
						{foreach key=key item=item from=$team_country_list_ne name=tcl}
							<option value="{$item.cn_id}"{if !empty($smarty.get.country) && $smarty.get.country == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
						{/foreach}
						{/if}
					</select>
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=team{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}&filter='+this.value">
							<option value="all"{if !empty($smarty.get.filter) && $smarty.get.filter == 'all'} selected{/if}>все</option>
							<option value="null"{if !empty($smarty.get.filter) && $smarty.get.filter == 'null'} selected{/if}>без фильтра</option>
						{if !empty($team_filter_list)}
						{foreach key=key item=item from=$team_filter_list name=tcl}
							{if !empty($item.filter)}<option value="{$item.filter}"{if !empty($smarty.get.filter) && $smarty.get.filter == $item.filter} selected{/if}>{$item.filter}</option>{/if}
						{/foreach}
						{/if}
					</select>
					<br><br>
				</td>
			</tr>
			{if $team_list}
			{foreach key=key item=item from=$team_list name=team}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=team&get=edit&item={$item.t_id}">{if $item.t_title_ru == ''}<span style="color: #f00;">Без названия</span>{else}{$item.t_title_ru}{/if}</a>{if $item.t_is_technical == 'yes'} (тех.){/if}</td>
					<td><a href="?show=team&get=edit&item={$item.t_id}">{$item.t_filter}</a></td>
					<td>{if !empty($item.logo)}<img src="{$item.logo}" alt="" border="0" style="max-width: 30px; max-height: 30px;">{/if}</td>
					<td>{if !empty($item.t_is_detailed) && $item.t_is_detailed == 'yes'}<img src="images/menu_yes.gif" alt="подробно" border="0">{else}<img src="images/menu_no.gif" alt="коротко" border="0">{/if}</td>
					<td>{if !empty($item.t_is_main) && $item.t_is_main == 'yes'}<img src="images/main.jpg" alt="главная" border="0">{/if}</td>
					<td>{if !empty($item.t_is_active) && $item.t_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			<br>

		<script>
		function filterTeams() {
			var input = document.getElementById('team_search');
			var filter = input.value.toLowerCase();
			var table = document.getElementById('teams_table');
			var rows = table.getElementsByTagName('tr');

			// Пропускаем первую строку (с фильтрами)
			for (var i = 1; i < rows.length; i++) {
				var row = rows[i];
				var cells = row.getElementsByTagName('td');
				if (cells.length > 0) {
					var teamName = cells[0].textContent || cells[0].innerText;
					if (teamName.toLowerCase().indexOf(filter) > -1) {
						row.style.display = '';
					} else {
						row.style.display = 'none';
					}
				}
			}
		}
		</script>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить команду</h1>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="t_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">подробно о команде: </th>
							<td width="50%" align="center"><input type="Checkbox" name="t_is_detailed"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">техническая команда: </th>
							<td width="50%" align="center"><input type="Checkbox" name="t_is_technical"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата основания: </th>
							<td width="50%" align="center">
								<select name="t_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="t_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="t_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+1}
								{section name = year start = 1850 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="t_cn_id" id="input100" onchange='getCityList(this.value, 0, 1)'>
									<option value="0">----------</option>
								{if $team_country_list}
								{foreach key=key item=item from=$team_country_list name=country}
									<option value="{$item.cn_id}">{$item.cn_title_ru}</option>
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
							{*
								<select name="t_ct_id" id="input100" onchange='getStadiumList(this.value, 0)'>
								{if $team_city_list}
								<option value="">----------</option>
								{foreach key=key item=item from=$team_city_list name=city}
									{if $item.ct_cn_id == $team_country_list.0.cn_id}<option value="{$item.ct_id}">{$item.ct_title_ru}</option>{/if}
								{/foreach}
								{else}
								нет городов
								{/if}
							*}не выбрана страна
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">стадион: </th>
							<td width="50%" align="center" id="stadium">
								{*
								<select name="t_std_id" id="input100">
								{if $team_stadium_list}
								<option value="">----------</option>
								{foreach key=key item=item from=$team_stadium_list name=stadium}
									{if $item.std_ct_id == $team_city_list.0.ct_id}<option value="{$item.std_id}">{$item.std_title_ru}</option>{/if}
								{/foreach}
								{else}
								нет стадионов
								{/if}
								</select>
								*}
								не выбран город
							</td>
						</tr>
                        <tr>
                            <th width="50%" align="center">адрес: </th>
                            <td width="50%" align="center" id="stadium">
                                <input type="text" name="t_address" id="input100">
                            </td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">для фильтра: </th>
                            <td width="50%" align="center" id="stadium">
                                <input type="text" name="t_filter" id="input100">
                            </td>
                        </tr>
    						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_team" id="submitsave" value="Добавить">
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
					<td width="80%"><input type="text" name="t_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание кратко (РУС):</b> <br><textarea name="t_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание подробно (РУС):</b> <br><textarea name="t_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="t_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание кратко (УКР):</b> <br><textarea name="t_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание подробно (УКР):</b> <br><textarea name="t_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="t_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание кратко (ENG):</b> <br><textarea name="t_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание подробно (ENG):</b> <br><textarea name="t_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && !empty($smarty.get.item) && !empty($team_item)}
		<h1>Редактирование команды: &laquo;{$team_item.t_title_ru}&raquo;</h1>
		
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false" >
				<input type="Hidden" name="t_id" value="{$team_item.t_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="t_is_active"{if $team_item.t_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">подробно о команде: </th>
							<td width="50%" align="center"><input type="Checkbox" name="t_is_detailed"{if $team_item.t_is_detailed == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">техническая команда: </th>
							<td width="50%" align="center"><input type="Checkbox" name="t_is_technical"{if $team_item.t_is_technical == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата основания: </th>
							<td width="50%" align="center">
								<select name="t_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $team_item.t_date_foundation|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="t_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $team_item.t_date_foundation|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="t_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+1}
								{section name = year start = 1850 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $team_item.t_date_foundation|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="t_cn_id" id="input100" onchange='getCityList(this.value, {$team_item.t_ct_id}, 1)'>
									<option value="0"{if $team_item.t_cn_id == 0} selected{/if}>----------</option>
								{if $team_country_list}
								{foreach key=key item=item from=$team_country_list name=categories}
									<option value="{$item.cn_id}"{if $team_item.t_cn_id == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100" onchange='getStadiumList(this.value, 0)'>
								{if $team_city_list}
								<option value="">----------</option>
								{foreach key=key item=item from=$team_city_list name=categories}
									{if $team_item.t_cn_id > 0}
										{if $item.ct_cn_id == $team_item.t_cn_id}<option value="{$item.ct_id}"{if $team_item.t_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{else}
										{if $item.ct_cn_id == $team_country_list.0.cn_id}<option value="{$item.ct_id}"{if $team_item.t_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{/if}
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">стадион: </th>
							<td width="50%" align="center" id="stadium">
								<select name="t_std_id" id="input100">
								{if $team_stadium_list}
								<option value="">----------</option>
								{foreach key=key item=item from=$team_stadium_list name=stadium}
									<option value="{$item.std_id}"{if $team_item.t_std_id == $item.std_id} selected{/if}>{$item.std_title_ru}</option>
									{* if $team_item.t_ct_id > 0}
										{if $item.std_ct_id == $team_item.t_ct_id}<option value="{$item.std_id}"{if $team_item.t_std_id == $item.std_id} selected{/if}>{$item.std_title_ru}</option>{/if}
									{else}
										{if $item.std_ct_id == $team_city_list.0.ct_id}<option value="{$item.std_id}"{if $team_item.t_std_id == $item.std_id} selected{/if}>{$item.std_title_ru}</option>{/if}
									{/if *}
								{/foreach}
								{else}
								нет стадионов
								{/if}
								</select>
							</td>
						</tr>
                        <tr>
                            <th width="50%" align="center">адрес: </th>
                            <td width="50%" align="center" id="stadium">
                                <input type="text" name="t_address" value="{$team_item.t_address}" id="input100">
                            </td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">для фильтра: </th>
                            <td width="50%" align="center" id="filter">
                                <input type="text" name="t_filter" value="{$team_item.t_filter}" id="input100">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="15"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">объединить с ID: </th>
                            <td width="50%" align="center">
                                <input type="number" name="merge_source_id" placeholder="ID дубля" id="input100">
                            </td>
                        </tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_team_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						{if $team_item.clubs == 0 and $team_item.games == 0 and $team_item.championships == 0}<input type="submit" name="delete_team" id="submitdelete" value="Удалить">{else}<b>Удаление невозможно.</b>{/if}
						<br>
						<br>
						{if $team_item.clubs>0}<p>Клубы: {$team_item.clubs}.</p>{/if}
						{if $team_item.games>0}<p>Игры: {$team_item.games}.</p>{/if}
						{if $team_item.championships>0}<p>Чемпионаты: {$team_item.championships}.</p>{/if}
						<input type="submit" name="merge_team" value="Объединить команды" onclick="return confirm('ВНИМАНИЕ! Все данные (матчи, игроки, фото и т.д.) из указанной команды будут перенесены в текущую. Старая команда будет удалена. Продолжить?');" style="background: #ff9800; color: white; border: none; padding: 5px 15px; cursor: pointer;">
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="t_title_ru" value="{$team_item.t_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание кратко (РУС):</b> <br><textarea name="t_description_ru" rows="5" style="width: 100%;">{$team_item.t_description_ru}</textarea></td></tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание подробно (РУС):</b> <br><textarea name="t_text_ru" style="width: 100%; height: 400px;">{$team_item.t_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="t_title_ua" value="{$team_item.t_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание кратко (УКР):</b> <br><textarea name="t_description_ua" rows="5" style="width: 100%;">{$team_item.t_description_ua}</textarea></td></tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание подробно (УКР):</b> <br><textarea name="t_text_ua" style="width: 100%; height: 400px;">{$team_item.t_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="t_title_en" value="{$team_item.t_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание кратко (ENG):</b> <br><textarea name="t_description_en" rows="5" style="width: 100%;">{$team_item.t_description_en}</textarea></td></tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание подробно (ENG):</b> <br><textarea name="t_text_en" style="width: 100%; height: 400px;">{$team_item.t_text_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Настройки</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>
			
			{include file="team/admin_team_gallery.tpl"}
		</div>
		{* =========== СОСТАВ ============ *}
		<div id="cont_5">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Настройки</a></td>
				</tr>
			</table>
			{include file="team/admin_team_staff.tpl"}
		</div>
		{* =========== НАСТРОЙКИ ============ *}
		<div id="cont_6">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="16%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Настройки</a></td>
				</tr>
			</table>
			<h1>Настройки</h1>
			{include file="team/admin_team_settings.tpl"}
		</div>
		{include file="admin_meta_seo.tpl" meta_seo_item_type="team" meta_seo_item_id="`$team_item.t_id`"}
		<br>
		
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>

		<center>
		<b>Вкл/откл блока команд слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $team_settings_list.is_active_team_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_team_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество команд в блоке команд слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$team_settings_list.count_team_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_team_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество команд на одну страницу в разделе команд:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$team_settings_list.count_team_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_team_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="team/admin_title_team.tpl"}
		{include file="team/admin_title_team_informer.tpl"}

		<br>
		</center>
	{/if}
	</div>