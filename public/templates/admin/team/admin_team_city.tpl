<br>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
		<tr>
			<td width="20%" id="notactive"><a href="?show=team&get=categories">Должности</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=country">Страны</a></td>
			<td width="20%" id="active"><a href="?show=team&get=city">Города</a></td>
            <td width="20%" id="notactive"><a href="?show=team&get=champ">Чемпионаты *</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=stadium">Стадионы</a></td>
		</tr>
	</table>
		
		
	<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'city'}active_left{else}notactive{/if}"><a href="?show=team&get=city">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'cityedit'}active{else}notactive{/if}">{if isset($smarty.get.item) && $smarty.get.item>0}<a href="?show=team&get=cityedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'cityadd'}active_right{else}notactive{/if}"><a href="?show=team&get=cityadd">Добавить</a></td>
			</tr>
		</table>
	
	{if $smarty.get.get == 'city'}
		<h1>Список городов</h1>
		<center>
		{if $team_city_list && $team_country_list && is_array($team_country_list) && is_array($team_city_list)}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach item=item_country from=$team_country_list name=team_country}{if $item_country.cn_id > 0}
				<tr>
					<td align="center">{if $item_country.cn_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item_country.cn_title_ru}{/if}</td>
					<td></td>
				</tr>
				{foreach key=key item=item from=$team_city_list name=team_city}
				{if $item_country.cn_id == $item.ct_cn_id}
					<tr>
						<td><a href="?show=team&get=cityedit&item={$item.ct_id}">{if $item.ct_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.ct_title_ru}{/if}</a></td>
						<td>{if $item.ct_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
					</tr>
				{/if}
				{/foreach}
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
			{/if}{/foreach}
			</table>
		{else}
			<p>Нет данных о странах или городах.</p>
		{/if}
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'cityadd'}
		<h1>Добавить город</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="ct_is_active" checked></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="ct_cn_id" id="input100">
								{if $team_country_list && is_array($team_country_list)}
									{foreach key=key item=item from=$team_country_list name=country}{if $item.cn_id > 0}
										<option value="{$item.cn_id}">{$item.cn_title_ru}</option>
									{/if}{/foreach}
								{else}
									<option value="0">Нет стран</option>
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="ct_order" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_city" id="submitsave" value="Добавить">
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
					<td width="80%" colspan="3"><input type="text" name="ct_title_ru" id="input100"></td>					
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="ct_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="ct_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="ct_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="ct_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="ct_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'cityedit'}
		<h1>Редактирование города: &laquo;{$city_item.ct_title_ru}&raquo;</h1>
		<center>
		<form method="post">
			<input type="Hidden" name="ct_id" value="{$city_item.ct_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="ct_is_active" {if $city_item.ct_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="ct_cn_id" id="input100">
								{if $team_country_list && is_array($team_country_list)}
									{foreach key=key item=item from=$team_country_list name=country}{if $item.cn_id > 0}
										<option value="{$item.cn_id}"{if $item.cn_id == $city_item.ct_cn_id} selected{/if}>{$item.cn_title_ru}</option>
									{/if}{/foreach}
								{else}
									<option value="0">Нет стран</option>
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="ct_order" value="{$city_item.ct_order}" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_city_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_city" id="submitdelete" value="Удалить">
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
					<td width="80%" colspan="3"><input type="text" name="ct_title_ru" value="{$city_item.ct_title_ru|default:''}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="ct_description_ru" rows="5" style="width: 100%;">{$city_item.ct_description_ru|default:''}</textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="ct_title_ua" value="{$city_item.ct_title_ua|default:''}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="ct_description_ua" rows="5" style="width: 100%;">{$city_item.ct_description_ua|default:''}</textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="ct_title_en" value="{$city_item.ct_title_en|default:''}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="ct_description_en" rows="5" style="width: 100%;">{$city_item.ct_description_en|default:''}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}