<H1>Администраторы:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=admins">Список</a></td>
				<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=admins&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=admins&get=add">Добавить</a></td>
			</tr>
		</table>
	{if empty($smarty.get.get)}
		<h1>Список администраторов</h1>
		<center>
		{if $admins_list.admin and $admin_status == 'yes'}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="table_1">
			<tr>
				<th> Администраторы</th>
			</tr>
			<tr>
				<td>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_2">
					<tr><th>login</th><th>фамилия имя</th><th>e-mail</th><th>последний заход</th></tr>
				{foreach key=key item=item from=$admins_list.admin}
					<tr><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_login}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_f_name} {$item.a_name}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_email}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_date_time_last_login}</a></td></tr>
				{/foreach}
					</table>
				</td>
			</tr>
			</table>
			<br>
		{/if}
		{if $admins_list.editor}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="table_1">
			<tr>
				<th> Редакторы</th>
			</tr>
			<tr>
				<td>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_2">
					<tr><th>login</th><th>фамилия имя</th><th>e-mail</th><th>последний заход</th></tr>
				{foreach key=key item=item from=$admins_list.editor}
					<tr><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_login}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_f_name} {$item.a_name}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_email}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_date_time_last_login}</a></td></tr>
				{/foreach}
					</table>
				</td>
			</tr>
			</table>
			<br>
		{/if}
		{if $admins_list.other}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="table_1">
			<tr>
				<th> Остальные</th>
			</tr>
			<tr>
				<td>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_2">
					<tr><th>login</th><th>фамилия имя</th><th>e-mail</th><th>последний заход</th></tr>
				{foreach key=key item=item from=$admins_list.other}
					<tr><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_login}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_f_name} {$item.a_name}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_email}</a></td><td><a href="?show=admins&get=edit&item={$item.a_id}">{$item.a_date_time_last_login}</a></td></tr>
				{/foreach}
					</table>
				</td>
			</tr>
			</table>
		{/if}
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавление администратора</h1>
		<center>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
				<tr><th width="20%" align="center">Логин: </th><td width="80%"><input type="text" name="user_login" id="input100"></td></tr>
				<tr><th align="center">Пароль: </th><td><input type="Password" name="passwd" id="input100"></td></tr> 
				<tr><th align="center">Еще раз: </th><td><input type="Password" name="passwd_conf" id="input100"></td></tr>
				<tr><th align="center">Имя: </th><td><input type="text" name="a_name" id="input100"></td></tr> 
				<tr><th align="center">Отчество: </th><td><input type="text" name="a_o_name" id="input100"></td></tr>
				<tr><th align="center">Фамилия: </th><td><input type="text" name="a_f_name" id="input100"></td></tr> 
				<tr><th align="center">Почта:</th><td><input type="text" name="a_email" id="input100"></td></tr>
				<tr><td colspan="2" align="center"><br>
								<b>О себе:</b><br>
					<textarea name="a_about" rows="10" style="width: 100%;"></textarea>
				</td></tr>
				{if $admin_status == 'yes'}<tr><th width="20%" align="center">Администратор: </th><td width="80%"><input type="checkbox" name="is_admin"></td></tr>{/if}
				<tr><th width="20%" align="center">Редактор: </th><td width="80%"><input type="checkbox" name="is_publisher"></td></tr>
				<tr><th width="20%" align="center">Вкл./откл.: </th><td width="80%"><input type="checkbox" name="a_is_active"></td></tr>
				<tr><td align="center" colspan="2">
				<br>
					<input type="submit" name="add_new_admin" id="submitsave" value="Сохранить">
				</td></tr>        
			</form>
			</table>
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'edit' and $smarty.get.item >0}
		<h1>Редактирование настроек администратора</h1>
		
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.action == ''}active_left{else}notactive{/if}"><a href="?show=admins&get=edit&item={$smarty.get.item}">Общие данные</a></td>
				<td width="34%" id="{if $smarty.get.action == 'password'}active{else}notactive{/if}"><a href="?show=admins&get=edit&item={$smarty.get.item}&action=password">Изменить пароль</a></td>
				<td width="33%" id="{if $smarty.get.action == 'rights'}active_right{else}notactive{/if}"><a href="?show=admins&get=edit&item={$smarty.get.item}&action=rights">Права доступа</a></td>
			</tr>
		</table>
		<br>
		{if $smarty.get.action == ''}
		<center>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
				<input type="hidden" name="a_id" value="{$admin_item.a_id}">
				<tr><th width="20%" align="center">Вкл./откл.: </th><td width="80%"><input type="checkbox" name="a_is_active"{if $admin_item.a_is_active == 'yes'}checked{/if}></td></tr>
				<tr><th width="20%" align="center" colspan="2">&nbsp;</th></tr>
				<tr><th width="20%" align="center">Логин: </th><td width="80%"><b>{$admin_item.a_login}</b></td></tr>
				<tr><th align="center">Имя: </th><td><input type="text" name="a_name" value="{$admin_item.a_name}" id="input100"></td></tr> 
				<tr><th align="center">Отчество: </th><td><input type="text" name="a_o_name" value="{$admin_item.a_o_name}" id="input100"></td></tr>
				<tr><th align="center">Фамилия: </th><td><input type="text" name="a_f_name" value="{$admin_item.a_f_name}" id="input100"></td></tr> 
				<tr><th align="center">Почта:</th><td><input type="text" name="a_email" value="{$admin_item.a_email}" id="input100"></td></tr>
				<tr><td colspan="2" align="center"><br>
								<b>О себе:</b><br>
					<textarea name="a_about" rows="10" style="width: 100%;">{$admin_item.a_about}</textarea>
				</td></tr>
				<tr><td align="center" colspan="2">
				<br>
					<input type="submit" name="save_profile_changes" id="submitsave" value="Сохранить">
				</td></tr>        
			</form>
			</table>
			<br>
		</center>
		{/if}
		{if $smarty.get.action == 'password'}
		<center>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
				<input type="hidden" name="a_id" value="{$admin_item.a_id}">
				<tr><th width="20%" align="center">Старый пароль: </th><td width="80%"><input type="Password" name="old_passwd" id="input100"></td></tr>
				<tr><th align="center">Новый пароль: </th><td><input type="Password" name="new_passwd" id="input100"></td></tr> 
				<tr><th align="center">Новый еще раз: </th><td><input type="Password" name="new_passwd_conf" id="input100"></td></tr>
				
				<tr><td align="center" colspan="2">
				<br>
					<input type="submit" name="save_password_changes" id="submitsave" value="Сохранить">
				</td></tr>        
			</form>
			</table>
			<br>
		</center>
		{/if}
		{if $smarty.get.action == 'rights'}
		<center>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
				<input type="hidden" name="a_id" value="{$admin_item.a_id}">
				{if $admin_status == 'yes'}<tr><th width="20%" align="center">Администратор: </th><td width="80%"><input type="checkbox" name="is_admin"{if $admin_item.a_admin_status == 'yes'}checked{/if}></td></tr>{/if}
				<tr><th width="20%" align="center">Редактор: </th><td width="80%"><input type="checkbox" name="is_publisher"{if $admin_item.a_publisher_status == 'yes'}checked{/if}></td></tr>
				<tr><td align="center" colspan="2">
				<br>
					<input type="submit" name="save_rights_changes" id="submitsave" value="Сохранить">
				</td></tr>        
			</form>
			</table>
			<br>
		</center>
		{/if}
	{/if}
	<br>
	</div>