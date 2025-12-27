<H1>Голосования:</H1>

<div id="conteiner">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=votes">Список</a></td>
        <td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}active{else}notactive{/if}">{if !empty($smarty.get.get) && $smarty.get.get == 'edit'}<a href="?show=votes&get=edit&item={$smarty.get.item}">Редактировать</a>{else}Редактировать{/if}</td>
        <td width="25%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'add'}active{else}notactive{/if}"><a href="?show=votes&get=add">Добавить</a></td>
        <td width="25%" id="{if $smarty.get.get == 'settings'}active_right{else}notactive{/if}"><a href="?show=votes&get=settings">Настройки</a></td>
    </tr>
</table>

{if empty($smarty.get.get)}
    <h1>Список голосований</h1>

    <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        <tr>
            <td align="center" colspan="6">
                <select id="input" style="width: 200px;" onchange="document.location.href='?show=votes&nnc='+this.value">
                    <option value="all"{if $smarty.get.nnc == 'all'} selected{/if}>все</option>
                </select>
                <br><br>
            </td>
        </tr>
        {if $votes_list}
            {foreach key=key item=item from=$votes_list name=votes}
                <tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''" >
                    <td><a href="?show=votes&get=edit&item={$item.id}">{if $item.vt_question == ''}<font style="color: #f00;">Без вопроса</font>{else}{$item.vt_question}{/if}</a></td>
                    <td>{if $item.vt_always == 'yes'}всегда{else}<nobr>{$item.vt_date_from|date_format:"%d.%m.%Y %H:%M"}</nobr> - <nobr>{$item.vt_date_to|date_format:"%d.%m.%Y %H:%M"}</nobr>&nbsp;&nbsp;{/if}</td>
                    <td>{$item.vt_a_count}</td>
                    <td width="16">{if $item.vt_is_active == 'no'}<img src="images/off.gif" alt="откл" border="0">{else}<img src="images/on.gif" alt="вкл" border="0">{/if}</td>
                </tr>
            {/foreach}
        {/if}
    </table>
    {if $votes_pages|@count >1}
        <div class="pages"><i>Страницы: </i>
            {foreach key=key item=item from=$votes_pages}
                {if $smarty.get.page == $key}<b>{$item}</b>{else}<a href="?show=votes&page={$key}">{$item}</a>{/if}
            {/foreach}
        </div>
    {/if}

    <br>
{/if}
{if !empty($smarty.get.get) && $smarty.get.get == 'add'}
    <h1>Добавить голосование</h1>
    <form method="post" name="add_voting">
        <input type="hidden" name="count_answer" value="1">
        <table width="95%" cellspacing="0" cellpadding="0" border="0" class="center">
            <tr>
                <td width="50%">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="50%" align="center">вкл/откл: </th>
                            <td width="50%" align="center"><input type="Checkbox" name="vt_is_active" checked></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">проводить всё время: </th>
                            <td width="50%" align="center"><input type="Checkbox" name="vt_always" checked></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Считать голоса: </th>
                            <td width="50%" align="center">
                                <select name="vt_voters_type" id="input">
                                    <option value="no_ban">без ограничений</option>
                                    <option value="day_ip">1 день с 1 ip</option>
                                    <option value="ip">1 раз с 1 ip</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">проводить с: </th>
                            <td width="50%" align="center"><nobr>
                                    <select name="vt_date_from_day" id="input">
                                        {section name = day start = 1 loop = 32}
                                            <option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_from_month" id="input">
                                        {section name = month start = 1 loop = 13}
                                            <option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_from_year" id="input">
                                        {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                        {assign var="now_year" value=$now_year+2}
                                        {section name = year start = 2009 loop = $now_year}
                                            <option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                        {/section}
                                    </select>

                                    <select name="vt_date_from_hour" id="input">
                                        {section name = hour start = 0 loop = 24}
                                            <option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_from_minute" id="input">
                                        {section name = minute start = 0 loop = 60}
                                            <option value="{$smarty.section.minute.index}"{if $smarty.now|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                        {/section}
                                    </select></nobr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">проводить до: </th>
                            <td width="50%" align="center"><nobr>
                                    <select name="vt_date_to_day" id="input">
                                        {section name = day start = 1 loop = 32}
                                            <option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_to_month" id="input">
                                        {section name = month start = 1 loop = 13}
                                            <option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_to_year" id="input">
                                        {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                        {assign var="now_year" value=$now_year+2}
                                        {section name = year start = 2009 loop = $now_year}
                                            <option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                        {/section}
                                    </select>

                                    <select name="vt_date_to_hour" id="input">
                                        {section name = hour start = 0 loop = 24}
                                            <option value="{$smarty.section.hour.index}"{if $smarty.now|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_to_minute" id="input">
                                        {section name = minute start = 0 loop = 60}
                                            <option value="{$smarty.section.minute.index}"{if $smarty.now|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                        {/section}
                                    </select></nobr>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" align="center" valign="middle">
                    <input type="submit" name="add_new_votes" id="submitsave" value="Добавить">
                </td>
            </tr>
        </table>
        <br>
        <h2>Вопросы и ответы</h2>
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
                    <th width="20%" align="center">Вопрос: </th>
                    <td width="80%"><input type="text" name="vt_question_ru" class="input100"></td>
                </tr>
                <tr class="answer_item">
                    <th width="20%" align="center">Ответ 1: </th>
                    <td width="80%"><input type="text" name="vta_answer_ru[1]" class="input100"></td>
                </tr>
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
                    <th width="20%" align="center">Вопрос: </th>
                    <td width="80%"><input type="text" name="vt_question_ua" class="input100"></td>
                </tr>
                <tr class="answer_item">
                    <th width="20%" align="center">Ответ 1: </th>
                    <td width="80%"><input type="text" name="vta_answer_ua[1]" class="input100"></td>
                </tr>
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
                    <th width="20%" align="center">Вопрос: </th>
                    <td width="80%"><input type="text" name="vt_question_en" class="input100"></td>
                </tr>
                <tr class="answer_item">
                    <th width="20%" align="center">Ответ 1: </th>
                    <td width="80%"><input type="text" name="vta_answer_en[1]" class="input100"></td>
                </tr>
            </table>
        </div>
        <div class="voting_answer_add_more" data-count-items="1">
            <strong>Добавить еще один ответ</strong>
        </div>

    </form>
    {literal}
        <script>
            jQuery(document).ready(function($){
                $(".voting_answer_add_more").on("click", function(){
                    var _that = $(this);
                    var countItems = _that.data("count-items");
                    countItems++;
                    _that.data("count-items", countItems);
                    $("form[name=add_voting] input[name=count_answer]").val(countItems)
                    $("#cont_1 .answer_item:last").after("<tr class=\"answer_item\"><th align=\"center\">Ответ "+countItems+":</td><td><input type=\"text\" name=\"vta_answer_ru["+countItems+"]\" class=\"input100\"></td></tr>")
                    $("#cont_2 .answer_item:last").after("<tr class=\"answer_item\"><th align=\"center\">Ответ "+countItems+":</td><td><input type=\"text\" name=\"vta_answer_ua["+countItems+"]\" class=\"input100\"></td></tr>")
                    $("#cont_3 .answer_item:last").after("<tr class=\"answer_item\"><th align=\"center\">Ответ "+countItems+":</td><td><input type=\"text\" name=\"vta_answer_en["+countItems+"]\" class=\"input100\"></td></tr>")
                });
            });
        </script>
    {/literal}
    <br>
{/if}
{if $smarty.get.get == 'edit' and $smarty.get.item>0}
    <h1>Редактирование голосования: &laquo;{$votes_item.vt_question_ru}&raquo;</h1>
    <form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
        <input type="hidden" name="vt_id" value="{$votes_item.vt_id}">
        <input type="hidden" name="count_answer" value="{$votes_item.answer_list|@count}">
        <table width="95%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="50%">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
                        <tr>
                            <th width="50%" align="center">вкл/откл: </th>
                            <td width="50%" align="center"><input type="Checkbox" name="vt_is_active" {if $votes_item.vt_is_active == 'yes'} checked{/if} /></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">проводить всё время: </th>
                            <td width="50%" align="center"><input type="Checkbox" name="vt_always" {if $votes_item.vt_always == 'yes'} checked{/if} /></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">Считать голоса: </th>
                            <td width="50%" align="center">
                                <select name="vt_voters_type" id="input">
                                    <option value="no_ban" {if $votes_item.vt_voters_type == 'no_ban'} selected{/if}>без ограничений</option>
                                    <option value="day_ip" {if $votes_item.vt_voters_type == 'day_ip'} selected{/if}>1 день с 1 ip</option>
                                    <option value="ip" {if $votes_item.vt_voters_type == 'ip'} selected{/if}>1 раз с 1 ip</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">проводить с: </th>
                            <td width="50%" align="center"><nobr>
                                    <select name="vt_date_from_day" id="input">
                                        {section name = day start = 1 loop = 32}
                                            <option value="{$smarty.section.day.index}"{if $votes_item.vt_date_from|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_from_month" id="input">
                                        {section name = month start = 1 loop = 13}
                                            <option value="{$smarty.section.month.index}"{if $votes_item.vt_date_from|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_from_year" id="input">
                                        {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                        {assign var="now_year" value=$now_year+2}
                                        {section name = year start = 2009 loop = $now_year}
                                            <option value="{$smarty.section.year.index}"{if $votes_item.vt_date_from|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                        {/section}
                                    </select>


                                    <select name="vt_date_from_hour" id="input">
                                        {section name = hour start = 0 loop = 24}
                                            <option value="{$smarty.section.hour.index}"{if $votes_item.vt_date_from|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_from_minute" id="input">
                                        {section name = minute start = 0 loop = 60}
                                            <option value="{$smarty.section.minute.index}"{if $votes_item.vt_date_from|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                        {/section}
                                    </select></nobr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <th width="50%" align="center">проводить до: </th>
                            <td width="50%" align="center"><nobr>
                                    <select name="vt_date_to_day" id="input">
                                        {section name = day start = 1 loop = 32}
                                            <option value="{$smarty.section.day.index}"{if $votes_item.vt_date_to|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_to_month" id="input">
                                        {section name = month start = 1 loop = 13}
                                            <option value="{$smarty.section.month.index}"{if $votes_item.vt_date_to|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_to_year" id="input">
                                        {assign var="now_year" value=$smarty.now|date_format:"%Y"}
                                        {assign var="now_year" value=$now_year+2}
                                        {section name = year start = 2009 loop = $now_year}
                                            <option value="{$smarty.section.year.index}"{if $votes_item.vt_date_to|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
                                        {/section}
                                    </select>


                                    <select name="vt_date_to_hour" id="input">
                                        {section name = hour start = 0 loop = 24}
                                            <option value="{$smarty.section.hour.index}"{if $votes_item.vt_date_to|date_format:"%H" == $smarty.section.hour.index} selected{/if}>{$smarty.section.hour.index}</option>
                                        {/section}
                                    </select>
                                    <select name="vt_date_to_minute" id="input">
                                        {section name = minute start = 0 loop = 60}
                                            <option value="{$smarty.section.minute.index}"{if $votes_item.vt_date_to|date_format:"%M" == $smarty.section.minute.index} selected{/if}>{if $smarty.section.minute.index<10}0{/if}{$smarty.section.minute.index}</option>
                                        {/section}
                                    </select></nobr>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" align="center" valign="middle">
                    <input type="submit" name="save_votes_changes" id="submitsave" value="Сохранить">
                    <br><br><br>
                    <input type="submit" name="delete_votes" id="submitdelete" value="Удалить">
                </td>
            </tr>
        </table>
        <br>
        <h2>Вопросы и ответы</h2>
        <span class="vta_ids">
            {foreach key=key item=item from=$votes_item.answer_list}
                <input type="hidden" name="vta_id[{$key}]" value="{$item.vta_id}">
            {/foreach}
        </span>
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
                    <th width="20%" align="center">Вопрос: </th>
                    <td width="80%"><input type="text" name="vt_question_ru" value="{$votes_item.vt_question_ru}" class="input100"></td>
                </tr>
                {foreach key=key item=item from=$votes_item.answer_list}
                    <tr class="answer_item">
                        <th width="20%" align="center">Ответ {$key+1} ({$item.vta_a_count}): </th>
                        <td width="80%"><input type="text" name="vta_answer_ru[{$key}]" value="{$item.vta_answer_ru}" class="input100"></td>
                    </tr>
                {/foreach}
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
                    <th width="20%" align="center">Вопрос: </th>
                    <td width="80%"><input type="text" name="vt_question_ua" value="{$votes_item.vt_question_ua}" class="input100"></td>
                </tr>
                {foreach key=key item=item from=$votes_item.answer_list}
                    <tr class="answer_item">
                        <th width="20%" align="center">Ответ {$key+1} ({$item.vta_a_count}): </th>
                        <td width="80%"><input type="text" name="vta_answer_ua[{$key}]" value="{$item.vta_answer_ua}" class="input100"></td>
                    </tr>
                {/foreach}
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
                    <th width="20%" align="center">Вопрос: </th>
                    <td width="80%"><input type="text" name="vt_question_en" value="{$votes_item.vt_question_en}" class="input100"></td>
                </tr>
                {foreach key=key item=item from=$votes_item.answer_list}
                    <tr class="answer_item">
                        <th width="20%" align="center">Ответ {$key+1} ({$item.vta_a_count}): </th>
                        <td width="80%"><input type="text" name="vta_answer_en[{$key}]" value="{$item.vta_answer_en}" class="input100"></td>
                    </tr>
                {/foreach}
            </table>
        </div>
        <div class="voting_answer_add_more" data-count-items="1">
            <strong>Добавить еще один ответ</strong>
        </div>
    </form>
    {literal}
        <script>
            jQuery(document).ready(function($){
                $(".voting_answer_add_more").on("click", function(){
                    var _that = $(this);
                    var countItems = _that.data("count-items");
                    countItems++;
                    _that.data("count-items", countItems);
                    $("form[name=edit_voting] input[name=count_answer]").val(countItems)
                    $("#cont_1 .answer_item:last").after("<tr class=\"answer_item\"><th align=\"center\">Ответ "+countItems+":</td><td><input type=\"text\" name=\"vta_answer_ru["+countItems+"]\" class=\"input100\"></td></tr>")
                    $("#cont_2 .answer_item:last").after("<tr class=\"answer_item\"><th align=\"center\">Ответ "+countItems+":</td><td><input type=\"text\" name=\"vta_answer_ua["+countItems+"]\" class=\"input100\"></td></tr>")
                    $("#cont_3 .answer_item:last").after("<tr class=\"answer_item\"><th align=\"center\">Ответ "+countItems+":</td><td><input type=\"text\" name=\"vta_answer_en["+countItems+"]\" class=\"input100\"></td></tr>")
                });
            });
        </script>
    {/literal}
    <br>
{/if}
{if $smarty.get.get == 'settings'}
    <h1>Настройки</h1>

    <b>Количество голосований на одну страницу в разделе голосований:</b><br><br>
    <form method="post">
        <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
            <tr>
                <td width="33%">число:</td>
                <td width="34%" align="center"><input type="text" name="cnv_value" id="input100" value="{$votes_settings_list.count_votes_page}"></td>
                <td width="33%" align="center"><input type="submit" name="save_votes_count_settings" id="submitsave" value="Сохранить"></td>
            </tr>
        </table>
    </form>
    <br>
    {include file="votes/admin_title_votes_informer.tpl"}
    <br>
{/if}
</div>