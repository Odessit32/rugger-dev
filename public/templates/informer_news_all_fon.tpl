{strip}
{if !empty($all_fon_news_main_list) && $page_item.p_adress == 'news' && !empty($news_item)}
    <div class="informer inf_newsfeed inf_newsfeed_ajax">
        <div class="title title_newsfeed">
            <a href="{$sitepath}news/">{$language.NewsFeed}</a>
        </div>
        <ul class="news_list inf_newsfeed_list">
            {assign var="date_title_n" value=""}
            {foreach key=key item=item from=$all_fon_news_main_list}
                {if $date_title_n|date_format:"%Y.%m.%d" != $item.n_date_show|date_format:"%Y.%m.%d"}
                    {assign var="date_title_n" value=$item.n_date_show}
                    <li class="date">{$item.n_date_show|date_format:"%d"}{assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$month.$month_name}</li>
                {/if}
                <li class="item">
                    <a href="{$sitepath}news/{$item.n_id}">
                        <div class="date">{$item.n_date_show|date_format:"%H:%M"}</div>
                        <div class="n_title">{$item.title}</div>
                    </a>
                </li>
            {/foreach}
        </ul>
        <a href="#show_more" class="all_news_more inf_newsfeed_loadmore"
           data-page="1"
        >{$language.show_more}</a>
    </div>
{/if}
{/strip}