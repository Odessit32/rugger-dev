<b>Дополнительная статистика:</b><br><br>

<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
    <tr>
        <th>Команда</th>
        <th>Турнир</th>
        <th>Сезон</th>
        <th>Игры</th>
        <th>Очки</th>
        <th>П</th>
        <th>Ш</th>
        <th>Р</th>
        <th>ДГ</th>
        <th>ЖК</th>
        <th>КК</th>
        <th></th>
    </tr>
    <form method="post" action="?show=staff&get=edit&item={$smarty.get.item}&cont=5">
        <input type="hidden" name="st_id" value="{$staff_item.st_id}" />
        <tr>
            <td align="center" width="160px" height="45px">
                {if $team_list}
                    <select name="team_list_id" id="team_list">
                        <option value="new">нет в списке:</option>
                        {foreach key=key item=item from=$team_list name=team}
                            <option value="{$item.t_id}">{if $item.t_title_ru == ''}Без названия{else}{$item.t_title_ru}{/if}</option>
                        {/foreach}
                    </select>
                {/if}

                <input type="text" name="team_new_item" value="" id="team_new_item" style="margin: 10px 0 0 0">
                <script>
                    {literal}
                    jQuery(document).ready(function($){
                        $("#team_list").on("change", function(){
                            if ($(this).val() === 'new') {
                                $("#team_new_item").show();
                            } else {
                                $("#team_new_item").hide();
                            }
                        });
                    });
                    {/literal}
                </script>
            </td>
            <td align="center"><input type="text" name="staging" value="" ></td>
            <td align="center"><input type="text" name="season" value="" ></td>
            <td align="center"><input type="text" name="games" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="points" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="p" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="sh" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="r" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="dg" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="yc" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"><input type="text" name="rc" value="" style="width: 30px; text-align: center;"></td>
            <td align="center"></td>
        </tr>
        <tr>
            <td colspan="12" align="center" height="20px"></td>
        </tr>
        <tr>
            <td colspan="12" align="center"><input type="submit" name="save_staff_custom_statistics" id="submitsave" value="Добавить"></td>
        </tr>
    </form>
    <tr>
        <td colspan="12" align="center" height="20px"></td>
    </tr>
    <tr>
        <td colspan="12" align="center">Добавленная статистика:</td>
    </tr>
    {if $staff_item.st_info.statistic.custom}
        <tr>
            <th>Команда</th>
            <th>Турнир</th>
            <th>Сезон</th>
            <th>Игры</th>
            <th>Очки</th>
            <th>П</th>
            <th>Ш</th>
            <th>Р</th>
            <th>ДГ</th>
            <th>ЖК</th>
            <th>КК</th>
            <th></th>
        </tr>
        {foreach key=key item=item from=$staff_item.st_info.statistic.custom name=stat}
            <form method="post" action="?show=staff&get=edit&item={$smarty.get.item}&cont=5">
                <input type="hidden" name="st_id" value="{$staff_item.st_id}">
                <input type="hidden" name="c_stat_key" value="{$key}">
                <tr>
                    <td align="center" width="160px" height="45px">
                        {if $item.team_id > 0}<a href="?show=team&get=edit&item={$item.team_id}">{$item.team_title}</a>{else}{$item.team_title}{/if}
                    </td>
                    <td align="center">{$item.staging}</td>
                    <td align="center">{$item.season}</td>
                    <td align="center">{$item.games}</td>
                    <td align="center">{$item.points}</td>
                    <td align="center">{$item.p}</td>
                    <td align="center">{$item.sh}</td>
                    <td align="center">{$item.r}</td>
                    <td align="center">{$item.dg}</td>
                    <td align="center">{$item.yc}</td>
                    <td align="center">{$item.rc}</td>
                    <td align="center" class="edit_stat" style="cursor: pointer">[edit]</td>
                </tr>
                <tr style="display: none;">
                    <td align="center" width="160px" height="45px">
                        {if $team_list}
                            <select name="team_list_id" id="team_list_o">
                                <option value="new">нет в списке:</option>
                                {foreach item=item_o from=$team_list name=team}
                                    <option value="{$item_o.t_id}"{if $item.team_id > 0 && $item.team_id==$item_o.t_id} selected{/if}>{if $item_o.t_title_ru == ''}Без названия{else}{$item_o.t_title_ru}{/if}</option>
                                {/foreach}
                            </select>
                        {/if}

                        <input type="text" name="team_new_item_e" value="{if $item.team_id == 0}{$item.team_title}{/if}" id="team_new_item" style="margin: 10px 0 0 0">
                        <script>
                            {literal}
                            jQuery(document).ready(function($){
                                $("#team_list_o").on("change", function(){
                                    if ($(this).val() === 'new') {
                                        $("#team_new_item_e").show();
                                    } else {
                                        $("#team_new_item_e").hide();
                                    }
                                });
                            });
                            {/literal}
                        </script>
                    </td>
                    <td align="center"><input type="text" name="staging" value="{$item.staging}" ></td>
                    <td align="center"><input type="text" name="season" value="{$item.season}" ></td>
                    <td align="center"><input type="text" name="games" value="{$item.games}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="points" value="{$item.points}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="p" value="{$item.p}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="sh" value="{$item.sh}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="r" value="{$item.r}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="dg" value="{$item.dg}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="yc" value="{$item.yc}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="text" name="rc" value="{$item.rc}" style="width: 30px; text-align: center;"></td>
                    <td align="center"><input type="submit" name="update_staff_custom_statistics" value="save"></td>
                </tr>
            </form>
        {/foreach}
    {/if}
    <tr>
        <td colspan="12" align="center" height="20px"></td>
    </tr>
    <tr>
        <td colspan="12" align="center">Расчитанная статистика:</td>
    </tr>
</table>
<script>
    {literal}
    jQuery(document).ready(function($){
        $(".edit_stat").on("click", function(){
            $(this).parent('tr').hide();
            $(this).parent('tr').next().show();
        });
    });
    {/literal}
</script>
