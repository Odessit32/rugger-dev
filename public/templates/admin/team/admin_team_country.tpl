<br>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
		<tr>
			<td width="20%" id="notactive"><a href="?show=team&get=categories">Должности</a></td>
			<td width="20%" id="active"><a href="?show=team&get=country">Страны</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=city">Города</a></td>
            <td width="20%" id="notactive"><a href="?show=team&get=champ">Чемпионаты *</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=stadium">Стадионы</a></td>
		</tr>
	</table>
		
		
	<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'country'}active_left{else}notactive{/if}"><a href="?show=team&get=country">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'countryedit'}active{else}notactive{/if}">{if isset($smarty.get.item) && $smarty.get.item>0}<a href="?show=team&get=countryedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'countryadd'}active_right{else}notactive{/if}"><a href="?show=team&get=countryadd">Добавить</a></td>
			</tr>
		</table>
	
	{if $smarty.get.get == 'country'}
		<h1>Список стран</h1>
		<center>
		{if $team_country_list && is_array($team_country_list)}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$team_country_list name=team_country}{if $item.cn_id > 0}
				<tr>
					<td><a href="?show=team&get=countryedit&item={$item.cn_id}">{if $item.cn_title_ru == ''}<span style="color: #f00;">Без названия</span>{else}{$item.cn_title_ru}{/if}</a></td>
					<td>{if $item.cn_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/if}{/foreach}
			</table>
		{else}
			<p>Нет данных о странах.</p>
		{/if}
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'countryadd'}
		<h1>Добавить страну</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cn_is_active" checked></td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="cn_order" id="input100"></td>
						</tr>
                            <tr>
                                <th width="50%" align="center">адрес: </th>
                                <td width="50%" align="center"><input type="text" name="cn_address" id="input100"></td>
                            </tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_country" id="submitsave" value="Добавить">
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
						<td width="80%"><input type="text" name="cn_title_ru" id="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="cn_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
						<td width="80%"><input type="text" name="cn_title_ua" id="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="cn_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
						<td width="80%"><input type="text" name="cn_title_en" id="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="cn_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
			<div id="cont_4">
			</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'countryedit'}
		<h1>Диагностика редактирования страны</h1>
		<center>
			<p>Параметр item: {$smarty.get.item|default:'не указан'}</p>
			<p>Переменная country_item: {if isset($country_item)}{$country_item|print_r}{else}не определена{/if}</p>
			<p>Тип country_item: {if isset($country_item)}{$country_item|gettype}{else}не определена{/if}</p>
			{if !isset($country_item)}
				<p>Проверьте PHP-код: возможно, $smarty->assign('country_item', $country_item) не выполняется.</p>
			{/if}
		</center>
	{/if}