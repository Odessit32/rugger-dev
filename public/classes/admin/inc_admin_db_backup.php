<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_db_backup.php');
$db_backup = new db_backup;

$smarty->assign("backup_list", $db_backup->getBackupList());

if($_POST['add_new_backup']){
    if ($db_backup->createBackup()) $smarty->assign("ok_message", 'Новый backup создан');
    else $smarty->assign("error_message", 'Новый backup не создан');
}

?>