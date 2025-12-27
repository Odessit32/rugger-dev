<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;

// Tables for informer
$result_informer = $informers->getResultInformer(false, 3);
$smarty->assign("result_table_informer", $result_informer);
