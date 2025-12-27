<H1>Live трансляции:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="20%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=live{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">Список</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=live&get=edit&item={$smarty.get.item}">Редактировать новость</a>{else}Редактировать{/if}</td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=live&get=add">Добавить</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd')}active{else}notactive{/if}"><a href="?show=live&get=categories">Рубрики</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=live&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd')}
		{include file="live/admin_live_categories.tpl"}
	{/if}
	
	{if empty($smarty.get.get)}
		<h1>Список Live трансляций</h1>
		
		
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<td align="center" colspan="6">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=live&nnc='+this.value">
							<option value="all"{if !empty($smarty.get.nnc) && $smarty.get.nnc == 'all'} selected{/if}>все</option>
						{if $live_categories_list}
						{foreach key=key item=item from=$live_categories_list name=nc}
							<option value="{$item.lc_id}"{if !empty($smarty.get.nnc) && $smarty.get.nnc == $item.lc_id} selected{/if}>{$item.lc_title_ru}</option>
						{/foreach}
						{/if}
					</select>
					<br><br>
				</td>
			</tr>
			{if $live_list}
			{foreach key=key item=item from=$live_list name=live}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
					<td><a href="?show=live&get=edit&item={$item.id}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">{if $item.title == ''}<font style="color: #f00;">Без названия</font>{else}{$item.title}{/if}</a></td>
					<td><nobr>{$item.l_date_show|date_format:"%d.%m.%Y %H:%M"}&nbsp;&nbsp;</nobr></td>
					<td><nobr>{assign var="lc_id" value=$item.l_lc_id}{$live_categories_list_id.$lc_id.lc_title_ru}</nobr></td>
					<td width="16" align="center">{if $item.l_top>0}<img src="images/top_{$item.l_top}.gif" alt="top {$item.l_top}" border="0">{/if}</td>
					<td width="16">{if $item.l_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
					<td width="16">{if $item.l_export_id != ''}<img src="images/import_yes.gif" alt="{$item.l_export_id}" border="0">{/if}</td>
					<td width="16">{if $item.l_is_export == 'no'}<img src="images/export_no.gif" alt="откл" border="0">{else}<img src="images/export_yes.gif" alt="вкл" border="0">{/if}</td>
					<td width="16">{if $item.l_is_export_vk == 'no'}<img src="images/export_no.gif" alt="откл" border="0">{else}<img src="images/export_yes.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			{if !empty($live_pages)}
				<div class="pages"><i>Страницы: </i>
					{foreach key=key item=item from=$live_pages}
						{if !empty($smarty.get.page) && $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=live&page={$key}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}">{$item}</a>{/if}
					{/foreach}
				</div>
			{/if}
		
			<br>
		
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить Live трансляцию</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">вкл/откл: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_active" checked></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">фотография внутри новости вверху: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_photo_top" checked="checked"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">дата: </th>
                                <td width="50%" align="center"><nobr>
                                        <select name="l_date_day" id="input">
                                            {section name = day start = 1 loop = 32}
                                                <option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                            {/section}
                                        </select>
                                        <select name="l_date_month" id="input">
                                            {section name = month start = 1 loop = 13}
                                                <option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                            {/section}
                                        </select>
                                        <select name="l_date_year" id="input">
                                            {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                            {assign var="now_year" value=$now_year+2}
                                            {section name = year start = 2009 loop = $now_year}
                                                <option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                            {/section}
                                        </select>

                                        <select name="l_date_hour" id="input">
                                            {section name = hour start = 0 loop = 24}
                                                <option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                            {/section}
                                        </select>
                                        <select name="l_date_minute" id="input">
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
                                    {if $live_categories_list}
                                        <select name="l_lc_id" id="input100">
                                            {foreach key=key item=item from=$live_categories_list name=categories}
                                                <option value="{$item.lc_id}">{$item.lc_title_ru}</option>
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
                                <th width="50%" align="center">теги: </th>
                                <td width="50%" align="center">
                                    <input type="text" name="l_tags" id="input100">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th align="center">адрес: </th>
                                <td><input type="text" name="l_address" value="" id="input100"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th align="center">Игра (матч): </th>
                                <td>
                                    <div class="div_30">Дата: </div>
                                    <div class="div_70">
                                        <input type="text" name="game_date" value="{$smarty.now|date_format:"%d.%m.%Y"}" class="input50 input_date game_date"><br>
                                    </div>
                                    <div class="div_30">Игра: </div>
                                    <div class="div_70">
                                        <select name="game_id" class="game_list_by_date input100">
                                            <option value="">----------------</option>
                                            {if !empty($live_item.connection_games) && $live_item.connection_games}
                                                {foreach key=key item=item from=$live_item.connection_games name=cc}
                                                    <option value="{$item.id}">{$item.title}</option>
                                                {/foreach}
                                            {/if}
                                        </select>
                                    </div>
                                    <div class="div_30">Добавлено: </div>
                                    <div class="div_100 game_list_added">
                                        <input id="games_auto_val" type="hidden" name="game_auto_val" value="">

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_live" id="submitsave" value="Добавить">
						
						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">экспорт: </th>
							<td width="50%" align="center"><input type="Checkbox" name="l_is_export" checked></td>
						</tr>
                            <tr>
                                <th width="50%" align="center">экспорт VK.com: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_export_vk"></td>
                            </tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="25%" align="center">подпись: </th>
							<td width="25%" align="center">
								<input type="text" name="l_sign" id="input100">
							</td>
                            <th width="25%" align="center">url к подписи: </th>
							<td width="25%" align="center">
								<input type="text" name="l_sign_url" id="input100">
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
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="l_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="l_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="l_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="l_title_ua" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="l_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="l_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="l_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="l_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="l_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' and $smarty.get.item>0}
		<h1>Редактирование новости: &laquo;{$live_item.l_title_ru}&raquo;</h1>
		<center>
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false"{if !empty($smarty.get.g_get)}class="form_disabled"{/if}>
				<input type="Hidden" name="l_id" value="{$live_item.l_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">вкл/откл: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_active"{if $live_item.l_is_active == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">фотография внутри новости вверху: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_photo_top"{if $live_item.l_is_photo_top == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">дата: </th>
                                <td width="50%" align="center"><nobr>
                                        <select name="l_date_day" id="input">
                                            {section name = day start = 1 loop = 32}
                                                <option value="{$smarty.section.day.index}"{if $live_item.l_date_show|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                            {/section}
                                        </select>
                                        <select name="l_date_month" id="input">
                                            {section name = month start = 1 loop = 13}
                                                <option value="{$smarty.section.month.index}"{if $live_item.l_date_show|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                            {/section}
                                        </select>
                                        <select name="l_date_year" id="input">
                                            {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                            {assign var="now_year" value=$now_year+2}
                                            {section name = year start = 2009 loop = $now_year}
                                                <option value="{$smarty.section.year.index}"{if $live_item.l_date_show|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                            {/section}
                                        </select>


                                        <select name="l_date_hour" id="input">
                                            {section name = hour start = 0 loop = 24}
                                                <option value="{$smarty.section.hour.index}"{if $live_item.l_date_show|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                            {/section}
                                        </select>
                                        <select name="l_date_minute" id="input">
                                            {section name = minute start = 0 loop = 60}
                                                <option value="{$smarty.section.minute.index}"{if $live_item.l_date_show|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
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
                                    {if $live_categories_list}
                                        <select name="l_lc_id" id="input100">
                                            {foreach key=key item=item from=$live_categories_list name=categories}
                                                <option value="{$item.lc_id}"{if $live_item.l_lc_id == $item.lc_id} selected{/if}>{$item.lc_title_ru}</option>
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
                                <th width="50%" align="center">теги: </th>
                                <td width="50%" align="center">
                                    <input type="text" name="l_tags" value="{$live_item.l_tags}" id="input100">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th align="center">адрес: </th>
                                <td><input type="text" name="l_address" value="{$live_item.l_address}" id="input100"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th align="center">Игра (матч): </th>
                                <td>
                                    <div class="div_30">Дата: </div>
                                    <div class="div_70">
                                        <input type="text" name="game_date" value="{$smarty.now|date_format:"%d.%m.%Y"}" class="input50 input_date game_date"><br>
                                    </div>
                                    <div class="div_30">Игра: </div>
                                    <div class="div_70">
                                        <select name="game_id" class="game_list_by_date input100">
                                            <option value="">----------------</option>
                                            {if $live_item.connection_games}
                                                {foreach key=key item=item from=$live_item.connection_games name=cc}
                                                    <option value="{$item.id}">{$item.title}</option>
                                                {/foreach}
                                            {/if}
                                        </select>
                                    </div>
                                    <div class="div_30">Добавлено: </div>
                                    <div class="div_100 game_list_added">
                                        <input id="games_auto_val" type="hidden" name="game_auto_val" value="{$live_item.games_auto_val}">
                                        {if $live_item.connected_games}
                                            {foreach key=key item=item from=$live_item.connected_games name=gla}
                                                <span class="selected_game_s" data-id="{$item.id}">{$item.title}</span>
                                            {/foreach}
                                        {/if}
                                    </div>
                                </td>
                            </tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_live_changes" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_live" id="submitdelete" value="Удалить">
						
						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">экспорт: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_export"{if $live_item.l_is_export == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">экспорт VK.com: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="l_is_export_vk"{if $live_item.l_is_export_vk == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">&nbsp; </th>
                                <td width="50%" align="center"> </td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">время добавления: </th>
                                <td width="50%" align="center">{$live_item.l_datetime_add}</td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">время добавления: </th>
                                <td width="50%" align="center">{$live_item.l_datetime_add}</td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">пользователь<br> (последнее редактирование): </th>
                                <td width="50%" align="center">{$authors[$live_item.l_author].f_name} {$authors[$live_item.l_author].name}</td>
                            </tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="25%" align="center">подпись: </th>
                            <td width="25%" align="center">
                                <input type="text" name="l_sign" value="{$live_item.l_sign}" id="input100">
                            </td>
                            <th width="25%" align="center">url к подписи: </th>
                            <td width="25%" align="center">
                                <input type="text" name="l_sign_url" value="{$live_item.l_sign_url}" id="input100">
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
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="l_title_ru" value="{$live_item.l_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="l_description_ru" rows="5" style="width: 100%;">{$live_item.l_description_ru}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="l_text_ru" style="width: 100%; height: 400px;">{$live_item.l_text_ru}</textarea></td></tr>
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
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="l_title_ua" value="{$live_item.l_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="l_description_ua" rows="5" style="width: 100%;">{$live_item.l_description_ua}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="l_text_ua" style="width: 100%; height: 400px;">{$live_item.l_text_ua}</textarea></td></tr>
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
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="l_title_en" value="{$live_item.l_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="l_description_en" rows="5" style="width: 100%;">{$live_item.l_description_en}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="l_text_en" style="width: 100%; height: 400px;">{$live_item.l_text_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="?show=live&get=edit&item={$smarty.get.item}">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="?show=live&get=edit&item={$smarty.get.item}">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="?show=live&get=edit&item={$smarty.get.item}">ENG</a></td>{/if}
					<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>
			
			{include file="live/admin_live_gallery.tpl"}
		</div>
            {include file="admin_meta_seo.tpl" meta_seo_item_type="live" meta_seo_item_id="`$live_item.l_id`"}
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>
		
		<center>
		{*
		<b>Вкл/откл блока новостей слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $live_settings_list.is_active_live_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_live_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		*}
		<b>Количество новостей в блоке новостей на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$live_settings_list.count_live_left}"></td>
					<td width="33%" align="center"><input type="submit" name="save_live_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество новостей на одну страницу в разделе новостей:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$live_settings_list.count_live_page}"></td>
					<td width="33%" align="center"><input type="submit" name="save_live_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество новостей на первой странице в разделе новостей:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$live_settings_list.count_live_page_index}"></td>
					<td width="33%" align="center"><input type="submit" name="save_live_index_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество знаков новости (меньше - список перейдет из левой колонки под новость):</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$live_settings_list.count_live_max_char}"></td>
					<td width="33%" align="center"><input type="submit" name="save_live_max_char" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="live/admin_title_live.tpl"}
		{include file="live/admin_title_live_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>