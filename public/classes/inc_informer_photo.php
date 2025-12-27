<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;


// photos for photo-informer
$informer_photo = $informers->getPhotoInformer();
$smarty->assign("informer_photo", $informer_photo);
