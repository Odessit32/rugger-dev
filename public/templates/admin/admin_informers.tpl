<H1>Информеры:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get) or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}active_left{else}notactive{/if}"><a href="?show=informers">Информеры</a></td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=informers&get=settings">Настройки</a></td>
			</tr>
		</table>
		
	{if empty($smarty.get.get) or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}
		<h1>Информеры</h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=informers">Список</a></td>
				<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.item)}<a href="?show=informers&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=informers&get=add">Добавить</a></td>
			</tr>
		</table>
	{/if}
	{if empty($smarty.get.get) }
		<h1>Список информеров</h1>
		<center>
		{if !empty($informers_list)}
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
			{foreach key=key item=item from=$informers_list name=informers}
				<div class="image_item">
					<a href="?show=informers&get=edit&item={$item.i_id}"><img src="images/informer_thumb_{$item.i_id}.jpg" width="166"></a>
					<input type="Text" value="{$item.i_title_ru}" id="input_item_image" onfocus="this.select();">
				</div>
			{/foreach}
			</td></tr>
		</table>
		{/if}
			<br>
		</center>
	{/if}
		<center>
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавление информера</h1>
		<center>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
				<tr>
					<td>&nbsp;</td>
					<th>Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="i_is_active" checked>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Показывать всё время Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="i_is_datetime" checked>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th colspan="2">Показывать только в промежуток: </th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<b>с: </b>
							<select name="i_date_from_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="i_date_from_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="i_date_from_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
					</td>
					<td>
						<b>до: </b>
							<select name="i_date_to_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="i_date_to_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="i_date_to_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<td width="5%">&nbsp;</td>
					<th width="45%" align="center">Файл-шаблон: </th>
					<td width="45%"><input type="text" name="i_path" id="input100"></td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td width="5%">&nbsp;</td>
					<th width="45%" align="center">Файл-класс: </th>
					<td width="45%"><input type="text" name="i_class" id="input100"></td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>NOINDEX Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="i_noindex" checked>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th align="center">Порядок: </th>
					<td><input type="text" name="i_order" id="input100"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
                    <td colspan="4" align="center">
                        Код:<br>
                        <textarea name="i_code" rows="5" style="width: 80%;" class="mceNoEditor"></textarea>
                    </td>
                </tr>
				<tr><td colspan="4" align="center">
					<br>
					<div id="lang_1">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Название (рус):</b><br>
					<input type="text" name="i_title_ru" id="input50"><br>
                    <b>Описание (рус):</b>
                    <br><textarea name="i_description_ru" rows="5" style="width: 100%;"></textarea>
					</div>
					<div id="lang_2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Название (укр):</b><br>
					<input type="text" name="i_title_ua" id="input50"><br>
                    <b>Описание (укр):</b>
                    <br><textarea name="i_description_ua" rows="5" style="width: 100%;"></textarea>
					</div>
					<div id="lang_3">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Название (eng):</b><br>
					<input type="text" name="i_title_en" id="input50"><br>
                    <b>Описание (eng):</b>
                    <br><textarea name="i_description_en" rows="5" style="width: 100%;"></textarea>
					</div>
				</td></tr>
				<tr><td align="center" colspan="4">
				<br>
					<input type="submit" name="add_new_informer" id="submitsave" value="Добавить">
				</td></tr>        
			</form>
			</table>
			<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}
		<h1>Редактирование информера</h1>
		<center>
		<table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
		<tr>
			<td valign="top" align="center"><img src="images/informer_thumb_{$informer_item.i_id}.jpg" border="0" alt="{$informer_item.i_title_ru}" style="max-width: 300px;"></td>
			<td valign="top" align="center">
			<div id="conteiner" style="margin: 10px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="i_id" value="{$informer_item.i_id}">
				<tr>
					<td>&nbsp;</td>
					<th>Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="i_is_active"{if $informer_item.i_is_active == 'yes'} checked{/if}>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Показывать всё время Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="i_is_datetime"{if $informer_item.i_is_datetime == 'yes'} checked{/if}>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th colspan="2">Показывать только в промежуток: </th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<b>с: </b>
							<select name="i_date_from_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $informer_item.i_datetime_from|date_format:"%d"} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="i_date_from_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $informer_item.i_datetime_from|date_format:"%m"} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="i_date_from_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.section.year.index == $informer_item.i_datetime_from|date_format:"%Y"} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
					</td>
					<td>
						<b>до: </b>
							<select name="i_date_to_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $informer_item.i_datetime_to|date_format:"%d"} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="i_date_to_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $informer_item.i_datetime_to|date_format:"%m"} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="i_date_to_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.section.year.index == $informer_item.i_datetime_to|date_format:"%Y"} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" height="10"></td>
				</tr>
				<tr>
					<td width="5%">&nbsp;</td>
					<th width="50%" align="center">Файл-шаблон: </th>
					<td width="50%"><input type="text" name="i_path" value="{$informer_item.i_path}" id="input100"></td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td width="5%">&nbsp;</td>
					<th width="45%" align="center">Файл-класс: </th>
					<td width="45%"><input type="text" name="i_class" value="{$informer_item.i_class}" id="input100"></td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>NOINDEX Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="i_noindex"{if !empty($informer_item.i_noindex) && $informer_item.i_noindex == 'yes'} checked{/if}>
					</td>
					<td>&nbsp;</td>
				</tr>
                <tr>
                    <td colspan="4" align="center">
                        Код:<br>
                        <textarea name="i_code" rows="5" style="width: 80%;" class="mceNoEditor">{$informer_item.i_code}</textarea>
                    </td>
                </tr>
				<tr><td colspan="4" align="center">
					<br>
					<div id="lang_1">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Название (рус):</b><br>
					<input type="text" name="i_title_ru" value="{$informer_item.i_title_ru}" id="input50"><br>
                    <b>Описание (рус):</b>
                    <br><textarea name="i_description_ru" rows="5" style="width: 100%;">{$informer_item.i_description_ru}</textarea>
					</div>
					<div id="lang_2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Название (укр):</b><br>
					<input type="text" name="i_title_ua" value="{$informer_item.i_title_ua}" id="input50"><br>
                    <b>Описание (укр):</b>
                    <br><textarea name="i_description_ua" rows="5" style="width: 100%;">{$informer_item.i_description_ua}</textarea>
					</div>
					<div id="lang_3">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Название (eng):</b><br>
					<input type="text" name="i_title_en" value="{$informer_item.i_title_en}" id="input50"><br>
                    <b>Описание (eng):</b>
                    <br><textarea name="i_description_en" rows="5" style="width: 100%;">{$informer_item.i_description_en}</textarea>
					</div>
				</td></tr>
				<tr>
					<td></td>
					<td align="center">
						<br>
						<input type="submit" name="save_edited_informer" id="submitsave" value="Сохранить">
					</td>
					<td>
						<br>
						<input type="submit" name="delete_edited_informer" id="submitdelete" value="Удалить">
					</td>
					<td></td>
				</tr>
			</form>
			</table>
			<br>
			</div>
			<div id="conteiner" style="margin: 10px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="i_id" value="{$informer_item.i_id}">
				<tr>
					<td>&nbsp;</td>
					<th colspan="2"><br>Добавление информера на страницы<br><br></th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Список страниц для размещения: </th>
					<th>Сторона страницы (общая настройка для всех выбранных страниц)</th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width="5%">&nbsp;</td>
					<td width="45%" align="center" valign="top">
						<select multiple name="page_id[]" size="10" id="input100">
							<option value="0"{if $informer_page_list.0} selected{/if}>везде{if $informer_page_list.0.pbi_place == 'left'}&nbsp;&nbsp;&nbsp;(слева){/if}{if $informer_page_list.0.pbi_place == 'right'}&nbsp;&nbsp;&nbsp;(справа){/if}{if $informer_page_list.0.pbi_place == 'down'}&nbsp;&nbsp;&nbsp;(внизу){/if}</option>
							{foreach key=key item=item from=$pages_parent}{assign var="page_id" value=$item.p_id}<option value="{$item.p_id}"{if $informer_page_list.$page_id} selected{/if}>{section name = mySection start = 0 loop = $item.nesting}../{/section}{$item.p_title_ru}{if $informer_page_list.$page_id.pbi_place == 'left'}&nbsp;&nbsp;&nbsp;(слева){/if}{if $informer_page_list.$page_id.pbi_place == 'right'}&nbsp;&nbsp;&nbsp;(справа){/if}{if $informer_page_list.$page_id.pbi_place == 'down'}&nbsp;&nbsp;&nbsp;(внизу){/if}{if $informer_page_list.$page_id.pbi_place == 'center'}&nbsp;&nbsp;&nbsp;(по центру){/if}</option>
							{/foreach}
						</select>
					</td>
					<td width="45%" align="center" valign="top">
						<select name="place" id="input50">
							<option value="left">слева</option>
							<option value="right">справа</option>
							<option value="down">внизу</option>
							<option value="center">по центру</option>
						</select>
					</td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td></td>
					<td align="center" colspan="2">
						<br>
						<input type="submit" name="save_edited_informer_pages" id="submitsave" value="Сохранить">
					</td>
					<td></td>
				</tr>
			</form>
			</table>
			<br>
			</div>
			</td>
		</tr>
		</table>
			<br>
		</center>
	{/if}
	
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>
		
	{/if}
	
	
	
	
	<br>
	</div>