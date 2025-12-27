	<br>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
		<tr>
			<td width="20%" id="notactive"><a href="?show=team&get=categories">Должности</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=country">Страны</a></td>
			<td width="20%" id="notactive"><a href="?show=team&get=city">Города</a></td>
            <td width="20%" id="notactive"><a href="?show=team&get=champ">Чемпионаты *</a></td>
			<td width="20%" id="active_right"><a href="?show=team&get=stadium">Стадионы</a></td>
		</tr>
	</table>
		
		
	<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'stadium'}active_left{else}notactive{/if}"><a href="?show=team&get=stadium">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'stadiumedit'}active{else}notactive{/if}">{if isset($smarty.get.item) && $smarty.get.item>0}<a href="?show=team&get=stadiumedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'stadiumadd'}active_right{else}notactive{/if}"><a href="?show=team&get=stadiumadd">Добавить</a></td>
			</tr>
		</table>
	
	{if $smarty.get.get == 'stadium'}
		<h1>Список стадионов</h1>
		<center>
		{if $team_stadium_list and $team_country_list}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach item=item_country from=$team_country_list name=team_country}
				<tr>
					<td align="center">{if $item_country.cn_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item_country.cn_title_ru}{/if}</td>
					<td></td>
				</tr>
				{foreach key=key item=item from=$team_stadium_list name=team_stadium}
				{if $item_country.cn_id == $item.std_cn_id}
					<tr>
						<td><a href="?show=team&get=stadiumedit&item={$item.std_id}">{if $item.std_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.std_title_ru}{/if}</a></td>
						<td>{if $item.std_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
					</tr>
				{/if}
				{/foreach}
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
			{/foreach}
			</table>
		{/if}
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'stadiumadd'}
		<h1>Добавить стадион</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="std_is_active" checked></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="std_cn_id" id="input100" onchange="javascript: getCityList(this.value, 0, 0);">
								{if $team_country_list}
								{foreach key=key item=item from=$team_country_list name=categories}{if $item.cn_id>0}
									<option value="{$item.cn_id}">{$item.cn_title_ru}</option>
								{/if}{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100">
									<option value="0">----------</option>
								{foreach key=key item=item from=$team_city_list name=categories}{if $item.ct_cn_id>0}
									{if $item.ct_cn_id == $team_country_list.0.cn_id}<option value="{$item.ct_id}">{$item.ct_title_ru}</option>{/if}
								{/if}{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="std_order" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_stadium" id="submitsave" value="Добавить">
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
					<td width="80%" colspan="3"><input type="text" name="std_title_ru" id="input100"></td>					
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="std_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="std_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="std_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="std_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="std_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'stadiumedit'}
		<h1>Редактирование стадиона: &laquo;{$stadium_item.std_title_ru}&raquo;</h1>
		<center>
		<form method="post">
			<input type="Hidden" name="std_id" value="{$stadium_item.std_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="std_is_active" {if $stadium_item.std_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="std_cn_id" id="input100" onchange="javascript: getCityList(this.value, {$stadium_item.std_ct_id}, {$stadium_item.std_id});">
								{foreach key=key item=item from=$team_country_list name=categories}{if $item.cn_id>0}
									<option value="{$item.cn_id}"{if $stadium_item.std_cn_id == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
								{/if}{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100">
								{foreach key=key item=item from=$team_city_list name=categories}{if $item.ct_cn_id>0}
									{if $stadium_item.std_cn_id > 0}
										{if $item.ct_cn_id == $stadium_item.std_cn_id}<option value="{$item.ct_id}"{if $stadium_item.std_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{else}
										{if $item.ct_cn_id == $team_country_list.0.cn_id}<option value="{$item.ct_id}"{if $stadium_item.std_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{/if}
								{/if}{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center"><input type="text" name="std_order" value="{$stadium_item.std_order}" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_stadium_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_stadium" id="submitdelete" value="Удалить">
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
					<td width="80%" colspan="3"><input type="text" name="std_title_ru" value="{$stadium_item.std_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="std_description_ru" rows="5" style="width: 100%;">{$stadium_item.std_description_ru}</textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="std_title_ua" value="{$stadium_item.std_title_ua}" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="std_description_ua" rows="5" style="width: 100%;">{$stadium_item.std_description_ua}</textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="std_title_en" value="{$stadium_item.std_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="std_description_en" rows="5" style="width: 100%;">{$stadium_item.std_description_en}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	