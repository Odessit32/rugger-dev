<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
if (USER_IS_ADMIN == 'yes' OR USER_IS_PUBLISHER == 'yes'){
    include_once('../classes/admin/admin_admin.php');
    $admin = new admin;
    if (!isset($_GET['get'])) $smarty->assign("admins_list", $admin->getAdminsList());
    else {
        if ($_GET['get']=='add'){
            if($_POST['add_new_admin']){
                if ($_POST['passwd'] == $_POST['passwd_conf']){
                    if ($admin->createAdmin()) $smarty->assign("ok_message", 'Администратор добавлен');
                    else $smarty->assign("error_message", 'Администратор не добавлен');
                } else $smarty->assign("error_message", 'Пароли не совпадают');
            }
        }
        if (isset($_GET['item']) and $_GET['get']=='edit'){
            if ($_GET['item']>0){
                $temp_item = $admin->getAdminItem($_GET['item']);
                if ($temp_item['a_admin_status'] == 'no' or ($temp_item['a_admin_status'] == 'yes' and USER_IS_ADMIN == 'yes')){
                    if($_POST['save_profile_changes']){
                        if ($admin->updateAdmin()) $smarty->assign("ok_message", 'Профиль пользователя обновлен');
                        else $smarty->assign("error_message", 'Профиль пользователя не обновлен');
                    }
                    if($_POST['save_rights_changes']){
                        if ($admin->updateRightsAdmin()) $smarty->assign("ok_message", 'Права пользователя обновлены');
                        else $smarty->assign("error_message", 'Права пользователя не обновлены');
                    }
                    if($_POST['save_password_changes']){
                        if ($_POST['new_passwd'] == $_POST['new_passwd_conf']){
                            if ($admin->updatePassAdmin()) $smarty->assign("ok_message", 'Пароль обновлен');
                            else $smarty->assign("error_message", 'Пароль обновить не удалось');
                        } else $smarty->assign("error_message", 'Пароли не совпадают');
                    }
                    $smarty->assign("admin_item", $admin->getAdminItem($_GET['item']));
                }
            }
        }
    }
} else {
    $smarty->assign("error_message", 'У вас нет прав для просмотра этой страницы');
}
?>