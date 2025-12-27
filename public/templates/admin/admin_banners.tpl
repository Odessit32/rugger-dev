<H1>Баннеры:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get) or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}active_left{else}notactive{/if}"><a href="?show=banners">Баннеры</a></td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=banners&get=settings">Настройки</a></td>
			</tr>
		</table>
		
	{if empty($smarty.get.get) or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}
		<h1>Баннеры</h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=banners">Список</a></td>
				<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.item)}<a href="?show=banners&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=banners&get=add">Добавить</a></td>
			</tr>
		</table>
	{/if}
	{if empty($smarty.get.get) }
		<h1>Список баннеров</h1>
		<center>
		{if $banners_list}
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
			{foreach key=key item=item from=$banners_list name=banners}
				<div class="image_item">
					<a href="?show=banners&get=edit&item={$item.b_id}"><img src="../upload/banners{$item.b_path}" width="166"></a>
					<input type="Text" value="{$item.b_url}" id="input_item_image" onfocus="this.select();">
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
		<h1>Добавление баннера</h1>
		<center>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" enctype="multipart/form-data">
				<tr>
					<td>&nbsp;</td>
					<th>тип баннера: </th>
					<td>
						<select name="b_type">
							<option value="image">Картинка</option>
							<option value="code">Код</option>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_is_active" checked>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Показывать всё время Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_is_datetime" checked>
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
							<select name="b_date_from_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="b_date_from_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="b_date_from_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
					</td>
					<td>
						<b>до: </b>
							<select name="b_date_to_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="b_date_to_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="b_date_to_year">
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
					<th width="45%" align="center">Файл: </th>
					<td width="45%"><input type="File" name="file_banner" id="input100"></td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th align="center">Ссылка: </th>
					<td><input type="text" name="b_url" id="input100"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Открывать в новом окне Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_target" checked>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>NOINDEX Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_noindex" checked>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th align="center">Порядок: </th>
					<td><input type="text" name="b_order" id="input100"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th align="center">Код: </th>
					<td><textarea name="b_code" class="mceNoEditor" id="input100"></textarea style="height: 150px;"></td>
					<td>&nbsp;</td>
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
					<b>Описание (рус):</b><br>
					<input type="text" name="b_title_ru" id="input50">
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
					<b>Описание (укр):</b><br>
					<input type="text" name="b_title_ua" id="input50">
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
					<b>Описание (eng):</b><br>
					<input type="text" name="b_title_en" id="input50">
					</div>
				</td></tr>
				<tr><td align="center" colspan="4">
				<br>
					<input type="submit" name="add_new_banner" id="submitsave" value="Добавить">
				</td></tr>        
			</form>
			</table>
			<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}
		<h1>Редактирование баннера</h1>
		<center>
		<table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
		<tr>
			<td valign="top" align="center"><a href="{$banner_item.b_url}"{if $banner_item.b_target == 'yes'} target="_blank"{/if} title="{$banner_item.b_title_ru}"><img src="../upload/banners{$banner_item.b_path}" border="0" alt="{$banner_item.b_title_ru}"></a></td>
			<td valign="top" align="center">
			<div id="conteiner" style="margin: 10px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="b_id" value="{$banner_item.b_id}">
				<tr>
					<td>&nbsp;</td>
					<th>тип баннера: </th>
					<td>
						<select name="b_type">
							<option value="image"{if $banner_item.b_type == 'image'} selected{/if}>Картинка</option>
							<option value="code"{if $banner_item.b_type == 'code'} selected{/if}>Код</option>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_is_active"{if $banner_item.b_is_active == 'yes'} checked{/if}>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Показывать всё время Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_is_datetime"{if $banner_item.b_is_datetime == 'yes'} checked{/if}>
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
							<select name="b_date_from_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $banner_item.b_datetime_from|date_format:"%d"} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="b_date_from_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $banner_item.b_datetime_from|date_format:"%m"} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="b_date_from_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.section.year.index == $banner_item.b_datetime_from|date_format:"%Y"} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
					</td>
					<td>
						<b>до: </b>
							<select name="b_date_to_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $banner_item.b_datetime_to|date_format:"%d"} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name="b_date_to_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $banner_item.b_datetime_to|date_format:"%m"} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name="b_date_to_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.section.year.index == $banner_item.b_datetime_to|date_format:"%Y"} selected{/if}>{$smarty.section.year.index}</option>
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
					<th width="50%" align="center">Файл: </th>
					<td width="50%"><input type="File" name="file_banner" id="input100"></td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th align="center">Ссылка: </th>
					<td><input type="text" name="b_url" value="{$banner_item.b_url}" id="input100"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>Открывать в новом окне Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_target"{if $banner_item.b_target == 'yes'} checked{/if}>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th>NOINDEX Вкл./Откл.: </th>
					<td>
						<input type="checkbox" name="b_noindex"{if $banner_item.b_noindex == 'yes'} checked{/if}>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<th align="center">Код: </th>
					<td><textarea name="b_code" class="mceNoEditor" id="input100">{$banner_item.b_code}</textarea style="height: 150px;"></td>
					<td>&nbsp;</td>
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
					<b>Описание (рус):</b><br>
					<input type="text" name="b_title_ru" value="{$banner_item.b_title_ru}" id="input50">
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
					<b>Описание (укр):</b><br>
					<input type="text" name="b_title_ua" value="{$banner_item.b_title_ua}" id="input50">
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
					<b>Описание (eng):</b><br>
					<input type="text" name="b_title_en" value="{$banner_item.b_title_en}" id="input50">
					</div>
				</td></tr>
				<tr>
					<td></td>
					<td align="center">
						<br>
						<input type="submit" name="save_edited_banner" id="submitsave" value="Сохранить">
					</td>
					<td>
						<br>
						<input type="submit" name="delete_edited_banner" id="submitdelete" value="Удалить">
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
				<input type="Hidden" name="b_id" value="{$banner_item.b_id}">
				<tr>
					<td>&nbsp;</td>
					<th colspan="2"><br>Добавление баннера на страницы<br><br></th>
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
							<option value="0"{if $banner_page_list.0} selected{/if}>везде{if $banner_page_list.0.bp_place == 'left'}&nbsp;&nbsp;&nbsp;(слева){/if}{if $banner_page_list.0.bp_place == 'right'}&nbsp;&nbsp;&nbsp;(справа){/if}{if $banner_page_list.0.bp_place == 'down'}&nbsp;&nbsp;&nbsp;(внизу){/if}</option>
							{foreach key=key item=item from=$pages_parent}{assign var="page_id" value=$item.p_id}<option value="{$item.p_id}"{if $banner_page_list.$page_id} selected{/if}>{section name = mySection start = 0 loop = $item.nesting}../{/section}{$item.p_title_ru}{if $banner_page_list.$page_id.bp_place == 'left'}&nbsp;&nbsp;&nbsp;(слева){/if}{if $banner_page_list.$page_id.bp_place == 'right'}&nbsp;&nbsp;&nbsp;(справа){/if}{if $banner_page_list.$page_id.bp_place == 'down'}&nbsp;&nbsp;&nbsp;(внизу){/if}</option>
							{/foreach}
						</select>
					</td>
					<td width="45%" align="center" valign="top">
						<b>место</b><br>
						<select name="pbi_place" id="input50">
							<option value="left">в лево</option>
							<option value="right">в право</option>
						</select>
						<br><b>показывать</b><br>
						<select name="pbi_display_type" id="input50">
							<option value="fixed">постоянно</option>
							<option value="shuffle">вперемешку</option>
						</select>
						<br><b>порядок</b><br>
						<input type="text" name="pbi_order" size="3" id="input"/>
					</td>
					<td width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td></td>
					<td align="center" colspan="2">
						<br>
						<input type="submit" name="save_edited_banner_pages" id="submitsave" value="Сохранить">
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