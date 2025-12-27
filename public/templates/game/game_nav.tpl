
<div class="nav_poonkt">
    <div id="m_poonkt_1">{if $game_item.text != ''}<a href="javascript:void(0)" onclick="javascript:showBlock('1', '2')">{/if}{if $game_item.g_is_done == 'yes'}Отчет{else}Анонс{/if}{if $game_item.text != ''}</a>{/if}</div>
    <div class="m_blank"></div>
    <div class="m_poonkt_b"></div>
    <div class="m_blank"></div>
    {if $game_item.text != ''}<div id="m_poonkt_3"><a href="javascript:void(0)" onclick="javascript:showBlock(3, 3)">Описание</a></div>{else}<div class="m_poonkt_b"></div>{/if}
    <div class="m_blank"></div>
    <div class="m_poonkt_b"></div>
</div>