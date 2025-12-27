<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;


// Get Anons for informer
$announce_informer = $informers->getAnnounceInformer();
$smarty->assign("announce_informer", $announce_informer);
