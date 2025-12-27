	<br>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">

		<tr>
			<td width="50%" valign="top">
			<div id="list">
			<div id="conteiner" style="margin: 10px; padding: 0;">

			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 10px 0;">
				<tr><td colspan="2" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="tab_player" class="{if $app_type == 'player'}active_left{else}notactive{/if}"><a href="javascript:void(0)" onclick="javascript:switchStaffType('player');">Игроки</a></td>
					<td width="50%" id="tab_rest" class="{if $app_type == 'rest'}active_right{else}notactive{/if}"><a href="javascript:void(0)" onclick="javascript:switchStaffType('rest');">Персонал</a></td>
				</tr>
			</table>

			<!-- БЛОК ИГРОКОВ -->
			<div id="staff_type_player" style="display: {if $app_type == 'player'}block{else}none{/if};">
				<div id="player_lang_1" style="display: block;">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							<td width="50%" id="player_tab_name" class="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('player_lang_1', 'player');">по имени</a></td>
							<td width="50%" id="player_tab_app" class="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('player_lang_2', 'player');">по должности</a></td>
						</tr>
					</table>
					<div style="margin: 30px 10px 0 10px;">
					{if isset($team_staff_player_byname) && $team_staff_player_byname}
					{foreach key=key item=item_stbn from=$team_staff_player_byname name=stbyname}
						<b>{$item_stbn.name}</b><br>
						{foreach key=key item=item from=$item_stbn.st_app name=appointmen}
						<a href="?show=team&get=edit&item={$smarty.get.item}&cont=5&staff={$item.cnstt_id}&app_type=player">{$item.app_title_ru}</a>{if !$smarty.foreach.appointmen.last}, {/if}
						{/foreach}
						<br><br>
					{/foreach}
					{/if}
					</div>
				</div>
				<div id="player_lang_2" style="display: none;">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							<td width="50%" id="player_tab_name" class="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('player_lang_1', 'player');">по имени</a></td>
							<td width="50%" id="player_tab_app" class="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('player_lang_2', 'player');">по должности</a></td>
						</tr>
					</table>
					<div style="margin: 30px 10px 0 10px;">
					{if isset($team_staff_player_byapp) && $team_staff_player_byapp}
					{foreach key=key item=item_stbn from=$team_staff_player_byapp name=stbyname}
						<b>{$item_stbn.app_title_ru}</b><br>
						<ol style="margin: 5px 10px 0px 20px; ">
						{foreach key=key item=item from=$item_stbn.st_app name=appointmen}
						<li><a href="?show=team&get=edit&item={$smarty.get.item}&cont=5&staff={$item.cnstt_id}&app_type=player">{$item.name}</a></li>
						{/foreach}
						</ol>
						<br>
					{/foreach}
					{/if}
					</div>
				</div>
			</div>

			<!-- БЛОК ПЕРСОНАЛА -->
			<div id="staff_type_rest" style="display: {if $app_type == 'rest'}block{else}none{/if};">
				<div id="rest_lang_1" style="display: block;">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							<td width="50%" id="rest_tab_name" class="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('rest_lang_1', 'rest');">по имени</a></td>
							<td width="50%" id="rest_tab_app" class="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('rest_lang_2', 'rest');">по должности</a></td>
						</tr>
					</table>
					<div style="margin: 30px 10px 0 10px;">
					{if isset($team_staff_rest_byname) && $team_staff_rest_byname}
					{foreach key=key item=item_stbn from=$team_staff_rest_byname name=stbyname}
						<b>{$item_stbn.name}</b><br>
						{foreach key=key item=item from=$item_stbn.st_app name=appointmen}
						<a href="?show=team&get=edit&item={$smarty.get.item}&cont=5&staff={$item.cnstt_id}&app_type=rest">{$item.app_title_ru}</a>{if !$smarty.foreach.appointmen.last}, {/if}
						{/foreach}
						<br><br>
					{/foreach}
					{/if}
					</div>
				</div>
				<div id="rest_lang_2" style="display: none;">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							<td width="50%" id="rest_tab_name" class="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('rest_lang_1', 'rest');">по имени</a></td>
							<td width="50%" id="rest_tab_app" class="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('rest_lang_2', 'rest');">по должности</a></td>
						</tr>
					</table>
					<div style="margin: 30px 10px 0 10px;">
					{if isset($team_staff_rest_byapp) && $team_staff_rest_byapp}
					{foreach key=key item=item_stbn from=$team_staff_rest_byapp name=stbyname}
						<b>{$item_stbn.app_title_ru}</b><br>
						<ol style="margin: 5px 10px 0px 20px; ">
						{foreach key=key item=item from=$item_stbn.st_app name=appointmen}
						<li><a href="?show=team&get=edit&item={$smarty.get.item}&cont=5&staff={$item.cnstt_id}&app_type=rest">{$item.name}</a></li>
						{/foreach}
						</ol>
						<br>
					{/foreach}
					{/if}
					</div>
				</div>
			</div>

			</div>
			</div>
			</div>
			</td>
			<td width="50%" valign="top">
		{if !empty($t_staff_item)}
			<div id="conteiner" style="margin: 10px; background: #efefef">
				<center><b>Редактирование: </b>
				<form method="post" action="?show=team&get=edit&item={$smarty.get.item}{if $smarty.get.app_type}&app_type={$smarty.get.app_type}{/if}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
				<input type="Hidden" name="cnstt_id" value="{$t_staff_item.cnstt_id}">
				<b>{$t_staff_item.st_family_ru} {$t_staff_item.st_name_ru} {$t_staff_item.st_surname_ru}</b>
				<br><br>
				на должность:
				<br>
				{if isset($team_categories_list) && $team_categories_list}
					<select name="appointment_id" class="appointment_select appointment_select_player" style="display: {if $app_type == 'player'}inline{else}none{/if};"{if $app_type != 'player'} disabled{/if}>
						{foreach key=key item=item from=$team_categories_list name=appointmen}{if $item.app_type == 'player'}<option value="{$item.app_id}"{if $item.app_id == $t_staff_item.cnstt_app_id} selected{/if}>{$item.app_title_ru}</option>
						{/if}{/foreach}
					</select>
					<select name="appointment_id" class="appointment_select appointment_select_rest" style="display: {if $app_type == 'rest'}inline{else}none{/if};"{if $app_type != 'rest'} disabled{/if}>
						{foreach key=key item=item from=$team_categories_list name=appointmen}{if $item.app_type == 'rest'}<option value="{$item.app_id}"{if $item.app_id == $t_staff_item.cnstt_app_id} selected{/if}>{$item.app_title_ru}</option>
						{/if}{/foreach}
					</select>
				{else}
					<span style="color: #f00;">Список должностей пуст</span>
				{/if}
				<br><br>
				с
				<select name="app_date_day">
				{section name = day start = 1 loop = 32}
					<option value="{$smarty.section.day.index}"{if !empty($t_staff_item.cnstt_date_add) && $t_staff_item.cnstt_date_add|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
				{/section}
				</select>
				<select name="app_date_month">
				{section name = month start = 1 loop = 13}
					<option value="{$smarty.section.month.index}"{if !empty($t_staff_item.cnstt_date_add) && $t_staff_item.cnstt_date_add|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
				{/section}
				</select>
				<select name="app_date_year">
				{section name = year start = 2000 loop = 2031}
					<option value="{$smarty.section.year.index}"{if !empty($t_staff_item.cnstt_date_add) && $t_staff_item.cnstt_date_add|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
				{/section}
				</select>
				<br><br>
				сортировка:
				<input type="text" name="cnstt_order" value="{$t_staff_item.cnstt_order}" id="input" maxlength="2" style="text-align: center; width: 40px;">
				<br><br>

				<input type="submit" name="edit_team_staff" id="submitsave" value="Сохранить">
				</form>
				</center>
			</div>
			<div id="conteiner" style="margin: 10px; background: #ffffff">
				<center><b>Добавить должность: </b>
				<form method="post" action="?show=team&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
				<input type="Hidden" name="staff_id" value="{$t_staff_item.cnstt_st_id}">
				<input type="Hidden" name="t_id" value="{$t_staff_item.cnstt_t_id}">
				<br><br>
				{if isset($team_categories_list) && $team_categories_list}
					<select name="appointment_id" id="input50">
						{foreach key=key item=item from=$team_categories_list name=appointmen}{if $item.app_type == $app_type}<option value="{$item.app_id}">{$item.app_title_ru}</option>
						{/if}{/foreach}
					</select>
				{else}
					<span style="color: #f00;">Список должностей пуст</span>
				{/if}
				<br><br>
				с
				<select name="app_date_day">
				{section name = day start = 1 loop = 32}
					<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $smarty.now|date_format:"%d"} selected{/if}>{$smarty.section.day.index}</option>
				{/section}
				</select>
				<select name="app_date_month">
				{section name = month start = 1 loop = 13}
					<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $smarty.now|date_format:"%m"} selected{/if}>{$smarty.section.month.index}</option>
				{/section}
				</select>
				<select name="app_date_year">
				{section name = year start = 2000 loop = 2031}
					<option value="{$smarty.section.year.index}"{if $smarty.section.year.index == $smarty.now|date_format:"%Y"} selected{/if}>{$smarty.section.year.index}</option>
				{/section}
				</select>
				<br><br>
				сортировка:
				<input type="text" name="cnstt_order" value="0" id="input" maxlength="2" style="text-align: center; width: 40px;">
				<br><br>
				<input type="submit" name="add_new_team_staff" id="submitsave" value="Добавить">
				</form>
				</center>
			</div>
			<div id="conteiner" style="margin: 10px; background: #f00; color: #fff">
				<center><b>Удалить: </b>
				<form method="post"  action="?show=team&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
				<input type="Hidden" name="cnstt_id" value="{$t_staff_item.cnstt_id}">
				<b>{$t_staff_item.st_family_ru} {$t_staff_item.st_name_ru} {$t_staff_item.st_surname_ru}</b>
				<br><br>
				<input type="submit" name="delete_team_staff" id="submitsave" value="Удалить">
				</form>
				</center>
			</div>
		{/if}
		{if empty($t_staff_item)}
			<div id="conteiner" style="margin: 10px;">
				<center><b>Добавление: </b>
				<form method="post"  action="?show=team&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
				<input type="Hidden" name="t_id" value="{$team_item.t_id}">
				<input type="Hidden" name="staff_id" id="staff_auto_val">
				<input type="Hidden" name="app_type" id="current_app_type" value="{$app_type}">
				<br><br>
				<!-- Autocomplete поиск игрока -->
				<input type="text" id="staff_input_id" placeholder="Начните вводить имя игрока..." style="width: 300px;">
				<div id="staff_selected_container"></div>
				<br><br>
				{if isset($team_categories_list) && $team_categories_list}
					<select name="appointment_id" class="appointment_select appointment_select_player" style="display: {if $app_type == 'player'}inline{else}none{/if};"{if $app_type != 'player'} disabled{/if}>
						{foreach key=key item=item from=$team_categories_list name=appointmen}{if $item.app_type == 'player'}<option value="{$item.app_id}">{$item.app_title_ru}</option>
						{/if}{/foreach}
					</select>
					<select name="appointment_id" class="appointment_select appointment_select_rest" style="display: {if $app_type == 'rest'}inline{else}none{/if};"{if $app_type != 'rest'} disabled{/if}>
						{foreach key=key item=item from=$team_categories_list name=appointmen}{if $item.app_type == 'rest'}<option value="{$item.app_id}">{$item.app_title_ru}</option>
						{/if}{/foreach}
					</select>
				{else}
					<span style="color: #f00;">Список должностей пуст</span>
				{/if}
				<br><br>
				с
				<select name="app_date_day">
				{section name = day start = 1 loop = 32}
					<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $smarty.now|date_format:"%d"} selected{/if}>{$smarty.section.day.index}</option>
				{/section}
				</select>
				<select name="app_date_month">
				{section name = month start = 1 loop = 13}
					<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $smarty.now|date_format:"%m"} selected{/if}>{$smarty.section.month.index}</option>
				{/section}
				</select>
				<select name="app_date_year">
				{section name = year start = 2000 loop = 2031}
					<option value="{$smarty.section.year.index}"{if $smarty.section.year.index == $smarty.now|date_format:"%Y"} selected{/if}>{$smarty.section.year.index}</option>
				{/section}
				</select>
				<br><br>
				сортировка:
				<input type="text" name="cnstt_order" value="0" id="input" maxlength="2" style="text-align: center; width: 40px;">
				<br><br>
				<input type="submit" name="add_team_staff" id="submitsave" value="Добавить">
				</form>
				</center>
			</div>
		{/if}
			</td>
		</tr>
	</table>

<script>
// Функция переключения между Игроки/Персонал
function switchStaffType(type) {
	// Скрываем все блоки
	document.getElementById('staff_type_player').style.display = 'none';
	document.getElementById('staff_type_rest').style.display = 'none';

	// Показываем нужный блок
	document.getElementById('staff_type_' + type).style.display = 'block';

	// Обновляем стили вкладок
	var tabPlayer = document.getElementById('tab_player');
	var tabRest = document.getElementById('tab_rest');

	if (type === 'player') {
		// Активируем вкладку "Игроки"
		tabPlayer.className = 'active_left';
		tabRest.className = 'notactive';
	} else {
		// Активируем вкладку "Персонал"
		tabPlayer.className = 'notactive';
		tabRest.className = 'active_right';
	}

	// Обновляем скрытое поле в форме добавления
	var appTypeField = document.getElementById('current_app_type');
	if (appTypeField) {
		appTypeField.value = type;
	}

	// Переключаем видимость select'ов с должностями
	var selectsPlayer = document.querySelectorAll('.appointment_select_player');
	var selectsRest = document.querySelectorAll('.appointment_select_rest');

	if (type === 'player') {
		for (var i = 0; i < selectsPlayer.length; i++) {
			selectsPlayer[i].style.display = 'inline';
			selectsPlayer[i].disabled = false;
		}
		for (var i = 0; i < selectsRest.length; i++) {
			selectsRest[i].style.display = 'none';
			selectsRest[i].disabled = true;
		}
	} else {
		for (var i = 0; i < selectsPlayer.length; i++) {
			selectsPlayer[i].style.display = 'none';
			selectsPlayer[i].disabled = true;
		}
		for (var i = 0; i < selectsRest.length; i++) {
			selectsRest[i].style.display = 'inline';
			selectsRest[i].disabled = false;
		}
	}
}

// Функция переключения между "по имени" и "по должности"
function showBlokLang(blockId, staffType) {
	// Определяем префикс для ID вкладок
	var prefix = staffType; // 'player' или 'rest'

	// Скрываем оба блока
	document.getElementById(prefix + '_lang_1').style.display = 'none';
	document.getElementById(prefix + '_lang_2').style.display = 'none';

	// Показываем нужный блок
	document.getElementById(blockId).style.display = 'block';

	// Обновляем стили вкладок
	var tabName = document.getElementById(prefix + '_tab_name');
	var tabApp = document.getElementById(prefix + '_tab_app');

	if (blockId === prefix + '_lang_1') {
		// Активная вкладка "по имени"
		tabName.className = 'active_left';
		tabApp.className = 'notactive';
	} else {
		// Активная вкладка "по должности"
		tabName.className = 'notactive';
		tabApp.className = 'active_right';
	}
}
</script>
