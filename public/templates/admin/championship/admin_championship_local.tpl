		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'local'}active_left{else}notactive{/if}"><a href="?show=championship&get=local">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'localedit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=championship&get=localedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'localadd'}active_right{else}notactive{/if}"><a href="?show=championship&get=localadd">Добавить</a></td>
			</tr>
		</table>
	
	{if $smarty.get.get == 'local'}
		<h1>Список локализаций групп чемпионатов</h1>
		<center>
		{if $championship_local_list}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$championship_local_list}
				<tr>
					<td><a href="?show=championship&get=localedit&item={$item.chl_id}">{if $item.title == ''}<font style="color: #f00;">Без названия</font>{else}{$item.title}{/if}</a></td>
					<td>{$item.chl_address}</td>
					<td>{if $item.is_main == 'yes'}<img src="images/main.gif" alt="главная" border="0">{/if}</td>
					<td>{if $item.is_menu == 'no'}<img src="images/menu_no.gif" alt="скрыт" border="0">{else}<img src="images/menu_yes.gif" alt="в меню" border="0">{/if}</td>
					<td>{if $item.is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			</table>
		{else}
			Нет локализаций групп.<br>
		{/if}
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'localadd'}
		<h1>Добавить локализацию группы чемпионата</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="chl_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главная локаль: </th>
							<td width="50%" align="center"><input type="Checkbox" name="chl_is_main"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Отображать в меню: </th>
							<td width="50%" align="center"><input type="Checkbox" name="chl_is_menu"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Порядок: </th>
							<td width="50%" align="center"><input type="text" name="chl_order" id="input" size="5"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Адресс: </th>
							<td width="50%" align="center"><input type="text" name="chl_address" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_local" id="submitsave" value="Добавить">
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
					<td width="80%" colspan="3"><input type="text" name="chl_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="chl_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="chl_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="chl_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="chl_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="chl_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'localedit'}
		<h1>Редактирование локализациb группы чемпионата: &laquo;{$local_item.chl_title_ru}&raquo;</h1>
		<center>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="chl_id" value="{$local_item.chl_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="chl_is_active" {if $local_item.chl_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главная локаль: </th>
							<td width="50%" align="center"><input type="Checkbox" name="chl_is_main"{if $local_item.chl_is_main == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Отображать в меню: </th>
							<td width="50%" align="center"><input type="Checkbox" name="chl_is_menu"{if $local_item.chl_is_menu == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Порядок: </th>
							<td width="50%" align="center"><input type="text" name="chl_order" id="input" size="5" value="{$local_item.chl_order}"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Адресс: </th>
							<td width="50%" align="center"><input type="text" name="chl_address" value="{$local_item.chl_address}" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_local_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_local" id="submitdelete" value="Удалить">
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
					<td width="80%" colspan="3"><input type="text" name="chl_title_ru" value="{$local_item.chl_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="chl_description_ru" rows="5" style="width: 100%;">{$local_item.chl_description_ru}</textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="chl_title_ua" value="{$local_item.chl_title_ua}" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="chl_description_ua" rows="5" style="width: 100%;">{$local_item.chl_description_ua}</textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="chl_title_en" value="{$local_item.chl_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="chl_description_en" rows="5" style="width: 100%;">{$local_item.chl_description_en}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	