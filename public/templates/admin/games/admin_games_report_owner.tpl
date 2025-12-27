				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
					<tr>
						<td width="50%" id="active_left"><a href="?show=games&get=edit&item={$smarty.get.item}&team=owner{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}{if !empty($smarty.get.ch)}&ch={$smarty.get.ch}{/if}">{$games_item.g_owner_t_title}</a></td>
						<td width="50%" id="notactive"><a href="?show=games&get=edit&item={$smarty.get.item}&team=guest{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}{if !empty($smarty.get.ch)}&ch={$smarty.get.ch}{/if}">{$games_item.g_guest_t_title}</a></td>
					</tr>
				</table>
			{if !empty($g_t_staff.owner.main)}
				<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th title="позиция">пз</th>
						<th title="Фамилия Имя Отчество">ФИО</th>
						<th title="Замена">зам</th>
						<th title="Попытка">поп</th>
						<th title="Штрафной">шт</th>
						<th title="Реализация">рез</th>
						<th title="Дроп гол">д г</th>
						<th title="Желтая карточка">ж к</th>
						<th title="Красная карточка">к к</th>
					</tr>
				{foreach key=key item=item from=$g_t_staff.owner.main name=main}
					{assign var="st_id" value=$item.st_id}
					<tr>
						<td height="3"></td>
					</tr>
					<tr onmouseover="this .style.background='#E4E4E4';" onmouseout="this.style.background='#FFF';">
						<td width="60">
							{$item.app_title_ru}
						</td>
						<td>
							<b>{$item.st_family_ru} {$item.st_name_ru} {$item.st_surname_ru}</b>
						</td>
						<td width="50" valign="bottom">
							{if !empty($g_action.owner.$st_id.zam_out)}{foreach item=item_a from=$g_action.owner.$st_id.zam_out}
								<a href="javascript:void(0);" onclick="document.getElementById('ga_i_{$item_a.ga_id}').style.display='inline'; document.getElementById('ga_a_{$item_a.ga_id}').style.display='none';" style="color: #000;" title="изменить" id="ga_a_{$item_a.ga_id}">{$item_a.ga_min} м.</a>
								{if $games_item.g_is_done == 'no'}
								<div id="ga_i_{$item_a.ga_id}" style="display: none; margin: 0; padding: 0;">
									<form method="post" style="margin: 0; padding: 0;">
										<input type="hidden" name="ga_id" value="{$item_a.ga_id}">
										<input type="hidden" name="a_id" value="zam_out">
										<input type="text" name="min" size="3" value="{$item_a.ga_min}" id="input"><input type="submit" style="background: white url(images/yes.jpg) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; text-indent: 999px;" value="1" name="edit_g_action">
									</form>
								</div>
								{/if}
							{/foreach}{else}
							{if $games_item.g_is_done == 'no'}
							<form method="post">
								<input type="hidden" name="g_id" value="{$item.cngst_g_id}">
								<input type="hidden" name="t_id" value="{$item.cngst_t_id}">
								<input type="hidden" name="st_id" value="{$item.st_id}">
								<input type="hidden" name="a_id" value="zam_out">
								<input type="text" name="min" size="3" id="input" style="width: 25px;"><input type="submit" style="background: white url(images/out.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; text-indent: 999px;" value="1" name="add_g_action">
							</form>
							{/if}
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item.st_id}_pop_A">
							{if !empty($g_action.owner.$st_id.pop)}{foreach item=item_a from=$g_action.owner.$st_id.pop}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item.st_id}_pop" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item.st_id}, 'pop')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item.st_id}_sht_A">
							{if !empty($g_action.owner.$st_id.sht)}{foreach item=item_a from=$g_action.owner.$st_id.sht}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item.st_id}_sht" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item.st_id}, 'sht')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item.st_id}_pez_A">
							{if !empty($g_action.owner.$st_id.pez)}{foreach item=item_a from=$g_action.owner.$st_id.pez}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item.st_id}_pez" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item.st_id}, 'pez')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item.st_id}_d_g_A">
							{if !empty($g_action.owner.$st_id.d_g)}{foreach item=item_a from=$g_action.owner.$st_id.d_g}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item.st_id}_d_g" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item.st_id}, 'd_g')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item.st_id}_y_c_A">
							{if !empty($g_action.owner.$st_id.y_c)}{foreach item=item_a from=$g_action.owner.$st_id.y_c}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item.st_id}_y_c" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item.st_id}, 'y_c')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item.st_id}_r_c_A">
							{if !empty($g_action.owner.$st_id.r_c)}{foreach item=item_a from=$g_action.owner.$st_id.r_c}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item.st_id}_r_c" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item.st_id}, 'r_c')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
					</tr>
					{if !empty($g_action.owner.$st_id.zam_out)}
					<tr>
						<td height="3"></td>
					</tr>
					<tr onmouseover="this .style.background='#E4E4E4';" onmouseout="this.style.background='#FFF';">
						<td>
							замена:
						</td>
					{if !empty($g_t_staff.owner.zam.$st_id.st_id)}
						{assign var="item_zam" value=$g_t_staff.owner.zam.$st_id}
						{assign var="item_st_id" value=$item_zam.st_id}
						<td>
						{if $games_item.g_is_done == 'no'}
							<div id="u_{$st_id}">
							<a href="javascript:void(0);" onclick="document.getElementById('f_{$st_id}').style.display='block'; document.getElementById('u_{$st_id}').style.display='none';" style="color: #000;" title="изменить"><b>{$item_zam.st_family_ru} {$item_zam.st_name_ru} {$item_zam.st_surname_ru}</b></a>
							</div>
							<div id="f_{$st_id}" style="display: none">
							<a href="javascript:void(0);" onclick="document.getElementById('f_{$st_id}').style.display='none'; document.getElementById('u_{$st_id}').style.display='block';" style="color: #000;" title="отменить"><b>{$item_zam.st_family_ru} {$item_zam.st_name_ru} {$item_zam.st_surname_ru}</b></a>
							<form method="post">
								<input type="hidden" name="ga_id" value="{$g_action.owner.$item_st_id.zam_in.0.ga_id}">
								<input type="hidden" name="a_id" value="zam_in">
								{if $g_t_staff.owner.reserve}
								<select name="st_id" id="input50">
								{foreach key=key_st item=item_st from=$g_t_staff.owner.reserve name=reserve}
									<option value="{$item_st.st_id}">{$item_st.st_family_ru} {$item_st.st_name_ru} {$item_st.st_surname_ru}</option>
								{/foreach}
								</select>
								<input type="submit" name="edit_g_action" style="background: white url(images/in.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; text-indent: 999px;" value="1">
								{else}
								В замене никого нет
								{/if}
							</form>
							</div>
						{else}
							<b>{$item_zam.st_family_ru} {$item_zam.st_name_ru} {$item_zam.st_surname_ru}</b>
						{/if}
						</td>
						<td width="50" valign="bottom">
							{if !empty($g_action.owner.$item_st_id.zam_in)}{foreach item=item_a from=$g_action.owner.$item_st_id.zam_in}
								{$item_a.ga_min} м.
							{/foreach}
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item_st_id}_pop_A">
							{if !empty($g_action.owner.$item_st_id.pop)}{foreach item=item_a from=$g_action.owner.$item_st_id.pop}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item_st_id}_pop" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item_st_id}, 'pop')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item_st_id}_sht_A">
							{if !empty($g_action.owner.$item_st_id.sht)}{foreach item=item_a from=$g_action.owner.$item_st_id.sht}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item_st_id}_sht" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item_st_id}, 'sht')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item_st_id}_pez_A">
							{if !empty($g_action.owner.$item_st_id.pez)}{foreach item=item_a from=$g_action.owner.$item_st_id.pez}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item_st_id}_pez" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item_st_id}, 'pez')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item_st_id}_d_g_A">
							{if !empty($g_action.owner.$item_st_id.d_g)}{foreach item=item_a from=$g_action.owner.$item_st_id.d_g}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item_st_id}_d_g" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item_st_id}, 'd_g')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item_st_id}_y_c_A">
							{if !empty($g_action.owner.$item_st_id.y_c)}{foreach item=item_a from=$g_action.owner.$item_st_id.y_c}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item_st_id}_y_c" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item_st_id}, 'y_c')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
						<td width="50" valign="bottom" id="{$item_st_id}_r_c_A">
							{if !empty($g_action.owner.$item_st_id.r_c)}{foreach item=item_a from=$g_action.owner.$item_st_id.r_c}
								<div id="{$item_a.ga_id}" class="edit_b"><a href="javascript: void(0);" onclick="javascript: game_action_edit({$item_a.ga_id}, {$item_a.ga_min})" >{if $item_a.ga_min > 0}{$item_a.ga_min} м.{else}+1 шт.{/if}</a></div>
							{/foreach}{/if}
							{if $games_item.g_is_done == 'no'}
							<div id="{$item_st_id}_r_c" class="add_b"><a href="javascript: void(0);" onclick="javascript: game_action({$item.cngst_g_id}, {$item.cngst_t_id}, {$item_st_id}, 'r_c')"  id="add">&nbsp;</a></div>
							{/if}
						</td>
					{else}
					{if $games_item.g_is_done == 'no'}
						<form method="post">
						<td>
								<input type="hidden" name="g_id" value="{$item.cngst_g_id}">
								<input type="hidden" name="t_id" value="{$item.cngst_t_id}">
								<input type="hidden" name="a_id" value="zam_in">
								<input type="hidden" name="zst_id" value="{$item.st_id}">
								<input type="hidden" name="zapp_id" value="{$item.app_id}">
								<input type="hidden" name="min" value="{$g_action.owner.$st_id.zam_out.0.ga_min}">
								{if $g_t_staff.owner.reserve}
								<select name="st_id" id="input100">
								{foreach key=key_st item=item_st from=$g_t_staff.owner.reserve name=reserve}
									<option value="{$item_st.st_id}">{$item_st.st_family_ru} {$item_st.st_name_ru} {$item_st.st_surname_ru}</option>
								{/foreach}
								</select>
								{else}
								В замене никого нет
								{/if}
						</td>
						<td>{if $g_t_staff.owner.reserve}<input type="submit" style="background: white url(images/in.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; text-indent: 999px;" value="1" name="add_g_action">{/if}</td>
						</form>
					{/if}
					{/if}
					</tr>
					{/if}
				{/foreach}
				</table>
					{if $games_item.g_is_done == 'no'}
					<br><br>
					<form method="post" style="float:right; margin: 0 20px 0 0;" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="g_id" value="{$item.cngst_g_id}">
						<input type="hidden" name="t_id" value="{$item.cngst_t_id}">
						<input type="submit" value="Очистить отчет" name="clear_report" id="submitdelete">
					</form>
					<br><br>
					{/if}
			{else}
				<br><br>
				Списки команды на игру не определены - <a href="?show=competitions&ch={$games_item.g_ch_id}&get=edit&item={$games_item.g_cp_id}&edit_comp=games_edit&g_item={$games_item.g_id}&cont=4{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}"><b>их можно определить тут</b></a>.
			{/if}
				