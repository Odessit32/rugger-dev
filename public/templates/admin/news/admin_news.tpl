<H1>Новости:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="20%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=news{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">Список новостей</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=news&get=edit&item={$smarty.get.item}">Редактировать новость</a>{else}Редактировать новость{/if}</td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=news&get=add">Добавить новость</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' || $smarty.get.get == 'categedit' || $smarty.get.get == 'categadd')}active{else}notactive{/if}"><a href="?show=news&get=categories">Рубрики</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=news&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd')}
		{include file="news/admin_news_categories.tpl"}
	{/if}

	{if empty($smarty.get.get)}
		<h1>Список новостей</h1>


			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<td align="center" colspan="6">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=news&nnc='+this.value">
							<option value="all"{if !empty($smarty.get.nnc) && $smarty.get.nnc == 'all'} selected{/if}>все</option>
						{if $news_categories_list}
						{foreach key=key item=item from=$news_categories_list name=nc}
							<option value="{$item.nc_id}"{if !empty($smarty.get.nnc) && $smarty.get.nnc == $item.nc_id} selected{/if}>{$item.nc_title_ru}</option>
						{/foreach}
						{/if}
					</select>
					<br><br>
				</td>
			</tr>
			{if $news_list}
			{foreach key=key item=item from=$news_list name=news}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
					<td><a href="?show=news&get=edit&item={$item.id}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">{if $item.title == ''}<font style="color: #f00;">Без названия</font>{else}{$item.title}{/if}</a></td>
					<td><nobr>{$item.n_date_show|date_format:"%d.%m.%Y %H:%M"}&nbsp;&nbsp;</nobr></td>
					<td><nobr>{assign var="nc_id" value=$item.n_nc_id}{$news_categories_list_id.$nc_id.nc_title_ru}</nobr></td>
					<td width="16" align="center">{if $item.n_top>0}<img src="images/top_{$item.n_top}.gif" alt="top {$item.n_top}" border="0">{/if}</td>
					<td width="16">{if $item.n_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
					<td width="16">{if $item.n_export_id != ''}<img src="images/import_yes.gif" alt="{$item.n_export_id}" border="0">{/if}</td>
					<td width="16">{if $item.n_is_export == 'no'}<img src="images/export_no.gif" alt="откл" border="0">{else}<img src="images/export_yes.gif" alt="вкл" border="0">{/if}</td>
					<td width="16">{if $item.n_is_export_vk == 'no'}<img src="images/export_no.gif" alt="откл" border="0">{else}<img src="images/export_yes.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			{if $news_pages|@count >1}
				<div class="pages"><i>Страницы: </i>
					{foreach key=key item=item from=$news_pages}
						{if !empty($smarty.get.page) && $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=news&page={$key}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}">{$item}</a>{/if}
					{/foreach}
				</div>
			{/if}

			<br>

	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить новость</h1>
		<center>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="n_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главные новости: </th>
							<td width="50%" align="center">
								<select name="n_top" id="input100">
									<option value="0">----------</option>
									<option value="1" >позиция №1</option>
									<option value="2" >позиция №2</option>
									<option value="3" >позиция №3</option>
									<option value="4" >позиция №4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главное фото: </th>
							<td width="50%" align="center"><input type="Checkbox" name="n_is_photo_top" checked="checked"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center"><nobr>
								<select name="n_date_day" id="input">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="n_date_month" id="input">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="n_date_year" id="input">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
								<select name="n_date_hour" id="input">
								{section name = hour start = 0 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="n_date_minute" id="input">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $smarty.now|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select></nobr>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">рубрика: </th>
							<td width="50%" align="center">
							{if $news_categories_list}
								<select name="n_nc_id" id="input100">
								{foreach key=key item=item from=$news_categories_list name=categories}
									<option value="{$item.nc_id}"{if !empty($news_item.n_nc_id) && $news_item.n_nc_id == $item.nc_id} selected{/if}>{$item.nc_title_ru}</option>
								{/foreach}
								</select>
							{else}
							<b>нет рубрик</b>
							{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
                        <tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Страны: </th>
							<td width="50%" align="center">
								<input id="country_auto_val" type="hidden" name="country_auto_val" vlue="">
                                {if $country_list}
                                    <select name="country_" id="country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                {else}
                                    <b>нет добавленных стран</b>
                                {/if}
							</td>
						</tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Турниры: </th>
                            <td width="50%" align="center">
                                <input id="champ_auto_val" type="hidden" name="champ_auto_val" vlue="">
                                {if $champ_list}
                                    <select name="champ_" id="champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Матч: </th>
                            <td width="50%" align="center">
                                <input type="text" name="game_date" value="{$smarty.now|date_format:"%d.%m.%Y"}" class="input50 input_date game_date">
                                <select name="game_id" class="game_list_by_date input100">
                                    <option value="">----------------</option>
                                </select>
                                <div class="div_100 game_list_added">
                                    <input id="games_auto_val" type="hidden" name="game_auto_val" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
							<tr>
								<th width="50%" align="center">Люди: </th>
								<td width="50%" align="center">
									<input id="staff_input_id" type="text" value="" placeholder="Начните писать имя" class="input100" style="text-align: left;"/>
									<div class="div_100 staff_list_added">
										<input id="staff_auto_val" type="hidden" name="staff_auto_val" value="">
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="top">
						<br><br><br><br><br>
						<input type="submit" name="add_new_news" id="submitsave" value="Добавить">

						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">экспорт: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="n_is_export"></td>
                            </tr>
                            {*<tr>
                                <th width="50%" align="center">экспорт VK.com: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="n_is_export_vk"></td>
                            </tr>*}
                            <tr>
                                <th width="50%" align="center">&nbsp; </th>
                                <td width="50%" align="center"> </td>
                            </tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="25%" align="center">Источник: </th>
                            <td width="25%" align="center">
                                <input type="text" name="n_sign" id="input100">
                            </td>
                            <th width="25%" align="center">URL источника: </th>
                            <td width="25%" align="center">
                                <input type="text" name="n_sign_url" id="input100">
                            </td>
                        </tr>
					</table>
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
					<td width="25%" id="notactive"><input type="submit" name="add_new_news_gallery" class="submitsave tab" value="Галерея"></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="n_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="n_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="n_text_ru" style="width: 100%; height: 520px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><input type="submit" name="add_new_news_gallery" class="submitsave tab" value="Галерея"></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="n_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="n_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="n_text_ua" style="width: 100%; height: 520px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><input type="submit" name="add_new_news_gallery" class="submitsave tab" value="Галерея"></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="n_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="n_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="n_text_en" style="width: 100%; height: 520px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">

		</div>

		{* =========== ЧИТАЙТЕ ТАКЖЕ ============ *}
		<div id="related_news_block" style="margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: #fafafa;">
			<h3 style="margin: 0 0 15px 0;">Читайте также (связанные новости)</h3>
			<p style="color: #666; font-size: 12px; margin-bottom: 15px;">Начните вводить заголовок новости для поиска. Выбранные новости будут добавлены в блок "Читайте также" под текстом.</p>
			<table width="100%" cellspacing="0" cellpadding="5" border="0">
				<tr>
					<td width="30" valign="top"><b>1.</b></td>
					<td>
						<input type="text" class="related_news_input" data-index="1" placeholder="Введите заголовок новости..." style="width: 95%; padding: 5px;">
						<input type="hidden" name="related_news_id[1]" id="related_news_id_1" value="">
						<input type="hidden" name="related_news_url[1]" id="related_news_url_1" value="">
						<div id="related_news_selected_1" class="related_news_selected"></div>
					</td>
				</tr>
				<tr>
					<td width="30" valign="top"><b>2.</b></td>
					<td>
						<input type="text" class="related_news_input" data-index="2" placeholder="Введите заголовок новости..." style="width: 95%; padding: 5px;">
						<input type="hidden" name="related_news_id[2]" id="related_news_id_2" value="">
						<input type="hidden" name="related_news_url[2]" id="related_news_url_2" value="">
						<div id="related_news_selected_2" class="related_news_selected"></div>
					</td>
				</tr>
				<tr>
					<td width="30" valign="top"><b>3.</b></td>
					<td>
						<input type="text" class="related_news_input" data-index="3" placeholder="Введите заголовок новости..." style="width: 95%; padding: 5px;">
						<input type="hidden" name="related_news_id[3]" id="related_news_id_3" value="">
						<input type="hidden" name="related_news_url[3]" id="related_news_url_3" value="">
						<div id="related_news_selected_3" class="related_news_selected"></div>
					</td>
				</tr>
			</table>
			<div style="margin-top: 15px; text-align: center;">
				<input type="submit" name="add_new_news" id="submitsave" value="Добавить">
			</div>
		</div>

		</form>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && $smarty.get.item>0 && !empty($news_item)}
		<h1>Редактирование новости: &laquo;{$news_item.n_title_ru}&raquo;</h1>
		<center>
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false"{if !empty($smarty.get.g_get)}class="form_disabled"{/if}>
				<input type="Hidden" name="n_id" value="{$news_item.n_id}">
			<table width="99%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="n_is_active"{if $news_item.n_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главные новости: </th>
							<td width="50%" align="center">
								<select name="n_top" id="input100">
									<option value="0"{if $news_item.n_top == 0} selected{/if}>----------</option>
									<option value="1"{if $news_item.n_top == 1} selected{/if}>позиция №1</option>
									<option value="2"{if $news_item.n_top == 2} selected{/if}>позиция №2</option>
									<option value="3"{if $news_item.n_top == 3} selected{/if}>позиция №3</option>
									<option value="4"{if $news_item.n_top == 4} selected{/if}>позиция №4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Главное фото: </th>
							<td width="50%" align="center"><input type="Checkbox" name="n_is_photo_top"{if $news_item.n_is_photo_top == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center"><nobr>
								<select name="n_date_day" id="input">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $news_item.n_date_show|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="n_date_month" id="input">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $news_item.n_date_show|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="n_date_year" id="input">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $news_item.n_date_show|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>


								<select name="n_date_hour" id="input">
								{section name = hour start = 0 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $news_item.n_date_show|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="n_date_minute" id="input">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $news_item.n_date_show|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select></nobr>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">рубрика: </th>
							<td width="50%" align="center">
							{if $news_categories_list}
								<select name="n_nc_id" id="input100">
								{foreach key=key item=item from=$news_categories_list name=categories}
									<option value="{$item.nc_id}"{if !empty($news_item.n_nc_id) && $news_item.n_nc_id == $item.nc_id} selected{/if}>{$item.nc_title_ru}</option>
								{/foreach}
								</select>
							{else}
							<b>нет рубрик</b>
							{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="country_auto_val" type="hidden" name="country_auto_val" value="{$news_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $news_item.connection_country}
                                        {foreach item=item_cc from=$news_item.connection_country name=connection_country}
                                            <span class="selected_country" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных стран</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Турниры: </th>
                            <td width="50%" align="center">
                                <input id="champ_auto_val" type="hidden" name="champ_auto_val" value="{$news_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $news_item.connection_champ}
                                        {foreach item=item_cc from=$news_item.connection_champ name=connection_champ}
                                            <span class="selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Матч: </th>
                            <td width="50%" align="center">
                                <input type="text" name="game_date" value="{$smarty.now|date_format:"%d.%m.%Y"}" class="input50 input_date game_date">
                                <select name="game_id" class="game_list_by_date input100">
                                    <option value="">----------------</option>
                                    {if $news_item.connection_games}
                                        {foreach key=key item=item from=$news_item.connection_games name=cc}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                                <div class="div_100 game_list_added">
                                    <input id="games_auto_val" type="hidden" name="game_auto_val" value="{$news_item.games_auto_val}">
                                    {if $news_item.connected_games}
                                        {foreach key=key item=item from=$news_item.connected_games name=gla}
                                            <span class="selected_game_s" data-id="{$item.id}">{$item.title}</span>
                                        {/foreach}
                                    {/if}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
							<tr>
								<th width="50%" align="center">Люди: </th>
								<td width="50%" align="center">
									<input id="staff_input_id" type="text" value="" placeholder="Начните писать имя" class="input100" style="text-align: left;"/>
									<div class="div_100 staff_list_added">
										<input id="staff_auto_val" type="hidden" name="staff_auto_val" value="{if !empty($news_item.staff_auto_val)}{$news_item.staff_auto_val}{/if}">
										{if !empty($news_item.connected_staff)}
											{foreach key=key item=item from=$news_item.connected_staff name=gla}
												<span class="selected_staff_item" data-id="{$item.id}">{$item.title}</span>
											{/foreach}
										{/if}
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="top">
						<a href="{$sitepath}news/{$news_item.n_id}" target="_blank">Посмотреть новость</a>
						<br><br><br><br><br>
						<input type="submit" name="save_news_changes" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_news" id="submitdelete" value="Удалить">

						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">экспорт: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="n_is_export"{if $news_item.n_is_export == 'yes'} checked{/if}></td>
                            </tr>
                            {*<tr>
                                <th width="50%" align="center">экспорт VK.com: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="n_is_export_vk"{if $news_item.n_is_export_vk == 'yes'} checked{/if}></td>
                            </tr>*}
                            <tr>
                                <th width="50%" align="center">&nbsp; </th>
                                <td width="50%" align="center"> </td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">добавлено: </th>
                                <td width="50%" align="center">{$news_item.n_datetime_add}</td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">редактирование: </th>
                                <td width="50%" align="center">{$news_item.n_datetime_edit}</td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">сохранил: </th>
                                <td width="50%" align="center">{$authors[$news_item.n_author].f_name} {$authors[$news_item.n_author].name}</td>
                            </tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="25%" align="center">Источник: </th>
                            <td width="25%" align="center">
                                <input type="text" name="n_sign" value="{$news_item.n_sign}" id="input100">
                            </td>
                            <th width="25%" align="center">URL источника: </th>
                            <td width="25%" align="center">
                                <input type="text" name="n_sign_url" value="{$news_item.n_sign_url}" id="input100">
                            </td>
                        </tr>
					</table>
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
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="n_title_ru" value="{$news_item.n_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="n_description_ru" rows="5" style="width: 100%;">{$news_item.n_description_ru}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="n_text_ru" style="width: 100%; height: 520px;">{$news_item.n_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="n_title_ua" value="{$news_item.n_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="n_description_ua" rows="5" style="width: 100%;">{$news_item.n_description_ua}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="n_text_ua" style="width: 100%; height: 520px;">{$news_item.n_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="n_title_en" value="{$news_item.n_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="n_description_en" rows="5" style="width: 100%;">{$news_item.n_description_en}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="n_text_en" style="width: 100%; height: 520px;">{$news_item.n_text_en}</textarea></td></tr>
			</table>
		</div>

		{* =========== ЧИТАЙТЕ ТАКЖЕ ============ *}
		<div id="related_news_block" style="margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: #fafafa;">
			<h3 style="margin: 0 0 15px 0;">Читайте также (связанные новости)</h3>
			<p style="color: #666; font-size: 12px; margin-bottom: 15px;">Начните вводить заголовок новости для поиска. Выбранные новости будут добавлены в блок "Читайте также" под текстом.</p>
			<table width="100%" cellspacing="0" cellpadding="5" border="0">
				<tr>
					<td width="30" valign="top"><b>1.</b></td>
					<td>
						<input type="text" class="related_news_input" data-index="1" placeholder="Введите заголовок новости..." style="width: 95%; padding: 5px;{if !empty($news_item.related_news.1)} display:none;{/if}">
						<input type="hidden" name="related_news_id[1]" id="related_news_id_1" value="{$news_item.related_news.1.id}">
						<input type="hidden" name="related_news_url[1]" id="related_news_url_1" value="{$news_item.related_news.1.url}">
						<div id="related_news_selected_1" class="related_news_selected">
							{if !empty($news_item.related_news.1)}<span class="selected_related_news">{$news_item.related_news.1.title} <a href="javascript:void(0);" class="remove_related_news" data-index="1">[удалить]</a></span>{/if}
						</div>
					</td>
				</tr>
				<tr>
					<td width="30" valign="top"><b>2.</b></td>
					<td>
						<input type="text" class="related_news_input" data-index="2" placeholder="Введите заголовок новости..." style="width: 95%; padding: 5px;{if !empty($news_item.related_news.2)} display:none;{/if}">
						<input type="hidden" name="related_news_id[2]" id="related_news_id_2" value="{$news_item.related_news.2.id}">
						<input type="hidden" name="related_news_url[2]" id="related_news_url_2" value="{$news_item.related_news.2.url}">
						<div id="related_news_selected_2" class="related_news_selected">
							{if !empty($news_item.related_news.2)}<span class="selected_related_news">{$news_item.related_news.2.title} <a href="javascript:void(0);" class="remove_related_news" data-index="2">[удалить]</a></span>{/if}
						</div>
					</td>
				</tr>
				<tr>
					<td width="30" valign="top"><b>3.</b></td>
					<td>
						<input type="text" class="related_news_input" data-index="3" placeholder="Введите заголовок новости..." style="width: 95%; padding: 5px;{if !empty($news_item.related_news.3)} display:none;{/if}">
						<input type="hidden" name="related_news_id[3]" id="related_news_id_3" value="{$news_item.related_news.3.id}">
						<input type="hidden" name="related_news_url[3]" id="related_news_url_3" value="{$news_item.related_news.3.url}">
						<div id="related_news_selected_3" class="related_news_selected">
							{if !empty($news_item.related_news.3)}<span class="selected_related_news">{$news_item.related_news.3.title} <a href="javascript:void(0);" class="remove_related_news" data-index="3">[удалить]</a></span>{/if}
						</div>
					</td>
				</tr>
			</table>
			<div style="margin-top: 15px; text-align: center;">
				<input type="submit" name="save_news_changes" id="submitsave" value="Сохранить">
			</div>
		</div>

		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="?show=news&get=edit&item={$smarty.get.item}">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="?show=news&get=edit&item={$smarty.get.item}">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="?show=news&get=edit&item={$smarty.get.item}">ENG</a></td>{/if}
					<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>

			{include file="news/admin_news_gallery.tpl"}
		</div>

            {include file="admin_meta_seo.tpl" meta_seo_item_type="news" meta_seo_item_id="`$news_item.n_id`"}
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>

		<center>
		{*
		<b>Вкл/откл блока новостей слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $news_settings_list.is_active_news_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_news_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		*}
		<b>Количество новостей в блоке новостей на всех страницах:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$news_settings_list.count_news_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_news_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество новостей на одну страницу в разделе новостей:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$news_settings_list.count_news_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_news_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество новостей на первой странице в разделе новостей:</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$news_settings_list.count_news_page_index}"></td>
					<td width="33%" align="center"><input type="submit" name="save_news_index_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество знаков новости (меньше - список перейдет из левой колонки под новость):</b><br><br>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$news_settings_list.count_news_max_char}"></td>
					<td width="33%" align="center"><input type="submit" name="save_news_max_char" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="news/admin_title_news.tpl"}
		{include file="news/admin_title_news_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>