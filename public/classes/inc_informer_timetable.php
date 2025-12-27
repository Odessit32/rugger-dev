<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;

// Result for informer
$result_informer = $informers->getResultInformer(false, 1);
$smarty->assign("result_informer", $result_informer);
