<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_files.php');
$files = new files;

// картинки начало ///////////////////////////////////////////////////////////////////
if($_POST['add_new_image']){
    if ($files->saveImage()) $smarty->assign("ok_message", 'Картинка добавлена');
    else $smarty->assign("error_message", 'Картинка не добавлена');
}

if($_POST['save_edited_image']){
    if ($files->saveEditedImage()) $smarty->assign("ok_message", 'Картинка обновлена');
    else $smarty->assign("error_message", 'Картинка не обновлена');
}

if($_POST['delete_edited_image']){
    if ($files->deleteFile($_POST['f_id'])) header('Location: ./?show=files');
    else $smarty->assign("error_message", 'Картинка не удалена');
}

if ($_GET['get'] == '' or $_GET['get'] == 'images'){
    //тут еще выборку папок и страниц для отображения...
    $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
    $perpage = 12;
    $smarty->assign("images_list", $files->getImagesList('', $page, $perpage));
    $smarty->assign("images_pages", $files->getImagesPages('', $page, $perpage));
}
if ($_GET['get'] == 'imagesedit' and $_GET['item']>0){
    $smarty->assign("image_item", $files->getImageItem($_GET['item']));
}

// картинки конец ///////////////////////////////////////////////////////////////////

// другие файлы начало //////////////////////////////////////////////////////////////
if($_POST['add_new_file']){
    if ($files->saveFile()) $smarty->assign("ok_message", 'Файл добавлен');
    else $smarty->assign("error_message", 'Файл не добавлен');
}

if($_POST['save_edited_file']){
    if ($files->saveEditedFile()) $smarty->assign("ok_message", 'Файл обновлен');
    else $smarty->assign("error_message", 'Файл не обновлен');
}

if($_POST['delete_edited_file']){
    if ($files->deleteFile($_POST['f_id'])) header('Location: ./?show=files&get=other');
    else $smarty->assign("error_message", 'Файл не удален');
}

if ($_GET['get'] == 'other'){
    //тут еще выборку папок и страниц для отображения...
    $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
    $perpage = 12;
    $smarty->assign("files_list", $files->getFilesList('', $page, $perpage));
    $smarty->assign("files_pages", $files->getFilesPages('', $page, $perpage));
}
if ($_GET['get'] == 'otheredit' and $_GET['item']>0){
    $smarty->assign("file_item", $files->getFileItem($_GET['item']));
}

// другие файлы конец ///////////////////////////////////////////////////////////////
?>