		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			
			<tr>
				<td width="50%" valign="top">
				{if !$smarty.get.history}
				<div id="list">
				<div id="conteiner" style="margin: 10px; padding: 0;">
				<div style="width: 175px; display: block; line-height: 20px; text-align: center; float: left;"><b>Список:</b></div>
				<div style="width: 174px; display: block; line-height: 20px; text-align: center; float: left; border-left: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; background: #ebebeb"><b><a href="?show=club&get=edit&item=1&cont=6&history=1">История:</a></b></div>
				<div style="margin: 30px 10px 0 10px;">
					{if $club_team_list.on}
						<ol style="margin: 5px 10px 0px 20px; ">
						{foreach key=key item=item from=$club_team_list.on name=tsl}
							<li><a href="?show=club&get=edit&item={$smarty.get.item}&cont=6&team={$item.cntcl_id}">{$item.t_title_ru}</a></li>
						{/foreach}
						</ol>
						<br>
					{else}
						<br><br><br><center>список пуст</center><br><br><br>
					{/if}
				</div>
				</div>
				</div>
				{/if}
				{if $smarty.get.history}
				<div id="history">
				<div id="conteiner" style="margin: 10px; padding: 0;">
				<div style="width: 175px; display: block; line-height: 20px; text-align: center; float: left; border-right: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; background: #ebebeb;"><b><a href="?show=club&get=edit&item=1&cont=6&list=1">Список:</a></b></div>
				<div style="width: 174px; display: block; line-height: 20px; text-align: center; float: left;"><b>История:</b></div>
				<div style="margin: 30px 10px 10px 10px;">
					{if $club_team_history}
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
					{foreach key=key item=item_tsl from=$club_team_history name=tslh}
						<tr>
							<td valign="top">{$smarty.foreach.tslh.iteration}.</td>
							<td><a href="?show=club&get=edit&item={$smarty.get.item}&cont=6&history=1&team={$item_tsl.cntcl_id}">{$item_tsl.t_title_ru}</a></td>
							<td><center><img src="images/in.gif" border="0" alt="{$item_tsl.cntcl_date_add}"><br><span style="font-size: 8px;">{$item_tsl.cntcl_date_add|date_format:"%e. %m. %Y"}</span></center></td>
							<td>{if $item_tsl.cntcl_date_quit != '0000-00-00 00:00:00'}<center><img src="images/out.gif" border="0" alt="{$item_tsl.cntcl_date_quit}"><br><span style="font-size: 8px;">{$item_tsl.cntcl_date_quit|date_format:"%e. %m. %Y"}</span></center>{/if}</td>
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
				{if $cl_team_item}
				{if !$smarty.get.history}
				<div id="conteiner" style="margin: 10px; background: #efefef">
					<center><b>Редактирование: </b>
					<form method="post" action="?show=club&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cntcl_id" value="{$cl_team_item.cntcl_id}">
					<b>«{$cl_team_item.t_title_ru}»</b>
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $cl_team_item.cntcl_date_add|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $cl_team_item.cntcl_date_add|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{section name = year start = 1900 loop = 2012}
						<option value="{$smarty.section.year.index}"{if $cl_team_item.cntcl_date_add|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					{if $staff_list and $club_categories_list}<input type="submit" name="edit_club_team" id="submitsave" value="Сохранить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
				<div id="conteiner" style="margin: 10px; background: #faa">
					<center><b>Уволить: </b>
					<form method="post"  action="?show=club&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cntcl_id" value="{$cl_team_item.cntcl_id}">
					<b>«{$cl_team_item.t_title_ru}»</b>
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
					{if $staff_list and $club_categories_list}<input type="submit" name="quit_club_team" id="submitsave" value="Уволить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
				{else}
				<div id="conteiner" style="margin: 10px; background: #f00; color: #fff">
					<center><b>Удалить: </b>
					<form method="post"  action="?show=club&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cntcl_id" value="{$cl_team_item.cntcl_id}">
					<b>«{$cl_team_item.t_title_ru}»</b>
					<br><br>
					принят:
					<br>
					<b>{$cl_team_item.cntcl_date_add|date_format:"%e. %m. %Y"}</b>
					<br><br>
					{if $cl_team_item.cntcl_date_quit != '0000-00-00 00:00:00'}
					уволен:
					<br>
					<b>{$cl_team_item.cntcl_date_quit|date_format:"%e. %m. %Y"}</b>
					<br><br>
					{/if}
					{if $staff_list and $club_categories_list}<input type="submit" name="delete_club_team" id="submitsave" value="Удалить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
				{/if}
				{/if}
				<div id="conteiner" style="margin: 10px;">
					<center><b>Добавление: </b>
					<form method="post"  action="?show=club&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cl_id" value="{$club_item.cl_id}">
					{if $club_team_list.off}
						<select name="team_id" id="input50">
							{foreach key=key item=item from=$club_team_list.off name=staff}<option value="{$item.t_id}">{$item.t_title_ru}</option>
							{/foreach}
						</select>
					{else}
						<span style="color: #f00;">Список команд пуст</span>
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
					{if $club_team_list.off}<input type="submit" name="add_club_team" id="submitsave" value="<<< Добавить">{else}<span style="color: #f00;">невозможно добавление</span>{/if}
					</form>
					</center>
				</div>
				
				</td>
				</tr>
			</table>
		<br>