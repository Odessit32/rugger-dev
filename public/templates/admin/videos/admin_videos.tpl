<H1>Видеогалерея:</H1>

<div id="conteiner">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" id="{if empty($smarty.get.get) or $smarty.get.get == 'videos' or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}active_left{else}notactive{/if}"><a href="?show=videos">Видео</a></td>
        <td width="25%" id="{if $smarty.get.get == 'gallery' or $smarty.get.get == 'gallery_edit' or $smarty.get.get == 'gallery_add'}active{else}notactive{/if}"><a href="?show=videos&get=gallery">Галереи</a></td>
        <td width="25%" id="{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd'}active{else}notactive{/if}"><a href="?show=videos&get=categories">Рубрики</a></td>
        <td width="25%" id="{if $smarty.get.get == 'informer'}active_right{else}notactive{/if}"><a href="?show=videos&get=informer">Настройки</a></td>
    </tr>
</table>
{if $smarty.get.get == 'categories' or $smarty.get.get == 'categedit' or $smarty.get.get == 'categadd'}
    {include file="videos/admin_videos_categories.tpl"}
{/if}

{* ============== ГАЛЕРЕИ ============== *}

{if $smarty.get.get == 'gallery' or $smarty.get.get == 'gallery_edit' or $smarty.get.get == 'gallery_add'}
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
        <tr>
            <td width="33%" id="{if $smarty.get.get == 'gallery'}active_left{else}notactive{/if}"><a href="?show=videos&get=gallery">Список</a></td>
            <td width="33%" id="{if $smarty.get.get == 'gallery_edit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=videos&get=gallery_edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
            <td width="33%" id="{if $smarty.get.get == 'gallery_add'}active_right{else}notactive{/if}"><a href="?show=videos&get=gallery_add">Добавить</a></td>
        </tr>
    </table>
{/if}
{if $smarty.get.get == 'gallery'}
    <h1>Список галерей</h1>
    <center>
        <select id="input" style="width: 200px;" onchange="document.location.href='?show=videos&get=gallery&sort_list='+this.value">
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
                    <th width="10%">количество видео</th>
                    <th width="10%">вкл/откл</th>
                </tr>
                {foreach key=key item=item from=$gallery_list_page name=images}
                    <tr>
                        <td><a href="?show=videos&get=gallery_edit&item={$item.vg_id}">{if $item.vg_title_ru !== ''}{$item.vg_title_ru}{elseif $item.vg_title_ua !== ''}{$item.vg_title_ua}{else}Без названия{/if}</a></td>
                        <td align="center">{$item.vg_type}</td>
                        <td align="center">{$item.count_video}</td>
                        <td align="center">{if $item.vg_is_active == 'no'}<img src="images/off.jpg" alt="откл" border="0">{else}<img src="images/on.jpg" alt="вкл" border="0">{/if}</td>
                    </tr>
                {/foreach}
            </table>
            {if $gallery_pages|@count >1}
                <div class="pages"><i>Страницы: </i>
                    {foreach key=key item=item from=$gallery_pages}
                        {if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=videos&get=gallery{if $smarty.get.sort_list != ''}&sort_list={$smarty.get.sort_list}{/if}&page={$key}">{$item}</a>{/if}
                    {/foreach}
                </div>
            {/if}
        {else}
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
                                    <input type="checkbox" name="vg_is_active" checked>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th width="50%">В информере вкл./откл.: </th>
                                <td width="50%">
                                    <input type="checkbox" name="vg_is_informer" checked>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            <tr>
                                <th>Рубрика: </th>
                                <td>
                                    <select name="vg_vc_id" id="input50">
                                        <option value="0">без рубрики</option>
                                        {if $category_list}
                                            {foreach key=key item=item from=$category_list name=category}
                                                <option value="{$item.vc_id}">{$item.vc_title_ru}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Дата: </th>
                                <td>
                                    <nobr><select name="vg_date_day" id="input">
                                            {section name = day start = 1 loop = 32}
                                                <option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                            {/section}
                                        </select>
                                        <select name="vg_date_month" id="input">
                                            {section name = month start = 1 loop = 13}
                                                <option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                            {/section}
                                        </select>
                                        <select name="vg_date_year" id="input">
                                            {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                            {assign var="now_year" value=$now_year+2}
                                            {section name = year start = 2000 loop = $now_year}
                                                <option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                            {/section}
                                        </select>

                                        <select name="vg_date_hour" id="input">
                                            {section name = hour start = 0 loop = 24}
                                                <option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                            {/section}
                                        </select>
                                        <select name="vg_date_minute" id="input">
                                            {section name = minute start = 0 loop = 60}
                                                <option value="{$smarty.section.minute.index}"{if $smarty.now|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                            {/section}
                                        </select></nobr>
                                </td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Страны: </th>
                                <td width="50%" align="center">
                                    <input id="v_country_auto_val" type="hidden" name="v_country_auto_val" value="">
                                    {if $country_list}
                                        <select name="country_" id="v_country_auto" class="country_auto">
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
                                    <input id="v_champ_auto_val" type="hidden" name="v_champ_auto_val" vlue="">
                                    {if $champ_list}
                                        <select name="champ_" id="v_champ_auto" class="champ_auto">
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
                        <input type="submit" name="add_new_video_gallery" id="submitsave" value="Добавить">
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
                        <td width="80%"><input type="text" name="vg_title_ru" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="vg_description_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
                        <td width="80%"><input type="text" name="vg_title_ua" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="vg_description_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
                        <td width="80%"><input type="text" name="vg_title_en" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="vg_description_en" rows="5" style="width: 100%;"></textarea></td></tr>
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
    <input type="Hidden" name="vg_id" value="{$gallery_item.vg_id}">
    <table width="95%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="50%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                    <tr>
                        <th width="50%">Вкл./Откл.: </th>
                        <td width="50%">
                            <input type="checkbox" name="vg_is_active"{if $gallery_item.vg_is_active == 'yes'} checked{/if}>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" height="10"></td>
                    </tr>
                    <tr>
                        <th width="50%">В информере вкл./откл.: </th>
                        <td width="50%">
                            <input type="checkbox" name="vg_is_informer"{if $gallery_item.vg_is_informer == 'yes'} checked{/if}>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" height="10"></td>
                    </tr>
                    <tr>
                        <th>Рубрика: </th>
                        <td>
                            <select name="vg_vc_id" id="input50">
                                <option value="0">без рубрики</option>
                                {if $category_list}
                                    {foreach key=key item=item from=$category_list name=category}
                                        <option value="{$item.vc_id}"{if $item.vc_id == $gallery_item.vg_vc_id} selected{/if}>{$item.vc_title_ru}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Дата: </th>
                        <td>
                            <nobr><select name="vg_date_day" id="input">
                                    {section name = day start = 1 loop = 32}
                                        <option value="{$smarty.section.day.index}"{if $gallery_item.vg_datetime_pub|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                    {/section}
                                </select>
                                <select name="vg_date_month" id="input">
                                    {section name = month start = 1 loop = 13}
                                        <option value="{$smarty.section.month.index}"{if $gallery_item.vg_datetime_pub|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                    {/section}
                                </select>
                                <select name="vg_date_year" id="input">
                                    {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                    {assign var="now_year" value=$now_year+2}
                                    {section name = year start = 2000 loop = $now_year}
                                        <option value="{$smarty.section.year.index}"{if $gallery_item.vg_datetime_pub|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                    {/section}
                                </select>

                                <select name="vg_date_hour" id="input">
                                    {section name = hour start = 0 loop = 24}
                                        <option value="{$smarty.section.hour.index}"{if $gallery_item.vg_datetime_pub|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                    {/section}
                                </select>
                                <select name="vg_date_minute" id="input">
                                    {section name = minute start = 0 loop = 60}
                                        <option value="{$smarty.section.minute.index}"{if $gallery_item.vg_datetime_pub|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                    {/section}
                                </select></nobr>
                        </td>
                    </tr>
                    <tr>
                        <th width="50%" align="center">Страны: </th>
                        <td width="50%" align="center">
                            <input id="v_country_auto_val" type="hidden" name="v_country_auto_val" value="{$gallery_item.connection_country_val}">
                            {if $country_list}
                                <select name="country_" id="v_country_auto" class="country_auto">
                                    <option value="">------------</option>
                                    {foreach key=key item=item from=$country_list name=countries_list}
                                        <option value="{$item.id}">{$item.title}</option>
                                    {/foreach}
                                </select>
                                {if $gallery_item.connection_country}
                                    {foreach item=item_cc from=$gallery_item.connection_country name=connection_country}
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
                            <input id="v_champ_auto_val" type="hidden" name="v_champ_auto_val" value="{$gallery_item.connection_champ_val}">
                            {if $champ_list}
                                <select name="champ_" id="v_champ_auto" class="champ_auto">
                                    <option value="">------------</option>
                                    {foreach key=key item=item from=$champ_list name=countries_list}
                                        <option value="{$item.id}">{$item.title}</option>
                                    {/foreach}
                                </select>
                                {if $gallery_item.connection_champ}
                                    {foreach item=item_cc from=$gallery_item.connection_champ name=connection_champ}
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
                <input type="submit" name="save_video_gallery" id="submitsave" value="Сохранить">
                <br><br><br>
                {if $gallery_item.count_video == 0}Видеогалерею можно удалить<br>{else}
                    Видеогалерею нельзя удалить, т.к. она не пустая.{/if}
                {if $gallery_item.count_video == 0}<input type="submit" name="delete_edited_video_gallery" id="submitdelete" value="Удалить">{/if}

                {if $gallery_item.count_video > 0}
                    <br><br>Переместить всё видео <br>
                    <b>в </b>
                    <select name="to_gallery_id" id="input50">
                        <option value="0">без галереи</option>
                        {if $gallery_list}
                            {foreach key=key item=item from=$gallery_list name=images}
                                {if $item.id != $gallery_item.vg_id}<option value="{$item.id}">{$item.title}</option>{/if}
                            {/foreach}
                        {/if}
                    </select>
                    <br>
                    <input type="submit" name="trans_videos_gallery" id="submitsave" value="Переместить">
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
                <td width="80%"><input type="text" name="vg_title_ru" value="{$gallery_item.vg_title_ru}" id="input100"></td>
            </tr>
            <tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="vg_description_ru" rows="5" style="width: 100%;">{$gallery_item.vg_description_ru}</textarea></td></tr>
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
                <td width="80%"><input type="text" name="vg_title_ua" value="{$gallery_item.vg_title_ua}" id="input100"></td>
            </tr>
            <tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="vg_description_ua" rows="5" style="width: 100%;">{$gallery_item.vg_description_ua}</textarea></td></tr>
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
                <td width="80%"><input type="text" name="vg_title_en" value="{$gallery_item.vg_title_en}" id="input100"></td>
            </tr>
            <tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="vg_description_en" rows="5" style="width: 100%;">{$gallery_item.vg_description_en}</textarea></td></tr>
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
        <td width="50%" id="active"><a>Всего в галерее {$gallery_item.count_video} видео.</a></td>
        <td width="25%" id="notactive"></td>
    </tr>
</table>
<br>

{if $video_list}
<div style="display: block; width: 740px; height: 220px; overflow-x: scrol; overflow-y: hidden;">
    <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            {foreach key=key item=item from=$video_list name=images}
            <td>
                <div class="image_item">
                    <a href="?show=videos&get=edit&item={$item.v_id}"><img src="../upload/video_thumbs{$item.v_folder}{$item.v_id}-small.jpg?{$smarty.now}" width="166"></a>
                    <input type="Text" value="{$item.v_code}" id="input_item_image" onfocus="this.select();">
                </div>
</div>
{/foreach}
</tr>
</table>
</div>
{/if}

<br>
</center>
{/if}

{* ============== ВИДЕО ============== *}

{if empty($smarty.get.get) or $smarty.get.get == 'videos' or $smarty.get.get == 'edit' or $smarty.get.get == 'add'}
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
        <tr>
            <td width="33%" id="{if $smarty.get.get == 'videos' or empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=videos">Список</a></td>
            <td width="34%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if $smarty.get.item>0}<a href="?show=videos&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
            <td width="33%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active_right{else}notactive{/if}"><a href="?show=videos&get=add">Добавить</a></td>
        </tr>
    </table>
{/if}
{if empty($smarty.get.get) or $smarty.get.get == 'videos'}
    <h1>Список видео</h1>
    <center>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="center">
                    <select id="input50" onchange="document.location.href='?show=videos&vg='+this.value">
                        <option value="all"{if $smarty.get.vg == 'all'} selected{/if}>всё видео</option>
                        <option value="no"{if $smarty.get.vg == 'no'} selected{/if}>без галереи</option>
                        {if $gallery_list}
                            {foreach key=key item=item from=$gallery_list name=images}
                                <option value="{$item.id}"{if $smarty.get.vg == $item.id} selected{/if}>{$item.title}</option>
                            {/foreach}
                        {/if}
                    </select>
                    <br><br>
                </td>
            </tr>
            <tr><td align="center">
                    {if $video_list_page}
                        {foreach key=key item=item from=$video_list_page name=images}
                            <div class="image_item">
                                <a href="?show=videos&get=edit&item={$item.v_id}"><img src="../upload/video_thumbs{$item.v_folder}{$item.v_id}-med.jpg?{$smarty.now}" width="166"></a>
                                <input type="Text" value="{$item.v_code}" id="input_item_image" onfocus="this.select();">
                            </div>
                        {/foreach}
                        {if $video_pages|@count >1}
                            <div class="pages"><i>Страницы: </i>
                                {foreach key=key item=item from=$video_pages}
                                    {if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=videos{if $smarty.get.vg != ''}&vg={$smarty.get.vg}{/if}&page={$key}">{$item}</a>{/if}
                                {/foreach}
                            </div>
                        {/if}
                    {else}
                        <b>нет видео</b>
                    {/if}
                </td></tr>
        </table>
        <br>
    </center>
{/if}
<center>
{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
    <h1>Добавление видео</h1>
    <center>
        <form method="post" enctype="multipart/form-data">
            <table width="95%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
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
                                    <select name="v_folder" id="input100">
                                        <option value="/">в корень "/"</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th width="50%" align="center">Страны: </th>
                                <td width="50%" align="center">
                                    <input id="v_country_auto_val" type="hidden" name="v_country_auto_val" value="">
                                    {if $country_list}
                                        <select name="country_" id="v_country_auto" class="country_auto">
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
                                    <input id="v_champ_auto_val" type="hidden" name="v_champ_auto_val" vlue="">
                                    {if $champ_list}
                                        <select name="champ_" id="v_champ_auto" class="champ_auto">
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
                        <input type="submit" name="add_new_video" id="submitsave" value="Добавить">
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
                        <td width="80%"><input type="text" name="v_title_ru" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="v_about_ru" rows="5" style="width: 100%;"></textarea></td></tr>
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
                        <td width="80%"><input type="text" name="v_title_ua" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="v_about_ua" rows="5" style="width: 100%;"></textarea></td></tr>
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
                        <td width="80%"><input type="text" name="v_title_en" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="v_about_en" rows="5" style="width: 100%;"></textarea></td></tr>
                </table>
            </div>
            <div id="cont_4">

            </div>
        </form>
        <br>
    </center>
{/if}
{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}
    <h1>Редактирование видео</h1>
    <center>
        {if !empty($video_item.v_code)}
        <b>Код выделен красным:</b> http://www.youtube.com/watch?v=<b style="color: #f00">ХХХХХХХХХХХХ</b>&feature=popular...<br>
        этот код необходимо скопировать и вписать в поле "код".
        {/if}
        <form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
            <input type="Hidden" name="v_id" value="{$video_item.v_id}">
            <table width="95%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            {if !empty($video_item.v_code)}
                            <tr>
                                <th width="50%" align="center">код (youtube): </th>
                                <td width="50%" align="center"><input type="text" name="v_code" value="{$video_item.v_code}" id="input100"></td>
                            </tr>
                            {/if}
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
                                        <option value="0"{if $video_item.v_gallery_id == 0} selected{/if}>без галереи</option>
                                        {if $gallery_list}
                                            {foreach key=key item=item from=$gallery_list name=images}
                                                <option value="{$item.id}"{if $item.id == $video_item.v_gallery_id} selected{/if}>{$item.title}</option>
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
                        <input type="submit" name="save_edited_video" id="submitsave" value="Сохранить">
                        <br><br>
                        <input type="submit" name="delete_edited_video" id="submitdelete" value="Удалить">
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
                        <td width="80%"><input type="text" name="v_title_ru" value="{$video_item.v_title_ru}" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (РУС):</b> <br><textarea name="v_about_ru" rows="5" style="width: 100%;">{$video_item.v_about_ru}</textarea></td></tr>
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
                        <td width="80%"><input type="text" name="v_title_ua" value="{$video_item.v_title_ua}" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (УКР):</b> <br><textarea name="v_about_ua" rows="5" style="width: 100%;">{$video_item.v_about_ua}</textarea></td></tr>
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
                        <td width="80%"><input type="text" name="v_title_en" value="{$video_item.v_title_en}" id="input100"></td>
                    </tr>
                    <tr><td colspan="2" width="100%" align="center"><b>Описание (ENG):</b> <br><textarea name="v_about_en" rows="5" style="width: 100%;">{$video_item.v_about_en}</textarea></td></tr>
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
                <td width="50%" id="active"><a>Видео</a></td>
                <td width="25%" id="notactive"></td>
            </tr>
        </table>
        <br>

        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="50%" valign="top" align="center">
                    {if $video_item.v_code != ''}
                        <iframe width="350" height="250"
                                src="//www.youtube.com/embed/{$video_item.v_code}"
                                frameborder="0" allowfullscreen></iframe>
                    {else}
                        {$video_item.v_code_text}
                    {/if}
                </td>
                <td width="50%" valign="top">
                    <div id="conteiner" style="padding: 10px; margin: 0 10px 0 0;">
                        <img src="../upload/video_thumbs{$video_item.v_folder}{$video_item.v_id}-small.jpg?{$smarty.now}" border="0" style="max-width: 90px; max-height: 400px; float: left; ">
                        <div style="display: block; margin: 0 0 0 100px;">
                            <b>Код для размещения на страницах:</b>
                            <br><br>
                            <textarea name="mp_value" class="mceNoEditor" style="width: 240px; height: 100px;" onfocus="this.select();"><iframe width="100%" height="400" src="//www.youtube.com/embed/{$video_item.v_code}" frameborder="0" allowfullscreen></iframe></textarea>
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
                        <a href="../upload/video_thumbs{$video_item.v_folder}{$video_item.v_id}.jpg?{$smarty.now}" onclick="return bigImage(this);" target="_blank" title="{$video_item.v_title}"><img src="../upload/video_thumbs{$video_item.v_folder}{$video_item.v_id}-small.jpg?{$smarty.now}" border="0" alt="{$video_item.v_title}" border="0" style="max-width: 200px; max-height: 200px; "></a>
                    </td>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                            <form method="post" enctype="multipart/form-data">
                                <input type="Hidden" name="v_id" value="{$video_item.v_id}">
                                <tr><th colspan="2">Картинка-превью для видео на сайте<br><br></th></tr>
                                <tr><th width="20%" align="center">Файл: </th><td width="80%"><input type="File" name="file_photo_preview" id="input100"></td></tr>
                                <tr>
                                    <td align="center" colspan="2">
                                        <br>
                                        <input type="submit" name="save_edited_preview" id="submitsave" value="Перезаписать">
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
    <b>Количество видео в информере:</b><br><br>
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input" value="{$video_settings_list.count_video_informer}"></td>
                <td width="33%" align="center"><input type="submit" name="save_video_informer_count_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>
    <b>Количество видео на одну страницу в разделе видео:</b><br><br>
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input" value="{$video_settings_list.count_video_page}"></td>
                <td width="33%" align="center"><input type="submit" name="save_video_count_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>
    <b>Количество видео на первой странице в разделе видео:</b><br><br>
    <form method="post">
        <table width="60%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input" value="{$video_settings_list.count_video_page_index}"></td>
                <td width="33%" align="center"><input type="submit" name="save_video_index_count_settings" id="submitsave" value="Сохранить"></td>
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
    <b>Заголовок информера видео:</b><br><br>
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
                <input type="Hidden" name="cnv_name" value="inform_video">
                <tr>
                    <td width="10%">Заголовок: </td>
                    <td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.rus.inform_video}"></td>
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
                <input type="Hidden" name="cnv_name" value="inform_video">
                <tr>
                    <td width="10%">Заголовок: </td>
                    <td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.ukr.inform_video}"></td>
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
                <input type="Hidden" name="cnv_name" value="inform_video">
                <tr>
                    <td width="10%">Заголовок: </td>
                    <td width="65%" align="center"><input type="text" name="cnv_value" id="input100" value="{$conf_vars.eng.inform_video}"></td>
                    <td width="25%" align="center"><input type="submit" name="save_var" id="submitsave" value="Сохранить"></td>
                </tr>
            </form>
        </table>
    </div>
    <br>

{/if}


<br>
</div>