<H1>Обратная связь:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=feedback">Список сообщений</a></td>
				<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=feedback&get=edit&item={$smarty.get.item}">Просмотр сообщения</a>{else}Просмотр сообщения{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=feedback&get=settings">Настройки</a></td>
			</tr>
		</table>
	
	{if empty($smarty.get.get)}
		<h1>Список сообщений</h1>
		
		
			<table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<td align="center" colspan="6">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=feedback&fbc='+this.value">
						<option value="all"{if $smarty.get.fbc == 'all'} selected{/if}>все</option>
						<option value="mail"{if $smarty.get.fbc == 'mail'} selected{/if}>сообщения</option>
						<option value="none"{if $smarty.get.fbc == 'none'} selected{/if}>без типа</option>
					</select>
					<br><br>
				</td>
			</tr>
			{if $feedback_list}
			{foreach key=key item=item from=$feedback_list name=feedback}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
					<td><a href="?show=feedback&get=edit&item={$item.fb_id}" title="{$item.fb_ip}">{$item.fb_name} &lt;{$item.fb_email}&gt;</a></td>
					<td><a href="?show=feedback&get=edit&item={$item.fb_id}">{if $item.fb_is_viewed == 'no'}<b>{/if}{if $item.fb_title == ''}<font style="color: #f00;">Без названия</font>{else}{$item.fb_title}{/if}{if $item.fb_is_viewed == 'no'}</b>{/if}</a></td>
					<td><nobr>{$item.fb_datetime_add|date_format:"%d.%m.%Y %H:%M"}&nbsp;&nbsp;&nbsp;</nobr></td>
					<td>{$item.fb_type}</td>
					<td>{if $item.fb_response != ''}<img src="images/mail_response.gif" alt="ответ: {$item.fb_datetime_response}" title="ответ: {$item.fb_datetime_response}" border="0">{/if}</td>
					<td>{if $item.fb_is_posted == 'yes'}<img src="images/mail_posted.gif" alt="переслано" title="переслано" border="0">{/if}</td>
					<td>{if $item.fb_is_viewed == 'no'}<img src="images/mail_close.gif" alt="не просматривалось" title="не просматривалось" border="0">{else}<img src="images/mail_open.gif" alt="просмотр: {$item.fb_datetime_viewed}" title="просмотр: {$item.fb_datetime_viewed}" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			{if $feedback_pages|@count >1}
				<div class="pages"><i>Страницы: </i>
					{foreach key=key item=item from=$feedback_pages}
						{if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=feedback&page={$key}">{$item}</a>{/if}
					{/foreach}
				</div>
			{/if}
		
			<br>
		
	{/if}
	{if $smarty.get.get == 'edit' and $smarty.get.item>0}
		<h1>Просмотр сообщения</h1>
		<center>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="fb_id" value="{$feedback_item.fb_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="right">прислано:&nbsp;</th>
							<td width="50%" align="left">&nbsp;{$feedback_item.fb_datetime_add}</td>
						</tr>
						<tr>
							<th width="50%" align="right">тип:&nbsp;</th>
							<td width="50%" align="left">&nbsp;{$feedback_item.fb_type}</td>
						</tr>
						<tr>
							<th width="50%" align="right">просмотрено:&nbsp;</th>
							<td width="50%" align="left">&nbsp;{$feedback_item.fb_datetime_viewed}</td>
						</tr>
						<tr>
							<th width="50%" align="right">ответ набран:&nbsp;</th>
							<td width="50%" align="left">&nbsp;{if $feedback_item.fb_datetime_response != '0000-00-00 00:00:00'}{$feedback_item.fb_datetime_response}{/if}</td>
						</tr>
						<tr>
							<th width="50%" align="right">ответ отослан:&nbsp;</th>
							<td width="50%" align="left">&nbsp;{if $feedback_item.fb_is_posted == 'yes'}да{else}нет{/if}</td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_feedback_changes" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_feedback" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="48%"><b>имя: </b><input type="text" name="fb_name" value="{$feedback_item.fb_name}" id="input100"></td>
					<th width="4%" align="center"></th>
					<td width="48%"><b>email: </b><input type="text" name="fb_email" value="{$feedback_item.fb_email}" id="input100"></td>
				</tr>
				<tr>
					<td colspan="3" width="100%">
						<b>тема:</b> <br> 
						<input type="text" name="fb_title" value="{$feedback_item.fb_title}" id="input100">
					</td>
				</tr>
				<tr>
					<td colspan="3" width="100%">
						<b>текст:</b> <br> 
						<textarea name="fb_text" rows="20" style="width: 100%;" class="mceNoEditor" id="input100">{$feedback_item.fb_text}</textarea>
					</td>
				</tr>
			</table>
		</form>
		<br>
		<h1>Написать ответ:</h1>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="fb_id" value="{$feedback_item.fb_id}">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">
						<b>отправить на email</b>
					</td>
					<td width="34%">
						{$feedback_item.fb_name} &lt;{$feedback_item.fb_email}&gt;
					</td>
					<th width="33%">
						<input type="checkbox" name="fb_is_posted">
					</th>
				</tr>
				<tr>
					<td colspan="3" width="100%" height="10">
					</td>
				</tr>
				<tr>
					<td colspan="3" width="100%">
						<b>текст:</b> <br> 
						<textarea name="fb_response" rows="5" style="width: 100%;" class="mceNoEditor" id="input100">{$feedback_item.fb_response}</textarea>
					</td>
				</tr>
				<tr>
					<th colspan="3" width="100%">
						<input type="submit" name="send_feedback_response" id="submitsave" value="Отправить">
					</th>
				</tr>
			</table>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'settings' or $smarty.get.get == 'letters'}
		<h1>Настройки</h1>
		
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="2" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="50%" id="{if $smarty.get.get == 'settings'}active_left{else}notactive{/if}"><a href="?show=feedback&get=settings">Общие настройки</a></td>
				<td width="50%" id="{if $smarty.get.get == 'letters'}active_right{else}notactive{/if}"><a href="?show=feedback&get=letters">Шаблоны писем</a></td>
			</tr>
		</table>
		<br>
		<br>
		
		{if $smarty.get.get == 'settings'}{include file="feedback/admin_feedback_settings.tpl"}{/if}
		{if $smarty.get.get == 'letters'}{include file="feedback/admin_feedback_letters.tpl"}{/if}
		
		<br>
	{/if}
	</div>