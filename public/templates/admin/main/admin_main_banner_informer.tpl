<div id="conteiner" style="margin: 10px;">
	<br>
	<b>Настройки баннеров и информеров на главной странице:</b><br><br>
		
		{* БАННЕРЫ или ИНФОРМЕРЫ *}
		<a name="mbi"></a>
			
			<div onclick="javascript:showBlock('add_banner_informer');" style="width: 150px; height: 20px; border: 1px solid #999; background: #eee; line-height: 20px; font-weight: bold; cursor: pointer; margin: 5px 0 5px 0;">Добавить</div>
			<div id="add_banner_informer" style="padding: 5px; display: none; border: 1px solid #b2b2b2; background-color: White; width: 650px;">
				<b>Добавление баннера или информера</b><br>
			<span id="c_banner">
				<form method="post"name="f_banner" action="?show=main&get=banner_informer#mbi">
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
							<select name="mbi_item_type" id="input" onchange="document.forms.f_informer.mbi_item_type.value = 'informer'; document.getElementById('c_banner').style.display = 'none'; document.getElementById('c_'+this.value).style.display = 'inline'; ">
								<option value="banner" selected>баннер</option>
								<option value="informer">информер</option>
							</select>
						</td>
						{if $mbi.banners}
						<td align="center">
							<select name="mbi_banner_id" id="input" style="width: 100%;">
								{foreach key=key item=item from=$mbi.banners name=pvs}<option value="{$item.id}">{if $item.title == ''}без названия{else}{$item.title}{/if}</option>
								{/foreach}
							</select>
						</td>
						<td align="center">
							<select name="mbi_place" id="input">
								<option value="left">в лево</option>
								<option value="right">в право</option>
								<option value="center">по центру</option>
							</select>
						</td>
						<td align="center">
							<select name="mbi_display_type" id="input">
								<option value="fixed">постоянно</option>
								<option value="shuffle">вперемешку</option>
							</select>
						</td>
						<td align="center">
							<input type="text" name="mbi_order" size="3" id="input">
						</td>
						<td align="center">
							<input type="submit" name="add_main_banner" id="submitsave" value="Добавить">
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
				<form method="post" name="f_informer" action="?show=main&get=banner_informer#mbi">
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
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
						<td align="center">
							<select name="mbi_item_type" id="input" onchange="document.forms.f_banner.mbi_item_type.value = 'banner'; document.getElementById('c_informer').style.display = 'none'; document.getElementById('c_'+this.value).style.display = 'inline'; ">
								<option value="banner">баннер</option>
								<option value="informer" selected>информер</option>
							</select>
						</td>
						{if $mbi.informers}
						<td align="center">
							<select name="mbi_informer_id" id="input" style="width: 100%;">
								{foreach key=key item=item from=$mbi.informers name=pvs}<option value="{$item.id}">{if $item.title == ''}без названия{else}{$item.title}{/if}</option>
								{/foreach}
							</select>
						</td>
						<td align="center">
							<select name="mbi_place" id="input">
								<option value="left">в лево</option>
								<option value="right">в право</option>
								<option value="center">по центру</option>
							</select>
						</td>
						<td align="center">
							<select name="mbi_display_type" id="input">
								<option value="fixed">постоянно</option>
								<option value="shuffle">вперемешку</option>
							</select>
						</td>
						<td align="center">
							<input type="text" name="mbi_order" size="3" id="input">
						</td>
						<td align="center">
							<input type="submit" name="add_main_informer" id="submitsave" value="Добавить">
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
			
			{if $main_ban_inf_list}
				<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th>тип</th>
					<th>баннер/информер</th>
					<th>позиция</th>
					<th>место</th>
					<th>показывать</th>
					<th colspan="2">управление</th>
				</tr>
				<tr>
					<th colspan="7">левые</th>
				</tr>
				{foreach key=key item=item from=$main_ban_inf_list name=mbi}
				{if $item.mbi_place == 'left'}
					{if !empty($smarty.get.mbi) && $item.mbi_id == $smarty.get.mbi}
					<tr>
					<form method="post" name="f_informer" action="?show=main&get=banner_informer#mbi" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="mbi_id" value="{$item.mbi_id}">
						<td align="center">
							<select name="mbi_item_type" id="input" onchange="document.getElementById('sel_banner').style.display = 'none'; document.getElementById('sel_informer').style.display = 'none'; document.getElementById('sel_'+this.value).style.display = 'inline'; ">
								<option value="banner" {if $item.mbi_item_type == 'banner'}selected{/if}>баннер</option>
								<option value="informer" {if $item.mbi_item_type == 'informer'}selected{/if}>информер</option>
							</select>
						</td>
						<td align="center">
						<div id="sel_banner" {if $item.mbi_item_type !== 'banner'}style="display: none;"{/if}>
							<select name="mbi_banner_id" id="input" style="width: 100%;">
								{if $item.mbi_item_type == 'banner'}<option value="{$item.mbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if $mbi.banners}
								{foreach key=key item=item_ from=$mbi.banners name=pvs}<option value="{$item_.id}"{if $item_.id == $item.mbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						<div id="sel_informer" {if $item.mbi_item_type !== 'informer'}style="display: none;"{/if}>
							<select name="mbi_informer_id" id="input" style="width: 100%;">
								{if $item.mbi_item_type == 'informer'}<option value="{$item.mbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if $mbi.informers}
								{foreach key=key item=item_ from=$mbi.informers name=pvs}<option value="{$item_.id}"{if $item_.id == $item.mbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						</td>
						<td align="center"><input type="text" name="mbi_order" value="{$item.mbi_order}" size="3" id="input"></td>
						<td align="center">
							<select name="mbi_place" id="input">
								<option value="left" {if $item.mbi_place == 'left'}selected{/if}>в лево</option>
								<option value="right" {if $item.mbi_place == 'right'}selected{/if}>в право</option>
								<option value="center" {if $item.mbi_place == 'center'}selected{/if}>по центру</option>
							</select>
						</td>
						<td align="center">
							<select name="mbi_display_type" id="input">
								<option value="fixed" {if $item.mbi_display_type == 'fixed'}selected{/if}>постоянно</option>
								<option value="shuffle" {if $item.mbi_display_type == 'shuffle'}selected{/if}>вперемешку</option>
							</select>
						</td>
						<td align="center" colspan="2">
							<input name="save_mbi" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</td>
					</form>
					</tr>
					{else}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
						<td align="center">{$item.mbi_item_type}</td>
						<td align="center">
							{if $item.mbi_item_type == 'banner'}<a href="?show=banners&get=edit&item={$item.mbi_item_id}" title="Редактировать баннер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>
							{elseif $item.mbi_item_type == 'informer'}<a href="?show=informers&get=edit&item={$item.mbi_item_id}" title="Редактировать информер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>{/if}
						</td>
						<td align="center">{$item.mbi_order}</td>
						<td align="center">{$item.mbi_place}</td>
						<td align="center">{$item.mbi_display_type}</td>
						<td align="center">{$item.mbi_page_id}
							{*if !empty($item.mbi_page_id) && $item.mbi_page_id == $page_item.p_id*}
								<a href="?show=main&get=banner_informer&mbi={$item.mbi_id}#mbi" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
							{*/if*}
						</td>
						<td align="center">
						<form method="post" action="?show=main&get=banner_informer#mbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="mbi_id" value="{$item.mbi_id}">
							{*if !empty($item.mbi_page_id) &&  $item.mbi_page_id == $page_item.p_id*}
								<input name="delete_mbi" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
							{*/if*}
						</form>
						</td>
					</tr>
					{/if}
				{/if}
				{/foreach}
				<tr>
					<th colspan="7">правые</th>
				</tr>
				{foreach key=key item=item from=$main_ban_inf_list name=mbi}
				{if $item.mbi_place == 'right'}
					{if !empty($smarty.get.mbi) && $item.mbi_id == $smarty.get.mbi}
					<tr>
					<form method="post" name="f_informer" action="?show=main&get=banner_informer#mbi" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="mbi_id" value="{$item.mbi_id}">
						<td align="center">
							<select name="mbi_item_type" id="input" onchange="document.getElementById('sel_banner').style.display = 'none'; document.getElementById('sel_informer').style.display = 'none'; document.getElementById('sel_'+this.value).style.display = 'inline'; ">
								<option value="banner" {if $item.mbi_item_type == 'banner'}selected{/if}>баннер</option>
								<option value="informer" {if $item.mbi_item_type == 'informer'}selected{/if}>информер</option>
							</select>
						</td>
						<td align="center">
						<div id="sel_banner" {if $item.mbi_item_type !== 'banner'}style="display: none;"{/if}>
							<select name="mbi_banner_id" id="input" style="width: 100%;">
								{if $item.mbi_item_type == 'banner'}<option value="{$item.mbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if $mbi.banners}
								{foreach key=key item=item_ from=$mbi.banners name=pvs}<option value="{$item_.id}"{if $item_.id == $item.mbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						<div id="sel_informer" {if $item.mbi_item_type !== 'informer'}style="display: none;"{/if}>
						
							<select name="mbi_informer_id" id="input" style="width: 100%;">
								{if $item.mbi_item_type == 'informer'}<option value="{$item.mbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if $mbi.informers}
								{foreach key=key item=item_ from=$mbi.informers name=pvs}<option value="{$item_.id}"{if $item_.id == $item.mbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						</td>
						<td align="center"><input type="text" name="mbi_order" value="{$item.mbi_order}" size="3" id="input"></td>
						<td align="center">
							<select name="mbi_place" id="input">
								<option value="left" {if $item.mbi_place == 'left'}selected{/if}>в лево</option>
								<option value="right" {if $item.mbi_place == 'right'}selected{/if}>в право</option>
								<option value="center" {if $item.mbi_place == 'center'}selected{/if}>по центру</option>
							</select>
						</td>
						<td align="center">
							<select name="mbi_display_type" id="input">
								<option value="fixed" {if $item.mbi_display_type == 'fixed'}selected{/if}>постоянно</option>
								<option value="shuffle" {if $item.mbi_display_type == 'shuffle'}selected{/if}>вперемешку</option>
							</select>
						</td>
						<td align="center" colspan="2">
							<input name="save_mbi" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</td>
					</form>
					</tr>
					{else}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
						<td align="center">{$item.mbi_item_type}</td>
						<td align="center">
							{if $item.mbi_item_type == 'banner'}<a href="?show=banners&get=edit&item={$item.mbi_item_id}" title="Редактировать баннер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>
							{elseif $item.mbi_item_type == 'informer'}<a href="?show=informers&get=edit&item={$item.mbi_item_id}" title="Редактировать информер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>{/if}
						</td>
						<td align="center">{$item.mbi_order}</td>
						<td align="center">{$item.mbi_place}</td>
						<td align="center">{$item.mbi_display_type}</td>
						<td align="center">
							{*{if !empty($item.mbi_page_id) && $item.mbi_page_id == $page_item.p_id}*}
								<a href="?show=main&get=banner_informer&mbi={$item.mbi_id}#mbi" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
							{*{/if}*}
						</td>
						<td align="center">
						<form method="post" action="?show=main&get=banner_informer#mbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="mbi_id" value="{$item.mbi_id}">
							{*{if !empty($item.mbi_page_id) && $item.mbi_page_id == $page_item.p_id}*}
								<input name="delete_mbi" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
							{*{/if}*}
						</form>
						</td>
					</tr>
					{/if}
				{/if}
				{/foreach}
				<tr>
					<th colspan="7">по центру</th>
				</tr>
				{foreach key=key item=item from=$main_ban_inf_list name=mbi}
				{if $item.mbi_place == 'center'}
					{if !empty($smarty.get.mbi) && $item.mbi_id == $smarty.get.mbi}
					<tr>
					<form method="post" name="f_informer" action="?show=main&get=banner_informer#mbi" onsubmit="if (!confirm('Вы уверены?')) return false">
						<input type="hidden" name="mbi_id" value="{$item.mbi_id}">
						<td align="center">
							<select name="mbi_item_type" id="input" onchange="document.getElementById('sel_banner').style.display = 'none'; document.getElementById('sel_informer').style.display = 'none'; document.getElementById('sel_'+this.value).style.display = 'inline'; ">
								<option value="banner" {if $item.mbi_item_type == 'banner'}selected{/if}>баннер</option>
								<option value="informer" {if $item.mbi_item_type == 'informer'}selected{/if}>информер</option>
							</select>
						</td>
						<td align="center">
						<div id="sel_banner" {if $item.mbi_item_type !== 'banner'}style="display: none;"{/if}>
							<select name="mbi_banner_id" id="input" style="width: 100%;">
								{if $item.mbi_item_type == 'banner'}<option value="{$item.mbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if $mbi.banners}
								{foreach key=key item=item_ from=$mbi.banners name=pvs}<option value="{$item_.id}"{if $item_.id == $item.mbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						<div id="sel_informer" {if $item.mbi_item_type !== 'informer'}style="display: none;"{/if}>
							<select name="mbi_informer_id" id="input" style="width: 100%;">
								{if $item.mbi_item_type == 'informer'}<option value="{$item.mbi_item_id}" selected>{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</option>{/if}
							{if $mbi.informers}
								{foreach key=key item=item_ from=$mbi.informers name=pvs}<option value="{$item_.id}"{if $item_.id == $item.mbi_item_id} selected{/if}>{if $item_.title == ''}без названия{else}{$item_.title}{/if}</option>
								{/foreach}
							{/if}
							</select>
						</div>
						</td>
						<td align="center"><input type="text" name="mbi_order" value="{$item.mbi_order}" size="3" id="input"></td>
						<td align="center">
							<select name="mbi_place" id="input">
								<option value="left" {if $item.mbi_place == 'left'}selected{/if}>в лево</option>
								<option value="right" {if $item.mbi_place == 'right'}selected{/if}>в право</option>
								<option value="center" {if $item.mbi_place == 'center'}selected{/if}>по центру</option>
							</select>
						</td>
						<td align="center">
							<select name="mbi_display_type" id="input">
								<option value="fixed" {if $item.mbi_display_type == 'fixed'}selected{/if}>постоянно</option>
								<option value="shuffle" {if $item.mbi_display_type == 'shuffle'}selected{/if}>вперемешку</option>
							</select>
						</td>
						<td align="center" colspan="2">
							<input name="save_mbi" type="submit" style="background: white url(images/yes.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
						</td>
					</form>
					</tr>
					{else}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
						<td align="center">{$item.mbi_item_type}</td>
						<td align="center">
							{if $item.mbi_item_type == 'banner'}<a href="?show=banners&get=edit&item={$item.mbi_item_id}" title="Редактировать баннер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>
							{elseif $item.mbi_item_type == 'informer'}<a href="?show=informers&get=edit&item={$item.mbi_item_id}" title="Редактировать информер">{if $item.item.title == ''}без названия{else}{$item.item.title}{/if}</a>{/if}
						</td>
						<td align="center">{$item.mbi_order}</td>
						<td align="center">{$item.mbi_place}</td>
						<td align="center">{$item.mbi_display_type}</td>
						<td align="center">
							{*{if !empty($item.mbi_page_id) && $item.mbi_page_id == $page_item.p_id}*}
								<a href="?show=main&get=banner_informer&mbi={$item.mbi_id}#mbi" style="background: white url(images/edit.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0; display: block;"> </a>
							{*{/if}*}
						</td>
						<td align="center">
						<form method="post" action="?show=main&get=banner_informer#mbi" onsubmit="if (!confirm('Вы уверены?')) return false">
							<input type="hidden" name="mbi_id" value="{$item.mbi_id}">
							{*{if !empty($item.mbi_page_id) && $item.mbi_page_id == $page_item.p_id}*}
								<input name="delete_mbi" type="submit" style="background: white url(images/delete.gif) no-repeat center; width: 16px; height: 16px; border: 0; margin: 0 5px 0 0;" value=" ">
							{*{/if}*}
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