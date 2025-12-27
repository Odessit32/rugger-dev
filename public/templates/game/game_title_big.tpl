<div class="game_title_big">
    <div class="team_logo left"
         style="background-image: url('{$imagepath}{if !empty($game_item.owner.photo_main)}upload/photos{$game_item.owner.photo_main.ph_folder}{$game_item.owner.photo_main.ph_full}{else}images/def_logo.png{/if}')"></div>
    <span class="title_names_score">
        <span class="game-title-team game-title-team-owner">{$game_item.owner.title}</span>
        <span class="game-title-points">
        {if $game_item.g_is_done == 'yes'}
            {if !empty($game_item.g_owner_ft_points) || !empty($game_item.g_guest_ft_points)}
                ({$game_item.g_owner_ft_points})
            {/if}
            {$game_item.g_owner_points} - {$game_item.g_guest_points}
            {if !empty($game_item.g_owner_ft_points) || !empty($game_item.g_guest_ft_points)}
                ({$game_item.g_guest_ft_points})
            {/if}
        {else}:
        {/if}
        </span>
        <span class="game-title-team game-title-team-guest">{$game_item.guest.title}</span>
    </span>
    <div class="team_logo right"
         style="background-image: url('{$imagepath}{if !empty($game_item.guest.photo_main)}upload/photos{$game_item.guest.photo_main.ph_folder}{$game_item.guest.photo_main.ph_full}{else}images/def_logo.png{/if}')"></div>
</div>