<body class="body_small">
<div class="content">
    <div class="content_block">
        <div class="left_block">
            <div class="gray_caption">
                {include file="social.tpl"}
                {if $competition_item.championship.chg_title}{$competition_item.championship.chg_title}.{/if}
                {*{$competition_item.championship.title}.*}
                {if $competition_item.competition.title}{$competition_item.competition.title}.{/if}
                {$game_item.g_date_schedule|date_format:"%d"}
                {assign var="month_name" value=$game_item.g_date_schedule|date_format:"%m"} {$month.$month_name}
                {$game_item.g_date_schedule|date_format:"%Y"}.
                {if $game_item.g_is_schedule_time == 'yes'} в {$game_item.g_date_schedule|date_format:"%H:%M"}{/if}
                {if $game_item.g_is_stadium == 'yes'}<b>{$game_item.country}. {$game_item.city}. {$game_item.stadium}</b>{/if}
            </div>
            {*include file="game/game_nav.tpl"*}
            {if $game_item}
                <div class="comp_item">
                    <div id="cont_1">
                        {include file="game/game_title.tpl"}
                        {include file="game/game_table.tpl"}
                    </div>
                    {include file="game/game_photo.tpl"}
                </div>
            {/if}
        </div>
    </div>
</div>
</body>