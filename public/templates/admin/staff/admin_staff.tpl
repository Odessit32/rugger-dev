<H1>Люди:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="25%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=staff">Список</a></td>
				<td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=staff&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=staff&get=add">Добавить</a></td>
				<td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=staff&get=settings">Настройки</a></td>
			</tr>
		</table>
	
	{if empty($smarty.get.get)}
		<h1>Список</h1>
		<div style="margin: 10px 0; padding: 10px; background: #f5f5f5; border-radius: 4px;">
			<form method="get" style="display: inline-block;">
				<input type="hidden" name="show" value="staff">
				<input type="text" name="search" value="{$search|escape:'html'}" placeholder="Поиск по ФИО..." style="width: 250px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px;">
				<input type="submit" value="Найти" style="padding: 5px 15px; cursor: pointer;">
				{if !empty($search)}<a href="?show=staff" style="margin-left: 10px;">Сбросить</a>{/if}
			</form>
			{if !empty($search)}<span style="margin-left: 15px; color: #666;">Результаты поиска: &laquo;{$search|escape:'html'}&raquo;</span>{/if}
		</div>
		{if $letter_list && empty($search)}
			<div class="staff_letter">
			{foreach key=key item=item from=$letter_list name=letter}{if $item != ''}<a href="?show=staff{if !empty($smarty.get.sor)}&sort={$smarty.get.sort}{/if}&letter={$item}">{$key}</a>{else}<b>{$key}</b>{/if} {/foreach}
			</div>
		{/if}
		{if $staff_list}
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th style="width: 150px;">фамилия</th>
					<th style="width: 150px;">имя</th>
					<th style="width: 150px;">отчество</th>
					<th width="100" style="width: 100px;">команда</th>
					<th width="100" style="width: 100px;">клуб</th>
					<th width="16" style="width: 16px;">фото</th>
					<th style="width: 50px;">вкл/откл</th>
				</tr>
			{foreach key=key item=item from=$staff_list name=staff}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td valign="top"><a href="?show=staff&get=edit&item={$item.st_id}">{if $item.st_family_ru == ''}<font style="color: #f00;">Без фамилии</font>{else}{$item.st_family_ru}{/if}</a></td>
					<td valign="top"><a href="?show=staff&get=edit&item={$item.st_id}">{if $item.st_name_ru == ''}<font style="color: #f00;">Без имени</font>{else}{$item.st_name_ru}{/if}</a></td>
					<td valign="top"><a href="?show=staff&get=edit&item={$item.st_id}">{if $item.st_surname_ru == ''}<font style="color: #f00;">Без отчества</font>{else}{$item.st_surname_ru}{/if}</a></td>
					<td valign="top">{if (!empty($item.team))}{$item.team}{/if}</td>
					<td valign="top">{if !empty($item.club)}{$item.club}{/if}</td>
					<td align="center" valign="top">{if !empty($item.photo_av)}<img src="images/photos.gif" alt="есть фото" border="0">{/if}</td>
					<td align="center" valign="top">{if $item.st_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			</table>
		{/if}
        <br>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="st_is_active" checked /></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать дату: </th>
							<td width="50%" align="center"><input type="Checkbox" name="st_is_show_birth" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата рождения: </th>
							<td width="50%" align="center">
								<select name="st_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="st_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="st_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+1}
								{section name = year start = 1850 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">телефон: </th>
							<td width="50%" align="center"><input type="text" name="st_height" id="input100" /></td>
						</tr>
						<tr>
							<th width="50%" align="center">тел./факс: </th>
							<td width="50%" align="center"><input type="text" name="st_weight" id="input100" /></td>
						</tr>
						<tr>
							<th width="50%" align="center">e-mail: </th>
							<td width="50%" align="center"><input type="text" name="st_email" id="input100" /></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_staff" id="submitsave" value="Добавить" />
					</td>
				</tr>
                <tr>
                    <th width="50%" align="center">address: </th>
                    <td width="50%" align="center">{$sitepath}people/<input type="text" name="st_address" id="input50" /></td>
                </tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Фамилия (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="st_family_ru" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Имя (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="st_name_ru" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Отчество (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="st_surname_ru" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание-кратко (РУС):</b> <br><textarea name="st_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст полностью (РУС):</b> <br><textarea name="st_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>
					<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Фамилия (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="st_family_ua" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Имя (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="st_name_ua" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Отчество (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="st_surname_ua" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание-кратко (УКР):</b> <br><textarea name="st_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст полностью (УКР):</b> <br><textarea name="st_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>
					<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Фамилия (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="st_family_en" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Имя (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="st_name_en" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Отчество (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="st_surname_en" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание-кратко (ENG):</b> <br><textarea name="st_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст полностью (ENG):</b> <br><textarea name="st_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">

		</div>
		</form>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && $smarty.get.item>0}
		<h1>Редактирование: &laquo;{$staff_item.st_family_ru} {$staff_item.st_name_ru} {$staff_item.st_surname_ru}&raquo;</h1>
		<center>
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="st_id" value="{$staff_item.st_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="st_is_active"{if $staff_item.st_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать дату: </th>
							<td width="50%" align="center"><input type="Checkbox" name="st_is_show_birth"{if $staff_item.st_is_show_birth == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата рождения: </th>
							<td width="50%" align="center">
								<select name="st_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $staff_item.st_date_birth|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="st_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $staff_item.st_date_birth|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="st_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+1}
								{section name = year start = 1850 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $staff_item.st_date_birth|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">рост: </th>
							<td width="50%" align="center"><input type="text" name="st_height" value="{$staff_item.st_height}" id="input100"></td>
						</tr>
						<tr>
							<th width="50%" align="center">вес: </th>
							<td width="50%" align="center"><input type="text" name="st_weight" value="{$staff_item.st_weight}" id="input100"></td>
						</tr>
						<tr>
							<th width="50%" align="center">e-mail: </th>
							<td width="50%" align="center"><input type="text" name="st_email" value="{$staff_item.st_email}" id="input100"></td>
						</tr>
						<tr>
							<th width="50%" align="center">address: </th>
							<td width="50%" align="center">{$sitepath}people/<input type="text" name="st_address" value="{$staff_item.st_address}" id="input50"></td>
						</tr>
						<tr>
							<td colspan="2" height="15"></td>
						</tr>
						<tr>
							<th width="50%" align="center">объединить с ID: </th>
							<td width="50%" align="center"><input type="number" name="merge_source_id" placeholder="ID дубля" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
                        <a href="/{if !empty($staff_item.st_address)}people/{$staff_item.st_address}{else}u/{$staff_item.st_id}{/if}" target="_blank">Ссылка на профиль</a>
                        <br>
                        <br>
                        <input type="submit" name="save_staff_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_staff" id="submitdelete" value="Удалить">
						<br>
						<br>
						<input type="submit" name="merge_staff" value="Объединить игроков" onclick="return confirm('ВНИМАНИЕ! Все данные (матчи, статистика, фото и т.д.) из указанного игрока будут перенесены в текущий профиль. Старый профиль будет деактивирован. Продолжить?');" style="background: #ff9800; color: white; border: none; padding: 5px 15px; cursor: pointer;">
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
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Статистика</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Фамилия (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="st_family_ru" value="{$staff_item.st_family_ru}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Имя (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="st_name_ru" value="{$staff_item.st_name_ru}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Отчество (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="st_surname_ru" value="{$staff_item.st_surname_ru}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание-кратко (РУС):</b> <br><textarea name="st_description_ru" rows="5" style="width: 100%;">{$staff_item.st_description_ru}</textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст полностью (РУС):</b> <br><textarea name="st_text_ru" style="width: 100%; height: 400px;">{$staff_item.st_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Статистика</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Фамилия (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="st_family_ua" value="{$staff_item.st_family_ua}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Имя (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="st_name_ua" value="{$staff_item.st_name_ua}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Отчество (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="st_surname_ua" value="{$staff_item.st_surname_ua}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание-кратко (УКР):</b> <br><textarea name="st_description_ua" rows="5" style="width: 100%;">{$staff_item.st_description_ua}</textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст полностью (УКР):</b> <br><textarea name="st_text_ua" style="width: 100%; height: 400px;">{$staff_item.st_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					<td width="20%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Статистика</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Фамилия (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="st_family_en" value="{$staff_item.st_family_en}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Имя (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="st_name_en" value="{$staff_item.st_name_en}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Отчество (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="st_surname_en" value="{$staff_item.st_surname_en}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание-кратко (ENG):</b> <br><textarea name="st_description_en" rows="5" style="width: 100%;">{$staff_item.st_description_en}</textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Текст полностью (ENG):</b> <br><textarea name="st_text_en" style="width: 100%; height: 400px;">{$staff_item.st_text_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Статистика</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>

			{include file="staff/admin_staff_gallery.tpl"}
		</div>
		{* =========== Statistics ============ *}
		<div id="cont_5">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="20%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
					<td width="20%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Статистика</a></td>
				</tr>
			</table>
			<h1>Статистика</h1>

			{include file="staff/admin_staff_statistics.tpl"}
		</div>
            {include file="admin_meta_seo.tpl" meta_seo_item_type="staff" meta_seo_item_id="`$staff_item.st_id`"}
            <br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>

		<center>
		<b>Вкл/откл блока информера на всех страницах:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $staff_settings_list.is_active_staff_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_staff_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество людей в списках в блоке информера:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$staff_settings_list.count_staff_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_staff_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество людей на одну страницу в списке на странице:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$staff_settings_list.count_staff_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_staff_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{*{include file="staff/admin_title_staff_player_informer.tpl"}*}
		{*{include file="staff/admin_title_staff_head_informer.tpl"}*}
		</center>
		<br>
	{/if}
	</div>