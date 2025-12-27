<H1>Уведомления:</H1>

<div id="conteiner">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=notifications{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">Список уведомлений</a></td>
			<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=notifications&get=edit&item={$smarty.get.item}">Редактировать уведомление</a>{else}Редактировать уведомление{/if}</td>
			<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=notifications&get=add">Добавить уведомление</a></td>
		</tr>
	</table>

	{if empty($smarty.get.get)}
		<h1>Список уведомлений</h1>


		<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">

			{if !empty($notification_list)}
				{foreach key=key item=item from=$notification_list name=notifications}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
						<td><a href="?show=notifications&get=edit&item={$item.id}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">{if $item.title == ''}<font style="color: #f00;">Без названия</font>{else}{$item.title}{/if}</a></td>
						<td><nobr>{$item.date_show_start|date_format:"%d.%m.%Y %H:%M"}&nbsp;&nbsp;</nobr></td>
						<td><nobr>{$item.date_show_finish|date_format:"%d.%m.%Y %H:%M"}&nbsp;&nbsp;</nobr></td>
						<td width="16">{if $item.is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
					</tr>
				{/foreach}
			{/if}
		</table>
		{if !empty($notification_pages) && $notification_pages|@count >1}
			<div class="pages"><i>Страницы: </i>
				{foreach key=key item=item from=$notification_pages}
					{if !empty($smarty.get.page) && $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=notifications&page={$key}">{$item}</a>{/if}
				{/foreach}
			</div>
		{/if}

		<br>

	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить уведомление</h1>
		<form method="post">
			<table width="99%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="50%" align="center">вкл/откл: </th>
								<td width="50%" align="center"><input type="Checkbox" name="is_active" checked></td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
							<tr>
								<th width="50%" align="center">дата начало: </th>
								<td width="50%" align="center">
									<input type="text" name="date_show_start" class="input100 input_datetime">
								</td>
							</tr>
							<tr>
								<th width="50%" align="center">дата конец: </th>
								<td width="50%" align="center">
									<input type="text" name="date_show_finish" class="input100 input_datetime">
								</td>
							</tr>
							<tr>
								<th width="50%" align="center">показать после (сек): </th>
								<td width="50%" align="center">
									<input type="text" name="time_show" class="input100" value="5">
								</td>
							</tr>
							<tr>
								<th width="50%" align="center">скрыть после (сек): </th>
								<td width="50%" align="center">
									<input type="text" name="time_hide" class="input100" value="15">
								</td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
							<tr>
								<th width="50%" align="center">цвет: </th>
								<td width="50%" align="center">
									<select name="type" class="input100">
										<option value="normal"{if !empty($notification_item.type) && $notification_item.type == 'normal'} selected{/if}>Обычный</option>
										<option value="error"{if !empty($notification_item.type) && $notification_item.type == 'error'} selected{/if}>Error</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_notification" id="submitsave" value="Добавить">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="25%" align="center">url: </th>
								<td width="75%" align="center">
									<input type="text" name="url" class="input100">
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
				<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (РУС): </th>
						<td width="80%"><input type="text" name="title_ru" class="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
				<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (УКР): </th>
						<td width="80%"><input type="text" name="title_ua" class="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
				<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (ENG): </th>
						<td width="80%"><input type="text" name="title_en" class="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
			<div id="cont_4">

			</div>
		</form>
		<br>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && $smarty.get.item>0 && !empty($notification_item)}
		<h1>Редактирование уведомления: &laquo;{$notification_item.title_ru}&raquo;</h1>

		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false"{if !empty($smarty.get.g_get)}class="form_disabled"{/if}>
			<input type="Hidden" name="id" value="{$notification_item.id}">
			<table width="99%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="50%" align="center">вкл/откл: </th>
								<td width="50%" align="center"><input type="Checkbox" name="is_active"{if $notification_item.is_active == 'yes'} checked{/if}></td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
							<tr>
								<th width="50%" align="center">дата начало: </th>
								<td width="50%" align="center">
									<input type="text" name="date_show_start" class="input100 input_datetime" value="{$notification_item.date_show_start}">
								</td>
							</tr>
							<tr>
								<th width="50%" align="center">дата конец: </th>
								<td width="50%" align="center">
									<input type="text" name="date_show_finish" class="input100 input_datetime" value="{$notification_item.date_show_finish}">
								</td>
							</tr>
							<tr>
								<th width="50%" align="center">показать после (сек): </th>
								<td width="50%" align="center">
									<input type="text" name="time_show" class="input100" value="{$notification_item.time_show}">
								</td>
							</tr>
							<tr>
								<th width="50%" align="center">скрыть после (сек): </th>
								<td width="50%" align="center">
									<input type="text" name="time_hide" class="input100" value="{$notification_item.time_hide}">
								</td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
							<tr>
								<th width="50%" align="center">цвет: </th>
								<td width="50%" align="center">
									<select name="type" class="input100">
										<option value="normal"{if !empty($notification_item.type) && $notification_item.type == 'normal'} selected{/if}>Обычный</option>
										<option value="error"{if !empty($notification_item.type) && $notification_item.type == 'error'} selected{/if}>Error</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="top">
						<a href="{$sitepath}notifications/{$notification_item.id}" target="_blank">Посмотреть уведомление</a>
						<br><br><br><br><br>
						<input type="submit" name="save_notification_changes" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_notification" id="submitdelete" value="Удалить">

						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="50%" align="center">&nbsp; </th>
								<td width="50%" align="center"> </td>
							</tr>
							<tr>
								<th width="50%" align="center">время добавления: </th>
								<td width="50%" align="center">{$notification_item.datetime_add}</td>
							</tr>
							<tr>
								<th width="50%" align="center">время добавления: </th>
								<td width="50%" align="center">{$notification_item.datetime_add}</td>
							</tr>
							<tr>
								<th width="50%" align="center">пользователь<br> (последнее редактирование): </th>
								<td width="50%" align="center">{$authors[$notification_item.author].f_name} {$authors[$notification_item.author].name}</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="25%" align="center">url: </th>
								<td width="75%" align="center">
									<input type="text" name="url" value="{$notification_item.url}" class="input100">
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
				<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (РУС): </th>
						<td width="80%"><input type="text" name="title_ru" value="{$notification_item.title_ru}" class="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="description_ru" rows="5" style="width: 100%;">{$notification_item.description_ru}</textarea></td></tr>
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
				<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (УКР): </th>
						<td width="80%"><input type="text" name="title_ua" value="{$notification_item.title_ua}" class="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="description_ua" rows="5" style="width: 100%;">{$notification_item.description_ua}</textarea></td></tr>
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
				<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (ENG): </th>
						<td width="80%"><input type="text" name="title_en" value="{$notification_item.title_en}" class="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="description_en" rows="5" style="width: 100%;">{$notification_item.description_en}</textarea></td></tr>
				</table>
			</div>
		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">

		</div>
	{/if}

</div>