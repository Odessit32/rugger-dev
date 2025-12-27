
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="tab_gallery_list" class="{if (!empty($smarty.get.g_get) && $smarty.get.g_get == 'photos') || (empty($smarty.get.g_get) && empty($smarty.get.g_f_item) && empty($smarty.get.g_v_item))}active_left{else}notactive{/if}"><a href="javascript:void(0)" onclick="javascript:switchGalleryTab('list');">Список</a></td>
				<td width="34%" id="tab_gallery_edit" class="{if !empty($smarty.get.g_f_item) || !empty($smarty.get.g_v_item)}active{else}notactive{/if}">{if !empty($smarty.get.g_f_item)}<a href="?show=team&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_f_item={$smarty.get.g_f_item}">Редактировать</a>{elseif !empty($smarty.get.g_v_item)}<a href="?show=team&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_v_item={$smarty.get.g_v_item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="tab_gallery_add" class="{if !empty($smarty.get.g_get) && $smarty.get.g_get == 'add'}active_right{else}notactive{/if}"><a href="javascript:void(0)" onclick="javascript:switchGalleryTab('add');">Добавить</a></td>
			</tr>
		</table>
	<div id="gallery_block_list" style="display: {if (empty($smarty.get.g_get) && empty($smarty.get.g_f_item) && empty($smarty.get.g_v_item))}block{else}none{/if};">
		<h1>Список файлов</h1>
		<center>
		{* == PHOTO == *}
		<div id="gal_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_1');">Фото</a></td>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_2');">Видео</a></td>
				</tr>
			</table>
			<h2>Фото</h2>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
			{if $team_photo_list}
			{foreach key=key item=item from=$team_photo_list name=images}
				<div class="image_item{if $item.ph_type_main == 'yes'}_main{/if}">
					<a href="?show=team&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_f_item={$item.ph_id}"><img src="../upload/photos{$item.ph_folder}{$item.ph_path}?{$smarty.now}" width="166"></a>
					<input type="Text" value="upload/photos{$item.ph_folder}{$item.ph_path}" id="input_item_image">
				</div>
			{/foreach}
			{else}
				<center>фотографий нет.</center>
			{/if}
			</td></tr>
		</table>
			<br>
		</div>
		{* == VIDEO == *}
		<div id="gal_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_1');">Фото</a></td>
					<td width="50%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_2');">Видео</a></td>
				</tr>
			</table>
			<h2>Видео</h2>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
			{if $team_video_list}
			{foreach key=key item=item from=$team_video_list name=images}
				<div class="image_item">
					<a href="?show=team&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_v_item={$item.v_id}"><img src="../upload/video_thumbs{$item.v_folder}{$item.v_id}-small.jpg?{$smarty.now}" width="166"></a>
					<input type="Text" value="{$item.v_code}" id="input_item_image">
				</div>
			{/foreach}
			{else}
				<center>Видео нет.</center>
			{/if}
			</td></tr>
		</table>
			<br>
		</div>
		</center>
	</div>
	<div id="gallery_block_add" style="display: {if !empty($smarty.get.g_get) && $smarty.get.g_get == 'add'}block{else}none{/if};">
		<center>
		<h1>Добавление</h1>
		{* == PHOTO == *}
		<div id="gal_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_1');">Фото</a></td>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_2');">Видео</a></td>
				</tr>
			</table>
		<h1>Фото</h1>
		<center>
		<form method="post" enctype="multipart/form-data">
			<input type="Hidden" name="n_id" value="{$team_item.t_id}">

			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="ph_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">отображать в информере: </th>
							<td width="50%" align="center"><input type="checkbox" name="ph_is_informer" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">главная: </th>
							<td width="50%" align="center"><input type="checkbox" name="ph_type_main"></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Файл: </th>
							<td width="50%" align="center"><input type="File" name="file_photo" id="input100"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Галерея: </th>
							<td width="50%" align="center">
							<select name="ph_gallery_id" id="input100">
								{if !empty($team_photo_gallery.phg_id)}<option value="{$team_photo_gallery.phg_id}" selected>{$team_photo_gallery.phg_title_ru}</option>{else}<option value="new" selected>Новая: {$team_item.t_title_ru}</option>{/if}
								<option value="0">без галереи</option>
							{if $gallery_list}
							{foreach key=key item=item from=$gallery_list name=images}
								{if empty($team_photo_gallery.phg_id) || $team_photo_gallery.phg_id != $item.id}<option value="{$item.id}"{if !empty($photo_item.ph_gallery_id) && $item.id == $photo_item.ph_gallery_id} selected{/if}>{$item.title}</option>{/if}
							{/foreach}
							{/if}
							</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">В папку: </th>
							<td width="50%" align="center">
							<select name="ph_folder" id="input100">
								<option value="/">в корень "/"</option>
							</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">Подпись: </th>
							<td width="50%" align="center">
								<textarea name="ph_signature" rows="2" class="mceNoEditor" id="input100"></textarea>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center">
								<input type="text" name="ph_order" size="3" id="input">
							</td>
						</tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_team_photo" id="submitsave" value="Добавить">
					</td>
				</tr>
			</table>
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
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				    <tr>
                        <th width="20%" align="center">Title (РУС): </th>
                        <td width="80%"><input type="text" name="ph_title_ru" id="input100"></td>
                    </tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="ph_about_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
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
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <th width="20%" align="center">Title (УКР): </th>
                        <td width="80%"><input type="text" name="ph_title_ua" id="input100"></td>
                    </tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="ph_about_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
			<div id="lang_3">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
					<tr>
						{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
						{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
						{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
					</tr>
				</table>
				<br>
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <th width="20%" align="center">Title (ENG): </th>
                        <td width="80%"><input type="text" name="ph_title_en" id="input100"></td>
                    </tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="ph_about_en" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
		</form>
		<br>
		</center>
		</div>
		{* == VIDEO == *}
		<div id="gal_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					<td width="50%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_1');">Фото</a></td>
					<td width="50%" id="active"><a href="javascript:void(0)" onclick="javascript:showGalBlock('gal_2');">Видео</a></td>
				</tr>
			</table>
		<h1>Видео</h1>
		<center>
		<b>Код выделен красным:</b> http://www.youtube.com/watch?v=<b style="color: #f00">ХХХХХХХХХХХХ</b>&feature=popular...<br>
								этот код необходимо скопировать и вписать в поле "код".

		<form method="post" enctype="multipart/form-data">
			<input type="Hidden" name="t_id" value="{$team_item.t_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">код (youtube): </th>
							<td width="50%" align="center"><input type="text" name="v_code" id="input100"></td>
						</tr>
                        <tr>
                            <th width="50%" align="center">код (полный): </th>
                            <td width="50%" align="center"><textarea name="v_code_text" class="input100 mceNoEditor"></textarea></td>
                        </tr>
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="checkbox" name="v_is_active" checked></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Галерея: </th>
							<td width="50%" align="center">
							<select name="v_gallery_id" id="input100">
								{if !empty($news_video_gallery.vg_id)}<option value="{$news_video_gallery.vg_id}" selected>{$news_video_gallery.vg_title_ru}</option>{else}<option value="new" selected>Новая: {if !empty($news_item.n_title_ru)}{$news_item.n_title_ru}{else}{$team_item.t_title_ru}{/if}</option>{/if}
								<option value="0">без галереи</option>
							{if $gallery_list_video}
							{foreach key=key item=item from=$gallery_list_video name=images}
								{if empty($news_video_gallery.vg_id) || $news_video_gallery.vg_id != $item.id}<option value="{$item.id}">{$item.title}</option>{/if}
							{/foreach}
							{/if}
							</select>
							</td>
						</tr>
                        <tr>
                            <th>Рубрика (для галереи): </th>
                            <td>
                                <select name="vg_vc_id" id="input100">
                                    <option value="0">без рубрики</option>
                                    {if $video_category_list}
                                        {foreach key=key item=item from=$video_category_list name=category}
                                            <option value="{$item.vc_id}"{if !empty($news_video_gallery.vg_vc_id) && $news_video_gallery.vg_vc_id == $item.vc_id} selected="selected"{/if}>{$item.vc_title_ru}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                            </td>
                        </tr>
						<tr>
							<th width="50%" align="center">В папку: </th>
							<td width="50%" align="center">
							<select name="v_folder" id="input100">
								<option value="/">в корень "/"</option>
							</select>
							</td>
						</tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_team_video" id="submitsave" value="Добавить">
					</td>
				</tr>
			</table>
			<br>
			<div id="img_lang_1">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
					<tr>
						{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_1');">РУС</a></td>{/if}
						{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_2');">УКР</a></td>{/if}
						{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_3');">ENG</a></td>{/if}
					</tr>
				</table>
				<br>
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (РУС): </th>
						<td width="80%"><input type="text" name="v_title_ru" id="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="v_about_ru" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
			<div id="img_lang_2">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
					<tr>
						{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_1');">РУС</a></td>{/if}
						{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_2');">УКР</a></td>{/if}
						{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_3');">ENG</a></td>{/if}
					</tr>
				</table>
				<br>
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (УКР): </th>
						<td width="80%"><input type="text" name="v_title_ua" id="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="v_about_ua" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
			<div id="img_lang_3">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
					<tr>
						{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_1');">РУС</a></td>{/if}
						{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_2');">УКР</a></td>{/if}
						{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_3');">ENG</a></td>{/if}
					</tr>
				</table>
				<br>
				<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
					<tr>
						<th width="20%" align="center">Название (ENG): </th>
						<td width="80%"><input type="text" name="v_title_en" id="input100"></td>
					</tr>
					<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="v_about_en" rows="5" style="width: 100%;"></textarea></td></tr>
				</table>
			</div>
		</form>
		<br>
		</center>
		</div>
	</div>
	<div id="gallery_block_edit" style="display: {if !empty($smarty.get.g_f_item) || !empty($smarty.get.g_v_item)}block{else}none{/if};">
	{* == PHOTO == *}
	{if !empty($smarty.get.g_f_item)}
		<h1>Редактирование фотографии</h1>
		<center>

		<form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="ph_id" value="{$photo_item.ph_id}">
			<input type="Hidden" name="n_id" value="{$team_item.t_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="Checkbox" name="ph_is_active"{if $photo_item.ph_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">отображать в информере: </th>
							<td width="50%" align="center"><input type="checkbox" name="ph_is_informer"{if $photo_item.ph_is_informer == 'yes'} checked{/if}></td>
						</tr>
						<td colspan="2" height="10"></td>
						<tr>
							<th width="50%" align="center">Главная: </th>
							<td width="50%" align="center"><input type="checkbox" name="ph_type_main"{if $photo_item.ph_type_main == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Файл: </th>
							<td width="50%" align="center"><input type="File" name="file_photo" id="input100"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Галерея: </th>
							<td width="50%" align="center">
							<select name="ph_gallery_id" id="input100">
								{if !empty($team_photo_gallery.id)}<option value="{$team_photo_gallery.id}"{if $team_photo_gallery.id == $photo_item.ph_gallery_id} selected{/if}>{$team_photo_gallery.title}</option>{/if}
								<option value="0"{if !empty($photo_item.ph_gallery_id) && $photo_item.ph_gallery_id == 0} selected{/if}>без галереи</option>
							{if $gallery_list}
							{foreach key=key item=item from=$gallery_list name=images}
								{if empty($team_photo_gallery.id) || $team_photo_gallery.id != $item.id}<option value="{$item.id}"{if !empty($photo_item.ph_gallery_id) && $item.id == $photo_item.ph_gallery_id} selected{/if}>{$item.title}</option>{/if}
							{/foreach}
							{/if}
							</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">В папку: </th>
							<td width="50%" align="center">
							<select name="ph_folder" id="input100">
								<option value="/"{if $photo_item.ph_folder == '/'} selected{/if}>в корень "/"</option>
							</select>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">Подпись: </th>
							<td width="50%" align="center">
								<textarea name="ph_signature" rows="2" class="mceNoEditor" id="input100"></textarea>
							</td>
						</tr>
						<tr>
							<th width="50%" align="center">порядок: </th>
							<td width="50%" align="center">
								<input type="text" name="ph_order" size="3" value="{$photo_item.ph_order}" id="input">
							</td>
						</tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_edited_team_photo" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_edited_team_photo" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
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
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			    <tr>
                    <th width="20%" align="center">Title (РУС): </th>
                    <td width="80%"><input type="text" name="ph_title_ru" value="{$photo_item.ph_title_ru}" id="input100"></td>
                </tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="ph_about_ru" rows="5" style="width: 100%;">{$photo_item.ph_about_ru}</textarea></td></tr>
			</table>
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
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			    <tr>
                    <th width="20%" align="center">Title (УКР): </th>
                    <td width="80%"><input type="text" name="ph_title_ua" value="{$photo_item.ph_title_ua}" id="input100"></td>
                </tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="ph_about_ua" rows="5" style="width: 100%;">{$photo_item.ph_about_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="lang_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showBlokLang('lang_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			    <tr>
                    <th width="20%" align="center">Title (ENG): </th>
                    <td width="80%"><input type="text" name="ph_title_en" value="{$photo_item.ph_title_en}" id="input100"></td>
                </tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="ph_about_en" rows="5" style="width: 100%;">{$photo_item.ph_about_en}</textarea></td></tr>
			</table>
		</div>
		</form>

		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="25%" id="notactive"></td>
				<td width="50%" id="active"><a>Фотография</a></td>
				<td width="25%" id="notactive"></td>
			</tr>
		</table>
		<br>

		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" valign="top" align="center"><a href="../upload/photos{$photo_item.photo}" onclick="return bigImage(this);" target="_blank" title="{$photo_item.ph_about}"><img src="../upload/photos{$photo_item.photo_med}" border="0" alt="{$photo_item.ph_about}" style="max-width: 350px; max-height: 400px;"></a></td>
				<td width="50%" valign="top">
				<div id="conteiner" style="padding: 10px; margin: 0 10px 0 0;">
					<img src="../upload/photos{$photo_item.photo_small}" border="0" alt="{$photo_item.ph_about}" style="max-width: 90px; max-height: 400px; float: left; ">
					<div style="display: block; margin: 0 0 0 100px;">
						Большая<br>
						файл: <b>{$photo_item.photo_size}</b><br>
						размер: <b>{$photo_item.photo_imagesize.0}</b> x <b>{$photo_item.photo_imagesize.1}</b> px<br>
						<br>
						Средняя<br>
						файл: <b>{$photo_item.photo_med_size}</b><br>
						размер: <b>{$photo_item.photo_med_imagesize.0}</b> x <b>{$photo_item.photo_med_imagesize.1}</b> px<br>
						<br>
						Маленькая<br>
						файл: <b>{$photo_item.photo_small_size}</b><br>
						размер: <b>{$photo_item.photo_small_imagesize.0}</b> x <b>{$photo_item.photo_small_imagesize.1}</b> px<br>
						<br>
						Сохранение: {$photo_item.ph_datetime_add}<br>
						Редактирование: {$photo_item.ph_datetime_edit}<br>
					</div>
				</div>
				</td>
			</tr>
		</table>


			<br>
		</center>
	{/if}
	{* == VIDEO == *}
	{if !empty($smarty.get.g_v_item)}
		<h1>Редактирование видео</h1>
		<center>
		<b>Код выделен красным:</b> http://www.youtube.com/watch?v=<b style="color: #f00">ХХХХХХХХХХХХ</b>&feature=popular...<br>
								этот код необходимо скопировать и вписать в поле "код".
		<form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="v_id" value="{$video_item.v_id}">
			<input type="Hidden" name="n_id" value="{$team_item.t_id}">
			<table width="95%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="50%">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
						<tr>
							<th width="50%" align="center">код (youtube): </th>
							<td width="50%" align="center"><input type="text" name="v_code" value="{$video_item.v_code}" id="input100"></td>
						</tr>
                        <tr>
                            <th width="50%" align="center">код (полный): </th>
                            <td width="50%" align="center"><textarea name="v_code_text" class="input100 mceNoEditor">{$video_item.v_code_text}</textarea></td>
                        </tr>
						<tr>
							<th width="50%" align="center">вкл/откл: </th>
							<td width="50%" align="center"><input type="checkbox" name="v_is_active"{if $video_item.v_is_active == 'yes'} checked{/if}></td>
						</tr>
						<tr>
							<td colspan="2" height="10"></td>
						</tr>
						<tr>
							<th width="50%" align="center">Галерея: </th>
							<td width="50%" align="center">
								<select name="v_gallery_id" id="input100">
									{if !empty($news_video_gallery.vg_id)}<option value="{$news_video_gallery.vg_id}">{$news_video_gallery.vg_title_ru}</option>{else}<option value="new" selected>Новая: {if !empty($news_item.n_title_ru)}{$news_item.n_title_ru}{else}{$team_item.t_title_ru}{/if}</option>{/if}
									<option value="0"{if !empty($video_item.v_gallery_id) && $video_item.v_gallery_id == 0} selected{/if}>без галереи</option>
								{if $gallery_list_video}
								{foreach key=key item=item from=$gallery_list_video name=images}
									{if empty($news_video_gallery.vg_id) || $news_video_gallery.vg_id != $item.id}<option value="{$item.id}"{if !empty($video_item.v_gallery_id) && $item.id == $video_item.v_gallery_id} selected{/if}>{$item.title}</option>{/if}
								{/foreach}
								{/if}
								</select>
							</td>
						</tr>
                        <tr>
                            <th>Рубрика (для галереи): </th>
                            <td>
                                <select name="vg_vc_id" id="input100">
                                    <option value="0">без рубрики</option>
                                    {if $video_category_list}
                                        {foreach key=key item=item from=$video_category_list name=category}
                                            <option value="{$item.vc_id}"{if !empty($news_video_gallery.vg_vc_id) && $news_video_gallery.vg_vc_id == $item.vc_id} selected="selected"{/if}>{$item.vc_title_ru}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                            </td>
                        </tr>
						<tr>
							<th width="50%" align="center">В папку: </th>
							<td width="50%" align="center">
								<select name="v_folder" id="input100">
									<option value="/"{if $video_item.v_folder == '/'} selected{/if}>в корень "/"</option>
								</select>
							</td>
						</tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_edited_team_video" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_edited_team_video" id="submitdelete" value="Удалить">
					</td>
				</tr>
			</table>
			<br>
		<div id="img_lang_1">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (РУС): </th>
					<td width="80%"><input type="text" name="v_title_ru" value="{$video_item.v_title_ru}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="v_about_ru" rows="5" style="width: 100%;">{$video_item.v_about_ru}</textarea></td></tr>
			</table>
		</div>
		<div id="img_lang_2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (УКР): </th>
					<td width="80%"><input type="text" name="v_title_ua" value="{$video_item.v_title_ua}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="v_about_ua" rows="5" style="width: 100%;">{$video_item.v_about_ua}</textarea></td></tr>
			</table>
		</div>
		<div id="img_lang_3">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
				<tr>
					{if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_1');">РУС</a></td>{/if}
					{if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_2');">УКР</a></td>{/if}
					{if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showImgLang('img_lang_3');">ENG</a></td>{/if}
				</tr>
			</table>
			<br>
			<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<tr>
					<th width="20%" align="center">Название (ENG): </th>
					<td width="80%"><input type="text" name="v_title_en" value="{$video_item.v_title_en}" id="input100"></td>
				</tr>
				<tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="v_about_en" rows="5" style="width: 100%;">{$video_item.v_about_en}</textarea></td></tr>
			</table>
		</div>
		</form>

		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="25%" id="notactive"></td>
				<td width="50%" id="active"><a>Видео</a></td>
				<td width="25%" id="notactive"></td>
			</tr>
		</table>
		<br>

		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" valign="top" align="center">
					<object width="350" height="250"><param name="movie" value="http://www.youtube.com/v/{$video_item.v_code}?fs=1&amp;hl=ru_RU&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/{$video_item.v_code}?fs=1&amp;hl=ru_RU&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="350" height="250"></embed></object>
				</td>
				<td width="50%" valign="top">
				<div id="conteiner" style="padding: 10px; margin: 0 10px 0 0;">
					<img src="../upload/video_thumbs{$video_item.v_folder}{$video_item.v_id}-small.jpg?{$smarty.now}" border="0" style="max-width: 90px; max-height: 400px; float: left; ">
					<div style="display: block; margin: 0 0 0 100px;">
						<b>Код для размещения на страницах:</b>
						<br><br>
						<textarea name="mp_value" class="mceNoEditor" style="width: 240px; height: 100px;" onfocus="this.select();"><object width="560" height="340"><param name="movie" value="http://www.youtube.com/v/{$video_item.v_code}?fs=1&amp;hl=ru_RU&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/{$video_item.v_code}?fs=1&amp;hl=ru_RU&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="560" height="340"></embed></object></textarea>
						<br><br>
						<b>Ссылка на сайт в youtube.com:</b>
						<br><br>
						<a href="http://www.youtube.com/watch?v={$video_item.v_code}" target="_blank">http://www.youtube.com/watch?v={$video_item.v_code}</a>
						<br><br>
						Сохранение: {$video_item.v_datetime_add}<br>
						Редактирование: {$video_item.v_datetime_edit}<br>
					</div>
				</div>
				</td>
			</tr>
		</table>
		<br>
		<div id="conteiner" style="padding: 10px; margin: 10px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<td align="center">
					<a href="../upload/video_thumbs{$video_item.v_folder}{$video_item.v_id}.jpg?{$smarty.now}" onclick="return bigImage(this);" target="_blank" title="{$video_item.v_title}"><img src="../upload/video_thumbs{$video_item.v_folder}{$video_item.v_id}-med.jpg?{$smarty.now}" border="0" alt="{$video_item.v_title}" border="0" style="max-width: 200px; max-height: 200px; "></a>
				</td>
				<td>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
				<form method="post" enctype="multipart/form-data">
					<input type="Hidden" name="v_id" value="{$video_item.v_id}">
					<input type="Hidden" name="n_id" value="{$team_item.t_id}">
					<tr><th colspan="2">Картинка-превью для видео на сайте<br><br></th></tr>
					<tr><th width="20%" align="center">Файл: </th><td width="80%"><input type="File" name="file_photo_preview" id="input100"></td></tr>
					<tr>
						<td align="center" colspan="2">
						<br>
						<input type="submit" name="save_edited_team_preview" id="submitsave" value="Перезаписать">
						</td>
					</tr>
				</form>
				</table>
				</td>
			</tr>
			</table>
		</div>
		<br>
		</center>
	{/if}
	</div>

<script type="text/javascript">
function switchGalleryTab(tabType) {
	// Скрыть все блоки
	document.getElementById('gallery_block_list').style.display = 'none';
	document.getElementById('gallery_block_add').style.display = 'none';
	document.getElementById('gallery_block_edit').style.display = 'none';

	// Сбросить все вкладки на notactive
	document.getElementById('tab_gallery_list').className = 'notactive';
	document.getElementById('tab_gallery_edit').className = 'notactive';
	document.getElementById('tab_gallery_add').className = 'notactive';

	// Показать нужный блок и активировать вкладку
	if (tabType == 'list') {
		document.getElementById('gallery_block_list').style.display = 'block';
		document.getElementById('tab_gallery_list').className = 'active_left';
	} else if (tabType == 'add') {
		document.getElementById('gallery_block_add').style.display = 'block';
		document.getElementById('tab_gallery_add').className = 'active_right';
	} else if (tabType == 'edit') {
		document.getElementById('gallery_block_edit').style.display = 'block';
		document.getElementById('tab_gallery_edit').className = 'active';
	}
}
</script>