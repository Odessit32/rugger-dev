<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;

// Result for informer
$result_informer = $informers->getResultInformer();
$smarty->assign("result_informer", $result_informer);

?>