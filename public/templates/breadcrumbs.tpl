{if !empty($page_item)}
    <div class="breadcrumbs_block">
        <div class="breadcrumbs" class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{$sitepath}" itemprop="item">
                    <span itemprop="name">{$conf_vars.main}</span>
                </a>
                {assign var="meta_position" value=1}
                <meta itemprop="position" content="{$meta_position}" />
            </span> <b>/</b>
            {if $page_item.p_mod_id == 31}{* competitions *}
                {if $pages}
                    {foreach key=key item=item from=$pages name="b_m"}
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="{$sitepath}{$item.page_path}{$item.p_adress}"><span itemprop="name">{$item.title}</span></a>
                            {assign var="meta_position" value=$meta_position+1}
                            <meta itemprop="position" content="{$meta_position}" />
                        </span>
                        <b>/</b>
                    {/foreach}
                {/if}
                {if $championship_item}
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}"><span itemprop="name">{$page_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                    <b>/</b>
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}"><span itemprop="name">{$local_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                    <b>/</b>
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$group_item.address}"><span itemprop="name">{$group_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                    <b>/</b>
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$local_item.address}/{$group_item.address}/{$championship_item.address}"><span itemprop="name">{$championship_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                {else}
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <span itemprop="name">{$page_item.title}</span>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                {/if}
            {elseif $page_item.p_mod_id == 37}{* s_competitions *}
                {if $pages}
                    {foreach key=key item=item from=$pages name="b_m"}
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="{$sitepath}{$item.page_path}{$item.p_adress}"><span itemprop="name">{$item.title}</span></a>
                            {assign var="meta_position" value=$meta_position+1}
                            <meta itemprop="position" content="{$meta_position}" />
                        </span>
                        <b>/</b>
                    {/foreach}
                {/if}
                {if $championship_item}
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemprop="item" href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}"><span itemprop="name">{$page_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                    <b>/</b>
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemprop="item" href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}"><span itemprop="name">{$group_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                    <b>/</b>
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemprop="item" href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$group_item.address}/{$championship_item.address}"><span itemprop="name">{$championship_item.title}</span></a>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                {else}
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <span itemprop="name">{$page_item.title}</span>
                        {assign var="meta_position" value=$meta_position+1}
                        <meta itemprop="position" content="{$meta_position}" />
                    </span>
                {/if}
            {elseif $page_item.p_mod_id == 33}{* game *}
                {if $pages}
                    {foreach key=key item=item from=$pages name="b_m"}
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="{$sitepath}{$item.page_path}{$item.p_adress}"><span itemprop="name">{$item.title}</span></a>
                            {assign var="meta_position" value=$meta_position+1}
                            <meta itemprop="position" content="{$meta_position}" />
                        </span>
                        <b>/</b>
                    {/foreach}
                {/if}
                <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        {if $game_item && !empty($game_item.owner) && !empty($game_item.guest)}
                            <h1 class="breadcrumbs_h1">
                                {if !empty($game_meta.h1)}
                                    {$game_meta.h1}
                                {else}
                                    {if !empty($competition_item.championship.chg_title)}{$competition_item.championship.chg_title}, {/if}
                                    {if !empty($competition_item.championship.title)}{$competition_item.championship.title},{/if}
                                    {if !empty($competition_item.competition.title) && $competition_item.competition.title != $competition_item.championship.chg_title}{$competition_item.competition.title}, {/if}
                                    «{$game_item.owner.title}» - «{$game_item.guest.title}».
                                {/if}
                            </h1>
                        {else}
                            {$page_item.title}
                        {/if}
                    </span>
                    {assign var="meta_position" value=$meta_position+1}
                    <meta itemprop="position" content="{$meta_position}" />
                </span>
            {elseif $page_item.p_mod_id == 30}{* team *}
                {if $pages}
                    {foreach key=key item=item from=$pages name="b_m"}
                        <span itemprop="item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a href="{$sitepath}{$item.page_path}{$item.p_adress}"><span itemprop="name">{$item.title}</span></a>
                            {assign var="meta_position" value=$meta_position+1}
                            <meta itemprop="position" content="{$meta_position}" />
                        </span>
                        <b>/</b>
                    {/foreach}
                {/if}
                <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        {if $team_item}
                            {$team_item.title}
                        {else}{$page_item.title}{/if}
                    </span>
                    {assign var="meta_position" value=$meta_position+1}
                    <meta itemprop="position" content="{$meta_position}" />
                </span>
            {else}
                {if $pages}
                    {foreach key=key item=item from=$pages name="b_m"}
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="{$sitepath}{$item.page_path}{$item.p_adress}"><span itemprop="name">{$item.title}</span></a>
                            {assign var="meta_position" value=$meta_position+1}
                            <meta itemprop="position" content="{$meta_position}" />
                        </span>
                        <b>/</b>
                    {/foreach}
                {/if}
                <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">{$page_item.title}</span>
                    {assign var="meta_position" value=$meta_position+1}
                    <meta itemprop="position" content="{$meta_position}" />
                </span>
            {/if}
        </div>
    </div>
{/if}
