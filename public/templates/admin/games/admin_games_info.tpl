<form method="post" action="?show=games&get=edit&item={$smarty.get.item}&cont=5" onsubmit="if (!confirm('Вы уверены?')) return false">
    <input type="Hidden" name="g_id" value="{$games_item.g_id}" />
    <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        <tr>
            <th></th>
            <td width="70%" align="center"><input type="submit" name="save_games_info" id="submitsave" value="Сохранить" /></td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <th>Город: </th>
            <td><input type="text" name="g_info[town]" value="{if !empty($games_item.g_info->town)}{$games_item.g_info->town}{/if}" id="input100" /></td>
        </tr>
        <tr>
            <th>Стадион: </th>
            <td><input type="text" name="g_info[stadium]" value="{if !empty($games_item.g_info->stadium)}{$games_item.g_info->stadium}{/if}" id="input100" /></td>
        </tr>
        <tr>
            <th>Количество зрителей: </th>
            <td><input type="text" name="g_info[viewers]" value="{if !empty($games_item.g_info->viewers)}{$games_item.g_info->viewers}{/if}" id="input100" /></td>
        </tr>
        <tr>
            <th>Главный судья: </th>
            <td><input type="text" name="g_info[main_judge]" value="{if !empty($games_item.g_info->main_judge)}{$games_item.g_info->main_judge}{/if}" id="input100" /></td>
        </tr>
        <tr>
            <th>Боковые судьи: </th>
            <td><input type="text" name="g_info[side_referee]" value="{if !empty($games_item.g_info->side_referee)}{$games_item.g_info->side_referee}{/if}" id="input100" /></td>
        </tr>
        <tr>
            <th>Видео-рефери: </th>
            <td><input type="text" name="g_info[video_referee]" value="{if !empty($games_item.g_info->video_referee)}{$games_item.g_info->video_referee}{/if}" id="input100" /></td>
        </tr>
    </table>
</form>