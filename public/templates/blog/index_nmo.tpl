{if !empty($post_main_one)}
<div class="title_caption"><a href="#blog">{$language.Main}</a></div>
<div class="news_main_content">
    {foreach key=key item=item from=$post_main_one name=nm_list}
        <div class="news_main_content_item {if $smarty.foreach.nm_list.first}active{/if}" data-id="{$item.n_id}">
            <a href="{if $section_address}/{$section_address}{else}{$sitepath}{/if}blog/{$item.address}">
                <div class="photo" style="background-image: url('{$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_path}');"></div>
                {*<div class="title">{$item.title}</div>
                <div class="description">
                    {$item.description}
                </div>*}
            </a>
        </div>
    {/foreach}
</div>
<ul class="news_main_list">
    {foreach key=key item=item from=$post_main_one name=nm_list}
    <li class="list_item {if $smarty.foreach.nm_list.first}active{/if}" data-id="{$item.n_id}">
        <a href="{if $section_address}{$section_address}{else}{$sitepath}{/if}blog/{$item.address}">{$item.title}</a>
    </li>
    {/foreach}
</ul>
{/if}