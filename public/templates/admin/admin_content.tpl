{if $smarty.get.show eq 'profile'}
    {include file='admin_profile.tpl'}
{elseif $smarty.get.show eq 'pages'}
    {include file='pages/admin_pages.tpl'}
{elseif $smarty.get.show eq 'admins' and ($admin_status == 'yes' or $publisher_status == 'yes')}
    {include file='admin_admin.tpl'}
{elseif $smarty.get.show eq 'files'}
    {include file='admin_files.tpl'}
{elseif $smarty.get.show eq 'main' and ($admin_status == 'yes' or $publisher_status == 'yes')}
    {include file='main/admin_main.tpl'}
{elseif $smarty.get.show eq 'news'}
    {include file='news/admin_news.tpl'}
{elseif $smarty.get.show eq 'photos'}
    {include file='photos/admin_photos.tpl'}
{elseif $smarty.get.show eq 'videos'}
    {include file='videos/admin_videos.tpl'}
{elseif $smarty.get.show eq 'banners'}
    {include file='admin_banners.tpl'}
{elseif $smarty.get.show eq 'informers'}
    {include file='admin_informers.tpl'}
{elseif $smarty.get.show eq 'staff'}
    {include file='staff/admin_staff.tpl'}
{elseif $smarty.get.show eq 'feedback'}
    {include file='feedback/admin_feedback.tpl'}
{elseif $smarty.get.show eq 'db_backup'}
    {include file='admin_db_backup.tpl'}
{elseif $smarty.get.show eq 'votes'}
    {include file='votes/admin_votes.tpl'}
{elseif $smarty.get.show eq 'team'}
    {include file='team/admin_team.tpl'}
{elseif $smarty.get.show eq 'club'}
    {include file='club/admin_club.tpl'}
{elseif $smarty.get.show eq 'championship'}
    {include file='championship/admin_championship.tpl'}
{elseif $smarty.get.show eq 'competitions'}
    {include file='competitions/admin_competitions.tpl'}
{elseif $smarty.get.show eq 'games'}
    {include file='games/admin_games.tpl'}
{elseif $smarty.get.show eq 'announce'}
    {include file='announce/admin_announce.tpl'}
{elseif $smarty.get.show eq 'live'}
    {include file='live/admin_live.tpl'}
{elseif $smarty.get.show eq 'redirects'}
    {include file='redirects/admin_redirects.tpl'}
{elseif $smarty.get.show eq 'blog'}
    {include file='blog/admin_blog.tpl'}
{elseif $smarty.get.show eq 'notifications'}
    {include file='notifications/admin_notifications.tpl'}
{/if}