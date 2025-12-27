<h1>Настройки команд</h1>
<center>
		{if $championship_item.ch_chc_id == 2}
		<select id="input" style="width: 200px;" onchange="document.location.href='?show=competitions&ch={$smarty.get.ch}&get=request{if !empty($smarty.get.item)}&item={$smarty.get.item}{/if}&tour='+this.value">
			{section name = tour start = 0 loop = $championship_item.ch_tours}
				<option value="{$smarty.section.tour.index}"{if $smarty.section.tour.index == $smarty.get.tour} selected{/if}>Тур {$smarty.section.tour.index+1}</option>
			{/section}
		</select><br><br><br>
		{/if}
		</center>
		
		
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if empty($smarty.get.item)}active_left{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=request{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}">Список команд</a></td>
				<td width="34%" id="{if !empty($smarty.get.item)}active{else}notactive{/if}">{if !empty($smarty.get.item)}<a href="?show=competitions&ch={$smarty.get.ch}&get=request&item={$smarty.get.item}{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}">Заявка</a>{else}Заявка{/if}</td>
			</tr>
		</table>
		
		{if empty($smarty.get.item)}
			{if $championship_item.ch_chc_id == 2}<h2>Тур {$smarty.get.tour+1}</h2>{else}<br><br>{/if}
			<center>
			{if $championship_team}
                <form method="post" action="?show=competitions&ch={$championship_item.ch_id}&get=request{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}">
                    {assign var="tour_id" value=(!empty($smarty.get.tour))?$smarty.get.tour:0}
                    {if !$tour_id}{assign var="tour_id" value=0}{/if}
                    <input type="hidden" name="tour" value="{$tour_id}" />
                    <input type="hidden" name="ch_id" value="{$championship_item.ch_id}" />
				<table width="50%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				{foreach key=key item=item from=$championship_team}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
						<td style="padding: 5px;"><a href="?show=competitions&ch={$smarty.get.ch}&get=request&item={$item.t_id}{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}" style="font-size: 12px; color: #000; font-weight: bold;">{$item.t_title_ru}</a></td>
						<td style="padding: 5px;" align="center"><a href="?show=competitions&ch={$smarty.get.ch}&get=request&item={$item.t_id}{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}" >{assign var="t_id" value=$item.t_id}{if $request_list.$t_id}заявка сохранена{else}нет заявки{/if}</a></td>
						<td style="padding: 5px;" align="center"><input type="checkbox" name="p_t_id[{$item.t_id}]"{if !empty($championship_item.ch_settings.tour_team_is_points.$tour_id.$t_id)} checked{/if}/></td>
					</tr>
				{/foreach}
				</table>
                    <br>
                    <input type="submit" name="submitsaveteamtourcount" class="submitsave" value="не считать очки у выбранных команд в этом туре" />
                </form>
			{else}
				нет команд <a href="?show=championship&get=edit&item={$championship_item.ch_id}&cont=6">добавить</a>
			{/if}
			</center>
		{/if}
		
		{if !empty($smarty.get.item)}
			<h2>Заявка команды &laquo;{$request_item.team.t_title_ru}&raquo; на тур {$smarty.get.tour+1}</h2>
			
			<br><br>
			<center>
			{if $request_item.team_staff}
				<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
				<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td colspan="5">
						<center>
							<input type="submit" name="save_request" id="submitsave" value="Сохранить">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="submit" name="clear_request" id="submitdelete" value="Очистить">
						</center>
						<br><br>
						</td>
					</tr>
					<tr>
						<th>№</th>
						<th><a href="?show=competitions&ch={$smarty.get.ch}&get=request&item={$smarty.get.item}&tour={$smarty.get.tour}&sort=fio">ФИО</a></th>
						<th>Осн. состав <input type="checkbox" onclick="{foreach key=key item=item from=$request_item.team_staff name=staff}this.form.main_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=this.checked; this.form.reserve_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=false; {/foreach}" id="input"></th>
						<th><a href="?show=competitions&ch={$smarty.get.ch}&get=request&item={$smarty.get.item}&tour={$smarty.get.tour}&sort=pos">Позиция</a></th>
						<th>Замена</th>
					</tr>
				<input type="hidden" name="t_id" value="{$request_item.team.t_id}">
				<input type="hidden" name="tour" value="{$smarty.get.tour}">
				<input type="hidden" name="ch_id" value="{$championship_item.ch_id}">
				
				{foreach key=key item=item from=$request_item.team_staff name=staff}
					<input type="hidden" name="staff_{$smarty.foreach.staff.iteration}" value="{$item.st_id}">
					<input type="hidden" name="tr_id_{$smarty.foreach.staff.iteration}" value="{$item.tr_id}">
					
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
						<td align="right">{$smarty.foreach.staff.iteration}.&nbsp;&nbsp;</td>
						<td>{$item.st_family_ru} {$item.st_name_ru} {$item.st_surname_ru}</td>
						<td align="center">
							<input type="checkbox" name="main_{$smarty.foreach.staff.iteration}_{$item.st_id}" onclick="this.form.reserve_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=false;" id="input"{if $item.cngst_type == 'main'} checked{/if}>
						</td>
						<td>
							{if $team_categories_list}
								<select name="appointment_id_{$smarty.foreach.staff.iteration}" id="input100">
									{foreach key=key item=item_a from=$team_categories_list name=appointmen}{if $item_a.app_type == 'player'}<option value="{$item_a.app_id}"{if $item.app_id == $item_a.app_id} selected{/if}>{$item_a.app_title_ru}</option>
									{/if}{/foreach}
								</select>
							{else}
								<span style="color: #f00;">Список должностей пуст</span>
							{/if}
						</td>
						<td align="center">
							<input type="checkbox" name="reserve_{$smarty.foreach.staff.iteration}_{$item.st_id}" onclick="this.form.main_{$smarty.foreach.staff.iteration}_{$item.st_id}.checked=false;" id="input"{if $item.cngst_type == 'reserve'} checked{/if}>
						</td>
						<td align="center">{if $item.save == 'yes'}<img src="images/save_yes.gif" alt="сохранено" border="0">{else}<img src="images/save_no.gif" alt="не сохранено" border="0">{/if}</td>
					</tr>
					
				{/foreach}
					<input type="hidden" name="staff_count" value="{$smarty.foreach.staff.iteration}">
					<tr>
						<td colspan="5">
						<br>
						<center>
							<input type="submit" name="save_request" id="submitsave" value="Сохранить">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="submit" name="clear_request" id="submitdelete" value="Очистить">
						</center>
						</td>
					</tr>
				</table>
				</form>
			{else}
				<span style="color: #f00;">В команде пусто</span>
			{/if}
			</center>
		{/if}
		
			<br><br>
		