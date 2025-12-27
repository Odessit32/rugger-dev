<br>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
		<tr>
			<td width="20%" id="active_left"><a href="?show=team&get=categories">Должности</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=country">Страны</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=city">Города</a></td>
            <td width="20%" id="notactive"><a href="?show=team&get=champ">Чемпионаты *</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=stadium">Стадионы</a></td>
		</tr>
	</table>
		
		
	<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'categories'}active_left{else}notactive{/if}"><a href="?show=team&get=categories">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'categedit'}active{else}notactive{/if}">{if isset($smarty.get.item) && $smarty.get.item>0}<a href="?show=team&get=categedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'categadd'}active_right{else}notactive{/if}"><a href="?show=team&get=categadd">Добавить</a></td>
			</tr>
		</table>
	
	{if $smarty.get.get == 'categories'}
		<h1>Список должностей в команде</h1>
		<center>
		{if isset($team_categories_list) && $team_categories_list && is_array($team_categories_list)}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr><td colspan="2" align="center"><b>игроки</b></td></tr>
			{foreach key=key item=item from=$team_categories_list name=team_category}
			{if $item.app_type == 'player'}
				<tr>
					<td><a href="?show=team&get=categedit&item={$item.app_id}">{if $item.app_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.app_title_ru}{/if}</a></td>
					<td>{if $item.app_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/if}
			{/foreach}
			<tr><td colspan="2" align="center"><b>руководители</b></td></tr>
			{foreach key=key item=item from=$team_categories_list name=team_category}
			{if $item.app_type == 'head'}
				<tr>
					<td><a href="?show=team&get=categedit&item={$item.app_id}">{if $item.app_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.app_title_ru}{/if}</a></td>
					<td>{if $item.app_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/if}
			{/foreach}
			<tr><td colspan="2" align="center"><b>другие</b></td></tr>
			{foreach key=key item=item from=$team_categories_list name=team_category}
			{if $item.app_type == 'rest'}
				<tr>
					<td><a href="?show=team&get=categedit&item={$item.app_id}">{if $item.app_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.app_title_ru}{/if}</a></td>
					<td>{if $item.app_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/if}
			{/foreach}
			</table>
		{else}
			<p>Нет данных о должностях.</p>
		{/if}
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'categadd'}
		<h1>Добавить должность</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="app_is_active" checked></td>
						</tr>
						<tr>
							<th width="50%" align="center">тип должности: </th>
							<td width="50%" align="center">
								<select name="app_type" id="input100">
									<option value="player">Игрок</option>
									<option value="head">Руководство</option>
									<option value="rest">Другие</option>
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="app_order" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_category" id="submitsave" value="Добавить">
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
					<td width="80%"><input type="text" name="app_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="app_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="app_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="app_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="app_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="app_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'categedit'}
		<h1>Редактирование должности: &laquo;{$category_item.app_title_ru|default:'Не указано'}&raquo;</h1>
		<center>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="app_id" value="{$category_item.app_id|default:0}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="app_is_active" {if $category_item.app_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th width="50%" align="center">тип должности: </th>
							<td width="50%" align="center">
								<select name="app_type" id="input100">
									<option value="player"{if $category_item.app_type == 'player'} selected{/if}>Игрок</option>
									<option value="head"{if $category_item.app_type == 'head'} selected{/if}>Руководство</option>
									<option value="rest"{if $category_item.app_type == 'rest'} selected{/if}>Другие</option>
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="app_order" value="{$category_item.app_order|default:''}" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_category_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_category" id="submitdelete" value="Удалить">
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
					<td width="80%"><input type="text" name="app_title_ru" value="{$category_item.app_title_ru|default:''}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="app_description_ru" rows="5" style="width: 100%;">{$category_item.app_description_ru|default:''}</textarea></td></tr>
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
					<td width="80%"><input type="text" name="app_title_ua" value="{$category_item.app_title_ua|default:''}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="app_description_ua" rows="5" style="width: 100%;">{$category_item.app_description_ua|default:''}</textarea></td></tr>
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
					<td width="80%"><input type="text" name="app_title_en" value="{$category_item.app_title_en|default:''}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="app_description_en" rows="5" style="width: 100%;">{$category_item.app_description_en|default:''}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}