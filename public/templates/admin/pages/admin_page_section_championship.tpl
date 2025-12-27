Выбор чемпионата для раздела:
<br /><br />
<span id="c_banner">
	<form method="post" name="admin_page_section_championship" action="?show=pages&get=edit&item={$smarty.get.item}&cont=4" enctype="multipart/form-data">
		<input type="Hidden" name="p_id" value="{$page_item.p_id}">
		<table width="630">
            <tr>
                <th width="50%" colspan="3">Чемпионат</th>
                <td width="50%" colspan="3">
                    <select name="pe_championship_id" id="input" style="width: 100%;">
                        <option value=""{if $page_extra_item.championship.pe_item_id == ''} selected{/if}>-----</option>
                        {if $champ_list}
                            {foreach key=key item=item from=$champ_list name=ctr}
                                <option value="{$item.id}"{if $page_extra_item.championship.pe_item_id == $item.id} selected{/if}>
                                    {if $item.title == ''}названия{else}{$item.title}{/if}
                                </option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr><td colspan="6" height="20"></td></tr>
            <tr>
                <th width="50%" colspan="3">Дополнительная отметка типа чемтионата для меню: </th>
                <td width="50%" colspan="3">
                    <select name="pe_championship_type" class="input" style="width: 100%;">
                        <option value=""{if empty($page_extra_item.championship_type.pe_item_id)} selected{/if}>-----</option>
                        <option value="international"{if !empty($page_extra_item.championship_type.pe_item_id) && $page_extra_item.championship_type.pe_item_id == 'international'} selected{/if}>
                            Межуднародные
                        </option>
                        <option value="club"{if !empty($page_extra_item.championship_type.pe_item_id) && $page_extra_item.championship_type.pe_item_id == 'club'} selected{/if}>
                            Клубные
                        </option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="6" height="20"></td></tr>
            <tr>
                <th width="33%" colspan="2">
                    Флаг:
                </th>
                <td width="34%" colspan="2" align="center">
                    {if $page_extra_item.championship_logo}
                        <img src="../{$page_extra_item.championship_logo.pe_item_id}" style="max-width: 200px; max-height: 200px;" />
                    {/if}
                </td>
                <td width="330%" colspan="2">
                    <input type="file" name="championship_flag" />
                </td>
            </tr>
            <tr>
                <td colspan="6" align="center"><br>
                    <input type="submit" name="save_page_championship" id="submitsave" value="Сохранить">
                </td>
            </tr>
		</table>
	</form>
</span>
<br>