<H1>Соревнования:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="25%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=competitions{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Список</a></td>
				<td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'archive'}active{else}notactive{/if}"><a href="?show=competitions&get=archive{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Архив</a></td>
				<td width="50%" id="notactive"> </td>
			</tr>
		</table>
	
	{if empty($smarty.get.get) and !$championship_item}
		<h1>Чемпионаты:</h1>

			<table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td align="center" colspan="7">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=competitions{if !empty($smarty.get.season)}&season={$smarty.get.season}{/if}&country='+this.value">
							<option value="all"{if empty($smarty.get.country) || $smarty.get.country == 'all'} selected{/if}>все</option>
							<option value="0"{if isset($smarty.get.country) && $smarty.get.country == '0'} selected{/if}>без страны</option>
						{if $championship_country_list_ne}
						{foreach key=key item=item from=$championship_country_list_ne name=tcl}
							<option value="{$item.cn_id}"{if !empty($smarty.get.country) && $smarty.get.country == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
						{/foreach}
						{/if}
					</select>
                                        <select id="input" style="width: 200px;" onchange="document.location.href='?show=competitions{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}&season='+this.value">
							<option value=''>----------</option>
						{if $season_list}
						{foreach key=key item=item from=$season_list name=seasons}
							<option value="{$item.title}"{if !empty($smarty.get.country) && $smarty.get.season == $item.title} selected{/if}>{$item.title}</option>
						{/foreach}
						{/if}
					</select>
					<br><br>
					</td>
				</tr>
			{if $championship_list}
				<tr>
					<th>локаль</th>
					<th>группа</th>
					<th>название чемпионата</th>
					<th>тип</th>
					<th>структура</th>
					<th>вкл./откл.</th>
				</tr>
			{foreach key=key item=item from=$championship_list name=championship}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=competitions&ch={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chl_title_ru}</a>&nbsp;</td>
					<td><a href="?show=competitions&ch={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chg_title_ru}</a>&nbsp;</td>
					<td><a href="?show=competitions&ch={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{if $item.ch_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.ch_title_ru}{/if}</a></td>
					<td>{$item.chc_title_ru}</td>
					<td >{if $item.is_strusture == 'no'}нет{else}{if !empty($item.ch_cp_is_done) && $item.ch_cp_is_done == 'no'}не закончена{else}{/if}есть{/if}</td>
					<td align="center">{if $item.ch_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			<br>
	{/if}
    {if !empty($smarty.get.get) && $smarty.get.get == 'archive'}
		<h1>Архив:</h1>

			<table width="95%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<td align="center" colspan="7">
					<select id="input" style="width: 200px;" onchange="document.location.href='?show=competitions&get=archive{if !empty($smarty.get.season)}&season={$smarty.get.season}{/if}&country='+this.value">
							<option value="all"{if empty($smarty.get.country) || $smarty.get.country == 'all'} selected{/if}>все</option>
							<option value="0"{if isset($smarty.get.country) && $smarty.get.country == '0'} selected{/if}>без страны</option>
						{if $championship_country_list_ne}
						{foreach key=key item=item from=$championship_country_list_ne name=tcl}
							<option value="{$item.cn_id}"{if !empty($smarty.get.country) && $smarty.get.country == $item.cn_id} selected{/if}>{$item.cn_title_ru}</option>
						{/foreach}
						{/if}
					</select>
                                        <select id="input" style="width: 200px;" onchange="document.location.href='?show=competitions&get=archive{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}&season='+this.value">
							<option value=''>----------</option>
						{if $season_list}
						{foreach key=key item=item from=$season_list name=seasons}
							<option value="{$item.title}"{if !empty($smarty.get.season) && $smarty.get.season == $item.title} selected{/if}>{$item.title}</option>
						{/foreach}
						{/if}
					</select>
					<br><br>
					</td>
				</tr>
			{if $championship_list}
				<tr>
					<th>локаль</th>
					<th>группа</th>
					<th>название чемпионата</th>
					<th>тип</th>
					<th>структура</th>
					<th>вкл./откл.</th>
				</tr>
			{foreach key=key item=item from=$championship_list name=championship}
				<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
					<td><a href="?show=competitions&ch={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chl_title_ru}</a>&nbsp;</td>
					<td><a href="?show=competitions&ch={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{$item.chg_title_ru}</a>&nbsp;</td>
					<td><a href="?show=competitions&ch={$item.ch_id}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{if $item.ch_title_ru == ''}<font style="color: #f00;">Без названия</font>{else}{$item.ch_title_ru}{/if}</a></td>
					<td>{$item.chc_title_ru}</td>
					<td >{if $item.is_strusture == 'no'}нет{else}{if !empty($item.ch_cp_is_done) && $item.ch_cp_is_done == 'no'}не закончена{else}{/if}есть{/if}</td>
					<td align="center">{if $item.ch_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
				</tr>
			{/foreach}
			{/if}
			</table>
			<br>
	{/if}
	{if $championship_item}
		<h1>Чемпионат: &laquo;{$championship_item.chg_title_ru}. {$championship_item.ch_title_ru}&raquo;</h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="5" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="20%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}">Просмотр структуры</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$smarty.get.item}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="20%" id="{if !empty($smarty.get.get) && ($smarty.get.get == 'add' || $smarty.get.get == 'addmanual')}active{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=add{if !empty($championship_item.ch_cp_is_done) && $championship_item.ch_cp_is_done == 'yes'}manual{/if}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Добавить</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'request'}active{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=request{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Настройки команд</a></td>
				<td width="20%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'tour'}active_right{else}notactive{/if}"><a href="?show=competitions&ch={$smarty.get.ch}&get=tour{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Туры</a></td>
			</tr>
		</table>
		{if empty($smarty.get.get)}
			{include file="competitions/admin_competitions_structure.tpl"}
		{/if}
		{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}
			{include file="competitions/admin_competitions_structure_edit.tpl"}
		{/if}
		{if !empty($smarty.get.get) && ($smarty.get.get == 'add' || $smarty.get.get == 'addmanual')}
			{include file="competitions/admin_competitions_structure_add.tpl"}
		{/if}
		{if !empty($smarty.get.get) && $smarty.get.get == 'tour'}
			{include file="competitions/admin_competitions_tour.tpl"}
		{/if}
		{if !empty($smarty.get.get) && $smarty.get.get == 'request'}
			{include file="competitions/admin_competitions_request.tpl"}
		{/if}
	{/if}
	
	</div>