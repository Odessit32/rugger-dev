{strip}
{if isset($s_fon_news_main_list) && !empty($s_fon_news_main_list) && isset($page_item.p_adress) && $page_item.p_adress == 'news' && isset($news_item) && !empty($news_item) && isset($section) && !empty($section)}
    <div class="informer inf_newsfeed inf_newsfeed_ajax">
        <div class="title title_newsfeed_s">
            <a href="{$sitepath}{$section.page.p_adress|default:''}/news/">{if isset($section.page.title)}{$section.page.title}{/if}. {$language.News|default:'Новости'}</a>
        </div>
        <ul class="news_list inf_newsfeed_list">
            {foreach key=key item=item from=$s_fon_news_main_list}
                {if !isset($date_title_n) || $date_title_n|date_format:"%Y.%m.%d" != $item.n_date_show|date_format:"%Y.%m.%d"}
                    {assign var="date_title_n" value=$item.n_date_show}
                    <li class="date">{if isset($item.n_date_show)}{$item.n_date_show|date_format:"%d"}{assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$month.$month_name|default:''}{else}Дата неизвестна{/if}</li>
                {/if}
                <li class="item">
                    <a href="{$sitepath}{$section.page.p_adress|default:''}/news/{$item.n_id|default:''}">
                        <div class="date">{if isset($item.n_date_show)}{$item.n_date_show|date_format:"%H:%M"}{else}Время неизвестно{/if}</div>
                        <div class="n_title">{if isset($item.title)}{$item.title}{else}Без заголовка{/if}</div>
                    </a>
                </li>
            {/foreach}
        </ul>
        <a href="#show_more" class="all_news_more inf_newsfeed_loadmore"
           data-page="1"
           data-section="{if isset($section.info.pe_item_id)}{$section.info.pe_item_id}{/if}"
           data-sectiontype="{if isset($section.info.pe_item_type)}{$section.info.pe_item_type}{/if}"
        >{$language.show_more|default:'Показать еще'}</a>
    </div>
{/if}
{/strip}