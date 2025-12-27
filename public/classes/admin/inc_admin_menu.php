<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
$menu = '';

if (USER_IS_ADMIN == 'yes'){
    $menu .= '<a href="?show=main"'; if ($_GET['show'] == 'main') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Главная</a>';
    $menu .= '<a href="?show=pages"'; if ($_GET['show'] == 'pages') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Страницы</a>';
    $menu .= '<a href="?show=news"'; if ($_GET['show'] == 'news') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Новости</a>';
//    $menu .= '<a href="?show=blog"'; if ($_GET['show'] == 'blog') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Блог</a>';
//    $menu .= '<a href="?show=live"'; if ($_GET['show'] == 'live') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Live</a>';
    $menu .= '<a href="?show=staff"'; if ($_GET['show'] == 'staff') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Люди</a>';
    $menu .= '<a href="?show=team"'; if ($_GET['show'] == 'team') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Команды</a>';
//    $menu .= '<a href="?show=club"'; if ($_GET['show'] == 'club') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Клубы</a>';
    $menu .= '<a href="?show=championship"'; if ($_GET['show'] == 'championship') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Чемпионаты</a>';
    $menu .= '<a href="?show=competitions"'; if ($_GET['show'] == 'competitions') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Соревнования</a>';
    $menu .= '<a href="?show=games"'; if ($_GET['show'] == 'games') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Игры</a>';
//    $menu .= '<a href="?show=announce"'; if ($_GET['show'] == 'announce') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Анонсы</a>';
    $menu .= '<a href="?show=files"'; if ($_GET['show'] == 'files') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Файлы</a>';
    $menu .= '<a href="?show=photos"'; if ($_GET['show'] == 'photos') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Фотогалерея</a>';
    $menu .= '<a href="?show=videos"'; if ($_GET['show'] == 'videos') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Видеогалерея</a>';
    $menu .= '<a href="?show=banners"'; if ($_GET['show'] == 'banners') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Баннеры</a>';
    $menu .= '<a href="?show=informers"'; if ($_GET['show'] == 'informers') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Информеры</a>';
//    $menu .= '<a href="?show=feedback"'; if ($_GET['show'] == 'feedback') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Обратная связь</a>';
//    $menu .= '<a href="?show=notifications"'; if ($_GET['show'] == 'notifications') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Уведомления</a>';
//    $menu .= '<a href="?show=votes"'; if ($_GET['show'] == 'votes') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Голосования</a>';
    $menu .= '<a href="?show=redirects"'; if ($_GET['show'] == 'redirects') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Переадресация</a>';
    $menu .= '<a href="?show=profile"'; if ($_GET['show'] == 'profile') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Профиль</a>';
    $menu .= '<a href="?show=admins"'; if ($_GET['show'] == 'admins') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Администраторы</a>';
    $menu .= '<a href="?show=db_backup"'; if ($_GET['show'] == 'db_backup') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Backup БД</a>';
}
elseif (USER_IS_PUBLISHER == 'yes'){
    $menu .= '<a href="?show=pages"'; if ($_GET['show'] == 'pages') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Страницы</a>';
    $menu .= '<a href="?show=news"'; if ($_GET['show'] == 'news') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Новости</a>';
//    $menu .= '<a href="?show=blog"'; if ($_GET['show'] == 'blog') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Блог</a>';
//    $menu .= '<a href="?show=live"'; if ($_GET['show'] == 'live') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Live</a>';
    $menu .= '<a href="?show=files"'; if ($_GET['show'] == 'files') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Файлы</a>';
    $menu .= '<a href="?show=photos"'; if ($_GET['show'] == 'photos') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Фотогалерея</a>';
    $menu .= '<a href="?show=videos"'; if ($_GET['show'] == 'videos') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Видеогалерея</a>';
    $menu .= '<a href="?show=banners"'; if ($_GET['show'] == 'banners') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Баннеры</a>';
    $menu .= '<a href="?show=informers"'; if ($_GET['show'] == 'informers') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Информеры</a>';
//    $menu .= '<a href="?show=feedback"'; if ($_GET['show'] == 'feedback') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Обратная связь</a>';
//    $menu .= '<a href="?show=votes"'; if ($_GET['show'] == 'votes') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Голосования</a>';
    $menu .= '<a href="?show=profile"'; if ($_GET['show'] == 'profile') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Профиль</a>';
}
else {
    if ($login->checkAdminMenuItemRights("?show=main")) {$menu .= '<a href="?show=main"'; if ($_GET['show'] == 'main') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Главная</a>';}
    if ($login->checkAdminMenuItemRights("?show=pages")) {$menu .= '<a href="?show=pages"'; if ($_GET['show'] == 'pages') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Страницы</a>';}
    if ($login->checkAdminMenuItemRights("?show=news")) {$menu .= '<a href="?show=news"'; if ($_GET['show'] == 'news') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Новости</a>';}
//    if ($login->checkAdminMenuItemRights("?show=blog")) {$menu .= '<a href="?show=blog"'; if ($_GET['show'] == 'blog') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Блог</a>';}
//    if ($login->checkAdminMenuItemRights("?show=live")) {$menu .= '<a href="?show=live"'; if ($_GET['show'] == 'live') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Live</a>';}
    if ($login->checkAdminMenuItemRights("?show=staff")) {$menu .= '<a href="?show=staff"'; if ($_GET['show'] == 'staff') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Люди</a>';}
    if ($login->checkAdminMenuItemRights("?show=team")) {$menu .= '<a href="?show=team"'; if ($_GET['show'] == 'team') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Команды</a>';}
//    if ($login->checkAdminMenuItemRights("?show=club")) {$menu .= '<a href="?show=club"'; if ($_GET['show'] == 'club') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Клубы</a>';}
    if ($login->checkAdminMenuItemRights("?show=championship")) {$menu .= '<a href="?show=championship"'; if ($_GET['show'] == 'championship') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Чемпионаты</a>';}
    if ($login->checkAdminMenuItemRights("?show=competitions")) {$menu .= '<a href="?show=competitions"'; if ($_GET['show'] == 'competitions') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Соревнования</a>';}
    if ($login->checkAdminMenuItemRights("?show=games")) {$menu .= '<a href="?show=games"'; if ($_GET['show'] == 'games') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Игры</a>';}
//    if ($login->checkAdminMenuItemRights("?show=announce")) {$menu .= '<a href="?show=announce"'; if ($_GET['show'] == 'announce') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Анонсы</a>';}
    if ($login->checkAdminMenuItemRights("?show=files")) {$menu .= '<a href="?show=files"'; if ($_GET['show'] == 'files') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Файлы</a>';}
    if ($login->checkAdminMenuItemRights("?show=photos")) {$menu .= '<a href="?show=photos"'; if ($_GET['show'] == 'photos') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Фотогалерея</a>';}
    if ($login->checkAdminMenuItemRights("?show=videos")) {$menu .= '<a href="?show=videos"'; if ($_GET['show'] == 'videos') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Видеогалерея</a>';}
    if ($login->checkAdminMenuItemRights("?show=banners")) {$menu .= '<a href="?show=banners"'; if ($_GET['show'] == 'banners') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Баннеры</a>';}
    if ($login->checkAdminMenuItemRights("?show=informers")) {$menu .= '<a href="?show=informers"'; if ($_GET['show'] == 'informers') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Информеры</a>';}
//    if ($login->checkAdminMenuItemRights("?show=feedback")) {$menu .= '<a href="?show=feedback"'; if ($_GET['show'] == 'feedback') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Обратная связь</a>';}
//    if ($login->checkAdminMenuItemRights("?show=votes")) {$menu .= '<a href="?show=votes"'; if ($_GET['show'] == 'votes') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Голосования</a>';}
    if ($login->checkAdminMenuItemRights("?show=redirects")) {$menu .= '<a href="?show=redirects"'; if ($_GET['show'] == 'redirects') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Переадресация</a>';}
    if ($login->checkAdminMenuItemRights("?show=profile")) {$menu .= '<a href="?show=profile"'; if ($_GET['show'] == 'profile') $menu .= ' style="color:#fff;  background-color: #3ea015;"'; $menu .= '>Профиль</a>';}
}


$smarty->assign("menu", $menu);
?>