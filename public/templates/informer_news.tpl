{strip}
{if $news_main_list && ($page_item.p_adress != 'news' || $news_item)}
<div class="informer inf_news"><!-- news wo. photo -->
    <div class="title title_news">
        <a href="/news">Новости</a>
    </div>
    <ul class="news_list">
        {foreach key=key item=item from=$news_main_list}
            {if empty($date_title_n) || $date_title_n|date_format:"%Y.%m.%d" != $item.n_date_show|date_format:"%Y.%m.%d"}
                {assign var="date_title_n" value=$item.n_date_show}{assign var="item_gray" value=0}
                <li class="date">{$item.n_date_show|date_format:"%d"}{assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$month.$month_name}</li>
            {/if}
            <li class="item{if $item_gray == 1} gray{/if}">
                <a href="{$sitepath}news/{$item.n_id}">
                    <div class="date">{$item.n_date_show|date_format:"%H:%M"}</div>
                    <div class="n_title">{$item.title}</div>
                </a>
            </li>
            {if $item_gray == 0}{assign var="item_gray" value=1}{else}{assign var="item_gray" value=0}{/if}
        {/foreach}
    </ul>
    <a href="{$sitepath}news" class="all_news_more">{$language.show_more}</a>
</div>
{/if}
{/strip}