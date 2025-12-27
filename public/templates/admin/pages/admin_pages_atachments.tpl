		<div id="conteiner" style="margin: 10px;">
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th colspan="2" align="center">Заголовок к файлам: </th>
			</tr>
			<tr>
				<td align="center" width="50%">
					<input type="text" name="p_title_files" value="{$page_item.p_title_files}" id="input100">
				</td>
				<td align="center" width="50%">
					<input type="submit" name="save_page_title_files" id="submitsave" value="Сохранить">
				</td>
			</tr>
			</form>
			</table>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<th colspan="3" align="center">Прикрепить файл к странице: </th>
			</tr>
			<tr>
				<td width="45%" align="center">
				<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
					<input type="Hidden" name="page_id" value="{$page_item.p_id}">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
						<select name="file_id" id="input">
							<option value="0">-----</option>
							{if $files_list}{foreach key=key item=item from=$files_list}<option value="{$item.f_id}">{$item.f_path}</option>{/foreach}{/if}
						</select>
						</td>
						<td>&nbsp;&nbsp;&nbsp;</td>
						<td>
						<select name="lang" id="input">
							<option value="all">для любых языков</option>
							<option value="rus">для русского</option>
							<option value="ukr">для украинского</option>
							<option value="eng">для английского</option>
						</select>
						</td>
					</tr>
					</table>
					<input type="submit" name="atach_file" id="submitsave" value="Прикрепить файл >>>">
				</form>
				</td>
				<td width="10%">&nbsp;</td>
				<td width="45%" align="center">
				<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
					<select name="at_id" id="input">
						<option value="0">-----</option>
						{if $attached_list}{foreach key=key item=item from=$attached_list}<option value="{$item.at_id}">{$item.f_path} - {$item.at_lang}</option>{/foreach}{/if}
					</select><br>
					<input type="submit" name="delete_atach_file" id="submitsave" value="<<< Удалить файл">
				</form>
				</td>
			</tr>
			</table>
			<br>
		</div>