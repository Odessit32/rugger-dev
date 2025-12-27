	<center>
		<b>Отправка уведомлений администратору о новых сообщениях, заявках и т.д.:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_send_admin"{if $feedback_settings_list.feedback_is_send_admin > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_feedback_is_send_admin_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>E-mail на который пересылать уведомления администратору:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">e-mail:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$feedback_settings_list.feedback_email_admin}"></td>
					<td width="33%" align="center"><input type="submit" name="save_feedback_email_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Максимальное количество сообщений с одного IP за 24 часа:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$feedback_settings_list.feedback_count_message}"></td>
					<td width="33%" align="center"><input type="submit" name="save_feedback_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
	</center>