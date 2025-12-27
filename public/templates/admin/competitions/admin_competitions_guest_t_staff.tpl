		<a name="guest_t" id="guest_t"></a><h1>Основной состав команды: &laquo;<a href="?show=team&get=edit&item={$game_item.g_guest_t_id}">{$game_item.g_guest_t_title_ru}</a>&raquo;</h1>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="33%" id="{if $app_type == 'player'}active_left{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$smarty.get.g_item}&app_type=player&cont=5{if $smarty.get.country>0}&country={$smarty.get.country}{/if}">Игроки</a></td>
					<td width="34%" id="{if $app_type == 'head'}active{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$smarty.get.g_item}&app_type=head&cont=5{if $smarty.get.country>0}&country={$smarty.get.country}{/if}">Руководство</a></td>
					<td width="33%" id="{if $app_type == 'rest'}active_right{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$smarty.get.g_item}&app_type=rest&cont=5{if $smarty.get.country>0}&country={$smarty.get.country}{/if}">Другие</a></td>
				</tr>
			</table>
		
		<br><br>
		<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
		{if $game_item.g_is_done == 'no'}
		<form method="post" action="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$smarty.get.g_item}&app_type={$app_type}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false">
			<tr>
				<td colspan="5">
				<center>
					<input type="submit" name="save_guest_st" id="submitsave" value="Сохранить">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="clear_guest_st" id="submitdelete" value="Очистить">
				</center>
				<br><br>
				</td>
			</tr>
		{/if}
			<tr>
				<th>№</th>
				<th><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$smarty.get.g_item}&app_type={$smarty.get.app_type}&sort=fio&cont=5{if $smarty.get.country>0}&country={$smarty.get.country}{/if}">ФИО</a></th>
				<th>Осн. состав {if $game_item.g_is_done == 'no'}<input type="checkbox" onclick="{foreach key=key item=item from=$game_item.g_guest_t_staff name=staff}this.form.main_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=this.checked; this.form.reserve_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=false; {/foreach}" id="input">{/if}</th>
				<th><a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}&edit_comp=games_edit&g_item={$smarty.get.g_item}&app_type={$smarty.get.app_type}&sort=fio&cont=5{if $smarty.get.country>0}&country={$smarty.get.country}{/if}">Позиция</a></th>
				<th>Замена</th>
			</tr>
		<input type="hidden" name="t_id" value="{$game_item.g_guest_t_id}">
		<input type="hidden" name="g_id" value="{$game_item.g_id}">
		{if $game_item.g_guest_t_staff}
			{foreach key=key item=item from=$game_item.g_guest_t_staff name=staff}
			<input type="hidden" name="staff_{$smarty.foreach.staff.iteration}" value="{$item.st_id}">
			<input type="hidden" name="cngst_id_{$smarty.foreach.staff.iteration}" value="{$item.cngst_id}">
			{if $game_item.g_is_done == 'no' OR $item.save == 'yes'}
			<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
				<td align="right">{$smarty.foreach.staff.iteration}.&nbsp;&nbsp;</td>
				<td>{$item.st_family_ru} {$item.st_name_ru} {$item.st_surname_ru}</td>
				<td align="center">
				{if $game_item.g_is_done == 'no'}
					<input type="checkbox" name="main_{$smarty.foreach.staff.iteration}_{$item.st_id}" onclick="this.form.reserve_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=false;" id="input"{if $item.cngst_type == 'main'} checked{/if}>
				{else}
					{if $item.cngst_type == 'main'}<b>Осн.</b>{/if}
				{/if}
				</td>
				<td>
					{if $game_item.g_is_done == 'no'}
					{if $team_categories_list}
						<select name="appointment_id_{$smarty.foreach.staff.iteration}" id="input100">
							{foreach key=key item=item_a from=$team_categories_list name=appointmen}{if $app_type == $item_a.app_type}<option value="{$item_a.app_id}"{if $item.app_id == $item_a.app_id} selected{/if}>{$item_a.app_title_ru}</option>
							{/if}{/foreach}
						</select>
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					{else}
						{foreach key=key item=item_a from=$team_categories_list name=appointmen}{if $app_type == $item_a.app_type and $item.app_id == $item_a.app_id}<b>{$item_a.app_title_ru}</b>{/if}{/foreach}
					{/if}
				</td>
				<td align="center">
				{if $game_item.g_is_done == 'no'}
					<input type="checkbox" name="reserve_{$smarty.foreach.staff.iteration}_{$item.st_id}" onclick="this.form.main_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=false;" id="input"{if $item.cngst_type == 'reserve'} checked{/if}>
				{else}
					{if $item.cngst_type == 'reserve'}<b>Зам.</b>{/if}
				{/if}
				</td>
				<td align="center">{if $item.save == 'yes'}<img src="images/save_yes.gif" alt="сохранено" border="0">{elseif $item.save == 'else'}<img src="images/save_else.gif" alt="в заявке" border="0">{else}<img src="images/save_no.gif" alt="не сохранено" border="0">{/if}</td>
			</tr>
			{/if}
			{/foreach}
			<input type="hidden" name="staff_count" value="{$smarty.foreach.staff.iteration}">
		{else}
			<tr>
				<td colspan="5"><span style="color: #f00;">В команде пусто</span></td>
			</tr>
		{/if}
		{if $game_item.g_is_done == 'no'}
			<tr>
				<td colspan="5">
				<br>
				<center>
					<input type="submit" name="save_guest_st" id="submitsave" value="Сохранить">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="clear_guest_st" id="submitdelete" value="Очистить">
				</center>
				</td>
			</tr>
		</form>
		{/if}
		</table>
		
		<br>