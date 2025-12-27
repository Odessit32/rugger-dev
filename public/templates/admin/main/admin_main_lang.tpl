	<div id="conteiner" style="margin: 10px;"><br>
		<b>Языковые настройки сайта:</b><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="25%"></th>
					<th width="25%">рус</th>
					<th width="25%">укр</th>
					<th width="25%">eng</th>
				</tr>
				<tr>
					<td width="25%">отображать</td>
					<td width="25%" align="center"><input type="checkbox" name="rus_is_active"{if $main_settings.rus.sl_is_active == 'yes'} checked{/if}></td>
					<td width="25%" align="center"><input type="checkbox" name="ukr_is_active"{if $main_settings.ukr.sl_is_active == 'yes'} checked{/if}></td>
					<td width="25%" align="center"><input type="checkbox" name="eng_is_active"{if $main_settings.eng.sl_is_active == 'yes'} checked{/if}></td>
				</tr>
				<tr>
					<td colspan="4" align="center"><input type="submit" name="save_lang_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
	</div>