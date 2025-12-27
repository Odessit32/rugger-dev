{if $result_informer}
    <div class="informer inf_table"><!-- news wo. photo -->
        <div class="title title_tables">
            {$language.titmetable}
        </div>

        {* === СКОРО === *}
        {literal}
        <script>
            $(document).ready(function(){
                // MM/DD/YYYY
                var dates = [{/literal}{if $result_informer.soon_date_list}{$result_informer.soon_date_list}{/if}{literal}];
                $('#date_soon').datepicker({
                    dateFormat: 'dd.mm.yy',
                    beforeShowDay: function(date) {
                        for (var i = 0; i < dates.length; i++) {
                            if (new Date(dates[i]).toString() == date.toString()) {
                                return [true, 'highlight', ''];
                            }
                        }
                        return [true, ''];
                    },
                    showOtherMonths: true,
                    minDate: "dateToday",
                    firstDay: 1
                });
                $.datepicker.setDefaults($.datepicker.regional['ru']);
            });
        </script>
        {/literal}
        <div class="results_block_text first">
            {if $result_informer.soon}
                <input type="text" class="date_soon_center" id="date_soon" value="{$result_informer.soon_date_now|date_format:"%d.%m.%Y"}">
                <div class="games" id="soon_games">
                    {foreach key=key item=item from=$result_informer.soon name=soon}
                        {if $item.an_type=='game'}
                            <a href="{$sitepath}game/{$item.g_id}" title="{$item.owner.title} - {$item.guest.title}">
                                <div class="item">
                                    <div class="date">
                                        {$item.datetime|date_format:"%d"}.{$item.datetime|date_format:"%m"}.{$item.datetime|date_format:"%Y"}{if $item.g_is_schedule_time == 'yes'} в {$item.datetime|date_format:"%H:%M"}{/if}
                                    </div>
                                    <div class="game">
                                        {$item.owner.title} - {$item.guest.title}
                                    </div>
                                    <div class="c_title">{$item.title}</div>
                                </div>
                            </a>
                        {/if}
                        {if $item.an_type=='competition'}
                            <a href="{$sitepath}competitions/{$item.chl_address}/{$item.chg_address}/{$item.ch_address}/{$item.cp_tour}/{$item.cp_substage}/{$item.g_cp_id}">
                                <div class="item">
                                    <div class="date">
                                        {$item.datetime|date_format:"%d"}.{$item.datetime|date_format:"%m"}.{$item.datetime|date_format:"%Y"}{if $item.g_is_schedule_time == 'yes'} в {$item.datetime|date_format:"%H:%M"}{/if}<br/>
                                    </div>
                                    <div class="c_title">{$item.chl_title}. {$item.chg_title}. {$item.ch_title}. {$item.cp_title}.</div>
                                </div>
                            </a>
                        {/if}
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>
{/if}