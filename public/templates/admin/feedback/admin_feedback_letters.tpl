	<center>
		<b>Шаблоны писем:</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<select id="input" style="width: 200px;" onchange="document.location.href='?show=feedback&get=letters&l_item='+this.value">
			<option value="1"{if $smarty.get.l_item == '1'} selected{/if}>администратору о письме в обратную связь</option>
			<option value="2"{if $smarty.get.l_item == '2'} selected{/if}>клиенту о письме в обратную связь</option>
			<option value="3"{if $smarty.get.l_item == '3'} selected{/if}>клиенту - комментарии от админа в обратную связь</option>
		</select>
		<br><br>
		
<div id="conteiner" style="margin: 10px;">
{literal}
<style type="text/css">
	#title_1 { display: blok; }
	#title_2, #title_3 { display: none; }
</style>
{/literal}
		<br>
		<b>«
			{if $smarty.get.l_item == '1' or $smarty.get.l_item == ''}администратору о письме в обратную связь{/if}
			{if $smarty.get.l_item == '2'}клиенту о письме в обратную связь{/if}
			{if $smarty.get.l_item == '3'}клиенту - комментарии от админа в обратную связь{/if}
		»</b><br><br>
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
			<form method="post">
				<input type="Hidden" name="lang" value="rus">
				<input type="Hidden" name="cnv_name" value="{$letter_t.rus.cnv_name}">
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="20%">Тема письма: </td>
						<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$letter_t.rus.cnv_value}"></td>
						<td width="25%" align="right"><input type="submit" name="save_letter_title" id="submitsave" value="Сохранить"></td>
					</tr>
				</table>
			</form>
			<br>
			<form method="post">
				<input type="Hidden" name="lang" value="rus">
				<input type="Hidden" name="cnv_name" value="{$letter.rus.cnv_name}">
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td align="center"><textarea type="text" name="cnv_value" id="input100" style="height: 300px;" class="mceNoEditor">{$letter.rus.cnv_value}</textarea></td>
					</tr>
					<tr>
						<td align="center"><input type="submit" name="save_letter" id="submitsave" value="Сохранить"></td>
					</tr>
				</table>
			</form>
			<br>
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
			<form method="post">
				<input type="Hidden" name="lang" value="ukr">
				<input type="Hidden" name="cnv_name" value="{$letter_t.ukr.cnv_name}">
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="20%">Тема письма: </td>
						<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$letter_t.ukr.cnv_value}"></td>
						<td width="25%" align="right"><input type="submit" name="save_letter_title" id="submitsave" value="Сохранить"></td>
					</tr>
				</table>
			</form>
			<br>
			<form method="post">
				<input type="Hidden" name="lang" value="ukr">
				<input type="Hidden" name="cnv_name" value="{$letter.ukr.cnv_name}">
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td align="center"><textarea type="text" name="cnv_value" id="input100" style="height: 300px;" class="mceNoEditor">{$letter.ukr.cnv_value}</textarea></td>
					</tr>
					<tr>
						<td align="center"><input type="submit" name="save_letter" id="submitsave" value="Сохранить"></td>
					</tr>
				</table>
			</form>
			<br>
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
			<form method="post">
				<input type="Hidden" name="lang" value="eng">
				<input type="Hidden" name="cnv_name" value="{$letter_t.eng.cnv_name}">
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td width="20%">Тема письма: </td>
						<td width="55%" align="center"><input type="text" name="cnv_value" id="input100" value="{$letter_t.eng.cnv_value}"></td>
						<td width="25%" align="right"><input type="submit" name="save_letter_title" id="submitsave" value="Сохранить"></td>
					</tr>
				</table>
			</form>
			<br>
			<form method="post">
				<input type="Hidden" name="lang" value="eng">
				<input type="Hidden" name="cnv_name" value="{$letter.eng.cnv_name}">
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<td align="center"><textarea type="text" name="cnv_value" id="input100" style="height: 300px;" class="mceNoEditor">{$letter.eng.cnv_value}</textarea></td>
					</tr>
					<tr>
						<td align="center"><input type="submit" name="save_letter" id="submitsave" value="Сохранить"></td>
					</tr>
				</table>
			</form>
			<br>
		</div>
		<br>
</div>
		
		<br>
	</center>