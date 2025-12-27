{literal}
<style type="text/css">
	#i_championship_1 { display: blok; }
	#i_championship_2, #i_championship_3 { display: none; }
</style>
{/literal}
		<b>Заголовки информера чемпионатов:</b><br><br>
		<!-- РУС -->
		<div id="i_championship_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="inform_championship">
				<tr>
					<td width="10%">Заголовок: </td>
					<td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.inform_championship}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<!-- УКР -->
		<div id="i_championship_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="active"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="inform_championship">
				<tr>
					<td width="10%">Заголовок: </td>
					<td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.inform_championship}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<!-- ENG -->
		<div id="i_championship_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showVarLang('i_championship_','3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="inform_championship">
				<tr>
					<td width="10%">Заголовок: </td>
					<td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.inform_championship}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<br>