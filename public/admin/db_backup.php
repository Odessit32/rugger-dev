<?php
include_once('../classes/config.php');
include_once('../classes/DB.php');
include_once('../classes/admin/admin_db_backup.php');
$db_backup = new db_backup;

$db_backup->dir_backup = './bd_backup';

if ($db_backup->createBackup()) echo 'OK';
else echo ":(";

?>
