	<div id="conteiner" style="margin: 10px;">
		<table width="70%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th colspan="3" align="center">Прикрепить готовое видео с видеосервисов (необходимо ввести код): </th>
			</tr>
			<tr>
				<th colspan="3" align="center" height="20">&nbsp;</th>
			</tr>
			<tr>
				<th align="center" width="34%">Код: </th>
				<td width="33%"><input type="text" name="pvs_code" id="input100"></td>
				<td width="33%" align="center">
					<input type="submit" name="add_pvs" id="submitsave" value="Добавить">
				</td>
			</tr>
			</form>
		</table>
		{if $pvs_list}
		<center>
			<table width="70%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$pvs_list name=pvs}
			{if $smarty.get.pvs_item == $item.pvs_id and $item.pvs_id > 0}
				<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="pvs_id" value="{$item.pvs_id}">
				<tr onmouseover="bgcolor: #c0c0c0;" >
					<td width="34%"><input type="text" name="pvs_code" value="{$item.pvs_code}" id="input100"></td>
					<td width="33%" align="center">{if $item.pvs_service == 'youtube'}<a href="http://www.youtube.com/watch?v={$item.pvs_code}" target="_blank">{$item.pvs_service}</a>{/if}</td>
					<td width="33%" align="center"><input type="submit" name="save_pvs" id="submitsave" value="Сохранить"></td>
				</tr>
				</form>
			{else}
				<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="pvs_id" value="{$item.pvs_id}">
				<tr onmouseover="bgcolor: #c0c0c0;" >
					<th width="34%"><a href="?show=pages&get=edit&item={$smarty.get.item}&pvs_item={$item.pvs_id}&cont=4">{$item.pvs_code}</a></th>
					<td width="33%" align="center">{if $item.pvs_service == 'youtube'}<a href="http://www.youtube.com/watch?v={$item.pvs_code}" target="_blank">{$item.pvs_service}</a>{/if}</td>
					<td width="33%" align="center"><input type="submit" name="delete_pvs" id="submitdelete" value="Удалить"></td>
				</tr>
				</form>
			{/if}
			{/foreach}
			</table>
			
		</center>
		{/if}
		<br>
	</div>