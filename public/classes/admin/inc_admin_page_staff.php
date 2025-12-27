<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_page_staff.php');
$page_staff = new page_staff;

if($_POST['add_page_staff']){
    if ($page_staff->createPageStaff()) $smarty->assign("ok_message", 'Список обнавлен');
    else $smarty->assign("error_message", 'Список не обнавлен');
}
if($_POST['edit_page_staff']){
    if ($page_staff->updatePageStaff()) $smarty->assign("ok_message", 'Список обнавлен');
    else $smarty->assign("error_message", 'Список не обнавлен');
}

if($_POST['delete_page_staff']){ // удаление
    if ($page_staff->deletePageStaff($_POST['ps_id'])) $smarty->assign("ok_message", 'Удаление прошло успешно');
    else $smarty->assign("error_message", 'Удаление не произведено');
}

$smarty->assign("page_staff_list", $page_staff->getPageStaffList($_GET['item']));
$smarty->assign("staff_list", $page_staff->getStaffList());
?>