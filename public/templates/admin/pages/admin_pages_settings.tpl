		<div id="conteiner" style="margin: 10px;">
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th colspan="6" align="center">Отображение информации: </th>
			</tr>
			<tr>
				<td align="center" width="16%">
					Название
				</td>
				<td align="center" width="17%">
					Описание
				</td>
				<td align="center" width="16%">
					Текст
				</td>
				<td align="center" width="17%">
					Список из подстраниц
				</td>
				<td align="center" width="17%">
					Отображать файлы
				</td>
				<td align="center" width="17%">
					Отображать первую подстраницу
				</td>
				<td align="center" width="17%">
					Отображать социальные сети
				</td>
			</tr>
			<tr>
				<td align="center">
					<input type="Checkbox" name="p_is_title"{if $page_item.p_is_title == 'yes'} checked{/if}>
				</td>
				<td align="center">
					<input type="Checkbox" name="p_is_description"{if $page_item.p_is_description == 'yes'} checked{/if}>
				</td>
				<td align="center">
					<input type="Checkbox" name="p_is_text"{if $page_item.p_is_text == 'yes'} checked{/if}>
				</td>
				<td align="center">
					<input type="Checkbox" name="p_is_scrol"{if $page_item.p_is_scrol == 'yes'} checked{/if}>
				</td>
				<td align="center">
					<input type="Checkbox" name="p_is_files"{if $page_item.p_is_files == 'yes'} checked{/if}>
				</td>
				<td align="center">
					<input type="Checkbox" name="p_is_first_subpage"{if $page_item.p_is_first_subpage == 'yes'} checked{/if}>
				</td>
				<td align="center">
					<input type="Checkbox" name="p_is_socials"{if $page_item.p_is_socials == 'yes'} checked{/if}>
				</td>
			</tr>
			<tr>
				<td colspan="7" align="center">
					<br>
					<input type="submit" name="save_page_is" id="submitsave" value="Сохранить">
				</td>
			</tr>
			</form>
			</table>
			<br>
		</div>