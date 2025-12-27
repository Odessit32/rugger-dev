{strip}
<div class="title_caption title_caption_news"><a href="{$sitepath}{if !empty($page_item)}{$page_item.page_path}{$page_item.p_adress}{/if}news">{$language.News}</a></div>
<div class="news_list">
    {if $news_main_list}
    {foreach key=key item=item from=$news_main_list}
     <a href="{if $section_address}{$section_address}{else}{$sitepath}{/if}news/{$item.n_id}" title="{$language.News}: {$item.title}" class="item">
        {if $item.photo_main && !empty($item.photo_main.ph_folder) && !empty($item.photo_main.ph_med)}
        <div class="photo" style="background-image: url({$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_med});"></div>
        {/if}
        <div class="texts">
            {if $item.phg_id>0 and $item.phg_is_active=='yes'}
                <div class="icons">
                    <div class="photo_ico"></div>
                </div>
            {/if}
            {if $item.vg_id>0 and $item.vg_is_active=='yes'}
                <div class="icons">
                    <div class="video_ico"></div>
                </div>
            {/if}
            <div class="date">{$item.n_date_show|date_format:"%d"} {assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$month.$month_name}</div>
            <div class="time">{$item.n_date_show|date_format:"%H:%M"}</div>
            <div class="title">{$item.title}</div>
            <div class="description">{$item.description|strip}</div>
        </div>
    </a>
    {/foreach}
    {/if}
    <a href="{$sitepath}news" class="more_news show_more_index_news"
       data-offset="{$news_main_list|@count}"
       data-page="0"
       data-section_id="{if $section_type_id}{$section_type_id}{else}0{/if}"
       data-section_type="{$section_type}"
       data-section_address="{$section_address}"><span>{$language.show_more}</span><div class="bounce bounce1"></div><div class="bounce bounce2"></div><div class="bounce bounce3"></div></a>
</div>
{/strip}