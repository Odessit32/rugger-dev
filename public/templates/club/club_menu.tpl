{if $team_list}
<div class="submenu">
    <div class="submenu_nav">
        {foreach key=key item=item from=$team_list}<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$item.address}" title="{$item.title}"{if $item.active == 'yes'} class="active"{/if}>{$item.title}</a>{/foreach}
    </div>
</div>
{/if}