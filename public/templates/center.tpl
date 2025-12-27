{if !empty($pbi.center)}
<div class="pbi">
{foreach key=key item=item from=$pbi.center name=b_center}
	{if $item.pbi_item_type == 'banner'}
		{if $item.item.b_type == 'image'}
			{if $item.item.b_noindex == 'yes'}<noindex>{/if}
			<a href="{$item.item.url}" title="{$item.item.title}"{if $item.item.target == 'yes'} target="_blank"{/if}><img src="{$imagepath}upload/banners{$item.item.path}" style="max-width: 311px;" border="0" loading="lazy" alt="{$item.item.title}"></a>
			{if $item.item.b_noindex == 'yes'}</noindex>{/if}
			{if !$smarty.foreach.b_center.last}<div id="delim">&nbsp;</div>{/if}
		{/if}
		{if $item.item.b_type == 'code'}
			{$item.item.b_code}
			{if !$smarty.foreach.b_center.last}<div id="delim">&nbsp;</div>{/if}
		{/if}
	{elseif $item.pbi_item_type == 'informer'}
		{if !empty($item.item.i_noindex) && $item.item.i_noindex == 'yes'}<noindex>{/if}
		{include file=$item.item.i_path}
		{if !empty($item.item.i_noindex) && $item.item.i_noindex == 'yes'}</noindex>{/if}
	{/if}
{/foreach}
</div>
{/if}