<div class="game_title">
    {if $game_item.owner.photo_main}<img class="game_logo game_logo_left" src="{$sitepath}upload/photos{$game_item.owner.photo_main.ph_folder}{$game_item.owner.photo_main.ph_small}" alt="Фото{if $game_item.owner.photo_main.ph_about != ''}: {$game_item.owner.photo_main.ph_about}{/if}">{else}<img class="game_logo" src="{$sitepath}images/def_logo.jpg" alt="">{/if}
    {$game_item.owner.title}
    {if $game_item.g_is_done == 'yes'}{$game_item.g_owner_points} : {$game_item.g_guest_points}{else}:{/if}
    {$game_item.guest.title}
    {if $game_item.guest.photo_main}<img class="game_logo game_logo_right" src="{$sitepath}upload/photos{$game_item.guest.photo_main.ph_folder}{$game_item.guest.photo_main.ph_small}" alt="Фото{if $game_item.guest.photo_main.ph_about != ''}: {$game_item.guest.photo_main.ph_about}{/if}">{else}<img class="game_logo" src="{$sitepath}images/def_logo.jpg" alt="">{/if}
</div>