<H1>Файлы:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get) or $smarty.get.get == 'images' or $smarty.get.get == 'imagesedit' or $smarty.get.get == 'imagesadd'}active_left{else}notactive{/if}"><a href="?show=files">Картинки</a></td>
				<td width="33%" id="{if $smarty.get.get == 'other' or $smarty.get.get == 'otheredit' or $smarty.get.get == 'otheradd'}active_right{else}notactive{/if}"><a href="?show=files&get=other">Другие файлы</a></td>
			</tr>
		</table>
		
	{if empty($smarty.get.get) or $smarty.get.get == 'images' or $smarty.get.get == 'imagesedit' or $smarty.get.get == 'imagesadd'}
		<h1>Картинки</h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'images' or empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=files&get=images">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'imagesedit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=files&get=imagesedit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'imagesadd'}active_right{else}notactive{/if}"><a href="?show=files&get=imagesadd">Добавить</a></td>
			</tr>
		</table>
	{/if}
	{if empty($smarty.get.get) or $smarty.get.get == 'images'}
		<h1>Список картинок</h1>
		<center>
		{if $images_list}
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
			{foreach key=key item=item from=$images_list name=images}
				<div class="image_item">
					<a href="?show=files&get=imagesedit&item={$item.f_id}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}"><img src="../upload/{$item.f_type}{$item.f_folder}{$item.f_path}" width="166"></a>
					<input type="Text" value="/upload/{$item.f_type}{$item.f_folder}{$item.f_path}" id="input_item_image" onfocus="this.select();">
				</div>
			{/foreach}
			</td></tr>
		</table>
		{if $images_pages|@count > 1}
			<div class="pages"><i>Страницы: </i>
				{foreach key=key item=item from=$images_pages}
					{if $smarty.get.page == $key || (empty($smarty.get.page) && $key == 0)}<b>{$item}</b>{else}<a href="?show=files&get=images&page={$key}">{$item}</a>{/if}
				{/foreach}
			</div>
		{/if}
		{/if}
			<br>
		</center>
	{/if}
		<center>
	{if $smarty.get.get == 'imagesadd'}
		<h1>Добавление картинки</h1>
		<center>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" enctype="multipart/form-data">
				<tr><td width="10%"></td><th width="20%" align="center">Файл: </th><td width="80%"><input type="File" name="file_image" id="input100"></td><td width="10%"></td></tr>
				<tr>
					<td width="10%"></td>
					<th>В папку: </th>
					<td>
						<select name="f_folder" id="input100">
							<option value="/">в корень "/"</option>
						</select>
					</td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<br>
						<div id="lang_1">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
							<tr>
								{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
								{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
								{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
							</tr>
						</table>
						<br>
						<b>Описание (рус):</b><br>
						<textarea name="f_about_ru" rows="3" style="width: 80%;"></textarea>
						</div>
						<div id="lang_2">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
							<tr>
								{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
								{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
								{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
							</tr>
						</table>
						<br>
						<b>Описание (укр):</b><br>
						<textarea name="f_about_ua" rows="3" style="width: 80%;"></textarea>
						</div>
						<div id="lang_3">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
							<tr>
								{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
								{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
								{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
							</tr>
						</table>
						<br>
						<b>Описание (eng):</b><br>
						<textarea name="f_about_en" rows="3" style="width: 80%;"></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<td width="10%"></td>
					<td align="center" colspan="2">
						<br>
						<input type="submit" name="add_new_image" id="submitsave" value="Добавить">
					</td>
					<td width="10%"></td>
				</tr>        
			</form>
			</table>
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'imagesedit'}
		<h1>Редактирование картинки</h1>
		<center>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
		<tr>
			<td valign="top"><a href="../upload/{$image_item.f_type}{$image_item.f_folder}{$image_item.f_path}" target="_blank" title="{$image_item.f_about}"><img src="../upload/{$image_item.f_type}{$image_item.f_folder}{$image_item.f_path}" border="0" alt="{$image_item.f_about}" width="300"></a></td>
			<td valign="top">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="f_id" value="{$image_item.f_id}">
				<tr><td width="10%"></td><th width="20%" align="center">Файл: </th><td width="80%"><input type="File" name="file_image" id="input100"></td><td width="10%"></td></tr>
				<tr>
					<td width="10%"></td>
					<th>В папку: </th>
					<td>
						<select name="f_folder" id="input100">
							<option value="/"{if $image_item.f_folder == '/'} selected{/if}>в корень "/"</option>
						</select>
					</td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<br>
						<div id="lang_1">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
							<tr>
								{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
								{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
								{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
							</tr>
						</table>
						<br>
						<b>Описание (рус):</b><br>
						<textarea name="f_about_ru" rows="3" style="width: 100%;">{$image_item.f_about_ru}</textarea>
						</div>
						<div id="lang_2">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
							<tr>
								{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
								{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
								{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
							</tr>
						</table>
						<br>
						<b>Описание (укр):</b><br>
						<textarea name="f_about_ua" rows="3" style="width: 100%;">{$image_item.f_about_ua}</textarea>
						</div>
						<div id="lang_3">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
							<tr>
								{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
								{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
								{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
							</tr>
						</table>
						<br>
						<b>Описание (eng):</b><br>
						<textarea name="f_about_en" rows="3" style="width: 100%;">{$image_item.f_about_en}</textarea>
						</div>
					</td>
				</tr>
				<tr>
					<td width="10%"></td>
					<td align="center" colspan="2">
						<br>
						<input type="submit" name="save_edited_image" id="submitsave" value="Сохранить">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="delete_edited_image" id="submitdelete" value="Удалить">
					</td>
					<td width="10%"></td>
				</tr>
			</form>
			</table>
			</td>
		</tr>
		</table>
			<br>
		</center>
	{/if}
	
	{if $smarty.get.get == 'other' or $smarty.get.get == 'otheredit' or $smarty.get.get == 'otheradd'}
		<h1>Другие файлы</h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if $smarty.get.get == 'other'}active_left{else}notactive{/if}"><a href="?show=files&get=other">Список</a></td>
				<td width="34%" id="{if $smarty.get.get == 'otheredit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=files&get=otheredit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if $smarty.get.get == 'otheradd'}active_right{else}notactive{/if}"><a href="?show=files&get=otheradd">Добавить</a></td>
			</tr>
		</table>
	{/if}
	{if $smarty.get.get == 'other'}
		<h1>Список файлов</h1>
		<center>
		{if $files_list}
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
			{foreach key=key item=item from=$files_list name=files}
				<div class="file_item">
					<a href="?show=files&get=otheredit&item={$item.f_id}{if !empty($smarty.get.page)}&page={$smarty.get.page}{/if}"><img src="{$item.ico}" width="75"></a>
					<b>{$item.f_about_ru}</b>
					<input type="Text" value="/upload/{$item.f_type}{$item.f_folder}{$item.f_path}" id="input_item_file">
				</div>
			{/foreach}
			</td></tr>
		</table>
		{if $files_pages|@count > 1}
			<div class="pages"><i>Страницы: </i>
				{foreach key=key item=item from=$files_pages}
					{if $smarty.get.page == $key || (empty($smarty.get.page) && $key == 0)}<b>{$item}</b>{else}<a href="?show=files&get=other&page={$key}">{$item}</a>{/if}
				{/foreach}
			</div>
		{/if}
		{/if}
			<br>
		</center>
	{/if}
		<center>
	{if $smarty.get.get == 'otheradd'}
		<h1>Добавление файла</h1>
		<center>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" enctype="multipart/form-data">
				<tr><td width="10%"></td><th width="20%" align="center">Файл: </th><td width="80%"><input type="File" name="file_image" id="input100"></td><td width="10%"></td></tr>
				<tr>
					<td width="10%"></td>
					<th>В папку: </th>
					<td>
						<select name="f_folder" id="input100">
							<option value="/">в корень "/"</option>
						</select>
					</td>
					<td width="10%"></td>
				</tr>
				<tr><td colspan="4" align="center">
					<br>
					<div id="lang_1">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Описание (рус):</b><br>
					<textarea name="f_about_ru" rows="3" style="width: 80%;"></textarea>
					</div>
					<div id="lang_2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Описание (укр):</b><br>
					<textarea name="f_about_ua" rows="3" style="width: 80%;"></textarea>
					</div>
					<div id="lang_3">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Описание (eng):</b><br>
					<textarea name="f_about_en" rows="3" style="width: 80%;"></textarea>
					</div>
				</td></tr>
				<tr><td width="10%"></td><td align="center" colspan="2">
				<br>
					<input type="submit" name="add_new_file" id="submitsave" value="Добавить">
				</td><td width="10%"></td></tr>        
			</form>
			</table>
			<br>
		</center>
	{/if}
	{if $smarty.get.get == 'otheredit'}
		<h1>Редактирование файла</h1>
		<center>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
		<tr>
			<td valign="top">
				<div class="file_item" style="width: 87px;">
					<a href="../upload/{$file_item.f_type}{$file_item.f_folder}{$file_item.f_path}"><img src="{$file_item.ico}" width="75"></a>
					<input type="Text" value="upload/{$file_item.f_type}{$file_item.f_folder}{$file_item.f_path}" id="input100" onfocus="this.select();" >
				</div>
			</td>
			<td valign="top">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
				<input type="Hidden" name="f_id" value="{$file_item.f_id}">
				<tr>
					<th width="20%" align="center">Файл: </th>
					<td width="60%"><input type="File" name="file_image" id="input100"></td>
					<td width="10%"></td>
				</tr>
				<tr>
					<th>В папку: </th>
					<td>
						<select name="f_folder" id="input100">
							<option value="/"{if $file_item.f_folder == '/'} selected{/if}>в корень "/"</option>
						</select>
					</td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td colspan="3" align="center">
					<br>
					<div id="lang_1">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Описание (рус):</b><br>
					<textarea name="f_about_ru" rows="3" style="width: 100%;">{$file_item.f_about_ru}</textarea>
					</div>
					<div id="lang_2">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Описание (укр):</b><br>
					<textarea name="f_about_ua" rows="3" style="width: 100%;">{$file_item.f_about_ua}</textarea>
					</div>
					<div id="lang_3">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
						<tr>
							{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
							{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
							{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
						</tr>
					</table>
					<br>
					<b>Описание (eng):</b><br>
					<textarea name="f_about_en" rows="3" style="width: 100%;">{$file_item.f_about_en}</textarea>
					</div>
					</td>
				</tr>
				<tr><td align="center" colspan="2">
				<br>
					<input type="submit" name="save_edited_file" id="submitsave" value="Сохранить">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="delete_edited_file" id="submitdelete" value="Удалить">
				</td></tr>
			</form>
			</table>
			</td>
		</tr>
		</table>
			<br>
		</center>
	{/if}
	
	
	<br>
	</div>