
<H1>Страницы сайта:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=pages">Список страниц</a></td>
				<td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=pages&get=edit&item={$smarty.get.item}">Редактировать страницу</a>{else}Редактировать страницу{/if}</td>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=pages&get=add">Добавить страницу</a></td>
			</tr>
		</table>
		
	{if empty($smarty.get.get)}
		<h1>Список страниц</h1>

        {if $pages_list}
            <ul class="page_list" >
            {foreach key=key item=item from=$pages_list name=page}
                {if $smarty.foreach.page.first}
                    {assign var="nesting_prev" value=$item.nesting}
                {else}
                    {if $item.nesting == $nesting_prev}
                        </li>
                    {else}
                        {if $nesting_prev<$item.nesting}
                            <ul>
                        {else}
                            </li>
                            {section name = nesting_p start = 0 loop = $nesting_prev-$item.nesting}
                                </ul></li>
                            {/section}
                        {/if}
                        {assign var="nesting_prev" value=$item.nesting}
                    {/if}
                {/if}
            <li data-nesting="{$item.nesting}" onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" ><em></em>
                {assign var="border_page" value=$item.nesting}
                {assign var="border_page" value=$border_page*20}
                <a href="?show=pages&get=edit&item={$item.p_id}">{if $item.p_title_ru != ''}{$item.p_title_ru}{else}<span style="color:#f00;">БЕЗ НАЗВАНИЯ!!!</span>{/if}</a>
                <span class="ico">{if $item.p_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</span>
                <span class="ico">{if $item.p_is_menu == 'yes'}<img src="images/menu_yes.gif" alt="в меню" border="0">{else}<img src="images/menu_no.gif" alt="скрыт" border="0">{/if}</span>
                <span class="ico">{if $item.p_is_f_menu == 'yes'}<img src="images/menu_yes.gif" alt="в меню" border="0">{else}<img src="images/menu_no.gif" alt="скрыт" border="0">{/if}</span>
                <span class="ico">{assign var="prev" value=$key-1}{if $smarty.foreach.page.first or $item.nesting>$pages_list.$prev.nesting}{else}<a href="?show=pages&do=up&item={$item.p_id}"><img src="images/up.gif" width="16" height="16" alt="" border="0"></a>{/if}</span>
                <span class="ico">{assign var="next" value=$key+1}{if $smarty.foreach.page.last or $item.nesting>$pages_list.$next.nesting}{else}<a href="?show=pages&do=down&item={$item.p_id}"><img src="images/down.gif" width="16" height="16" alt="" border="0"></a>{/if}</span>
                {if $smarty.foreach.page.last}
                    </li>
                {/if}
            {/foreach}
            {if $nesting_prev>0}
                {section name = "nesting_p" start = 0 loop = $nesting_prev+1}
                    </ul></li>
                {/section}
            {/if}
            </ul>
        {else}
			Страниц нет.
		{/if}
			<br>
		
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить страницу</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="p_is_active" checked></td>
						</tr>
						<tr>
							<th align="center">отображать в меню: </th>
							<td align="center"><input type="Checkbox" name="p_is_menu" checked></td>
						</tr>
                        <tr>
                            <th align="center">выпадающее верхнее меню: </th>
                            <td align="center"><input type="Checkbox" name="p_is_menu_dropdown"></td>
                        </tr>
						<tr>
							<th align="center">отображать в нижнем меню: </th>
							<td align="center"><input type="Checkbox" name="p_is_f_menu"></td>
						</tr>
						<tr>
							<th align="center">поместить в раздел: </th>
							<td>
								<select name="p_parent_id" id="input100">
									<option value="0"{if !empty($page_item.p_parent_id)} selected{/if}>главный</option>
								{if !empty($pages_parent)}
                                    {foreach key=key item=item from=$pages_parent}
                                    <option value="{$item.p_id}"{if !empty($smarty.get.item) && $smarty.get.item == $item.p_id} selected{/if}>
                                        {section name = mySection start = 0 loop = $item.nesting}../{/section}{$item.p_title_ru}
                                    </option>
                                    {/foreach}
                                {/if}
								</select>
							</td>
						</tr>
						<tr>
							<th align="center">позиция: </th>
							<td><input type="text" name="p_order" value="{if !empty($page_item.p_order)}{$page_item.p_order}{/if}" id="input100"></td>
						</tr>
						<tr>
							<th align="center">адрес: </th>
							<td><input type="text" name="p_adress" value="{if !empty($page_item.p_adress)}{$page_item.p_adress}{/if}" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_page" id="submitsave" value="Добавить">
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="p_title_ru" value="{if !empty($page_item.p_title_ru)}{$page_item.p_title_ru}{/if}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="p_description_ru" rows="5" style="width: 100%;">{if !empty($page_item.p_description_ru)}{$page_item.p_description_ru}{/if}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="p_text_ru" style="width: 100%; height: 400px;">{if !empty($page_item.p_text_ru)}{$page_item.p_text_ru}{/if}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="p_title_ua" value="{if !empty($page_item.p_title_ua)}{$page_item.p_title_ua}{/if}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="p_description_ua" rows="5" style="width: 100%;">{if !empty($page_item.p_description_ua)}{$page_item.p_description_ua}{/if}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="p_text_ua" style="width: 100%; height: 400px;">{if !empty($page_item.p_text_ua)}{$page_item.p_text_ua}{/if}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="p_title_en" value="{if !empty($page_item.p_title_en)}{$page_item.p_title_en}{/if}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="p_description_en" rows="5" style="width: 100%;">{if !empty($page_item.p_description_en)}{$page_item.p_description_en}{/if}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="p_text_en" style="width: 100%; height: 400px;">{if !empty($page_item.p_text_en)}{$page_item.p_text_en}{/if}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			Настройки будут доступны после добавления страницы.
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && $smarty.get.item>0}
		<h1>Редактирование страницы: &laquo;{$page_item.p_title_ru}&raquo;</h1>
		<center>
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="p_is_active"{if $page_item.p_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th align="center">отображать в меню: </th>
							<td align="center"><input type="Checkbox" name="p_is_menu"{if $page_item.p_is_menu == 'yes'} checked{/if}></td>
						</tr>
                        <tr>
                            <th align="center">выпадающее верхнее меню: </th>
                            <td align="center"><input type="Checkbox" name="p_is_menu_dropdown" {if !empty($page_item.p_is_menu_dropdown) && $page_item.p_is_menu_dropdown == 'yes'} checked{/if}></td>
                        </tr>
						<tr>
							<th align="center">отображать в нижнем меню: </th>
							<td align="center"><input type="Checkbox" name="p_is_f_menu" {if $page_item.p_is_f_menu == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<th align="center">поместить в раздел: </th>
							<td>
								<select name="p_parent_id" id="input100">
									<option value="0"{if $page_item.p_parent_id == 0} selected{/if}>главный</option>
								{foreach key=key item=item from=$pages_parent}
                                    {if $item.p_id != $page_item.p_id}
                                        <option value="{$item.p_id}"{if $page_item.p_parent_id == $item.p_id} selected{/if}>
                                        {section name = mySection start = 0 loop = $item.nesting}../{/section}{$item.p_title_ru}
                                        </option>
                                    {/if}
                                {/foreach}
								</select>
						</tr>
						<tr>
							<th align="center">позиция: </th>
							<td><input type="text" name="p_order" value="{$page_item.p_order}" id="input100"></td>
						</tr>
						<tr>
							<th align="center">адрес: </th>
							<td><input type="text" name="p_adress" value="{$page_item.p_adress}" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						{if $page_item.p_parent_id >0}<a href="?show=pages&get=edit&item={$page_item.p_parent_id}"><-Раздел выше</a><br>{/if}
						
						<a href="?show=pages&get=add&item={$page_item.p_id}">Добавить подстраницу -></a>
						<br>
						<br>
						<input type="submit" name="save_page_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_page" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="p_title_ru" value="{$page_item.p_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="p_description_ru" rows="5" style="width: 100%;">{$page_item.p_description_ru}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="p_text_ru" style="width: 100%; height: 400px;">{$page_item.p_text_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="p_title_ua" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="p_description_ua" rows="5" style="width: 100%;">{$page_item.p_description_ua}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="p_text_ua" style="width: 100%; height: 400px;">{$page_item.p_text_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<table width="99%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="p_title_en" value="{$page_item.p_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="p_description_en" rows="5" style="width: 100%;">{$page_item.p_description_en}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="p_text_en" style="width: 100%; height: 400px;">{$page_item.p_text_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
					<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Настройки</a></td>
				</tr>
			</table>
			<br>
			<br>
			{*
			<form method="post" enctype="multipart/form-data">
				<input type="Hidden" name="page_id" value="{$page_item.p_id}">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="40%" align="center">Добавить картинку: </th>
						<td width="30%" align="center">
							<input type="File" name="page_image" id="input100">
						</td>
						<td width="30%" align="center"><input type="submit" name="save_page_image" id="submitsave" value="Загрузить"></td>
					</tr>
				</table>
			</form>
			<br>
			{if $page_images}
				<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				{foreach key=key item=item from=$page_images}
				<form method="post" enctype="multipart/form-data">
					<input type="Hidden" name="page_image_id" value="{$item.mi_id}">
					<tr>
						<td width="40%" align="center"><img src="../upload/page_images/{$item.mi_path}" border="0"></td>
						<td width="30%" align="center">
							Заменить:<br>
							<input type="File" name="page_image" id="input100">
							<br>
							<br>
							Вкл./откл.: <input type="checkbox" name="page_image_is_active"{if $item.mi_is_active == 'yes'} checked{/if}>
						</td>
						<td width="30%" align="center">
							<input type="submit" name="save_edited_page_image" id="submitsave" value="Сохранить">
							<br>
							<br>
							<input type="submit" name="delete_edited_page_image" id="submitsave" value="Удалить">
						</td>
					</tr>
				</form>
				{/foreach}
				</table>
			{/if}
			*}
		<div id="conteiner" style="margin: 10px;">
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4">
				<input type="Hidden" name="p_id" value="{$page_item.p_id}">
			<tr>
				<th colspan="2" align="center">Модуль к странице: </th>
			</tr>
			<tr>
				<td align="center" width="50%">
				{if $modules_list}
					<select name="p_mod_id" id="input100">
						{foreach key=key item=item from=$modules_list}<option value="{$item.mod_id}"{if $item.mod_id == $page_item.p_mod_id} selected{/if}>{$item.mod_title}</option>{/foreach}
					</select>
				{/if}
				</td>
				<td align="center" width="50%">
					<input type="submit" name="save_page_module" id="submitsave" value="Сохранить">
				</td>
			</tr>
			</form>
			</table>
			<br>
			{if $page_item.p_mod_id>0 and $page_item.mod.mod_a_template != ''}
				{assign var=template value=$page_item.mod.mod_a_template}
				{include file="pages/$template"}
			{/if}
		</div>
		
		{include file="pages/admin_pages_atachments.tpl"}
		{include file="pages/admin_pages_settings.tpl"}
		{include file="pages/admin_pages_photos.tpl"}
		{include file="pages/admin_pages_pvs.tpl"}
		{include file="pages/admin_pages_banner_informer.tpl"}
		
		</div>
        {include file="admin_meta_seo.tpl" meta_seo_item_type="page" meta_seo_item_id="`$page_item.p_id`"}
		<br>
		</center>
	{/if}
	</div>