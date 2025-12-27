{strip}
{if isset($news_main_one) && $news_main_one}
    <div class="title_caption"><a href="#news">{if isset($section_type) && $section_type}Последнее{else}{$language.Main|default:'Главное'}{/if}</a></div>
    <div class="news_main_content">
        {foreach key=key item=item from=$news_main_one name=nm_list}
            <div class="news_main_content_item {if $smarty.foreach.nm_list.first}active{/if}" data-id="{$item.n_id|default:''}">
                <a href="{if isset($section_address) && $section_address}/{$section_address}{else}{$sitepath}{/if}news/{$item.n_id|default:''}" title="{if isset($item.title_meta)}{$item.title_meta}{/if}">
                    <div class="preview_big_image photo">
                        {if isset($item.photo_main) && isset($item.photo_main.ph_folder) && isset($item.photo_main.ph_path)}
                            <img src="{$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_path}"
                                 class="image"
                                 {if $smarty.foreach.nm_list.first}fetchpriority="high"{else}loading="lazy"{/if}
                                 alt="{if isset($item.title_meta)}{$item.title_meta}{/if}">
                        {/if}
                    </div>
                </a>
            </div>
        {/foreach}
    </div>
    <ul class="news_main_list">
        {foreach key=key item=item from=$news_main_one name=nm_list}
            <li class="list_item {if $smarty.foreach.nm_list.first}active{/if}" data-id="{$item.n_id|default:''}">
                <a href="{if isset($section_address) && $section_address}{$section_address}{else}{$sitepath}{/if}news/{$item.n_id|default:''}">{if isset($item.title)}{$item.title}{else}Без заголовка{/if}</a>
            </li>
        {/foreach}
    </ul>
{/if}
{/strip}