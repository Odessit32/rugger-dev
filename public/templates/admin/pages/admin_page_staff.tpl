		Список людей на странице:
			<div  onclick="javascript:showBlock('add_page_staff');" style="width: 150px; height: 20px; border: 1px solid #999; background: #eee; line-height: 20px; font-weight: bold; cursor: pointer; margin: 5px 0 5px 0;">Добавить</div>
			<div id="add_page_staff" style="padding: 5px; display: none; border: 1px solid #b2b2b2; background-color: White; width: 650px;">
				<b>Добавление в список</b><br><br>
			<span id="c_banner">
				<form method="post"name="f_banner" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
					<input type="Hidden" name="p_id" value="{$page_item.p_id}">
					<table width="630">
					<tr>
						<th width="50%">Человек</th>
						<td width="50%">
							<select name="ps_s_id" id="input" style="width: 100%;">
								<option value="" selected>-----</option>
								{if $staff_list}{foreach key=key item=item from=$staff_list name=ps}<option value="{$item.st_id}">{if $item.st_family_ru == ''}без фамилии{else}{$item.st_family_ru}{/if} {$item.st_name_ru} {$item.st_surname_ru}</option>
								{/foreach}{/if}
							</select>
						</td>
					</tr>
					<tr>
						<th width="50%">Адрес</th>
						<td width="50%">
							<input type="text" name="ps_address" id="input100">
						</td>
					</tr>
					<tr>
						<th width="50%">Заголовок (РУС)</th>
						<td width="50%">
							<input type="text" name="ps_title_ru" id="input100">
						</td>
					</tr>
					<tr>
						<th width="50%">Заголовок (УКР)</th>
						<td width="50%">
							<input type="text" name="ps_title_ua" id="input100">
						</td>
					</tr>
					<tr>
						<th width="50%">Заголовок (ENG)</th>
						<td width="50%">
							<input type="text" name="ps_title_en" id="input100">
						</td>
					</tr>
					<tr>
						<th width="50%">Порядок</th>
						<td width="50%">
							<input type="text" name="ps_order" id="input" size="3" maxlength="3">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center"><br>
							<input type="submit" name="add_page_staff" id="submitsave" value="Добавить">
						</td>
					</tr>
					</table>
				</form>
			</span>
			</div>
			
			{if $page_staff_list}
				<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th>Заголовок (РУС)</th>
					<th>Заголовок (УКР)</th>
					<th>Заголовок (ENG)</th>
					<th>ФИО</th>
					<th>Адрес</th>
					<th colspan="2">управление</th>
				</tr>
				{foreach key=key item=item from=$page_staff_list name=ps}
					{if $smarty.get.ps > 0 and $item.ps_id == $smarty.get.ps}
					<tr>	
						<td colspan="7" align="center">
						
							<form method="post" name="f_banner" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4" onsubmit="if (!confirm('Вы уверены?')) return false">
								<input type="hidden" name="ps_id" value="{$item.ps_id}">
								<table width="630">
								<tr>
									<th width="50%">Человек</th>
									<td width="50%">
										<select name="ps_s_id" id="input" style="width: 100%;">
											<option value="" selected>-----</option>
											{if $staff_list}{foreach key=key_s item=item_s from=$staff_list name=ps}<option value="{$item_s.st_id}"{if $item_s.st_id == $item.ps_s_id} selected{/if}>{if $item_s.st_family_ru == ''}без фамилии{else}{$item_s.st_family_ru}{/if} {$item_s.st_name_ru} {$item_s.st_surname_ru}</option>
											{/foreach}{/if}
										</select>
									</td>
								</tr>
								<tr>
									<th width="50%">Адрес</th>
									<td width="50%">
										<input type="text" name="ps_address" value="{$item.ps_address}" id="input100">
									</td>
								</tr>
								<tr>
									<th width="50%">Заголовок (РУС)</th>
									<td width="50%">
										<input type="text" name="ps_title_ru" value="{$item.ps_title_ru}" id="input100">
									</td>
								</tr>
								<tr>
									<th width="50%">Заголовок (УКР)</th>
									<td width="50%">
										<input type="text" name="ps_title_ua" value="{$item.ps_title_ua}" id="input100">
									</td>
								</tr>
								<tr>
									<th width="50%">Заголовок (ENG)</th>
									<td width="50%">
										<input type="text" name="ps_title_en" value="{$item.ps_title_en}" id="input100">
									</td>
								</tr>
								<tr>
									<th width="50%">Порядок</th>
									<td width="50%">
										<input type="text" name="ps_order" value="{$item.ps_order}" id="input" size="3" maxlength="3">
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><br>
										<input name="edit_page_staff" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
									</td>
								</tr>
								</table>
							</form>
						</td>
					</tr>
					{else}
					<tr>
						<td align="center">{$item.ps_title_ru}</td>
						<td align="center">{$item.ps_title_ua}</td>
						<td align="center">{$item.ps_title_en}</td>
						<td align="center">{$item.st_family_ru} {$item.st_name_ru} {$item.st_surname_ru}</td>
						<td align="center">{$item.ps_address}</td>
						<td align="center">
							<a href="?show=pages&get=edit&item={$smarty.get.item}&cont=4&ps={$item.ps_id}#ps" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
						</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#ps" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="ps_id" value="{$item.ps_id}">
								<input name="delete_page_staff" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</form>
						</td>
					</tr>
					{/if}
				{/foreach}
				</table>
			{/if}
			<br>