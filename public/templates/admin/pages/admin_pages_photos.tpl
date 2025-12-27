{* == Фотогалерея НАЧАЛО == *}
	<div id="conteiner" style="margin: 10px;">
		<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th colspan="3" align="center">Прикрепить фотогалерею: </th>
			</tr>
			<tr>
				<th colspan="3" align="center" height="20">&nbsp;</th>
			</tr>
			<tr>
				<th align="center" width="33%">Галерея: </th>
				<td width="33%">
					<select name="phg_id" id="input100">
						<option value="0">--- без галерей ---</option>
						{foreach key=key item=item from=$photo_gallery_list name=pvs}
						<option value="{$item.id}"{if $ph_page_item.pe_item_id == $item.id} selected{/if}>{$item.title}</option>{/foreach}
					</select>
				</td>
				<td width="34%" rowspan="3" align="center">
					{if $ph_page_item}<input type="submit" name="save_phg_page" id="submitsave" value="Обновить">
					{else}<input type="submit" name="save_phg_page" id="submitsave" value="Прикрепить">
					{/if}
				</td>
			</tr>
			<tr>
				<th align="center" width="33%">стиль отображения: </th>
				<td width="33%">
					<select name="style_id" id="input100">
						<option value="0"{if $ph_page_item.pe_style == '0'} selected{/if}>не показывать галерею внутри (для картинки у превью)</option>
						<option value="1"{if $ph_page_item.pe_style == '1'} selected{/if}>средние картинки с описанием</option>
					</select>
				</td>
			</tr>
			<tr>
				<th align="center" width="33%">отображать фото в описании страницы: </th>
				<th width="33%">
					<input type="Checkbox" name="pe_is_on_descr"{if $ph_page_item.pe_is_on_descr == 'yes'} checked{/if}>
				</th>
			</tr>
			<tr>
				<th align="center" width="33%">отображать фото в тексте страницы: </th>
				<th width="33%">
					<input type="Checkbox" name="pe_is_on_text"{if $ph_page_item.pe_is_on_text == 'yes'} checked{/if}>
				</th>
			</tr>
			</form>
		</table>
		<br>
	</div>
{* == Фотогалерея КОНЕЦ == *}