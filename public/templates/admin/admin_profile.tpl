<H1>Профиль администратора:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=profile">Общие данные</a></td>
				<td width="50%" id="{if $smarty.get.get == 'password'}active_right{else}notactive{/if}"><a href="?show=profile&get=password">Изменить пароль</a></td>
			</tr>
		</table>
		
	{if empty($smarty.get.get)}
		<h1>Общие данные</h1>
		<center>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
				<tr><th width="20%" align="center">Логин: </th><td width="80%"><input type="text" readonly="readonly" name="login_login" value="{$login_profile.a_login}" id="input100" style="background: #eee;"></td></tr>
				<tr><th align="center">Имя: </th><td><input type="text" name="a_name" value="{$login_profile.a_name}" id="input100"></td></tr> 
				<tr><th align="center">Отчество: </th><td><input type="text" name="a_o_name" value="{$login_profile.a_o_name}" id="input100"></td></tr>
				<tr><th align="center">Фамилия: </th><td><input type="text" name="a_f_name" value="{$login_profile.a_f_name}" id="input100"></td></tr> 
				<tr><th align="center">Почта:</th><td><input type="text" name="a_email" value="{$login_profile.a_email}" id="input100"></td></tr>
				<tr><td colspan="2" align="center"><br>
								<b>О себе:</b><br>
					<textarea name="a_about" rows="10" style="width: 100%;">{$login_profile.a_about}</textarea>
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
	{if $smarty.get.get == 'password'}
		<h1>Изменить пароль</h1>
		<center>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
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
	</div>