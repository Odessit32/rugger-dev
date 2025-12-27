	<br>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
		<tr>
			<td width="25%" id="notactive"><a href="?show=club&get=categories">Должности</a></td>
			<td width="25%" id="active"><a href="?show=club&get=country">Страны</a></td>
			<td width="25%" id="notactive"><a href="?show=club&get=city">Города</a></td>
			<td width="25%" id="notactive"><a href="?show=club&get=stadium">Стадионы</a></td>
		</tr>
	</table>
		
		
	<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'country'}active_left{else}notactive{/if}"><a href="?show=club&get=country">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'countryedit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=club&get=countryedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'countryadd'}active_right{else}notactive{/if}"><a href="?show=club&get=countryadd">Добавить</a></td>
			</tr>
		</table>
	
	{if $smarty.get.get == 'country'}
		<h1>Список стран</h1>
		<center>
		{if $club_country_list}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$club_country_list name=club_country}{if $item.cn_id > 0}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=club&get=countryedit&item={$item.cn_id}">{if $item.cn_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.cn_title_ru}{/if}</a></td>
					<td>{if $item.cn_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/if}{/foreach}
			</table>
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
		<h1>Редактирование страны: &laquo;{$country_item.cn_title_ru}&raquo;</h1>
		<center>
		<form method="post">
			<input type="Hidden" name="cn_id" value="{$country_item.cn_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cn_is_active" {if $country_item.cn_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="cn_order" value="{$country_item.cn_order}" id="input100"></td>
						</tr>
                            <tr>
                                <th width="50%" align="center">адрес: </th>
                                <td width="50%" align="center"><input type="text" name="cn_address" value="{$country_item.cn_address}" id="input100"></td>
                            </tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_country_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_country" id="submitdelete" value="Удалить">
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
					<td width="80%"><input type="text" name="cn_title_ru" value="{$country_item.cn_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="cn_description_ru" rows="5" style="width: 100%;">{$country_item.cn_description_ru}</textarea></td></tr>
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
					<td width="80%"><input type="text" name="cn_title_ua" value="{$country_item.cn_title_ua}" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="cn_description_ua" rows="5" style="width: 100%;">{$country_item.cn_description_ua}</textarea></td></tr>
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
					<td width="80%"><input type="text" name="cn_title_en" value="{$country_item.cn_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="cn_description_en" rows="5" style="width: 100%;">{$country_item.cn_description_en}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	