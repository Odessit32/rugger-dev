{if $news_categories}
    <div class="left_menu overflow">
        <ul>
            {foreach key=key item=item from=$news_categories}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}{if $item.nc_address != ''}/{$item.nc_address}{/if}{if $date_show}/date-{$date_show}/{/if}" title="{$item.title}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a></li>
            {/foreach}
            <li class="date_show"><span class="date_soon_picker w_sub_menu{if $date_show} active{/if}"><input type="text" id="date_soon" data-url="{$sitepath}{$page_item.page_path}{$page_item.p_adress}" value="{$date_show_date|date_format:"%d.%m.%Y"}" readonly></span></li>
        </ul>
    </div>
{/if}