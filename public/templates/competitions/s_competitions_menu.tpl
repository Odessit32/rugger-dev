{if $tour_list|@count > 1}
    <div class="sub_menu s_competitions_menu">
        <ul class="sub_menu_list">
            <li class="comp_title_menu"><span>{$group_item.title}</span></li>
            {foreach key=key item=item from=$tour_list}{* == TOUR LIST == *}
                <li{if $item.active == 'yes'} class="active"{/if}>
                    <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$championship_item.address}{if $item.address != ''}/{$item.address}{/if}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}