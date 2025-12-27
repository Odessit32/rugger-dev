<H1>Переадресации:</H1>

<div id="conteiner">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=redirects{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">Список переадресаций</a></td>
			<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=redirects&get=edit&item={$smarty.get.item}">Редактировать переадресацию</a>{else}Редактировать переадресацию{/if}</td>
			<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=redirects&get=add">Добавить переадресацию</a></td>
		</tr>
	</table>

	{if empty($smarty.get.get)}
		<h1>Список переадресаций</h1>
		<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input" style="margin: 0 auto; display: block;">
			<tr>
				<td align="center" colspan="2" style="width: 670px;">
					<form method="post">
						<input type="submit" name="update_redirects_file" id="submitsave" value="Обновить файл переадресайии">
					</form>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2" style="width: 670px;">
					Поиск: <input type="text" onchange="document.location.href='?show=redirects&nnc='+this.value" />
					<br><br>
				</td>
			</tr>
			{if $redirects_list}
				{foreach key=key item=item from=$redirects_list name=redirects}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
						<td><a href="?show=redirects&get=edit&item={$item.id}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">{$item.url} => {$item.redirect_url}</a></td>
						<td width="16">{if $item.is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
					</tr>
				{/foreach}
			{/if}
		</table>
		{if $redirects_pages|@count >1}
			<div class="pages"><i>Страницы: </i>
				{foreach key=key item=item from=$redirects_pages}
					{if !empty($smarty.get.page) && $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=redirects&page={$key}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}">{$item}</a>{/if}
				{/foreach}
			</div>
		{/if}
		<br>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить переадресацию</h1>
		<form method="post" style="margin: 0 auto; display: block;">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
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
								<th width="50%" align="center">регулярное выражение <br>вкл/откл: </th>
								<td width="50%" align="center"><input type="Checkbox" name="is_regexp"></td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_redirects" id="submitsave" value="Добавить">
					</td>
				</tr>
				<tr>
					<th align="center">URL:</th>
					<td><input type="text" name="url" id="input100"></td>
				</tr>
				<tr>
					<th align="center">переадресация на URL:</th>
					<td><input type="text" name="redirect_url" id="input100"></td>
				</tr>
			</table>
			<br>
		</form>
		<br>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && $smarty.get.item>0 && !empty($redirects_item)}
		<h1>Редактирование переадресации: &laquo;{$redirects_item.url}&raquo;</h1>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false"{if !empty($smarty.get.g_get)}class="form_disabled"{/if} style="margin: 0 auto; display: block;">
			<input type="Hidden" name="id" value="{$redirects_item.id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="50%" align="center">вкл/откл: </th>
								<td width="50%" align="center"><input type="checkbox" name="is_active"{if $redirects_item.is_active == 'yes'} checked{/if}></td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
							<tr>
								<th width="50%" align="center">регулярное выражение <br>вкл/откл: </th>
								<td width="50%" align="center"><input type="checkbox" name="is_regexp"{if $redirects_item.is_regexp == 'yes'} checked{/if}></td>
							</tr>
							<tr>
								<td colspan="2" height="10"></td>
							</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="top">
						<input type="submit" name="save_redirects_changes" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_redirects" id="submitdelete" value="Удалить">

						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
							<tr>
								<th width="50%" align="center">время добавления: </th>
								<td width="50%" align="center">{$redirects_item.datetime_add}</td>
							</tr>
							<tr>
								<th width="50%" align="center">пользователь<br> (последнее редактирование): </th>
								<td width="50%" align="center">{$authors[$redirects_item.author].f_name} {$authors[$redirects_item.author].name}</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th align="center">URL:</th>
					<td><input type="text" name="url" value="{$redirects_item.url}" id="input100"></td>
				</tr>
				<tr>
					<th align="center">переадресация на URL:</th>
					<td><input type="text" name="redirect_url" value="{$redirects_item.redirect_url}" id="input100"></td>
				</tr>
			</table>
			<br>
		</form>
		<br>
	{/if}
</div>