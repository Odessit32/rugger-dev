<H1>Фотогалерея:</H1>

<div id="conteiner">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" id="{if empty($smarty.get.get) or $smarty.get.get == 'photos' or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}active_left{else}notactive{/if}"><a href="?show=photos">Фотографии</a></td>
        <td width="25%" id="{if $smarty.get.get == 'gallery' or $smarty.get.get == 'gallery_edit' or $smarty.get.get == 'gallery_add'}active{else}notactive{/if}"><a href="?show=photos&get=gallery">Галереи</a></td>
        <td width="25%" id="{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd'}active{else}notactive{/if}"><a href="?show=photos&get=categories">Рубрики</a></td>
        <td width="25%" id="{if $smarty.get.get == 'informer'}active_right{else}notactive{/if}"><a href="?show=photos&get=informer">Настройки</a></td>
    </tr>
</table>

{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd'}
    {include file="photos/admin_photos_categories.tpl"}
{/if}

{* ============== ГАЛЕРЕИ ============== *}

{if $smarty.get.get == 'gallery' or $smarty.get.get == 'gallery_edit' or $smarty.get.get == 'gallery_add'}
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
        <tr>
            <td width="33%" id="{if $smarty.get.get == 'gallery'}active_left{else}notactive{/if}"><a href="?show=photos&get=gallery">Список</a></td>
            <td width="33%" id="{if $smarty.get.get == 'gallery_edit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=photos&get=gallery_edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
            <td width="33%" id="{if $smarty.get.get == 'gallery_add'}active_right{else}notactive{/if}"><a href="?show=photos&get=gallery_add">Добавить</a></td>
        </tr>
    </table>
{/if}
{if $smarty.get.get == 'gallery'}
    <h1>Список галерей</h1>
    <center>
        <select id="input" style="width: 200px;" onchange="document.location.href='?show=photos&get=gallery&sort_list='+this.value">
            <option value="all"{if $smarty.get.sort_list == 'all'} selected{/if}>все</option>
            <option value="on"{if $smarty.get.sort_list == 'on'} selected{/if}>вкл</option>
            <option value="off"{if $smarty.get.sort_list == 'off'} selected{/if}>откл</option>
            <option value="staff"{if $smarty.get.sort_list == 'staff'} selected{/if}>добавлено из: Люди</option>
            <option value="news"{if $smarty.get.sort_list == 'news'} selected{/if}>добавлено из: Новости</option>
            <option value="team"{if $smarty.get.sort_list == 'team'} selected{/if}>добавлено из: Команды</option>
            <option value="club"{if $smarty.get.sort_list == 'club'} selected{/if}>добавлено из: Клубы</option>
            <option value="game"{if $smarty.get.sort_list == 'game'} selected{/if}>добавлено из: Игры</option>
            <option value="championship"{if $smarty.get.sort_list == 'championship'} selected{/if}>добавлено из: Чемпионаты</option>
            <option value="partners"{if $smarty.get.sort_list == 'partners'} selected{/if}>добавлено из: Партнеры</option>
            <option value="none"{if $smarty.get.sort_list == 'none'} selected{/if}>добавлено из: Не определено</option>
            <option value="count1"{if $smarty.get.sort_list == 'count1'} selected{/if}>с одной фотографией</option>
            <option value="countm1"{if $smarty.get.sort_list == 'countm1'} selected{/if}>больше одной фотографии</option>
        </select>
        <br><br>
        {if $gallery_list_page}
            <table width="80%" cellspacing="0" cellpadding="0" border="0" class="table_3">
                <tr>
                    <th width="80%">название</th>
                    <th width="10%">тип галереи</th>
                    <th width="10%">количество фото</th>
                    <th width="10%">вкл/откл</th>
                    <th width="10%">инф.</th>
                </tr>
                {foreach key=key item=item from=$gallery_list_page name=images}
                    <tr>
                        <td><a href="?show=photos&get=gallery_edit&item={$item.phg_id}">{if $item.phg_title_ru !== ''}{$item.phg_title_ru}{elseif $item.phg_title_ua !== ''}{$item.phg_title_ua}{else}Без названия{/if}</a></td>
                        <td align="center">{$item.phg_type}</td>
                        <td align="center">{$item.count_photo}</td>
                        <td align="center">{if $item.phg_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
                        <td align="center">{if $item.phg_is_informer == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
                    </tr>
                {/foreach}
            </table>
            {if $gallery_pages|@count >1}
                <div class="pages"><i>Страницы: </i>
                    {foreach key=key item=item from=$gallery_pages}
                        {if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=photos&get=gallery{if $smarty.get.sort_list != ''}&sort_list={$smarty.get.sort_list}{/if}&page={$key}">{$item}</a>{/if}
                    {/foreach}
                </div>
            {/if}
        {/if}
        {if !$gallery_list_page}
            <b>нет галерей</b>
        {/if}
        <br>
    </center>
{/if}
<center>
{if $smarty.get.get == 'gallery_add'}
    <h1>Добавление галереи</h1>
    <center>
        <form method="post">
            <table width="95%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <tr>
                                <th width="50%">Вкл./Откл.: </th>
                                <td width="50%">
                                    <input type="checkbox" name="phg_is_active" checked>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%">В информере вкл./откл.: </th>
                                <td width="50%">
                                    <input type="checkbox" name="phg_is_informer" checked>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th>Рубрика: </th>
                                <td>
                                    <select name="phg_phc_id" id="input100">
                                        <option value="0">без рубрики</option>
                                        {if $category_list}
                                            {foreach key=key item=item from=$category_list name=category}
                                                <option value="{$item.phc_id}">{$item.phc_title_ru}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Дата: </th>
                                <td>
                                    <nobr><select name="phg_date_day" id="input">
                                            {section name = day start = 1 loop = 32}
                                                <option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                            {/section}
                                        </select>
                                        <select name="phg_date_month" id="input">
                                            {section name = month start = 1 loop = 13}
                                                <option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                            {/section}
                                        </select>
                                        <select name="phg_date_year" id="input">
                                            {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                            {assign var="now_year" value=$now_year+2}
                                            {section name = year start = 2000 loop = $now_year}
                                                <option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                            {/section}
                                        </select>

                                        <select name="phg_date_hour" id="input">
                                            {section name = hour start = 0 loop = 24}
                                                <option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                            {/section}
                                        </select>
                                        <select name="phg_date_minute" id="input">
                                            {section name = minute start = 0 loop = 60}
                                                <option value="{$smarty.section.minute.index}"{if $smarty.now|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                            {/section}
                                        </select></nobr>
                                </td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Страны: </th>
                                <td width="50%" align="center">
                                    <input id="ph_country_auto_val" type="hidden" name="ph_country_auto_val" value="">
                                    {if $country_list}
                                        <select name="country_" id="ph_country_auto" class="country_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$country_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
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
                                    <input id="ph_champ_auto_val" type="hidden" name="ph_champ_auto_val" vlue="">
                                    {if $champ_list}
                                        <select name="champ_" id="ph_champ_auto" class="champ_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$champ_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
                                    {else}
                                        <b>нет добавленных чемпионатов</b>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" align="center" valign="middle">
                        <input type="submit" name="add_new_photo_gallery" id="submitsave" value="Добавить">
                    </td>
                </tr>
            </table>
            <br>
            <div id="cont_1">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
                    </tr>
                </table>
                <br>
                <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <th width="20%" align="center">Название (РУС): </th>
                        <td width="80%"><input type="text" name="phg_title_ru" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="phg_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
                </table>
            </div>
            <div id="cont_2">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
                    </tr>
                </table>
                <br>
                <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <th width="20%" align="center">Название (УКР): </th>
                        <td width="80%"><input type="text" name="phg_title_ua" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="phg_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
                </table>
            </div>
            <div id="cont_3">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
                    </tr>
                </table>
                <br>
                <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <th width="20%" align="center">Название (ENG): </th>
                        <td width="80%"><input type="text" name="phg_title_en" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="phg_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
                </table>
            </div>
            <div id="cont_4">

            </div>
        </form>
        <br>
    </center>
{/if}
{if $smarty.get.get == 'gallery_edit'}
    <h1>Редактирование галереи</h1>
    <center>
    <form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
        <input type="Hidden" name="phg_id" value="{$gallery_item.phg_id}">
        <table width="95%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="50%">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="50%">Вкл./Откл.: </th>
                            <td width="50%">
                                <input type="checkbox" name="phg_is_active"{if $gallery_item.phg_is_active == 'yes'} checked{/if}>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%">В информере вкл./откл.: </th>
                            <td width="50%">
                                <input type="checkbox" name="phg_is_informer"{if $gallery_item.phg_is_informer == 'yes'} checked{/if}>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th>Рубрика: </th>
                            <td>
                                <select name="phg_phc_id" id="input50">
                                    <option value="0">без рубрики</option>
                                    {if $category_list}
                                        {foreach key=key item=item from=$category_list name=category}
                                            <option value="{$item.phc_id}"{if $item.phc_id == $gallery_item.phg_phc_id} selected{/if}>{$item.phc_title_ru}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Дата: </th>
                            <td>
                                <nobr><select name="phg_date_day" id="input">
                                        {section name = day start = 1 loop = 32}
                                            <option value="{$smarty.section.day.index}"{if $gallery_item.phg_datetime_pub|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                        {/section}
                                    </select>
                                    <select name="phg_date_month" id="input">
                                        {section name = month start = 1 loop = 13}
                                            <option value="{$smarty.section.month.index}"{if $gallery_item.phg_datetime_pub|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                        {/section}
                                    </select>
                                    <select name="phg_date_year" id="input">
                                        {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                        {assign var="now_year" value=$now_year+2}
                                        {section name = year start = 2000 loop = $now_year}
                                            <option value="{$smarty.section.year.index}"{if $gallery_item.phg_datetime_pub|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                        {/section}
                                    </select>

                                    <select name="phg_date_hour" id="input">
                                        {section name = hour start = 0 loop = 24}
                                            <option value="{$smarty.section.hour.index}"{if $gallery_item.phg_datetime_pub|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                        {/section}
                                    </select>
                                    <select name="phg_date_minute" id="input">
                                        {section name = minute start = 0 loop = 60}
                                            <option value="{$smarty.section.minute.index}"{if $gallery_item.phg_datetime_pub|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                        {/section}
                                    </select></nobr>
                            </td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Страны: </th>
                            <td width="50%" align="center">
                                <input id="ph_country_auto_val" type="hidden" name="ph_country_auto_val" value="{$gallery_item.connection_country_val}">
                                {if $country_list}
                                    <select name="country_" id="ph_country_auto" class="country_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$country_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $gallery_item.connection_country}
                                        {foreach item=item_cc from=$gallery_item.connection_country name=connection_country}
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
                                <input id="ph_champ_auto_val" type="hidden" name="ph_champ_auto_val" value="{$gallery_item.connection_champ_val}">
                                {if $champ_list}
                                    <select name="champ_" id="ph_champ_auto" class="champ_auto">
                                        <option value="">------------</option>
                                        {foreach key=key item=item from=$champ_list name=countries_list}
                                            <option value="{$item.id}">{$item.title}</option>
                                        {/foreach}
                                    </select>
                                    {if $gallery_item.connection_champ}
                                        {foreach item=item_cc from=$gallery_item.connection_champ name=connection_champ}
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
                    <input type="submit" name="save_photo_gallery" id="submitsave" value="Сохранить">
                    <br><br><br>
                    {if $gallery_item.count_photo == 0}Фотогалерею можно удалить<br>{else}
                        Фотогалерею нельзя удалить, т.к. она не пустая.{/if}

                    {if $gallery_item.count_photo == 0}<input type="submit" name="delete_edited_photo_gallery" id="submitdelete" value="Удалить">{/if}
                    {if $gallery_item.count_photo > 0}
                        <br><br>Переместить все фотографии <br>
                        <b>в </b>
                        <select name="to_gallery_id" id="input50">
                            <option value="0">без галереи</option>
                            {if $gallery_list}
                                {foreach key=key item=item from=$gallery_list name=images}
                                    {if $item.id != $gallery_item.phg_id}<option value="{$item.id}">{$item.title}</option>{/if}
                                {/foreach}
                            {/if}
                        </select>
                        <br>
                        <input type="submit" name="trans_photos_gallery" id="submitsave" value="Переместить">
                    {/if}
                </td>
            </tr>
        </table>
        <br>
        <div id="cont_1">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                <tr>
                    {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                    {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                    {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
                </tr>
            </table>
            <br>
            <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                <tr>
                    <th width="20%" align="center">Название (РУС): </th>
                    <td width="80%"><input type="text" name="phg_title_ru" value="{$gallery_item.phg_title_ru}" id="input100"></td>
                </tr>
                <tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="phg_description_ru" rows="5" style="width: 100%;">{$gallery_item.phg_description_ru}</textarea></td></tr>
            </table>
        </div>
        <div id="cont_2">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                <tr>
                    {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                    {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                    {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
                </tr>
            </table>
            <br>
            <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                <tr>
                    <th width="20%" align="center">Название (УКР): </th>
                    <td width="80%"><input type="text" name="phg_title_ua" value="{$gallery_item.phg_title_ua}" id="input100"></td>
                </tr>
                <tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="phg_description_ua" rows="5" style="width: 100%;">{$gallery_item.phg_description_ua}</textarea></td></tr>
            </table>
        </div>
        <div id="cont_3">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                <tr>
                    {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                    {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                    {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
                </tr>
            </table>
            <br>
            <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                <tr>
                    <th width="20%" align="center">Название (ENG): </th>
                    <td width="80%"><input type="text" name="phg_title_en" value="{$gallery_item.phg_title_en}" id="input100"></td>
                </tr>
                <tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="phg_description_en" rows="5" style="width: 100%;">{$gallery_item.phg_description_en}</textarea></td></tr>
            </table>
        </div>
        <div id="cont_4">

        </div>
    </form>
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
        <tr>
            <td width="25%" id="notactive"></td>
            <td width="50%" id="active"><a>Всего в галерее {$gallery_item.count_photo} фото.</a></td>
            <td width="25%" id="notactive"></td>
        </tr>
    </table>
    <br>

    {if $photo_list}
        <div style="display: block; width: 745px; max-height: 900px; overflow-y: scrol; overflow-x: hidden;">
            {foreach key=key item=item from=$photo_list name=images}
                <div class="image_form_item"{if $item.ph_type_main == 'yes'} style="background: #ddd;"{/if}>
                    <form onsubmit="submitFormItem(this.ph_id.value, this.ph_order.value); return false">
                        <input type="hidden" name="ph_id" value="{$item.ph_id}">
                        <div class="order">
                            <input type="text" name="ph_order" value="{$item.ph_order}" size="3" id="input">
                            <br>
                            <input type="submit" name="save_item_photo_a" value="сохранить" id="s_{$item.ph_id}" class="subm">
                        </div>
                        <div class="photo">
                            <a href="?show=photos&get=edit&item={$item.ph_id}"><img src="../{$item.small}" border="0"></a>
                        </div>
                        <div class="text">
                            <a href="javascript:void(0)" onclick="javascript:imageFormItem('{$item.ph_id}', 'ru');" id="a_{$item.ph_id}_ru"><b>рус</b></a>
                            <a href="javascript:void(0)" onclick="javascript:imageFormItem('{$item.ph_id}', 'ua');" id="a_{$item.ph_id}_ua">укр</a>
                            <a href="javascript:void(0)" onclick="javascript:imageFormItem('{$item.ph_id}', 'en');" id="a_{$item.ph_id}_en">eng</a>
                            <br>
                            <div class="about" id="about_{$item.ph_id}_ru">{$item.ph_about_ru}</div>
                            <div class="about" id="about_{$item.ph_id}_ua" style="display: none">{$item.ph_about_ua}</div>
                            <div class="about" id="about_{$item.ph_id}_en" style="display: none">{$item.ph_about_en}</div>
                        </div>

                    </form>
                </div>
            {/foreach}

            {*foreach key=key item=item from=$photo_list name=images}
                <div class="image_item{if $item.ph_type_main == 'yes'}_main{/if}">
                    <a href="?show=photos&get=edit&item={$item.ph_id}"><img src="../upload/photos{$item.ph_folder}{$item.ph_path}" width="166"></a>
                    <input type="Text" value="upload/photos{$item.ph_folder}{$item.ph_path}" id="input_item_image" onfocus="this.select();">
                </div>
            {/foreach*}

        </div>
    {/if}

    </center>
{/if}

{* ============== ФОТОГРАФИИ ============== *}

{if empty($smarty.get.get) or $smarty.get.get == 'photos' or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
        <tr>
            <td width="33%" id="{if $smarty.get.get == 'photos' or empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=photos">Список</a></td>
            <td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=photos&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
            <td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=photos&get=add">Добавить</a></td>
        </tr>
    </table>
{/if}
{if empty($smarty.get.get) or $smarty.get.get == 'photos'}
    <h1>Список фотографий</h1>
    <center>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="center">
                    <select id="input50" onchange="document.location.href='?show=photos&phg='+this.value">
                        <option value="all"{if $smarty.get.phg == 'all'} selected{/if}>все фотографии</option>
                        <option value="no"{if $smarty.get.phg == 'no'} selected{/if}>без галереи</option>
                        {if $gallery_list}
                            {foreach key=key item=item from=$gallery_list name=images}
                                <option value="{$item.id}"{if $smarty.get.phg == $item.id} selected{/if}>{$item.title}</option>
                            {/foreach}
                        {/if}
                    </select>
                    <br><br>
                </td>
            </tr>
            <tr><td align="center">
                    {if $photo_list_page}
                        {foreach key=key item=item from=$photo_list_page name=images}
                            <div class="image_item">
                                <a href="?show=photos&get=edit&item={$item.ph_id}"><img src="../upload/photos{$item.med}" width="166"></a>
                                <input type="Text" value="/upload/photos{$item.ph_folder}{$item.ph_path}" id="input_item_image" onfocus="this.select();">
                            </div>
                        {/foreach}
                        {if $photo_pages|@count >1}
                            <div class="pages"><i>Страницы: </i>
                                {foreach key=key item=item from=$photo_pages}
                                    {if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=photos{if $smarty.get.phg != ''}&phg={$smarty.get.phg}{/if}&page={$key}">{$item}</a>{/if}
                                {/foreach}
                            </div>
                        {/if}
                    {else}
                        <b>нет фотографий</b>
                    {/if}
                </td></tr>
        </table>
        <br>
    </center>
{/if}
<center>
{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
    <h1>Добавление фотографии</h1>
    <center>
        <form method="post" enctype="multipart/form-data">
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
                                <td colspan="2" height="10">
                                </td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Галерея: </th>
                                <td width="50%" align="center">
                                    <select name="ph_gallery_id" id="input100">
                                        <option value="0">без галереи</option>
                                        {if $gallery_list}
                                            {foreach key=key item=item from=$gallery_list name=images}
                                                <option value="{$item.id}">{$item.title}</option>
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
                                    <input id="ph_country_auto_val" type="hidden" name="ph_country_auto_val" value="">
                                    {if $country_list}
                                        <select name="country_" id="ph_country_auto" class="country_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$country_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
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
                                    <input id="ph_champ_auto_val" type="hidden" name="ph_champ_auto_val" vlue="">
                                    {if $champ_list}
                                        <select name="champ_" id="ph_champ_auto" class="champ_auto">
                                            <option value="">------------</option>
                                            {foreach key=key item=item from=$champ_list name=countries_list}
                                                <option value="{$item.id}">{$item.title}</option>
                                            {/foreach}
                                        </select>
                                    {else}
                                        <b>нет добавленных чемпионатов</b>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" align="center" valign="middle">
                        <input type="submit" name="add_new_photo" id="submitsave" value="Добавить">
                    </td>
                </tr>
            </table>
            <br>
            <div id="cont_1">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
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
            <div id="cont_2">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
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
            <div id="cont_3">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
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
            <div id="cont_4">

            </div>
        </form>

    </center>
{/if}
{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}
    <h1>Редактирование фотографии</h1>
    <center>

        <form method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Вы уверены?')) return false">
            <input type="Hidden" name="ph_id" value="{$photo_item.ph_id}">
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
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">главная: </th>
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
                                        <option value="0"{if $photo_item.ph_gallery_id == 0} selected{/if}>без галереи</option>
                                        {if $gallery_list}
                                            {foreach key=key item=item from=$gallery_list name=images}
                                                <option value="{$item.id}"{if $item.id == $photo_item.ph_gallery_id} selected{/if}>{$item.title}</option>
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
                        <input type="submit" name="save_edited_photo" id="submitsave" value="Сохранить">
                        <br><br><br>
                        <input type="submit" name="delete_edited_photo" id="submitdelete" value="Удалить">
                    </td>
                </tr>
            </table>
            <br>
            <div id="cont_1">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
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
            <div id="cont_2">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
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
            <div id="cont_3">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
                    <tr>
                        {if $main_settings.rus.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_1');">РУС</a></td>{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}<td width="25%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_2');">УКР</a></td>{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}<td width="25%" id="active"><a href="javascript:void(0)" onclick="javascript:showTextLang('cont_3');">ENG</a></td>{/if}
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
            <div id="cont_4">

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

{* ============== Настройки ============== *}

{if $smarty.get.get == 'informer'}
    <h1>Настройки</h1>
    {*
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">отображать (вкл./откл.)</td>
                <td width="34%" align="center"><input type="checkbox" name="is_active"{if $informer.set_value > 0} checked{/if}></td>
                <td align="center" width="33%"><input type="submit" name="save_informer_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>
    *}
    <b>Количество фото в информере:</b><br><br>
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input" value="{$photo_settings_list.count_photo_informer}"></td>
                <td width="33%" align="center"><input type="submit" name="save_photo_informer_count_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>
    <b>Количество фото на одну страницу в разделе фото:</b><br><br>
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input" value="{$photo_settings_list.count_photo_page}"></td>
                <td width="33%" align="center"><input type="submit" name="save_photo_count_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>
    <b>Количество фото на первой странице в разделе фото:</b><br><br>
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input" value="{$photo_settings_list.count_photo_page_index}"></td>
                <td width="33%" align="center"><input type="submit" name="save_photo_index_count_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>


    <br><br>
    {literal}
        <style type="text/css">
            #news_1 { display: blok; }
            #news_2, #news_3 { display: none; }
        </style>
    {/literal}
    <b>Заголовки информера фотографий:</b><br><br>
    <!-- РУС -->
    <div id="news_1">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
            <tr>
                {if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="active_left"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','1');">РУС</a></td>{/if}
                {if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','2');">УКР</a></td>{/if}
                {if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','3');">ENG</a></td>{/if}
            </tr>
        </table>
        <br>
        <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <form method="post">
                <input type="Hidden" name="lang" value="rus">
                <input type="Hidden" name="cnv_name" value="inform_photo">
                <tr>
                    <td width="10%">Заголовок: </td>
                    <td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.inform_photo}"></td>
                    <td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
                </tr>
            </form>
        </table>
    </div>
    <!-- УКР -->
    <div id="news_2">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
            <tr>
                {if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','1');">РУС</a></td>{/if}
                {if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="active"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','2');">УКР</a></td>{/if}
                {if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','3');">ENG</a></td>{/if}
            </tr>
        </table>
        <br>
        <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <form method="post">
                <input type="Hidden" name="lang" value="ukr">
                <input type="Hidden" name="cnv_name" value="inform_photo">
                <tr>
                    <td width="10%">Заголовок: </td>
                    <td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.inform_photo}"></td>
                    <td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
                </tr>
            </form>
        </table>
    </div>
    <!-- ENG -->
    <div id="news_3">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td colspan="4" height="1" bgcolor="#b2b2b2"></td></tr>
            <tr>
                {if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','1');">РУС</a></td>{/if}
                {if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" id="notactive"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','2');">УКР</a></td>{/if}
                {if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" id="active_right"><a href="javascript:void(0)" onclick="javascript:showVarLang('news_','3');">ENG</a></td>{/if}
            </tr>
        </table>
        <br>
        <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <form method="post">
                <input type="Hidden" name="lang" value="eng">
                <input type="Hidden" name="cnv_name" value="inform_photo">
                <tr>
                    <td width="10%">Заголовок: </td>
                    <td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.inform_photo}"></td>
                    <td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
                </tr>
            </form>
        </table>
    </div>
    <br>

{/if}


<br>
</div>