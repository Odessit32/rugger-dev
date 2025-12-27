<H1>Чемпионаты:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=championship{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Список</a></td>
				<td id="{if !empty($smarty.get.get) && $smarty.get.get == 'archive'}active{else}notactive{/if}"><a href="?show=championship&get=archive{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Архив</a></td>
				<td id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=championship&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=championship&get=add">Добавить</a></td>
				<td id="{if !empty($smarty.get.get) && ($smarty.get.get == 'group' || $smarty.get.get == 'groupedit' || $smarty.get.get == 'groupadd')}active{else}notactive{/if}"><a href="?show=championship&get=group">Группы</a></td>
				<td id="{if !empty($smarty.get.get) && ($smarty.get.get == 'local' || $smarty.get.get == 'localedit' || $smarty.get.get == 'localadd')}active{else}notactive{/if}"><a href="?show=championship&get=local">Локали</a></td>
				<td id="{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' || $smarty.get.get == 'categedit' || $smarty.get.get == 'categadd')}active{else}notactive{/if}"><a href="?show=championship&get=categories">Тип</a></td>
				<td id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=championship&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' || $smarty.get.get == 'categedit' || $smarty.get.get == 'categadd')}
		{include file="championship/admin_championship_categories.tpl"}
	{/if}
	{if !empty($smarty.get.get) && ($smarty.get.get == 'group' || $smarty.get.get == 'groupedit' || $smarty.get.get == 'groupadd')}
		{include file="championship/admin_championship_group.tpl"}
	{/if}
	{if !empty($smarty.get.get) && ($smarty.get.get == 'local' || $smarty.get.get == 'localedit' || $smarty.get.get == 'localadd')}
		{include file="championship/admin_championship_local.tpl"}
	{/if}

	{if empty($smarty.get.get)}
		<h1>Список чемпионатов</h1>
		<center>

			<table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td align="center" colspan="7">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=championship{if !empty($smarty.get.season)}&season={$smarty.get.season}{/if}&country='+this.value">
							<option value="all"{if !empty($smarty.get.country) && $smarty.get.country == 'all'} selected{/if}>все</option>
							<option value="0"{if empty($smarty.get.country)} selected{/if}>без страны</option>
						{if $championship_country_list_ne}
						{foreach key=key item=item from=$championship_country_list_ne name=tcl}
							<option value="{$item.cn_id}"{if !empty($smarty.get.country) && $smarty.get.country == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
						{/foreach}
						{/if}
					</select>
                                        <select id="input" style="width: 200px;" onchange="document.location.href='?show=championship{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}&season='+this.value">
							<option value=''>----------</option>
						{if $season_list}
						{foreach key=key item=item from=$season_list name=seasons}
							<option value="{$item.title}"{if !empty($smarty.get.season) && $smarty.get.season == $item.title} selected{/if}>{$item.title}</option>
						{/foreach}
						{/if}
					</select>
					<br><br>
					</td>
				</tr>
				<tr>
					<th>локаль</th>
					<th>группа</th>
					<th>название чемпионата</th>
					<th>адрес</th>
					<th>тип</th>
					<th>главный</th>
					<th>вкл./откл.</th>
				</tr>
			{if $championship_list}
			{foreach key=key item=item from=$championship_list name=championship}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=championship&get=edit&item={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chl_title_ru}</a>&nbsp;</td>
					<td><a href="?show=championship&get=edit&item={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chg_title_ru}</a>&nbsp;</td>
					<td><a href="?show=championship&get=edit&item={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{if $item.ch_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.ch_title_ru}{/if}</a></td>
					<td>{$item.ch_address}</td>
					<td>{$item.chc_title_ru}</td>
					<td align="center">{if $item.ch_is_main == 'yes'}<img src="images/main.gif" alt="главная" border="0">{/if}</td>
					<td align="center">{if $item.ch_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			<br>
		</center>
	{/if}
        {if !empty($smarty.get.get) && $smarty.get.get == 'archive'}
            <h1>Архив: Список чемпионатов</h1>

                <table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <td align="center" colspan="7">
                            <select id="input" style="width: 200px;" onchange="document.location.href='?show=championship{if $smarty.get.season!=''}&season={$smarty.get.season}{/if}&country='+this.value">
                                <option value="all"{if !empty($smarty.get.country) && $smarty.get.country == 'all'} selected{/if}>все</option>
                                <option value="0"{if empty($smarty.get.country)} selected{/if}>без страны</option>
                                {if $championship_country_list_ne}
                                    {foreach key=key item=item from=$championship_country_list_ne name=tcl}
                                        <option value="{$item.cn_id}"{if !empty($smarty.get.country) && $smarty.get.country == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            <select id="input" style="width: 200px;" onchange="document.location.href='?show=championship{if $smarty.get.country!=''}&country={$smarty.get.country}{/if}&season='+this.value">
                                <option value=''>----------</option>
                                {if $season_list}
                                    {foreach key=key item=item from=$season_list name=seasons}
                                        <option value="{$item.title}"{if !empty($smarty.get.season) && $smarty.get.season == $item.title} selected{/if}>{$item.title}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <th>локаль</th>
                        <th>группа</th>
                        <th>название чемпионата</th>
                        <th>адрес</th>
                        <th>тип</th>
                        <th>главный</th>
                        <th>вкл./откл.</th>
                    </tr>
                    {if $championship_list}
                        {foreach key=key item=item from=$championship_list name=championship}
                            <tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
                                <td><a href="?show=championship&get=edit&item={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chl_title_ru}</a>&nbsp;</td>
                                <td><a href="?show=championship&get=edit&item={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chg_title_ru}</a>&nbsp;</td>
                                <td><a href="?show=championship&get=edit&item={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{if $item.ch_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.ch_title_ru}{/if}</a></td>
                                <td>{$item.ch_address}</td>
                                <td>{$item.chc_title_ru}</td>
                                <td align="center">{if $item.ch_is_main == 'yes'}<img src="images/main.gif" alt="главная" border="0">{/if}</td>
                                <td align="center">{if $item.ch_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
                            </tr>
                        {/foreach}
                    {/if}
                </table>
                <br>
        {/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить чемпионат</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%" colspan="2">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Показывать в меню: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_menu" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Показывать в информере: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_informer" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главный чемпионат: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_main"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">закрыть чемпионат<br>(когда все игры сыграны): </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_done"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Группа: </th>
							<td width="50%" align="center">
								<select name="ch_chg_id" id="input100">
								{foreach key=key item=item from=$championship_group_list name=group}
									<option value="{$item.chg_id}"{if $championship_item.ch_chg_id == $item.chg_id} selected{/if}>{$item.chg_title_ru}</option>
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата c: </th>
							<td width="50%" align="center">
								<select name="ch_date_from_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="ch_date_from_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="ch_date_from_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата до: </th>
							<td width="50%" align="center">
								<select name="ch_date_to_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="ch_date_to_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="ch_date_to_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">тип: </th>
							<td width="50%" align="center">
								<select name="ch_chc_id" id="input100">
								{foreach key=key item=item from=$championship_categories_list name=categories}
									<option value="{$item.chc_id}"{if $championship_item.ch_chc_id == $item.chc_id} selected{/if}>{$item.chc_title_ru}</option>
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">основная страна: </th>
							<td width="50%" align="center">
								<select name="ch_cn_id" id="input100" onchange='getCityList(this.value, 0)'>
									<option value="0">----------</option>
								{foreach key=key item=item from=$country_list name=categories}
									<option value="{$item.id}">{$item.title}</option>
								{/foreach}
								</select>
							</td>
						</tr>

						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100">
									<option value="0">----------</option>
								{foreach key=key item=item from=$city_list name=categories}
									{if $item.ct_cn_id == $country_list.0.id}<option value="{$item.ct_id}">{$item.ct_title_ru}</option>{/if}
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">Порядок: </th>
							<td width="50%" align="center"><input type="text" name="ch_order" id="input" size="5"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle" colspan="2">
						<input type="submit" name="add_new_championship" id="submitsave" value="Добавить">
					</td>
				</tr>
                <tr>
                    <th align="center" width="25%">Адресс: </th>
                    <td colspan="3" width="75%">
                        {$sitepath}<input type="text" name="ch_address_path" id="input">&nbsp;/&nbsp;<input type="text" name="ch_address" value="{$championship_item.ch_address}" id="input">
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
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Очки</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="ch_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="ch_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="ch_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Очки</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="ch_title_ua" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="ch_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="ch_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Очки</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="ch_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="ch_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="ch_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Очки</a></td>
				</tr>
			</table>
			<br>
			<table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" colspan="2" align="center">Количество очков: </th>
				</tr>
				<tr>
					<th width="20%" align="center">Победа: </th>
					<td width="80%" ><input type="text" name="ch_p_win" id="input100"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Ничья: </th>
					<td width="80%" ><input type="text" name="ch_p_draw" id="input100"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Проигрыш: </th>
					<td width="80%" ><input type="text" name="ch_p_loss" id="input100"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Бонус 1 (4 попытки): </th>
					<td width="80%" ><input type="text" name="ch_p_bonus_1" id="input100"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Бонус 2: </th>
					<td width="80%" >
						<input type="text" name="ch_p_bonus_2" id="input100" style="width: 50px;"> очков за проигрыш с разницей
						<select name="ch_p_bonus_2_diff" id="input100">
							<option value="7" selected>7 или меньше очков</option>
							<option value="5">5 или меньше очков</option>
						</select>
					</td>
				</tr>
				<tr>
					<th width="20%" align="center">Техническая победа: </th>
					<td width="80%" ><input type="text" name="ch_p_tehwin" id="input100"></td>
				</tr>
			</table>
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && !empty($smarty.get.item)}
		<h1>Редактирование чемпионата: &laquo;{$championship_item.ch_title_ru}&raquo;</h1>
		<center>
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="ch_id" value="{$championship_item.ch_id}">
				<input type="Hidden" name="active_tab" id="active_tab" value="{$smarty.get.tab|default:'cont_1'}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%" colspan="2">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_active"{if $championship_item.ch_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Показывать в меню: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_menu"{if $championship_item.ch_is_menu == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Показывать в информере: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_informer"{if $championship_item.ch_is_informer == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главный чемпионат: </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_main"{if $championship_item.ch_is_main == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">закрыть чемпионат<br>(когда все игры сыграны): </th>
							<td width="50%" align="center"><input type="checkbox" name="ch_is_done"{if $championship_item.ch_is_done == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th width="50%" align="center">Группа: </th>
							<td width="50%" align="center">
								<select name="ch_chg_id" id="input100">
								{foreach key=key item=item from=$championship_group_list name=group}
									<option value="{$item.chg_id}"{if $championship_item.ch_chg_id == $item.chg_id} selected{/if}>{$item.chg_title_ru}</option>
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата c: </th>
							<td width="50%" align="center">
								<select name="ch_date_from_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $championship_item.ch_date_from|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="ch_date_from_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $championship_item.ch_date_from|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="ch_date_from_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $championship_item.ch_date_from|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата до: </th>
							<td width="50%" align="center">
								<select name="ch_date_to_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $championship_item.ch_date_to|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="ch_date_to_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $championship_item.ch_date_to|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="ch_date_to_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $championship_item.ch_date_to|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">тип: </th>
							<td width="50%" align="center">
								{foreach key=key item=item from=$championship_categories_list name=categories}
									{if $championship_item.ch_chc_id == $item.chc_id}<b>{$item.chc_title_ru}</b>{/if}
								{/foreach}
								{*
								<select name="ch_chc_id" id="input100">
								{foreach key=key item=item from=$championship_categories_list name=categories}
									<option value="{$item.chc_id}"{if $championship_item.ch_chc_id == $item.chc_id} selected{/if}>{$item.chc_title_ru}</option>
								{/foreach}
								</select>
								*}
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">основная страна: </th>
							<td width="50%" align="center">
								<select name="ch_cn_id" id="input100" onchange='getCityList(this.value, {$championship_item.ch_ct_id})'>
									<option value="0">----------</option>
								{foreach key=key item=item from=$country_list name=categories}
									<option value="{$item.id}"{if $championship_item.ch_cn_id == $item.id} selected{/if}>{$item.title}</option>
								{/foreach}
								</select>
							</td>
						</tr>


						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100">
									<option value="0">----------</option>
								{foreach key=key item=item from=$city_list name=categories}
									{if $championship_item.ch_cn_id > 0}
										{if $item.ct_cn_id == $championship_item.ch_cn_id}<option value="{$item.ct_id}"{if $championship_item.ch_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{else}
										{if $item.ct_cn_id == $country_list.0.id}<option value="{$item.ct_id}"{if $championship_item.ch_ct_id == $item.ct_id} selected{/if}>{$item.ct_title_ru}</option>{/if}
									{/if}
								{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">Порядок: </th>
							<td width="50%" align="center"><input type="text" name="ch_order" value="{$championship_item.ch_order}" id="input" size="5"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle" colspan="2">
						<input type="submit" name="save_championship_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						{if $championship_item.competitions}Удаление не возможно - есть структура чемпионата{else}<input type="submit" name="delete_championship" id="submitdelete" value="Удалить">{/if}
					</td>
				</tr>
                <tr>
                    <th align="center" width="25%">Адресс: </th>
                    <td colspan="3" width="75%">
                        {$sitepath}<input type="text" name="ch_address_path" value="{$championship_item.ch_settings.ch_address_path}" id="input">&nbsp;/&nbsp;<input type="text" name="ch_address" value="{$championship_item.ch_address}" id="input">
                    </td>
                </tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Очки</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="ch_title_ru" value="{$championship_item.ch_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="ch_description_ru" rows="5" style="width: 100%;">{$championship_item.ch_description_ru}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="ch_text_ru" style="width: 100%; height: 400px;">{$championship_item.ch_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Очки</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="ch_title_ua" value="{$championship_item.ch_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="ch_description_ua" rows="5" style="width: 100%;">{$championship_item.ch_description_ua}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="ch_text_ua" style="width: 100%; height: 400px;">{$championship_item.ch_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Очки</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="ch_title_en" value="{$championship_item.ch_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="ch_description_en" rows="5" style="width: 100%;">{$championship_item.ch_description_en}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="ch_text_en" style="width: 100%; height: 400px;">{$championship_item.ch_text_en}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_5">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Очки</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" colspan="2" align="center">Количество очков: </th>
				</tr>
				<tr>
					<th width="50%" align="center">Победа: </th>
					<td width="50%" >{if $championship_item.competitions_done}{$championship_item.ch_p_win}{else}<input type="text" name="ch_p_win" value="{$championship_item.ch_p_win}" id="input100">{/if}</td>
				</tr>
				<tr>
					<th width="50%" align="center">Ничья: </th>
					<td width="50%" >{if $championship_item.competitions_done}{$championship_item.ch_p_draw}{else}<input type="text" name="ch_p_draw" value="{$championship_item.ch_p_draw}" id="input100">{/if}</td>
				</tr>
				<tr>
					<th width="50%" align="center">Проигрыш: </th>
					<td width="50%" >{if $championship_item.competitions_done}{$championship_item.ch_p_loss}{else}<input type="text" name="ch_p_loss" value="{$championship_item.ch_p_loss}" id="input100">{/if}</td>
				</tr>
				<tr>
					<th width="50%" align="center">Бонус 1: </th>
					<td width="50%" >{if $championship_item.competitions_done}{$championship_item.ch_p_bonus_1}{else}<input type="text" name="ch_p_bonus_1" value="{$championship_item.ch_p_bonus_1}" id="input100">{/if}</td>
				</tr>
				<tr>
					<th width="50%" align="center">Бонус 2: </th>
					<td width="50%" >
						{if $championship_item.competitions_done}
							{$championship_item.ch_p_bonus_2} очков за проигрыш с разницей {if !empty($championship_item.ch_p_bonus_2_diff)}{$championship_item.ch_p_bonus_2_diff}{else}7{/if} или меньше очков
						{else}
							<input type="text" name="ch_p_bonus_2" value="{$championship_item.ch_p_bonus_2}" id="input100" style="width: 50px;"> очков за проигрыш с разницей
							<select name="ch_p_bonus_2_diff" id="input100">
								<option value="7"{if empty($championship_item.ch_p_bonus_2_diff) || $championship_item.ch_p_bonus_2_diff == 7} selected{/if}>7 или меньше очков</option>
								<option value="5"{if !empty($championship_item.ch_p_bonus_2_diff) && $championship_item.ch_p_bonus_2_diff == 5} selected{/if}>5 или меньше очков</option>
							</select>
						{/if}
					</td>
				</tr>
				<tr>
					<th width="50%" align="center">Техническая победа: </th>
					<td width="50%" >{if $championship_item.competitions_done}{$championship_item.ch_p_tehwin}{else}<input type="text" name="ch_p_tehwin" value="{$championship_item.ch_p_tehwin}" id="input100">{/if}</td>
				</tr>
				<tr>
					<th colspan="2" align="center" height="30px"> </th>
				</tr>
				<tr>
					<th>Считать Бонус 1 (атаки):</th>
					<td>
						<select name="bonus_1_type">
							<option value="0"{if empty($championship_item.ch_settings.bonus_1_type)} selected{/if}>Без учета разницы попыток.</option>
							<option value="1"{if !empty($championship_item.ch_settings.bonus_1_type) && $championship_item.ch_settings.bonus_1_type == '1'} selected{/if}>Если у команды на 3 и больше попыток чем у соперника</option>
						</select>
					</td>
				</tr>
				<tr>
					<th colspan="2" align="center" height="30px"> </th>
				</tr>
				{* РЕГБИ-7: Блок "Количество очков в туре" закомментирован *}
				{*
				<tr>
					<th colspan="2" align="center">Количество очков в туре: </th>
				</tr>
				{if $championship_team_list.on}
					{foreach key=key item=item from=$championship_team_list.on name=tour_pionts}
					<tr>
						<th width="50%" align="center">За {$smarty.foreach.tour_pionts.index+1} место</th>
						<td width="50%" ><input type="text" name="ch_tour_points[]" value="{if !empty($championship_item.ch_settings.tourPoints[$smarty.foreach.tour_pionts.index])}{$championship_item.ch_settings.tourPoints[$smarty.foreach.tour_pionts.index]}{/if}" id="input100"></td>
					</tr>
					{/foreach}
				{else}
					Добавьте команды для назначения количества очков в туре.
				{/if}
                <tr>
                    <th width="50%" align="center">Обратный порядок подсчета очков: </th>
                    <td width="50%" ><input type="checkbox" name="tours_points_order" {if !empty($championship_item.ch_settings.tours_points_order)}checked{/if}></td>
                </tr>
				*}
                <tr>
                    <th>Порядок приоритетов в подсчете места команды в турнирной таблице при равном количестве очков:</th>
                    <td>
						{if !empty($championship_table_order_priority)}
						<ul id="sortable_priority" class="sortable_priority">
                            {foreach item=item from=$championship_table_order_priority}
                                {if $item == 'win'}<li class="ui-state-default" data-name="win">Количество побед</li>{/if}
                                {if $item == 'z_p_p'}<li class="ui-state-default" data-name="z_p_p">Разница забитых/пропущенных очков</li>{/if}
                                {if $item == 'z_p_t'}<li class="ui-state-default" data-name="z_p_t">Разница забитых/пропущенных попыток</li>{/if}
                                {if $item == 'b'}<li class="ui-state-default" data-name="b">Количество бонусов</li>{/if}
                                {if $item == 'w_p_v'}<li class="ui-state-default" data-name="w_p_v">Победы в личных встречах (кто победил, тот и выше)</li>{/if}
                                {if $item == 'p_p_v'}<li class="ui-state-default" data-name="p_p_v">Разница игровых очков в личных встречах</li>{/if}
                                {if $item == 't_p_v'}<li class="ui-state-default" data-name="t_p_v">Разница попыток в личных встречах</li>{/if}
                            {/foreach}
                        </ul>
						{/if}
                        <input type="hidden" name="table_order_priority" value="{if !empty($championship_item.ch_settings.table_order_priority)}{$championship_item.ch_settings.table_order_priority}{/if}" />
                        <script>
                            {literal}
                            function getOrderListPriority(){
                                var order = [];
                                $('#sortable_priority li').each( function(e) {
                                    order.push( $(this).data('name') );
                                });
                                var positions = order.join(',')
                                console.log('positions:', positions);
                                $("input[type='hidden']input[name='table_order_priority']").val(positions);
                            }
                            $(function() {
                                $( "#sortable_priority" ).sortable({
                                    placeholder: "ui-state-highlight",
                                    update: function(event, ui) {
                                        getOrderListPriority();
                                    },
                                    create: function(event, ui) {
                                        getOrderListPriority();
                                    }
                                });
                                $( "#sortable_priority" ).disableSelection();
                            });
                            {/literal}
                        </script>
                    </td>
                </tr>
				<tr>
					<th>Отображать статистику бомбардиров на сайте:</th>
					<td><input type="checkbox" name="show_stuff_rating" {if !isset($championship_item.ch_settings.show_stuff_rating) || $championship_item.ch_settings.show_stuff_rating == 1}checked{/if}></td>
				</tr>
				<tr>
					<th>Количество бомбардиров в статистике:</th>
					<td><input type="text" name="count_stuff_rating" value="{if !empty($championship_item.ch_settings.count_stuff_rating)}{$championship_item.ch_settings.count_stuff_rating}{/if}"></td>
				</tr>
				<tr><td colspan="2" height="20"></td></tr>
				<tr>
					<th>Турнирная таблица Регби супер 15:</th>
					<td>
						<select name="table_type_super_15">
							<option value="0"{if empty($championship_item.ch_settings.table_type_super_15)} selected{/if}>Вверху команды, занимающие первые места</option>
							<option value="1"{if !empty($championship_item.ch_settings.table_type_super_15) && $championship_item.ch_settings.table_type_super_15 == '1'} selected{/if}>Сортировать по классической системе</option>
							<option value="2"{if !empty($championship_item.ch_settings.table_type_super_15) && $championship_item.ch_settings.table_type_super_15 == '2'} selected{/if}>Не выводить общую турнирную таблицу</option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		</form>
		{* =========== КОМАНДЫ ============ *}
		<div id="cont_6">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Очки</a></td>
					<td width="17%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			{include file="championship/admin_championship_team.tpl"}
		</div>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="16%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_5');">Очки</a></td>
					<td width="17%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_6');">Команды</a></td>
					<td width="16%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>

			{include file="championship/admin_championship_gallery.tpl"}
		</div>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>

		<center>
		<b>Вкл/откл блока чемпионата слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $championship_settings_list.is_active_championship_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_championship_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество чемпионатов в блоке чемпионата слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$championship_settings_list.count_championship_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_championship_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество чемпионатов на одну страницу в разделе чемпионаты:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$championship_settings_list.count_championship_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_championship_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество бомбордиров по умолчанию для рейтинга:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$championship_settings_list.championship_count_stuff_rating}"></td>
					<td width="33%" align="center"><input type="submit" name="save_championship_count_stuff_rating" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="championship/admin_title_championship.tpl"}
		{include file="championship/admin_title_championship_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>

<script type="text/javascript">
// Сохранение активной вкладки
if (typeof showTextLang !== 'undefined') {
	var originalShowTextLang = showTextLang;
	showTextLang = function(tab) {
		originalShowTextLang(tab);
		var tabField = document.getElementById('active_tab');
		if (tabField) {
			tabField.value = tab;
		}
	};
}

// Восстановление активной вкладки после загрузки
window.addEventListener('DOMContentLoaded', function() {
	var tabParam = '{$smarty.get.tab}';
	if (tabParam && typeof showTextLang !== 'undefined') {
		showTextLang(tabParam);
	}
});
</script>