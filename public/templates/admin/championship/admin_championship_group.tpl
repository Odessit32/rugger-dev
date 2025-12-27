		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'group'}active_left{else}notactive{/if}"><a href="?show=championship&get=group">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'groupedit'}active{else}notactive{/if}">{if !empty($smarty.get.item)}<a href="?show=championship&get=groupedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'groupadd'}active_right{else}notactive{/if}"><a href="?show=championship&get=groupadd">Добавить</a></td>
			</tr>
		</table>
	
	{if !empty($smarty.get.get) && $smarty.get.get == 'group'}
		<h1>Список групп чемпионатов</h1>
		<center>
		{if $championship_group_list}
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{foreach key=key item=item from=$championship_group_list}
				<tr>
					<td><a href="?show=championship&get=groupedit&item={$item.chg_id}">{if $item.chg_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.chg_title_ru}{/if}</a></td>
					<td>{assign var="chg_chl_id" value=$item.chg_chl_id}{$championship_local_list_id.$chg_chl_id}</td>
					<td>{$item.chg_address}</td>
					<td>{if $item.chg_is_main == 'yes'}<img src="images/main.gif" alt="главная" border="0">{/if}</td>
					<td>{if $item.chg_is_menu == 'no'}<img src="images/menu_no.gif" alt="скрыт" border="0">{else}<img src="images/menu_yes.gif" alt="в меню" border="0">{/if}</td>
					<td>{if $item.chg_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			</table>
		{else}
			Нет групп.<br>
		{/if}
			<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'groupadd'}
		<h1>Добавить группу чемпионата</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">вкл/откл: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="chg_is_active" checked></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Главная группа: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="chg_is_main"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Отображать в меню: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="chg_is_menu"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Порядок: </th>
                                <td width="50%" align="center"><input type="text" name="chg_order" id="input" size="5"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Локализация: </th>
                                <td width="50%" align="center">
                                    <select name="chg_chl_id" id="input100">
                                        <option value="0">--------------------</option>
                                        {if $championship_local_list}
                                            {foreach key=key item=item from=$championship_local_list name=local}
                                                <option value="{$item.chl_id}">{$item.title}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Адресс: </th>
                                <td width="50%" align="center"><input type="text" name="chg_address" id="input100"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Страны: </th>
                                <td width="50%" align="center">
                                    <input id="country_auto_val" type="hidden" name="country_auto_val" value="">
                                    {if $country_list}
                                        <select name="country_" id="country_auto" class="country_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$country_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
                                    {else}
                                        <b>нет добавленных стран</b>
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Сущность Чемпионаты: </th>
                                <td width="50%" align="center">
                                    <input id="champ_auto_val" type="hidden" name="champ_auto_val" vlue="">
                                    {if $champ_list}
                                        <select name="champ_" id="champ_auto" class="champ_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$champ_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
                                    {else}
                                        <b>нет добавленных чемпионатов</b>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_group" id="submitsave" value="Добавить">
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
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="chg_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="chg_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="chg_title_ua" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="chg_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="chg_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="chg_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'groupedit'}
		<h1>Редактирование группы чемпионата: &laquo;{$group_item.chg_title_ru}&raquo;</h1>
		<center>
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="chg_id" value="{$group_item.chg_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">вкл/откл: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="chg_is_active" {if $group_item.chg_is_active == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Главная группа: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="chg_is_main"{if $group_item.chg_is_main == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Отображать в меню: </th>
                                <td width="50%" align="center"><input type="Checkbox" name="chg_is_menu"{if $group_item.chg_is_menu == 'yes'} checked{/if}></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Порядок: </th>
                                <td width="50%" align="center"><input type="text" name="chg_order" id="input" size="5" value="{$group_item.chg_order}"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Локализация: </th>
                                <td width="50%" align="center">
                                    <select name="chg_chl_id" id="input100">
                                        <option value="0"{if $group_item.chg_chl_id == 0} selected{/if}>--------------------</option>
                                        {if $championship_local_list}
                                            {foreach key=key item=item from=$championship_local_list name=local}
                                                <option value="{$item.chl_id}"{if $group_item.chg_chl_id == $item.chl_id} selected{/if}>{$item.title}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Адресс: </th>
                                <td width="50%" align="center"><input type="text" name="chg_address" value="{$group_item.chg_address}" id="input100"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Страны: </th>
                                <td width="50%" align="center">
                                    <input id="country_auto_val" type="hidden" name="country_auto_val" value="{$group_item.connection_country_val}">
                                    {if $country_list}
                                        <select name="country_" id="country_auto" class="country_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$country_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
                                        {if $group_item.connection_country}
                                            {foreach item=item_cc from=$group_item.connection_country name=connection_country}
                                                <span class="selected_country" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                            {/foreach}
                                        {/if}
                                    {else}
                                        <b>нет добавленных стран</b>
                                    {/if}

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Сущность Чемпионаты: </th>
                                <td width="50%" align="center">
                                    <input id="champ_auto_val" type="hidden" name="champ_auto_val" value="{$group_item.connection_champ_val}">
                                    {if $champ_list}
                                        <select name="champ_" id="champ_auto" class="champ_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$champ_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
                                        {if $group_item.connection_champ}
                                            {foreach item=item_cc from=$group_item.connection_champ name=connection_champ}
                                                <span class="selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                            {/foreach}
                                        {/if}
                                    {else}
                                        <b>нет добавленных чемпионатов</b>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_group_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_group" id="submitdelete" value="Удалить">
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
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%" colspan="3"><input type="text" name="chg_title_ru" value="{$group_item.chg_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="chg_description_ru" rows="5" style="width: 100%;">{$group_item.chg_description_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%" colspan="3"><input type="text" name="chg_title_ua" value="{$group_item.chg_title_ua}" value="{$page_item.p_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="chg_description_ua" rows="5" style="width: 100%;">{$group_item.chg_description_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%" colspan="3"><input type="text" name="chg_title_en" value="{$group_item.chg_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="chg_description_en" rows="5" style="width: 100%;">{$group_item.chg_description_en}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	