<div id="conteiner" style="margin: 10px;">
{literal}
<style type="text/css">
	#title_1 { display: blok; }
	#title_2, #title_3 { display: none; }
</style>
{/literal}
		<br>
		<b>Подвал:</b><br><br>
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
			{* == Копирайты: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="copyright">
				<tr>
					<th colspan="3" height="30">Копирайты: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.rus.copyright}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Текст с права: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="footer_right">
				<tr>
					<th colspan="3" height="30">Текст с права: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.rus.footer_right}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Текст с снизу: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="footer_center">
				<tr>
					<th colspan="3" height="30">Текст по центру: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 200px;">{$conf_vars.rus.footer_center}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share telegram: == *}
			<form method="post">
            <input type="Hidden" name="lang" value="rus">
            <input type="Hidden" name="cnv_name" value="share_tg">
            <tr>
                <th width="30%">Ссылка на страницу в Telegram: </th>
                <td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.share_tg}"></td>
                <td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
            </tr>
            </form>
			{* == Share facebook: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="share_fb">
				<tr>
					<th width="30%">Ссылка на страницу в facebook: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.share_fb}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share vkontakte: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="share_vk">
				<tr>
					<th width="30%">Ссылка на страницу в Вконтакте: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.share_vk}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share Twitter: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="share_tw">
				<tr>
					<th width="30%">Ссылка на страницу в Twitter: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.share_tw}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share RSS: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="rus">
			<input type="Hidden" name="cnv_name" value="share_rss">
				<tr>
					<th width="30%">Ссылка на канал RSS: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.share_rss}"></td>
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
			{* == Копирайты: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="copyright">
				<tr>
					<th colspan="3" height="30">Копирайты: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.ukr.copyright}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Текст с права: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="footer_right">
				<tr>
					<th colspan="3" height="30">Текст с права: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.ukr.footer_right}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Текст по центру: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="footer_center">
				<tr>
					<th colspan="3" height="30">Текст по центру: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.ukr.footer_center}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share facebook: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="share_fb">
				<tr>
					<th width="30%">Ссылка на страницу в facebook: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.share_fb}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share vkontakte: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="share_vk">
				<tr>
					<th width="30%">Ссылка на страницу в Вконтакте: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.share_vk}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share Twitter: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="share_tw">
				<tr>
					<th width="30%">Ссылка на страницу в Twitter: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.share_tw}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share RSS: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="ukr">
			<input type="Hidden" name="cnv_name" value="share_rss">
				<tr>
					<th width="30%">Ссылка на канал RSS: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.share_rss}"></td>
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
			{* == Копирайты: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="copyright">
				<tr>
					<th colspan="3" height="30">Копирайты: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.eng.copyright}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Текст с права: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="footer_right">
				<tr>
					<th colspan="3" height="30">Текст с права: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.eng.footer_right}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Текст по центру: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="footer_center">
				<tr>
					<th colspan="3" height="30">Текст по центру: </th>
				</tr>
				<tr>
					<td align="center" colspan="3"><textarea name="cnv_value" style="width: 500px; height: 100px;">{$conf_vars.eng.footer_center}</textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="3" height="30"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share facebook: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="share_fb">
				<tr>
					<th width="30%">Ссылка на страницу в facebook: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.share_fb}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share vkontakte: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="share_vk">
				<tr>
					<th width="30%">Ссылка на страницу в Вконтакте: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.share_vk}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share Twitter: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="share_tw">
				<tr>
					<th width="30%">Ссылка на страницу в Twitter: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.share_tw}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			{* == Share RSS: == *}
			<form method="post">
			<input type="Hidden" name="lang" value="eng">
			<input type="Hidden" name="cnv_name" value="share_rss">
				<tr>
					<th width="30%">Ссылка на канал RSS: </th>
					<td width="45%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.share_rss}"></td>
					<td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
				</tr>
			</form>
			</table>
		</div>
		<br>
</div>