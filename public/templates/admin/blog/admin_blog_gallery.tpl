
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
			<tr>
				<td width="33%" id="{if empty($smarty.get.g_get) || $smarty.get.g_get == 'photos'}active_left{else}notactive{/if}"><a href="?show=blog&get=edit&item={$smarty.get.item}&cont=4">Список</a></td>
				<td width="34%" id="{if !empty($smarty.get.g_get) && $smarty.get.g_get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.g_f_item)}<a href="?show=blog&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_f_item={$smarty.get.g_f_item}">Редактировать</a>{elseif !empty($smarty.get.g_v_item)}<a href="?show=blog&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_v_item={$smarty.get.g_v_item}">Редактировать</a>{else}Редактировать{/if}</td>
				<td width="33%" id="{if !empty($smarty.get.g_get) && $smarty.get.g_get == 'add'}active_right{else}notactive{/if}"><a href="?show=blog&get=edit&item={$smarty.get.item}&cont=4&g_get=add">Добавить</a></td>
			</tr>
		</table>
	{if empty($smarty.get.g_get)}
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
			{if !empty($blog_photo_list)}
			{foreach key=key item=item from=$blog_photo_list name=images}
				<div class="image_item{if $item.ph_type_main == 'yes'}_main{/if}">
					<a href="?show=blog&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_f_item={$item.ph_id}"><img src="../upload/photos{$item.ph_folder}{$item.ph_path}?{$smarty.now}" width="166"></a>
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
			{if !empty($blog_video_list)}
			{foreach key=key item=item from=$blog_video_list name=images}
				<div class="image_item">
					<a href="?show=blog&get=edit&item={$smarty.get.item}&cont=4&g_get=edit&g_v_item={$item.v_id}"><img src="../upload/video_thumbs{$item.v_folder}{$item.v_id}-small.jpg?{$smarty.now}" width="166"></a>
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
	{/if}
		<center>
	{if !empty($smarty.get.g_get) && $smarty.get.g_get == 'add'}
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
			<input type="Hidden" name="id" value="{$post_item.id}">
			
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
						{*<tr>*}
							{*<th width="50%" align="center">Файл: </th>*}
							{*<td width="50%" align="center"><input type="File" name="file_photo" id="input100"></td>*}
						{*</tr>*}
						<tr>
                            <td colspan="2">
                                <div class="dropzone_block">
                                    <input type="hidden" name="files_array" id="dropzone_block_file_array" value="">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <div class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add files...</span>
                                        <!-- The file input field used as target for the file upload widget -->
                                        <input id="fileupload" type="file" name="files[]" multiple>
                                    </div>
                                    <br>
                                    <br>
                                    <!-- The global progress bar -->
                                    <div id="progress" class="progress">
                                        <div class="progress-bar progress-bar-success"></div>
                                    </div>
                                    <!-- The container for the uploaded files -->
                                    <div id="files" class="files"></div>
                                    <br>
                                </div>
                            </td>
                        </tr>
						<tr>
							<th width="50%" align="center">Галерея: </th>
							<td width="50%" align="center">
							<select name="ph_gallery_id" id="input100">
								{if $blog_photo_gallery}<option value="{$blog_photo_gallery.phg_id}" selected>{$blog_photo_gallery.phg_title_ru}</option>{else}<option value="new" selected>Новая: {$post_item.title}</option>{/if}
								<option value="0">без галереи</option>
							{if $gallery_list}
							{foreach key=key item=item from=$gallery_list name=images}
								{if $blog_photo_gallery.phg_id != $item.id}<option value="{$item.id}"{if $item.id == $photo_item.ph_gallery_id} selected{/if}>{$item.title}</option>{/if}
							{/foreach}
							{/if}
							</select>
							</td>
						</tr>
                        <tr>
                            <th>Рубрика (для галереи): </th>
                            <td>
                                <select name="phg_phc_id" id="input100">
                                    <option value="0">без рубрики</option>
                                    {if $photo_category_list}
                                        {foreach key=key item=item from=$photo_category_list name=category}
                                            <option value="{$item.phc_id}"{if $blog_photo_gallery.phg_phc_id == $item.phc_id} selected="selected"{/if}>{$item.phc_title_ru}</option>
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
                        <tr>
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="ph_country_auto_val" type="hidden" name="ph_country_auto_val" value="{$post_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="ph_country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $post_item.connection_country}
                                        {foreach item=item_cc from=$post_item.connection_country name=connection_country}
                                            <span class="ph_selected_country" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных стран</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Сущность Чемпионаты: </th>
                            <td width="50%" align="center">
                                <input id="ph_champ_auto_val" type="hidden" name="ph_champ_auto_val" value="{$post_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="ph_champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $post_item.connection_champ}
                                        {foreach item=item_cc from=$post_item.connection_champ name=connection_champ}
                                            <span class="ph_selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}

                            </td>
                        </tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_blog_photo" id="submitsave" value="Добавить">
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
			<input type="Hidden" name="id" value="{$post_item.id}">
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
								{if $blog_video_gallery}<option value="{$blog_video_gallery.vg_id}" selected>{$blog_video_gallery.vg_title_ru}</option>{else}<option value="new" selected>Новая: {$post_item.title}</option>{/if}
								<option value="0">без галереи</option>
							{if $gallery_list_video}
							{foreach key=key item=item from=$gallery_list_video name=images}
								{if $blog_video_gallery.vg_id != $item.id}<option value="{$item.id}">{$item.title}</option>{/if}
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
                                            <option value="{$item.vc_id}"{if $blog_video_gallery.vg_vc_id == $item.vc_id} selected="selected"{/if}>{$item.vc_title_ru}</option>
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
                        <tr>
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="v_country_auto_val" type="hidden" name="v_country_auto_val" value="{$post_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="v_country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $post_item.connection_country}
                                        {foreach item=item_cc from=$post_item.connection_country name=connection_country}
                                            <span class="v_selected_country" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных стран</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Сущность Чемпионаты: </th>
                            <td width="50%" align="center">
                                <input id="v_champ_auto_val" type="hidden" name="v_champ_auto_val" value="{$post_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="v_champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $post_item.connection_champ}
                                        {foreach item=item_cc from=$post_item.connection_champ name=connection_champ}
                                            <span class="v_selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}
                            </td>
                        </tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="add_new_blog_video" id="submitsave" value="Добавить">
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
	{/if}
	{* == PHOTO == *}
	{if !empty($smarty.get.g_f_item)}
		<h1>Редактирование фотографии</h1>
		<center>
		
		<form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="Hidden" name="ph_id" value="{$photo_item.ph_id}">
			<input type="Hidden" name="id" value="{$post_item.id}">
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
								<option value="{$blog_photo_gallery.id}"{if $blog_photo_gallery.id == $photo_item.ph_gallery_id} selected{/if}>{$blog_photo_gallery.title}</option>
								<option value="0"{if $photo_item.ph_gallery_id == 0} selected{/if}>без галереи</option>
							{if $gallery_list}
							{foreach key=key item=item from=$gallery_list name=images}
								{if $blog_photo_gallery.id != $item.id}<option value="{$item.id}"{if $item.id == $photo_item.ph_gallery_id} selected{/if}>{$item.title}</option>{/if}
							{/foreach}
							{/if}
							</select>
							</td>
						</tr>
                        <tr>
                            <th>Рубрика (для галереи): </th>
                            <td>
                                <select name="phg_phc_id" id="input100">
                                    <option value="0">без рубрики</option>
                                    {if $photo_category_list}
                                        {foreach key=key item=item from=$photo_category_list name=category}
                                            <option value="{if $blog_photo_gallery.phg_phc_id == $item.phc_id} selected="selected"{/if}>{$item.phc_title_ru}</option>
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
                        <tr>
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="ph_country_auto_val" type="hidden" name="ph_country_auto_val" value="{$photo_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="ph_country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $photo_item.connection_country}
                                        {foreach item=item_cc from=$photo_item.connection_country name=connection_country}
                                            <span class="ph_selected_country" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных стран</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Сущность Чемпионаты: </th>
                            <td width="50%" align="center">
                                <input id="ph_champ_auto_val" type="hidden" name="ph_champ_auto_val" value="{$photo_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="ph_champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $photo_item.connection_champ}
                                        {foreach item=item_cc from=$photo_item.connection_champ name=connection_champ}
                                            <span class="ph_selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}
                            </td>
                        </tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_edited_blog_photo" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_edited_blog_photo" id="submitdelete" value="Удалить">
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
			<input type="Hidden" name="id" value="{$post_item.id}">
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
									{if $blog_video_gallery}<option value="{$blog_video_gallery.vg_id}">{$blog_video_gallery.vg_title_ru}</option>{else}<option value="new" selected>Новая: {$post_item.title}</option>{/if}
									<option value="0"{if $video_item.v_gallery_id == 0} selected{/if}>без галереи</option>
								{if $gallery_list_video}
								{foreach key=key item=item from=$gallery_list_video name=images}
									{if $blog_video_gallery.vg_id != $item.id}<option value="{$item.id}"{if $item.id == $video_item.v_gallery_id} selected{/if}>{$item.title}</option>{/if}
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
                                            <option value="{$item.vc_id}"{if $blog_video_gallery.vg_vc_id == $item.vc_id} selected="selected"{/if}>{$item.vc_title_ru}</option>
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
                        <tr>
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="v_country_auto_val" type="hidden" name="v_country_auto_val" value="{$video_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="v_country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $video_item.connection_country}
                                        {foreach item=item_cc from=$video_item.connection_country name=connection_country}
                                            <span class="v_selected_country" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных стран</b>
                                {/if}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Сущность Чемпионаты: </th>
                            <td width="50%" align="center">
                                <input id="v_champ_auto_val" type="hidden" name="v_champ_auto_val" value="{$video_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="v_champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $video_item.connection_champ}
                                        {foreach item=item_cc from=$video_item.connection_champ name=connection_champ}
                                            <span class="v_selected_champ" data-id="{$item_cc.id}">{$item_cc.title}</span>
                                        {/foreach}
                                    {/if}
                                {else}
                                    <b>нет добавленных чемпионатов</b>
                                {/if}
                            </td>
                        </tr>
					</table>
					</td>
					<td width="50%" align="center" valign="middle">
						<input type="submit" name="save_edited_blog_video" id="submitsave" value="Сохранить">
						<br><br><br>
						<input type="submit" name="delete_edited_blog_video" id="submitdelete" value="Удалить">
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
					<input type="Hidden" name="id" value="{$post_item.id}">
					<tr><th colspan="2">Картинка-превью для видео на сайте<br><br></th></tr>
					<tr><th width="20%" align="center">Файл: </th><td width="80%"><input type="File" name="file_photo_preview" id="input100"></td></tr>
					<tr>
						<td align="center" colspan="2">
						<br>
						<input type="submit" name="save_edited_blog_preview" id="submitsave" value="Перезаписать">
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