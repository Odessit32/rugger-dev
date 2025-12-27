{strip}
<div class="footer">
    <div class="content_block">
        <ul class="social_list" aria-label="Социальные сети">
            {if isset($conf_vars.share_rss) && $conf_vars.share_rss != ''}<li><a href="{$conf_vars.share_rss}" target="_blank" aria-label="RSS лента" title="RSS"><i class="fas fa-rss" aria-hidden="true"></i></a></li>{/if}
            {if isset($conf_vars.share_tg) && $conf_vars.share_tg != ''}<li><a href="{$conf_vars.share_tg}" target="_blank" aria-label="Telegram канал" title="Telegram"><i class="fab fa-telegram-plane" aria-hidden="true"></i></a></li>{/if}
            {if isset($conf_vars.share_tw) && $conf_vars.share_tw != ''}<li><a href="{$conf_vars.share_tw}" target="_blank" aria-label="Twitter" title="Twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>{/if}
            {if isset($conf_vars.share_fb) && $conf_vars.share_fb != ''}<li><a href="{$conf_vars.share_fb}" target="_blank" aria-label="Facebook" title="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>{/if}
            {if isset($conf_vars.share_vk) && $conf_vars.share_vk != ''}<li><a href="{$conf_vars.share_vk}" target="_blank" aria-label="ВКонтакте" title="ВКонтакте"><i class="fab fa-vk" aria-hidden="true"></i></a></li>{/if}
        </ul>

        <div class="logo">
            <a href=""><img src="{$imagepath}images/logo_footer.png" loading="lazy" alt="Rugger.info" /></a>
        </div>
        <div class="footer_right">{$conf_vars.footer_right}</div>
        <div class="copyright">
            {$conf_vars.copyright}
        </div>
        {if !empty($f_menu_array) && $f_menu_array.menu.0}
        <ul class="footer_menu">
            {foreach key=key item=item from=$f_menu_array.menu.0 name="up_m"}
                <li>{if $item.p_id == $menu_array.active.0.p_id}<b>{$item.title}</b>{else}<a href="{$sitepath}{$item.url_pre}{$item.url}{$item.url_post}" title="{$item.title}">{$item.title}</a>{/if}</li>
            {/foreach}
        </ul>
        {/if}
        <div class="footer_center">{$conf_vars.footer_center}</div>
    </div>
</div>
<div class="to_the_top"></div>
{if $ENV =='PRODUCTION'}
<script defer src="{$imagepath}js/scripts.min.js"></script>
{else}
<script defer src="{$imagepath}jscripts/jquery-ui.min.js"></script>
<script defer src="{$imagepath}jscripts/datepicker-ru.js"></script>
<script defer src="{$imagepath}flexslider/jquery.flexslider-min.js"></script>
<script defer src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
<script defer src="{$imagepath}jscripts/core.js"></script>
<script defer src="{$imagepath}jscripts/md5-min.js"></script>
<script defer src="{$imagepath}jscripts/scripts.js?v=0.01.06"></script>
<script defer src="{$imagepath}jscripts/index.js?v=0.1.3"></script>
<script defer src="{$imagepath}jscripts/competitionStaff.js"></script>
{/if}
{* Pull to refresh - загружаем после основного контента *}
<script defer src="/pulltorefresh.js"></script>
<script>
window.addEventListener('load', function() {ldelim}
    if (typeof PullToRefresh !== 'undefined') {ldelim}
        PullToRefresh.init({ldelim}
            onRefresh: function() {ldelim} location.reload(); {rdelim},
            instructionsPullToRefresh: "Потяните вниз для обновления",
            instructionsReleaseToRefresh: "Отпустите для обновления",
            instructionsRefreshing: "Обновление"
        {rdelim});
    {rdelim}
{rdelim});
</script>
    
{literal}
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = 'https://vk.com/rtrg?p=VK-RTRG-113169-2sl8m';</script>


{/literal}
{*
<script src="{$sitepath}jscripts/DD_belatedPNG.js" type="text/javascript"></script>
<script>
    DD_belatedPNG.fix("#logo");
</script>
<script type="text/javascript" src="{$sitepath}jscripts/tip_script.js"></script>
<script type="text/javascript" src="{$sitepath}jscripts/ajax_func.js"></script>
<script type="text/javascript" src="{$sitepath}jscripts/popup.js"></script>
*}
{if !empty($notifications_list)}
    <div class="notification_list">
    {foreach key=key item=item from=$notifications_list name="notifications"}
        <div class="notification_item" data-time_show="{$item.time_show}" data-time_hide="{$item.time_hide}">
            {if !empty($item.url)}<a href="{$item.url}" class="notification_content">{else}<div class="notification_content">{/if}
                {if !empty($item.title)}<div class="title">{$item.title}</div>{/if}
                {if !empty($item.description)}<div class="description">{$item.description}</div>{/if}
            {if !empty($item.url)}</a>{else}</div>{/if}
            <a href="#clase" class="close"></a>
        </div>
    {/foreach}
    </div>
{/if}
{/strip}