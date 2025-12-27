<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('../classes/admin/admin_page.php');
$page = new page;
if(!empty($_POST['add_new_page'])){
    if ($page->createPage()) $smarty->assign("ok_message", 'Страница добавлена');
    else $smarty->assign("error_message", 'Страница не добавлена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='edit' && $_GET['item']>0){

    // PAGE BANNER INFORMER ///////////////////////////////////////////////////////////////////////////////////////////////////////

    if(!empty($_POST['add_page_informer'])){ // прикрепление информера к странице
        if ($page->addPageBannerInformer()) $smarty->assign("ok_message", 'Информер прикреплен');
        else $smarty->assign("error_message", 'Информер не прикреплен');
    }

    if(!empty($_POST['add_page_banner'])){ // прикрепление баннера к странице
        if ($page->addPageBannerInformer()) $smarty->assign("ok_message", 'Баннер прикреплен');
        else $smarty->assign("error_message", 'Баннер не прикреплен');
    }

    if(!empty($_POST['delete_pbi'])){ // удаление баннера / информера со страницы
        if ($page->deletePageBannerInformer($_POST['pbi_id'])) $smarty->assign("ok_message", 'Удаление прошло успешно');
        else $smarty->assign("error_message", 'Удалить не удалось');
    }

    if(!empty($_POST['off_pbi'])){ // выключение баннера / информера со страницы
        if ($page->offPageBannerInformer($_POST['pbi_id'])) $smarty->assign("ok_message", 'Выключение прошло успешно');
        else $smarty->assign("error_message", 'Выключить не удалось');
    }

    if(!empty($_POST['on_pbi'])){ // включение баннера / информера со страницы
        if ($page->onPageBannerInformer($_POST['pbi_id'])) $smarty->assign("ok_message", 'Включение прошло успешно');
        else $smarty->assign("error_message", 'Включить не удалось');
    }

    if(!empty($_POST['save_pbi'])){ // сохранение изменений баннера / информера на странице
        if ($page->savePageBannerInformer($_POST['pbi_id'])) $smarty->assign("ok_message", 'Изменения сохранены успешно');
        else $smarty->assign("error_message", 'Изменения сохранить не удалось');
    }

    $smarty->assign("pbi", $page->getBanInfList($_GET['item']));
    $smarty->assign("page_ban_inf_list", $page->getPageBanInfList($_GET['item']));

    // PAGE BANNER INFORMER ///////////////////////////////////////////////////////////////////////////////////////////////////////

    // PVS ////////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////////////////////////////
    include_once('../classes/admin/admin_pvs.php');
    $pvs = new pvs;

    if(!empty($_POST['add_pvs'])){
        if ($pvs->addPVS($_POST['p_id'], $_POST['pvs_code'])) $smarty->assign("ok_message", 'Страница обновлена: добавлено прикрепленное видео');
        else $smarty->assign("error_message", 'Страница не обновлена: не добавлено прикрепленное видео');
    }

    if(!empty($_POST['save_pvs'])){
        if ($pvs->savePVS($_POST['pvs_id'], $_POST['pvs_code'])) $smarty->assign("ok_message", 'Страница обновлена: обновлено прикрепленное видео');
        else $smarty->assign("error_message", 'Страница не обновлена: не обновлено прикрепленное видео');
    }

    if(!empty($_POST['delete_pvs'])){
        if ($pvs->deletePVS($_POST['pvs_id'])) $smarty->assign("ok_message", 'Страница обновлена: удалено прикрепленное видео');
        else $smarty->assign("error_message", 'Страница не обновлена: не удалено прикрепленное видео');
    }

    $smarty->assign("pvs_list", $pvs->getPVSList($_GET['item']));

    // PVS ////////// КОНЕЦ ///////////////////////////////////////////////////////////////////////////////////////////////////////

    // PHOTO GALLERY ////////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////////////////////////////
    if (!empty($_GET['item'])){
        if(!empty($_POST['save_phg_page'])){
            if ($page->savePhPage()) $smarty->assign("ok_message", 'Страница обновлена: прикреплена новая фото галерея');
            else $smarty->assign("error_message", 'Страница не обновлена: не прикреплена новая фото галерея');
        }
        $ph_page_item = $page->getPhPageItem($_GET['item']);
        $smarty->assign("ph_page_item", $ph_page_item);

        $smarty->assign("photo_gallery_list", $page->getPhotoGalList($ph_page_item['pe_item_id']));
    }
    // PHOTO GALLERY ////////// КОНЕЦ ///////////////////////////////////////////////////////////////////////////////////////////////////////

    if(!empty($_POST['delete_page'])){
        if ($page->deletePage($_POST['p_id'])) header('Location: ./?show=pages');
        else $smarty->assign("error_message", 'Страница не удалена');
    }

    if(!empty($_POST['save_page_changes'])){
        if ($page->updatePage()) $smarty->assign("ok_message", 'Страница обновлена');
        else $smarty->assign("error_message", 'Страница не обновлена');
    }

    if(!empty($_POST['save_page_title_files'])){ // Заголовок к файлам на странице
        if ($page->savePageTitleFiles($_POST['p_id'], $_POST['p_title_files'])) $smarty->assign("ok_message", 'Заголовок для файлов обновлен');
        else $smarty->assign("error_message", 'Заголовок для файлов не обновлен');
    }

    if(!empty($_POST['save_page_module'])){ // Модуль к странице
        if ($page->savePageModule($_POST['p_id'], $_POST['p_mod_id'])) $smarty->assign("ok_message", 'Модуль к странице обновлен');
        else $smarty->assign("error_message", 'Модуль к странице не обновлен');
    }
    $smarty->assign("modules_list", $page->getPageModuleList()); // Список модулей

    if(!empty($_POST['save_page_banner_count_center'])){ // Количество баннеров на странице
        if ($page->savePageBannerCountC($_POST['p_id'], $_POST['p_c_banners_center'])) $smarty->assign("ok_message", 'Количество баннеров на странице обновлено');
        else $smarty->assign("error_message", 'Количество баннеров на странице не обновлено');
    }

    if(!empty($_POST['save_page_banner_count_right'])){ // Количество баннеров на странице
        if ($page->savePageBannerCountR($_POST['p_id'], $_POST['p_c_banners_right'])) $smarty->assign("ok_message", 'Количество баннеров на странице обновлено');
        else $smarty->assign("error_message", 'Количество баннеров на странице не обновлено');
    }

    if(!empty($_POST['save_page_banner_count_right'])){ // Количество баннеров на странице
        if ($page->savePageBannerCountL($_POST['p_id'], $_POST['p_c_banners_left'])) $smarty->assign("ok_message", 'Количество баннеров на странице обновлено');
        else $smarty->assign("error_message", 'Количество баннеров на странице не обновлено');
    }

    if(!empty($_POST['save_page_is'])){
        if ($page->savePageIs()) $smarty->assign("ok_message", 'Настройки отображения обновлены');
        else $smarty->assign("error_message", 'Настройки отображения не обновлены');
    }
    if(!empty($_POST['atach_file'])){
        if ($page->atachFile($_POST['page_id'], $_POST['file_id'], $_POST['lang'])) $smarty->assign("ok_message", 'Файл присоединен');
        else $smarty->assign("error_message", 'Файл не присоединен');
    }
    if(!empty($_POST['delete_atach_file'])){
        if ($page->atachDeleteFile($_POST['at_id'])) $smarty->assign("ok_message", 'Файл отсоединен');
        else $smarty->assign("error_message", 'Файл не отсоединен');
    }
    $smarty->assign("attached_list", $page->getPageAttachList($_GET['item']));

    $smarty->assign("files_list", $page->getPageFileList($folder="", $_GET['item']));

    $page_item = $page->getPageItem($_GET['item']);
    $smarty->assign("page_item", $page_item);
    if ($page_item['p_mod_id']>0 and $page_item['mod']['mod_a_class'] != '') include_once('../classes/admin/'.$page_item['mod']['mod_a_class']);

    // meta SEO functions start
    $meta_seo_item = $page_item['p_id'];
    $meta_seo_item_type = 'page';
    include_once('../classes/admin/inc_admin_meta_seo.php');
    // meta SEO functions finish
}
if(!empty($_GET['item']) && !empty($_GET['do']) && $_GET['do'] == 'up' && $_GET['item']>0){
    $temp_item = $page->getPageItem($_GET['item']);
    if ($page->doPageUp($temp_item)) $smarty->assign("ok_message", 'Страница отсортирована выше');
    else $smarty->assign("error_message", 'Страница не отсортирована выше');
}
if(!empty($_GET['item']) && !empty($_GET['do']) && $_GET['do'] == 'down' && $_GET['item']>0){
    $temp_item = $page->getPageItem($_GET['item']);
    if ($page->doPageDown($temp_item)) $smarty->assign("ok_message", 'Страница отсортирована ниже');
    else $smarty->assign("error_message", 'Страница не отсортирована ниже');
}

$smarty->assign("pages_list", $page->getPagesList());

if (!empty($_GET['item']) && $_GET['item']>0) $smarty->assign("pages_parent", $page->getPagesList());
else $smarty->assign("pages_parent", $page->getPagesList());
?>