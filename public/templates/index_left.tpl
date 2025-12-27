{strip}
{if $mbi.left}
	{foreach key=key item=item from=$mbi.left name=b_left}
		{if $item.mbi_item_type == 'banner'}
			{if $item.item.b_type == 'image'}
				{if $item.item.b_noindex == 'yes'}<noindex>{/if}
				<a href="{$item.item.url}" title="{$item.item.title}"{if $item.item.target == 'yes'} target="_blank"{/if}><img src="{$imagepath}upload/banners{$item.item.path}" style="max-width: 230px;" border="0" alt="{$item.item.title}" loading="lazy"></a>
				{if $item.item.b_noindex == 'yes'}</noindex>{/if}
				{if !$smarty.foreach.b_left.last}<div id="delim">&nbsp;</div>{/if}
			{/if}
			{if $item.item.b_type == 'code'}
				{$item.item.b_code}
				{if !$smarty.foreach.b_left.last}<div id="delim">&nbsp;</div>{/if}
			{/if}
		{elseif $item.mbi_item_type == 'informer'}
			{if !empty($item.item.i_noindex) && $item.item.i_noindex == 'yes'}<noindex>{/if}
			{include file=$item.item.i_path}
			{if !empty($item.item.i_noindex) && $item.item.i_noindex == 'yes'}</noindex>{/if}
			{*if !$smarty.foreach.b_left.last}<div id="delim">&nbsp;</div>{/if*}
		{/if}
	{/foreach}
{/if}
{/strip}