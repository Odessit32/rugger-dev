{strip}
{if $result_table_informer.ch_list}
    <div class="informer inf_table"><!-- news wo. photo -->
        <div class="title title_tables">
            {$language.tables}
        </div>
    {* === ТАБЛИЦЫ === *}
        <div class="results_block_text">
            {if $result_table_informer.ch_list}
            <div class="tab_nav">
                <div class="item_selected{if $result_table_informer.ch_list|@count > 1} more_select{/if}">{$result_table_informer.ch_list[0].title}</div>
                {if $result_table_informer.ch_list|@count > 1}
                <ul class="ul_sub_menu">
                {foreach key=key item=item from=$result_table_informer.ch_list name=nc}
                    <li data-ch_id="{$item.ch_id}"{if $item.active} class="active"{/if}>{$item.title}</li>
                {/foreach}
                </ul>
                {/if}
            </div>
            {/if}
            <div class="table_data comp_item">
                {if $result_table_informer.tables}
                    <div class="comp_title">
                        <div class="item_selected{if $result_table_informer.ch_other_list|@count > 1} more_select{/if}">{$result_table_informer.tables_title}</div>
                        {if $result_table_informer.ch_other_list|@count > 1}
                            <ul class="ul_other_menu">
                                {foreach key=key item=item from=$result_table_informer.ch_other_list name=nc}
                                    <li data-ch_id="{$item.ch_id}"{if $item.active} class="active"{/if}>{$item.title}</li>
                                {/foreach}
                            </ul>
                        {/if}
                    </div>
                    {if $result_table_informer.tables_type == 1}
                        {foreach key=key_t item=item_t from=$result_table_informer.tables name=table}
                            {*<div class="table_title">{$item_t.title}</div>*}
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <th>№</th>
                                    <th>{$language.Team}</th>
                                    <th>{$language.G}</th>
                                    <th>{$language.W}</th>
                                    <th>{$language.D}</th>
                                    <th>{$language.L}</th>
                                    <th>{$language.RIO}</th>
                                    <th>{$language.P}</th>
                                </tr>
                                <tr>
                                    <td class="p_pre" colspan="8">

                                    </td>
                                </tr>
                                {foreach key=key item=item from=$item_t.data name=team}
                                    <tr>
                                        <td align="center">{$smarty.foreach.team.iteration}</td>
                                        <td align="center">{if $item.t_is_detailed == 'yes'}<a href="{$sitepath}team/{$item.t_id}"><b>{$item.title}</b></a>{else}<b>{$item.title}</b>{/if}</td>
                                        <td align="center">{if $item.games>0}{$item.games}{else}-{/if}</td>
                                        <td align="center">{if $item.win>0}{$item.win}{else}-{/if}</td>
                                        <td align="center">{if $item.draw>0}{$item.draw}{else}-{/if}</td>
                                        <td align="center">{if $item.loss>0}{$item.loss}{else}-{/if}</td>
                                        <td align="center">{if $item.p_scored>0}{$item.p_scored}{/if}-{if $item.p_missed>0}{$item.p_missed}{/if}</td>
                                        <td align="center">{if $item.p>0}{$item.p}{else}-{/if}</td>
                                    </tr>
                                {/foreach}
                            </table>
                        {/foreach}
                    {/if}
                    {if $result_table_informer.tables_type == 2}
                        <h4>{$result_table_informer.tables.title}</h4>
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <th>№</th>
                                <th>{$language.Team}</th>
                                <th>{$language.points}</th>
                            </tr>
                            {foreach key=key item=item from=$result_table_informer.tables.data name=team}
                                <tr>
                                    <td align="center">{$smarty.foreach.team.iteration}</td>
                                    <td align="center">{*if $item.t_is_detailed == 'yes'}<a href="{$sitepath}team/{$item.t_id}"><b>{$item.title}</b></a>{else*}<b>{$item.title}</b>{*/if*}</td>
                                    <td align="center">{$item.p}</td>
                                </tr>
                            {/foreach}
                        </table>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
    {literal}
    <script>
        $(document).ready(function(){
            if ($(".tab_nav .ul_sub_menu").length>0) {
                $(".tab_nav .item_selected").on("click", function(){
                    $(this).next(".ul_sub_menu").addClass('active');
                    $('.comp_title .ul_other_menu').removeClass('active');
                    return false;
                });
                $("body").on("click", function(event) {
                    if (!$(event.target).is(".tab_nav .ul_sub_menu") && !$(event.target).is(".tab_nav .item_selected")) {
                        $('.tab_nav .ul_sub_menu').removeClass('active');
                    }
                });
                $(".tab_nav .ul_sub_menu li").on("click", function(){
                    $(".tab_nav .item_selected").text($(this).text());
                    var ch_id = $(this).data("ch_id");
                    if (ch_id>0) {
                        $.ajax({
                            url: "/ajax.php",
                            type: "POST",
                            data: {
                                ch_id: ch_id,
                                action: 'inf_result_table'
                            },
                            dataType: 'html',
                            success: function(response, textStatus, jqXHR){
                                if (response != ''){
                                    $(".informer .results_block_text .table_data").html(response);
                                }
                            }
                        });
                    }
                });
            }
            if ($(".comp_title").length>0) {
                $(document).on("click", ".comp_title .item_selected", function(){
                    console.log('comp_title .ul_other_menu');
                    $(this).next(".ul_other_menu").addClass('active');
                    $('.tab_nav .ul_sub_menu').removeClass('active');
                    return false;
                });
                $("body").on("click", function(event) {
                    if (!$(event.target).is(".comp_title .ul_other_menu") && !$(event.target).is(".comp_title .item_selected")) {
                        $('.comp_title .ul_other_menu').removeClass('active');
                    }
                });
                $(document).on("click", ".comp_title .ul_other_menu li", function(){
                    $(".comp_title .item_selected").text($(this).text());
                    var ch_id = $(this).data("ch_id");
                    if (ch_id>0) {
                        $.ajax({
                            url: "/ajax.php",
                            type: "POST",
                            data: {
                                ch_id: ch_id,
                                action: 'inf_result_table'
                            },
                            dataType: 'html',
                            success: function(response, textStatus, jqXHR){
                                if (response != ''){
                                    $(".informer .results_block_text .table_data").html(response);
                                }
                            }
                        });
                    }
                });
            }
        });
    </script>
    {/literal}

{/if}
{/strip}