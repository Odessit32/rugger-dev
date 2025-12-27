<H1>Анонсы:</H1>
<script type="text/javascript">
{literal}
	function showFormAnnounce(el) {
		InptEl = document.getElementById('anonce_'+el);
		if (InptEl.style.display == "none" || InptEl.style.display=="") {
			for (i = 1; i <= 2; i++) {
				document.getElementById("anonce_"+i).style.display = "none";
			}
			InptEl.style.display = "block";
			if (el == 1) document.forms.event.an_type.value='event';
			if (el == 2) document.forms.event.an_type.value='game';
		}
	}

	function otherTeam(el, select) {
		InptEl = document.getElementById('team_'+el);
		if (select == '-1') {
			if (InptEl.style.display == "none" || InptEl.style.display=="") {
				InptEl.style.display = "block";
			}
		} else {
			InptEl.style.display = "none";
		}
	}
{/literal}
</script>
	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="25%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=announce">Список анонсов</a></td>
				<td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=announce&get=edit&item={$smarty.get.item}">Редактировать анонс</a>{else}Редактировать анонс{/if}</td>
				<td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=announce&get=add">Добавить анонс</a></td>
				<td width="25%" id="{if $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=announce&get=settings">Настройки</a></td>
			</tr>
		</table>
	{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd'}
		{include file="announce/admin_announce_categories.tpl"}
	{/if}
	
	{if empty($smarty.get.get)}
		<h1>Список анонсов</h1>
		<center>
		
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<td align="center" colspan="6">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=announce&anc='+this.value">
							<option value="all"{if $smarty.get.anc == 'all'} selected{/if}>все</option>
							<option value="event"{if $smarty.get.anc == 'event'} selected{/if}>события</option>
							<option value="game"{if $smarty.get.anc == 'game'} selected{/if}>игры</option>
					</select>
					<br><br>
				</td>
			</tr>
			{if $announce_list}
			{foreach key=key item=item from=$announce_list name=announce}
				<tr>
					<td><a href="?show=announce&get=edit&item={$item.an_id}">
					{if $item.an_type == 'game'}
						{if $item.an_owner_t_id >0}
							{$item.an_owner_t_id_title}
						{elseif $item.an_owner_t_id <0}
							{$item.an_owner_t_title}
						{else}
						<font style="color: #f00;">Без названия</font>
						{/if}
						- 
						{if $item.an_guest_t_id >0}
							{$item.an_guest_t_id_title}
						{elseif $item.an_guest_t_id <0}
							{$item.an_guest_t_title}
						{else}
						<font style="color: #f00;">Без названия</font>
						{/if}
					{else}Событие #{$item.an_id}{/if}
						</a></td>
					<td>{$item.an_datetime|date_format:"%d.%m.%Y"}</td>
					<td>{if $item.an_type == 'game'}игра{elseif $item.an_type == 'event'}событие{/if}</td>
					<td>{if !empty($item.an_is_main) && $item.an_is_main == 'yes'}<img src="images/main.gif" alt="главная" border="0">{/if}</td>
					<td>{if !empty($item.an_is_active) && $item.an_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			{if $announce_pages|@count >1}
				<div class="pages"><i>Страницы: </i>
					{foreach key=key item=item from=$announce_pages}
						{if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=announce&page={$key}">{$item}</a>{/if}
					{/foreach}
				</div>
			{/if}
		
			<br>
		</center>
	{/if}
	{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
		<h1>Добавить анонс</h1>
		<center>
		<form method="POST" enctype="multipart/form-data" name="event">
			<input type="hidden" name="an_type" value="event">
			<div id="anonce_1">
			{* СОБЫТИЕ *}
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showFormAnnounce('1');">Событие</a></td>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showFormAnnounce('2');">Игра</a></td>
				</tr>
			</table>
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главный анонс: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_main"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">фото: </th>
							<td width="50%" align="center"><input type="file" name="an_photo_event" id="input100"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">ссылка: </th>
							<td width="50%" align="center"><input type="text" name="an_link" id="input100"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_announce" id="submitsave" value="Добавить">
					</td>
				</tr>
			</table>
			</div>
			<div id="anonce_2" style="display: none">
			{* ИГРА *}
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showFormAnnounce('1');">Событие</a></td>
					<td width="50%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showFormAnnounce('2');">Игра</a></td>
				</tr>
			</table>
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главный анонс: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_main"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center">
								<select name="an_date_day" id="input">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="an_date_month" id="input">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="an_date_year" id="input">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">время: </th>
							<td width="50%" align="center">
								<select name="an_date_hour" id="input">
								{section name = hour start = 0 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $smarty.section.hour.index == $smarty.now|date_format:"%H"} selected{/if}>{if $smarty.section.hour.index <10}0{/if}{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="an_date_minute" id="input">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $smarty.section.minute.index == $smarty.now|date_format:"%M"} selected{/if}>{if $smarty.section.minute.index <10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th colspan="2" height="10">команды:</th>
						</tr>
						<tr>
							<th width="50%" align="center">хозяева</th>
							<th width="50%" align="center">гости</th>
						</tr>
						<tr>
							<th colspan="2" height="10"></th>
						</tr>
						<tr>
							<td width="50%" align="center" valign="top">
								<select name="an_owner_t_id" onchange="otherTeam(1, this.value)" id="input">
									<option value="">----------</option>
									<option value="-1">-- другая команда --</option>
								{foreach key=key item=item from=$announce_teams name=teams}
									<option value="{$item.t_id}">{$item.title}</option>
								{/foreach}
								</select>
								<span id="team_1" style="display: none;">
									<br><b>название</b><br>
									<input type="text" name="an_owner_t_title" id="input">
									<br><b>логотип</b><br>
									<input type="file" name="an_owner_t_logo" id="input">
								</span>
							</td>
							<td width="50%" align="center" valign="top">
								<select name="an_guest_t_id" onchange="otherTeam(2, this.value)" id="input">
									<option value="">----------</option>
									<option value="-1">-- другая команда --</option>
								{foreach key=key item=item from=$announce_teams name=teams}
									<option value="{$item.t_id}">{$item.title}</option>
								{/foreach}
								</select>
								<span id="team_2" style="display: none;">
									<br><b>название</b><br>
									<input type="text" name="an_guest_t_title" id="input">
									<br><b>логотип</b><br>
									<input type="file" name="an_guest_t_logo" id="input">
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">ссылка: </th>
							<td width="50%" align="center"><input type="text" name="an_link" id="input100"></td>
						</tr>
						<tr>
							<th colspan="2" height="10"></th>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_announce" id="submitsave" value="Добавить">
					</td>
				</tr>
			</table>
			</div>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="an_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="an_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="an_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'edit' and $smarty.get.item>0}
		<h1>Редактирование анонса: &laquo;{$announce_item.an_title_ru}&raquo;</h1>
		<center>
			<form method="post" enctype="multipart/form-data" name="event">
				<input type="Hidden" name="an_id" value="{$announce_item.an_id}">
			{if $announce_item.an_type == 'event'}
			<div id="anonce_1">
			{* СОБЫТИЕ *}
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="active_left"><b>Событие</b></td>
					<td width="50%" id="notactive">Игра</td>
				</tr>
			</table>
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_active"{if $announce_item.an_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главный анонс: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_main"{if !empty($announce_item.an_is_main) && $announce_item.an_is_main == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">фото: </th>
							<td width="50%" align="center">
								{if $announce_item.an_photo_event != ''}<a href="../{$announce_item.an_photo_event}" target="_blank"><img src="../{$announce_item.an_photo_event}?{$smarty.now}" border="0" style="max-width: 200px; max-height: 200px;"></a><br>{/if}
								<input type="file" name="an_photo_event" id="input100">
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">ссылка: </th>
							<td width="50%" align="center"><input type="text" name="an_link" id="input100" value="{$announce_item.an_link}"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_announce_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_announce" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
			</div>
			{/if}
			{if $announce_item.an_type == 'game'}
			<div id="anonce_2">
			{* ИГРА *}
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="notactive">Событие</td>
					<td width="50%" id="active_right"><b>Игра</b></td>
				</tr>
			</table>
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_active"{if $announce_item.an_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главный анонс: </th>
							<td width="50%" align="center"><input type="Checkbox" name="an_is_main"{if !empty($announce_item.an_is_main) && $announce_item.an_is_main == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center">
								<select name="an_date_day" id="input">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $announce_item.an_datetime|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="an_date_month" id="input">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $announce_item.an_datetime|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="an_date_year" id="input">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $announce_item.an_datetime|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">время: </th>
							<td width="50%" align="center">
								<select name="an_date_hour" id="input">
								{section name = hour start = 0 loop = 24}
									<option value="{$smarty.section.hour.index}"{if $smarty.section.hour.index == $announce_item.an_datetime|date_format:"%H"} selected{/if}>{if $smarty.section.hour.index <10}0{/if}{$smarty.section.hour.index}</option>
								{/section}
								</select>
								<select name="an_date_minute" id="input">
								{section name = minute start = 0 loop = 60}
									<option value="{$smarty.section.minute.index}"{if $smarty.section.minute.index == $announce_item.an_datetime|date_format:"%M"} selected{/if}>{if $smarty.section.minute.index <10}0{/if}{$smarty.section.minute.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th colspan="2" height="10">команды:</th>
						</tr>
						<tr>
							<th width="50%" align="center">хозяева</th>
							<th width="50%" align="center">гости</th>
						</tr>
						<tr>
							<th colspan="2" height="10"></th>
						</tr>
						<tr>
							<td width="50%" align="center" valign="top">
								<select name="an_owner_t_id" onchange="otherTeam(1, this.value)" id="input">
									<option value=""{if '' == $announce_item.an_owner_t_id} selected{/if}>----------</option>
									<option value="-1"{if '-1' == $announce_item.an_owner_t_id} selected{/if}>-- другая команда --</option>
								{foreach key=key item=item from=$announce_teams name=teams}
									<option value="{$item.t_id}"{if $item.t_id == $announce_item.an_owner_t_id} selected{/if}>{$item.title}</option>
								{/foreach}
								</select>
								<span id="team_1" {if $announce_item.an_owner_t_id >=0}style="display: none;"{/if}>
									<br><b>название</b><br>
									<input type="text" name="an_owner_t_title" id="input" value="{$announce_item.an_owner_t_title}">
									<br><b>логотип</b><br>
									{if $announce_item.an_owner_t_logo != ''}<a href="../{$announce_item.an_owner_t_logo}" target="_blank"><img src="../{$announce_item.an_owner_t_logo}?{$smarty.now}" border="0" style="max-width: 200px; max-height: 200px;"></a><br>{/if}
									<input type="file" name="an_owner_t_logo" id="input">
								</span>
							</td>
							<td width="50%" align="center" valign="top">
								<select name="an_guest_t_id" onchange="otherTeam(2, this.value)" id="input">
									<option value=""{if '' == $announce_item.an_guest_t_id} selected{/if}>----------</option>
									<option value="-1"{if '-1' == $announce_item.an_guest_t_id} selected{/if}>-- другая команда --</option>
								{foreach key=key item=item from=$announce_teams name=teams}
									<option value="{$item.t_id}"{if $item.t_id == $announce_item.an_guest_t_id} selected{/if}>{$item.title}</option>
								{/foreach}
								</select>
								<span id="team_2" {if $announce_item.an_guest_t_id >=0}style="display: none;"{/if}>
									<br><b>название</b><br>
									<input type="text" name="an_guest_t_title" id="input" value="{$announce_item.an_guest_t_title}">
									<br><b>логотип</b><br>
									{if $announce_item.an_guest_t_logo != ''}<a href="../{$announce_item.an_guest_t_logo}" target="_blank"><img src="../{$announce_item.an_guest_t_logo}?{$smarty.now}" border="0" style="max-width: 200px; max-height: 200px;"></a><br>{/if}
									<input type="file" name="an_guest_t_logo" id="input">
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">ссылка: </th>
							<td width="50%" align="center"><input type="text" name="an_link" id="input100" value="{$announce_item.an_link}"></td>
						</tr>
						<tr>
							<th colspan="2" height="10"></th>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_announce_changes" id="submitsave" value="Сохранить">
						<br>
						<br>
						<input type="submit" name="delete_announce" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
			</div>
			{/if}
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="an_description_ru" rows="5" style="width: 100%;">{$announce_item.an_description_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="an_description_ua" rows="5" style="width: 100%;">{$announce_item.an_description_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td colspan="4" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="an_description_en" rows="5" style="width: 100%;">{$announce_item.an_description_en}</textarea></td></tr>
			</table>
		</div>
		</form>
		<div id="cont_4">
		</div>
		<br>
		</center>
	{/if}
	{if $smarty.get.get == 'settings'}
		<h1>Настройки</h1>
		
		<center>
		<b>Вкл/откл блока анонсов слева на всех страницах:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">вкл./откл.</td>
					<td width="34%" align="center"><input type="checkbox" name="is_active"{if $announce_settings_list.announce_is_informer > 0} checked{/if}></td>
					<td width="33%" align="center"><input type="submit" name="save_announce_is_informer_settings" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		<b>Для "Было" игры какого чемпионата выводить:</b><br><br>
		<form method="post">
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td width="33%">чемпионат: </td>
					<td width="34%" align="center">
						<select id="input" style="width: 200px;" name="ch_id">
						{if $championship_list}
						{foreach key=key item=item from=$championship_list name=tcl}
							<option value="{$item.ch_id}"{if $announce_settings_list.announce_championship == $item.ch_id} selected{/if}>{$item.title}</option>
						{/foreach}
						{/if}
					</select>
					</td>
					<td width="33%" align="center"><input type="submit" name="save_announce_championship" id="submitsave" value="Сохранить"></td>
				</tr>
			</table>
		</form>
		<br>
		{include file="announce/admin_title_announce_informer.tpl"}
		</center>
		<br>
	{/if}
	</div>