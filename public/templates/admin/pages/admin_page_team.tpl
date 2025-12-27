Выбор команды для страницы:
<br /><br />
<span id="c_banner">
	<form method="post"name="f_banner" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
		<input type="Hidden" name="p_id" value="{$page_item.p_id}">
		<table width="630">
		<tr>
			<th width="50%">Команда</th>
			<td width="50%">
				<select name="pe_t_id" id="input" style="width: 100%;">
					<option value="" selected>-----</option>
					{if $team_list}{foreach key=key item=item from=$team_list name=ps}<option value="{$item.id}"{if $page_team_item.pe_item_id == $item.id} selected{/if}>{if $item.title == ''}названия{else}{$item.title}{/if}</option>
					{/foreach}{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br>
				<input type="submit" name="save_page_team" id="submitsave" value="Сохранить">
			</td>
		</tr>
		</table>
	</form>
</span>
<br>