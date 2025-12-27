<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/informers.php');
$informers = new informers;


// фото в информер фото
$informer_votes = $informers->getVotesInformer();
$smarty->assign("informer_votes", $informer_votes);

$vote_code = ($informer_votes) ? $informers->getVoteCode(): '';
$smarty->assign("vote_code", $vote_code);