		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			
			<tr>
				<td width="50%" valign="top">
			{if !$smarty.get.history}
				<div id="list">
				<div id="conteiner" style="margin: 10px; padding: 0;">
				<div style="width: 175px; display: block; line-height: 20px; text-align: center; float: left; margin: 0 0 10px 0;"><b>Список:</b></div>
				<div style="width: 174px; display: block; line-height: 20px; text-align: center; float: left; margin: 0 0 10px 0; border-left: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; background: #ebebeb"><b><a href="?show=club&get=edit&item=1&cont=5&history=1">История:</a></b></div>
				
				
				<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 10px 0;">
					<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
					<tr>
						<td width="33%" id="{if $app_type == 'player'}active_left{else}notactive{/if}"><a href="?show=club&get=edit&item={$smarty.get.item}&cont=5&list={$smarty.get.list}&app_type=player&cont=5">Игроки</a></td>
						<td width="34%" id="{if $app_type == 'head'}active{else}notactive{/if}"><a href="?show=club&get=edit&item={$smarty.get.item}&cont=5&list={$smarty.get.list}&app_type=head&cont=5">Руководство</a></td>
						<td width="33%" id="{if $app_type == 'rest'}active_right{else}notactive{/if}"><a href="?show=club&get=edit&item={$smarty.get.item}&cont=5&list={$smarty.get.list}&app_type=rest&cont=5">Другие</a></td>
					</tr>
				</table>
				<div id="lang_1">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							<td width="50%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">по имени</a></td>
							<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">по должности</a></td>
						</tr>
					</table>
					<div style="margin: 30px 10px 0 10px;">
					{if $club_staff_list_byname}
					{foreach key=key item=item_stbn from=$club_staff_list_byname name=stbyname}
						<b>{$item_stbn.name}</b><br>
						{foreach key=key item=item from=$item_stbn.st_app name=appointmen}
						<a href="?show=club&get=edit&item={$smarty.get.item}&cont=5&staff={$item.cnstcl_id}&app_type={$app_type}">{$item.app_title_ru}</a>{if !$smarty.foreach.appointmen.last}, {/if}
						{/foreach}
						<br><br>
					{/foreach}
					{/if}
					</div>
				</div>
				<div id="lang_2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">по имени</a></td>
							<td width="50%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">по должности</a></td>
						</tr>
					</table>
					<div style="margin: 30px 10px 0 10px;">
					{if $club_staff_list_byapp}
					{foreach key=key item=item_stbn from=$club_staff_list_byapp name=stbyname}
						<b>{$item_stbn.app_title_ru}</b><br>
						<ol style="margin: 5px 10px 0px 20px; ">
						{foreach key=key item=item from=$item_stbn.st_app name=appointmen}
						<li><a href="?show=club&get=edit&item={$smarty.get.item}&cont=5&staff={$item.cnstcl_id}&app_type={$app_type}">{$item.name}</a></li>
						{/foreach}
						</ol>
						<br>
					{/foreach}
					{/if}
					</div>
				</div>
				<div id="lang_3">
					
				</div>
				</div>
				</div>
				{/if}
				{if $smarty.get.history}
				<div id="history">
				<div id="conteiner" style="margin: 10px; padding: 0;">
				<div style="width: 175px; display: block; line-height: 20px; text-align: center; float: left; border-right: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; background: #ebebeb;"><b><a href="?show=club&get=edit&item=1&cont=5&list=1">Список:</a></b></div>
				<div style="width: 174px; display: block; line-height: 20px; text-align: center; float: left;"><b>История:</b></div>
				<div style="margin: 30px 10px 10px 10px;">
					{if $club_staff_history and $club_categories_list}
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
					{foreach key=key item=item_tsl from=$club_staff_history name=tslh}
						<tr>
							<td valign="top">{$smarty.foreach.tslh.iteration}.</td>
							<td>{assign var="app_id" value=$item_tsl.cnstcl_app_id}{$club_categories_list_id.$app_id.app_title_ru}<br><a href="?show=club&get=edit&item={$smarty.get.item}&cont=5&history=1&staff={$item_tsl.cnstcl_id}">{$item_tsl.st_family_ru} {$item_tsl.st_name_ru} {$item_tsl.st_surname_ru}</a></td>
							<td><center><img src="images/in.gif" border="0" alt="{$item_tsl.cnstcl_date_add}"><br><span style="font-size: 8px;">{$item_tsl.cnstcl_date_add|date_format:"%e. %m. %Y"}</span></center></td>
							<td>{if $item_tsl.cnstcl_date_quit != '0000-00-00 00:00:00'}<center><img src="images/out.gif" border="0" alt="{$item_tsl.cnstcl_date_quit}"><br><span style="font-size: 8px;">{$item_tsl.cnstcl_date_quit|date_format:"%e. %m. %Y"}</span></center>{/if}</td>
						</tr>
					{/foreach}
					</table>
					{else}
						<br><br><br><center>список пуст</center><br><br><br>
					{/if}
				</div>
				</div>
				</div>
			{/if}
				</td>
				<td width="50%" valign="top">
			{if $cl_staff_item}
			{if !$smarty.get.history}
				<div id="conteiner" style="margin: 10px; background: #efefef">
					<center><b>Редактирование: </b>
					<form method="post" action="?show=club&get=edit&item={$smarty.get.item}{if $smarty.get.app_type}&app_type={$smarty.get.app_type}{/if}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cnstcl_id" value="{$cl_staff_item.cnstcl_id}">
					<b>{$cl_staff_item.st_family_ru} {$cl_staff_item.st_name_ru} {$cl_staff_item.st_surname_ru}</b>
					<br><br>
					на должность:
					<br>
					{if $club_categories_list}
						<select name="appointment_id" id="input50">
							{foreach key=key item=item from=$club_categories_list name=appointmen}{if $item.app_type == $app_type}<option value="{$item.app_id}"{if $item.app_id == $cl_staff_item.cnstcl_app_id} selected{/if}>{$item.app_title_ru}</option>
							{/if}{/foreach}
						</select>
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{section name = year start = 1900 loop = 2012}
						<option value="{$smarty.section.year.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					сортировка: 
					<input type="text" name="cnstcl_order" value="{$cl_staff_item.cnstcl_order}" id="input" maxlength="2" style="text-align: center; width: 40px;">
					<br><br>
					{if $staff_list and $club_categories_list}<input type="submit" name="edit_club_staff" id="submitsave" value="Сохранить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
				<div id="conteiner" style="margin: 10px; background: #ffffff">
					<center><b>Добавить должность: </b>
					<form method="post" action="?show=club&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="staff_id" value="{$cl_staff_item.cnstcl_st_id}">
					<input type="Hidden" name="cl_id" value="{$cl_staff_item.cnstcl_cl_id}">
					<b>{$cl_staff_item.st_family_ru} {$cl_staff_item.st_name_ru} {$cl_staff_item.st_surname_ru}</b>
					<br><br>
					на новую должность:
					<br>
					{if $club_categories_list}
						<select name="appointment_id" id="input50">
							{foreach key=key item=item from=$club_categories_list name=appointmen}{if $item.app_id !== $cl_staff_item.cnstcl_app_id and $item.app_type == $app_type}<option value="{$item.app_id}">{$item.app_title_ru}</option>{/if}
							{/foreach}
						</select>
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{section name = year start = 1900 loop = 2012}
						<option value="{$smarty.section.year.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					{if $staff_list and $club_categories_list}<input type="submit" name="add_new_club_staff" id="submitsave" value="Добавить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
			{/if}
			{if $cl_staff_item.cnstcl_id>0 and $cl_staff_item.cnstcl_date_quit == '0000-00-00 00:00:00'}
				<div id="conteiner" style="margin: 10px; background: #faa">
					<center><b>Уволить: </b>
					<form method="post"  action="?show=club&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cnstcl_id" value="{$cl_staff_item.cnstcl_id}">
					<b>{$cl_staff_item.st_family_ru} {$cl_staff_item.st_name_ru} {$cl_staff_item.st_surname_ru}</b>
					<br><br>
					с должности:
					<br>
					{if $club_categories_list}
						{foreach key=key item=item from=$club_categories_list name=appointmen}{if $item.app_id == $cl_staff_item.cnstcl_app_id}<b>{$item.app_title_ru}</b>{/if}
						{/foreach}
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{section name = year start = 1900 loop = 2012}
						<option value="{$smarty.section.year.index}"{if $cl_staff_item.cnstcl_date_add|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					{if $staff_list and $club_categories_list}<input type="submit" name="quit_club_staff" id="submitsave" value="Уволить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
			{/if}
			{if $cl_staff_item.cnstcl_date_quit != '0000-00-00 00:00:00'}
				<div id="conteiner" style="margin: 10px; background: #efefef">
					<center><b>Вернуть на должность: </b>
					<form method="post" action="?show=club&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cnstcl_id" value="{$cl_staff_item.cnstcl_id}">
					<b>{$cl_staff_item.st_family_ru} {$cl_staff_item.st_name_ru} {$cl_staff_item.st_surname_ru}</b>
					<br><br>
					на должность:
					<br>
					{if $club_categories_list}
						{foreach key=key item=item from=$club_categories_list name=appointmen}{if $item.app_id == $cl_staff_item.cnstcl_app_id}<b>{$item.app_title_ru}</b>{/if}
						{/foreach}
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					<br><br>
					<input type="submit" name="return_club_staff" id="submitsave" value="Вернуть">
					</form>
					</center>
				</div>
				<div id="conteiner" style="margin: 10px; background: #f00; color: #fff">
					<center><b>Удалить: </b>
					<form method="post"  action="?show=club&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cnstcl_id" value="{$cl_staff_item.cnstcl_id}">
					<b>{$cl_staff_item.st_family_ru} {$cl_staff_item.st_name_ru} {$cl_staff_item.st_surname_ru}</b>
					<br><br>
					должность:
					<br>
					{if $club_categories_list}
						{foreach key=key item=item from=$club_categories_list name=appointmen}{if $item.app_id == $cl_staff_item.cnstcl_app_id}<b>{$item.app_title_ru}</b>{/if}
						{/foreach}
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					<br><br>
					принят:
					<br>
					<b>{$cl_staff_item.cnstcl_date_add|date_format:"%e. %m. %Y"}</b>
					<br><br>
					{if $cl_staff_item.cnstcl_date_quit != '0000-00-00 00:00:00'}
					уволен:
					<br>
					<b>{$cl_staff_item.cnstcl_date_quit|date_format:"%e. %m. %Y"}</b>
					<br><br>
					{/if}
					{if $staff_list and $club_categories_list}<input type="submit" name="delete_club_staff" id="submitsave" value="Удалить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
			{/if}
			{/if}
				<div id="conteiner" style="margin: 10px;">
					<center><b>Добавление: </b>
					<form method="post"  action="?show=club&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cl_id" value="{$club_item.cl_id}">
					{if $staff_list}
						<select name="staff_id" id="input50">
							{foreach key=key item=item from=$staff_list name=staff}<option value="{$item.st_id}">{$item.st_family_ru} {$item.st_name_ru} {$item.st_surname_ru}</option>
							{/foreach}
						</select>
					{else}
						<span style="color: #f00;">Список людей пуст</span>
					{/if}
					<br><br>
					на должность:
					<br>
					{if $club_categories_list}
						<select name="appointment_id" id="input50">
							{foreach key=key item=item from=$club_categories_list name=appointmen}{if $item.app_type == $app_type}<option value="{$item.app_id}">{$item.app_title_ru}</option>
							{/if}{/foreach}
						</select>
					{else}
						<span style="color: #f00;">Список должностей пуст</span>
					{/if}
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{section name = year start = 1900 loop = 2012}
						<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					сортировка: 
					<input type="text" name="cnstcl_order" id="input" maxlength="2" style="text-align: center; width: 40px;">
					<br><br>
					{if $staff_list and $club_categories_list}<input type="submit" name="add_club_staff" id="submitsave" value="<<< Добавить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
				
				</td>
				</tr>
			</table>
		<br>