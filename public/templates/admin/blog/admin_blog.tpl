<H1>Блог:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="20%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=blog{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">Список записей</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=blog&get=edit&item={$smarty.get.item}">Редактировать запись</a>{else}Редактировать запись{/if}</td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=blog&get=add">Добавить запись</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' || $smarty.get.get == 'categedit' || $smarty.get.get == 'categadd')}active{else}notactive{/if}"><a href="?show=blog&get=categories">Рубрики</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=blog&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if !empty($smarty.get.get) && ($smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd')}
		{include file="blog/admin_blog_categories.tpl"}
	{/if}
	
	{if empty($smarty.get.get)}
		<h1>Список записей</h1>
		
		
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<td align="center" colspan="6">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=blog&nnc='+this.value">
							<option value="all"{if !empty($smarty.get.nnc) && $smarty.get.nnc == 'all'} selected{/if}>все</option>
						{if $blog_categories_list}
						{foreach key=key item=item from=$blog_categories_list name=nc}
							<option value="{$item.nc_id}"{if !empty($smarty.get.nnc) && $smarty.get.nnc == $item.nc_id} selected{/if}>{$item.nc_title_ru}</option>
						{/foreach}
						{/if}
					</select>
					<br><br>
				</td>
			</tr>
			{if $post_list}
			{foreach key=key item=item from=$post_list name=blog}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
					<td><a href="?show=blog&get=edit&item={$item.id}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}">{if $item.title == ''}<font style="color: #f00;">Без названия</font>{else}{$item.title}{/if}</a></td>
					<td><nobr>{$item.date_show|date_format:"%d.%m.%Y %H:%M"}&nbsp;&nbsp;</nobr></td>
					<td>{$item.author_type}</td>
					<td>{$item.lang}</td>
					<td width="16" align="center">{if $item.is_top>0}<img src="images/top_{$item.is_top}.gif" alt="top {$item.is_top}" border="0">{/if}</td>
					<td width="16">{if $item.is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			{if $post_pages|@count >1}
				<div class="pages"><i>Страницы: </i>
					{foreach key=key item=item from=$post_pages}
						{if !empty($smarty.get.page) && $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=blog&page={$key}{if !empty($smarty.get.nnc)}&nnc={$smarty.get.nnc}{/if}">{$item}</a>{/if}
					{/foreach}
				</div>
			{/if}
		
			<br>
		
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить запись</h1>
		<center>
		<form method="post">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">верхняя запись 1 из 4-х (на главной): </th>
							<td width="50%" align="center">
								<select name="is_top" id="input100">
									<option value="0">----------</option>
									<option value="1" >верхняя запись №1</option>
									<option value="2" >верхняя запись №2</option>
									<option value="3" >верхняя запись №3</option>
									<option value="4" >верхняя запись №4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">фотография внутри новости вверху: </th>
							<td width="50%" align="center"><input type="Checkbox" name="is_photo_top" checked="checked"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center"><nobr>
								<select name="date_day" id="input">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="date_month" id="input">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="date_year" id="input">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
								
								<select name="date_hour" id="input">
								{section name = hour start = 0 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="date_minute" id="input">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $smarty.now|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select></nobr>
							</td>
						</tr>
                        <tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">язык: </th>
							<td>
								<select name="lang" id="input">
									<option value="ru">русский</option>
									<option value="ua">украинский</option>
									<option value="en">английский</option>
								</select>
							</td>
						</tr>
                        <tr>
							<td colspan="2" height="10"></td>
						</tr>
						{*
						<tr style="background: #eee">
							<th width="50%" align="center">Страны: </th>
							<td width="50%" align="center">
								<input id="country_auto_val" type="hidden" name="country_auto_val" vlue="">
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
                        <tr style="background: #eee">
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr style="background: #eee">
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
                        <tr style="background: #eee">
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr style="background: #eee">
                            <th align="center">Игра (матч): </th>
                            <td>
                                <div class="div_30">Дата: </div>
                                <div class="div_70">
                                    <input type="text" name="game_date" value="{$smarty.now|date_format:"%d.%m.%Y"}" class="input50 input_date game_date"><br>
                                </div>
                                <div class="div_30">Игра: </div>
                                <div class="div_70">
                                    <select name="game_id" class="game_list_by_date input100">
                                        <option value="">----------------</option>
                                    </select>
                                </div>
                                <div class="div_30">Добавлено: </div>
                                <div class="div_100 game_list_added">
                                    <input id="games_auto_val" type="hidden" name="game_auto_val" value="">

                                </div>
                            </td>
                        </tr>
                        <tr style="background: #eee">
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr style="background: #eee">
                            <th align="center">Человек: </th>
                            <td>
                                <div class="div_30">Имя: </div>
                                <div class="div_70">
                                    <input id="staff_input_id" type="text" value="" placeholder="Начните писать имя" class="input100" style="text-align: left; width: 99%;"/>
                                </div>
                                <div class="div_30">Добавлено: </div>
                                <div class="div_100 staff_list_added">
                                    <input id="staff_auto_val" type="hidden" name="staff_auto_val" value="{if !empty($post_item.staff_auto_val)}{$post_item.staff_auto_val}{/if}">
                                </div>
                            </td>
                        </tr>
                        *}
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_post" id="submitsave" value="Добавить">
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="25%" align="center">подпись: </th>
							<td width="25%" align="center">
								<input type="text" name="sign" id="input100">
							</td>
                            <th width="25%" align="center">url к подписи: </th>
							<td width="25%" align="center">
								<input type="text" name="sign_url" id="input100">
							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Адрес: </th>
					<td width="80%"><input type="text" name="address" id="input100"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Название: </th>
					<td width="80%"><input type="text" name="title" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание:</b> <br><textarea name="description" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст:</b> <br><textarea name="text" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'edit' && $smarty.get.item>0 && !empty($post_item)}
		<h1>Редактирование записи: &laquo;{$post_item.title}&raquo;</h1>
		<center>
			<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false"{if !empty($smarty.get.g_get)}class="form_disabled"{/if}>
				<input type="Hidden" name="id" value="{$post_item.id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="is_active"{if $post_item.is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">верхняя запись 1 из 4-х (на главной)ba: </th>
							<td width="50%" align="center">
								<select name="is_top" id="input100">
									<option value="0"{if $post_item.is_top == 0} selected{/if}>----------</option>
									<option value="1"{if $post_item.is_top == 1} selected{/if}>верхняя запись №1</option>
									<option value="2"{if $post_item.is_top == 2} selected{/if}>верхняя запись №2</option>
									<option value="3"{if $post_item.is_top == 3} selected{/if}>верхняя запись №3</option>
									<option value="4"{if $post_item.is_top == 4} selected{/if}>верхняя запись №4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">фотография внутри новости вверху: </th>
							<td width="50%" align="center"><input type="Checkbox" name="is_photo_top"{if $post_item.is_photo_top == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center"><nobr>
								<select name="date_day" id="input">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $post_item.date_show|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="date_month" id="input">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $post_item.date_show|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="date_year" id="input">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $post_item.date_show|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
								
								
								<select name="date_hour" id="input">
								{section name = hour start = 0 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $post_item.date_show|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="date_minute" id="input">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $post_item.date_show|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select></nobr>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">язык: </th>
							<td>
								<select name="lang" id="input">
									<option value="ru"{if $post_item.lang == 'ru'} selected{/if}>русский</option>
									<option value="ua"{if $post_item.lang == 'ua'} selected{/if}>украинский</option>
									<option value="en"{if $post_item.lang == 'en'} selected{/if}>английский</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
							{*
						<tr style="background: #eee">
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="country_auto_val" type="hidden" name="country_auto_val" value="{$post_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $post_item.connection_country}
                                        {foreach item=item_cc from=$post_item.connection_country name=connection_country}
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
                                <input id="champ_auto_val" type="hidden" name="champ_auto_val" value="{$post_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $post_item.connection_champ}
                                        {foreach item=item_cc from=$post_item.connection_champ name=connection_champ}
                                            <span class="selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th align="center">Игра (матч): </th>
                            <td>
                                <div class="div_30">Дата: </div>
                                <div class="div_70">
                                    <input type="text" name="game_date" value="{$smarty.now|date_format:"%d.%m.%Y"}" class="input50 input_date game_date"><br>
                                </div>
                                <div class="div_30">Игра: </div>
                                <div class="div_70">
                                    <select name="game_id" class="game_list_by_date input100">
                                        <option value="">----------------</option>
                                        {if $post_item.connection_games}
                                            {foreach key=key item=item from=$post_item.connection_games name=cc}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </div>
                                <div class="div_30">Добавлено: </div>
                                <div class="div_100 game_list_added">
                                    <input id="games_auto_val" type="hidden" name="game_auto_val" value="{$post_item.games_auto_val}">
                                    {if $post_item.connected_games}
                                        {foreach key=key item=item from=$post_item.connected_games name=gla}
                                            <span class="selected_game_s" data-id="{$item.id}">{$item.title}</span>
                                        {/foreach}
                                    {/if}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th align="center">Человек: </th>
                            <td>
                                <div class="div_30">Имя: </div>
                                <div class="div_70">
                                    <select name="staff_id" class="staff_list_by_name input100">
                                        <option value="">----------------</option>
                                        {if $post_item.connection_staff}
                                            {foreach key=key item=item from=$post_item.connection_staff name=cc}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </div>
                                <div class="div_30">Добавлено: </div>
                                <div class="div_100 staff_list_added">
                                    <input id="staff_auto_val" type="hidden" name="staff_auto_val" value="{$post_item.staff_auto_val}">
                                    {if !empty($post_item.connected_staff)}
                                        {foreach key=key item=item from=$post_item.connected_staff name=gla}
                                            <span class="selected_staff_s" data-id="{$item.id}">{$item.title}</span>
                                        {/foreach}
                                    {/if}
                                </div>
                            </td>
                        </tr>
                        *}
						</table>
					</td>
					<td width="50%" align="center" valign="top">
						<a href="{$sitepath}blog/{$post_item.address}" target="_blank">Посмотреть запись</a>
						<br><br><br>
						<input type="submit" name="save_post_changes" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_post" id="submitdelete" value="Удалить">
						
						<br><br><br><br>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%" align="center">время добавления: </th>
                                <td width="50%" align="center">{$post_item.datetime_add}</td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">время редактирования: </th>
                                <td width="50%" align="center">{$post_item.datetime_edit}</td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">пользователь<br> (последнее редактирование): </th>
                                <td width="50%" align="center">{if $post_item.author_type == 'admin'}Admin: {$authors[$post_item.author].f_name} {$authors[$post_item.author].name}{/if}</td>
                            </tr>
						</table>
						<br><br>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="25%" align="center">подпись: </th>
                            <td width="25%" align="center">
                                <input type="text" name="sign" value="{$post_item.sign}" id="input100">
                            </td>
                            <th width="25%" align="center">url к подписи: </th>
                            <td width="25%" align="center">
                                <input type="text" name="sign_url" value="{$post_item.sign_url}" id="input100">
                            </td>
                        </tr>
					</table>
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">Содержание</a></td>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Адрес: </th>
					<td width="80%"><input type="text" name="address" value="{$post_item.address}" id="input100"></td>
				</tr>
				<tr>
					<th width="20%" align="center">Название: </th>
					<td width="80%"><input type="text" name="title" value="{$post_item.title}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание:</b> <br><textarea name="description" rows="5" style="width: 100%;">{$post_item.description}</textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст:</b> <br><textarea name="text" style="width: 100%; height: 400px;">{$post_item.text}</textarea></td></tr>
			</table>
		</div>
		</form>
		{* =========== ГАЛЕРЕЯ ============ *}
		<div id="cont_4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="25%" id="notactive"><a href="?show=blog&get=edit&item={$smarty.get.item}">Содержание</a></td>
					<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_4');">Галерея</a></td>
				</tr>
			</table>
			<h1>Галерея</h1>
			
			{include file="blog/admin_blog_gallery.tpl"}
		</div>
            {include file="admin_meta_seo.tpl" meta_seo_item_type="blog" meta_seo_item_id="`$post_item.id`"}
		<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'settings'}
		<h1>Настройки</h1>
		
		<center>
		{*
		<b>Вкл/откл блока новостей слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $blog_settings_list.is_active_blog_left > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_blog_left_active_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		*}
		<b>Количество новостей в блоке новостей на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{if !empty($blog_settings_list.count_blog_left)}{$blog_settings_list.count_blog_left}{/if}"></td>
					<td width="33%" align="center"><input type="submit" name="save_blog_left_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество новостей на одну страницу в разделе новостей:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{if !empty($blog_settings_list.count_blog_page)}{$blog_settings_list.count_blog_page}{/if}"></td>
					<td width="33%" align="center"><input type="submit" name="save_blog_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество новостей на первой странице в разделе новостей:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{if !empty($blog_settings_list.count_blog_page_index)}{$blog_settings_list.count_blog_page_index}{/if}"></td>
					<td width="33%" align="center"><input type="submit" name="save_blog_index_count_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Количество знаков новости (меньше - список перейдет из левой колонки под запись):</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">число:</td>
					<td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{if !empty($blog_settings_list.count_blog_max_char)}{$blog_settings_list.count_blog_max_char}{/if}"></td>
					<td width="33%" align="center"><input type="submit" name="save_blog_max_char" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="blog/admin_title_blog.tpl"}
		{include file="blog/admin_title_blog_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>