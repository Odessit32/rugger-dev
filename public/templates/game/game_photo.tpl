<div id="cont_3" style="display: none">
    {if !empty($game_item.photo_main)}<div class="partner_logo"><img src="{$sitepath}upload/photos{$game_item.photo_main.ph_folder}{$game_item.photo_main.ph_main}" alt="Фото{if !empty($game_item.photo_main.ph_about)}: {$game_item.photo_main.ph_about}{/if}" border="0" height="250"></div>{/if}
    {if !empty($game_item.title)}<h3>{$game_item.title}</h3>{/if}
    {if !empty($game_item.text)}{$game_item.text}{/if}
</div>
<div class="b_border"></div>