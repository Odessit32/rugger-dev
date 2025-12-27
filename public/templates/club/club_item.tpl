<div id="partner_more">
    <div class="left_rubrika">{$club_item.title}</div>
    {include file="social.tpl"}
    <div class="breadcrumbs">
        <a href="{$sitepath}">{$conf_vars.main}</a> <b>/</b>
        {if $pages}
            {foreach key=key item=item from=$pages name="b_m"}
                <a href="{$sitepath}{$item.page_path}{$item.p_adress}">{$item.title}</a> <b>/</b>
            {/foreach}
        {/if}
        {$club_item.title}
    </div>
    <div id="m_poonkt_1"><a href="javascript:void(0)" onclick="javascript:showBlock('1', '2')">История</a></div>
    <div class="m_blank"></div>
    <div id="m_poonkt_2"><a href="javascript:void(0)" onclick="javascript:showBlock('2', '2')">Руководство</a></div>
    <div class="m_blank"></div>
    {*
    <div id="m_poonkt_3"><a href="javascript:void(0)" onclick="javascript:showBlock('3', '3')">Команды</a></div>
    <div class="m_blank"></div>
    *}
    <div class="m_poonkt_b"></div>
    <div class="m_blank"></div>
    <div class="m_poonkt_b"></div>
    <div class="cont" id="cont_1">
        <div id="more_text">
            <h3>{$club_item.title}</h3>
            {if $club_item.photo_main}<div class="main_img"><img src="{$sitepath}upload/photos{$club_item.photo_main.ph_big}" alt="" border="0" loading="lazy" /></div>{/if}
            {$club_item.text}
        </div>
    </div>
    <div class="cont" id="cont_2" style="display: none">
        <div id="more_text">
            <h3>Руководство:</h3>
            {if $club_item.staff}
                {foreach key=key item=item from=$club_item.staff name=staff}
                    {if $item.app_type == 'head'}
                        <div class="staff_img">
                            {if $item.photo_main}{if $item.text !== ''}<a href="{$sitepath}player/{$item.st_id}" title="{$item.app_title}: {$item.name} {$item.surname} {$item.family}">{/if}<img src="{$sitepath}upload/photos{$item.photo_main.ph_med}" alt="" border="0" loading="lazy" />{if $item.text !== ''}</a>{/if}{/if}
                            <div class="staff_text">
                                <h5>{if $item.text == ''}{$item.name} {$item.surname} {$item.family}{else}<a href="{$sitepath}player/{$item.st_id}" title="{$item.app_title}: {$item.name} {$item.surname} {$item.family}">{$item.name} {$item.surname} {$item.family}</a>{/if}</h5>
                                <b>{$item.app_title}</b>
                            </div>
                        </div>
                    {/if}
                {/foreach}
            {/if}
            {*
                <h3>Другой персонал:</h3>
                {if $club_item.staff}
                {foreach key=key item=item from=$club_item.staff name=staff}
                {if $item.app_type == 'rest'}
                <div class="staff_img">
                    {if $item.photo_main}<a href="{$sitepath}player/{$item.st_id}" title="{$item.app_title}: {$item.name} {$item.surname} {$item.family}"><img src="{$sitepath}upload/photos{$item.photo_main.ph_small}" alt="" border="0" loading="lazy" /></a>{/if}
                    <div class="staff_text">
                        <h5><a href="{$sitepath}player/{$item.st_id}" title="{$item.app_title}: {$item.name} {$item.surname} {$item.family}">{$item.name} {$item.surname} {$item.family}</a></h5>
                        <b>{$item.app_title}</b>
                        {$item.description}
                    </div>
                </div>
                {/if}
                {/foreach}
                {/if}
            *}
        </div>
    </div>
    <div class="cont" id="cont_3" style="display: none">
        <div id="more_text">
            <h3>Команды:</h3>
            {if $club_item.teams}
                {foreach key=key item=item from=$club_item.teams name=teams}
                    <div class="staff_img">
                        {if $item.photo_main}<a href="{$sitepath}team/{$item.t_id}" title="{$item.title}"><img src="{$sitepath}upload/photos{$item.photo_main.ph_small}" alt="" border="0" loading="lazy" /></a>{/if}
                        <div class="staff_text">
                            <h5><a href="{$sitepath}team/{$item.t_id}" title="{$item.title}">{$item.title}</a></h5>
                            {$item.description}
                        </div>
                    </div>
                {/foreach}
            {/if}
        </div>
    </div>

    <div class="poonkt_active">{$club_item.title}</div>
    <div class="blank"></div>
    <div class="poonkt">{if $club_item.video_gallery}<a href="{$sitepath}videos/gal{$club_item.video_gallery.vg_id}">Видео</a>{else}Видео{/if}</div>
    <div class="blank"></div>
    <div class="poonkt">{if $club_item.photo_gallery}<a href="{$sitepath}photos/gal{$club_item.photo_gallery.phg_id}">Фото</a>{else}Фото{/if}</div>
    <div class="blank"></div>
    <div class="poonkt_b">{*<a href="#">RSS</a>*}</div>

</div>