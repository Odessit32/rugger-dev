{strip}
{if $article_news_main_list}
    <div class="informer inf_newsarticle inf_newsarticle_ajax">
        <div class="title title_newsarticle"
             data-page="0"
             {if $section}data-section="{$section.info.pe_item_id}"{/if}
             {if $section}data-sectiontype="{$section.info.pe_item_type}"{/if}>
            <a href="{$sitepath}{if $section}{$section.page.p_adress}/{/if}news/articles">{$language.NewsArticle}</a>
            <a href="#next" class="inf_news_next" aria-label="Следующая новость" title="Следующая"></a>
            <a href="#prev" class="inf_news_prev" aria-label="Предыдущая новость" title="Предыдущая"></a>
        </div>
        <ul class="article_news_list_n inf_newsarticle_list">
            {foreach key=key item=item from=$article_news_main_list}
                <li class="item">
                    <a href="{$sitepath}{if $section}{$section.page.p_adress}/{/if}news/{$item.n_id}"
                       {if $item.photo_main}style="background-image: url({$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_med});"{/if}>
                        <div class="n_title">
                            <div class="date">
                                {$item.n_date_show|date_format:"%d"} {assign var="month_name" value=$item.n_date_show|date_format:"%m"} {$month.$month_name} {$item.n_date_show|date_format:"%Y"} г.
                            </div>
                            {$item.title}
                        </div>
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}
{/strip}