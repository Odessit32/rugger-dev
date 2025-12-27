<div id="conteiner" style="margin: 10px;">
{literal}
<style type="text/css">
	#title_1 { display: blok; }
	#title_2, #title_3 { display: none; }
</style>
{/literal}
		<br>
		<b>Заголовок сайта:</b><br><br>
		<!-- РУС -->
		<div id="title_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="title">
				<tr>
					<td width="20%">Заголовок сайта: </td>
					<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.title}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="title_1">
				<tr>
					<td width="20%">Текст под заголовком: </td>
					<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.title_1}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<!-- УКР -->
		<div id="title_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="active"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="title">
				<tr>
					<td width="20%">Заголовок сайта: </td>
					<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.title}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="title_1">
				<tr>
					<td width="20%">Текст под заголовком: </td>
					<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.title_1}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<!-- ENG -->
		<div id="title_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showVarLang('title_','3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="title">
				<tr>
					<td width="20%">Заголовок сайта: </td>
					<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.title}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="title_1">
				<tr>
					<td width="20%">Текст под заголовком: </td>
					<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.title_1}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<br>
</div>