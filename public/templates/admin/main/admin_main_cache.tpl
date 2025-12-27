<div id="conteiner" style="margin: 10px;">
    {literal}
        <style type="text/css">
            #title_1 { display: blok; }
            #title_2, #title_3 { display: none; }
        </style>
    {/literal}
    <br>
    <b>Кеширование:</b><br><br>

    <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        {* == Caching time: == *}
        <form method="post">
            <tr>
                <th width="30%">Время кеширования: </th>
                <td width="45%" align="center"><input type="text" name="caching_time_value" id="input100" value="{$cache_vars.caching_time}"></td>
                <td width="25%" align="center"><input type="submit" name="caching_time_save" id="submitsave" value="Сохранить"></td>
            </tr>
        </form>
    </table>
    <br>
    <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        {* == Is caching: == *}
        <form method="post">
            <tr>
                <th width="30%">Вкл/откл кеширование: </th>
                <td width="45%" align="center"><input type="checkbox" name="is_caching_value" id="input100" value="1" {if $cache_vars.is_caching}checked{/if}></td>
                <td width="25%" align="center"><input type="submit" name="is_caching_save" id="submitsave" value="Сохранить"></td>
            </tr>
        </form>
    </table>
    <br>
    <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        {* == Caching type: == *}
        <form method="post">
            <tr>
                <th width="30%">Способ сохранения ключей: </th>
                <td width="45%" align="center">
                    <select name="caching_type_value" class="input_100">
                        <option value="0"{if $cache_vars.caching_type == 0} selected{/if}>база данных</option>
                        <option value="1"{if $cache_vars.caching_type == 1} selected{/if}>memcache</option>
                    </select>
                <td width="25%" align="center"><input type="submit" name="caching_type_save" id="submitsave" value="Сохранить"></td>
            </tr>
        </form>
    </table>
    <br>
    <table width="90%" cellspacing="0" cellpadding="0" border="0" id="form_input">
        {* == Caching clear: == *}
        <form method="post">
            <tr>
                <th width="30%">Очистить кеш: </th>
                <td width="45%" align="center"></td>
                <td width="25%" align="center"><input type="submit" name="caching_clear_function" id="submitsave" value="Очистить"></td>
            </tr>
        </form>
    </table>
    <br>
</div>