{strip}
{if $item.item.i_code != '' || $item.item.i_description != ''}
<div class="informer inf_code"><!-- inf code -->
    <div class="title title_code">
        {$item.item.i_title}
    </div>
    {$item.item.i_description}
    {$item.item.i_code}
</div>
{/if}
{/strip}