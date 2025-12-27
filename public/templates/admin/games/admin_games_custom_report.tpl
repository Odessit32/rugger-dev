<form method="post" action="?show=games&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false">
    <input type="Hidden" name="g_id" value="{$games_item.g_id}" />
    <table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        <tr>
            <th></th>
            <td width="25%" align="center"><input type="submit" name="save_games_info" id="submitsave" value="Сохранить" /></td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <th>Заголовок для вкладки: </th>
            <td><input type="text" name="g_info[custom_report_title]" value="{if !empty($games_item.g_info->custom_report_title)}{$games_item.g_info->custom_report_title}{/if}" id="input100" /></td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <th>Контент: </th>
            <td></td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <textarea name="g_info[custom_report]">{if !empty($games_item.g_info->custom_report)}{$games_item.g_info->custom_report}{/if}</textarea>
            </td>
        </tr>
    </table>
</form>