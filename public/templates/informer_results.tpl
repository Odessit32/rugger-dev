{strip}
{if isset($result_informer) && $result_informer}
    <div class="informer inf_results results">
        <script>
            {literal}
            $(document).ready(function(){
                if ($(".title_result").length>0){
                    $(".title_result").on('click', function(){
                        var _that = $(this);
                        if (!_that.hasClass('active')){
                            $(".title_result").removeClass('active');
                            $(".inf_results_cont").slideUp();
                            _that.addClass('active');
                            _that.next(".inf_results_cont").slideDown();
                        }
                    });
                }
            });
            {/literal}
        </script>
        <div class="title title_result active">
            {$language.titmetable|default:'Расписание'}
        </div>
        <div class="inf_results_cont results_block_text first active">
            {if isset($result_informer.soon) && $result_informer.soon}
                <div class="games" id="soon_games">
                    {foreach key=key item=item from=$result_informer.soon name=soon}
                        {if isset($item.an_type)}
                            {if $item.an_type == 'game'}
                                {if isset($item.is_detailed) && !empty($item.is_detailed)}<a href="{$sitepath}game/{$item.g_id|default:''}" title="{if isset($item.owner.title) && isset($item.guest.title)}{$item.owner.title} - {$item.guest.title}{/if}">{/if}
                                    <div class="item">
                                        <div class="date">
                                            {if isset($item.datetime)}{$item.datetime|date_format:"%d.%m.%Y"}{else}Дата неизвестна{/if}
                                        </div>
                                        <div class="game">
                                            {if isset($item.owner.title) && isset($item.guest.title)}{$item.owner.title} - {$item.guest.title}{else}Команды неизвестны{/if}
                                        </div>
                                        <div class="c_title">{if isset($item.title)}{$item.title}{else}Без названия{/if}</div>
                                    </div>
                                {if isset($item.is_detailed) && !empty($item.is_detailed)}</a>{/if}
                            {elseif $item.an_type == 'competition'}
                                {if isset($item.chl_address) && isset($item.chg_address) && isset($item.ch_address) && isset($item.cp_tour) && isset($item.cp_substage) && isset($item.g_cp_id)}
                                    <a href="{$sitepath}competitions/{$item.chl_address}/{$item.chg_address}/{$item.ch_address}/{$item.cp_tour}/{$item.cp_substage}/{$item.g_cp_id}">
                                        <div class="item">
                                            <div class="date">
                                                {if isset($item.datetime)}{$item.datetime|date_format:"%d.%m.%Y"}{else}Дата неизвестна{/if}
                                            </div>
                                            <div class="c_title">{if isset($item.chl_title) && isset($item.chg_title) && isset($item.ch_title) && isset($item.cp_title)}{$item.chl_title}. {$item.chg_title}. {$item.ch_title}. {$item.cp_title}.{/if}</div>
                                        </div>
                                    </a>
                                {/if}
                            {/if}
                        {/if}
                    {/foreach}
                </div>
            {/if}
        </div>
        <div class="title title_result">
            {$language.results|default:'Результаты'}
        </div>
        <div class="inf_results_cont results_block_text">
            <div class="games" id="was_games">
                {if isset($result_informer.game_list) && $result_informer.game_list}
                    {foreach key=key item=item from=$result_informer.game_list name=news}
                        {if isset($item.an_type)}
                            {if $item.an_type == 'game'}
                                {if isset($item.is_detailed) && !empty($item.is_detailed)}<a href="{$sitepath}game/{$item.g_id|default:''}" title="{if isset($item.owner.title) && isset($item.guest.title)}{$item.owner.title} - {$item.guest.title}{/if}">{/if}
                                    <div class="item">
                                        <div class="date">
                                            {if isset($item.datetime)}{$item.datetime|date_format:"%d.%m.%Y"}{else}Дата неизвестна{/if}
                                        </div>
                                        <div class="game">
                                            {if isset($item.owner.title) && isset($item.guest.title) && isset($item.g_owner_points) && isset($item.g_guest_points)}{$item.owner.title} {$item.g_owner_points} : {$item.g_guest_points} {$item.guest.title}{else}Результат неизвестен{/if}
                                        </div>
                                        <div class="c_title">{if isset($item.title)}{$item.title}{else}Без названия{/if}</div>
                                    </div>
                                {if isset($item.is_detailed) && !empty($item.is_detailed)}</a>{/if}
                            {elseif $item.an_type == 'competition'}
                                {if isset($item.chl_address) && isset($item.chg_address) && isset($item.ch_address) && isset($item.cp_tour) && isset($item.cp_substage) && isset($item.g_cp_id)}
                                    <a href="{$sitepath}competitions/{$item.chl_address}/{$item.chg_address}/{$item.ch_address}/{$item.cp_tour}/{$item.cp_substage}/{$item.g_cp_id}">
                                        <div class="item">
                                            <div class="date">
                                                {if isset($item.datetime)}{$item.datetime|date_format:"%d.%m.%Y"}{else}Дата неизвестна{/if}
                                            </div>
                                            <div class="c_title">{if isset($item.chl_title) && isset($item.chg_title) && isset($item.ch_title) && isset($item.cp_title)}{$item.chl_title}. {$item.chg_title}. {$item.ch_title}. {$item.cp_title}.{/if}</div>
                                        </div>
                                    </a>
                                {/if}
                            {/if}
                        {/if}
                    {/foreach}
                {/if}
            </div>
        </div>
        <div class="title title_result">
            {$language.Live|default:'Live'}
        </div>
        <div class="inf_results_cont results_block_text">
            {$language.no_live_content|default:'Нет прямых трансляций'}
        </div>
    </div>
{/if}
{/strip}