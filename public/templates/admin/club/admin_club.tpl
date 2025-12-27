<H1>Клубы:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="20%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=club">Список</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=club&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=club&get=add">Добавить</a></td>
				<td width="20%" id="{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd' or $smarty.get.get == 'country' or $smarty.get.get == 'countryedit' or $smarty.get.get == 'countryadd' or $smarty.get.get == 'city' or $smarty.get.get == 'cityedit' or $smarty.get.get == 'cityadd' or $smarty.get.get == 'stadium' or $smarty.get.get == 'stadiumadd' or $smarty.get.get == 'stadiumedit'}active{else}notactive{/if}"><a href="?show=club&get=categories">Переменные</a></td>
				<td width="20%" id="{if $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=club&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd'}
		{include file="club/admin_club_categories.tpl"}
	{/if}
	{if $smarty.get.get == 'country' or $smarty.get.get == 'countryedit' or $smarty.get.get == 'countryadd'}
		{include file="club/admin_club_country.tpl"}
	{/if}
	{if $smarty.get.get == 'city' or $smarty.get.get == 'cityedit' or $smarty.get.get == 'cityadd'}
		{include file="club/admin_club_city.tpl"}
	{/if}
	{if $smarty.get.get == 'stadium' or $smarty.get.get == 'stadiumedit' or $smarty.get.get == 'stadiumadd'}
		{include file="club/admin_club_stadium.tpl"}
	{/if}
	
	{if empty($smarty.get.get)}
		<h1>Список клубов</h1>
		<center>
		{if $club_list}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$club_list name=club}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=club&get=edit&item={$item.cl_id}">{if $item.cl_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.cl_title_ru}{/if}</a></td>
					<td>{assign var="nc_id" value=$item.cl_nc_id}{$club_categories_list_id.$nc_id.nc_title_ru}</td>
					<td>{if $item.cl_is_main == 'yes'}<img src="images/main.jpg" alt="главная" border="0">{/if}</td>
					<td>{if $item.cl_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			</table>
		{/if}
			<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить клуб</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cl_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главный клуб: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cl_is_main"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата основания: </th>
							<td width="50%" align="center">
								<select name="cl_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="cl_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="cl_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+1}
								{section name = year start = 1900 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="cl_cn_id" id="input100" onchange='getCityList(this.value, 0)'>
								{foreach key=key item=item from=$club_country_list name=categories}
									<option value="{$item.cn_id}">{$item.cn_title_ru}</option>
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100">
								{foreach key=key item=item from=$club_city_list name=categories}
									{if $item.ct_cn_id == $club_country_list.0.cn_id}<option value="{$item.ct_id}">{$item.ct_title_ru}</option>{/if}
								{/foreach}
								</select>
							</td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_club" id="submitsave" value="Добавить">
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
					<td width="80%" colspan="3"><input type="text" name="cl_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание кратко (РУС):</b> <br><textarea name="cl_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание подробно (РУС):</b> <br><textarea name="cl_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="cl_title_ua" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание кратко (УКР):</b> <br><textarea name="cl_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание подробно (УКР):</b> <br><textarea name="cl_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%" colspan="3"><input type="text" name="cl_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание кратко (ENG):</b> <br><textarea name="cl_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание подробно (ENG):</b> <br><textarea name="cl_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'edit' and $smarty.get.item>0}
		<h1>Редактирование клуба: &laquo;{$club_item.cl_title_ru}&raquo;</h1>
		<center>
			<form method="post">
				<input type="Hidden" name="cl_id" value="{$club_item.cl_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cl_is_active"{if $club_item.cl_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главный клуб: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cl_is_main"{if $club_item.cl_is_main == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата основания: </th>
							<td width="50%" align="center">
								<select name="cl_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $club_item.cl_date_foundation|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="cl_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $club_item.cl_date_foundation|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="cl_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+1}
								{section name = year start = 1900 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $club_item.cl_date_foundation|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
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
								<select name="cl_cn_id" id="input100" onchange='getCityList(this.value, {$club_item.cl_ct_id})'>
								{foreach key=key item=item from=$club_country_list name=categories}
									<option value="{$item.cn_id}"{if $club_item.cl_cn_id == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100">
								{foreach key=key item=item from=$club_city_list name=categories}
									{if $club_item.cl_cn_id > 0}
										{if $item.ct_cn_id == $club_item.cl_cn_id}<option value="{$item.ct_id}"{if $club_item.cl_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{else}
										{if $item.ct_cn_id == $club_country_list.0.cn_id}<option value="{$item.ct_id}"{if $club_item.cl_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{/if}
								{/foreach}
								</select>
							</td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_club_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_club" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="17%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="cl_title_ru" value="{$club_item.cl_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание кратко (РУС):</b> <br><textarea name="cl_description_ru" rows="5" style="width: 100%;">{$club_item.cl_description_ru}</textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание подробно (РУС):</b> <br><textarea name="cl_text_ru" style="width: 100%; height: 400px;">{$club_item.cl_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="cl_title_ua" value="{$club_item.cl_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание кратко (УКР):</b> <br><textarea name="cl_description_ua" rows="5" style="width: 100%;">{$club_item.cl_description_ua}</textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание подробно (УКР):</b> <br><textarea name="cl_text_ua" style="width: 100%; height: 400px;">{$club_item.cl_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="16%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="cl_title_en" value="{$club_item.cl_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание кратко (ENG):</b> <br><textarea name="cl_description_en" rows="5" style="width: 100%;">{$club_item.cl_description_en}</textarea></td></tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание подробно (ENG):</b> <br><textarea name="cl_text_en" style="width: 100%; height: 400px;">{$club_item.cl_text_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>
			
			{include file="club/admin_club_gallery.tpl"}
		</div>
		{* =========== СОСТАВ ============ *}
		<div id="cont_5">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			{include file="club/admin_club_staff.tpl"}
		</div>
		{* =========== КОМАНДЫ ============ *}
		<div id="cont_6">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="16%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Состав</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			{include file="club/admin_club_team.tpl"}
		</div>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'settings'}
		<h1>Настройки</h1>
		
		<center>
		<b>Вкл/откл блока команд слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $club_settings_list.is_active_club_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_club_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество команд в блоке команд слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$club_settings_list.count_club_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_club_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество команд на одну страницу в разделе команд:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$club_settings_list.count_club_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_club_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="club/admin_title_club.tpl"}
		{include file="club/admin_title_club_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>