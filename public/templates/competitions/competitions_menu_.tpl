<div class="submenu">
    {if $local_list}
    <div class="sub_menu">
        {foreach key=key item=item from=$local_list}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$item.address}" title="{$item.title}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a>{/foreach}
    </div>
    {/if}
    <div class="submenu_c">
        <div id="menu_c_bg" onclick="javascript: closeMenuC();"></div>
        <ul>
            <li>{* == GROUP LIST == *}
                {if $group_list and $group_list|@count > 1}<ul class="menu_c" id="m_1">
                    {foreach key=key item=item from=$group_list}<li{if $item.active == 'yes'} class="active"{/if}>{if $item.active == 'yes'}<b>{$item.title}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$item.address}">{$item.title}</a>{/if}</li>{/foreach}
                    </ul>{/if}
                {if $group_list and $group_list|@count > 1}
                    <a href="javascript:void(0);"{if $group_list and $group_list|@count > 1} onclick="javascript: getMenuC(1);"{/if}>{$group_item.title} <img src="{$imagepath}images/m_c_more.gif" width="9" height="9" border="0" /></a>{else}{*<span>{$group_item.title}</span>*}{/if}
            </li>
            <li>{* == CHAMPIONSHIP LIST == *}
                {if $championship_list and $championship_list|@count > 1}<ul class="menu_c" id="m_2">
                    {foreach key=key item=item from=$championship_list}<li{if $item.active == 'yes'} class="active"{/if}>{if $item.active == 'yes'}<b>{$item.title}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$group_item.address}/{$item.address}">{$item.title}</a>{/if}</li>{/foreach}
                    </ul>{/if}
                {if $championship_list and $championship_list|@count > 1}
                    <a href="javascript:void(0);"{if $championship_list and $championship_list|@count > 1} onclick="javascript: getMenuC(2);"{/if}>{$championship_item.title} <img src="{$imagepath}images/m_c_more.gif" width="9" height="9" border="0" /></a>{else}{*<span>{$championship_item.title}</span>*}{/if}
            </li>
            {if $tour_list}
                <li>{* == TOUR LIST == *}
                    {if $tour_list|@count > 1}<ul class="menu_c" id="m_3">
                        {foreach key=key item=item from=$tour_list}<li{if $item.active == 'yes'} class="active"{/if}>{if $item.active == 'yes'}<b>{$item.title}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$group_item.address}/{$championship_item.address}{if $item.address != ''}/{$item.address}{/if}">{$item.title}</a>{/if}</li>{/foreach}
                        </ul>{/if}
                    {if $tour_list|@count > 1}<a href="javascript:void(0);"{if $tour_list|@count > 1} onclick="javascript: getMenuC(3);"{/if}>{$tour_item.title} <img src="{$imagepath}images/m_c_more.gif" width="9" height="9" border="0" /></a>{else}{*<span>{$tour_item.title}</span>*}{/if}
                </li>
            {/if}
            {if $stage_list and $tour_item.address !== ''}
                <li>{* == STAGE LIST == *}
                    {if $stage_list|@count > 1}<ul class="menu_c" id="m_4">
                        {foreach key=key item=item from=$stage_list}<li{if $item.active == 'yes'} class="active"{/if}>{if $item.active == 'yes'}<b>{$item.title}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$group_item.address}/{$championship_item.address}/{$tour_item.address}{if $item.address != ''}/{$item.address}{/if}">{$item.title}</a>{/if}</li>{/foreach}
                        </ul>{/if}
                    {if $stage_list|@count > 1}<a href="javascript:void(0);"{if $stage_list|@count > 1} onclick="javascript: getMenuC(4);"{/if}>{$stage_item.title} <img src="{$imagepath}images/m_c_more.gif" width="9" height="9" border="0" /></a>{else}{*<span>{$stage_item.title}</span>*}{/if}
                </li>
            {/if}
            {if $competition_list and $stage_list.address !== '' and $tour_item.address !== '' and $current_part_type == 'competition'}
                <li>{* == COMPETITION LIST == *}
                    {if $competition_list|@count > 1}<ul class="menu_c" id="m_5">
                        {foreach key=key item=item from=$competition_list}<li{if $item.active == 'yes'} class="active"{/if}>{if $item.active == 'yes'}<b>{$item.title}</b>{else}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$group_item.address}/{$championship_item.address}/{$tour_item.address}/{$stage_item.address}{if $item.address != ''}/{$item.address}{/if}">{$item.title}</a>{/if}</li>{/foreach}
                        </ul>{/if}
                    {if $competition_list|@count > 1}<a href="javascript:void(0);"{if $competition_list|@count > 1} onclick="javascript: getMenuC(5);"{/if}>{$competition_item.title} <img src="{$imagepath}images/m_c_more.gif" width="9" height="9" border="0" /></a>{else}{*<span>{$competition_item.title}</span>*}{/if}
                </li>
            {/if}
        </ul>
    </div>
</div>