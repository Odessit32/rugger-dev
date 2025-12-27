<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
if($_POST['save_profile_changes']){
    $info = array(
        "a_name"=>$_POST['a_name'],
        "a_f_name"=>$_POST['a_f_name'],
        "a_o_name"=>$_POST['a_o_name'],
        "a_about"=>$_POST['a_about'],
        "a_email"=>$_POST['a_email']
    );
    $login->setLoginInfo($info);
    $smarty->assign("ok_message", 'Профиль пользователя обновлен');
    $login_info = $login->getLoginSession();
}
if($_POST['save_password_changes']){
    if (md5($_POST['old_passwd']) == $login_info['a_passwd']){
        if ($_POST['new_passwd'] == $_POST['new_passwd_conf']){
            $info = array(
                "a_passwd"=>md5($_POST['new_passwd'])
            );
            $login->setLoginInfo($info);
            $smarty->assign("ok_message", 'Пароль обновлен');
            $login_info = $login->getLoginSession();
        } else $smarty->assign("error_message", 'Пароли не совпадают');
    } else $smarty->assign("error_message", 'Старый пароль введен не верно');
}
$smarty->assign("login_profile",$login_info);
?>