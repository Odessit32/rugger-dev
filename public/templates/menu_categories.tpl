{if $categories_list|@count >1}
    <div class="left_menu overflow">
        <ul>
            {foreach key=key item=item from=$categories_list}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}{if isset($item.address) && $item.address != ''}/{$item.address}{/if}" title="{$item.title|default:''}"{if !empty($item.active) && $item.active == 'yes'} class="active"{/if}>{$item.title|default:''}</a></li>
            {/foreach}
        </ul>
    </div>
    {*
{elseif $categories}
    <div class="left_menu overflow">
        <ul>
            {foreach key=key item=item from=$categories}
                <li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}{if $item.address != ''}/{$item.address}{/if}" title="{$item.title}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a></li>
            {/foreach}
        </ul>
    </div>
    *}
{/if}
