<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;


// видео галереи в информер видео
$informer_video = $informers->getVideoInformer($conf->conf_settings['count_video_informer']);
$smarty->assign("informer_video", $informer_video);
