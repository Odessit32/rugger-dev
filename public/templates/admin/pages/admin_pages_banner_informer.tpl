		{*COUNT BANNERS*}
		<div id="conteiner" style="margin: 10px;">
		<table width="70%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<th colspan="3" align="center">Количество баннеров в колонке </th>
			</tr>
			<tr>
				<th colspan="3" align="center" height="20">&nbsp;</th>
			</tr>
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th align="center" width="34%">С лева: </th>
				<td width="33%"><input type="text" name="p_c_banners_left" value="{$page_item.p_c_banners_left}" size="3" id="input"></td>
				<td width="33%" align="center">
					<input type="submit" name="save_page_banner_count_left" id="submitsave" value="Сохранить">
				</td>
			</tr>
			</form>
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th align="center" width="34%">По центру: </th>
				<td width="33%"><input type="text" name="p_c_banners_center" value="{$page_item.p_c_banners_center}" size="3" id="input"></td>
				<td width="33%" align="center">
					<input type="submit" name="save_page_banner_count_center" id="submitsave" value="Сохранить">
				</td>
			</tr>
			</form>
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th align="center" width="34%">С права: </th>
				<td width="33%"><input type="text" name="p_c_banners_right" value="{$page_item.p_c_banners_right}" size="3" id="input"></td>
				<td width="33%" align="center">
					<input type="submit" name="save_page_banner_count_right" id="submitsave" value="Сохранить">
				</td>
			</tr>
			</form>
		</table>
		<br>
		</div>
		
		{* БАННЕРЫ или ИНФОРМЕРЫ *}
		<a name="pbi"></a>
		<div id="conteiner" style="margin: 10px;">
			Баннера и информеры на странице:
			<div  onclick="javascript:showBlock('add_banner_informer');" style="width: 150px; height: 20px; border: 1px solid #999; background: #eee; line-height: 20px; font-weight: bold; cursor: pointer; margin: 5px 0 5px 0;">Добавить</div>
			<div id="add_banner_informer" style="padding: 5px; display: none; border: 1px solid #b2b2b2; background-color: White; width: 650px;">
				<b>Добавление баннера или информера</b><br>
			<span id="c_banner">
				<form method="post"name="f_banner" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi">
					<input type="Hidden" name="p_id" value="{$page_item.p_id}">
					<table width="630">
					<tr>
						<th width="60">тип</th>
						<th width="200">выбор</th>
						<th width="80">место</th>
						<th width="80">показывать</th>
						<th width="50">порядок</th>
						<th width="100"></th>
					</tr>
					<tr>
						<td align="center">
							<select name="pbi_item_type" id="input" onchange="document.forms.f_informer.pbi_item_type.value = 'informer'; document.getElementById('c_banner').style.display = 'none'; document.getElementById('c_'+this.value).style.display = 'inline'; ">
								<option value="banner" selected>баннер</option>
								<option value="informer">информер</option>
							</select>
						</td>
						{if !empty($pbi.banners)}
						<td align="center">
							<select name="pbi_banner_id" id="input" style="width: 100%;">
								{foreach key=key item=item from=$pbi.banners name=pvs}<option value="{$item.id}">{if $item.title == ''}без названия{else}{$item.title}{/if}</option>
								{/foreach}
							</select>
						</td>
						<td align="center">
							<select name="pbi_place" id="input">
								<option value="left">в лево</option>
								<option value="center">по центру</option>
								<option value="right">в право</option>
							</select>
						</td>
						<td align="center">
							<select name="pbi_display_type" id="input">
								<option value="fixed">постоянно</option>
								<option value="shuffle">вперемешку</option>
							</select>
						</td>
						<td align="center">
							<input type="text" name="pbi_order" size="3" id="input">
						</td>
						<td align="center">
							<input type="submit" name="add_page_banner" id="submitsave" value="Добавить">
						</td>
						{else}
						<td colspan="3" align="center">
							Все баннеры уже добавлены
						</td>
						{/if}
					</tr>
					</table>
				</form>
			</span>
			<span id="c_informer" style="display: none;">
				<form method="post" name="f_informer" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi">
					<input type="Hidden" name="p_id" value="{$page_item.p_id}">
					<table width="580">
					<tr>
						<th width="60">тип</th>
						<th width="230">выбор</th>
						<th width="80">место</th>
						<th width="80">показывать</th>
						<th width="50">порядок</th>
						<th width="100"></th>
					</tr>
					<tr>
						<td align="center">
							<select name="pbi_item_type" id="input" onchange="document.forms.f_banner.pbi_item_type.value = 'banner'; document.getElementById('c_informer').style.display = 'none'; document.getElementById('c_'+this.value).style.display = 'inline'; ">
								<option value="banner">баннер</option>
								<option value="informer" selected>информер</option>
							</select>
						</td>
						{if !empty($pbi.informers)}
						<td align="center">
							<select name="pbi_informer_id" id="input" style="width: 100%;">
								{foreach key=key item=item from=$pbi.informers name=pvs}<option value="{$item.id}">{if $item.title == ''}без названия{else}{$item.title}{/if}</option>
								{/foreach}
							</select>
						</td>
						<td align="center">
							<select name="pbi_place" id="input">
								<option value="left">в лево</option>
								<option value="center">по центру</option>
								<option value="right">в право</option>
							</select>
						</td>
						<td align="center">
							<select name="pbi_display_type" id="input">
								<option value="fixed">постоянно</option>
								<option value="shuffle">вперемешку</option>
							</select>
						</td>
						<td align="center">
							<input type="text" name="pbi_order" size="3" id="input">
						</td>
						<td align="center">
							<input type="submit" name="add_page_informer" id="submitsave" value="Добавить">
						</td>
						{else}
						<td colspan="3" align="center">
							Все информеры уже добавлены
						</td>
						{/if}
					</tr>
					</table>
				</form>
				</span>
			</div>
			
			{if $page_ban_inf_list}
				<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th>тип</th>
					<th>баннер/информер</th>
					<th>позиция</th>
					<th>место</th>
					<th>показывать</th>
					<th>страница</th>
					<th colspan="3">управление</th>
				</tr>
				<tr>
					<th colspan="8">левые</th>
				</tr>
				{foreach key=key item=item from=$page_ban_inf_list name=pbi}
				{if $item.pbi_item_type !== 'setting' and $item.pbi_place == 'left'}
					{if !empty($smarty.get.pbi) && $item.pbi_id == $smarty.get.pbi}
					<tr>
					<form method="post" name="f_informer" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
						<td align="center">
							<select name="pbi_item_type" id="input" onchange="document.getElementById('sel_banner').style.display = 'none'; document.getElementById('sel_informer').style.display = 'none'; document.getElementById('sel_'+this.value).style.display = 'inline'; ">
								<option value="banner" {if $item.pbi_item_type == 'banner'}selected{/if}>баннер</option>
								<option value="informer" {if $item.pbi_item_type == 'informer'}selected{/if}>информер</option>
							</select>
						</td>
						<td align="center">
						<div id="sel_banner" {if $item.pbi_item_type !== 'banner'}style="display: none;"{/if}>
							<select name="pbi_banner_id" id="input" style="width: 100%;">
								{if $item.pbi_item_type == 'banner'}<option value="{$item.pbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if !empty($pbi.banners)}
								{foreach key=key item=item_ from=$pbi.banners name=pvs}<option value="{$item_.id}"{if $item_.id == $item.pbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						<div id="sel_informer" {if $item.pbi_item_type !== 'informer'}style="display: none;"{/if}>
						
							<select name="pbi_informer_id" id="input" style="width: 100%;">
								{if $item.pbi_item_type == 'informer'}<option value="{$item.pbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if !empty($pbi.informers)}
								{foreach key=key item=item_ from=$pbi.informers name=pvs}<option value="{$item_.id}"{if $item_.id == $item.pbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						</td>
						<td align="center"><input type="text" name="pbi_order" value="{$item.pbi_order}" size="3" id="input"></td>
						<td align="center">
							<select name="pbi_place" id="input">
								<option value="left" {if $item.pbi_place == 'left'}selected{/if}>в лево</option>
								<option value="center" {if $item.pbi_place == 'center'}selected{/if}>по центру</option>
								<option value="right" {if $item.pbi_place == 'right'}selected{/if}>в право</option>
							</select>
						</td>
						<td align="center">
							<select name="pbi_display_type" id="input">
								<option value="fixed" {if $item.pbi_display_type == 'fixed'}selected{/if}>постоянно</option>
								<option value="shuffle" {if $item.pbi_display_type == 'shuffle'}selected{/if}>вперемешку</option>
							</select>
						</td>
						<td align="center">{if $item.pbi_page_id == 0}на всех{elseif $item.pbi_page_id == $page_item.p_id}на этой{else}на родительской{/if}</td>
						<td align="center" colspan="3">
							<input name="save_pbi" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</td>
					</form>
					</tr>
					{else}
					<tr>
						<td align="center">{$item.pbi_item_type}</td>
						<td align="center">
							{if $item.pbi_item_type == 'banner'}<a href="?show=banners&get=edit&item={$item.pbi_item_id}" title="Редактировать баннер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>
							{elseif $item.pbi_item_type == 'informer'}<a href="?show=informers&get=edit&item={$item.pbi_item_id}" title="Редактировать информер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>{/if}
						</td>
						<td align="center">{$item.pbi_order}</td>
						<td align="center">{$item.pbi_place}</td>
						<td align="center">{$item.pbi_display_type}</td>
						<td align="center">{if $item.pbi_page_id == 0}на всех{elseif $item.pbi_page_id == $page_item.p_id}на этой{else}на родительской{/if}</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
						{if $item.pbi_display == 'yes'}
							<input value=" " name="off_pbi" type="submit" style="background: white url(images/on.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;">
						{else}
							<input value=" " name="on_pbi" type="submit" style="background: white url(images/off.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;">
						{/if}
						</form>
						</td>
						<td align="center">
							{if $item.pbi_page_id == $page_item.p_id}
								<a href="?show=pages&get=edit&item={$smarty.get.item}&cont=4&pbi={$item.pbi_id}#pbi" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
							{/if}
						</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
							{if $item.pbi_page_id == $page_item.p_id}
								<input name="delete_pbi" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
							{/if}
						</form>
						</td>
					</tr>
					{/if}
				{/if}
				{/foreach}
				<tr>
					<th colspan="8">правые</th>
				</tr>
				{foreach key=key item=item from=$page_ban_inf_list name=pbi}
				{if $item.pbi_item_type !== 'setting' and $item.pbi_place == 'right'}
					{if !empty($smarty.get.pbi) and $item.pbi_id == $smarty.get.pbi}
					<tr>
					<form method="post" name="f_informer" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
						<td align="center">
							<select name="pbi_item_type" id="input" onchange="document.getElementById('sel_banner').style.display = 'none'; document.getElementById('sel_informer').style.display = 'none'; document.getElementById('sel_'+this.value).style.display = 'inline'; ">
								<option value="banner" {if $item.pbi_item_type == 'banner'}selected{/if}>баннер</option>
								<option value="informer" {if $item.pbi_item_type == 'informer'}selected{/if}>информер</option>
							</select>
						</td>
						<td align="center">
						<div id="sel_banner" {if $item.pbi_item_type !== 'banner'}style="display: none;"{/if}>
							<select name="pbi_banner_id" id="input" style="width: 100%;">
								{if $item.pbi_item_type == 'banner'}<option value="{$item.pbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if !empty($pbi.banners)}
								{foreach key=key item=item_ from=$pbi.banners name=pvs}<option value="{$item_.id}"{if $item_.id == $item.pbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						<div id="sel_informer" {if $item.pbi_item_type !== 'informer'}style="display: none;"{/if}>
						
							<select name="pbi_informer_id" id="input" style="width: 100%;">
								{if $item.pbi_item_type == 'informer'}<option value="{$item.pbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if !empty($pbi.informers)}
								{foreach key=key item=item_ from=$pbi.informers name=pvs}<option value="{$item_.id}"{if $item_.id == $item.pbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						</td>
						<td align="center"><input type="text" name="pbi_order" value="{$item.pbi_order}" size="3" id="input"></td>
						<td align="center">
							<select name="pbi_place" id="input">
								<option value="left" {if $item.pbi_place == 'left'}selected{/if}>в лево</option>
								<option value="center" {if $item.pbi_place == 'center'}selected{/if}>по центру</option>
								<option value="right" {if $item.pbi_place == 'right'}selected{/if}>в право</option>
							</select>
						</td>
						<td align="center">
							<select name="pbi_display_type" id="input">
								<option value="fixed" {if $item.pbi_display_type == 'fixed'}selected{/if}>постоянно</option>
								<option value="shuffle" {if $item.pbi_display_type == 'shuffle'}selected{/if}>вперемешку</option>
							</select>
						</td>
						<td align="center">{if $item.pbi_page_id == 0}на всех{elseif $item.pbi_page_id == $page_item.p_id}на этой{else}на родительской{/if}</td>
						<td align="center" colspan="3">
							<input name="save_pbi" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</td>
					</form>
					</tr>
					{else}
					<tr>
						<td align="center">{$item.pbi_item_type}</td>
						<td align="center">
							{if $item.pbi_item_type == 'banner'}<a href="?show=banners&get=edit&item={$item.pbi_item_id}" title="Редактировать баннер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>
							{elseif $item.pbi_item_type == 'informer'}<a href="?show=informers&get=edit&item={$item.pbi_item_id}" title="Редактировать информер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>{/if}
						</td>
						<td align="center">{$item.pbi_order}</td>
						<td align="center">{$item.pbi_place}</td>
						<td align="center">{$item.pbi_display_type}</td>
						<td align="center">{if $item.pbi_page_id == 0}на всех{elseif $item.pbi_page_id == $page_item.p_id}на этой{else}на родительской{/if}</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
						{if $item.pbi_display == 'yes'}
							<input value=" " name="off_pbi" type="submit" style="background: white url(images/on.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;">
						{else}
							<input value=" " name="on_pbi" type="submit" style="background: white url(images/off.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;">
						{/if}
						</form>
						</td>
						<td align="center">
							{if $item.pbi_page_id == $page_item.p_id}
								<a href="?show=pages&get=edit&item={$smarty.get.item}&cont=4&pbi={$item.pbi_id}#pbi" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
							{/if}
						</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
							{if $item.pbi_page_id == $page_item.p_id}
								<input name="delete_pbi" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
							{/if}
						</form>
						</td>
					</tr>
					{/if}
				{/if}
				{/foreach}
				<tr>
					<th colspan="8">по центру</th>
				</tr>
				{foreach key=key item=item from=$page_ban_inf_list name=pbi}
				{if $item.pbi_item_type !== 'setting' and $item.pbi_place == 'center'}
					{if !empty($smarty.get.pbi) and $item.pbi_id == $smarty.get.pbi}
					<tr>
					<form method="post" name="f_informer" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
						<td align="center">
							<select name="pbi_item_type" id="input" onchange="document.getElementById('sel_banner').style.display = 'none'; document.getElementById('sel_informer').style.display = 'none'; document.getElementById('sel_'+this.value).style.display = 'inline'; ">
								<option value="banner" {if $item.pbi_item_type == 'banner'}selected{/if}>баннер</option>
								<option value="informer" {if $item.pbi_item_type == 'informer'}selected{/if}>информер</option>
							</select>
						</td>
						<td align="center">
						<div id="sel_banner" {if $item.pbi_item_type !== 'banner'}style="display: none;"{/if}>
							<select name="pbi_banner_id" id="input" style="width: 100%;">
								{if $item.pbi_item_type == 'banner'}<option value="{$item.pbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if !empty($pbi.banners)}
								{foreach key=key item=item_ from=$pbi.banners name=pvs}<option value="{$item_.id}"{if $item_.id == $item.pbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						<div id="sel_informer" {if $item.pbi_item_type !== 'informer'}style="display: none;"{/if}>
						
							<select name="pbi_informer_id" id="input" style="width: 100%;">
								{if $item.pbi_item_type == 'informer'}<option value="{$item.pbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if !empty($pbi.informers)}
								{foreach key=key item=item_ from=$pbi.informers name=pvs}<option value="{$item_.id}"{if $item_.id == $item.pbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						</td>
						<td align="center"><input type="text" name="pbi_order" value="{$item.pbi_order}" size="3" id="input"></td>
						<td align="center">
							<select name="pbi_place" id="input">
								<option value="left" {if $item.pbi_place == 'left'}selected{/if}>в лево</option>
								<option value="center" {if $item.pbi_place == 'center'}selected{/if}>по центру</option>
								<option value="right" {if $item.pbi_place == 'right'}selected{/if}>в право</option>
							</select>
						</td>
						<td align="center">
							<select name="pbi_display_type" id="input">
								<option value="fixed" {if $item.pbi_display_type == 'fixed'}selected{/if}>постоянно</option>
								<option value="shuffle" {if $item.pbi_display_type == 'shuffle'}selected{/if}>вперемешку</option>
							</select>
						</td>
						<td align="center">{if $item.pbi_page_id == 0}на всех{elseif $item.pbi_page_id == $page_item.p_id}на этой{else}на родительской{/if}</td>
						<td align="center" colspan="3">
							<input name="save_pbi" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</td>
					</form>
					</tr>
					{else}
					<tr>
						<td align="center">{$item.pbi_item_type}</td>
						<td align="center">
							{if $item.pbi_item_type == 'banner'}<a href="?show=banners&get=edit&item={$item.pbi_item_id}" title="Редактировать баннер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>
							{elseif $item.pbi_item_type == 'informer'}<a href="?show=informers&get=edit&item={$item.pbi_item_id}" title="Редактировать информер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>{/if}
						</td>
						<td align="center">{$item.pbi_order}</td>
						<td align="center">{$item.pbi_place}</td>
						<td align="center">{$item.pbi_display_type}</td>
						<td align="center">{if $item.pbi_page_id == 0}на всех{elseif $item.pbi_page_id == $page_item.p_id}на этой{else}на родительской{/if}</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
						{if $item.pbi_display == 'yes'}
							<input value=" " name="off_pbi" type="submit" style="background: white url(images/on.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;">
						{else}
							<input value=" " name="on_pbi" type="submit" style="background: white url(images/off.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;">
						{/if}
						</form>
						</td>
						<td align="center">
							{if $item.pbi_page_id == $page_item.p_id}
								<a href="?show=pages&get=edit&item={$smarty.get.item}&cont=4&pbi={$item.pbi_id}#pbi" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
							{/if}
						</td>
						<td align="center">
						<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4#pbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="pbi_id" value="{$item.pbi_id}">
							{if $item.pbi_page_id == $page_item.p_id}
								<input name="delete_pbi" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
							{/if}
						</form>
						</td>
					</tr>
					{/if}
				{/if}
				{/foreach}
				</table>
			{/if}
			<br>
		</div>
		