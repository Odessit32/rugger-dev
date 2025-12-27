<h1>Добавление структуры соревнований</h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_left{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=add">За 5 шагов</a></td>
				<td width="34%" id="{if $smarty.get.get == 'addmanual'}active{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=addmanual">Ручное</a></td>
			</tr>
		</table>
{if !empty($smarty.get.get) && $smarty.get.get == 'add'}

		{* меню шагов *}
		{if $championship_item.ch_cp_is_done == 'yes'}<center><br>Структура уже добавлена<br><br></center>{/if}

{* ШАГ 1 *}
{if $smarty.post.step <= 1 AND $championship_item.ch_cp_is_done == 'no'}
	<h1>Шаг 1: Количество подэтапов </h1>
	<center>
		<form method="post">
			<input type="hidden" name="ch" value="{$smarty.get.ch}">
			<table width="50%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%" align="center">
						количество подэтапов:
					</td>
					<td width="50%" align="center">
					<select name="ch_stage">
					{section name = stage start = 1 loop = 16}
						<option value="{$smarty.section.stage.index}">{$smarty.section.stage.index}</option>
					{/section}
					</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr>
					<td colspan="2" align="center" valign="middle">
						<input type="hidden" name="step" value="2">
						<input type="submit" name="next_step" id="submitsave" value="Следующий шаг 2">
					</td>
				</tr>
			</table>
			<br>
		
		</form>
{/if}

{* ШАГ 2 *}
{if $smarty.post.step == 2 AND $championship_item.ch_cp_is_done == 'no'}
	<h1>Шаг 2: Структура подэтапов</h1>
	<center>
		<form method="post">
			<input type="hidden" name="ch" value="{$postdata.ch}">
			<input type="hidden" name="ch_stage" value="{$postdata.ch_stage}">
			<table width="50%" cellspacing="0" cellpadding="0" border="0">
			{section name = stage start = 1 loop = $postdata.ch_stage+1}
				<tr>
					<th colspan="2" align="center">
						подэтап {$smarty.section.stage.index}
					</th>
				</tr>
				<tr>
					<td width="50%" align="center">
						количество соревнований:
					</td>
					<td width="50%" align="center">
					<select name="ch_competitions_{$smarty.section.stage.index}">
					{section name = comp start = 1 loop = 16}
						<option value="{$smarty.section.comp.index}">{$smarty.section.comp.index}</option>
					{/section}
					</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="20"></td>
				</tr>
			{/section}
				<tr>
					<td colspan="2" align="center" valign="middle">
						<input type="hidden" name="step" value="3">
						<input type="submit" name="next_step" id="submitsave" value="Следующий шаг 3">
					</td>
				</tr>
			</table>
			<br>
		
		</form>
{/if}

{* ШАГ 3 *}
{if $smarty.post.step == 3 AND $championship_item.ch_cp_is_done == 'no'}
	<h1>Шаг 3: Последовательность соревнований</h1>
	<center>
		<form method="post" id="form_input">
			<input type="hidden" name="ch" value="{$postdata.ch}">
			<input type="hidden" name="ch_stage" value="{$postdata.ch_stage}">
			{foreach key=key item=item from=$postdata.ch_competitions name=championship}
				<input type="hidden" name="ch_competitions_{$key}" value="{$item}">
			{/foreach}
			<table width="50%" cellspacing="0" cellpadding="0" border="0">
			{foreach key=key item=item from=$postdata.ch_competitions name=championship}
				<tr>
					<th colspan="2" align="left">
						подэтап {$key}:
					</th>
				</tr>
				{section name = competitions start = 1 loop = $item+1}
				<tr>
					<th colspan="2" align="center">
						соревнование {$key}-{$smarty.section.competitions.index}
					</th>
				</tr>
				<tr>
					<td width="50%" align="center">
						название:
					</td>
					<td width="50%" align="center">
						<input type="text" name="cp_title_{$key}-{$smarty.section.competitions.index}" value="соревнование {$key}-{$smarty.section.competitions.index}" id="input100">
					</td>
				</tr>
				{*if $key>1}
				<tr>
					<td width="50%" align="center">
						прикрепить к:
					</td>
					<td width="50%" align="center">
					<select name="cp_parent_{$key}-{$smarty.section.competitions.index}">
					{assign var="prev" value=$key-1}
					{section name = comp start = 1 loop = $postdata.ch_competitions.$prev+1}
						<option value="{$prev}-{$smarty.section.comp.index}">соревнование {$prev}-{$smarty.section.comp.index}</option>
					{/section}
					</select>
					</td>
				</tr>
				{/if*}
				{/section}
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td colspan="2" height="20"></td>
				</tr>
			{/foreach}
				<tr>
					<td colspan="2" align="center" valign="middle">
						<input type="hidden" name="step" value="4">
						<input type="submit" name="next_step" id="submitsave" value="Следующий шаг 4">
					</td>
				</tr>
			</table>
			<br>
		
		</form>
{/if}

{* ШАГ 4 *}
{if $smarty.post.step == 4 AND $championship_item.ch_cp_is_done == 'no'}
	<h1>Шаг 4: Выбор команд</h1>
	<center>
		<form method="post" id="form_input">
			<input type="hidden" name="ch" value="{$postdata.ch}">
			<input type="hidden" name="ch_stage" value="{$postdata.ch_stage}">
			{foreach key=key item=item from=$postdata.ch_competitions name=championship}
				<input type="hidden" name="ch_competitions_{$key}" value="{$item}">
			{/foreach}
			{foreach key=key1 item=item1 from=$postdata.cp_title}
				{foreach key=key item=item from=$item1 name=title}
				<input type="hidden" name="cp_title_{$key1}-{$key}" value="{$item}">
				{/foreach}
			{/foreach}
			{*  =====  БЕЗ СВЯЗЕЙ  =====  *}
			{*foreach key=key1 item=item1 from=$postdata.cp_parent}
				{foreach key=key item=item from=$item1 name=title}
				<input type="hidden" name="cp_parent_{$key1}-{$key}" value="{$item}">
				{/foreach}
			{/foreach*}
			<table width="50%" cellspacing="0" cellpadding="0" border="0">
			{foreach key=key item=item from=$postdata.ch_competitions name=championship}
				<tr>
					<th colspan="2" align="left">
						подэтап {$key}:
					</th>
				</tr>
				{section name = competitions start = 1 loop = $item+1}
				<tr>
					<td colspan="2" align="center">
					{assign var="competitions_index" value=$smarty.section.competitions.index}
						<b>{$postdata.cp_title.$key.$competitions_index}</b> (соревнование {$key}-{$smarty.section.competitions.index})
					</td>
				</tr>
				<tr>
					<td width="50%" align="center">
						команды:
					</td>
					<td width="50%" align="center">
					{if $championship_team}
					<div class="team_select">
					<select multiple size="3" name="cp_team_{$key}-{$smarty.section.competitions.index}[]" id="input_{$key}-{$smarty.section.competitions.index}" onfocus="javascript: getSelectTeam('input_{$key}-{$smarty.section.competitions.index}');" onblur="javascript: this.style=''">
						{if !$smarty.foreach.championship.last}
						{assign var="next" value=$key+1}
						{section name = comp start = 1 loop = $postdata.ch_competitions.$next+1}
							{assign var="comp_index" value=$smarty.section.comp.index}
							{assign var="comp_c" value=$postdata.ch_competitions.$next}
							{assign var="ch_team_c" value=$championship_team|@count}
							{assign var="n_team_place" value=$ch_team_c/$comp_c+1}
							{section name = ch_g start = 1 loop = $n_team_place+1}
								<option value="{$smarty.section.ch_g.index}-{$next}-{$smarty.section.comp.index}">{$comp_c}{$smarty.section.ch_g.index} место из {$postdata.cp_title.$next.$comp_index}</option>
							{/section}
						{/section}
						{/if}
						{foreach key=key_t item=item_t from=$championship_team name=ch_team}
						<option value="{$item_t.t_id}">{$item_t.t_title_ru}</option>
						{/foreach}
					</select>
					</div>
					{else}
						команды не добавлены в чемпионат ( <a href="?show=championship&get=edit&item={$championship_item.ch_id}&cont=6" target="_blank">добавить</a> )
					{/if}
					</td>
				</tr>
				{/section}
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<tr><td colspan="2" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td colspan="2" height="20"></td>
				</tr>
			{/foreach}
				<tr>
					<td align="center" valign="middle">
						<input type="hidden" name="step" value="5">
						<input type="submit" name="save_structure" id="submitsave" value="Сохранить структуру">
					</td>
					<td align="center" valign="middle">
						<input type="submit" name="next_step" id="submitsave" value="Следующий шаг 5">
					</td>
				</tr>
			</table>
			<br>
		
		</form>
{/if}

{* ШАГ 5 *}
{if $smarty.post.step == 5}
	<h1>Шаг 5: Генерация игр</h1>
	<center>
		<form method="post" id="form_input">
			<input type="hidden" name="ch" value="{$postdata.ch}">
			<input type="hidden" name="ch_stage" value="{$postdata.ch_stage}">
			{foreach key=key item=item from=$postdata.ch_competitions name=championship}
				<input type="hidden" name="ch_competitions_{$key}" value="{$item}">
			{/foreach}
			{foreach key=key1 item=item1 from=$postdata.cp_title}
				{foreach key=key item=item from=$item1 name=title}
				<input type="hidden" name="cp_title_{$key1}-{$key}" value="{$item}">
				{/foreach}
			{/foreach}
			{*  =====  БЕЗ СВЯЗЕЙ  =====  *}
			{*foreach key=key1 item=item1 from=$postdata.cp_parent}
				{foreach key=key item=item from=$item1 name=title}
				<input type="hidden" name="cp_parent_{$key1}-{$key}" value="{$item}">
				{/foreach}
			{/foreach*}
			
			
			{assign var="n_game" value=1}
			
			<table width="90%" cellspacing="0" cellpadding="0" border="0">
			{foreach key=key item=item from=$postdata.ch_competitions name=championship}
				<tr>
					<th colspan="6" align="left">
						подэтап {$key}:
					</th>
				</tr>
				{section name = competitions start = 1 loop = $item+1}
				<tr>
					<td colspan="6" align="center">
					{assign var="competitions_index" value=$smarty.section.competitions.index}
						<b>{$postdata.cp_title.$key.$competitions_index}</b> (соревнование {$key}-{$competitions_index})
						<br>
						игры:
					</td>
				</tr>
				{foreach key=key_t_1 item=item_t_1 from=$postdata.cp_team.$key.$competitions_index}
				{foreach key=key_t_2 item=item_t_2 from=$postdata.cp_team.$key.$competitions_index}
					{if $item_t_1.title != $item_t_2.title}
					{assign var="t_1" value=$item_t_1.title}
					{assign var="t_2" value=$item_t_2.title}
					<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
						<td width="2%" align="center"><input type="checkbox" name="g_{$n_game}_is_save" checked title="сохранить эту игру"></td>
						<td width="48%" align="center">
							<input type="hidden" name="g_{$n_game}_comp" value="{$key}-{$competitions_index}">
							<input type="hidden" name="g_{$n_game}_team_1" value="{$item_t_1.title}">
							<input type="hidden" name="g_{$n_game}_team_2" value="{$item_t_2.title}">
							<b>
								{if $championship_team_id.$t_1.t_title_ru != ''}
									{$championship_team_id.$t_1.t_title_ru}
								{else}
									{$item_t_1.ex.0} место 
									{assign var="cp_st" value=$item_t_1.ex.1}
									{assign var="cp_n" value=$item_t_1.ex.2}
									{$postdata.cp_title.$cp_st.$cp_n}
								{/if}
							</b>
							 - 
							<b>
								{if $championship_team_id.$t_2.t_title_ru != ''}
									{$championship_team_id.$t_2.t_title_ru}
								{else}
									{$item_t_2.ex.0} место 
									{assign var="cp_st" value=$item_t_2.ex.1}
									{assign var="cp_n" value=$item_t_2.ex.2}
									{$postdata.cp_title.$cp_st.$cp_n}
								{/if}
							</b><br>
							{if $championship_team_id.$t_1.stadium.std_title_ru != ''}{$championship_team_id.$t_1.stadium.std_title_ru}<input type="hidden" name="g_{$n_game}_stadium" value="{$championship_team_id.$t_1.stadium.std_id}">{else}стадион не определен{/if}
							
							
						</td>
						<td width="5%" align="center">
							тур:&nbsp;<input type="text" name="g_{$n_game}_g_round" align="center" id="input" size="3" maxlength="3">
						</td>
						<td width="28%" align="center">
							дата: 
							<select name="g_{$n_game}_date_day" title="дата проведения игры">
							{section name = day start = 1 loop = 32}
								<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{if $smarty.section.day.index<10}0{/if}{$smarty.section.day.index}</option>
							{/section}
							</select>
							<select name="g_{$n_game}_date_month" title="дата проведения игры">
							{section name = month start = 1 loop = 13}
								<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{if $smarty.section.month.index<10}0{/if}{$smarty.section.month.index}</option>
							{/section}
							</select>
							<select name="g_{$n_game}_date_year" title="дата проведения игры">
							{assign var="now_year" value=$smarty.now|date_format:"%Y"}
							{assign var="now_year" value=$now_year+2}
							{section name = year start = 2009 loop = $now_year}
								<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
							{/section}
							</select>
						</td>
						<td width="15%" align="center">
							в: 
							<select name="g_{$n_game}_date_hour" title="время проведения игры">
							{section name = day start = 1 loop = 25}
								<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == '18'} selected{/if}>{$smarty.section.day.index}</option>
							{/section}
							</select>
							<select name="g_{$n_game}_date_minute" title="время проведения игры">
							{section name = month start = 0 loop = 60}
								<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == '0'} selected{/if}>{if $smarty.section.month.index<10}0{/if}{$smarty.section.month.index}</option>
							{/section}
							</select>
						</td>
						<td width="2%" align="center"><input type="checkbox" name="g_{$n_game}_is_schedule_time" checked title="показывать время"></td>
					</tr>
					{assign var="n_game" value=$n_game+1}
					{/if}
				{/foreach}
				{/foreach}
				{/section}
				<tr>
					<td colspan="6" height="10"></td>
				</tr>
				<tr><td colspan="6" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td colspan="6" height="20"></td>
				</tr>
			{/foreach}
				<tr>
					<td colspan="6" align="center" valign="middle">
						<input type="hidden" name="n_game" value="{$n_game}">
						<input type="hidden" name="step" value="6">
						<input type="submit" name="save_structure_games" id="submitsave" value="Сохранить игры">
					</td>
				</tr>
			</table>
			<br>
		
		</form>
{/if}
{/if}

{* РУЧНОЕ ДОБАВЛЕНИЕ *}
{if $smarty.get.get == 'addmanual'}
	<h1>Ручное добавление</h1>
	
	<center>
		{if $championship_item.ch_chc_id == 2}
		<select id="input" style="width: 200px;" onchange="document.location.href='?show=competitions&ch={$smarty.get.ch}&get=addmanual{if !empty($smarty.get.get_m)}&get_m={$smarty.get.get_m}{/if}&tour='+this.value">
			{section name = tour start = 0 loop = $championship_item.ch_tours}
				<option value="{$smarty.section.tour.index}"{if $smarty.section.tour.index == $smarty.get.tour} selected{/if}>Тур {$smarty.section.tour.index+1}</option>
			{/section}
		</select>
		<br><br>
		{/if}
	</center>
	
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
		<tr>
			<td width="50%" id="{if empty($smarty.get.get_m) || $smarty.get.get_m == 'competition'}active_left{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=addmanual&get_m=competition{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}">Добавить соревнование</a></td>
			<td width="50%" id="{if !empty($smarty.get.get_m) && $smarty.get.get_m == 'game'}active{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=addmanual&get_m=game{if !empty($smarty.get.tour)}&tour={$smarty.get.tour}{/if}">Добавить игру</a></td>
		</tr>
	</table>
	{if empty($smarty.get.get_m) || $smarty.get.get_m == 'competition'}
		<h1>
			Добавление соревнования{if $championship_item.ch_chc_id == 2} в Тур {$smarty.get.tour+1}{/if}
		</h1>
		
		<center>
		<form method="post">
			<input type="hidden" name="ch" value="{$championship_item.ch_id}">
			<input type="hidden" name="tour" value="{if !empty($smarty.get.tour)}{$smarty.get.tour}{else}0{/if}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cp_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">отображать в меню: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cp_is_menu" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">отображать рейтинговую таблицу: </th>
							<td width="50%" align="center"><input type="Checkbox" name="cp_is_rating_table" checked></td>
						</tr>
						<tr>
							<th width="50%" align="center">подэтап: </th>
							<td width="50%" align="center"><input type="text" name="cp_substage" value="" id="input50" maxlength="3"></td>
						</tr>
						<tr>
							<th width="50%" align="center">сортировка: </th>
							<td width="50%" align="center"><input type="text" name="cp_order" value="" id="input50" maxlength="3"></td>
						</tr>
						{*
						<tr>
							<th width="50%" align="center">провести до: </th>
							<td width="50%" align="center">
								<select name="cp_parent_id" id="input100">
									<option value="0">самым последним</option>
								{if $competitions_list}{foreach key=key item=item from=$competitions_list}<option value="{$item.cp_id}"{if $smarty.get.item_comp == $item.cp_id} selected{/if}>{section name = mySection start = 0 loop = $item.nesting}../{/section}{$item.cp_title_ru}</option>{/foreach}{/if}
								</select>
							</td>
						</tr>
						*}
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_comp" id="submitsave" value="Добавить">
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
					<td width="80%"><input type="text" name="cp_title_ru" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="cp_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="cp_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="cp_title_ua" value="" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="cp_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="cp_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
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
					<td width="80%"><input type="text" name="cp_title_en" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="cp_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td colspan="2" width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="cp_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
		
	{/if}
	{if !empty($smarty.get.get_m) && $smarty.get.get_m == 'game'}
		<h1>Добавление игры{if $championship_item.ch_chc_id == 2} в Тур {$smarty.get.tour+1}{/if}</h1>
		
		<center>
		<form method="post">
        {if $championship_item.ch_chc_id != 3}<input type="hidden" name="g_cp_id_g" value="0" />{/if}
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="g_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">тур: </th>
							<td width="50%" align="center"><input type="text" name="g_round" align="center" id="input50"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Соревнование{if $championship_item.ch_chc_id == 3} (Хозяева){/if}: </th>
							<td width="50%" align="center">
							{if $competitions_list_form}
								<select name="g_cp_id" id="input100" onchange="getCompTeam({$smarty.get.ch}, this.value)">
								<option value="0">----------</option>
								{foreach key=key item=item from=$competitions_list_form}<option value="{$item.cp_id}"{if $smarty.get.item_comp == $item.cp_id} selected{/if}>{$item.title}</option>{/foreach}
								</select>
							{else}
								<span style="color: #f00;">нет соревнований</span>
							{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Хозяева: </th>
							<td width="50%" align="center" id="owner_t">
							{if $championship_team}
								<select name="g_owner_t_id" id="input100" onchange="getCompTeamGuest({$smarty.get.ch}, {$smarty.get.item_comp}, this.value, 0)">
								<option value="0">----------</option>
								{foreach key=key item=item from=$championship_team}<option value="{$item.t_id}">{$item.t_title_ru}</option>{/foreach}
								</select>
							{else}
								<span style="color: #f00;">нет команд</span> <a href="?show=championship&get=edit&item={$championship_item.ch_id}&cont=6">добавить</a>
							{/if}
							</td>
						</tr>
                        {if $championship_item.ch_chc_id == 3}
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Соревнование (Гости): </th>
                            <td width="50%" align="center">
                                {if $competitions_list_form}
                                    <select name="g_cp_id_g" id="input100" {*onchange="getCompTeam({$smarty.get.ch}, this.value)"*}>
                                        <option value="0">----------</option>
                                        {foreach key=key item=item from=$competitions_list_form}<option value="{$item.cp_id}"{if $smarty.get.item_comp == $item.cp_id} selected{/if}>{$item.title}</option>{/foreach}
                                    </select>
                                {else}
                                    <span style="color: #f00;">нет соревнований</span>
                                {/if}
                            </td>
                        </tr>
                        {/if}
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Гости: </th>
							<td width="50%" align="center" id="guest_t">
							{if $championship_team}
								<select name="g_guest_t_id" id="input100" onchange="getCompTeamOwner({$smarty.get.ch}, {$smarty.get.item_comp}, this.value, 0)">
								<option value="0">----------</option>
								{foreach key=key item=item from=$championship_team}<option value="{$item.t_id}">{$item.t_title_ru}</option>{/foreach}
								</select>
							{else}
								<span style="color: #f00;">нет команд</span> <a href="?show=championship&get=edit&item={$championship_item.ch_id}&cont=6">добавить</a>
							{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">дата: </th>
							<td width="50%" align="center">
								<select name="g_date_day">
								{section name = day start = 1 loop = 32}
									<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{if $smarty.section.day.index<10}0{/if}{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="g_date_month">
								{section name = month start = 1 loop = 13}
									<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{if $smarty.section.month.index<10}0{/if}{$smarty.section.month.index}</option>
								{/section}
								</select>
								<select name="g_date_year">
								{assign var="now_year" value=$smarty.now|date_format:"%Y"}
								{assign var="now_year" value=$now_year+2}
								{section name = year start = 2009 loop = $now_year}
									<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
								{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">время: </th>
							<td width="50%" align="center">
								<select name="g_date_hour">
								{section name = day start = 0 loop = 24}
									<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == 18} selected{/if}>{$smarty.section.day.index}</option>
								{/section}
								</select>
								<select name="g_date_minute">
								{section name = month start = 0 loop = 60}
									<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == 0} selected{/if}>{if $smarty.section.month.index <10}0{/if}{$smarty.section.month.index}</option>
								{/section}
								</select>
							</td>
						</tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">часовой пояс: </th>
                            <td width="50%" align="center">UTC
                                <select name="g_date_time_zone">
                                    {section name = tzone start = 27 loop = 27 step=-1}
                                        {assign var="tz_title" value=$smarty.section.tzone.index-12}
                                        {if $tz_title == -4}
                                            <option value="-4.5"{if -4.5 == $game_item.g_date_time_zone} selected{/if}>-4:30</option>
                                        {/if}
                                        {if $tz_title == -3}
                                            <option value="-3.5"{if -3.5 == $game_item.g_date_time_zone} selected{/if}>-3:30</option>
                                        {/if}
                                        {if $tz_title == 4}
                                            <option value="4.5"{if 4.5 == $game_item.g_date_time_zone} selected{/if}>+4:30</option>
                                        {/if}
                                        {if $tz_title == 3}
                                            <option value="3.5"{if 3.5 == $game_item.g_date_time_zone} selected{/if}>+3:30</option>
                                        {/if}
                                        {if $tz_title == 5}
                                            <option value="5.5"{if 5.5 == $game_item.g_date_time_zone} selected{/if}>+5:30</option>
                                            <option value="5.75"{if 5.75 == $game_item.g_date_time_zone} selected{/if}>+5:45</option>
                                        {/if}
                                        {if $tz_title == 6}
                                            <option value="6.5"{if 6.5 == $game_item.g_date_time_zone} selected{/if}>+6:30</option>
                                        {/if}
                                        {if $tz_title == 8}
                                            <option value="8.75"{if 8.75 == $game_item.g_date_time_zone} selected{/if}>+8:45</option>
                                        {/if}
                                        {if $tz_title == 9}
                                            <option value="9.5"{if 9.5 == $game_item.g_date_time_zone} selected{/if}>+9:30</option>
                                        {/if}
                                        {if $tz_title == 10}
                                            <option value="10.5"{if 10.5 == $game_item.g_date_time_zone} selected{/if}>+10:30</option>
                                        {/if}
                                        {if $tz_title == 11}
                                            <option value="11.5"{if 11.5 == $game_item.g_date_time_zone} selected{/if}>+11:30</option>
                                        {/if}
                                        {if $tz_title == 12}
                                            <option value="12.75"{if 12.75 == $game_item.g_date_time_zone} selected{/if}>+12:45</option>
                                        {/if}
                                        {if $tz_title == 13}
                                            <option value="13.75"{if 13.75 == $game_item.g_date_time_zone} selected{/if}>+13:45</option>
                                        {/if}
                                        <option value="{$tz_title}"{if $tz_title == $game_item.g_date_time_zone} selected{/if}>{if $tz_title>0}+{/if}{$tz_title}</option>

                                    {/section}
                                </select>
                            </td>
                        </tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать время: </th>
							<td width="50%" align="center"> <input type="Checkbox" name="g_is_schedule_time" checked> </td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">показывать стадион: </th>
							<td width="50%" align="center"> <input type="Checkbox" name="g_is_stadium"> </td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">страна: </th>
							<td width="50%" align="center">
								<select name="t_cn_id" id="input100" onchange='getCityList(this.value, 0, 1)'>
									<option value="0"{if $item.cn_id == 0} selected{/if}>----------</option>
								{if $country_list}
								{foreach key=key item=item from=$country_list name=country}
									<option value="{$item.cn_id}">{$item.cn_title_ru}</option>
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">город: </th>
							<td width="50%" align="center" id="city">
								<select name="t_ct_id" id="input100" onchange='getStadiumList(this.value, 0)'>
								
								<option value="">----------</option>
								
								</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">Стадион: </th>
							<td width="50%" align="center" id="stadium">
								<select name="t_std_id" id="input100">
								
								<option value="">----------</option>
								
								</select>
							</td>
						</tr>
						</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_game" id="submitsave" value="Добавить">
					</td>
				</tr>
			</table>
			<br>
		<div id="cont_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="g_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td width="100%" align="center"><b>Текст (РУС):</b> <br><textarea name="g_text_ru" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="g_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td width="100%" align="center"><b>Текст (УКР):</b> <br><textarea name="g_text_ua" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr><td width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="g_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
				<tr><td width="100%" align="center"><b>Текст (ENG):</b> <br><textarea name="g_text_en" style="width: 100%; height: 400px;"></textarea></td></tr>
			</table>
		</div>
		<div id="cont_4">
			
		</div>
		</form>
		<br>
		</center>
		
	{/if}
{/if}
		